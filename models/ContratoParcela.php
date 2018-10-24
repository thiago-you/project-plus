<?php

namespace app\models;

use Yii;
use app\base\Helper;

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
    // const para a flag de status
    CONST SEM_NEGOCIACAO = 1;
    CONST EM_NEGOCIACAO = 2;
    
    /**
     * Valores da parcela
     */
    public $multa = 0;
    public $juros = 0;
    public $honorarios = 0;
    public $total = 0;
    
    /**
     * Salva a porcentagem de calculo dos honorarios
     */
    public $honorariosCalculo;
    
    /**
     * @var $atraso int => atraso da parcela em dias
     */
    public $atraso;
    
    /**
     * Guarda a faixa de calculo da parcela
     */
    public $faixaCalculo;
    
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
        
        // formata a data para salvar
        $this->data_vencimento = Helper::dateUnmask($this->data_vencimento, Helper::DATE_DEFAULT);
        
        return parent::beforeSave($insert);
    }
    
    /**
     * @inheritDoc
     * @see \yii\db\BaseActiveRecord::afterFind()
     */
    public function afterFind()
    {
        // formata a data para ser exibida
        $this->data_vencimento = Helper::dateMask($this->data_vencimento, Helper::DATE_DEFAULT);
    }
    
    /**
     * Retorna o numero de dias em atraso apartir da data de vencimento
     */
    public function getAtraso() 
    {
        // verifica se o atras ja foi calculado
        if (!$this->atraso) {            
            $dataAtual = time();
            $vencimento = strtotime(Helper::dateUnmask($this->data_vencimento, Helper::DATE_DEFAULT));
            $diferenca = $dataAtual - $vencimento;

            // calcula o atraso e
            // não considera o dia do vencimento
            $this->atraso = round($diferenca / (60 * 60 * 24)) - 1;
            
            // valida o atraso negativo
            if ($this->atraso < 0) {
                $this->atraso = 0;
            }
        }
        
        return $this->atraso;
    }
    
    /**
     * Retorna a descricao do tipo
     */
    public function getStatusDescricao() 
    {
        switch ($this->status) {
            case self::SEM_NEGOCIACAO:
                return '<span class="label label-warning">Sem Neg.</span>';
                break;
            case self::EM_NEGOCIACAO:
                return '<span class="label label-info">Em Neg.</span>';
                break;
        }
    }
    
    /**
     * Calcula os valores da parcela
     */
    public function calcularValores($id_campanha)
    {
        // busca a faixa de calculo
        if ($this->faixaCalculo = CredorCalculo::findFaixa($id_campanha, $this->getAtraso())) {            
            $this->multa = floor(($this->valor * ($this->faixaCalculo->multa / 100)) * 100) / 100;
            $this->juros = floor(($this->valor * (($this->faixaCalculo->juros / 30 * $this->getAtraso()) / 100)) * 100) / 100;
            $this->honorarios = floor((($this->valor + $this->juros + $this->multa) * ($this->faixaCalculo->honorario / 100)) * 100) / 100;
            $this->total = $this->valor + $this->multa + $this->juros + $this->honorarios;
            
            // seta a taxa de calculo dos honorarios
            $this->honorariosCalculo = $this->faixaCalculo->honorario;
        }
    }
}



