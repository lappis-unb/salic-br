<?php
Class AreadeTrabalhoDAO extends Zend_Db_Table{

       	protected $_name    = 'sac.dbo.Projetos';



       	public function buscarAnalise($idagente)
       	{
       		$sql = "
                                SELECT Pr.idPRONAC
       					,Pr.AnoProjeto + Pr.Sequencial AS PRONAC
						,Pr.NomeProjeto
						,CASE WHEN Pa.ParecerFavoravel in ('2','3')
							THEN 'Sim'
							ELSE 'N�o'
						  End AS ParecerFavoravel
						 ,CONVERT(CHAR(10),DPC.dtDistribuicao,103) AS DataRecebimento

					FROM sac.dbo.Projetos Pr
						,sac.dbo.Parecer Pa
						,bdcorporativo.scsac.tbDistribuicaoProjetoComissao DPC

					WHERE Pa.idPRONAC = Pr.IdPRONAC
						AND DPC.idPRONAC = Pr.IdPRONAC
						AND Pr.Situacao = 'C10'
						AND Pa.TipoParecer = '1'
                                                AND DPC.idAgente = $idagente
                                                AND not exists(select 1 from bdcorporativo.scsac.tbPauta where idpronac = Pr.idPRONAC )
                                                AND Pa.stAtivo = 1
			";
			$db = Zend_Db_Table::getDefaultAdapter();
			$db->setFetchMode(Zend_DB::FETCH_OBJ);
			$resultado = $db->fetchAll($sql);

			return $resultado;
		}



       	public function buscarResposta($idagente)
       	{
       		$sql1 = "SELECT Pr.idPRONAC
       					,Pr.AnoProjeto + Pr.Sequencial AS PRONAC
						,Pr.NomeProjeto
						,CASE WHEN Pa.ParecerFavoravel in ('2','3')
							THEN 'Sim'
							ELSE 'N�o'
						  End AS ParecerFavoravel
						 ,CONVERT(CHAR(10),D.DtResposta,103) AS DtResposta
					FROM sac.dbo.Projetos Pr
					INNER JOIN sac.dbo.Parecer Pa on Pa.IdPRONAC = Pr.IdPRONAC
					INNER JOIN sac.dbo.tbDiligencia D on D.idPronac = Pr.IdPRONAC
					WHERE Pr.Situacao = 'B14'
					AND Pa.TipoParecer = '1'
                                        AND  D.idSolicitante = $idagente
                                        And D.DtResposta is not null
			";
			//AND d.idSolicitante = idParametro";
//                die('<pre>'.$sql1);

			$db = Zend_Db_Table::getDefaultAdapter();
			$db->setFetchMode(Zend_DB::FETCH_OBJ);
			$resultado1 = $db->fetchAll($sql1);

			return $resultado1;
       	}



		public function buscarDiligencia($idagente)
       	{
       		$sql2 = "SELECT Pr.idPRONAC
						,Pr.AnoProjeto + Pr.Sequencial AS PRONAC
						,Pr.NomeProjeto
						,CASE WHEN Pa.ParecerFavoravel in ('2','3')
							THEN 'Sim'
							ELSE 'N�o'
						End AS ParecerFavoravel
						,CONVERT(CHAR(10),d.dtSolicitacao,103) AS dtSolicitacao

					FROM sac.dbo.Projetos Pr
					INNER JOIN sac.dbo.Parecer Pa on Pa.IdPRONAC = Pr.IdPRONAC
					INNER JOIN sac.dbo.tbDiligencia D on D.idPronac = Pr.IdPRONAC
					WHERE D.dtResposta is null
                                        and D.idSolicitante = $idagente
					AND Pr.Situacao = 'B14'
			";
			//AND d.idSolicitante = idParametro";
//die('<pre>'.$sql2);
			$db = Zend_Db_Table::getDefaultAdapter();
			$db->setFetchMode(Zend_DB::FETCH_OBJ);
			$resultado2 = $db->fetchAll($sql2);

			return $resultado2;
       	}
       	
       	
       	
       	public function mostrar(){
       		
       	}
}