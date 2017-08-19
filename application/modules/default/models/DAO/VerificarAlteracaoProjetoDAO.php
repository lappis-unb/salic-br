<?php

Class VerificarAlteracaoProjetoDAO extends Zend_Db_Table
{

    protected $_name = 'sac.dbo.Projetos';

    public static function buscarProjetos()
    {
        $sql = "select
                tpap.idPedidoAlteracao,
                pr.idpronac,
                pr.AnoProjeto+pr.Sequencial as pronac,
                pr.NomeProjeto,
                ar.Descricao as area,
                seg.Descricao as segmento,
                pr.DtInicioExecucao,
                pr.DtFimExecucao,
                pr.UfProjeto,
                taipa.idAgenteAvaliador,
                taipa.dtInicioAvaliacao,
                taipa.dtFimAvaliacao,
                taipa.dsAvaliacao,
                taipa.stAvaliacaoItemPedidoAlteracao,
                tpta.tpAlteracaoProjeto
                from bdcorporativo.scsac.tbPedidoAlteracaoXTipoAlteracao tpta
                JOIN bdcorporativo.scsac.tbPedidoAlteracaoProjeto tpap on tpap.idPedidoAlteracao = tpta.idPedidoAlteracao
                JOIN sac.dbo.Projetos pr on pr.IdPRONAC = tpap.IdPRONAC
                JOIN sac.dbo.Area ar on ar.Codigo = pr.Area
                JOIN sac.dbo.Segmento seg on seg.Codigo = pr.Segmento
                LEFT JOIN bdcorporativo.scsac.tbAvaliacaoItemPedidoAlteracao taipa on taipa.idPedidoAlteracao = tpta.idPedidoAlteracao
                and taipa.tpAlteracaoProjeto =  tpta.tpAlteracaoProjeto
               ";
        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);
        $resultado = $db->fetchAll($sql);

        return $resultado;
    }

    public static function BuscarDadosGenericos($idPronac, $idPedidoAlteracao = null)
    {
        /*$sql = "select
                pr.AnoProjeto+pr.Sequencial as pronac,
                pr.NomeProjeto,
                pr.CgcCpf,
                nm.Descricao as proponente
                from sac..Projetos pr
                JOIN bdcorporativo.scsac.tbPedidoAlteracaoProjeto tpap on tpap.IdPRONAC = pr.IdPRONAC
                JOIN sac..tbProposta pp on pp.idPedidoAlteracao = tpap.idPedidoAlteracao
                JOIN agentes.dboAgentes ag on ag.CNPJCPF = pr.CgcCpf
                JOIN agentes.dboNomes nm on nm.idAgente = ag.idAgente
                where pp.idPedidoAlteracao=$idpedidoalteracao";*/

        $sql = "select
                    taipa.idAvaliacaoItemPedidoAlteracao AS idAvaliacao,
                    nm.idNome,
                    pr.IdPRONAC as idPronac,
                    pr.AnoProjeto+pr.Sequencial as pronac,
                    pr.AnoProjeto as ano,
                    pr.Sequencial as seq,
                    pr.NomeProjeto,
                    pr.CgcCpf as CgcCpf,
                    nm.Descricao as proponente,
                    pp.dsFichaTecnica AS fichaTecnicaSolicitada,
                    tpax.dsJustificativa
                from
                    bdcorporativo.scsac.tbPedidoAlteracaoProjeto tpap
                    inner JOIN sac.dbo.Projetos pr on pr.IdPRONAC = tpap.IdPRONAC
                    left JOIN sac.dbo.tbProposta pp on pp.idPedidoAlteracao = tpap.idPedidoAlteracao
                    left JOIN agentes.dbo.Agentes ag on ag.CNPJCPF = pr.CgcCpf
                    inner join sac.dbo.PreProjeto prepro on pr.idProjeto = prepro.idPreProjeto
                    inner JOIN agentes.dbo.Nomes nm on nm.idAgente = ag.idAgente
                    inner join bdcorporativo.scsac.tbPedidoAlteracaoXTipoAlteracao tpax on tpax.idPedidoAlteracao = tpap.idPedidoAlteracao
                    left join bdcorporativo.scsac.tbAvaliacaoItemPedidoAlteracao taipa on taipa.idPedidoAlteracao = tpap.idPedidoAlteracao AND taipa.tpAlteracaoProjeto = 4
                where
                   tpap.IdPRONAC=$idPronac AND tpap.idPedidoAlteracao = $idPedidoAlteracao";
        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_ASSOC);
        $resultado = $db->fetchRow($sql);


        return $resultado;
    }

    public static function buscarArquivosSolicitacao($idPronac, $tipo, $idPedidoAlteracao = null)
    {

        $sql = "select
                    papxa.idArquivo,
                    ta.nmarquivo
                from
                    bdcorporativo.scsac.tbPedidoAltProjetoXArquivo papxa
                    INNER JOIN bdcorporativo.scCorp.tbArquivo ta on ta.idArquivo = papxa.idArquivo
                    INNER JOIN bdcorporativo.scsac.tbPedidoAlteracaoProjeto pap on pap.idPedidoAlteracao = papxa.idPedidoAlteracao
                where
                    pap.IdPRONAC =$idPronac and papxa.tpAlteracaoProjeto = $tipo AND pap.idPedidoAlteracao = $idPedidoAlteracao ";
        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);
        return $db->fetchAll($sql);
    }


    public static function buscarDadosParecerTecnico($idPronac,$tipo, $idPedidoAlteracao = null){
        $sql = "select
                    CAST(aipa.dsAvaliacao as TEXT) as dsparecertecnico,
                    aipa.dtFimAvaliacao as dtparecertecnico,
                    nom.Descricao as nometecnico
                    
                from
                    bdcorporativo.scsac.tbAvaliacaoItemPedidoAlteracao aipa
                    inner join bdcorporativo.scsac.tbPedidoAlteracaoXTipoAlteracao pt on pt.idPedidoAlteracao = aipa.idPedidoAlteracao
                    inner join bdcorporativo.scsac.tbTipoAlteracaoProjeto tap on tap.tpAlteracaoProjeto = pt.tpAlteracaoProjeto
                    inner join bdcorporativo.scsac.tbPedidoAlteracaoProjeto pap on pap.idPedidoAlteracao = pt.idPedidoAlteracao
                    inner join agentes.dbo.Nomes nom on nom.idAgente = aipa.idAgenteAvaliador
                where
                    pap.IdPRONAC = {$idPronac} and aipa.tpAlteracaoProjeto = $tipo and tap.tpAlteracaoProjeto = $tipo AND pap.idPedidoAlteracao = $idPedidoAlteracao";
         $db = Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);
        $resultado = $db->fetchAll($sql);

        return $resultado;

    }

}
?>
