<?php
namespace app\controllers;

use Yii;
use yii\db\Query;
use yii\helpers\Json;
use yii\db\IntegrityException;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;

use JasperPHP\JasperPHP;

use app\base\Helper;
use app\models\Cidade;
use app\models\Pedido;
use app\models\Empresa;
use app\models\Clientes;
use app\base\SystemError;
use yii\base\UserException;
use app\models\ClientesSearch;
use app\models\PedidoPagamento;
use app\modules\financeiro\models\FormaPagamento;

/**
 * @resolveName: Clientes
 * @id: ClientesController
 * @modulo: Cadastro
 */
class ClientesController extends MasterController
{
    /**
     * @inheritdoc
     */
   public function behaviors()
   {
       return [
           'access' => [
               'class' => AccessControl::className(),
               'rules' => [
					[
                       'allow' => true,
                       'roles' => ['@'],
                       'matchCallback' => function ($rule, $action) {
                           if (\Yii::$app->user->can('/clientes/gerenciar')) {
                               return true;
                           }
                       }
					],
                   [
                       'allow' => true,
                       'actions' => ['index', 'search-list', 'verifica-cpf', 'relatorio'],
                       'roles' => ['@'],
                       'matchCallback' => function ($rule, $action) {
                           if (\Yii::$app->user->can('/clientes/index')) {
                               return true;
   						   }
                       }
					],
					[
       					'allow' => true,
       					'actions' => [
       					    'list','list-especial', 'create-basic', 
       					    'endereco', 'verifica-cpf', 'verificar-credito'
       					],
       					'roles' => ['@'],
       					'matchCallback' => function ($rule, $action) {
           					if (
           					    \Yii::$app->user->can('/pedido/utilizar-pedido-normal') ||
           					    \Yii::$app->user->can('/pedido/utilizar-pdv') ||
           					    \Yii::$app->user->can('/pedido/utilizar-pedido-especial')
       					    ) {
           					    return true;
           					}
       					}
                    ],
               ],
           ],
       ];
   }

   /**
    * @inheritDoc
    * @see \app\controllers\MasterController::beforeAction()
    */
   public function beforeAction($action)
   {
       return parent::beforeAction($action);
   }
   
   /**
    * @inheritDoc
    * @see \app\controllers\MasterController::afterAction()
    */
   public function afterAction($action, $result)
   {
       return parent::afterAction($action, $result);
   }
   
   /**
    * @nome: Gerenciar Clientes
    * @id: /clientes/gerenciar
    * @descr: Permite realizar ações que envolvam clientes
    */
   public function actionGerenciar()
   {
       throw new NotFoundHttpException();
   }

    /**
     * @nome: Consultar Clientes
     * @id: /clientes/index
     * @descr: Lista os clientes cadastrados no sistema
     */
    public function actionIndex()
    {
    	$searchModel = new ClientesSearch();
    	$searchModel->scenario = ClientesSearch::SCENARIO_INDEX;
    	
        $params = Yii::$app->request->queryParams;
        if (isset($params['ClientesSearch']['tipo']) && strlen($params['ClientesSearch']['tipo']) > 10) {
            strlen(Helper::removeMascara($params['ClientesSearch']['tipo'])) == 11 ? $params['ClientesSearch']['cpf'] = Helper::removeMascara($params['ClientesSearch']['tipo']) : $params['ClientesSearch']['cnpj'] = Helper::removeMascara($params['ClientesSearch']['tipo']);
            unset($params['ClientesSearch']['tipo']);
        }
        $dataProvider = $searchModel->search($params);
    	   
        return $this->render('index', [
    		'searchModel' => $searchModel,
    	   	'dataProvider' => $dataProvider,
		]);
    }
        
    public function actionSearchList(array $q)
    {
        $data = [];
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $query = Clientes::find();
        if(isset($q['id_cliente'])) { $query->andWhere(['like', 'id_cliente', $q['id_cliente']]); }
        if(isset($q['nome'])) { $query->andWhere(['like', 'nome', $q['nome']]); }
        $model = $query->all();
        if($model != null)
        {
            foreach($model as $key)
            {
                if(isset($q['id_cliente'])) { $data[]['value'] = $key['id_cliente']; }
                if(isset($q['nome'])) { $data[]['value'] = $key['nome']; }
            }
        }
    
        return $data;
    }   
    
