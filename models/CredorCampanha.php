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
 *
 * @property Credor $credor
 */
class CredorCampanha extends \yii\db\ActiveRecord
{
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
            'vigencia_inicial' => 'Vigencia Inicial',
            'vigencia_final' => 'Vigencia Final',
            'prioridade' => 'Prioridade',
            'por_parcela' => 'Por Parcela',
            'por_portal' => 'Por Portal',
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
