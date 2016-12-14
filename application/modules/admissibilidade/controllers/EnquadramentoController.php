<?php

/**
 * @package Controller
 * @since 02/12/2016 16:06
 */
class Admissibilidade_EnquadramentoController extends MinC_Controller_Action_Abstract
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
        $this->redirect("/admissibilidade/enquadramento/listar");
    }

    /**
     * ListarAction - Lista com Projetos em situção de Enuquadramento.
     *
     * @access public
     */
    public function listarAction()
    {
        $idusuario = $this->auth->getIdentity()->usu_codigo;
        $projeto = new  Projetos();
        $projetos = $projeto->listarPorSituacao('B01');//E63

        $codOrgao = $this->grupoAtivo->codOrgao;
        $this->view->codOrgao = $codOrgao;
        $this->view->idUsuarioLogado = $idusuario;
        $this->view->dados = $projetos;
    }

    public function enquadrarprojetoAction()
    {
        try {
            $get = $this->getRequest()->getParams();
            if (!isset($get['pronac']) || empty($get['pronac'])) {
                throw new Exception("Número de PRONAC não informado.");
            }
            $this->view->pronac = $get['pronac'];
            $objProjeto = new Projetos();
            $projeto = $objProjeto->findBy(array('IdPRONAC' => $this->view->pronac));

            if (!$projeto) {
                throw new Exception("PRONAC não encontrado.");
            }

            if($projeto['Situacao'] != "B01") {
                throw new Exception("Situa&ccedil;&atilde;o do projeto n&atilde;o &eacute; v&aacute;lida.");
            }

            $post = $this->getRequest()->getPost();
            if (!$post) {
                $this->carregardadosEnquadramentoProjeto($projeto);
            } else {
                $this->salvarEnquadramentoProjeto($projeto);
            }
        } catch (Exception $objException) {
            parent::message($objException->getMessage(), "/admissibilidade/enquadramento/listar");
        }
    }

    /**
     * @todo Verificar com Rômulo qual informaçõa deve ser armazenada para a coluna "ProvidenciaTomada" da tabela "Projetos"
     */
    private function salvarEnquadramentoProjeto($projeto)
    {
        $auth = Zend_Auth::getInstance();
        $post = $this->getRequest()->getPost();
        $get = $this->getRequest()->getParams();
        $authIdentity = array_change_key_case((array) $auth->getIdentity());
        $objEnquadramento = new Enquadramento();
        $arrayInclusao = array(
            'AnoProjeto' => $projeto['AnoProjeto'],
            'Sequencial' => $projeto['Sequencial'],
            'Enquadramento' => $post['enquadramento_projeto'],
            'DtEnquadramento' => $objEnquadramento->getExpressionDate(),
            'Observacao' => $post['observacao'],
            'Logon' => $authIdentity['usu_codigo'],
            'IdPRONAC' => $get['pronac'],
        );
        $objEnquadramento->inserir($arrayInclusao);

        $objProjeto = new Projetos();
        $arrayDados = array(
            'Situacao' => 'B02',
            'DtSituacao' => $objProjeto->getExpressionDate(),
            'ProvidenciaTomada' => $post['observacao'],
            'logon' => $authIdentity['usu_codigo']
        );
        $arrayWhere = array('IdPRONAC  = ?' => $projeto['IdPRONAC']);
        $objProjeto->update($arrayDados, $arrayWhere);

        /**
         * @todo Verificar com Rômulo para quem deve enviar o e-mail e qual a mensagem.
         */
        //EmailDAO::enviarEmail($email, "Projeto Cultural", $mensagemEmail);

        parent::message("Enquadramento cadastrado com sucesso.", "/admissibilidade/enquadramento/listar", "CONFIRM");
    }

    private function carregardadosEnquadramentoProjeto($projeto)
    {
        $mapperArea = new Agente_Model_AreaMapper();
        $this->view->comboareasculturais = $mapperArea->fetchPairs('Codigo', 'Descricao');
        $this->view->projeto = $projeto;

        if(count($this->view->comboareasculturais) < 1) {
            throw new Exception("N&atilde;o foram encontradas &Aacute;reas Culturais para o PRONAC informado.");
        }

        $this->view->combosegmentosculturais = Segmentocultural::buscarSegmento($projeto['Area']);

        if(count($this->view->combosegmentosculturais) < 1) {
            throw new Exception("N&atilde;o foram encontradas Segmentos Culturais para o PRONAC informado.");
        }

        $objEnquadramento = new Enquadramento();
        $arrayPesquisa = array(
            'AnoProjeto' => $projeto["AnoProjeto"],
            'Sequencial' => $projeto["Sequencial"],
            'IdPRONAC' => $projeto["IdPRONAC"]
        );
        $arrayEnquadramento = $objEnquadramento->findBy($arrayPesquisa);

        $this->view->observacao = $arrayEnquadramento['Observacao'];
    }
}
