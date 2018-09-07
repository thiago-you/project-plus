<?php
namespace app\controllers;

use Yii;
use yii\helpers\Json;
use yii\web\Controller;
use app\base\AjaxResponse;
use yii\filters\VerbFilter;
use app\models\CredorCalculo;
use yii\web\NotFoundHttpException;

/**
 * CredorCalculoController implements the CRUD actions for CredorCalculo model.
 */
class CredorCalculoController extends Controller
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
     * Lists all CredorCalculo models.
     * @return mixed
     */
    public function actionIndex($id = null)
    {
        $model = CredorCalculo::findAll(['id_campanha' => $id]);
        
        return $this->renderAjax('index', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new CredorCalculo model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        // valida a requisição
        if (!\Yii::$app->request->isAjax) {
            throw new NotFoundHttpException();
        }
        
        // cria a model
        $model = new CredorCalculo();
        
        // salva a camapnha
        if ($post = \Yii::$app->request->post()) {
            try {
                // cria o retorno e carrega os dados da campanha
                $retorno = new AjaxResponse();
                $model->load($post);
                
                if (!$model->save()) {
                    throw new \Exception();
                }
            } catch(\Exception $e) {
                $retorno->success = false;
            }
            
            return Json::encode($retorno);
        }
        
        return $this->renderAjax('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing CredorCalculo model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing CredorCalculo model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the CredorCalculo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CredorCalculo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CredorCalculo::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
