<?php
namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ClienteSearch represents the model behind the search form of `app\models\Cliente`.
 */
class ClienteSearch extends Cliente
{
	/**
	 * @var
	 */
	public $telefone;
	
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'estado_civil'], 'integer'],
            [[
            	'nome', 'nome_social', 'rg', 'documento', 'inscricao_estadual', 'sexo', 'data_nascimento', 
            	'data_cadastro', 'nome_conjuge', 'nome_pai', 'nome_mae', 'empresa', 'profissao', 'ativo', 'tipo',
            	'telefone',
            ], 'safe'],
            [['salario'], 'number'],
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
        $query = Cliente::find();

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
            'data_nascimento' => $this->data_nascimento,
            'data_cadastro' => $this->data_cadastro,
            'estado_civil' => $this->estado_civil,
            'salario' => $this->salario,
        ]);

        $query->andFilterWhere(['like', 'nome', $this->nome])
            ->andFilterWhere(['like', 'nome_social', $this->nome_social])
            ->andFilterWhere(['like', 'rg', $this->rg])
            ->andFilterWhere(['like', 'documento', $this->documento])
            ->andFilterWhere(['like', 'inscricao_estadual', $this->inscricao_estadual])
            ->andFilterWhere(['like', 'sexo', $this->sexo])
            ->andFilterWhere(['like', 'nome_conjuge', $this->nome_conjuge])
            ->andFilterWhere(['like', 'nome_pai', $this->nome_pai])
            ->andFilterWhere(['like', 'nome_mae', $this->nome_mae])
            ->andFilterWhere(['like', 'empresa', $this->empresa])
            ->andFilterWhere(['like', 'profissao', $this->profissao])
            ->andFilterWhere(['like', 'ativo', $this->ativo])
            ->andFilterWhere(['like', 'tipo', $this->tipo]);

        return $dataProvider;
    }
}
