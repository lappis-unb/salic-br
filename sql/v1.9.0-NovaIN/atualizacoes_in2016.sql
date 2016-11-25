-- Acrescenta "Recolhimento" na etapa de Custos de Produto
UPDATE SAC.dbo.tbPlanilhaEtapa
SET tpCusto='P'
WHERE idPlanilhaEtapa=5;

-- Renomeia o título das etapas

UPDATE SAC.dbo.tbPlanilhaEtapa
SET Descricao='Pré-Produção'
WHERE idPlanilhaEtapa=1;

UPDATE SAC.dbo.tbPlanilhaEtapa
SET Descricao='Produção'
WHERE idPlanilhaEtapa=2;

UPDATE SAC.dbo.tbPlanilhaEtapa
SET Descricao='Pós-Produção'
WHERE idPlanilhaEtapa=3;

-- Remove Not Null na coluna idPolicaoDaLogo
ALTER TABLE sac.dbo.PlanoDistribuicaoProduto ALTER COLUMN idPosicaoDaLogo INT;

-- cria coluna para lista de Execucao Imediata na Proposta
CREATE TABLE sac.dbo.ExecucaoImediata
(
  idExecucaoImediata INT PRIMARY KEY NOT NULL,
  Descricao VARCHAR(200)
);

-- Popula a tabela ExecucaoImediata

INSERT INTO sac.dbo.ExecucaoImediata (idExecucaoImediata,Descricao)
VALUES (0,'Proposta normal');

INSERT INTO sac.dbo.ExecucaoImediata (idExecucaoImediata,Descricao)
VALUES (1,'Proteção do patrimônio material');

INSERT INTO sac.dbo.ExecucaoImediata (idExecucaoImediata,Descricao)
VALUES (2,'Proteção do patrimônio imaterial');

INSERT INTO sac.dbo.ExecucaoImediata (idExecucaoImediata,Descricao)
VALUES (3,'Proteção de acervos');

INSERT INTO sac.dbo.ExecucaoImediata (idExecucaoImediata,Descricao)
VALUES (4,'Planos anuais');

INSERT INTO sac.dbo.ExecucaoImediata (idExecucaoImediata,Descricao)
VALUES (5,'Proposta museológica');

INSERT INTO sac.dbo.ExecucaoImediata (idExecucaoImediata,Descricao)
VALUES (6,'Proposta de manutenção de corpos estáveis');

INSERT INTO sac.dbo.ExecucaoImediata (idExecucaoImediata,Descricao)
VALUES (7,'Proposta de construção de equipamentos culturais');

INSERT INTO sac.dbo.ExecucaoImediata (idExecucaoImediata,Descricao)
VALUES (8,'Proposta aprovado em editais');

INSERT INTO sac.dbo.ExecucaoImediata (idExecucaoImediata,Descricao)
VALUES (9,'Proposta  com contratos de patrocínios');

-- Remove Not Null na coluna stPlanoAnual
ALTER TABLE sac.dbo.PreProjeto ALTER COLUMN stPlanoAnual BIT;