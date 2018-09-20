<?php
namespace app\models;

use Yii;
use app\base\Util;

/**
 * This is the model class for table "contador".
 *
 * @property integer $id_contador
 * @property integer $empresa_id
 * @property string  $nome_contador
 * @property integer $tipo
 * @property integer $cpf_contador
 * @property string  $crc_contador
 * @property integer $cnpj
 * @property string  $logradouro
 * @property string  $nroend
 * @property string  $complemento
 * @property string  $bairro
 * @property integer $cep
 * @property integer $id_cidade
 * @property integer $id_estado
 * @property string  $fone
 * @property string  $email
 *
 * @property Empresa         $empresa
 * @property Cidade          $cidade
 * @property EstadoFederacao $estado
 */
class Contador extends \yii\db\ActiveRecord
{
    // const para o tipo fisico/juridico
    CONST TIPO_FISICO = 1;
    CONST TIPO_JURIDICO = 2;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'contador';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['empresa_id', 'nome_contador'], 'required'],
            [['empresa_id', 'tipo', 'id_cidade', 'id_estado'], 'integer'],
            [['nome_contador', 'logradouro', 'email'], 'string', 'max' => 255],
            [['crc_contador'], 'string', 'max' => 20],
            [['nroend'], 'string', 'max' => 10],
            [['cnpj', 'cpf_contador', 'crc_contador'], 'unique'],
            [['complemento', 'bairro'], 'string', 'max' => 60], 
            [['empresa_id'], 'exist', 'skipOnError' => true, 'targetClass' => Empresa::className(), 'targetAttribute' => ['empresa_id' => 'id']],
            [['id_cidade'], 'exist', 'skipOnError' => true, 'targetClass' => Cidade::className(), 'targetAttribute' => ['id_cidade' => 'id']],
            [['id_estado'], 'exist', 'skipOnError' => true, 'targetClass' => EstadoFederacao::className(), 'targetAttribute' => ['id_estado' => 'id']],
            [['cep'], 'string', 'length' => [8], 'tooShort' => '"{attribute}" deve conter no mínimo {min} números.'],
            [['fone'], 'string', 'length' => [10, 15], 'tooShort' => '"{attribute}" deve conter no mínimo {min} números.'],
            [['cpf_contador', 'cnpj'], 'customValidator'],
            ['crc_contador', 'crcValidator'],
            [['cpf_contador', 'cnpj'], 'unique', 'message' => '"{attribute}" já foi utilizado em outro contador'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id_contador'   => 'Cód.',
            'empresa_id'    => 'Empresa',
            'nome_contador' => 'Nome',
            'tipo'          => 'Tipo',
            'cpf_contador'  => 'CPF',
            'crc_contador'  => 'CRC',
            'cnpj'          => 'CNPJ',
            'logradouro'    => 'Logradouro',
            'nroend'        => 'Número',
            'complemento'   => 'Complemento',
            'bairro'        => 'Bairro',
            'cep'           => 'Cep',
            'id_cidade'     => 'Cidade',
            'id_estado'     => 'Estado',
            'fone'          => 'Telefone',
            'email'         => 'E-mail',
        ];
    }
     
    /**
     * Validação para o CPF e o CNPJ do contador
     */
    public function customValidator($attribute, $params, $validator)
    {
        if ($this->tipo == self::TIPO_FISICO) {
            if ($attribute == 'cpf_contador') {
                $this->$attribute = Util::removeMascara($this->$attribute, true);
                // cpf é required
                if (empty($this->$attribute)) {
                    $validator->addError($this, $attribute, 'O "CPF" não pode ficar em branco.');
                }
                // valida o tamanho do cnpj
                if (strlen($this->$attribute) != 11) {
                    $validator->addError($this, $attribute, 'O "CPF" deve conter 11 digítos.');
                }
                // valida o CPF
                if (!Util::validarCPF($this->$attribute)) {
                    $validator->addError($this, $attribute, 'O "CPF" informado é invalido.');
                }
            }
        } elseif ($this->tipo == self::TIPO_JURIDICO) {            
            if ($attribute == 'cnpj') {                
                $this->$attribute = Util::removeMascara($this->$attribute, true);
                // cnpj é required
                if (empty($this->$attribute)) {
                    $validator->addError($this, $attribute, 'O "CNPJ" não pode ficar em branco.');
                }
                // valida o tamanho do cnpj
                if (strlen($this->$attribute) != 14) {
                    $validator->addError($this, $attribute, 'O "CNPJ" deve conter 14 digítos.');
                }
                // valida o CNPJ
                if (!Util::validarCPNJ($this->$attribute)) {
                    $validator->addError($this, $attribute, 'O "CNPJ" informado é invalido.');
                }
            }
        }
    }
    
    /**
     * Valida o CRC do contador
     */
    public function crcValidator($attribute, $params, $validator)
    {
        if (!empty($this->$attribute)) {
            $crc = (string) $this->$attribute;
            
            $uf = strtoupper(substr($crc, 0, 2));
            $po = strtoupper(substr($crc, -1));
            
            // lista de UFS
            $ufs = ['AC','AL','AM','AP','BA','CE','CF','DF','ES','FR','GB','GO','LG','MA','MG','MS','MT','NI','NR','PA','PB','PE','PF','PI','PJ','PR','RJ','RN','RO','RR','RS','SC','SE','SP','TO'];
            
            // valida o CRC
            if (!in_array($uf, $ufs) || ($po != 'P' && $po != 'O')) {
                $validator->addError($this, $attribute, 'O "CRC" informado é inválido.');
            }
        }
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmpresa()
    {
        return $this->hasOne(Empresa::className(), ['id' => 'empresa_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCidade()
    {
        return $this->hasOne(Cidade::className(), ['id' => 'id_cidade']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEstado()
    {
        return $this->hasOne(EstadoFederacao::className(), ['id' => 'id_estado']);
    }
    
    /**
     * @inheritDoc
     * @see \yii\db\BaseActiveRecord::beforeSave()
     */
    public function beforeSave($insert) 
    {
        // valida o tipo do contador
        if ($this->tipo == self::TIPO_FISICO) {            
            $this->cpf_contador = Util::removeMascara($this->cpf_contador, true);
            $this->cnpj = null;
        } elseif ($this->tipo == self::TIPO_JURIDICO) {            
            $this->cnpj = Util::removeMascara($this->cnpj, true);
            $this->cpf_contador = null;
        }
        
        // remove as mascaras antes de salvar
        $this->fone = Util::removeMascara($this->fone, true);
        $this->cep  = Util::removeMascara($this->cep, true);
        
        // seta o crc como maiusculo
        if (!empty($this->crc_contador)) {            
            $this->crc_contador = strtoupper($this->crc_contador);
        } else {
            $this->crc_contador = null;
        }
        
        return parent::beforeSave($insert);
    }
}
