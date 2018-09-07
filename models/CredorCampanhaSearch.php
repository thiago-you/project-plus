<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\CredorCampanha;

/**
 * CredorCampanhaSearch represents the model behind the search form of `app\models\CredorCampanha`.
 */
class CredorCampanhaSearch extends CredorCampanha
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'id_credor', 'prioridade'], 'integer'],
            [['nome', 'vigencia_inicial', 'vigencia_final', 'por_parcela', 'por_portal'], 'safe'],
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
        $query = CredorCampanha::find();

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
            'id_credor' => $this->id_credor,
            'vigencia_inicial' => $this->vigencia_inicial,
            'vigencia_final' => $this->vigencia_final,
            'prioridade' => $this->prioridade,
        ]);

        $query->andFilterWhere(['like', 'nome', $this->nome])
            ->andFilterWhere(['like', 'por_parcela', $this->por_parcela])
            ->andFilterWhere(['like', 'por_portal', $this->por_portal]);

        return $dataProvider;
    }
}
