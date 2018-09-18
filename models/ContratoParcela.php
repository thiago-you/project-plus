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
 * @property string $observacao
 * @property string $status
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
            [['data_cadastro', 'data_vencimento', 'observacao', 'status'], 'safe'],
            [['valor'], 'number'],
            [['id_contrato'], 'exist', 'skipOnError' => true, 'targetClass' => Contrato::className(), 'targetAttribute' => ['id_contrato' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Cód.',
            'id_contrato' => 'Cód. Contrato',
            'num_parcela' => 'N° Parcela',
            'data_cadastro' => 'Data de Cadastro',
            'data_vencimento' => 'Data de Vencimento',
            'valor' => 'Valor',
            'observacao' => 'Observação',
            'status' => 'Status',
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
        
        return parent::beforeSave($insert);
    }
    
    /**
     * Retorna o numero de dias em atraso apartir da data de vencimento
     */
    public function getAtraso() 
    {
        $dataAtual = time();
        $vencimento = strtotime($this->data_vencimento);
        $diferenca = $dataAtual - $vencimento;
        
        return round($diferenca / (60 * 60 * 24));
    }
    
    /**
     * Retorna a descricao do tipo
     */
    public function getStatusDescricao() 
    {
        switch ($this->status) {
            case 1:
                return 'Sem Neg.';
                break;
        }
    }
}



