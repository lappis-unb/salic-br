<?php

class Agente_Model_TbTitulacaoConselheiro extends MinC_Db_Model
{
    protected $_idagente;
    protected $_cdarea;
    protected $_cdsegmento;
    protected $_sttitular;
    protected $_stconselheiro;

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
    public function getCdarea()
    {
        return $this->_cdarea;
    }

    /**
     * @param mixed $cdarea
     */
    public function setCdarea($cdarea)
    {
        $this->_cdarea = $cdarea;
    }

    /**
     * @return mixed
     */
    public function getCdsegmento()
    {
        return $this->_cdsegmento;
    }

    /**
     * @param mixed $cdsegmento
     */
    public function setCdsegmento($cdsegmento)
    {
        $this->_cdsegmento = $cdsegmento;
    }

    /**
     * @return mixed
     */
    public function getSttitular()
    {
        return $this->_sttitular;
    }

    /**
     * @param mixed $sttitular
     */
    public function setSttitular($sttitular)
    {
        $this->_sttitular = $sttitular;
    }

    /**
     * @return mixed
     */
    public function getStconselheiro()
    {
        return $this->_stconselheiro;
    }

    /**
     * @param mixed $stconselheiro
     */
    public function setStconselheiro($stconselheiro)
    {
        $this->_stconselheiro = $stconselheiro;
    }

}