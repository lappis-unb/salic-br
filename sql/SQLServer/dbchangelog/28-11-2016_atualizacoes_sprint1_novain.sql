-- Acrescenta "Recolhimento" na etapa de Custos de Produto
UPDATE SAC.dbo.tbPlanilhaEtapa
SET tpCusto='P'
WHERE idPlanilhaEtapa=5;

-- Remove Not Null na coluna idPolicaoDaLogo
ALTER TABLE sac.dbo.PlanoDistribuicaoProduto ALTER COLUMN idPosicaoDaLogo INT;

-- cria mais duas etapas
INSERT INTO SAC.dbo.tbPlanilhaEtapa (Descricao, tpCusto, stEstado, tpGrupo) VALUES ('Pós-Produção', 'P', 1, 'A');
INSERT INTO SAC.dbo.tbPlanilhaEtapa (Descricao, tpCusto, stEstado, tpGrupo) VALUES ('Custos Vinculados', 'A', 1, 'D');

-- cria itens para a lista de execução imediata
INSERT INTO SAC.dbo.Verificacao (idTipo, Descricao, stEstado) VALUES (23, 'Proposta normal', 1);
INSERT INTO SAC.dbo.Verificacao (idTipo, Descricao, stEstado) VALUES (23, 'Proteção do patrimônio material', 1);
INSERT INTO SAC.dbo.Verificacao (idTipo, Descricao, stEstado) VALUES (23, 'Proteção do patrimônio imaterial', 1);
INSERT INTO SAC.dbo.Verificacao (idTipo, Descricao, stEstado) VALUES (23, 'Proteção  de acervos', 1);
INSERT INTO SAC.dbo.Verificacao (idTipo, Descricao, stEstado) VALUES (23, 'Planos anuais', 1);
INSERT INTO SAC.dbo.Verificacao (idTipo, Descricao, stEstado) VALUES (23, 'Proposta museológica', 1);
INSERT INTO SAC.dbo.Verificacao (idTipo, Descricao, stEstado) VALUES (23, 'Proposta de manutenção de corpos estáveis', 1);
INSERT INTO SAC.dbo.Verificacao (idTipo, Descricao, stEstado) VALUES (23, 'Proposta de construção de equipamentos culturais', 1);
INSERT INTO SAC.dbo.Verificacao (idTipo, Descricao, stEstado) VALUES (23, 'Proposta aprovado em editais', 1);
INSERT INTO SAC.dbo.Verificacao (idTipo, Descricao, stEstado) VALUES (23, 'Proposta  com contratos de patrocínios', 1);

-- Remove Not Null na coluna stPlanoAnual
ALTER TABLE sac.dbo.PreProjeto ALTER COLUMN stPlanoAnual BIT;

-- Cria novo documento
INSERT INTO SAC.dbo.DocumentosExigidos (Descricao,Area,Opcao,stEstado,stUpload)
VALUES ('RESULTADO DA SELEÇÃO PÚBLICA','0',2,1,1);

-- Cria novo campo para prorrogacao automática
ALTER TABLE sac.dbo.PreProjeto ADD tpProrrogacao BIT DEFAULT 1 NULL;