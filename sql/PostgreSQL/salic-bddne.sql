


-- noinspection SqlDialectInspectionForFile
-- CREATE SCHEMA agentes;
-- DROP SCHEMA agentes CASCADE;
-- COMMIT;
-- ROLLBACK;
-- BEGIN;

CREATE SCHEMA IF NOT EXISTS bddne AUTHORIZATION postgres;

CREATE TABLE bddne.tbBairro
(
  nrBairro INT PRIMARY KEY NOT NULL,
  nrLocalidade INT NOT NULL,
  cdUf CHAR(2) NOT NULL,
  nmBairro CHAR(72) NOT NULL,
  nmBairroAbreviado CHAR(36)
);
CREATE TABLE bddne.tbBairroVariacao
(
  nrBairro INT NOT NULL,
  nrVariacao INT NOT NULL,
  nmBairroVariacao CHAR(72) NOT NULL,
  CONSTRAINT PK_tbBairroVariacao PRIMARY KEY (nrBairro, nrVariacao)
);
CREATE TABLE bddne.tbCaixaPostalComunitaria
(
  nrCaixaPostal INT PRIMARY KEY NOT NULL,
  cdUf CHAR(2) NOT NULL,
  nrLocalidade INT NOT NULL,
  nmCaixaPostal CHAR(72) NOT NULL,
  dsEndereco CHAR(100) NOT NULL,
  cdCep CHAR(8) NOT NULL
);
CREATE TABLE bddne.tbFaixaCaixaPostalComunitaria
(
  nrCaixaPostal INT NOT NULL,
  nrInicioCaixaPostal CHAR(6) NOT NULL,
  nrFimCaixaPostal CHAR(6) NOT NULL,
  CONSTRAINT PK_tbFaixaCaixaPostalComunitaria PRIMARY KEY (nrCaixaPostal, nrInicioCaixaPostal)
);
CREATE TABLE bddne.tbFaixaCepBairro
(
  nrBairro INT NOT NULL,
  cdInicioCep CHAR(8) NOT NULL,
  cdFimCep CHAR(8) NOT NULL,
  CONSTRAINT PK_tbFaixaCepBairro PRIMARY KEY (nrBairro, cdInicioCep)
);
CREATE TABLE bddne.tbFaixaCepLocalidade
(
  nrLocalidade INT NOT NULL,
  cdInicioCep CHAR(8) NOT NULL,
  cdFimCep CHAR(8) NOT NULL,
  CONSTRAINT PK_tbFaixaCepLocalidade PRIMARY KEY (nrLocalidade, cdInicioCep)
);
CREATE TABLE bddne.tbFaixaCepUf
(
  cdUf CHAR(2) NOT NULL,
  cdInicioCep CHAR(8) NOT NULL,
  cdFimCep CHAR(8) NOT NULL,
  CONSTRAINT PK_tbFaixaCepUf PRIMARY KEY (cdUf, cdInicioCep)
);
CREATE TABLE bddne.tbFaixaPostalUnidOperacional
(
  nrUnidadeOperacional INT NOT NULL,
  cdInicioCaixaPostal CHAR(8) NOT NULL,
  cdFimCaixaPostal CHAR(8) NOT NULL,
  CONSTRAINT PK_tbFaixaPostalUnidOperacional PRIMARY KEY (nrUnidadeOperacional, cdInicioCaixaPostal)
);
CREATE TABLE bddne.tbGrandeUsuario
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
CREATE TABLE bddne.tbLocalidade
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
CREATE TABLE bddne.tbLocalidadeVariacao
(
  nrLocalidade INT NOT NULL,
  nrLoclidadevariacao INT NOT NULL,
  nmLoclidadeVariacao CHAR(72) NOT NULL,
  CONSTRAINT PK_tbLocalidadeVariacao PRIMARY KEY (nrLocalidade, nrLoclidadevariacao)
);
CREATE TABLE bddne.tbLogradouroSeccionamento
(
  nrLogradouroUf INT NOT NULL,
  cdCep CHAR(8) NOT NULL,
  cdIniLogradouroSec CHAR(11) NOT NULL,
  cdFimLogradouroSec CHAR(11) NOT NULL,
  stLadoLogradouroSec CHAR(1) NOT NULL,
  CONSTRAINT PK_tbLogradouroSeccionamento PRIMARY KEY (nrLogradouroUf, cdCep)
);
CREATE TABLE bddne.tbLogradouroUf
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
  CONSTRAINT PK_tbLogradouroUf PRIMARY KEY (nrLogradouroUf, cdCep)
);
CREATE TABLE bddne.tbLogradouroVariacao
(
  nrLogradouroUf INT NOT NULL,
  nrLogradouroVariacao INT NOT NULL,
  cdCep CHAR(8) NOT NULL,
  nmOrdemVariacao VARCHAR(36) NOT NULL,
  nmFimVariacao VARCHAR(150) NOT NULL,
  CONSTRAINT PK_tbLogradouroVariacao PRIMARY KEY (nrLogradouroUf, nrLogradouroVariacao, cdCep)
);
CREATE TABLE bddne.tbUnidadeOperacional
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
ALTER TABLE bddne.tbBairro ADD FOREIGN KEY (nrLocalidade) REFERENCES bddne.tbLocalidade (nrLocalidade);
ALTER TABLE bddne.tbBairroVariacao ADD FOREIGN KEY (nrBairro) REFERENCES bddne.tbBairro (nrBairro);
ALTER TABLE bddne.tbCaixaPostalComunitaria ADD FOREIGN KEY (nrLocalidade) REFERENCES bddne.tbLocalidade (nrLocalidade);
CREATE UNIQUE INDEX IX_tbCaixaPostalComunitaria ON bddne.tbCaixaPostalComunitaria (cdCep);
ALTER TABLE bddne.tbFaixaCaixaPostalComunitaria ADD FOREIGN KEY (nrCaixaPostal) REFERENCES bddne.tbCaixaPostalComunitaria (nrCaixaPostal);
ALTER TABLE bddne.tbFaixaCepBairro ADD FOREIGN KEY (nrBairro) REFERENCES bddne.tbBairro (nrBairro) ON UPDATE CASCADE;
ALTER TABLE bddne.tbFaixaCepLocalidade ADD FOREIGN KEY (nrLocalidade) REFERENCES bddne.tbLocalidade (nrLocalidade);
ALTER TABLE bddne.tbFaixaPostalUnidOperacional ADD FOREIGN KEY (nrUnidadeOperacional) REFERENCES bddne.tbUnidadeOperacional (nrUnidadeOperacional);
ALTER TABLE bddne.tbGrandeUsuario ADD FOREIGN KEY (nrLocalidade) REFERENCES bddne.tbLocalidade (nrLocalidade);
CREATE UNIQUE INDEX IX_tbGrandeUsuario ON bddne.tbGrandeUsuario (cdCep);
CREATE UNIQUE INDEX IX_tbGrandeUsuario1 ON bddne.tbGrandeUsuario (nrBairro);
ALTER TABLE bddne.tbLocalidade ADD FOREIGN KEY (nrSubLocalidade) REFERENCES bddne.tbLocalidade (nrLocalidade);
CREATE UNIQUE INDEX IX_tbLocalidade ON bddne.tbLocalidade (cdCep);
CREATE UNIQUE INDEX IX_tbLocalidade1 ON bddne.tbLocalidade (cdUf, nmLocalidade);
ALTER TABLE bddne.tbLocalidadeVariacao ADD FOREIGN KEY (nrLocalidade) REFERENCES bddne.tbLocalidade (nrLocalidade);
-- ALTER TABLE bddne.tbLogradouroSeccionamento ADD FOREIGN KEY (cdCep) REFERENCES bddne.;
ALTER TABLE bddne.tbLogradouroUf ADD FOREIGN KEY (nrLocalidade) REFERENCES bddne.tbLocalidade (nrLocalidade);
CREATE UNIQUE INDEX IX_tbLogradouroUf1 ON bddne.tbLogradouroUf (cdCep);
-- ALTER TABLE bddne.tbLogradouroVariacao ADD FOREIGN KEY (cdCep) REFERENCES ;
ALTER TABLE bddne.tbUnidadeOperacional ADD FOREIGN KEY (nrLocalidade) REFERENCES bddne.tbLocalidade (nrLocalidade);
CREATE UNIQUE INDEX IX_tbUnidadeOperacional ON bddne.tbUnidadeOperacional (cdUf);
CREATE UNIQUE INDEX IX_tbUnidadeOperacional1 ON bddne.tbUnidadeOperacional (nrInicioBairro);