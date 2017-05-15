<?php

class MinC_Assinatura_Servico_Assinatura implements MinC_Assinatura_Servico_IServico
{
    /**
     * @var MinC_Assinatura_Autenticacao_IAutenticacaoAdapter $servicoAutenticacao
     */
    private $servicoAutenticacao;

    /**
     * @var MinC_Assinatura_Servico_DocumentoAssinatura $servicoDocumentoAssinatura
     */
    private $servicoDocumentoAssinatura;

    public $post;

    public $identidadeUsuarioLogado;

    public $isMovimentarProjetoPorOrdemAssinatura = true;

    function __construct($post, $identidadeUsuarioLogado)
    {
        $this->post = $post;
        $this->identidadeUsuarioLogado = $identidadeUsuarioLogado;
    }

    /**
     * @return MinC_Assinatura_Autenticacao_IAutenticacaoAdapter
     */
    public function obterServicoAutenticacao() {
        if(!isset($this->servicoAutenticacao)) {
            $this->servicoAutenticacao = new MinC_Assinatura_Servico_Autenticacao($this->post, $this->identidadeUsuarioLogado);
        }
        return $this->servicoAutenticacao;
    }

    /**
     * @return MinC_Assinatura_Servico_DocumentoAssinatura
     */
    public function obterServicoDocumento() {
        if(!isset($this->servicoDocumentoAssinatura)) {
            $this->servicoDocumentoAssinatura = new MinC_Assinatura_Servico_DocumentoAssinatura();
        }
        return $this->servicoDocumentoAssinatura;
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

        $servicoAutenticacao = $this->obterServicoAutenticacao();
        if(!$servicoAutenticacao->autenticar()) {
            throw new Exception ("Os dados utilizados para autentica&ccedil;&atilde;o s&atilde;o inv&aacute;lidos.");
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

        $usuario = $servicoAutenticacao->obterInformacoesAssinante();
        $objTbAssinatura = new Assinatura_Model_DbTable_TbAssinatura();

        $assinaturaExistente = $objTbAssinatura->buscar(array(
            'idPronac = ?' => $modelAssinatura->getIdPronac(),
            'idAtoAdministrativo = ?' => $dadosAtoAdministrativoAtual['idAtoAdministrativo'],
            'idAssinante = ?' => $usuario['usu_codigo'],
            'idDocumentoAssinatura = ?' => $dadosDocumentoAssinatura['idDocumentoAssinatura']
        ));

        if($assinaturaExistente) {
            throw new Exception ("O documento j&aacute; foi assinado pelo usu&aacute;rio logado nesta fase atual.");
        }

        $dadosInclusaoAssinatura = array(
            'idPronac' => $modelAssinatura->getIdPronac(),
            'idAtoAdministrativo' => $dadosAtoAdministrativoAtual['idAtoAdministrativo'],
            'dtAssinatura' => $objTbAtoAdministrativo->getExpressionDate(),
            'idAssinante' => $usuario['usu_codigo'],
            'dsManifestacao' => $modelAssinatura->getDsManifestacao(),
            'idDocumentoAssinatura' => $dadosDocumentoAssinatura['idDocumentoAssinatura']
        );

        $objTbAssinatura->inserir($dadosInclusaoAssinatura);

        if($this->isMovimentarProjetoPorOrdemAssinatura) {
            $orgaoDestino = $objTbAtoAdministrativo->obterProximoOrgaoDeDestino($modelAssinatura->getIdTipoDoAtoAdministrativo(), $dadosAtoAdministrativoAtual['idOrdemDaAssinatura']);

            if ($orgaoDestino) {
                $objTbProjetos = new Projeto_Model_DbTable_Projetos();
                $objTbProjetos->alterarOrgao($orgaoDestino, $modelAssinatura->getIdPronac());
            }
        }
    }

}