-- =========================================================================================
-- Autor: Rômulo Menhô Barbosa
-- Data de Criação: 30/04/2016
-- Descrição: Conciliacao bancária.
-- =========================================================================================

IF OBJECT_ID ('vwConciliacaoBancaria', 'V') IS NOT NULL
DROP VIEW vwConciliacaoBancaria ;
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE VIEW dbo.vwConciliacaoBancaria
AS
SELECT QtdeA as idPronac,ChaveA as Pronac,CampoA as NomeProjeto,CampoB as ItemOrcamentario,
       ChaveB as CNPJCPF,CampoC as Fornecedor,QtdeB as idComprovantePagamento,ChaveC as nrDocumentoDePagamento,
	   DataA as dtPagamento,ValorA as vlPagamento,ValorB as vlComprovado,CampoD as dsLancamento,DataB as dtLancamento,
	   ValorC as vlDebitado,ValorD as vlDiferenca
  FROM sac.dbo.Intranet
  WHERE Tipo = 35


GO 

GRANT  SELECT ON dbo.vwConciliacaoBancaria  TO usuarios_internet
GO
