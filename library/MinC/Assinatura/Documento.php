<?php

class MinC_Assinatura_Documento
{
    public function criarDocumentoAssinatura($idPronac, $idTipoDoAtoAdministrativo)
    {
        $auth = Zend_Auth::getInstance();

        $conteudo = $this->gerarConteudo($idPronac, $idTipoDoAtoAdministrativo);

        $dadosDocumentoAssinatura = array(
            'IdPRONAC' => $idPronac,
            'idTipoDoAtoAdministrativo' => $idTipoDoAtoAdministrativo,
            'conteudo' => $conteudo,
            'idCriadorDocumento' => $auth->getIdentity()->usu_codigo
        );
        $objModelDocumentoAssinatura = new Assinatura_Model_DbTable_TbDocumentoAssinatura();
        $objModelDocumentoAssinatura->inserir($dadosDocumentoAssinatura);
    }

    public function gerarConteudo($idPronac, $idTipoDoAto)
    {
        $view = new Zend_View();
        $view->setScriptPath(APPLICATION_PATH . '/../library/MinC/Assinatura/templates');
        $view->titulo = $this->obterTituloPorTipoDoAto($idTipoDoAto);
        $view->templateTipoDocumento = $this->carregarTemplatePorTipoDoAto(
            $idTipoDoAto,
            $idPronac
        );

        return $view->render('documento-assinatura.phtml');
    }

    protected function obterTituloPorTipoDoAto($idTipoDoAto)
    {

        switch ($idTipoDoAto) {
            case Assinatura_Model_DbTable_TbAssinatura::TIPO_ATO_ENQUADRAMENTO:
                $titulo = "Enquadramento";
                break;
            default:
                $titulo = "Projeto";
                break;
        }

        return $titulo;
    }

    public function carregarTemplatePorTipoDoAto($idTipoDoAto, $idPronac)
    {
        switch ($idTipoDoAto) {
            case Assinatura_Model_DbTable_TbAssinatura::TIPO_ATO_ENQUADRAMENTO:
                return $this->carregarTemplateEnquadramento($idPronac);
                break;
            default:
                break;
        }
    }

    private function carregarTemplateEnquadramento($idPronac) {
        $view = new Zend_View();
        $view->setScriptPath(APPLICATION_PATH . '/../library/MinC/Assinatura/templates/tipo_documento');

        $objPlanoDistribuicaoProduto = new Projeto_Model_vwPlanoDeDistribuicaoProduto();
        $view->dadosProducaoProjeto = $objPlanoDistribuicaoProduto->obterProducaoProjeto(array(
            'IdPRONAC = ?' => $idPronac
        ));

        $view->IdPRONAC = $idPronac;

        $objProjeto = new Projeto_Model_DbTable_Projetos();
        $view->projeto = $objProjeto->findBy(array(
            'IdPRONAC' => $idPronac
        ));

        $objAgentes = new Agente_Model_DbTable_Agentes();
        $dadosAgente = $objAgentes->buscarFornecedor(
            array(
                'a.CNPJCPF = ?' => $view->projeto['CgcCpf']
            )
        );

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
        $view->valoresProjeto = $objProjeto->obterValoresProjeto($idPronac);

        $objProjeto = new Projeto_Model_DbTable_Projetos();
        $dadosProjeto = $objProjeto->findBy(array(
            'IdPRONAC' => $idPronac
        ));

        $objEnquadramento = new Admissibilidade_Model_Enquadramento();
        $arrayPesquisa = array(
            'AnoProjeto' => $dadosProjeto['AnoProjeto'],
            'Sequencial' => $dadosProjeto['Sequencial'],
            'IdPRONAC' => $idPronac
        );

        $view->dadosEnquadramento = $objEnquadramento->findBy($arrayPesquisa);

        return $view->render('enquadramento.phtml');
    }
}