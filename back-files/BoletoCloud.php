<?php
namespace app\api;

/**
 * Classe de comunicação com web service boleto cloud
 * @author Alex
 */
class BoletoCloud extends Api
{
    // constantes do retorno
    CONST HTTP_CREATED                = 201;
    CONST HTTP_BAD_REQUEST            = 400;
    CONST HTTP_SUCCESS                = 200;
    CONST HTTP_SUCCESS_BUT_NOT_RETURN = 204;
    CONST HTTP_INTERNAL_SERVER_ERROR  = 500;
    
    // token de acesso que compôe o header do pacote enviado a API
    private $tokenApi;
    // cabeçalho dos pacotes enviados e recebidos da api
    private $headers = ['Accept: application/pdf, application/json', 'Content-Type: application/x-www-form-urlencoded; charset=utf-8'];
    // cURL
    private $cURL;
    // resposta da execução do cURL
    private $response;
        
    /**
     * Contrutor padrão
     * @param $pReturnType
     */
    public function __construct($ambiente) 
    {
        if ($ambiente == self::AMBIENTE_PRODUCAO) {
            $this->tokenApi = 'api-key_WJxqar2KsAXQ7fyX7D_8mWD75j8C-l9olYVbqfjJra8=';//PRODUÇÃO
            $this->url = "https://app.boletocloud.com/api/v1/";//AMBIENTE DE PRODUÇÃO
        } else {
            $this->tokenApi = 'api-key_xEGe3cgXWnPStkBnwTbU7f3zQs3pGUbdxLqYswmUpvk=';//HOMOLOGAÇÃO
            $this->url = "https://sandbox.boletocloud.com/api/v1/";//AMBIENTE DE HOMOLOGACAO            
        }        
        
        // inicializa o cURL
        $this->cURL = curl_init();
    }
    
    /**
     * Executa e fecha o cURl após configurado
     */
    private function executeCURL()
    {        
        $response = curl_exec($this->cURL);        
        $this->response['http_code']   = curl_getinfo($this->cURL, CURLINFO_HTTP_CODE);
        $this->response['header_size'] = curl_getinfo($this->cURL, CURLINFO_HEADER_SIZE);
        curl_close($this->cURL);
        
        return $response;
    }
    
