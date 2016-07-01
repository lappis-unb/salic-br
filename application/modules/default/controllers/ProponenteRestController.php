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

class ProponenteRestController extends MinC_Controller_Rest_Abstract {

    public function postAction(){}
    
    public function indexAction(){
        $modelProponente = new Proponente();
        $objResultado = $modelProponente->buscarProponenteProjetoDeUsuario((int)$this->usuario->IdUsuario);
        $arrResultado = $objResultado->toArray();
        $listaProponente = array();

        if($arrResultado){
            foreach ($arrResultado as $contador => $proponente) {
//                $proponente->CNPJCPF = Mascara::addMaskCpfCnpj($proponente->CNPJCPF);
                $proponente['NomeProponente'] = utf8_encode(ucwords(strtolower($proponente['NomeProponente'])));
                $listaProponente[] = (object)$proponente;
            }
        }

        # Resposta da autenticação.
        $this->getResponse()->setHttpResponseCode(200)->setBody(json_encode($listaProponente));
    }
    
    public function getAction(){}

    public function putAction(){}

    public function deleteAction(){}

}