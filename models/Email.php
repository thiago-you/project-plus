<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "email".
 *
 * @property int $id
 * @property int $id_cliente
 * @property string $email
 * @property string $observacao
 * @property string $ativo Flag que valida se o email esta ativo
 *
 * @property Cliente $cliente
 */
class Email extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'email';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_cliente', 'email'], 'required'],
            [['id_cliente'], 'integer'],
            [['ativo'], 'string'],
            [['email'], 'string', 'max' => 100],
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
            'id' => 'ID',
            'id_cliente' => 'Id Cliente',
            'email' => 'Email',
            'observacao' => 'Observacao',
            'ativo' => 'Ativo',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCliente()
    {
        return $this->hasOne(Cliente::className(), ['id' => 'id_cliente']);
    }
}
