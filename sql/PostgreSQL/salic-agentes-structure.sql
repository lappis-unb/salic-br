-- noinspection SqlDialectInspectionForFile
-- CREATE SCHEMA agentes;
-- DROP SCHEMA agentes CASCADE;
-- COMMIT;
-- ROLLBACK;
-- BEGIN;

CREATE SCHEMA IF NOT EXISTS agentes AUTHORIZATION postgres;


CREATE TABLE agentes.tbTipoAusencia
(
  idTipoAusencia INT PRIMARY KEY NOT NULL ,
  nmAusencia VARCHAR(20) NOT NULL
);
CREATE TABLE agentes.tbTipoEscolaridade
(
  idTipoEscolaridade INT PRIMARY KEY NOT NULL ,
  nmEscolaridade VARCHAR(20) NOT NULL
);
CREATE TABLE agentes.UF
(
  idUF INT PRIMARY KEY NOT NULL,
  Sigla CHAR(2) NOT NULL,
  Descricao VARCHAR(100) NOT NULL,
  Regiao VARCHAR(20) NOT NULL
);
CREATE SEQUENCE agentes.uf_iduf_seq NO MINVALUE NO MAXVALUE NO CYCLE;
ALTER TABLE agentes.uf ALTER COLUMN iduf SET DEFAULT nextval('agentes.uf_iduf_seq');
ALTER SEQUENCE agentes.uf_iduf_seq OWNED BY agentes.uf.iduf;
CREATE INDEX IX_UF ON agentes.UF (Sigla);
CREATE INDEX IX_UF_1 ON agentes.UF (Descricao);
CREATE INDEX IX_UF_2 ON agentes.UF (Regiao);
CREATE TABLE agentes.Tipo
(
  idTipo INT PRIMARY KEY NOT NULL ,
  Descricao VARCHAR(100) NOT NULL
);
CREATE SEQUENCE agentes.tipo_idtipo_seq NO MINVALUE NO MAXVALUE NO CYCLE;
ALTER TABLE agentes.tipo ALTER COLUMN idtipo SET DEFAULT nextval('agentes.tipo_idtipo_seq');
ALTER SEQUENCE agentes.tipo_idtipo_seq OWNED BY agentes.tipo.idtipo;
CREATE TABLE agentes.Pais
(
  idPais INT PRIMARY KEY NOT NULL,
  Sigla VARCHAR(10) DEFAULT ' ',
  Descricao VARCHAR(100) NOT NULL,
  Continente VARCHAR(20) DEFAULT ' '
);
CREATE TABLE agentes.Prefeituras
(
  CNPJ VARCHAR(14),
  Municipio VARCHAR(255),
  Endereço VARCHAR(255),
  Número VARCHAR(20),
  Complemento VARCHAR(255),
  Bairro VARCHAR(255),
  CEP CHAR(8),
  Uf VARCHAR(50),
  Site VARCHAR(255),
  EnderecoSite VARCHAR(255),
  Telefone VARCHAR(20)
);
CREATE TABLE agentes.PrefeiturasReceita
(
  Municipio VARCHAR(255),
  UF VARCHAR(255),
  CNPJ VARCHAR(14)
);
CREATE TABLE agentes.Agentes
(
  idAgente INT PRIMARY KEY NOT NULL ,
  CNPJCPF VARCHAR(14) DEFAULT '00000000000000' NOT NULL,
  CNPJCPFSuperior VARCHAR(14) DEFAULT '00000000000000',
  TipoPessoa INTEGER DEFAULT 0,
  DtCadastro TIMESTAMP,
  DtAtualizacao TIMESTAMP,
  DtValidade TIMESTAMP,
  Status SMALLINT DEFAULT 0 NOT NULL,
  Usuario INT DEFAULT 0 NOT NULL
);
CREATE SEQUENCE agentes.agentes_idagente_seq NO MINVALUE NO MAXVALUE NO CYCLE;
ALTER TABLE agentes.agentes ALTER COLUMN idagente SET DEFAULT nextval('agentes.agentes_idagente_seq');
ALTER SEQUENCE agentes.agentes_idagente_seq OWNED BY agentes.agentes.idagente;
CREATE INDEX _dta_index_Agentes_9_1977058079__K2_K1 ON agentes.Agentes (CNPJCPF, idAgente);
CREATE INDEX _dta_index_Agentes_9_1977058079__K2_K1_3_4_5_6_7_8_9 ON agentes.Agentes (CNPJCPF, idAgente, CNPJCPFSuperior, TipoPessoa, DtCadastro, DtAtualizacao, DtValidade, Status, Usuario);
CREATE TABLE agentes.Verificacao
(
  idVerificacao INT PRIMARY KEY NOT NULL ,
  IdTipo INT NOT NULL,
  Descricao VARCHAR(100) NOT NULL,
  Sistema INT DEFAULT 0 NOT NULL,
  CONSTRAINT FK_Verificacao_Tipo FOREIGN KEY (IdTipo) REFERENCES agentes.Tipo (idTipo)
);
CREATE SEQUENCE agentes.verificacao_idverificacao_seq NO MINVALUE NO MAXVALUE NO CYCLE;
ALTER TABLE agentes.verificacao ALTER COLUMN idverificacao SET DEFAULT nextval('agentes.verificacao_idverificacao_seq');
ALTER SEQUENCE agentes.verificacao_idverificacao_seq OWNED BY agentes.verificacao.idverificacao;
CREATE INDEX IX_Verificacao ON agentes.Verificacao (IdTipo);
CREATE INDEX IX_Verificacao_1 ON agentes.Verificacao (Descricao);
CREATE TABLE agentes.Telefones
(
  idTelefone INT PRIMARY KEY NOT NULL ,
  idAgente INT NOT NULL,
  TipoTelefone INT NOT NULL,
  UF INT NOT NULL,
  DDD INT NOT NULL,
  Numero VARCHAR(12) NOT NULL,
  Divulgar INT DEFAULT 0 NOT NULL,
  Usuario INT DEFAULT 0 NOT NULL,
  CONSTRAINT FK_Telefones_Agentes FOREIGN KEY (idAgente) REFERENCES agentes.Agentes (idAgente),
  CONSTRAINT FK_Telefones_Verificacao FOREIGN KEY (TipoTelefone) REFERENCES agentes.Verificacao (idVerificacao),
  CONSTRAINT FK_Telefones_UF FOREIGN KEY (UF) REFERENCES agentes.UF (idUF)
);
CREATE SEQUENCE agentes.telefones_idtelefone_seq NO MINVALUE NO MAXVALUE NO CYCLE;
ALTER TABLE agentes.telefones ALTER COLUMN idtelefone SET DEFAULT nextval('agentes.telefones_idtelefone_seq');
ALTER SEQUENCE agentes.telefones_idtelefone_seq OWNED BY agentes.telefones.idtelefone;
CREATE TABLE agentes.Sistema
(
  idSistema INT PRIMARY KEY NOT NULL,
  Descricao VARCHAR(100) NOT NULL,
  Usuario INT DEFAULT 0 NOT NULL
);
CREATE TABLE agentes.sysdiagrams
(
  name TEXT NOT NULL,
  principal_id INT NOT NULL,
  diagram_id INT PRIMARY KEY NOT NULL ,
  version INT,
  definition VARBIT
);
CREATE UNIQUE INDEX UK_principal_name ON agentes.sysdiagrams (principal_id, name);
CREATE TABLE agentes.bancos
(
  Codigo CHAR(3) PRIMARY KEY NOT NULL,
  CNPJ VARCHAR(255),
  Sufixo VARCHAR(255),
  DV VARCHAR(255),
  Descricao VARCHAR(255)
);

