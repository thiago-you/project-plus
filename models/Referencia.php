<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "referencia".
 *
 * @property int $id
 * @property int $id_cliente
 * @property string $nome
 * @property string $cpf
 * @property string $observacao
 * @property int $tipo Flag que valida o tipo da referencia
 * @property string $ativo Flag que valida se a referencia esta ativa
 *
 * @property Cliente $cliente
 */
class Referencia extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'referencia';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_cliente', 'nome', 'cpf'], 'required'],
            [['id_cliente', 'tipo'], 'integer'],
            [['ativo'], 'string'],
            [['nome', 'observacao'], 'string', 'max' => 250],
            [['cpf'], 'string', 'max' => 11],
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
            'nome' => 'Nome',
            'cpf' => 'Cpf',
            'observacao' => 'Observacao',
            'tipo' => 'Tipo',
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
