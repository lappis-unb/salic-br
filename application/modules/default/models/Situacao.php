<?php

class Situacao extends MinC_Db_Table_Abstract
{
    protected $_schema = 'SAC';
    protected $_name = 'Situacao';

    public function listasituacao($codigosituacao = array())
    {
        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->from(
            array('sit' => $this->_name)
        );

        if (!empty($codigosituacao)) {
            $select->orWhere('sit.Codigo IN (?) ', $codigosituacao);
        }

        return $this->fetchAll($select);
    }
}