CREATE TABLE agentes.CheckListDocumentosSistemas
(
  idCheckListDocumentosSistema INT PRIMARY KEY NOT NULL ,
  idCheckListDocumento INT NOT NULL,
  idSistema INT NOT NULL,
  CONSTRAINT FK_CheckListDocumentosSistemas_CheckListDocumentos FOREIGN KEY (idCheckListDocumento) REFERENCES agentes.Verificacao (idVerificacao),
  CONSTRAINT FK_CheckListDocumentosSistemas_Sistema FOREIGN KEY (idSistema) REFERENCES agentes.Sistema (idSistema)
);
CREATE TABLE agentes.ContaCorrente
(
  idContaCorrente INT PRIMARY KEY NOT NULL ,
  idAgente INT NOT NULL,
  Banco CHAR(3) NOT NULL,
  Agencia VARCHAR(6) NOT NULL,
  ContaCorrente VARCHAR(15) NOT NULL,
  Usuario INT NOT NULL,
  CONSTRAINT FK_ContaCorrente_Agentes FOREIGN KEY (idAgente) REFERENCES agentes.Agentes (idAgente)
);
CREATE INDEX IX_ContaCorrente ON agentes.ContaCorrente (idAgente);
CREATE INDEX IX_ContaCorrente_2 ON agentes.ContaCorrente (Banco, Agencia, ContaCorrente);
CREATE TABLE agentes.DDD
(
  idDDD INT PRIMARY KEY NOT NULL ,
  idUF INT NOT NULL,
  Codigo VARCHAR(3) NOT NULL,
  CONSTRAINT FK_DDD_UF FOREIGN KEY (idUF) REFERENCES agentes.UF (idUF)
);
CREATE SEQUENCE agentes.ddd_idddd_seq NO MINVALUE NO MAXVALUE NO CYCLE;
ALTER TABLE agentes.ddd ALTER COLUMN idddd SET DEFAULT nextval('agentes.ddd_idddd_seq');
ALTER SEQUENCE agentes.ddd_idddd_seq OWNED BY agentes.ddd.idddd;
CREATE INDEX IX_DDD ON agentes.DDD (idUF);
CREATE TABLE agentes.Documentos
(
  idDocumentos INT PRIMARY KEY NOT NULL ,
  idAgente INT NOT NULL,
  TipoDocumento INT NOT NULL,
  Numero VARCHAR(20) NOT NULL,
  DtEmissao TIMESTAMP,
  OrgaoExpedidor VARCHAR(20) DEFAULT ' ' NOT NULL,
  Complemento VARCHAR(20) DEFAULT ' ' NOT NULL,
  Usuario INT DEFAULT 0 NOT NULL,
  CONSTRAINT FK_Documentos_Agentes FOREIGN KEY (idAgente) REFERENCES agentes.Agentes (idAgente),
  CONSTRAINT FK_Documentos_Verificacao1 FOREIGN KEY (TipoDocumento) REFERENCES agentes.Verificacao (idVerificacao)
);
CREATE TABLE agentes.dtproperties
(
  id INT NOT NULL ,
  objectid INT,
  property VARCHAR(64) NOT NULL,
  value VARCHAR(255),
  uvalue VARCHAR(255),
  lvalue BYTEA,
  version INT DEFAULT 0 NOT NULL,
  CONSTRAINT pk_dtproperties PRIMARY KEY (id, property)
);
CREATE TABLE agentes.EnderecoInternacional
(
  idEnderecoInternacional INT PRIMARY KEY NOT NULL ,
  idAgente INT NOT NULL,
  idPais INT NOT NULL,
  Logradouro VARCHAR(100) DEFAULT ' ' NOT NULL,
  Estado VARCHAR(100) DEFAULT ' ' NOT NULL,
  Cidade VARCHAR(100) DEFAULT ' ' NOT NULL,
  Cep VARCHAR(20) DEFAULT ' ' NOT NULL,
  Telefones VARCHAR(50) DEFAULT ' ' NOT NULL,
  Usuario INT DEFAULT 0 NOT NULL,
  CONSTRAINT FK_EnderecoInternacional_Agentes FOREIGN KEY (idAgente) REFERENCES agentes.Agentes (idAgente),
  CONSTRAINT FK_Internacional_Pais FOREIGN KEY (idPais) REFERENCES agentes.Pais (idPais)
);
CREATE TABLE agentes.EnderecoNacional
(
  idEndereco INT PRIMARY KEY NOT NULL ,
  idAgente INT NOT NULL,
  TipoEndereco INT NOT NULL,
  TipoLogradouro INT NOT NULL,
  Logradouro VARCHAR(100) NOT NULL,
  Numero VARCHAR(15) DEFAULT 'S/N',
  Bairro VARCHAR(100) DEFAULT ' ',
  Complemento VARCHAR(100) DEFAULT ' ',
  Cidade VARCHAR(6) DEFAULT ' ',
  UF INT DEFAULT 0,
  Cep CHAR(8) NOT NULL,
  Municipio VARCHAR(100),
  UfDescricao VARCHAR(50),
  Status INT DEFAULT 0 NOT NULL,
  Divulgar INT DEFAULT 0 NOT NULL,
  Usuario INT DEFAULT 0 NOT NULL,
  CONSTRAINT FK_EnderecoNacional_Agentes FOREIGN KEY (idAgente) REFERENCES agentes.Agentes (idAgente),
  CONSTRAINT FK_EnderecoNacional_Verificacao1 FOREIGN KEY (TipoEndereco) REFERENCES agentes.Verificacao (idVerificacao),
  CONSTRAINT FK_EnderecoNacional_Verificacao2 FOREIGN KEY (TipoLogradouro) REFERENCES agentes.Verificacao (idVerificacao)
);
CREATE SEQUENCE agentes.endereconacional_idendereco_seq NO MINVALUE NO MAXVALUE NO CYCLE;
ALTER TABLE agentes.endereconacional ALTER COLUMN idendereco SET DEFAULT nextval('agentes.endereconacional_idendereco_seq');
ALTER SEQUENCE agentes.endereconacional_idendereco_seq OWNED BY agentes.endereconacional.idendereco;
CREATE INDEX IX_EnderecoNacional ON agentes.EnderecoNacional (idEndereco);
CREATE INDEX IX_EnderecoNacional_1 ON agentes.EnderecoNacional (Cidade);
CREATE INDEX IX_EnderecoNacional_2 ON agentes.EnderecoNacional (UF);
CREATE INDEX IX_EnderecoNacional_idAgente ON agentes.EnderecoNacional (idAgente);
CREATE TABLE agentes.HistoricoAgente
(
  idAgente INT PRIMARY KEY NOT NULL,
  Historico VARCHAR(8000) DEFAULT ' ' NOT NULL,
  Usuario INT DEFAULT 0 NOT NULL,
  CONSTRAINT FK_HistoricoAgente_Agentes FOREIGN KEY (idAgente) REFERENCES agentes.Agentes (idAgente)
);
CREATE TABLE agentes.Internet
(
  idInternet INT PRIMARY KEY NOT NULL ,
  idAgente INT NOT NULL,
  TipoInternet INT NOT NULL,
  Descricao VARCHAR(100) NOT NULL,
  Status INT DEFAULT 1 NOT NULL,
  Divulgar INT DEFAULT 0 NOT NULL,
  Usuario INT DEFAULT 0 NOT NULL,
  CONSTRAINT FK_Internet_Agentes FOREIGN KEY (idAgente) REFERENCES agentes.Agentes (idAgente),
  CONSTRAINT FK_Internet_Verificacao FOREIGN KEY (TipoInternet) REFERENCES agentes.Verificacao (idVerificacao)
);
CREATE SEQUENCE agentes.internet_idinternet_seq NO MINVALUE NO MAXVALUE NO CYCLE;
ALTER TABLE agentes.internet ALTER COLUMN idinternet SET DEFAULT nextval('agentes.internet_idinternet_seq');
ALTER SEQUENCE agentes.internet_idinternet_seq OWNED BY agentes.internet.idinternet;
CREATE TABLE agentes.MesoRegiao
(
  idMeso CHAR(4) PRIMARY KEY NOT NULL,
  descricao VARCHAR(100) NOT NULL
);
CREATE TABLE agentes.MicroRegiao
(
  idMicro CHAR(5) PRIMARY KEY NOT NULL,
  descricao VARCHAR(100) NOT NULL
);
CREATE TABLE agentes.Municipios
(
  idMunicipioIBGE VARCHAR(6) PRIMARY KEY NOT NULL,
  idUFIBGE INT NOT NULL,
  IdMeso CHAR(4) NOT NULL,
  idMicro CHAR(5) NOT NULL,
  Descricao VARCHAR(100) NOT NULL,
  CONSTRAINT FK_Municipios_UF FOREIGN KEY (idUFIBGE) REFERENCES agentes.UF (idUF),
  CONSTRAINT FK_Municipios_MesoRegiao FOREIGN KEY (IdMeso) REFERENCES agentes.MesoRegiao (idMeso),
  CONSTRAINT FK_Municipios_MicroRegiao FOREIGN KEY (idMicro) REFERENCES agentes.MicroRegiao (idMicro)
);
CREATE INDEX IX_Municipios_idUFIBGE ON agentes.Municipios (idUFIBGE);
CREATE INDEX IX_Municipios_Cidade ON agentes.Municipios (Descricao);
CREATE TABLE agentes.Natureza
(
  idNatureza INT PRIMARY KEY NOT NULL ,
  idAgente INT NOT NULL,
  Direito SMALLINT NOT NULL,
  Esfera SMALLINT NOT NULL,
  Poder SMALLINT DEFAULT 0 NOT NULL,
  Administracao SMALLINT DEFAULT 0 NOT NULL,
  Usuario INT DEFAULT 0 NOT NULL,
  CONSTRAINT FK_Natureza_Agentes FOREIGN KEY (idAgente) REFERENCES agentes.Agentes (idAgente)
);
CREATE INDEX IX_Natureza ON agentes.Natureza (idAgente);
CREATE TABLE agentes.Nomes
(
  idNome INT PRIMARY KEY NOT NULL ,
  idAgente INT NOT NULL,
  TipoNome INT NOT NULL,
  Descricao VARCHAR(150) NOT NULL,
  Status INT DEFAULT 0 NOT NULL,
  Usuario INT DEFAULT 0 NOT NULL,
  CONSTRAINT FK_Nomes_Agentes FOREIGN KEY (idAgente) REFERENCES agentes.Agentes (idAgente),
  CONSTRAINT FK_Nomes_Verificacao FOREIGN KEY (TipoNome) REFERENCES agentes.Verificacao (idVerificacao)
);
CREATE SEQUENCE agentes.nomes_idnome_seq NO MINVALUE NO MAXVALUE NO CYCLE;
ALTER TABLE agentes.nomes ALTER COLUMN idnome SET DEFAULT nextval('agentes.nomes_idnome_seq');
ALTER SEQUENCE agentes.nomes_idnome_seq OWNED BY agentes.nomes.idnome;
CREATE INDEX _dta_index_Nomes_9_5575058__K5_K2_K4 ON agentes.Nomes (Status, idAgente, Descricao);
CREATE INDEX _dta_index_Nomes_9_5575058__K2_K4 ON agentes.Nomes (idAgente, Descricao);
CREATE TABLE agentes.Ocupacao
(
  idOcupacao INT PRIMARY KEY NOT NULL ,
  idAgente INT NOT NULL,
  Ocupacao VARCHAR(6) NOT NULL,
  Atividade INT NOT NULL,
  DtInicio TIMESTAMP,
  DtFim TIMESTAMP,
  Status INT NOT NULL,
  Usuario INT DEFAULT 0 NOT NULL,
  CONSTRAINT FK_Ocupacao_Agentes FOREIGN KEY (idAgente) REFERENCES agentes.Agentes (idAgente)
);
CREATE TABLE agentes.Perfil
(
  idPerfil INT PRIMARY KEY NOT NULL ,
  idAgente INT NOT NULL,
  Perfil INT NOT NULL,
  Caracteristica INT DEFAULT 0 NOT NULL,
  Usuario INT DEFAULT 0 NOT NULL,
  CONSTRAINT FK_Perfil_Agentes FOREIGN KEY (idAgente) REFERENCES agentes.Agentes (idAgente),
  CONSTRAINT FK_Perfil_Verificacao FOREIGN KEY (Perfil) REFERENCES agentes.Verificacao (idVerificacao),
  CONSTRAINT FK_Perfil_Verificacao1 FOREIGN KEY (Caracteristica) REFERENCES agentes.Verificacao (idVerificacao)
);
CREATE INDEX IX_Perfil ON agentes.Perfil (idAgente);
CREATE TABLE agentes.PopulacaoMunicipio
(
  IdMunicipio VARCHAR(6) PRIMARY KEY NOT NULL,
  idMunicipio7 VARCHAR(7) NOT NULL,
  Populacao INT NOT NULL
);
CREATE TABLE agentes.Ramais
(
  idRamais INT PRIMARY KEY NOT NULL ,
  idTelefone INT NOT NULL,
  Numero VARCHAR(4) NOT NULL,
  Usuario INT DEFAULT 0 NOT NULL,
  CONSTRAINT FK_Ramais_Telefones FOREIGN KEY (idTelefone) REFERENCES agentes.Telefones (idTelefone)
);

