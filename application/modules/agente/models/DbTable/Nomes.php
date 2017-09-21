<?php

class Agente_Model_DbTable_Nomes extends MinC_Db_Table_Abstract
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
    protected $_name = 'Nomes';

    /**
     * _primary
     *
     * @var bool
     * @access protected
     */
    protected $_primary = 'idNome';

    /**
     * Se a tabela possui sequence
     * @var bool
     */
    protected $_sequence = true;
}