<?php
require_once "GenericControllerNew.php";

class GerenciarparecerController extends GenericControllerNew {

    /**
     * @var integer (variável com o id do usuário logado)
     * @access private
     */
    private $getIdUsuario = 0;
    private $intTamPag = 10;


    public function init() {
        $this->view->title  = "Salic - Sistema de Apoio às Leis de Incentivo à Cultura"; // título da página
        $auth               = Zend_Auth::getInstance(); // pega a autenticação
        $Usuario            = new UsuarioDAO(); // objeto usuário
        $GrupoAtivo         = new Zend_Session_Namespace('GrupoAtivo'); // cria a sessão com o grupo ativo

        if ($auth->hasIdentity()) // caso o usuário esteja autenticado
        {
            // verifica as permissões
            $PermissoesGrupo    = array();
            $PermissoesGrupo[]  = 94;
            $PermissoesGrupo[]  = 93;
            $PermissoesGrupo[]  = 137;

            if (!in_array($GrupoAtivo->codGrupo, $PermissoesGrupo)) // verifica se o grupo ativo está no array de permissões
            {
                parent::message("Você não tem permissão para acessar essa área do sistema!", "principal/index", "ALERT");
            }

            // pega as unidades autorizadas, orgãos e grupos do usuário (pega todos os grupos)
            $grupos = $Usuario->buscarUnidades($auth->getIdentity()->usu_codigo, 21);

            // manda os dados para a visão
            $this->view->usuario        = $auth->getIdentity(); // manda os dados do usuário para a visão
            $this->view->arrayGrupos    = $grupos; // manda todos os grupos do usuário para a visão
            $this->view->grupoAtivo     = $GrupoAtivo->codGrupo; // manda o grupo ativo do usuário para a visão
            $this->view->orgaoAtivo     = $GrupoAtivo->codOrgao; // manda o órgão ativo do usuário para a visão

            if (isset($auth->getIdentity()->usu_codigo)) // autenticacao novo salic
            {
                $this->getIdUsuario = UsuarioDAO::getIdUsuario($auth->getIdentity()->usu_codigo);
                $this->getIdUsuario = ($this->getIdUsuario) ? $this->getIdUsuario["idAgente"] : 0;
            }

        } // fecha if
        else // caso o usuário não esteja autenticado
        {
            return $this->_helper->redirector->goToRoute(array('controller' => 'index', 'action' => 'logout'), null, true);
        }

        parent::init(); // chama o init() do pai GenericControllerNew
    }

    // fecha método init()


    public function indexAction() {
        return $this->_helper->redirector->goToRoute(array('controller' => 'gerenciarparecer', 'action' => 'listaprojetos'), null, true);
    }

    public function listaprojetosAction(){
        //** Usuario Logado ************************************************/
        $auth               = Zend_Auth::getInstance(); // pega a autenticação
        $idusuario          = $auth->getIdentity()->usu_codigo;
        $GrupoAtivo         = new Zend_Session_Namespace('GrupoAtivo'); // cria a sessão com o grupo ativo
        $codOrgao           = $GrupoAtivo->codOrgao; //  Órgão ativo na sessão
        $this->view->codOrgao = $codOrgao;
        $this->view->idUsuarioLogado = $idusuario;
        /******************************************************************/

        //DEFINE PARAMETROS DE ORDENACAO / QTDE. REG POR PAG. / PAGINACAO
        if($this->_request->getParam("qtde")) {
            $this->intTamPag = $this->_request->getParam("qtde");
        }
        $order = array();

        //==== parametro de ordenacao  ======//
        if($this->_request->getParam("ordem")) {
            $ordem = $this->_request->getParam("ordem");
            if($ordem == "ASC") {
                $novaOrdem = "DESC";
            }else {
                $novaOrdem = "ASC";
            }
        }else {
            $ordem = "ASC";
            $novaOrdem = "ASC";
        }

        //==== campo de ordenacao  ======//
        if($this->_request->getParam("campo")) {
            $campo = $this->_request->getParam("campo");
            $order = array($campo." ".$ordem);
            $ordenacao = "&campo=".$campo."&ordem=".$ordem;

        }else {
            $campo = null;
            $order = array('DtEnvioMincVinculada','NomeProjeto','stPrincipal desc');
            $ordenacao = null;
        }

        $pag = 1;
        $get = Zend_Registry::get('get');
        if (isset($get->pag)) $pag = $get->pag;
        $inicio = ($pag>1) ? ($pag-1)*$this->intTamPag : 0;

        /* ================== PAGINACAO ======================*/
        $where = [];
	$where["idOrgao = ?"] = $codOrgao;
	
        if((isset($_POST['pronac']) && !empty($_POST['pronac'])) || (isset($_GET['pronac']) && !empty($_GET['pronac']))){
	    $pronac = isset($_POST['pronac']) ? $_POST['pronac'] : $_GET['pronac'];
            $where["NrProjeto = ?"] = $pronac;
            $this->view->pronacProjeto = $pronac;
        }
	
        if(isset($_POST['tipoFiltro']) || isset($_GET['tipoFiltro'])){
            $tipoFiltro = isset($_POST['tipoFiltro']) ? $_POST['tipoFiltro'] : $_GET['tipoFiltro'];
            $this->view->tipoFiltro = $tipoFiltro;
        } else {
            // filtro padrao: aguardando_distribuicao
	    $tipoFiltro = 'aguardando_distribuicao';
            $this->view->tipoFiltro = $tipoFiltro;
        }
	
        $tbDistribuirParecer = new tbDistribuirParecer();
        $total = $tbDistribuirParecer->painelAnaliseTecnica($where, $order, null, null, true, $tipoFiltro);
        $fim = $inicio + $this->intTamPag;
	
        $totalPag = (int)(($total % $this->intTamPag == 0)?($total/$this->intTamPag):(($total/$this->intTamPag)+1));
        $tamanho = ($fim > $total) ? $total - $inicio : $this->intTamPag;

        $busca = $tbDistribuirParecer->painelAnaliseTecnica($where, $order, $tamanho, $inicio, false, $tipoFiltro);

        $paginacao = array(
                "pag"=>$pag,
                "qtde"=>$this->intTamPag,
                "campo"=>$campo,
                "ordem"=>$ordem,
                "ordenacao"=>$ordenacao,
                "novaOrdem"=>$novaOrdem,
                "total"=>$total,
                "inicio"=>($inicio+1),
                "fim"=>$fim,
                "totalPag"=>$totalPag,
                "Itenspag"=>$this->intTamPag,
                "tamanho"=>$tamanho
         );

        $this->view->paginacao     = $paginacao;
        $this->view->qtdDocumentos = $total;
        $this->view->dados         = $busca;
        $this->view->intTamPag     = $this->intTamPag;
    }


    public function produtosdistribuidosAction() {
        //** Usuario Logado ************************************************/
        $auth               = Zend_Auth::getInstance(); // pega a autenticação
//		$idusuario          = $auth->getIdentity()->usu_codigo;
        $idusuario          = $this->getIdUsuario;
        $GrupoAtivo         = new Zend_Session_Namespace('GrupoAtivo'); // cria a sessão com o grupo ativo
        $codOrgao           = $GrupoAtivo->codOrgao; //  Órgão ativo na sessão

        /******************************************************************/
        $tbDistribuirParecer    = new tbDistribuirParecer();
        $busca                  = $tbDistribuirParecer->produtosDistribuidos($codOrgao);
        /******************************************************************/

        Zend_Paginator::setDefaultScrollingStyle('Sliding');
        Zend_View_Helper_PaginationControl::setDefaultViewPartial('paginacao/paginacao.phtml');
        $paginator          = Zend_Paginator::factory($busca); // dados a serem paginados
        $currentPage        = $this->_getParam('page', 1);
        $paginator->setCurrentPageNumber($currentPage)->setItemCountPerPage(10);

        $this->view->dadospainel	= $paginator;
        $this->view->qtdDocumentos      = count($busca); // quantidade

        /******************************************************************/

    }

    public function enviarpagamentoAction() {
        //** Usuario Logado ************************************************/

        $auth               = Zend_Auth::getInstance(); // pega a autenticação
//		$idusuario          = $auth->getIdentity()->usu_codigo;
        $idusuario          = $this->getIdUsuario;
        $GrupoAtivo         = new Zend_Session_Namespace('GrupoAtivo'); // cria a sessão com o grupo ativo
        $codOrgao           = $GrupoAtivo->codOrgao; //  Órgão ativo na sessão
        $Grupo              = $GrupoAtivo->codGrupo;

        /******************************************************************/
        $tbDistribuirParecer    = new tbDistribuirParecer();
        $busca                  = $tbDistribuirParecer->pagamentoParecerista($codOrgao, $Grupo);
        /******************************************************************/

        Zend_Paginator::setDefaultScrollingStyle('Sliding');
        Zend_View_Helper_PaginationControl::setDefaultViewPartial('paginacao/paginacao.phtml');
        $paginator          = Zend_Paginator::factory($busca); // dados a serem paginados
        $currentPage        = $this->_getParam('page', 1);
        $paginator->setCurrentPageNumber($currentPage)->setItemCountPerPage(10);

        $this->view->dadospainel 	= $paginator;
        $this->view->qtdDocumentos  = count($busca); // quantidade
        $this->view->busca          = $busca;
        $this->view->grupo          = $Grupo;

        /******************************************************************/
    }

