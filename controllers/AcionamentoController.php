<?php
namespace app\controllers;

use Yii;
use app\base\Helper;
use yii\helpers\Json;
use yii\web\Controller;
use app\models\Contrato;
use app\base\AjaxResponse;
use app\models\Colaborador;
use yii\filters\VerbFilter;
use app\models\Acionamento;
use yii\web\NotFoundHttpException;

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
     * Valida a permissão do usuário com base no cargo
     *
     * @inheritDoc
     * @see \yii\web\Controller::beforeAction()
     */
    public function beforeAction($action)
    {
        if ($this->action->id == 'delete') {
            if (\Yii::$app->user->identity->cargo != Colaborador::CARGO_ADMINISTRADOR) {
                throw new NotFoundHttpException();
            }
        }
        
        return parent::beforeAction($action);
    }
    
    /**
     * Retorna uma lista de acionamentos 
     * @return mixed
     */
    public function actionIndex($contrato = null)
    {
        // valida a requisicao
        if (!\Yii::$app->request->isAjax) {
            throw new NotFoundHttpException();
        }
        
        // busca o contrato
        $contrato = Contrato::findOne(['id' => $contrato]);
        
        return $this->renderAjax('index', [
            'acionamentos' => $contrato->acionamentos,
            'negociacao' => $contrato->negociacao,
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
     * Cadastra/Altera um acionamento por Ajax
     */
    public function actionSave()
    {
        // valida a requisicao
        if (!\Yii::$app->request->isAjax) {
            throw new NotFoundHttpException();
        }
        
        $retorno = new AjaxResponse(false);
        
        // receb o post
        if ($post = \Yii::$app->request->post()) {
            // valida se esta alterando ou cadastrando um novo acionamento
            if (isset($post['id']) && !empty($post['id'])) {
                $model = Acionamento::findOne(['id' => $post['id']]);
            } else {                
                $model = new Acionamento();
            }
            
            if ($model) {                
                // seta os dados da model
                $model->tipo = $post['tipo'];
                $model->data = $post['data'];
                $model->descricao = $post['descricao'];
                $model->subtipo = $post['subtipo'];
                $model->colaborador_id = \Yii::$app->user->id;
                $model->id_cliente = $post['cliente'];
                $model->id_contrato = $post['contrato'];
                $model->colaborador_agendamento = isset($post['colaboradorAgendamento']) ? $post['colaboradorAgendamento'] : null;
                $model->data_agendamento = isset($post['dataAgendamento']) ? $post['dataAgendamento'] : null;
                
                // salva a model
                if (!$model->save()) {
                    $retorno->message = Helper::renderErrors($model->getErrors());
                } else {
                    $retorno->success = true;                
                }
            } else {
                $retorno->message = 'O acionamento não foi encontrado. Por favor, recarregue a página e tente novamente.';
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
        // valida a requisicao
        if (!\Yii::$app->request->isAjax) {
            throw new NotFoundHttpException();
        }
        
        $retorno = new AjaxResponse(false);
        
        if ($this->findModel($id)->delete()) {
            $retorno->success = true;
        }

        return Json::encode($retorno);
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
