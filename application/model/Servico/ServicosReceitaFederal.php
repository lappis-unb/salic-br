<?php

/**
 * Classe do componente de SEI que gerencia a
 * comunicacao via webservice entre o NovoSalic e o serviço do Corporativo
 * que consome o Webservice da Receita Federal
 * Este Serviço é do tipo REST
 *
 * @copyright Ministério da Cultura
 * @author Hepta/Minc - Alysson Vicuña de Oliveira
 * @since 18/04/2016
 * @version 1.0
 */
class ServicosReceitaFederal {

    const urlPessoaFisica = "pessoa_fisica/consultar/";
    const urlPessoaJuridica = "pessoa_juridica/consultar/";
    const urlForcar = "?forcarBuscaNaReceita=true";

    /**
     * Endereço do Webservice.
     * 
     * @var type 
     */
    protected $baseUrl;
    
    /**
     * Nome usado para se conectar com o WebService da Receita Federal.
     * 
     * @var string
     */
    protected $user;
    
    /**
     * Senha de acesso ao Webservice.
     * 
     * @var string
     */
    protected $password;

    public function getBaseUrl() {
        return $this->baseUrl;
    }

    public function getUser() {
        return $this->user;
    }

    public function getPassword() {
        return $this->password;
    }

    public function setBaseUrl(type $baseUrl) {
        $this->baseUrl = $baseUrl;
        return $this;
    }

    public function setUser($user) {
        $this->user = $user;
        return $this;
    }

    public function setPassword($password) {
        $this->password = $password;
        return $this;
    }
    
    /**
     * @author Alysson Vicuña de Oliveira
     *
     * @param $cnpj - CNPJ a ser consultado
     * @param bool $returnJSON - Define se o retorno sera um JSON ou Array de Objetos
     * @param bool $forcarBuscaReceita - Define se deve ir na Base da receita federal, mesmo já existindo o CPF na base do MINC
     * @return ArrayObject|mixed - Resultado da consulta em Json ou ArrayObject
     */
    public function consultarPessoaJuridicaReceitaFederal($cnpj, $forcarBuscaReceita = false, $returnJSON = false) {
        $chars = array(".", "/", "-");
        $cnpj = str_replace($chars, "", $cnpj);

        if (15 == strlen($cnpj) && !isCnpjValid($cnpj)) {
            throw new InvalidArgumentException("CPF/CNPJ inválido");
        }

        $url = $this->baseUrl . self::urlPessoaJuridica . $cnpj;
        if ($forcarBuscaReceita) {
            $url .= self::urlForcar;
        }

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_USERPWD, "$this->user:$this->password");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $resultCurl = curl_exec($curl);
        curl_close($curl);
        $result = new ArrayObject(json_decode($resultCurl, true));

        if ($returnJSON) {
            $retornoResultado = $resultCurl; #Retorno do Formato JSON
        } else {
            $retornoResultado = $result; #Retorno no Formato ArrayObject
        }
#xd($retornoResultado);

        return $retornoResultado;
    }

    /**
     * @author Alysson Vicuña de Oliveira
     *
     * @param $cpf - CPF a ser consultado
     * @param bool $returnJSON - Define se o retorno sera um JSON ou Array de Objetos
     * @param bool $forcarBuscaReceita - Define se deve ir na Base da receita federal, mesmo já existindo o CPF na base do MINC
     * @return ArrayObject|mixed - Resultado da consulta em Json ou ArrayObject
     */
    public function consultarPessoaFisicaReceitaFederal($cpf, $forcarBuscaReceita = false, $returnJSON = false) {
        if (11 == strlen($cpf) && !validaCPF($cpf)) {
            throw new InvalidArgumentException("CPF/CNPJ inválido");
        }

        $url = $this->baseUrl . self::urlPessoaFisica . $cpf;
        if ($forcarBuscaReceita) {
            $url .= self::urlForcar;
        }

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($curl, CURLOPT_USERPWD, "$this->user:$this->password");
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $resultCurl = curl_exec($curl);
        curl_close($curl);
        $result = new ArrayObject(json_decode($resultCurl, true));

        if ($returnJSON) {
            $retornoResultado = $resultCurl; #Retorno do Formato JSON
        } else {
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
    public function __construct() {
        # Carrega configurações do Webservice
        $config = new Zend_Config_Ini("./application/configs/config.ini");
        $this->baseUrl = $config->get('default')->resources->view->service->wsReceita->baseUrl;
        $this->user = $config->get('default')->resources->view->service->wsReceita->user;
        $this->password = $config->get('default')->resources->view->service->wsReceita->password;
    }

    /**
     * Metodo chamado quando o objeto da classe e serializado
     *
     * @return VOID
     */
    public function __sleep() {
        return;
    }

    /**
     * Metodo chamado quando o objeto da classe e unserializado
     *
     * @return VOID
     */
    public function __wakeup() {
        return;
    }

    /**
     * Caso o metodo nao seja encontrado
     *
     * @param STRING $strMethod
     * @param ARRAY $arrParameters
     * @return VOID
     */
    public function __call($strMethod, $arrParameters) {
        debug("O metodo " . $strMethod . " nao foi encontrado na classe " . get_class($this) . ".<br />" . __FILE__ . "(linha " . __LINE__ . ")", 1);
    }

}
