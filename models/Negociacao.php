<?php

namespace app\models;

use Yii;
use app\base\Helper;

/**
 * This is the model class for table "negociacao".
 *
 * @property int $id
 * @property int $id_contrato
 * @property int $id_carteira
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
 * @property string $valor_entrada
 * @property string $taxa_parcelado
 * @property string $tipo Flag que valida se a negociacao é a vista ou parcelado
 * @property string $observacao
 * @property integer $status
 * 
 * @property Contrato $contrato
 * @property NegociacaoParcela $parcelas
 */
class Negociacao extends \yii\db\ActiveRecord
{
    // const para o tipo da negociacao
    CONST A_VISTA = 'V';
    CONST PARCELADO = 'P';
    // status da negociacao
    CONST STATUS_ABERTA = 0;
    CONST STATUS_FECHADA = 1;
    CONST STATUS_FATURADA = 2;
    
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
            [['data_negociacao', 'id_contrato',], 'required'],
            [['id_carteira'], 'required', 'message' => 'A "carteira" do contrato não foi configurada.'],
            [['id_campanha'], 'required', 'message' => 'A "campanha" do contrato/carteira não foi configurada.'],
            [['id_contrato', 'id_carteira', 'id_campanha', 'status'], 'integer'],
            [[
                'subtotal', 'desconto', 'receita', 'total', 'desconto_encargos', 'taxa_parcelado',
                'desconto_principal', 'desconto_honorarios', 'desconto_total', 'valor_entrada'           
            ], 'number'],
            [['data_negociacao', 'data_cadastro'], 'safe'],
            [['tipo'], 'string'],
            [['tipo'], 'in', 'range' => [self::A_VISTA, self::PARCELADO]],
            [['status'], 'in', 'range' => [self::STATUS_ABERTA, self::STATUS_FECHADA, self::STATUS_FATURADA]],
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
            'id_contrato' => 'Contrato',
            'id_carteira' => 'Carteira',
            'id_campanha' => 'Campanha',
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
            'valor_entrada' => 'Valor de Entrada',
            'taxa_parcelado' => 'Taxa',
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
     * @return \yii\db\ActiveQuery
     */
    public function getParcelas()
    {
        return $this->hasMany(NegociacaoParcela::className(), ['id_negociacao' => 'id']);
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
                if ($faixaCalculo = CarteiraCalculo::findFaixa($this->id_campanha, $parcela->getAtraso())) {
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
        $this->data_negociacao = Helper::dateUnmask($this->data_negociacao, Helper::DATE_DEFAULT);
        
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
        $this->data_negociacao = Helper::dateMask($this->data_negociacao, Helper::DATE_DEFAULT);
    }

    /**
     * Retorna a quantidade de parcelas disponíveis para o contrato
     */
    public static function getQuantidadeParcelas() 
    {
        return [
            2 => '2x',
            3 => '3x',
            4 => '4x',
            5 => '5x',
            6 => '6x',
            7 => '7x',
            8 => '8x',
            9 => '9x',
            10 => '10x',
            11 => '11x',
            12 => '12x',
            13 => '13x',
            14 => '14x',
            15 => '15x',
        ];        
    }
}




