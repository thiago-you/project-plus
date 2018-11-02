<?php
namespace app\controllers;

use Yii;
use app\base\Helper;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\Colaborador;
use app\models\ColaboradorSearch;
use yii\web\NotFoundHttpException;

/**
 * ColaboradorController implements the CRUD actions for Colaborador model.
 */
class ColaboradorController extends Controller
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
     * Valida a permissão do usuário com base no cargo
     *
     * @inheritDoc
     * @see \yii\web\Controller::beforeAction()
     */
    public function beforeAction($action)
    {
        if ($this->action->id != 'index' && $this->action->id != 'update') {
            if (\Yii::$app->user->identity->cargo != Colaborador::CARGO_ADMINISTRADOR) {
                throw new NotFoundHttpException();
            }
        }
        
        return parent::beforeAction($action);
    }
    
    /**
     * Lists all Colaborador models.
     * @return mixed
     */
    public function actionIndex()
    {
        // valida o usuario operador
        if (\Yii::$app->user->identity->cargo == Colaborador::CARGO_OPERADOR) {
            return $this->redirect(['update', 'id' => \Yii::$app->user->identity->id]);
        }
        
        $searchModel = new ColaboradorSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Colaborador model.
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
     * Creates a new Colaborador model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Colaborador();

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
                \Yii::$app->session->setFlash('success', '<i class="fa fa-check"></i>&nbsp; O colaborador foi cadastrado com sucesso.');
                return $this->redirect(['index']);
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
     * Updates an existing Colaborador model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        // valida o usuário admin
        if ($id == 0) {
            \Yii::$app->session->setFlash('warning', 'O usuário "Admin" não pode ser alterado.');
            return $this->redirect(['site/index']);
        }
        
        // busca a model
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
                \Yii::$app->session->setFlash('success', '<i class="fa fa-check"></i>&nbsp; O colaborador foi alterado com sucesso.');
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
     * Deletes an existing Colaborador model.
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
            \Yii::$app->session->setFlash('success', '<i class="fa fa-check"></i>&nbsp; O colaborador foi excluído com sucesso.');
        } catch (\Exception $e) {
            $transaction->rollBack();
            \Yii::$app->session->setFlash('danger', "<i class='fa fa-exclamation-triangle'></i>&nbsp; Erros: {$e->getMessage()}");
        }
        
        return $this->redirect(['index']);
    }

    /**
     * Finds the Colaborador model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Colaborador the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Colaborador::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException();
    }
}
