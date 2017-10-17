<?php

class Agente_Model_DbTable_Nomes extends MinC_Db_Table_Abstract
{
    protected $_schema = 'agentes';
    protected $_name = 'Nomes';
    protected $_primary = 'idNome';
    protected $_sequence = true;
}