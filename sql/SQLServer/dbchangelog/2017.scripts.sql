/** SCRIPTS CRIADOS EM 2017 **/

/* INCLUSÃO DE TÍTULO A SER UTILIZADO NO ENVIO DE E-MAIL */
INSERT INTO sac.dbo.Verificacao (idVerificacao, idTipo, Descricao, stEstado)
     VALUES (620, 6, 'Enquadramento de projeto cultural', 1);

/* INCLUSÃO DE CORPO A SER UTILIZADO NO ENVIO DE E-MAIL */
INSERT INTO sac.dbo.tbTextoEmail(idTextoemail, idAssunto, dsTexto)
     VALUES (23, 620, '<p>Prezado Proponente,</p>');

--select * from sac.dbo.tbTextoEmail where idTextoemail = 23

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
FROM SAC.dbo.preprojeto                     AS a
INNER JOIN SAC.dbo.tbMovimentacao           AS b ON (a.idPreProjeto = b.idProjeto)
INNER JOIN SAC.dbo.tbAvaliacaoProposta      AS c ON (a.idPreProjeto = c.idProjeto)
INNER JOIN agentes.dbo.Agentes              AS d ON (a.idAgente     = d.idAgente)
INNER JOIN sac.dbo.Verificacao              AS e ON (b.Movimentacao = e.idVerificacao)
INNER JOIN sac.dbo.PlanoDistribuicaoProduto AS f ON (a.idPreProjeto = f.idProjeto)

WHERE b.Movimentacao IN(96,97,127,128)
      AND b.stEstado = 0
      AND a.stTipoDemanda = 'NA'
      AND f.stPrincipal = 1
	  AND c.stEstado = 0
	  AND NOT EXISTS(SELECT * FROM SAC.dbo.Projetos AS u WHERE a.idPreProjeto = idProjeto)

GO

GRANT  SELECT ON dbo.vwPainelAvaliarPropostas  TO usuarios_internet
GO

-- ===========================================================================================
-- Autor: Rômulo Menhô Barbosa
-- Data de Criação: 14/01/2017
-- Descrição: Listar propostas culturais
-- ===========================================================================================

IF OBJECT_ID ('vwListarPropostas', 'V') IS NOT NULL
DROP VIEW vwListarPropostas ;
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO
CREATE VIEW dbo.vwListarPropostas
AS
SELECT a.idPreProjeto AS idProjeto,a.NomeProjeto AS NomeProposta,a.stPlanoAnual,d.CNPJCPF,a.idAgente,c.idTecnico AS idUsuario,
	   SAC.dbo.fnNomeTecnicoMinc(c.idTecnico) AS Tecnico,
	   CASE
	     WHEN a.AreaAbrangencia = 0 THEN 251
	     WHEN a.AreaAbrangencia = 1 THEN 160
		END AS idSecretaria,
	   CONVERT(CHAR(20),c.DtAvaliacao,120) AS DtAdmissibilidade,
	   DATEDIFF(d,c.DtAvaliacao,GETDATE()) as dias,c.idAvaliacaoProposta,b.idMovimentacao,a.stTipoDemanda
FROM SAC.dbo.PreProjeto                AS a
INNER JOIN SAC.dbo.tbMovimentacao      AS b ON (a.idPreProjeto = b.idProjeto)
INNER JOIN SAC.dbo.tbAvaliacaoProposta AS c ON (a.idPreProjeto = c.idProjeto)
INNER JOIN AGENTES.dbo.Agentes         AS d ON (a.idAgente = d.idAgente)
WHERE a.stEstado = 1
	  AND a.stTipoDemanda = 'NA'
	  AND b.Movimentacao = 127
	  AND b.stEstado = 0
	  AND c.ConformidadeOK = 1
	  AND c.stEstado = 0
	  AND NOT EXISTS(SELECT *	FROM SAC.dbo.Projetos AS u WHERE a.idPreProjeto = idProjeto)
GO

GRANT  SELECT ON dbo.vwListarPropostas  TO usuarios_internet
GO


/**
 * @since 23/01/2017 10:17
 * Criação da tabela TbAssinatura
 */
CREATE TABLE TbAssinatura
(
    idAssinatura INT PRIMARY KEY NOT NULL IDENTITY,
    idPronac INT NOT NULL,
    idDocumento INT NOT NULL,
    dtAssinatura DATETIME NOT NULL,
    idAssinante INT NOT NULL,
    idOrgao INT NOT NULL,
    IdCargo INT NOT NULL
);
CREATE INDEX IX_idPronac ON TbAssinatura (idPronac);
CREATE INDEX IX_idAssinante ON TbAssinatura (idAssinante);

-- [ Novos grupos para quando o orgao for SEFIC ]
INSERT INTO Tabelas.dbo.Grupos (gru_codigo, gru_sistema, gru_nome, gru_status) VALUES (147, 21, 'Coordenador-Geral de Admissibilidade e Aprovação', 1);
INSERT INTO Tabelas.dbo.Grupos (gru_codigo, gru_sistema, gru_nome, gru_status) VALUES (148, 21, 'Diretor do Deparmentamento de Incentivo à Produção Cultural', 1);
INSERT INTO Tabelas.dbo.Grupos (gru_codigo, gru_sistema, gru_nome, gru_status) VALUES (149, 21, 'Secretario de Fomento e Incentivo à Cultura', 1);
--select * from Tabelas..Grupos where gru_sistema = 21 and gru_codigo in (147,148,149)


-- [ Novos grupos para quando o orgao for SAV ]
INSERT INTO Tabelas.dbo.Grupos (gru_codigo, gru_sistema, gru_nome, gru_status) VALUES (150, 21, 'Coordenador Geral de Acompanhamento e Prestação de Contas', 1);
INSERT INTO Tabelas.dbo.Grupos (gru_codigo, gru_sistema, gru_nome, gru_status) VALUES (151, 21, 'Diretor de Departamento de Políticas Audiovisuais', 1);
INSERT INTO Tabelas.dbo.Grupos (gru_codigo, gru_sistema, gru_nome, gru_status) VALUES (152, 21, 'Secretário do Audiovisual', 1);
--select * from Tabelas..Grupos where gru_sistema = 21 and gru_codigo in (150,151,152)


-- [ Novo órgão criado. OBS: já existe em PROD ]
INSERT INTO sac.dbo.Orgaos (Codigo, Sigla, idSecretaria, Vinculo, Status, stVinculada) VALUES (682, 'SAV/DPAC', 160, 0, 0, null);