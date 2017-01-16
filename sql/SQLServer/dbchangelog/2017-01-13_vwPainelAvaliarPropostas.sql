-- ===========================================================================================
-- Autor: Rômulo Menhô Barbosa
-- Data de Criação: 14/01/2017
-- Descrição: Listar propostas culturais a serem avaliadas (painel de admissibilidade)
-- ===========================================================================================

IF OBJECT_ID ('vwPainelAvaliarPropostas', 'V') IS NOT NULL
DROP VIEW vwPainelAvaliarPropostas ;
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE VIEW dbo.vwPainelAvaliarPropostas
AS
SELECT a.idPreProjeto AS idProjeto,a.NomeProjeto AS NomeProposta,a.idAgente,CONVERT(CHAR(20),b.DtMovimentacao,120) AS DtMovimentacao,
	   DATEDIFF(d,b.DtMovimentacao,GETDATE()) AS diasDesdeMovimentacao,b.idMovimentacao,b.Movimentacao AS CodSituacao,
	   CONVERT(CHAR(20),c.DtAvaliacao,120) AS DtAdmissibilidade, DATEDIFF(d,c.DtAvaliacao,GETDATE()) AS diasCorridos,
	   c.idTecnico AS idUsuario,c.DtAvaliacao,c.idAvaliacaoProposta,
	   (SELECT Usuarios.usu_nome FROM tabelas.dbo.Usuarios WHERE usu_codigo = c.idTecnico) AS Tecnico,
	   CASE
	     WHEN a.AreaAbrangencia = 0 THEN 251
	     WHEN a.AreaAbrangencia = 1 THEN 160
	   END AS idSecretaria,
	   d.CNPJCPF
FROM SAC.dbo.preprojeto                AS a
INNER JOIN SAC.dbo.tbMovimentacao      AS b ON (a.idPreProjeto = b.idProjeto)
INNER JOIN SAC.dbo.tbAvaliacaoProposta AS c ON (a.idPreProjeto = c.idProjeto)
INNER JOIN agentes.dbo.Agentes         AS d ON (a.idAgente     = d.idAgente)
INNER JOIN sac.dbo.Verificacao         AS e ON (b.Movimentacao = e.idVerificacao)
WHERE b.Movimentacao IN(96,97,127,128)
      AND b.stEstado = 0
	  AND c.stEstado = 0
	  AND NOT EXISTS(SELECT * FROM SAC.dbo.Projetos AS u WHERE a.idPreProjeto = idProjeto)

GO

GRANT  SELECT ON dbo.vwPainelAvaliarPropostas  TO usuarios_internet
GO
