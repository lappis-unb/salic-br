<?php
class HistoricoDAO extends Zend_Db_Table
{

       	protected $_name    = 'sac.dbo.Projetos';

       	
       	public function buscaProjeto($pronac)
	{
		$sql = "SELECT IdPRONAC, NomeProjeto, 
					   CONVERT(CHAR(10),DtProtocolo,103) as Data 
				FROM sac.dbo.Projetos 
				WHERE  IdPRONAC = " . $pronac . " 
				ORDER By Data";
		
		$db = Zend_Db_Table::getDefaultAdapter();
		$db->setFetchMode(Zend_DB :: FETCH_OBJ);
		$resultado = $db->fetchAll($sql);
		return $resultado;
	}
	
 
	
	public function buscaHistorico($pronac)
	{
		$sql = "select  convert (char(10),mp.dtEncaminhamento,103) as data, 
        				idRemetente, remetente.Descricao as nmRemetente, 
        				idDestinatario, 
        				destinatario.Descricao as nmDestinatario,
        				Pr.NomeProjeto,
        				Pr.IdPRONAC,
        				dsMensagem
				from bdcorporativo.scsac.tbmensagemprojeto mp
				left join agentes.dbo.Nomes remetente on remetente.idAgente = mp.idRemetente
				left join agentes.dbo.Nomes destinatario on destinatario.idAgente = mp.idDestinatario
				left join sac.dbo.Projetos Pr on Pr.IdPRONAC = mp.idPRONAC
 				where mp.stAtivo = 'A' and Pr.IdPRONAC = '$pronac'
				order by mp.dtEncaminhamento desc ";
					
		$db = Zend_Db_Table::getDefaultAdapter();
		$db->setFetchMode(Zend_DB :: FETCH_OBJ);
		$resultado = $db->fetchAll($sql);
		
		Zend_Debug::dump($resultado);$this->_helper->viewRenderer->setNoRender(TRUE);
		
		return $resultado;
		
	}
	
	
	
}		// fecha class
				