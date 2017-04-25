<?php

class MinC_Assinatura_Assinatura
{
    /**
     * @var MinC_Assinatura_Core_Autenticacao_IAutenticacaoAdapter $servicoAutenticacao
     */
    private $servicoAutenticacao;

    private $validarOrdemAssinatura = false;

    function __construct()
    {
        //$this->servicoAutenticacao =
    }

    /**
     * @return boolean
     */
    public function isValidarOrdemAssinatura()
    {
        return $this->validarOrdemAssinatura;
    }

    public function setValidarOrdemAssinatura($isValidarOrdemAssinatura)
    {
        if(is_bool($isValidarOrdemAssinatura)) {
            $this->validarOrdemAssinatura = $isValidarOrdemAssinatura;
        }
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

    public function assinarProjeto(MinC_Assinatura_Core_Model_Model_Assinatura $modelAssinatura)
    {

        if (empty($modelAssinatura->getDsManifestacao())) {
            throw new Exception ("Campo \"De acordo do Assinante\" &eacute; de preenchimento obrigat&oacute;rio.");
        }

        if (empty($modelAssinatura->getIdPronac())) {
            throw new Exception ("O n&uacute;mero do projeto &eacute; obrigat&oacute;rio.");
        }

        if (empty($modelAssinatura->getIdTipoDoAtoAdministrativo())) {
            throw new Exception ("O Tipo do Ato Administrativo &eacute; obrigat&oacute;rio.");
        }

        $auth = Zend_Auth::getInstance();

        $objProjeto = new Projeto_Model_DbTable_Projetos();
        $dadosProjeto = $objProjeto->findBy(array(
            'IdPRONAC' => $modelAssinatura->getIdPronac()
        ));

        $objEnquadramento = new Admissibilidade_Model_Enquadramento();
        $arrayPesquisa = array(
            'AnoProjeto' => $dadosProjeto['AnoProjeto'],
            'Sequencial' => $dadosProjeto['Sequencial'],
            'IdPRONAC' => $modelAssinatura->getIdPronac()
        );

        $dadosEnquadramento = $objEnquadramento->findBy($arrayPesquisa);
        $objModelDocumentoAssinatura = new Assinatura_Model_DbTable_TbDocumentoAssinatura();
        $dadosDocumentoAssinatura = $objModelDocumentoAssinatura->findBy(
            array(
                'IdPRONAC' => $modelAssinatura->getIdPronac(),
                'idTipoDoAtoAdministrativo' => $modelAssinatura->getIdTipoDoAtoAdministrativo()
            )
        );

        $objTbAtoAdministrativo = new Assinatura_Model_DbTable_TbAtoAdministrativo();
        $dadosAtoAdministrativoAtual = $objTbAtoAdministrativo->obterAtoAdministrativoAtual(
            $modelAssinatura->getIdTipoDoAtoAdministrativo(),
//            $this->grupoAtivo->codGrupo,
//            $this->grupoAtivo->codOrgao
            $modelAssinatura->getCodGrupo(),
            $modelAssinatura->getCodOrgao()
        );

        if (!$dadosAtoAdministrativoAtual) {
            throw new Exception ("A fase atual de assinaturas do projeto atual n&atilde;o permite realizar essa opera&ccedil;&atilde;o.");
        }

        $usuario = $this->servicoAutenticacao->obterInformacoesAssinante();
        $objTbAssinatura = new Assinatura_Model_DbTable_TbAssinatura();

        $dadosInclusaoAssinatura = array(
            'idPronac' => $modelAssinatura->getIdPronac(),
            'idAtoAdministrativo' => $dadosAtoAdministrativoAtual['idAtoAdministrativo'],
            'idAtoDeGestao' => $dadosEnquadramento['IdEnquadramento'],
            'dtAssinatura' => $objEnquadramento->getExpressionDate(),
            'idAssinante' => $usuario['usu_codigo'],
            'dsManifestacao' => $modelAssinatura->getDsManifestacao(),
            'idDocumentoAssinatura' => $dadosDocumentoAssinatura['idDocumentoAssinatura']
        );

        $objTbAssinatura->inserir($dadosInclusaoAssinatura);

        if($this->isValidarOrdemAssinatura()) {
            $orgaoDestino = $objTbAtoAdministrativo->obterProximoOrgaoDeDestino($modelAssinatura->getIdTipoDoAtoAdministrativo(), $dadosAtoAdministrativoAtual['idOrdemDaAssinatura']);

            if ($orgaoDestino) {
                $objTbProjetos = new Projeto_Model_DbTable_Projetos();
                $objTbProjetos->alterarOrgao($orgaoDestino, $modelAssinatura->getIdPronac());
            }
        }
    }

}