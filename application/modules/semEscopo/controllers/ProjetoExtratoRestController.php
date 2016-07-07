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
class ProjetoExtratoRestController extends MinC_Controller_Rest_Abstract {

    public function postAction(){}
    
    public function indexAction(){
        # Parametros da Paginação.
        $next = $this->getRequest()->getParam('next');
        $offset = $this->getRequest()->getParam('offset');
        $total = $this->getRequest()->getParam('total');
        # Parametros da Consulta.
        $projeto = $this->getRequest()->getParam('projeto');
        $ano = $this->getRequest()->getParam('ano');
        $mes = $this->getRequest()->getParam('mes');

        $modelProjetos = new Projetos();
        $objParam = (object) array(
            'next' => $next,
            'offset' => $offset,
            'idPronac' => $projeto,
            'ano' => $ano,
            'mes' => $mes);
        # Verifica se existe necessidade de buscar o número total de registros da consulta
        if(!$total){
            $total = $modelProjetos->buscarTotalExtrato($objParam);
        }
        # Busca os dados da lista
        $listaResult = $modelProjetos->buscarExtrato($objParam);
        $listaExtrato = $listaResult->toArray();
        if($listaExtrato){
            foreach ($listaExtrato as $identificador => $lancamento) {
                $lancamento['vlLancamento'] = number_format($lancamento['vlLancamento'], 2, ',', '.');
                $listaExtrato[$identificador] = $lancamento;
            }
        }

        # Resposta da autenticação
        $this->getResponse()->setHttpResponseCode(200)->setBody(json_encode((object) array('list' => $listaExtrato, 'total' => $total)));
    }
    
    public function getAction(){}

    public function putAction(){}

    public function deleteAction(){}

}
