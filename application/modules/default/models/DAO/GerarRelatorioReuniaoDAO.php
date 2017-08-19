<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of GerarRelatorioReuniao
 *
 * @author 01373930160
 */
class GerarRelatorioReuniaoDAO extends Zend_Db_Table {

    public static function geraRelatorioReuniao()
    {

        $sql = "SELECT
                    tbReuniao.idNrReuniao,
                    bdcorporativo.scsac.tbPauta.IdPRONAC,
                    tbReuniao.NrReuniao,
                    tbReuniao.DtInicio,
                    tbReuniao.DtFechamento,
                    tbReuniao.stEstado,
                    bdcorporativo.scsac.tbPauta.stEnvioPlenario
                FROM         
                   bdcorporativo.scsac.tbPauta
                INNER JOIN
                   sac.dbo.tbReuniao as tbReuniao
                   ON bdcorporativo.scsac.tbPauta.idNrReuniao = tbReuniao.idNrReuniao
                WHERE     (tbReuniao.stEstado = 0)";

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

    public static  function consultaAreaCultural()
    {
        $sql = "SELECT * FROM sac.dbo.Area ";

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

    public static function consultaProjetosPautaReuniao($idReuniao = null)
    {
        $sql = "SELECT     pr.IdPRONAC AS idPronac, pr.AnoProjeto + pr.Sequencial AS pronac, pr.NomeProjeto, seg.Descricao AS DescricaoSegmento,
                      par.Atendimento AS StatusAtendimento, tp.dtEnvioPauta AS DataEnvioPauta, tp.stEnvioPlenario AS StatusEnvioPlenario, tp.stAnalise AS StatusAnalise,
                      tp.dsAnalise AS dsatusAnalise, seg.Codigo, ar.Descricao AS DescricaoArea, tr.idNrReuniao AS NumeroReuniao, tr.NrReuniao, tr.stEstado,
                      ar.Codigo AS CodigoArea
FROM         sac.dbo.Projetos AS pr INNER JOIN
                      sac.dbo.Segmento AS seg ON pr.Segmento = seg.Codigo INNER JOIN
                      sac.dbo.Parecer AS par ON pr.IdPRONAC = par.idPRONAC INNER JOIN
                      bdcorporativo.scsac.tbPauta AS tp ON pr.IdPRONAC = tp.IdPRONAC INNER JOIN
                      sac.dbo.Area AS ar ON pr.Area = ar.Codigo INNER JOIN
                      sac.dbo.tbReuniao AS tr ON par.idPRONAC = pr.IdPRONAC where stEnvioPlenario = 'S'
and     (tr.stEstado = 0)";
        if($idReuniao != 0)
        {
            $sql .=" and tr.NrReuniao = $idReuniao";
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

        return $db->fetchAll($sql);

    }

    public static  function consultaValorAprovado($idPronac = null)
    {
        $sql = "SELECT DISTINCT
                      sac.dbo.Projetos.IdPRONAC AS Pronac,
                      bdcorporativo.scsac.tbPauta.idNrReuniao AS NumeroReuniao,
                      sac.dbo.Projetos.NomeProjeto,
                      sac.dbo.tbReuniao.stEstado AS StatusEstado,
                      sac.dbo.tbPlanilhaAprovacao.nrOcorrencia AS NumeroOcorrencia,
                      sac.dbo.tbPlanilhaAprovacao.vlUnitario AS ValorUnitario,
                      sac.dbo.tbPlanilhaAprovacao.qtItem AS QuantidadeItem,
                      sac.dbo.Projetos.Area AS CodigoArea,
                      sac.dbo.Projetos.Segmento AS CodigoSegmento,
                      sac.dbo.Area.Descricao AS DescricaoArea,
                      sac.dbo.tbPlanilhaAprovacao.nrOcorrencia * sac.dbo.tbPlanilhaAprovacao.vlUnitario * sac.dbo.tbPlanilhaAprovacao.qtItem AS Total
                FROM
                      sac.dbo.Projetos INNER JOIN
                      bdcorporativo.scsac.tbPauta
                      ON sac.dbo.Projetos.IdPRONAC = bdcorporativo.scsac.tbPauta.IdPRONAC
                INNER JOIN
                      sac.dbo.tbReuniao
                      ON bdcorporativo.scsac.tbPauta.idNrReuniao = sac.dbo.tbReuniao.idNrReuniao
                INNER JOIN
                      sac.dbo.tbPlanilhaAprovacao ON  sac.dbo.Projetos.IdPRONAC = sac.dbo.tbPlanilhaAprovacao.IdPRONAC
                INNER JOIN
                      sac.dbo.Area ON  sac.dbo.Projetos.Area =  sac.dbo.Area.Codigo
                WHERE     sac.dbo.tbReuniao.stEstado = 0 ";

                   if (!empty($idPronac))
                   {
                      $sql.= " AND sac.dbo.Projetos.IdPRONAC = $idPronac ";
                   }

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