    /**
     * Gera o boleto e devolve um array da resposta
     * @param array $pBoleto
     */
    public function generatedBoleto(array $fields)
    {
        //Varre o array para formar um string em formato de formulário para encapsulamento no pacote
        $fields_string = '';
        foreach ($fields as $key => $value) {
        	if (is_array($value)){
        		foreach ($value as $v) {
        			$fields_string .= urlencode($key).'='.urlencode($v).'&';
        		}
        	} else {
        		$fields_string .= urlencode($key).'='.urlencode($value).'&';	
        	}
        }
        
        // remove espações e caractere &
        $data = rtrim($fields_string, '&');
        
        // configura o cURL
        curl_setopt($this->cURL, CURLOPT_URL, $this->url.'boletos');
        curl_setopt($this->cURL, CURLOPT_HTTPHEADER, $this->headers);
        curl_setopt($this->cURL, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->cURL, CURLOPT_POST, true); 
        curl_setopt($this->cURL, CURLOPT_POSTFIELDS, $data);
        curl_setopt($this->cURL, CURLOPT_USERPWD, $this->tokenApi); #API TOKEN
        curl_setopt($this->cURL, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);# Basic Authorization
        curl_setopt($this->cURL, CURLOPT_HEADER, true);#Define que os headers estarão na resposta
        curl_setopt($this->cURL, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($this->cURL, CURLOPT_SSL_VERIFYHOST, false); #Para uso com https
        curl_setopt($this->cURL, CURLOPT_SSL_VERIFYPEER, false); #Para uso com https
        
        // executando o cURL veja a função
        $response = $this->executeCURL(); 
        // processando a resposta       
        // separando header e body na resposta
        $this->response['header'] = substr($response, 0, $this->response['header_size']);
        $this->response['body'] = substr($response, $this->response['header_size']);
        $this->response['header_array'] = explode("\r\n", $this->response['header']);
        
        // retira os principais header da resposta
        foreach($this->response['header_array'] as $h) {
            if (empty($h)) {
                continue;
            }
            if (preg_match('/X-BoletoCloud-Version:/i', $h)) { 
                $this->response['boleto_cloud_version'] = str_replace('X-BoletoCloud-Version: ', '', $h);
            }
            if (preg_match('/Location:/i', $h)) { 
                $this->response['location'] = $h;
            }
            if (preg_match('/X-BoletoCloud-NIB-Nosso-Numero:/i', $h)) {
                $this->response['nosso_numero'] = str_replace('X-BoletoCloud-NIB-Nosso-Numero: ', '', $h);
            }
            if (preg_match('/X-BoletoCloud-Token:/i', $h)) {
                $this->response['boleto_cloud_token'] = str_replace('X-BoletoCloud-Token: ', '', $h);
            }
        }
        
        // verifica se o boleto foi criado
        if ($this->response['http_code'] != self::HTTP_CREATED) {
            $erros = json_decode($this->response['body'], true);
            $causas = $erros['erro']['causas'];
            foreach ($causas as $causa) {
                $this->response['causas'][$causa['codigo']] = $causa['mensagem'];
            }
        }       
       
        return $this->response;
    }
    
    /**
     * Recupera um boleto da API, passe o token do boleto
     * @param string $pToken
     */
    public function viewBoleto($pToken)
    {
        // configura o CURL
        curl_setopt($this->cURL, CURLOPT_URL, $this->url.'boletos/'.$pToken);#Define a url
        curl_setopt($this->cURL, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);#Define o tipo de autenticação HTTP Basic 
        curl_setopt($this->cURL, CURLOPT_USERPWD, $this->tokenApi);#Define o API Token para realizar o acesso ao serviço
        curl_setopt($this->cURL, CURLOPT_RETURNTRANSFER, true);#True para enviar o conteúdo do arquivo direto para o navegador
        curl_setopt($this->cURL, CURLOPT_HEADER, true);#Define que os headers estarão na resposta
        curl_setopt($this->cURL, CURLOPT_SSL_VERIFYHOST, false);#verifica ssl para requisicao HTTPS
        curl_setopt($this->cURL, CURLOPT_SSL_VERIFYPEER, false);#verifica ssl para requisicao HTTPS
        
        // executa o curl veja função
        $response = $this->executeCURL();
        
        // separando header e body na resposta
        $this->response['header'] = substr($response, 0, $this->response['header_size']);
        $this->response['body'] = substr($response, $this->response['header_size']);
        $this->response['header_array'] = explode("\r\n", $this->response['header']);
        
        return $this->response;
    }
    
    /**
     * Busca na API o arquivo de remessa gerado
     * @param string $pTokenConta
     */
    public function generatedRemessaFile($pTokenConta)
    {
        $fields = ['remessa.conta.token' => $pTokenConta];
        $fields_string = '';     
        
        foreach ($fields as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $v) {
                    $fields_string .= urlencode($key).'='.urlencode($v).'&';
                }
            } else {
                $fields_string .= urlencode($key).'='.urlencode($value).'&';
            }
        }
        
        // prepara o token para encapsular no pacote
        $data = rtrim($fields_string, '&');
        
