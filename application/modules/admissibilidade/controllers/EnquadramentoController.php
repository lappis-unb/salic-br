<?php

/**
 * @package Controller
 * @author  Wouerner <wouerner@gmail.com>
 * @author  Vinícius Feitosa da Silva <viniciusfesil@gmail.com>
 */
class Admissibilidade_EnquadramentoController extends MinC_Controller_Action_Abstract  {

    public function init()
    {
        /*
         * $this->view->title = "Salic - Sistema de Apoio ás Leis de Incentivo é Cultura"; // tetulo da pegina
        $auth              = Zend_Auth::getInstance(); // pega a autenticaeeo
        $Usuario           = new Autenticacao_Model_Usuario(); // objeto usuerio
        $GrupoAtivo        = new Zend_Session_Namespace('GrupoAtivo'); // cria a sesseo com o grupo ativo
         */
        parent::perfil();
        parent::init();
    }

    public function indexAction()
    {
        $this->redirect("/admissibilidade/enquadramento/listar");
    }

    public function listarAction()
    {

    }
}
