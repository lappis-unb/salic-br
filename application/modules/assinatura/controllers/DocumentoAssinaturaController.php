<?php

class Assinatura_DocumentoAssinaturaController extends Assinatura_GenericController
{

    public function gerarDocumentoAssinatura()
    {
        $get = Zend_Registry::get('get');
        $this->view->IdPRONAC = $get->IdPRONAC;

        $objProjeto = new Projetos();
        $this->view->projeto = $objProjeto->findBy(array(
            'IdPRONAC' => $get->IdPRONAC
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
            'IdPRONAC = ?' => $get->IdPRONAC
        ));

        $objEnquadramento = new Admissibilidade_Model_Enquadramento();
        $arrayPesquisa = array(
            'AnoProjeto' => $this->view->projeto['AnoProjeto'],
            'Sequencial' => $this->view->projeto['Sequencial'],
            'IdPRONAC' => $get->IdPRONAC
        );

        $this->view->dadosEnquadramento = $objEnquadramento->findBy($arrayPesquisa);
        $this->view->titulo = "Enquadramento";

        $objAssinatura = new Assinatura_Model_DbTable_TbAssinatura();
        $this->view->assinaturas = $objAssinatura->obterAssinaturas($get->IdPRONAC, $this->idTipoDoAto);

        $objTbAtoAdministrativo = new Assinatura_Model_DbTable_TbAtoAdministrativo();
        $this->view->quantidade_minima_assinaturas = $objTbAtoAdministrativo->obterQuantidadeMinimaAssinaturas($this->idTipoDoAto);

        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
        $html = $this->view->render('/documento-assinatura/visualizar-projeto.phtml');
xd($html);
    }

}