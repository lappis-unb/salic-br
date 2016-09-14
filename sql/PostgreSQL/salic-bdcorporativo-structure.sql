-- noinspection SqlDialectInspectionForFile
-- CREATE SCHEMA agentes;
-- DROP SCHEMA agentes CASCADE;
-- COMMIT;
-- ROLLBACK;
-- BEGIN;

CREATE SCHEMA IF NOT EXISTS bdcorporativo AUTHORIZATION postgres;

CREATE TABLE bdcorporativo.tbBairro
(
  nrBairro INT PRIMARY KEY NOT NULL,
  nrLocalidade INT NOT NULL,
  cdUf CHAR(2) NOT NULL,
  nmBairro CHAR(72) NOT NULL,
  nmBairroAbreviado CHAR(36)
);
CREATE TABLE bdcorporativo.tbBairroVariacao
(
  nrBairro INT NOT NULL,
  nrVariacao INT NOT NULL,
  nmBairroVariacao CHAR(72) NOT NULL,
  CONSTRAINT pk_tbBairroVariacao PRIMARY KEY (nrBairro, nrVariacao)
);
CREATE TABLE bdcorporativo.tbCaixaPostalComunitaria
(
  nrCaixaPostal INT PRIMARY KEY NOT NULL,
  cdUf CHAR(2) NOT NULL,
  nrLocalidade INT NOT NULL,
  nmCaixaPostal CHAR(72) NOT NULL,
  dsEndereco CHAR(100) NOT NULL,
  cdCep CHAR(8) NOT NULL
);
CREATE TABLE bdcorporativo.tbFaixaCaixaPostalComunitaria
(
  nrCaixaPostal INT NOT NULL,
  nrInicioCaixaPostal CHAR(6) NOT NULL,
  nrFimCaixaPostal CHAR(6) NOT NULL,
  CONSTRAINT pk_tbFaixaCaixaPostalComunitaria PRIMARY KEY (nrCaixaPostal, nrInicioCaixaPostal)
);
CREATE TABLE bdcorporativo.tbFaixaCepBairro
(
  nrBairro INT NOT NULL,
  cdInicioCep CHAR(8) NOT NULL,
  cdFimCep CHAR(8) NOT NULL,
  CONSTRAINT pk_tbFaixaCepBairro PRIMARY KEY (nrBairro, cdInicioCep)
);
CREATE TABLE bdcorporativo.tbFaixaCepLocalidade
(
  nrLocalidade INT NOT NULL,
  cdInicioCep CHAR(8) NOT NULL,
  cdFimCep CHAR(8) NOT NULL,
  CONSTRAINT pk_tbFaixaCepLocalidade PRIMARY KEY (nrLocalidade, cdInicioCep)
);
CREATE TABLE bdcorporativo.tbFaixaCepUf
(
  cdUf CHAR(2) NOT NULL,
  cdInicioCep CHAR(8) NOT NULL,
  cdFimCep CHAR(8) NOT NULL,
  CONSTRAINT pk_tbFaixaCepUf PRIMARY KEY (cdUf, cdInicioCep)
);
CREATE TABLE bdcorporativo.tbFaixaPostalUnidOperacional
(
  nrUnidadeOperacional INT NOT NULL,
  cdInicioCaixaPostal CHAR(8) NOT NULL,
  cdFimCaixaPostal CHAR(8) NOT NULL,
  CONSTRAINT pk_tbFaixaPostalUnidOperacional PRIMARY KEY (nrUnidadeOperacional, cdInicioCaixaPostal)
);
CREATE TABLE bdcorporativo.tbGrandeUsuario
(
  nrGrandeUsuario INT PRIMARY KEY NOT NULL,
  cdUf VARCHAR(2) NOT NULL,
  nrLocalidade INT,
  nrBairro INT NOT NULL,
  nmGrandeUsuario VARCHAR(72) NOT NULL,
  cdCep VARCHAR(8) NOT NULL,
  nmTipoLogradouro CHAR(72),
  nmPreposicao CHAR(3),
  nmTituloPatente CHAR(72),
  nmLogradouro CHAR(100),
  nrLote CHAR(11),
  nmComplemento1 CHAR(36),
  cdComplemento1 CHAR(11),
  nmComplemento2 CHAR(36),
  cdComplemento2 CHAR(11),
  nmUnidadeOcupacao CHAR(36),
  cdUnidadeOcupacao CHAR(36)
);
CREATE TABLE bdcorporativo.tbLocalidade
(
  nrLocalidade INT PRIMARY KEY NOT NULL,
  cdUf CHAR(2) NOT NULL,
  nmLocalidade CHAR(72) NOT NULL,
  cdCep CHAR(8),
  stCodificacaoLocalidade CHAR(1) NOT NULL,
  stTipoLocalidade CHAR(1) NOT NULL,
  nrSubLocalidade INT,
  nmLocalidadeAbreviado CHAR(36)
);
CREATE TABLE bdcorporativo.tbLocalidadeVariacao
(
  nrLocalidade INT NOT NULL,
  nrLoclidadevariacao INT NOT NULL,
  nmLoclidadeVariacao CHAR(72) NOT NULL,
  CONSTRAINT pk_tbLocalidadeVariacao PRIMARY KEY (nrLocalidade, nrLoclidadevariacao)
);
CREATE TABLE bdcorporativo.tbLogradouroSeccionamento
(
  nrLogradouroUf INT NOT NULL,
  cdCep CHAR(8) NOT NULL,
  cdIniLogradouroSec CHAR(11) NOT NULL,
  cdFimLogradouroSec CHAR(11) NOT NULL,
  stLadoLogradouroSec CHAR(1) NOT NULL,
  CONSTRAINT pk_tbLogradouroSeccionamento PRIMARY KEY (nrLogradouroUf, cdCep)
);
CREATE TABLE bdcorporativo.tbLogradouroUf
(
  nrLogradouroUf INT NOT NULL,
  cdCep CHAR(8) NOT NULL,
  nrLocalidade INT NOT NULL,
  cdUf CHAR(2) NOT NULL,
  nrInicioBairro INT,
  nrFimBairro INT,
  nmLogradouro CHAR(100),
  dsLogradouroComplemento CHAR(100),
  nmTipoLogradouro CHAR(36) NOT NULL,
  stGrandeUsuarioLogradouro CHAR(1),
  nrLote CHAR(11),
  dsComplemento1 CHAR(36),
  cdComplemento1 CHAR(11),
  dsComplemento2 CHAR(36),
  cdComplemento2 CHAR(11),
  nmPreposicao CHAR(3),
  nmTituloPatente CHAR(72),
  CONSTRAINT pk_tbLogradouroUf PRIMARY KEY (nrLogradouroUf, cdCep)
);
CREATE TABLE bdcorporativo.tbLogradouroVariacao
(
  nrLogradouroUf INT NOT NULL,
  nrLogradouroVariacao INT NOT NULL,
  cdCep CHAR(8) NOT NULL,
  nmOrdemVariacao VARCHAR(36) NOT NULL,
  nmFimVariacao VARCHAR(150) NOT NULL,
  CONSTRAINT pk_tbLogradouroVariacao PRIMARY KEY (nrLogradouroUf, nrLogradouroVariacao, cdCep)
);
CREATE TABLE bdcorporativo.tbUnidadeOperacional
(
  nrUnidadeOperacional INT PRIMARY KEY NOT NULL,
  cdUf CHAR(2) NOT NULL,
  nrLocalidade INT NOT NULL,
  nrInicioBairro INT NOT NULL,
  nmUnidadeOperacional CHAR(255),
  cdCep CHAR(8) NOT NULL,
  nmTipoUnidOperacional CHAR(72),
  nmUnidOperacionalAbreviado CHAR(100),
  nmTipoLogradouro CHAR(72),
  nmPreposicao CHAR(3),
  nmTituloPatente CHAR(72),
  nmLogradouro CHAR(100),
  nrLote CHAR(11),
  nmComplemento1 CHAR(36),
  cdComplemento1 CHAR(11),
  nmComplemento2 CHAR(36),
  cdComplemento2 CHAR(11),
  nmUnidadeOcupacao CHAR(36),
  cdUnidadeOcupacao CHAR(36)
);
ALTER TABLE bdcorporativo.tbBairro ADD FOREIGN KEY (nrLocalidade) REFERENCES bdcorporativo.tbLocalidade (nrLocalidade);
ALTER TABLE bdcorporativo.tbBairroVariacao ADD FOREIGN KEY (nrBairro) REFERENCES bdcorporativo.tbBairro (nrBairro);
ALTER TABLE bdcorporativo.tbCaixaPostalComunitaria ADD FOREIGN KEY (nrLocalidade) REFERENCES bdcorporativo.tbLocalidade (nrLocalidade);
ALTER TABLE bdcorporativo.tbFaixaCaixaPostalComunitaria ADD FOREIGN KEY (nrCaixaPostal) REFERENCES bdcorporativo.tbCaixaPostalComunitaria (nrCaixaPostal);
-- ALTER TABLE bdcorporativo.tbFaixaCepBairro ADD FOREIGN KEY (nrBairro) REFERENCES bdcorporativo.tbBairro (nrBairro) ON bdcorporativo.UPDATE CASCADE;
ALTER TABLE bdcorporativo.tbFaixaCepLocalidade ADD FOREIGN KEY (nrLocalidade) REFERENCES bdcorporativo.tbLocalidade (nrLocalidade);
ALTER TABLE bdcorporativo.tbFaixaPostalUnidOperacional ADD FOREIGN KEY (nrUnidadeOperacional) REFERENCES bdcorporativo.tbUnidadeOperacional (nrUnidadeOperacional);
ALTER TABLE bdcorporativo.tbGrandeUsuario ADD FOREIGN KEY (nrLocalidade) REFERENCES bdcorporativo.tbLocalidade (nrLocalidade);
CREATE UNIQUE INDEX IX_tbGrandeUsuario_cdCep  ON bdcorporativo.tbGrandeUsuario (cdCep);
ALTER TABLE bdcorporativo.tbLocalidade ADD FOREIGN KEY (nrSubLocalidade) REFERENCES bdcorporativo.tbLocalidade (nrLocalidade);
ALTER TABLE bdcorporativo.tbLocalidadeVariacao ADD FOREIGN KEY (nrLocalidade) REFERENCES bdcorporativo.tbLocalidade (nrLocalidade);
-- ALTER TABLE bdcorporativo.tbLogradouroSeccionamento ADD FOREIGN KEY (cdCep) REFERENCES bdcorporativo.;
-- ALTER TABLE bdcorporativo.tbLogradouroVariacao ADD FOREIGN KEY (cdCep) REFERENCES bdcorporativo.;
ALTER TABLE bdcorporativo.tbUnidadeOperacional ADD FOREIGN KEY (nrLocalidade) REFERENCES bdcorporativo.tbLocalidade (nrLocalidade);



