<?php

class Agente_Model_Internet extends MinC_Db_Model
{
    protected $_idInternet;
    protected $_idAgente;
    protected $_TipoInternet;
    protected $_Descricao;
    protected $_Status;
    protected $_Divulgar;
    protected $_Usuario;

    /**
     * @return mixed
     */
    public function getIdInternet()
    {
        return $this->_idInternet;
    }

    /**
     * @param mixed $idinternet
     */
    public function setIdInternet($idinternet)
    {
        $this->_idInternet = $idinternet;
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
    public function getTipoInternet()
    {
        return $this->_TipoInternet;
    }

    /**
     * @param mixed $tipointernet
     */
    public function setTipoInternet($tipointernet)
    {
        $this->_TipoInternet = $tipointernet;
    }

    /**
     * @return mixed
     */
    public function getDescricao()
    {
        return $this->_Descricao;
    }

    /**
     * @param mixed $descricao
     */
    public function setDescricao($descricao)
    {
        $this->_Descricao = $descricao;
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
     */
    public function setStatus($status)
    {
        $this->_Status = $status;
    }

    /**
     * @return mixed
     */
    public function getDivulgar()
    {
        return $this->_Divulgar;
    }

    /**
     * @param mixed $divulgar
     */
    public function setDivulgar($divulgar)
    {
        $this->_Divulgar = $divulgar;
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


}
