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

        $auth = Zend_Auth::getInstance(); // pega a autenticacao
        $arrAuth = array_change_key_case((array) $auth->getIdentity());
        $intIdPronac = $this->getRequest()->getParam('id');
        if ($intIdPronac) {
            $dbTable = new Admissibilidade_Model_DbTable_TbMensagemProjeto();
            $this->view->arrResult = $dbTable->getAllBy(array('IdPRONAC' => $intIdPronac));

            $this->view->id = $intIdPronac;
            $vw = new vwUsuariosOrgaosGrupos();
            $this->view->arrUsuarios = $vw->carregarPorAdmissibilidadeGrupo();
        } else {
            parent::message("Pronac inv&aacute;lido.", "/admissibilidade/enquadramento/listar", "ALERT");
        }
        $this->render('index-material');
    }

    public function indexBootAction()
    {

        $auth = Zend_Auth::getInstance(); // pega a autenticacao
        $arrAuth = array_change_key_case((array) $auth->getIdentity());
        $intIdPronac = $this->getRequest()->getParam('id');
        if ($intIdPronac) {
            $dbTable = new Admissibilidade_Model_DbTable_TbMensagemProjeto();
            $this->view->arrResult = $dbTable->getAllBy(array('IdPRONAC' => $intIdPronac));
            $this->view->id = $intIdPronac;
            $vw = new vwUsuariosOrgaosGrupos();
            $this->view->arrUsuarios = $vw->carregarPorAdmissibilidadeGrupo();
        } else {
            parent::message("Pronac inv&aacute;lido.", "/admissibilidade/enquadramento/listar", "ALERT");
        }
        $this->render('index-boots');
    }

    public function indexDefaultAction()
    {

        $auth = Zend_Auth::getInstance(); // pega a autenticacao
        $arrAuth = array_change_key_case((array) $auth->getIdentity());
        $intIdPronac = $this->getRequest()->getParam('id');
        if ($intIdPronac) {
            $dbTable = new Admissibilidade_Model_DbTable_TbMensagemProjeto();
            $this->view->arrResult = $dbTable->getAllBy(array('IdPRONAC' => $intIdPronac));

            $this->view->id = $intIdPronac;
            $vw = new vwUsuariosOrgaosGrupos();
            $this->view->arrUsuarios = $vw->carregarPorAdmissibilidadeGrupo();
        } else {
            parent::message("Pronac inv&aacute;lido.", "/admissibilidade/enquadramento/listar", "ALERT");
        }
        $this->render('index');
    }

    public function listarAction()
    {
        $this->_helper->layout->disableLayout();
        $intIdPronac = $this->getRequest()->getParam('id');
        if ($intIdPronac) {
            $dbTable = new Admissibilidade_Model_DbTable_TbMensagemProjeto();
            $this->view->arrResult = $dbTable->getAllBy(array('IdPRONAC' => $intIdPronac));
        } else {
            $this->view->arrResult = array();
        }
    }

    /**
     *
     * @name salvarAction
     *
     * @author Ruy Junior Ferreira Silva <ruyjfs@gmail.com>
     * @since  06/12/2016
     *
     * @todo verificar qual o tipo da mensagem.
     */
    public function salvarAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->layout->disableLayout();
        $arrPost = $this->getRequest()->getPost();
        $arrResult = array(
            'status' => 0,
            'msg' => 'Nao foi possivel enviar mensagem!',
        );
        if ($this->getRequest()->isPost()) {
            $auth = Zend_Auth::getInstance(); // pega a autenticacao
            $arrAuth = array_change_key_case((array) $auth->getIdentity());
            $arrPost['dtMensagem'] = date('Y-m-d');
            $arrPost['idRemetente'] = $arrAuth['usu_codigo'];
            $arrPost['cdTipoMensagem'] = 1;
            $arrPost['stAtivo'] = 1;
            $mapper = new Admissibilidade_Model_TbMensagemProjetoMapper();
            $intId = $mapper->save(new Admissibilidade_Model_TbMensagemProjeto($arrPost));
            if ($intId) {
                $arrResult['status'] = 1;
                $arrResult['msg'] = 'Mensagem enviada com sucesso!';
            }
        }
        echo json_encode($arrResult);
    }
}