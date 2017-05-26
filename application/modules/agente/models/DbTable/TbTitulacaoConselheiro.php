<?php

class Agente_Model_DbTable_TbTitulacaoConselheiro extends MinC_Db_Table_Abstract
{
    /**
     * _schema
     *
     * @var string
     * @access protected
     */
    protected $_schema = 'agentes';

    /**
     * _name
     *
     * @var bool
     * @access protected
     */
    protected $_name = 'tbTitulacaoConselheiro';

    /**
     * _primary
     *
     * @var bool
     * @access protected
     */
    protected $_primary = 'idAgente';


    /**
     * Se a tabela possui sequence
     * @var bool
     */
    protected $_sequence = false;
}