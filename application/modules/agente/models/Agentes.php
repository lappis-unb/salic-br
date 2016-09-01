<?php

/**
 * Class Agente_Model_Agentes
 *
 * @name Agente_Model_Agentes
 * @package Modules/Agente
 * @subpackage Models
 * @version $Id$
 *
 * @author Ruy Junior Ferreira Silva <ruyjfs@gmail.com>
 * @since 01/09/2016
 *
 * @copyright © 2012 - Ministerio da Cultura - Todos os direitos reservados.
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
     * @param mixed $idagente
     */
    public function setIdagente($idagente)
    {
        $this->_idagente = $idagente;
    }

    /**
     * @return mixed
     */
    public function getIdagente()
    {
        return $this->_idagente;
    }

    /**
     * @param string $cnpjcpf
     */
    public function setCnpjcpf($cnpjcpf)
    {
        $this->_cnpjcpf = (string) Mascara::delMaskCPFCNPJ($cnpjcpf);
    }

    /**
     * @return string
     */
    public function getCnpjcpf()
    {
        return $this->_cnpjcpf;
    }

    /**
     * @return string
     */
    public function getCnpjcpfMask()
    {
        return Mascara::addMaskCpfCnpj($this->_cnpjcpf);
    }

    /**
     * @param mixed $cnpjcpfsuperior
     */
    public function setCnpjcpfsuperior($cnpjcpfsuperior)
    {
        $this->_cnpjcpfsuperior = $cnpjcpfsuperior;
    }

    /**
     * @return mixed
     */
    public function getCnpjcpfsuperior()
    {
        return $this->_cnpjcpfsuperior;
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
     */
    public function setTipopessoa($tipopessoa)
    {
        $this->_tipopessoa = $tipopessoa;
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
     */
    public function setDtcadastro($dtcadastro)
    {
        $this->_dtcadastro = $dtcadastro;
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
     */
    public function setDtatualizacao($dtatualizacao)
    {
        $this->_dtatualizacao = $dtatualizacao;
    }

    /**
     * @return mixed
     */
    public function getDtvalidade()
    {
        return $this->_dtvalidade;
    }

    /**
     * @param mixed $dtvalidadeprotected
     */
    public function setDtvalidade($dtvalidade)
    {
        $this->_dtvalidade = $dtvalidade;
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
     */
    public function setStatus($status)
    {
        $this->_status = $status;
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

            'idagente' => self::getIdagente(),
            'cnpjcpf' => self::getCnpjcpf(),
            'cnpjcpfsuperior' => self::getCnpjcpfsuperior(),
            'tipopessoa' => self::getTipopessoa(),
            'dtcadastro' => self::getDtatualizacao(),
            'dtatualizacao' => self::getDtatualizacao(),
            'dtvalidade' => self::getDtvalidade(),
            'status' => self::getStatus(),
            'usuario' => self::getUsuario()
        );
    }

}
