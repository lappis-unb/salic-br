<?php
/**
 * MantertabelaitensController
 * @author Equipe RUP - Politec
 * @author wouerner <wouerner@gmail.com>
 * @since 10/12/2010
 * @version 1.0
 * @package application
 * @subpackage application.controller
 * @link http://www.cultura.gov.br
 * @copyright ? 2010 - Ministério da Cultura - Todos os direitos reservados.
 */
set_time_limit(0);

class Proposta_MantertabelaitensController extends MinC_Controller_Action_Abstract {

    private $getIdUsuario = 0;
    private $idUsuario = 0;
    private $idPreProjeto = 0;

    /**
     * Reescreve o método init()
     * @access public
     * @param void
     * @return void
     */
    public function init() {

        // autenticaç?o e permiss?es zend (AMBIENTE MINC)
        $PermissoesGrupo = array();
        $PermissoesGrupo[] = 127; // Coordenador - Geral de Análise (Ministro)
        $PermissoesGrupo[] = 97;  // Gestor do SALIC
        $arrAuth = array_change_key_case((array) Zend_Auth::getInstance()->getIdentity()); // instancia da autenticação
        isset($arrAuth['usu_codigo']) ? parent::perfil(1, $PermissoesGrupo) : parent::perfil(4, $PermissoesGrupo);

        parent::init(); // chama o init() do pai GenericControllerNew

        if(isset($arrAuth['usu_codigo'])){
            $this->idUsuario = $arrAuth['usu_codigo'];
        } else {
            $this->idUsuario = $arrAuth['idusuario'];
            $this->verificarPermissaoAcesso(true, false, false);
        }

        //recupera ID do pre projeto (proposta)
        if(!empty ($_REQUEST['idPreProjeto'])) {
            $this->idPreProjeto = $_REQUEST['idPreProjeto'];
            //VERIFICA SE A PROPOSTA ESTA COM O MINC
            $Movimentacao = new Movimentacao();
            $rsStatusAtual = $Movimentacao->buscarStatusAtualProposta($_REQUEST['idPreProjeto']);
            $this->view->movimentacaoAtual = isset($rsStatusAtual->Movimentacao) ? $rsStatusAtual->Movimentacao : '';
        }
    }

    /**
     * Redireciona para o fluxo inicial
     * @access public
     * @param void
     * @return void
     */
    public function indexAction() {
        $this->_forward("consultartabelaitens", "mantertabelaitens");
    }

    /**
     * produtosetapasitensAction
     *
     * @access public
     * @return void
     */
    public function produtosetapasitensAction() {

        $tableManterTabelaItens = new Proposta_Model_DbTable_tbSolicitarItem();

        $tbproduto = $tableManterTabelaItens->buscaproduto();
        $this->view->produto = $tbproduto;

        $tbetapa = $tableManterTabelaItens->buscaetapa();
        $this->view->etapa = $tbetapa;

        $tbitem = $tableManterTabelaItens->buscaitem();
        $this->view->item = $tbitem;
    }