    public function historicoAction() {

        $auth = Zend_Auth::getInstance(); // pega a autenticação
//        $idUsuario = $auth->getIdentity()->usu_codigo;
        $idusuario          = $this->getIdUsuario;

        $GrupoAtivo = new Zend_Session_Namespace('GrupoAtivo'); // cria a sessão com o grupo ativo
        $idOrgao 	= $GrupoAtivo->codOrgao; //  Órgão ativo na sessão

        $idPronac 			 = $this->_request->getParam("idPronac");
        $idProduto 			 = $this->_request->getParam("idProduto");
        $stPrincipal 		 = $this->_request->getParam("stPrincipal");
        $tbDistribuirParecer = new tbDistribuirParecer();

        $where['d.idPronac 		= ?'] 	= $idPronac;
        $where['d.idProduto 	= ?'] 	= $idProduto;
        $where['d.stPrincipal 	= ?'] 	= $stPrincipal;

        $resp = $tbDistribuirParecer->buscarHistoricoCoordenador($where);

        $cont = 0;
        $Pareceres = array();

        foreach ($resp as $key => $val) {
            $cont++;

            if ($val->DtSolicitacao && $val->DtResposta == NULL) {
                $diligencia = 1;
            }
            else if ($val->DtSolicitacao && $val->DtResposta != NULL) {
                $diligencia = 2;
            }
            else if ($val->DtSolicitacao && round(data::CompararDatas($val->DtDistribuicao)) > $val->tempoFimDiligencia) {
                $diligencia = 3;
            }
            else {
                $diligencia = 0;
            }

            $Pareceres['pareceres'][$cont]['Nome do Produto'] 			= "$val->dsProduto";
            $Pareceres['pareceres'][$cont]['Unidade Responsável'] 		= "$val->Unidade";
            $Pareceres['pareceres'][$cont]['Data'] 						= date('d/m/Y', strtotime($val->DtDistribuicao));
            $Pareceres['pareceres'][$cont]['Observa&ccedil;&otilde;es'] = $val->Observacao;
            $Pareceres['pareceres'][$cont]['Nome do Remetente'] 		= $val->nmUsuario;
            $Pareceres['pareceres'][$cont]['Nome do Parecerista'] 		= $val->nmParecerista;

        }

        $this->view->Pareceres = $Pareceres;
    }

    public function distribuirAction() {
        //** Usuario Logado ************************************************/
        $auth = Zend_Auth::getInstance(); // pega a autenticação
//		$idusuario 	= $auth->getIdentity()->usu_codigo;
        $idusuario = $this->getIdUsuario;
        $GrupoAtivo = new Zend_Session_Namespace('GrupoAtivo'); // cria a sessão com o grupo ativo
        $codOrgao = $GrupoAtivo->codOrgao; //  Órgão ativo na sessão
        /******************************************************************/

        $idPronac = $this->_request->getParam("idpronac");
        $idProduto = $this->_request->getParam("idproduto");
        $TipoAnalise = $this->_request->getParam("tipoanalise");
	$tipoFiltro = $this->_request->getParam("tipoFiltro");

        $tbDistribuirParecer = new tbDistribuirParecer();

        $dadosWhere["IdPRONAC = ?"]               = $idPronac;
        $dadosWhere["idOrgao = ?"]                = $codOrgao;
	$dadosWhere["stPrincipal = ?"]            = 1;       
	$buscaDadosProjeto = $tbDistribuirParecer->painelAnaliseTecnica($dadosWhere, null, null, null, null, $tipoFiltro);
	
        $pareceristas = array();
        $spSelecionarParecerista = new spSelecionarParecerista();
        if(count($buscaDadosProjeto) > 0){
            $pareceristas = $spSelecionarParecerista->exec($buscaDadosProjeto[0]->idOrgao, $buscaDadosProjeto[0]->idArea, $buscaDadosProjeto[0]->idSegmento, $buscaDadosProjeto[0]->Valor);
        }

        $orgaos = new Orgaos();
        $buscar = $orgaos->buscar(array("Codigo <> ?" => $codOrgao, "Status = ?" => 0, "Vinculo = ?" => 1), array(2));

	$this->view->idSegmentoProduto = $buscaDadosProjeto[0]->idSegmento;
	$this->view->idAreaProduto  = $buscaDadosProjeto[0]->idArea;	
        $this->view->orgaos         = $buscar;
        $this->view->pareceristas   = $pareceristas;
        $this->view->dadosProjeto   = $buscaDadosProjeto;
        $this->view->idpronac       = $idPronac;
        $this->view->idproduto      = $idProduto;
        $this->view->tipoanalise    = $TipoAnalise;
        $this->view->tipoFiltro     = $tipoFiltro;

    }

    public function distribuiuAction() {
        //** Usuario Logado ************************************************/
        $auth           = Zend_Auth::getInstance(); // pega a autenticação
        $idusuario 		= $auth->getIdentity()->usu_codigo;
        $GrupoAtivo     = new Zend_Session_Namespace('GrupoAtivo'); // cria a sessão com o grupo ativo
        $codOrgao       = $GrupoAtivo->codOrgao; //  Órgão ativo na sessão
        /******************************************************************/

        $idPronac                   = $this->_request->getParam("idpronac");
        $observacao                 = $this->_request->getParam("obs");
        $orgaoDestino               = $this->_request->getParam("orgao");
        $idAgenteParecerista        = $this->_request->getParam("idAgenteParecerista");
        $tipoescolha                = $this->_request->getParam("tipodistribuir");
        $stPrincipal                = $this->_request->getParam("stPrincipal");
        $tipoFiltro                 = $this->_request->getParam("tipoFiltro");

        if(strlen($observacao) < 11 ) {
            parent::message("Dados obrigatórios n&atilde;o informados.","gerenciarparecer/distribuir/idpronac/".$idPronac ,"ALERT");
        }

        if((empty($idAgenteParecerista)) && ($tipoescolha == 1)) {
            parent::message("Dados obrigatórios n&atilde;o informados.",
                    "gerenciarparecer/encaminhar/idproduto/".$idProduto."/tipoanalise/".$TipoAnalise."/idpronac/".$idPronac  . "/tipoFiltro/" . $tipoFiltro,
                    "ALERT");
        }
        $tbDistribuirParecer = new tbDistribuirParecer();

        $dadosWhere["IdPRONAC = ?"]               = $idPronac;
        $dadosWhere["idOrgao = ?"]                = $codOrgao;
	$buscaDadosProjeto = $tbDistribuirParecer->painelAnaliseTecnica($dadosWhere, null, null, null, null, $tipoFiltro);
	//xd($buscaDadosProjeto);
	$error = "";
        $msg = "Distribuição Realizada com sucesso!";

        $db = Zend_Registry :: get('db');
        $db->setFetchMode(Zend_DB :: FETCH_OBJ);
        $db->beginTransaction();

        try {

            foreach($buscaDadosProjeto as $dp) {
                if($tipoescolha == 2) {
                    $msg = "Enviado os Produtos/Projeto para a entidade!";

                    // ALTERAR UNIDADE DE ANÁLISE ( COORDENADOR DE PARECER )

                    $dadosE = array(
                            'idOrgao'       		=> $orgaoDestino,
                            'DtEnvio'       		=> new Zend_Db_Expr("GETDATE()"),
                            'idAgenteParecerista'	=> null,
                            'DtDistribuicao'		=> null,
                            'DtDevolucao'   		=> null,
                            'DtRetorno'     		=> null,
                            'FecharAnalise' 		=> $dp->FecharAnalise,
                            'Observacao'    		=> $observacao,
                            'idUsuario'     		=> $idusuario,
                            'idPRONAC'      		=> $dp->IdPRONAC,
                            'idProduto'     		=> $dp->idProduto,
                            'TipoAnalise'   		=> 3,
                            'stEstado'      		=> 0,
                            'stPrincipal'   		=> $dp->stPrincipal,
                            'stDiligenciado'   		=> null
                    );

                    $where['idDistribuirParecer = ?']  = $dp->idDistribuirParecer;
                    $salvar = $tbDistribuirParecer->alterar(array('stEstado' => 1), $where);

                    $insere = $tbDistribuirParecer->inserir($dadosE);

		    $orgaos = new Orgaos();
		    $orgao = $orgaos->pesquisarNomeOrgao($codOrgao);
		    $projeto->alterarSituacao($dp->IdPRONAC, null, 'B11', 'Encaminhado para ' . $orgao->NomeOrgao . '.');
                }
                else {
                    $msg = "Distribuição Realizada com sucesso!";

                    // DISTRIBUIR OU REDISTRIBUIR ( COORDENADOR DE PARECER )

                    $dadosD = array(
                            'idOrgao'       		=> $dp->idOrgao,
                            'DtEnvio'       		=> $dp->DtEnvioMincVinculada,
                            'idAgenteParecerista'	=> $idAgenteParecerista,
                            'DtDistribuicao'		=> new Zend_Db_Expr("GETDATE()"),
                            'DtDevolucao'   		=> null,
                            'DtRetorno'     		=> null,
                            'FecharAnalise' 		=> $dp->FecharAnalise,
                            'Observacao'    		=> $observacao,
                            'idUsuario'     		=> $idusuario,
                            'idPRONAC'      		=> $dp->IdPRONAC,
                            'idProduto'     		=> $dp->idProduto,
                            'TipoAnalise'   		=> 3,
                            'stEstado'      		=> 0,
                            'stPrincipal'   		=> $dp->stPrincipal,
                            'stDiligenciado'   		=> null
                    );
		    
                    $where['idDistribuirParecer = ?']  = $dp->idDistribuirParecer;
                    $salvar = $tbDistribuirParecer->alterar(array('stEstado' => 1), $where);

                    $insere = $tbDistribuirParecer->inserir($dadosD);
		    
		    $projeto->alterarSituacao($dp->IdPRONAC, null, 'B11', 'Encaminhado para o perito para análise técnica e emissão de parecer.');
                }

            }

            parent::message($msg, "gerenciarparecer/listaprojetos?tipoFiltro=" . $tipoFiltro, "CONFIRM");
            $db->commit();

        }
        catch(Zend_Exception $ex) {
            $db->rollBack();
            parent::message("Error". $ex->getMessage(), "gerenciarparecer/distribuir/idDistribuirParecer/".$idDistribuirParecer."/idproduto/".$idProduto."/tipoanalise/".$TipoAnalise."/idpronac/".$idPronac . "/tipoFiltro/" . $tipoFiltro, "ERROR");
        }

    }

