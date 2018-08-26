<?php
namespace app\base;

use Yii;
use yii\helpers\Json;
use yii\data\ArrayDataProvider;

/**
 * Classe que contém métodos úteis para formatação, renderização de valores, etc.
 *
 * @author Thiago You <thya9o@outlook.com>
 */
class Util
{
	// BTN default para forms
	CONST BTN_CREATE = '<i class="fa fa-save"></i>  Salvar';
	CONST BTN_UPDATE = '<i class="fa fa-save"></i>  Salvar Alteração';
	CONST BTN_CANCEL = '<i class="fa fa-close"></i>  Cancelar';
	CONST BTN_RETURN = '<i class="fa fa-reply"></i>  Voltar';	
	CONST BTN_CREATE_FORM  = 'btn btn-success btn-flat';
	CONST BTN_UPDATE_FORM  = 'btn btn-warning btn-flat';
	CONST BTN_CANCEL_FORM  = 'btn btn-default btn-flat';
	
	// BTN COLORS
	CONST BTN_COLOR_PRIMARY = 'btn btn-primary btn-flat';
	CONST BTN_COLOR_SUCCESS = 'btn btn-success btn-flat';
	CONST BTN_COLOR_DANGER  = 'btn btn-danger btn-flat';
	CONST BTN_COLOR_WARNING = 'btn btn-warning btn-flat';
	CONST BTN_COLOR_DEFAULT = 'btn btn-default btn-flat';
	CONST BTN_COLOR_INFO    = 'btn btn-info btn-flat';
		
	CONST CLASS_CREATE = 'success';
	CONST CLASS_UPDATE = 'warning';

	// constantes de mascaras
    CONST MASK_CNPJ     = 'CNPJ';
    CONST MASK_CPF      = 'CPF';
    CONST MASK_TELEFONE = 'FONE';
    CONST MASK_CEP      = 'CEP';
    CONST MASK_MONEY    = 'MONEY';
    CONST MASK_PERCENT  = 'PERCENT';
    CONST MASK_NUMBER   = 'NUMBER';

    // constantes para formatar e setar datas
    CONST DATE_DEFAULT    = 'DEFAULT';    // save as (Y-m-d), display as (d/m/Y)
    CONST DATE_MONTH      = 'MONTH';      // save as (m), display as (M)
    CONST DATE_MONTH_YEAR = 'MONTH_YEAR'; // save as (Y-m), display as (M/Y)
    CONST DATE_YEAR       = 'YEAR';       // save as (Y), display as (Y)
    CONST DATE_INI        = 'DATE_INI';   // save as (Y-m-d 00:00:00), display as (d/m/Y 00:00:00)
    CONST DATE_END        = 'DATE_END';   // save as (Y-m-d 23:59:59), display as (d/m/Y 23:59:59)
    CONST DATE_TIMESTAMP  = 'TIMESTAMP';  // return timeStamp (mkdir)
    CONST DATE_TIME       = 'TIME';       // return only time (H:i:s)
   
    /**
     * Retorna uma lista html com os erros retornados na validação da model
     *
     * @param array $errors
     * @return string (html)
     */
    public static function renderModelErrors(array $errors)
    {
        if(count($errors) == 0)
            return null;
            
            $errorStr = '<ul>';
            foreach($errors as $field => $errorList) {
                if(count($errorList)) {
                    foreach ($errorList as $error) {
                        $errorStr .= "<li>{$error}</li>";
                    }
                }
            }
            $errorStr .= '</ul>';
            
            return $errorStr;
    }
    
    /**
     *
     * Format => [CPF, CNPJ, CEP, TELEFONE, MONEY VALUE, DATE]
     * Params => [
     *     $val => value,
     *     $mask => mask to apply
     *     $removeMask => option to remove value mask before apply new one
     *     $option => aditional param
     * ]
     *
     */
    public static function maskBackend($val, $mask, $removeMask = false, $option = null)
    {
        // force val as string
        if(!is_string($val)) {
            $val = strval($val);
        }
       
        // remove a mascara inicial para inserir outra
        if($removeMask) {
            $val = self::removeMascara($val);
        }
        
        // remove a mascara inicial para inserir outra
        if($removeMask) {
            $val = self::removeMascara($val);
        }
        
        // define mask
        switch($mask) {
            case self::MASK_CNPJ:
                $mask = '##.###.###/####-##';
                break;
            case self::MASK_CPF:
                $mask = '###.###.###-##';
                break;
            case self::MASK_CEP:
                $mask = '#####-###';
                break;
            case self::MASK_TELEFONE:
                if (strlen($val) == 11)
                    $mask = '(##) #####-####';
                    else
                        $mask = '(##) ####-####';
                        break;
            case self::MASK_MONEY:
                // $option => define decimal length
                if(!$val) {
                    $val = 0.00;
                }
                if($option == 'decimalLength') {
                    $val = floatval($val);
                    $option = count(explode('.', $val)) == 1 ? 2 : strlen(explode('.', $val)[1]);
                }
                if(empty($option)) {
                    $option = 2;
                }
                return 'R$ ' . number_format($val, $option, ',', '.');
                break;
            case self::MASK_NUMBER:
                // $option => define decimal length
                if(!$val) {
                    $val = 0.00;
                }
                if($option == 'decimalLength') {
                    $val = floatval($val);
                    $option = count(explode('.', $val)) == 1 ? 2 : strlen(explode('.', $val)[1]);
                }
                if(empty($option)) {
                    $option = 2;
                }
                return number_format($val, $option, ',', '.');
                break;
            case self::MASK_PERCENT:
                if(!$val) {
                    $val = 0.00;
                }
                return number_format($val, 2, ',', '.') . ' %';
                break;
            default:
                throw new \Exception('Tipo de máscara não definida. Utilize os tipos disponíveis na classe.');
                break;
        }
        
        // format value with defined mask
        $maskared = '';
        if(strlen($val)) {
            $k = 0;
            for($i = 0; $i < strlen($mask); $i++) {
                if($mask[$i] == '#') {
                    if(isset($val[$k]))
                        $maskared .= $val[$k++];
                }else {
                    if(isset($mask[$i]))
                        $maskared .= $mask[$i];
                }
            }
        }
        
        return $maskared;
    }
    
