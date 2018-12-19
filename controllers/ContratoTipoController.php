<?php
namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use app\models\Colaborador;
use app\models\ContratoTipo;
use yii\db\IntegrityException;
use app\models\ContratoTipoSearch;
use yii\web\NotFoundHttpException;
use yii\base\UserException;
use app\base\Helper;

/**
 * ContratoTipoController implements the CRUD actions for ContratoTipo model.
 */
class ContratoTipoController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
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
        if (\Yii::$app->user->identity->cargo != Colaborador::CARGO_ADMINISTRADOR) {
            throw new NotFoundHttpException();
        }
        
        return parent::beforeAction($action);
    }
    
    /**
     * Lists all ContratoTipo models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ContratoTipoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Cadastra um novo tipo de contrato
     */
    public function actionCreate()
    {
        $model = new ContratoTipo();

        if ($post = \Yii::$app->request->post()) {
            try {
                if (!$model->load($post) || !$model->save()) {
                    throw new UserException(Helper::renderErrors($model->getErrors()));
                }
                
                \Yii::$app->session->setFlash('success', '<i class="fa fa-check"></i>&nbsp; O tipo de contrato foi cadastrado com sucesso.');
                return $this->redirect(['index']);
            } catch (\Exception $e) {
                \Yii::$app->session->setFlash('danger', "<i class='fa fa-exclamation-triangle'></i>&nbsp; Erros: {$e->getMessage()}");
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Altera o tipo do contrato
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($post = \Yii::$app->request->post()) {
            try {
                if (!$model->load($post) || !$model->save()) {
                    throw new UserException(Helper::renderErrors($model->getErrors()));
                }
                
                \Yii::$app->session->setFlash('success', '<i class="fa fa-check"></i>&nbsp; O tipo de contrato foi alterado com sucesso.');
                return $this->redirect(['index']);
            } catch (\Exception $e) {
                \Yii::$app->session->setFlash('danger', "<i class='fa fa-exclamation-triangle'></i>&nbsp; Erros: {$e->getMessage()}");
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deleta o tipo de contrato
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
            \Yii::$app->session->setFlash('success', '<i class="fa fa-check"></i>&nbsp; O tipo de contrato foi excluído com sucesso.');
        } catch (IntegrityException $e) {
            $transaction->rollBack();
            \Yii::$app->session->setFlash('danger', '<i class="fa fa-exclamation-triangle"></i>&nbsp; O tipo de contrato não pode ser deletada pois possui dados vinculados.');
        } catch (\Exception $e) {
            $transaction->rollBack();
            \Yii::$app->session->setFlash('danger', "<i class='fa fa-exclamation-triangle'></i>&nbsp; Erros: {$e->getMessage()}");
        }
        
        return $this->redirect(['index']);
    }

    /**
     * Busca a model
     */
    protected function findModel($id)
    {
        if (($model = ContratoTipo::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException();
    }
}
