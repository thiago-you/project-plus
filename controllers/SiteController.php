<?php
namespace app\controllers;

use Yii;
use yii\web\Response;
use app\assets\AppAsset;
use app\models\LoginForm;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;

use moonland\phpexcel\Excel;
use yii\web\UploadedFile;
use app\base\Helper;
use app\models\Cliente;
use app\models\Telefone;
use app\models\Endereco;
use app\models\Estado;
use app\models\Contrato;
use app\models\ContratoParcela;
use app\models\Email;
use yii\web\NotFoundHttpException;

class SiteController extends BaseController
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        // se for guest redireciona para o login
        if (\Yii::$app->user->isGuest) {
            return $this->redirect(['site/login'])->send();
        }
        
        // registra a api para gerar graficos
        AppAsset::register(\Yii::$app->view)->js[] = 'plugins/chart.js/dist/Chart.js';
        
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin($invalidAcess = null)
    {
        // se não for guets retorna para a home
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        // layout de login
        $this->layout = 'login';
        
        $model = new LoginForm();
        if ($model->load(\Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }
        
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
    
    /**
     * TODO
     * Realiza o upload e processamento de um arquivo Excel
     */
    public function actionImportacao()
    {
        // model do import file
        $model = new \yii\base\DynamicModel(['fileImport' => 'File Import']);
        $model->addRule(['fileImport'],'required');
        $model->addRule(['fileImport'], 'file', ['extensions' => 'ods,xls,xlsx']);

        if ($post = \Yii::$app->request->post()) {
            try {                
                $transaction = \Yii::$app->db->beginTransaction();
                
                // pega a instancia do arquivo
                $model->fileImport = UploadedFile::getInstance($model, 'fileImport');
                
                if ($model->fileImport && $model->validate()) {
                    // importa o arquivo excel para array
                    $excelFile = Excel::widget([
                        'mode' => 'import',
                        'fileName' => $model->fileImport->tempName,
                        'setFirstRecordAsKeys' => true,
                        'setIndexSheetByName' => false,
                    ]); 
                    
                    // valida a planilha
                    if (!$excelFile || !is_array($excelFile)) {
                        throw new \Exception('Houve um erro ao importar o arquivo.');                        
                    }
                    
                    // varre as planilhas
                    foreach ($excelFile as $key => $workSheet) {
                        if (is_array($workSheet) && count($workSheet) > 0) {
                            // seta variaveis de controle
                            $posicao = 1;
                            $contratoTemp = null;
                            
                            // varre cada linha da planilha
                            foreach ($workSheet as $key => $data) {
                                // posicao da linha
                                ++$posicao;
                                
                                // transforma o array em objeto e valida a linha
                                // se retornar false (ou null) pula para a proxima linha
                                if (!$data = $this->synthesizeWorksheet($data)) {
                                    continue;
                                }
                                                            
                                // busca um cliente e verifica se é o mesmo que esta sendo cadastrado
                                if ($cliente = Cliente::findOne(['documento' => $data->documento])) {
                                    if (strtoupper($cliente->nome) != strtoupper($data->nome)) {
                                        throw new \Exception('Não é possível cadastrar um cliente diferente usando o mesmo CPF/CNPJ.');     
                                    }
                                    
                                    // reseta o contrato temporario
                                    if ($contratoTemp && $contratoTemp->id_cliente != $cliente->id) {
                                        $contratoTemp = null;
                                    }
                                } else {                                    
                                    $cliente = new Cliente();
                                    $cliente->documento = $data->documento;
                                    
                                    // seta o tipo do cliente
                                    if (strlen($data->documento) == 11) {
                                        $cliente->tipo = Cliente::TIPO_FISICO;
                                    } else {
                                        $cliente->tipo = Cliente::TIPO_JURIDICO;
                                    }
                                    
                                    // seta o nome
                                    $cliente->nome = $data->nome;
                                    $cliente->nome_pai = $data->nome_pai;
                                    $cliente->nome_mae = $data->nome_mae;
                                    
                                    // salva a model
                                    if (!$cliente->save()) {
                                        throw new \Exception(Helper::renderErrors($cliente->getErrors()));
                                    }
                                    
                                    // reseta o contrato temporario
                                    $contratoTemp = null;
                                }
                                
                                // seta o id do cliente
                                $id_cliente = $cliente->id ? $cliente->id : $cliente->getPrimaryKey();
                                
                                // seta os dados basicos e o contrato do cliente
                                //                                
                                // se ja cadastrou um contrato para este cliente
                                // entao os dados basicos do cliente ja foram adicionados
                                if (!$contrato = $contratoTemp) { 
                                    // adiciona os telefones do cliente
                                    $cliente->addTelefone($data->tel_residencial, Telefone::TIPO_RESIDENCIAL, $id_cliente);
                                    $cliente->addTelefone($data->tel_comercial, Telefone::TIPO_COMERCIAL, $id_cliente);
                                    $cliente->addTelefone($data->tel_celular, Telefone::TIPO_MOVEL, $id_cliente);
                                    
                                    // adiciona o email
                                    if (!empty($data->email)) {
                                        $email = new Email();      
                                        $email->id_cliente = $id_cliente;
                                        $email->email = $data->email;
                                        $email->ativo = Email::SIM;
                                        
                                        // salva a model
                                        if (!$email->save()) {
                                            throw new \Exception(Helper::renderErrors($email->getErrors()));
                                        }
                                    }
                                    
                                    // valida e seta o endereco
                                    if (!empty($data->logradouro) && !empty($data->cep)) {
                                        if (!Endereco::findEndereco($id_cliente, $data->logradouro, $data->numero, $data->cep)) {
                                            $endereco = new Endereco();
                                            $endereco->id_cliente = $id_cliente;
                                            $endereco->logradouro = $data->logradouro;
                                            $endereco->numero = (string) $data->numero;
                                            $endereco->cep = $data->cep;
                                            $endereco->bairro = $data->bairro;
                                            
                                            // busca o estado
                                            if (!$estado = Estado::findOne(['sigla' => $data->estado])) {
                                                throw new \Exception("Não foi possível encontrar um estado com a sigla: \"{$data['UF']}\"");
                                            }
                                            // busca a cidade
                                            if (!$cidade = $estado->findCidade($data->cidade)) {
                                                throw new \Exception("Não foi possível encontrar uma cidade com o nome: \"{$data['CIDADE']}\"");
                                            }
                                            
                                            // seta a cidade e o estado
                                            $endereco->cidade_id = $cidade->id;
                                            $endereco->estado_id = $estado->id;
                                            
                                            // salva a model
                                            if (!$endereco->save()) {
                                                throw new \Exception(Helper::renderErrors($endereco->getErrors()));
                                            }
                                        }
                                    }
                                
                                    // cria um novo contrato                              
                                    $contrato = new Contrato();
                                    $contrato->id_cliente = $id_cliente;
                                    $contrato->data_cadastro = $data->data_contrato;
                                    $contrato->data_negociacao = $data->data_contrato;
                                    $contrato->observacao = $data->obs_contrato;
                                    $contrato->tipo = Contrato::getTipoByName($data->produto);
                                    $contrato->num_contrato = $data->num_contrato;
                                    $contrato->num_plano = (String) $data->plano;

                                    // seta o vencimento do contrato
                                    if (empty($contrato->data_vencimento)) {
                                        $contrato->data_vencimento = $data->data_vencimento;
                                    }
                                }

                                // soma o valor do contrato
                                $contrato->valor = $contrato->valor + $data->valor;
                                
                                // salva a model
                                if (!$contrato->save()) {
                                    throw new \Exception(Helper::renderErrors($contrato->getErrors()));
                                }
                                
                                // se o contrato tiver mais de 1 parcela, 
                                // salva o contrato temporariamente em uma variavel
                                if ($data->parcela > 1) {
                                    $contratoTemp = $contrato;
                                }

                                // valida e seta a parcela
                                if ($data->parcela && $data->data_vencimento && $data->valor) {
                                    $parcela = new ContratoParcela();
                                    $parcela->id_contrato = $contrato->id ? $contrato->id : $contrato->getPrimaryKey();
                                    $parcela->num_parcela = $data->obs_parcela;
                                    $parcela->data_vencimento = $data->data_vencimento;
                                    $parcela->valor = $data->valor;
                                    $parcela->multa = $data->encargo;
                                                                        
                                    // valida os valores do contrato
                                    if (strval($data->valor + $data->encargo) != strval($data->saldo)) {
                                        throw new \Exception("Não foi possível salvar o contrato na linha \"{$posicao}\", pois o saldo da parcela \"{$data['OBS_PARCELA']}\" diverge do saldo calculado.");
                                    }
                                    
                                    // seta o saldo total
                                    $parcela->total = $data->saldo;
                                    
                                    // salva a model
                                    if (!$parcela->save()) {
                                        throw new \Exception(Helper::renderErrors($parcela->getErrors()));
                                    }
                                }
                            }
                        }
                    }   
                }
                
                $transaction->commit();
                \Yii::$app->session->setFlash('success', '<i class="fa fa-check"></i>&nbsp; O arquivo foi importado com sucesso.');
            } catch (\Exception $e) {
                $transaction->rollBack();
                \Yii::$app->session->setFlash('danger', "<i class='fa fa-exclamation-triangle'></i>&nbsp; Houve um erro na linha \"{$posicao}\": {$e->getMessage()}");
            }
        }
        
        // importacao
        return $this->render('importacao', [
            'model' => $model,
        ]);
    }
    
    /**
     * Retorna a lista de cidades do estado
     */
    public function actionCidades($ufId = '') 
    {
        // valida a requisicao
        if (!\Yii::$app->request->isAjax || empty($ufId)) {
            throw new NotFoundHttpException();
        }
        // busca todo o estado selecionado
        if (!$estado = Estado::findOne(['id' => $ufId])) {
            throw new NotFoundHttpException();
        }
        
        // busca todas as cidades do estado
        $options = '';
        if ($cidades = $estado->cidades) {
            foreach ($cidades as $cidade) {            
                $options .= "<option value='{$cidade->id}'>{$cidade->nome}</option>";
            }
        }
        
        return $options;
    }
    
    /**
     * Faz um parse do array da planilha excel em objeto
     */
    private function synthesizeWorksheet($data = []) 
    {
        // verifica se a linha é vazia
        if (empty($data)) {
            return false;
        }
        
        // valida a quantidade de colunas na linha
        if (count($data) > 0 && count($data) != 27) {
            throw new \Exception('O arquivo de importação não é válido. O número de colunas é diferente de 27.');
        }
        
        // cria o objeto
        $worksheet = new \stdClass();
        
        // seta as propriedades do objeto com base na posicao da coluna
        $worksheet->documento = current($data);
        $worksheet->nome = ucwords(strtolower(next($data)));
        $worksheet->num_contrato = next($data);
        $worksheet->data_contrato = next($data);
        $worksheet->plano = next($data);
        $worksheet->produto = next($data);
        $worksheet->obs_contrato = next($data);
        $worksheet->parcela = next($data);
        $worksheet->data_vencimento = next($data);
        $worksheet->valor = next($data);
        $worksheet->encargo = next($data);
        $worksheet->saldo = next($data);
        $worksheet->obs_parcela = next($data);
        $worksheet->tel_residencial = next($data);
        $worksheet->tel_comercial = next($data);
        $worksheet->tel_celular = next($data);
        $worksheet->email = next($data);
        $worksheet->logradouro = ucwords(strtolower(next($data)));
        $worksheet->numero = next($data);
        $worksheet->bairro = ucwords(strtolower(next($data)));
        $worksheet->cep = next($data);
        $worksheet->cidade = next($data);
        $worksheet->estado = next($data);
        $worksheet->ie_ins = next($data);
        $worksheet->data_sem_nome = next($data);
        $worksheet->nome_mae = next($data);
        $worksheet->nome_pai = next($data);
        
        // valida se o documento e nome foram enviados
        if (empty($worksheet->documento) || empty($worksheet->nome)) {
            return false;
        }

        // remove a mascara do documento e cep
        $worksheet->documento = Helper::unmask($worksheet->documento, true);
        
        // valida o documento
        if (strlen($worksheet->documento) != 11 && strlen($worksheet->documento) != 14) {
            throw new \Exception('O CPF/CNPJ não é um número válido.');
        }
        
        // valida o nome do pai e mae
        $worksheet->nome_mae = !empty(trim($worksheet->nome_mae)) ? $worksheet->nome_mae : null;
        $worksheet->nome_pai = !empty(trim($worksheet->nome_pai)) ? $worksheet->nome_pai : null;

        // remove a mascara do cep
        $worksheet->cep = Helper::unmask($worksheet->cep, true);
        
        // valida o numero do endereco
        $worksheet->numero = empty($worksheet->numero) ? 0 : $worksheet->numero;
        
        // valida a quantidade de parcela
        $worksheet->parcela = empty($worksheet->parcela) ? 0 : $worksheet->parcela;
        
        // formata as datas
        $worksheet->data_contrato = Helper::formatDateToSave($worksheet->data_contrato, Helper::DATE_EXCEL);
        $worksheet->data_vencimento = Helper::formatDateToSave($worksheet->data_vencimento, Helper::DATE_EXCEL);
        $worksheet->data_sem_nome = Helper::formatDateToSave($worksheet->data_sem_nome, Helper::DATE_EXCEL);
        
        // formata os valores removendo a virgula
        $worksheet->valor = str_replace(',', '', $worksheet->valor);
        $worksheet->encargo = str_replace(',', '', $worksheet->encargo);
        $worksheet->saldo = str_replace(',', '', $worksheet->saldo);
        
        // valida a observacao/numero da parcela
        $worksheet->obs_parcela = empty($worksheet->obs_parcela) ? 1 : $worksheet->obs_parcela;
        
        return $worksheet;
    }
}







