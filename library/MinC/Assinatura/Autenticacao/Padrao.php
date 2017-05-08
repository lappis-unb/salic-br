<?php

class MinC_Assinatura_Autenticacao_Padrao implements MinC_Assinatura_Autenticacao_IAutenticacaoAdapter
{
    /**
     * @var Autenticacao_Model_Usuario $usuario
     */
    private $usuario;

    public function __construct(stdClass $post, stdClass $identidadeUsuarioLogado)
    {
        $this->usuario = new Autenticacao_Model_Usuario();
        $this->usuario->setUsuIdentificacao($identidadeUsuarioLogado->usu_codigo);
        $this->usuario->setUsuSenha($post['password']);
    }

    /**
     * @return boolean
     */
    public function autenticar()
    {
        $isUsuarioESenhaValidos = $this->usuario->isUsuarioESenhaValidos();
        if (!$isUsuarioESenhaValidos) {
            throw new Exception ("Usu&aacute;rio ou Senha inv&aacute;lida.");
        }
    }

    /**
     * @return array
     */
    public function obterInformacoesAssinante()
    {
        $usuariosBuscar = $this->usuario->buscar(array('usu_identificacao = ?' => $this->usuario))->current();
        return $usuariosBuscar->toArray();
    }

    /**
     * @return string
     */
    public function obterTemplateAutenticacao()
    {
        $view = new Zend_View();
        $view->setScriptPath(APPLICATION_PATH . '/../library/MinC/Assinatura/templates/tipo_documento');

//        $objPlanoDistribuicaoProduto = new Projeto_Model_vwPlanoDeDistribuicaoProduto();
//        $view->dadosProducaoProjeto = $objPlanoDistribuicaoProduto->obterProducaoProjeto(array(
//            'IdPRONAC = ?' => $idPronac
//        ));
//
//        $view->IdPRONAC = $idPronac;
//
//        $objProjeto = new Projeto_Model_DbTable_Projetos();
//        $view->projeto = $objProjeto->findBy(array(
//            'IdPRONAC' => $idPronac
//        ));
//
//        $objAgentes = new Agente_Model_DbTable_Agentes();
//        $dadosAgente = $objAgentes->buscarFornecedor(
//            array(
//                'a.CNPJCPF = ?' => $view->projeto['CgcCpf']
//            )
//        );
//
//        $arrayDadosAgente = $dadosAgente->current();
//        $view->nomeAgente = $arrayDadosAgente['nome'];
//
//        $mapperArea = new Agente_Model_AreaMapper();
//        $view->areaCultural = $mapperArea->findBy(array(
//            'Codigo' => $view->projeto['Area']
//        ));
//        $objSegmentocultural = new Segmentocultural();
//        $view->segmentoCultural = $objSegmentocultural->findBy(
//            array(
//                'Codigo' => $view->projeto['Segmento']
//            )
//        );
//        $view->valoresProjeto = $objProjeto->obterValoresProjeto($idPronac);
//
//        $objProjeto = new Projeto_Model_DbTable_Projetos();
//        $dadosProjeto = $objProjeto->findBy(array(
//            'IdPRONAC' => $idPronac
//        ));
//
//        $objEnquadramento = new Admissibilidade_Model_Enquadramento();
//        $arrayPesquisa = array(
//            'AnoProjeto' => $dadosProjeto['AnoProjeto'],
//            'Sequencial' => $dadosProjeto['Sequencial'],
//            'IdPRONAC' => $idPronac
//        );
//
//        $view->dadosEnquadramento = $objEnquadramento->findBy($arrayPesquisa);
//
//        return $view->render('enquadramento.phtml');
    }
}