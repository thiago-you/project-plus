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
 * @property int $estado_civil
 * @property string $nome_conjuge
 * @property string $nome_pai
 * @property string $nome_mae
 * @property string $empresa
 * @property string $profissao
 * @property string $salario
 * @property string $ativo Flag que valida se o cliente esta ativo
 * @property string $tipo Flag que valida se o cliente é tipo fisico (F) ou juridico (J)
 *
 * @property Contrato[] $contratos
 * @property Email[] $emails
 * @property Endereco[] $enderecos
 * @property Evento[] $eventos
 * @property Referencia[] $referencias
 * @property Telefone[] $telefones
 */
class Cliente extends \yii\db\ActiveRecord
{
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
            [['nome', 'data_cadastro'], 'required'],
            [['sexo', 'ativo', 'tipo'], 'string'],
            [['data_nascimento', 'data_cadastro'], 'safe'],
            [['estado_civil'], 'integer'],
            [['salario'], 'number'],
            [['nome', 'nome_social', 'nome_conjuge', 'nome_pai', 'nome_mae', 'empresa'], 'string', 'max' => 250],
            [['rg', 'documento'], 'string', 'max' => 14],
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
            'id' => 'ID',
            'nome' => 'Nome',
            'nome_social' => 'Nome Social',
            'rg' => 'Rg',
            'documento' => 'Documento',
            'inscricao_estadual' => 'Inscricao Estadual',
            'sexo' => 'Sexo',
            'data_nascimento' => 'Data Nascimento',
            'data_cadastro' => 'Data Cadastro',
            'estado_civil' => 'Estado Civil',
            'nome_conjuge' => 'Nome Conjuge',
            'nome_pai' => 'Nome Pai',
            'nome_mae' => 'Nome Mae',
            'empresa' => 'Empresa',
            'profissao' => 'Profissao',
            'salario' => 'Salario',
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
}
