-- SQL
SELECT * FROM Agentes.Agentes
WHERE idAgente = 1;


-- DUMP

DROP SCHEMA agentes CASCADE;
DROP SCHEMA tabelas CASCADE;
DROP SCHEMA controledeacesso CASCADE;

CREATE SCHEMA IF NOT EXISTS sac AUTHORIZATION postgres;
CREATE SCHEMA IF NOT EXISTS agentes AUTHORIZATION postgres;
CREATE SCHEMA IF NOT EXISTS tabelas AUTHORIZATION postgres;
CREATE SCHEMA IF NOT EXISTS controledeacesso AUTHORIZATION postgres;

CREATE TABLE agentes.agentes (
  idAgente integer,
  CNPJCPF character varying(14),
  CNPJCPFSuperior character varying(14),
  TipoPessoa boolean,
  DtCadastro timestamp without time zone,
  DtAtualizacao timestamp without time zone,
  DtValidade timestamp without time zone,
  Status smallint,
  Usuario integer
);
ALTER TABLE agentes.agentes ADD CONSTRAINT agentes_idagente_pk PRIMARY KEY (idagente);
ALTER TABLE agentes.agentes OWNER TO postgres;


CREATE TABLE Tabelas.Usuarios (
  usu_codigo smallint,
  usu_identificacao character varying(16),
  usu_nome character varying(20),
  usu_pessoa integer,
  usu_orgao smallint,
  usu_sala character varying(20),
  usu_ramal smallint,
  usu_nivel smallint,
  usu_exibicao character varying(1),
  usu_sql_login character varying(20),
  usu_sql_senha character varying(1),
  usu_duracao_senha smallint,
  usu_data_validade timestamp without time zone,
  usu_limite_utilizacao timestamp without time zone,
  usu_senha character varying(15),
  usu_validacao character varying(10),
  usu_status smallint,
  usu_seguranca character varying(8),
  usu_data_atualizacao timestamp without time zone,
  usu_conta_nt integer,
  usu_dica_intranet integer,
  usu_controle text,
  usu_localizacao smallint,
  usu_andar character varying(10),
  usu_telefone character varying(10)
);
ALTER TABLE Tabelas.Usuarios ADD CONSTRAINT usuarios_usu_codigo_pk PRIMARY KEY (usu_codigo);
ALTER TABLE Tabelas.Usuarios OWNER TO postgres;

CREATE TABLE tabelas.usuarios
(
  usu_codigo SMALLINT PRIMARY KEY NOT NULL,
  usu_identificacao CHAR(16) NOT NULL,
  usu_nome VARCHAR(20) NOT NULL,
  usu_pessoa INT NOT NULL,
  usu_orgao SMALLINT,
  usu_sala VARCHAR(20),
  usu_ramal SMALLINT,
  usu_nivel TINYINT NOT NULL,
  usu_exibicao CHAR NOT NULL,
  usu_SQL_login VARCHAR(20) NOT NULL,
  usu_SQL_senha CHAR NOT NULL,
  usu_duracao_senha SMALLINT NOT NULL,
  usu_data_validade SMALLDATETIME NOT NULL,
  usu_limite_utilizacao SMALLDATETIME NOT NULL,
  usu_senha CHAR(15) NOT NULL,
  usu_validacao CHAR(10) NOT NULL,
  usu_status TINYINT NOT NULL,
  usu_seguranca CHAR(8) NOT NULL,
  usu_data_atualizacao SMALLDATETIME NOT NULL,
  usu_conta_nt INT,
  usu_dica_intranet INT NOT NULL,
  usu_controle TIMESTAMP NOT NULL,
  usu_localizacao TINYINT,
  usu_andar CHAR(10),
  usu_telefone CHAR(10),
  CONSTRAINT FK_Usuarios_Pessoas FOREIGN KEY (usu_pessoa) REFERENCES Pessoas (pes_codigo)
);
CREATE INDEX _dta_index_Usuarios_c_11_1645248916__K2 ON Usuarios (usu_identificacao);
CREATE UNIQUE INDEX usu_IDX_identificacao ON Usuarios (usu_identificacao);
CREATE INDEX _dta_index_Usuarios_11_1645248916__K1_4_5 ON Usuarios (usu_codigo, usu_pessoa, usu_orgao);
CREATE INDEX _dta_index_Usuarios_11_1645248916__K1_5 ON Usuarios (usu_codigo, usu_orgao);


