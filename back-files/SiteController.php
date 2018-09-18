<?php
namespace app\controllers;

use Yii;
use yii\helpers\Url;
use yii\helpers\Json;
use yii\web\Controller;
use yii\base\UserException;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;

use app\base\Util;
use app\models\Email;
use app\models\Pedido;
use app\models\Remessa;
use app\models\Empresa;
use app\models\NfeEnvio;
use app\base\SystemError;
use app\models\UserConfig;
use app\models\EmpresaConfig;
use app\models\nfe\NfeEnviada;
use app\models\nfe\NfeFactory;
use app\models\ResetPasswordForm;
use app\models\nfe\NfeParametros;
use app\models\nfe\NfeCertificado;
use app\models\NotificaAtualizacao;
use app\modules\auth\models\AuthUser;
use app\models\NotificacaoUsuarioView;
use app\models\NotificaAtualizacaoUser;
use app\models\PasswordResetRequestForm;

use app\models\nfe\NfeStat;
use app\base\LoginException;
use app\base\InvalidActionException;
use app\modules\auth\models\LoginForm;

use NFePHP\Common\Exception\SoapException;
use League\Flysystem\FileNotFoundException;

class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['reset-senha', 'reset-password'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @inheritDoc
     * @see \yii\base\Controller::actions()
     */
    public function actions()
    {
        return [
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }
    
    /**
     * Exibe a tela de erro do sistema
     */
    public function actionError()
    {
        if (\Yii::$app->user->isGuest) {
            return $this->redirect(['site/login'])->send();
        } else {
            $this->layout = '@app/views/layouts/main';
        }
        
        $exception = \Yii::$app->errorHandler->exception;
        
        // vars do erro da exception
        $title = 'Erro';
        $label = 'warning';
        $message = method_exists($exception, 'getMessage') ? $exception->getMessage() : 'Ocorreu um erro inesperado.';
        
        // notifica o erro da exception por email
        SystemError::SendException($exception);
        
        if ($exception !== null) {
            // mensagens de erro personalizadas para not found e forbidden
            if ($exception instanceof ForbiddenHttpException) {
                $title = 'Não Permitido';
                $label = 'info';
                $message = 'Oops... Parece que você não está autorizado a realizar esta ação.';
                $link = Url::to(['/auth/permissao', 'id' => \Yii::$app->user->id]);
                $message .= "<br><br> <small style='color: #fff;'>(Para verificar suas permissões consulte a <a href='{$link}' target='_blank'>tela de permissões do sistema</a>. Se não tiver acesso a esta tela, solicite ao usuário administrador)</small>";
            } elseif ($exception instanceof NotFoundHttpException) {
                $title = 'Não Encontrado';
                $label = 'info';
                $message = 'Oops... Parece que não encontramos a página que está procurando.';
            } elseif ($exception instanceof InvalidActionException) {
                $title = 'Problema com o Certificado';
                $label = 'danger';
                $message = $exception->getMessage();
                $tipo = 'certificado';
            }
            
            return $this->render('error', [
                'message'   => $message,
                'exception' => $exception,
                'title'     => $title,
                'label'     => $label,
                'tipo'      => $tipo,
            ]);
        }
    }

    /**
     * Exibe a tela inicial do sistema (dashboard) ou redireciona para o login
     */
    public function actionIndex() 
    {     
        // se for guest destroi a sessão atual
        // e redireciona para o login
        if (\Yii::$app->user->isGuest) {
            // desloga o usuario atual
            if (\Yii::$app->session->get('user.id') !== null) {
                LoginForm::setUserOffline();
            }
            
            return $this->redirect(['site/login'])->send();
        }

        // verifica se o usuário está offline,
        // e valida se session do usuário é valida
        if (!AuthUser::validateAccess()) {
            // redireciona para a tela de login
            return $this->redirect(['/site/logout',
                'messageLogin'       => 'warning',
                'messageLoginHeader' => 'Sessão Expirada',
                'messageLoginBody'   => 'Oops... '.ucfirst(\Yii::$app->user->identity->username).', parece que sua sessão expirou ou foi finalizada.'
            ]);
        }
        
        // valida o certificado da empresa e verifica se há algum aviso para exibir
        $avisoCertificado = NfeCertificado::validadeCertificado();        

        // recebe os widgets de preferência ou define um default
        $preferencias = UserConfig::getAllWidgets(['id_colaborador' => \Yii::$app->user->identity->colaborador_id]);

        // verifica se possui boletos ainda não inseridos na remessa
        // se sim, cria uma notificacao
        Remessa::verificarBoletos();
        
        // Verifica se será mostrada a modal com mensagen de nova versão para o usuário
        $notifica = NotificaAtualizacao::getShowNotificacaoToUser();        

        // remove todas as flash msg para não serem mostradas na dashboard inicial
        // exceto se for um flash disparado por erro do certificado (atualizado e exibido na dash)
        if (!\Yii::$app->session->hasFlash('certificado-status')) {
            \Yii::$app->session->removeAllFlashes();
        }
        
        return $this->render('index', [
            'aviso'        => $avisoCertificado, 
            'preferencias' => $preferencias, 
            'notifica'     => $notifica,
            'news'         => $news,
        ]);
    }
    
    /**
     * Realiza o login do usuário
     */
    public function actionLogin($messageLogin = null, $messageLoginHeader = null, $messageLoginBody = null)
    {
        // valida o usuario
        if (!\Yii::$app->user->isGuest) {
            return $this->redirect(['/']);
        }
        
        // remove os cookies do sistema
        $cookies = \Yii::$app->response->cookies->remove('_gercpn');
        // seta o layout de login
        $this->layout = 'login';
        
        // seta as models de login e da empresa
        $model = new LoginForm();
        $model_empresa = Empresa::find()->all();
        
        // verifica se foi enviado algo por post
        if ($post = \Yii::$app->request->post()) {
            // carrega os dados na model
            $model->load($post);
            
            // seta a empresa default caso nenhum tenha sido selecionada
            if (!$model->entity) {
                $model->entity = $model_empresa[0]->id;
            }
            
            try {
                // valida e realiza o login do usuario
                if ($model->login()) {
                    // verifica e executa a rotina de auto cancelamento de pedidos
                    // se a empresa estiver configurada para isso
                    $this->autoCancelarPedidos();
                    
                    // define a pasta da empresa
                    $pasta_sistema = $empresa->empresaConfig->pasta_sistema;
                    if (!empty($pasta_sistema)) {
                        \Yii::$app->session->set('PASTA_SISTEMA', $pasta_sistema);
                    }
                    
                    // verifica se a empresa já possui a configuração inicial minima
                    if ($model_empresa[0]->empresaConfig->configuracao_inicial == EmpresaConfig::EMPRESA_NAO_CONFIGURADA) {
                        return $this->render('@app/views/empresa/wizard_configuracao_inicial', [
                            'model'       => new Empresa(),
                            'config'      => new EmpresaConfig(),
                            'parametros'  => new NfeParametros(),
                            'certificado' => new NfeCertificado(),
                        ]);
                    }
                    
                    // redireciona para a home
                    return $this->redirect('index');
                }
            } catch (LoginException $e) {
                // atribui o valor das variaveis de retorno para a tela de login
                $messageLogin       = $e->status;
                $messageLoginHeader = $e->messageTitle;
                $messageLoginBody   = $e->message;
                $derrubarSession    = $e->derrubarSession;
            }
        }
        
        // renderiza a pagina de login
        return $this->render('@app/modules/auth/views/auth-user/login', [
            'model'              => $model,
            'entity'             => $model_empresa,
            'entity_count'       => count($model_empresa),
            'messageLogin'       => $messageLogin,
            'messageLoginHeader' => $messageLoginHeader,
            'messageLoginBody'   => $messageLoginBody,
            'derrubar'           => $derrubarSession,
        ]);
    }
    
    /**
     * Realiza o logout do usuário
     */
    public function actionLogout($messageLogin = null, $messageLoginHeader = null, $messageLoginBody = null)
    {
        // seta o usuario atual offline (deslogado)
        LoginForm::setUserOffline();
        
        if (empty($messageLogin)) {
            $this->redirect(['site/login']);
        } else {
            $this->redirect(['site/login',
                'messageLogin'       => $messageLogin,
                'messageLoginHeader' => $messageLoginHeader,
                'messageLoginBody'   => $messageLoginBody
            ]);
        }
    }
    
    /**
     * Action que verifica e renderiza as ultimas news (notificações)
     */
    public function actionNews() 
    {
        $retorno = new \stdClass();
        $retorno->success = 0;
        
        // busca a ultima atualizacao
        $news = NotificaAtualizacao::getShowUltimaNotificacao();
        
        if (!empty($news)) {
            $retorno->success = 1;
            $retorno->data = $this->renderAjax('_versao_notificacao', [
                'notifica' => $news,
                'modal_news' => true,
            ]);            
        }
        
        return Json::encode($retorno);
    }
    
    /**
     * Método que envia um contato por e-mail
     */
    public function actionEmailContato()
    {
        $email = new Email();
        $email->destinatario_principal = 'suporte@cpninformatica.com.br';
        $email->layout = Email::LAYOUT_CONTATO;
        
        if ($post = Yii::$app->request->post()) {
            $retorno = new \stdClass();
            $retorno->success = 1;
            try {
                $email->load($post);
                $email->assunto = "GER - ".\Yii::$app->user->identity->empresa_nome_fantasia.": Contato/{$email->assunto}";
                $email->setConteudoEmail();
                
                if (!$email->SendFast()) {                    
                    throw new \Exception();
                }
            } catch (\Exception $e) {
                $retorno->success = 0;
            }
            
            return Json::encode($retorno);
        }
        
        return $this->renderAjax('/email/create', [
            'model'          => $email,
            'renderPartial'  => true,
            'disabledAttach' => true,
            'disableSave'    => true,
            'removeSave'     => true,
        ]);
    }
    
    /**
     * Reseta a senha do usuário
     */
    public function actionResetSenha()
    {
        $this->layout = 'login';
        $model = new PasswordResetRequestForm();
        
        if ($post = \Yii::$app->request->post()) {
            try {
                // envia o email para resetar a senha
                $model->sendEmail($post[PasswordResetRequestForm][email]);
            } catch (\Exception $e) {
                return $this->redirect(['login', 
                    'messageLogin'       => 'danger', 
                    'messageLoginHeader' => 'Email inválido', 
                    'messageLoginBody'   => $e->getMessage()
                ]);
            }
            
            \Yii::$app->session->setFlash('success', 'Email enviado, verifique a sua caixa de entrada.');
            return $this->redirect(['login', 
                'messageLogin'       => 'info',
                'messageLoginHeader' => 'Redefinição de Senha',
                'messageLoginBody'   => 'O e-mail foi enviado para o endereço informado. Por favor, verifique a sua caixa de entrada para redefinir sua senha.'
            ]);
        }
        
        return $this->render('@app/modules/auth/views/auth-user/request-reset-senha', [
            'model' => $model,
        ]);
    }
    
    /**
     * Form da nova senha do usuario
     */
    public function actionNovaSenha($token = null)
    {
        $this->layout = 'login';
        
        try {
            // valida o token para resetar a senha
            if (empty($token)) {
                throw new \Exception('Token ausente.');
            }
            
            $model = new ResetPasswordForm($token);
            
        } catch (\Exception $e) { 
            // valida a mensagem de erro que sera exibida
            if ($e->getMessage() == 'Token ausente.') {
                $errorMessage = 'O token necessário para alteração da senha não está sendo informado no link.';
            } elseif ($e->getMessage() == 'Token inválido.') {
                $errorMessage = 'O token necessário para alteração é inválido ou está expirado.';
            } else {
                $errorMessage = $e->getMessage();
            }
            
            return $this->redirect(['login', 
                'messageLogin'       => 'danger', 
                'messageLoginHeader' => $e->getMessage(), 
                'messageLoginBody'   => $errorMessage
            ]);
        }
        
        // altera a senha do usuario
        if ($post = \Yii::$app->request->post()) {
            if ($model->load(\Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
                \Yii::$app->session->setFlash('success', 'A senha foi alterada com sucesso.');
                return $this->redirect(['login']);
            } else {
                \Yii::$app->getSession()->setFlash('danger', Util::renderModelErrors($model->getErrors()));
            }
        }
        
        return $this->render('@app/modules/auth/views/auth-user/altera-senha', [
            'model' => $model,
        ]);
    }
    
    /**
     * Action que verifica se a empresa esta em contingência
     */
    public function actionVerificarContingencia()
    {
        // valida a requisição
        if (!\Yii::$app->request->isAjax) {
            throw new NotFoundHttpException();
        }
        
        // objeto de retorno
    	$retorno = new \stdClass();
    	$retorno->contingencia = false;
    	$retorno->statusSefaz  = false;
    	$retorno->gerenciar    = false;
    	$retorno->notificar    = false;
    	$retorno->notas        = 0;

	    // seta a session
	    $session = \Yii::$app->session;
	    // seta o status do loop
	    $session->set('loop-status', \Yii::$app->request->post('loop'));

    	// valida se esta verificando a contingencia em loop (usando em telas que emitem nota)
    	// neste caso verifica os params salvos em session para executar a requisicao apenas 1 vez
    	// a cada 60 segundos, idependente de quantas telas estiverem executando a verificacao em loop
	    if (!empty($session->get('loop-status'))) {
    	    // verifica o tempo de requisicao slavo na session
    	    if ($session->get('contingencia-loop') != null && $session->get('contingencia-loop') > date('Y-m-d H:i:s')) {
    	        // seta os params que foram salvos em session
    	        $retorno->contingencia = $session->get('contingencia', $retorno->contingencia);
    	        $retorno->statusSefaz  = $session->get('statusSefaz', $retorno->statusSefaz);
    	        $retorno->gerenciar    = $session->get('gerenciar', $retorno->gerenciar);
    	        $retorno->notificar    = $session->get('notificar', $retorno->notificar);
    	        $retorno->notas        = $session->get('notas', $retorno->notas);
    	        
    	        return Json::encode($retorno);
    	    } else {
    	        $session->set('contingencia-loop', date('Y-m-d H:i:s', strtotime('+60 seconds')));
    	    }
    	}
    	
    	// busca as configuracoes da empresa atual
    	if ($empresa = EmpresaConfig::findOne(['empresa_id' => \Yii::$app->user->identity->empresa_id])) {
    	    // verifica se a empresa esta configurada para auto gerenciar ou notificar
    	    // o modo de contingencia
    	    $retorno->gerenciar = $empresa->contingencia_gerenciar == EmpresaConfig::CONFIG_SIM;
    	    $retorno->notificar = $empresa->contingencia_notificar == EmpresaConfig::CONFIG_SIM;
    	    
    	    // verifica se a empresa emite nota fiscal e se ativou as opções
    	    // se gerenciamento ou notificacao
    	    if ($empresa->emite_nota_fiscal == EmpresaConfig::EMITE_NOTA_FISCAL &&
    	       ($retorno->gerenciar == true || $retorno->notificar == true)
	        ) {    	        
        	    // verifica o status da sefaz
	            $retorno->statusSefaz = $this->VerificarStatusSefaz($empresa);
        	    
                // valida a notificacao sobre o status da sefaz
	            if (!$empresa->consultarTempoDeVerificacao()) {
	                $retorno->notificar = false;
                }
	            
            	// se a empresa esta em contingencia ou se a sefaz não estiver funcionando
            	// entra no modo de contingencia
        	    if ($empresa->nf_contingencia == EmpresaConfig::EMPRESA_EM_CONTINGENCIA || !$statusSefaz) {
    	            // seta o modo de contingencia como ativo
            		$retorno->contingencia = true;
            		// procura a quantidade de notas em contingência
            		$retorno->notas = NfeEnviada::countNotaNaoEnviada();
    
                    // verifica o status da sefaz
            		if ($retorno->statusSefaz) { 
            		    // seta o modo de contingencia como desativado
            		    $retorno->contingencia = false;
            		    
            		    // desativa automaticamente o modo de contingencia
                        if ($empresa->nf_contingencia == EmpresaConfig::EMPRESA_EM_CONTINGENCIA && 
                            $empresa->contingencia_gerenciar == EmpresaConfig::CONFIG_SIM
                        ) {                            
                            $empresa->nf_contingencia = EmpresaConfig::EMPRESA_FORA_CONTINGENCIA;
                            $empresa->save();
                        }
            		}
            	}
    	    }
    	}
    	   	 
    	// valida se esta verificando a contingencia em loop (usando em telas que emitem nota)
    	// neste caso salva os params retornados na session para poder realizar a requisição
    	// apenas uma vez a cada 60 segundos
    	if (!empty(\Yii::$app->request->post('loop'))) {
	        // seta os params que foram salvos em session
	        $session->set('contingencia', $retorno->contingencia);
	        $session->set('statusSefaz', $retorno->statusSefa);
	        $session->set('gerenciar', $retorno->gerenciar);
	        $session->set('notificar', $retorno->notificar);
	        $session->set('notas', $retorno->notas);
    	}
    	
    	return Json::encode($retorno);
    }
	
    /**
     * Habilita/Desabilita o modo de contingencia
     * ou adia a verificação em 1 hora
     */
    public function actionSetContingencia() 
    {        
        // apenas acessivel por post
        if (!$post = \Yii::$app->request->post()) {
            throw new NotFoundHttpException();
        }
        
        $retorno = new \stdClass();
        $retorno->success      = true;
        $retorno->contingencia = true;
        
        try {
            // busca as configurações da empresa
            $empresaConfig = EmpresaConfig::findOne(['empresa_id' => \Yii::$app->user->identity->empresa_id]);
            
            // verifica se deseja apenas adiar a proxima verificacao
            if (isset($post['adiar']) && $post['adiar'] == true) {
                $empresaConfig->setContingenciaProxVerificacao();
                $empresaConfig->save();
            } else {                
                // valida se a requisição ainda é valida
                if ($empresaConfig->consultarTempoDeVerificacao()) { 
                    // se a empresa esta em contingencia, então desabilita o modo de contingencia
                    // senao, habilita o modo de contingencia
                    if ($post['contingencia'] == 'true') {
                        if ($empresaConfig->nf_contingencia == EmpresaConfig::EMPRESA_EM_CONTINGENCIA) {
                            throw new UserException();
                        }
                        
                        $retorno->contingencia = true;
                        $empresaConfig->nf_contingencia = EmpresaConfig::EMPRESA_EM_CONTINGENCIA;
                    } else {
                        if ($empresaConfig->nf_contingencia == EmpresaConfig::EMPRESA_FORA_CONTINGENCIA) {
                            throw new UserException();
                        }
                        
                        $retorno->contingencia = false;
                        $empresaConfig->nf_contingencia = EmpresaConfig::EMPRESA_FORA_CONTINGENCIA;
                    }
                    
                    $empresaConfig->setContingenciaProxVerificacao();
                    $empresaConfig->save();
                }
            }
        } catch (UserException $e) {
            $retorno->success = false;
        } catch (\Exception $e) {
            $log = new SystemError(['mensagem' => "Exception: tabela: ".$this->id.", action: ".$this->action->actionMethod, 'arquivo' => $e->getFile(), 'arquivo_metodo' => $this->action->actionMethod, 'arquivo_linha' => $e->getLine(), 'tipo' => SystemError::TIPO_FATAL, 'exception' => $e->getMessage(), 'tratamento' => SystemError::TRATAMENTO_ERRO_NAO_TRATADO]);
            $log->save();
        }
        
        return Json::encode($retorno);
    }

    /**
     * Atualiza o status da notificação de atualização para o usuário
     */
    public function actionAtualizaNotificacaoUser()
    {
        $retorno = new \stdClass();
        $retorno->success = 0;
        
        try {
            if (\Yii::$app->request->isAjax && $post = Yii::$app->request->post()) {
                if (empty($post['id'])) {
                    throw new \Exception('A notificação não foi encontrada.');
                }
                
                if (NotificaAtualizacaoUser::UpdateNotificacao($post['id'], $post['showAgain'])) {
                    $retorno->success = 1;
                    $retorno->message = 'Notificação registrada.';
                }
                
            } else {
                throw new \Exception('Ação não permitida.');
            }
            
        } catch (\Exception $e) {
            $retorno->message = $e->getMessage();
        }
        
        return Json::encode($retorno);
    }
    
    /**
     * Salva a visualização das notificações gerais
     */
    public function actionViewNotificacoes()
    {
        if(!\Yii::$app->request->isAjax || !$id = \Yii::$app->request->post('id')) {
            throw new NotFoundHttpException();
        }
        
        try {
            if (!is_array($id)) {
                $view = new NotificacaoUsuarioView();
                $view->user_id = \Yii::$app->user->identity->colaborador_id;
                $view->notificacao_id = $id;
                $view->save();
            } else {
                foreach($id as $view_id) {
                    $view = new NotificacaoUsuarioView();
                    $view->user_id = \Yii::$app->user->identity->colaborador_id;
                    $view->notificacao_id = $view_id;
                    $view->save();
                }
            }
        }catch(\Exception $e) {
            $log = new SystemError(['mensagem' => "Exception: tabela: ".$this->id.", action: ".$this->action->actionMethod, 'arquivo' => $e->getFile(), 'arquivo_metodo' => $this->action->actionMethod, 'arquivo_linha' => $e->getLine(), 'tipo' => SystemError::TIPO_FATAL, 'exception' => $e->getMessage(), 'tratamento' => SystemError::TRATAMENTO_ERRO_NAO_TRATADO]);
            $log->save();
        }
    }

    /**
     * Rotina de auto cancelamento de pedidos
     * 
     * @desc esta rotina é executada apenas no primeiro login do dia, 
     * e se a empresa estiver configurada
     */
    private function autoCancelarPedidos()
    {
        try {
            // pega a empresa do usuario
            $empresa = Empresa::findOne(['id' => \Yii::$app->user->identity->empresa_id]);
            
            // valida se encontrou a emrpesa e se a empresa esta configurada para auto cancelar os pedidos
            if ($empresa && $empresa->empresaConfig->auto_cancel == EmpresaConfig::AUTO_CANCELAR_SIM) {                                
                // veirica se já há logins no dia atual
                $login = Yii::$app->db->createCommand('
                    SELECT from_unixtime(date_acess) as data FROM log_acess
                    WHERE empresa_id = :empresa and from_unixtime(date_acess) > :today
                    ORDER BY date_acess DESC
                    LIMIT 1;
                ')
                ->bindValue(':empresa', $empresa->id)
                ->bindValue(':today', date('Y-m-d') . ' 00:00:00')
                ->queryAll();
    
                // executa a rotina se for o primeiro login do dia
                if (!$login) {
                    // encontra todos os pedidos pendentes da empresa apos determinado periodo
                    $pedidos = Pedido::find()->where([
                        'empresa_id' => $empresa->id, 
                        'situacao_pedido' => Pedido::SIT_PENDENTE
                    ])->andWhere([
                        '<', 'data_alteracao', date('Y-m-d H:i:s', strtotime($empresa->empresaConfig->auto_cancel_period))
                    ])->all();
                    
                    // cancela os pedidos encontrados
                    if (!empty($pedidos)) {
                        foreach ($pedidos as $pedido) {
                            $pedido->cancelaPedido();
                        }
                    }         
                }
            }
        } catch (\Exception $e) {
            $log = new SystemError(['mensagem' => "Exception: tabela: ".$this->id.", action: ".$this->action->actionMethod, 'arquivo' => $e->getFile(), 'arquivo_metodo' => $this->action->actionMethod, 'arquivo_linha' => $e->getLine(), 'tipo' => SystemError::TIPO_FATAL, 'exception' => $e->getMessage(), 'tratamento' => SystemError::TRATAMENTO_ERRO_NAO_TRATADO]);
            $log->save();
        }
    }
    
    /**
     * Método que verifica o status do SEFAZ
     */
    private function VerificarStatusSefaz($empresaConfig)
    {
        // verifica se o certificado da empresa é valido
        // se nao, retorna true e não verifica o status da sefaz
        if (!EmpresaConfig::validarCertificado(false, $empresaConfig)) {
            return true;
        }

        try {     
            // pega a session do sistema
            $session = \Yii::$app->session;
            
            // pega os dois modelos de nota (NF-e e NFC-e)
            $modelos = [NfeEnvio::NOTA_FISCAL_ELETRONICA, NfeEnvio::NOTA_FISCAL_ELETRONICA_CONSUMIDOR];
            
            // itera sobre os modelos para realizar a requisicao de consulta do status da sefaz
            foreach ($modelos as $modelo) {
                // verifica o status da sefaz apenas
                // se não estiver em loop ou ainda não tiver verificado este modelo
                //
                // Obs: quando em loop (telas de pedido), verifica apenas um modelo de cada vez
                if (empty($session->get('loop-status')) || $session->get('contingencia-modelo') != $modelo) {
                    // seta na session o modelo verificado
                    $session->set('contingencia-modelo', $modelo);
                    
                    // instancia um objeto da api NFE-PHP do modelo iterado
                    $tools = NfeFactory::getTools($modelo);
                    // se comunica com a sefaz e formata a resposta para uma stdClass
                    $resposta = NfeFactory::getStandardizeXML($tools->sefazStatus());
                    
                    // verifica o status do sefaz
                    if ($resposta->cStat != NfeStat::STATUS_EM_OPERACAO) {
                        throw new UserException($resposta->xMotivo);
                    }
                    
                    // se estiver verificando em loop exita o foreach
                    if (!empty($session->get('loop-status'))) {                        
                        break;
                    }
                }
            }
                          
            // destroi o objeto tools
            // (força a chamada do _destruct do objeto da api)
            unset($tools);
            
        } catch (UserException $e) {
            // se o serviço não esta em operação e se a empresa esta configurada corretamente
            // retorna false (sefaz em contingencia) e coloca a empresa em contingencia
            if ($empresaConfig->nf_contingencia == EmpresaConfig::EMPRESA_FORA_CONTINGENCIA &&
                $empresaConfig->contingencia_gerenciar == EmpresaConfig::CONFIG_SIM
            ) {
                $empresaConfig->nf_contingencia = EmpresaConfig::EMPRESA_EM_CONTINGENCIA;
                $empresaConfig->save(false);
            }
            
            return false;
        } catch (\Exception $e) {
            // captura as exceptions de soap ou runtime quando a problema
            // de conexão com o cURL
            if ($e instanceof SoapException || $e instanceof \RuntimeException) {
                // verifica se a empresa esta configurada corretamente
                // retorna false (sefaz em contingencia) e coloca a empresa em contingencia
                if ($empresaConfig->nf_contingencia == EmpresaConfig::EMPRESA_FORA_CONTINGENCIA &&
                    $empresaConfig->contingencia_gerenciar == EmpresaConfig::CONFIG_SIM
                ) {
                    $empresaConfig->nf_contingencia = EmpresaConfig::EMPRESA_EM_CONTINGENCIA;
                    $empresaConfig->save(false);
                }
                
                return false;
            }
            if (!$e instanceof FileNotFoundException) {                
                // grava o erro da exception não esperada que foi disparada
                $log = new SystemError(['mensagem' => "Exception durante verificacao da contingencia: tabela: ".$this->id.", action: ".$this->action->actionMethod, 'arquivo' => $e->getFile(), 'arquivo_metodo' => $this->action->actionMethod, 'arquivo_linha' => $e->getLine(), 'tipo' => SystemError::TIPO_FATAL, 'exception' => $e->getMessage(), 'tratamento' => SystemError::TRATAMENTO_ERRO_NAO_TRATADO]);
                $log->save();
                return false;
            }
        }
        
        return true;
    }
}