    public function encaminharAction() {
        //** Usuario Logado ************************************************/
        $auth           = Zend_Auth::getInstance(); // pega a autenticação
	//$idusuario 	= $auth->getIdentity()->usu_codigo;
        $idusuario      = $this->getIdUsuario;
        $GrupoAtivo     = new Zend_Session_Namespace('GrupoAtivo'); // cria a sessão com o grupo ativo
        $codOrgao       = $GrupoAtivo->codOrgao; //  Órgão ativo na sessão

        /******************************************************************/

        $idPronac       = $this->_request->getParam("idpronac");
        $idProduto      = $this->_request->getParam("idproduto");
        $tipoFiltro     = $this->_request->getParam("tipoFiltro");
        $tbDistribuirParecer = new tbDistribuirParecer();
	
	//Produto Principal
        $dadosWhere["IdPRONAC = ?"]                   = $idPronac;
        $dadosWhere["idOrgao = ?"]                    = $codOrgao;
	$dadosWhere["stPrincipal = ?"]  	      = 1;
        $dadosWhere["idProduto = ?"]                  = $idProduto;
	
	$buscaDadosProjeto = $tbDistribuirParecer->painelAnaliseTecnica($dadosWhere, null, null, null, null, $tipoFiltro);
	
        //Produto Secundario
        $dadosWhereS["IdPRONAC = ?"]                   = $idPronac;
        $dadosWhereS["idOrgao = ?"]                    = $codOrgao;
        $dadosWhereS["stPrincipal = ?"] 		 = 0;
        $dadosWhereS["idProduto = ?"]                  = $idProduto;
	$buscaDadosProjetoS = $tbDistribuirParecer->painelAnaliseTecnica($dadosWhereS, null, null, null, null, $tipoFiltro);
	
        if( (count($buscaDadosProjetoS) == 0) && (count($buscaDadosProjeto) == 0) ) {
            parent::message("Todos os produtos foram distribuidos!", "gerenciarparecer/listaprojetos?tipoFiltro=" . $tipoFiltro ,"ALERT");
            //parent::message("Aguardando as análises dos produtos secundários!", "gerenciarparecer/listaprojetos" ,"ALERT");
        }

        //Produto Secundario
        $dadosWhereSA["IdPRONAC = ?"]                   = $idPronac;
        $dadosWhereSA["idOrgao = ?"]                    = $codOrgao;
        $dadosWhereSA["stPrincipal = ?"]                = 0;
        $dadosWhereSA["idProduto = ?"]                  = $idProduto;
	$buscaDadosProjetoSA = $tbDistribuirParecer->painelAnaliseTecnica($dadosWhereSA, null, null, null, null, $tipoFiltro);

        if( count($buscaDadosProjetoSA) > 0 && count($buscaDadosProjetoS) == 0) {
            parent::message("Todos os produtos foram distribuidos SA!", "gerenciarparecer/listaprojetos?tipoFiltro=" . $tipoFiltro ,"ALERT");
            //parent::message("Aguardando as análises dos produtos secundários!", "gerenciarparecer/listaprojetos" ,"ALERT");
        }

        if(count($buscaDadosProjetoS) != 0) {
            $this->view->dadosProjeto   = $buscaDadosProjetoS;
        } else {
            $this->view->dadosProjeto   = $buscaDadosProjeto;
        }

        $pareceristas = array();
        $spSelecionarParecerista = new spSelecionarParecerista();
        if(count($buscaDadosProjetoS) > 0){
            $pareceristas = $spSelecionarParecerista->exec($buscaDadosProjetoS[0]->idOrgao, $buscaDadosProjetoS[0]->idArea, $buscaDadosProjetoS[0]->idSegmento, $buscaDadosProjetoS[0]->Valor);
	    $this->view->idSegmentoProduto = $buscaDadosProjetoS[0]->idSegmento;
	    $this->view->idAreaProduto  = $buscaDadosProjetoS[0]->idArea;
	
        } else if(count($buscaDadosProjetoSA) > 0) {
            $pareceristas = $spSelecionarParecerista->exec($buscaDadosProjetoSA[0]->idOrgao, $buscaDadosProjetoSA[0]->idArea, $buscaDadosProjetoSA[0]->idSegmento, $buscaDadosProjetoSA[0]->Valor);
	    $this->view->idSegmentoProduto = $buscaDadosProjetoSA[0]->idSegmento;
	    $this->view->idAreaProduto  = $buscaDadosProjetoSA[0]->idArea;	
        } else {
            $pareceristas = $spSelecionarParecerista->exec($buscaDadosProjeto[0]->idOrgao, $buscaDadosProjeto[0]->idArea, $buscaDadosProjeto[0]->idSegmento, $buscaDadosProjeto[0]->Valor);
	    $this->view->idSegmentoProduto = $buscaDadosProjeto[0]->idSegmento;
	    $this->view->idAreaProduto  = $buscaDadosProjeto[0]->idArea;
	}
        $orgaos = new Orgaos();
        $buscar = $orgaos->buscar(array("Codigo <> ?" => $codOrgao, "Status = ?" => 0, "Vinculo = ?" => 1));
	
        $this->view->orgaos         = $buscar;
        $this->view->idpronac       = $idPronac;
        $this->view->pareceristas   = $pareceristas;
    }

    public function encaminhouAction() {
        //** Usuario Logado ************************************************/
        $auth           = Zend_Auth::getInstance(); // pega a autenticação
        $idusuario 	= $auth->getIdentity()->usu_codigo;
        $GrupoAtivo     = new Zend_Session_Namespace('GrupoAtivo'); // cria a sessão com o grupo ativo
        $codOrgao       = $GrupoAtivo->codOrgao; //  Órgão ativo na sessão
        /******************************************************************/

        $idDistribuirParecer        = $this->_request->getParam("idDistribuirParecer");
        $idPronac                   = $this->_request->getParam("idpronac");
        $idProduto                  = $this->_request->getParam("idproduto");
	
        $observacao                 = $this->_request->getParam("obs");
        $orgaoDestino               = $this->_request->getParam("orgao");
        $idAgenteParecerista        = $this->_request->getParam("idAgenteParecerista");
        $tipoescolha                = $this->_request->getParam("tipodistribuir");
        $tipoFiltro                 = $this->_request->getParam("tipoFiltro");
	
        if(strlen($observacao) < 11) {
            parent::message("O campo observação deve ter no mínimo 11 caracteres!",
                    "gerenciarparecer/encaminhar/idproduto/".$idProduto."/idpronac/".$idPronac , "ALERT");
        }

        if((empty($idAgenteParecerista)) && ($tipoescolha == 1)) {
            parent::message("Selecione um Parecerista!",
                    "gerenciarparecer/encaminhar/idproduto/".$idProduto."/idpronac/".$idPronac . "/tipoFiltro/" . $tipoFiltro,
                    "ALERT");
        }

        $tbDistribuirParecer = new tbDistribuirParecer();
	
        $dadosWhere["idDistribuirParecer = ?"] = $idDistribuirParecer;
	$buscaDadosProjeto = $tbDistribuirParecer->painelAnaliseTecnica($dadosWhere, null, null, null, null, $tipoFiltro);
	
        $error = '';
        $db = Zend_Registry :: get('db');
        $db->setFetchMode(Zend_DB :: FETCH_OBJ);
        $db->beginTransaction();

        try {


            foreach($buscaDadosProjeto as $dp) {

                if($tipoescolha == 2) {
                    // ALTERAR UNIDADE DE ANÁLISE ( COORDENADOR DE PARECER )

                    $dadosE = array(
                            'idOrgao'       		=> $orgaoDestino,
                            'DtEnvio'       		=> new Zend_Db_Expr("GETDATE()"),
                            'idAgenteParecerista'	=> null,
                            'DtDistribuicao'		=> null,
                            'DtDevolucao'   		=> null,
                            'DtRetorno'     		=> null,
                            'FecharAnalise' 		=> $dp->FecharAnalise,
                            'Observacao'    		=> $observacao,
                            'idUsuario'     		=> $idusuario,
                            'idPRONAC'      		=> $dp->IdPRONAC,
                            'idProduto'     		=> $dp->idProduto,
                            'TipoAnalise'   		=> 3,
                            'stEstado'      		=> 0,
                            'stPrincipal'   		=> $dp->stPrincipal,
                            'stDiligenciado'   		=> null
                    );

                    $where['idDistribuirParecer = ?']  = $idDistribuirParecer;
                    $salvar = $tbDistribuirParecer->alterar(array('stEstado' => 1), $where);

                    $insere = $tbDistribuirParecer->inserir($dadosE);

		    $orgaos = new Orgaos();
		    $orgao = $orgaos->pesquisarNomeOrgao($codOrgao);
		    $projeto->alterarSituacao($dp->IdPRONAC, null, 'B11', 'Encaminhado para ' . $orgao->NomeOrgao . '.');
		    
                    parent::message("Enviado os Produtos/Projeto para a entidade!", "gerenciarparecer/listaprojetos?tipoFiltro=" . $tipoFiltro, "CONFIRM");
                }
                else {
                    // DISTRIBUIR OU REDISTRIBUIR ( COORDENADOR DE PARECER )
		  
                    $dadosD = array(
                            'idOrgao'       		=> $dp->idOrgao,
                            'DtEnvio'       		=> $dp->DtEnvioMincVinculada,
                            'idAgenteParecerista'	=> $idAgenteParecerista,
                            'DtDistribuicao'		=> new Zend_Db_Expr("GETDATE()"),
                            'DtDevolucao'   		=> null,
                            'DtRetorno'     		=> null,
                            'FecharAnalise' 		=> $dp->FecharAnalise,
                            'Observacao'    		=> $observacao,
                            'idUsuario'     		=> $idusuario,
                            'idPRONAC'      		=> $dp->IdPRONAC,
                            'idProduto'     		=> $dp->idProduto,
                            'TipoAnalise'   		=> 3,
                            'stEstado'      		=> 0,
                            'stPrincipal'   		=> $dp->stPrincipal,
                            'stDiligenciado'   		=> null
                    );

                    $where['idDistribuirParecer = ?']  = $idDistribuirParecer;
                    $salvar = $tbDistribuirParecer->alterar(array('stEstado' => 1), $where);

                    $insere = $tbDistribuirParecer->inserir($dadosD);

		    $projetos = new Projetos();
		    $projetos->alterarSituacao($dp->IdPRONAC, null, 'B11', 'Produto ' . $dp->Produto . ' encaminhado ao perito para análise técnica e emissão de parecer.');
		    
                    parent::message("Distribuição Realizada com sucesso!  ", "gerenciarparecer/listaprojetos?tipoFiltro=" . $tipoFiltro, "CONFIRM");
                }
            }
            $db->commit();

        } catch(Zend_Exception $ex) {
            $db->rollBack();
            parent::message("Error". $ex->getMessage(), "gerenciarparecer/encaminhar/idpronac/".$idPronac  . "/tipoFiltro/" . $tipoFiltro . "/idproduto/" . $idProduto, "ERROR");
        }

    }


