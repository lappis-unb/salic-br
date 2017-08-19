<?php

class ManterAvaliadorDAO extends Zend_Db_Table {
	
    public static function buscaIdAgente ($cpf = null)
    {
//    	$sql = "select * from agentes.dbo.Agentes where CNPJCPF = '$cpf'";
    	
         $sql = "select usu.usu_identificacao, usu.usu_nome, a.idAgente
                                        FROM TABELAS.dbo.Usuarios usu 
                                        INNER JOIN agentes.dbo.Agentes as a on a.CNPJCPF = usu.usu_identificacao
                                        INNER JOIN agentes.dbo.Nomes as n on n.idAgente = a.idAgente
                                where usu_identificacao = '$cpf'";    
            

    	$db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        return $db->fetchAll($sql);
    }
	
	public static function buscaAvaliador ($cpf = null, $idAgente = null)
    {
//		$sql = "select usu_identificacao, usu_nome, em.Descricao as email, a.idAgente, usu.usu_codigo 
//					FROM TABELAS.dbo.Usuarios usu 
//					INNER JOIN agentes.dbo.Agentes as a on a.CNPJCPF = usu.usu_identificacao
//					INNER JOIN agentes.dbo.Internet as em on a.idAgente = em.idAgente
//				where usu_identificacao = $cpf ";
//
//    	$sql = "SELECT a.idAgente, a.CNPJCPF as usu_identificacao, a.Usuario as usu_codigo, i.Descricao as email, n.Descricao as nome,
//    				a.Usuario as usu_Agentes, i.Usuario as usu_email, n.Usuario as usu_nome
//				FROM agentes.dbo.Agentes a
//					inner join agentes.dbo.Internet i on a.idAgente = i.idAgente
//					inner join agentes.dbo.Nomes n on a.idAgente = n.idAgente
//				WHERE a.CNPJCPF = '$cpf' and a.idAgente = $idAgente";
            
         $sql = "select usu.usu_identificacao, usu.usu_nome as nome, a.idAgente
                                        FROM TABELAS.dbo.Usuarios usu 
                                        INNER JOIN agentes.dbo.Agentes as a on a.CNPJCPF = usu.usu_identificacao
                                        INNER JOIN agentes.dbo.Nomes as n on n.idAgente = a.idAgente
                                where usu_identificacao = '$cpf' and a.idAgente = $idAgente";
            
//
    	$db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        return $db->fetchAll($sql);
    }
    
	public static function buscaDadosEdital ($idEdital = null)
    {
		$sql = "select * from sac.dbo.Edital where idEdital = $idEdital and NrEdital is not null";
		

		$db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        return $db->fetchAll($sql);
    }
	
//    public static function listarEditaisAvaliador ($codOrgao)
//    {
////		$sql = "select
////			        idAvaliador as idAgente,nmFormDocumento as nmEdital, a.idEdital,d.NrEdital,stAtivo, a.idAvaliador
////			    from bdcorporativo.scsac.tbAvaliadorEdital a
////			        inner join sac.dbo.Edital b on a.idEdital = b.idEdital
////			        inner join bdcorporativo.scQuiz.tbFormDocumento c on c.idEdital = a.idEdital and c.idClassificaDocumento not in (23,24,25)
////			        inner join sac.dbo.Edital d on a.idEdital = d.idEdital
////			    where  a.stAtivo = 'A' and a.idAvaliador = $idAgente
////			    order by d.NrEdital,nmFormDocumento asc ";
//		
//    	$sql = "select
//			        nmFormDocumento as nmEdital, b.idEdital,b.NrEdital, b.idOrgao
//			    from sac.dbo.Edital b
//			        inner join bdcorporativo.scQuiz.tbFormDocumento c on c.idEdital = b.idEdital 
//			    where  c.stFormDocumento = 'A' and b.idOrgao = $codOrgao
//			    order by b.NrEdital,nmFormDocumento asc ";
//
//		$db= Zend_Db_Table::getDefaultAdapter();
//        $db->setFetchMode(Zend_DB::FETCH_OBJ);
//
//        return $db->fetchAll($sql);
//    }
    
    public static function listarEditaisAvaliador ()
    {
        $objAcesso= new Acesso();
    	$sql = "SELECT distinct TOP 1000 a.idEdital
				      ,[dtIniFase]
				      ,[dtFimFase]
				      ,[qtDiasRecurso]
				      ,[qtDiasJulgamento], a.idFaseEdital
				  FROM [bdcorporativo].[scSAC].[tbEditalXtbFaseEdital] a
				  inner join bdcorporativo.scQuiz.tbFormDocumento b on (a.idEdital = b.idEdital)
				  where  b.idClassificaDocumento not in (23,24,25) and
				  a.dtFimFase >= {$objAcesso->getDate()} and (idFaseEdital = 4)
				  order by dtFimFase";

		$db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        return $db->fetchAll($sql);
    }
    
    public static function buscaEditais ($idAgente = null, $idEdital = null)
    {
    	$sql = "select * from bdcorporativo.scsac.tbAvaliadorEdital where idAvaliador = $idAgente";
    	if($idEdital){
    		$sql .= " and idEdital = $idEdital";
    	}

		$db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        return $db->fetchAll($sql);
    }
    
    
    public static function buscaEditaisAtivos ($idAgente = null)
    {
    	$sql = "select * from bdcorporativo.scsac.tbAvaliadorEdital where idAvaliador = $idAgente and stAtivo = 'A'";

		$db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        return $db->fetchAll($sql);
    }
    
	public static function inserirAgente ($cpf, $idusuario)
    {
        $objAcesso= new Acesso();
    	$sql = "insert into agentes.dbo.Agentes (CNPJCPF, TipoPessoa, DtCadastro, Status, Usuario) values ('$cpf', 18, {$objAcesso->getDate()}, 0, $idusuario)";
		$db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        return $db->fetchAll($sql);
    }
    
	public static function inserirEmail ($idAgente, $email, $idusuario)
    {
    	$sql = "insert into agentes.dbo.Internet (idAgente, TipoInternet, Descricao, Status, Divulgar, Usuario) values ($idAgente, 28, '$email', 0, 1, $idusuario)";

		$db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        return $db->fetchAll($sql);
    }
    
	public static function atualizarEmail ($idAgente, $email, $idusuario)
    {
    	$sql = "update agentes.dbo.Internet set Descricao = '$email' where idAgente = $idAgente and TipoInternet = 28 and Usuario = $idusuario";

		$db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        return $db->fetchAll($sql);
    }
    
	public static function inserirNome ($idAgente, $nome, $idusuario)
    {
    	$sql = "insert into agentes.dbo.Nomes (idAgente, TipoNome, Descricao, Status, Usuario) values ($idAgente, 18, '$nome', 0, $idusuario)";

		$db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        return $db->fetchAll($sql);
    }
    
	public static function atualizarNome ($idAgente, $nome, $idusuario)
    {
    	$sql = "update agentes.dbo.Nomes set Descricao = '$nome' where idAgente = $idAgente and TipoNome = 18 and Usuario = $idusuario";

		$db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        return $db->fetchAll($sql);
    }
	
}