<?php

/**
 * Created by PhpStorm.
 * User: vinnyfs89
 * Date: 08/02/17
 * Time: 11:48
 *
 * @todo Tentar criar namespaces usando o composer.
 */
class MinC_Assinatura_DocumentoAssinatura
{
    public function gerarConteudo($idPronac, $idTipoDoAto)
    {
        $view = new Zend_View();
        $view->setScriptPath(APPLICATION_PATH . '/../library/MinC/Assinatura/templates');

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
        $view->segmentoCultural = $objSegmentocultural->findBy(array(
            'Codigo' => $view->projeto['Segmento']
        ));

        $objPlanoDistribuicaoProduto = new Projeto_Model_vwPlanoDeDistribuicaoProduto();
        $view->dadosProducaoProjeto = $objPlanoDistribuicaoProduto->obterProducaoProjeto(array(
            'IdPRONAC = ?' => $idPronac
        ));

        $objEnquadramento = new Admissibilidade_Model_Enquadramento();
        $arrayPesquisa = array(
            'AnoProjeto' => $view->projeto['AnoProjeto'],
            'Sequencial' => $view->projeto['Sequencial'],
            'IdPRONAC' => $idPronac
        );

        $view->dadosEnquadramento = $objEnquadramento->findBy($arrayPesquisa);
        $view->titulo = $this->obterTituloPorTipoDoAto($idTipoDoAto);

        $objAssinatura = new Assinatura_Model_DbTable_TbAssinatura();
        $view->assinaturas = $objAssinatura->obterAssinaturas($idPronac, $idTipoDoAto);

        $objTbAtoAdministrativo = new Assinatura_Model_DbTable_TbAtoAdministrativo();
        $view->quantidade_minima_assinaturas = $objTbAtoAdministrativo->obterQuantidadeMinimaAssinaturas($idTipoDoAto);

        $this->view->valoresProjeto = $objProjeto->obterValoresProjeto($idPronac);

        return $view->render('documento-assinatura.phtml');
    }

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
}