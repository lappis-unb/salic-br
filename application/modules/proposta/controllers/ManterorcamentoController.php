<?php
/**
 * @since 07/06/2010
 * @link http://www.cultura.gov.br
 */

class Proposta_ManterorcamentoController extends MinC_Controller_Action_Abstract
{
    private $idUsuario = null;
    private $idPreProjeto = null;

    /**
     * Reescreve o metodo init()
     * @access public
     * @param void
     * @return void
     */
    public function init()
    {
        $idPreProjeto = $this->getRequest()->getParam('idPreProjeto');

        $this->view->title = "Salic - Sistema de Apoio às Leis de Incentivo à Cultura"; // t?tulo da p?gina
        $auth = Zend_Auth::getInstance(); // pega a autentica??o
        $PermissoesGrupo = array();
        if (!$auth->hasIdentity()) // caso o usu?rio esteja autenticado
        {
             return $this->_helper->redirector->goToRoute(array('controller' => 'index', 'action' => 'logout'), null, true);
        }

        //Da permissao de acesso a todos os grupos do usuario logado afim de atender o UC75
        if(isset($auth->getIdentity()->usu_codigo)){
            //Recupera todos os grupos do Usuario
            $Usuario = new Autenticacao_Model_Usuario(); // objeto usu?rio
            $grupos = $Usuario->buscarUnidades($auth->getIdentity()->usu_codigo, 21);
            foreach ($grupos as $grupo){
                $PermissoesGrupo[] = $grupo->gru_codigo;
            }
        }

        isset($auth->getIdentity()->usu_codigo) ? parent::perfil(1, $PermissoesGrupo) : parent::perfil(4, $PermissoesGrupo);
        parent::init(); // chama o init() do pai GenericControllerNew

        //recupera ID do pre projeto (proposta)
        if(!empty ($idPreProjeto)) {
            $this->idPreProjeto = $idPreProjeto;
            $this->view->idPreProjeto = $idPreProjeto;

            //VERIFICA SE A PROPOSTA ESTA COM O MINC
            $Movimentacao = new Proposta_Model_DbTable_TbMovimentacao();
            $rsStatusAtual = $Movimentacao->buscarStatusAtualProposta($idPreProjeto);
            //var_dump($rsStatusAtual);die;
            $this->view->movimentacaoAtual = isset($rsStatusAtual['Movimentacao']) ? $rsStatusAtual['Movimentacao'] : '';
        }else {
            if($idPreProjeto != '0'){
                parent::message("Necessário informar o número da proposta.", "/proposta/manterpropostaincentivofiscal/index", "ERROR");
            }
        }
        $this->idUsuario = !empty($auth->getIdentity()->usu_codigo) ? $auth->getIdentity()->usu_codigo : $auth->getIdentity()->IdUsuario;
        /* =============================================================================== */
        /* ==== VERIFICA PERMISSAO DE ACESSO DO PROPONENTE A PROPOSTA OU AO PROJETO ====== */
        /* =============================================================================== */
        $this->verificarPermissaoAcesso(true, false, false);
    }

    /**
     * Redireciona para o fluxo inicial do sistema
     * @access public
     * @param void
     * @return void
     */
    public function indexAction() {
        // Usuario Logado
        $auth = Zend_Auth::getInstance(); // instancia da autenticacao
        $idusuario = $auth->getIdentity()->usu_codigo;

        $GrupoAtivo = new Zend_Session_Namespace('GrupoAtivo'); // cria a sessao com o grupo ativo
        $codOrgao = $GrupoAtivo->codOrgao; //  orgao ativo na sessao

        $this->view->codOrgao = $codOrgao;
        $this->view->idUsuarioLogado = $idusuario;
    }

    /**
     * produtoscadastradosAction
     *
     * @name produtoscadastradosAction
     */
    public function produtoscadastradosAction()
    {
        $buscarEstado = new Agente_Model_DbTable_UF();
        $this->view->Estados = $buscarEstado->buscar();

        $tbPreprojeto = new Proposta_Model_DbTable_PreProjeto();
        $this->view->Produtos = $tbPreprojeto->listarProdutos($this->idPreProjeto);

        $manterOrcamento = new Proposta_Model_DbTable_TbPlanilhaEtapa();

        $listaEtapa = $manterOrcamento->buscarEtapas('P');

        //altera ordem de apresenção das etapas no orçcamento
        $newListaEtapa = $listaEtapa;
        $newListaEtapa[3] = $listaEtapa[2];
        $newListaEtapa[2] = $listaEtapa[3];
        $this->view->Etapa = $newListaEtapa;

        $this->view->Item = $tbPreprojeto->listarItensProdutos($this->idPreProjeto);

        $this->view->EtapaCusto = $manterOrcamento->buscarEtapas("A");
        $this->view->ItensEtapaCusto = $manterOrcamento->listarItensCustosAdministrativos($this->idPreProjeto, "A");

        $arrBusca = array();
        $arrBusca['idprojeto'] = $this->idPreProjeto;
        $arrBusca['stabrangencia'] = 1;
        $tblAbrangencia = new Proposta_Model_DbTable_Abrangencia();
        $this->view->localRealizacao = $tblAbrangencia->buscar($arrBusca);

        $this->view->idPreProjeto = $this->idPreProjeto;

        $this->view->charset = Zend_Registry::get('config')->db->params->charset;
    }


