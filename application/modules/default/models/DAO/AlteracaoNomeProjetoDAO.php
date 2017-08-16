<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AtleracaoNomeProjeto
 *
 * @author 01129075125
 */
class AlteracaoNomeProjetoDAO extends Zend_Db_Table{

    public static function buscarDadosParecerTecnico($idpedidoalteracao){
      $sql = "select
                    aipa.dsAvaliacao as dsparecertecnico,
                    aipa.dtFimAvaliacao as dtparecertecnico,
                    nom.Descricao as nometecnico
                from
                    bdcorporativo.scSAC.tbAvaliacaoItemPedidoAlteracao aipa
                    inner join bdcorporativo.scSAC.tbPedidoAlteracaoXTipoAlteracao pt on pt.idPedidoAlteracao = aipa.idPedidoAlteracao
                    inner join bdcorporativo.scSAC.tbPedidoAlteracaoProjeto pap on pap.idPedidoAlteracao = pt.idPedidoAlteracao
                    inner join agentes.dbo.Nomes nom on nom.idAgente = aipa.idAgenteAvaliador
                where
                    pap.IdPRONAC = {$idpedidoalteracao} and pt.tpAlteracaoProjeto = 5
                ";
        $db = Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);
        $resultado = $db->fetchAll($sql);
        return $resultado;

    }

}
?>
