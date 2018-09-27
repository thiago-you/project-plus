<?php

namespace app\controllers;

use Yii;
use app\models\Negociacao;
use app\models\NegociacaoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use app\base\AjaxResponse;
use app\base\Helper;
use yii\base\UserException;

/**
 * NegociacaoController implements the CRUD actions for Negociacao model.
 */
class NegociacaoController extends Controller
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
     * Lists all Negociacao models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new NegociacaoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Negociacao model.
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
     * Salva a negociação
     */
    public function actionSalvar()
    {
        // valida a requisicao
        if (!$post = \Yii::$app->request->post()) {
            throw new NotFoundHttpException();
        }
        
        // cria o objeto de retorno
        $retorno = new AjaxResponse();

        if ($post['Negociacao']['id'] != null) {
            $model = $this->findModel($post['Negociacao']['id']);
        } else {            
            $model = new Negociacao();
        }
        
        // carrega os dados enviados
        $model->load($post);
        
        try {
            $transaction = \Yii::$app->db->beginTransaction();
            
            // salva a negociacao
            if (!$model->save()) {
                throw new UserException(Helper::renderErrors($model->getErrors()));
            }
            
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            $retorno->success = false;
            $retorno->message = $e->getMessage();
        }
        
        
        return Json::encode($retorno);
    }

    /**
     * Updates an existing Negociacao model.
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
     * Deletes an existing Negociacao model.
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
     * Finds the Negociacao model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Negociacao the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Negociacao::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException();
    }
}
