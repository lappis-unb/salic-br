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
        $objQuery = $this->select();
        $objQuery->setIntegrityCheck(false);
        $objQuery->from(
            array('vwPlanoDeDistribuicaoProduto' => $this->_name),
            '*',
            $this->_schema
        );

        if(count($arrayWhere) > 0) {
            foreach ($arrayWhere as $condicao => $valor) {
                $objQuery->where($condicao, $valor);
            }
        }

        $db = Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_ASSOC);
//xd($objQuery->assemble());
        return $db->fetchAll($objQuery);
    }

}