        // configura o CURL
        curl_setopt($this->cURL, CURLOPT_URL, $this->url.'arquivos/cnab/remessas');
        curl_setopt($this->cURL, CURLOPT_HTTPHEADER, $this->headers);
        curl_setopt($this->cURL, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->cURL, CURLOPT_POST, true);
        curl_setopt($this->cURL, CURLOPT_POSTFIELDS, $data);
        curl_setopt($this->cURL, CURLOPT_USERPWD, $this->tokenApi); // TOKEN do usuário
        curl_setopt($this->cURL, CURLOPT_HTTPAUTH, CURLAUTH_BASIC); // Basic Authorization
        curl_setopt($this->cURL, CURLOPT_HEADER, true); // Define que os headers estarão na resposta
        curl_setopt($this->cURL, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($this->cURL, CURLOPT_SSL_VERIFYHOST, false); // Para uso com https
        curl_setopt($this->cURL, CURLOPT_SSL_VERIFYPEER, false); // Para uso com https
        
        // executa o cURL
        $response = $this->executeCURL();
        
        // separando header e body na resposta
        $this->response['header'] = substr($response, 0, $this->response['header_size']);
        $this->response['body'] = substr($response, $this->response['header_size']);
        $this->response['header_array'] = explode("\r\n", $this->response['header']);
        
        // varre os cabeçalhos
        foreach ($this->response['header_array'] as $header) {
            if (empty($header)) {
                continue;
            }
            if (preg_match('/X-BoletoCloud-Version:/i', $header)) { 
                $this->response['boleto_cloud_version'] = str_replace('X-BoletoCloud-Version: ', '', $header);
            }
            if (preg_match('/X-BoletoCloud-Token:/i', $header)) {
                $this->response['boleto_cloud_token'] = str_replace('X-BoletoCloud-Token: ', '', $header);
            }
            if (preg_match('/Location:/i', $header)) { 
                $this->response['location'] = $header;
            }
            if (preg_match('/Content-Disposition: .*filename=([^ ]+)/i', $header)) {
                $this->response['file_name'] = preg_replace('/Content-Disposition:.*filename=/i', '', $header);
            }
        }
        
        if ($this->response['http_code'] != self::HTTP_CREATED) {
            $erros = json_decode($this->response['body'], true);
            $causas = $erros['erro']['causas'];
            if (is_array($causas)) {    
                foreach ($causas as $causa) {
                    $this->response['causas'][$causa['codigo']] = $causa['mensagem'];
                }
            }
        }
        
        return $this->response;
    }
    
    /**
     * Realiza o upload do arquivo de Retorno
     * @param string $path diretorio para local onde esta arquivo de retorno
     */
    public function uploadRetornoFile($path)
    {
        $file = new \CURLFile($path);
        $data = ['arquivo' => $file];
        
        curl_setopt($this->cURL, CURLOPT_URL, $this->url.'arquivos/cnab/retornos');
        curl_setopt($this->cURL, CURLOPT_HTTPHEADER, ["Accept: application/json", "Content-Type: multipart/form-data"]);
        curl_setopt($this->cURL, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($this->cURL, CURLOPT_SAFE_UPLOAD, false);
        curl_setopt($this->cURL, CURLOPT_POST,1);
        curl_setopt($this->cURL, CURLOPT_POSTFIELDS, $data);
        curl_setopt($this->cURL, CURLOPT_USERPWD, $this->tokenApi); #API TOKEN
        curl_setopt($this->cURL, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);# Basic Authorization
        curl_setopt($this->cURL, CURLOPT_HEADER, true);#Define que os headers estarão na resposta
        curl_setopt($this->cURL, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($this->cURL, CURLOPT_SSL_VERIFYHOST, false); #Para uso com https
        curl_setopt($this->cURL, CURLOPT_SSL_VERIFYPEER, false); #Para uso com https
        
        // executa o cURL
        $response = $this->executeCURL();
        
        // Separando header e body na resposta
        $this->response['header'] = substr($response, 0, $this->response['header_size']);
        $this->response['body'] = substr($response, $this->response['header_size']);
        $this->response['header_array'] = explode("\r\n", $this->response['header']);
        
        // varre os cabeçalhos
        foreach ($this->response['header_array'] as $header) {
            if (preg_match('/X-BoletoCloud-Version:/i', $header)) { 
                $this->response['boleto_cloud_version'] = str_replace('X-BoletoCloud-Version: ', '', $header);
            }
            if (preg_match('/X-BoletoCloud-Token:/i', $header)) { 
                $this->response['boleto_cloud_token'] = str_replace('X-BoletoCloud-Token: ', '', $header);
            }
            if (preg_match('/Location:/i', $header)) { 
                $this->response['location'] = $header;
            }
        }
        
        return $this->response;
    }
}