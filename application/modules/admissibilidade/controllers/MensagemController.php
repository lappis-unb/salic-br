<?php
/**
 * @name Admissibilidade_MensagemController
 * @package Modules/admissibilidade
 * @subpackage Controller
 *
 * @author Equipe RUP - Politec
 * @author Ruy Junior Ferreira Silva <ruyjfs@gmail.com>
 * @since 07/12/2016
 *
 * @link http://salic.cultura.gov.br
 */
class Admissibilidade_MensagemController extends MinC_Controller_Action_Abstract
{
    private $idPreProjeto = null;
    private $idUsuario = null;
    private $intTamPag = 50;
    private $codOrgaoSuperior = null;
    private $codGrupo = null;
    private $codOrgao = null;
    private $COD_CLASSIFICACAO_DOCUMENTO = 23;

    /**
     * Reescreve o metodo init()
     * @access public
     * @param void
     * @return void
     */
    public function init() {
        $auth = Zend_Auth::getInstance(); // instancia da autenticacao

        // verifica as permissoes
        $PermissoesGrupo = array();
        $PermissoesGrupo[] = 90;  // Protocolo - Documento
        $PermissoesGrupo[] = 91;  // Protocolo - Recebimento
        $PermissoesGrupo[] = 92;  // Tecnico de Admissibilidade
        $PermissoesGrupo[] = 93;  // Coordenador de Parecerista
        $PermissoesGrupo[] = 94;  // Parecerista
        $PermissoesGrupo[] = 95;  // Consulta
        $PermissoesGrupo[] = 96;  // Consulta Gerencial
        $PermissoesGrupo[] = 97;  // Gestor do SALIC
        $PermissoesGrupo[] = 99;  // Acompanhamento
        $PermissoesGrupo[] = 100; // Prestacao de Contas
        $PermissoesGrupo[] = 103; // Coordenador de Analise
        $PermissoesGrupo[] = 104; // Protocolo - Envio / Recebimento
        $PermissoesGrupo[] = 110; // Tecnico de Analise
        $PermissoesGrupo[] = 113; // Coordenador de Arquivo
        $PermissoesGrupo[] = 114; // Coordenador de Editais
        $PermissoesGrupo[] = 115; // Atendimento Representacoes
        $PermissoesGrupo[] = 119; // Presidente da CNIC
        $PermissoesGrupo[] = 120; // Coordenador CNIC
        $PermissoesGrupo[] = 121; // Tecnico de Acompanhamento
        $PermissoesGrupo[] = 122; // Coordenador de Acompanhamento
        $PermissoesGrupo[] = 123; // Coordenador Geral de Acompanhamento
        $PermissoesGrupo[] = 124; // Tecnico de Prestacao de Contas
        $PermissoesGrupo[] = 125; // Coordenador de Prestacao de Contas
        $PermissoesGrupo[] = 127; // Coordenador de Atendimento
        $PermissoesGrupo[] = 128; // Tecnico de Portaria
        $PermissoesGrupo[] = 131; // Coordenador de Admissibilidade
        $PermissoesGrupo[] = 133; // Membros Natos da CNIC
        $PermissoesGrupo[] = 134; // Coordenador de Fiscalizacao
        $PermissoesGrupo[] = 135; // Tecnico de Fiscalizacao
        $PermissoesGrupo[] = 136; // Coordenador de Entidade Vinculada
        $PermissoesGrupo[] = 138; // Coordenador de Avaliacao
        $PermissoesGrupo[] = 139; // Tecnico de Avaliacao
        $PermissoesGrupo[] = 140; // Tecnico de Admissibilidade Edital
        //parent::perfil(1, $PermissoesGrupo);
        //parent::init();
        isset($auth->getIdentity()->usu_codigo) ? parent::perfil(1, $PermissoesGrupo) : parent::perfil(4, $PermissoesGrupo);
        parent::init();

        //recupera ID do pre projeto (proposta)
        if(!empty ($_REQUEST['idPreProjeto'])){
            $this->idPreProjeto = $_REQUEST['idPreProjeto'];
        }

        isset($auth->getIdentity()->usu_codigo) ? $this->idUsuario = $auth->getIdentity()->usu_codigo : $this->idUsuario = $auth->getIdentity()->IdUsuario;
        //$this->idUsuario = $auth->getIdentity()->usu_codigo;
        $GrupoAtivo = new Zend_Session_Namespace('GrupoAtivo'); // cria a sess�o com o grupo ativo
        if(isset($auth->getIdentity()->usu_codigo)){

            $this->codGrupo = $GrupoAtivo->codGrupo; // manda o grupo ativo do usu�rio para a vis�o
            $this->codOrgao = $GrupoAtivo->codOrgao; // manda o �rg�o ativo do usu�rio para a vis�o

            $this->codOrgaoSuperior = (!empty($auth->getIdentity()->usu_org_max_superior))?$auth->getIdentity()->usu_org_max_superior:$auth->getIdentity()->usu_orgao;
        }

    }

