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
        $this->view->idUsuarioLogado = $this->auth->getIdentity()->usu_codigo;
        $enquadramento = new Assinatura_Model_DbTable_TbAssinatura();

        $this->view->dados = array();
        $ordenacao = array("projetos.DtSituacao asc");

//        if($this->grupoAtivo->codGrupo == Autenticacao_Model_Grupos::COORDENADOR_ADMISSIBILIDADE) {
            $this->view->dados = $enquadramento->obterProjetosEnquadrados($this->grupoAtivo->codOrgao, $ordenacao);
xd($this->view->dados);
//        }

        $this->view->codGrupo = $this->grupoAtivo->codGrupo;
    }

    private function tratarAssinaturaAction()
    {
        /*
         *
            --- [ DEVOLUÇÃO ]
            select * from sac.dbo.Orgaos where idSecretaria = 251
            -- Quando devolver o projeto deve voltar para o órgçao 262 ( qunado for SEFIC [ SEFIC é o órgão superior ] )

            select * from sac.dbo.Orgaos where idSecretaria = 160
            -- Quando devolver o projeto deve voltar para o órgçao 171 ( qunado for SAV [ SAV é o órgão superior ] )

            --- [ ENCAMINHAR ]

            -- Quando encaminha para o 'Diretor' o Código é 341

            -- Quando encaminha para o 'Secretário' o Código é 251

            select * from Tabelas..Grupos where gru_sistema = 21
         */
    }

}
