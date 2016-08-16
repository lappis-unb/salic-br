<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of paDocumentos
 */
class paDocumentos extends GenericModel {
        
    protected $_banco = 'SAC';
    protected $_name  = 'paDocumentos';

    public function marcasAnexadas($idPronac){

        $idPronac = preg_replace("/[^0-9]/","", $idPronac); //REMOVE injections
        $sql = 'exec '.$this->_banco.'.dbo.'.$this->_name.' '.$idPronac;
        
        $db = Zend_Registry :: get('db');
        $db->setFetchMode(Zend_DB :: FETCH_OBJ);
        return $db->fetchAll($sql);
    }
}
?>
