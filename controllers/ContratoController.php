<?php
namespace app\controllers;

use app\base\Helper;
use yii\web\Controller;
use app\models\Contrato;
use yii\filters\VerbFilter;
use app\models\ContratoSearch;
use app\models\ContratoParcela;
use yii\web\NotFoundHttpException;
use app\models\Cliente;

/**
 * ContratoController implements the CRUD actions for Contrato model.
 */
class ContratoController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Contrato models.
     * @return mixed
     */
    public function actionIndex($index = '', $value = '')
    {
        $searchModel = new ContratoSearch();
        
        // seta os params do filtro
        if (!$params = \Yii::$app->request->post()) {
            $params = \Yii::$app->request->queryParams;
        }
        
        // seta o nome da pesquisa rápida
        if (!empty($index) && !empty($value)) {
            $params['ContratoSearch'][$index] = $value;
        }
        
        // realiza o filtro
        $dataProvider = $searchModel->search($params);
        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Contrato model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Contrato model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id_cliente = null)
    {
        $model = new Contrato();

        // seta os dados e salva a model
        if ($post = \Yii::$app->request->post()) {
            try {
                $transaction = \Yii::$app->db->beginTransaction();
                
                // carrega os dados da model
                $model->load($post);
                
                // salva a model
                if (!$model->save()) {
                    throw new \Exception(Helper::renderErrors($model->getErrors()));
                }
                
                // salva as parcelas cadastrados
                if (isset($post['Parcela']) && is_array($post['Parcela'])) {
                    foreach ($post['Parcela'] as $key => $parcela) {
                        if (isset($parcela['vencimento']) && !empty($parcela['vencimento']) &&
                            isset($parcela['valor']) && !empty($parcela['valor'])
                        ) {
                            // cria a model de parcela e
                            // seta os dados
                            $modelParcela = new ContratoParcela();
                            $modelParcela->id_contrato = $model->id ? $model->id : $model->getPrimaryKey();
                            $modelParcela->num_parcela = ++$key;
                            $modelParcela->data_vencimento = $parcela['vencimento'];
                            $modelParcela->valor = $parcela['valor'];
                            
                            // salva o telefone
                            if (!$modelParcela->save()) {
                                throw new \Exception(Helper::renderErrors($modelParcela->getErrors()));
                            }
                        }
                    }
                }
                
                $transaction->commit();
                \Yii::$app->session->setFlash('success', '<i class="fa fa-check"></i>&nbsp; O contrato foi cadastrado com sucesso.');
                return $this->redirect(['index']);
            } catch(\Exception $e) {
                $transaction->rollBack();
                \Yii::$app->session->setFlash('danger', "<i class='fa fa-exclamation-triangle'></i>&nbsp; Erros: {$e->getMessage()}");
            }
        }

        // seta o cliente se foi enviado algum id
        $model->id_cliente = $id_cliente;
        
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Contrato model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        // seta os dados e salva a model
        if ($post = \Yii::$app->request->post()) {
            try {
                $transaction = \Yii::$app->db->beginTransaction();
                
                // carrega os dados da model
                $model->load($post);
                
                // salva a model
                if (!$model->save()) {
                    throw new \Exception(Helper::renderErrors($model->getErrors()));
                }
                
                // salva as parcelas cadastrados
                if (isset($post['Parcela']) && is_array($post['Parcela'])) {
                    // deleta todos as parcelas do contrato
                    ContratoParcela::deleteAll();
                    
                    // cadastra/recadastra as parcelas
                    foreach ($post['Parcela'] as $parcela) {
                        if (isset($parcela['vencimento']) && !empty($parcela['vencimento']) &&
                            isset($parcela['valor']) && !empty($parcela['valor'])
                        ) {
                            // cria a model de parcela e
                            // seta os dados
                            $modelParcela = new ContratoParcela();
                            $modelParcela->id_contrato = $model->id ? $model->id : $model->getPrimaryKey();
                            $modelParcela->data_vencimento = $parcela['vencimento'];
                            $modelParcela->valor = $parcela['valor'];
                            
                            // salva o telefone
                            if (!$modelParcela->save()) {
                                throw new \Exception(Helper::renderErrors($modelParcela->getErrors()));
                            }
                        }
                    }
                }
                
                $transaction->commit();
                \Yii::$app->session->setFlash('success', '<i class="fa fa-check"></i>&nbsp; O contrato foi cadastrado com sucesso.');
                return $this->redirect(['index']);
            } catch(\Exception $e) {
                $transaction->rollBack();
                \Yii::$app->session->setFlash('danger', "<i class='fa fa-exclamation-triangle'></i>&nbsp; Erros: {$e->getMessage()}");
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }
    
    /**
     * Negociacao dos contratos do cliente
     */
    public function actionNegociacao() 
    {
        return $this->render('negociacao', [
            
        ]);
    }
     
    /**
     * Deletes an existing Contrato model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        // busca a model
        $model = $this->findModel($id);
        
        try {
            $transaction = \Yii::$app->db->beginTransaction();
            
            // deleta o contrato
            $model->delete();
            
            $transaction->commit();
            \Yii::$app->session->setFlash('success', '<i class="fa fa-check"></i>&nbsp; O contrato foi excluído com sucesso.');
        } catch (\Exception $e) {
            $transaction->rollBack();
            \Yii::$app->session->setFlash('danger', "<i class='fa fa-exclamation-triangle'></i>&nbsp; Erros: {$e->getMessage()}");
        }

        return $this->redirect(['index']);
    }

    /**
     * Realiza a pesquisa rápida de um ou mais contratos
     */
    public function actionQuickSearch($value = null, $strict = null)
    {
        // monta o where de busca
        $where = [
            'nome' => ['like', 'cli.nome', $value],
            'documento' => ['like', 'cli.documento', $value]
        ];
        
        // valida o modo de busca (like ou stritamente o mesmo)
        if ($strict) {
            $where = [
                'nome' => ['cli.nome' => $value],
                'documento' => ['cli.documento' => $value]
            ];
        }
        
        // busca todos os clientes com o nome passado
        // se nao achou nenhum cliente pelo nome, tenta pesquisar pelo cpf/cnpj
        $index = 'nome';
        if (!$contrato = Contrato::find()->alias('con')->innerJoin('cliente cli', 'cli.id = con.id_cliente')->where($where['nome'])->all()) {
            // remove a mascara
            $value = Helper::unmask($value, true);
            
            // busca pelo busca pelo cpf/cnpj
            $contrato = Contrato::find()->alias('con')->innerJoin('cliente cli', 'cli.id = con.id_cliente')->where($where['documento'])->all();
            $index = 'documento';
        }
        
        // se achou apenas um contrato, redireciona para a página do contrato
        // se houver mais contatos com este nome
        // redireciona para a página de listagem de contratos
        if (!empty($value) && count($contrato) == 1) {
            return $this->redirect(['update', 'id' => $contrato[0]->id]);
        }
        
        return $this->redirect(['index', 'index' => $index, 'value' => $value]);
    }
    
    /**
     * Busca um cliente por ajax typeahead
     */
    public function actionSearchList(array $q)
    {
        // valida a requisição
        if (!\Yii::$app->request->isAjax) {
            throw new NotFoundHttpException();
        }
        
        $data = [];
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $query = Cliente::find();
        
        if (isset($q['nome'])) { $query->select('nome')->andWhere(['like', 'nome', $q['nome']])->distinct(true); }
        if (isset($q['telefone'])) { $query->select('telefone')->andWhere(['like', 'telefone', $q['telefone']])->distinct(true); }
        if (isset($q['documento'])) { $query->select('documento')->andWhere(['like', 'documento', $q['documento']])->distinct(true); }
        
        // params da busca rapida
        if (isset($q['quick'])) {
            // se enviou um numero pesquisa pelo documento
            // senao, busca pelo nome
            if (is_numeric($q['quick'])) {
                $query->select('documento')->andWhere(['like', 'documento', $q['quick']])->distinct(true);
            } else {
                $query->select('nome')->andWhere(['like', 'nome', $q['quick']])->distinct(true);
            }
        }

        $model = $query->all();

        if ($model != null) {
            foreach ($model as $key) {
                if (isset($q['nome'])) { $data[]['value'] = $key['nome']; }
                if (isset($q['telefone'])) { $data[]['value'] = $key['telefone']; }
                if (isset($q['documento'])) {
                    $documento = $key['documento'];
                    
                    // verifica se é um cpf ou cnpj, se ssim formata o documento
                    if (strlen($documento) == 11) {
                        $documento = Helper::mask($documento, Helper::MASK_CPF);
                    } elseif (strlen($documento) == 14) {
                        $documento = Helper::mask($documento, Helper::MASK_CNPJ);
                    }
                    
                    $data[]['value'] = $documento; 
                }
                if (isset($q['quick'])) {
                    if (isset($key['documento'])) {
                        $documento = $key['documento'];
                        
                        // verifica se é um cpf ou cnpj, se ssim formata o documento
                        if (strlen($documento) == 11) {
                            $documento = Helper::mask($documento, Helper::MASK_CPF);
                        } elseif (strlen($documento) == 14) {
                            $documento = Helper::mask($documento, Helper::MASK_CNPJ);
                        }
                        
                        $data[]['value'] = $documento;
                    } else {
                        $data[]['value'] = $key['nome'];
                    }
                }
            }
        }
        
        return $data;
    }
    
    /**
     * Finds the Contrato model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Contrato the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Contrato::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException();
    }
}