    /**
     * solicitaritensAction
     *
     * @access public
     * @return void
     */
    public function solicitaritensAction() {
        $tableManterTabelaItens = new Proposta_Model_DbTable_tbSolicitarItem();

        $tbproduto = $tableManterTabelaItens->buscaproduto();
        $this->view->produto = $tbproduto;

        $tbetapa = $tableManterTabelaItens->buscaetapa();
        $this->view->etapa = $tbetapa;

        $tbitem = $tableManterTabelaItens->buscaitem();
        $this->view->item = $tbitem;

        $tbsolicitacao = $tableManterTabelaItens->solicitacoes($this->idUsuario);
        $this->view->solicitacao = $tbsolicitacao;

        $buscardados =  $tableManterTabelaItens->buscaprodutoetapaitem();
        $this->view->buscardados = $buscardados;

        if ($this->getRequest()->isPost()) {
            // recebe os dados via post
            $post = Zend_Registry::get('post');
            $justificativa = substr(trim($post->justificativa),0,1000);
            $idAgente = $this->getIdUsuario;
            $DtSolicitacao = $post->DtSolicitacao;
            $stEstado = $post->stEstado;
            $solicitacao = $post->solicitacao;
            $idPlanilhaItens = $post->idPlanilhaItens;
            $NomeItem = trim($post->Descricao);
            $etapa = $post->etapa;
            $produto = $post->produto;

            try {
                //recupera nome do item
                $nomeItem = $tableManterTabelaItens->buscaprodutoetapaitem($idPlanilhaItens);

                $dadosassociar = array(
                    'idPlanilhaItens' => $idPlanilhaItens,
                    'NomeDoItem' => $nomeItem->NomeDoItem,
                    'Descricao' => $justificativa,
                    'idProduto' => $produto,
                    'idEtapa' => $etapa,
                    'idAgente' => $this->idUsuario,
                    'DtSolicitacao' => new Zend_Db_Expr('GETDATE()'),
                    'stEstado' => '0'
                );

                $dadosincluir = array(
                    'idPlanilhaItens' => 0,
                    'NomeDoItem' => $NomeItem,
                    'Descricao' => $justificativa,
                    'idProduto' => $produto,
                    'idEtapa' => $etapa,
                    'idAgente' => $this->idUsuario,
                    'DtSolicitacao' => new Zend_Db_Expr('GETDATE()'),
                    'stEstado' => '0'
                );

                if(!empty($idPlanilhaItens) && $idPlanilhaItens!="0") {
                    $itemNome = $nomeItem->NomeDoItem;
                }else {
                    $itemNome = $post->Descricao;
                }

                $arrBusca = array();
                $arrBusca['prod.Codigo'] = $produto;
                $arrBusca['et.idPlanilhaEtapa'] = $etapa;

                $res = $tableManterTabelaItens->buscarSolicitacoes($arrBusca,$itemNome);

                if(count($res)>0) {
                    throw new Exception("Cadastro duplicado de Produto na mesma etapa envolvendo o mesmo Item, transa&ccedil;&atilde;o cancelada!");
                }

                if (empty($justificativa)) {
                    throw new Exception("Por favor, informe a justificativa!");
                }
                else if (strlen($justificativa) > 1000) {
                    throw new Exception("A justificativa n&atilde;o pode conter mais de 1000 caracteres!");

                }else if ($solicitacao == 'produtoetapa') {
                    //xd($dadosassociar);
                    $associaritem = $tableManterTabelaItens->associaritem($dadosassociar);
                    if ($associaritem) {
                        parent::message("A solicitação foi encaminhada ao Minc. Aguarde a resposta!", "/proposta/mantertabelaitens/solicitacoes?idPreProjeto=".$this->idPreProjeto, "CONFIRM");
                    }
                }else if ($solicitacao == 'novoitem');
                {

                    $incluiritem = false;
                    if (empty($NomeItem)) {
                        throw new Exception("Por favor, informe o nome do Item!");
                    }
                    else if (strlen($post->Descricao) > 100) {
                        throw new Exception("O nome do Item n?o pode conter mais de 100 caracteres!");
                    }

                    //codigo antigo
                    //$incluiritem = Mantertabelaitens::cadastraritem($Descricao, $this->idUsuario);
                    $incluiritem = $tableManterTabelaItens->cadastraritem($dadosincluir);

                    if ($incluiritem) {
                        $NovoItem = $tableManterTabelaItens->buscarItem($this->idUsuario);
                        $dadosassociar['idPlanilhaItens'] = $NovoItem['idPlanilhaItens'];

                        parent::message("Cadastro realizado com sucesso!", "proposta/mantertabelaitens/solicitacoes?idPreProjeto=".$this->idPreProjeto, "CONFIRM");
                        return;
                    }
                    else {
                        throw new Exception("Erro ao cadastrar o Item!");
                    }
                }
            }catch (Exception $e) {

                parent::message($e->getMessage(), "proposta/mantertabelaitens/solicitaritens?idPreProjeto=".$this->idPreProjeto, "ERROR");
                return;
            }
        }
    }

