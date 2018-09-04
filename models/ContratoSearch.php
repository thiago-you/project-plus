<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Contrato;

/**
 * ContratoSearch represents the model behind the search form of `app\models\Contrato`.
 */
class ContratoSearch extends Contrato
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'id_cliente', 'tipo'], 'integer'],
            [['codigo_cliente', 'codigo_contrato', 'num_contrato', 'num_plano', 'data_cadastro', 'data_vencimento', 'regiao', 'filial', 'observacao'], 'safe'],
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
        $query = Contrato::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'id_cliente' => $this->id_cliente,
            'valor' => $this->valor,
            'data_cadastro' => $this->data_cadastro,
            'data_vencimento' => $this->data_vencimento,
            'tipo' => $this->tipo,
        ]);

        $query->andFilterWhere(['like', 'codigo_cliente', $this->codigo_cliente])
            ->andFilterWhere(['like', 'codigo_contrato', $this->codigo_contrato])
            ->andFilterWhere(['like', 'num_contrato', $this->num_contrato])
            ->andFilterWhere(['like', 'num_plano', $this->num_plano])
            ->andFilterWhere(['like', 'regiao', $this->regiao])
            ->andFilterWhere(['like', 'filial', $this->filial])
            ->andFilterWhere(['like', 'observacao', $this->observacao]);

        return $dataProvider;
    }
}
