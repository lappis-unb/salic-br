<?php

class tbalteracaonomeprojetoDAO extends Zend_Db_Table
{
    protected $_name = "bdcorporativo.scsac.tbalteracaonomeprojeto";

    public static function buscarDadosNmProj($idpedidoalteracao)
    {
        $sql = "       select tba.nmprojeto,
                       tba.dsjustificativa,
                       tap.idPRONAC
                       from bdcorporativo.scsac.tbalteracaonomeprojeto tba
                       join bdcorporativo.scsac.tbPedidoAlteracaoProjeto tap on tap.idPedidoAlteracao = tba.idPedidoAlteracao
                       where tba.idpedidoalteracao= ".$idpedidoalteracao;

        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);
        return $db->fetchAll($sql);
    }

    public static function buscarDadosParecerTecnico($idpedidoalteracao)
    {
        $sql = "select dtparecertecnico,
                       dsparecertecnico,
                       nom.Descricao as nometecnico
                       from bdcorporativo.scsac.tbavaliacaopedidoalteracao apa
                       join agentes.dbo.Nomes nom on nom.idAgente =  apa.idTecnico
                       where apa.idpedidoalteracao= ".$idpedidoalteracao;

        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);
        return $db->fetchAll($sql);

    }

    public static function alterarNomeProjeto($dados,$idpronac)
    {
        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        $where = "idpronac = ".$idpronac;
        $alterar = $db->update("sac.dbo.projetos", $dados, $where);

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
