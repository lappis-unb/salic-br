<?php
Class AreadeTrabalho extends Zend_Db_Table{

       	protected $_name    = 'sac.dbo.Projetos';

       	public function buscarAnalise()
       	{
       		$sql = "
       		select Pr.idPRONAC, Pr.NomeProjeto,
       		CASE WHEN Pa.ParecerFavoravel in ('2','3') THEN 'Sim'
            ELSE 'N�o' End AS ParecerFavoravel,
       		CONVERT(CHAR(10),DPC.dtDistribuicao,103) AS DataRecebimento
 			from sac.dbo.Projetos Pr, sac.dbo.Parecer Pa, bdcorporativo.scsac.tbDistribuicaoProjetoComissao DPC
			where Pa.idPRONAC = Pr.idPRONAC
			AND DPC.idPRONAC = Pr.idPRONAC";
			//AND DPC.idAgente = idParametro";
			$db = Zend_Db_Table::getDefaultAdapter();
			$db->setFetchMode(Zend_DB::FETCH_OBJ);
			$resultado = $db->fetchAll($sql);

			return $resultado;
       	}
       	public function buscarResposta()
       	{
       		$sql1 = "
       		select Pr.idPRONAC, Pr.NomeProjeto,
       		CASE WHEN Pa.ParecerFavoravel in ('2','3') THEN 'Sim'
            ELSE 'N�o' End AS ParecerFavoravel,
       		CONVERT(CHAR(10),d.dtSolicitacao,103) AS DataSolicitacao
 			from sac.dbo.Projetos Pr, sac.dbo.Parecer Pa, sac.dbo.tbDiligencia D
			where Pa.idPRONAC = Pr.idPRONAC
			AND d.idPRONAC = Pr.idPRONAC
			AND D.dtResposta IS NULL
			";
			//AND d.idSolicitante = idParametro";

			$db = Zend_Db_Table::getDefaultAdapter();
			$db->setFetchMode(Zend_DB::FETCH_OBJ);
			$resultado1 = $db->fetchAll($sql1);

			return $resultado1;
       	}
       		public function buscarDiligencia()
       	{
       		$sql2 = "
       		select Pr.idPRONAC, Pr.NomeProjeto,
       		CASE WHEN Pa.ParecerFavoravel in ('2','3') THEN 'Sim'
            ELSE 'N�o' End AS ParecerFavoravel,
       		CONVERT(CHAR(10),d.dtResposta,103) AS DataResposta
 			from sac.dbo.Projetos Pr, sac.dbo.Parecer Pa, sac.dbo.tbDiligencia D
			where Pa.idPRONAC = Pr.idPRONAC
			AND d.idPRONAC = Pr.idPRONAC
			AND D.dtResposta IS NOT NULL";
			//AND d.idSolicitante = idParametro";

			$db = Zend_Db_Table::getDefaultAdapter();
			$db->setFetchMode(Zend_DB::FETCH_OBJ);
			$resultado2 = $db->fetchAll($sql2);

			return $resultado2;
       	}
       	public function mostrar(){
       		
       	}
       	}
