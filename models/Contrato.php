<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "contrato".
 *
 * @property int $id
 * @property int $id_cliente
 * @property string $codigo_cliente
 * @property string $codigo_contrato
 * @property string $num_contrato
 * @property string $num_plano
 * @property string $valor
 * @property string $data_cadastro
 * @property string $data_vencimento
 * @property int $tipo
 * @property string $regiao
 * @property string $filial
 * @property string $observacao
 *
 * @property Cliente $cliente
 * @property ContratoParcela[] $contratoParcelas
 * @property Negociacao[] $negociacaos
 */
class Contrato extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'contrato';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_cliente', 'data_cadastro'], 'required'],
            [['id_cliente', 'tipo'], 'integer'],
            [['valor'], 'number'],
            [['data_cadastro', 'data_vencimento'], 'safe'],
            [['codigo_cliente', 'codigo_contrato', 'num_contrato', 'num_plano', 'regiao', 'filial'], 'string', 'max' => 50],
            [['observacao'], 'string', 'max' => 250],
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
            'codigo_cliente' => 'Codigo Cliente',
            'codigo_contrato' => 'Codigo Contrato',
            'num_contrato' => 'Num Contrato',
            'num_plano' => 'Num Plano',
            'valor' => 'Valor',
            'data_cadastro' => 'Data Cadastro',
            'data_vencimento' => 'Data Vencimento',
            'tipo' => 'Tipo',
            'regiao' => 'Regiao',
            'filial' => 'Filial',
            'observacao' => 'Observacao',
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
     * @return \yii\db\ActiveQuery
     */
    public function getContratoParcelas()
    {
        return $this->hasMany(ContratoParcela::className(), ['id_contrato' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNegociacaos()
    {
        return $this->hasMany(Negociacao::className(), ['id_contrato' => 'id']);
    }
}
