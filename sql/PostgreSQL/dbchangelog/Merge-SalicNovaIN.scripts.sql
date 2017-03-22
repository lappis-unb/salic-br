/** SCRIPTS CRIADOS EM 2016 **/

/* Adição da coluna 'id_login_cidadao' no banco de dados  */
ALTER TABLE ControleDeAcesso.SGCacesso ADD id_login_cidadao int;

/* INCLUSÃO DA SITUAÇÃO PARA TRABALHAR COM O ENQUADRAMENTO */
INSERT INTO SAC.Situacao VALUES('B01','Proposta transformada em projeto','C','1');

-- Remove Not Null na coluna idPolicaoDaLogo
ALTER TABLE sac.PlanoDistribuicaoProduto ALTER COLUMN idPosicaoDaLogo TYPE INT;

--  Modificação de estrutura para que esteja compatível com sql server

CREATE SEQUENCE sac.tipo_idtipo_seq NO MINVALUE NO MAXVALUE NO CYCLE;
ALTER TABLE sac.tipo ALTER COLUMN idtipo SET DEFAULT nextval('sac.tipo_idtipo_seq');
ALTER SEQUENCE sac.tipo_idtipo_seq OWNED BY sac.tipo.idtipo;

CREATE SEQUENCE sac.verificacao_idverificacao_seq NO MINVALUE NO MAXVALUE NO CYCLE;
ALTER TABLE sac.verificacao ALTER COLUMN idverificacao SET DEFAULT nextval('sac.verificacao_idverificacao_seq');
ALTER SEQUENCE sac.verificacao_idverificacao_seq OWNED BY sac.verificacao.idverificacao;

-- Remove Not Null na coluna stPlanoAnual
ALTER TABLE sac.preprojeto ALTER COLUMN stplanoanual DROP NOT NULL;

CREATE SEQUENCE sac.documentosexigidos_codigo_seq NO MINVALUE NO MAXVALUE NO CYCLE;
ALTER TABLE sac.documentosexigidos ALTER COLUMN codigo SET DEFAULT nextval('sac.documentosexigidos_codigo_seq');
ALTER SEQUENCE sac.documentosexigidos_codigo_seq OWNED BY sac.documentosexigidos.codigo;

-- Cria novo documento
INSERT INTO SAC.DocumentosExigidos (Descricao,Area,Opcao,stEstado,stUpload)
VALUES ('RESULTADO DA SELEÇÃO PÚBLICA','0',2,1,1);

-- Cria novo campo para prorrogacao automática
ALTER TABLE sac.PreProjeto ADD tpProrrogacao int DEFAULT 1 NULL;

/* INCLUSÃO DA SITUAÇÃO PARA TRABALHAR COM O ENQUADRAMENTO */
INSERT INTO SAC.Situacao VALUES('B02','Projeto enquadrado','C','1');

/* INCLUSÃO DA SITUAÇÃO PARA TRABALHAR COM O ENQUADRAMENTO */
INSERT INTO SAC.Situacao VALUES('B03','Projeto enquadrado com recusso','C','1');

/* ADIÇÃO DA COLUNA TP_ENQUADRAMENTO - onde os valores são : 1 para artigo 26 e 2 para artigo 18 */
ALTER TABLE sac.Segmento ADD tp_enquadramento CHAR(1) NULL;

CREATE SEQUENCE sac.tbplanilhaitens_idplanilhaitens_seq NO MINVALUE NO MAXVALUE NO CYCLE;
CREATE TABLE SAC.tbplanilhaitens
(
    idplanilhaitens INTEGER PRIMARY KEY NOT NULL DEFAULT nextval('sac.tbplanilhaitens_idplanilhaitens_seq'),
    descricao VARCHAR(250) NOT NULL,
    idusuario INTEGER DEFAULT 0 NOT NULL
);

----

-- Criação da tabela SAC.tbItensPlanilhaProduto
CREATE TABLE SAC.tbItensPlanilhaProduto
(
    idItensPlanilhaProduto INT PRIMARY KEY NOT NULL,
    idProduto INT NOT NULL,
    idPlanilhaEtapa INT NOT NULL,
    idPlanilhaItens INT NOT NULL,
    idUsuario INT,
    CONSTRAINT FK_tbItensPlanilhaProduto_tbPlanilhaEtapa FOREIGN KEY (idPlanilhaEtapa) REFERENCES sac.tbPlanilhaEtapa (idPlanilhaEtapa),
    CONSTRAINT FK_tbItensPlanilhaProduto_tbPlanilhaItens FOREIGN KEY (idPlanilhaItens) REFERENCES sac.tbPlanilhaItens (idPlanilhaItens)
);
CREATE UNIQUE INDEX IX_tbItensPlanilhaProduto ON tbItensPlanilhaProduto (idProduto, idPlanilhaEtapa, idPlanilhaItens);
CREATE SEQUENCE sac.tbitensplanilhaproduto_iditensplanilhaproduto_seq NO MINVALUE NO MAXVALUE NO CYCLE;
ALTER TABLE sac.tbitensplanilhaproduto ALTER COLUMN iditensplanilhaproduto SET DEFAULT nextval('sac.tbitensplanilhaproduto_iditensplanilhaproduto_seq');
ALTER SEQUENCE sac.tbitensplanilhaproduto_iditensplanilhaproduto_seq OWNED BY sac.tbitensplanilhaproduto.iditensplanilhaproduto;



TRUNCATE SAC.tbdeparaplanilhaaprovacao;
TRUNCATE SAC.tbrecursoxplanilhaaprovacao;
TRUNCATE SAC.tbplanilhaaprovacao;
TRUNCATE SAC.tbplanilhajustificativa;
TRUNCATE SAC.tbdeparaplanilhaaprovacao;
TRUNCATE SAC.tbrecursoxplanilhaaprovacao;
TRUNCATE SAC.tbplanilhaProjeto;
TRUNCATE SAC.tbplanilhaproposta;
TRUNCATE SAC.tbItensPlanilhaProduto;
TRUNCATE SAC.tintegerensplanilhaproduto;
TRUNCATE SAC.tbPlanilhaItens;
TRUNCATE SAC.tbsolicitaritem;
TRUNCATE SAC.tbPlanilhaEtapa;



--CNPQ
