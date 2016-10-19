<?php
function d() {
    $debug = debug_backtrace();
    echo <<<HTML
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
    <head>
        <style>
        html,body{
            padding: 0;
            margin: 0;
            width: 100%;
            height: 100%;
            font-family: verdana;
            font-size: 16px !important;
            color: #fff;
            width: 100%;
            background: #070707 url("../../public/images/planet.jpg")  no-repeat;
            /*filter: grayscale(1)*/
            /*text-shadow: 0px 0px 5px #FFF;*/
        }
        h3{
            color: #fff;
            text-shadow: 0px 0px 5px #FFF;
        }
        fieldset{

        }
        h3 {
            margin: 10px;
        }
        </style>
    </head>
    <body>
    <div class="msg-error exception">
    <span>
        <h3>Salic Debug</h3>
        <!--<p>Debug:</p>-->
    </span>
    </div>
        <div class="msg-exception">
            <fieldset>
                <legend>Par&acirc;metros</legend>
HTML;
    for ($i = 0; $i < func_num_args(); $i++) {
        $value = func_get_arg($i);
        var_dump($value);
    }
    echo <<<HTML
            </fieldset>
            <fieldset>
                <legend>Local</legend>
                <p><b>Classe:</b> {$debug[1]['class']}</p>
                <p><b>M&eacute;todo:</b> {$debug[1]['function']}</p>
                <p><b>Arquivo:</b> {$debug[0]['file']} ({$debug[0]['line']})</p>
            </fieldset>
HTML;
    for ($i = 2; $i < count($debug); $i++) {
        if (is_int(strpos($debug[$i]['class'], 'Zend'))) {
            break;
        }
        $intBacktrace = $i - 1;
        echo <<<HTML
            <fieldset>
                <legend>Rastro por onde passou {$intBacktrace}</legend>
                <p><b>Classe:</b> {$debug[$i]['class']}</p>
                <p><b>M&eacute;todo:</b> {$debug[$i]['function']}</p>
                <p><b>Arquivo:</b> {$debug[$i - 1]['file']} ({$debug[$i - 1]['line']})</p>
            </fieldset>
HTML;
    }
    echo <<<HTML
</table>
            </div>
    </body>
</html>
HTML;
    exit;
}

/* FUN��O �TIL PARA DEBUG */
/*
function xd($obj) {
    $debug = debug_backtrace();
    $calledLine = $debug[0]['line'];
    $calledFile = $debug[0]['file'];
    echo "<div style='background-color:#87CEEB; border:1px #666666 solid; text-align:left;'>"; #DFDFDF
    echo "<pre>";
    echo "{$calledFile} - {$calledLine}<br/><br/>";
    print_r($obj);
    echo "</pre>";
    echo "</div>";
    die();
}
*/
/* FUN��O �TIL PARA DEBUG SEM  DIE */
/*
function x($obj) {
    echo "<div style='background-color:#9370DB; border:1px #666666 solid; text-align:left;'>"; #DFDFDF
    echo "<pre>";
    print_r($obj);
    echo "</pre>";
    echo "</div>";
}
*/
function preparaValor($valor, $tipo=1) {
    if ($tipo == 1) {
        $valor = str_replace(".", "", $valor);
        $valor = str_replace(",", ".", $valor);
    } else {
        $negativo = false;
        if (substr($valor, 0, 1) == "-") {
            $negativo = true;
            $valor = substr($valor, 1);
        }
        $valor = str_replace(",", "", $valor);
        $valor = str_replace(".", ",", $valor);
        $tamanho = strlen($valor);
        $posicao_separador = strpos($valor, ",");
        if (!$posicao_separador)
            $valor .= ",00";
        elseif (($tamanho - $posicao_separador) == 2)
            $valor .= "0";
        $valor = pontoMilhar($valor);
        if ($negativo)
            $valor = "-" . $valor;
    }
    return $valor;
}

function pontoMilhar($valor) {
    $tamanho = strlen($valor);
    $inteiro = substr($valor, 0, ($tamanho - 3));
    $decimal = substr($valor, ($tamanho - 2));
    $temp = "";
    $cont = 0;
    for ($i = (strlen($inteiro) - 1); $i >= 0; $i--) {
        $temp = substr($valor, $i, 1) . $temp;
        $cont++;
        if ($i > 0 && $cont == 3) {
            $temp = "." . $temp;
            $cont = 0;
        }
    }
    $valor = $temp . "," . $decimal;
    return $valor;
}

/**
 * Prepara o nome do arquivo enviado para upload para ser gravado no banco
 * @param  $strNomeArquivo - nome do arquivo
 * @return string
 */
