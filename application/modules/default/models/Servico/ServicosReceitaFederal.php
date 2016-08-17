<?php

/**
 * Classe do componente de SEI que gerencia a
 * comunicacao via webservice entre o NovoSalic e o servi�o do Corporativo
 * que consome o Webservice da Receita Federal
 * Este Servi�o � do tipo REST
 *
 * @copyright Minist�rio da Cultura
 * @author Hepta/Minc - Alysson Vicu�a de Oliveira
 * @since 18/04/2016
 * @version 1.0
 */
class ServicosReceitaFederal {

    # Constante usada na classe para conexao com o WS
    const username 		        = "3bf410091bddf5e2092be862aa25fe34";
    #const password 		    = "QJHJWRRM"; #Produ��o
    const password 		        = "123456"; #Homologa��o
    #const hostServico           = "sistemasweb.cultura.gov.br";
    #const urlServico 	        = "/minc-pessoa/servicos/"; #Produ��o
    const hostServico           = "homolog.cultura.gov.br";
    const urlServico 	        = "/minc-pessoa/servicos/"; #Homologa��o

    const urlPessoaFisica 	    = "pessoa_fisica/consultar/";
    const urlPessoaJuridica 	= "pessoa_juridica/consultar/";
    const urlForcar	            = "?forcarBuscaNaReceita=true";

    # Atributos da classe
    #private static $objSoapCliente;


    /**
     * @author Alysson Vicu�a de Oliveira
     *
     * @param $cnpj - CNPJ a ser consultado
     * @param bool $returnJSON - Define se o retorno sera um JSON ou Array de Objetos
     * @param bool $forcarBuscaReceita - Define se deve ir na Base da receita federal, mesmo j� existindo o CPF na base do MINC
     * @return ArrayObject|mixed - Resultado da consulta em Json ou ArrayObject
     */
    public function consultarPessoaJuridicaReceitaFederal($cnpj, $forcarBuscaReceita = false, $returnJSON = false)
    {
        $chars = array(".","/","-");
        $cnpj = str_replace($chars,"",$cnpj);

        if (15 == strlen($cnpj) && !isCnpjValid($cnpj)) {
            throw new InvalidArgumentException("CPF/CNPJ inv�lido");
        }

        $url = "http://". self::hostServico . self::urlServico . self::urlPessoaJuridica . $cnpj;
        if ($forcarBuscaReceita) {
            $url .= self::urlForcar;
        }

        #xd($url);
        $username = self::username;
        $password = self::password;

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_USERPWD, "$username:$password");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $resultCurl = curl_exec($curl);
        curl_close($curl);
        $result = new ArrayObject(json_decode($resultCurl, true));

        if($returnJSON){
            $retornoResultado = $resultCurl; #Retorno do Formato JSON
        } else{
            $retornoResultado = $result; #Retorno no Formato ArrayObject
        }
        #xd($retornoResultado);

        return $retornoResultado;
    }

    /**
     * @author Alysson Vicu�a de Oliveira
     *
     * @param $cpf - CPF a ser consultado
     * @param bool $returnJSON - Define se o retorno sera um JSON ou Array de Objetos
     * @param bool $forcarBuscaReceita - Define se deve ir na Base da receita federal, mesmo j� existindo o CPF na base do MINC
     * @return ArrayObject|mixed - Resultado da consulta em Json ou ArrayObject
     */
    public function consultarPessoaFisicaReceitaFederal($cpf, $forcarBuscaReceita = false, $returnJSON = false)
    {
        if (11 == strlen($cpf) && !validaCPF($cpf)) {
            throw new InvalidArgumentException("CPF/CNPJ inválido");
        }

        $url = "http://". self::hostServico . self::urlServico . self::urlPessoaFisica . $cpf;
        if ($forcarBuscaReceita) {
            $url .= self::urlForcar;
        }

        $username = self::username;
        $password = self::password;

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_USERPWD, "$username:$password");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $resultCurl = curl_exec($curl);
        curl_close($curl);
        $result = new ArrayObject(json_decode($resultCurl, true));

        if($returnJSON){
            $retornoResultado = $resultCurl; #Retorno do Formato JSON
        } else{
            $retornoResultado = $result; #Retorno no Formato ArrayObject
        }
#xd($retornoResultado);

        return $retornoResultado;
    }

    /**
     * Metodo chamado quando o objeto da classe e instanciado
     *
     * @return VOID
     */
    public function __construct()
    {
        return;
    }

    /**
     * Metodo chamado quando o objeto da classe e serializado
     *
     * @return VOID
     */
    public function __sleep()
    {
        return;
    }

    /**
     * Metodo chamado quando o objeto da classe e unserializado
     *
     * @return VOID
     */
    public function __wakeup()
    {
        return;
    }

    /**
     * Caso o metodo nao seja encontrado
     *
     * @param STRING $strMethod
     * @param ARRAY $arrParameters
     * @return VOID
     */
    public function __call( $strMethod , $arrParameters )
    {
        debug( "O metodo " . $strMethod . " nao foi encontrado na classe " . get_class( $this ) . ".<br />" . __FILE__ . "(linha " . __LINE__ . ")" , 1 );
    }

} // end Utils_Wsdne