    public function listarprodutosAction()
    {
        $this->_helper->layout->disableLayout();

        $buscarEstado = new Agente_Model_DbTable_UF();
        $this->view->Estados = $buscarEstado->buscar();

        $tbPreprojeto = new Proposta_Model_DbTable_PreProjeto();
        $this->view->Produtos = $tbPreprojeto->listarProdutos($this->idPreProjeto);

        $manterOrcamento = new Proposta_Model_DbTable_TbPlanilhaEtapa();

        $listaEtapa = $manterOrcamento->buscarEtapas('P');

        //altera ordem de apresenção das etapas no orçcamento
        $newListaEtapa = $listaEtapa;
        $newListaEtapa[3] = $listaEtapa[2];
        $newListaEtapa[2] = $listaEtapa[3];
        $this->view->Etapa = $newListaEtapa;

        $this->view->Item = $tbPreprojeto->listarItensProdutos($this->idPreProjeto);

        $this->view->EtapaCusto = $manterOrcamento->buscarEtapas("A");
        $this->view->ItensEtapaCusto = $manterOrcamento->listarItensCustosAdministrativos($this->idPreProjeto, "A");

        $arrBusca = array();
        $arrBusca['idprojeto'] = $this->idPreProjeto;
        $arrBusca['stabrangencia'] = 1;
        $tblAbrangencia = new Proposta_Model_DbTable_Abrangencia();
        $this->view->localRealizacao = $tblAbrangencia->buscar($arrBusca);

        $this->view->idPreProjeto = $this->idPreProjeto;

        $this->view->charset = Zend_Registry::get('config')->db->params->charset;
    }

    /**
     * custosadministrativosAction
     *
     * @access public
     * @return void
     * @deprecated Custos administrativos foi desativado em Proposta em 23/11/2016
     */
    public function custosadministrativosAction()
    {
        $manterOrcamento = new Proposta_Model_DbTable_TbPlanilhaEtapa();
        $this->view->Etapas = $manterOrcamento->listarCustosAdministrativos();
        $this->view->EtapaCusto = $manterOrcamento->listarItensCustosAdministrativos($this->idPreProjeto, "A");
        $this->view->dados = $manterOrcamento->listarDadosCadastrarCustos($this->idPreProjeto);
        $buscarEstado = new Agente_Model_DbTable_UF();
        $this->view->Estados = $buscarEstado->listar();
        $this->view->Etapa = $manterOrcamento->listarEtapasCusto();
        $this->view->idPreProjeto = $this->idPreProjeto;
    }

    /**
     * planilhaorcamentariaAction
     *
     *
     * @access public
     * @return void
     */
    public function planilhaorcamentariaAction() {
        $this->view->idPreProjeto = $this->idPreProjeto;
    }

    /**
     * planilhaorcamentariageralAction
     *
     * @access public
     * @return void
     */
    public function planilhaorcamentariageralAction(){
        $this->view->tipoPlanilha = 0; // 0=Planilha Or?ament?ria da Proposta
    }

    /**
     * consultarcomponenteAction
     *
     * @access public
     * @return void
     */
    public function consultarcomponenteAction() {
        $this->_helper->layout->disableLayout(); // desabilita o layout


        if(!empty($this->idPreProjeto) || $this->idPreProjeto=='0') {
            $uf = new Agente_Model_DbTable_UF();
            $this->view->Estados = $uf->buscar();

            $buscarProduto = new Proposta_Model_DbTable_PreProjeto();
            $this->view->Produtos = $buscarProduto->buscarProdutos($this->idPreProjeto);

            $buscarEtapa = ManterorcamentoDAO::buscarEtapasProdutos($this->idPreProjeto);
            $this->view->Etapa = $buscarEtapa;

            $buscarItem = ManterorcamentoDAO::buscarItensProdutos($this->idPreProjeto);
            $this->view->Item = $buscarItem;

            $buscarPlanilhaProduto = ManterorcamentoDAO::buscarPlanilhaOrcamentariaP($this->idPreProjeto);
            $this->view->PlanilhaProduto = $buscarPlanilhaProduto;

            $buscarPlanilhaCustos = ManterorcamentoDAO::buscarPlanilhaOrcamentariaC($this->idPreProjeto);
            $this->view->PlanilhaCusto = $buscarPlanilhaCustos;

            $buscarPlanilha = ManterorcamentoDAO::buscarPlanilha($this->idPreProjeto);
            $this->view->Planilha = $buscarPlanilha;

            $buscarPlanilhaEtapa = ManterorcamentoDAO::buscarPlanilhaEtapa($this->idPreProjeto);
            $this->view->PlanilhaEtapa = $buscarPlanilhaEtapa;

            $this->view->idPreProjeto = $this->idPreProjeto;}

        else {
            return false;
        }
    }

