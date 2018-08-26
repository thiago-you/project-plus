<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "telefone".
 *
 * @property integer $id_cliente
 * @property string $fone
 * @property string $descricao
 * @property string $tipo
 *
 * @property Cliente $idCliente
 */
class Telefone extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'telefone';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id_cliente', 'fone', 'descricao'], 'required'],
            [['id_cliente'], 'integer'],
            [['tipo'], 'string'],
            [['fone'], 'string', 'max' => 11],
            [['descricao'], 'string', 'max' => 100],
            [['id_cliente'], 'exist', 'skipOnError' => true, 'targetClass' => Cliente::className(), 'targetAttribute' => ['id_cliente' => 'id_cliente']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_cliente' => 'Id Cliente',
            'fone' => 'Fone',
            'descricao' => 'Descricao',
            'tipo' => 'Tipo',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getIdCliente()
    {
        return $this->hasOne(Cliente::className(), ['id_cliente' => 'id_cliente']);
    }
}
