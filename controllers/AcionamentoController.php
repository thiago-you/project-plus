<?php

namespace app\controllers;

use Yii;
use app\models\Acionamento;
use app\models\AcionamentoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use app\base\AjaxResponse;
use app\base\Helper;

/**
 * AcionamentoController implements the CRUD actions for Acionamento model.
 */
class AcionamentoController extends Controller
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
     * Lists all Acionamento models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AcionamentoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Acionamento model.
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
     * Cadastra um acionamento por Ajax
     */
    public function actionCreate()
    {
        // valida a requisicao
        if (!\Yii::$app->request->isAjax) {
            throw new NotFoundHttpException();
        }
        
        $retorno = new AjaxResponse(false);
        
        // receb o post
        if ($post = \Yii::$app->request->post()) {
            $model = new Acionamento();
            
            // seta os dados da model
            $model->tipo = $post['tipo'];
            $model->data = $post['data'];
            $model->descricao = $post['descricao'];
            $model->colaborador_id = \Yii::$app->user->id;
            $model->id_cliente = $post['cliente'];
            $model->hora = isset($post['hora']) ? $post['hora'] : null;
            
            // salva a model
            if (!$model->save()) {
                $retorno->message = Helper::renderErrors($model->getErrors());
            } else {
                $retorno->success = true;                
            }
        }
        
        // retorna o array conevrtido
        return Json::encode($retorno);
    }

    /**
     * Updates an existing Acionamento model.
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
     * Deleta a model
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Busca a model
     */
    protected function findModel($id)
    {
        if (($model = Acionamento::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException();
    }
}