    public function actionRelatorio(array $filtros = null)
    {
        if(Yii::$app->request->isGet){
            try{
                //Verifica se há um relatório na pasta reports, se houver exclui
                if(is_file(getcwd() . '/reports/clientes.pdf')){
                    unlink(getcwd() . '/reports/clientes.pdf');
                }
                
                $empresa = Empresa::findOne(['id' => Yii::$app->user->identity->empresa_id]);
                //Dados da conexão
                $connection = array('driver' => 'mysql', 'username' => Yii::$app->params['dbconnection']['username'], 'password' => Yii::$app->params['dbconnection']['password'], 'host' => Yii::$app->params['dbconnection']['host'], 'database' => Yii::$app->params['dbconnection']['database'], 'port' => '3306');
                //Parametros para jasper
                $params = array("REPORT_LOCALE" => "pt_BR", "nome_empresa" => '"'.$empresa->razao_social.'"');
                //Verifica se os parametros que vieram e adiciona ao array de parametros
                if(isset($filtros['ativo'])){
                    $params["ativo"] = '"'.$filtros['ativo'].'"';
                }
                //Biblioteca que manipula, compila o arquivo jrxml
                $jasper = new JasperPHP();
                //Processa o doc .jasper
                $jasper->process(Yii::$app->params['relatorios']['relatorio_clientes'], getcwd() . '/reports/clientes', array("pdf"), $params, $connection, false)->execute();
                //Se houver erros e desejar visualizar o erro descomente a linha abaixo e comente a linha acima, copie o resultado da tela remova os caracteres ^ e execute via linha de comando
                //echo $jasper->process(Yii::$app->params['relatorios']['relatorio_clientes'], getcwd() . '/reports/clientes', array("pdf"), $params, $connection, false)->output();die;
                // Abre o arquivo
                $pdf = getcwd() . '/reports/clientes.pdf';
                if(!is_file($pdf)){
                    Yii::$app->session->setFlash('danger', 'Ocorreu um erro inesperado ao gerar o relatório de clientes');
                    return $this->redirect(['index']);
                }
                else {              
                    return Yii::$app->response->sendFile($pdf, 'Relatorio', ['inline'=>true]);
                }
            }
            catch(\Exception $e){
                Yii::$app->session->setFlash('danger', 'Ocorreu um erro inesperado ao gerar o relatório de clientes, ERRO: '.$e->getMessage());
                return $this->redirect(['index']);
            }
        }
    }
       
    /**
     * Cadastro simplificado de cliente
     */
    public function actionCreateBasic($tipo)
    {
        // preenche os dados iniciais do objeto
        $model = new Clientes();
        $model->tipo_cadastro = $tipo;

        if (\Yii::$app->request->isPost) {
            try {                        
                $model->load(\Yii::$app->request->post());
                
                $model->empresa_id    = \Yii::$app->user->identity->empresa_id;
                $model->data_cadastro = date('Y-m-d H:i:s');
                $model->tipo          = empty($model->tipo)? Clientes::TIPO_PESSOA_FISICA : $model->tipo;
                $model->ativo         = Clientes::CLIENTE_ATIVO;                
                $model->cpf           = Helper::removeMascara($model->cpf);
                $model->cnpj          = Helper::removeMascara($model->cnpj);
                $model->cep           = Helper::removeMascara($model->cep);
                $model->fone          = Helper::removeMascara($model->fone, true);
                $model->fone_celular  = Helper::removeMascara($model->fone_celular, true);
                $model->usuario       = \Yii::$app->user->identity->colaborador_id;
                
                if ($model->tipo == Clientes::TIPO_PESSOA_JURIDICA) {
                    $model->razao_social = $model->nome;
                    $model->fantasia = Helper::shortName($model->nome, 19);
                }
                
                if ($model->save()) {                
                    $retorno = [
                        'success' => 1,
                        'nome'    => $model->nome,
                        'tipo'    => $model->tipo,
                        'id'      => $model->id_cliente,
                        'message' => 'Cliente cadastrado com sucesso',
                        'identificacao' => ($model->tipo == Clientes::TIPO_PESSOA_FISICA)? $model->cpf : $model->cnpj,
                    ];                
                } else { 
                    $retorno = [
                        'success' => 0,
                        'message' => Helper::renderModelErrors($model->getErrors())
                    ];
                }
            }  catch (\Exception $e) {
                $log = new SystemError(['mensagem' => "Erro ao criar um cliente via ajax", 'arquivo' => $e->getFile(), 'arquivo_metodo' => $this->action->actionMethod, 'arquivo_linha' => $e->getLine(), 'tipo' => SystemError::TIPO_FATAL, 'exception' => $e->getMessage(), 'tratamento' => SystemError::TRATAMENTO_ERRO_NAO_TRATADO]);
                $log->save();
                
                $retorno = [
                    'success' => 0,
                    'message' => $e->getMessage()
                ];
            }
            
            return Json::encode($retorno);
        }
        
        return $this->renderAjax('_create-modal',[
            'model' => $model
        ]);
    }    