    /**
     * @name indexAction
     *
     * @author Ruy Junior Ferreira Silva <ruyjfs@gmail.com>
     * @since  05/12/2016
     */
    public function indexAction()
    {
        $intIdPronac = $this->getRequest()->getParam('id');
        if ($intIdPronac) {
//            $dbTable = new Admissibilidade_Model_DbTable_TbMensagemProjeto();
//            $this->view->arrResult = $dbTable->getAllBy(array('IdPRONAC' => $intIdPronac));
            $this->view->title = "Perguntas do Pronac: Projeto TAL ({$intIdPronac})";
            $this->view->idPronac = $intIdPronac;
        } else {
            $this->view->title = "Perguntas";
//            parent::message("Pronac inv&aacute;lido.", "/admissibilidade/enquadramento/listar", "ALERT");
        }
    }

    public function listarAction()
    {
        $this->_helper->layout->disableLayout();
        $intIdPronac = $this->getRequest()->getParam('id');
        if ($intIdPronac) {
            $dbTable = new Admissibilidade_Model_DbTable_TbMensagemProjeto();
            $this->view->arrResult = $dbTable->getAllBy(array('IdPRONAC' => $intIdPronac, 'idMensagemOrigem IS NULL' => ''));
        } else {
            $auth = Zend_Auth::getInstance(); // pega a autenticacao
            $arrAuth = array_change_key_case((array) $auth->getIdentity());
            $dbTable = new Admissibilidade_Model_DbTable_TbMensagemProjeto();
            $this->view->arrResult = $dbTable->getAllBy(array('idDestinatario' => $arrAuth['usu_codigo'], 'idMensagemOrigem IS NULL' => ''));
            $this->view->arrResult = array();
        }
    }

    /**
     * Acao responsavel por inserir ou editar uma mensagem.
     *
     * @name salvarAction
     *
     * @author Ruy Junior Ferreira Silva <ruyjfs@gmail.com>
     * @since  06/12/2016
     */
    public function salvarAction()
    {
        $this->_helper->layout->disableLayout();
        if ($this->getRequest()->isPost()) {
            $this->_helper->viewRenderer->setNoRender(true);
            $mapper = new Admissibilidade_Model_TbMensagemProjetoMapper();
            echo json_encode(array('status' => $mapper->salvar($this->getRequest()->getPost()), 'msg' => $mapper->getMessages()));
        } else {
            $this->view->title = 'Enviar pergunta';
            $this->view->action = 'salvar';
            $this->prepareForm(array('dsResposta' => array('show' => false)));
        }
    }

