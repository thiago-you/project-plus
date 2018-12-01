<?php
namespace app\models;

use Yii;
use app\base\Helper;

/**
 * This is the model class for table "contrato".
 *
 * @property int    $id
 * @property int    $id_cliente
 * @property int    $id_carteira
 * @property string $codigo_cliente
 * @property string $codigo_contrato
 * @property string $num_contrato
 * @property string $num_plano
 * @property string $valor
 * @property string $data_cadastro
 * @property string $data_vencimento
 * @property string $data_negociacao
 * @property int    $tipo
 * @property string $regiao
 * @property string $filial
 * @property string $observacao
 * @property int    $situacao
 *
 * @property Cliente $cliente
 * @property Carteira $carteira
 * @property Acionamento[] $acionamentos
 * @property ContratoParcela[] $contratoParcelas
 * @property Negociacao $negociacao
 * @property ContratoTipo $tipo
 */
class Contrato extends \yii\db\ActiveRecord
{
    // consts para a situacao
    CONST SIT_EM_ANDAMENTO = '1';
    CONST SIT_FECHADO = '2';
    
    /**
     * @var string
     */
    public $atraso;
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'contrato';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_cliente',], 'required'],
            [['id_cliente', 'id_carteira', 'tipo', 'situacao'], 'integer'],
            [['valor'], 'number'],
            [['data_cadastro', 'data_vencimento', 'data_negociacao'], 'safe'],
            [['codigo_cliente', 'codigo_contrato', 'num_contrato', 'num_plano', 'regiao', 'filial'], 'string', 'max' => 50],
            [['observacao'], 'string', 'max' => 250],
            [['id_cliente'], 'exist', 'skipOnError' => true, 'targetClass' => Cliente::className(), 'targetAttribute' => ['id_cliente' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Cód.',
            'id_cliente' => 'Cliente',
            'id_carteira' => 'Carteira',
            'codigo_cliente' => 'Código do Cliente',
            'codigo_contrato' => 'Código do Contrato',
            'num_contrato' => 'N° Contrato',
            'num_plano' => 'N° Plano',
            'valor' => 'Valor',
            'data_cadastro' => 'Data do Contrato',
            'data_vencimento' => 'Data de Expiração',
            'data_negociacao' => 'Data de Negociação',
            'tipo' => 'Tipo',
            'regiao' => 'Região',
            'filial' => 'Filial',
            'observacao' => 'Observação',
            'situacao' => 'Situação',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCliente()
    {
        return $this->hasOne(Cliente::className(), ['id' => 'id_cliente']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCarteira()
    {
        return $this->hasOne(Carteira::className(), ['id' => 'id_carteira']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getAcionamentos()
    {
        return $this->hasMany(Acionamento::className(), ['id_contrato' => 'id'])->orderBy(['id' => SORT_DESC]);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContratoParcelas()
    {
        return $this->hasMany(ContratoParcela::className(), ['id_contrato' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNegociacao()
    {
        return $this->hasOne(Negociacao::className(), ['id_contrato' => 'id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContratoTipo()
    {
        return $this->hasOne(ContratoTipo::className(), ['id' => 'tipo']);
    }
    
    /**
     * @inheritDoc
     * @see \yii\db\BaseActiveRecord::beforeSave()
     */
    public function beforeSave($insert) 
    {
        // seta a data de cadastro
        if (empty($this->data_cadastro)) {
            $this->data_cadastro = date('Y-m-d');
        } else {
            $this->data_cadastro = Helper::dateUnmask($this->data_cadastro, Helper::DATE_DEFAULT);
        }
        
        // formata as datas para salvar
        $this->data_vencimento = Helper::dateUnmask($this->data_vencimento, Helper::DATE_DEFAULT);
        $this->data_negociacao = Helper::dateUnmask($this->data_negociacao, Helper::DATE_DEFAULT);
        
        return parent::beforeSave($insert);        
    }
    
    /**
     * @inheritDoc
     * @see \yii\db\BaseActiveRecord::afterFind()
     */
    public function afterFind()
    {
        // formata a data para ser exibida
        $this->data_cadastro = Helper::dateMask($this->data_cadastro, Helper::DATE_DEFAULT);
        $this->data_vencimento = Helper::dateMask($this->data_vencimento, Helper::DATE_DEFAULT);
        $this->data_negociacao = Helper::dateMask($this->data_negociacao, Helper::DATE_DEFAULT);
    }
    
    /**
     * @inheritDoc
     * @see \yii\db\BaseActiveRecord::beforeDelete()
     */
    public function beforeDelete() 
    {
        // deleta todas as parcelas do contrato
        ContratoParcela::deleteAll(['id_contrato' => $this->id]);
        
        return parent::beforeDelete();
    }
    
    /**
     * Calcula o valor total do contrato
     */
    public function getValorTotal() 
    {
        $total = 0;
        foreach ($this->contratoParcelas as $parcela) {
            // seta o valor total
            $totalTemp = $parcela->valor;
            
            // busca a faixa e calcula os encargos
            if ($faixaCalculo = CarteiraCalculo::findFaixa($this->carteira->id_campanha, $parcela->getAtraso())) {                
                // soma o total da parcela
                $totalTemp += floor(($parcela->valor * ($faixaCalculo->multa / 100)) * 100) / 100;
                $totalTemp += floor(($parcela->valor * (($faixaCalculo->juros / 30 * $parcela->getAtraso()) / 100)) * 100) / 100;
                $totalTemp += floor(($totalTemp * ($faixaCalculo->honorario / 100)) * 100) / 100;
                
                // soma o total do contrato
                $total += $totalTemp;
            }
        }
        
        return $total;
    }
}
