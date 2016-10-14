<?php
/**
 * Modelo Estado
 * @since 29/03/2010
 */

class EstadoDAO extends MinC_Db_Table_Abstract
{
<<<<<<< HEAD
	protected $_name = 'uf';
	protected $_schema = 'agentes';
=======
	protected $_name = 'uf'; // nome da tabela
	protected $_schema = 'agentes'; // nome da tabela

	/**
	 * M�todo para buscar os estados
	 * @access public
	 * @param void
	 * @return object $db->fetchAll($sql)
	 */
	public function buscar($id = null)
	{
		$sql = "SELECT idUF AS id, Sigla AS descricao ";
		$sql.= "FROM AGENTES.dbo.UF ";

		if (!empty($id))
		{
			$sql.= "WHERE idUF = {$id} ";
		}

		$sql.= "ORDER BY Sigla";

		try
		{
			$db = Zend_Db_Table::getDefaultAdapter();
			$db->setFetchMode(Zend_DB::FETCH_OBJ);
		}
		catch (Zend_Exception_Db $e)
		{
			$this->view->message = "Erro ao buscar Estados: " . $e->getMessage();
		}
		//xd($sql);
		return $db->fetchAll($sql);
	} // fecha buscar()
>>>>>>> 2c8444b792d3daa70a31b425c3a4ae9907a65032

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
