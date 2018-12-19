<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "negociacao_parcela".
 *
 * @property int $id
 * @property int $id_negociacao
 * @property int $num_parcela
 * @property string $data_cadastro
 * @property string $data_vencimento
 * @property string $valor
 * @property string $observacao
 * @property int $status Consultar model para checar as situacoes possiveis
 *
 * @property Negociacao $negociacao
 */
class NegociacaoParcela extends \yii\db\ActiveRecord
{
    // status da parcela
    CONST STATUS_ABERTA = 0;
    CONST STATUS_FATURADA = 1;
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'negociacao_parcela';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_negociacao', 'data_vencimento'], 'required'],
            [['id_negociacao', 'num_parcela', 'status'], 'integer'],
            [['data_cadastro', 'data_vencimento'], 'safe'],
            [['valor'], 'number'],
            [['observacao'], 'string', 'max' => 250],
            [['status'], 'in', 'range' => [self::STATUS_ABERTA, self::STATUS_FATURADA]],
            [['id_negociacao'], 'exist', 'skipOnError' => true, 'targetClass' => Negociacao::className(), 'targetAttribute' => ['id_negociacao' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_negociacao' => 'Id Negociacao',
            'num_parcela' => 'Num Parcela',
            'data_cadastro' => 'Data Cadastro',
            'data_vencimento' => 'Data Vencimento',
            'valor' => 'Valor',
            'observacao' => 'Observacao',
            'status' => 'Status',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNegociacao()
    {
        return $this->hasOne(Negociacao::className(), ['id' => 'id_negociacao']);
    }
    
    /**
     * @inheritDoc
     * @see \yii\db\BaseActiveRecord::beforeSave()
     */
    public function beforeSave($insert) 
    {
        if (empty($this->data_cadastro)) {
            $this->data_cadastro = date('Y-m-d');
        }
        
        return parent::beforeSave($insert);
    }
}
