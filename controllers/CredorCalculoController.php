<?php
namespace app\controllers;

use yii\helpers\Json;
use yii\web\Controller;
use app\base\AjaxResponse;
use yii\filters\VerbFilter;
use app\models\CredorCalculo;
use yii\web\NotFoundHttpException;
use app\base\Helper;

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
        
        // salva a faixa de calculo
        if ($post = \Yii::$app->request->post()) {
            try {
                // cria o retorno e carrega os dados da model
                $retorno = new AjaxResponse();
                $model->load($post);
                
                // seta os valores direto do plugin
                $model->multa = Helper::unmask($post['credorcalculo-multa-disp'], true);
                $model->juros = Helper::unmask($post['credorcalculo-juros-disp'], true);
                $model->honorario = Helper::unmask($post['credorcalculo-honorario-disp'], true);
                $model->desc_encargos_max = Helper::unmask($post['credorcalculo-desc_encargos_max-disp'], true);
                $model->desc_principal_max = Helper::unmask($post['credorcalculo-desc_principal_max-disp'], true);
                $model->desc_honorario_max = Helper::unmask($post['credorcalculo-desc_honorario_max-disp'], true);
                
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
                
                // seta os valores direto do plugin
                $model->multa = Helper::unmask($post['credorcalculo-multa-disp'], true);
                $model->juros = Helper::unmask($post['credorcalculo-juros-disp'], true);
                $model->honorario = Helper::unmask($post['credorcalculo-honorario-disp'], true);
                $model->desc_encargos_max = Helper::unmask($post['credorcalculo-desc_encargos_max-disp'], true);
                $model->desc_principal_max = Helper::unmask($post['credorcalculo-desc_principal_max-disp'], true);
                $model->desc_honorario_max = Helper::unmask($post['credorcalculo-desc_honorario_max-disp'], true);
                
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
     * Deletes an existing CredorCalculo model.
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

        throw new NotFoundHttpException();
    }
}
