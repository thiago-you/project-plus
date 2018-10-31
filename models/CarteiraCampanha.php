<?php
namespace app\models;

use Yii;
use app\base\Helper;

/**
 * This is the model class for table "carteira_campanha".
 *
 * @property int $id
 * @property int $id_carteira
 * @property string $nome
 * @property string $vigencia_inicial
 * @property string $vigencia_final
 * @property int $prioridade Nivel de prioridade da campanha
 * @property string $por_parcela
 * @property string $por_portal
 * @property string $tipo
 *
 * @property Carteira $carteira
 * @property CarteiraCalculo[] $carteiraCalculos
 */
class CarteiraCampanha extends \yii\db\ActiveRecord
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
        return 'carteira_campanha';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_carteira', 'nome', 'vigencia_inicial'], 'required'],
            [['id_carteira', 'prioridade'], 'integer'],
            [['vigencia_inicial', 'vigencia_final'], 'safe'],
            [['por_parcela', 'por_portal'], 'string'],
            [['nome'], 'string', 'max' => 250],
            [['id_carteira'], 'exist', 'skipOnError' => true, 'targetClass' => Carteira::className(), 'targetAttribute' => ['id_carteira' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_carteira' => 'Id Carteira',
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
    public function getCarteira()
    {
        return $this->hasOne(Carteira::className(), ['id' => 'id_carteira']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCarteiraCalculos()
    {
        return $this->hasMany(Carteira::className(), ['id_campanha' => 'id']);
    }
    
    /**
     * @inheritDoc
     * @see \yii\db\BaseActiveRecord::beforeDelete()
     */
    public function beforeDelete() 
    {
        // deleta todas as faixas de cálculo da campanha
        CarteiraCalculo::deleteAll(['id_campanha' => $this->id]);
        
        return parent::beforeDelete(); 
    }
    
    /**
     * @inheritDoc
     * @see \yii\db\BaseActiveRecord::beforeSave()
     */
    public function beforeSave($insert) 
    {
        // formata a data para salvar
        $this->vigencia_inicial = Helper::dateUnmask($this->vigencia_inicial, Helper::DATE_DEFAULT);
        $this->vigencia_final = Helper::dateUnmask($this->vigencia_final, Helper::DATE_DEFAULT);
        
        return parent::beforeSave($insert);    
    }
    
    /**
     * @inheritDoc
     * @see \yii\db\BaseActiveRecord::afterFind()
     */
    public function afterFind()
    {
        // formata a data para ser exibida
        $this->vigencia_inicial = Helper::dateMask($this->vigencia_inicial, Helper::DATE_DEFAULT);
        $this->vigencia_final = Helper::dateMask($this->vigencia_final, Helper::DATE_DEFAULT);
    }
}