    /**
     * Format => Long name to Short custom name
     * Paramas => [$name => String, $size => int]
     */
    public static function shortName($name, $size)
    {
        $shortName = $name;
        if($size > 0 && strlen($name) > $size) {
            $shortName  = substr($name, 0, ($size - 4)) . ' ...';
        }
        
        return $shortName;
    }
    
    /**
     * Paramas => Unknow
     */
    public static function gerarListaControllers($deny = [], $termo = null, $cache = null, $combo = false, $compara = false)
    {
        $loop = [];
        $_array = Json::decode(Yii::$app->listcontrollers->getList($deny, $termo, $cache));
        
        if(!empty($_array)) {
            if(is_array($_array)) {
                $nome = [];
                $id = [];
                $descricao = [];
                
                foreach($_array['list'] as $k => $_arrays) {
                    if(!empty($_arrays)) {
                        if(is_array($_arrays)) {
                            foreach($_arrays as $key => $value) {
                                if(($key % 3) == 0) {
                                    $nome[trim($k)][] = trim($value);
                                }else if ((($key - 1) % 3) == 0) {
                                    $id[trim($k)][] = trim($value);
                                }else if ((($key - 2) % 3) == 0) {
                                    $descricao[trim($k)][] = trim($value);
                                }
                            }
                        }
                    }
                }
            }
        }
        
        if($combo) {
            return ['nome' => $nome, 'id' => $id, 'descr' => $descricao];
        }
        
        if(!empty($nome) && !empty($id) && !empty($descricao)) {
            foreach($nome as $k_nome => $nomes) {
                foreach($id as $k_id => $ids) {
                    foreach($descricao as $k_descr => $descricoes) {
                        
                        if($k_nome == $k_id && $k_nome == $k_descr) {
                            if(count($ids) == count($nomes)) {
                                $_i = 0;
                                
                                foreach($ids as $_muda) {
                                    $_nome = $k_nome . ' - ' . $nomes[$_i];
                                    $chave = base64_encode(Json::encode([
                                        'm' => $k_nome,
                                        'c' => $_muda,
                                    ]));
                                    
                                    if($compara == true) {
                                        $loop[$_muda][$chave] = $_nome;
                                    }else {
                                        $loop[$chave] = $_nome;
                                    }
                                    
                                    $_i++;
                                }
                            }
                        }
                    }
                }
            }
        }
        
        return $loop;
    }
    
    /**
     * Escreve o numero por extenso
     *
     * @param string $value (value)
     * @param string $moneyFormat (boolean)
     * @return string
     */
    public static function numberExt($value = null, $moneyFormat = false) {
        $_value;
        if(!$value) {
            $value = 0;
        }
        // instancia a class para formatar
        $_formater = new \NumberFormatter('pt_br', \NumberFormatter::SPELLOUT);
        // formata o valor conforme o param opcional 
        if(!$moneyFormat) {
            $_value = $_formater->format($value);
        }else {
            list($_number, $_decimal) = explode('.', $value);
            $_value = $_formater->format(intval($_number)) . ' reais';
            if(intval($_decimal)) {
                $_value .= ' e ' . $_formater->format(intval($_decimal)) . ' centavos';
            }
        }
        return ucfirst(Util::tirarAcentos($_value));
    }
    
    /**
     * Remove os acentos de uma string
     * @param string $string
     * @return string (sem acentos)
     */
    public static function tirarAcentos($string) {
        return preg_replace(array("/(á|à|ã|â|ä)/","/(Á|À|Ã|Â|Ä)/","/(é|è|ê|ë)/","/(É|È|Ê|Ë)/","/(í|ì|î|ï)/","/(Í|Ì|Î|Ï)/","/(ó|ò|õ|ô|ö)/","/(Ó|Ò|Õ|Ô|Ö)/","/(ú|ù|û|ü)/","/(Ú|Ù|Û|Ü)/","/(ñ)/","/(Ñ)/"),explode(" ","a A e E i I o O u U n N"),$string);
    }
    
