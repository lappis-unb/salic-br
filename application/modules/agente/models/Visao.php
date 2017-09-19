<?php

class Agente_Model_Visao extends MinC_Db_Model
{
    protected $_idVisao;
    protected $_idAgente;
    protected $_Visao;
    protected $_Usuario;
    protected $_stAtivo;

    /**
     * @return mixed
     */
    public function getIdVisao()
    {
        return $this->_idVisao;
    }

    /**
     * @param mixed $idVisao
     */
    public function setIdVisao($idVisao)
    {
        $this->_idVisao = $idVisao;
    }

    /**
     * @return mixed
     */
    public function getIdAgente()
    {
        return $this->_idAgente;
    }

    /**
     * @param mixed $idagente
     */
    public function setIdAgente($idagente)
    {
        $this->_idAgente = $idagente;
    }

    /**
     * @return mixed
     */
    public function getVisao()
    {
        return $this->_Visao;
    }

    /**
     * @param mixed $visao
     */
    public function setVisao($visao)
    {
        $this->_Visao = $visao;
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
     */
    public function setUsuario($usuario)
    {
        $this->_Usuario = $usuario;
    }

    /**
     * @return mixed
     */
    public function getStAtivo()
    {
        return $this->_stAtivo;
    }

    /**
     * @param mixed $stativo
     */
    public function setStAtivo($stativo)
    {
        $this->_stAtivo = $stativo;
    }
}