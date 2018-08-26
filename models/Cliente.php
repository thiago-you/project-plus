<?php

namespace app\models;

use Yii;

/**
 * Esta é a classe para a tabela (entidade) "cliente".
 *
 * @include @yii/docs/base-Component.md
 * @author Thiago You <thya9o@outlook.com>
 * @since 1.0
 * 
 * @property integer $id_cliente
 * @property string $nome
 * @property string $sobrenome
 * @property string $apelido
 * @property string $documento
 * @property string $sexo
 * @property string $data_nascimento
 * @property string $data_cadastro
 * @property string $cep
 * @property string $endereco
 * @property string $numero
 * @property string $complemento
 * @property string $bairro
 * @property integer $id_cidade
 * @property integer $id_estado
 * @property string $email
 * @property integer $situacao
 * @property string $tipo
 *
 * @property Telefone[] $telefones
 */
class Cliente extends \yii\db\ActiveRecord
{
    /**
    * Retorna o nome da tabela (entidade) utilizada pela classe.
    * 
    * @return string => Nome da tabela
    */
    public static function tableName()
    {
        return 'cliente';
    }

    /**
    * Define as regras, restrições e validações para os atributos da classe.
    * Estes atributos devem possuír no mínimo as mesmas restrições dos atributos da tabela na database,
    * para que não ocorra erro durante a persistência.
    * Regras adicionais podem ser aplicadas.
    *
    * @return array => regras para os atributos
    */
    public function rules()
    {
        return [
            [['nome', 'sobrenome', 'data_cadastro'], 'required'],
            [['sexo'], 'string'],
            [['data_nascimento', 'data_cadastro'], 'safe'],
            [['id_cidade', 'id_estado', 'situacao'], 'integer'],
            [['nome', 'apelido', 'email'], 'string', 'max' => 100],
            [['sobrenome'], 'string', 'max' => 150],
            [['documento'], 'string', 'max' => 14],
            [['cep'], 'string', 'max' => 8],
            [['endereco'], 'string', 'max' => 50],
            [['numero'], 'string', 'max' => 5],
            [['complemento'], 'string', 'max' => 20],
            [['bairro'], 'string', 'max' => 30],
            [['tipo'], 'string', 'max' => 1],
        ];
    }

    /**
    * Retorna a label (descrição) de cada atirbuto
    * 
    * @return array => labels de cada atirbuto da classe
    */
    public function attributeLabels()
    {
        return [
            'id_cliente' => 'Cód.',
            'nome' => 'Nome',
            'sobrenome' => 'Sobrenome',
            'apelido' => 'Apelido',
            'documento' => 'Documento',
            'sexo' => 'Sexo',
            'data_nascimento' => 'Data de Nascimento',
            'data_cadastro' => 'Data de Cadastro',
            'cep' => 'CEP',
            'endereco' => 'Endereço',
            'numero' => 'Numero',
            'complemento' => 'Complemento',
            'bairro' => 'Bairro',
            'id_cidade' => 'Cidade',
            'id_estado' => 'Estado',
            'email' => 'Email',
            'situacao' => 'Situação',
            'tipo' => 'Tipo',
        ];
    }

    /**
    * Procura e retorna uma lista de telefones que possuem relação com a tabela cliente
    *
    * ```
    * $component->hasMany(class::(), ['atributo' => 'atributo']);
    * ```
    *
    * @return array => telefone relacionados com cliente ou null se nada for encontrado
    */
    public function getTelefones()
    {
        return $this->hasMany(Telefone::className(), ['id_cliente' => 'id_cliente']);
    }
    
    /**
     * Inicializa a data de cadastro como a data atual antes de persistir os dados.
     * Este método chama o beforeSave (mesmo método) da super classe enviando o $insert.
     *
     * @param array $insert => atributos que foram alterados no registro
     * @return boolean => true (verdadeiro) 
     */
    public function beforeSave($insert)
    {
        parent::beforeSave($insert);

        $this->data_cadastro = date('Y-m-d');   
        return true;
    }
}
