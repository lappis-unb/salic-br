<?php
Class Dadosprojeto extends Zend_Db_Table
{

    protected $_name    = 'sac.dbo.Projetos';

    public static function buscar($pronac)
    {
        $sql = "SELECT 
            Pr.idPRONAC,
            Tp.Emissor,
            Tp.dtTramitacaoEnvio,
            Tp.Situacao, 
            Tp.Destino, 
            Tp.Receptor, 
            Tp.DtTramitacaoRecebida, 
            Pr.NomeProjeto,
            Pr.ResumoProjeto, 
            Tp.meDespacho,
            St.Descricao dsSituacao,
            Mc.Descricao dsMecanismo,
            Sg.Descricao dsSegmento,
            Ar.Descricao dsArea,
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
            sac.dbo.fnOutrasFontes(Pr.idPronac) AS OutrasFontes,
            ISNULL(sac.dbo.fnValorDaProposta(Pr.idPRONAC),sac.dbo.fnValorSolicitado(Pr.AnoProjeto,Pr.Sequencial)) as ValorProposta,
            CASE WHEN Pr.Mecanismo in ('2','6')
            THEN sac.dbo.fnValorAprovadoConvenio(Pr.AnoProjeto,Pr.Sequencial) 
            ELSE sac.dbo.fnValorAprovado(Pr.AnoProjeto,Pr.Sequencial)
            END AS ValorAprovado,
            CASE WHEN Pr.Mecanismo in ('2','6')
            THEN sac.dbo.fnValorAprovadoConvenio(Pr.AnoProjeto,Pr.Sequencial)
            ELSE sac.dbo.fnValorAprovado(Pr.AnoProjeto,Pr.Sequencial) + sac.dbo.fnOutrasFontes(Pr.idPronac)                
            END AS ValorProjeto,
            sac.dbo.fnCustoProjeto (Pr.AnoProjeto,Pr.Sequencial) as ValorCaptado
            FROM sac.dbo.Projetos Pr
            INNER JOIN sac.dbo.Situacao St ON St.Codigo = Pr.Situacao
            INNER JOIN sac.dbo.Area Ar ON  Ar.Codigo = Pr.Area
            INNER JOIN sac.dbo.Segmento Sg ON Sg.Codigo = Pr.Segmento
            INNER JOIN sac.dbo.Mecanismo Mc ON Mc.Codigo = Pr.Mecanismo
            INNER JOIN sac.dbo.Enquadramento En ON En.idPRONAC =  Pr.idPRONAC
            LEFT JOIN agentes.dbo.Agentes A ON A.CNPJCPF = Pr.CgcCpf
            LEFT JOIN sac.dbo.PreProjeto PP ON PP.idPreProjeto = Pr.idProjeto
            LEFT JOIN agentes.dbo.Nomes N ON N.idAgente = A.idAgente 
            LEFT JOIN sac.dbo.vwTramitarProjeto Tp ON Tp.idPronac = Pr.idPRONAC
            LEFT JOIN sac.dbo.Interessado I ON Pr.CgcCpf = I.CgcCpf
            WHERE Pr.idPRONAC = {$pronac}";

        $db = Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);
        $resultado = $db->fetchAll($sql);

        return $resultado;
    }

}
