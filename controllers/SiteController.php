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
use app\base\Util;
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
                                
                                // valida a planilha
                                if (!array_key_exists('CNPJCPF', $data) || !array_key_exists('NOME_RAZAO', $data)) {
                                    throw new \Exception('O arquivo de importação não é válido.');
                                }                                
                                // valida se a linha esta vazia
                                if (empty($data['CNPJCPF']) || empty($data['NOME_RAZAO'])) {
                                    continue;
                                }
                                
                                // seta e remove a mascara do documento
                                $documento = Util::unmask($data['CNPJCPF']);
                                
                                // busca um cliente e verifica se é o mesmo que esta sendo cadastrado
                                if ($cliente = Cliente::findOne(['documento' => $documento])) {
                                    if (strtoupper($cliente->nome) != strtoupper($data['NOME_RAZAO'])) {
                                        throw new \Exception('Não é possível cadastrar um cliente diferente usando o mesmo CPF/CNPJ.');     
                                    }
                                    
                                    // reseta o contrato temporario
                                    if ($contratoTemp->id_cliente != $cliente->id) {
                                        $contratoTemp = null;
                                    }
                                } else {                                    
                                    $cliente = new Cliente();
                                    $cliente->documento = $documento;
                                    
                                    // valida o tipo do cliente
                                    if (strlen($documento) == 11) {
                                        $cliente->tipo = Cliente::TIPO_FISICO;
                                    } elseif (strlen($documento)) {
                                        $cliente->tipo = Cliente::TIPO_JURIDICO;
                                    } else {
                                        throw new \Exception('O CPF/CNPJ informado não é um número válido.');
                                    }
                                    
                                    // seta o nome
                                    $cliente->nome = ucwords(strtolower($data['NOME_RAZAO']));
                                    $cliente->nome_pai = ucwords(strtolower($data['PAI']));
                                    $cliente->nome_mae = ucwords(strtolower($data['MAE']));
                                    
                                    // salva a model
                                    if (!$cliente->save()) {
                                        throw new \Exception(Util::renderErrors($cliente->getErrors()));
                                    }
                                    
                                    // reseta o contrato temporario
                                    $contratoTemp = null;
                                }
                                
                                // seta os dados basicos e o contrato do cliente
                                //                                
                                // se ja cadastrou um contrato para este cliente
                                // entao os dados basicos do cliente ja foram adicionados
                                if (!$contrato = $contratoTemp) { 
                                    // adiciona os telefones do cliente
                                    $cliente->addTelefone($data['TES_RES'], Telefone::TIPO_RESIDENCIAL);
                                    $cliente->addTelefone($data['TES_CON'], Telefone::TIPO_COMERCIAL);
                                    $cliente->addTelefone($data['CELULAR'], Telefone::TIPO_MOVEL);
                                    
                                    // adiciona o email
                                    if (isset($data['EMAIL07']) && !empty($data['EMAIL07'])) {
                                        $email = new Email();      
                                        $email->id_cliente = $cliente->id ? $cliente->id : $cliente->getPrimaryKey();;
                                        $email->email = $data['EMAIL07'];
                                        $email->ativo = Email::SIM;
                                        
                                        // salva a model
                                        if (!$email->save()) {
                                            throw new \Exception(Util::renderErrors($email->getErrors()));
                                        }
                                    }
                                    
                                    // valida e seta o endereco
                                    if ((isset($data['ENDERECO']) && !empty($data['ENDERECO'])) || 
                                        (isset($data['CEP']) && !empty($data['CEP']))
                                    ) {
                                        // remove a mascara do cep
                                        $cep = Util::unmask($data['CEP'], true);
                                        
                                        if (!Endereco::findOne([
                                                'id_cliente' => $cliente->id,
                                                'logradouro' => $data['ENDERECO'],
                                                'numero' => $data['NUMERO'],
                                                'cep' => $data['CEP'],
                                            ])
                                        ) {
                                            $endereco = new Endereco();
                                            $endereco->id_cliente = $cliente->id ? $cliente->id : $cliente->getPrimaryKey();
                                            $endereco->logradouro = $data['ENDERECO'];
                                            $endereco->numero = (string) $data['NUMERO'];
                                            $endereco->cep = $cep;
                                            $endereco->bairro = $data['BAI_CLI07'] ? $data['BAI_CLI07'] : $data['BAIRRO'];
                                            
                                            // busca o estado
                                            if (!$estado = Estado::findOne(['sigla' => $data['UF']])) {
                                                throw new \Exception("Não foi possível encontrar um estado com a sigla: \"{$data['UF']}\"");
                                            }
                                            // busca a cidade
                                            if (!$cidade = $estado->findCidade($data['CIDADE'])) {
                                                throw new \Exception("Não foi possível encontrar uma cidade com o nome: \"{$data['CIDADE']}\"");
                                            }
                                            
                                            // seta a cidade e o estado
                                            $endereco->cidade_id = $cidade->id;
                                            $endereco->estado_id = $estado->id;
                                            
                                            // salva a model
                                            if (!$endereco->save()) {
                                                throw new \Exception(Util::renderErrors($endereco->getErrors()));
                                            }
                                        }
                                    }
                                
                                    // TODO
                                    // campo DTC_CLI07 não tem conexão
                                    
                                    // cria um novo contrato                              
                                    $contrato = new Contrato();
                                    $contrato->id_cliente = $cliente->id ? $cliente->id : $cliente->getPrimaryKey();
                                    $contrato->data_cadastro = Util::formatDateToSave($data['DATA_CONTRATO'], Util::DATE_EXCEL);
                                    $contrato->observacao = $data['OBSERVCAO_CONTRATO'];
                                    $contrato->tipo = Contrato::getTipoByName($data['PRODUTO']);
                                    $contrato->num_contrato = $data['NOCONTRATO'];
                                    $contrato->num_plano = (String) $data['PLANO'];

                                    // seta  vencimento do contrato
                                    if (empty($contrato->data_vencimento)) {
                                        $contrato->data_vencimento = Util::formatDateToSave($data['VENCIMENTO'], Util::DATE_EXCEL);
                                    }
                                }

                                // soma o valor do contrato
                                $contrato->valor = $contrato->valor + $data['VALOR'];
                                
                                // salva a model
                                if (!$contrato->save()) {
                                    throw new \Exception(Util::renderErrors($contrato->getErrors()));
                                }
                                
                                // se o contrato tiver mais de 1 parcela, 
                                // salva o contrato temporariamente em uma variavel
                                if (isset($data['PARCELA']) && $data['PARCELA'] > 1) {
                                    $contratoTemp = $contrato;
                                }
                                
                                if (isset($data['OBS_PARCELA'])) {
                                    // formata os valores removendo a virgula
                                    $data['VALOR'] = str_replace(',', '', $data['VALOR']);
                                    $data['SALDO'] = str_replace(',', '', $data['SALDO']);
                                    $data['SALDO'] = str_replace(',', '', $data['SALDO']);
                                    
                                    $parcela = new ContratoParcela();
                                    $parcela->id_contrato = $contrato->id ? $contrato->id : $contrato->getPrimaryKey();
                                    $parcela->num_parcela = $data['OBS_PARCELA'];
                                    $parcela->data_vencimento = Util::formatDateToSave($data['VENCIMENTO'], Util::DATE_EXCEL);
                                    $parcela->valor = $data['VALOR'];
                                    $parcela->multa = $data['ENCARGO'];
                                                                        
                                    // valida os valores do contrato
                                    if (strval($data['VALOR'] + $data['ENCARGO']) != strval($data['SALDO'])) {
                                        throw new \Exception("Não foi possível salvar o contrato na linha \"{$posicao}\", pois o saldo da parcela \"{$data['OBS_PARCELA']}\" diverge do saldo calculado.");
                                    }
                                    
                                    // seta o saldo total
                                    $parcela->total = $data['SALDO'];
                                    
                                    // salva a model
                                    if (!$parcela->save()) {
                                        throw new \Exception(Util::renderErrors($parcela->getErrors()));
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
}
