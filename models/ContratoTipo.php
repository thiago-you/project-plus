<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "contrato_tipo".
 *
 * @property int $id
 * @property string $descricao
 * @property string $ativo
 *
 * @property Contrato[] $contratos
 */
class ContratoTipo extends \yii\db\ActiveRecord
{
    // flag para validar se o tipo esta ativo
    CONST ATIVO = 'S';
    CONST NAO_ATIVO = 'N';
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'contrato_tipo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['descricao'], 'required'],
            [['ativo'], 'string'],
            [['ativo'], 'in', 'range' => [self::ATIVO, self::NAO_ATIVO]],
            [['descricao'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Cód.',
            'descricao' => 'Descrição',
            'ativo' => 'Ativo',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContratos()
    {
        return $this->hasMany(Contrato::className(), ['tipo' => 'id']);
    }
}
