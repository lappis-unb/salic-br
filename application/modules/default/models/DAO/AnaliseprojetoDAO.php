<?php
Class AnaliseprojetoDAO extends Zend_Db_Table{

       	protected $_name    = 'sac.dbo.Projetos';

       	public static function buscar($pronac)
       	{
       		$sql = "select idPRONAC,
NomeProjeto
from sac.dbo.Projetos where IdPRONAC = " . $pronac . " ";
   
			$db = Zend_Db_Table::getDefaultAdapter();
			$db->setFetchMode(Zend_DB::FETCH_OBJ);
			$resultado = $db->fetchAll($sql);

			return $resultado;
       	}
       	
}