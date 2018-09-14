<?php
namespace app\base;

/**
 * Help class
 *
 * @author Thiago You <thya9o@outlook.com>
 */
class Helper
{
	// BTN default para forms
    CONST BTN_CREATE = '<i class="fa fa-save"></i>&nbsp; <span class="hidden-xs">Salvar</span>';
    CONST BTN_UPDATE = '<i class="fa fa-save"></i>&nbsp; <span class="hidden-xs">Alterar</span>';
    CONST BTN_CANCEL = '<i class="fa fa-times"></i>&nbsp; <span class="hidden-xs">Cancelar</span>';
    CONST BTN_RETURN = '<i class="fa fa-reply"></i>&nbsp; <span class="hidden-xs">Voltar</span>';	
	CONST BTN_CREATE_FORM  = 'btn btn-success btn-flat';
	CONST BTN_UPDATE_FORM  = 'btn btn-warning btn-flat';
	CONST BTN_CANCEL_FORM  = 'btn btn-default btn-flat';
	
	// BTN COLORS
	CONST BTN_COLOR_PRIMARY = 'btn btn-primary btn-flat btn-sm';
	CONST BTN_COLOR_SUCCESS = 'btn btn-success btn-flat btn-sm';
	CONST BTN_COLOR_DANGER  = 'btn btn-danger btn-flat btn-sm';
	CONST BTN_COLOR_WARNING = 'btn btn-warning btn-flat btn-sm';
	CONST BTN_COLOR_DEFAULT = 'btn btn-default btn-flat btn-sm';
	CONST BTN_COLOR_INFO    = 'btn btn-info btn-flat btn-sm';
	CONST BTN_COLOR_EMERALD = 'btn btn-emerald btn-flat btn-sm';
	CONST BTN_COLOR_PURPLE  = 'btn btn-purple btn-flat btn-sm';
	CONST BTN_COLOR_PINK    = 'btn btn-pink btn-flat btn-sm';
	CONST BTN_COLOR_ORANGE  = 'btn btn-orange btn-flat btn-sm';
		
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
    CONST DATE_EXCEL      = 'EXCEL';      // change day by month
   