    /**
     * cadastrarprodutosAction
     *
     * @access public
     * @return void
     */
    public function cadastrarprodutosAction() {
        $this->_helper->layout->disableLayout();

        $this->view->idPreProjeto = $this->idPreProjeto;

        if  ( isset ( $_GET['idPreProjeto'] ) && isset ( $_GET['produto'] )) {
            $idPreProjeto = $_GET['idPreProjeto'];
            $idProduto = $_GET['produto'];
            $TDP = new Proposta_Model_DbTable_PlanoDistribuicaoProduto();
            $this->view->Dados = $TDP->buscarDadosCadastrarProdutos($idPreProjeto, $idProduto);
            $this->view->idProduto = $idProduto;
        }

        if(isset($_POST['iduf'])) {
            $this->_helper->layout->disableLayout(); // desabilita o Zend_Layout
            $iduf = $_POST['iduf'];

//            $cidade = CidadeDAO::buscar($iduf);
            $tbMun= new Agente_Model_DbTable_Municipios();
            $cidade = $tbMun->listar($iduf);

            $a = 0;
            foreach($cidade as $DadosCidade) {
                $cidadeArray[$a]['idCidade'] = $DadosCidade->id;
                $cidadeArray[$a]['nomeCidade'] = utf8_encode($DadosCidade->Descricao);
                $a++;
            }
            echo json_encode($cidadeArray);
            die;
        }

        if(isset($_POST['idetapa'])) {
            $this->_helper->layout->disableLayout(); // desabilita o Zend_Layout
            $idetapa = $_POST['idetapa'];
            $idProduto = $_POST['idProduto'];

//            $item = ManterorcamentoDAO::buscarItens($idetapa,$idProduto);
            $itensPlanilhaProduto = new tbItensPlanilhaProduto();
            $item = $itensPlanilhaProduto->buscarItens($idetapa, $idProduto);

            if (count($item) <= 0) { $item = $itensPlanilhaProduto->buscarItens($idetapa,null); }
            $a = 0;
            foreach($item as $Dadositem) {
                $itemArray[$a]['idItem'] = $Dadositem->idPlanilhaItens;
                $itemArray[$a]['nomeItem'] = utf8_encode($Dadositem->Descricao);
                $a++;
            }
            echo json_encode($itemArray);
            die;
        }

        $etapaSelecionada["id"] = $_GET["etapa"];
        $etapaSelecionada["etapaNome"] = $_GET["etapaNome"];
        $this->view->etapaSelecionada = $etapaSelecionada;

        $uf = new Agente_Model_DbTable_UF();
        $buscarEstado = $uf->buscar();
        $this->view->Estados = $buscarEstado;

//        $buscarEtapa = ManterorcamentoDAO::buscarEtapasCadastrarProdutos();
        $buscarEtapa = new Proposta_Model_DbTable_TbPlanilhaEtapa();
        $this->view->Etapa = $buscarEtapa->buscarEtapasCadastrarProdutos();

//        $buscarRecurso = ManterorcamentoDAO::buscarFonteRecurso();
        $buscarRecurso = new Proposta_Model_DbTable_Verificacao();
        $this->view->Recurso = $buscarRecurso->buscarFonteRecurso();

        $buscarUnidade = new Proposta_Model_DbTable_PlanilhaUnidade();
        $this->view->Unidade = $buscarUnidade->buscarUnidade();

//        $buscarItem = ManterorcamentoDAO::buscarItensProdutos($this->idPreProjeto);
        $buscarItem = new Proposta_Model_DbTable_PreProjeto();
        $this->view->Item = $buscarItem->listarItensProdutos($this->idPreProjeto);
//        echo '<pre>';
//        var_dump( $buscarItem->listarItensProdutos($this->idPreProjeto));
//        exit;
//        $buscarProduto = ManterorcamentoDAO::buscarProdutos($this->idPreProjeto);
        $buscarProduto = new Proposta_Model_DbTable_PreProjeto();
        $this->view->Produtos = $buscarProduto->buscarProdutos($this->idPreProjeto);

    }

    /**
     * formitemAction
     *
     * @access public
     * @return void
     */
    public function formitemAction() {

        $this->_helper->layout->disableLayout();

        $this->view->idPreProjeto = $this->idPreProjeto;
        $params = $this->getRequest()->getParams();

        $idPreProjeto   = $params['idPreProjeto'];
        $idProduto      = $params['produto'];
        $iduf           = $params['idUf'];
        $idMunicipio    = $params['idMunicipio'];
        $etapa          = $params['etapa'];

        if( !empty( $params['idPlanilhaProposta']) ) {

            $idProposta = $params['idPreProjeto'];
            $idEtapa = $params['etapa'];
            $idProduto = $params['produto'];
            $idItem = $params['item'];
            $idPlanilhaProposta = $params['idPlanilhaProposta'];
            $tblanilhaProposta = new Proposta_Model_DbTable_TbPlanilhaProposta();
            $buscaDados = $tblanilhaProposta->buscarDadosEditarProdutos($idProposta, $idEtapa, $idProduto, $idItem, $idPlanilhaProposta);

            $this->view->Dados = $buscaDados;

        } else {
            if( !empty( $idPreProjeto ) && !empty( $idProduto ) ) {
                $TDP = new Proposta_Model_DbTable_PlanoDistribuicaoProduto();
                $this->view->Dados = $TDP->buscarDadosCadastrarProdutos($idPreProjeto, $idProduto);
                $this->view->idProduto = $idProduto;
            }
        }

        $uf = new Agente_Model_DbTable_UF();
        $estado = $uf->findBy(array("iduf" => $iduf));
        $this->view->Estado = $estado;

        $mun = new Agente_Model_DbTable_Municipios();
        $municipio = $mun->findBy(array("idMunicipioIBGE" => $idMunicipio));
        $this->view->Municipio = $municipio;

        $buscarEtapa = new Proposta_Model_DbTable_TbPlanilhaEtapa();
        $this->view->Etapa = $buscarEtapa->findBy(array('idPlanilhaEtapa' => $etapa));

        $buscarRecurso = new Proposta_Model_DbTable_Verificacao();
        $this->view->Recurso = $buscarRecurso->buscarFonteRecurso();

        $buscarUnidade = new Proposta_Model_DbTable_PlanilhaUnidade();
        $this->view->Unidade = $buscarUnidade->buscarUnidade();

        $itensPlanilhaProduto = new tbItensPlanilhaProduto();
        $this->view->Item = $itensPlanilhaProduto->buscarItens($etapa, $idProduto);

        $buscarProduto = new Proposta_Model_DbTable_PreProjeto();
        $this->view->Produtos = $buscarProduto->buscarProdutos($this->idPreProjeto);

    }

