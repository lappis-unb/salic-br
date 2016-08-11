<?php 
/**
 * DAO vwMemoriaDeCalculo
 * @since 01/03/2013
 * @version 1.0
 * @package application
 * @subpackage application.model
 * @copyright © 2011 - Ministério da Cultura - Todos os direitos reservados.
 * @link http://www.cultura.gov.br
 */

class vwMemoriaDeCalculo extends GenericModel {

    /* dados da tabela */
    protected $_banco  = 'SAC';
    protected $_schema = 'dbo';
    protected $_name   = 'vwMemoriaDeCalculo';

    public function busca($idPronac) {

        $table = Zend_Db_Table::getDefaultAdapter();

        $select = $table->select()
            ->from('vwMemoriaDeCalculo',
                array('idPronac', 'PRONAC', 'ValorProposta', 'OutrasFontes', 'ValorSolicitado', 'Elaboracao', 'ValorParecer', 'ValorSugerido'),
                'SAC.dbo')
            ->where('idPronac = ?', $idPronac);

        try {
            $db = Zend_Registry::get('db');
            $db->setFetchMode(Zend_DB::FETCH_OBJ);
        } catch (Zend_Exception_Db $e) {
            $this->view->message = $e->getMessage();
        }
        return $db->fetchAll($select);
    }

} // fecha class