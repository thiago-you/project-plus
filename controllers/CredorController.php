<?php
namespace app\controllers;

use Yii;
use app\models\Credor;
use app\models\CredorSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\base\Util;

/**
 * CredorController implements the CRUD actions for Credor model.
 */
class CredorController extends Controller
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
     * Lists all Credor models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CredorSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Credor model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Credor();

        if ($post = \Yii::$app->request->post()) {
            try {
                $transaction = \Yii::$app->db->beginTransaction();
                
                // seta os dados da model
                $model->load($post);
                                
                // salva a model
                if (!$model->save()) {
                    throw new \Exception(Util::renderErrors($model->getErrors()));
                }
                
                $transaction->commit();
                \Yii::$app->session->setFlash('success', '<i class="fa fa-check"></i>&nbsp; O credor foi alterado com sucesso.');
                return $this->redirect(['configuracao', 'id' => $model->id]);
            } catch (\Exception $e) {
                $transaction->rollBack();
                \Yii::$app->session->setFlash('danger', "<i class='fa fa-exclamation-triangle'></i>&nbsp; {$e->getMessage()}");
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Credor model.
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
                    throw new \Exception(Util::renderErrors($model->getErrors()));                    
                }

                $transaction->commit();
                \Yii::$app->session->setFlash('success', '<i class="fa fa-check"></i>&nbsp; O credor foi alterado com sucesso.');
                return $this->redirect(['index']);
            } catch (\Exception $e) {
                $transaction->rollBack();
                \Yii::$app->session->setFlash('danger', "<i class='fa fa-exclamation-triangle'></i>&nbsp; {$e->getMessage()}");
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Credor model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionConfiguracao($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }
        
        return $this->render('configuracao', [
            'model' => $model,
        ]);
    }
    
    /**
     * Deletes an existing Credor model.
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
            \Yii::$app->session->setFlash('success', '<i class="fa fa-check"></i>&nbsp; O credor foi excluído com sucesso.');
        } catch (\Exception $e) {
            $transaction->rollBack();
            \Yii::$app->session->setFlash('danger', "<i class='fa fa-exclamation-triangle'></i>&nbsp; {$e->getMessage()}");
        }
        
        return $this->redirect(['index']);
    }

    /**
     * Finds the Credor model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Credor the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Credor::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException();
    }
}
