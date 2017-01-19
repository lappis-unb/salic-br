<?php

class Assinatura_EnquadramentoController extends MinC_Controller_Action_Abstract
{
    public function init()
    {
        parent::perfil();
        parent::init();
        $this->auth = Zend_Auth::getInstance();
        $this->grupoAtivo = new Zend_Session_Namespace('GrupoAtivo');
    }

    public function indexAction()
    {
        $this->redirect("/{$this->moduleName}/enquadramento/gerenciar-projetos");
    }

    public function gerenciarProjetosAction()
    {
xd("parei aqui");
//        $auth = Zend_Auth::getInstance();
//        $objSession = $auth->getIdentity();
//
//
//        // LEMBRAR :
//        // $this->grupoAtivo->codOrgao  => Orgão logado   ==== Projetos.Orgao
//
//        $this->view->idUsuarioLogado = $this->auth->getIdentity()->usu_codigo;
//        $enquadramento = new Enquadramento();
//
//        $this->view->dados = array();
//        $ordenacao = array("projetos.DtSituacao asc");
//
//        if($this->grupoAtivo->codGrupo == Autenticacao_Model_Grupos::COORDENADOR_ADMISSIBILIDADE) {
//            $this->view->dados = $enquadramento->obterProjetosParaEnquadramento(
//                $this->grupoAtivo->codOrgao,
//                $ordenacao
//            );
//        } elseif ($this->grupoAtivo->codGrupo == Autenticacao_Model_Grupos::TECNICO_ADMISSIBILIDADE) {
//            $this->view->dados = $enquadramento->obterProjetosParaEnquadramentoVinculados(
//                $this->view->idUsuarioLogado,
//                $this->grupoAtivo->codOrgao,
//                $ordenacao
//            );
//        }
//
//        $this->view->codGrupo = $this->grupoAtivo->codGrupo;
    }

}