    /**
     * Cadastra um novo cliente
     */
    public function actionCreate()
    {
        $erros = '';
        $errotab = '';
        
        $model = new Clientes();
        $model->empresa_id = \Yii::$app->user->identity->empresa_id;
        $model->situacao = Clientes::SITUACAO_FINANCEIRA_NORMAL;

        if ($model->load(Yii::$app->request->post())) {            
            try {
                $transaction = Yii::$app->db->beginTransaction();

                // valida se o rg ja foi cadastrado
                if ($model->rg != null) { 
                    if (Clientes::find()->where(['rg' => $model->rg])->exists()) {
                        $erros .= "<li>\"RG\" {$model->rg} já foi cadastrado.</li>";
                    }
                }
                
                // valida os dados de endereço
                if (
                    $model->cep != null || $model->bairro != null || $model->id_estado != null ||
                    $model->id_cidade != null || $model->endereco != null || $model->numero != null
                ) {
                    if ($model->cep == null) {
                        $erros .= "<li> Quando informado, o endereço deve ser completo. Por favor, preencha o \"CEP\".</li>";
                    }
                    if ($model->numero == null) {
                        $erros .= "<li> Quando informado, o endereço deve ser completo. Por favor, preencha o \"Número\".</li>";
                    }
                    if ($model->bairro == null) {
                        $erros .= "<li> Quando informado, o endereço deve ser completo. Por favor, preencha o \"Bairro\".</li>";
                    }
                    if ($model->id_estado == null) {
                        $erros .= "<li> Quando informado, o endereço deve ser completo. Por favor, preencha o \"Estado\".</li>";
                    }
                    if ($model->id_cidade == null) {
                        $erros .= "<li> Quando informado, o endereço deve ser completo. Por favor, preencha a \"Cidade\".</li>";
                    }
                    if ($model->endereco == null) {
                        $erros .= "<li> Quando informado, o endereço deve ser completo. Por favor, preencha a \"Logradouro\".</li>";
                    }
                    
                    if (!empty($erros)) {
                        $erros = "<ul>{$erros}</ul>";
                    }
                }
         
                // seta os dados do cliente
                $model->data_cadastro = date('Y-m-d H:i:s');
                $model->usuario       = \Yii::$app->user->identity->colaborador_id;
                
                // seta a cidade do cliente
                if ($model->id_cidade) {
                    $cidades = Cidade::findOne(['id'=>$model->id_cidade]);
                    $model->codmunic =$cidades->cod_ibge;
                }

                // valida o tipo do cliente
                if ($model->tipo == Clientes::TIPO_PESSOA_JURIDICA) {
                    $model->cnpj    = Helper::removeMascara($model->cnpj);
                    $model->cpf     = null;
                    $model->nome    = strtoupper($_REQUEST['Clientes']['razao_social']);
                    $model->apelido = strtoupper($_REQUEST['Clientes']['fantasia']);
                } else {
                    $model->cpf     = Helper::removeMascara($model->cpf);
                    $model->cnpj    = null;
    	        	$model->nome    = strtoupper($model->nome);
    	        	$model->apelido = strtoupper($model->apelido);
                }
                
                if(!empty($model->dtanascto)) {
                    $model->dtanascto = Helper::formatDateToSave($model->dtanascto, Helper::DATE_DEFAULT);
                }
                
                $model->vlrult_compra = Helper::removeMascara($model->vlrult_compra, true);
                
                // valida os attrs da class
                $erros .= $this->valida_campos($model);
                
                if (!empty($erros)) {
                    // formata a lista de erros
                    $erros = str_replace(['<ul>', '</ul>'], '', $erros);
                    $erros = "<ul>{$erros}</ul>";

                    $model->empresa_id = Yii::$app->user->identity->empresa_id;
        		    $model->data_cadastro = date('d/m/Y H:i:s');
        		    $model->usuario = Yii::$app->user->identity->colaborador_id;
        		    $errotab = $model->getErrors(); 
        		    $transaction->rollBack();
        		   
        		    return $this->render('create', [
    				    'model' => $model,
    				    'erros' => $erros,
                        'errotab' => $errotab,
        		   ]);
        		   
                }
                   
                // salva as alteracoes
                if ($model->save()) {
                    $transaction->commit();
                    \Yii::$app->getSession()->setFlash('success', '<i class="fa fa-check"></i> Cliente cadastrado com Sucesso!');
                    return $this->redirect(['index']);
                }        	 
            }catch (\Exception $e) {
        		$transaction->rollBack();
                //Salva log do erro, ver tabela log_error
                $log = new SystemError(['mensagem' => "Exception: tabela: ".$this->id.", action: ".$this->action->actionMethod, 'arquivo' => $e->getFile(), 'arquivo_metodo' => $this->action->actionMethod, 'arquivo_linha' => $e->getLine(), 'tipo' => SystemError::TIPO_FATAL, 'exception' => $e->getMessage(), 'tratamento' => SystemError::TRATAMENTO_ERRO_NAO_TRATADO]);
                $log->save();
        		throw $e;
        	}
        	
        }
        
    	$valores = array();
    	$modelFaturas = array();
    	$errotab = $model->getErrors();
    	 
        return $this->render('create', [
            'model' => $model,
       		'erros' => $erros,
            'errotab' => $errotab,
        ]);
    }

