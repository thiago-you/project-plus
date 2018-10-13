<?php
namespace app\controllers;

use app\base\Helper;
use yii\helpers\Json;
use yii\web\Controller;
use app\models\Contrato;
use app\models\Negociacao;
use app\base\AjaxResponse;
use yii\filters\VerbFilter;
use yii\base\UserException;
use app\models\Acionamento;
use app\models\NegociacaoParcela;
use yii\web\NotFoundHttpException;

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
     * Retorna a negociacao do contrato
     */
    public function actionIndex($id, $contratoId = null)
    {
        // valida a rquisicao
        if (!\Yii::$app->request->isAjax) {
            throw new NotFoundHttpException();
        }
        
        $retorno = new AjaxResponse();
        
        // tenta encontrar a model
        if (!$model = Negociacao::find()->where(['id' => $id])->one()) {
            // busca o contrato
            if (!$contrato = Contrato::findOne(['id' => $contratoId])) {
                throw new NotFoundHttpException();
            }

            // cria a model
            $model = new Negociacao();       
            
            // seta os dados do contrato
            $model->id_contrato = $contrato->id;
            $model->id_credor = $contrato->id_credor;
            $model->id_campanha = $contrato->credor->id_campanha;
            $model->tipo = Negociacao::A_VISTA;
            
            // calcula os valores da negociacao
            $model->calcularValores($contrato);
        }
        
        // seta o id da negociacao
        $retorno->id = $model->id ? $model->id : $model->getPrimaryKey();
        
        // seta o conteudo da negociacao
        $retorno->content = $this->renderAjax('negociacao', [
            'contrato' => $model->contrato,
            'negociacao' => $model,
        ]);
        
        return Json::encode($retorno);
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
            
            // deleta todas as parcelas existentes da negociacao
            NegociacaoParcela::deleteAll(['id_negociacao' => $model->id ? $model->id : $model->getPrimaryKey()]);
            
            // seta as parcelas da negociacao
            if ($model->tipo == Negociacao::PARCELADO) {                
                if (isset($post['Negociacao']['parcelas']) && is_array($post['Negociacao']['parcelas'])) {
                    foreach ($post['Negociacao']['parcelas'] as $key => $parcela) {
                        $modelParcela = new NegociacaoParcela();
                        $modelParcela->id_negociacao = $model->id ? $model->id : $model->getPrimaryKey();
                        $modelParcela->num_parcela = $parcela['num'];
                        $modelParcela->data_vencimento = Helper::formatDateToSave($parcela['vencimento'], Helper::DATE_DEFAULT);
                        $modelParcela->valor = $parcela['valor'];
                        
                        // salva a parcela da negociacao
                        if (!$modelParcela->save()) {
                            throw new UserException(Helper::renderErrors($modelParcela->getErrors()));
                        }
                    }
                }
            }
            
            // registra o acionamento
            Acionamento::setAcionamento([
                'id_cliente' => $model->contrato->id_cliente,
                'id_contrato' => $model->id_contrato,
                'tipo' => Acionamento::TIPO_SISTEMA,
                'titulo' => 'Alteração na Negociação',
                'descricao' => 'Alteração dos cálculos da negociação.',
            ]);
            
            $retorno->id = $model->id ? $model->id : $model->getPrimaryKey();
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            $retorno->success = false;
            $retorno->message = $e->getMessage();
        }
        
        // seta o conteudo da negociacao
        $retorno->content = $this->renderAjax('negociacao', [
            'contrato' => $model->contrato,
            'negociacao' => $model,
        ]);
        
        return Json::encode($retorno);
    }

    /**
     * Altera uma negociacao
     */
    public function actionAlterar($id)
    {
        // valida a requisição
        if (!\Yii::$app->request->isAjax) {
            throw new NotFoundHttpException();
        }

        $retorno = new AjaxResponse();
        
        // busca a model
        $model = $this->findModel($id);

        // altera o status da negociacao
        if ($model->status == Negociacao::STATUS_ABERTA) {
            $model->status = Negociacao::STATUS_FECHADA;
            
            // registra o acionamento
            Acionamento::setAcionamento([
                'id_cliente' => $model->contrato->id_cliente,
                'id_contrato' => $model->id_contrato,
                'tipo' => Acionamento::TIPO_SISTEMA,
                'titulo' => 'Alteração na Negociação',
                'descricao' => 'Alteração da situação da negociação de "aberta" para "fechada".',
            ]);
        } elseif ($model->status == Negociacao::STATUS_FECHADA) {
            $model->status = Negociacao::STATUS_ABERTA;

            // registra o acionamento
            Acionamento::setAcionamento([
                'id_cliente' => $model->contrato->id_cliente,
                'id_contrato' => $model->id_contrato,
                'tipo' => Acionamento::TIPO_SISTEMA,
                'titulo' => 'Alteração na Negociação',
                'descricao' => 'Alteração da situação da negociação de "fechada" para "aberta".',
            ]);
        }

        // salva a negociacao
        if (!$model->save()) {
            $retorno->success = false;
            $retorno->message = 'Não foi possível alterar a negociação.';
        }
        
        // renderiza o html da página
        $retorno->content = $this->renderAjax('negociacao', [
            'contrato' => $model->contrato,
            'negociacao' => $model,
        ]);
        
        return Json::encode($retorno);
    }

    /**
     * Fatura uma negociacao
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
            if ($model->status == Negociacao::STATUS_ABERTA) {
                throw new UserException('A negociação não pode ser faturada pois ainda esta aberta.');
            }
            
            if ($model->status == Negociacao::STATUS_FECHADA) {
                $model->status = Negociacao::STATUS_FATURADA;
                
                // fatura todas as parcelas da negociacao
                if ($model->tipo == Negociacao::PARCELADO) {                    
                    foreach ($model->parcelas as $parcela) {
                         $parcela->status = NegociacaoParcela::STATUS_FATURADA;
                         // salva a parcela
                         if (!$parcela->save()) {
                             throw new UserException('Não foi possível faturar as parcelas da negociação.');
                         }
                    }
                }
                
                // registra o acionamento
                Acionamento::setAcionamento([
                    'id_cliente' => $model->contrato->id_cliente,
                    'id_contrato' => $model->id_contrato,
                    'tipo' => Acionamento::TIPO_SISTEMA,
                    'titulo' => 'Alteração na Negociação',
                    'descricao' => 'A negociação foi faturada.',
                ]);
            } elseif ($model->status == Negociacao::STATUS_FATURADA) {
                $model->status = Negociacao::STATUS_FECHADA;
                
                // estorna todas as parcelas da negociacao
                if ($model->tipo == Negociacao::PARCELADO) {
                    foreach ($model->parcelas as $parcela) {
                        $parcela->status = NegociacaoParcela::STATUS_ABERTA;
                        // salva a parcela
                        if (!$parcela->save()) {
                            throw new UserException('Não foi possível estornar as parcelas da negociação.');
                        }
                    }
                }
                
                // registra o acionamento
                Acionamento::setAcionamento([
                    'id_cliente' => $model->contrato->id_cliente,
                    'id_contrato' => $model->id_contrato,
                    'tipo' => Acionamento::TIPO_SISTEMA,
                    'titulo' => 'Alteração na Negociação',
                    'descricao' => 'Estorno da negociação e alteração da situação da negociação de "faturada" para "fechada".',
                ]);
            }
            
            // salva a negociacao
            if (!$model->save()) {
                throw new UserException('Não foi possível alterar a negociação.');
            }
        } catch (\Exception $e) {
            $retorno->success = false;
            $retorno->message = $e->getMessage();
        }
        
        // renderiza o html da página
        $retorno->content = $this->renderAjax('negociacao', [
            'contrato' => $model->contrato,
            'negociacao' => $model,
        ]);
        
        return Json::encode($retorno);
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
