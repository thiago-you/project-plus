<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "contrato_parcela".
 *
 * @property int $id
 * @property int $id_contrato
 * @property int $num_parcela
 * @property string $data_cadastro
 * @property string $data_vencimento
 * @property string $valor
 * @property string $multa
 * @property string $total
 *
 * @property Contrato $contrato
 */
class ContratoParcela extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'contrato_parcela';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_contrato', 'data_vencimento'], 'required'],
            [['id_contrato', 'num_parcela'], 'integer'],
            [['data_cadastro', 'data_vencimento'], 'safe'],
            [['valor', 'multa', 'total'], 'number'],
            [['id_contrato'], 'exist', 'skipOnError' => true, 'targetClass' => Contrato::className(), 'targetAttribute' => ['id_contrato' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_contrato' => 'Id Contrato',
            'num_parcela' => 'N° Parcela',
            'data_cadastro' => 'Data Cadastro',
            'data_vencimento' => 'Data Vencimento',
            'valor' => 'Valor',
            'multa' => 'Multa',
            'total' => 'Total',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContrato()
    {
        return $this->hasOne(Contrato::className(), ['id' => 'id_contrato']);
    }
    
    /**
     * @inheritDoc
     * @see \yii\db\BaseActiveRecord::beforeSave()
     */
    public function beforeSave($insert) 
    {
        // seta a data de cadastro da parcela
        if (empty($this->data_cadastro)) {
            $this->data_cadastro = date('Y-m-d H:i:s');
        }
        // seta o total da parcela
        if (empty($this->total)) {
            $this->total = $this->valor + $this->multa;
        }
        
        return parent::beforeSave($insert);
    }
}
