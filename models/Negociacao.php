<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "negociacao".
 *
 * @property int $id
 * @property int $id_contrato
 * @property int $id_credor
 * @property int $id_campanha
 * @property string $data_negociacao
 * @property string $data_cadastro
 * @property string $desconto_encargos
 * @property string $desconto_principal
 * @property string $desconto_honorarios
 * @property string $desconto_total
 * @property string $subtotal
 * @property string $desconto
 * @property string $receita
 * @property string $total
 * @property string $tipo Flag que valida se a negociacao é a vista ou parcelado
 * 
 * @property Contrato $contrato
 */
class Negociacao extends \yii\db\ActiveRecord
{
    // const para o tipo da negociacao
    CONST A_VISTA = 'V';
    CONST PARCELADO = 'P';
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'negociacao';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['data_negociacao', 'data_cadastro'], 'required'],
            [['id_contrato', 'id_credor', 'id_campanha'], 'integer'],
            [[
                'subtotal', 'desconto', 'receita', 'total', 'desconto_encargos',
                'desconto_principal', 'desconto_honorarios', 'desconto_total'           
            ], 'number'],
            [['data_negociacao', 'data_cadastro', 'tipo'], 'safe'],
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
            'id_credor' => 'Id Credor',
            'id_campanha' => 'Id Campanha',
            'data_negociacao' => 'Data Negociacao',
            'data_cadastro' => 'Data Cadastro',
            'subtotal' => 'Subtotal',
            'desconto' => 'Desconto',
            'receita' => 'Receita',
            'total' => 'Total',
            'desconto_encargos' => 'Desconto dos Encargos',
            'desconto_principal' => 'Desconto Principal',
            'desconto_honorarios' => 'Desconto dos Honorários',
            'desconto_total' => 'Desconto Total',
            'tipo' => 'Tipo de Pagamento',
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
     * Calcula os valores da negociacao
     */
    public function calcularValores($contrato = null)
    {
        // busca o contrato
        if (!$contrato) {
            $contrato = Contrato::findOne(['id' => $this->id_contrato]);
        }
        
        // busca as parcelas do contrato
        if (!empty($contrato->contratoParcelas) && is_array($contrato->contratoParcelas)) {
            $this->subtotal = 0;
            $this->receita = 0;
            foreach ($contrato->contratoParcelas as $parcela) {                
                // busca a faixa de calculo
                // e calcula o subtotal e a receita
                if ($faixaCalculo = CredorCalculo::findFaixa($this->id_campanha, $parcela->getAtraso())) {
                    // calcula os valores da parcela
                    $multa = $parcela->valor * ($faixaCalculo->multa / 100);
                    $juros = $parcela->valor * ($faixaCalculo->juros / 100);
                    $honorarios = ($parcela->valor + $multa + $juros) * ($faixaCalculo->honorario / 100);
                    
                    $this->subtotal += $parcela->valor + $multa + $juros + $honorarios;
                    $this->receita += $honorarios;
                }
            }
            
            // seta o valor total
            $this->total = $this->subtotal;
        }        
    }
}




