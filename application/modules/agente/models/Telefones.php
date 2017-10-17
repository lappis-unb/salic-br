<?php

class Agente_Model_Telefones extends MinC_Db_Model
{
    protected $_idTelefone;
    protected $_idAgente;
    protected $_TipoTelefone;
    protected $_UF;
    protected $_DDD;
    protected $_Numero;
    protected $_Divulgar;
    protected $_Usuario;

    /**
     * @return mixed
     */
    public function getIdTelefone()
    {
        return $this->_idTelefone;
    }

    /**
     * @param mixed $idtelefone
     */
    public function setIdTelefone($idtelefone)
    {
        $this->_idTelefone = $idtelefone;
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
    public function getTipoTelefone()
    {
        return $this->_TipoTelefone;
    }

    /**
     * @param mixed $tipotelefone
     */
    public function setTipoTelefone($tipotelefone)
    {
        $this->_TipoTelefone = $tipotelefone;
    }

    /**
     * @return mixed
     */
    public function getUF()
    {
        return $this->_UF;
    }

    /**
     * @param mixed $uf
     */
    public function setUF($uf)
    {
        $this->_UF = $uf;
    }

    /**
     * @return mixed
     */
    public function getDDD()
    {
        return $this->_DDD;
    }

    /**
     * @param mixed $ddd
     */
    public function setDDD($ddd)
    {
        $this->_DDD = $ddd;
    }

    /**
     * @return mixed
     */
    public function getNumero()
    {
        return $this->_Numero;
    }

    /**
     * @param mixed $numero
     */
    public function setNumero($numero)
    {
        $this->_Numero = $numero;
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
