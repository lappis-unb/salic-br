<?php

/**
 * Description of paIncluirRecusarItem
 * @author Jefferson Alessandro
 */
class paIncluirRecusarItem extends MinC_Db_Table_Abstract {
        
    protected $_banco = 'SAC';
    protected $_name  = 'paIncluirRecusarItem';

    public function incluirRecusarItem($idSolicitarItem, $usuariologado, $stEstado){
        $sql = "exec ".$this->_banco.".".$this->_name." $idSolicitarItem, $usuariologado, $stEstado ";
        $db = Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB :: FETCH_OBJ);
        return $db->fetchAll($sql);
    }
}
?>
