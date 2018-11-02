<?php
namespace app\controllers;

use Yii;
use yii\helpers\Json;
use yii\web\Controller;
use app\base\AjaxResponse;
use app\models\Negociacao;
use yii\base\UserException;
use app\models\Acionamento;
use yii\filters\VerbFilter;
use yii\data\ActiveDataProvider;
use app\models\NegociacaoParcela;
use yii\web\NotFoundHttpException;

/**
 * NegociacaoParcelaController implements the CRUD actions for NegociacaoParcela model.
 */
class NegociacaoParcelaController extends Controller
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
     * Lists all NegociacaoParcela models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => NegociacaoParcela::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single NegociacaoParcela model.
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
     * Fatura uma parcela da negociacao
     */
    public function actionFaturar($id)
    {
        // valida a requisição
        if (!\Yii::$app->request->isAjax) {
            throw new NotFoundHttpException();
        }
        
        $retorno = new AjaxResponse();
        
        // busca a model
        $model = $this->findModel($id);
        
        // altera o status da negociacao
        try {
            // verifica se a negociacao ja esta fechada
            if ($model->negociacao->status == Negociacao::STATUS_ABERTA) {
                throw new UserException('A parcela não pode ser faturada pois a negociação ainda esta aberta.');
            }
            
            // verifica o status da parcela
            if ($model->status == NegociacaoParcela::STATUS_ABERTA) {
                $model->status = NegociacaoParcela::STATUS_FATURADA;
                
                // registra o acionamento
                Acionamento::setAcionamento([
                    'id_cliente' => $model->negociacao->contrato->id_cliente,
                    'id_contrato' => $model->negociacao->id_contrato,
                    'tipo' => Acionamento::TIPO_SISTEMA,
                    'subtipo' => Acionamento::SUBTIPO_NEGOCIACAO,
                    'descricao' => "A parcela N° {$model->num_parcela} foi faturada.",
                ]);
            } elseif ($model->status == NegociacaoParcela::STATUS_FATURADA) {
                $model->status = NegociacaoParcela::STATUS_ABERTA;
                
                // registra o acionamento
                Acionamento::setAcionamento([
                    'id_cliente' => $model->negociacao->contrato->id_cliente,
                    'id_contrato' => $model->negociacao->id_contrato,
                    'tipo' => Acionamento::TIPO_SISTEMA,
                    'subtipo' => Acionamento::SUBTIPO_NEGOCIACAO,
                    'descricao' => "A parcela N° {$model->num_parcela} foi estornada.",
                ]);
            }
            
            // salva a negociacao
            if (!$model->save()) {
                throw new UserException('Não foi possível alterar a parcela.');
            }
        } catch (\Exception $e) {
            $retorno->success = false;
            $retorno->message = $e->getMessage();
        }
        
        // renderiza o html da página
        $retorno->content = $this->renderAjax('/negociacao/negociacao', [
            'contrato' => $model->negociacao->contrato,
            'negociacao' => $model->negociacao,
        ]);
        
        return Json::encode($retorno);
    }
    
    /**
     * Salva as parcelas da negociacao
     * @return mixed
     */
    public function actionSave()
    {
        $model = new NegociacaoParcela();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing NegociacaoParcela model.
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
     * Deletes an existing NegociacaoParcela model.
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
     * Finds the NegociacaoParcela model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return NegociacaoParcela the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = NegociacaoParcela::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
