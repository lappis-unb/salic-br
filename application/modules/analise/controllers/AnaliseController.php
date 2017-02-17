<?php

class Analise_AnaliseController extends Analise_GenericController
{
    private $idUsuario = null;
    private $idPreProjeto = null;
    private $idProjeto = null;

    private $codGrupo = null;
    private $codOrgao = null;

    public function init()
    {
        parent::init();

        # define as permissoes
        $PermissoesGrupo = array();
        $PermissoesGrupo[] = Autenticacao_Model_Grupos::COORDENADOR_ANALISE;
        $PermissoesGrupo[] = Autenticacao_Model_Grupos::TECNICO_ANALISE;


        if (!empty ($_REQUEST['idPreProjeto'])) {
            $this->idPreProjeto = $_REQUEST['idPreProjeto'];
        }
        $auth = Zend_Auth::getInstance(); // instancia da autenticacao

        //parent::perfil(1, $PermissoesGrupo);
        isset($auth->getIdentity()->usu_codigo) ? parent::perfil(1, $PermissoesGrupo) : parent::perfil(4, $PermissoesGrupo);

        isset($auth->getIdentity()->usu_codigo) ? $this->idUsuario = $auth->getIdentity()->usu_codigo : $this->idUsuario = $auth->getIdentity()->IdUsuario;
        //$this->idUsuario = $auth->getIdentity()->usu_codigo;
        $GrupoAtivo = new Zend_Session_Namespace('GrupoAtivo');
        if (isset($auth->getIdentity()->usu_codigo)) {

            $this->codGrupo = $GrupoAtivo->codGrupo;
            $this->codOrgao = $GrupoAtivo->codOrgao;
            $this->codOrgaoSuperior = (!empty($auth->getIdentity()->usu_org_max_superior)) ? $auth->getIdentity()->usu_org_max_superior : null;
        }

    }

    public function listarprojetosAction()
    {

    }

    public function listarProjetosAjaxAction()
    {
        $start = $this->getRequest()->getParam('start');
        $length = $this->getRequest()->getParam('length');
        $draw = (int)$this->getRequest()->getParam('draw');
        $search = $this->getRequest()->getParam('search');
        $order = $this->getRequest()->getParam('order');
        $columns = $this->getRequest()->getParam('columns');

        $order = ($order[0]['dir'] != 1) ? array($columns[$order[0]['column']]['name'] . ' ' . $order[0]['dir']) : array("DtSituacao DESC");

        $vwPainelAvaliar = new Analise_Model_DbTable_vwProjetosAdequadosRealidadeExecucao();

        if (Autenticacao_Model_Grupos::TECNICO_ANALISE == $this->codGrupo) {
            $where['idUsuario = ?'] = $this->idUsuario;
        }

        $orgao = new Orgaos();
        $orgao = $orgao->codigoOrgaoSuperior($this->codOrgao);
        $orgaoSuperior = $orgao[0]['Codigo'];
        $where['Orgao = ?'] = $orgaoSuperior;

        $projetos = $vwPainelAvaliar->projetos($where, $order, $start, $length, $search);
        $recordsTotal = 0;
        $recordsFiltered = 0;
        $aux = array();
        if (!empty($projetos)) {
            foreach ($projetos as $key => $projetos) {
                $projetos->NomeProjeto = utf8_encode($projetos->NomeProjeto);
                $projetos->Tecnico = utf8_encode($projetos->Tecnico);
                $projetos->Segmento = utf8_encode($projetos->Segmento);
                $projetos->Proponente = utf8_encode($projetos->Proponente);
                $projetos->Enquadramento = utf8_encode($projetos->Enquadramento);
                $projetos->VlSolicitado = number_format(($projetos->VlSolicitado), 2, ",", ".");
                $aux[$key] = $projetos;
            }
            $recordsTotal = $vwPainelAvaliar->projetosTotal($where);
            $recordsFiltered = $vwPainelAvaliar->projetosTotal($where, null, null, null, $search);
        }

        $this->_helper->json(array(
            "data" => !empty($aux) ? $aux : 0,
            'recordsTotal' => $recordsTotal ? $recordsTotal : 0,
            'draw' => $draw,
            'recordsFiltered' => $recordsFiltered ? $recordsFiltered : 0));
    }