    /**
     * Remove value mask
     * Remove: array => ['.', '-', '/', '(', ')', 'R', '$', '%', '_'];
     *
     * @param string $campo (value)
     * @param boolean $removerEspaco (Flag que determina remover ou não os espaços em branco. Default false.)
     * @param array $extra (Caractres extras a serem removidos)
     * @return string
     */
    public static function removeMascara($campo, $removerEspaco = false, $extra = [])
    {
        // set chars to remove
        $remover = array('.', '-', '/', '(', ')', 'R', '$', '%', '_');
        $remover = array_merge($remover, $extra);
        
        // check for remove empty space
        if($removerEspaco) {
            $remover = array_merge($remover, [' ']);
        }
        
        // fomat and return  value
        return str_replace(',', '.', (str_replace($remover, '', $campo)));
    }
    
    /**
     * Formata a data para o padrão BR
     *
     * @param $data (any)
     * @param $format (date format: pre-defined const's)
     * @return $data (formated date)
     */
    public static function formatDateToDisplay($data, $format = null, $completeFormat = null)
    {
        // seta a data atual se nada for enviado (empty)
        if(empty($data)) {
            $data = date('Y-m-d H:i:s');
        }
        
        // seta o formato e as config da data
        switch($format) {
            case self::DATE_DEFAULT:
                $format = 'd/m/Y';
                break;
            case self::DATE_MONTH:
                $format = 'M';
                $completeFormat = false;
                break;
            case self::DATE_MONTH_YEAR:
                $format = 'M, Y';
                setlocale(LC_ALL, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
                return (empty($data) ? $data : ucfirst(strftime('%B de %Y', strtotime(str_replace('/', '-', $data)))));
                break;
            case self::DATE_YEAR:
                $format = 'Y';
                $completeFormat = false;
                break;
            case self::DATE_TIMESTAMP:
                $part = explode('/', $data);
                return mktime(0, 0, 0, $part[1], $part[0], $part[2]);
                break;
            case self::DATE_TIME:
                $format = 'H:i';
                $completeFormat = false;
                break;
            case self::DATE_INI:
                $format = 'd/m/Y';
                $completeFormat = true;
                $data .= ' 00:00:00';
                break;
            case self::DATE_END:
                $format = 'd/m/Y';
                $completeFormat = true;
                $data .= ' 23:59:59';
                break;
            default:
                if(empty($format)) {                    
                    $format = 'd/m/Y';
                }
                break;
        }
        
        // verifica se deseja exibir a hora
        if($completeFormat) {
            $format .= ' H:i:s';
        }
        
        // retorna a data formatada
        return (empty($data) ? $data : date($format,strtotime(str_replace('/', '-', $data))));
    }
    
    /**
     * Formata a data para o padrão do db
     *
     * @param $data (any)
     * @param $format (date format: pre-defined const's)
     * @return $data (formated date)
     */
    public static function formatDateToSave($data, $format = null, $completeFormat = null)
    {
        // seta a data atual se nada for enviado (empty)
        if(empty($data)) {
            $data = date('Y-m-d H:i:s');
        }
        
        // seta o formato e as config da data
        switch($format) {
            case self::DATE_DEFAULT:
                $format = 'Y-m-d';
                break;
            case self::DATE_MONTH:
                $format = 'm';
                $completeFormat = false;
                break;
            case self::DATE_MONTH_YEAR:
                $format = 'Y-m';
                $completeFormat = false;
                break;
            case self::DATE_YEAR:
                $format = 'Y';
                $completeFormat = false;
                break;
            case self::DATE_TIMESTAMP:
                $part = explode('/', $data);
                return mktime(0, 0, 0, $part[1], $part[0], $part[2]);
                break;
            case self::DATE_TIME:
                $format = 'H:i';
                $completeFormat = false;
                break;
            case self::DATE_INI:
                $format = 'Y-m-d';
                $completeFormat = true;
                $data .= ' 00:00:00';
                break;
            case self::DATE_END:
                $format = 'Y-m-d';
                $completeFormat = true;
                $data .= ' 23:59:59';
                break;
            default:
                if(empty($format)) {
                    $format = 'Y-m-d';
                }
                break;
        }
        
        // verifica se deseja exibir a hora
        if($completeFormat) {
            $format .= ' H:i:s';
        }
        
        // retorna a data formatada
        return (date($format, strtotime(str_replace('/', '-', $data))));
    }
    
    /**
     * Retorna a versão atual do sistema
     * Busca a versão na tag do branch
     *
     * @return string
     */
    public static function getVersion()
    {
        return '1.0';
        return Yii::$app->params['version'];
    }
}
// ----------------------------------------------------------------------------------------------------------
// end file
// ----------------------------------------------------------------------------------------------------------
