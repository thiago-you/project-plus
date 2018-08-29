<?php
namespace app\models;

class User extends \yii\base\BaseObject implements \yii\web\IdentityInterface
{
    public $id;
    public $username;
    public $password;
    public $authKey;
    public $accessToken;

    /**
     * @var string => usuario admin
     */
    const adminUsername = 'admin';
    
    /**
     * Usuario Admin
     */
    private static $admin = [
        'id' => '0',
        'username' => 'admin',
        'password' => 'admin',
    	'authKey' => '0-key',
    	'accessToken' => '0-token',
    ];

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        // retorna o admin ou busca um usuario no banco
        $user = null;
        if (self::$admin['id'] === $id) {
            $user = self::$admin;
        } else {            
            $user = Colaborador::find()->where(['id' => $id])->select([
                'id', 'username', 'password', 'authKey'
            ])->asArray(true)->one();
        }
        
        return $user ? new static($user) : null;
    }

    /**
     * @deprecated não implementado
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new \Exception('Não suportado.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
    	// retorna o admin ou busca um usuario no banco
        $user = self::$admin;
    	if ($username !== self::adminUsername) {
    	    $user = Colaborador::find()->where(['username' => $username])->select([
    	        'id', 'username', 'password', 'authKey'
    	    ])->asArray(true)->one();
    	}
    	
    	return $user ? new static($user) : null;
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->authKey;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->authKey === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return $this->password === $password;
    }
}