CREATE TABLE tabelas.UsuariosXOrgaosXGrupos
(
  uog_usuario SMALLINT NOT NULL,
  uog_orgao SMALLINT NOT NULL,
  uog_grupo SMALLINT NOT NULL,
  uog_status BIT DEFAULT 1 NOT NULL,
  CONSTRAINT uog_PK_usuorggru PRIMARY KEY (uog_usuario, uog_orgao, uog_grupo),
  CONSTRAINT FK_UsuariosXOrgaosXGrupos_Usuarios FOREIGN KEY (uog_usuario) REFERENCES tabelas.Usuarios (usu_codigo),
  CONSTRAINT FK_UsuariosXOrgaosXGrupos_Orgaos FOREIGN KEY (uog_orgao) REFERENCES tabelas.Orgaos (org_codigo),
  CONSTRAINT FK_UsuariosXOrgaosXGrupos_Grupos FOREIGN KEY (uog_grupo) REFERENCES tabelas.Grupos (gru_codigo)
);
CREATE INDEX uog_IDX_usuario ON tabelas.UsuariosXOrgaosXGrupos (uog_usuario);
CREATE INDEX uxg_IDX_grupo ON tabelas.UsuariosXOrgaosXGrupos (uog_grupo);
CREATE INDEX _dta_index_UsuariosXOrgaosXGrupos_11_1606296782__K1_K4_2_3 ON tabelas.UsuariosXOrgaosXGrupos (uog_usuario, uog_status, uog_orgao, uog_grupo);
CREATE INDEX _dta_index_UsuariosXOrgaosXGrupos_11_1606296782__K4_K1_K3_K2 ON tabelas.UsuariosXOrgaosXGrupos (uog_status, uog_usuario, uog_grupo, uog_orgao);
CREATE INDEX idx_uog_uog_orgao ON tabelas.UsuariosXOrgaosXGrupos (uog_orgao);







CREATE TABLE sac.abrangencia (
  idabrangencia integer,
  idprojeto integer,
  idpais integer,
  iduf integer,
  idmunicipioibge integer,
  usuario integer,
  stabrangencia boolean,
  siabrangencia character varying(1),
  dsjustificativa character varying(500),
  dtiniciorealizacao timestamp without time zone,
  dtfimrealizacao timestamp without time zone
);

ALTER TABLE sac.abrangencia OWNER TO postgres;

CREATE TABLE agentes.endereconacional (
  idendereco integer,
  idagente integer,
  tipoendereco integer,
  tipologradouro integer,
  logradouro character varying(100),
  numero character varying(15),
  bairro character varying(100),
  complemento character varying(100),
  cidade character varying(6),
  uf integer,
  cep character varying(8),
  municipio character varying(100),
  ufdescricao character varying(50),
  status boolean,
  divulgar boolean,
  usuario integer
);

ALTER TABLE agentes.endereconacional OWNER TO postgres;

CREATE TABLE agentes.internet (
  tipo smallint,
  chavea character varying(20),
  chaveb character varying(20),
  chavec character varying(50),
  chaved character varying(100),
  campoa character varying(100),
  campob character varying(100),
  campoc character varying(100),
  valora numeric(23,4),
  valorb numeric(23,4),
  valorc numeric(23,4),
  valord numeric(23,4),
  valore numeric(23,4)
);

ALTER TABLE agentes.internet OWNER TO postgres;

CREATE TABLE agentes.municipios (
  idmunicipioibge character varying(6),
  idufibge integer,
  idmeso character varying(4),
  idmicro character varying(5),
  descricao character varying(100)
);

ALTER TABLE agentes.municipios OWNER TO postgres;

CREATE TABLE tabelas.orgaos (
  codigo integer,
  sigla character varying(20),
  idsecretaria integer,
  vinculo boolean,
  status boolean
);

