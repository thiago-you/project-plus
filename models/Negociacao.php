<?php

namespace app\models;

use Yii;
use app\base\Helper;

/**
 * This is the model class for table "negociacao".
 *
 * @property int $id
 * @property int $id_contrato
 * @property int $id_credor
 * @property int $id_campanha
 * @property string $data_negociacao
 * @property string $data_cadastro
 * @property string $desconto_encargos
 * @property string $desconto_principal
 * @property string $desconto_honorarios
 * @property string $desconto_total
 * @property string $subtotal
 * @property string $desconto
 * @property string $receita
 * @property string $total
 * @property string $tipo Flag que valida se a negociacao é a vista ou parcelado
 * @property string $observacao
 * @property integer $status
 * 
 * @property Contrato $contrato
 */
class Negociacao extends \yii\db\ActiveRecord
{
    // const para o tipo da negociacao
    CONST A_VISTA = 'V';
    CONST PARCELADO = 'P';
    // status da negociacao
    CONST STATUS_ABERTA = 0;
    CONST STATUS_FECHADA = 1;
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'negociacao';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['data_negociacao', 'id_contrato', 'id_credor', 'id_campanha'], 'required'],
            [['id_contrato', 'id_credor', 'id_campanha', 'status'], 'integer'],
            [[
                'subtotal', 'desconto', 'receita', 'total', 'desconto_encargos',
                'desconto_principal', 'desconto_honorarios', 'desconto_total'           
            ], 'number'],
            [['data_negociacao', 'data_cadastro', 'tipo'], 'safe'],
            [['observacao'], 'string', 'max' => 250],
            [['id_contrato'], 'exist', 'skipOnError' => true, 'targetClass' => Contrato::className(), 'targetAttribute' => ['id_contrato' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_contrato' => 'Id Contrato',
            'id_credor' => 'Id Credor',
            'id_campanha' => 'Id Campanha',
            'data_negociacao' => 'Data Negociacao',
            'data_cadastro' => 'Data Cadastro',
            'subtotal' => 'Subtotal',
            'desconto' => 'Desconto',
            'receita' => 'Receita',
            'total' => 'Total',
            'desconto_encargos' => 'Desconto dos Encargos',
            'desconto_principal' => 'Desconto Principal',
            'desconto_honorarios' => 'Desconto dos Honorários',
            'desconto_total' => 'Desconto Total',
            'tipo' => 'Tipo de Pagamento',
            'observacao' => 'Observação',
            'status' => 'Status da Negociação',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContrato()
    {
        return $this->hasOne(Contrato::className(), ['id' => 'id_contrato']);
    }
    
    /**
     * Calcula os valores da negociacao
     */
    public function calcularValores($contrato = null)
    {
        // busca o contrato
        if (!$contrato) {
            $contrato = Contrato::findOne(['id' => $this->id_contrato]);
        }
        
        // busca as parcelas do contrato
        if (!empty($contrato->contratoParcelas) && is_array($contrato->contratoParcelas)) {
            $this->subtotal = 0;
            $this->receita = 0;
            foreach ($contrato->contratoParcelas as $parcela) {                
                // busca a faixa de calculo
                // e calcula o subtotal e a receita
                if ($faixaCalculo = CredorCalculo::findFaixa($this->id_campanha, $parcela->getAtraso())) {
                    // calcula os valores da parcela
                    $multa = floor(($parcela->valor * ($faixaCalculo->multa / 100)) * 100) / 100;
                    $juros = floor(($parcela->valor * (($faixaCalculo->juros / 30 * $parcela->getAtraso()) / 100)) * 100) / 100;
                    $honorarios = floor((($parcela->valor + $multa + $juros) * ($faixaCalculo->honorario / 100)) * 100) / 100;
                    
                    $this->subtotal += $parcela->valor + $multa + $juros + $honorarios;
                    $this->receita += $honorarios;
                }
            }
            
            // seta o valor total
            $this->total = $this->subtotal;
        }        
    }
    
    /**
     * @inheritDoc
     * @see \yii\db\BaseActiveRecord::beforeSave()
     */
    public function beforeSave($insert) 
    {
        if (empty($this->data_cadastro)) {
            $this->data_cadastro = date('Y-m-d H:i:s');
        }
        
        // formata a data antes de salvar
        $this->data_negociacao = Helper::formatDateToSave($this->data_negociacao, Helper::DATE_DEFAULT);
        
        // altera o status das parcelas
        if ($insert) {
            if (!empty($this->contrato->contratoParcelas) && is_array($this->contrato->contratoParcelas)) {
                foreach ($this->contrato->contratoParcelas as $parcela) {
                    $parcela->status = ContratoParcela::EM_NEGOCIACAO;
                    $parcela->save(false);
                }
            }
            
            // seta o status inicial
            $this->status = Negociacao::STATUS_ABERTA;
        }
        
        // converte os \n da string em html <br/> e remove line breaks extras
        if (!empty($this->observacao)) {
            $this->observacao = trim(preg_replace('/\s+/', ' ', preg_replace('/\n/', '<br/>',  $this->observacao)));
        }
        
        return parent::beforeSave($insert);       
    }
    
    /**
     * @inheritDoc
     * @see \yii\db\BaseActiveRecord::afterFind()
     */
    public function afterFind() 
    {
        // formata a data para exibicao
        $this->data_negociacao = Helper::formatDateToDisplay($this->data_negociacao, Helper::DATE_DEFAULT);
    }
}




