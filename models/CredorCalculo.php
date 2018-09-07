<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "credor_calculo".
 *
 * @property int    $id
 * @property int    $id_campanha
 * @property string $atraso
 * @property string $multa
 * @property string $juros
 * @property int    $parcela_num Numero da parcela qunado o tipo for parcelado
 * 
 * @property CredorCampanha $credorCampanha
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
            [['id_campanha', 'atraso'], 'required'],
            [['id_campanha', 'parcela_num'], 'integer'],
            [['multa', 'juros'], 'number'],
            [['atraso'], 'string', 'max' => 3],
            [['id_campanha'], 'exist', 'skipOnError' => true, 'targetClass' => CredorCampanha::className(), 'targetAttribute' => ['id_campanha' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Cód.',
            'id_campanha' => 'Cód. Campanha',
            'atraso' => 'Atraso',
            'multa' => 'Multa',
            'juros' => 'Juros',
            'parcela_num' => 'N° Parcela',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCredorCampanha()
    {
        return $this->hasOne(CredorCampanha::className(), ['id' => 'id_campanha']);
    }
}
