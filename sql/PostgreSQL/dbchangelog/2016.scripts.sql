/** SCRIPTS CRIADOS EM 2016 **/

/* Adição da coluna 'id_login_cidadao' no banco de dados  */
ALTER TABLE ControleDeAcesso.SGCacesso ADD id_login_cidadao int;

/* INCLUSÃO DA SITUAÇÃO PARA TRABALHAR COM O ENQUADRAMENTO */
INSERT INTO SAC.Situacao VALUES('B01','Proposta transformada em projeto','C','1');

-- Acrescenta "Recolhimento" na etapa de Custos de Produto
UPDATE SAC.tbPlanilhaEtapa
SET tpCusto='P'
WHERE idPlanilhaEtapa=5;

-- Remove Not Null na coluna idPolicaoDaLogo
ALTER TABLE sac.PlanoDistribuicaoProduto ALTER COLUMN idPosicaoDaLogo INT;

-- cria itens para a lista de execução imediata
INSERT INTO SAC.Verificacao (idTipo, Descricao, stEstado) VALUES (23, 'Proposta normal', 1);
INSERT INTO SAC.Verificacao (idTipo, Descricao, stEstado) VALUES (23, 'Proteção do patrimônio material', 1);
INSERT INTO SAC.Verificacao (idTipo, Descricao, stEstado) VALUES (23, 'Proteção do patrimônio imaterial', 1);
INSERT INTO SAC.Verificacao (idTipo, Descricao, stEstado) VALUES (23, 'Proteção  de acervos', 1);
INSERT INTO SAC.Verificacao (idTipo, Descricao, stEstado) VALUES (23, 'Planos anuais', 1);
INSERT INTO SAC.Verificacao (idTipo, Descricao, stEstado) VALUES (23, 'Proposta museológica', 1);
INSERT INTO SAC.Verificacao (idTipo, Descricao, stEstado) VALUES (23, 'Proposta de manutenção de corpos estáveis', 1);
INSERT INTO SAC.Verificacao (idTipo, Descricao, stEstado) VALUES (23, 'Proposta de construção de equipamentos culturais', 1);
INSERT INTO SAC.Verificacao (idTipo, Descricao, stEstado) VALUES (23, 'Proposta aprovado em editais', 1);
INSERT INTO SAC.Verificacao (idTipo, Descricao, stEstado) VALUES (23, 'Proposta  com contratos de patrocínios', 1);

-- Remove Not Null na coluna stPlanoAnual
ALTER TABLE sac.PreProjeto ALTER COLUMN stPlanoAnual BIT;

-- Cria novo documento
INSERT INTO SAC.DocumentosExigidos (Descricao,Area,Opcao,stEstado,stUpload)
VALUES ('RESULTADO DA SELEÇÃO PÚBLICA','0',2,1,1);

-- Cria novo campo para prorrogacao automática
ALTER TABLE sac.PreProjeto ADD tpProrrogacao BIT DEFAULT 1 NULL;

-- cria mais duas etapas para planilha orcamentaria
INSERT INTO SAC.tbPlanilhaEtapa (Descricao, tpCusto, stEstado, tpGrupo) VALUES ('Pós-Produção', 'P', 1, 'A');
INSERT INTO SAC.tbPlanilhaEtapa (Descricao, tpCusto, stEstado, tpGrupo) VALUES ('Custos Vinculados', 'A', 1, 'D');

-- cria novos itens
INSERT INTO SAC.tbPlanilhaItens
(idPlanilhaItens, Descricao, idUsuario)
VALUES(8197, 'Custos de Administração', 236);
INSERT INTO SAC.tbPlanilhaItens
(idPlanilhaItens, Descricao, idUsuario)
VALUES(8198, 'Custos de Divulgação', 236);
INSERT INTO SAC.tbPlanilhaItens
(idPlanilhaItens, Descricao, idUsuario)
VALUES(8199, 'Controle e Auditoria', 236);

-- ajusta o cárdapio da etapa de custos vinculados
insert into sac.tbItensPlanilhaProduto
(idProduto,idPlanilhaEtapa,idPlanilhaItens,idUsuario)
select 0,8,8197,236;

insert into sac.tbItensPlanilhaProduto
(idProduto,idPlanilhaEtapa,idPlanilhaItens,idUsuario)
select 0,8,8198,236;