    /**
     * minhasSolicitacoesAction
     *
     * @access public
     * @return void
     */
    public function minhasSolicitacoesAction() {
        $this->intTamPag = 10;

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

        } else {
            $campo = null;
            $order = array(2,4,6);
            $ordenacao = null;
        }

        $pag = 1;
        $get = Zend_Registry::get('get');
        $this->view->idPreProjeto = $get->idPreProjeto;

        if (isset($get->pag)) $pag = $get->pag;
        $inicio = ($pag>1) ? ($pag-1)*$this->intTamPag : 0;

        /* ================== PAGINACAO ======================*/
        $auth = Zend_Auth::getInstance()->getIdentity(); // pega a autenticação
        $arrAuth = array_change_key_case((array) $auth); // pega a autenticação
        $where = array();
        $where['sol.idAgente = ?'] = $arrAuth['idusuario'];
        $where['sol.stEstado = ?'] = 1; // Atendido

        if(isset($_POST['tipoFiltro']) || isset($_GET['tipoFiltro'])){
            $filtro = isset($_POST['tipoFiltro']) ? $_POST['tipoFiltro'] : $_GET['tipoFiltro'];
            $this->view->filtro = $filtro;
            switch ($filtro) {
                case '':
                    $where['sol.stEstado = ?'] = 1; // Atendido
                    $this->view->nmPagina = 'Atendido';
                    break;
                case 'solicitado':
                    $where['sol.stEstado = ?'] = 0; // Solicitado
                    $this->view->nmPagina = 'Solicitado';
                    break;
                case 'negado':
                    $where['sol.stEstado = ?'] = 2; // Negado
                    $this->view->nmPagina = 'Negado';
                    break;
            }
        } else {
            $where['sol.stEstado = ?'] = 1; // Atendido
            $this->view->nmPagina = 'Atendido';
        }

        //die('w');
        $tbSolicitarItem = new Proposta_Model_DbTable_tbSolicitarItem();
        $total = $tbSolicitarItem->buscarItens($where, $order, null, null, true);
        $fim = $inicio + $this->intTamPag;

        $totalPag = (int)(($total % $this->intTamPag == 0)?($total/$this->intTamPag):(($total/$this->intTamPag)+1));
        $tamanho = ($fim > $total) ? $total - $inicio : $this->intTamPag;

        $busca = $tbSolicitarItem->buscarItens($where, $order, $tamanho, $inicio);
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

        $tbTitulacaoConselheiro = new tbTitulacaoConselheiro();
        $this->view->conselheiros = $tbTitulacaoConselheiro->buscarConselheirosTitulares();

        $this->view->paginacao     = $paginacao;
        $this->view->qtdRegistros  = $total;
        $this->view->dados         = $busca;
        $this->view->intTamPag     = $this->intTamPag;

