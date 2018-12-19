<?php
namespace app\models;

/**
 * This is the model class for table "colaborador".
 *
 * @property int $id
 * @property int $id_carteira Carteira do cliente
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
            [['cargo', 'id_carteira'], 'integer'],
            [['username', 'authKey'], 'string', 'max' => 30],
            [['password'], 'string', 'max' => 60],
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
            'id_carteira' => 'Carteira',
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
    
    /**
     * Retorna uma instancia do usuario Admin
     */
    public static function getAdminUser() 
    {
        $admin = new Colaborador();
        $admin->id = 0;
        $admin->cargo = self::CARGO_ADMINISTRADOR;
        $admin->nome = 'Admin';
        $admin->isNewRecord = false;
        
        return $admin;
    }
    
    /** 
     * {@inheritDoc}
     * @see \yii\db\BaseActiveRecord::beforeSave()
     */
    public function beforeSave($insert) 
    {
        // transforma o password em um hash
        if ($insert || !empty($this->password) && $this->password != $this->oldAttributes['password']) {
            $this->password = \Yii::$app->getSecurity()->generatePasswordHash($this->password);
        } else {
            $this->password = $this->oldAttributes['password'];
        }
        
        return parent::beforeSave($insert);
    }
}
