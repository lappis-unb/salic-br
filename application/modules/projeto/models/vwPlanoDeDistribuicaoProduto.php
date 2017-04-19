<?php

/**
 * Created by PhpStorm.
 * User: vinnyfs89
 * Date: 01/02/17
 * Time: 14:57
 */
class Projeto_Model_vwPlanoDeDistribuicaoProduto extends MinC_Db_Table_Abstract {

    protected $_schema = 'SAC';
    protected $_name   = 'vwPlanoDeDistribuicaoProduto';
    protected $_primary = 'IdPRONAC';

    public function obterProducaoProjeto(array $arrayWhere = array()) {
        $db = $this->getAdapter();
        $db->select()->from(
            array('vwPlanoDeDistribuicaoProduto' => $this->_name),
            '*',
            $this->_schema
        );

        if(count($arrayWhere) > 0) {
            foreach ($arrayWhere as $condicao => $valor) {
                $db->select()->where($condicao, $valor);
            }
        }
        
        $db->setFetchMode(Zend_DB::FETCH_ASSOC);
        
        return $db->fetchAll($objQuery);
    }

}
