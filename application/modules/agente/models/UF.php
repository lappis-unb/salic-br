<?php

class Agente_Model_UF extends MinC_Db_Model
{
    protected $_iduf;
    protected $_sigla;
    protected $_descricao;
    protected $_regiao;

    /**
     * @return mixed
     */
    public function getIdUF()
    {
        return $this->_idUF;
    }

    /**
     * @param mixed $idUF
     */
    public function setIdUF($idUF)
    {
        $this->_idUF = $idUF;
    }

    /**
     * @return mixed
     */
    public function getSigla()
    {
        return $this->_Sigla;
    }

    /**
     * @param mixed $Sigla
     */
    public function setSigla($Sigla)
    {
        $this->_Sigla = $Sigla;
    }

    /**
     * @return mixed
     */
    public function getDescricao()
    {
        return $this->_Descricao;
    }

    /**
     * @param mixed $Descricao
     */
    public function setDescricao($Descricao)
    {
        $this->_Descricao = $Descricao;
    }

    /**
     * @return mixed
     */
    public function getRegiao()
    {
        return $this->_Regiao;
    }

    /**
     * @param mixed $Regiao
     */
    public function setRegiao($Regiao)
    {
        $this->_Regiao = $Regiao;
    }
}