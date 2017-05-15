<?php

class Admissibilidade_EnquadramentoDocumentoAssinaturaController implements MinC_Assinatura_Documento_IDocumentoAssinatura
{
    public $idPronac;

    function encaminharProjetoParaAssinatura() {

        if(!$this->idPronac) {
            throw new Exception("Identificador do Projeto não informado.");
        }

        $objTbProjetos = new Projeto_Model_DbTable_Projetos();
        $dadosProjeto = $objTbProjetos->findBy(array('IdPRONAC' => $this->idPronac));

        if(!$dadosProjeto) {
            throw new Exception("Projeto n&atilde;o encontrado.");
        }

        if($dadosProjeto['Situacao'] != 'B02' && $dadosProjeto['Situacao'] != 'B03') {
            throw new Exception("Situa&ccedil;&atilde;o do projeto inv&aacute;lida!");
        }

        $post = $this->getRequest()->getPost();
        $objDocumentoAssinatura = new MinC_Assinatura_Servico_Assinatura($post, $this->auth->getIdentity());
        $idTipoDoAtoAdministrativo = Assinatura_Model_DbTable_TbAssinatura::TIPO_ATO_ENQUADRAMENTO;

        $enquadramento = new Admissibilidade_Model_Enquadramento();
        $dadosEnquadramento = $enquadramento->obterEnquadramentoPorProjeto($this->idPronac, $dadosProjeto['AnoProjeto'], $dadosProjeto['Sequencial']);

        $objModelDocumentoAssinatura = new Assinatura_Model_TbDocumentoAssinatura();
        $objModelDocumentoAssinatura->setIdPRONAC($this->idPronac);
        $objModelDocumentoAssinatura->setIdTipoDoAtoAdministrativo($idTipoDoAtoAdministrativo);
        $objModelDocumentoAssinatura->setIdAtoDeGestao($dadosEnquadramento['IdEnquadramento']);
        $objModelDocumentoAssinatura->setConteudo($this->gerarDocumentoAssinatura());
        $auth = Zend_Auth::getInstance();
        $objModelDocumentoAssinatura->setIdCriadorDocumento($auth->getIdentity()->usu_codigo);

        $servicoDocumento = $objDocumentoAssinatura->obterServicoDocumento();
        $servicoDocumento->registrarDocumentoAssinatura($objModelDocumentoAssinatura);

        $objProjeto = new Projetos();
        $objProjeto->alterarSituacao($this->idPronac, null, 'B04', 'Projeto encamihado para Portaria.');

        $orgaoDestino = 166;
        $objOrgaos = new Orgaos();
        $dadosOrgaoSuperior = $objOrgaos->obterOrgaoSuperior($dadosProjeto['Orgao']);
        if ($dadosOrgaoSuperior['Codigo'] == Orgaos::ORGAO_SUPERIOR_SEFIC) {
            $orgaoDestino = 262;
        }
        $objTbProjetos->alterarOrgao($orgaoDestino, $this->idPronac);
    }

    /**
     * @return string
     */
    function gerarDocumentoAssinatura()
    {
        $view = new Zend_View();
        $view->setScriptPath(__DIR__ . '/views/scripts/enquadramento-documento-assinatura');

        $view->titulo = 'Enquadramento';

        $objPlanoDistribuicaoProduto = new Projeto_Model_vwPlanoDeDistribuicaoProduto();
        $view->dadosProducaoProjeto = $objPlanoDistribuicaoProduto->obterProducaoProjeto(array(
            'IdPRONAC = ?' => $this->idPronac
        ));

        $view->IdPRONAC = $this->idPronac;

        $objProjeto = new Projeto_Model_DbTable_Projetos();
        $view->projeto = $objProjeto->findBy(array('IdPRONAC' => $this->idPronac));

        $objAgentes = new Agente_Model_DbTable_Agentes();
        $dadosAgente = $objAgentes->buscarFornecedor(array('a.CNPJCPF = ?' => $view->projeto['CgcCpf']));

        $arrayDadosAgente = $dadosAgente->current();
        $view->nomeAgente = $arrayDadosAgente['nome'];

        $mapperArea = new Agente_Model_AreaMapper();
        $view->areaCultural = $mapperArea->findBy(array(
            'Codigo' => $view->projeto['Area']
        ));
        $objSegmentocultural = new Segmentocultural();
        $view->segmentoCultural = $objSegmentocultural->findBy(
            array(
                'Codigo' => $view->projeto['Segmento']
            )
        );
        $view->valoresProjeto = $objProjeto->obterValoresProjeto($this->idPronac);

        $objProjeto = new Projeto_Model_DbTable_Projetos();
        $dadosProjeto = $objProjeto->findBy(array(
            'IdPRONAC' => $this->idPronac
        ));

        $objEnquadramento = new Admissibilidade_Model_Enquadramento();
        $arrayPesquisa = array(
            'AnoProjeto' => $dadosProjeto['AnoProjeto'],
            'Sequencial' => $dadosProjeto['Sequencial'],
            'IdPRONAC' => $this->idPronac
        );

        $view->dadosEnquadramento = $objEnquadramento->findBy($arrayPesquisa);

        $view->templateTipoDocumento = $view->render('enquadramento.phtml');

        return $view->render('documento-assinatura.phtml');
    }
}
