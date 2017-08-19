<?php
Class DadosprojetoDAO extends Zend_Db_Table{

       	protected $_name    = 'sac.dbo.Projetos';

       	public static function buscar($pronac)
       	{
			$sql = "SELECT
			Pr.AnoProjeto+Pr.Sequencial as pronac ,
			Pr.idPRONAC,
			Tp.Emissor, 
			CONVERT(CHAR(10),Tp.dtTramitacaoEnvio,103) as dtTramitacaoEnvio, 
			Tp.Situacao, 
			Tp.Destino, 
			Tp.Receptor, 
			CONVERT(CHAR(10),Tp.DtTramitacaoRecebida,103) as DtTramitacaoRecebida, 
			Pr.NomeProjeto,
			Pr.ResumoProjeto, 
			Tp.meDespacho,
			St.descricao dsSituacao,
			Mc.descricao dsMecanismo,
			Sg.descricao dsSegmento,
			Ar.descricao dsArea,
			PP.idPreProjeto,
			CASE WHEN N.Descricao IS NULL
			THEN I.Nome
			ELSE N.Descricao
			END AS nmProponente,
			Pr.UfProjeto, 
			Pr.Processo, 
			Pr.CgcCpf, 
			CONVERT(CHAR(10),Pr.DtSituacao,103) as DtSituacao, 
			Pr.ProvidenciaTomada, 
			Pr.Localizacao,
			CASE En.Enquadramento when 1 then 'Artigo 26' when 2 then 'Artigo 18' else 'N�o enquadrado' end as Enquadramento,
			Pr.SolicitadoReal,
			--sac.dbo.fnOutrasFontes(Pr.idPronac) AS OutrasFontes,
			CASE WHEN sac.dbo.fnOutrasFontes(Pr.idPronac) is null
			Then sac.dbo.fnValorDaProposta(PP.idPreProjeto)
			else sac.dbo.fnValorSolicitado(Pr.AnoProjeto,Pr.Sequencial)
			end as ValorProposta,
			--CASE WHEN Pr.Mecanismo in ('2','6')
			--THEN sac.dbo.fnValorAprovadoConvenio(Pr.AnoProjeto,Pr.Sequencial)
			--ELSE sac.dbo.fnValorAprovado(Pr.AnoProjeto,Pr.Sequencial)
			--END AS ValorAprovado,
			CASE WHEN Pr.Mecanismo in ('2','6')
			--THEN sac.dbo.fnValorAprovadoConvenio(Pr.AnoProjeto,Pr.Sequencial)
			--ELSE sac.dbo.fnValorAprovado(Pr.AnoProjeto,Pr.Sequencial) + sac.dbo.fnOutrasFontes(Pr.idPronac)
			--END AS ValorProjeto,
			--sac.dbo.fnCustoProjeto (Pr.AnoProjeto,Pr.Sequencial) as ValorCaptado
			FROM sac.dbo.Projetos Pr
			JOIN sac.dbo.Situacao St ON St.Codigo = Pr.Situacao
			JOIN sac.dbo.Area Ar ON  Ar.Codigo = Pr.Area
			JOIN sac.dbo.Segmento Sg ON Sg.Codigo = Pr.Segmento
			JOIN sac.dbo.Mecanismo Mc ON Mc.Codigo = Pr.Mecanismo
			LEFT JOIN sac.dbo.Enquadramento En ON En.idPRONAC =  Pr.idPRONAC
			JOIN agentes.dbo.Agentes A ON A.CNPJCPF = Pr.CgcCpf
			JOIN sac.dbo.PreProjeto PP ON PP.idPreProjeto = Pr.idProjeto
			JOIN agentes.dbo.Nomes N ON N.idAgente = A.idAgente 
			LEFT JOIN sac.dbo.vwTramitarProjeto Tp ON Tp.idPronac = Pr.idPRONAC
			JOIN sac.dbo.Interessado I ON Pr.CgcCpf = I.CgcCpf
			WHERE Pr.idPRONAC = ". $pronac ."" ;

                        $db = Zend_Db_Table::getDefaultAdapter();
			$db->setFetchMode(Zend_DB::FETCH_OBJ);
			$resultado = $db->fetchAll($sql);

			return $resultado;
       	}

       	
}

