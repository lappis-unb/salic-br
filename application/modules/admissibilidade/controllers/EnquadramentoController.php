<?php

/**
 * @package Controller
 * @author  Vinícius Feitosa da Silva <viniciusfesil@gmail.com>
 * @author  Wouerner <wouerner@gmail.com>
 * @since 02/12/2016 16:06
 */
class Admissibilidade_EnquadramentoController extends MinC_Controller_Action_Abstract
{

    public function init()
    {
        parent::perfil();
        parent::init();
    }

    public function indexAction()
    {
        $this->redirect("/admissibilidade/enquadramento/listar");
    }

    public function listarAction()
    {
        $auth = Zend_Auth::getInstance();
        $idusuario = $auth->getIdentity()->usu_codigo;
        $GrupoAtivo = new Zend_Session_Namespace('GrupoAtivo');
        $codOrgao = $GrupoAtivo->codOrgao;
        $this->view->codOrgao = $codOrgao;
        $this->view->idUsuarioLogado = $idusuario;

        if ($this->_request->getParam("qtde")) {
            $this->intTamPag = $this->_request->getParam("qtde");
        }
        $order = array();

        if ($this->_request->getParam("ordem")) {
            $ordem = $this->_request->getParam("ordem");
            if ($ordem == "ASC") {
                $novaOrdem = "DESC";
            } else {
                $novaOrdem = "ASC";
            }
        } else {
            $ordem = "ASC";
            $novaOrdem = "ASC";
        }

        if ($this->_request->getParam("campo")) {
            $campo = $this->_request->getParam("campo");
            $order = array($campo . " " . $ordem);
            $ordenacao = "&campo=" . $campo . "&ordem=" . $ordem;

        } else {
            $campo = null;
            $order = array('DtEnvioMincVinculada', 'NomeProjeto', 'stPrincipal desc');
            $ordenacao = null;
        }

        $pag = 1;
        $get = Zend_Registry::get('get');
        if (isset($get->pag)) $pag = $get->pag;
        $inicio = ($pag > 1) ? ($pag - 1) * $this->intTamPag : 0;

        $where = array();
        $where["idOrgao = ?"] = $codOrgao;

        if ((isset($_POST['pronac']) && !empty($_POST['pronac'])) || (isset($_GET['pronac']) && !empty($_GET['pronac']))) {
            $pronac = isset($_POST['pronac']) ? $_POST['pronac'] : $_GET['pronac'];
            $where["NrProjeto = ?"] = $pronac;
            $this->view->pronacProjeto = $pronac;
        }

        if (isset($_POST['tipoFiltro']) || isset($_GET['tipoFiltro'])) {
            $tipoFiltro = isset($_POST['tipoFiltro']) ? $_POST['tipoFiltro'] : $_GET['tipoFiltro'];
            $this->view->tipoFiltro = $tipoFiltro;
        } else {
            $tipoFiltro = 'aguardando_distribuicao';
            $this->view->tipoFiltro = $tipoFiltro;
        }

        $tbDistribuirParecer = new tbDistribuirParecer();
        $total = $tbDistribuirParecer->painelAnaliseTecnica($where, $order, null, null, true, $tipoFiltro);
        $fim = $inicio + $this->intTamPag;

        $totalPag = (int)(($total % $this->intTamPag == 0) ? ($total / $this->intTamPag) : (($total / $this->intTamPag) + 1));
        $tamanho = ($fim > $total) ? $total - $inicio : $this->intTamPag;
        $busca = $tbDistribuirParecer->painelAnaliseTecnica($where, $order, $tamanho, $inicio, false, $tipoFiltro);
        if ($tipoFiltro == 'validados' || $tipoFiltro == 'em_validacao' || $tipoFiltro == 'devolvida') {
            $checarValidacaoSecundarios = array();
            foreach ($busca as $chave => $item) {
                if ($item->stPrincipal == 1) {
                    $checarValidacaoSecundarios[$item->IdPRONAC] = $tbDistribuirParecer->checarValidacaoProdutosSecundarios($item->IdPRONAC);
                }
            }
            $this->view->checarValidacaoSecundarios = $checarValidacaoSecundarios;
        }

        $paginacao = array(
            "pag" => $pag,
            "qtde" => $this->intTamPag,
            "campo" => $campo,
            "ordem" => $ordem,
            "ordenacao" => $ordenacao,
            "novaOrdem" => $novaOrdem,
            "total" => $total,
            "inicio" => ($inicio + 1),
            "fim" => $fim,
            "totalPag" => $totalPag,
            "Itenspag" => $this->intTamPag,
            "tamanho" => $tamanho
        );

        $this->view->paginacao = $paginacao;
        $this->view->qtdDocumentos = $total;
        $this->view->dados = $busca;
        $this->view->intTamPag = $this->intTamPag;
    }

    public function enquadrarprojetoAction()
    {
        try {
            $post = $this->getRequest()->getPost();

            if (!$post) {
                $get = $this->getRequest()->getParams();
                if (!isset($get['pronac']) || empty($get['pronac'])) {
                    throw new Exception("Número de PRONAC não informado.");
                }
                $this->view->idPronac = $get['pronac'];
                $objProjeto = new Projetos();
                $whereProjeto['IdPRONAC'] = $this->view->idPronac;
                $projeto = $objProjeto->findBy($whereProjeto);
                if (!$projeto) {
                    throw new Exception("PRONAC não encontrado.");
                }

                $mapperArea = new Agente_Model_AreaMapper();
                $this->view->comboareasculturais = $mapperArea->fetchPairs('Codigo', 'Descricao');
                $this->view->projeto = $projeto;

                if(count($this->view->comboareasculturais) < 1) {
                    throw new Exception("Não foram encontradas Áreas Culturais para o PRONAC informado.");
                }

                $this->view->combosegmentosculturais = Segmentocultural::buscarSegmento($projeto['Area']);

                if(count($this->view->combosegmentosculturais) < 1) {
                    throw new Exception("Não foram encontradas Segmentos Culturais para o PRONAC informado.");
                }

                $parecerDAO 			= new Parecer();
                $whereParecer['idPRONAC = ?'] = $this->view->idPronac;
                $buscaParecer 			= $parecerDAO->buscar($whereParecer);
                //$this->consolidacao->ResumoParecer
                // $buscaParecer == descrição
            } else {

                $auth = Zend_Auth::getInstance(); // pega a autenticacao
                $authIdentity = array_change_key_case((array) $auth->getIdentity());

xd($authIdentity);
                $objEnquadramento = new Enquadramento();
                $objEnquadramento->
                    /*
                     * Enquadramento
                     *  > AnoProjeto = $projeto['AnoProjeto']
                     *  > Sequencial = $projeto['Sequencial']
                     *  > Enquadramento = $post['enquadramento_projeto']
                     *  > DtEnquadramento = 'getDate()'
                     *  > Observacao = $post['observacao']
                     *  > Logon = $authIdentity['usu_codigo']
                     */
                xd(123);
            }
        } catch (Exception $objException) {
            parent::message($objException->getMessage(), "/admissibilidade/enquadramento/listar");
        }
    }
}