    public function visualizarprsojetoAction(){


        $idPronac = $this->getRequest()->getParam('idpronac');

        try {
            if (empty($idPronac)) {
                throw new Exception ("Identificador do projeto &eacute; necess&aacute;rio para acessar essa funcionalidade.");
            }

            $this->view->IdPRONAC = $idPronac;

            $objTbProjetos = new Projeto_Model_DbTable_Projetos();
            $projeto = $objTbProjetos->findBy(array(
                'IdPRONAC' => $idPronac
            ));
            $this->view->projeto = $projeto;

            xd($this->view->projeto);

            $this->view->valoresProjeto = $objTbProjetos->obterValoresProjeto($idPronac);

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

            $objPlanoDistribuicaoProduto = new Projeto_Model_vwPlanoDeDistribuicaoProduto();
            $this->view->dadosProducaoProjeto = $objPlanoDistribuicaoProduto->obterProducaoProjeto(array(
                'IdPRONAC = ?' => $idPronac
            ));

            $objEnquadramento = new Admissibilidade_Model_Enquadramento();
            $arrayPesquisa = array(
                'AnoProjeto' => $this->view->projeto['AnoProjeto'],
                'Sequencial' => $this->view->projeto['Sequencial'],
                'IdPRONAC' => $idPronac
            );
            $this->view->dadosEnquadramento = $objEnquadramento->findBy($arrayPesquisa);

            $objAssinatura = new Assinatura_Model_DbTable_TbAssinatura();
            $this->view->assinaturas = $objAssinatura->obterAssinaturas($idPronac, $this->idTipoDoAtoAdministrativo);

            $objTbAtoAdministrativo = new Assinatura_Model_DbTable_TbAtoAdministrativo();
            $this->view->quantidade_minima_assinaturas = $objTbAtoAdministrativo->obterQuantidadeMinimaAssinaturas($this->idTipoDoAtoAdministrativo);

            $objModelDocumentoAssinatura = new Assinatura_Model_DbTable_TbDocumentoAssinatura();
            $this->view->documentoAssinatura = $objModelDocumentoAssinatura->findBy(
                array(
                    'IdPRONAC' => $idPronac,
                    'idTipoDoAtoAdministrativo' => $this->idTipoDoAtoAdministrativo
                )
            );
        } catch (Exception $objException) {
            parent::message($objException->getMessage(), "/{$this->moduleName}/analise/listarprojetos", "ERROR");
        }
    }

