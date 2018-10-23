<?php
namespace app\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use yii\web\NotFoundHttpException;
use yii\di\NotInstantiableException;

use app\base\Util;
use app\models\Email;
use app\models\Boleto;
use app\api\BoletoCloud;
use app\base\SystemError;
use app\models\BoletoSearch;
use app\models\BoletoOcorrencia;
use app\modules\financeiro\models\Receita;
use app\modules\financeiro\models\ReceitaParcela;

/**
 * @resolveName: Boletos e Arquivos de Remessa e Retorno
 * @id: BoletoController
 * @modulo: Financeiro
 */
class BoletoController extends MasterController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            if (!\app\config\Menu::validarPlano(\app\config\Menu::MODULO_FINANCEIRO)) {
                                throw new NotFoundHttpException();
                            }
                            if (
                                \Yii::$app->user->can('/boleto/gerenciar') || 
                                \Yii::$app->user->can('/pedido/utilizar-pedido-normal') || 
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
        // verifica se a estrutura
        // de pastas relacionadas com boleto existe
        Boleto::validStructure();
        
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
     * @nome: Gerenciar Boletos e Arquivos de Remessa e Retorno
     * @id: /boleto/gerenciar
     * @descr: Permite emitir boletos nos pedidos e avulsamente
     */
    public function actionGerenciar()
    {
        throw new NotFoundHttpException();
    }

    /**
     * Listagem dos boletos
     */
    public function actionIndex()
    {
        $searchModel = new BoletoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->setSort(['defaultOrder' => ['id' => 'DESC']]);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
       
    /**
     * Busca por um registro no typeahead
     */
    public function actionSearchList(array $q)
    {
        $data = [];
        \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        $query = Boleto::find();
        
        if (isset($q['id'])) {            
            $query->andWhere(['like', 'id', $q['id']]);
        }
        if (isset($q['nosso_numero'])) {            
            $query->andWhere(['like', 'nosso_numero', $q['nosso_numero']]);
        }
        
        $model = $query->all();
        if ($model != null) {
            foreach ($model as $key) {
                if (isset($q['id'])) {                    
                    $data[]['value'] = $key['id'];
                }
                if (isset($q['nosso_numero'])) {                    
                    $data[]['value'] = $key['nosso_numero'];
                }
            }
        }
        
        return $data;
    }
    
    /**
     * Método que visualiza o boleto, atraves da api BoletoCloud
     * @param int $id
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $apiResponse = $model->visualizarBoleto();
        
        if ($apiResponse['http_code'] == BoletoCloud::HTTP_SUCCESS) {
            header('Content-type: application/pdf');
            header('Content-Disposition: inline; filename='.strtoupper(Util::shortName($model->empresa->razao_social, 40)).' Boleto '.strtoupper(Util::shortName($model->cliente->nome, 40)).'.pdf');
            echo $apiResponse['body'];
            exit();
        } elseif ($apiResponse['http_code'] == BoletoCloud::HTTP_INTERNAL_SERVER_ERROR) {
            $log = new SystemError(['mensagem' => "Serviço BoletoCloud indisponível em ".date('d/m/Y H:i:s'), 'arquivo' => 'app/controllers/BoletoController.php', 'arquivo_metodo' => $this->action->actionMethod, 'arquivo_linha' => 82, 'tipo' => SystemError::TIPO_FATAL, 'exception' => 'veja descrição', 'tratamento' => SystemError::TRATAMENTO_ERRO_NAO_TRATADO]);
            $log->save();
            Yii::$app->session->setFlash('danger', 'Serviço indisponível no momento');
            return $this->redirect(['index']);
        } else {
            $log = new SystemError(['mensagem' => "Erro ao visualizar boleto id: ".$model->id.", token: ".$model->token.", em ".date('d/m/Y H:i:s'), 'arquivo' => 'app/controllers/BoletoController.php', 'arquivo_metodo' => $this->action->actionMethod, 'arquivo_linha' => 88, 'tipo' => SystemError::TIPO_FATAL, 'exception' => 'veja descrição', 'tratamento' => SystemError::TRATAMENTO_ERRO_NAO_TRATADO]);
            $log->save();
            Yii::$app->session->setFlash('danger', 'Não foi possível abrir o boleto para visualização, cód: '.$apiResponse['http_code'], 'mensagem: '.$apiResponse['body']);
            return $this->redirect(['index']);
        }
    }
    
    /**
     * Modal que exibe os detalhes do boleto
     * @param int $id
     */
    public function actionDetail($id)
    {
        $model = $this->findModel($id);
        return $this->renderAjax('_detail', [
            'model' => $model
        ]);
    }
    
    /**
     * Método que envia por e-mail o boleto como PDF
     * @param int $id
     */
    public function actionEmail()
    {
        $id = $_GET['id'];
        $model = $this->findModel($id);
        $email = new Email();
        
        $email->destinatario_principal = $model->cliente->email;
        $email->assunto = "Boleto para {$model->cliente->nome}";
        $email->extra_message = '<span class="text-danger">*<i class="fa fa-file-pdf"></i></span> O boleto será enviado como anexo em formato PDF';
        $email->attach_preview = "<i class='fa fa-file-pdf'></i> {$model->token}.pdf";
        $email->conteudo = "Olá Caro Cliente, \nSegue em anexo o boleto:";
        
        if ($post = \Yii::$app->request->post()) { 
            try {
                // carrega os dados
                $email->load($post);
                
                // se o cliente possuir um e-mail
                if (!$email->destinatario_principal) {
                    throw new \Exception('Email não enviado. Nenhum destinatário foi informado.');
                }
                
                // chamada da API
                $apiResponse = $model->visualizarBoleto();

                // se o retorno da API for positivo entao envia por e-mail
                if (!($apiResponse['http_code'] == BoletoCloud::HTTP_SUCCESS)) {
                    throw new NotInstantiableException($apiResponse['body']);
                }
                
                $email->save();
                
                //Escrevendo o pdf no arquivo
                $boletoPdf = Yii::$app->params['boleto']['pdf'].$model->token.'.pdf';
                $fopen = fopen($boletoPdf, 'a');
                fwrite($fopen, $apiResponse['body']);
                fclose($fopen);
                
                if (!is_file($boletoPdf)) {
                    throw new \Exception('Email não enviado. Ocorreu um problema ao gerar o boleto.');
                }
                
                $email->UpdateModel($post['EmailDestinatario']);
                
                if (!$email->UploadFilesDirect($boletoPdf) || !$email->send()) {
                    throw new \Exception('Email não enviado. Ocorreu um problema ao preparar o e-mail.');
                }
                
                // limpando a pasta atual
                unlink($boletoPdf);
                
                \Yii::$app->getSession()->setFlash('success', '<i class="fa fa-check"></i>&nbsp; E-mail enviado com sucesso!');
                return $this->redirect('index');
            } catch (NotInstantiableException $e) {
                // salva log do erro, ver tabela log_error
                $log = new SystemError(['mensagem' => "Erro ao enviar e-mail, boleto nº: ".$model->id." destinatario: ".$email->destinatario_principal, 'arquivo' => $e->getFile(), 'arquivo_metodo' => $this->action->actionMethod, 'arquivo_linha' => $e->getLine(), 'tipo' => SystemError::TIPO_FATAL, 'exception' => $e->getMessage(), 'tratamento' => SystemError::TRATAMENTO_ERRO_NAO_TRATADO]);
                $log->save();
                \Yii::$app->getSession()->setFlash('danger', '<i class="fa fa-exclamation-triangle"></i>&nbsp; Email não enviado. Houve um erro ao gerar o boleto, se o erro persistir contate o administrador.');
            } catch (\Exception $e) {
                // salva log do erro, ver tabela log_error
                $log = new SystemError(['mensagem' => "Erro ao enviar e-mail, boleto nº: ".$model->id." destinatario: ".$email->destinatario_principal, 'arquivo' => $e->getFile(), 'arquivo_metodo' => $this->action->actionMethod, 'arquivo_linha' => $e->getLine(), 'tipo' => SystemError::TIPO_FATAL, 'exception' => $e->getMessage(), 'tratamento' => SystemError::TRATAMENTO_ERRO_NAO_TRATADO]);
                $log->save();
                \Yii::$app->getSession()->setFlash('danger', "<i class='fa fa-exclamation-triangle'></i>&nbsp; {$e->getMessage()}");
            }
         
            return $this->redirect('index');            
        }
        
        return $this->renderAjax('/email/create', [
            'model' => $email,
            'renderPartial' => true,
            'disabledAttach' => true,
            'disableSave' => true,
        ]);
    }
    
    public function actionMassCreate($receita_id)
    {
        $receita = Receita::findOne(['id' => $receita_id]);
        if(Yii::$app->request->isPost){
            $post = Yii::$app->request->post();
            $boletos = [];
            if(isset($post['parcelas']) && isset($post['conta_id'])){
                //Varre a lista de parcelas para gerar o boleto
                foreach($post['parcelas'] as $parcela){
                    $parcela = ReceitaParcela::findOne(['id' => $parcela]);
                    //Verifica se já não há um boleto para está parcela
                    $boleto = Boleto::findOne(['receita_parcela_id' => $parcela->id]);
                    if(empty($boleto)){
                        //Cria o novo boleto conforme a parcela
                        $boleto = new Boleto();
                        $boleto->empresa_id = Yii::$app->user->identity->empresa_id;
                        $boleto->conta_id = $post['conta_id'];
                        $boleto->cliente_id = $receita->cliente_id;
                        $boleto->receita_parcela_id = $parcela->id;
                        $boleto->valor = $parcela->valor;
                        $boleto->data_vencimento = $parcela->vencimento;
                        $boleto->data_emissao = date('d/m/Y');
                        $boleto->documento = 'REC'.$receita->id.'PARC'.$parcela->parcela_num;
                        $boleto->situacao_fluxo = Boleto::SITUACAO_FLUXO_BOLETO_NAO_ENVIADO_API;
                        $boleto->situacao_pagamento = Boleto::SITUACAO_PAGAMENTO_PENDENTE;                        
                        if($boleto->save()){
                            $boleto->gerarBoleto();    
                            $boletos [] = $boleto;
                        }                
                        else{
                            Yii::$app->session->setFlash('danger', '<strong>Erro ao salvar boleto referente a parcela nº '.$parcela->parcela_num.'</strong><br/>'.Util::renderModelErrors($boleto->errors));
                        }                        
                    }
                }
                //Renderiza a tela de visualização
                return $this->render('mass-view', [
                    'model' => $receita,
                    'boletos' => $boletos
                ]);
            }
            else{
                Yii::$app->session->setFlash('warning', 'Informe ao menos um boleto e uma conta bancaria para geração em massa de boletos!');
                return $this->redirect(['index']);
            }
        }
        else{
            $dataProvider = new ActiveDataProvider([
                'query' => ReceitaParcela::find()->where(['receita_id' => $receita->id])
            ]);
            
            return $this->render('mass-create', [
                'model' => $receita,
                'dataProvider' => $dataProvider
            ]);
        }
    }

    /**
     * Cadastra um nobo boleto
     */
    public function actionCreate()
    {
        $model = new Boleto();
        
        if ($post = \Yii::$app->request->post()) {
            try {
                $model->load($post);
                $model->empresa_id = \Yii::$app->user->identity->empresa_id;                
                $model->situacao_fluxo = Boleto::SITUACAO_FLUXO_BOLETO_NAO_ENVIADO_API;
                $model->situacao_pagamento = Boleto::SITUACAO_PAGAMENTO_PENDENTE;

                // tenta salvar e gerara o boleto
                if ($save = $model->save()) {                    
                    $model->gerarBoleto();
                }

                // lista as Ocorrencias para este boleto
                $ocorrencias = \Yii::$app->db->createCommand("SELECT COUNT(*) FROM boleto_ocorrencia WHERE boleto_id = :id AND situacao = 0")
                ->bindValue(':id', $model->id ? $model->id : $model->getPrimaryKey())
                ->queryScalar();

                // alerta para caso a model não seja salva
                if (!$save) {
                    \Yii::$app->session->setFlash('warning', '<i class="fa fa-exclamation-triangle"></i>&nbsp; Não foi possível salvar o boleto: '.Util::renderModelErrors($model->errors));
                
                // alerta para caso a model seja salva, porém a API tenha retornado algum ocorrencia
                } else {
                    if ($save && $ocorrencias > 0) {
                        \Yii::$app->session->setFlash('warning', "<i class='fa fa-exclamation-triangle'></i>&nbsp; Boleto foi salvo com {$ocorrencias} ocorrências, clique em <span class'label label-warning'><i class='fa fa-pencil-alt'></i></i>&nbsp;, resolva as pendências e tente salvar novamente.");
                    } else {                         
                        \Yii::$app->session->setFlash('success', '<i class="fa fa-check"></i>&nbsp; O boleto foi criado com sucesso.');
                    }
                    return $this->redirect(['index']);
                }
            } catch (\Exception $e) {
                $log = new SystemError(['mensagem' => "Erro ao emitir um boleto", 'arquivo' => $e->getFile(), 'arquivo_metodo' => $this->action->actionMethod, 'arquivo_linha' => $e->getLine(), 'tipo' => SystemError::TIPO_FATAL, 'exception' => $e->getMessage(), 'tratamento' => SystemError::TRATAMENTO_ERRO_NAO_TRATADO]);
                $log->save();
                
                \Yii::$app->session->setFlash('danger', "<i class='fa fa-exclamation-triangle'></i>&nbsp; {$e->getMessage()}");
                return $this->redirect(['index']);
            }
        }
        
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Altera um boleto
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($post = \Yii::$app->request->post()) {
            try {
                $model->load($post);
                $model->empresa_id = \Yii::$app->user->identity->empresa_id;                
                $model->situacao_fluxo = Boleto::SITUACAO_FLUXO_BOLETO_NAO_ENVIADO_API;
                $model->situacao_pagamento = Boleto::SITUACAO_PAGAMENTO_PENDENTE;
                
                // tenta salvar e gerara o boleto
                if ($save = $model->save()) {
                    $model->gerarBoleto();
                }
                
                // lista as Ocorrencias para este boleto
                $ocorrencias = Yii::$app->db->createCommand("SELECT COUNT(*) FROM boleto_ocorrencia WHERE boleto_id = :id AND situacao = 0")->bindValue(':id', $this->id)->queryScalar();
                
                // alerta para caso a model não seja salva
                if (!$save) {
                    \Yii::$app->session->setFlash('warning', '<i class="fa fa-exclamation-triangle"></i>&nbsp; Não foi possível salvar o boleto: '.Util::renderModelErrors($model->errors));
                
                // alerta para caso a model seja salva, porém a API tenha retornado algum ocorrencia
                } else {
                    if ($save && $ocorrencias > 0) {
                        \Yii::$app->session->setFlash('warning', '<i class="fa fa-exclamation-triangle"></i>&nbsp; Boleto foi salvo porém não foi possível gerar devido a pendências, clique em <span class"label label-warning"><i class="fa fa-pencil-alt"></i></i>&nbsp;, resolva as pendências e tente reenviar novamente.');
                    } else {                        
                        \Yii::$app->session->setFlash('success', 'Boleto alterado com sucesso!');
                    }
                    
                    return $this->redirect(['index']);
                }
            } catch(\Exception $e) {
                $log = new SystemError(['mensagem' => "Erro ao alterar um boleto", 'arquivo' => $e->getFile(), 'arquivo_metodo' => $this->action->actionMethod, 'arquivo_linha' => $e->getLine(), 'tipo' => SystemError::TIPO_FATAL, 'exception' => $e->getMessage(), 'tratamento' => SystemError::TRATAMENTO_ERRO_NAO_TRATADO]);
                $log->save();
                
                \Yii::$app->session->setFlash('danger', "<i class='fa fa-exclamation-triangle'></i>&nbsp; {$e->getMessage()}");
                return $this->redirect(['index']);
            }
        }
        
        // DataProvider com as ocorrencias para este boleto
        $dataProvider = new ActiveDataProvider([
            'query' => BoletoOcorrencia::find()->where(['boleto_id' => $model->id, 'situacao' => BoletoOcorrencia::SITUACAO_PENDENTE]) 
        ]);
        
        return $this->render('update', [
            'model' => $model,
            'dataProvider' => $dataProvider
        ]);
    }

    /**
     * deleta o boleto
     */
    public function actionDelete($id)
    {
        try {            
            $transaction = \Yii::$app->db->beginTransaction();
            
            // busca o boleto
            $model = $this->findModel($id);
            // deleta primeiro as ocorrencias
            BoletoOcorrencia::deleteAll(['boleto_id' => $model->id]);            
            // deleta o boleto
            $model->delete();
            $transaction->commit();
            
        } catch (\Exception $e) {
            $transaction->rollBack();  
            $log = new SystemError(['mensagem' => "Erro ao alterar um boleto", 'arquivo' => $e->getFile(), 'arquivo_metodo' => $this->action->actionMethod, 'arquivo_linha' => $e->getLine(), 'tipo' => SystemError::TIPO_FATAL, 'exception' => $e->getMessage(), 'tratamento' => SystemError::TRATAMENTO_ERRO_NAO_TRATADO]);
            $log->save();
            \Yii::$app->session->setFlash('danger', "<i class='fa fa-exclamation-triangle'></i>&nbsp; Houve um erro inesperado: {$e->getMessage()}");
        }

        return $this->redirect(['index']);
    }

    /**
     * Busca uma model de boleto
     */
    protected function findModel($id)
    {
        if (($model = Boleto::findOne($id)) !== null) {
            return $model;
        }
        
        throw new NotFoundHttpException();
    }
}
