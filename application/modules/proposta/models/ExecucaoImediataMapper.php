<?php

/**
 * Class Proposta_Model_ExecucaoImediataMapper
 *
 * @name Proposta_Model_ExecucaoImediataMapper
 * @package Modules/Proposta
 * @subpackage Models
 *
 * @since 25/11/2016
 * @updated 25/11/2016
 *
 */
class Proposta_Model_ExecucaoImediataMapper extends MinC_Db_Mapper
{
    public function __construct()
    {
        parent::setDbTable('Proposta_Model_DbTable_ExecucaoImediata');
    }

    public function save($model)
    {
        return parent::save($model);
    }
}