    /**
     * salvaritemaction
     *
     * @access public
     * @return void
     */
    public function salvaritemAction() {

        $this->_helper->layout->disableLayout();
        $params = $this->getRequest()->getParams();

        $idPreProjeto = $params['idPreProjeto'];

        $justificativa = utf8_decode(substr(trim(strip_tags($params['editor1'])),0,500));

        $dados = array(
            'idProjeto' => $idPreProjeto,
            'idProduto' => $params['produto'],
            'idEtapa' => $params['idPlanilhaEtapa'],
            'idPlanilhaItem' => $params['item'],
            'Descricao' => '',
            'Unidade' => $params['unidade'],
            'Quantidade' => $params['qtd'],
            'Ocorrencia' => $params['ocorrencia'],
            'ValorUnitario' => str_replace(",", ".",  str_replace(".", "",  $params['vlunitario'])),
            'QtdeDias' => $params['qtdDias'],
            'TipoDespesa' => 0,
            'TipoPessoa' => 0,
            'Contrapartida' => 0,
            'FonteRecurso' => $params['fonterecurso'],
            'UfDespesa' => $params['uf'],
            'MunicipioDespesa' => $params['municipio'],
            'dsJustificativa' =>  substr($justificativa,0,450),
            'idUsuario' => $this->idUsuario
        );

        $tbPlanilhaProposta = new Proposta_Model_DbTable_TbPlanilhaProposta();

        if( empty( $params['idPlanilhaProposta'] ) ) {

            $buscarProdutos = $tbPlanilhaProposta->buscarDadosEditarProdutos($idPreProjeto, $params['etapa'], $params['produto'], $params['item'], null, $params['uf'], $params['municipio']);

            if($buscarProdutos){
                $retorno['msg'] = "Cadastro duplicado de Produto na mesma etapa envolvendo o mesmo item, transa&ccedil;&atilde;o cancelada!";
                $retorno['pergunta'] = true;
                $retorno['status'] = false;

            }else{
                $result = $tbPlanilhaProposta->insert($dados);

                if( $result )
                    $this->salvarcustosvinculados($idPreProjeto);

                $retorno['msg'] = "Item cadastrado com sucesso.";
                $retorno['pergunta'] = true;
                $retorno['status'] = true;
            }
        } else {


            if( isset($params['produto'] ) ) {

                $where = "idPlanilhaProposta = ".$params['idPlanilhaProposta'];

                $tbPlanilhaProposta->update($dados, $where);

                $this->salvarcustosvinculados($idPreProjeto);

                $retorno['msg'] = "Altera&ccedil;&atilde;o realizada com sucesso!";
                $retorno['pergunta'] = true;
                $retorno['status'] = true;

            }
        }

        echo json_encode($retorno);
        die;

    }

    /**
     * cadastrarcustosAction
     *
     * @access public
     * @return void
     * @deprecated Custos administrativos foi desativado em Proposta em 23/11/2016
     */
    public function cadastrarcustosAction() {
        $this->_helper->layout->disableLayout();

        # Forcando o charset conforme o application.ini
        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV);
        $this->view->charset = $config->resources->db->params->charset;

        if  ( isset ( $_GET['idPreProjeto'] ) ) {
            $idPreProjeto = $_GET['idPreProjeto'];

            $manterOrcamento = new Proposta_Model_DbTable_TbPlanilhaProposta();
            $buscaDados = $manterOrcamento->findBy(array('idProjeto' => $idPreProjeto));
//            $buscaDados = $manterOrcamento->buscarDadosCadastrarCustos($idPreProjeto);
            $this->view->dados = $buscaDados;

            $buscaDados = new Proposta_Model_DbTable_PlanilhaProposta();

            $this->view->dados = $buscaDados->buscarDadosCadastrarCustos($idPreProjeto);
        }

        if( isset( $_GET['cadastro'] ) ) {
            $dados_cadastrados = ManterorcamentoDAO::buscarUltimosDadosCadastrados();
            $this->view->dados_cadastrados = $dados_cadastrados;

            $mun = new Agente_Model_DbTable_Municipios();
            $cidade = $mun->listar($dados_cadastrados[0]['UfDespesa']);
            $this->view->municipios = $cidade;

            $itens = new tbItensPlanilhaProduto();
            $this->view->item = $itens->buscarItens($dados_cadastrados[0]['idEtapa']);

        } else {
            $this->view->dados_cadastrados = array();
        }

        if(isset($_POST['iduf'])) {
            $this->_helper->layout->disableLayout(); // desabilita o Zend_Layout
            $iduf = $_POST['iduf'];

             $tbMun= new Agente_Model_DbTable_Municipios();
            $cidade = $tbMun->listar($iduf);
            $a = 0;
            foreach($cidade as $DadosCidade) {
                $cidadeArray[$a]['idCidade'] = $DadosCidade->id;
                $cidadeArray[$a]['nomeCidade'] = utf8_encode($DadosCidade->Descricao);
                $a++;
            }
            echo json_encode($cidadeArray);
            die;
        }

        if(isset($_POST['idetapa'])) {
        	$this->_helper->layout->disableLayout(); // desabilita o Zend_Layout
            $idetapa = $_POST['idetapa'];
            $item = new tbItensPlanilhaProduto();
            $item = $item->buscarItens($idetapa);
            $a = 0;
            foreach($item as $Dadositem) {
                $itemArray[$a]['idItem'] = $Dadositem->idplanilhaitens;
                $itemArray[$a]['nomeItem'] = utf8_encode($Dadositem->Descricao);
                $a++;
            }
            echo json_encode($itemArray);
            die;
        }