function preparaNomeArquivo($strNomeArquivo) {
    //$strNomeArquivo = html_entity_decode(utf8_decode($strNomeArquivo));
    //retira acentos e substitui espa�o em branco por underscores
    $arr1 = array("�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", "�", " ", "-", "'", "/", "\\");
    $arr2 = array("a", "a", "a", "a", "a", "e", "e", "e", "e", "i", "i", "i", "i", "o", "o", "o", "o", "o", "u", "u", "u", "u", "c", "A", "A", "A", "A", "A", "E", "E", "E", "E", "I", "I", "I", "I", "O", "O", "O", "O", "O", "U", "U", "U", "U", "C", "", "", "", "", "");
    $strNomeArquivoAlterado = str_replace($arr1, $arr2, $strNomeArquivo);

    //transformas as letras em min�sulas
    $strNomeArquivoAlterado = strtolower($strNomeArquivoAlterado);

    return $strNomeArquivoAlterado;
}

/**
 * Apaga os caracteres da mascara do texto enviado
 * @param  $strNomeArquivo - nome do arquivo
 * @return string
 */
function retiraMascara($strNomeArquivo) {
    //retira acentos e substitui espa�o em branco por underscores
    $arr1 = array("(", ")", "-", ".", "_", "/");
    $arr2 = array("", "", "", "", "", "");
    $strNomeArquivoAlterado = str_replace($arr1, $arr2, $strNomeArquivo);

    return $strNomeArquivoAlterado;
}

/**
 * Retorna o valor passado com o formato de CEP(99999-999)
 * @param  $valor - valor que deseja aplicar a mascara
 * @param  $mascara - formato da mascara que deseja aplicar
 * @return string
 */
function aplicaMascara($valor, $mascara) {
    $novoValor = null;
    $arrCaracteresEspeciais = array("-", "/", ".", ",", " ", "(", ")", "[", "]", "{", "}");

    $ct = 0;
    $tamanho = strlen($valor);
    for ($i = 0; $i < $tamanho; $i++) {
        if (!empty($mascara[$i])) {
            if (!in_array($mascara[$i], $arrCaracteresEspeciais)) {
                $novoValor .= $valor[$i - $ct];
            } else {
                $novoValor .= $mascara[$i];
                $ct++;
                $tamanho++;
            }
        }
    }
    return $novoValor;
}

function montaGuiaLinks($controller, $links = array()) {
    try {
        $pattern = "#(.*?)/$controller#is";
        if ($BASEURL = retornaBaseUrl($controller)) {

            $guia = "<div id='breadcrumb'><ul>";
            $guia .= "<li class='first'><a href='{$BASEURL}/principal/' title='In&iacute;cio'>In&iacute;cio</a></li>";
            $qtdLinks = count($links);
            $contador = 0;
            if ($qtdLinks > 0) {
                foreach ($links as $link) {
                    foreach ($link as $key => $val) {
                        $contador++;
                        if ($contador == $qtdLinks) {
                            $guia .= "<li class='last'>{$key}</li>";
                        } else {
                            $router = Zend_Controller_Front::getInstance()->getRouter();
                            if (is_array($val)) {
                                $url = $router->assemble(array('controller' => $val['controller'], 'action' => $val['action']));
                            } else {
                                $url = $val;
                            }
                            $guia .= "<li><a href='" . $url . "' title='{$key}'>" . $key . "</a></li>";
                        }
                    }
                }
            }
            $guia .= "</ul></div>";
            print $guia;
        } else {
            print "<span style='color: red;'>Erro na apresenta�?o da guia! - controller invalido</span>";
        }
    } catch (Zend_Exception $e) {
        parent::message("Erro ao montar guia de links", "projetosgerenciar/projetosgerenciar", "ERROR");
    }
}

function retornaBaseUrl($controller) {
    $BASEURL = false;
    $ESTAURL = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

    $pattern = "#(.*?)/$controller#is";
    if (preg_match($pattern, $ESTAURL, $RESP)) {
        $BASEURL = $RESP[1];
//        $PROTOCOLO = preg_replace("#($BASEURL.*)#is", '', $_SERVER['HTTP_REFERER']);
        $PROTOCOLO = "http://";
        $BASEURL = $PROTOCOLO . $BASEURL;
    }
    return $BASEURL;
}