    /**
     * Atualiza um cliente
     */
    public function actionUpdate($id)
    {
        if (isset($_REQUEST['forma'])) {
	    	$tipo   = $_REQUEST['tipo'];
	    	$existe = 0;
    	} else {
	        $model = $this->findModel($id);
    	}
    	
    	$erros = '';
    	
    	if ($model->tipo == "F") {
            \Yii::$app->getSession()->setFlash('danger', '<i class="fa fa-exclamation-triangle"></i> O cliente '. $model->nome .' não pode ser editado' . $linkativo);
            return $this->redirect(['index']);
    	}

    	if ($model->tipo == Clientes::TIPO_PESSOA_JURIDICA) {
            $model->razao_social = strtoupper($model->nome);
    		$model->fantasia     = strtoupper($model->apelido);
    	} else {
    		$model->nome    = strtoupper($model->nome);
    		$model->apelido = strtoupper($model->apelido);
            $model->indie   = 9;
    	}

        if ($model->load(\Yii::$app->request->post())) {
            $post = \Yii::$app->request->post('Clientes');
        	
            if ($model->id_cidade) {
                $cidades = Cidade::findOne(['id'=>$model->id_cidade]);
                $model->codmunic =$cidades->cod_ibge;
            }
									
			if ($post['indie'] != null) {
                $model->indie = $post['indie'];
			}
        	
			// valida o tipo do cliente
			if ($model->tipo == Clientes::TIPO_PESSOA_JURIDICA) {
			    $model->cpf  = null;
                $model->nome    = strtoupper($_REQUEST['Clientes']['razao_social']);
                $model->apelido = strtoupper($_REQUEST['Clientes']['fantasia']);
            } else {
                $model->cnpj = null;
                $model->nome    = strtoupper($model->nome);
                $model->apelido = strtoupper($model->apelido);
            }
      
            $model->endereco    = strtoupper($model->endereco);
            $model->complemento = strtoupper($model->complemento);
            $model->bairro      = strtoupper($model->bairro);
            
            if (!empty($model->dtanascto)) {
                $model->dtanascto = Helper::formatDateToSave($model->dtanascto, Helper::DATE_DEFAULT);
            }
            
            // valida os dados de endereço
            if (
                $model->cep != null || $model->bairro != null || $model->id_estado != null ||
                $model->id_cidade != null || $model->endereco != null || $model->numero != null
            ) {
                if ($model->cep == null) {
                    $erros .= "<li> Quando informado, o endereço deve ser completo. Por favor, preencha o \"CEP\".</li>";
                }
                if ($model->numero == null) {
                    $erros .= "<li> Quando informado, o endereço deve ser completo. Por favor, preencha o \"Número\".</li>";
                }
                if ($model->bairro == null) {
                    $erros .= "<li> Quando informado, o endereço deve ser completo. Por favor, preencha o \"Bairro\".</li>";
                }
                if ($model->id_estado == null) {
                    $erros .= "<li> Quando informado, o endereço deve ser completo. Por favor, preencha o \"Estado\".</li>";
                }
                if ($model->id_cidade == null) {
                    $erros .= "<li> Quando informado, o endereço deve ser completo. Por favor, preencha a \"Cidade\".</li>";
                }
                if ($model->endereco == null) {
                    $erros .= "<li> Quando informado, o endereço deve ser completo. Por favor, preencha o \"Logradouro\".</li>";
                }
                
                if (!empty($erros)) {
                    $erros = "<ul>{$erros}</ul>";
                }
            }
            
            // valida os attrs da class
            $erros .= $this->valida_campos($model);
            
            if (!empty($erros)) {
                // formata a lista de erros
                $erros = str_replace(['<ul>', '</ul>'], '', $erros);
                $erros = "<ul>{$erros}</ul>";
                
                $model->empresa_id    = Yii::$app->user->identity->empresa_id;
                $model->data_cadastro = date('Y-m-d H:i:s');
                $model->usuario       = Yii::$app->user->identity->colaborador_id;
                $valores = array();
                $modelFaturas = array();
                $errotab = $model->getErrors();
        		
                return $this->render('update', [
                    'model' => $model,
                    'erros' => $erros,
                    'errotab' => $errotab,
                ]);
                
            }

            $transaction = Yii::$app->db->beginTransaction();
      
            try {
                if ($model->save()) {
                    $transaction->commit();
                    Yii::$app->getSession()->setFlash('success', '<i class="fa fa-check"></i> Cliente atualizado com sucesso.');
                    return $this->redirect(['index']);
                } 
                    
                $erros = '';
                $errotab = $model->getErrors();
                $transaction->rollBack();
                
                return $this->render('update', [
                    'model' => $model,
                    'erros' => $erros,
                    'errotab' => $errotab,
                ]);
                
            } catch(\Exception $e) {
                $transaction->rollBack();
                
                $log = new SystemError(['mensagem' => "Exception: tabela: ".$this->id.", action: ".$this->action->actionMethod, 'arquivo' => $e->getFile(), 'arquivo_metodo' => $this->action->actionMethod, 'arquivo_linha' => $e->getLine(), 'tipo' => SystemError::TIPO_FATAL, 'exception' => $e->getMessage(), 'tratamento' => SystemError::TRATAMENTO_ERRO_NAO_TRATADO]);
                $log->save();
                throw $e;
            }     
        }
        
        $model->cpf = Helper::maskBackend($model->cpf, Helper::MASK_CPF);
        $model->cnpj = Helper::maskBackend($model->cnpj, Helper::MASK_CNPJ);
        
        if (!empty($model->dtanascto)) {
            $model->dtanascto = Helper::formatDateToDisplay($model->dtanascto, Helper::DATE_DEFAULT);
        }
        
        $model->dtault_compra = Helper::formatDateToDisplay($model->dtault_compra, Helper::DATE_DEFAULT);
        $model->data_cadastro = Helper::formatDateToDisplay($model->data_cadastro, Helper::DATE_DEFAULT, ['removeMask' => true]);
        $errotab = $model->getErrors();
    	
        if (isset($_REQUEST['forma'])) {
            return $this->renderAjax('update', [
                'model'   => $model,
                'erros'   => $erros,
                'errotab' => $errotab,
            ]);
        }
        
        return $this->render('update', [
            'model'   => $model,
            'erros'   => $erros,
            'errotab' => $errotab,
        ]);
    }
    
