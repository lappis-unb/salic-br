<?php
class Agente_Model_TbVinculoProposta extends MinC_Db_Model
{
    protected $_idVinculoproposta;
    protected $_idVinculo;
    protected $_idPreProjeto;
    protected $_siVinculoProposta;

    /**
     * @return mixed
     */
    public function getIdvinculoproposta()
    {
        return $this->_idVinculoproposta;
    }

    /**
     * @param mixed $idVinculoproposta
     * @return Agente_Model_TbVinculoProposta
     */
    public function setIdvinculoproposta($idVinculoproposta)
    {
        $this->_idVinculoproposta = $idVinculoproposta;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIdvinculo()
    {
        return $this->_idVinculo;
    }

    /**
     * @param mixed $idVinculo
     * @return Agente_Model_TbVinculoProposta
     */
    public function setIdvinculo($idVinculo)
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
     * @param mixed $idpreprojeto
     * @return Agente_Model_TbVinculoProposta
     */
    public function setIdPreProjeto($idpreprojeto)
    {
        $this->_idPreProjeto = $idpreprojeto;
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
     * @param mixed $sivinculoproposta
     * @return Agente_Model_TbVinculoProposta
     */
    public function setSiVinculoProposta($sivinculoproposta)
    {
        $this->_siVinculoProposta = $sivinculoproposta;
        return $this;
    }
}
