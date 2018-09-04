<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "negociacao".
 *
 * @property int $id
 * @property int $id_contrato
 * @property string $data_negociacao
 * @property string $data_cadastro
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
            [['id_contrato', 'data_negociacao', 'data_cadastro'], 'required'],
            [['id_contrato'], 'integer'],
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
            'data_negociacao' => 'Data Negociacao',
            'data_cadastro' => 'Data Cadastro',
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
