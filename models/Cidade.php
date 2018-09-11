<?php
namespace app\models;

/**
 * This is the model class for table "cidade".
 *
 * @property int    $id
 * @property int    $codigo
 * @property string $nome
 * @property string $uf
 * 
 * @property Estado $estado
 */
class Cidade extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cidade';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['codigo', 'nome', 'uf'], 'required'],
            [['codigo'], 'integer'],
            [['nome'], 'string', 'max' => 255],
            [['uf'], 'string', 'max' => 2],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'codigo' => 'Codigo',
            'nome' => 'Nome',
            'uf' => 'Uf',
        ];
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEstado()
    {
        return $this->hasMany(Estado::className(), ['sigla' => 'uf']);
    }
}
