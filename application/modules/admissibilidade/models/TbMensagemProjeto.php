<?php

class Admissibilidade_Model_TbMensagemProjeto extends MinC_Db_Model
{
    protected $_idMensagemProjeto;
    protected $_dtMensagem;
    protected $_dsMensagem;
    protected $_stAtivo;
    protected $_cdTipoMensagem;
    protected $_idDestinatario;
    protected $_idDestinatarioUnidade;
    protected $_idRemetente;
    protected $_idRemetenteUnidade;
    protected $_IdPRONAC;
    protected $_idMensagemOrigem;

    /**
     * @return mixed
     */
    public function getIdDestinatarioUnidade()
    {
        return $this->_idDestinatarioUnidade;
    }

    /**
     * @param mixed $idDestinatarioUnidade
     * @return Admissibilidade_Model_TbMensagemProjeto
     */
    public function setIdDestinatarioUnidade($idDestinatarioUnidade)
    {
        $this->_idDestinatarioUnidade = $idDestinatarioUnidade;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIdRemetenteUnidade()
    {
        return $this->_idRemetenteUnidade;
    }

    /**
     * @param mixed $idRemetenteUnidade
     * @return Admissibilidade_Model_TbMensagemProjeto
     */
    public function setIdRemetenteUnidade($idRemetenteUnidade)
    {
        $this->_idRemetenteUnidade = $idRemetenteUnidade;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIdMensagemProjeto()
    {
        return $this->_idMensagemProjeto;
    }

    /**
     * @param mixed $idMensagemProjeto
     * @return Agente_Model_Agentes
     */
    public function setIdMensagemProjeto($idMensagemProjeto)
    {
        $this->_idMensagemProjeto = $idMensagemProjeto;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDtMensagem()
    {
        return $this->_dtMensagem;
    }

    /**
     * @param mixed $dtMensagem
     * @return Agente_Model_Agentes
     */
    public function setDtMensagem($dtMensagem)
    {
        $this->_dtMensagem = $dtMensagem;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDsMensagem()
    {
        return $this->_dsMensagem;
    }

    /**
     * @param mixed $dsMensagem
     * @return Agente_Model_Agentes
     */
    public function setDsMensagem($dsMensagem)
    {
        $this->_dsMensagem = $dsMensagem;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStAtivo()
    {
        return $this->_stAtivo;
    }

    /**
     * @param mixed $stAtivo
     * @return Agente_Model_Agentes
     */
    public function setStAtivo($stAtivo)
    {
        $this->_stAtivo = $stAtivo;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCdTipoMensagem()
    {
        return $this->_cdTipoMensagem;
    }

    /**
     * @param mixed $cdTipoMensagem
     * @return Agente_Model_Agentes
     */
    public function setCdTipoMensagem($cdTipoMensagem)
    {
        $this->_cdTipoMensagem = $cdTipoMensagem;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIdDestinatario()
    {
        return $this->_idDestinatario;
    }

    /**
     * @param mixed $idDestinatario
     * @return Agente_Model_Agentes
     */
    public function setIdDestinatario($idDestinatario)
    {
        $this->_idDestinatario = $idDestinatario;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIdRemetente()
    {
        return $this->_idRemetente;
    }

    /**
     * @param mixed $idRemetente
     * @return Agente_Model_Agentes
     */
    public function setIdRemetente($idRemetente)
    {
        $this->_idRemetente = $idRemetente;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIdPRONAC()
    {
        return $this->_IdPRONAC;
    }

    /**
     * @param mixed $IdPRONAC
     * @return Agente_Model_Agentes
     */
    public function setIdPRONAC($IdPRONAC)
    {
        $this->_IdPRONAC = $IdPRONAC;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIdMensagemOrigem()
    {
        return $this->_idMensagemOrigem;
    }

    /**
     * @param mixed $idMensagemOrigem
     * @return Agente_Model_Agentes
     */
    public function setIdMensagemOrigem($idMensagemOrigem)
    {
        $this->_idMensagemOrigem = $idMensagemOrigem;
        return $this;
    }
}
