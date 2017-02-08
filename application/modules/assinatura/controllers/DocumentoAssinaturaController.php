<?php

class Assinatura_DocumentoAssinaturaController extends Assinatura_GenericController
{

    public function gerarDocumentoAssinaturaAction($idPronac, $idTipoDoAto)
    {
        $this->view->IdPRONAC = $idPronac;

        $objProjeto = new Projetos();
        $this->view->projeto = $objProjeto->findBy(array(
            'IdPRONAC' => $idPronac
        ));

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
        $this->view->titulo = "Enquadramento";

        $objAssinatura = new Assinatura_Model_DbTable_TbAssinatura();
        $this->view->assinaturas = $objAssinatura->obterAssinaturas($idPronac, $idTipoDoAto);

        $objTbAtoAdministrativo = new Assinatura_Model_DbTable_TbAtoAdministrativo();
        $this->view->quantidade_minima_assinaturas = $objTbAtoAdministrativo->obterQuantidadeMinimaAssinaturas($idTipoDoAto);

        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $html = $this->view->render('/documento-assinatura/visualizar-projeto.phtml');
xd($html);
    }

}