    public function visualizarprojetoAction()
    {

        $idPronac = $this->getRequest()->getParam('idpronac');

        try {
            if (empty($idPronac)) {
                throw new Exception ("Identificador do projeto &eacute; necess&aacute;rio para acessar essa funcionalidade.");
            }

            $objTbProjetos = new Projeto_Model_DbTable_Projetos();
            $projeto = $objTbProjetos->findBy(array(
                'IdPRONAC' => $idPronac
            ));
            $this->view->projeto = $projeto;

            $idPreProjeto = $projeto['idProjeto'];
            $dados = Proposta_Model_AnalisarPropostaDAO::buscarGeral($idPreProjeto);
            $this->view->itensGeral = $dados;

            $movimentacao = new Proposta_Model_DbTable_TbMovimentacao();
            $movimentacao = $movimentacao->buscarStatusAtualProposta($idPreProjeto);
            $this->view->movimentacao = $movimentacao['Movimentacao'];

            //========== inicio codigo dirigente ================
            $arrMandatos = array();
            $this->view->mandatos = $arrMandatos;
            $preProjeto = new Proposta_Model_DbTable_PreProjeto();
            $rsDirigentes = array();

            $Empresa = $preProjeto->buscar(array('idPreProjeto = ?' => $idPreProjeto))->current();
            $idEmpresa = $Empresa->idAgente;

            $Projetos = new Projetos();
            $dadosProjeto = $Projetos->buscar(array('idProjeto = ?' => $idPreProjeto))->current();

            // Busca na tabela apoio ExecucaoImediata stproposta
            $tableVerificacao = new Proposta_Model_DbTable_Verificacao();
            if( !empty($this->view->itensGeral[0]->stProposta))
                $this->view->ExecucaoImediata = $tableVerificacao->findBy(array('idVerificacao' => $this->view->itensGeral[0]->stProposta));

            $Pronac = null;
            if (count($dadosProjeto) > 0) {
                $Pronac = $dadosProjeto->AnoProjeto . $dadosProjeto->Sequencial;
            }
            $this->view->Pronac = $Pronac;

            if (isset($dados[0]->CNPJCPFdigirente) && $dados[0]->CNPJCPFdigirente != "") {
                $tblAgente = new Agente_Model_DbTable_Agentes();
                $tblNomes = new Nomes();
                foreach ($dados as $v) {
                    $rsAgente = $tblAgente->buscarAgenteENome(array('CNPJCPF=?' => $v->CNPJCPFdigirente))->current();
                    $rsDirigentes[$rsAgente->idAgente]['CNPJCPFDirigente'] = $rsAgente->CNPJCPF;
                    $rsDirigentes[$rsAgente->idAgente]['idAgente'] = $rsAgente->idAgente;
                    $rsDirigentes[$rsAgente->idAgente]['NomeDirigente'] = $rsAgente->Descricao;
                }

                $tbDirigenteMandato = new tbAgentesxVerificacao();
                foreach ($rsDirigentes as $dirigente) {
                    $rsMandato = $tbDirigenteMandato->listarMandato(array('idEmpresa = ?' => $idEmpresa, 'idDirigente = ?' => $dirigente['idAgente'], 'stMandato = ?' => 0));
                    $NomeDirigente = $dirigente['NomeDirigente'];
                    $arrMandatos[$NomeDirigente] = $rsMandato;
                }
            }

            $this->view->dirigentes = $rsDirigentes;
            $this->view->mandatos = $arrMandatos;
            //============== fim codigo dirigente ================

//            $propostaPorEdital = false;
//            if ($this->view->itensGeral[0]->idEdital && $this->view->itensGeral[0]->idEdital != 0) {
//                $propostaPorEdital = true;
//            }
//            $this->view->isEdital = $propostaPorEdital;
            $this->view->itensTelefone = Proposta_Model_AnalisarPropostaDAO::buscarTelefone($this->view->itensGeral[0]->idAgente);
            $this->view->itensPlanosDistribuicao = Proposta_Model_AnalisarPropostaDAO::buscarPlanoDeDistribucaoProduto($idPreProjeto);
            $this->view->itensFonteRecurso = Proposta_Model_AnalisarPropostaDAO::buscarFonteDeRecurso($idPreProjeto);
            $this->view->itensLocalRealiazacao = Proposta_Model_AnalisarPropostaDAO::buscarLocalDeRealizacao($idPreProjeto);
            $this->view->itensDeslocamento = Proposta_Model_AnalisarPropostaDAO::buscarDeslocamento($idPreProjeto);
            $this->view->itensPlanoDivulgacao = Proposta_Model_AnalisarPropostaDAO::buscarPlanoDeDivulgacao($idPreProjeto);

            $PPM = new Proposta_Model_DbTable_PreProjetoMeta();

            $historico['planilha'] = unserialize($PPM->buscarMeta($idPreProjeto, 'alterarprojeto_tbplanilhaproposta'));
            $historico['abrangencia'] = unserialize($PPM->buscarMeta($idPreProjeto, 'alterarprojeto_abrangencia'));
            $historico['planodistribuicaoproduto'] = unserialize($PPM->buscarMeta($idPreProjeto, 'alterarprojeto_planodistribuicaoproduto'));
            $historico['tbdetalhaplanodistribuicao'] = unserialize($PPM->buscarMeta($idPreProjeto, 'alterarprojeto_tbdetalhaplanodistribuicao'));
            $historico['identificacaoproposta'] = unserialize($PPM->buscarMeta($idPreProjeto, 'alterarprojeto_identificacaoproposta'));
            $historico['responsabilidadesocial'] = unserialize($PPM->buscarMeta($idPreProjeto, 'alterarprojeto_responsabilidadesocial'));
            $historico['detalhestecnicos'] = unserialize($PPM->buscarMeta($idPreProjeto, 'alterarprojeto_detalhestecnicos'));
            $historico['outrasinformacoes'] = unserialize($PPM->buscarMeta($idPreProjeto, 'alterarprojeto_outrasinformacoes'));

            $this->view->historico = $historico;

            //DOCUMENTOS ANEXADOS PROPOSTA
            $tbl = new Proposta_Model_DbTable_TbDocumentosPreProjeto();
            $rs = $tbl->buscarDocumentos(array("idProjeto = ?" => $idPreProjeto));
            $this->view->arquivosProposta = $rs;

            //DOCUMENTOS ANEXADOS PROPONENTE
            $tbA = new Proposta_Model_DbTable_TbDocumentosAgentes();
            $rsA = $tbA->buscarDocumentos(array("idAgente = ?" => $dados[0]->idAgente));
            $this->view->arquivosProponente = $rsA;

            //DOCUMENTOS ANEXADOS NA DILIGENCIA
            $tblAvaliacaoProposta = new AvaliacaoProposta();
            $rsAvaliacaoProposta = $tblAvaliacaoProposta->buscar(array("idProjeto = ?" => $idPreProjeto, "idArquivo ?" => new Zend_Db_Expr("IS NOT NULL")));
            $tbArquivo = new tbArquivo();
            $arrDadosArquivo = array();
            $arrRelacionamentoAvaliacaoDocumentosExigidos = array();
            if (count($rsAvaliacaoProposta) > 0) {
                foreach ($rsAvaliacaoProposta as $avaliacao) {
                    $arrDadosArquivo[$avaliacao->idArquivo] = $tbArquivo->buscar(array("idArquivo = ?" => $avaliacao->idArquivo));
                    $arrRelacionamentoAvaliacaoDocumentosExigidos[$avaliacao->idArquivo] = $avaliacao->idCodigoDocumentosExigidos;
                }
            }
            $this->view->relacionamentoAvaliacaoDocumentosExigidos = $arrRelacionamentoAvaliacaoDocumentosExigidos;
            $this->view->itensDocumentoPreProjeto = $arrDadosArquivo;

            //PEGANDO RELACAO DE DOCUMENTOS EXIGIDOS(GERAL, OU SEJA, TODO MUNDO)
            $tblDocumentosExigidos = new DocumentosExigidos();
            $rsDocumentosExigidos = $tblDocumentosExigidos->buscar()->toArray();
            $arrDocumentosExigidos = array();
            foreach ($rsDocumentosExigidos as $documentoExigido) {
                $arrDocumentosExigidos[$documentoExigido["Codigo"]] = $documentoExigido;
            }
            $this->view->documentosExigidos = $arrDocumentosExigidos;
            $this->view->itensHistorico = Proposta_Model_AnalisarPropostaDAO::buscarHistorico($idPreProjeto);
            $this->view->itensPlanilhaOrcamentaria = Proposta_Model_AnalisarPropostaDAO::buscarPlanilhaOrcamentaria($idPreProjeto);

            $buscarProduto = ManterorcamentoDAO::buscarProdutos($idPreProjeto);
            $this->view->Produtos = $buscarProduto;

            $tbPlanilhaEtapa = new Proposta_Model_DbTable_TbPlanilhaEtapa();
            $buscarEtapa = $tbPlanilhaEtapa->listarEtapasProdutos($idPreProjeto);

            $this->view->Etapa = $buscarEtapa;

            $preProjeto = new Proposta_Model_DbTable_PreProjeto();

            $buscarItem = $preProjeto->listarItensProdutos($idPreProjeto);
            $this->view->AnaliseCustos = Proposta_Model_DbTable_PreProjeto::analiseDeCustos($idPreProjeto);

            $this->view->idPreProjeto = $idPreProjeto;
            $pesquisaView = $this->_getParam('pesquisa');
            if ($pesquisaView == 'proposta') {
                $this->view->menu = 'inativo';
                $this->view->tituloTopo = 'Consultar dados da proposta';
            }

        } catch (Exception $objException) {
            parent::message($objException->getMessage(), "/{$this->moduleName}/analise/listarprojetos", "ERROR");
        }
    }
}