        $tableManterTabelaItens = new Proposta_Model_DbTable_tbSolicitarItem();
        echo '<pre>';
        var_dump($this->getIdUsuario);
        var_dump($this->idUsuario);
        exit;
        $tbsolicitacao = $tableManterTabelaItens->solicitacoes($this->idUsuario);
        $this->view->solicitacao = $tbsolicitacao;
    }

    /**
     * imprimirMinhasSolicitacoesAction
     *
     * @access public
     * @return void
     */
    public function imprimirMinhasSolicitacoesAction() {
        $this->intTamPag = 10;

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

        } else {
            $campo = null;
            $order = array(2,4,6);
            $ordenacao = null;
        }

        $pag = 1;
        $get = Zend_Registry::get('get');
        $this->view->idPreProjeto = $get->idPreProjeto;

        if (isset($get->pag)) $pag = $get->pag;
        $inicio = ($pag>1) ? ($pag-1)*$this->intTamPag : 0;

        /* ================== PAGINACAO ======================*/
        $auth = Zend_Auth::getInstance(); // pega a autenticação
        $where = array();
        $where['sol.idAgente = ?'] = $arrAuth['idusuario'];
        $where['sol.stEstado = ?'] = 1; // Atendido

        if(isset($_POST['tipoFiltro']) || isset($_GET['tipoFiltro'])){
            $filtro = isset($_POST['tipoFiltro']) ? $_POST['tipoFiltro'] : $_GET['tipoFiltro'];
            $this->view->filtro = $filtro;
            switch ($filtro) {
                case '':
                    $where['sol.stEstado = ?'] = 1; // Atendido
                    $this->view->nmPagina = 'Atendido';
                    break;
                case 'solicitado':
                    $where['sol.stEstado = ?'] = 0; // Solicitado
                    $this->view->nmPagina = 'Solicitado';
                    break;
                case 'negado':
                    $where['sol.stEstado = ?'] = 2; // Negado
                    $this->view->nmPagina = 'Negado';
                    break;
            }
        } else {
            $where['sol.stEstado = ?'] = 1; // Atendido
            $this->view->nmPagina = 'Atendido';
        }

        $tbSolicitarItem = new Proposta_Model_DbTable_tbSolicitarItem();
        $total = $tbSolicitarItem->buscarItens($where, $order, null, null, true);
        $fim = $inicio + $this->intTamPag;

        $totalPag = (int)(($total % $this->intTamPag == 0)?($total/$this->intTamPag):(($total/$this->intTamPag)+1));
        $tamanho = ($fim > $total) ? $total - $inicio : $this->intTamPag;

        $busca = $tbSolicitarItem->buscarItens($where, $order, $tamanho, $inicio);

        $this->view->qtdRegistros = $total;
        $this->view->dados = $busca;
        $this->_helper->layout->disableLayout(); // Desabilita o Zend Layout

    }

    /**
     * associaritemAction
     *
     * @access public
     * @return void
     */
    public function associaritemAction() {

    }

    /**
     * cadastraritemAction
     *
     * @access public
     * @return void
     */
    public function cadastraritemAction() {

    }

    /**
     * solicitacoesAction
     *
     * @access public
     * @return void
     */
    public function solicitacoesAction() {
        $tableManterTabelaItens = new Proposta_Model_DbTable_tbSolicitarItem();

        $tbsolicitacao = $tableManterTabelaItens->solicitacoes($this->idUsuario);
        $this->view->solicitacao = $tbsolicitacao;

    	if($_POST)
        {
        	$tbsolicitacaos = $tableManterTabelaItens->solicitacoes($this->idUsuario);

        	$html = '<table class="tabela">
			            <tr>
			                <th colspan="6" >Minhas Solicita&ccedil;&otilde;es</th>
			            </tr>
			            <tr>
			                <td><b>Produto</b></td>
			                <td><b>Etapa</b></td>
			                <td><b>Item Solicitado</b></td>
			                <td><b>Justificativa</b></td>
			                <td><b>Estado</b></td>
			                <td><b>Resposta</b></td>
			            </tr>';

			            foreach($tbsolicitacaos as $tbsolicitacao):
			$html .= 	'<tr>
			                <td>' . $tbsolicitacao->Produto . '</td>
			                <td>' . $tbsolicitacao->Etapa . '</td>
			                <td>' . $tbsolicitacao->ItemSolicitado . '</td>
			                <td>' . $tbsolicitacao->Justificativa .' </td>
			                <td>' . $tbsolicitacao->Estado . '</td>
			                <td>' . $tbsolicitacao->Resposta . '</td>
			            </tr>';
			            endforeach;
			$html .= '</table>';

			$this->_helper->layout->disableLayout();
	        $this->_helper->viewRenderer->setNoRender();

	        $pdf = new PDF($html, 'pdf');
	        $pdf->gerarRelatorio();
        }
    }

    /**
     * exibirdadosAction
     *
     * @access public
     * @return void
     */
    public function exibirdadosAction() {
        $tableManterTabelaItens = new Proposta_Model_DbTable_tbSolicitarItem();

        if ($this->getRequest()->isPost()) {
            // recebe os dados via post
            $post   = Zend_Registry::get('post');
            $tipoPesquisa   = $post->TipoPesquisa;
            $item           = $post->NomeDoItem;
            $etapa          = $post->etapa;
            $produto        = $post->produto;

            $this->view->tipopesquisa 	= $post->TipoPesquisa;
            $this->view->produto 		= $post->produto;
            $this->view->etapa 			= $post->etapa;
            $this->view->item 			= $post->NomeDoItem;

            //CODIGO ANTIGO
            //$tbpretitem = Mantertabelaitens::exibirprodutoetapaitem($item);
            $where = null;
            if($tipoPesquisa==1) {
                $where = " LIKE '%".$item."%'";
            }elseif($tipoPesquisa==2) {
                $where = " LIKE '%".$item."'";
            }elseif($tipoPesquisa==3) {
                $where = " = '".$item."'";
            }elseif($tipoPesquisa==4) {
                $where = " <> '%".$item."'";
            }

            $tbpretitem = $tableManterTabelaItens->exibirprodutoetapaitem($item=null,$where,$etapa,$produto);

            $this->view->pretitem = $tbpretitem;

            try
            {
                if($tbpretitem)
                {
                }
                else
                {
                    throw new Exception("Dados n&atilde;o localizados");
                }

            }catch (Exception $e)
            {
                parent::message($e->getMessage(), "mantertabelaitens/exibirdados?idPreProjeto=".$this->idPreProjeto, "ERROR");
            }
        }
    }

	/**
	 * buscaetapasAction
	 *
	 * @access public
	 * @return void
	 */
	public function buscaetapasAction()
	{
		$this->_helper->layout->disableLayout();

		$idProduto = $_POST['idProduto'];

        $tableManterTabelaItens = new Proposta_Model_DbTable_tbSolicitarItem();
		$this->view->etapas = $tableManterTabelaItens->exibirEtapa($idProduto);

		$this->view->idProduto = $idProduto;
	}

    /**
     * buscaitensAction
     *
     * @access public
     * @return void
     * @author wouerner <wouerner@gmail.com>
     */
    public function buscaitensAction()
    {        $tableManterTabelaItens = new Proposta_Model_DbTable_tbSolicitarItem();

        $this->_helper->layout->disableLayout();

        $idProduto 	= $_POST['idProduto'];
        $idEtapa 	= $_POST['idEtapa'];

        $this->view->itens = $tableManterTabelaItens->exibirItem($idProduto, $idEtapa);
        $this->view->idProduto = $idProduto;
        $this->view->idEtapa = $idEtapa;
    }

    /**
     * consultartabelaitensAction
     *
     * @access public
     * @return void
     */
    public function consultartabelaitensAction() {
        $post = Zend_Registry::get('post');

        $tableManterTabelaItens = new Proposta_Model_DbTable_tbSolicitarItem();

        $item = $post->NomeDoItem;

        $tbpretitem = $tableManterTabelaItens->exibirprodutoetapaitem($item);
        $this->view->pretitem = $tbpretitem;

        $tbproduto = $tableManterTabelaItens->buscaproduto();
        $this->view->produto = $tbproduto;

        $tbetapa = $tableManterTabelaItens->buscaetapa();
        $this->view->etapa = $tbetapa;

        $tbitem = $tableManterTabelaItens->buscaitem();
        $this->view->ites = $tbitem;

        $buscardados =  $tableManterTabelaItens->buscaprodutoetapaitem();
        $this->view->buscardados = $buscardados;
    }

    /**
     * imprimirAction
     *
     * @access public
     * @return void
     */
    public function imprimirAction(){

        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        $post = Zend_Registry::get('post');

        if(empty($post->tipoPesquisa) && empty($post->item) && empty($post->etapa) && empty($post->produto)){
            $this->_redirect("mantertabelaitens/exibirdados?idPreProjeto=".$this->idPreProjeto);
        }
        $tipoPesquisa   = $post->tipoPesquisa;
        $item           = $post->item;
        $etapa          = $post->etapa;
        $produto        = $post->produto;

        $where = null;
        if($tipoPesquisa==1) {
            $where = " LIKE '%".$item."%'";
        }elseif($tipoPesquisa==2) {
            $where = " LIKE '%".$item."'";
        }elseif($tipoPesquisa==3) {
            $where = " = '".$item."'";
        }elseif($tipoPesquisa==4) {
            $where = " <> '%".$item."'";
        }
        $tableManterTabelaItens = new Proposta_Model_DbTable_tbSolicitarItem();
        $tbpretitem = $tableManterTabelaItens->exibirprodutoetapaitem($item=null,$where,$etapa,$produto);

        $arr = array();
        $arrNomeProduto = array();
        foreach($tbpretitem as $item){
            $arr[$item->idProduto][$item->idEtapa][] = $item;
            $arrNomeProduto[$item->idProduto] = $item->Produto;

        }

        $html = '
                <table width="100%" style="font-family:Arial">
                    <tr>
                        <th style="font-size:20px;" colspan="3">
                            Relatório Produto/Etapa/Item
                        </th>
                    </tr>
                ';

        if(!empty($arr)) {
            $ct=0;
            foreach($arr as $chaveProduto=>$Produto) {

                $html .= '
                            <tr>
                                <td colspan="3" align="left" style="background-color: #cccccc; font-size:14px; font-weight:bold;">
                                    Produto : '.$arrNomeProduto[$chaveProduto].'
                                </td>
                            </tr>';
                foreach($Produto as $chaveEtapa=>$Etapa) {
                    $html .= '
                                <tr>
                                    <td width="25px">  </td>
                                    <td colspan="2" align="left" style="background-color: #EFEFEF; font-size:14px; font-weight:bold;">
                                        Etapa: '.$arr[$chaveProduto][$chaveEtapa][0]->Etapa.'
                                    </td>
                                </tr>';

                    foreach($Etapa as $Item) {
                        $html .= '
                                        <tr>
                                        <td width="25px">  </td>
                                        <td width="25px">  </td>
                                        <td align="left">
                                                Item: '.$Item->NomeDoItem.'
                                            </td>
                                        </tr>';
                    }
                }
            }
            $html .= '</table>';
            $ct++;
        }


        try{
            //echo $html; die;
            $pdf = new PDF($html, 'pdf');
            $pdf->gerarRelatorio();
        }catch(Exception $e){
            xd($e->getMessage());
        }
    }

    /**
     * gerarpdfAction
     *
     * @access public
     * @return void
     * @todo retirar html
     */
    public function gerarpdfAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();

        $output = '
			<style>
				th{
                                background:#ABDA5D;
                                color:#3A7300;
                                text-transform:uppercase;
                                font-size:14px;
        			font-weight: bold;
        			font-family: sans-serif;
        			height: 16px;
        			line-height: 16px;
                                border:1px #ccc solid;
        		}
        		td{
        			color:#000;
        			font-size:14px;
        			font-family: sans-serif;
        			height: 14px;
        			line-height: 14px;
                                border:1px #ccc solid;
        		}
        		.blue{
        			color: blue;
        		}
        		.red{
        			color: red;
        		}
        		.orange{
        			color: orange;
        		}
        		.green{
        			color: green;
        		}

        		.direita{
        			text-align: right;
        		}

        		.centro{
        			text-align: center;
        		}

			</style>';

        $output .= $_POST['html'];

        $patterns = array();
        $patterns[] = '/<table.*?>/is';
        $patterns[] = '/<thead>/is';
        $patterns[] = '/<\/thead>/is';
        $patterns[] = '/<tbody>/is';
        $patterns[] = '/<\/tbody>/is';
        $patterns[] = '/<col.*?>/is';
        $patterns[] = '/<a.*?>/is';

        $replaces = array();
        $replaces[] = '<table cellpadding="0" cellspacing="1" border="1" width="97%" align="center">';
        $replaces[] = '';
        $replaces[] = '';
        $replaces[] = '';
        $replaces[] = '';
        $replaces[] = '';
        $replaces[] = '';

        $output = preg_replace($patterns,$replaces,$output);

        $pdf = new PDF($output, 'pdf');
        $pdf->gerarRelatorio('h');
    }
}