insert into sac.tbItensPlanilhaProduto
(idProduto,idPlanilhaEtapa,idPlanilhaItens,idUsuario)
select 0,8,8199,236;

insert into sac.tbItensPlanilhaProduto
(idProduto,idPlanilhaEtapa,idPlanilhaItens,idUsuario)
select 0,8,5249,236;

-- amarrando os valores de pos-producao etapa 7
-- @todo ATENCAO, esse valores não irão para produção, o Rômulo fará o insert correto
INSERT INTO SAC.tbItensPlanilhaProduto (idProduto, idPlanilhaEtapa, idPlanilhaItens, idUsuario) VALUES (1, 7, 4001, 236);
INSERT INTO SAC.tbItensPlanilhaProduto (idProduto, idPlanilhaEtapa, idPlanilhaItens, idUsuario) VALUES (1, 7, 4002, 236);
INSERT INTO SAC.tbItensPlanilhaProduto (idProduto, idPlanilhaEtapa, idPlanilhaItens, idUsuario) VALUES (1, 7, 4003, 236);
INSERT INTO SAC.tbItensPlanilhaProduto (idProduto, idPlanilhaEtapa, idPlanilhaItens, idUsuario) VALUES (1, 7, 4005, 236);
INSERT INTO SAC.tbItensPlanilhaProduto (idProduto, idPlanilhaEtapa, idPlanilhaItens, idUsuario) VALUES (19, 7, 5000, 236);
INSERT INTO SAC.tbItensPlanilhaProduto (idProduto, idPlanilhaEtapa, idPlanilhaItens, idUsuario) VALUES (19, 7, 5001, 236);
INSERT INTO SAC.tbItensPlanilhaProduto (idProduto, idPlanilhaEtapa, idPlanilhaItens, idUsuario) VALUES (19, 7, 5002, 236);
INSERT INTO SAC.tbItensPlanilhaProduto (idProduto, idPlanilhaEtapa, idPlanilhaItens, idUsuario) VALUES (19, 7, 5003, 236);
INSERT INTO SAC.tbItensPlanilhaProduto (idProduto, idPlanilhaEtapa, idPlanilhaItens, idUsuario) VALUES (19, 7, 5004, 236);
INSERT INTO SAC.tbItensPlanilhaProduto (idProduto, idPlanilhaEtapa, idPlanilhaItens, idUsuario) VALUES (19, 7, 5005, 236);
INSERT INTO SAC.tbItensPlanilhaProduto (idProduto, idPlanilhaEtapa, idPlanilhaItens, idUsuario) VALUES (51, 7, 6000, 236);
INSERT INTO SAC.tbItensPlanilhaProduto (idProduto, idPlanilhaEtapa, idPlanilhaItens, idUsuario) VALUES (51, 7, 6001, 236);
INSERT INTO SAC.tbItensPlanilhaProduto (idProduto, idPlanilhaEtapa, idPlanilhaItens, idUsuario) VALUES (51, 7, 6002, 236);
INSERT INTO SAC.tbItensPlanilhaProduto (idProduto, idPlanilhaEtapa, idPlanilhaItens, idUsuario) VALUES (51, 7, 6003, 236);
INSERT INTO SAC.tbItensPlanilhaProduto (idProduto, idPlanilhaEtapa, idPlanilhaItens, idUsuario) VALUES (51, 7, 6004, 236);
INSERT INTO SAC.tbItensPlanilhaProduto (idProduto, idPlanilhaEtapa, idPlanilhaItens, idUsuario) VALUES (51, 7, 6005, 236);

/* INCLUSÃO DA SITUAÇÃO PARA TRABALHAR COM O ENQUADRAMENTO */
INSERT INTO SAC.Situacao VALUES('B02','Projeto enquadrado','C','1');

/* INCLUSÃO DA SITUAÇÃO PARA TRABALHAR COM O ENQUADRAMENTO */
INSERT INTO SAC.Situacao VALUES('B03','Projeto enquadrado com recusso','C','1');

/* ADIÇÃO DA COLUNA TP_ENQUADRAMENTO - onde os valores são : 1 para artigo 26 e 2 para artigo 18 */
ALTER TABLE sac.Segmento ADD tp_enquadramento CHAR(1) NULL;