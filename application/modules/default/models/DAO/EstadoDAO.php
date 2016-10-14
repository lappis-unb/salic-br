<?php
/**
 * Modelo Estado
 * @since 29/03/2010
 */

class EstadoDAO extends MinC_Db_Table_Abstract
{
	protected $_name = 'uf';
	protected $_schema = 'agentes';

    public function listar($id = null)
    {
        $db = Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        $sql = $db->select()
            ->from($this->_name, ['iduf AS id', 'sigla AS descricao'], $this->_schema);

        if (!empty($id)) {
            $sql->where('idUF = ?', $id);
        }

        return $db->fetchAll($sql);
    }
}
