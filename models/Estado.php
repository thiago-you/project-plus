<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "estados".
 *
 * @property int $id_estado
 * @property string $codigo_ibge
 * @property string $sigla
 * @property string $nome
 */
class Estado extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'estados';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['codigo_ibge', 'sigla', 'nome'], 'required'],
            [['codigo_ibge'], 'string', 'max' => 4],
            [['sigla'], 'string', 'max' => 2],
            [['nome'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_estado' => 'Id Estado',
            'codigo_ibge' => 'Codigo Ibge',
            'sigla' => 'Sigla',
            'nome' => 'Nome',
        ];
    }
}