CREATE TABLE agentes.tbAgenteFisico
(
  idAgente INT PRIMARY KEY NOT NULL,
  stSexo CHAR DEFAULT 'M' NOT NULL,
  stEstadoCivil CHAR(2) DEFAULT 'OU' NOT NULL,
  stNecessidadeEspecial CHAR DEFAULT 'N' NOT NULL,
  nmMae CHAR(100) DEFAULT 'Não Informado' NOT NULL,
  nmPai CHAR(100) DEFAULT 'Não Informado' NOT NULL,
  dtNascimento TIMESTAMP NOT NULL,
  stCorRaca CHAR,
  nrIdentificadorProcessual CHAR(17),
  CONSTRAINT FK_tbAgenteFisico_Agentes FOREIGN KEY (idAgente) REFERENCES agentes.Agentes (idAgente)
);
CREATE TABLE agentes.tbAgentesxVerificacao
(
  idAgentexVerificacao INT PRIMARY KEY NOT NULL ,
  idVerificacao INT NOT NULL,
  dsNumeroDocumento VARCHAR(12) NOT NULL,
  dtInicioMandato DATE NOT NULL,
  dtFimMandato DATE NOT NULL,
  stMandato INT NOT NULL,
  idDirigente INT NOT NULL,
  idEmpresa INT NOT NULL,
  idArquivo INT NOT NULL,
  CONSTRAINT fktbAgentesxVerificacao01 FOREIGN KEY (idVerificacao) REFERENCES agentes.Verificacao (idVerificacao),
  CONSTRAINT fktbAgentesxVerificacao03 FOREIGN KEY (idDirigente) REFERENCES agentes.Agentes (idAgente),
  CONSTRAINT fktbAgentesxVerificacao02 FOREIGN KEY (idEmpresa) REFERENCES agentes.Agentes (idAgente)
);
CREATE TABLE agentes.tbAgenteXPais
(
  idAgente INT NOT NULL,
  idPais INT NOT NULL,
  dtChegada TIMESTAMP NOT NULL,
  dtNaturalizacao TIMESTAMP,
  CONSTRAINT PK_tbAgenteXPais PRIMARY KEY (idAgente, idPais),
  CONSTRAINT FK_tbAgenteXPais_Agentes FOREIGN KEY (idAgente) REFERENCES agentes.Agentes (idAgente),
  CONSTRAINT FK_tbAgenteXPais_Pais FOREIGN KEY (idPais) REFERENCES agentes.Pais (idPais)
);
CREATE TABLE agentes.tbAusencia
(
  idAusencia INT PRIMARY KEY NOT NULL ,
  idTipoAusencia INT NOT NULL,
  idAgente INT NOT NULL,
  dtInicioAusencia TIMESTAMP NOT NULL,
  dtFimAusencia TIMESTAMP NOT NULL,
  idDocumento INT,
  dsJustificativaAusencia VARCHAR(300) NOT NULL,
  stImpacto CHAR NOT NULL,
  siAusencia CHAR,
  idAlteracao INT,
  dtCadastroAusencia TIMESTAMP NOT NULL,
  CONSTRAINT fk_tbAusencia_tbTipoAusencia FOREIGN KEY (idTipoAusencia) REFERENCES agentes.tbTipoAusencia (idTipoAusencia),
  CONSTRAINT fk_tbAusencia_Agentes FOREIGN KEY (idAgente) REFERENCES agentes.Agentes (idAgente),
  CONSTRAINT fk_tbAusencia FOREIGN KEY (idAlteracao) REFERENCES agentes.tbAusencia (idAusencia)
);
CREATE TABLE agentes.tbComprovantePagamento
(
  idComprovantePagamento INT PRIMARY KEY NOT NULL ,
  idDocumento INT NOT NULL,
  nrOrdemPagamento CHAR(12) NOT NULL,
  dtPagamento TIMESTAMP
);
CREATE TABLE agentes.tbCredenciamentoParecerista
(
  idCredenciamentoParecerista INT PRIMARY KEY NOT NULL ,
  idCodigoArea INT NOT NULL,
  idCodigoSegmento CHAR(2) NOT NULL,
  siCredenciamento CHAR NOT NULL,
  idAgente INT NOT NULL,
  qtPonto SMALLINT,
  idVerificacao INT NOT NULL,
  CONSTRAINT fk_tbCredenciamentoParecerista_Agentes FOREIGN KEY (idAgente) REFERENCES agentes.Agentes (idAgente),
  CONSTRAINT fk_tbCredenciamentoParecerista_01 FOREIGN KEY (idVerificacao) REFERENCES agentes.Verificacao (idVerificacao)
);
CREATE TABLE agentes.tbDistrito
(
  idMunicipioIBGE VARCHAR(6) NOT NULL,
  idDistrito SMALLINT NOT NULL,
  nmDistrito VARCHAR(100) NOT NULL,
  CONSTRAINT PK_tbDistrito PRIMARY KEY (idMunicipioIBGE, idDistrito),
  CONSTRAINT FK_tbDistrito_Municipios FOREIGN KEY (idMunicipioIBGE) REFERENCES agentes.Municipios (idMunicipioIBGE)
);
CREATE TABLE agentes.tbEscolaridade
(
  idEscolaridade INT PRIMARY KEY NOT NULL ,
  idAgente INT NOT NULL,
  idTipoEscolaridade INT NOT NULL,
  idDocumento INT,
  nmCurso VARCHAR(20) NOT NULL,
  nmInstituicao VARCHAR(20) NOT NULL,
  dtInicioCurso TIMESTAMP NOT NULL,
  dtFimCurso TIMESTAMP NOT NULL,
  idPais INT NOT NULL,
  CONSTRAINT fk_tbEscolaridade_Agentes FOREIGN KEY (idAgente) REFERENCES agentes.Agentes (idAgente),
  CONSTRAINT fk_tbEscolaridade_tbTipoEscolaridade FOREIGN KEY (idTipoEscolaridade) REFERENCES agentes.tbTipoEscolaridade (idTipoEscolaridade),
  CONSTRAINT fk_tbEscolaridade_Pais FOREIGN KEY (idPais) REFERENCES agentes.Pais (idPais)
);
CREATE TABLE agentes.tbInformacaoProfissional
(
  idInformacaoProfissional INT PRIMARY KEY NOT NULL ,
  idAgente INT NOT NULL,
  nmProfissao VARCHAR(80) NOT NULL,
  nmCargo VARCHAR(50) NOT NULL,
  dsEndereco VARCHAR(200) NOT NULL,
  dtInicioVinculo TIMESTAMP NOT NULL,
  dtFimVinculo TIMESTAMP,
  idDocumento INT,
  siInformacao CHAR NOT NULL,
  CONSTRAINT fk_tbInformacaoProfissional_Agentes FOREIGN KEY (idAgente) REFERENCES agentes.Agentes (idAgente)
);
CREATE TABLE agentes.tbNecessiadeEspecial
(
  idAgente INT NOT NULL,
  idNecessidadeEspecial INT NOT NULL ,
  nmNecessidadeEspecial VARCHAR(100) NOT NULL,
  CONSTRAINT PK_tbNecessiadeEspecial PRIMARY KEY (idAgente, idNecessidadeEspecial),
  CONSTRAINT FK_tbNecessiadeEspecial_tbAgentesFisico FOREIGN KEY (idAgente) REFERENCES agentes.tbAgenteFisico (idAgente)
);
CREATE TABLE agentes.tbPagamentoParecerista
(
  idPagamentoParecerista INT PRIMARY KEY NOT NULL ,
  idProduto INT NOT NULL,
  vlPagamento NUMERIC(10,2) NOT NULL,
  siPagamento CHAR NOT NULL,
  idAgente INT NOT NULL,
  idComprovantePagamento INT NOT NULL,
  CONSTRAINT fk_tbPagamentoParecerista_Agentes FOREIGN KEY (idAgente) REFERENCES agentes.Agentes (idAgente),
  CONSTRAINT fk_tbPagamentoParecerista_tbComprovantePagamento FOREIGN KEY (idComprovantePagamento) REFERENCES agentes.tbComprovantePagamento (idComprovantePagamento)
);
CREATE TABLE agentes.tbProcuracao
(
  idProcuracao INT PRIMARY KEY NOT NULL ,
  idAgente INT NOT NULL,
  idDocumento INT NOT NULL,
  dtProcuracao TIMESTAMP NOT NULL,
  siProcuracao CHAR DEFAULT 0 NOT NULL,
  dsJustificativa VARCHAR(300) NOT NULL,
  dsObservacao VARCHAR(300),
  idSolicitante INT NOT NULL,
  CONSTRAINT FK_tbProcuracao_tbProcuracao FOREIGN KEY (idAgente) REFERENCES agentes.Agentes (idAgente)
);
CREATE TABLE agentes.tbProcuradorProjeto
(
  idProcuradorProjeto INT PRIMARY KEY NOT NULL ,
  idProcuracao INT NOT NULL,
  idPronac INT NOT NULL,
  dtVinculacao TIMESTAMP,
  dtDesvinculacao TIMESTAMP,
  siEstado CHAR NOT NULL,
  CONSTRAINT FK_tbProcuradorProjeto_tbProcuracao FOREIGN KEY (idProcuracao) REFERENCES agentes.tbProcuracao (idProcuracao)
);
CREATE TABLE agentes.tbSubdistrito
(
  idMunicipioIBGE VARCHAR(6) NOT NULL,
  idDistrito SMALLINT NOT NULL,
  cdSubdistritoIbge SMALLINT NOT NULL,
  nmSubdistrito VARCHAR(100) NOT NULL,
  CONSTRAINT PK_tbSubdistrito PRIMARY KEY (idMunicipioIBGE, idDistrito, cdSubdistritoIbge),
  CONSTRAINT FK_tbSubdistrito_tbDistrito FOREIGN KEY (idMunicipioIBGE, idDistrito) REFERENCES agentes.tbDistrito (idMunicipioIBGE, idDistrito)
);
CREATE TABLE agentes.tbTitulacaoConselheiro
(
  idAgente INT PRIMARY KEY NOT NULL,
  cdArea CHAR NOT NULL,
  cdSegmento VARCHAR(4) NOT NULL,
  stTitular INT NOT NULL,
  stConselheiro CHAR DEFAULT 'A' NOT NULL,
  CONSTRAINT fk_Agentes_01 FOREIGN KEY (idAgente) REFERENCES agentes.Agentes (idAgente)
);
CREATE TABLE agentes.tbvinculo
(
  idVinculo INT PRIMARY KEY NOT NULL ,
  idAgenteProponente INT NOT NULL,
  dtVinculo TIMESTAMP NOT NULL,
  siVinculo SMALLINT DEFAULT 0 NOT NULL,
  idUsuarioResponsavel INT NOT NULL,
  CONSTRAINT tbVinculo_Agentes_02 FOREIGN KEY (idAgenteProponente) REFERENCES agentes.Agentes (idAgente)
);
CREATE SEQUENCE agentes.tbvinculo_idvinculo_seq NO MINVALUE NO MAXVALUE NO CYCLE;
ALTER TABLE agentes.tbvinculo ALTER COLUMN idvinculo SET DEFAULT nextval('agentes.tbvinculo_idvinculo_seq');
ALTER SEQUENCE agentes.tbvinculo_idvinculo_seq OWNED BY agentes.tbvinculo.idvinculo;
CREATE TABLE agentes.tbVinculoProposta
(
  idVinculoProposta INT PRIMARY KEY NOT NULL ,
  idVinculo INT NOT NULL,
  idPreProjeto INT NOT NULL,
  siVinculoProposta CHAR NOT NULL,
  CONSTRAINT FK_tbVinculoProposta_tbVinculo FOREIGN KEY (idVinculo) REFERENCES agentes.tbVinculo (idVinculo)
);
CREATE INDEX IX_tbVinculoProposta ON agentes.tbVinculoProposta (idVinculoProposta);
CREATE TABLE agentes.TCU
(
  UF VARCHAR(255),
  Codigo_UF FLOAT,
  Codigo_Munic VARCHAR(255),
  Municipio VARCHAR(255),
  Populacao FLOAT
);
CREATE TABLE agentes.Vinculacao
(
  idVinculacao INT PRIMARY KEY NOT NULL ,
  idAgente INT NOT NULL,
  idVinculado INT NOT NULL,
  idVinculoPrincipal INT DEFAULT 0 NOT NULL,
  Usuario INT DEFAULT 0 NOT NULL,
  CONSTRAINT FK_Vinculacao_Agentes FOREIGN KEY (idAgente) REFERENCES agentes.Agentes (idAgente),
  CONSTRAINT FK_Vinculacao_Agentes1 FOREIGN KEY (idVinculado) REFERENCES agentes.Agentes (idAgente)
);
CREATE TABLE agentes.Visao
(
  idVisao INT PRIMARY KEY NOT NULL ,
  idAgente INT NOT NULL,
  Visao INT NOT NULL,
  Usuario INT NOT NULL,
  stAtivo CHAR DEFAULT 'A' NOT NULL,
  CONSTRAINT FK_Visao_Agentes FOREIGN KEY (idAgente) REFERENCES agentes.Agentes (idAgente),
  CONSTRAINT FK_Visao_Verificacao FOREIGN KEY (Visao) REFERENCES agentes.Verificacao (idVerificacao)
);
CREATE SEQUENCE agentes.visao_idvisao_seq NO MINVALUE NO MAXVALUE NO CYCLE;
ALTER TABLE agentes.visao ALTER COLUMN idvisao SET DEFAULT nextval('agentes.visao_idvisao_seq');
ALTER SEQUENCE agentes.visao_idvisao_seq OWNED BY agentes.visao.idvisao;
CREATE INDEX IX_Visao ON agentes.Visao (idAgente);
CREATE INDEX IX_Visao_1 ON agentes.Visao (Visao);
CREATE TABLE agentes.vAgentes
(
  idAgente INT NOT NULL,
  CNPJCPF VARCHAR(14) NOT NULL,
  CNPJCPFSuperior VARCHAR(14),
  TipoPessoa BIT,
  DtCadastro TIMESTAMP,
  DtAtualizacao TIMESTAMP,
  DtValidade TIMESTAMP,
  Status SMALLINT NOT NULL,
  Usuario INT NOT NULL
);
CREATE TABLE agentes.vCheckListDocumentos
(
  idCheckListDocumentos INT NOT NULL,
  idSistema INT NOT NULL,
  idDocumento INT NOT NULL,
  idAgente INT NOT NULL,
  Apresentou INT NOT NULL,
  Usuario INT NOT NULL
);
CREATE TABLE agentes.vConselhosRegiaoUF
(
  Regiao VARCHAR(20) NOT NULL,
  UF VARCHAR(100) NOT NULL,
  Perfil VARCHAR(100) NOT NULL,
  Conselho VARCHAR(100)
);
CREATE TABLE agentes.vContaCorrente
(
  idContaCorrente INT NOT NULL,
  idAgente INT NOT NULL,
  Banco CHAR(3) NOT NULL,
  Agencia VARCHAR(6) NOT NULL,
  ContaCorrente VARCHAR(15) NOT NULL,
  Usuario INT NOT NULL
);
CREATE TABLE agentes.vDDD
(
  idDDD INT NOT NULL,
  idUF INT NOT NULL,
  Codigo VARCHAR(3) NOT NULL
);
CREATE TABLE agentes.vDocumentos
(
  idDocumentos INT NOT NULL,
  idAgente INT NOT NULL,
  TipoDocumento INT NOT NULL,
  Numero VARCHAR(20) NOT NULL,
  DtEmissao TIMESTAMP,
  OrgaoExpedidor VARCHAR(20) NOT NULL,
  Complemento VARCHAR(20) NOT NULL,
  Usuario INT NOT NULL
);
CREATE TABLE agentes.vFolhaGrio
(
  idagente INT NOT NULL,
  CPF_Grio VARCHAR(14) NOT NULL,
  NomeGrio VARCHAR(200),
  Agencia VARCHAR(8000),
  ContaCorr VARCHAR(15) NOT NULL,
  ContaCorrente VARCHAR(13),
  TipoConta VARCHAR(13) NOT NULL
);
CREATE TABLE agentes.vHistoricoAgente
(
  idAgente INT NOT NULL,
  Historico VARCHAR(8000) NOT NULL,
  Usuario INT NOT NULL
);
CREATE TABLE agentes.vInternet
(
  idInternet INT NOT NULL,
  idAgente INT NOT NULL,
  TipoInternet INT NOT NULL,
  Descricao VARCHAR(100) NOT NULL,
  Status INT NOT NULL,
  Divulgar INT NOT NULL,
  Usuario INT NOT NULL
);
CREATE TABLE agentes.vMunicipios
(
  idMunicipioIBGE VARCHAR(6) NOT NULL,
  idUFIBGE INT NOT NULL,
  IdMeso CHAR(4) NOT NULL,
  idMicro CHAR(5) NOT NULL,
  Descricao VARCHAR(100) NOT NULL
);
CREATE TABLE agentes.vNacional
(
  idEndereco INT NOT NULL,
  idAgente INT NOT NULL,
  TipoEndereco INT NOT NULL,
  TipoLogradouro INT NOT NULL,
  Logradouro VARCHAR(100) NOT NULL,
  Numero VARCHAR(15),
  Bairro VARCHAR(100),
  Complemento VARCHAR(100),
  Cidade VARCHAR(6),
  UF INT,
  Cep CHAR(8) NOT NULL,
  Status INT NOT NULL,
  Divulgar INT NOT NULL,
  Usuario INT NOT NULL
);
CREATE TABLE agentes.vNatureza
(
  idNatureza INT NOT NULL,
  idAgente INT NOT NULL,
  Direito SMALLINT NOT NULL,
  Esfera SMALLINT NOT NULL,
  Poder SMALLINT NOT NULL,
  Administracao SMALLINT NOT NULL,
  Usuario INT NOT NULL
);
CREATE TABLE agentes.vNomes
(
  idNome INT NOT NULL,
  idAgente INT NOT NULL,
  TipoNome INT NOT NULL,
  Descricao VARCHAR(150) NOT NULL,
  Status INT NOT NULL,
  Usuario INT NOT NULL
);
CREATE TABLE agentes.vOcupacao
(
  idOcupacao INT NOT NULL,
  idAgente INT NOT NULL,
  Ocupacao VARCHAR(6) NOT NULL,
  Atividade INT NOT NULL,
  DtInicio TIMESTAMP,
  DtFim TIMESTAMP,
  Status INT NOT NULL,
  Usuario INT NOT NULL
);
CREATE TABLE agentes.vOcupacaoCBO
(
  idOcupacao INT NOT NULL,
  Codigo VARCHAR(6) NOT NULL,
  Familia VARCHAR(4) NOT NULL,
  TItulo VARCHAR(255) NOT NULL
);
CREATE TABLE agentes.vPais
(
  idPais INT NOT NULL,
  Sigla VARCHAR(10),
  Descricao VARCHAR(100) NOT NULL,
  Continente VARCHAR(20)
);
CREATE TABLE agentes.vPerfil
(
  idAgente INT NOT NULL,
  Perfil INT NOT NULL,
  Caracteristica INT NOT NULL,
  Usuario INT NOT NULL
);
CREATE TABLE agentes.vPrefeiturasRegiaoUF
(
  Regiao VARCHAR(20) NOT NULL,
  UF VARCHAR(100) NOT NULL,
  Perfil VARCHAR(100) NOT NULL,
  CNPJ VARCHAR(14) NOT NULL,
  Prefeitura VARCHAR(100),
  Municipio VARCHAR(100) NOT NULL
);
CREATE TABLE agentes.vPropPontoGrio
(
  Proponente VARCHAR(100),
  CNPJ_Proponenete VARCHAR(14),
  Ponto_Cultura VARCHAR(100),
  CPF_Grio VARCHAR(14) NOT NULL,
  Nome VARCHAR(150) NOT NULL,
  Tipo VARCHAR(100) NOT NULL,
  Endereco VARCHAR(218),
  Cidade VARCHAR(100) NOT NULL,
  UF VARCHAR(100) NOT NULL,
  Cep CHAR(8) NOT NULL,
  Banco CHAR(3) NOT NULL,
  Agência VARCHAR(6) NOT NULL,
  ContaCorrente VARCHAR(13)
);
CREATE TABLE agentes.vRamais
(
  idRamais INT NOT NULL,
  idTelefone INT NOT NULL,
  Numero VARCHAR(4) NOT NULL,
  Usuario INT NOT NULL
);
CREATE TABLE agentes.vSinonimo
(
  idSinonimo INT NOT NULL,
  Ocupacao VARCHAR(6) NOT NULL,
  Titulo VARCHAR(255) NOT NULL
);
CREATE TABLE agentes.vSistema
(
  idSistema INT NOT NULL,
  Descricao VARCHAR(100) NOT NULL,
  Usuario INT NOT NULL
);
CREATE TABLE agentes.vTelefones
(
  idTelefone INT NOT NULL,
  idAgente INT NOT NULL,
  TipoTelefone INT NOT NULL,
  DDD INT NOT NULL,
  Numero VARCHAR(12) NOT NULL,
  UF INT NOT NULL,
  Divulgar INT NOT NULL,
  Usuario INT NOT NULL
);
CREATE TABLE agentes.vUF
(
  idUF INT NOT NULL,
  Sigla CHAR(2) NOT NULL,
  Descricao VARCHAR(100) NOT NULL,
  Regiao VARCHAR(20) NOT NULL
);
CREATE TABLE agentes.vUFMunicipio
(
  idUF INT NOT NULL,
  UF CHAR(2) NOT NULL,
  idMunicipio VARCHAR(6) NOT NULL,
  Municipio VARCHAR(100) NOT NULL
);
CREATE TABLE agentes.vVerificacao
(
  idVerificacao INT NOT NULL,
  IdTipo INT NOT NULL,
  Descricao VARCHAR(100) NOT NULL,
  Sistema INT NOT NULL
);
CREATE TABLE agentes.vVinculacao
(
  idVinculacao INT NOT NULL,
  idAgente INT NOT NULL,
  idVinculado INT NOT NULL,
  idVinculoPrincipal INT NOT NULL,
  Usuario INT NOT NULL
);
CREATE TABLE agentes.vVisao
(
  idVisao INT NOT NULL,
  idAgente INT NOT NULL,
  Visao INT NOT NULL,
  Usuario INT NOT NULL
);
CREATE TABLE agentes.vwCadastrarParecerista
(
  idAgente INT NOT NULL,
  CNPJCPF VARCHAR(14) NOT NULL,
  idNome INT,
  TipoNome INT,
  Nome VARCHAR(150),
  idEndereco INT,
  TipoEndereco INT,
  TipoLogradouro INT,
  Logradouro VARCHAR(100),
  Numero VARCHAR(15),
  Bairro VARCHAR(100),
  Complemento VARCHAR(100),
  Cidade VARCHAR(6),
  UF INT,
  Cep CHAR(8),
  DivulgarEndereco BIT,
  Correspondencia BIT,
  Usuario INT NOT NULL
);


