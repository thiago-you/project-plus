<?php
namespace app\models;

use Yii;
use app\base\Helper;

/**
 * This is the model class for table "contrato".
 *
 * @property int    $id
 * @property int    $id_cliente
 * @property int    $id_credor
 * @property string $codigo_cliente
 * @property string $codigo_contrato
 * @property string $num_contrato
 * @property string $num_plano
 * @property string $valor
 * @property string $data_cadastro
 * @property string $data_vencimento
 * @property string $data_negociacao
 * @property int    $tipo
 * @property string $regiao
 * @property string $filial
 * @property string $observacao
 * @property int    $situacao
 *
 * @property Cliente $cliente
 * @property Credor $credor
 * @property ContratoParcela[] $contratoParcelas
 * @property Negociacao $negociacao
 */
class Contrato extends \yii\db\ActiveRecord
{
    // consts para a situacao
    CONST SIT_EM_ANDAMENTO = '1';
    CONST SIT_FECADO = '2';
    
    /**
     * @var string
     */
    public $atraso;
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'contrato';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_cliente',], 'required'],
            [['id_cliente', 'id_credor', 'tipo', 'situacao'], 'integer'],
            [['valor'], 'number'],
            [['data_cadastro', 'data_vencimento', 'data_negociacao'], 'safe'],
            [['codigo_cliente', 'codigo_contrato', 'num_contrato', 'num_plano', 'regiao', 'filial'], 'string', 'max' => 50],
            [['observacao'], 'string', 'max' => 250],
            [['id_cliente'], 'exist', 'skipOnError' => true, 'targetClass' => Cliente::className(), 'targetAttribute' => ['id_cliente' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Cód.',
            'id_cliente' => 'Cliente',
            'id_credor' => 'Credor',
            'codigo_cliente' => 'Código do Cliente',
            'codigo_contrato' => 'Código do Contrato',
            'num_contrato' => 'N° Contrato',
            'num_plano' => 'N° Plano',
            'valor' => 'Valor',
            'data_cadastro' => 'Data do Contrato',
            'data_vencimento' => 'Data de Expiração',
            'data_negociacao' => 'Data de Negociação',
            'tipo' => 'Tipo',
            'regiao' => 'Região',
            'filial' => 'Filial',
            'observacao' => 'Observação',
            'situacao' => 'Situação',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCliente()
    {
        return $this->hasOne(Cliente::className(), ['id' => 'id_cliente']);
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
    public function getContratoParcelas()
    {
        return $this->hasMany(ContratoParcela::className(), ['id_contrato' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNegociacao()
    {
        return $this->hasOne(Negociacao::className(), ['id_contrato' => 'id']);
    }
    
    /**
     * Retorna a lista de tipos do contrato
     */
    public static function getListaTipos() 
    {
        return [
            '1' => 'Boleto / Mensalidade',
            '2' => 'Capital de Giro Fisica',
            '3' => 'Capital de Giro Juridica',
            '4' => 'Cheque',
            '5' => 'Curso Profissionalizante',
            '6' => 'Depósito Indevido',
            '7' => 'Desc. Cheque-Fisica',
            '8' => 'Desc. Cheque-Juridica',
            '9' => 'Desconto P. Juridica',
            '10' => 'Ensino',
            '11' => 'Espanhol',
            '12' => 'Inglês',
            '13' => 'Material Didadíco',
            '14' => 'Mensalidade',
            '15' => 'Nota Fiscal',
            '16' => 'Nota Promissória',
        ];
    }
    
    /**
     * @inheritDoc
     * @see \yii\db\BaseActiveRecord::beforeSave()
     */
    public function beforeSave($insert) 
    {
        // seta a data de cadastro
        if (empty($this->data_cadastro)) {
            $this->data_cadastro = date('Y-m-d H:i:s');
        }
        
        return parent::beforeSave($insert);        
    }
    
    /**
     * @inheritDoc
     * @see \yii\db\BaseActiveRecord::beforeDelete()
     */
    public function beforeDelete() 
    {
        // deleta todas as parcelas do contrato
        ContratoParcela::deleteAll();
        
        return parent::beforeDelete();
    }

    /**
     * Retorna um tipo pelo nome
     */
    public static function getTipoByName($name) 
    {
        $listaTipos = self::getListaTipos(); 
        $tipo = array_search(ucfirst(strtolower($name)), $listaTipos);
        
        return $tipo ? $tipo : 1;
    }
    
    /**
     * Calcula o valor total do contrato
     */
    public function getValorTotal() 
    {
        $total = 0;
        foreach ($this->contratoParcelas as $parcela) {
            // seta o valor total
            $total += $parcela->valor;
            
            // busca a faixa e calcula os encargos
            if ($faixaCalculo = CredorCalculo::findFaixa($this->credor->id_campanha, $parcela->getAtraso())) {                
                $total += $parcela->valor * ($faixaCalculo->multa / 100);
                $total += $parcela->valor * ($faixaCalculo->juros / 100);
                $total += $parcela->valor * ($faixaCalculo->honorario / 100);
            }
        }
        
        return $total;
    }
}
