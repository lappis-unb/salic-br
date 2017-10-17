<?php
class Agente_Model_TbVinculoProposta extends MinC_Db_Model
{
    protected $_idVinculoproposta;
    protected $_idVinculo;
    protected $_idpreprojeto;
    protected $_sivinculoproposta;

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
    public function getIdpreprojeto()
    {
        return $this->_idpreprojeto;
    }

    /**
     * @param mixed $idpreprojeto
     * @return Agente_Model_TbVinculoProposta
     */
    public function setIdpreprojeto($idpreprojeto)
    {
        $this->_idpreprojeto = $idpreprojeto;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getSivinculoproposta()
    {
        return $this->_sivinculoproposta;
    }

    /**
     * @param mixed $sivinculoproposta
     * @return Agente_Model_TbVinculoProposta
     */
    public function setSivinculoproposta($sivinculoproposta)
    {
        $this->_sivinculoproposta = $sivinculoproposta;
        return $this;
    }
}
