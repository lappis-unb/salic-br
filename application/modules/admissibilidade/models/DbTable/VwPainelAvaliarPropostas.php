<?php

/**
 * View para painel de avaliação das propostas e tranformação em projetos.
 *
 * @link http://salic.cultura.gov.br
 */
class Admissibilidade_Model_DbTable_VwPainelAvaliarPropostas extends MinC_Db_Table_Abstract{
    protected $_schema    = 'sac';
    protected $_name      = 'vwPainelAvaliarPropostas';

    public function propostas($where=array(), $order=array())
    {
        $db = $this->getAdapter();
        $db->setFetchMode(Zend_DB :: FETCH_OBJ);

        $sql = $db->select()
            ->from('vwPainelAvaliarPropostas', '*', $this->_schema)
            ;

        foreach ($where as $coluna=>$valor)
        {
            $sql->where($coluna, $valor);
        }

        $sql->order($order);

        return $db->fetchAll($sql);
    }
}
