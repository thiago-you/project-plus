<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "telefone".
 *
 * @property int    $id
 * @property int    $id_cliente
 * @property string $numero
 * @property string $ramal
 * @property int    $tipo
 * @property string $observacao
 * @property string $contato Flag que valida de o numero e para contato
 * @property string $whatsapp Flag que valida se o numero possui whatsapp
 * @property string $ativo
 *
 * @property Cliente $cliente
 */
class Telefone extends \yii\db\ActiveRecord
{
	// flag para whatsapp e ativo
	CONST SIM = 'S';
	CONST NAO = 'N';
	
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'telefone';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_cliente', 'numero'], 'required'],
            [['id_cliente', 'tipo'], 'integer'],
            [['contato', 'whatsapp', 'ativo'], 'string'],
            [['numero'], 'string', 'max' => 15],
            [['ramal'], 'string', 'max' => 4],
            [['observacao'], 'string', 'max' => 100],
            [['id_cliente'], 'exist', 'skipOnError' => true, 'targetClass' => Cliente::className(), 'targetAttribute' => ['id_cliente' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_cliente' => 'Id Cliente',
            'numero' => 'Numero',
            'ramal' => 'Ramal',
            'tipo' => 'Tipo',
            'observacao' => 'Observacao',
            'contato' => 'Contato',
            'whatsapp' => 'Whatsapp',
            'ativo' => 'Ativo',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCliente()
    {
        return $this->hasOne(Cliente::className(), ['id' => 'id_cliente']);
    }
    
    /**
     * Retorna uma lista dos tipos de telefone
     */
    public static function getListaTipos()
    {
    	return [
    		'1' => 'Residencial',
    		'2' => 'Móvel',
    		'3' => 'Comercial',
    		'4' => 'Fax',
    		'5' => 'Referência',
    	];
    }
}
