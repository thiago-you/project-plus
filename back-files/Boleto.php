<?php
namespace app\models;

use Yii;
use app\api\Api;
use app\base\Util;
use app\api\ApiFactory;
use app\modules\financeiro\models\Conta;
use app\modules\financeiro\models\Receita;
use app\modules\financeiro\models\ContaConfig;
use app\modules\financeiro\models\ReceitaParcela;
use app\modules\financeiro\models\ReceitaParcelaPgto;


/**
 * This is the model class for table "boleto".
 *
 * @property int    $id
 * @property int    $empresa_id
 * @property int    $conta_id
 * @property int    $cliente_id
 * @property int    $remessa_id
 * @property int    $receita_parcela_id
 * @property string $valor Valor final do boleto
 * @property string $data_vencimento
 * @property string $data_emissao
 * @property string $documento => Número para controle interno
 * @property string $instrucao => Parte que irá compor o corpo do boleto, Exemplo: Pagavél ate dia XX/XX/XXXX
 * @property string $nosso_numero => Gerado pela API BoletoCloud
 * @property string $token => Chave de acesso devolvido pela api ao gerar um novo boleto
 * @property int    $situacao_fluxo
 * @property int    $situacao_pagamento
 *
 * @property Clientes           $cliente
 * @property Conta              $conta
 * @property Empresa            $empresa
 * @property BoletoOcorrencia[] $ocorrencias
 * @property Remessa            $remessa
 * @property RetornoTitulos     $retornoTitulo
 * @property ReceitaParcela     $receitaParcela
 */
class Boleto extends \yii\db\ActiveRecord
{
    // situação do fluxo
    CONST SITUACAO_FLUXO_BOLETO_NAO_ENVIADO_API       = 0; // Boleto ainda não enviado a API
    CONST SITUACAO_FLUXO_BOLETO_GERADO_API            = 1; // Boleto gerado na API, com ocorrencias ou não
    CONST SITUACAO_FLUXO_BOLETO_EM_ARQUIVO_DE_REMESSA = 2; // Boleto em arquivo de remessa
    CONST SITUACAO_FLUXO_BOLETO_EM_ARQUIVO_RETORNO    = 3; // Boleto retornou em arquivo de retorno
    // situação do pagamento
    CONST SITUACAO_PAGAMENTO_PENDENTE   = 0; // Pagamento pendente antes do vencimento
    CONST SITUACAO_PAGAMENTO_ATRASADO   = 1; // Passou a data de vencimento
    CONST SITUACAO_PAGAMENTO_LIQUIDADO  = 2; // Pago, quitado etc...    
    CONST SITUACAO_PAGAMENTO_PROTESTADO = 3; // Protestado, cartorio, cobrança judicial etc...
    CONST SITUACAO_PAGAMENTO_DEVOLVIDO  = 4; // Venceu o prazo de dias para pagamento
    CONST SITUACAO_PAGAMENTO_PARCIAL    = 5; // Pagou parcial
    
