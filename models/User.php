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
     * Usuario Admin
     */
    private static $users = [
        '0' => [
            'id' => '0',
            'username' => 'admin',
            'password' => 'admin',
        	'authKey' => '0-key',
        	'accessToken' => '0-token',
        ],
    ];

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return isset(self::$users[$id]) ? new static(self::$users[$id]) : null;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        foreach (self::$users as $user) {
            if ($user['accessToken'] === $token) {
                return new static($user);
            }
        }

        return null;
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
    	// pega a lista de usuarios locais
    	$users = self::$users;
    	
    	// busca o usuario
    	if ($colaborador = Colaborador::find()->where(['username' => $username])->one()) {    		
    		$users[] = [
    			'id' => $colaborador->id,
    			'username' => $colaborador->username,
    			'password' => $colaborador->senha,
    			'authKey' => "{$colaborador->id}-key",
    			'accessToken' => "{$colaborador->id}-token",
    		];
    	}
    	
    	// verifica se o usuario existe e retorna o usuario
        foreach ($users as $user) {
            if (strcasecmp($user['username'], $username) === 0) {
                return new static($user);
            }
        }

        return null;
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