    /**
     * validação de endereço do cliente
     */
	public function actionEndereco()
	{

		$retorno = new \stdClass();

		$post = Yii::$app->request->post();
		if (!isset($post['id'])){
			$retorno->success = false;
			$retorno->message = 'Sem o parâmetro ID.';
			$retorno->data = $post;
			return Json::encode($retorno);
		}

		$cliente = Clientes::findOne($post['id']);

		if ($cliente){

			if (
                (!$cliente->id_cidade || !$cliente->id_estado || !$cliente->cep || !$cliente->endereco || !$cliente->numero || !$cliente->bairro) &&
                (!$cliente->id_cidade_cobr || !$cliente->id_estado_cobr || !$cliente->cep_cobr || !$cliente->ender_cobr || !$cliente->nrend_cobr || !$cliente->bairro_cobr)
			){
				$retorno->success = false;
				$retorno->message = 'O cliente não possui nenhum endereço válido cadastrado.';
				$retorno->data = [];
				return Json::encode($retorno);
			} else {
				$primario = [
						'cep' => $cliente->cep,
						'endereco' => $cliente->endereco,
						'numero' => $cliente->numero,
						'complemento' => $cliente->complemento,
						'bairro' => $cliente->bairro,
						'id_cidade' => $cliente->id_cidade,
						'cidade' => $cliente->getCidade(),
						'id_estado' => $cliente->id_estado,
						'estado' => $cliente->getEstadoFederacao(),
						'option' => $cliente->endereco . ', ' . $cliente->numero . ' ' . $cliente->complemento . ', ' . $cliente->bairro,
				];
				$cobranca = [
						'cep' => $cliente->cep_cobr,
						'endereco' => $cliente->ender_cobr,
						'numero' => $cliente->nrend_cobr,
						'complemento' => $cliente->compl_cobr,
						'bairro' => $cliente->bairro_cobr,
						'id_cidade' => $cliente->id_cidade_cobr,
						'cidade' => $cliente->cidadeCobranca,
						'id_estado' => $cliente->id_estado_cobr,
						'estado' => $cliente->estadoFederacaoCobranca,
						'option' => $cliente->ender_cobr . ', ' . $cliente->nrend_cobr . ' ' . $cliente->compl_cobr . ', ' . $cliente->bairro_cobr,
				];
			}

			$retorno->success = true;
			$retorno->message = 'Endereços do cliente encontrados.';

			// se o endereço principal e o de cobrança forem iguais manda apenas um
			if ($primario['option'] != $cobranca['option'])
				$retorno->data = ['0' => $primario, '1' => $cobranca];
			else // se não manda os dois
				$retorno->data = [$primario];
			// se estiver apenas mudando o combo endereco_selecionado para o endereco de cobranca
			// envia apenas o endereco de cobranca
			if (isset($post['cobranca']) && $post['cobranca'] == 1)
				$retorno->data = [$cobranca];

		} else {
			$retorno->success = false;
			$retorno->message = 'Cliente não encontrado.';
			$retorno->data = [];
		}

		return Json::encode($retorno);
	}

