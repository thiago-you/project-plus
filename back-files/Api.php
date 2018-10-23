<?php

namespace app\api;

abstract class Api
{
    protected $url;
    protected $method;
    protected $returnType;
    protected static $apis = [self::API_VIA_CEP, self::API_VIA_CEP, self::API_BOLETO_CLOUD];
    protected static $returns_bearer = [self::RETURN_XML, self::RETURN_JSON, self::RETURN_ARRAY, self::RETURN_DEFAULT];
    
    //Lista de API disponiveis, adicione uma constante com nome da classe da api
    CONST API_VIA_CEP = "ViaCep";
    CONST API_DISTANCE_MATRIX = "Distance";
    CONST API_BOLETO_CLOUD = "BoletoCloud";
    //Extensoes de retorno possiveis
    CONST RETURN_XML = "xml";
    CONST RETURN_JSON = "json";
    CONST RETURN_ARRAY = "array";
    CONST RETURN_DEFAULT = "default";
    //Ambiente da API
    CONST AMBIENTE_PRODUCAO = true;
    CONST AMBIENTE_HOMOLOGACAO = false;
}
