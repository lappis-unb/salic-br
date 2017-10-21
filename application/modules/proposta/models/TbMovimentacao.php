<?php

class Proposta_Model_TbMovimentacao extends MinC_Db_Model
{
    protected $_idMovimentacao;
    protected $_idProjeto;
    protected $_Movimentacao;
    protected $_DtMovimentacao;
    protected $_stEstado;
    protected $_Usuario;

    /**
     * @return mixed
     */
    public function getIdMovimentacao()
    {
        return $this->_idMovimentacao;
    }

    /**
     * @param mixed $idmovimentacao
     * @return Proposta_Model_TbMovimentacao
     */
    public function setIdMovimentacao($idmovimentacao)
    {
        $this->_idMovimentacao = $idmovimentacao;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIdProjeto()
    {
        return $this->_idProjeto;
    }

    /**
     * @param mixed $idprojeto
     * @return Proposta_Model_TbMovimentacao
     */
    public function setIdProjeto($idprojeto)
    {
        $this->_idProjeto = $idprojeto;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMovimentacao()
    {
        return $this->_Movimentacao;
    }

    /**
     * @param mixed $movimentacao
     * @return Proposta_Model_TbMovimentacao
     */
    public function setMovimentacao($movimentacao)
    {
        $this->_Movimentacao = $movimentacao;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDtMovimentacao()
    {
        return $this->_DtMovimentacao;
    }

    /**
     * @param mixed $dtmovimentacao
     * @return Proposta_Model_TbMovimentacao
     */
    public function setDtMovimentacao($dtmovimentacao)
    {
        $this->_DtMovimentacao = $dtmovimentacao;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStEstado()
    {
        return $this->_stEstado;
    }

    /**
     * @param mixed $stestado
     * @return Proposta_Model_TbMovimentacao
     */
    public function setStEstado($stestado)
    {
        $this->_stEstado = $stestado;
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
     * @return Proposta_Model_TbMovimentacao
     */
    public function setUsuario($usuario)
    {
        $this->_Usuario = $usuario;
        return $this;
    }

}
