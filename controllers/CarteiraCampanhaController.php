<?php
namespace app\controllers;

use yii\helpers\Json;
use yii\web\Controller;
use app\base\AjaxResponse;
use yii\filters\VerbFilter;
use app\models\CarteiraCampanha;
use yii\web\NotFoundHttpException;

/**
 * CarteiraCampanhaController implements the CRUD actions for CarteiraCampanha model.
 */
class CarteiraCampanhaController extends Controller
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
     * Displays a single CarteiraCampanha model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        // valida a requisição
        if (!\Yii::$app->request->isAjax) {
            throw new NotFoundHttpException();
        }
        
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Cadastra uma nova campanha por Ajax
     */
    public function actionCreate()
    {
        // valida a requisição
        if (!\Yii::$app->request->isAjax) {
            throw new NotFoundHttpException();
        }
        
        // cria a model
        $model = new CarteiraCampanha();

        // salva a camapnha
        if ($post = \Yii::$app->request->post()) {
            try {
                // cria o retorno e carrega os dados da campanha
                $retorno = new AjaxResponse();
                $model->load($post);

                if (!$model->save()) {
                    throw new \Exception();
                }                
                
                // seta os dados de retorno
                $retorno->id = $model->id ? $model->id : $model->getPrimaryKey();
                $retorno->nome = $model->nome;
                $retorno->newRecord = true;
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
     * Updates an existing CarteiraCampanha model.
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

        // salva a camapnha
        if ($post = \Yii::$app->request->post()) {
            try {
                // cria o retorno e carrega os dados da campanha
                $retorno = new AjaxResponse();
                $model->load($post);
                
                if (!$model->save()) {
                    throw new \Exception();
                }
                
                // seta os dados de retorno
                $retorno->id = $model->id;
                $retorno->nome = $model->nome;
                $retorno->newRecord = false;
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
     * Deletes an existing CarteiraCampanha model.
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
            // cria o retorno e carrega os dados da campanha
            $retorno = new AjaxResponse();
            
            // deleta a campanha
            $model->delete();
            
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            $retorno->success = false;
        }
        
        return Json::encode($retorno);
    }

    /**
     * Finds the CarteiraCampanha model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CarteiraCampanha the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CarteiraCampanha::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException();
    }
}
