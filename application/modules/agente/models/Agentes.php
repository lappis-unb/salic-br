<?php

/**
 * Class Agente_Model_Agentes
 *
 * @name Agente_Model_Agentes
 * @package Modules/Agente
 * @subpackage Models
 *
 * @author Ruy Junior Ferreira Silva <ruyjfs@gmail.com>
 * @since 01/09/2016
 *
 * @link http://salic.cultura.gov.br
 */
class Agente_Model_Agentes extends MinC_Db_Model
{
    protected $_idagente;
    protected $_cnpjcpf;
    protected $_cnpjcpfsuperior;
    protected $_tipopessoa;
    protected $_dtcadastro;
    protected $_dtatualizacao;
    protected $_dtvalidade;
    protected $_status;
    protected $_usuario;

    /**
     * @return mixed
     */
    public function getIdagente()
    {
        return $this->_idagente;
    }

    /**
     * @param mixed $idagente
     * @return Agente_Model_Agentes
     */
    public function setIdagente($idagente)
    {
        $this->_idagente = $idagente;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCnpjcpf()
    {
        return $this->_cnpjcpf;
    }

    /**
     * @param mixed $cnpjcpf
     * @return Agente_Model_Agentes
     */
    public function setCnpjcpf($cnpjcpf)
    {
        $this->_cnpjcpf = $cnpjcpf;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCnpjcpfsuperior()
    {
        return $this->_cnpjcpfsuperior;
    }

    /**
     * @param mixed $cnpjcpfsuperior
     * @return Agente_Model_Agentes
     */
    public function setCnpjcpfsuperior($cnpjcpfsuperior)
    {
        $this->_cnpjcpfsuperior = $cnpjcpfsuperior;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTipopessoa()
    {
        return $this->_tipopessoa;
    }

    /**
     * @param mixed $tipopessoa
     * @return Agente_Model_Agentes
     */
    public function setTipopessoa($tipopessoa)
    {
        $this->_tipopessoa = $tipopessoa;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDtcadastro()
    {
        return $this->_dtcadastro;
    }

    /**
     * @param mixed $dtcadastro
     * @return Agente_Model_Agentes
     */
    public function setDtcadastro($dtcadastro)
    {
        $this->_dtcadastro = $dtcadastro;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDtatualizacao()
    {
        return $this->_dtatualizacao;
    }

    /**
     * @param mixed $dtatualizacao
     * @return Agente_Model_Agentes
     */
    public function setDtatualizacao($dtatualizacao)
    {
        $this->_dtatualizacao = $dtatualizacao;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDtvalidade()
    {
        return $this->_dtvalidade;
    }

    /**
     * @param mixed $dtvalidade
     * @return Agente_Model_Agentes
     */
    public function setDtvalidade($dtvalidade)
    {
        $this->_dtvalidade = $dtvalidade;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->_status;
    }

    /**
     * @param mixed $status
     * @return Agente_Model_Agentes
     */
    public function setStatus($status)
    {
        $this->_status = $status;
        return $this;
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
     * @return Agente_Model_Agentes
     */
    public function setUsuario($usuario)
    {
        $this->_usuario = $usuario;
        return $this;
    }
}
