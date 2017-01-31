<?php

abstract class Proposta_GenericController extends MinC_Controller_Action_Abstract
{

    protected $_proposta;

    protected $_proponente;

    private $_movimentacaoAlterarProposta = '95';

    private $_situacaoAlterarProjeto = 'E90'; // @todo situacao correta 'E90'

    public function init()
    {
        parent::init();

        //recupera ID do pre projeto (proposta)
        $idPreProjeto = $this->getRequest()->getParam('idPreProjeto');

        if (!empty($idPreProjeto)) {

            $this->view->idPreProjeto = $idPreProjeto;

            $arrBusca['idPreProjeto = ?'] = $idPreProjeto;

            $tblPreProjeto = new Proposta_Model_DbTable_PreProjeto();
            $this->_proposta = $tblPreProjeto->buscar($arrBusca)->current();

            if ($this->_proposta) {
                $this->_proposta = array_change_key_case($this->_proposta->toArray());
            }
            $this->view->proposta = $this->_proposta;

            $arrBuscaProponete['a.idagente = ?'] = $this->_proposta['idagente'];
            $tblAgente = new Agente_Model_DbTable_Agentes();
            $this->_proponente = $tblAgente->buscarAgenteNome($arrBuscaProponete)->current();
            if ($this->_proponente) {
                $this->_proponente = array_change_key_case($this->_proponente->toArray());
            }
            $this->view->proponente = $this->_proponente;

            $this->view->url = $this->getRequest()->REQUEST_URI;
            $this->view->isEditarProposta = $this->isEditarProposta($idPreProjeto);
            $this->view->isEditarProjeto = $this->isEditarProjeto($idPreProjeto);
            $this->view->isEditavel = $this->isEditavel($idPreProjeto);

            $layout = array(
                'titleShort' => 'Proposta',
                'titleFull' => 'Proposta Cultural',
                'projeto' => $idPreProjeto,
                'listagem' => array('Lista de propostas' => array('controller' => 'manterpropostaincentivofiscal', 'action' => 'listar-propostas')),
            );

            // Alterar projeto
            if (!empty($this->view->isEditarProjeto)) {
                $tblProjetos = new Projetos();
                $projeto = array_change_key_case($tblProjetos->findBy(array('idprojeto = ?' => $idPreProjeto)));

                if (!isset($projeto['nrprojeto']))
                    $projeto['nrprojeto'] = $projeto['anoprojeto'] . $projeto['sequencial'];

                $this->view->projeto = $projeto;

                $layout = array(
                    'titleShort' => 'Projeto',
                    'titleFull' => 'Alterar projeto',
                    'projeto' => $projeto['nrprojeto'],
                    'listagem' => array('Lista de projetos' => array('module' => 'default', 'controller' => 'Listarprojetos', 'action' => 'listarprojetos')),
                );

            }
            $this->view->layout = $layout;
        }
    }

    public function isEditarProposta($idPreProjeto)
    {

        if (empty($idPreProjeto))
            return false;

        // Verifica se a proposta estah com o minc
        $tbMovimentacao = new Proposta_Model_DbTable_TbMovimentacao();
        $rsStatusAtual = $tbMovimentacao->findBy(array('idprojeto = ?' => $idPreProjeto, 'stestado = ?' => 0));

        if ($rsStatusAtual['Movimentacao'] == $this->_movimentacaoAlterarProposta)
            return true;

        return false;
    }

    public function isEditarProjeto($idPreProjeto)
    {

        if (empty($idPreProjeto))
            return false;

        // Verifica se o projeto esta na situacao para editar
        $tblProjetos = new Projetos();
        $projeto = $tblProjetos->findBy(array('idprojeto = ?' => $idPreProjeto));

        if ($projeto['Situacao'] == $this->_situacaoAlterarProjeto)
            return true;

        return false;
    }

    public function isEditavel($idPreProjeto)
    {
        if (!$this->isEditarProjeto($idPreProjeto) && !$this->isEditarProposta($idPreProjeto))
            return false;

        return true;
    }

    public function buscarStatusProposta($idPreProjeto)
    {
        if( empty($idPreProjeto))
            return false;

        $tbMovimentacao = new Proposta_Model_DbTable_TbMovimentacao();
        $rsStatusAtual = $tbMovimentacao->buscarStatusPropostaNome($idPreProjeto);

        return $rsStatusAtual;

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
            $todosItensPlanilha = $TPP->buscarCustos($idPreProjeto, 'P', '', '', '', '', 109); // apenas itens de incentivo fiscal

            $valorTotalProjeto = null;
            if( empty($todosItensPlanilha) ) { // se não tiver nenhum item zerar os custos

                $valorTotalProjeto = '1';
                $limitCaptacao = 200000;
                $calcDivugacao = 0;
                $calcCaptacao = 0;
                $idUf  = 1;
                $idMunicipio = 1;

            }else {

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
            }

            $itensPlanilhaProduto = new tbItensPlanilhaProduto();
            $itensCustoAdministrativo = $itensPlanilhaProduto->buscarItens(8);

            foreach ($itensCustoAdministrativo as $item ) {
                $custosVinculados = null;
                $valorCustoItem = null;
                $save = true;

                //fazer uma nova busca com o essencial para este caso
                $custosVinculados = $TPP->buscarCustos($idPreProjeto, $tipoCusto, $idEtapa, $item->idPlanilhaItens);

                switch($item->idPlanilhaItens) {
                    case 8197: // Custo Administrativo
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
                    'idplanilhaitem' => $item->idPlanilhaItens,
                    'descricao' => '',
                    'unidade' => '1',
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

    public function calcularValorTotalProjeto($totalSolicitado) {
        $custoAdministrativo = $totalSolicitado * 0.15;
        $divulgacao = $totalSolicitado * 0.2;
        $remuneracaoCaptacaoRecurso = $totalSolicitado * 0.1;
        $controleAuditoria = $totalSolicitado * 0.1;
        $totalProjeto = $totalSolicitado + $custoAdministrativo + $divulgacao + $remuneracaoCaptacaoRecurso + $controleAuditoria;
    ?>
}
