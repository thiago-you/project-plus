<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cliente".
 *
 * @property int $id
 * @property string $nome Razao social ou nome do cliente
 * @property string $nome_social Nome fantasia ou apelido do cliente
 * @property string $rg
 * @property string $documento Documento pode ser usado para CPF ou CNPJ
 * @property string $inscricao_estadual
 * @property string $sexo
 * @property string $data_nascimento
 * @property string $data_cadastro
 * @property int    $estado_civil
 * @property string $nome_conjuge
 * @property string $nome_pai
 * @property string $nome_mae
 * @property string $empresa
 * @property string $profissao
 * @property string $salario
 * @property string $ativo Flag que valida se o cliente esta ativo
 * @property string $tipo Flag que valida se o cliente é tipo fisico (F) ou juridico (J)
 *
 * @property Contrato[]   $contratos
 * @property Email[]      $emails
 * @property Endereco[]   $enderecos
 * @property Evento[]     $eventos
 * @property Referencia[] $referencias
 * @property Telefone[]   $telefones
 */
class Cliente extends \yii\db\ActiveRecord
{
	// const to tipo do cliente
	CONST TIPO_FISICO = 'F';
	CONST TIPO_JURIDICO = 'J';
	// const do sexo do cliente
	CONST SEXO_MASC = 'M';
	CONST SEXO_FEM = 'F';
	
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cliente';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            //[['nome', 'data_cadastro'], 'required'],
            [['sexo', 'ativo', 'tipo'], 'string'],
            [['data_nascimento', 'data_cadastro'], 'safe'],
            [['estado_civil'], 'integer'],
            [['salario'], 'number'],
            [['nome', 'nome_social', 'nome_conjuge', 'nome_pai', 'nome_mae', 'empresa'], 'string', 'max' => 250],
            [['rg', 'documento'], 'string'],
            [['inscricao_estadual'], 'string', 'max' => 15],
            [['profissao'], 'string', 'max' => 100],
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
            'nome_social' => 'Nome Social',
            'rg' => 'Rg',
            'documento' => 'Documento',
            'inscricao_estadual' => 'Inscrição Estadual',
            'sexo' => 'Sexo',
            'data_nascimento' => 'Data de Nascimento',
            'data_cadastro' => 'Data de Cadastro',
            'estado_civil' => 'Estado Civil',
            'nome_conjuge' => 'Nome do(a) Cônjuge',
            'nome_pai' => 'Nome do Pai',
            'nome_mae' => 'Nome da Mãe',
            'empresa' => 'Empresa',
            'profissao' => 'Profissão',
            'salario' => 'Salário',
            'ativo' => 'Ativo',
            'tipo' => 'Tipo',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContratos()
    {
        return $this->hasMany(Contrato::className(), ['id_cliente' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmails()
    {
        return $this->hasMany(Email::className(), ['id_cliente' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEnderecos()
    {
        return $this->hasMany(Endereco::className(), ['id_cliente' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEventos()
    {
        return $this->hasMany(Evento::className(), ['id_cliente' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReferencias()
    {
        return $this->hasMany(Referencia::className(), ['id_cliente' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTelefones()
    {
        return $this->hasMany(Telefone::className(), ['id_cliente' => 'id']);
    }
    
    /**
     * Retorna uma lista de estados civis
     */
    public static function getListaEstadoCivil() 
    {
    	return [
    		'1' => 'Solteiro(a)',
    		'2' => 'Casado(a)',
    		'3' => 'Divorciado(a)',
    		'4' => 'Viuvo(a)',
    	];
    }
    
    /**
     * @inheritDoc
     * @see \yii\db\BaseActiveRecord::beforeSave()
     */
    public function beforeSave($insert) 
    {
    	if (empty($this->data_cadastro)) {
    		$this->data_cadastro = date('Y-m-d H:i:s');
    	}
    	
    	return parent::beforeSave($insert);
    }
    
    /**
     * @inheritDoc
     * @see \yii\db\BaseActiveRecord::beforeDelete()
     */
    public function beforeDelete() 
    {
    	// deleta todos os dados relacionados ao cliente
    	Telefone::deleteAll(['id_cliente' => $this->id]);
    	Email::deleteAll(['id_cliente' => $this->id]);
    	Endereco::deleteAll(['id_cliente' => $this->id]);
    	
    	return parent::beforeDelete();
    }
}
