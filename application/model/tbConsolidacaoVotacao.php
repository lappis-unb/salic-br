<?php
/**
 * tbConsolidacaoVotacao
 * @author jefferson.silva - XTI
 * @since 02/08/2013
 * @version 1.0
 * @package application
 * @subpackage application.model
 * @copyright © 2011 - Ministério da Cultura - Todos os direitos reservados.
 * @link http://www.cultura.gov.br
 */

class tbConsolidacaoVotacao extends GenericModel
{
    protected $_banco  = "BDCORPORATIVO";
    protected $_schema = "scSAC";
    protected $_name   = "tbConsolidacaoVotacao";

    public function consolidacaoPlenaria($idPronac)
    {
        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->from("$this->_name",
            array('idNrReuniao', 'IdPRONAC', 'dsConsolidacao'),
            "$this->_banco.$this->_schema")
        ->where('idPRONAC = ?', $idPronac);

        try {
            $db = Zend_Registry::get('db');
            $db->setFetchMode(Zend_DB::FETCH_OBJ);
        } catch (Zend_Exception_Db $e) {
            $this->view->message = $e->getMessage();
        }
        return $db->fetchAll($select);
    }

} // fecha class