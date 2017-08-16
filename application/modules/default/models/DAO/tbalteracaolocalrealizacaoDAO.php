<?php

class tbalteracaolocalrealizacaoDAO extends Zend_Db_Table
{
    protected $_name = "bdcorporativo.scSAC.tbalteracaolocalrealizacao";

    public static function buscarDadosAltLocRel($idpedidoalteracao)
    {
        $sql = "
        select
        mun.Descricao as mun,
        uf.Descricao as uf,
        pais.Descricao as pais,
        alr.tpoperacao,
        alr.dsjustificativa
        from bdcorporativo.scSAC.tbAlteracaoLocalRealizacao alr
        left join agentes.dbo.Municipios mun on mun.idMunicipioIBGE = alr.idMunicipioIBGE
        left join agentes.dbo.UF uf on uf.idUF = alr.idUF
        join agentes.dbo.Pais pais on pais.idPais = alr.idPais
        where alr.idpedidoalteracao= ".$idpedidoalteracao." order by pais, uf, mun asc";
        
        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);
        return $db->fetchAll($sql);
    }

}

?>
