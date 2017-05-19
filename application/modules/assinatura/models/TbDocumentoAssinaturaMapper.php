<?php

class Assinatura_Model_TbDocumentoAssinaturaMapper extends MinC_Db_Mapper
{
    public function __construct()
    {
        parent::setDbTable('Assinatura_Model_DbTable_TbDocumentoAssinatura');
    }

    public function save(Assinatura_Model_TbDocumentoAssinatura $model)
    {
        return parent::save($model);
    }
}
