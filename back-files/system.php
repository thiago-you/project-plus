<?php

namespace app\base;

use Yii;

class System
{
    protected $dataBaseConfig;
    protected $client; 
    
    /**
     * Método construtor, executa metodos de rotinas e busca configuração do client que esta acessando
     * @param string $client
     */
    public function __construct($client)
    {
        $this->client = $client;
    
        //Cria a configuraÃ§Ã£o inicial do cliente
        $this->setupClient();
        //Executa rotinas padrÃµes do sistema
        $this->systemRoutines();
    }

    /**
     * @return string configurações do banco de dados do client
     */
    public function getDataBaseConfig()
    {
        return $this->dataBaseConfig;
    }

    /**
     * @return string retorna o nome do client
     */
    public function getClient()
    {
        return $this->client;
    }
    
    /**
     * Método que executa as configurações do cliente que esta acessando
     */
    private function setupClient()
    {

        //Verifica se foi definida a session que determina a pasta do sistema
        //caso não tenha sido especificada, define a pasta conforme a url que o cliente está acessando
        if (Yii::$app->session->get('PASTA_SISTEMA')){
            $client = Yii::$app->session->get('PASTA_SISTEMA');
        }         
        
        //Busca o arquivo de configuração do banco
        $this->dataBaseConfig = require($_SERVER['DOCUMENT_ROOT'].'/../clientes/'.$this->client.'/config/db.php');
        
        //Seta a conexão ao banco da empresa que está logando
        Yii::$app->db->dsn = $this->dataBaseConfig['dsn'];
        Yii::$app->db->username = $this->dataBaseConfig['username'];
        Yii::$app->db->password = $this->dataBaseConfig['password'];
        Yii::$app->db->charset = $this->dataBaseConfig['charset'];
        
        
        //Define variaveis de sessao que serão utilizadas nos parametros
        Yii::$app->session->set('CLIENT', $this->client);
        
        //Seta os parametros da empresa
        Yii::$app->params = [
            'adminEmail' => 'admin@example.com',
            'user.passwordResetTokenExpire' => 360000,
            'hash'=>'gercpn2016',
            'menu' => 'mega-menu',
            'version' => '2.2.26',
            'plano' => 'MASTER',
            'numero_usuarios_simultaneos' => 10,
            'dbconnection' => [
                'host' => str_replace('mysql:host=', '', (explode(';', $this->dataBaseConfig['dsn'])[0])),#IP
                'username' => $this->dataBaseConfig['username'],
                'password' => $this->dataBaseConfig['password'],
                'database' => str_replace('dbname=', '', (explode(';', $this->dataBaseConfig['dsn'])[1])),
            ],
            'nota-fiscal-entrada' => [
                  'nota-fiscal-entrada' => realpath(dirname(__FILE__).'/../')."/clientes/{$this->client}/nfe-entrada",
            ],
            'pedido' => [
                'pdf' => realpath(dirname(__FILE__).'/../')."/clientes/{$this->client}/pedido/pdf/",                 
            ],
            'notification-sound' => [
                'sound_1' => '/midia/job.mp3',
            ],
            'iddel-img' => [
                'mixirica_1' => '/midia/mixirica_1.png',
                'mixirica_2' => '/midia/mixirica_2.png',
            ],
            'boleto' => [
                'pdf' => realpath(dirname(__FILE__).'/../')."/clientes/{$this->client}/boleto/pdf/",
                'remessa' => realpath(dirname(__FILE__).'/../')."/clientes/{$this->client}/boleto/remessa/",
                'retorno' => realpath(dirname(__FILE__).'/../')."/clientes/{$this->client}/boleto/retorno/",
            ],
            'nfse' => [
                'homologacao' => [
                    'canceladas'=> realpath(dirname(__FILE__).'/../') . "/clientes/{$this->client}/nfse/homologacao/canceladas/". date('Y-m-d'),
                    'enviadas'=> realpath(dirname(__FILE__).'/../') . "/clientes/{$this->client}/nfse/homologacao/enviadas/" . date('Y-m-d'),
                ],
                'producao' => [
                    'canceladas'=> realpath(dirname(__FILE__).'/../') . "/clientes/{$this->client}/nfse/producao/canceladas/" . date('Y-m-d'),
                    'enviadas'=> realpath(dirname(__FILE__).'/../') . "/clientes/{$this->client}/nfse/producao/enviadas/" . date('Y-m-d'),
                ]
            ],
            'relatorios' => [
                'relatorio_clientes' => realpath(dirname(__FILE__).'/../')."/relatorios/relatorio_clientes.jrxml",
                'relatorio_colaboradores' => realpath(dirname(__FILE__).'/../')."/relatorios/relatorio_colaboradores.jrxml",
                'recibo' => realpath(dirname(__FILE__).'/../')."/relatorios/recibo.jrxml",
                'compromisso-pagamento' => realpath(dirname(__FILE__).'/../')."/relatorios/compromisso-pagamento.jrxml",
            ],
            'nota_fiscal' => [
                'nota_fiscal' => realpath(dirname(__FILE__).'/../')."/clientes/{$this->client}/nfe/",
                'distribuidas' => realpath(dirname(__FILE__).'/../')."/clientes/{$this->client}/nfe/distribuidas",
                'download' => realpath(dirname(__FILE__).'/../')."/clientes/{$this->client}/nfe/download",
                'homologacao' => [
                    'canceladas' => realpath(dirname(__FILE__).'/../')."/clientes/{$this->client}/nfe/homologacao/canceladas",
                    'cartacorrecao' => realpath(dirname(__FILE__).'/../')."/clientes/{$this->client}/nfe/homologacao/cartacorrecao",
                    'enviadas' => realpath(dirname(__FILE__).'/../')."/clientes/{$this->client}/nfe/homologacao/enviadas",
                    'inutilizadas' => realpath(dirname(__FILE__).'/../')."/clientes/{$this->client}/nfe/homologacao/inutilizadas",
                    'pdf' =>  realpath(dirname(__FILE__).'/../')."/clientes/{$this->client}/nfe/homologacao/pdf",
                    'temporarias' => realpath(dirname(__FILE__).'/../')."/clientes/{$this->client}/nfe/homologacao/temporarias",
                ],   
                'producao' => [
                    'canceladas' => realpath(dirname(__FILE__).'/../')."/clientes/{$this->client}/nfe/producao/canceladas",
                    'cartacorrecao' => realpath(dirname(__FILE__).'/../')."/clientes/{$this->client}/nfe/producao/cartacorrecao",
                    'enviadas' => realpath(dirname(__FILE__).'/../')."/clientes/{$this->client}/nfe/producao/enviadas",
                    'inutilizadas' => realpath(dirname(__FILE__).'/../')."/clientes/{$this->client}/nfe/producao/inutilizadas",
                    'pdf' =>  realpath(dirname(__FILE__).'/../')."/clientes/{$this->client}/nfe/producao/pdf",
                    'temporarias' => realpath(dirname(__FILE__).'/../')."/clientes/{$this->client}/nfe/producao/temporarias",
                ], 
            ],
            'config' => [
                'nota_fiscal' => realpath(dirname(__FILE__).'/../')."/clientes/{$this->client}/config/config-".\Yii::$app->user->identity->empresa_id.".json",
                'certificados' => realpath(dirname(__FILE__).'/../')."/clientes/{$this->client}/certificados/",
                'params_local' => realpath(dirname(__FILE__).'/../')."/clientes/{$this->client}/config/params.local.php",
            ],
            'dir' => [
                'nfe' => realpath(dirname(__FILE__)."/../")."/clientes/{$this->client}/nfe/",                
                'yii_raiz' => realpath(dirname(__FILE__).'/../').'/',
                'img' => "/{$this->client}/img/",
                'empresa' => [
                    'imagem' => "/clientes/{$this->client}/empresa/",
                ],
                'colaborador' => [
                    'imagem' => "/clientes/{$this->client}/colaborador/",
                ],
                'produto' => [
                    'imagem' => "/clientes/{$this->client}/produto/"
                ],                
            ],
            'upload' => [
                'empresa' => [
                    //relativo a pasta web
                    'imagem' => realpath(dirname(__FILE__).'/../')."/clientes/{$this->client}/empresa",
                    'imagem_real' => realpath(dirname(__FILE__).'/../web/clientes/'.$this->client.'/empresa/'),
                ],
                'colaborador' => [
                    //relativo a pasta web
                    'imagem' => "clientes/{$this->client}/colaborador/",
                ],
                'produto' => [
                    //relativo a pasta web
                    'imagem' => "clientes/{$this->client}/produto/"
                ],
                //relativo a pasta raiz da aplicacao
                'certificado' => "/clientes/{$this->client}/certificado/",
                'nfe' =>  '/nfe/',
                'nfe-download' => "/clientes/{$this->client}/nfe/download",
            ],
            'email-send' => [
                'anexo-enviado' => realpath(dirname(__FILE__).'/../') . "/clientes/{$this->client}/email/anexo-enviado/",
                'anexo-temp' => realpath(dirname(__FILE__).'/../') . "/clientes/{$this->client}/email/temp/",
            ],
            'log' => realpath(dirname(__FILE__).'/../')."/clientes/{$this->client}/log/log_error.txt",
            'default' => [
                'empresa' => [
                        'imagem' => '/img/empresa-sem-logo.png',
                    ],
                'colaborador' => [
                        'imagem' => '/img/user.jpg',
                    ],
                'produto' => [
                        'imagem' => '/img/produto-sem-imagem.png',
                    ],
            ],
            'email'=>[
                'from'=>'naoresponda@cpninformatica.com.br',
                'port'=>587,
                'smtp'=>'smtp.gmail.com',
                'secure'=>'tls',
                'user'=>'naoresponda@cpninformatica.com.br',
                'pass'=>'cpn#2010',
                'reply'=>'comercial@cpninformatica.com.br'
            ],
            'tipo-notificacao' => [// tipos de notificações emitidas pelo sistema
                'titulo-protestado'=>'Título Protestado',
                'nf-contingencia'=>'Nota em Contingência',
            ],
            'notificao'=>[
                'financeiro'=>[
                    'titulo-protestado'=>[
                        'titulo'=>'!Importante, Título protestado',
                        'mensagem'=>'Título protestado em cartório',
                    ],
                ],
                'nota-fiscal'=>[
                    'nf-contigencia'=>[
                        'titulo'=>'Notas em contingência',
                        'mensagem'=>'Existem notas em contingência, favor reenvia-las para o SEFAS',
                    ]
                ]
            ],
        ];       
        
        //Verifica se foi criado o arquivo com os parâmetros locais da aplicação, se foi irá fazer um merge com mesmo
        if (is_file(\Yii::$app->params['config']['params_local'])){
            $paramsLocal = require(\Yii::$app->params['config']['params_local']);
            Yii::$app->params = array_merge(Yii::$app->params, $paramsLocal);
        }
    }    
    
    /**
     * ROTINAS DO SISTEMA, IMPLEMENTE AQUI FUNCIONALIDADES QUE DESEJA QUE SEJAM EXECUTAS SEMPRE QUE HOUVER UMA REQUISIÃ‡ÃƒO
     */
    private function systemRoutines()
    {
        //Verifica se a um arquivo com log de erro na pasta do cliente, se houver irÃ¡ gravar estes erros nos banco
        $systemError = new SystemError();
        $systemError->checkFileLog_Error();
    }
}