    public function concluirAction() {

        //** Usuario Logado ************************************************/
        $auth           = Zend_Auth::getInstance(); // pega a autenticação
        $idusuario          = $this->getIdUsuario;
        $GrupoAtivo     = new Zend_Session_Namespace('GrupoAtivo'); // cria a sessão com o grupo ativo
        $codOrgao       = $GrupoAtivo->codOrgao; //  Órgão ativo na sessão
        /******************************************************************/

        $idDistribuirParecer    = $this->_request->getParam("idDistribuirParecer");
	$tipoFiltro             = $this->_request->getParam("tipoFiltro");

        $tbDistribuirParecer    = new tbDistribuirParecer();
        $dadosWhere["t.idDistribuirParecer = ?"]    = $idDistribuirParecer;

        $buscaDadosProjeto 	= $tbDistribuirParecer->dadosParaDistribuir($dadosWhere);

        $this->view->dadosProjeto   = $buscaDadosProjeto;
        $this->view->idDistribuirParecer 	= $idDistribuirParecer;

    }


    public function concluiuAction() {
        //** Usuario Logado ************************************************/
        $auth           = Zend_Auth::getInstance(); // pega a autenticação
        $idusuario 	= $auth->getIdentity()->usu_codigo;
        $GrupoAtivo     = new Zend_Session_Namespace('GrupoAtivo'); // cria a sessão com o grupo ativo
        $codOrgao       = $GrupoAtivo->codOrgao; //  Órgão ativo na sessão

        /******************************************************************/

        $idDistribuirParecer    = $this->_request->getParam("idDistribuirParecer");
        $idPronac             	= $this->_request->getParam("idpronac");
        $observacao             = $this->_request->getParam("obs");
	$tipoFiltro             = $this->_request->getParam("tipoFiltro");


        if(strlen($observacao) < 11) {
            parent::message("O campo observação deve ter no mínimo 11 caracteres!",
                    "gerenciarparecer/concluir/idDistribuirParecer/".$idDistribuirParecer."/idpronac/".$idPronac ,
                    "ALERT");
        }

        $db = Zend_Registry :: get('db');
        $db->setFetchMode(Zend_DB :: FETCH_OBJ);

        try {
            $db->beginTransaction();

            $tbDistribuirParecer    = new tbDistribuirParecer();
            $dadosWhere["t.idDistribuirParecer = ?"]    = $idDistribuirParecer;

            $buscaDadosProjeto 	= $tbDistribuirParecer->dadosParaDistribuir($dadosWhere);

            foreach ($buscaDadosProjeto as $dp):

                // FECHAR ANALISE ( COORDENADOR DE PARECER )
                $orgaos = array('91','92','93','94','95','160','171','335');

                // Caso não esteja dentro do array
                if (!in_array($dp->idOrgao, $orgaos)) {
                    $idOrgao 		= 91;
                    $fecharAnalise 	= 0;
                } else {
                    $idOrgao 		= $dp->idOrgao;
		    
		    if ($tipoFiltro == 'em_validacao') {
		      $fecharAnalise 	= 3;	    
		    } else if ($tipoFiltro == 'validados') {
		      $fecharAnalise 	= 1;
		    }
                }
		
                $dados = array(
                        'DtEnvio'       		=> $dp->DtEnvio,
                        'idAgenteParecerista'	=> $dp->idAgenteParecerista,
                        'DtDistribuicao'		=> $dp->DtDistribuicao,
                        'DtDevolucao'   		=> $dp->DtDevolucao,
                        'DtRetorno'     		=> new Zend_Db_Expr("GETDATE()"),
                        'Observacao'    		=> $observacao,
                        'idUsuario'     		=> $idusuario,
                        'FecharAnalise' 		=> $fecharAnalise,
                        'idOrgao'       		=> $idOrgao,
                        'idPRONAC'      		=> $dp->IdPRONAC,
                        'idProduto'     		=> $dp->idProduto,
                        'TipoAnalise'   		=> $dp->TipoAnalise,
                        'stEstado'      		=> 0,
                        'stPrincipal'   		=> $dp->stPrincipal,
                        'stDiligenciado'   		=> null
                );
		
                $whereD['idDistribuirParecer = ?']  = $idDistribuirParecer;
                $salvar = $tbDistribuirParecer->alterar(array('stEstado' => 1), $whereD);
                $insere = $tbDistribuirParecer->inserir($dados);

            endforeach;

            /** Grava o Parecer nas Tabelas tbPlanilhaProjeto e Parecer e altera a situação do Projeto para  ***************/
            $projeto = new Projetos();
            $wherePro['IdPRONAC = ?'] = $idPronac;
            $buscaDadosdoProjeto = $projeto->buscar($wherePro);

	    // se for produto principal
            if($buscaDadosProjeto[0]->stPrincipal == 1) {

                $inabilitadoDAO = new Inabilitado();
                $buscaInabilitado = $inabilitadoDAO->BuscarInabilitado($buscaDadosdoProjeto[0]->CgcCpf, $buscaDadosdoProjeto[0]->AnoProjeto, $buscaDadosdoProjeto[0]->Sequencial);
		
		// nao está inabilitado
                if(count($buscaInabilitado == 0)) {
  		    // dentro das unidades abaixo
                    if(in_array($dp->idOrgao, array(91,92,93,94,95,160,171,335))){
		      if ($tipoFiltro == 'validados') {
			$projeto->alterarSituacao($idPronac, null, 'C20', 'Análise técnica concluída');
		      } else if ($tipoFiltro == 'em_validacao') {
			$projeto->alterarSituacao($idPronac, null, 'B11', 'Aguardando validação do parecer técnico');
		      }
                    } else {
		      // fora das unidades acima
		      $projeto->alterarSituacao($idPronac, null, 'B11', 'Aguardando validação do parecer técnico');
                    }
                } else {
		  // inabilitado
		  $projeto->alterarSituacao($idPronac, null, 'C09', 'Projeto fora da pauta de reunião da CNIC porque o proponente está inabilitado no Ministério da Cultura.');
                }
		
                /****************************************************************************************************************/
                $parecerDAO = new Parecer();
                $whereParecer['idPRONAC = ?'] = $idPronac;
                $buscarParecer = $parecerDAO->buscar($whereParecer);

                $analiseDeConteudoDAO = new Analisedeconteudo();
                $whereADC['idPRONAC = ?'] = $idPronac;
                $dadosADC = array('idParecer' => $buscarParecer[0]->IdParecer);
                $alteraADC = $analiseDeConteudoDAO->alterar($dadosADC, $whereADC);

                $planilhaProjetoDAO = new PlanilhaProjeto();
                $wherePP['idPRONAC = ?'] = $idPronac;
                $dadosPP = array('idParecer' => $buscarParecer[0]->IdParecer);
                $alteraPP = $planilhaProjetoDAO->alterar($dadosPP, $wherePP);
                /****************************************************************************************************************/
            }
            $db->commit();
            parent::message("Concluído com sucesso!", "gerenciarparecer/listaprojetos?tipoFiltro=" . $tipoFiltro, "CONFIRM");

        } catch(Zend_Exception $ex) {
            $db->rollBack();
            parent::message("Erro ao concluir ".$ex->getMessage(), "gerenciarparecer/concluir/idDistribuirParecer/".$idDistribuirParecer . "/tipoFiltro/" . $tipoFiltro ,"ERROR");
        }

    }

