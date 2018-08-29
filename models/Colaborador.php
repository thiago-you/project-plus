<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "colaborador".
 *
 * @property int $id
 * @property string $nome
 * @property string $username
 * @property string $senha
 * @property string $cargo Cargo do usuario
 */
class Colaborador extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'colaborador';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nome', 'username'], 'required'],
            [['nome'], 'string', 'max' => 250],
            [['username', 'cargo'], 'string', 'max' => 100],
            [['senha'], 'string', 'max' => 60],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nome' => 'Nome',
            'username' => 'Username',
            'senha' => 'Senha',
            'cargo' => 'Cargo',
        ];
    }
}
