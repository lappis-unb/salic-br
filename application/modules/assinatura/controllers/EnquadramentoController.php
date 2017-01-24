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

    /**
     * @todo foram comentados os tratamentos de acordo com o pefil, temporariamente
     */
    public function gerenciarProjetosAction()
    {
        $this->view->idUsuarioLogado = $this->auth->getIdentity()->usu_codigo;
        $enquadramento = new Assinatura_Model_DbTable_TbAssinatura();

        $this->view->dados = array();
        $ordenacao = array("projetos.DtSituacao asc");

//        if($this->grupoAtivo->codGrupo == Autenticacao_Model_Grupos::COORDENADOR_ADMISSIBILIDADE) {
            $this->view->dados = $enquadramento->obterProjetosEnquadrados($this->grupoAtivo->codOrgao, $ordenacao);
//        }

        $this->view->codGrupo = $this->grupoAtivo->codGrupo;
    }

    public function visualizarEnquadramentoAction()
    {
        throw new Exception("@todo implementar!");
    }

    public function devolverProjetoAction()
    {
        /**
         *  --- [ DEVOLUÇÃO ]
        select * from sac.dbo.Orgaos where idSecretaria = 251
        -- Quando devolver o projeto deve voltar para o órgçao 262 ( qunado for SEFIC [ SEFIC é o órgão superior ] )

        select * from sac.dbo.Orgaos where idSecretaria = 160
        -- Quando devolver o projeto deve voltar para o órgçao 171 ( qunado for SAV [ SAV é o órgão superior ] )

         */
        throw new Exception("@todo implementar!");
    }

    public function assinarProjetoAction()
    {
        throw new Exception("@todo implementar!");

        /**
         * Ao assinar:
         * - Caso esteja com o Coordenador Geral a próxima assinatura deve ser para Diretor:
         * - Caso esteja com o Diretor a próxima assinatura deve ser para Secretário:
         *
         * Usar esse script como base:
         *
         *
         */
    }

    public function finalizarAssinaturaAction()
    {
        throw new Exception("@todo implementar!");
        // portaria
        $orgaoDestino = 272;
        if($projeto['Area'] == 2) {
            $orgaoDestino = 166;
        }
    }

    private function tratarAssinaturaAction()
    {
        /*
         *
            select * from Tabelas..Grupos where gru_sistema = 21
         */
    }

}