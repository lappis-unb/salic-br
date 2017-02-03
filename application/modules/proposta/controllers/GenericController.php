<?php

abstract class Proposta_GenericController extends MinC_Controller_Action_Abstract
{

    protected $_proposta;

    protected $_proponente;

    private $_movimentacaoAlterarProposta = '95';

    private $_situacaoAlterarProjeto = 'E90'; // @todo situacao correta 'E90'

    private $_diasParaAlterarProjeto = 10;

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
                    'prazoAlterarProjeto' =>  $this->contagemRegressivaSegundos($projeto['dtsituacao'], $this->_diasParaAlterarProjeto)
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

        if( $this->contagemRegressivaDias($projeto['DtSituacao'], $this->_diasParaAlterarProjeto) < 0)
            return false;

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
        if (empty($idPreProjeto))
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
    public function salvarcustosvinculados($idPreProjeto)
    {
        $idEtapa = '8'; // Custos Vinculados
        $tipoCusto = 'A';

        if (empty($idPreProjeto))
            return false;

        $TPP = new Proposta_Model_DbTable_TbPlanilhaProposta();
        $somaPlanilhaPropostaProdutos = $TPP->somarPlanilhaPropostaProdutos($idPreProjeto, 109);

        if (is_numeric($somaPlanilhaPropostaProdutos['soma']) &&  $somaPlanilhaPropostaProdutos['soma'] <= 0) {
            $TPP->excluirCustosVinculados($idPreProjeto);
            return true;
        }

        $itens = $this->calcularCustosVinculados($idPreProjeto, $somaPlanilhaPropostaProdutos['soma']);

        foreach ($itens as $item) {

            $custosVinculados = null;

            //fazer uma nova busca com o essencial para este caso
            $custosVinculados = $TPP->buscarCustos($idPreProjeto, $tipoCusto, $idEtapa, $item['idplanilhaitem']);

            if (isset($custosVinculados[0]->idItem)) {
                $where = 'idPlanilhaProposta = ' . $custosVinculados[0]->idPlanilhaProposta;
                $TPP->update($item, $where);
            } else {
                $TPP->insert($item);
            }
        }
    }

    public function calcularCustosVinculados($idPreProjeto, $valorTotalProdutos = null)
    {

        if (empty($idPreProjeto))
            return false;

        $idEtapa = '8'; // Custos Vinculados
        $fonteRecurso = '109'; // incentivo fiscal
        $dados = array();

        $TPP = new Proposta_Model_DbTable_TbPlanilhaProposta();

        if (empty($valorTotalProdutos)) {
            $somaPlanilhaPropostaProdutos = $TPP->somarPlanilhaPropostaProdutos($idPreProjeto, 109);
            $valorTotalProdutos = $somaPlanilhaPropostaProdutos['soma'];
        }

        if (!is_numeric($valorTotalProdutos)) {
            return 0;
        }

        $ufRegionalizacaoPlanilha = $TPP->buscarItensUfRegionalizacao($idPreProjeto);

        // definindo os criterios de regionalizacao
        if (!empty($ufRegionalizacaoPlanilha)) {
            $calcDivugacao = 0.2;
            $calcCaptacao = 0.1;
            $limiteCaptacao = 100000;

            $idUf = $ufRegionalizacaoPlanilha->idUF;
            $idMunicipio = $ufRegionalizacaoPlanilha->idMunicipio;
        } else {
            $calcDivugacao = 0.3;
            $calcCaptacao = 0.2;
            $limiteCaptacao = 200000;

            $arrBusca['idprojeto'] = $idPreProjeto;
            $arrBusca['stabrangencia'] = 1;
            $tblAbrangencia = new Proposta_Model_DbTable_Abrangencia();
            $rsAbrangencia = $tblAbrangencia->findBy($arrBusca);

            $idUf        = 1;
            $idMunicipio = 1;
        }

        // Busca os itens da etapa 8 (custos vinculados)
        $itensPlanilhaProduto = new tbItensPlanilhaProduto();
        $itensCustoAdministrativo = $itensPlanilhaProduto->buscarItens($idEtapa);

        foreach ($itensCustoAdministrativo as $item) {
            $custosVinculados = null;
            $valorCustoItem = null;
            $calcular = true;

            switch ($item->idPlanilhaItens) {
                case 8197: // Custo Administrativo
                    $valorCustoItem = ($valorTotalProdutos * 0.15);
                    break;
                case 8198: // Divulgacao
                    $valorCustoItem = ($valorTotalProdutos * $calcDivugacao);
                    break;
                case 5249: // Remuneracao p/ Captar Recursos
                    $valorCustoItem = ($valorTotalProdutos * $calcCaptacao);
                    if ($valorCustoItem > $limiteCaptacao)
                        $valorCustoItem = $limiteCaptacao;
                    break;
                case 8199: // Controle e Auditoria
                    $valorCustoItem = ($valorTotalProdutos * 0.1);
                    if ($valorCustoItem > 100000)
                        $valorCustoItem = 100000;
                    break;
                default:
                    $calcular = false;
            }

            if ($calcular == true) {

                $dados[] = array(
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
            }
        }
        return $dados;
    }

    public function somarTotalCustosVinculados($idPreProjeto, $valorTotalProdutos = null)
    {
        $itens = $this->calcularCustosVinculados($idPreProjeto, $valorTotalProdutos);

        if ($itens == 0)
            return 0;

        if ($itens) {
            $soma = 0;
            foreach ($itens as $item) {
                $soma = $item['valorunitario'] + $soma;
            }
        }
        return $soma;
    }

    public function contagemRegressivaDias($datainicial = null, $prazo = null)
    {
        $datafinal="NOW";

        $datainicial = strtotime($datainicial . "+ " . $prazo ." day");
        $datafinal   = strtotime($datafinal);
        $datatirada  = $datainicial - $datafinal;
        $dias = (($datatirada / 3600) / 24);

        return $dias;
    }

    public function contagemRegressivaSegundos($datainicial = null, $prazo = null)
    {
        $datafinal="NOW";

        $datainicial = strtotime($datainicial . "+ " . $prazo ." day");
        $datafinal   = strtotime($datafinal) + 24 * 3600;
        $segundos  = $datainicial - $datafinal;

        return $segundos;
    }
}
