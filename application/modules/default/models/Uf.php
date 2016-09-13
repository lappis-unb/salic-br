<?php

/**
 * Description of Uf
 *
 * @author 01610881125
 */
class Uf extends GenericModel {

    protected $_banco = 'agentes';
    protected $_name = 'uf';
    protected $_schema = 'agentes';

    public function buscarRegiao() {
        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->from(
                array($this->_name), array(
            'Regiao'
                )
        );
        $select->order('Regiao');
        $select->group('Regiao');
        return $this->fetchAll($select);
    }

    public function buscaRegiaoPorPRONAC($PRONAC) {
        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->from(
                array('uf'=>$this->_name), array(
            'Regiao'
                )
        );
        $select->joinInner(array('p' => 'Projetos'), 'uf.Sigla = p.UfProjeto', array(), 'SAC.dbo');

        $select->where('(p.AnoProjeto+p.Sequencial) = ?', $PRONAC);

        return $this->fetchAll($select);
    }

    /**
     * Método para buscar os estados
     * @access public
     * @param void
     * @return array
     * @author wouerner <wouerner@gmail.com>
     */
    public function buscar()
    {
        $sql = 'SELECT idUF AS id, Sigla AS descricao ';
        //$sql .= 'FROM ' . GenericModel::getStaticTableName($objEstado->_schema, $objEstado->_name);
        $sql .= 'FROM agentes.dbo.uf ';
        $sql .= ' ORDER BY Sigla';

        try {
            $db= Zend_Db_Table::getDefaultAdapter();
            $db->setFetchMode(Zend_DB::FETCH_OBJ);
            return $db->fetchAll($sql);
        } catch (Zend_Exception_Db $objException) {
            throw new Exception("Erro ao buscar Estados: " . $objException->getMessage(), 0, $objException);
        }
    }
}
