<?php

class Agente_Model_Agentes extends MinC_Db_Model
{
    protected $_idAgente;
    protected $_CNPJCPF;
    protected $_CNPJCPFSuperior;
    protected $_TipoPessoa;
    protected $_DtCadastro;
    protected $_DtAtualizacao;
    protected $_DtValidade;
    protected $_Status;
    protected $_Usuario;

    /**
     * @return mixed
     */
    public function getIdAgente()
    {
        return $this->_idAgente;
    }

    /**
     * @param mixed $idagente
     * @return Agente_Model_Agentes
     */
    public function setIdAgente($idagente)
    {
        $this->_idAgente = $idagente;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCNPJCPF()
    {
        return $this->_CNPJCPF;
    }

    /**
     * @param mixed $cnpjcpf
     * @return Agente_Model_Agentes
     */
    public function setCNPJCPF($cnpjcpf)
    {
        $this->_CNPJCPF = Mascara::delMaskCNPJ(trim($cnpjcpf));
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCNPJCPFSuperior()
    {
        return $this->_CNPJCPFSuperior;
    }

    /**
     * @param mixed $cnpjcpfsuperior
     * @return Agente_Model_Agentes
     */
    public function setCNPJCPFSuperior($cnpjcpfsuperior)
    {
        $this->_CNPJCPFSuperior = $cnpjcpfsuperior;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTipoPessoa()
    {
        return $this->_TipoPessoa;
    }

    /**
     * @param mixed $tipopessoa
     * @return Agente_Model_Agentes
     */
    public function setTipoPessoa($tipopessoa)
    {
        $this->_TipoPessoa = $tipopessoa;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDtCadastro()
    {
        return $this->_DtCadastro;
    }

    /**
     * @param mixed $dtcadastro
     * @return Agente_Model_Agentes
     */
    public function setDtCadastro($dtcadastro)
    {
        $this->_DtCadastro = $dtcadastro;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDtAtualizacao()
    {
        return $this->_DtAtualizacao;
    }

    /**
     * @param mixed $dtatualizacao
     * @return Agente_Model_Agentes
     */
    public function setDtAtualizacao($dtatualizacao)
    {
        $this->_DtAtualizacao = $dtatualizacao;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDtValidade()
    {
        return $this->_DtValidade;
    }

    /**
     * @param mixed $dtvalidade
     * @return Agente_Model_Agentes
     */
    public function setDtValidade($dtvalidade)
    {
        $this->_DtValidade = $dtvalidade;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->_Status;
    }

    /**
     * @param mixed $status
     * @return Agente_Model_Agentes
     */
    public function setStatus($status)
    {
        $this->_Status = $status;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUsuario()
    {
        return $this->_Usuario;
    }

    /**
     * @param mixed $usuario
     * @return Agente_Model_Agentes
     */
    public function setUsuario($usuario)
    {
        $this->_Usuario = $usuario;
        return $this;
    }
}