ALTER TABLE tabelas.orgaos OWNER TO postgres;

CREATE TABLE sac.planodedivulgacao (
  idplanodivulgacao integer,
  idprojeto integer,
  idpeca integer,
  idveiculo integer,
  usuario integer,
  siplanodedivulgacao character varying(1),
  iddocumento integer,
  stplanodivulgacao boolean
);

ALTER TABLE sac.planodedivulgacao OWNER TO postgres;

CREATE TABLE sac.preprojeto (
  idpreprojeto integer,
  idagente integer,
  nomeprojeto character varying(300),
  mecanismo integer,
  agenciabancaria character varying(5),
  areaabrangencia boolean,
  dtiniciodeexecucao timestamp without time zone,
  dtfinaldeexecucao timestamp without time zone,
  justificativa text,
  nratotombamento character varying(25),
  dtatotombamento timestamp without time zone,
  esferatombamento smallint,
  resumodoprojeto text,
  objetivos text,
  acessibilidade text,
  democratizacaodeacesso text,
  etapadetrabalho text,
  fichatecnica text,
  sinopse text,
  impactoambiental text,
  especificacaotecnica text,
  estrategiadeexecucao text,
  dtaceite timestamp without time zone,
  dtarquivamento timestamp without time zone,
  stestado boolean,
  stdatafixa boolean,
  stplanoanual boolean,
  idusuario integer,
  sttipodemanda character varying(2),
  idedital integer
);

ALTER TABLE sac.preprojeto OWNER TO postgres;

CREATE TABLE sac.projetos (
  idpronac integer,
  anoprojeto character varying(2),
  sequencial character varying(5),
  ufprojeto character varying(2),
  area character varying(1),
  segmento character varying(4),
  mecanismo character varying(1),
  nomeprojeto character varying(300),
  processo character varying(17),
  cgccpf character varying(14),
  situacao character varying(3),
  dtprotocolo timestamp without time zone,
  dtanalise timestamp without time zone,
  modalidade character varying(3),
  orgaoorigem integer,
  orgao integer,
  dtsaida timestamp without time zone,
  dtretorno timestamp without time zone,
  unidadeanalise character varying(15),
  analista character varying(100),
  dtsituacao timestamp without time zone,
  resumoprojeto text,
  providenciatomada character varying(500),
  localizacao character varying(20),
  dtinicioexecucao timestamp without time zone,
  dtfimexecucao timestamp without time zone,
  solicitadoufir numeric(23,4),
  solicitadoreal numeric(23,4),
  solicitadocusteioufir numeric(23,4),
  solicitadocusteioreal numeric(23,4),
  solicitadocapitalufir numeric(23,4),
  solicitadocapitalreal numeric(23,4),
  logon integer,
  idprojeto integer
);

ALTER TABLE sac.projetos OWNER TO postgres;

CREATE TABLE controledeacesso.sgcacesso (
  idusuario integer,
  cpf character varying(11),
  nome character varying(100),
  dtnascimento timestamp without time zone,
  email character varying(60),
  senha character varying(15),
  dtcadastro timestamp without time zone,
  situacao smallint,
  dtsituacao timestamp without time zone
);
ALTER TABLE controledeacesso.sgcacesso ADD CONSTRAINT sgcacesso_idusuario_pk PRIMARY KEY (idusuario);
ALTER TABLE controledeacesso.sgcacesso OWNER TO postgres;

CREATE TABLE agentes.tbagentefisico (
  idagente integer,
  stsexo character varying(1),
  stestadocivil character varying(2),
  stnecessidadeespecial character varying(1),
  nmmae character varying(100),
  nmpai character varying(100),
  dtnascimento timestamp without time zone,
  stcorraca character varying(1),
  nridentificadorprocessual character varying(17)
);

ALTER TABLE agentes.tbagentefisico OWNER TO postgres;

