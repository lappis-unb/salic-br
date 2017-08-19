<?php

class tbalteracaofictecDAO extends Zend_Db_Table
{
    protected $_name = "bdcorporativo.scsac.tbalteracaofichatecnica";

    public static function buscarDadosFicTec($idpedidoalteracao)
    {
        $sql = "select dsfichatecnica,
                dsjustificativa
                from bdcorporativo.scsac.tbAlteracaoFichaTecnica
                where idpedidoalteracao= ".$idpedidoalteracao;
        
        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);
        //return $sql;
        return $db->fetchAll($sql);
    }

}

?>
