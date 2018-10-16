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
 * @property string $desc_encargos_max
 * @property string $desc_principal_max
 * @property string $desc_honorario_max
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
            [['multa', 'juros', 'honorario', 'desc_encargos_max', 'desc_principal_max', 'desc_honorario_max'], 'safe'],
            [['atraso_inicio', 'atraso_fim'], 'string', 'max' => 5],
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
            'desc_encargos_max' => 'Desconto Máximo dos Encargos',
            'desc_principal_max' => 'Desconto Máximo Principal',
            'desc_honorario_max' => 'Desconto Máximo dos Honorários',
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
            $this->atraso_fim = 99999;
        }
        
        // seta os valores direto do plugin
        $this->multa = str_replace('%', '', $this->multa);
        $this->juros = str_replace('%', '', $this->juros);
        $this->honorario = str_replace('%', '', $this->honorario);
        $this->desc_encargos_max = str_replace('%', '', $this->desc_encargos_max);
        $this->desc_principal_max = str_replace('%', '', $this->desc_principal_max);
        $this->desc_honorario_max = str_replace('%', '', $this->desc_honorario_max);
        
        return parent::beforeSave($insert);    
    }
    
    /**
     * Busca uma faixa de cálculo no período de atraso
     */
    public static function findFaixa($campanhaId, $atraso) 
    {
        return CredorCalculo::find()->where(['id_campanha' => $campanhaId])->andWhere([
            '<=', 'atraso_inicio', $atraso
        ])->andWhere([
            '>=', 'atraso_fim', $atraso
        ])->orderBy(['id' => SORT_DESC])->one();
    }
}






