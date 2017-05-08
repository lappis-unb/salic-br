<?php

class MinC_Assinatura_Servico_Assinatura implements MinC_Assinatura_Servico_IServico
{
    /**
     * @var MinC_Assinatura_Autenticacao_IAutenticacaoAdapter $metodoAutenticacao
     */
    private $metodoAutenticacao;

    /**
     * @var MinC_Assinatura_Servico_Documento $servicoDocumento
     */
    private $servicoDocumento;

    private $isValidarOrdemAssinatura = true;

    function __construct($post, $identidadeUsuarioLogado)
    {

xd($configuracoesAplicacao);
        $servicoAutenticacao = new MinC_Assinatura_Servico_Autenticacao($post, $identidadeUsuarioLogado);
        $this->metodoAutenticacao = $servicoAutenticacao->obterMetodoAutenticacao();
        $this->servicoDocumento = new MinC_Assinatura_Servico_Documento();

    }

    public function validarOrdemDeAssinaturas($isValidarOrdemAssinatura)
    {
        $this->isValidarOrdemAssinatura = $isValidarOrdemAssinatura;
    }

    /**
     * @return MinC_Assinatura_Autenticacao_IAutenticacaoAdapter
     */
    public function obterServicoAutenticacao() {
        return $this->metodoAutenticacao;
    }

    /**
     * @return MinC_Assinatura_Servico_Documento
     */
    public function obterServicoDocumento() {
        return $this->servicoDocumento;
    }

    public function assinarProjeto(MinC_Assinatura_Model_Assinatura $modelAssinatura)
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


        $objModelDocumentoAssinatura = new Assinatura_Model_DbTable_TbDocumentoAssinatura();
        $dadosDocumentoAssinatura = $objModelDocumentoAssinatura->findBy(
            array(
                'IdPRONAC' => $modelAssinatura->getIdPronac(),
                'idTipoDoAtoAdministrativo' => $modelAssinatura->getIdTipoDoAtoAdministrativo(),
                'cdSituacao' => Assinatura_Model_TbDocumentoAssinatura::CD_SITUACAO_DISPONIVEL_PARA_ASSINATURA
            )
        );

        $objTbAtoAdministrativo = new Assinatura_Model_DbTable_TbAtoAdministrativo();
        $dadosAtoAdministrativoAtual = $objTbAtoAdministrativo->obterAtoAdministrativoAtual(
            $modelAssinatura->getIdTipoDoAtoAdministrativo(),
            $modelAssinatura->getCodGrupo(),
            $modelAssinatura->getCodOrgao()
        );

        if (!$dadosAtoAdministrativoAtual) {
            throw new Exception ("A fase atual de assinaturas do projeto atual n&atilde;o permite realizar essa opera&ccedil;&atilde;o.");
        }

        $usuario = $this->obterServicoAutenticacao()->obterInformacoesAssinante();
        $objTbAssinatura = new Assinatura_Model_DbTable_TbAssinatura();

        $dadosInclusaoAssinatura = array(
            'idPronac' => $modelAssinatura->getIdPronac(),
            'idAtoAdministrativo' => $dadosAtoAdministrativoAtual['idAtoAdministrativo'],
            'idAtoDeGestao' => $modelAssinatura->getIdAtoGestao(), //$dadosEnquadramento['IdEnquadramento']
            'dtAssinatura' => $objTbAtoAdministrativo->getExpressionDate(),
            'idAssinante' => $usuario['usu_codigo'],
            'dsManifestacao' => $modelAssinatura->getDsManifestacao(),
            'idDocumentoAssinatura' => $dadosDocumentoAssinatura['idDocumentoAssinatura']
        );

        $objTbAssinatura->inserir($dadosInclusaoAssinatura);

        if($this->isValidarOrdemAssinatura) {
            $orgaoDestino = $objTbAtoAdministrativo->obterProximoOrgaoDeDestino($modelAssinatura->getIdTipoDoAtoAdministrativo(), $dadosAtoAdministrativoAtual['idOrdemDaAssinatura']);

            if ($orgaoDestino) {
                $objTbProjetos = new Projeto_Model_DbTable_Projetos();
                $objTbProjetos->alterarOrgao($orgaoDestino, $modelAssinatura->getIdPronac());
            }
        }
    }

}