    public function visualizaranalisedeconteudoAction() {
        $idPronac           = $this->_request->getParam("idpronac");
        $idProduto          = $this->_request->getParam("idproduto");
        $projetos           = new Projetos();
        $where              = array('a.idUsuario IS NOT NULL' =>'', 'p.IdPRONAC = ?' => $idPronac, 'a.idProduto = ?' => $idProduto);
        $dados              = $projetos->vwAnaliseConteudo($where, $order=array(), $tamanho=-1, $inicio=-1);
        $this->view->dados  = $dados;

    }

    public function visualizarplanilhadecustosAction() {
        $idPronac               = $this->_request->getParam("idpronac");
        $idProduto              = $this->_request->getParam("idproduto");
        $this->view->idPronac   = $idPronac;
    }

    public function planilhaAction() {

    }

    public function pareceristaAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->ViewRenderer->setNoRender(true);
        $post = Zend_Registry::get('get');
        $NomesDAO  =   new Nomes();
        $retorno   =   $NomesDAO->buscarPareceristas($post->idOrgao);

        foreach ($retorno as $value) {
            $pareceristas[] = array('id'=>$value->id,'nome'=>utf8_encode($value->Nome));
        }

        echo json_encode($pareceristas);
    }

    public function infopareceristaAction() {
        $this->_helper->layout->disableLayout();
	$this->_helper->ViewRenderer->setNoRender(true);

        $idAgente = $this->_request->getParam("idAgente");
        $idpronac = $this->_request->getParam("idpronac");
	$idArea = $this->_request->getParam("idArea");
	$idSegmento = $this->_request->getParam("idSegmento");
		
        $situacao 		= '1';
        $situacaoTexto 	= 'Ativo';

        $dataAtual 		= date("Y/m/d");
        $mesAnoAtual 	= date("Y/m");
        $diaAtual 		= date("d");
        $menos10 		= ($diaAtual - 10);

        $dataModificada = $mesAnoAtual.'/'.$menos10;

        $ausencia = new TbAusencia();

        $ferias   = $ausencia->BuscarAusenciaAtiva($idAgente, null, 2);
        $atestado = $ausencia->BuscarAusenciaAtiva($idAgente, null, 1);

        if(count($ferias) > 0) {
            $situacao = '0';
            $situacaoTexto = 'Férias';
        }

        if(count($atestado) > 0) {
            $situacao = '0';
            $situacaoTexto = 'Atestado';
        }

        if((count($ferias) > 0) && (count($atestado) > 0)) {
            $situacao = '0';
            $situacaoTexto = 'Férias e Atestado';
        }

        // CREDENCIAMENTO
	
        $projetosDAO 		= new Projetos();
        $credenciamentoDAO  = new TbCredenciamentoParecerista();

        $whereProjeto['IdPRONAC = ?'] = $idpronac;
        //$projeto = $projetosDAO->buscar($whereProjeto);
	// se for produto pegar area e segmento do produto
	
        $whereCredenciamento['idAgente = ?'] 			= $idAgente;
        $whereCredenciamento['idCodigoArea = ?'] 		= $idArea;
        $whereCredenciamento['idCodigoSegmento = ?']            = $idSegmento;
        $credenciamento = $credenciamentoDAO->buscar($whereCredenciamento)->count();
	
        if($credenciamento == 0) {
            $situacao = '0';
            $situacaoTexto .= '<br /> Parecerista não credenciado na área e segmento do Produto!';
        }
        //$situacaoTexto .= '<br /> Area: '.$projeto[0]->Area.' Segmento: '.$projeto[0]->Segmento.' idAgente: '.$idAgente;


        // Análises em eberto
        $whereAnalise['distribuirParecer.idAgenteParecerista = ?'] = $idAgente;
        $analiseEmAberto = $projetosDAO->buscaProjetosProdutos($whereAnalise);
        $situacaoTexto .= '<br /> Análise em aberto: '.count($analiseEmAberto);

        $pareceristas[] = array('situacao' => utf8_encode($situacao), 'situacaoTexto' => utf8_encode($situacaoTexto));

        echo json_encode($pareceristas);
    }

    private function tipos($array,$labelCampo,$tp,$infoInicial,$infoFinal = '') {
        switch ($tp) {
            case 1:
                $array[$labelCampo.' = ?']=$infoInicial;
                break;
            case 2:
                $array[$labelCampo.' < ?']=$infoInicial;
                $array[$labelCampo.' > ?']=$infoFinal;
                break;
            case 3:
                $array[$labelCampo.' > ?']=$infoInicial;
                break;
            case 4:
                $array[$labelCampo.' >= ?']=$infoInicial;
                break;
            case 5:
                $array[$labelCampo.' < ?']=$infoInicial;
                break;
            case 6:
                $array[$labelCampo.' <= ?']=$infoInicial;
                break;
        }

        return $array;
    }

    public function resconsolidacaopareceristaAction() {
        //** Usuario Logado ************************************************/
        $auth           = Zend_Auth::getInstance(); // pega a autenticação
//		$idusuario 	= $auth->getIdentity()->usu_codigo;
        $idusuario          = $this->getIdUsuario;
        $GrupoAtivo     = new Zend_Session_Namespace('GrupoAtivo'); // cria a sessão com o grupo ativo
        $codOrgao       = $GrupoAtivo->codOrgao; //  Órgão ativo na sessão
        /******************************************************************/

        $tela   = 'resconsolidacaoparecerista';
        $post = Zend_Registry::get('get');
        $where = array();

        if(!empty ($post->parecerista))     $where['dp.idAgenteParecerista = ?']    =   $post->parecerista;

        $distribuirParecerDAO   =   new tbDistribuirParecer();

        $resp = $distribuirParecerDAO->analisePorPareceristaPagamento($where);
        $retorno = array();

        foreach ($resp as $val) {
            $retorno['nmParecerista']   =   $val['nmParecerista'];
            $retorno['stPrincipal']     =   $val['stPrincipal'];

            if(!empty ($val->dtInicioAusencia)) {
                $dataini    =   date('d/m/Y',strtotime($val->dtInicioAusencia));
                $datafim    =   date('d/m/Y',strtotime($val->dtFimAusencia));
                $retorno['ferias'] =   $dataini.' a '.$datafim;
            }
            else {
                $retorno['ferias']  =   'N&atilde;o agendada';
                $area       =   $val->Area;
                $segmento   =   $val->Segmento;
                $nivel      =   $val->qtPonto;
                $Principal  =   $val->stPrincipal;

                $retorno['area_segmento_nivel'][$area.'-'.$segmento.'-'.$nivel]                                                                     =   $area.'-'.$segmento.'-'.$nivel;
                $retorno['projetos'][$val['IdPRONAC']]['pronac']                                                                                    =   $val['pronac'];
                $retorno['projetos'][$val['IdPRONAC']]['nmProjeto']                                                                                 =   $val['NomeProjeto'];
                $retorno['projetos'][$val['IdPRONAC']]['produtos'][$val['idProduto']]['nmProduto']                                                  =   $val['nmProduto'];
                $retorno['projetos'][$val['IdPRONAC']]['produtos'][$val['idProduto']]['distribuicao'][$val->idDistribuirParecer]['dtDistribuicao']  =   date('d/m/Y',strtotime($val['DtDistribuicao']));
                $retorno['projetos'][$val['IdPRONAC']]['produtos'][$val['idProduto']]['distribuicao'][$val->idDistribuirParecer]['nrDias']          =   $val['nrDias'];
            }

        }

        $cProduto = 0;
        $contaProjetos = 0;
        $cDistribuicao = 0;

        foreach ($retorno['projetos'] as $projeto) {
            $contaProjetos ++;
            $cProduto += count($projeto['produtos']);

            foreach ($projeto['produtos'] as $produto) {
                $cDistribuicao += count($produto['distribuicao']);
            }
        }

        $retorno['qtAnalise']  =   $cDistribuicao;
        $this->view->parecerista = $retorno;
    }

    public function visaopareceristaAction() {
        //** Usuario Logado ************************************************/
        $auth           = Zend_Auth::getInstance(); // pega a autenticação
//		$idusuario 	= $auth->getIdentity()->usu_codigo;
        $idusuario          = $this->getIdUsuario;
        $GrupoAtivo     = new Zend_Session_Namespace('GrupoAtivo'); // cria a sessão com o grupo ativo
        $codOrgao       = $GrupoAtivo->codOrgao; //  Órgão ativo na sessão
        /******************************************************************/

        $tela   = 'visaoparecerista';
        $post 	= Zend_Registry::get('get');
        $where 	= array();

        if(!empty ($post->parecerista))     $where['dp.idAgenteParecerista = ?']    =   $post->parecerista;

        $distribuirParecerDAO   =   new tbDistribuirParecer();

        $resp = $distribuirParecerDAO->analiseParecerista($where);
        $retorno = array();

        foreach ($resp as $val) {
            $retorno['nmParecerista']   =   $val['nmParecerista'];
            $retorno['projetos'][$val['IdPRONAC']]['IdPRONAC']                                                                                  =   $val['IdPRONAC'];
            $retorno['projetos'][$val['IdPRONAC']]['pronac']                                                                                    =   $val['pronac'];
            $retorno['projetos'][$val['IdPRONAC']]['nmProjeto']                                                                                 =   $val['NomeProjeto'];
            $retorno['projetos'][$val['IdPRONAC']]['produtos'][$val['idProduto']]['nmProduto']                                                  =   $val['nmProduto'];
            $retorno['projetos'][$val['IdPRONAC']]['produtos'][$val['idProduto']]['distribuicao'][$val->idDistribuirParecer]['dtDistribuicao']  =   date('d/m/Y',strtotime($val['DtDistribuicao']));
            $retorno['projetos'][$val['IdPRONAC']]['produtos'][$val['idProduto']]['distribuicao'][$val->idDistribuirParecer]['nrDias']          =   $val['nrDias'];
        }

        $cProduto = 0;
        $contaProjetos = 0;
        $cDistribuicao = 0;

        foreach ($retorno['projetos'] as $projeto) {
            $contaProjetos ++;
            $cProduto += count($projeto['produtos']);
            foreach ($projeto['produtos'] as $produto) {
                $cDistribuicao += count($produto['distribuicao']);
            }
        }

        $this->view->parecerista = $retorno;

    }

    public function consolidacaopareceristaAction() {
        $OrgaosDAO      =   new Orgaos();
        $NomesDAO       =   new Nomes();
        $AreaDAO        =   new Area();
        $SegmentoDAO    =   new Segmento();

        $this->view->Orgaos         =   $OrgaosDAO->buscar(array('Status = ?'=>0,'Vinculo = ?'=>1));
        $this->view->Pareceristas   =   $NomesDAO->buscarPareceristas();
        $this->view->Areas          =   $AreaDAO->buscar();
        $this->view->Segmento       =   $SegmentoDAO->buscar(array('stEstado = ?'=>1));
    }

    private function gerarInfoPaginas($tipo,$where = array(),$paginacao = 0) {
        $post = Zend_Registry::get('post');
        $retorno = array();
        $ProjetosDAO            =   new Projetos();
        $distribuirParecerDAO   =   new tbDistribuirParecer();

        switch ($tipo) {
            case 'resaguardandoparecer':

                if($paginacao > 0) {
                    $total	=   $distribuirParecerDAO->aguardandoparecerTotal($where)->current()->toArray();
                    $limit	=   $this->paginacao($total["total"], $paginacao);
                    $resp	=   $distribuirParecerDAO->aguardandoparecer($where,$limit['tamanho'], $limit['inicio']);
                }
                else {
                    $resp	=   $distribuirParecerDAO->aguardandoparecer($where);
                }

                $cDistribuicao = 0;

                foreach ($resp as $val) {
                    $retorno[$val['idOrgao']]['nmOrgao']                                                                                                        = $val['nmOrgao'];
                    $retorno[$val['idOrgao']]['pareceristas'][$val['idAgente']]['nmParecerista']                                                                = $val['nmParecerista'];
                    $retorno[$val['idOrgao']]['pareceristas'][$val['idAgente']]['projetos'][$val['IdPRONAC']]['idPronac']                                       = $val['IdPRONAC'];
                    $retorno[$val['idOrgao']]['pareceristas'][$val['idAgente']]['projetos'][$val['IdPRONAC']]['pronac']                                         = $val['pronac'];
                    $retorno[$val['idOrgao']]['pareceristas'][$val['idAgente']]['projetos'][$val['IdPRONAC']]['nmProjeto']                                      = $val['NomeProjeto'];
                    $retorno[$val['idOrgao']]['pareceristas'][$val['idAgente']]['projetos'][$val['IdPRONAC']]['Produtos'][$val['idProduto']]['nmProduto']       = $val['nmProduto'];
                    $retorno[$val['idOrgao']]['pareceristas'][$val['idAgente']]['projetos'][$val['IdPRONAC']]['Produtos'][$val['idProduto']]['distribuicao'][$cDistribuicao]['dtEnvio']         = date('d/m/Y',strtotime($val['DtEnvio']));
                    $retorno[$val['idOrgao']]['pareceristas'][$val['idAgente']]['projetos'][$val['IdPRONAC']]['Produtos'][$val['idProduto']]['distribuicao'][$cDistribuicao]['dtDistribuicao']  = date('d/m/Y',strtotime($val['DtDistribuicao']));
                    $retorno[$val['idOrgao']]['pareceristas'][$val['idAgente']]['projetos'][$val['IdPRONAC']]['Produtos'][$val['idProduto']]['distribuicao'][$cDistribuicao]['qtDias']          = $val['nrDias'];

                    $cDistribuicao++;
                }

                break;
            case 'resumo':
                $resp	=   $distribuirParecerDAO->aguardandoparecerresumo($where);
                $orgAnt = '';

                foreach ($resp as $val) {
                    if($orgAnt == '' || $orgAnt!=$val['idOrgao']) {
                        $retorno[$val['idOrgao']]['qt'] =   0;
                        $orgAnt = $val['idOrgao'];
                    }

                    $retorno[$val['idOrgao']]['nmOrgao']                                            =   $val['nmOrgao'];
                    $retorno[$val['idOrgao']]['qt']                                                 +=  $val['qt'];
                    $retorno[$val['idOrgao']]['pareceristas'][$val['idAgente']]['nmParecerista']    =   $val['nmParecerista'];
                    $retorno[$val['idOrgao']]['pareceristas'][$val['idAgente']]['qt']               =   $val['qt'];
                }
                break;

            case 'pareceremitido':
                if($paginacao > 0) {
                    $total	=   $ProjetosDAO->listaPareceremitidoTotal()->current()->toArray();
                    $limit	=   $this->paginacao($total["total"], $paginacao);
                    $resp	=   $ProjetosDAO->listaPareceremitido($limit['tamanho'], $limit['inicio']);
                }
                else {
                    $resp	=   $ProjetosDAO->listaPareceremitido();
                }

                foreach ($resp as $val) {
                    $retorno[$val['IdPRONAC']]['idPronac']                                                                  =   $val['IdPRONAC'];
                    $retorno[$val['IdPRONAC']]['pronac']                                                                    =   $val['pronac'];
                    $retorno[$val['IdPRONAC']]['nmProjeto']                                                                 =   $val['NomeProjeto'];
                    $retorno[$val['IdPRONAC']]['nmOrgao']                                                                   =   $val['OrgaoOrigem'];
                    $retorno[$val['IdPRONAC']]['dtEnvio']                                                                   =   date('d/m/Y',strtotime($val['DtEnvio']));
                    $retorno[$val['IdPRONAC']]['dtRetorno']                                                                 =   date('d/m/Y',strtotime($val['DtRetorno']));
                    $retorno[$val['IdPRONAC']]['qtDias']                                                                    =   $val['Dias'];
                    $retorno[$val['IdPRONAC']]['stParecer']                                                                 =   'Emitido';
                    $resp2 = $distribuirParecerDAO->pareceremitido($val['pronac']);
                    foreach ($resp2 as $val2) {
                        $retorno[$val['IdPRONAC']]['Orgaos'][$val2['idOrgao']]['nmOrgao']                                           =   $val2['Sigla'];
                        $retorno[$val['IdPRONAC']]['Orgaos'][$val2['idOrgao']]['Produtos'][$val2['idProduto']]['nmProduto']         =   $val2['Produto'];
                        if($val2['stPrincipal']) {
                            $retorno[$val['IdPRONAC']]['Orgaos'][$val2['idOrgao']]['Produtos'][$val2['idProduto']]['prodPrincipal'] =   'sim';
                        }
                        else {
                            $retorno[$val['IdPRONAC']]['Orgaos'][$val2['idOrgao']]['Produtos'][$val2['idProduto']]['prodPrincipal'] =   '';
                            $retorno[$val['IdPRONAC']]['Orgaos'][$val2['idOrgao']]['Produtos'][$val2['idProduto']]['dtFechamento']      =   date('d/m/Y',strtotime($val2['DtDevolucao']));
                            $retorno[$val['IdPRONAC']]['Orgaos'][$val2['idOrgao']]['Produtos'][$val2['idProduto']]['area']              =   $val2['Area'];
                            $retorno[$val['IdPRONAC']]['Orgaos'][$val2['idOrgao']]['Produtos'][$val2['idProduto']]['segmento']          =   $val2['Segmento'];
                        }
                    }
                }
                break;

            case 'parecerconsolidado':
                if($paginacao > 0) {
                    $total	=   $ProjetosDAO->listaParecerconsolidadoTotal()->current()->toArray();
                    $limit	=   $this->paginacao($total["total"], $paginacao);
                    $resp	=   $ProjetosDAO->listaParecerconsolidado($limit['tamanho'], $limit['inicio']);
                }
                else {
                    $resp	=   $ProjetosDAO->listaParecerconsolidado();
                }

                foreach ($resp as $val) {
                    $retorno[$val['IdPRONAC']]['idPronac']                                                                  =   $val['IdPRONAC'];
                    $retorno[$val['IdPRONAC']]['pronac']                                                                    =   $val['pronac'];
                    $retorno[$val['IdPRONAC']]['nmProjeto']                                                                 =   $val['NomeProjeto'];
                    $retorno[$val['IdPRONAC']]['nmOrgao']                                                                   =   $val['OrgaoOrigem'];
                    $retorno[$val['IdPRONAC']]['dtEnvio']                                                                   =   date('d/m/Y',strtotime($val['DtEnvio']));
                    $retorno[$val['IdPRONAC']]['dtRetorno']                                                                 =   date('d/m/Y',strtotime($val['DtRetorno']));
                    $retorno[$val['IdPRONAC']]['qtDiasRetorno']                                                             =   date('d/m/Y',strtotime($val['DtConsolidacaoParecer']));
                    $retorno[$val['IdPRONAC']]['dtConsolidacao']                                                            =   $val['Dias'];
                    $retorno[$val['IdPRONAC']]['qtDiasConsolidado']                                                         =   $val['QtdeConsolidar'];
                    $retorno[$val['IdPRONAC']]['stParecer']                                                                 =   'Consolidado';
                    $resp2 = $distribuirParecerDAO->parecerconsolidado($val['pronac']);
                    foreach ($resp2 as $val2) {
                        $retorno[$val['IdPRONAC']]['Orgaos'][$val2['idOrgao']]['nmOrgao']                                        =   $val2['Sigla'];
                        $retorno[$val['IdPRONAC']]['Orgaos'][$val2['idOrgao']]['Produtos'][$val2['idProduto']]['nmProduto']         =   $val2['Produto'];
                        if($val2['stPrincipal'])
                            $retorno[$val['IdPRONAC']]['Orgaos'][$val2['idOrgao']]['Produtos'][$val2['idProduto']]['prodPrincipal']   =   'sim';
                        else
                            $retorno[$val['IdPRONAC']]['Orgaos'][$val2['idOrgao']]['Produtos'][$val2['idProduto']]['prodPrincipal']   =   '';
                        $retorno[$val['IdPRONAC']]['Orgaos'][$val2['idOrgao']]['Produtos'][$val2['idProduto']]['dtFechamento']     =   date('d/m/Y',strtotime($val2['DtDevolucao']));
                        $retorno[$val['IdPRONAC']]['Orgaos'][$val2['idOrgao']]['Produtos'][$val2['idProduto']]['area']            =   $val2['Area'];
                        $retorno[$val['IdPRONAC']]['Orgaos'][$val2['idOrgao']]['Produtos'][$val2['idProduto']]['segmento']        =   $val2['Segmento'];
                    }
                }
                break;

            case 'resgeraldeanalise':
                $this->view->titulo = array('PRONAC',
                        'Nome do Projeto',
                        'Produto',
                        'Dt. Primeiro Envio para Vinculada',
                        'Dt. &Uacute;ltimo Envio para Vinculada',
                        'Dt. Distribui&ccedil;&atilde;o para Parecerista',
                        'Parecerista',
                        'Qtde Dias Para Distribuir',
                        'Qtde Dias na caixa do Parecerista',
                        'Total de dias gastos para An&aacute;lise',
                        'Dt. Devolu&ccedil;&atilde;o do Parecerista para o Coordenador',
                        'Qtde Dias para  Parecerista Analisar',
                        'Qtde Dias Aguardando Avalia&ccedil;&atilde;o do Coordenador',
                        'Status da Dilig&ecirc;ncia',
                        '&Oacute;rg&atilde;o',
                        'Periodo de Execu&ccedil;&atilde;o do Projeto',
                        'Dias Vencidos ou a Vencer para Execu&ccedil;&atilde;o do Projeto'
                );
                if($paginacao > 0) {
                    $total	=   $ProjetosDAO->geraldeanaliseTotal($where)->current()->toArray();
                    $limit	=   $this->paginacao($total["total"], $paginacao);
                    $resp	=   $ProjetosDAO->geraldeanalise($where,$limit['tamanho'], $limit['inicio']);
                }
                else {
                    $resp	=   $ProjetosDAO->geraldeanalise($where);
                }

                foreach ($resp as $key=>$val) {
                    $retorno[$key]['pronac']                                            =   $val['PRONAC'];
                    $retorno[$key]['idpronac']                                          =   $val['IdPRONAC'];
                    $retorno[$key]['nmProjeto']                                         =   $val['NomeProjeto'];
                    $retorno[$key]['Produtos'][$val['idProduto']]['nmProduto']          =   $val['Produto'];
                    $retorno[$key]['Produtos'][$val['idProduto']]['dtPriEnvVinc']       =   $val['DtPrimeiroEnvio'];
                    $retorno[$key]['Produtos'][$val['idProduto']]['dtUltEnvVinc']       =   $val['DtUltimoEnvio'];
                    $retorno[$key]['Produtos'][$val['idProduto']]['dtDistPar']          =   $val['DtDistribuicao'];
                    $retorno[$key]['Produtos'][$val['idProduto']]['nmParecerista']      =   $val['Parecerista'];
                    $retorno[$key]['Produtos'][$val['idProduto']]['qtDiasDist']         =   $val['QtdeDiasParaDistribuir'];
                    $retorno[$key]['Produtos'][$val['idProduto']]['qtDiasCaixaPar']     =   $val['QtdeDiasComParecerista'];
                    $retorno[$key]['Produtos'][$val['idProduto']]['tDiasAnal']          =   $val['QtdeTotalDiasAnalisar'];
                    $retorno[$key]['Produtos'][$val['idProduto']]['dtDevParCoo']        =   $val['dtDevolucao'];
                    $retorno[$key]['Produtos'][$val['idProduto']]['qtDiasParAnal']      =   $val['QtdeDiasPareceristaAnalisar'];
                    $retorno[$key]['Produtos'][$val['idProduto']]['qtDiasAguarAval']    =   $val['QtdeDiasDevolvidosCoordenador'];
                    $retorno[$key]['Produtos'][$val['idProduto']]['stDiligencia']       =   $this->estadoDiligencia($val);
                    $retorno[$key]['Produtos'][$val['idProduto']]['nmOrgao']            =   $val['nmOrgao'];
                    $retorno[$key]['Produtos'][$val['idProduto']]['perExecProj']        =   $val['PeriodoExecucao'];
                    $retorno[$key]['Produtos'][$val['idProduto']]['qtDiasVencExecProj'] =   $val['QtdeDiasVencido'];

                }
                break;
            case 'resconsolidacaoparecerista':
                $zerado = false;
                $resp = $distribuirParecerDAO->analisePorParecerista($where);
                if($resp->count() > 0) {

                    foreach ($resp as $val) {
                        if(!empty ($post->stAnalise)) {
                            if($post->stAnalise==1) {
                                if ($val->DtSolicitacao && round(data::CompararDatas($val->DtDistribuicao)) > $val->tempoFimDiligencia) {
                                    $retorno = $this->dadosResconsolidacaoparecerista($val,$retorno);
                                }
                            }
                            if($post->stAnalise==2) {
                                if ($val->DtSolicitacao && $val->DtResposta == NULL) {
                                    $retorno = $this->dadosResconsolidacaoparecerista($val,$retorno);
                                }
                            }
                            if($post->stAnalise==3) {
                                if (!($val->DtSolicitacao && round(data::CompararDatas($val->DtDistribuicao)) > $val->tempoFimDiligencia) and !($val->DtSolicitacao && $val->DtResposta == NULL)) {
                                    $retorno = $this->dadosResconsolidacaoparecerista($val,$retorno);
                                }
                            }
                        }
                        else {
                            $retorno = $this->dadosResconsolidacaoparecerista($val,$retorno);
                        }
                    }

                    if(count($retorno)>0) {
                        $cProduto = 0;
                        $contaProjetos = 0;
                        $cDistribuicao = 0;
                        foreach ($retorno['projetos'] as $projeto) {
                            $contaProjetos ++;
                            $cProduto += count($projeto['produtos']);
                            foreach ($projeto['produtos'] as $produto) {
                                $cDistribuicao += count($produto['distribuicao']);
                            }
                        }
                        $retorno['qtAnalise']  =   $cDistribuicao;
                    }
                    else {
                        $zerado = true;
                    }
                }
                else {
                    $zerado = true;
                }

                if($zerado) {
                    $agentesDAO = new Agentes();

                    $tela    = 'resconsolidacaoparecerista2';
                    $where   = $this->filtroGeral($tela);
                    $val     = $agentesDAO->dadosParecerista($where);
                    $retorno = $this->dadosParecerista($val,$retorno);
                    $retorno['qtAnalise']  =   0;
                }

                break;
        }
        return $retorno;
    }

    private function dadosParecerista($val,$retorno) {
        $retorno['nmParecerista']   =   $val['nmParecerista'];
        if(!empty ($val->dtInicioAusencia)) {
            $dataini    		=   date('d/m/Y',strtotime($val->dtInicioAusencia));
            $datafim    		=   date('d/m/Y',strtotime($val->dtFimAusencia));
            $retorno['ferias']  =   $dataini.' h? '.$datafim;
        }
        else
            $retorno['ferias']  =   'N&atilde;o agendada';
        $area       		=   $val->Area;
        $segmento   		=   $val->Segmento;
        $nivel      		=   $this->nivelParecerista($val->qtPonto);

        $retorno['area_segmento_nivel'][$area.'-'.$segmento.'-'.$nivel]
                =   $area.'-'.$segmento.'-'.$nivel;
        return $retorno;
    }

    private function dadosResconsolidacaoparecerista($val,$retorno) {
        $retorno = $this->dadosParecerista($val,$retorno);
        $retorno['projetos'][$val['IdPRONAC']]['idPronac']                                                                                  =   $val['IdPRONAC'];
        $retorno['projetos'][$val['IdPRONAC']]['pronac']                                                                                    =   $val['pronac'];
        $retorno['projetos'][$val['IdPRONAC']]['nmProjeto']                                                                                 =   $val['NomeProjeto'];
        $retorno['projetos'][$val['IdPRONAC']]['produtos'][$val['idProduto']]['nmProduto']                                                  =   $val['nmProduto'];
        $retorno['projetos'][$val['IdPRONAC']]['produtos'][$val['idProduto']]['distribuicao'][$val->idDistribuirParecer]['dtDistribuicao']  =   date('d/m/Y',strtotime($val['DtDistribuicao']));
        $retorno['projetos'][$val['IdPRONAC']]['produtos'][$val['idProduto']]['distribuicao'][$val->idDistribuirParecer]['nrDias']          =   $val['nrDias'];
        $retorno['projetos'][$val['IdPRONAC']]['produtos'][$val['idProduto']]['diligencia']                                                 =   $this->estadoDiligencia($val);
        return $retorno;
    }

    private function estadoDiligencia($val) {
        $post = Zend_Registry::get('post');
        if($post->tipo == 'pdf' or $post->tipo == 'xls') {
            if ($val->DtSolicitacao && $val->DtResposta == NULL) {
                $diligencia = "<p style='text-align: center;'>Diligenciado</p>";//1
            }
            else if ($val->DtSolicitacao && $val->DtResposta != NULL) {
                $diligencia = "<p style='text-align: center;'>Dilig&ecirc;ncia respondida</p>";//2
            }
            else if ($val->DtSolicitacao && round(data::CompararDatas($val->DtDistribuicao)) > $val->tempoFimDiligencia) {
                $diligencia = "<p style='text-align: center;'>Dilig&ecirc;ncia n&atilde;o respondida</p>";//3
            }
            else {
                $diligencia = "<p style='text-align: center;'>A diligenciar</p>";//0
            }
        }
        else {
            if ($val->DtSolicitacao && $val->DtResposta == NULL) {
                $diligencia = "<p style='text-align: center;'><img src='../public/img/notice.png' width='30px'/></p>";//1
            }
            else if ($val->DtSolicitacao && $val->DtResposta != NULL) {
                $diligencia = "<p style='text-align: center;'><img src='../public/img/notice2.png' width='30px'/></p>";//2
            }
            else if ($val->DtSolicitacao && round(data::CompararDatas($val->DtDistribuicao)) > $val->tempoFimDiligencia) {
                $diligencia = "<p style='text-align: center;'><img src='../public/img/notice3.png' width='30px'/></p>";//3
            }
            else {
                $diligencia = "<p style='text-align: center;'><img src='../public/img/notice1.png' width='30px'/></p>";//0
            }
        }

        return $diligencia;
    }

    private function nivelParecerista($qtPonto) {
        if ($qtPonto>=7 and $qtPonto<=14) {
            $nivel = 'I';
        }
        elseif($qtPonto>=15 and $qtPonto<=19) {
            $nivel = 'II';
        }
        elseif($qtPonto>=20 and $qtPonto<=25) {
            $nivel = 'III';
        }
    }

    public function url(array $urlOptions = array(), $name = null, $reset = false, $encode = true) {
        $router = Zend_Controller_Front::getInstance()->getRouter();
        return $router->assemble($urlOptions, $name, $reset, $encode);
    }

    public function confirmapagamentopareceristaAction() {

        $dataAtual = date("Y-m-d h:i:s");
        $idProduto = $this->_request->getParam('confirmPagamento');
        $valor     = $this->_request->getParam('valorPagamento');
        $idAgente  = $this->_request->getParam('idAgente');

        try {

            for ($i = 0; $i < sizeof($idProduto); $i++) {

                $arrayComprovante = array('idDocumento'       => 0,
                        'nrOrdemPagamento'  => 0,
                        'dtPagamento'       => null
                );

                $TbComprovantePagamento = new TbComprovantePagamento();

                $buscarComprovante = $TbComprovantePagamento->salvarComprovante($arrayComprovante);

                $buscarComprovante = $TbComprovantePagamento->BuscaUltimoComprovante();

                $arrayPagamento = array(
                        'idAgente'                  => $idAgente[$i],
                        'idProduto'                 => $idProduto[$i],
                        'siPagamento'               => 0,
                        'vlPagamento'               => $valor[$i],
                        'idComprovantePagamento'    => $buscarComprovante[0]->idComprovantePagamento
                );

                $TbPagamentoParecerista = new TbPagamentoParecerista();

                $insere = $TbPagamentoParecerista->salvarPagamentoParecerista($arrayPagamento);

            }

            parent::message("Pagamentos enviados para aprova&ccedil;&atilde;o do coordenador PRONAC", "gerenciarparecer/enviarpagamento", "CONFIRM");


        }
        catch (Exception $e) {
            parent::message("Erro ao enviar pagamentos: " . $e->getMessage(), "gerenciarparecer/enviarpagamento", "ERROR");
        }


    }

    public function gerarmemorandoAction() {

        $dataAtual = date("Y-m-d h:i:s");
        $idProduto = $this->_request->getParam('confirmPagamento');
        $valor     = $this->_request->getParam('valorPagamento');
        $idAgente  = $this->_request->getParam('idAgente');
        $idComprovantePagamento  = $this->_request->getParam('idComprovantePagamento');
        $idPagamentoParecerista  = $this->_request->getParam('idPagamentoParecerista');

        // Dados do memorando!
        $nrMemorando  		= $this->_request->getParam("nrMemorando");
        $nmCoordenador  	= $this->_request->getParam("nmCoordenador");
        $cargoCoordenador  	= $this->_request->getParam("cargoCoordenador");
        $nmSecretario  		= $this->_request->getParam("nmSecretario");
        $cargoSecretario  	= $this->_request->getParam("cargoSecretario");

        $this->view->nrMemorando 		= $nrMemorando;
        $this->view->nmCoordenador 		= $nmCoordenador;
        $this->view->cargoCoordenador 	= $cargoCoordenador;
        $this->view->nmSecretario 		= $nmSecretario;
        $this->view->cargoSecretario 		= $cargoSecretario;

        if(empty($idAgente)) {
            parent::message("Dados obrigatórios n&atilde;o informados.",
                    "gerenciarparecer/enviarpagamento",
                    "ALERT");
        }


        /*** Validacao data pagamento  ************************************************/
        $diaFixo = 20;
        $diaAtual = date("d");
        $mesAtual = date("m");
        $anoAtual = date("Y");

        if(($diaAtual > 10) and ($mesAtual < 12)) {
            $mesAtual = $mesAtual + 1;
        }
        else if(($diaAtual > 10) and ($mesAtual == 12)) {
            $anoAtual = $anoAtual + 1;
            $mesAtual = 01;
        }

        $hora = date("m:i:s");

        $dataCerta = $anoAtual."/".$mesAtual."/20 ".$hora;

        $dataCertaM = "20/".$mesAtual."/".$anoAtual;
        /******************************************************************************/

        /* DADOS DO AGENTE ************************************************************/
        $tbDistribuirParecer = new tbDistribuirParecer();
        $dadosProduto = $tbDistribuirParecer->pagamentoParecerista(null, 137);

        $agentes = new Agentes();

        $whereAgente = array('a.idAgente = ?' => $idAgente[0] );
        $dadosAgente = $agentes->buscarAgenteNome($whereAgente);

        $nomeParecerista = $dadosAgente[0]->Descricao;
        $cpfParecerista  = $dadosAgente[0]->CNPJCPF;
        /******************************************************************************/

        $arrayProdutosProjeto = array();

        try {
            $valorTotal = 0;
            for ($i = 0; $i < sizeof($idProduto); $i++) {

                $valorTotal = $valorTotal + $valor[$i];

                $dadosWhere = array('idDistribuirParecer = ?' => $idProduto[$i] );

                $dadosProjeto = $tbDistribuirParecer->BuscarParaMemorando($dadosWhere)->current();

                $arrayBusca = array(
                        'Item'          => $i,
                        'PRONAC'        => $dadosProjeto['NrProjeto'],
                        'Objeto'        => $dadosProjeto['Produto'],
                        'ValorParecer'  => $valor[$i],
                        'DataPagamento' => $dataCerta,
                        'Processo'      => $dadosProjeto->Processo
                );

                $arrayProdutosProjeto[]= $arrayBusca;



                $TbPagamentoParecerista = new TbPagamentoParecerista();
                $TbComprovantePagamento = new TbComprovantePagamento();

                $arrayComprovante = array('dtPagamento' => $dataCerta, 'nrOrdemPagamento' => $nrMemorando);
                $buscarComprovante = $TbComprovantePagamento->alterarComprovante($arrayComprovante, $idComprovantePagamento[$i]);

                $arrayPagamento = array('siPagamento' => 1);
                $alterar = $TbPagamentoParecerista->alterarPagamento($arrayPagamento, $idPagamentoParecerista[$i]);

            }

            $arrayParecerista = array('Nome' => $nomeParecerista, 'CPF' => Mascara::addMaskCPF($cpfParecerista), 'ValorTotal' => $valorTotal);

            $this->view->dadosParecerista = $arrayParecerista;
            $this->view->dadosProduto     = $arrayProdutosProjeto;
            $this->view->dataMemorando     = $dataCertaM;


        }
        catch (Exception $e) {
            parent::message("Erro ao enviar pagamentos: " . $e->getMessage(), "gerenciarparecer/enviarpagamento", "ERROR");
        }


    }



}
