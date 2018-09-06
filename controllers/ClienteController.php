<?php
namespace app\controllers;

use app\models\Cliente;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;
use app\models\ClienteSearch;
use yii\web\NotFoundHttpException;
use app\base\Util;
use app\models\Telefone;

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
    public function actionIndex($nome = '')
    {
        $searchModel = new ClienteSearch();
        
        // seta os params do filtro
        if (!$params = \Yii::$app->request->post()) {
            $params = \Yii::$app->request->queryParams;
        }

        // seta o nome da pesquisa rápida
        if (!empty($nome)) {
        	$params['ClienteSearch']['nome'] = $nome;
        }
        
        // realiza o filtro
        $dataProvider = $searchModel->search($params);
        
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
     * Cadastra um registro
     */
    public function actionCreate()
    {
        $model = new Cliente();
        
        if ($post = \Yii::$app->request->post()) {
        	try {        		
        		$transaction = \Yii::$app->db->beginTransaction();
        		
        		// carrega os dados da model
        		$model->load($post);
	        	
	        	// valida o tipo do cliente
	        	if ($model->tipo == Cliente::TIPO_FISICO) {
	        		$model->nome = $post['cliente-nome'];
	        		$model->documento = Util::unmask($post['cliente-cpf'], true);
	        		$model->nome_social = $post['cliente-apelido'];
	        	} else {
	        		$model->nome = $post['cliente-razao-social'];
	        		$model->documento = Util::unmask($post['cliente-cnpj'], true);
	        		$model->nome_social = $post['cliente-fantasia'];
	        	}
	        	
	        	// remove a mascara do rg
	        	$model->rg = Util::unmask($model->rg);
	        	
	        	// salva o cliente
	        	if (!$model->save()) {
	        		throw new \Exception(Util::renderErrors($model->getErrors()));
	        	}
	        	
	        	// salva os telefones cadastrados
	        	if (isset($post['Telefones']) && is_array($post['Telefones'])) {
	        		foreach ($post['Telefones'] as $telefone) {
	        			if (isset($telefone['numero']) && !empty($telefone['numero'])) {
	        				// cria a model de telefone e
	        				// seta os dados
	        				$modelTelefone = new Telefone();
	        				$modelTelefone->id_cliente = $model->id ? $model->id : $model->getPrimaryKey();
	        				$modelTelefone->numero     = $telefone['numero'];
	        				$modelTelefone->ramal      = $telefone['ramal'];
	        				$modelTelefone->tipo       = $telefone['tipo'];
	        				$modelTelefone->contato   = $telefone['contato'];
	        				$modelTelefone->whatsapp   = $telefone['whatsapp'];
	        				$modelTelefone->ativo      = $telefone['ativo'];
	        				$modelTelefone->observacao = $telefone['observacao'];
	        				
	        				// salva o telefone
	        				if (!$modelTelefone->save()) {
	        					throw new \Exception(Util::renderErrors($modelTelefone->getErrors()));
	        				}
	        			}
	        		}
	        	}
	        	
	        	$transaction->commit();
	        	return $this->redirect(['index']);
        	} catch (\Exception $e) {
        		$transaction->rollBack();
        		\Yii::$app->session->setFlash('danger', "<i class='fa fa-exclamation-triangle'></i>&nbsp; {$e->getMessage()}");
        	}
        }
          
        return $this->render('create', [
        	'model' => $model,
        	'layout' => $model->tipo && $model->tipo == Cliente::TIPO_JURIDICO ? 'J' : 'F',
        ]);
        
    }

    /**
     * Altera um registro
     */
    public function actionUpdate($id)
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
        	'layout' => $model->tipo && $model->tipo == Cliente::TIPO_JURIDICO ? 'J' : 'F',
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
     * Deleta um registro
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        return $this->redirect(['index']);
    }

    /**
     * Realiza a pesquisa rápida de um ou mais clientes
     */
    public function actionQuickSearch($nome = '')
    {
    	// busca todos os clientes com o nome passado
    	$cliente = Cliente::find()->where(['like', 'nome', $nome])->all();

    	// se achou apenas um cliente, redireciona para a página do cliente
    	// se houver mais clientes com este nome
    	// redireciona para a página de listagem de clientes
    	if (!empty($nome) && count($cliente) == 1) {
    		return $this->redirect(['update', 'id' => $cliente[0]->id]);
    	}
    	
    	return $this->redirect(['index', 'nome' => $nome]);
    }
    
    /**
     * Busca um cliente por ajax typeahead
     */
    public function actionSearchList(array $q)
    {
    	// valida a requisição
    	if (!\Yii::$app->request->isAjax) {
    		throw new NotFoundHttpException();
    	}
    	
        $data = [];
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $query = Cliente::find();
        
        if (isset($q['nome'])) { $query->select('nome')->andWhere(['like', 'nome', $q['nome']])->distinct(true); }
        if (isset($q['telefone'])) { $query->select('telefone')->andWhere(['like', 'telefone', $q['telefone']])->distinct(true); }
        if (isset($q['documento'])) { $query->select('documento')->andWhere(['like', 'documento', $q['documento']])->distinct(true); }
        
        $model = $query->all();
        
        if ($model != null) {
            foreach ($model as $key) {
                if (isset($q['nome'])) { $data[]['value'] = $key['nome']; }
                if (isset($q['telefone'])) { $data[]['value'] = $key['telefone']; }
                if (isset($q['documento'])) { $data[]['value'] = $key['documento']; }
            }
        }
        
        return $data;
    }
    
    /**
     * Busca um registro
     */
    protected function findModel($id)
    {
        if (($model = Cliente::findOne($id)) !== null) {
            return $model;
        }
        
        throw new NotFoundHttpException();
    }
}
