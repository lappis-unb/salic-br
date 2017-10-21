<?php

class Proposta_Model_TbMovimentacaoMapper extends MinC_Db_Mapper
{
    public function __construct()
    {
        parent::setDbTable('Proposta_Model_DbTable_TbMovimentacao');
    }

    public function save(Proposta_Model_TbMovimentacao $model)
    {
        return parent::save($model);
    }
}