<?php
class tbPedidoAlteracaoProjetoDAO extends Zend_Db_Table
{
    protected $_name = "bdcorporativo.scsac.tbPedidoAlteracaoProjeto";

    public static function buscarDadosPedidoAlteracao($idpedidoalteracao = null)
    {
        $sql = "
        select distinct pap.idPRONAC,
        pr.idprojeto,
        pap.idpedidoalteracao,
        pap.tpAlteracaoProjeto,
        pr.NomeProjeto,
        ar.Descricao as area,
        seg.Descricao as segmento,
        pr.dtinicioexecucao,
        pr.dtfimexecucao,
        pap.dtSolicitacao,
	pap.stPedidoAlteracao,
	mun.Descricao as municipio,
        tap.dsAlteracaoProjeto,
        pp.tpProrrogacao
        ";
        if(!empty($idpedidoalteracao))
        {
            $sql .= ",
                  pap.idPedidoAlteracao,
                  apa.dtParecerTecnico,
                  apa.dsParecerTecnico,
                  apa.idTecnico,
                  pap.stDeferimentoAvaliacao,
                  apa.dsRetornoCoordenador,
                  apa.dtRetornoCoordenador,
                  apa.idCoordenador,
                  nm.descricao as nomecoordenador,
                  pap.dsJustificativaAvaliacao,
                  pap.dtAvaliacao,
                  pap.idAvaliador,
                  pr.CgcCpf,
                  pp.tpProrrogacao,
                  nm.Descricao as nomeAgente,
                  prep.objetivos";
        }
        $sql .= "
        from bdcorporativo.scsac.tbPedidoAlteracaoProjeto pap
        join sac.dbo.Projetos pr on pr.IdPRONAC = pap.idPRONAC
        join sac.dbo.Area ar on ar.Codigo = pr.Area
        join sac.dbo.Segmento seg on seg.Codigo = pr.Segmento
        left join sac.dbo.Abrangencia abrang on abrang.idProjeto = pr.idPronac AND abrang.stAbrangencia = 1 
        left join agentes.dbo.Municipios mun on mun.idMunicipioIBGE = abrang.idMunicipioIBGE
        left join bdcorporativo.scsac.tbTipoAlteracaoProjeto tap on tap.tpAlteracaoProjeto = pap.tpAlteracaoProjeto
        left join sac.dbo.PreProjeto prep on prep.idPreProjeto = pr.idProjeto
        left join agentes.dbo.Nomes nm on nm.idAgente = prep.idAgente
        left join bdcorporativo.scsac.tbAvaliacaoPedidoAlteracao apa on apa.idPedidoAlteracao = pap.idPedidoAlteracao
        left join bdcorporativo.scsac.tbProrrogacaoPrazo pp on pp.idPedidoAlteracao = pap.idPedidoAlteracao
        ";
        if(!empty($idpedidoalteracao))
        {
            $sql.=" where pap.idPedidoAlteracao='".$idpedidoalteracao."' ";
            //return $sql;die;
        }
        else{
            $sql .= " where pap.dtSolicitacao in
            (select max(dtSolicitacao) from bdcorporativo.scsac.tbPedidoAlteracaoProjeto where idPedidoAlteracao = pap.idPedidoAlteracao)";

        }
        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);
        return $db->fetchAll($sql);

    }

    public static function insertDadosProjeto($dados)
    {
        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        $alterar = $db->insert("bdcorporativo.scsac.tbAvaliacaoPedidoAlteracao", $dados);

        if ($alterar)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}
?>
