<?php
namespace app\models;

use Yii;

/**
 * This is the model class for table "contrato".
 *
 * @property int $id
 * @property int $id_cliente
 * @property int $id_credor
 * @property string $codigo_cliente
 * @property string $codigo_contrato
 * @property string $num_contrato
 * @property string $num_plano
 * @property string $valor
 * @property string $data_cadastro
 * @property string $data_vencimento
 * @property string $data_negociacao
 * @property int $tipo
 * @property string $regiao
 * @property string $filial
 * @property string $observacao
 *
 * @property Cliente $cliente
 * @property Credor $credor
 * @property ContratoParcela[] $contratoParcelas
 * @property Negociacao[] $negociacaos
 */
class Contrato extends \yii\db\ActiveRecord
{
    
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
            [['id_cliente', 'id_credor', 'data_negociacao'], 'required'],
            [['id_cliente', 'id_credor', 'tipo'], 'integer'],
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
            'id_credor' => 'Credor',
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
    public function getCredor()
    {
        return $this->hasOne(Credor::className(), ['id' => 'id_credor']);
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
    public function getNegociacaos()
    {
        return $this->hasMany(Negociacao::className(), ['id_contrato' => 'id']);
    }
    
    /**
     * @inheritDoc
     * @see \yii\db\BaseActiveRecord::beforeSave()
     */
    public function beforeSave($insert) 
    {
        // seta a data de cadastro
        if (empty($this->data_cadastro)) {
            $this->data_cadastro = date('Y-m-d H:i:s');
        }
        
        return parent::beforeSave($insert);        
    }
    
    /**
     * @inheritDoc
     * @see \yii\db\BaseActiveRecord::beforeDelete()
     */
    public function beforeDelete() 
    {
        // deleta todas as parcelas do contrato
        ContratoParcela::deleteAll();
        
        return parent::beforeDelete();
    }
}
