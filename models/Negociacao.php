<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "negociacao".
 *
 * @property int $id
 * @property int $id_contrato
 * @property int $id_credor
 * @property int $id_campanha
 * @property string $data_negociacao
 * @property string $data_cadastro
 * @property string $subtotal
 * @property string $desconto
 * @property string $receita
 * @property string $total
 * 
 * @property Contrato $contrato
 */
class Negociacao extends \yii\db\ActiveRecord
{
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
            [['data_negociacao', 'data_cadastro'], 'required'],
            [['id_contrato', 'id_credor', 'id_campanha'], 'integer'],
            [['subtotal', 'desconto', 'receita', 'total'], 'number'],
            [['data_negociacao', 'data_cadastro'], 'safe'],
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
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContrato()
    {
        return $this->hasOne(Contrato::className(), ['id' => 'id_contrato']);
    }
}
