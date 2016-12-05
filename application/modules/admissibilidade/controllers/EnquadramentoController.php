<?php

/**
 * @package Controller
 * @author  Wouerner <wouerner@gmail.com>
 * @author  VinÃ­cius Feitosa da Silva <viniciusfesil@gmail.com>
 */
class Admissibilidade_EnquadramentoController extends MinC_Controller_Action_Abstract  {

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

    public function listarAction()
    {
        $idusuario = $this->auth->getIdentity()->usu_codigo;
        $projeto = new  Projetos();
        $projetos = $projeto->listarPorSituacao('E63',null, 30);

        $codOrgao = $this->grupoAtivo->codOrgao;
        $this->view->codOrgao = $codOrgao;
        $this->view->idUsuarioLogado = $idusuario;

        //if ($this->_request->getParam("qtde")) {
            //$this->intTamPag = $this->_request->getParam("qtde");
        //}
        //$order = array();

        //if ($this->_request->getParam("ordem")) {
            //$ordem = $this->_request->getParam("ordem");
            //if ($ordem == "ASC") {
                //$novaOrdem = "DESC";
            //} else {
                //$novaOrdem = "ASC";
            //}
        //} else {
            //$ordem = "ASC";
            //$novaOrdem = "ASC";
        //}

        //if ($this->_request->getParam("campo")) {
            //$campo = $this->_request->getParam("campo");
            //$order = array($campo . " " . $ordem);
            //$ordenacao = "&campo=" . $campo . "&ordem=" . $ordem;

        //} else {
            //$campo = null;
            //$order = array('DtEnvioMincVinculada', 'NomeProjeto', 'stPrincipal desc');
            //$ordenacao = null;
        //}

        //$pag = 1;
        //$get = Zend_Registry::get('get');
        //if (isset($get->pag)) $pag = $get->pag;
        //$inicio = ($pag > 1) ? ($pag - 1) * $this->intTamPag : 0;

        //$where = array();
        //$where["idOrgao = ?"] = $codOrgao;

        //if ((isset($_POST['pronac']) && !empty($_POST['pronac'])) || (isset($_GET['pronac']) && !empty($_GET['pronac']))) {
            //$pronac = isset($_POST['pronac']) ? $_POST['pronac'] : $_GET['pronac'];
            //$where["NrProjeto = ?"] = $pronac;
            //$this->view->pronacProjeto = $pronac;
        //}

        //if (isset($_POST['tipoFiltro']) || isset($_GET['tipoFiltro'])) {
            //$tipoFiltro = isset($_POST['tipoFiltro']) ? $_POST['tipoFiltro'] : $_GET['tipoFiltro'];
            //$this->view->tipoFiltro = $tipoFiltro;
        //} else {
            //$tipoFiltro = 'aguardando_distribuicao';
            //$this->view->tipoFiltro = $tipoFiltro;
        //}

        //$tbDistribuirParecer = new tbDistribuirParecer();
        //$total = $tbDistribuirParecer->painelAnaliseTecnica($where, $order, null, null, true, $tipoFiltro);
        //$fim = $inicio + $this->intTamPag;

        //$totalPag = (int)(($total % $this->intTamPag == 0) ? ($total / $this->intTamPag) : (($total / $this->intTamPag) + 1));
        //$tamanho = ($fim > $total) ? $total - $inicio : $this->intTamPag;
        //$busca = $tbDistribuirParecer->painelAnaliseTecnica($where, $order, $tamanho, $inicio, false, $tipoFiltro);
        //if ($tipoFiltro == 'validados' || $tipoFiltro == 'em_validacao' || $tipoFiltro == 'devolvida') {
            //$checarValidacaoSecundarios = array();
            //foreach ($busca as $chave => $item) {
                //if ($item->stPrincipal == 1) {
                    //$checarValidacaoSecundarios[$item->IdPRONAC] = $tbDistribuirParecer->checarValidacaoProdutosSecundarios($item->IdPRONAC);
                //}
            //}
            //$this->view->checarValidacaoSecundarios = $checarValidacaoSecundarios;
        //}

        //$paginacao = array(
            //"pag" => $pag,
            //"qtde" => $this->intTamPag,
            //"campo" => $campo,
            //"ordem" => $ordem,
            //"ordenacao" => $ordenacao,
            //"novaOrdem" => $novaOrdem,
            //"total" => $total,
            //"inicio" => ($inicio + 1),
            //"fim" => $fim,
            //"totalPag" => $totalPag,
            //"Itenspag" => $this->intTamPag,
            //"tamanho" => $tamanho
        //);



        //$this->view->paginacao = $paginacao;
        //$this->view->qtdDocumentos = $total;
        $this->view->dados = $projetos;
        //$this->view->intTamPag = $this->intTamPag;
    }
}
