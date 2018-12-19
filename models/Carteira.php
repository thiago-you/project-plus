<?php

namespace app\models;

use Yii;
use app\base\Helper;

/**
 * This is the model class for table "carteira".
 *
 * @property int    $id
 * @property int    $id_campanha
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
 * @property string $logo Caminho para a logo da carteira
 * @property string $codigo
 * @property string $sigla
 *
 * @property CarteiraCampanha   $carteiraCampanha
 * @property CarteiraCampanha[] $carteiraCampanhas
 */
class Carteira extends \yii\db\ActiveRecord
{
	// flag que determina se a carteira esta ativo ou nao
	CONST ATIVO = 'S';
	CONST NAO_ATIVO = 'N';
	// const para o tipo
	CONST TIPO_PADRAO = '1';
	// const para o tipo de cobranca
	CONST TIPO_COBRANCA_ADM = '1';
	CONST TIPO_COBRANCA_JUR = '2';
	
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'carteira';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nome', 'cnpj', 'telefone', 'email', 'logradouro', 'numero'], 'required'],
            [['tipo', 'tipo_cobranca', 'cidade_id', 'estado_id', 'id_campanha'], 'integer'],
            [['ativo', 'cep'], 'string'],
            [['nome', 'razao_social', 'logo', 'codigo', 'sigla'], 'string', 'max' => 250],
            [['telefone'], 'string', 'max' => 15],
            [['email', 'logradouro', 'bairro'], 'string', 'max' => 100],
            [['numero'], 'string', 'max' => 10],
            [['complemento'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Cód.',
            'id_campanha' => 'Campanha',
            'nome' => 'Nome',
            'razao_social' => 'Razão Social',
            'tipo' => 'Tipo',
            'tipo_cobranca' => 'Tipo de Cobrança',
            'ativo' => 'Ativo',
            'cnpj' => 'CNPJ',
            'telefone' => 'Telefone',
            'email' => 'Email',
            'logradouro' => 'Logradouro',
            'numero' => 'Número',
            'complemento' => 'Complemento',
            'bairro' => 'Bairro',
            'cep' => 'CEP',
            'cidade_id' => 'Cidade',
            'estado_id' => 'Estado',
            'logo' => 'Logo',
            'codigo' => 'Codigo',
            'sigla' => 'Sigla',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCarteiraCampanha()
    {
        return $this->hasOne(CarteiraCampanha::className(), ['id' => 'id_campanha']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCarteiraCampanhas()
    {
        return $this->hasMany(CarteiraCampanha::className(), ['id_carteira' => 'id']);
    }
    
    /**
     * @inheritDoc
     * @see \yii\db\BaseActiveRecord::beforeSave()
     */
    public function beforeSave($insert) 
    {
        // remove as mascaras
        $this->cnpj = Helper::unmask($this->cnpj);
        $this->cep = Helper::unmask($this->cep);
        $this->telefone = Helper::unmask($this->telefone);
        
        // seta a razao social igual ao nome quando estiver vazia
        if (empty($this->razao_social)) {
            $this->razao_social = $this->nome;
        }
        
        return parent::beforeSave($insert);
    }
    
    /**
     * @inheritDoc
     * @see \yii\db\BaseActiveRecord::beforeDelete()
     */
    public function beforeDelete() 
    {
        // deleta todas as campanhas da carteira
        foreach($this->carteiraCampanhas as $campanha) {
            $campanha->delete();
        }
                
        return parent::beforeDelete();    
    }
}
