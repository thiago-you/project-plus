<?php
namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\base\Helper;

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
        $query = Cliente::find()->alias('cli');

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
            'cli.id' => $this->id,
            'cli.data_nascimento' => $this->data_nascimento,
            'data_cadastro' => $this->data_cadastro,
            'cli.estado_civil' => $this->estado_civil,
            'cli.salario' => $this->salario,
        ]);

        // remove a mascara antes de pesquisar
        $this->documento = $this->documento ? Helper::unmask($this->documento, true) : null;
        $this->telefone = $this->telefone ? Helper::unmask($this->telefone, true) : null;
        
        $query->andFilterWhere(['like', 'cli.nome', $this->nome])
            ->andFilterWhere(['like', 'cli.nome_social', $this->nome_social])
            ->andFilterWhere(['like', 'cli.rg', $this->rg])
            ->andFilterWhere(['like', 'cli.documento', $this->documento])
            ->andFilterWhere(['like', 'cli.inscricao_estadual', $this->inscricao_estadual])
            ->andFilterWhere(['like', 'cli.sexo', $this->sexo])
            ->andFilterWhere(['like', 'cli.nome_conjuge', $this->nome_conjuge])
            ->andFilterWhere(['like', 'cli.nome_pai', $this->nome_pai])
            ->andFilterWhere(['like', 'cli.nome_mae', $this->nome_mae])
            ->andFilterWhere(['like', 'cli.empresa', $this->empresa])
            ->andFilterWhere(['like', 'cli.profissao', $this->profissao])
            ->andFilterWhere(['like', 'cli.ativo', $this->ativo])
            ->andFilterWhere(['like', 'cli.tipo', $this->tipo]);
        
        // filtra pela telefone
        if ($this->telefone) {
            $query->innerJoin('telefone tel', 'tel.id_cliente = cli.id')
            ->andWhere(['tel.numero' => $this->telefone]);            
        }

        return $dataProvider;
    }
}