    /**
     * Acao responsavel por inserir ou editar uma mensagem.
     *
     * @name salvarAction
     *
     * @author Ruy Junior Ferreira Silva <ruyjfs@gmail.com>
     * @since  06/12/2016
     */
    public function visualizarAction()
    {
        $this->_helper->layout->disableLayout();
        $this->view->title = 'Visualizar pergunta';
        $this->view->action = 'visualizar';
        $this->prepareForm(array(
            'idDestinatario' => array('disabled' => true),
            'dsMensagem' => array('disabled' => true),
            'dsResposta' => array('disabled' => true)
        ));
    }

    /**
     * Acao responsavel por encaminhar uma mensagem para outro agente.
     *
     * @name responderAction
     *
     * @author Ruy Junior Ferreira Silva <ruyjfs@gmail.com>
     * @since  12/12/2016
     */
    public function encaminharAction()
    {
        $this->_helper->layout->disableLayout();
        if ($this->getRequest()->isPost()) {
            $this->_helper->viewRenderer->setNoRender(true);
            $mapper = new Admissibilidade_Model_TbMensagemProjetoMapper();
            echo json_encode(array('status' => $mapper->encaminhar($this->getRequest()->getPost()), 'msg' => $mapper->getMessage()));
        } else {
            $this->view->title = 'Encaminhar pergunta';
            $this->view->action = 'encaminhar';
            $this->prepareForm(array(
                'dsMensagem' => array('disabled' => true),
                'dsResposta' => array('show' => false)
            ));
        }
    }

    /**
     * Acao responsavel por responder uma mensagem, no caso cadastrar uma mensagem referenciando outra.
     *
     * @name responderAction
     *
     * @author Ruy Junior Ferreira Silva <ruyjfs@gmail.com>
     * @since  12/12/2016
     */
    public function responderAction()
    {
        $this->_helper->layout->disableLayout();
        if ($this->getRequest()->isPost()) {
            $this->_helper->viewRenderer->setNoRender(true);
            $mapper = new Admissibilidade_Model_TbMensagemProjetoMapper();
            echo json_encode(array('status' => $mapper->responder($this->getRequest()->getPost()), 'msg' => $mapper->getMessage()));
        } else {
            $this->view->title = 'Responder pergunta';
            $this->view->action = 'responder';
            $this->prepareForm(array(
                'idDestinatario' => array('disabled' => true),
                'dsMensagem' => array('disabled' => true),
            ));
        }
    }

    /**
     * Metodo responsavel por preparar o formulario conforme cada acao.
     *
     * @name prepareForm
     * @param array $arrConfig
     *
     * @author Ruy Junior Ferreira Silva <ruyjfs@gmail.com>
     * @since  12/12/2016
     */
    public function prepareForm($arrConfig = array())
    {
        $dbTable = new Admissibilidade_Model_DbTable_TbMensagemProjeto();
        $vw = new vwUsuariosOrgaosGrupos();
        $intId = $this->getRequest()->getParam('id', null);
        $this->view->idPronac = $this->getRequest()->getParam('idPronac');
        $this->view->id = $intId;
        $this->view->dataForm = array();
        if ($intId) {
            $arrDataForm = $dbTable->findBy($intId);
            $unidade = $vw->findBy(array('usu_codigo' => $arrDataForm['idDestinatario']));
            $arrDataForm['idUnidade'] = $unidade['org_superior'];
            $this->view->dataForm = $arrDataForm;
            $this->view->dataForm['dsResposta'] = ($arrMensagemResposta = $dbTable->findBy(array('idMensagemOrigem' => $intId)))? $arrMensagemResposta['dsMensagem'] : '';
        }
        $this->view->arrUnidades = $vw->carregarUnidade();
        $this->view->arrConfig = $arrConfig;
        $this->render('form');
    }

    public function usuariosAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $vw = new vwUsuariosOrgaosGrupos();
        $intId = $this->getRequest()->getParam('id', null);
        $arrUsuarios = $vw->carregarPorPareceristaGrupoFetchPairs($intId);
        echo json_encode($arrUsuarios);
    }
}