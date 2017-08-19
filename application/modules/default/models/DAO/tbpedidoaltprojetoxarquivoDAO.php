<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
*/

/**
 * Description of tbpedidoaltprojetoxarquivo
 *
 * @author 01129075125
 */
class tbpedidoaltprojetoxarquivoDAO extends Zend_Db_Table
{
    protected $_name = "bdcorporativo.scCORP.tbpedidoaltprojetoxarquivo";

    public static function buscarArquivos($idpedidoalteracao)
    {
        $sql = "
        select
        papxa.idArquivo,
        ta.nmarquivo
        from bdcorporativo.scsac.tbPedidoAltProjetoXArquivo papxa
        join bdcorporativo.scCorp.tbArquivo ta on ta.idArquivo = papxa.idArquivo
        where papxa.idPedidoAlteracao =".$idpedidoalteracao;

        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);
        return $db->fetchAll($sql);
    }
    
}
?>
