<?php
namespace app\controllers;

use yii\helpers\Json;
use yii\web\UploadedFile;
use yii\base\UserException;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use yii\db\IntegrityException;
use yii\web\NotFoundHttpException;

use app\base\Util;
use app\models\Cidade;
use app\models\Empresa;
use app\base\SystemError;
use app\models\Colaborador;
use app\models\NotificacaoTipo;
use app\models\UserWidgetAtalhos;
use app\models\ColaboradorSearch;
use app\modules\auth\models\AuthUser;
use app\models\ColaboradorNotificacao;

use JasperPHP\JasperPHP;
use app\models\UserWidget;
use app\models\UserConfig;
use app\models\UserWidgetObservacoes;

/**
 * @resolveName: Colaborador
 * @id: ColaboradorController
 * @modulo: Cadastro
 */
class ColaboradorController extends MasterController
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
                            if (\Yii::$app->user->can('/colaborador/gerenciar')) {
                                return true;
                            }
                        }
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index', 'search-list', 'relatorio', 'view'],
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            if (\Yii::$app->user->can('/colaborador/index')) {
                                return true;
                            }
                        }
                    ],
                    [
                        'allow' => true,
                        'actions' => ['update-usuario', 'set-atalho', 'set-observacao'],
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return true;
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
     * @nome: Gerenciar Colaboradores
     * @id: /colaborador/gerenciar
     * @descr: Permite realizar ações que envolvam colaboradores
     */
    public function actionGerenciar()
    {
        throw new NotFoundHttpException();
    }
    
    /**
     * @nome: Gerenciar Cargos
     * @id: /colaborador/gerenciar-cargos
     * @descr: Permite realizar ações que envolvam cargos
     */
    public function actionGerenciarCargos()
    {
        throw new NotFoundHttpException();
    }
    
    /**
     * @nome: Gerenciar Setores
     * @id: /colaborador/gerenciar-setores
     * @descr: Permite realizar ações que envolvam os setores
     */
    public function actionGerenciarSetores()
    {
        throw new NotFoundHttpException();
    }
    
    /**
     * @nome: Consultar Colaboradores
     * @id: /colaborador/index
     * @descr: Lista os colaboradores cadastrados no sistema
     */
    public function actionIndex()
    {
        $searchModel = new ColaboradorSearch();
        $params = [];
        $searchModel->empresa_id = \Yii::$app->user->identity->empresa_id;

        if (\Yii::$app->request->isGet) {
            $params = \Yii::$app->request->queryParams;
        }

        $dataProvider = $searchModel->search($params);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Relatorio de colaborador
     */
    public function actionRelatorio(array $filtros = [])
    {
        // valida a requisicao
        if (empty($filtros)) {
            throw new NotFoundHttpException();
        }
        
        try {
            // verifica se há um relatório na pasta reports, se houver exclui
            if (is_file(getcwd() . '/reports/colaboradores.pdf')) {
                unlink(getcwd() . '/reports/colaboradores.pdf');
            }
    
            $empresa = Empresa::findOne(['id' => \Yii::$app->user->identity->empresa_id]);
            // dados da conexão
            $connection = array('driver' => 'mysql', 'username' => \Yii::$app->params['dbconnection']['username'], 'password' => \Yii::$app->params['dbconnection']['password'], 'host' => \Yii::$app->params['dbconnection']['host'], 'database' => \Yii::$app->params['dbconnection']['database'], 'port' => '3306');
            // parametros para jasper
            $params = array("REPORT_LOCALE" => "pt_BR", "nome_empresa" => '"'.$empresa->razao_social.'"');
            // verifica se os parametros que vieram e adiciona ao array de parametros
            if (isset($filtros['ativo']) && !empty($filtros['ativo'])) {
                $params["ativo"] = '"'.$filtros['ativo'].'"';
            }
            
            // biblioteca que manipula, compila o arquivo jrxml
            $jasper = new JasperPHP();
            // processa o doc .jasper
            $jasper->process(\Yii::$app->params['relatorios']['relatorio_colaboradores'], getcwd() . '/reports/colaboradores', array("pdf"), $params, $connection, false)->execute();
            // se houver erros e desejar visualizar o erro descomente a linha abaixo e comente a linha acima, copie o resultado da tela remova os caracteres ^ e execute via linha de comando
            //echo $jasper->process(\Yii::$app->params['relatorios']['relatorio_colaboradores'], getcwd() . '/reports/colaboradores', array("pdf"), $params, $connection, false)->output();die;
            
            // abre o arquivo
            $pdf = getcwd() . '/reports/colaboradores.pdf';
            if (!is_file($pdf)) {
                \Yii::$app->session->setFlash('danger', '<i class="fa fa-exclamation-triangle"></i>&nbsp; Houve um erro inesperado ao gerar o relatório de colaboradores.');
                return $this->redirect(['index']);
            } else {
                return \Yii::$app->response->sendFile($pdf, 'Relatorio', ['inline' => true]);
            }
        } catch (\Exception $e) {
            \Yii::$app->session->setFlash('danger', "<i class='fa fa-exclamation-triangle'></i>&nbsp; Houve um erro inesperado ao gerar o relatório de colaboradores: {$e->getMessage()}");
            return $this->redirect(['index']);
        }
    }

    /**
     * Cadastra um novo colaborador
     */
    public function actionCreate()
    {
        $model = new Colaborador();
        $user  = new AuthUser();
        $empresas = ArrayHelper::map(Empresa::find()->all(), 'id', 'nome_fantasia');
        
        // seta o scenario para colaborador
        $user->setScenario(AuthUser::SCENARIO_COLABORADOR);
        
        if ($post = \Yii::$app->request->post()) {
            $model->load($post);
            $user->load($post);
            
            try {
                $transaction = \Yii::$app->db->beginTransaction();
            
                // verifica se o usuário e senha vieram se sim cadastra-o
                if (!empty($user->username) && !empty($user->password)) {
                    // verifica a repeticao da senha
                    if (isset($post['AuthUser']['password_repeat']) && !empty($post['AuthUser']['password_repeat'])) {
                        $user->password_repeat = $post['AuthUser']['password_repeat'];
                    }
                    
                    // seta o email do usuario
                    $user->email = $model->email_pessoal;
                    
                    // salva o usuario
                    if (!$user->save()) {
                        throw new UserException(Util::renderModelErrors($user->getErrors()));
                    }
                    
                    // seta o id do usuario no colaborador
                    $model->user_id = $user->id;
                }
                
                // salva a model
                if (!$model->save()) {
                    throw new UserException(Util::renderModelErrors($model->getErrors()));
                }
                
                // busca a lista completa de tipos de notificacao
                // e seta as configurações do colaborador
                foreach (NotificacaoTipo::find()->all() as $tipo) {
                    // procura pela notificao config
                    // se nao achar, cadastra uma nova
                    if (!$colab_not = ColaboradorNotificacao::getColaboradorNotficacao($model->id, $tipo->id)) {
                        $colab_not = new ColaboradorNotificacao();
                        $colab_not->colaborador_id = $model->id;
                        $colab_not->notificacao_tipo_id = $tipo->id;
                    }
                    
                    // seta o valor da notificacao e salva
                    $colab_not->ativo = isset($post['NotificacaoTipo'][$tipo->id]) ? ColaboradorNotificacao::ATIVO : ColaboradorNotificacao::NAO_ATIVO;
                    if (!$colab_not->save()) {
                        throw new UserException(Util::renderModelErrors($colab_not->getErrors()));
                    }
                }
                
                // seta os widgets do usuario
                UserConfig::setWidgets(
                    $model->id,
                    $model->empresa_id,
                    $post['widget'],
                    $post['ordemWidget'],
                    isset($post['widgetLimpar']) ? true : false
                );
                
                $transaction->commit();
                
                // verifica a mensagem de retorno
                if (empty($model->user_id)) {
                    \Yii::$app->getSession()->setFlash('success', 'O colaborador "'.strtoupper($model->nome).'" foi cadastrado com sucesso.');
                } else {
                    \Yii::$app->getSession()->setFlash('info', '
                    <h2 class="font16"><i class="fa fa-exclamation-triangle"></i>&nbsp; Deseja configurar as permissões para o usuário <b>'. $model->nome .'</b>?</h2>
                    <button class="btn btn-primary btn-flat" onclick="abrirPermissoes();"><i class="fa fa-check"></i>&nbsp; Sim </button>
                    <button class="btn btn-danger btn-flat"><i class="fa fa-times"></i>&nbsp; Não </button>
                    <script>
                         function abrirPermissoes() {
                           window.location = "../auth/permissao?id='.$model->user_id. '";
                         }
                    </script>');
                }
                    
                return $this->redirect(['index']);
               
            } catch(UserException $e) {
                $transaction->rollBack();
                \Yii::$app->session->setFlash('warning', "<i class='fa fa-exclamation-triangle'></i>&nbsp; Não foi possível salvar o colaborador: {$e->getMessage()}");
            } catch(\Exception $e) {
                $transaction->rollBack();
                $log = new SystemError(['mensagem' => "Exception: tabela: ".$this->id.", action: ".$this->action->actionMethod, 'arquivo' => $e->getFile(), 'arquivo_metodo' => $this->action->actionMethod, 'arquivo_linha' => $e->getLine(), 'tipo' => SystemError::TIPO_FATAL, 'exception' => $e->getMessage(), 'tratamento' => SystemError::TRATAMENTO_ERRO_NAO_TRATADO]);
                $log->save();                
                \Yii::$app->session->setFlash('danger', "<i class='fa fa-exclamation-triangle'></i>&nbsp; Houve um erro inesperado: {$e->getMessage()}");
            }
        }
        
        // valor default
        if (is_null($model->ativo)) {
            $model->ativo = Colaborador::ATIVO;
        }
        
        // busca todos os widgets
        $widgets = UserWidget::findWidgets();
        
        return $this->render('create', [
            'model'     => $model,
            'modelUser' => $user,
            'empresas'  => $empresas,
            'widgets'   => $widgets,
        ]);
    }

    /**
     * Atualiza um colaborador
     */
    public function actionUpdate($id)
    {
        // busca a model
        $model = $this->findModel($id);

        // Busca o usuário do colaborador, caso não possui envia uma nova instância
        if (!$user = AuthUser::findOne(['id' => $model->user_id])) {
            $user = new AuthUser();
        }
        
        // seta o scenario para colaborador
        $user->setScenario(AuthUser::SCENARIO_COLABORADOR);
        
        if ($post = \Yii::$app->request->post()) {
            $model->load($post);
            
            try {
                $transaction = \Yii::$app->db->beginTransaction();
                
                // verifica se o usuário e senha vieram se sim cadastra-o
                // ou atualiza o email do usuario se o email do colaborador foi atualizado
                if (!empty($post['AuthUser']['username']) && !empty($post['AuthUser']['password'])) {
                    // carrega os dados enviados
                    $user->load($post);
                    
                    // seta o email do usuario
                    $user->email = $model->email_pessoal;
                    
                    // salva o usuario
                    if (!$user->save()) {
                        throw new UserException(Util::renderModelErrors($user->getErrors()));
                    }
                   
                    // atualiza o id do usuario no colaborador
                    if ($model->user_id != $user->id) {                        
                        $model->user_id = $user->id;
                    }
                }  elseif (!$user->isNewRecord && $user->email != $model->email_pessoal) {
                    // seta o email do usuario
                    $user->email = $model->email_pessoal;
                    
                    // salva o usuario
                    if (!$user->save(false)) {
                        throw new UserException(Util::renderModelErrors($user->getErrors()));
                    }
                    
                    // atualiza o id do usuario no colaborador
                    if ($model->user_id != $user->id) {
                        $model->user_id = $user->id;
                    }
                }
                 
                if (!$model->save()) {
                    throw new UserException(Util::renderModelErrors($model->getErrors()));
                }
                
                // busca a lista completa de tipos de notificacao
                // e seta as configurações do colaborador
                foreach (NotificacaoTipo::find()->all() as $tipo) {
                    // procura pela notificao config
                    // se nao achar, cadastra uma nova
                    if (!$colab_not = ColaboradorNotificacao::getColaboradorNotficacao($model->id, $tipo->id)) {
                        $colab_not = new ColaboradorNotificacao();
                        $colab_not->colaborador_id = $model->id;
                        $colab_not->notificacao_tipo_id = $tipo->id;
                    }
                    
                    // seta o valor da notificacao e salva
                    $colab_not->ativo = isset($post['NotificacaoTipo'][$tipo->id]) ? ColaboradorNotificacao::ATIVO : ColaboradorNotificacao::NAO_ATIVO;
                    if (!$colab_not->save()) {
                        throw new UserException(Util::renderModelErrors($colab_not->getErrors()));
                    }
                }
                
                // seta os widgets do usuario
                UserConfig::setWidgets(
                    $model->id,
                    $model->empresa_id,
                    $post['widget'],
                    $post['ordemWidget'],
                    isset($post['widgetLimpar']) ? true : false
                );
                
                $transaction->commit();
                
                // verifica a mensagem de retorno
                if (empty($model->user_id)) {
                    \Yii::$app->getSession()->setFlash('success', 'O colaborador "'.strtoupper($model->nome).'" cadastrado com sucesso.');
                } else {
                    \Yii::$app->getSession()->setFlash('info', '
                    <h2 class="font16"><i class="fa fa-exclamation-triangle"></i>&nbsp; Deseja configurar as permissões para o usuário <b>'. $model->nome .'</b>?</h2>
                    <button class="btn btn-primary btn-flat" onclick="abrirPermissoes();"><i class="fa fa-check"></i>&nbsp; Sim </button>
                    <button class="btn btn-danger btn-flat"><i class="fa fa-times"></i>&nbsp; Não </button>
                    <script>
                         function abrirPermissoes() {
                           window.location = "../auth/permissao?id='.$model->user_id. '";
                         }
                    </script>');
                }        
                
                return $this->redirect(['index']);
                
            } catch(UserException $e) {
                $transaction->rollBack();
                \Yii::$app->session->setFlash('warning', "<i class='fa fa-exclamation-triangle'></i>&nbsp; Não foi possível salvar o colaborador: {$e->getMessage()}");
            } catch(\Exception $e) {
                $transaction->rollBack();
                $log = new SystemError(['mensagem' => "Exception: tabela: ".$this->id.", action: ".$this->action->actionMethod, 'arquivo' => $e->getFile(), 'arquivo_metodo' => $this->action->actionMethod, 'arquivo_linha' => $e->getLine(), 'tipo' => SystemError::TIPO_FATAL, 'exception' => $e->getMessage(), 'tratamento' => SystemError::TRATAMENTO_ERRO_NAO_TRATADO]);
                $log->save();
                \Yii::$app->session->setFlash('danger', "<i class='fa fa-exclamation-triangle'></i>&nbsp; Houve um erro inesperado: {$e->getMessage()}");
            }
        }

        // valor default
        if (is_null($model->ativo)) {
            $model->ativo = Colaborador::ATIVO;
        }
        
        // busca todos os widgets do usuario
        $widgets = UserWidget::findWidgets($model->id, $model->empresa_id);
        
        return $this->render('update', [
            'model'     => $model,
            'modelUser' => $user,
            'empresas'  => ArrayHelper::map(Empresa::find()->all(), 'id', 'nome_fantasia'),
            'widgets'   => $widgets,
        ]);
    }

    /*
     * Atualiza o usuario logado
     */
    public function actionUpdateUsuario()
    {
        $cidades   = [];
        $model     = $this->findModel(\Yii::$app->user->identity->colaborador_id);
        $modelUser = AuthUser::findOne($model->user_id);
        
        // seta o scenario para colaborador
        $modelUser->setScenario(AuthUser::SCENARIO_COLABORADOR);

        // busca as cidades do estado do usuario
        if (!empty($model->estado_id)) {
            $cidades = Cidade::find()->where('estado_federacao_id = :estado_id', ['estado_id' => $model->estado_id])->all();
        }

        if ($post = \Yii::$app->request->post()) {
            $model->load($post);

            try {
                $transaction = \Yii::$app->db->beginTransaction();

                // verifica se o usuário e senha vieram se sim cadastra-o
                // ou atualiza o email do usuario se o email do colaborador foi atualizado
                if (!empty($post['AuthUser']['username']) && !empty($post['AuthUser']['password'])) {
                    // carrega os dados enviados
                    $modelUser->load($post);
                    
                    // seta o email do usuario
                    $modelUser->email = $model->email_pessoal;
                    
                    // salva o usuario
                    if (!$modelUser->save()) {
                        throw new UserException(Util::renderModelErrors($modelUser->getErrors()));
                    }
                    
                    // atualiza o id do usuario no colaborador
                    if ($model->user_id != $modelUser->id) {
                        $model->user_id = $modelUser->id;
                    }
                }  elseif (!$modelUser->isNewRecord && $modelUser->email != $model->email_pessoal) {
                    // seta o email do usuario
                    $modelUser->email = $model->email_pessoal;
                    
                    // salva o usuario
                    if (!$modelUser->save(false)) {
                        throw new UserException(Util::renderModelErrors($modelUser->getErrors()));
                    }
                    
                    // atualiza o id do usuario no colaborador
                    if ($model->user_id != $modelUser->id) {
                        $model->user_id = $modelUser->id;
                    }
                }
                
                // salva o colaborador
                if (!$model->save()) {
                    throw new UserException(Util::renderModelErrors($model->getErrors()));
                }
                 
                // seta os widgets do usuario
                UserConfig::setWidgets(
                    $model->id, 
                    $model->empresa_id, 
                    $post['widget'], 
                    $post['ordemWidget'], 
                    isset($post['widgetLimpar']) ? true : false
                );

                // commita as alteracoes
                $transaction->commit();

                return $this->redirect(['/site/index']);
                
            } catch (UserException $e) {
                $transaction->rollBack();
                \Yii::$app->getSession()->setFlash('warning', "<i class='fa fa-exclamation-triangle'></i>&nbsp; Não foi possível salvar o colaborador: {$e->getMessage()}");
            } catch (\Exception $e) {
                $transaction->rollBack();
                // salva log do erro, ver tabela log_error
                $log = new SystemError(['mensagem' => "Exception: tabela: ".$this->id.", action: ".$this->action->actionMethod, 'arquivo' => $e->getFile(), 'arquivo_metodo' => $this->action->actionMethod, 'arquivo_linha' => $e->getLine(), 'tipo' => SystemError::TIPO_FATAL, 'exception' => $e->getMessage(), 'tratamento' => SystemError::TRATAMENTO_ERRO_NAO_TRATADO]);
                $log->save();
                \Yii::$app->getSession()->setFlash('danger', "<i class='fa fa-exclamation-triangle'></i>&nbsp; Houve um erro inesperado: {$e->getMessage()}");
            }
        }

        // valor default
        if (is_null($model->ativo)) {
            $model->ativo = Colaborador::ATIVO;
        }
        
        // busca todos os widgets do usuario
        $widgets = UserWidget::findWidgets($model->id, $model->empresa_id);

        return $this->render('updateusuario', [
            'model'         => $model,
            'modelUser'     => $modelUser,
            'cidades'       => $cidades,
            'widgets'       => $widgets,
        ]);
    }

    /**
     * Deleta um colaborador existente
     */
    public function actionDelete($id)
    {
        try {
            // busca a model
            $model = $this->findModel($id);
            // deleta o colaborador
            $model->delete();
            // deleta o user
            if (!empty($model->user)) {
                $model->user->delete();
            }
            
            \Yii::$app->getSession()->setFlash('success', '<i class="fa fa-check"></i>&nbsp; O colaborador foi deletado com sucesso.');
        
        } catch (IntegrityException $e) {
            \Yii::$app->getSession()->setFlash('warning', '<i class="fa fa-exclamation-triangle"></i>&nbsp; Não é possível deletar o colaborador, existem dados vinculados a ele.');
        } catch (\Exception $e) {
            \Yii::$app->getSession()->setFlash('danger', "<i class='fa fa-exclamation-triangle'></i>&nbsp; Houve um erro ao deletar o colaborador: {$e->getMessage()}");
        }

        return $this->redirect(['index']);
    }

    /**
     * Envia a foto do colaborador
     */
    public function actionEnviarFoto($id = null)
    {
        // valida a requisicao
        if (!\Yii::$app->request->isAjax) {
            throw new NotFoundHttpException();
        }
        
        $retorno = new \stdClass();
        
        // valida o id denviado
        if (!$id) {            
            $id = \Yii::$app->user->identity->colaborador_id;
        }

        // tenta fazer o upload do arquivo
        try {
            $file = UploadedFile::getInstanceByName('file');

            if ($file->saveAs(\Yii::$app->params['upload']['colaborador']['imagem']."colaborador{$id}.{$file->extension}")) {
                $retorno->success        = 1;
                $retorno->message        = 'Arquivo enviado com sucesso';
                $retorno->colaborador_id = $id;
                $retorno->foto_nome      = "colaborador{$id}.{$file->extension}";
                $retorno->foto_caminho   = \Yii::$app->params['upload']['colaborador']['imagem']."colaborador{$id}.{$file->extension}";
            } else {
                throw new \Exception('Houve um erro ao salvar o arquivo');
            }
        } catch (\Exception $e) {
            $log = new SystemError(['mensagem' => "Exception: tabela: ".$this->id.", action: ".$this->action->actionMethod, 'arquivo' => $e->getFile(), 'arquivo_metodo' => $this->action->actionMethod, 'arquivo_linha' => $e->getLine(), 'tipo' => SystemError::TIPO_FATAL, 'exception' => $e->getMessage(), 'tratamento' => SystemError::TRATAMENTO_ERRO_NAO_TRATADO]);
            $log->save();
            $retorno->error = "O arquivo não pode ser enviado: {$e->getMessage()}";
        }

        return Json::encode($retorno);
    }

    /**
     * Atualiza a imagem do colaborador
     */
    public function actionAtualizaImagem($img = null, $id = null)
    {
        // valida a requisicao
        if (!\Yii::$app->request->isAjax) {
            throw new NotFoundHttpException();
        }
        
        // valida o id enviado
        if (!$id) {            
            $id = \Yii::$app->user->identity->colaborador_id;
        }
        
        // objeto de retorno
        $retorno = new \stdClass();
        $retorno->success = false;

        if ($colab = Colaborador::findOne(['id' => $id])) {            
            try {
                $colab->foto = $img;
                $colab->save(false);
                $retorno->success = true;
            } catch (\Exception $e) {
                // salva log do erro, ver tabela log_error
                $log = new SystemError(['mensagem' => "Exception: tabela: ".$this->id.", action: ".$this->action->actionMethod, 'arquivo' => $e->getFile(), 'arquivo_metodo' => $this->action->actionMethod, 'arquivo_linha' => $e->getLine(), 'tipo' => SystemError::TIPO_FATAL, 'exception' => $e->getMessage(), 'tratamento' => SystemError::TRATAMENTO_ERRO_NAO_TRATADO]);
                $log->save();
                $retorno->error   = "Houve um erro inesperado: {$e->getMessage()}";
            }
        } else {
            $retorno->error = 'Não foi possível encontrar o usuário.';
        }

        return Json::encode($retorno);
    }

    /**
     * Adiciona ou remove um atalho do usuário
     */
    public function actionSetAtalho()
    {
        // valida a requisicao
        if (!\Yii::$app->request->isAjax) {
            throw new NotFoundHttpException();
        }
        
        $retorno = new \stdClass();
        $retorno->success = true;
        
        // seta o colaborador logado
        $empresa_id  = \Yii::$app->user->identity->empresa_id;
        $colaborador = \Yii::$app->user->identity->colaborador_id;
        
        if ($post = \Yii::$app->request->post()) {
            // se o atalho nao existir, entao adiciona
            // se existir, deleta
            if (!$atalho = UserWidgetAtalhos::findOne(['atalho' => $post['atalho'], 'id_colaborador' => $colaborador, 'empresa_id' => $empresa_id])) {
                $atalho = new UserWidgetAtalhos();
                $atalho->id_colaborador = $colaborador;
                $atalho->empresa_id     = $empresa_id;
                $atalho->atalho         = $post['atalho'];
                $atalho->descricao      = $post['descricao'];
                
                if (!$atalho->save()) {
                    $retorno->success = false;
                }
            } else {
                if (!$atalho->delete()) {
                    $retorno->success = false;
                }
            }
        }
        
        return Json::encode($retorno);
    }
    
    /**
     * Salva a observação do usuário
     */
    public function actionSetObservacao()
    {
        // valida a requisicao
        if (!\Yii::$app->request->isAjax) {
            throw new NotFoundHttpException();
        }
        
        $retorno = new \stdClass();
        $retorno->success = true;
        
        // seta o colaborador logado
        $empresa     = \Yii::$app->user->identity->empresa_id;
        $colaborador = \Yii::$app->user->identity->colaborador_id;
        
        if ($post = \Yii::$app->request->post()) {
            if (!$observacao = UserWidgetObservacoes::findOne(['id_colaborador' => $colaborador, 'empresa_id' => $empresa])) {
                $observacao = new UserWidgetObservacoes();
                $observacao->id_colaborador = $colaborador;
                $observacao->empresa_id     = $empresa;
            }
            
            // seta a observacao
            $observacao->observacao = $post['obs'];
            
            // tenta salvar a observacao
            if (!$observacao->save()) {
                $retorno->success = false;
            }
        }
        
        return Json::encode($retorno);
    }
    
    /**
     * Realiza a busca de um colaborador especifico
     * Usado por Ajax (Typeahead)
     *
     * Obs.: A requisição precisa ser um POST
     */
    public function actionSearchList(array $q = null)
    {
        // valida a requisicao
        if (!\Yii::$app->request->isAjax) {
            throw new NotFoundHttpException();
        }
        
        $data = [];
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $query = Colaborador::find()->where(['empresa_id' => \Yii::$app->user->identity->empresa_id]);
        
        // filtra a busca
        if (isset($q['id']))            { $query->andWhere(['like', 'id', $q['id']])->select('id'); }
        if (isset($q['nome']))          { $query->andWhere(['like', 'nome', $q['nome']])->select('nome'); }
        if (isset($q['email_pessoal'])) { $query->andWhere(['like', 'email_pessoal', $q['email_pessoal']])->select('email_pessoal'); }
        
        // realiza a busca e retorna o valor
        $model = $query->all();
        if ($model != null) {
            foreach ($model as $key) {
                if (isset($q['id']))            { $data[]['value'] = $key['id']; }
                if (isset($q['nome']))          { $data[]['value'] = $key['nome']; }
                if (isset($q['email_pessoal'])) { $data[]['value'] = $key['email_pessoal']; }
            }
        }
        
        return $data;
    }
    
    /**
     * Busca a model de colaborador
     */
    protected function findModel($id)
    {
        // busca o colaborador na empresa
        if ($model = Colaborador::find()->where(['id' => $id, 'empresa_id' => \Yii::$app->user->identity->empresa_id])->one()) {
            return $model;
        }
        
        throw new NotFoundHttpException();
    }
}