	/**
	 * Verifica o credito do disponivel do cliente
	 * @return array
	 */
	public function actionVerificarCredito()
	{
	    // acessível apenas por post
	    if (!$post = \Yii::$app->request->post()) {
	        throw new NotFoundHttpException();
	    }
	    
	    $retorno = new \stdClass();
	    $retorno->success = 1;
	    
	    try {
	        // se nao for enviado o id do cliente, tenta busca o cliente pelo pedido
	        if (!isset($post['cliente_id']) && isset($post['pedido_id']) && !empty($post['pedido_id'])) {
	            $cliente_id = Pedido::find()->where(['id' => $post['pedido_id']])->select('cliente_id')->asArray(false)->one()->cliente_id;
	        }
	        
	        // busca o cliente pelo id enviado ou pelo pedido
	        if (!$cliente = Clientes::find()->where(['id_cliente' => isset($post['cliente_id']) ? $post['cliente_id'] : $cliente_id])->one()) {
	            throw new UserException('O cliente não foi encontrado.'); 
	        }
	        
	        // verifica se o pedido atual já possui algum pagamento utilizando credito
	        $pagamentoCredito = 0;
	        if (isset($post['pedido_id']) && !empty($post['pedido_id'])) {
	            $pgtos = PedidoPagamento::find()->where(['pedido_id' => $post['pedido_id']])->asArray(true)->all();
	            foreach($pgtos as $pgto) {
	                if($pgto->formaPagamento->credito_cliente == FormaPagamento::CREDITO_SIM) {
	                    $pagamentoCredito += $pgto->valor;
	                }
	            }
	        }
	        
	        $retorno->data = [
	            'credito' => $cliente->getCreditoSaldo() - $pagamentoCredito,
	            'cliente' => $cliente->nome,
	            'cliente_id' => $cliente->id_cliente,
	        ];
	        
	    } catch(UserException $e) {
	        $retorno->success = 2;
	        $retorno->message = $e->getMessage();
	        $retorno->data = [];
	    } catch(\Exception $e) {
	        $log = new SystemError(['mensagem' => "Exception: tabela: ".$this->id.", action: ".$this->action->actionMethod, 'arquivo' => $e->getFile(), 'arquivo_metodo' => $this->action->actionMethod, 'arquivo_linha' => $e->getLine(), 'tipo' => SystemError::TIPO_FATAL, 'exception' => $e->getMessage(), 'tratamento' => SystemError::TRATAMENTO_ERRO_NAO_TRATADO]);
	        $log->save();
	        
	        $retorno->success = 0;
	        $retorno->message = $e->getMessage();
	        $retorno->data = [];
	    }
	    
	    return Json::encode($retorno);
	}
	
