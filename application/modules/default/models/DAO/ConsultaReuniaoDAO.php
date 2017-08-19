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
class ConsultaReuniaoDAO
{

    public static function listaReuniao($idreuniao=null)
    {
        $sql = "SELECT
                     tbr.idNrReuniao,
                     tp.IdPRONAC,
                     tbr.NrReuniao, 
                     tbr.DtInicio,
                     tbr.DtFechamento,
                     tbr.stEstado
                     FROM sac.dbo.tbReuniao tbr
                     JOIN bdcorporativo.scsac.tbPauta tp ON tp.idNrReuniao = tbr.idNrReuniao";
        if (!empty($idreuniao))
        {
            $sql .= " where tbr.idnrreuniao=$idreuniao";
        }
        else
        {
            $sql .= " where tbr.stEstado = 0";
        }

        try
        {
            $db= Zend_Db_Table::getDefaultAdapter();
            $db->setFetchMode(Zend_DB::FETCH_OBJ);
        }
        catch (Zend_Exception_Db $e)
        {
            $this->view->message = "Erro ao buscar os Tipos de Documentos: " . $e->getMessage();
        }

//        return $sql; die;
        return $db->fetchAll($sql);
    }

    public static function listaReuniaoAndamento()
    {
        $sql = "SELECT
                     tbr.idNrReuniao,
                     tp.IdPRONAC,
                     tbr.NrReuniao,
                     tbr.DtInicio,
                     tbr.DtFechamento,
                     tbr.stEstado
                     FROM sac.dbo.tbReuniao tbr
                     JOIN bdcorporativo.scsac.tbPauta tp ON tp.idNrReuniao = tbr.idNrReuniao
               WHERE tbr.stEstado = 0";

        try
        {
            $db= Zend_Db_Table::getDefaultAdapter();
            $db->setFetchMode(Zend_DB::FETCH_OBJ);
        }
        catch (Zend_Exception_Db $e)
        {
            $this->view->message = "Erro: " . $e->getMessage();
        }

        return $db->fetchAll($sql);
    }

    public static function dadosReuniaoEncerrada($idnrreuniao)
    {
        $sql = "select
		pr.IdPRONAC as idpronac,
                pr.NomeProjeto,
                pr.AnoProjeto+pr.Sequencial as pronac,
                tp.stAnalise,
                ap.AprovadoReal,
                ap.ResumoAprovacao,
                ar.Descricao as area,
                tcv.dsConsolidacao
                from bdcorporativo.scsac.tbPauta tp
                join sac.dbo.Projetos pr on pr.IdPRONAC = tp.IdPRONAC
                left join sac.dbo.Aprovacao ap on ap.IdPRONAC = pr.IdPRONAC
                join sac.dbo.Area ar on ar.Codigo = pr.Area
                join bdcorporativo.scsac.tbConsolidacaoVotacao tcv on tcv.IdPRONAC = tp.IdPRONAC and tcv.idNrReuniao = tp.idNrReuniao
                WHERE tcv.idnrreuniao = $idnrreuniao
                and  ((ap.DtAprovacao in (select max(DtAprovacao) as dtaprovacao from sac.dbo.Aprovacao where IdPRONAC=pr.IdPRONAC)) or tp.stAnalise = 'IS')
                order by 7 asc";

        try
        {
            $db= Zend_Db_Table::getDefaultAdapter();
            $db->setFetchMode(Zend_DB::FETCH_OBJ);
        }
        catch (Zend_Exception_Db $e)
        {
            $this->view->message = " Erro: " . $e->getMessage();
        }
        return $db->fetchAll($sql);
    }

    public static function listaReuniaoEncerrada()
    {
        $sql = "SELECT
                     tbr.idNrReuniao,
                     tbr.NrReuniao,
                     tbr.DtInicio,
                     tbr.DtFinal,
                     COUNT(tp.IdPRONAC) as qtdpronac
                     FROM sac.dbo.tbReuniao tbr
                     JOIN bdcorporativo.scsac.tbPauta tp ON tp.idNrReuniao = tbr.idNrReuniao
                     JOIN bdcorporativo.scsac.tbConsolidacaoVotacao tcv on tcv.idNrReuniao = tp.idNrReuniao and tcv.IdPRONAC=tp.IdPRONAC
		     WHERE tbr.stPlenaria = 'E'
		     group by tbr.idNrReuniao,
                     tbr.NrReuniao,
                     tbr.DtInicio,
                     tbr.DtFechamento,
                     tbr.DtFinal";

        try
        {
            $db= Zend_Db_Table::getDefaultAdapter();
            $db->setFetchMode(Zend_DB::FETCH_OBJ);
        }
        catch (Zend_Exception_Db $e)
        {
            $this->view->message = "Erro: " . $e->getMessage();
        }

        return $db->fetchAll($sql);
    }

    public static function verificaReuniao()
    {
        $sql = "SELECT idNrReuniao, stEstado from sac.dbo.tbReuniao WHERE stEstado = 0";

        try
        {
            $db= Zend_Db_Table::getDefaultAdapter();
            $db->setFetchMode(Zend_DB::FETCH_OBJ);
        }
        catch (Zend_Exception_Db $e)
        {
            $this->view->message = "Erro: " . $e->getMessage();
        }

//        return $sql; die;
        return $db->fetchAll($sql);
    }

}
?>

