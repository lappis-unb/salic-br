<?php

class Assinatura_EnquadramentoController extends Assinatura_GenericController
{
    public function init()
    {
        $auth = Zend_Auth::getInstance(); // instancia da autenticacao

        $PermissoesGrupo = array();
        $PermissoesGrupo[] = 147;
        $PermissoesGrupo[] = 148;
        $PermissoesGrupo[] = 149;
        $PermissoesGrupo[] = 150;
        $PermissoesGrupo[] = 151;
        $PermissoesGrupo[] = 152;

        isset($auth->getIdentity()->usu_codigo) ? parent::perfil(1, $PermissoesGrupo) : parent::perfil(4, $PermissoesGrupo);

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
     * @todo Validar quando o botão "Finalizar" deve ser exibido
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

    /**
     * @todo Preencher os campos que estão com "xxxx" na view.
     * @todo Validar quando o botão "Finalizar" deve ser exibido
     * @todo Validar quando o botão "Devolver" deve ser exibido
     * @todo Adicionar ícones aos bot&otilde;es
     */
    public function visualizarEnquadramentoAction()
    {
        $get = Zend_Registry::get('get');
        $this->view->IdPRONAC = $get->IdPRONAC;

        $objProjeto = new Projetos();
        $this->view->projeto = $objProjeto->findBy(array(
            'IdPRONAC' => $this->view->IdPRONAC
        ));

        $this->view->valoresProjeto = $objProjeto->obterValoresProjeto($this->view->IdPRONAC);

        $objAgentes = new Agente_Model_DbTable_Agentes();
        $dadosAgente = $objAgentes->buscarFornecedor(array(
            'a.CNPJCPF = ?' => $this->view->projeto['CgcCpf']
        ));
        $arrayDadosAgente = $dadosAgente->current();
        $this->view->nomeAgente = $arrayDadosAgente['nome'];

        $mapperArea = new Agente_Model_AreaMapper();
        $this->view->areaCultural = $mapperArea->findBy(array(
            'Codigo' => $this->view->projeto['Area']
        ));

        $objSegmentocultural = new Segmentocultural();
        $this->view->segmentoCultural = $objSegmentocultural->findBy(array(
            'Codigo' => $this->view->projeto['Segmento']
        ));
//xd($this->view->areaCultural, $this->view->segmentoCultural);
        $objEnquadramento = new Enquadramento();
        $arrayPesquisa = array(
            'AnoProjeto' => $this->view->projeto['AnoProjeto'],
            'Sequencial' => $this->view->projeto['Sequencial'],
            'IdPRONAC' => $this->view->projeto['IdPRONAC']
        );
        $arrayEnquadramento = $objEnquadramento->findBy($arrayPesquisa);
    }

    /**
     * @todo Criar view.
     * @todo Preencher os campos que estão com "xxxx" na view.
     * @todo Validar quando o botão "Finalizar" deve ser exibido
     * @todo Adicionar ícones aos bot&otilde;es
     */
    public function devolverProjetoAction()
    {
        /**
        -- [ DEVOLUÇÃO ]
        select * from sac.dbo.Orgaos where idSecretaria = 251
        -- Quando devolver o projeto deve voltar para o órgçao 262 ( qunado for SEFIC [ SEFIC é o órgão superior ] )

        select * from sac.dbo.Orgaos where idSecretaria = 160
        -- Quando devolver o projeto deve voltar para o órgçao 171 ( qunado for SAV [ SAV é o órgão superior ] )
         */
    }

    /**
     * @todo Criar view.
     * @todo Preencher os campos que estão com "xxxx" na view.
     * @todo Adicionar ícones aos bot&otilde;es
     */
    public function assinarProjetoAction()
    {
        /**
         * Ao assinar:
         * - Caso esteja com o Coordenador Geral a próxima assinatura deve ser para Diretor:
         * - Caso esteja com o Diretor a próxima assinatura deve ser para Secretário:
         *
         * Usar esse script como base:
         */
    }

    /**
     * @todo Criar view.
     * @todo Preencher os campos que estão com "xxxx" na view.
     * @todo Adicionar ícones aos bot&otilde;es
     */
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
            select * from Tabelas..Grupos where gru_sistema = 21
         */
    }

}