<?php
class VisualizarhistoricoDAO extends Zend_Db_Table
{

       	protected $_name    = 'sac.dbo.Projetos';

    public function buscar($sql)   
    {
       	//echo $sql . "<br>";
       	$db = Zend_Db_Table::getDefaultAdapter();
		$db->setFetchMode(Zend_DB :: FETCH_OBJ);
		$resultado = $db->fetchAll($sql);
		return $resultado;
	} 
	
    public function inserirMensagem($pronac,$componenteComissao,$mensagem)   
    {
        $objAcesso= new Acesso();
       	$sql = "INSERT INTO bdcorporativo.scsac.tbMensagemProjeto (idPRONAC, idRemetente, idDestinatario, dtEncaminhamento, dsMensagem, stAtivo)
					        VALUES ($pronac, 75, $componenteComissao, {$objAcesso->getDate()}, '$mensagem', 'A')";
       	//echo $sql; die();
       	$db = Zend_Db_Table::getDefaultAdapter();
		$db->setFetchMode(Zend_DB :: FETCH_OBJ);
		$resultado = $db->query($sql);
		return $resultado;
	} 
	
	public function buscaProjeto($pronac)
	{
		$sql = "SELECT AnoProjeto+Sequencial as pronac,
								IdPRONAC, 
								NomeProjeto, 
								CONVERT(CHAR(10),DtProtocolo,103) as Data 
						FROM sac.dbo.Projetos WHERE 
								IdPRONAC = " . $pronac . " ";
		
		$db = Zend_Db_Table::getDefaultAdapter();
		$db->setFetchMode(Zend_DB :: FETCH_OBJ);
		$resultado = $db->fetchAll($sql);
		return $resultado;
	}
	
	public function buscaHistorico($pronac)
	{
		$historico = "select  convert (char(10),mp.dtEncaminhamento,103) as data, 
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
		$resultado = $db->fetchAll($historico);
		return $resultado;
	}
	
	public function buscaConselheiro()
	{
		$conselheiro = "SELECT 
								N.Descricao as nomeConselheiro, 
								N.idAgente as agente
						FROM agentes.dbo.Agentes A,
								agentes.dbo.Nomes N, 
								agentes.dbo.Visao V 
				 		WHERE V.idAgente = A.idAgente 
						AND V.Visao = 210 
						AND N.idAgente = A.idAgente
						ORDER BY N.Descricao";
						
		$db = Zend_Db_Table::getDefaultAdapter();
		$db->setFetchMode(Zend_DB :: FETCH_OBJ);
		$resultado = $db->fetchAll($conselheiro);
		return $resultado;
	}
	
	

	
	
}		// fecha class
				