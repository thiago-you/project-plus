<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "credor_calculo".
 *
 * @property int    $id
 * @property int    $id_campanha
 * @property string $atraso_inicio
 * @property string $atraso_fim
 * @property string $multa
 * @property string $juros
 * @property string $honorario
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
            [['id_campanha'], 'required'],
            [['id_campanha', 'parcela_num'], 'integer'],
            [['multa', 'juros', 'honorario'], 'number'],
            [['atraso_inicio', 'atraso_fim'], 'string', 'max' => 3],
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
            'atraso_inicio' => 'Ínicio do Atraso',
            'atraso_fim' => 'Fim do Atraso',
            'multa' => 'Multa',
            'juros' => 'Juros',
            'honorario' => 'Honorários',           
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
    
    /**
     * Retorna a faixa de atraso
     */
    public function getAtraso() 
    {
        return "{$this->atraso_inicio} - {$this->atraso_fim}";
    }
    
    /**
     * @inheritDoc
     * @see \yii\db\BaseActiveRecord::beforeSave()
     */
    public function beforeSave($insert) 
    {
        // valida a data final de atraso
        if (empty($this->atraso_fim)) {
            $this->atraso_fim = 999;
        }
        
        return parent::beforeSave($insert);    
    }
}
