<?php
class tbConsolidacaoVotacao extends MinC_Db_Table_Abstract
{
    protected $_schema = "BDCORPORATIVO.scSAC";
    protected $_name   = "tbConsolidacaoVotacao";

    public function consolidacaoPlenaria($idPronac)
    {
        $db = Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        $cols = array('idNrReuniao', 'IdPRONAC', 'dsConsolidacao');

        $select = $db->select()
            ->from($this->_name, $cols, $this->_schema)
            ->where('IdPRONAC = ?', $idPronac)
            ;

        try {
        } catch (Zend_Exception_Db $e) {
            $this->view->message = $e->getMessage();
        }
        return $db->fetchAll($select);
    }
}
