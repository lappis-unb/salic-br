<?php

class Agente_Model_Nomes extends MinC_Db_Model
{
    protected $_idNome;
    protected $_idAgente;
    protected $_TipoNome;
    protected $_Descricao;
    protected $_Status;
    protected $_Usuario;

    /**
     * @return mixed
     */
    public function getIdNome()
    {
        return $this->_idNome;
    }

    /**
     * @param mixed $idnome
     */
    public function setIdNome($idnome)
    {
        $this->_idNome = $idnome;
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
    public function getTipoNome()
    {
        return $this->_TipoNome;
    }

    /**
     * @param mixed $tiponome
     */
    public function setTipoNome($tiponome)
    {
        $this->_TipoNome = $tiponome;
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
     *
     * @name toArray
     * @return array
     *
     * @author Ruy Junior Ferreira Silva <ruyjfs@gmail.com>
     * @since  01/09/2016
     */
    public function toArray()
    {
        return array(
            'idnome' => self::getIdNome(),
            'idAgente' => self::getIdAgente(),
            'tiponome' => self::getTipoNome(),
            'Descricao' => self::getDescricao(),
            'status' => self::getStatus(),
            'usuario' => self::getUsuario()
        );
    }
}