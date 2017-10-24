<?php
class Agente_Model_TbVinculoProposta extends MinC_Db_Model
{
    protected $_idVinculoProposta;
    protected $_idVinculo;
    protected $_idPreProjeto;
    protected $_siVinculoProposta;

    /**
     * @return mixed
     */
    public function getIdVinculoProposta()
    {
        return $this->_idVinculoProposta;
    }

    /**
     * @param mixed $idVinculoProposta
     * @return Agente_Model_TbVinculoProposta
     */
    public function setIdVinculoProposta($idVinculoProposta)
    {
        $this->_idVinculoProposta = $idVinculoProposta;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIdVinculo()
    {
        return $this->_idVinculo;
    }

    /**
     * @param mixed $idVinculo
     * @return Agente_Model_TbVinculoProposta
     */
    public function setIdVinculo($idVinculo)
    {
        $this->_idVinculo = $idVinculo;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIdPreProjeto()
    {
        return $this->_idPreProjeto;
    }

    /**
     * @param mixed $idPreProjeto
     * @return Agente_Model_TbVinculoProposta
     */
    public function setIdPreProjeto($idPreProjeto)
    {
        $this->_idPreProjeto = $idPreProjeto;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSiVinculoProposta()
    {
        return $this->_siVinculoProposta;
    }

    /**
     * @param mixed $siVinculoProposta
     * @return Agente_Model_TbVinculoProposta
     */
    public function setSiVinculoProposta($siVinculoProposta)
    {
        $this->_siVinculoProposta = $siVinculoProposta;
        return $this;
    }
}