CREATE TABLE agentes.CheckListDocumentos
(
  idCheckListDocumentos INT PRIMARY KEY NOT NULL ,
  idSistema INT DEFAULT 0 NOT NULL,
  idDocumento INT NOT NULL,
  idAgente INT NOT NULL,
  Apresentou INT DEFAULT 0 NOT NULL,
  Usuario INT NOT NULL,
  CONSTRAINT FK_CheckListDocumentos_Sistema FOREIGN KEY (idSistema) REFERENCES agentes.Sistema (idSistema),
  CONSTRAINT FK_CheckListDocumentos_Verificacao FOREIGN KEY (idDocumento) REFERENCES agentes.Verificacao (idVerificacao),
  CONSTRAINT FK_CheckListDocumentos_Agentes FOREIGN KEY (idAgente) REFERENCES agentes.Agentes (idAgente)
);
CREATE INDEX IX_Agente ON agentes.CheckListDocumentos (idAgente);

-- CREATE PROCEDURE dt_addtosourcecontrol(@vchSourceSafeINI VARCHAR, @vchProjectName VARCHAR, @vchComment VARCHAR, @vchLoginName VARCHAR, @vchPassword VARCHAR);
-- CREATE PROCEDURE dt_addtosourcecontrol_u(@vchSourceSafeINI TEXT, @vchProjectName TEXT, @vchComment TEXT, @vchLoginName TEXT, @vchPassword TEXT);
-- CREATE PROCEDURE dt_adduserobject();
-- CREATE PROCEDURE dt_adduserobject_vcs(@vchProperty VARCHAR);
-- CREATE PROCEDURE dt_checkinobject(@chObjectType CHAR, @vchObjectName VARCHAR, @vchComment VARCHAR, @vchLoginName VARCHAR, @vchPassword VARCHAR, @iVCSFlags INT, @iActionFlag INT, @txStream1 TEXT, @txStream2 TEXT, @txStream3 TEXT);
-- CREATE PROCEDURE dt_checkinobject_u(@chObjectType CHAR, @vchObjectName TEXT, @vchComment TEXT, @vchLoginName TEXT, @vchPassword TEXT, @iVCSFlags INT, @iActionFlag INT, @txStream1 TEXT, @txStream2 TEXT, @txStream3 TEXT);
-- CREATE PROCEDURE dt_checkoutobject(@chObjectType CHAR, @vchObjectName VARCHAR, @vchComment VARCHAR, @vchLoginName VARCHAR, @vchPassword VARCHAR, @iVCSFlags INT, @iActionFlag INT);
-- CREATE PROCEDURE dt_checkoutobject_u(@chObjectType CHAR, @vchObjectName TEXT, @vchComment TEXT, @vchLoginName TEXT, @vchPassword TEXT, @iVCSFlags INT, @iActionFlag INT);
-- CREATE PROCEDURE dt_displayoaerror(@iObject INT, @iresult INT);
-- CREATE PROCEDURE dt_displayoaerror_u(@iObject INT, @iresult INT);
-- CREATE PROCEDURE dt_droppropertiesbyid(@id INT, @property VARCHAR);
-- CREATE PROCEDURE dt_dropuserobjectbyid(@id INT);
-- CREATE PROCEDURE dt_generateansiname(@name VARCHAR);
-- CREATE PROCEDURE dt_getobjwithprop(@property VARCHAR, @value VARCHAR);
-- CREATE PROCEDURE dt_getobjwithprop_u(@property VARCHAR, @uvalue TEXT);
-- CREATE PROCEDURE dt_getpropertiesbyid(@id INT, @property VARCHAR);
-- CREATE PROCEDURE dt_getpropertiesbyid_u(@id INT, @property VARCHAR);
-- CREATE PROCEDURE dt_getpropertiesbyid_vcs(@id INT, @property VARCHAR, @value VARCHAR);
-- CREATE PROCEDURE dt_getpropertiesbyid_vcs_u(@id INT, @property VARCHAR, @value TEXT);
-- CREATE PROCEDURE dt_isundersourcecontrol(@vchLoginName VARCHAR, @vchPassword VARCHAR, @iWhoToo INT);
-- CREATE PROCEDURE dt_isundersourcecontrol_u(@vchLoginName TEXT, @vchPassword TEXT, @iWhoToo INT);
-- CREATE PROCEDURE dt_removefromsourcecontrol();
-- CREATE PROCEDURE dt_setpropertybyid(@id INT, @property VARCHAR, @value VARCHAR, @lvalue IMAGE);
-- CREATE PROCEDURE dt_setpropertybyid_u(@id INT, @property VARCHAR, @uvalue TEXT, @lvalue IMAGE);
-- CREATE PROCEDURE dt_validateloginparams(@vchLoginName VARCHAR, @vchPassword VARCHAR);
-- CREATE PROCEDURE dt_validateloginparams_u(@vchLoginName TEXT, @vchPassword TEXT);
-- CREATE PROCEDURE dt_vcsenabled();
-- CREATE PROCEDURE dt_verstamp006();
-- CREATE PROCEDURE dt_verstamp007();
-- CREATE PROCEDURE dt_whocheckedout(@chObjectType CHAR, @vchObjectName VARCHAR, @vchLoginName VARCHAR, @vchPassword VARCHAR);
-- CREATE PROCEDURE dt_whocheckedout_u(@chObjectType CHAR, @vchObjectName TEXT, @vchLoginName TEXT, @vchPassword TEXT);
-- CREATE FUNCTION fn_diagramobjects();
-- CREATE FUNCTION fnAgenteVinculado(@idAgente INT) RETURNS VARCHAR;
-- CREATE FUNCTION fnEnderecoParaCorrepondencia(@Opcao INT, @idAgente INT, @Status BIT, @Divulgar BIT) RETURNS VARCHAR;
-- CREATE FUNCTION fnMunicipioAgente(@p_idAgente INT) RETURNS VARCHAR;
-- CREATE FUNCTION fnNome(@p_idAgente INT) RETURNS VARCHAR;
-- CREATE FUNCTION fnTipoEvento(@p_IdMunicipio VARCHAR) RETURNS INT;
-- CREATE FUNCTION fnUFAgente(@p_idAgente INT) RETURNS VARCHAR;
-- CREATE FUNCTION fnVinculado(@idVinculado INT) RETURNS VARCHAR;
-- CREATE FUNCTION fnVinculoPrincipal(@idVinculado INT) RETURNS VARCHAR;
-- CREATE PROCEDURE sArquivoCaixaGrio(@p_Data VARCHAR, @p_Narquivo VARCHAR);
-- CREATE PROCEDURE sBeneficios(@Matricula VARCHAR);
-- CREATE PROCEDURE sMontarCaracteristica(@Perfil INT);
-- CREATE PROCEDURE sp_alterdiagram(@diagramname TEXT, @owner_id INT, @version INT, @definition VARBINARY);
-- CREATE PROCEDURE sp_creatediagram(@diagramname TEXT, @owner_id INT, @version INT, @definition VARBINARY);
-- CREATE PROCEDURE sp_dropdiagram(@diagramname TEXT, @owner_id INT);
-- CREATE PROCEDURE sp_helpdiagramdefinition(@diagramname TEXT, @owner_id INT);
-- CREATE PROCEDURE sp_helpdiagrams(@diagramname TEXT, @owner_id INT);
-- CREATE PROCEDURE sp_renamediagram(@diagramname TEXT, @owner_id INT, @new_diagramname TEXT);
-- CREATE PROCEDURE sp_upgraddiagrams();
-- CREATE PROCEDURE spu_coluna(@objectname VARCHAR);
-- CREATE PROCEDURE sVinculadoSubordinado(@CNPJCPF VARCHAR);
-- CREATE PROCEDURE sVinculos(@CNPJCPF VARCHAR);
-- CREATE PROCEDURE sVinculosOrgaosCultura(@CNPJCPF VARCHAR, @Perfil INT);
-- CREATE PROCEDURE sVinculosPF(@CNPJCPF VARCHAR);
-- CREATE PROCEDURE sVinculosSecretaria(@CNPJCPF VARCHAR);