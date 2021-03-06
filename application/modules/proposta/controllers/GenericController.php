<?php

abstract class Proposta_GenericController extends MinC_Controller_Action_Abstract
{

    protected $_proposta;

    protected $_proponente;

    private $_movimentacaoAlterarProposta = '95';

    private $_situacaoAlterarProjeto = Projeto_Model_Situacao::PROJETO_LIBERADO_PARA_AJUSTES;

    private $_diasParaAlterarProjeto = 10;

    public function init()
    {
        parent::init();

        //recupera ID do pre projeto (proposta)
        $idPreProjeto = $this->getRequest()->getParam('idPreProjeto');

        if (!empty($idPreProjeto)) {

            $this->_proposta = $this->buscarProposta($idPreProjeto);
            $this->_proponente = $this->buscarProponente($this->_proposta['idagente']);

            $this->view->idPreProjeto = $idPreProjeto;
            $this->view->proposta = $this->_proposta;
            $this->view->proponente = $this->_proponente;

            $this->view->url = $this->getRequest()->REQUEST_URI;
            $this->view->isEditarProposta = $this->isEditarProposta($idPreProjeto);
            $this->view->isEditarProjeto = $this->isEditarProjeto($idPreProjeto);
            $this->view->isEditavel = $this->isEditavel($idPreProjeto);

            $layout = array(
                'titleShort' => 'Proposta',
                'titleFull' => 'Proposta Cultural',
                'projeto' => $idPreProjeto,
                'listagem' => array('Lista de propostas' => array('controller' => 'manterpropostaincentivofiscal', 'action' => 'listarproposta')),
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
                    'prazoAlterarProjeto' => $this->contagemRegressivaSegundos($projeto['dtsituacao'], $this->_diasParaAlterarProjeto)
                );

                $this->salvarDadosPropostaSerializada($idPreProjeto);
            }
            $this->view->layout = $layout;
        }
    }

    private function buscarProponente($idAgente)
    {
        $tblAgente = new Agente_Model_DbTable_Agentes();

        $proponente = $tblAgente->buscarAgenteENome(array('a.idAgente = ?' => $idAgente))->current();

        if ($proponente) {
            $proponente = array_change_key_case($proponente->toArray());

            return $proponente;
        }

        return false;
    }

    private function buscarProposta($idPreProjeto)
    {

        $tblPreProjeto = new Proposta_Model_DbTable_PreProjeto();
        $proposta = $tblPreProjeto->buscar(array('idPreProjeto = ?' => $idPreProjeto))->current();

        if ($proposta) {
            $proposta = array_change_key_case($proposta->toArray());
            return $proposta;
        }
        return false;
    }

    public function isEditarProposta($idPreProjeto)
    {

        if (empty($idPreProjeto))
            return false;

        // Verifica se a proposta estah com o minc
        $tbMovimentacao = new Proposta_Model_DbTable_TbMovimentacao();
        $rsStatusAtual = $tbMovimentacao->findBy(array('idProjeto = ?' => $idPreProjeto, 'stEstado = false' => false));

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
        $projeto = $tblProjetos->findBy(array('idProjeto = ?' => $idPreProjeto));

//        $tblProjetos->verificarLiberacaoParaAdequacao($projeto['IdPRONAC']);
        if (!$tblProjetos->verificarLiberacaoParaAdequacao($projeto['IdPRONAC']))
            return false;

        if ($this->contagemRegressivaSegundos($projeto['DtSituacao'], $this->_diasParaAlterarProjeto) < 0)
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

        if (empty($somaPlanilhaPropostaProdutos['soma']) || (is_numeric($somaPlanilhaPropostaProdutos['soma']) && $somaPlanilhaPropostaProdutos['soma'] <= 0)) {
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

        # definindo os criterios de regionalizacao
        if (!empty($ufRegionalizacaoPlanilha)) { # sudeste e sul
            $calcDivugacao = 0.2;     # custo de divulgacao 20%
            $calcCaptacao = 0.1;      # custo para capta&ccedil;&atilde;o 10%
            $limiteCaptacao = 100000; # valor m&aacute;ximo para capta&ccedil;&atilde;o 100.000,00

            $idUf = $ufRegionalizacaoPlanilha->idUF;
            $idMunicipio = $ufRegionalizacaoPlanilha->idMunicipio;
        } else { # demais regi&otilde;es
            $calcDivugacao = 0.3;     # custo de divulga&ccedil;&atilde;o 30%
            $calcCaptacao = 0.15;     # custo para capta&ccedil;&atilde;o 15%
            $limiteCaptacao = 150000; # valor m&aacute;ximo para capta&ccedil;&atilde;o 150.000,00

            $arrBusca['idprojeto'] = $idPreProjeto;
            $arrBusca['stabrangencia'] = 1;

            $idUf = 1;
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
                    'Descricao' => '',
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
        $datafinal = "NOW";

        $datainicial = strtotime($datainicial . "+ " . $prazo . " day");
        $datafinal = strtotime($datafinal);
        $datatirada = $datainicial - $datafinal;
        $dias = (($datatirada / 3600) / 24);

        return $dias;
    }

    public function contagemRegressivaSegundos($datainicial = null, $prazo = null)
    {
        $datafinal = "NOW";

        $datainicial = strtotime($datainicial . "+ " . $prazo . " day");
        $datafinal = strtotime($datafinal) + 24 * 3600;
        $segundos = $datainicial - $datafinal;

        return $segundos;
    }

    /**
     * @param $object
     * @param $where
     * @return bool|string
     */
    public function serializarObjeto($object, $where)
    {
        $result = $object->findAll($where);

        if (!$result)
            return false;

        return serialize($result);
    }

    /**
     * @param $result
     * @param null $where
     * @return bool|mixed
     */
    public function unserializarObjeto($object, $idPreProjeto, $metakey = null)
    {
        if (empty($idPreProjeto))
            return false;

        # se n&atilde;o passar o metakey, tenta recuperar a tabela do objeto
        if (empty($metakey))
            $metakey = str_replace('dbo.', '', $object->getTableName());

        $PPM = new Proposta_Model_DbTable_PreProjetoMeta();
        $result = $PPM->buscarMeta($idPreProjeto, $metakey);

        return unserialize($result);
    }

    /**
     * @param $object
     * @param $idPreProjeto
     * @param null $metakey
     * @return bool|int|mixed
     */
    public function salvarObjetoSerializado($object, $idPreProjeto, $metakey = null, $where = null)
    {
        if (empty($where))
            $where = array('idProjeto' => $idPreProjeto);

        $serializado = $this->serializarObjeto($object, $where);

        # se n&atilde;o passar o metakey, salva o nome da tabela do objeto
        if (empty($metakey))
            $metakey = str_replace('dbo.', '', $object->getTableName());

        $PPM = new Proposta_Model_DbTable_PreProjetoMeta();
        return $PPM->salvarMeta($idPreProjeto, $metakey, $serializado);
    }

    /**
     * @param $array
     * @param $idPreProjeto
     * @param $metakey
     * @return bool|int|mixed
     */
    public function salvarArraySerializado($array, $idPreProjeto, $metakey)
    {
        if (empty($metakey))
            return false;

        $serializado = serialize($array);

        $PPM = new Proposta_Model_DbTable_PreProjetoMeta();
        return $PPM->salvarMeta($idPreProjeto, $metakey, $serializado);

    }

    /**
     * @param $object
     * @param $idPreProjeto
     * @param $metakey
     * @param null $whereDelete
     * @return bool
     */
    public function restaurarObjetoSerializadoParaTabela($object, $idPreProjeto, $metakey, $whereDelete = null)
    {
        if (empty($idPreProjeto))
            return false;

        if (empty($metakey))
            return false;

        # metakey de backup para o objeto atual
        $tableName = str_replace('dbo.', '', $object->getTableName());

        if ($tableName == 'PreProjeto' || $tableName == 'tbplanodistribuicao')
            return false;

        # recupera e verifica se os itens existem
        $itens = $this->unserializarObjeto($object, $idPreProjeto, $metakey);

        # se n&atilde;o tiver itens, n&atilde;o eh pra restaurar
        if (empty($itens) || !is_array($itens))
            return false;

        # metakey de backup para o objeto atual
        $metakeybkp = $metakey . "_bkp";

        # salvar objeto atual
        $salvarBkp = $this->salvarObjetoSerializado($object, $idPreProjeto, $metakeybkp);

        # excluir itens atuais
        if ($salvarBkp) {

            if (empty($whereDelete))
                $whereDelete = array('idProjeto' => $idPreProjeto);

            $delete = $object->deleteBy($whereDelete);
        }

        #incluir os novos itens
        if ($delete >= 0) {
            foreach ($itens as $item) {

                $PK = $object->getPrimary();
                $PK = $PK[1];

                if ($item[$PK])
                    unset($item[$PK]);

                $object->insert($item);
            }

            return true;
        }

        return false;
    }

    /**
     *
     * Metodo para salvar uma copia das informacoes da proposta antes do proponente alterar o projeto(proposta)
     * Salva a tbplanilhaproposta, abrangencia, planodistribuicaoproduto e tbdetalhaplanodistribuicao
     *
     * @param $idPreProjeto
     * @return bool
     */
    public function salvarDadosPropostaSerializada($idPreProjeto)
    {
        if (empty($idPreProjeto))
            return false;

        if (!$this->isEditarProjeto($idPreProjeto))
            return false;

        $PPM = new Proposta_Model_DbTable_PreProjetoMeta();

        # Recupera informa&ccedil;&otilde;es da proposta atual
        $proposta = $this->_proposta;


        # Planilha orcamentaria
        $metaPlanilha = $PPM->buscarMeta($idPreProjeto, 'alterarprojeto_tbplanilhaproposta');
        if (!$metaPlanilha) {

            $TPP = new Proposta_Model_DbTable_TbPlanilhaProposta();
            $dadosPlanilhaCompleta = $TPP->buscarPlanilhaCompleta($idPreProjeto);

            $this->view->PlanilhaSalvo = $this->salvarArraySerializado($dadosPlanilhaCompleta, $idPreProjeto, 'alterarprojeto_tbplanilhaproposta');
        } else {
            $this->view->PlanilhaSalvo = true;
        }

        # Local de realizacao (abrangencia)
        $metaAbrangencia = $PPM->buscarMeta($idPreProjeto, 'alterarprojeto_abrangencia');
        if (!$metaAbrangencia) {
            $TPA = new Proposta_Model_DbTable_Abrangencia();
            $abrangenciaCompleta = $TPA->buscar(array('idProjeto' => $idPreProjeto));
            $this->view->AbrangenciaSalvo = $this->salvarArraySerializado($abrangenciaCompleta, $idPreProjeto, 'alterarprojeto_abrangencia');
        } else {
            $this->view->AbrangenciaSalvo = true;
        }

        # Deslocamento
        $metaDeslocamento = $PPM->buscarMeta($idPreProjeto, 'alterarprojeto_deslocamento');
        if (!$metaDeslocamento) {
            $TPD = new Proposta_Model_DbTable_TbDeslocamento();
            $deslocamentoCompleto = $TPD->buscarDeslocamentosGeral(array('idProjeto' => $idPreProjeto));
            $this->view->DeslocamentoSalvo = $this->salvarArraySerializado($deslocamentoCompleto, $idPreProjeto, 'alterarprojeto_deslocamento');
        } else {
            $this->view->DeslocamentoSalvo = true;
        }

        # Plano distribuicao
        $metaPlanoDistribuicao = $PPM->buscarMeta($idPreProjeto, 'alterarprojeto_planodistribuicaoproduto');
        if (!$metaPlanoDistribuicao) {
            $TPDC = new PlanoDistribuicao();
            $planoDistribuicaoCompleto = $TPDC->buscar(array('idProjeto = ?' => $idPreProjeto))->toArray();
            $this->view->PlanoDistribuicaoSalvo = $this->salvarArraySerializado($planoDistribuicaoCompleto, $idPreProjeto, 'alterarprojeto_planodistribuicaoproduto');
        } else {
            $this->view->PlanoDistribuicaoSalvo = true;
        }

        # Plano de distribuicao Detalhado
        $metaPlanoDistribuicaoDetalha = $PPM->buscarMeta($idPreProjeto, 'alterarprojeto_tbdetalhaplanodistribuicao');
        if (!$metaPlanoDistribuicaoDetalha) {
            $TPD = new PlanoDistribuicao();
            $PlanoDetalhado = $TPD->buscarPlanoDistribuicaoDetalhadoByIdProjeto($idPreProjeto);

            $this->view->PlanoDistribuicaoDetalhadoSalvo = $this->salvarArraySerializado($PlanoDetalhado, $idPreProjeto, 'alterarprojeto_tbdetalhaplanodistribuicao');
        } else {
            $this->view->PlanoDistribuicaoDetalhadoSalvo = true;
        }

        # identificacao da proposta
        $metaIdentificacaoProposta = $PPM->buscarMeta($idPreProjeto, 'alterarprojeto_identificacaoproposta');
        if (!$metaIdentificacaoProposta) {

            $identificacaoProposta = array(
                'dtiniciodeexecucao' => $proposta['dtiniciodeexecucaoform'],
                'dtfinaldeexecucao' => $proposta['dtfinaldeexecucaoform']
            );

            $this->view->IdentificaoPropostaSalvo = $this->salvarArraySerializado($identificacaoProposta, $idPreProjeto, 'alterarprojeto_identificacaoproposta');
        } else {
            $this->view->IdentificaoPropostaSalvo = true;
        }

        # responsabilidade social
        $metaResponsabilidadeSocial = $PPM->buscarMeta($idPreProjeto, 'alterarprojeto_responsabilidadesocial');
        if (!$metaResponsabilidadeSocial) {

            $responsabilidadeSocial = array(
                'Acessibilidade' => $proposta['acessibilidade'],
                'DemocratizacaoDeAcesso' => $proposta['democratizacaodeacesso'],
                'ImpactoAmbiental' => $proposta['impactoambiental']
            );

            $this->view->ResponsabilidadeSocialSalvo = $this->salvarArraySerializado($responsabilidadeSocial, $idPreProjeto, 'alterarprojeto_responsabilidadesocial');
        } else {
            $this->view->ResponsabilidadeSocialSalvo = true;
        }

        # detalhes t&eacute;cnicos
        $metadetalhesTecnicos = $PPM->buscarMeta($idPreProjeto, 'alterarprojeto_detalhestecnicos');
        if (!$metadetalhesTecnicos) {

            $detalhesTecnicos = array(
                'EtapaDeTrabalho' => $proposta['etapadetrabalho'],
                'FichaTecnica' => $proposta['fichatecnica'],
                'Sinopse' => $proposta['sinopse'],
                'EspecificacaoTecnica' => $proposta['especificacaotecnica']
            );
            $this->view->DetalhesTecnicosSalvo = $this->salvarArraySerializado($detalhesTecnicos, $idPreProjeto, 'alterarprojeto_detalhestecnicos');
        } else {
            $this->view->DetalhesTecnicosSalvo = true;
        }

        # outras informacoes
        $metaOutrasInformacoes = $PPM->buscarMeta($idPreProjeto, 'alterarprojeto_outrasinformacoes');
        if (!$metaOutrasInformacoes) {

            $outrasInformacoes = array(
                'EstrategiadeExecucao' => $proposta['estrategiadeexecucao']
            );

            $this->view->OutrasInformacoesSalvo = $this->salvarArraySerializado($outrasInformacoes, $idPreProjeto, 'alterarprojeto_outrasinformacoes');
        } else {
            $this->view->OutrasInformacoesSalvo = true;
        }

        return true;
    }

    /**
     *  Devido ao desenho do banco para a tabela tbdetalhaplanodistribuicao, para restaurar o detalhamento dos produtos,
     *  eu tenho que saber o novo id dos produtos inseridos. Tendo em isso em mente, quando for salvar o Plano de distribuicao
     *  do produto, pega o id dele e salva os detalhamentos referentes a ele.
     *
     * @param $idPreProjeto
     * @return bool
     */
    public function restaurarPlanoDistribuicaoDetalhado($idPreProjeto)
    {
        if (empty($idPreProjeto))
            return false;

        $TPD = new PlanoDistribuicao();
        $produtos = $this->unserializarObjeto($TPD, $idPreProjeto, 'alterarprojeto_planodistribuicaoproduto');

        $TPDD = new Proposta_Model_DbTable_TbDetalhamentoPlanoDistribuicaoProduto();
        $detalhamentoProdutos = $this->unserializarObjeto($TPDD, $idPreProjeto, 'alterarprojeto_tbdetalhaplanodistribuicao');

        # se n&atilde;o tiver itens, n&atilde;o eh pra restaurar
        if (empty($produtos) || !is_array($produtos))
            return false;

        if (empty($detalhamentoProdutos) || !is_array($detalhamentoProdutos))
            return false;

        # metakey de backup para os objetos atuais
        $bkpPDP = "alterarprojeto_planodistribuicaoproduto_bkp";
        $bkpPDPD = "alterarprojeto_tbdetalhaplanodistribuicao_bkp";

        # salvar os objetos atuais
        $salvarPDP = $this->salvarObjetoSerializado($TPD, $idPreProjeto, $bkpPDP);

        $PlanoDetalhado = $TPD->buscarPlanoDistribuicaoDetalhadoByIdProjeto($idPreProjeto);
        $salvarPDPD = $this->salvarArraySerializado($PlanoDetalhado, $idPreProjeto, $bkpPDPD);

        # excluir itens atuais
        if ($salvarPDP && $salvarPDPD) {
            $TPD->delete(array('idProjeto = ?' => $idPreProjeto)); # produto
            $TPDD->excluirByIdPreProjeto($idPreProjeto); # detalhamento
        }

        foreach ($produtos as $produto) {
            # Guarda a chave primeira antiga do plano de distribuicao
            $oldIdPlanoDistribuicao = $produto['idPlanoDistribuicao'];

            # Remove a chave primaria antiga
            unset($produto['idPlanoDistribuicao']);

            # Salva como um novo item
            $novoID = $TPD->insert($produto);

            # Varre os detalhamentos do plano de distribuicao anterior e substitui o id pelo atual
            if ($novoID) {
                foreach ($detalhamentoProdutos as $detalhamento) {
                    if ($oldIdPlanoDistribuicao == $detalhamento['idPlanoDistribuicao']) {
                        $detalhamento['idPlanoDistribuicao'] = $novoID;
                        $novosDetalhamento[] = $detalhamento;
                    }
                }
            }
        }
        if ($novosDetalhamento) {
            # Salva o detalhamento dos produtos
            foreach ($novosDetalhamento as $detalhamento) {
                unset($detalhamento['idDetalhaPlanoDistribuicao']);
                $TPDD->insert($detalhamento);
            }
        }
        return true;
    }
}
