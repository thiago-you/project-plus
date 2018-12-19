<?php
namespace app\controllers;

use Yii;
use app\base\Helper;
use yii\helpers\Json;
use yii\web\Controller;
use app\models\Carteira;
use app\base\AjaxResponse;
use yii\filters\VerbFilter;
use app\models\Colaborador;
use app\models\CarteiraSearch;
use yii\web\NotFoundHttpException;
use yii\db\IntegrityException;

/**
 * CarteiraController implements the CRUD actions for Carteira model.
 */
class CarteiraController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
            ],
        ];
    }

    /**
     * Valida a permissão do usuário com base no cargo
     *
     * @inheritDoc
     * @see \yii\web\Controller::beforeAction()
     */
    public function beforeAction($action)
    {
        if (\Yii::$app->user->identity->cargo != Colaborador::CARGO_ADMINISTRADOR) {
            throw new NotFoundHttpException();
        }
        
        return parent::beforeAction($action);
    }
    
    /**
     * Lists all Carteira models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CarteiraSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Carteira model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Carteira();

        if ($post = \Yii::$app->request->post()) {
            try {
                $transaction = \Yii::$app->db->beginTransaction();
                
                // seta os dados da model
                $model->load($post);
                                
                // salva a model
                if (!$model->save()) {
                    throw new \Exception(Helper::renderErrors($model->getErrors()));
                }
                
                $transaction->commit();
                \Yii::$app->session->setFlash('success', '<i class="fa fa-check"></i>&nbsp; A carteira foi alterado com sucesso.');
                return $this->redirect(['configuracao', 'id' => $model->id]);
            } catch (\Exception $e) {
                $transaction->rollBack();
                \Yii::$app->session->setFlash('danger', "<i class='fa fa-exclamation-triangle'></i>&nbsp; Erros: {$e->getMessage()}");
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Carteira model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($post = \Yii::$app->request->post()) {
            try {
                $transaction = \Yii::$app->db->beginTransaction();
                
                // seta os dados da model
                $model->load($post);
                
                // salva a model
                if (!$model->save()) {
                    throw new \Exception(Helper::renderErrors($model->getErrors()));                    
                }

                $transaction->commit();
                \Yii::$app->session->setFlash('success', '<i class="fa fa-check"></i>&nbsp; A carteira foi alterado com sucesso.');
                return $this->redirect(['index']);
            } catch (\Exception $e) {
                $transaction->rollBack();
                \Yii::$app->session->setFlash('danger', "<i class='fa fa-exclamation-triangle'></i>&nbsp; Erros: {$e->getMessage()}");
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Configuração de campanha e cálculo da carteira
     */
    public function actionConfiguracao($id)
    {
        $model = $this->findModel($id);

        return $this->render('configuracao', [
            'model' => $model,
        ]);
    }
    
    /**
     * Atualiza a campanha da carteira
     */
    public function actionUpdateCampanha()
    {
        // valida a requisição
        if (!$post = \Yii::$app->request->post()) {
            throw new NotFoundHttpException();
        }
        
        // busca a carteira
        $model = $this->findModel($post['id_carteira']);
        
        try {
            // cria o retorno e carrega os dados da campanha
            $retorno = new AjaxResponse();
            
            $model->id_campanha = $post['id_campanha'];
            if (!$model->save()) {
                throw new \Exception();
            }
        } catch(\Exception $e) {
            $retorno->success = false;
        }
        
        return Json::encode($retorno);
    }
    
    /**
     * Deletes an existing Carteira model.
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
            
            // deleta o registro
            $model->delete();
            
            $transaction->commit();
            \Yii::$app->session->setFlash('success', '<i class="fa fa-check"></i>&nbsp; A carteira foi excluído com sucesso.');
        } catch (IntegrityException $e) {
            $transaction->rollBack();
            \Yii::$app->session->setFlash('danger', '<i class="fa fa-exclamation-triangle"></i>&nbsp; A carteira não pode ser deletada pois possui dados vinculados.');
        } catch (\Exception $e) {
            $transaction->rollBack();
            \Yii::$app->session->setFlash('danger', "<i class='fa fa-exclamation-triangle'></i>&nbsp; Erros: {$e->getMessage()}");
        }
        
        return $this->redirect(['index']);
    }

    /**
     * Finds the Carteira model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Carteira the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Carteira::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException();
    }
}