    public function actionDocumentos()
    {

    	$id_codigo = $_REQUEST['id'];

    	$tipo   = $_REQUEST['tipo'];
    	$existe = 0;
    	$id     = 0;
    	$cod    = 0;

    	if ($tipo == 1) {
	    	$cpf = Helper::removeMascara($_REQUEST['cpf']);
	    	$clientes = Clientes::findOne(['cpf' => $cpf]);
	    	if ($clientes) {
	    		$id_cliente = $clientes->id_cliente;
	    		if ($id_codigo != $id_cliente) {
		    		$cod = $clientes->empresa_id;
		    		$id = $clientes->id_cliente;
		    		$existe = 1;
	    		}
	    	}
    	}

    	if ($tipo == 2) {
    		$cnpj = Helper::removeMascara($_REQUEST['cnpj']);
    		$clientes = Clientes::findOne(['cnpj' => $cnpj]);
    		if ($clientes) {
	    		$id_cliente = $clientes->id_cliente;
	    		if ($id_codigo != $id_cliente) {
    				$cod = $clientes->empresa_id;
	    			$id = $clientes->id_cliente;
	    			$existe = 1;
	    		}
    		}
    	}

    	$retorno = array(
			'empresa_id' => $cod,
			'id' => $id,
			'existe' => $existe,
    	);

    	echo json_encode($retorno);
    }
    
    /**
     * Deleta um cliente existente
     */
    public function actionDelete($id)
    {
        // busca o cliente
        $model = $this->findModel($id);
         
        // valida se o cliente é ativo
        $linkativo = '';
        if ($model->ativo == Clientes::CLIENTE_ATIVO) {
            $linkativo = "É possível Inativar o cliente clicando <a href='/clientes/update/{$id}'>aqui</a>";
        }
    	
    	try {
    	    // busca os pedidos do cliente
        	$modelPedidos = Pedido::findAll(['empresa_id' => \Yii::$app->user->identity->empresa_id, 'cliente_id' => $id]);

        	// valida se o cliente pode ser deletado
    	    if ($model->divida_atual > 0) {
    	         throw new UserException('O cliente não pode ser deletado pois possui dívidas registradas no sistema');
    	    } else if($modelPedidos){
    	        throw new UserException('O cliente não pode ser deletado pois já realizou pedidos no sistema');
    	    }
    	    
    	    // deleta o cliente
	        $model->delete();
	        \Yii::$app->getSession()->setFlash('success', '<i class="fa fa-check"></i>&nbsp; Cliente deletado com sucesso.');
    	    
    	} catch (UserException $e) {
    	    \Yii::$app->getSession()->setFlash('danger', "<i class='fa fa-exclamation-triangle'></i>&nbsp; {$e->getMessage()}. {$linkativo}");
    	} catch (IntegrityException $e) {
            \Yii::$app->getSession()->setFlash('danger', "<i class='fa fa-exclamation-triangle'></i>&nbsp; Cliente não pode ser excluído pois existem dados relacionados a ele. {$linkativo}");          
        } catch (\Exception $e) {
            $log = new SystemError(['mensagem' => "Exception: tabela: ".$this->id.", action: ".$this->action->actionMethod, 'arquivo' => $e->getFile(), 'arquivo_metodo' => $this->action->actionMethod, 'arquivo_linha' => $e->getLine(), 'tipo' => SystemError::TIPO_FATAL, 'exception' => $e->getMessage(), 'tratamento' => SystemError::TRATAMENTO_ERRO_NAO_TRATADO]);
            $log->save();
            \Yii::$app->getSession()->setFlash('danger', $e->getMessage() . $linkativo);
        }
        
    	return $this->redirect(['index']);
    }

    public function actionList($q = null, $id = null)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['items' => ['id' => '', 'text' => '']];

