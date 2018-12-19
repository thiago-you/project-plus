<?php
namespace app\models;

use Yii;
use app\base\Helper;

/**
 * This is the model class for table "cliente".
 *
 * @property int $id
 * @property int $id_responsavel
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
 * @property string $tipo_cadastro Flag que valida se o cliente é responsável/avalista de outro cliente
 *
 * @property Contrato[]    $contratos
 * @property Email[]       $emails
 * @property Endereco[]    $enderecos
 * @property Acionamento[] $acionamentos
 * @property Referencia[]  $referencias
 * @property Telefone[]    $telefones
 * @property Cliente       $responsavel
 */
class Cliente extends \yii\db\ActiveRecord
{
	// const to tipo do cliente
	CONST TIPO_FISICO = 'F';
	CONST TIPO_JURIDICO = 'J';
	CONST TIPO_CADASTRO_CLIENTE = 1;
	CONST TIPO_CADASTRO_RESPONSAVEL = 2;
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
        $rules = [
            [['sexo', 'ativo', 'tipo'], 'string'],
            [['data_nascimento', 'data_cadastro'], 'safe'],
            [['estado_civil', 'tipo_cadastro', 'id_responsavel'], 'integer'],
            [['salario'], 'number'],
            [['tipo'], 'in', 'range' => [self::TIPO_FISICO, self::TIPO_JURIDICO]],
            [['nome', 'nome_social', 'nome_conjuge', 'nome_pai', 'nome_mae', 'empresa'], 'string', 'max' => 250],
            [['rg', 'documento'], 'string'],
            [['rg', 'documento'], 'unique'],
            [['inscricao_estadual'], 'string', 'max' => 15],
            [['profissao'], 'string', 'max' => 100],
        ];
        
        // valida o tipo do cliente
        if ($this->tipo == self::TIPO_FISICO) {
            $rules[] = [['nome'], 'required', 'message' => 'O "Nome" não pode ficar em branco.'];
        } elseif ($this->tipo == self::TIPO_JURIDICO) {
            $rules[] = [['nome'], 'required', 'message' => 'A "Razão Social" não pode ficar em branco.'];
        }
        
        return $rules;
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
            'tipo' => 'Tipo do Cliente',
            'tipo_cadastro' => 'Tipo de Cadastro',
            'id_responsavel' => 'Responsável/Avalista',
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
    public function getAcionamentos()
    {
        return $this->hasMany(Acionamento::className(), ['id_cliente' => 'id']);
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
     * @return \yii\db\ActiveQuery
     */
    public function getResponsavel()
    {
        return $this->hasOne(Cliente::className(), ['id' => 'id_responsavel']);
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
    	
    	// formata a data para salvar
    	$this->data_nascimento = Helper::dateUnmask($this->data_nascimento, Helper::DATE_DEFAULT);
    	
    	// formata o nome da mae e pai
    	$this->nome_mae = ucwords(strtolower($this->nome_mae));
    	$this->nome_pai = ucwords(strtolower($this->nome_pai));
    	
    	// valida o apelido/razao social
    	if (empty($this->nome_social)) {
    	    $this->nome_social = $this->nome;
    	}
    	
    	return parent::beforeSave($insert);
    }
    
    /**
     * @inheritDoc
     * @see \yii\db\BaseActiveRecord::afterFind()
     */
    public function afterFind() 
    {
        // formata a data para ser exibida
        $this->data_nascimento = Helper::dateMask($this->data_nascimento, Helper::DATE_DEFAULT);        
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
    
    /**
     * Adiciona um telefone ao cliente
     */
    public function addTelefone($telefone = '', $tipo = Telefone::TIPO_RESIDENCIAL, $id_cliente = null) 
    {
        // valida e seta os numeros de telefone
        if (isset($telefone) && !empty($telefone)) {
            // remove a mascara do telefone
            $telefone = Helper::unmask($telefone, true);

            // seta o id do cliente
            $id_cliente = $id_cliente ? $id_cliente : ($this->id ? $this->id : $this->getPrimaryKey());
            
            // verifica se este telefone ja foi cadastrado
            if (!Telefone::findOne(['id_cliente' => $id_cliente, 'numero' => $telefone])) {
                $modelTelefone = new Telefone();
                $modelTelefone->id_cliente = $id_cliente;
                $modelTelefone->numero = $telefone;
                $modelTelefone->tipo = $tipo;
                $modelTelefone->ativo = Telefone::SIM;
                
                // salva a model
                if (!$modelTelefone->save()) {
                    throw new \Exception(Helper::renderErrors($modelTelefone->getErrors()));
                }
            }
        }
    }
    
    /*
     * Retorna formatado o primeiro endereço ativo do cliente
     */
    public function getEnderecoCompleto() 
    {
        $endereco = '';
        $enderecoModel = Endereco::find()->where([
            'id_cliente' => $this->id,
            'ativo' => Endereco::ATIVO,
        ])->orderBy(['id' => SORT_ASC])->one();
        
        // valida se encontrou algum endereço
        if ($enderecoModel) {
            $endereco = $enderecoModel->getEnderecoCompleto();
        }
        
        return $endereco;        
    }
    
    /**
     * Retorna a descricao do estado civil
     */
    public function getEstadoCivilDescricao() 
    {
        switch ($this->estado_civil) {
            case '1':
                return 'Solteiro(a)';
                break;
            case '2':
                return 'Casado(a)';
                break;
            case '3':
                return 'Divorciado(a)';
                break;
            case '4':
                return 'Viuvo(a)';
                break;
        }
    }
}
