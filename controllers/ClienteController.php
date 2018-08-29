<?php
namespace app\controllers;

use Yii;
use app\models\Cliente;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use app\models\ClienteSearch;
use yii\web\NotFoundHttpException;

/**
 * ClienteController implements the CRUD actions for Cliente model.
 */
class ClienteController extends BaseController
{
    /**
     * @inheritdoc
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
     * Lists all Cliente models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ClienteSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        // model do import file
        $modelImport = new \yii\base\DynamicModel(['fileImport' => 'File Import']);
        $modelImport->addRule(['fileImport'],'required');
        $modelImport->addRule(['fileImport'], 'file', ['extensions' => 'ods,xls,xlsx']);
        
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        	'modelImport' => $modelImport
        ]);
    }

    /**
     * Creates a new Cliente model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCadastrar()
    {
        $model = new Cliente();
        
        if ($post = \Yii::$app->request->post()) {
        	$model->load($post);
        	if (!$model->save()) {
        		// TODO implementar mensagem de erro
        	}
        	
        	return $this->redirect(['index']);
        }
          
        return $this->render('create', [
        	'model' => $model,
        ]);
        
    }

    /**
     * Updates an existing Cliente model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionAlterar($id)
    {
        $model = $this->findModel($id);

        if ($post = \Yii::$app->request->post()) {
        	$model->load($post);
        	if (!$model->save()) {
        		// TODO implementar mensagem de erro
        	}
	        
            return $this->redirect(['index']);
        }
        
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * TODO
     * Realiza o upload e processamento de um arquivo Excel
     */
    public function actionUploadExcel() 
    {
    	// model do import file
    	$modelImport = new \yii\base\DynamicModel(['fileImport' => 'File Import']);
    	$modelImport->addRule(['fileImport'],'required');
    	$modelImport->addRule(['fileImport'], 'file', ['extensions' => 'ods,xls,xlsx']);
		
    	$modelImport->fileImport = UploadedFile::getInstance($modelImport,'fileImport');
    	
    	if ($modelImport->fileImport && $modelImport->validate()) {
    		/* $inputFileType = \PHPExcel_IOFactory::identify($modelImport->fileImport->tempName);
    		$objReader = \PHPExcel_IOFactory::createReader($inputFileType);
    		$objPHPExcel = $objReader->load($modelImport->fileImport->tempName);
    		$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
    		$baseRow = 3;
    		
    		while (!empty($sheetData[$baseRow]['B'])) {
    			var_dump((string)$sheetData[$baseRow]['B']);
    			$baseRow++;
    		} */
    		
    		var_dump('to aqui');
    	} else {
    		var_dump($modelImport->errors);
    	}
    	
    	die;	
    }
    
    /**
     * Deletes an existing Cliente model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDeletar($id)
    {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    //TODO
    /**
     * Redireciona para as actions corretas ate os links serem acertados
     */
    public function actionUpdate($id)
    {
     return $this->redirect(['alterar', 'id' => $id]);
    }
    public function actionDelete($id)
    {
     return $this->redirect(['deletar', 'id' => $id]);
    }
    
    /**
     * Finds the Cliente model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Cliente the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if(($model = Cliente::findOne($id)) !== null) {
            return $model;
        }else {
            throw new NotFoundHttpException('Página não encontrada.');
        }
    }
}