        if (!is_null($q)) {
            $query = new Query();
            $query->select('id_cliente AS id, nome, nome AS text, cpf, cnpj, email')
                ->from('clientes')
                ->where(['like', 'cpf', $q])
                ->orWhere(['like', 'cnpj', $q])
                ->orWhere(['like', 'nome', $q])
                ->orWhere(['like', 'email', $q])
                ->orderBy(['nome' => 'ASC'])
                ->limit(20);
            $command = $query->createCommand();
            $data = $command->queryAll();

            $out['total_count'] = count($data);
            $out['items'] = array_values($data);

        if(!empty( $out['items'])){
                $_count=0;
                foreach( $out['items'] as $key=>$_itens){
                    if($key=='cpf'){
                        if(!empty($_itens['cpf'])){
                            $out['items'][$_count]['cpf']= Helper::maskBackend($_itens['cpf'], Helper::MASK_CPF);
                        }
                        if(!empty($_itens['cnpj'])){
                            $out['items'][$_count]['cnpj']= Helper::maskBackend($_itens['cnpj'], Helper::MASK_CNPJ);
                        }
                    }
                    $_count++;
                }
            }

        }
        elseif ($id > 0) {
            $out['items'] = ['id' => $id, 'text' => Clientes::find($id)->name];
        }

        return $out;
    }

    /**
     * cria um array com os clientes para ser utilizado na tela de pedido especial
     */
    public function actionListEspecial($q = null, $id = null)
    {
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $out = ['items' => ['id' => '', 'text' => '']];

        if (!is_null($q)) {
            $query = new Query();
            $query->select('id_cliente AS id, nome, nome AS text, cpf, email, cnpj')
                ->from('clientes')
                ->where(['like', 'cpf', $q])
                ->orWhere(['like', 'nome', $q])
				->orWhere(['like', 'cnpj', $q])
                ->orWhere(['like', 'email', $q])
                ->orderBy(['nome' => 'ASC'])
                ->limit(20);
            $command = $query->createCommand();
            $data = $command->queryAll();

            $out['total_count'] = count($data);
            $out['items'] = array_values($data);
        
            foreach($out['items'] as $key => $item) {
                
                $out['items'][$key]['shortName'] = Helper::shortName($out['items'][$key]['nome'], 25);
                $out['items'][$key]['shortEmail'] = Helper::shortName($out['items'][$key]['email'], 45);
                
                if($out['items'][$key]['cpf']) {
                    $out['items'][$key]['cpf'] = Helper::maskBackend($out['items'][$key]['cpf'], Helper::MASK_CPF);
                }else if($out['items'][$key]['cnpj']) {
                    $out['items'][$key]['cnpj'] = Helper::maskBackend($out['items'][$key]['cnpj'], Helper::MASK_CNPJ);
                }   
            }
            
        }else if ($id > 0) {
            $out['items'] = ['id' => $id, 'text' => Clientes::find($id)->name];
        }
        
        return $out;
    }

    public function actionLists($id)
    {
        //$id = $_REQUEST['id_estado'];

    	$countCidade = Cidade::find()
    	->where(['estado_federacao_id' => $id])
    	->count();

    	$cidades = Cidade::find()
    	->where(['estado_federacao_id' => $id])
    	->all();

    	if($countCidade>0){
    		$ret = '<option></option>';
    		foreach($cidades as $cidade){
    			$ret.= "<option value='". $cidade->id ."'>". $cidade->descricao ."</option>";
    		}
    		echo $ret;
    	}
    	else{
    		echo "<option>-</option>";
    	}
    }

	public function atualizar_fatura($data_vencimento,$valor_parcela,$tipo)
	{
		$data_base = date('Ymd');
		$data_final =  Helper::formatDateToDisplay($data_base, Helper::DATE_DEFAULT);
		$data_inicial = $data_vencimento;
	
		$time_inicial = Helper::formatDateToSave($data_inicial, Helper::DATE_TIMESTAMP);
		$time_final = Helper::formatDateToSave($data_final, Helper::DATE_TIMESTAMP);
		$diferenca = $time_final - $time_inicial; // 19522800 segundos
		$dias = (int)floor( $diferenca / (60 * 60 * 24)); // 225 dias

		if ($tipo == 0) return $dias;

		$vlr_juros = 0;

		if ($dias > 0) {
			$taxa = ((10/30)*$dias);
			$vlr_juros = ($valor_parcela * $taxa / 100);
		}

		if ($tipo == 1) return $vlr_juros;
	}

	/**
	 * Valida os campos da class
	 * 
	 * @param Clientes $model
	 * @return string
	 */
	public function valida_campos($model) 
	{
		$erros = '';
        if (!$model->validate()) {
            $erros = Helper::renderModelErrors($model->getErrors());            
        }
        
        return $erros;
	}

    /**
     * Finds the Clientes model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $empresa_id
     * @param integer $id_cliente
     * @return Clientes the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Clientes::findOne(['id_cliente' => $id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }    
}
