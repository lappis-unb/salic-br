<?php

/**
 * Class Agente_Model_Verificacao
 *
 * @name Agente_Model_Verificacao
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
class Agente_Model_Verificacao extends MinC_Db_Model
{
    protected $_idverificacao;
    protected $_idtipo;
    protected $_descricao;
    protected $_sistema;

    /**
     * @return mixed
     */
    public function getIdverificacao()
    {
        return $this->_idverificacao;
    }

    /**
     * @param mixed $idverificacao
     */
    public function setIdverificacao($idverificacao)
    {
        $this->_idverificacao = $idverificacao;
    }

    /**
     * @return mixed
     */
    public function getIdtipo()
    {
        return $this->_idtipo;
    }

    /**
     * @param mixed $idtipo
     */
    public function setIdtipo($idtipo)
    {
        $this->_idtipo = $idtipo;
    }

    /**
     * @return mixed
     */
    public function getDescricao()
    {
        return $this->_descricao;
    }

    /**
     * @param mixed $descricao
     */
    public function setDescricao($descricao)
    {
        $this->_descricao = $descricao;
    }

    /**
     * @return mixed
     */
    public function getSistema()
    {
        return $this->_sistema;
    }

    /**
     * @param mixed $sistema
     */
    public function setSistema($sistema)
    {
        $this->_sistema = $sistema;
    }

}