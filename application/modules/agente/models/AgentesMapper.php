<?php

class Agente_Model_AgentesMapper extends MinC_Db_Mapper
{

    public function __construct()
    {
        $this->setDbTable('Agente_Model_DbTable_Agentes');
    }

    public function fetchAll()
    {
        $resultSet = $this->fetchAll();
        $entries   = array();
        foreach ($resultSet as $row) {
            $entry = new Agente_Model_Agentes($row->toArray());
            $entries[] = $entry->toArray();
        }
        return $entries;
    }

    public function isUniqueCpfCnpj($value)
    {
        return ($this->findBy(array("CNPJCPF = ?" => $value))) ? true : false;
    }

    public function save( $model)
    {
        if (!self::isUniqueCpfCnpj($model->getCnpjcpf())) {
            return parent::save($model);
        }
        throw new Exception('CNPJ ou CPF j&aacute; cadastrado.');
    }
}
