<?php
namespace app\controllers;

use app\base\Helper;
use app\models\Email;
use app\models\Estado;
use app\models\Cliente;
use app\models\Endereco;
use app\models\Telefone;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use app\models\ClienteSearch;
use yii\web\NotFoundHttpException;
use app\models\Contrato;

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
     * 
     * @var string $index => nome do campo a ser pesquisado
     * @var string $value => valor do campo
     * @return mixed
     */
    public function actionIndex($index = '', $value = '')
    {
        $searchModel = new ClienteSearch();
        
        // seta os params do filtro
        if (!$params = \Yii::$app->request->post()) {
            $params = \Yii::$app->request->queryParams;
        }

        // seta o nome da pesquisa rápida
        if (!empty($index) && !empty($value)) {
        	$params['ClienteSearch'][$index] = $value;
        }
        
        // realiza o filtro
        $dataProvider = $searchModel->search($params);
                
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
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
	        		$model->documento = Helper::unmask($post['cliente-cpf'], true);
	        		$model->nome_social = $post['cliente-apelido'];
	        	} else {
	        		$model->nome = $post['cliente-razao-social'];
	        		$model->documento = Helper::unmask($post['cliente-cnpj'], true);
	        		$model->nome_social = $post['cliente-fantasia'];
	        	}
	        	
	        	// remove a mascara do rg
	        	$model->rg = Helper::unmask($model->rg);
	        	
	        	// salva o cliente
	        	if (!$model->save()) {
	        		throw new \Exception(Helper::renderErrors($model->getErrors()));
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
	        				$modelTelefone->contato    = $telefone['contato'];
	        				$modelTelefone->whatsapp   = $telefone['whatsapp'];
	        				$modelTelefone->ativo      = $telefone['ativo'];
	        				$modelTelefone->observacao = $telefone['observacao'];
	        				
	        				// salva o telefone
	        				if (!$modelTelefone->save()) {
	        					throw new \Exception(Helper::renderErrors($modelTelefone->getErrors()));
	        				}
	        			}
	        		}
	        	}
	        	
	        	// salva os telefones cadastrados
	        	if (isset($post['Emails']) && is_array($post['Emails'])) {
	        		foreach ($post['Emails'] as $email) {
	        			if (isset($email['email']) && !empty($email['email'])) {
	        				// cria a model de email e
	        				// seta os dados
	        				$modelEmail = new Email();
	        				$modelEmail->id_cliente = $model->id ? $model->id : $model->getPrimaryKey();
	        				$modelEmail->email      = $email['email'];
	        				$modelEmail->ativo      = Email::SIM;
	        				$modelEmail->observacao = $email['observacao'];
	        				
	        				// salva o email
	        				if (!$modelEmail->save()) {
	        					throw new \Exception(Helper::renderErrors($modelEmail->getErrors()));
	        				}
	        			}
	        		}
	        	}
	        	
	        	// salva os telefones cadastrados
	        	if (isset($post['Enderecos']) && is_array($post['Enderecos'])) {
	        		foreach ($post['Enderecos'] as $endereco) {
	        			if (isset($endereco['logradouro']) && !empty($endereco['logradouro'])) {
	        				// cria a model de endereco e
	        				// seta os dados
	        				$modelEndereco = new Endereco();
	        				$modelEndereco->id_cliente  = $model->id ? $model->id : $model->getPrimaryKey();
	        				$modelEndereco->logradouro  = $endereco['logradouro'];
	        				$modelEndereco->numero      = $endereco['numero'];
	        				$modelEndereco->complemento = $endereco['complemento'];
	        				$modelEndereco->bairro      = $endereco['bairro'];
	        				$modelEndereco->cep         = $endereco['cep'];
	        				$modelEndereco->cidade_id   = $endereco['cidade_id'];
	        				$modelEndereco->estado_id   = $endereco['estado_id'];
	        				
	        				
	        				// salva o endereco
	        				if (!$modelEndereco->save()) {
	        					throw new \Exception(Helper::renderErrors($modelEndereco->getErrors()));
	        				}
	        			}
	        		}
	        	}
	        	
	        	$transaction->commit();
	        	\Yii::$app->session->setFlash('success', '<i class="fa fa-check"></i>&nbsp; O cliente foi cadastrado com sucesso.');
	        	return $this->redirect(['index']);
        	} catch (\Exception $e) {
        		$transaction->rollBack();
        		\Yii::$app->session->setFlash('danger', "<i class='fa fa-exclamation-triangle'></i>&nbsp; Erros: {$e->getMessage()}");
        	}
        }
          
        return $this->render('create', [
        	'model' => $model,
        	'layout' => $model->tipo && $model->tipo == Cliente::TIPO_JURIDICO ? 'J' : 'F',
            'estados' => ArrayHelper::map(Estado::find()->all(), 'id', 'nome'),
        ]);
        
    }

    /**
     * Altera um registro
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($post = \Yii::$app->request->post()) {
        	try {
        		$transaction = \Yii::$app->db->beginTransaction();
        		
        		// carrega os dados da model
        		$model->load($post);
        		
        		// valida o tipo do cliente
        		if ($model->tipo == Cliente::TIPO_FISICO) {
        			$model->nome = $post['cliente-nome'];
        			$model->documento = Helper::unmask($post['cliente-cpf'], true);
        			$model->nome_social = $post['cliente-apelido'];
        		} else {
        			$model->nome = $post['cliente-razao-social'];
        			$model->documento = Helper::unmask($post['cliente-cnpj'], true);
        			$model->nome_social = $post['cliente-fantasia'];
        		}
        		
        		// remove a mascara do rg
        		$model->rg = Helper::unmask($model->rg);
        		
        		// salva o cliente
        		if (!$model->save()) {
        			throw new \Exception(Helper::renderErrors($model->getErrors()));
        		}
        		
        		// verifica se algum telefone foi enviado
        		if (isset($post['Telefones']) && is_array($post['Telefones'])) {
        			// deleta os registros
        			Telefone::deleteAll(['id_cliente' => $model->id]);
        			
        			// cadastra os telefones enviados
        			foreach ($post['Telefones'] as $telefone) {
        				// cia a model
        				$modelTelefone = new Telefone();
        				
        				if (isset($telefone['numero']) && !empty($telefone['numero'])) {
        					// verifica se esta alterando um telefone ja cadastrado
        					if (isset($telefone['id']) && !empty($telefone['id']) && is_int($telefone['id'])) {
        						$modelTelefone = Telefone::findOne(['id' => $telefone['id'], 'id_cliente' => $model->id]);
        					}
        					
        					// seta os dados
        					$modelTelefone->id_cliente = $model->id;
        					$modelTelefone->numero     = $telefone['numero'];
        					$modelTelefone->ramal      = $telefone['ramal'];
        					$modelTelefone->tipo       = $telefone['tipo'];
        					$modelTelefone->contato    = $telefone['contato'];
        					$modelTelefone->whatsapp   = $telefone['whatsapp'];
        					$modelTelefone->ativo      = $telefone['ativo'];
        					$modelTelefone->observacao = $telefone['observacao'];
        					
        					// salva o telefone
        					if (!$modelTelefone->save()) {
        						throw new \Exception(Helper::renderErrors($modelTelefone->getErrors()));
        					}
        				}
        			}
        		}
        		
        		// verifica se algum email foi enviado
        		if (isset($post['Emails']) && is_array($post['Emails'])) {
        			// deleta os registros
        			Email::deleteAll(['id_cliente' => $model->id]);
        			
        			// cadastra os emails enviados
        			foreach ($post['Emails'] as $email) {
        				// cria a model
        				$modelEmail = new Email();
        				
        				if (isset($email['email']) && !empty($email['email'])) {
        					// verifica se esta alterando um email ja cadastrado
        					if (isset($email['id']) && !empty($email['id']) && is_int($email['id'])) {
        						$modelEmail = Email::findOne(['id' => $email['id'], 'id_cliente' => $model->id]);
        					}
        					
        					// seta os dados
        					$modelEmail->id_cliente = $model->id;
        					$modelEmail->email      = $email['email'];
        					$modelEmail->ativo      = Email::SIM;
        					$modelEmail->observacao = $email['observacao'];
        					
        					// salva o email
        					if (!$modelEmail->save()) {
        						throw new \Exception(Helper::renderErrors($modelEmail->getErrors()));
        					}
        				}
        			}
        		}
        		
        		// verifica se algum endereco foi enviado
        		if (isset($post['Enderecos']) && is_array($post['Enderecos'])) {
        			// deleta os registros
        			Endereco::deleteAll(['id_cliente' => $model->id]);
        			
        			// cadastra os enderecos enviados
        			foreach ($post['Enderecos'] as $endereco) {
        				// cia a model
        				$modelEndereco = new Endereco();
        				
        				if (isset($endereco['logradouro']) && !empty($endereco['logradouro'])) {
        					// verifica se esta alterando um endereco ja cadastrado
        					if (isset($endereco['id']) && !empty($endereco['id']) && is_int($endereco['id'])) {
        						$modelEndereco = Endereco::findOne(['id' => $endereco['id'], 'id_cliente' => $model->id]);
        					}
        					
        					// seta os dados
        					$modelEndereco->id_cliente  = $model->id;
        					$modelEndereco->logradouro  = $endereco['logradouro'];
        					$modelEndereco->numero      = $endereco['numero'];
        					$modelEndereco->complemento = $endereco['complemento'];
        					$modelEndereco->bairro      = $endereco['bairro'];
        					$modelEndereco->cep         = $endereco['cep'];
        					$modelEndereco->cidade_id   = $endereco['cidade_id'];
        					$modelEndereco->estado_id   = $endereco['estado_id'];
        					
        					
        					// salva o endereco
        					if (!$modelEndereco->save()) {
        						throw new \Exception(Helper::renderErrors($modelEndereco->getErrors()));
        					}
        				}
        			}
        		}
        		
        		$transaction->commit();
        		\Yii::$app->session->setFlash('success', '<i class="fa fa-check"></i>&nbsp; O cliente foi alterado com sucesso.');
        		return $this->redirect(['index']);
        	} catch (\Exception $e) {
        		$transaction->rollBack();
        		\Yii::$app->session->setFlash('danger', "<i class='fa fa-exclamation-triangle'></i>&nbsp; Erros: {$e->getMessage()}");
        	}
        }
        
        return $this->render('update', [
            'model' => $model,
        	'layout' => $model->tipo && $model->tipo == Cliente::TIPO_JURIDICO ? 'J' : 'F',
            'estados' => ArrayHelper::map(Estado::find()->all(), 'id', 'nome'),
        ]);
    }

    /**
     * Busca todos os contratos do clente/cadastra um contrato
     */
    public function actionContrato($id)
    {
        // busca a model
        $model = $this->findModel($id);
        
        // verifica se o cliente ja possui contratos
        // se sim, lista os contratos do cliente
        if (Contrato::findAll(['id_cliente' => $id])) {
            return $this->redirect(['/contrato', 'index' => 'id_cliente', 'value' => $id]);
        }
        
        // se nao achou nenhum contrato redireciona para a tela de cadastro do contrato
        return $this->redirect(['/contrato/create', 'id_cliente' => $id]);
    }
    
    /**
     * Deleta um registro
     */
    public function actionDelete($id)
    {
    	// busca a model
        $model = $this->findModel($id);
        
        try {
	        $transaction = \Yii::$app->db->beginTransaction();
	        
	        // deleta o cliente
	        $model->delete();
	        
	        $transaction->commit();
	        \Yii::$app->session->setFlash('success', '<i class="fa fa-check"></i>&nbsp; O cliente foi excluído com sucesso.');
	    } catch (\Exception $e) {
	    	$transaction->rollBack();
	    	\Yii::$app->session->setFlash('danger', "<i class='fa fa-exclamation-triangle'></i>&nbsp; Erros: {$e->getMessage()}");
	    }
        
        return $this->redirect(['index']);
    }

    /**
     * Realiza a pesquisa rápida de um ou mais clientes
     */
    public function actionQuickSearch($value = null)
    {    	
    	// busca todos os clientes com o nome passado
    	// se nao achou nenhum cliente pelo nome, tenta pesquisar pelo cpf/cnpj
    	$index = 'nome';
    	if (!$cliente = Cliente::find()->where(['like', 'nome', $value])->all()) {
    		// remove a mascara
    		$value = Helper::unmask($value, true);
    		
    		// busca pelo busca pelo cpf/cnpj
    		$cliente = Cliente::find()->where(['like', 'documento', $value])->all();
    		$index = 'documento';
    	}

    	// se achou apenas um cliente, redireciona para a página do cliente
    	// se houver mais clientes com este nome
    	// redireciona para a página de listagem de clientes
    	if (!empty($value) && count($cliente) == 1) {
    		return $this->redirect(['update', 'id' => $cliente[0]->id]);
    	}
    	
    	return $this->redirect(['index', 'index' => $index, 'value' => $value]);
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
        
        // params da busca rapida
        if (isset($q['quick'])) {
        	// se enviou um numero pesquisa pelo documento
        	// senao, busca pelo nome
        	if (is_numeric($q['quick'])) {
	        	$query->select('documento')->andWhere(['like', 'documento', $q['quick']])->distinct(true); 
        	} else {
        		$query->select('nome')->andWhere(['like', 'nome', $q['quick']])->distinct(true);
        	}
        }
        
        
        $model = $query->all();
        
        if ($model != null) {
            foreach ($model as $key) {
                if (isset($q['nome'])) { $data[]['value'] = $key['nome']; }
                if (isset($q['telefone'])) { $data[]['value'] = $key['telefone']; }
                if (isset($q['documento'])) { $data[]['value'] = $key['documento']; }
                if (isset($q['quick'])) { 
                	if (isset($key['documento'])) {    
                		$documento = $key['documento'];
                		
                		// verifica se é um cpf ou cnpj, se ssim formata o documento
                		if (strlen($documento) == 11) {
                			$documento = Helper::mask($documento, Helper::MASK_CPF);
                		} elseif (strlen($documento) == 14) {
                			$documento = Helper::mask($documento, Helper::MASK_CNPJ);
                		}
                		
                		$data[]['value'] = $documento; 
                	} else {
                		$data[]['value'] = $key['nome'];
                	}
                }
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
