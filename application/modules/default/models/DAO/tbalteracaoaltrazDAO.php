<?php

class tbalteracaoaltrazDAO extends Zend_Db_Table
{
    protected $_name = "bdcorporativo.scsac.tbalteracaorazaosocialprojeto";

    public static function buscarDadosAltRaz($idpedidoalteracao)
    {
        $sql = "select       
                       trsp.nmrazaosocial,
                       trsp.dsjustificativa,
                       tap.idPRONAC,
                       prepr.idAgente
                       from bdcorporativo.scsac.tbalteracaorazaosocialprojeto trsp
                       join bdcorporativo.scsac.tbPedidoAlteracaoProjeto tap on tap.idPedidoAlteracao = trsp.idPedidoAlteracao
                       join sac.dbo.Projetos pr on pr.IdPRONAC = tap.idPRONAC
                       join sac.dbo.PreProjeto prepr on prepr.idPreProjeto = pr.idProjeto
                       where trsp.idpedidoalteracao= ".$idpedidoalteracao;

        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);
        return $db->fetchAll($sql);
    }
    
    public static function alterarRazaoSocialProjeto($dados, $idagente)
    {
        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        $where = "idagente = ".$idagente;
        $alterar = $db->update("agentes.dbo.Nomes", $dados, $where);

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
