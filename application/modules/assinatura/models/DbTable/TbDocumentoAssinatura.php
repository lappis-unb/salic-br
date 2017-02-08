<?php

class Assinatura_Model_DbTable_TbDocumentoAssinatura extends MinC_Db_Table_Abstract
{
    protected $_schema    = 'sac';
    protected $_name      = 'tbDocumentoAssinatura';
    protected $_primary   = 'idDocumentoAssinatura';

    public function obterDocumentoAssinatura($idPronac, $idTipoDoAtoAdministrativo) {
        $objQuery = $this->select();
        $objQuery->setIntegrityCheck(false);
        $objQuery->from(
            $this->_name,
            '*',
            $this->_schema
        );
        $objQuery->where('IdPRONAC = ?', $idPronac);
        $objQuery->where('idTipoDoAtoAdministrativo = ?', $idTipoDoAtoAdministrativo);

        $result = $this->fetchRow($objQuery);
        if($result) {
            return $result->toArray();
        }
    }

}