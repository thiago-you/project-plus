<?php
namespace app\api;

use app\base\Util;

class ViaCep extends Api
{
    private $cep;
    
    public function __construct($pReturnType){
        $this->url = "https://viacep.com.br/ws/";
        $this->returnType = $pReturnType;
    }
    
    /**
     * 
     * 
     * @param string $pCep
     * @throws \Exception
     * @return boolean|\SimpleXMLElement|mixed
     */
    public function getRetorno($pCep){
        if(empty($pCep) || !intval($pCep)){
            return false;
        }
        
        $this->cep = Util::removeMascara($pCep);
        
        switch($this->returnType){
            case self::RETURN_XML:
                return new \SimpleXMLElement(file_get_contents($this->prepareUrl()));
            case self::RETURN_JSON:
                return json_decode(file_get_contents($this->prepareUrl()));
            default:
                throw new \Exception('Tipo de retorno não suportado: '.$this->returnType);
        }
    }
    
    private function prepareUrl() {
        return $this->url.$this->cep.'/'.$this->returnType;
    }    
}