    /**
     * Retorna uma lista html com os erros retornados na validação da model
     *
     * @param array $errors
     * @return string (html)
     */
    public static function renderErrors(array $errors)
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
    public static function mask($val, $mask, array $option = [])
    {
        // force val as string
        if (!is_string($val)) {
            $val = strval($val);
        }
        
        // remove a mascara inicial para inserir outra
        if (isset($option['removeMask'])) {
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
                if (strlen($val) == 11) {
                    $mask = '(##) #####-####';
                }else {
                    $mask = '(##) ####-####';
                }
                break;
            case self::MASK_MONEY:
                // $length => define default decimal length (casas decimais)
                $length = 2;
                
                // prefixo do retorno
                $prefix = 'R$ ';
                
                // valida o valor
                if (!$val) {
                    $val = 0.00;
                }
                
                // seta as options
                if (isset($option)) {
                    // $option['decimalLength'] => mantem o numero de casas decimais do valor passado
                    if (isset($option['decimalLength'])) {
                        $val = floatval($val);
                        $length = count(explode('.', $val)) == 1 ? 2 : strlen(explode('.', $val)[1]);
                    }
                    // $option['precision'] => seta o numero de casas decimais
                    if (isset($option['precision'])) {
                        $length = intval($option['precision']);
                    }
                    // defines min precision
                    if (isset($option['minPrecision'])) {
                        $length = $length < intval($option['minPrecision']) ? intval($option['minPrecision']) : $length;
                    }
                    // define o prefixo do retorno
                    if (isset($option['prefix'])) {
                        $prefix = $option['prefix'];
                    }
                }
                
                // força o 0 quando o número for zero
                if (intval(abs($val)) == 0) {
                    $val = 0;
                }
                
                // formata a retorna o numero
                return $prefix.number_format($val, $length, ',', '.');
                break;
            case self::MASK_NUMBER:
                // $length => define default decimal length (casas decimais)
                $length = 2;
                
                // valida o valor
                if (!$val) {
                    $val = 0.00;
                }
                
                // se nao for um numero, remove a mascara
                if (!is_numeric($val)) {
                    $val = Helper::removeMascara($val, true);
                }
                
                // $option['decimalLength'] => mantem o numero de casas decimais do valor passado
                if (isset($option['decimalLength'])) {
                    $val = floatval($val);
                    $length = strlen(explode('.', $val)[1]);
                }
                // $option['precision'] => seta o numero de casas decimais
                if(isset($option['precision'])) {
                    $length = intval($option['precision']);
                }
                // defines min precision
                if (isset($option['minPrecision'])) {
                    $length = $length < intval($option['minPrecision']) ? intval($option['minPrecision']) : $length;
                }
                
                // verifica se deseja fazer um round do numero
                // por default apenas pega as casas decimais sem realizar round
                if (isset($option['round'])) {
                    $val = round($val, $length);
                } else {
                    // seta o decimal length do var
                    $val = preg_replace('/\.(\d{'.$length.'}).*/', '.$1', $val);
                    // valida o min length do var
                    if (strlen(explode('.', $val)[1]) < $length) {
                        $val = explode('.', $val);
                        $val = $val[0].'.'.str_pad($val[1], $length, '0');
                    }
                }
                return $val;
                break;
            case self::MASK_PERCENT:
                if (!$val) {
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
        if (strlen($val)) {
            $k = 0;
            for ($i = 0; $i < strlen($mask); $i++) {
                if ($mask[$i] == '#') {
                    if (isset($val[$k]))
                        $maskared .= $val[$k++];
                } else {
                    if (isset($mask[$i]))
                        $maskared .= $mask[$i];
                }
            }
        }
        
        return $maskared;
    }
    
    /**
     * Formata um texto longo para um nome curto customizado
     * 
     * @param String  $name => texto
     * @param integer $size => tamamho do novo texto
     */
    public static function shortName($name = '', $size, $etc = true)
    {
        $shortName = $name;
        if ($size > 0 && strlen($shortName) > $size) {
            if (strlen($shortName) > 4 && $size > 4) {
                $shortName = mb_substr($name, 0, ($size - 4), 'utf-8');
            } else if(strlen($shortName) > 1) {
                $shortName = mb_substr($name, 0, $size, 'utf-8');
            }
            
            if ($etc && $size > 5) {
                $shortName .= ' ...';
            }
        }
        
        return $shortName;
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
    public static function unmask($campo, $removerEspaco = false, $extra = [])
    {
        // set chars to remove
        $remover = array('.', '-', '/', '(', ')', 'R', '$', '%', '_');
        $remover = array_merge($remover, $extra);
        
        // check for remove empty space
        if ($removerEspaco) {
            $remover = array_merge($remover, [' ']);
            // remove chars que não sejam utf-8
            $campo = preg_replace('/[\x00-\x1F\x80-\xFF]/', ' ', $campo);
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
        if (empty($data)) {
            $data = date('Y-m-d H:i:s');
        }
        
        // seta o formato e as config da data
        switch ($format) {
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
        if (empty($data)) {
            $data = date('Y-m-d H:i:s');
        }
        
        // se for uma data do excel (m/d/Y)
        // reorganiza a data e seta o padrão default
        if ($format == self::DATE_EXCEL) {
            $data = explode('/', $data);
            $data = "{$data[1]}/{$data[0]}/{$data[2]}";
            $format = self::DATE_DEFAULT;
        }
        
        // seta o formato e as config da data
        switch ($format) {
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
     * Algoritmo de validação do CNPJ
     *
     * @param string $cnpj => CNPJ
     * @return boolean
     */
    public static function validarCNPJ($cnpj = '')
    {
        // deixa o CNPJ com apenas números
        $cnpj = preg_replace('/[^\d]+/', '', $cnpj);
        // garante que o CNPJ é uma string
        $cnpj = (string) $cnpj;
        
        if (empty($cnpj) || strlen($cnpj) != 14) {
            return false;
        }
        
        // elimina CNPJs inválidos conhecidos
        if ($cnpj == "00000000000000" ||
            $cnpj == "11111111111111" ||
            $cnpj == "11111111111111" ||
            $cnpj == "22222222222222" ||
            $cnpj == "33333333333333" ||
            $cnpj == "44444444444444" ||
            $cnpj == "55555555555555" ||
            $cnpj == "66666666666666" ||
            $cnpj == "77777777777777" ||
            $cnpj == "88888888888888" ||
            $cnpj == "99999999999999")
        {
            return false;
        }
        
        // seta os primeiros 12 números do CNPJ
        // para calcular os digitos verificadores
        $stringCNPJ = substr($cnpj, 0, 12);
        
        /**
         * Funcção Anônima Calcular CNPJ
         * @desc seta a funcao para realizar o calculo do CNPJ
         *
         * @param  string $cnpj => os digitos do CNPJ
         * @param  int    $posicoes => a posição que vai iniciar a regressão
         *
         * @return int => calculo do CNPJ
         */
        $calcularCPNJ = function($cnpj, $posicao = 5) use (&$stringCNPJ) {
            // variável para o cálculo
            $calculo = 0;
            
            // laço para percorrer os item do cnpj
            for ($i = 0; $i < strlen($cnpj); $i++) {
                // cálculo mais posição do CNPJ * a posição
                $calculo += ($cnpj[$i] * $posicao--);
                
                // se a posição for menor que 2, ela se torna 9
                if ($posicao < 2) {
                    $posicao = 9;
                }
            }
            
            // se o resto da divisão entre o primeiro cálculo por 11 for menor que 2, o primeiro
            // dígito é zero (0), caso contrário é 11 menos o resto da divisão entre o cálculo por 11
            $calculo = ($calculo % 11) < 2 ? 0 : 11 - ($calculo % 11);
            
            // concatena o CNPJ com o dígito calculado
            $stringCNPJ = "{$stringCNPJ}{$calculo}";
        };
        
        // realiza o calculo do primeiro dígito verificador
        // o primeiro calculo começa na posição 5
        $calcularCNPJ($stringCNPJ, 5);
        // realiza o calculo do segundo dígito verificador
        // o segundo cálculo começa na posição 6
        $calcularCNPJ($stringCNPJ, 6);
        
        // valida se o CNPJ calculado é igual ao CNPJ informado
        if ($cnpj === $stringCNPJ) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Algoritmo de validação do CPF
     *
     * @param string $cpf => CFP
     * @return boolean
     */
    public static function validarCPF($cpf = null)
    {
        // deixa o CPF com apenas números
        $cpf = preg_replace('/[^\d]+/', '', $cpf);
        // garante que o CPF é uma string
        $cpf = (string) $cpf;
        
        if (empty($cpf) || strlen($cpf) != 11) {
            return false;
        }
        
        // elimina CPFs inválidos conhecidos
        if ($cpf == '00000000000' ||
            $cpf == '11111111111' ||
            $cpf == '22222222222' ||
            $cpf == '33333333333' ||
            $cpf == '44444444444' ||
            $cpf == '55555555555' ||
            $cpf == '66666666666' ||
            $cpf == '77777777777' ||
            $cpf == '88888888888' ||
            $cpf == '99999999999'
            ) {
                return false;
            }
            
            // valida o digito verificador do CPF
            for ($t = 9; $t < 11; $t++) {
                for ($d = 0, $c = 0; $c < $t; $c++) {
                    $d += $cpf{$c} * (($t + 1) - $c);
                }
                $d = ((10 * $d) % 11) % 10;
                if ($cpf{$c} != $d) {
                    return false;
                }
            }
            
            return true;
    }
    
    /**
     * Encripta/Decripta uma senha
     */
    public static function passwordCrypt($password, $decrypt = false)
    {
        // chaves e metodos de encriptação
        $key  = 'maklen';
        $iv = '0000000005395484';
        $method = "AES-256-CBC";
        
        // encripta ou decripta a senha
        if (!$decrypt) {
            // encripta a senha enviada
            $encryptedPass = base64_encode(openssl_encrypt($password, $method, $key, null, $iv));
            
            // verifica se a senha enviada ja esta encriptada
            // e verifica se a senha foi alterada
            $decryptedPass = openssl_decrypt(base64_decode($password, true), $method, $key, null, $iv);
            if ($decryptedPass ==  false) {
                $password = $encryptedPass;
            }
        } else {
            $decryptedPass = openssl_decrypt(base64_decode($password, true), $method, $key, null, $iv);
            if ($decryptedPass) {
                $password = $decryptedPass;
            }
        }
        
        return $password;
    }
}
