-- =========================================================================================
-- Autor: Rômulo Menhô Barbosa
-- Data de Criação: 23/09/2015
-- Descrição: Compatilizar os pagamentos com os cheques debitados na conta movimento do projeto.
-- =========================================================================================

IF OBJECT_ID ('vwCompatilizar_Despesa_DebitoNaConta', 'V') IS NOT NULL
DROP VIEW vwCompatilizar_Despesa_DebitoNaConta ;
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE VIEW dbo.vwCompatilizar_Despesa_DebitoNaConta
AS
SELECT a.idPronac,a.AnoProjeto+a.Sequencial as Pronac,a.NomeProjeto,
       c.idPlanilhaAprovacao,
       d.idComprovantePagamento,d.nrDocumentoDePagamento,d.dtPagamento,d.tpFormaDePagamento,
	   sac.dbo.fnVlComprovadoDocumento(a.idPronac,d.nrDocumentoDePagamento) as vlComprovado,
       e.dsLancamento,e.dtLancamento,e.vlLancamento as vlDebitado,e.stLancamento,
	   sac.dbo.fnVlComprovadoDocumento(a.idPronac,d.nrDocumentoDePagamento)- e.vlLancamento as vlDiferenca
FROM sac.dbo.Projetos a
INNER JOIN sac.dbo.tbPlanilhaAprovacao                                  b on (a.idPronac                  = b.idPronac)
INNER JOIN BDCORPORATIVO.scSAC.tbComprovantePagamentoxPlanilhaAprovacao c on (b.idPlanilhaAprovacao       = c.idPlanilhaAprovacao)
INNER JOIN BDCORPORATIVO.scSAC.tbComprovantePagamento                   d on (c.idComprovantePagamento    = d.idComprovantePagamento) 
LEFT  JOIN Sac.dbo.tbLancamentoBancario e on (a.IdPRONAC = e.idPronac and '0000'+d.nrDocumentoDePagamento = e.nrLancamento)
GROUP BY a.idPronac,c.idPlanilhaAprovacao,d.idComprovantePagamento,a.AnoProjeto+a.Sequencial,a.NomeProjeto,d.nrDocumentoDePagamento,
        d.dtPagamento,d.tpFormaDePagamento,e.dsLancamento,e.dtLancamento,e.stLancamento,e.vlLancamento,d.vlComprovacao

GO 

GRANT  SELECT , UPDATE  ON dbo.vwCompatilizar_Despesa_DebitoNaConta  TO usuarios_internet
GO
