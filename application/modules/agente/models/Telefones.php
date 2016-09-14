<?php

/**
 * Modelo Telefone
 *
 *
 * @author wouerner <wouerner@gmail.com>
 * @link   http://www.cultura.gov.br
 * @since  29/03/2010
 */

class Agente_Model_Telefones extends MinC_Db_Model
{
    protected $_idtelefone;
    protected $_idagente;
    protected $_tipotelefone;
    protected $_uf;
    protected $_ddd;
    protected $_numero;
    protected $_divulgar;
    protected $_usuario;

    /**
     * @return mixed
     */
    public function getIdtelefone()
    {
        return $this->_idtelefone;
    }

    /**
     * @param mixed $idtelefone
     */
    public function setIdtelefone($idtelefone)
    {
        $this->_idtelefone = $idtelefone;
    }

    /**
     * @return mixed
     */
    public function getIdagente()
    {
        return $this->_idagente;
    }

    /**
     * @param mixed $idagente
     */
    public function setIdagente($idagente)
    {
        $this->_idagente = $idagente;
    }

    /**
     * @return mixed
     */
    public function getTipotelefone()
    {
        return $this->_tipotelefone;
    }

    /**
     * @param mixed $tipotelefone
     */
    public function setTipotelefone($tipotelefone)
    {
        $this->_tipotelefone = $tipotelefone;
    }

    /**
     * @return mixed
     */
    public function getUf()
    {
        return $this->_uf;
    }

    /**
     * @param mixed $uf
     */
    public function setUf($uf)
    {
        $this->_uf = $uf;
    }

    /**
     * @return mixed
     */
    public function getDdd()
    {
        return $this->_ddd;
    }

    /**
     * @param mixed $ddd
     */
    public function setDdd($ddd)
    {
        $this->_ddd = $ddd;
    }

    /**
     * @return mixed
     */
    public function getNumero()
    {
        return $this->_numero;
    }

    /**
     * @param mixed $numero
     */
    public function setNumero($numero)
    {
        $this->_numero = $numero;
    }

    /**
     * @return mixed
     */
    public function getDivulgar()
    {
        return $this->_divulgar;
    }

    /**
     * @param mixed $divulgar
     */
    public function setDivulgar($divulgar)
    {
        $this->_divulgar = $divulgar;
    }

    /**
     * @return mixed
     */
    public function getUsuario()
    {
        return $this->_usuario;
    }

    /**
     * @param mixed $usuario
     */
    public function setUsuario($usuario)
    {
        $this->_usuario = $usuario;
    }
}
