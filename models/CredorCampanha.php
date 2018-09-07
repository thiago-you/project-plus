<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "credor_campanha".
 *
 * @property int $id
 * @property int $id_credor
 * @property string $nome
 * @property string $vigencia_inicial
 * @property string $vigencia_final
 * @property int $prioridade Nivel de prioridade da campanha
 * @property string $por_parcela
 * @property string $por_portal
 * @property string $tipo
 *
 * @property Credor $credor
 * @property CredorCalculo $credorCalculo
 */
class CredorCampanha extends \yii\db\ActiveRecord
{
    // flag para whatsapp e ativo
    CONST SIM = 'S';
    CONST NAO = 'N';
    // const para o tipo de calculo
    CONST CALCULO_A_VISTA = 'V';
    CONST CALCULO_PARCELADO = 'P';
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'credor_campanha';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_credor', 'nome', 'vigencia_inicial'], 'required'],
            [['id_credor', 'prioridade'], 'integer'],
            [['vigencia_inicial', 'vigencia_final'], 'safe'],
            [['por_parcela', 'por_portal'], 'string'],
            [['nome'], 'string', 'max' => 250],
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
            'nome' => 'Nome',
            'vigencia_inicial' => 'Vigência Inicial',
            'vigencia_final' => 'Vigência Final',
            'prioridade' => 'Prioridade',
            'por_parcela' => 'Enquadrar Por Parcela',
            'por_portal' => 'Portal do Cliente',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCredor()
    {
        return $this->hasOne(Credor::className(), ['id' => 'id_credor']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCredorCalculo()
    {
        return $this->hasOne(CredorCalculo::className(), ['id_campanha' => 'id']);
    }
}
