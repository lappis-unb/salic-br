<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ConstultaReuniao
 *
 * @author 01373930160
 */
class ConsultaReuniao {

    public static function listaReuniao()
    {

    $sql = "SELECT
                 sac.dbo.tbReuniao.idNrReuniao,
                 bdcorporativo.scsac.tbPauta.IdPRONAC,
                 sac.dbo.tbReuniao.NrReuniao, tbReuniao.DtInicio,
                 sac.dbo.tbReuniao.DtFechamento,
                 sac.dbo.tbReuniao.stEstado
            FROM
               bdcorporativo.scsac.tbPauta
            INNER JOIN
                      sac.dbo.tbReuniao ON
                      bdcorporativo.scsac.tbPauta.idNrReuniao = sac.dbo.tbReuniao.idNrReuniao
            WHERE sac.dbo.tbReuniao.stEstado = 0";

                try
		{
			$db = Zend_Db_Table::getDefaultAdapter();
			$db->setFetchMode(Zend_DB::FETCH_OBJ);
		}
		catch (Zend_Exception_Db $e)
		{
			$this->view->message = "Erro ao buscar os Tipos de Documentos: " . $e->getMessage();
		}

		return $db->fetchAll($sql);
    }

}

?>

