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
 * @property int $cargo Cargo do usuario
 */
class Colaborador extends \yii\db\ActiveRecord
{
    // const para os cargos disponíveis
    CONST CARGO_ADMINISTRADOR = '1';
    CONST CARGO_OPERADOR = '2';
    CONST CARGO_CLIENTE = '3';
    
    public $teste = '';
    
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
            [['nome', 'username', 'password'], 'required'],
            [['nome'], 'string', 'max' => 250],
            [['cargo'], 'integer'],
            [['username', 'password', 'authKey'], 'string', 'max' => 30],
            ['username', function ($attribute, $params, $validator) {
                if (strtoupper($this->$attribute) == 'ADMIN') {
                    $this->addError($attribute, 'O username "Admin" é reservado pelo sistema.');
                }
            }],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Cód.',
            'nome' => 'Nome',
            'username' => 'Username',
            'password' => 'Senha',
            'authKey' => 'authKey',
            'cargo' => 'Cargo',
        ];
    }
    
    /**
     * Retorna a lista de cargos disponíveis
     */
    public static function getListaCargos() 
    {
        return [
            self::CARGO_ADMINISTRADOR => 'Administrador',
            self::CARGO_OPERADOR => 'Operador',
            self::CARGO_CLIENTE => 'Cliente',
        ];
    }
    
    /**
     * Retorna a descrição do cargo
     */
    public function getCargo() 
    {
        // monta a lista de cargos
        $cargos = Colaborador::getListaCargos();

        // retorna o cargo
        return $cargos[$this->cargo];
    }
}
