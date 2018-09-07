<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "credor_calculo".
 *
 * @property int $id
 * @property int $id_credor
 * @property string $atraso
 * @property string $multa
 * @property string $juros
 * @property string $tipo Tipo do calculo => V: A vista / P: Parcelado
 * @property int $parcela_num Numero da parcela qunado o tipo for parcelado
 *
 * @property Credor $credor
 */
class CredorCalculo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'credor_calculo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_credor', 'atraso'], 'required'],
            [['id_credor', 'parcela_num'], 'integer'],
            [['multa', 'juros'], 'number'],
            [['tipo'], 'string'],
            [['atraso'], 'string', 'max' => 3],
            [['id_credor'], 'exist', 'skipOnError' => true, 'targetClass' => Credor::className(), 'targetAttribute' => ['id_credor' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_credor' => 'Id Credor',
            'atraso' => 'Atraso',
            'multa' => 'Multa',
            'juros' => 'Juros',
            'tipo' => 'Tipo',
            'parcela_num' => 'Parcela Num',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCredor()
    {
        return $this->hasOne(Credor::className(), ['id' => 'id_credor']);
    }
}