CREATE TABLE agentes.tbausencia (
  idausencia integer,
  idtipoausencia integer,
  idagente integer,
  dtinicioausencia timestamp without time zone,
  dtfimausencia timestamp without time zone,
  iddocumento integer,
  dsjustificativaausencia character varying(300),
  stimpacto character varying(1),
  siausencia character varying(1),
  idalteracao integer,
  dtcadastroausencia timestamp without time zone
);

ALTER TABLE agentes.tbausencia OWNER TO postgres;

CREATE TABLE agentes.tbcredenciamentoparecerista (
  idcredenciamentoparecerista integer,
  idcodigoarea integer,
  idcodigosegmento character varying(2),
  sicredenciamento character varying(1),
  idagente integer,
  qtponto smallint,
  idverificacao integer
);

ALTER TABLE agentes.tbcredenciamentoparecerista OWNER TO postgres;

CREATE TABLE sac.tbdocumentosagentes (
  iddocumentosagentes integer,
  codigodocumento integer,
  idagente integer,
  "Data" timestamp without time zone,
  noarquivo character varying(130),
  taarquivo integer,
  imdocumento text
);

ALTER TABLE sac.tbdocumentosagentes OWNER TO postgres;

CREATE TABLE sac.tbdocumentospreprojeto (
  iddocumentospreprojetos integer,
  codigodocumento integer,
  idprojeto integer,
  idpronac integer,
  "Data" timestamp without time zone,
  noarquivo character varying(130),
  taarquivo integer,
  bidocumento timestamp without time zone,
  dsdocumento character varying(1000),
  imdocumento text
);

ALTER TABLE sac.tbdocumentospreprojeto OWNER TO postgres;

CREATE TABLE agentes.tbinformacaoprofissional (
  idinformacaoprofissional integer,
  idagente integer,
  nmprofissao character varying(80),
  nmcargo character varying(50),
  dsendereco character varying(200),
  dtiniciovinculo timestamp without time zone,
  dtfimvinculo timestamp without time zone,
  iddocumento integer,
  siinformacao character varying(1)
);

ALTER TABLE agentes.tbinformacaoprofissional OWNER TO postgres;

CREATE TABLE sac.tbmovimentacao (
  idmovimentacao integer,
  idprojeto integer,
  movimentacao integer,
  dtmovimentacao timestamp without time zone,
  stestado boolean,
  usuario integer
);

ALTER TABLE sac.tbmovimentacao OWNER TO postgres;

CREATE TABLE agentes.tbtitulacaoconselheiro (
  idagente integer,
  cdarea character varying(1),
  cdsegmento character varying(4),
  sttitular boolean,
  stconselheiro character varying(1)
);

ALTER TABLE agentes.tbtitulacaoconselheiro OWNER TO postgres;

CREATE TABLE agentes.tbvinculo (
  idvinculo integer,
  idagenteproponente integer,
  dtvinculo timestamp without time zone,
  sivinculo character varying(1),
  idusuarioresponsavel integer
);

ALTER TABLE agentes.tbvinculo OWNER TO postgres;

CREATE TABLE agentes.tbvinculoproposta (
  idvinculoproposta integer,
  idvinculo integer,
  idpreprojeto integer,
  sivinculoproposta character varying(1)
);

ALTER TABLE agentes.tbvinculoproposta OWNER TO postgres;

CREATE TABLE agentes.telefones (
  idtelefone integer,
  idagente integer,
  tipotelefone integer,
  uf integer,
  ddd integer,
  numero character varying(12),
  divulgar boolean,
  usuario integer
);


ALTER TABLE agentes.telefones OWNER TO postgres;

CREATE TABLE agentes.uf (
  iduf integer,
  sigla character varying(2),
  descricao character varying(100),
  regiao character varying(20)
);

ALTER TABLE agentes.uf OWNER TO postgres;

CREATE TABLE sac.verificacao (
  idverificacao integer,
  idtipo integer,
  descricao character varying(100),
  stestado boolean
);

ALTER TABLE sac.verificacao OWNER TO postgres;

CREATE TABLE agentes.visao (
  idvisao integer,
  idagente integer,
  visao integer,
  usuario integer,
  stativo character varying(1)
);


ALTER TABLE agentes.visao OWNER TO postgres;