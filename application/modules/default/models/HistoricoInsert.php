<?php
/**
 * DAO HistoricoInsert
 * @author emanuel.sampaio - Politec
 * @since 18/02/2011
 * @version 1.0
 * @package application
 * @subpackage application.model
 * @link http://www.cultura.gov.br
 */

class HistoricoInsert extends MinC_Db_Table_Abstract
{
	protected $_banco  = "SAC";
	protected $_schema = "SAC";
	protected $_name   = "sysobjects";
	public $dados = 'HISTORICO_INSERT';


	/**
	 * Metodo para verificar se a trigger HISTORICO_INSERT estah habilitada
	 * @access public
	 * @param void
	 * @return integer (0 = Habilitado e 1 = desabilitado)
	 */
	public function statusHISTORICO_INSERT()
	{
		$sql = "SELECT ObjectProperty(Object_id(name), 'ExecIsTriggerDisabled') AS Habilitado
				FROM {$this->_schema}.{$this->_name}
				WHERE name = 'HISTORICO_INSERT'";

        $db = Zend_Db_Table::getDefaultAdapter();
		// executa a query
		$resultado = $db->fetchAll($sql);

		// encerra a conexao
		$db = Zend_Db_Table::getDefaultAdapter();
		$db->closeConnection();

		return $resultado[0]['Habilitado'];
	} // fecha metodo statusHISTORICO_INSERT()

}
