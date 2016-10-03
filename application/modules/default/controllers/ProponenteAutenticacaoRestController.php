<?php

/**
 * Login e autentica��o via REST
 *
 * @version 1.0
 * @package application
 * @subpackage application.controller
 * @link http://www.cultura.gov.br
 * @copyright � 2016 - Minist�rio da Cultura - Todos os direitos reservados.
 */
class ProponenteAutenticacaoRestController extends MinC_Controller_Rest_Abstract{

    public function init() {
        $this->setPublicMethod('post');
        parent::init();
    }

    public function postAction() {
        $result = (object) array('msg' => '');

        # Pegando parametros via POST no formato JSON
        $body = $this->getRequest()->getRawBody();
        $post = Zend_Json::decode($body);
        $username = $post['usuario'];
        $password = $post['senha'];

        if(empty($username) || empty($password)){
            $result->msg = 'Usu&aacute;rio ou Senha inv&aacute;lidos!';
        } else if (strlen($username) == 11 && !Validacao::validarCPF($username)){
            $result->msg = 'CPF inv&aacute;lido!';
        } else if (strlen($username) == 14 && !Validacao::validarCNPJ($username)){
            $result->msg = 'CNPJ inv&aacute;lido!';
        } else {
            $Usuario = new Autenticacao_Model_Sgcacesso();
            $verificaStatus = $Usuario->buscar(array ( 'Cpf = ?' => $username));

            $verificaSituacao = 0;
            if(count($verificaStatus)>0) {
                $IdUsuario =  $verificaStatus[0]->IdUsuario;
                $verificaSituacao = $verificaStatus[0]->Situacao;
            }

            if ($verificaSituacao != 1) {
                if(md5($password) != $this->validarSenhaInicial()){
                    $encriptaSenha = EncriptaSenhaDAO::encriptaSenha($username, $password);
                    //x($encriptaSenha);
                    $SenhaFinal = $encriptaSenha[0]->senha;
                    //x($SenhaFinal);
                    $buscar = $Usuario->loginSemCript($username, $SenhaFinal);
                } else {
                    $buscar = $Usuario->loginSemCript($username, md5($password));
                }

                if(!$buscar){
                    $result->msg = 'Usu&aacute;rio ou Senha inv&aacute;lidos!';
                }
            } else {
                $SenhaFinal = addslashes($password);
                $buscar = $Usuario->loginSemCript($username, $SenhaFinal);
            }

            if($buscar){
                //$result->usuario = Zend_Auth::getInstance()->getIdentity();
                $result->authorization = $this->encryptAuthorization();
//                $result->authorization = Seguranca::encrypt($result->usuario->IdUsuario, $this->encryptHash);

                $verificaSituacao = $verificaStatus[0]->Situacao;
                if($verificaSituacao == 1) {
                    $result->msg = 'Voc&ecirc; logou com uma senha tempor&aacute;ria. Por favor, troque a senha.';
                }

                $agentes = new Agente_Model_DbTable_Agentes();
                $verificaAgentes = $agentes->buscar(array('CNPJCPF = ?' => $username))->current();

                if(empty($verificaAgentes)){
                    $result->msg = 'Voc&ecirc; ainda n&atilde;o est&aacute; cadastrado como proponente!';
                }

            } else {
                $result->msg = 'Usu&aacute;rio ou Senha inv&aacute;lidos!';
            }
        }

        # Resposta da autentica��o.
        $this->getResponse()->setHttpResponseCode(200)->setBody(json_encode($result));
    }

    /**
     * Gera a chave de acesso do usu�rio para utilizar os servi�os que precisam de identifica��o de usu�rio.
     *
     * @return string
     */
    protected function encryptAuthorization(){
        $usuario = Zend_Auth::getInstance()->getIdentity();
        $authorization = Seguranca::encrypt($this->publicKey. $usuario->Cpf. $this->publicKey, $this->encryptHash);

        return $authorization;
    }

    /**
     * Define senha inicial para cadastros incompletos.
     *
     * @return string
     */
    public static function validarSenhaInicial(){
        return 'ae56f49edf70ec03b98f53ea6d2bc622';
    }

    public function indexAction(){}

    public function getAction(){}

    public function putAction(){}

    public function deleteAction(){}

}