CREATE TABLE bdcorporativo.tbArquivo
(
    idArquivo INT PRIMARY KEY NOT NULL,
    nmArquivo VARCHAR(255) NOT NULL,
    sgExtensao VARCHAR(5) DEFAULT '((0))' NOT NULL,
    nrTamanho VARCHAR(10),
    dtEnvio TIMESTAMP NOT NULL,
    dsHash VARCHAR(128),
    stAtivo CHAR(1) NOT NULL,
    dsTipoPadronizado CHAR(100),
    idUsuario INT DEFAULT ((0)) NOT NULL
);
CREATE TABLE bdcorporativo.tbArquivoImagem
(
    idArquivo INT PRIMARY KEY NOT NULL,
    biArquivo VARCHAR(255)
);
CREATE TABLE bdcorporativo.tbDocumento
(
    idTipoDocumento INT NOT NULL,
    idDocumento INT NOT NULL,
    idArquivo INT,
    dsDocumento VARCHAR(400),
    dtEmissaoDocumento TIMESTAMP,
    dtValidadeDocumento TIMESTAMP,
    idTipoEventoOrigem INT,
    nmTitulo VARCHAR(20),
    nrDocumento INT,
    CONSTRAINT pk_tbDocumento PRIMARY KEY (idTipoDocumento, idDocumento)
);
CREATE TABLE bdcorporativo.tbDocumentoAgente
(
    idTipoDocumento INT NOT NULL,
    idDocumento INT NOT NULL,
    idAgente INT NOT NULL,
    stAtivoDocumentoAgente NUMERIC DEFAULT (1) NOT NULL,
    CONSTRAINT pk_tbDocumentoAgente PRIMARY KEY (idTipoDocumento, idDocumento, idAgente)
);
CREATE TABLE bdcorporativo.tbDocumentoProjeto
(
    idTipoDocumento INT NOT NULL,
    idDocumento INT NOT NULL,
    idPronac INT NOT NULL,
    stAtivoDocumentoProjeto CHAR(1) DEFAULT 'E',
    CONSTRAINT pk_tbDocumentoProjeto PRIMARY KEY (idTipoDocumento, idDocumento, idPronac)
);
CREATE TABLE bdcorporativo.tbDocumentoProposta
(
    idTipoDocumento INT NOT NULL,
    idDocumento INT NOT NULL,
    idProposta INT NOT NULL,
    stAtivoDocumentoProposta NUMERIC DEFAULT (1) NOT NULL,
    CONSTRAINT pk_tbDocumentoPreProjeto PRIMARY KEY (idTipoDocumento, idDocumento, idProposta)
);
CREATE TABLE bdcorporativo.tbTipoDocumento
(
    idTipoDocumento INT PRIMARY KEY NOT NULL,
    dsTipoDocumento CHAR(200) NOT NULL
);
CREATE TABLE bdcorporativo.tbTipoEvento
(
    idTipoEvento INT PRIMARY KEY NOT NULL,
    dsTipoEvento CHAR(200) NOT NULL
);
ALTER TABLE bdcorporativo.tbArquivoImagem ADD FOREIGN KEY (idArquivo) REFERENCES bdcorporativo.tbArquivo (idArquivo);
ALTER TABLE bdcorporativo.tbDocumento ADD FOREIGN KEY (idTipoDocumento) REFERENCES bdcorporativo.tbTipoDocumento (idTipoDocumento) ON DELETE CASCADE;
ALTER TABLE bdcorporativo.tbDocumento ADD FOREIGN KEY (idArquivo) REFERENCES bdcorporativo.tbArquivo (idArquivo);
ALTER TABLE bdcorporativo.tbDocumento ADD FOREIGN KEY (idTipoEventoOrigem) REFERENCES bdcorporativo.tbTipoEvento (idTipoEvento);
-- ALTER TABLE bdcorporativo.tbDocumentoAgente ADD FOREIGN KEY (idDocumento) REFERENCES;
-- ALTER TABLE bdcorporativo.tbDocumentoProjeto ADD FOREIGN KEY (idDocumento) REFERENCES;
-- ALTER TABLE bdcorporativo.tbDocumentoProposta ADD FOREIGN KEY (idDocumento) REFERENCES;