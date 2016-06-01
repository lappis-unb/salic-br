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
    
    public function indexAction(){
        $projeto = $this->_request->getParam('projeto');
        $ano = $this->_request->getParam('ano');
        $mes = $this->_request->getParam('mes');

        $modelProjetos = new Projetos();
        $listaResult = $modelProjetos->buscarExtrato($projeto, $ano, $mes);
        $listaExtrato = $listaResult->toArray();
        if($listaExtrato){
            foreach ($listaExtrato as $identificador => $lancamento) {
                $lancamento['vlLancamento'] = number_format($lancamento['vlLancamento'], 2, ',', '.');
                $listaExtrato[$identificador] = $lancamento;
            }
        }

        # Resposta da autenticação
        $this->getResponse()->setHttpResponseCode(200)->setBody(json_encode($listaExtrato));
    }
    
    public function getAction(){}

    public function putAction(){}

    public function deleteAction(){}

}
