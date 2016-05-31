<?php

/**
 * Dados do proponente via REST
 * 
 * @version 1.0
 * @package application
 * @subpackage application.controller
 * @link http://www.cultura.gov.br
 * @copyright © 2016 - Ministério da Cultura - Todos os direitos reservados.
 */
class ProjetoExtratoRestController extends AbstractRestController {

    public function postAction(){}
    
    public function indexAction(){}
    
    public function getAction(){
        $projeto = $this->_request->getParam('id');
        
        $projetoExtrato = new stdClass();
        $projetoExtrato->incentivador = utf8_encode('Sucoritrico Cutrale Ltda');
        $projetoExtrato->numeroRecibo = 1499;
        $projetoExtrato->tipoApoio = utf8_encode('Patrocínio');
        $projetoExtrato->dataCaptacao = '29/12/2015';
        $projetoExtrato->dataTransferencia = '31/12/2015';
        $projetoExtrato->porcentagemCapitado = 419022.40;
        $projetoExtrato->valorCapitado = number_format(419022.40, 2, ',', '.');
        $projetoExtrato->bemServico = utf8_encode('Não');
        $projetoExtrato->valorTotalCapitado = number_format(419022.40, 2, ',', '.');
        $projetoExtrato->porcentagemTotalCapitado = 99.99;

        # Resposta da autenticação.
        $this->getResponse()->setHttpResponseCode(200)->setBody(json_encode($projetoExtrato));
    }

    public function putAction(){}

    public function deleteAction(){}

}
