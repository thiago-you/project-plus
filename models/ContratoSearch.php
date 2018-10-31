<?php
namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\base\Helper;

/**
 * ContratoSearch represents the model behind the search form of `app\models\Contrato`.
 */
class ContratoSearch extends Contrato
{
    /**
     * @var string => atributos do cliente
     */
    public $nome;
    public $telefone;
    public $documento;
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'id_cliente', 'tipo', 'id_carteira'], 'integer'],
            [[
                'codigo_cliente', 'codigo_contrato', 'num_contrato', 'num_plano', 
                'data_cadastro', 'data_vencimento', 'regiao', 'filial', 'observacao',
                'nome', 'documento', 'telefone',
            ], 'safe'],
            [['valor'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Contrato::find()
        ->alias('con')
        ->innerJoin('cliente cli', 'con.id_cliente = cli.id');

        // add conditions that should always apply here
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // remove a mascara antes de pesquisar
        $this->documento = $this->documento ? Helper::unmask($this->documento, true) : null;
        $this->telefone = $this->telefone ? Helper::unmask($this->telefone, true) : null;
        
        // grid filtering conditions
        $query->andFilterWhere([
            'con.id' => $this->id,
            'con.id_cliente' => $this->id_cliente,
            'con.id_carteira' => $this->id_carteira,
            'con.valor' => $this->valor,
            'con.data_cadastro' => $this->data_cadastro,
            'con.data_vencimento' => $this->data_vencimento,
            'con.tipo' => $this->tipo,
        ]);

        $query->andFilterWhere(['like', 'codigo_cliente', $this->codigo_cliente])
            ->andFilterWhere(['like', 'codigo_contrato', $this->codigo_contrato])
            ->andFilterWhere(['like', 'num_contrato', $this->num_contrato])
            ->andFilterWhere(['like', 'num_plano', $this->num_plano])
            ->andFilterWhere(['like', 'regiao', $this->regiao])
            ->andFilterWhere(['like', 'filial', $this->filial])
            ->andFilterWhere(['like', 'observacao', $this->observacao])
            ->andFilterWhere(['like', 'cli.nome', $this->nome])
            ->andFilterWhere(['like', 'cli.documento', $this->documento]);

        // filtra pela telefone
        if ($this->telefone) {
            $query->innerJoin('telefone tel', 'tel.id_cliente = cli.id')
            ->andWhere(['tel.numero' => $this->telefone]);
        }
            
        return $dataProvider;
    }
}
