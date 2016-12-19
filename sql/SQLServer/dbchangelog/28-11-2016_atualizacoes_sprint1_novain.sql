-- Acrescenta "Recolhimento" na etapa de Custos de Produto
UPDATE SAC.dbo.tbPlanilhaEtapa
SET tpCusto='P'
WHERE idPlanilhaEtapa=5;

-- Remove Not Null na coluna idPolicaoDaLogo
ALTER TABLE sac.dbo.PlanoDistribuicaoProduto ALTER COLUMN idPosicaoDaLogo INT;



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

-- cria mais duas etapas para planilha orcamentaria
INSERT INTO SAC.dbo.tbPlanilhaEtapa (Descricao, tpCusto, stEstado, tpGrupo) VALUES ('Pós-Produção', 'P', 1, 'A');
INSERT INTO SAC.dbo.tbPlanilhaEtapa (Descricao, tpCusto, stEstado, tpGrupo) VALUES ('Custos Vinculados', 'A', 1, 'D');

-- ajusta o cárdapio da etapa de custos vinculados no banco de dados
INSERT INTO SAC.dbo.tbItensPlanilhaProduto
(idItensPlanilhaProduto, idProduto, idPlanilhaEtapa, idPlanilhaItens, idUsuario)
VALUES(40302, 51, 8, 8197, 236);
INSERT INTO SAC.dbo.tbItensPlanilhaProduto
(idItensPlanilhaProduto, idProduto, idPlanilhaEtapa, idPlanilhaItens, idUsuario)
VALUES(40303, 51, 8, 8198, 236);
INSERT INTO SAC.dbo.tbItensPlanilhaProduto
(idItensPlanilhaProduto, idProduto, idPlanilhaEtapa, idPlanilhaItens, idUsuario)
VALUES(40304, 51, 8, 8199, 236);
INSERT INTO SAC.dbo.tbItensPlanilhaProduto
(idItensPlanilhaProduto, idProduto, idPlanilhaEtapa, idPlanilhaItens, idUsuario)
VALUES(40305, 1, 8, 8197, 236);
INSERT INTO SAC.dbo.tbItensPlanilhaProduto
(idItensPlanilhaProduto, idProduto, idPlanilhaEtapa, idPlanilhaItens, idUsuario)
VALUES(40306, 1, 8, 8198, 236);
INSERT INTO SAC.dbo.tbItensPlanilhaProduto
(idItensPlanilhaProduto, idProduto, idPlanilhaEtapa, idPlanilhaItens, idUsuario)
VALUES(40307, 1, 8, 8199, 236);
INSERT INTO SAC.dbo.tbItensPlanilhaProduto
(idItensPlanilhaProduto, idProduto, idPlanilhaEtapa, idPlanilhaItens, idUsuario)
VALUES(40308, 19, 8, 8197, 236);
INSERT INTO SAC.dbo.tbItensPlanilhaProduto
(idItensPlanilhaProduto, idProduto, idPlanilhaEtapa, idPlanilhaItens, idUsuario)
VALUES(40309, 19, 8, 8198, 236);
INSERT INTO SAC.dbo.tbItensPlanilhaProduto
(idItensPlanilhaProduto, idProduto, idPlanilhaEtapa, idPlanilhaItens, idUsuario)
VALUES(40310, 19, 8, 8199, 236);


-- cria novos itens
INSERT INTO SAC.dbo.tbPlanilhaItens
(idPlanilhaItens, Descricao, idUsuario)
VALUES(8197, 'Custos de Administração', 236);
INSERT INTO SAC.dbo.tbPlanilhaItens
(idPlanilhaItens, Descricao, idUsuario)
VALUES(8198, 'Custos de Divulgação', 236);
INSERT INTO SAC.dbo.tbPlanilhaItens
(idPlanilhaItens, Descricao, idUsuario)
VALUES(8199, 'Controle e Auditoria', 236);
