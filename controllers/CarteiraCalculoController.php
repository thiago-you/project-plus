<?php
namespace app\controllers;

use yii\helpers\Json;
use yii\web\Controller;
use app\base\AjaxResponse;
use yii\filters\VerbFilter;
use app\models\CarteiraCalculo;
use yii\web\NotFoundHttpException;

/**
 * CarteiraCalculoController implements the CRUD actions for CarteiraCalculo model.
 */
class CarteiraCalculoController extends Controller
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
     * Lists all CarteiraCalculo models.
     * @return mixed
     */
    public function actionIndex($id = null)
    {
        $model = CarteiraCalculo::findAll(['id_campanha' => $id]);
        
        return $this->renderAjax('index', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new CarteiraCalculo model.
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
        $model = new CarteiraCalculo();
        
        // salva a faixa de calculo
        if ($post = \Yii::$app->request->post()) {
            try {
                // cria o retorno e carrega os dados da model
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
     * Updates an existing CarteiraCalculo model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        // valida a requisição
        if (!\Yii::$app->request->isAjax) {
            throw new NotFoundHttpException();
        }
        
        // busca a model
        $model = $this->findModel($id);

        // salva a faixa de calculo
        if ($post = \Yii::$app->request->post()) {
            try {
                // cria o retorno e carrega os dados da model
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

        return $this->renderAjax('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing CarteiraCalculo model.
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
            // cria o retorno e carrega os dados da model
            $retorno = new AjaxResponse();
            
            // deleta a model
            $model->delete();
            
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            $retorno->success = false;
        }
        
        return Json::encode($retorno);
    }

    /**
     * Finds the CarteiraCalculo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CarteiraCalculo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CarteiraCalculo::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException();
    }
}