    // flags para gerar financeiro
    public $financeiro;
    public $centro_custo;
    public $conta_orcamentaria;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'boleto';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['empresa_id', 'conta_id', 'cliente_id', 'valor', 'situacao_fluxo', 'situacao_pagamento', 'data_vencimento'], 'required'],
            [['empresa_id', 'conta_id', 'cliente_id', 'remessa_id', 'situacao_fluxo', 'situacao_pagamento', 'receita_parcela_id'], 'integer'],
            [['valor'], 'number'],
            [['data_emissao', 'centro_custo_id', 'conta_orcamentaria_id'], 'safe'],
            [['documento', 'nosso_numero'], 'string', 'max' => 50],
            [['instrucao'], 'string', 'max' => 100],
            [['token'], 'string', 'max' => 100],
            [['token'], 'unique'],
            [['cliente_id'], 'exist', 'skipOnError' => true, 'targetClass' => Clientes::className(), 'targetAttribute' => ['cliente_id' => 'id_cliente']],
            [['conta_id'], 'exist', 'skipOnError' => true, 'targetClass' => Conta::className(), 'targetAttribute' => ['conta_id' => 'id']],
            [['empresa_id'], 'exist', 'skipOnError' => true, 'targetClass' => Empresa::className(), 'targetAttribute' => ['empresa_id' => 'id']],
            [['remessa_id'], 'exist', 'skipOnError' => true, 'targetClass' => Remessa::className(), 'targetAttribute' => ['remessa_id' => 'id']],
        ];
    }
  
    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'              => 'Cód.',
            'empresa_id'      => 'Empresa',
            'conta_id'        => 'Conta',
            'cliente_id'      => 'Cliente',
            'remessa_id'      => 'Remessa',
            'valor'           => 'Valor',
            'documento'       => 'Documento',
            'instrucao'       => 'Instrução',
            'token'           => 'Token',
            'situacao_fluxo'  => 'Situação',
            'data_emissao'    => 'Data de Emissão',
            'nosso_numero'    => 'Nosso Número',
            'data_vencimento' => 'Data de Vencimento',
            'situacao_pagamento' => 'Pagamento',
            'receita_parcela_id' => 'Parcela',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCliente()
    {
        return $this->hasOne(Clientes::className(), ['id_cliente' => 'cliente_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getConta()
    {
        return $this->hasOne(Conta::className(), ['id' => 'conta_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getEmpresa()
    {
        return $this->hasOne(Empresa::className(), ['id' => 'empresa_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOcorrencias()
    {
        return $this->hasMany(BoletoOcorrencia::className(), ['boleto_id' => 'id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRemessa()
    {
        return $this->hasOne(Remessa::className(), ['id' => 'remessa_id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getReceitaParcela()
    {
        return $this->hasOne(ReceitaParcela::className(), ['id' => 'receita_parcela_id']);
    }
    
    /**
     * @inheritDoc
     * @see \yii\db\BaseActiveRecord::beforeSave()
     */       
    public function beforeSave($insert)
    {
        // formatando datas
        if ($this->data_emissao) {
            $this->data_emissao = Util::formatDateToSave($this->data_emissao, Util::DATE_DEFAULT);
        } else {
            $this->data_emissao = date('Y-m-d');
        }
        if ($this->data_vencimento) {
            $this->data_vencimento = Util::formatDateToSave($this->data_vencimento, Util::DATE_DEFAULT);
        }
        
        //Verificando instrução padrão
        if (empty($this->instrucao)) {
            $this->instrucao = $this->conta->config->instrucao_padrao;    
        }
        
        // fluxo padrão
        if (empty($this->situacao_fluxo)) {
            $this->situacao_fluxo = self::SITUACAO_FLUXO_BOLETO_NAO_ENVIADO_API;
        }
        // pagamento padrão
        if (empty($this->situacao_pagamento)) {
            $this->situacao_pagamento = self::SITUACAO_PAGAMENTO_PENDENTE;
        }
        
        // pagamento do boleto
        // verifica se o boleto foi pago para baixar o financeiro
        if (
            $this->situacao_pagamento == self::SITUACAO_PAGAMENTO_LIQUIDADO || 
            $this->situacao_pagamento == self::SITUACAO_PAGAMENTO_PARCIAL
        ) {
            $receitaParcela = ReceitaParcela::findOne(['id' => $this->receita_parcela_id]);
            // verifica se o boleto tem um CONTA A RECEBER
            if (!empty($receitaParcela)) {
                // verifica o titulo no arquivo de retorno, ver tabela retorno_titulos para duvidas
                $tituloPagamento = RetornoTitulos::findOne(['boleto_id' => $this->id]);
                
                // gera o pagamento da parcela, e busca informações do titulo para preencher na parcela da receita
                $receitaPagamento = new ReceitaParcelaPgto();
                $receitaPagamento->receita_parcela_id = $receitaParcela->id;
                $receitaPagamento->conta_id           = $this->conta_id;
                $receitaPagamento->tipo_pagamento     = 'B';
                $receitaPagamento->juros              = $tituloPagamento->juros;
                $receitaPagamento->data_pagamento     = time(); // data default
                $receitaPagamento->empresa_id         = $this->empresa_id;
                $receitaPagamento->status             = ReceitaParcelaPgto::STATUS_ACTIVE;
                $receitaPagamento->descricao          = "Baixa via arquivo de retorno códº ".$tituloPagamento->retorno_id;
                
                // seta a data de pagamento
                if ($tituloPagamento->data_credito) {
                    $receitaPagamento->data_pagamento = \Yii::$app->formatter->asTimestamp(Util::formatDateToSave($tituloPagamento->data_credito, Util::DATE_DEFAULT)); 
                }
                
                // verifica se o valor pago foi menor que o valor do boleto e lança como desconto
                if ($this->valor > $tituloPagamento->valor_pago) {
                    $receitaPagamento->valor    = $this->valor;
                    $receitaPagamento->desconto = ($this->valor -  $tituloPagamento->valor_pago);
                } elseif ($tituloPagamento->valor_pago > $this->valor) {//Verifica se o valor do pgto foi maior que o valor do boleto e lança como juros
                    $receitaPagamento->valor  = $this->valor;
                    $receitaPagamento->juros += ($tituloPagamento->valor_pago - $this->valor);
                } else {
                    $receitaPagamento->valor = $tituloPagamento->valor_pago; 
                }
                
                if (!$receitaPagamento->save()) {
                    throw new \Exception(Util::renderModelErrors($receitaPagamento->errors));
                }
           }
        }
        
        return parent::beforeSave($insert);    
    }
    
    /**
     * @inheritDoc
     * @see \yii\db\BaseActiveRecord::afterSave()
     */
    public function afterSave($insert, $changedAttributes)
    {
        // se flegou o campo contas a receber e não existe nenhuma receita gera uma nova
        $receita = Receita::findOne(['origem'=>$this::className(), 'id_externo'=>$this->id]);
        if ($this->financeiro && empty($receita)) {
            $receita = new Receita();
            $receita->origem           = $this::className();
            $receita->id_externo       = $this->id;
            $receita->numero_documento = $this->nosso_numero;
            $receita->cliente_id       = $this->cliente_id;
            $receita->empresa_id       = \Yii::$app->user->identity->empresa_id;
            $receita->conta_id         = $this->conta_id;
            $receita->centro_custo_id  = $this->centro_custo;
            $receita->num_parcelas     = 1;
            $receita->valor            = $this->valor;
            $receita->conta_orcamentaria_id = $this->conta_orcamentaria;
            $receita->vencimentoManualParcela = [$this->data_vencimento];
            
            if (!$receita->save()) {
                throw new \Exception(Util::renderModelErrors($receita->errors));
            }
        } elseif ($this->financeiro && $receita) {
            // se marcou a flag e já há uma receita vai alterar centro de custo e conta orcamentaria e os dados
            $receita->numero_documento = $this->nosso_numero;
            $receita->cliente_id       = $this->cliente_id;
            $receita->conta_id         = $this->conta_id;
            $receita->centro_custo_id  = $this->centro_custo;
            $receita->valor            = $this->valor;
            $receita->conta_orcamentaria_id = $this->conta_orcamentaria;
            $receita->vencimentoManualParcela = [$this->data_vencimento];
            
            if (!$receita->save()) {
                throw new \Exception(Util::renderModelErrors($receita->errors));
            }
        }
           
        return parent::afterSave($insert, $changedAttributes);
    }
    
    /**
     * @inheritDoc
     * @see \yii\db\BaseActiveRecord::afterFind()
     */
    public function afterFind()
    {
        // formata as datas para exibir
        $this->data_emissao = Util::formatDateToDisplay($this->data_emissao, Util::DATE_DEFAULT);
        $this->data_vencimento = Util::formatDateToDisplay($this->data_vencimento, Util::DATE_DEFAULT);
        
        return parent::afterFind();
    }
    /**
     * Método que cria uma lista CHAVE/VALOR para as possiveis situações dessa model
     */
    public static function situacoesFluxo()
    {
        return [
            self::SITUACAO_FLUXO_BOLETO_GERADO_API            => 'EMITIDO',
            self::SITUACAO_FLUXO_BOLETO_NAO_ENVIADO_API       => 'PENDENTE',
            self::SITUACAO_FLUXO_BOLETO_EM_ARQUIVO_DE_REMESSA => 'EM REMESSA',
            self::SITUACAO_FLUXO_BOLETO_EM_ARQUIVO_RETORNO    => 'ENVIADO'
        ];
    }
    
    /**
     * Método que cria uma lista CHAVE/VALOR para as possiveis situações dessa model
     */
    public static function situacoesPagamento()
    {
        return [
            self::SITUACAO_PAGAMENTO_LIQUIDADO  => 'LIQUIDADO',
            self::SITUACAO_PAGAMENTO_ATRASADO   => 'ATRASADO',
            self::SITUACAO_PAGAMENTO_PENDENTE   => 'PENDENTE',
            self::SITUACAO_PAGAMENTO_PARCIAL    => 'PAGO PARCIAL',
            self::SITUACAO_PAGAMENTO_PROTESTADO => 'PROTESTADO',
            self::SITUACAO_PAGAMENTO_DEVOLVIDO  => 'DEVOLVIDO'
        ];
    }
    
    // visualiza o boleto
    public function visualizarBoleto()
    {
        // instância um objeto da fabrica de API
        $api = ApiFactory::getInstance(Api::API_BOLETO_CLOUD, Api::RETURN_ARRAY, ($this->conta->config->ambiente == ContaConfig::AMBIENTE_PRODUCAO));
        // veja o metodo viewBoleto em BoletoCloud
        return $api->viewBoleto($this->token);
    }
    
    /**
     * Método que gera o boleto na API BoletoCloud
     * Condição o boleto ainda não pode ter sido gerado
     * @throws \Exception
     */
    public function gerarBoleto()
    {
        if ($this->situacao_fluxo == self::SITUACAO_FLUXO_BOLETO_NAO_ENVIADO_API) {
            if ($this->data_emissao) {
                $this->data_emissao = Util::formatDateToSave($this->data_emissao, Util::DATE_DEFAULT);
            }
            if ($this->data_vencimento) {
                $this->data_vencimento = Util::formatDateToSave($this->data_vencimento, Util::DATE_DEFAULT);
            }
            
            $cidade = Cidade::findOne(['id' => $this->cliente->id_cidade]);
            $estado = EstadoFederacao::findOne(['id' => $this->cliente->id_estado]);
            $config = ContaConfig::findOne(['conta_id' => $this->conta_id]);
            
            // montando o array exigido pela API    
            $modelArray = [
                'boleto.conta.token' => $config->token,
                'boleto.documento' => empty($this->documento) ? $this->id : $this->documento,
                'boleto.emissao' => empty($this->data_emissao) ? date('Y-m-d') : $this->data_emissao,
                'boleto.instrucao' => empty($this->instrucao) ? $this->conta->config->instrucao_padrao : $this->instrucao,
                'boleto.pagador.cprf' => ($this->cliente->tipo == Clientes::TIPO_PESSOA_FISICA)? Util::maskBackend($this->cliente->cpf, Util::MASK_CPF) : Util::maskBackend($this->cliente->cnpj, Util::MASK_CNPJ),
                'boleto.pagador.nome' => $this->cliente->nome,
                'boleto.pagador.endereco.logradouro' => $this->cliente->endereco,
                'boleto.pagador.endereco.bairro' => $this->cliente->bairro,
                'boleto.pagador.endereco.localidade' => $cidade->descricao,
                'boleto.pagador.endereco.uf' => $estado->unidade_federacao,
                'boleto.pagador.endereco.numero' => $this->cliente->numero,
                'boleto.pagador.endereco.cep' => Util::maskBackend($this->cliente->cep, Util::MASK_CEP),
                'boleto.pagador.endereco.complemento' => $this->cliente->complemento,
                'boleto.valor' => number_format($this->valor, 2, '.', ''),
                'boleto.vencimento' => $this->data_vencimento,
            ];      
            
            // instância um objeto da fabrica de API
            $api = ApiFactory::getInstance(Api::API_BOLETO_CLOUD, Api::RETURN_ARRAY, ($config->ambiente == ContaConfig::AMBIENTE_PRODUCAO) ? true : false);
            // metodo da API que gera o boleto
            $responseApi = $api->generatedBoleto($modelArray);
            
            // verifica se API retornou erros, se Sim o boleto fica como PENDENTE, 
            // se foi enviado o boleto fica como EMITIDO
            if (array_key_exists('causas', $responseApi)) {
                foreach ($responseApi['causas'] as $codigo => $causa) {
                    // verifica se esta ocorrencia ja existia para o boleto
                    $ocorrencia = BoletoOcorrencia::findOne([
                        'codigo' => $codigo, 
                        'descricao' => $causa, 
                        'boleto_id' => $this->id
                    ]);
                    
                    if (empty($ocorrencia)) {
                        $ocorrencia = new BoletoOcorrencia();
                        $ocorrencia->boleto_id = $this->id;
                        $ocorrencia->codigo    = $codigo;
                        $ocorrencia->descricao = $this->parseCausa($causa, $this->data_vencimento);
                    }
                    
                    $ocorrencia->data_ocorrencia = date('Y-m-d H:i:s');  
                    if (!$ocorrencia->save()) {
                        throw new \Exception('Houve um erro ao gerar o boleto:<br/>'.Util::renderModelErrors($ocorrencia->errors));
                    }
                }
                $this->situacao_fluxo = self::SITUACAO_FLUXO_BOLETO_NAO_ENVIADO_API;
            } else {
                // verifica se haviam ocorrencias para este boleto
                if ($this->ocorrencias) {
                    // varre todas as ocorrencias nao resolvidas e altera para resolvida
                    foreach ($this->ocorrencias as $ocorrencia) {
                        $ocorrencia->situacao = BoletoOcorrencia::SITUACAO_RESOLVIDO;
                        if (!$ocorrencia->save()) {
                            throw new \Exception(Util::renderModelErrors($ocorrencia->errors));
                        }
                    }
                }
                
                // recupera informações úteis retornado pelo cabeçalho da API
                $this->nosso_numero   = $responseApi['nosso_numero'];
                $this->token          = $responseApi['boleto_cloud_token'];
                $this->situacao_fluxo = self::SITUACAO_FLUXO_BOLETO_GERADO_API;
            }   
    
            if (!$this->save()) {
                throw new \Exception("Houve um erro ao alterar a situacao do boleto, anote o seguinte token: {$this->token}, erros: <br/>".Util::renderModelErrors($ocorrencia->getErrors()));
            }
        }
    }
    
    /**
     * Valida a estrutura de pastas
     */
    public static function validStructure()
    {
        // define o caminho para as pastas relacionadas com boletos
        $paths = [
            \Yii::$app->params['boleto']['base'],
            \Yii::$app->params['boleto']['pdf'],
            \Yii::$app->params['boleto']['remessa'],
            \Yii::$app->params['boleto']['retorno']
        ];
        
        // verifica se o diretorio existe e se possui permissao
        foreach ($paths as $path) {
            // verifica se o diretorio existe
            if (!file_exists($path)) {
                mkdir($path);
                chmod($path, 0775);
            }
            // verifica a permissao
            if (!is_writable($path) || !is_readable($path)) {
                chmod($path, 0775);
            }
        }
    }
    
    // trata a string de causa (descricao) da ocorrencia do boleto
    // retornado pela api
    private function parseCausa($causa = '', $date = null) 
    {
        $causa = substr($causa, 0, 254);
        $causa = str_replace('(boleto.', '(', $causa);
        $causa = str_replace('(pagador.', '(', $causa);
        $causa = str_replace('(endereco.', '(', $causa);
        $causa = str_replace('Campo (', 'Campo "', $causa);
        $causa = str_replace(') -', '" -', $causa);
        $causa = str_replace($date, Util::formatDateToDisplay($date, Util::DATE_DEFAULT), $causa);
        return $causa;
    }
}
