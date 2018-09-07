<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "evento".
 *
 * @property int $id
 * @property int $id_cliente
 * @property int $colaborador_id
 * @property string $titulo
 * @property string $descricao
 * @property string $data
 * @property string $telefone
 * @property int $tipo Flag que valida o tipo do evento
 *
 * @property Cliente $cliente
 * @property Colaborador $colaborador
 */
class Evento extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'evento';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_cliente', 'colaborador_id', 'titulo'], 'required'],
            [['id_cliente', 'colaborador_id', 'tipo'], 'integer'],
            [['data'], 'safe'],
            [['titulo'], 'string', 'max' => 100],
            [['descricao'], 'string', 'max' => 250],
            [['telefone'], 'string', 'max' => 15],
            [['id_cliente'], 'exist', 'skipOnError' => true, 'targetClass' => Cliente::className(), 'targetAttribute' => ['id_cliente' => 'id']],
            [['colaborador_id'], 'exist', 'skipOnError' => true, 'targetClass' => Colaborador::className(), 'targetAttribute' => ['colaborador_id' => 'id']],
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
            'colaborador_id' => 'Colaborador ID',
            'titulo' => 'Titulo',
            'descricao' => 'Descricao',
            'data' => 'Data',
            'telefone' => 'Telefone',
            'tipo' => 'Tipo',
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
    public function getColaborador()
    {
        return $this->hasOne(Colaborador::className(), ['id' => 'colaborador_id']);
    }
}
