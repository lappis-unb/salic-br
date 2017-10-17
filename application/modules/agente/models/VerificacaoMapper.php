<?php

class Agente_Model_VerificacaoMapper extends MinC_Db_Mapper
{
    public function __construct()
    {
        parent::setDbTable('Agente_Model_DbTable_Verificacao');
    }
}