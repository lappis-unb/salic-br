<?php
class tbDocumentoProjeto extends MinC_Db_Table_Abstract {

    protected $_banco  = "bdcorporativo";
    protected $_schema = "bdcorporativo.scCorp";
    protected $_name   = "tbDocumentoProjeto";

    public function excluir($where)
    {
        return $this->delete($where);
    }
}
