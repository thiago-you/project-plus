<?php
namespace app\models;

/**
 * This is the model class for table "colaborador".
 *
 * @property int $id
 * @property string $nome
 * @property string $username
 * @property string $password
 * @property string $authKey
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
            [['cargo'], 'string', 'max' => 100],
            [['username', 'password', 'authKey'], 'string', 'max' => 30],
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
            'password' => 'password',
            'authKey' => 'authKey',
            'cargo' => 'Cargo',
        ];
    }
}
