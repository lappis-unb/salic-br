<?php
/**
 * @name Agente_Model_TbVinculoProposta
 * @package Modules/Agente
 * @subpackage Models
 *
 * @author Ruy Junior Ferreira Silva <ruyjfs@gmail.com>
 * @since 14/10/2016
 *
 * @link http://salic.cultura.gov.br
 */
class Agente_Model_TbVinculoProposta extends MinC_Db_Model
{
    protected $_idvinculoproposta;
    protected $_idvinculo;
    protected $_idpreprojeto;
    protected $_sivinculoproposta;

    /**
     * @return mixed
     */
    public function getIdvinculoproposta()
    {
        return $this->_idvinculoproposta;
    }

    /**
     * @param mixed $idvinculoproposta
     * @return Agente_Model_TbVinculoProposta
     */
    public function setIdvinculoproposta($idvinculoproposta)
    {
        $this->_idvinculoproposta = $idvinculoproposta;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getIdvinculo()
    {
        return $this->_idvinculo;
    }

    /**
     * @param mixed $idvinculo
     * @return Agente_Model_TbVinculoProposta
     */
    public function setIdvinculo($idvinculo)
    {
        $this->_idvinculo = $idvinculo;
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