        $etapaSelecionada["id"] = $_GET["etapa"];
        $etapaSelecionada["etapaNome"] = $_GET["etapaNome"];
        $this->view->etapaSelecionada = $etapaSelecionada;

        $buscarEstado = new Agente_Model_DbTable_UF();
        $this->view->Estados = $buscarEstado->buscar();

        $buscarEtapa = new Proposta_Model_DbTable_TbPlanilhaEtapa();
        $this->view->Etapa = $buscarEtapa->buscarEtapasCusto();

        $buscarRecurso = new Proposta_Model_DbTable_Verificacao();
        $this->view->Recurso = $buscarRecurso->buscarFonteRecurso();

        $buscarUnidade = new Proposta_Model_DbTable_TbPlanilhaUnidade();
        $this->view->Unidade = $buscarUnidade->buscarUnidade();

        $this->view->idPreProjeto = $this->idPreProjeto;
    }

    public function editarprodutosAction () {

        $this->_helper->layout->disableLayout(); // desabilita o Zend_Layout
        $tblanilhaProposta = new Proposta_Model_DbTable_TbPlanilhaProposta();
        $mun = new Agente_Model_DbTable_Municipios();

        if(isset($_POST['produto'])) {

            $idProposta = $_POST['proposta'];
            $idProduto = $_POST['produto'];
            $idUf = $_POST['uf'];
            $municipio = $_POST['municipio'];
            $idEtapa = $_POST['etapa'];
            $idItem = $_POST['item'];
            $unidade = $_POST['unidade'];
            $qtd = $_POST['qtd'];
            $ocorrencia = $_POST['ocorrencia'];
            $valor = str_replace(",",".",str_replace(".","",$_POST['vlunitario']));
            $qtdDias = $_POST['qtdDias'];
            $fonte = $_POST['fonterecurso'];

            $dados = array(
                    'idetapa' =>$_POST['etapa'],
                    'idplanilhaitem' =>$_POST['item'],
                    'unidade' =>$_POST['unidade'],
                    'quantidade' =>$_POST['qtd'],
                    'ocorrencia' =>$_POST['ocorrencia'],
                    'valorunitario' => str_replace(",",".",str_replace(".","",$_POST['vlunitario'])),
                    'qtdedias' =>$_POST['qtdDias'],
                    'fonterecurso' =>$_POST['fonterecurso'],
                    'ufdespesa' =>$_POST['uf'],
                    'municipiodespesa' =>$_POST['municipio'],
                    'dsjustificativa' =>$_POST['editor1']
            );

            $where = "idPlanilhaProposta = ".$_POST['proposta'];

            $buscarProdutos = $tblanilhaProposta->buscarDadosEditarProdutos(null, $idEtapa, $idProduto, $idItem, null, $idUf, $municipio);


            $tblanilhaProposta->editarPlanilhaProdutos($dados, $where);

            $this->salvarcustosvinculados($_POST['idPreProjeto']);

            $this->_helper->layout->disableLayout();
            echo "Altera&ccedil;&atilde;o realizada com sucesso!";
            die;
        }

        if  ( !empty( $_GET ) ) {

            $idProposta = $_GET['idPreProjeto'];
            $idEtapa = $_GET['etapa'];
            $idProduto = $_GET['produto'];
            $idItem = $_GET['item'];
            $idPlanilhaProposta = $_GET['idPlanilhaProposta'];

            //$buscaDados = ManterorcamentoDAO::buscarDadosEditarProdutos($idProposta, $idEtapa, $idProduto, $idItem, $idPlanilhaProposta);
            $buscaDados = $tblanilhaProposta->buscarDadosEditarProdutos($idProposta, $idEtapa, $idProduto, $idItem, $idPlanilhaProposta);
            $this->view->Dados = $buscaDados;
        }

        if(isset($_POST['iduf'])) {
            $this->_helper->layout->disableLayout(); // desabilita o Zend_Layout
            $iduf = $_POST['iduf'];

            $cidade = $mun->buscar($iduf);

            $a = 0;
            foreach($cidade as $DadosCidade) {
                $cidadeArray[$a]['idCidade'] = $DadosCidade->id;
                $cidadeArray[$a]['nomeCidade'] = utf8_encode($DadosCidade->descricao);
                $a++;
            }
            echo json_encode($cidadeArray);
            die;
        }

        if(isset($_POST['idetapa'])) {
            $this->_helper->layout->disableLayout(); // desabilita o Zend_Layout
            $idetapa = $_POST['idetapa'];

//            $item = ManterorcamentoDAO::buscarItens($idetapa);
            $tbItensPlanilhaProduto= new tbItensPlanilhaProduto();
            $item = $tbItensPlanilhaProduto->buscarItens($idetapa);

            $a = 0;
            foreach($item as $Dadositem) {
                $itemArray[$a]['idItem'] = $Dadositem->idplanilhaitens;
                $itemArray[$a]['nomeItem'] = $Dadositem->descricao;
                $a++;
            }
            echo json_encode($itemArray);
            die;
        }
        $uf = new Agente_Model_DbTable_UF();
        $buscarEstado = $uf->buscar();
        $this->view->Estados = $buscarEstado;


        $cidade = $mun->listar($buscaDados[0]->IdUf);
        $this->view->Cidades = $cidade;

        $buscarEtapa = new Proposta_Model_DbTable_TbPlanilhaEtapa();
        $this->view->itensEtapaCusto = $buscarEtapa->buscarEtapasCusto();

        $tbPlanilhaEtapa = new Proposta_Model_DbTable_TbPlanilhaEtapa();

        $this->view->Etapa = $tbPlanilhaEtapa->buscarEtapasCadastrarProdutos();

        $buscarRecurso = new Proposta_Model_DbTable_Verificacao();
        $this->view->Recurso = $buscarRecurso->buscarFonteRecurso();

        $buscarUnidade = new Proposta_Model_DbTable_PlanilhaUnidade();
        $this->view->Unidade = $buscarUnidade->buscarUnidade();

        $buscarItem = new tbItensPlanilhaProduto();
        $this->view->Item = $buscarItem->buscarItens($idEtapa);

        $buscarProduto = new Proposta_Model_DbTable_PreProjeto();
        $this->view->Produtos = $buscarProduto->buscarProdutos($this->idPreProjeto);

        $this->view->idPreProjeto = $this->idPreProjeto;
    }

    /**
     * editarcustosAction
     *
     * @access public
     * @return void
     * @deprecated Custos administrativos foi desativado na Proposta em 23/11/2016
     */
    public function editarcustosAction () {
        $this->_helper->layout->disableLayout(); // desabilita o Zend_Layout

        $tbPlanilhaProposta = new Proposta_Model_DbTable_TbPlanilhaProposta();

        if  ( !empty( $_GET ) && count($_GET)> 0 ) {

            $buscaDados = $tbPlanilhaProposta->buscarDadosCustos($_GET);
            $this->view->Dados = $buscaDados;
        }
        if(isset($_POST['iduf'])) {

            $this->_helper->layout->disableLayout(); // desabilita o Zend_Layout
            $iduf = $_POST['iduf'];

            $mun = new Agente_Model_DbTable_Municipios();
            $cidade = $mun->listar($iduf);

            $a = 0;
            foreach($cidade as $DadosCidade) {
                $cidadeArray[$a]['idCidade'] = $DadosCidade->id;
                $cidadeArray[$a]['nomeCidade'] = $DadosCidade->descricao;
                $a++;
            }
            echo json_encode($cidadeArray);
            die;
        }

        if(isset($_POST['idetapa'])) {
            $this->_helper->layout->disableLayout(); // desabilita o Zend_Layout
            $idetapa = $_POST['idetapa'];

            $itensPlanilhaProduto = new tbItensPlanilhaProduto();
            $item = $itensPlanilhaProduto->buscarItens($idetapa);
//            $item = ManterorcamentoDAO::buscarItens($idetapa);

            $a = 0;
            foreach($item as $Dadositem) {
                $itemArray[$a]['idItem'] = $Dadositem->idPlanilhaItens;
                $itemArray[$a]['nomeItem'] = $Dadositem->Descricao;
                $a++;
            }
            echo json_encode($itemArray);
            die;
        }
        $uf = new Agente_Model_DbTable_UF();
        $buscarEstado = $uf->buscar();
        $this->view->Estados = $buscarEstado;

//        $cidade = CidadeDAO::buscar($buscaDados[0]->iduf);
        $mun = new Agente_Model_DbTable_Municipios();
        $cidade = $mun->listar($buscaDados[0]->iduf);

        $this->view->Cidades = $cidade;

        $itensEtapaCusto = new Proposta_Model_DbTable_TbPlanilhaEtapa();

//        $itensEtapaCusto = ManterorcamentoDAO::buscarEtapasCusto();
        $this->view->itensEtapaCusto = $itensEtapaCusto->buscarEtapasCusto();

//        $buscarEtapa = ManterorcamentoDAO::buscarEtapasCadastrarProdutos();
        $this->view->Etapa = $itensEtapaCusto->buscarEtapasCadastrarProdutos();

        $buscarRecurso = new Proposta_Model_DbTable_Verificacao();
        $this->view->Recurso = $buscarRecurso->buscarFonteRecurso();

        $buscarUnidade = new Proposta_Model_DbTable_PlanilhaUnidade();
        $this->view->Unidade = $buscarUnidade->buscarUnidade();

//        ManterorcamentoDAO::buscarItensProdutos($this->idPreProjeto);
        $tbPreProjeto = new Proposta_Model_DbTable_PreProjeto();
        $this->view->Item = $tbPreProjeto->listarItensProdutos($this->idPreProjeto);

//      $buscarItens = ManterorcamentoDAO::buscarItens($_GET['etapa']);
        $tbItens = new tbItensPlanilhaProduto();
        $this->view->ListaItens = $tbItens->buscarItens($_GET['etapa']);

//        $buscaDados = ManterorcamentoDAO::buscarDadosCadastrarCustos($_GET['idPreProjeto']);
        $buscaDados = $tbPlanilhaProposta->buscarDadosCadastrarCustos($_GET['idPreProjeto']);
        $this->view->dados = $buscaDados;

        $this->view->idPreProjeto = $this->idPreProjeto;
    }

    /**
     * salvarprodutosAction
     *
     * @access public
     * @return void
     * @deprecated Custos administrativos foi desativado na Proposta em 23/11/2016
     */
    public function salvarprodutosAction () {

        if  ( isset ( $_POST ) ) {

            $tbPlanilhaProposta = new Proposta_Model_DbTable_TbPlanilhaProposta();

            $idProposta = $_POST['idPreProjeto'];
            $idProduto = $_POST['produto'];
            $idUf = $_POST['uf'];
            $idMunicipio = $_POST['municipio'];
            $idEtapa = $_POST['etapa'];
            $idItem = $_POST['item'];
            $fonte = $_POST['fonterecurso'];
            $unidade = $_POST['unidade'];
            $quantidade = $_POST['quantidade'];
            $ocorrencia = $_POST['ocorrencia'];
            $vlunitario = str_replace(",", ".",  str_replace(".", "",  $_POST['vlunitario']));
            $qtdDias = $_POST['qtdDias'];
            $justificativa = utf8_decode(substr(trim(strip_tags($_POST['editor1'])),0,500));
            $buscarProdutos = $tbPlanilhaProposta->buscarDadosEditarProdutos($idProposta, $idEtapa, $idProduto, $idItem, null, $idUf, $idMunicipio);

            if($buscarProdutos){
            	$this->_helper->layout->disableLayout(); // desabilita o Zend_Layout
	            echo "Cadastro duplicado de Produto na mesma etapa envolvendo o mesmo Item, transa&ccedil;&atilde;o cancelada! Deseja cadastrar um novo item?";
	            die;
            }else{
                $this->view->SalvarNovo = $tbPlanilhaProposta->salvarNovoProduto($idProposta, $idProduto, $idEtapa, $idItem,
                    $unidade, $quantidade, $ocorrencia, $vlunitario, $qtdDias, $fonte, $idUf, $idMunicipio, $justificativa, $this->idUsuario);

                if( $this->view->SalvarNovo )
                    $this->salvarcustosvinculados($idProposta);

	            $this->_helper->layout->disableLayout(); // desabilita o Zend_Layout
	            echo "Item cadastrado com sucesso. Deseja cadastrar um novo item?";
	            die;
            }
        }

        $this->view->idPreProjeto = $this->idPreProjeto;
    }


    /**
     * salvarcustosAction
     *
     * @access public
     * @return void
     * @deprecated Custos administrativos foi desativado na Proposta em 23/11/2016
     */
    public function salvarcustosAction () {

        if  ( isset ( $_POST ) ) {

            $idProposta = $_POST['idPreProjeto'];
            $idUf = $_POST['uf'];
            $idMunicipio = $_POST['municipio'];
            $idEtapa = $_POST['etapa'];
            $idItem = $_POST['item'];
            $fonte = $_POST['fonterecurso'];
            $unidade = $_POST['unidade'];
            $quantidade = $_POST['qtd'];
            $ocorrencia = $_POST['ocorrencia'];
            $vlunitario = str_replace(",", ".", str_replace(".", "",  $_POST['vlunitario']));
            $qtdDias = $_POST['qtdDias'];
            $dsJustificativa = substr(trim(strip_tags($_POST['editor1'])),0,500);
            $tipoCusto = 'A';

            $db= Zend_Db_Table::getDefaultAdapter();
            $dados = array(	'idprojeto'=>$idProposta,
                            'idetapa'=>$idEtapa,
                            'idplanilhaitem'=>$idItem,
                            'descricao'=>'',
                            'unidade'=>$unidade,
                            'quantidade'=>$quantidade,
                            'ocorrencia'=>$ocorrencia,
                            'valorunitario'=>$vlunitario,
                            'qtdedias'=>$qtdDias,
                            'tipodespesa'=>'0',
                            'tipopessoa'=>'0',
                            'contrapartida'=>'0',
                            'fonterecurso'=>$fonte,
                            'ufdespesa'=>$idUf,
                            'municipiodespesa'=>$idMunicipio,
                            'idusuario'=>462,
                            'dsjustificativa'=>$dsJustificativa
                            );

            if($_POST['acao']== 'alterar') {

                $buscarCustos =  new Proposta_Model_DbTable_PlanilhaProposta();
                $where = 'idPlanilhaProposta = ' . $_POST['idPlanilhaProposta'];

                $buscarCustos->update($dados, $where);
                $this->_helper->layout->disableLayout(); // desabilita o Zend_Layout
                echo "Altera&ccedil;&atilde;o realizada com sucesso!";
                die;
            }
            else {
                $TPP = new Proposta_Model_DbTable_PlanilhaProposta();
                $buscarCustos = $TPP->buscarCustos($idProposta, $tipoCusto, $idEtapa, $idItem, $idUf, $idMunicipio);
                if($buscarCustos){
                    $this->_helper->layout->disableLayout(); // desabilita o Zend_Layout
                    echo "Cadastro duplicado de Custo na mesma etapa envolvendo o mesmo Item, transa&ccedil;&atilde;o cancelada! Deseja cadastrar um novo item?";
                    die;
                }else{
                    $TPP->insert($dados);
                    $this->_helper->layout->disableLayout(); // desabilita o Zend_Layout
                    echo "Item cadastrado com sucesso. Deseja cadastrar um novo item?";
                    die;
                }
            }
        }

        $this->view->idPreProjeto = $this->idPreProjeto;
    }


    /**
     * salvarmesmoprodutoAction
     *
     * @access public
     * @return void
     */
    public function salvarmesmoprodutoAction () {
        if  ( isset ( $_POST ) ) {
            $dados = array(
                    'idProjeto'=>$_POST['proposta'],
                    'idProduto'=> $_POST['produto'],
                    'idEtapa'=>$_POST['etapa'],
                    'idPlanilhaItem'=>$_POST['etapa'],
                    'Unidade'=>$_POST['unidade'],
                    'Quantidade'=>$_POST['quantidade'],
                    'Ocorrencia'=>$_POST['ocorrencia'],
                    'valorUnitario'=>$_POST['vlunitario'],
                    'QtdeDias'=>$_POST['qtdDias'],
                    'FonteRecurso'=>$_POST['fonterecurso'],
                    'ufDespesa'=>$_POST['uf'],
                    'MunicipioDespesa'=>$_POST['municipio'],
                    idUsuario=>462
            );

            $where = "(pp.idProjeto = ". $_POST['proposta']." and pp.idProduto = ".$_POST['produto']." and pp.idUsuario = 462)";
            print_r($dados);
            die;
            $salvarProdutos = ManterorcamentoDAO::updateProdutos($dados, $where);
            $this->view->Salvar = $salvarProdutos;
        }
        $this->view->idPreProjeto = $this->idPreProjeto;
    }

    /**
     * excluiritensprodutosAction
     *
     * @access public
     * @return void
     */
    public function excluiritensprodutosAction()
    {
        $idPlanilhaProposta = $_GET['idPlanilhaProposta'];
        $idPreProjeto = $_GET['idPreProjeto'];

        $retorno = $_GET['retorno'];

        $tbPlaninhaProposta = new Proposta_Model_DbTable_PlanilhaProposta();

        $where = 'idPlanilhaProposta = ' . $idPlanilhaProposta;

        $resposta = $tbPlaninhaProposta->delete($where);

        if($resposta) {
            $this->salvarcustosvinculados($idPreProjeto);
            parent::message("Exclus&atilde;o realizada com sucesso!", "/proposta/manterorcamento/".$retorno."?idPreProjeto=".$this->idPreProjeto ,"CONFIRM");
        } else {
            parent::message("Erro ao excluir os dados", "/proposta/manterorcamento/".$retorno."?idPreProjeto=".$this->idPreProjeto ,"ERROR");
        }
        $this->view->idPreProjeto = $this->idPreProjeto;
    }

    /**
     * salvarcustosvinculadosAction
     *
     * @access public
     * @return void
     */
    public function salvarcustosvinculados( $idPreProjeto ) {

        if ( !empty($idPreProjeto) ) {

            $idEtapa      = '8'; // Custos Vinculados
            $tipoCusto    = 'A';
            $fonteRecurso = '109'; // incentivo fiscal

            // fazer uma busca geral
            $TPP = new Proposta_Model_DbTable_PlanilhaProposta();

            // @todo fazer uma busca mais leve, nao precisa dos joins
            $todosItensPlanilha = $TPP->buscarCustos($idPreProjeto, 'P', '', '', '', '', 109);

            $valorTotalProjeto = null;

            foreach ($todosItensPlanilha as $item) {

                $valorTotalItem = null;
                $valorTotalItem = ($item->quantidade * $item->ocorrencia * $item->valorunitario);

                $valorTotalProjeto = $valorTotalItem + $valorTotalProjeto;

                $idUf = $item->idUF;
                $idMunicipio = $item->idMunicipio;
            }

            $ufRegionalizacaoPlanilha =  $TPP->buscarItensUfRegionalizacao($idPreProjeto);

            // definindo os criterios de regionalizacao
            if( !empty($ufRegionalizacaoPlanilha) ) {
                $calcDivugacao =  0.2;
                $calcCaptacao = 0.1;
                $limitCaptacao = 100000;

                $idUf         = $ufRegionalizacaoPlanilha->idUF;
                $idMunicipio  = $ufRegionalizacaoPlanilha->idMunicipio;

            }else {
                $calcDivugacao =  0.3;
                $calcCaptacao = 0.2;
                $limitCaptacao = 200000;
            }

            $itensPlanilhaProduto = new tbItensPlanilhaProduto();
            $itensCustoAdministrativo = $itensPlanilhaProduto->buscarItens(8); //@todo corrigir id correto, Romulo vai criar a planilha

            foreach ($itensCustoAdministrativo as $item ) {
                $custosVinculados = null;
                $valorCustoItem = null;
                $save = true;

                //fazer uma nova busca com o essencial para este caso
                $custosVinculados = $TPP->buscarCustos($idPreProjeto, $tipoCusto, $idEtapa, $item->idPlanilhaItens);

                switch($item->idPlanilhaItens) {
                    case 8197: // Custo Administrativo @todo corrigir id correto, Romulo vai criar a planilha
                        $valorCustoItem = ( $valorTotalProjeto * 0.15);
                        break;
                    case 8198: // Divulgacao
                        $valorCustoItem = ( $valorTotalProjeto * $calcDivugacao );
                        break;
                    case 5249: // Remuneracao p/ Captar Recursos
                        $valorCustoItem = ( $valorTotalProjeto * $calcCaptacao );
                        if( $valorCustoItem > $limitCaptacao )
                            $valorCustoItem = $limitCaptacao;
                        break;
                    case 8199: // Controle e Auditoria
                        $valorCustoItem = ( $valorTotalProjeto * 0.1 );
                        if( $valorCustoItem > 100000 )
                            $valorCustoItem = 100000;
                        break;
                    default:
                        $save = false;
                }

                $dados = array(
                    'idprojeto' => $idPreProjeto,
                    'idetapa' => $idEtapa,
                    'idplanilhaitem' => $item->idPlanilhaItens, // item rômulo vai mandar
                    'descricao' => '',
                    'unidade' => '1', // nao informado
                    'quantidade' => '1',
                    'ocorrencia' => '1',
                    'valorunitario' => $valorCustoItem,
                    'qtdedias' => '1',
                    'tipodespesa' => '0',
                    'tipopessoa' => '0',
                    'contrapartida' => '0',
                    'fonterecurso' => $fonteRecurso,
                    'ufdespesa' => $idUf,
                    'municipiodespesa' => $idMunicipio,
                    'idusuario' => 462,
                    'dsjustificativa' => ''
                );

                if($save) {
                    if( isset($custosVinculados[0]->idItem)) {

                        $where = 'idPlanilhaProposta = ' . $custosVinculados[0]->idPlanilhaProposta;

                        $TPP->update($dados, $where);
                    }
                    else {
                        $TPP->insert($dados);
                    }
                }
            }
        }
    }
}
