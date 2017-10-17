<?php

class Agente_Model_Area extends MinC_Db_Model
{
    protected $_codigo;
    protected $_descricao;

    /**
     * @return mixed
     */
    public function getCodigo()
    {
        return $this->_codigo;
    }

    /**
     * @param mixed $codigo
     */
    public function setCodigo($codigo)
    {
        $this->_codigo = $codigo;
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


}