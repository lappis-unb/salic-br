<?php

class MinC_Assinatura_Model_Assinatura implements MinC_Assinatura_Model_IModelAssinatura
{
    /**
     * @var int $idPronac
     */
    private $idPronac;

    /**
     * @var string $dsManifestacao
     */
    private $dsManifestacao;

    /**
     * @var int $idTipoDoAtoAdministrativo
     */
    private $idTipoDoAtoAdministrativo;

    /**
     * @var int $cod_grupo
     */
    private $cod_grupo;

    /**
     * @var int $cod_orgao
     */
    private $cod_orgao;

    /**
     * @return int
     */
    public function getCodGrupo()
    {
        return $this->cod_grupo;
    }

    /**
     * @param int $cod_grupo
     * @return MinC_Assinatura_Model_Assinatura
     */
    public function setCodGrupo($cod_grupo)
    {
        $this->cod_grupo = $cod_grupo;
        return $this;
    }

    /**
     * @return int
     */
    public function getCodOrgao()
    {
        return $this->cod_orgao;
    }

    /**
     * @param int $cod_orgao
     * @return MinC_Assinatura_Model_Assinatura
     */
    public function setCodOrgao($cod_orgao)
    {
        $this->cod_orgao = $cod_orgao;
        return $this;
    }

    /**
     * @return int
     */
    public function getIdPronac()
    {
        return $this->idPronac;
    }

    /**
     * @param int $idPronac
     * @return MinC_Assinatura_Model_Assinatura
     */
    public function setIdPronac($idPronac)
    {
        $this->idPronac = $idPronac;
        return $this;
    }

    /**
     * @return MinC_Assinatura_Core_Autenticacao_IAutenticacaoAdapter
     */
    public function getServicoAutenticacao()
    {
        return $this->servicoAutenticacao;
    }

    /**
     * @param MinC_Assinatura_Core_Autenticacao_IAutenticacaoAdapter $servicoAutenticacao
     * @return MinC_Assinatura_Model_Assinatura
     */
    public function setServicoAutenticacao($servicoAutenticacao)
    {
        $this->servicoAutenticacao = $servicoAutenticacao;
        return $this;
    }

    /**
     * @return string
     */
    public function getDsManifestacao()
    {
        return $this->dsManifestacao;
    }

    /**
     * @param string $dsManifestacao
     * @return MinC_Assinatura_Model_Assinatura
     */
    public function setDsManifestacao($dsManifestacao)
    {
        $this->dsManifestacao = $dsManifestacao;
        return $this;
    }

    /**
     * @return int
     */
    public function getIdTipoDoAtoAdministrativo()
    {
        return $this->idTipoDoAtoAdministrativo;
    }

    /**
     * @param int $idTipoDoAtoAdministrativo
     * @return MinC_Assinatura_Model_Assinatura
     */
    public function setIdTipoDoAtoAdministrativo($idTipoDoAtoAdministrativo)
    {
        $this->idTipoDoAtoAdministrativo = $idTipoDoAtoAdministrativo;
        return $this;
    }
}