function montaBotaoVoltar($controller, $links) {
    $link = retornaBaseUrl($controller) . '/principal/';
    $ultimoLink = count($links);
    
    if (isset($links[$ultimoLink - 2])) {
        foreach ($links[$ultimoLink - 2] as $key => $value) {
            $titulo = $key;
            if (is_array($value) && count($value) > 0) {
                $router = Zend_Controller_Front::getInstance()->getRouter();
                $link = $router->assemble(array('controller' => $value['controller'], 'action' => $value['action']));
            } else if ($value) {
                $link = $value;
            }
        }
    }
    $botaoVoltar = "<span class='voltar'><a href='{$link}'>Voltar</a></span>";

    print($botaoVoltar);
}



/* Func��o que verifica se o Proponente est� Inabilitado*/
function proponenteInabilitado($cpf)
{
	$inabilitadoDAO = new Inabilitado();
	
	$where['CgcCpf 		= ?'] = $cpf;
	$where['Habilitado 	= ?'] = 'N';
	$busca = $inabilitadoDAO->Localizar($where);
	
	if(count($busca) > 0){
		return true;
	} else {
		return false;
	}
}

/**
 * @param $cpf
 * @return bool
 */
function validaCPF($cpf) { // Verifiva se o n�mero digitado cont�m todos os digitos
    $cpf = str_pad(preg_replace('[^0-9]', '', $cpf), 11, '0', STR_PAD_LEFT);

    if (strlen($cpf) != 11 || $cpf == '00000000000' || $cpf == '11111111111' || $cpf == '22222222222' || $cpf == '33333333333' || $cpf == '44444444444' || $cpf == '55555555555' || $cpf == '66666666666' || $cpf == '77777777777' || $cpf == '88888888888' || $cpf == '99999999999') {
        return false;
    } else {   // Calcula os numeros para verificar se o CPF � verdadeiro
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
}

function isValidDate($date, $format = 'Y-m-d') {
    if (is_numeric(str_replace('-', '', $date))) {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    } else {
        return false;
    }
}

function isCnpjValid($cnpj) {
    // Etapa 1: Cria um array com apenas os digitos num�ricos,
    // Isso permite receber o cnpj em diferentes formatos como:
    // "00.000.000/0000-00", "00000000000000", "00 000 000 0000 00" etc...
    $num = array();
    $j = 0;
    for ($i = 0; $i < (strlen($cnpj)); $i++) {
        if (is_numeric($cnpj[$i])) {
            $num[$j] = $cnpj[$i];
            $j++;
        }
    }

    //Etapa 2: Conta os d�gitos, um Cnpj v�lido possui 14 d�gitos num�ricos.
    if (count($num) != 14) {
        return false;
    }

    //Etapa 3: O n�mero 00000000000 embora n�o seja um cnpj real resultaria um cnpj v�lido
    // ap�s o calculo dos d�gitos verificares e por isso precisa ser filtradas nesta etapa.
    if ($num[0] == 0 && $num[1] == 0 && $num[2] == 0 && $num[3] == 0 && $num[4] == 0 && $num[5] == 0 && $num[6] == 0 && $num[7] == 0 && $num[8] == 0 && $num[9] == 0 && $num[10] == 0 && $num[11] == 0) {
        return false;
    }

    //Etapa 4: Calcula e compara o primeiro d�gito verificador.
    else {
        $j = 5;
        for ($i = 0; $i < 4; $i++) {
            $multiplica[$i] = $num[$i] * $j;
            $j--;
        }
        $soma = array_sum($multiplica);
        $j = 9;
        for ($i = 4; $i < 12; $i++) {
            $multiplica[$i] = $num[$i] * $j;
            $j--;
        }
        $soma = array_sum($multiplica);
        $resto = $soma % 11;
        if ($resto < 2) {
            $dg = 0;
        } else {
            $dg = 11 - $resto;
        }
        if ($dg != $num[12]) {
            return false;
        }
    }

    //Etapa 5: Calcula e compara o segundo d�gito verificador.
    if (!isset($isCnpjValid)) {
        $j = 6;
        for ($i = 0; $i < 5; $i++) {
            $multiplica[$i] = $num[$i] * $j;
            $j--;
        }
        $soma = array_sum($multiplica);
        $j = 9;
        for ($i = 5; $i < 13; $i++) {
            $multiplica[$i] = $num[$i] * $j;
            $j--;
        }
        $soma = array_sum($multiplica);
        $resto = $soma % 11;
        if ($resto < 2) {
            $dg = 0;
        } else {
            $dg = 11 - $resto;
        }
        if ($dg != $num[13]) {
            return false;
        } else {
            return true;
        }
    }
}


// FIM DO METODO montaGuiaLinks
?>