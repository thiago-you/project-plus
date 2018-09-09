<?php
namespace app\controllers;

use app\base\Util;
use yii\web\Controller;
use app\models\Contrato;
use yii\filters\VerbFilter;
use app\models\ContratoSearch;
use app\models\ContratoParcela;
use yii\web\NotFoundHttpException;

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
    public function actionIndex()
    {
        $searchModel = new ContratoSearch();
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
        
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
            'modelImport' => $modelImport
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
    public function actionCreate()
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
                    throw new \Exception(Util::renderErrors($model->getErrors()));
                }
                
                // salva as parcelas cadastrados
                if (isset($post['Parcela']) && is_array($post['Parcela'])) {
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
                                throw new \Exception(Util::renderErrors($modelParcela->getErrors()));
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
                    throw new \Exception(Util::renderErrors($model->getErrors()));
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
                                throw new \Exception(Util::renderErrors($modelParcela->getErrors()));
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
