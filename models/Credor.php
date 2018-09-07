<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "credor".
 *
 * @property int    $id
 * @property string $nome
 * @property int    $tipo
 * @property int    $tipo_cobranca
 * @property string $ativo
 * @property string $razao_social
 * @property string $cnpj
 * @property string $telefone
 * @property string $email
 * @property string $logradouro
 * @property string $numero
 * @property string $complemento
 * @property string $bairro
 * @property string $cep
 * @property int    $cidade_id
 * @property int    $estado_id
 * @property string $logo Caminho para a logo do credor
 * @property string $codigo
 * @property string $sigla
 *
 * @property CredorCampanha[] $credorCampanhas
 * @property CredorCalculo[]  $credorCalculos
 */
class Credor extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'credor';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nome', 'cnpj', 'telefone', 'email', 'logradouro', 'numero'], 'required'],
            [['tipo', 'tipo_cobranca', 'cidade_id', 'estado_id'], 'integer'],
            [['ativo'], 'string'],
            [['razao_social'], 'number'],
            [['nome', 'logo', 'codigo', 'sigla'], 'string', 'max' => 250],
            [['cnpj'], 'string', 'max' => 14],
            [['telefone'], 'string', 'max' => 15],
            [['email', 'logradouro', 'bairro'], 'string', 'max' => 100],
            [['numero'], 'string', 'max' => 10],
            [['complemento'], 'string', 'max' => 50],
            [['cep'], 'string', 'max' => 8],
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
            'tipo' => 'Tipo',
            'tipo_cobranca' => 'Tipo Cobranca',
            'ativo' => 'Ativo',
            'razao_social' => 'Razao Social',
            'cnpj' => 'Cnpj',
            'telefone' => 'Telefone',
            'email' => 'Email',
            'logradouro' => 'Logradouro',
            'numero' => 'Numero',
            'complemento' => 'Complemento',
            'bairro' => 'Bairro',
            'cep' => 'Cep',
            'cidade_id' => 'Cidade ID',
            'estado_id' => 'Estado ID',
            'logo' => 'Logo',
            'codigo' => 'Codigo',
            'sigla' => 'Sigla',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreadorCampanhas()
    {
        return $this->hasMany(CredorCampanha::className(), ['id_credor' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCredorCalculos()
    {
        return $this->hasMany(CredorCalculo::className(), ['id_credor' => 'id']);
    }
}
