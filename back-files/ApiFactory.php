<?php

namespace app\api;

abstract class ApiFactory extends Api
{
    /**
     * Retorna a instancia das apis disponiveis
     * @param $pApiName
     * @param $pApiReturnType => por default xml
     * @param bool $pHttps => se quer que o curl seja feito em HTTPS
     * @throws \Exception
     */
    public static function getInstance($pApiName, $pApiReturnType, $pApiAmbiente = null){
        if(!in_array($pApiReturnType, self::$returns_bearer))
            $pApiReturnType = self::RETURN_XML;
                
        switch($pApiName){
            case self::API_VIA_CEP:
                return new ViaCep($pApiReturnType);
                break;
            case self::API_DISTANCE_MATRIX:
                return new DistanceMatrix($pApiReturnType, 'AIzaSyBn32E8NSs_OseoXb2hcu3KWsXtIVjYsn0');
                break;
            case self::API_BOLETO_CLOUD:
                return new BoletoCloud($pApiAmbiente);
                break;
            default:
                throw new \Exception('Api inválida');
        }
    }
}
