-- noinspection SqlDialectInspectionForFile
-- DROP SCHEMA sac CASCADE;
-- CREATE SCHEMA sac;
-- COMMIT;
-- ROLLBACK;
-- BEGIN;

CREATE SCHEMA IF NOT EXISTS sac AUTHORIZATION postgres;

CREATE TABLE sac.tbMovimentacaoBancaria
(
  idMovimentacaoBancaria INT PRIMARY KEY NOT NULL,
  nrBanco CHAR(3) NOT NULL,
  nmArquivo VARCHAR(40) NOT NULL,
  dtArquivo timestamp NOT NULL,
  dtInicioMovimento timestamp NOT NULL,
  dtFimMovimento timestamp NOT NULL,
  idUsuario INT NOT NULL
);
CREATE TABLE sac.tbMovimentacaoBancariaItem
(
  idMovimentacaoBancariaItem INT PRIMARY KEY NOT NULL,
  tpRegistro CHAR,
  nrAgencia CHAR(5),
  nrConta VARCHAR(12),
  nmTituloRazao VARCHAR(12),
  nmAbreviado VARCHAR(30),
  dtAberturaConta timestamp,
  nrCNPJCPF VARCHAR(14),
  vlSaldoInicial DECIMAL(12,2),
  stSaldoInicial CHAR,
  vlSaldoFinal DECIMAL(12,2),
  stSaldoFinal CHAR,
  dtMovimento timestamp,
  cdHistorico CHAR(4),
  dsHistorico VARCHAR(15),
  nrDocumento VARCHAR(10),
  vlMovimento DECIMAL(12,2),
  stMovimento CHAR,
  idMovimentacaoBancaria INT NOT NULL,
  CONSTRAINT fk_tbMovimentacaoBancariaItem_tbMovimentacaoBancaria FOREIGN KEY (idMovimentacaoBancaria) REFERENCES sac.tbMovimentacaoBancaria (idMovimentacaoBancaria)
);
CREATE TABLE sac.tbLote
(
  idLote INT PRIMARY KEY NOT NULL,
  dtLote timestamp NOT NULL
);
CREATE TABLE sac.tbManterPortaria
(
  idManterPortaria INT PRIMARY KEY NOT NULL,
  dsAssinante VARCHAR(255) NOT NULL,
  dtPortariaPublicacao timestamp NOT NULL,
  stEstado INTEGER DEFAULT '1' NOT NULL,
  dsCargo VARCHAR(255) NOT NULL,
  dsPortaria VARCHAR(1000) NOT NULL
);
CREATE TABLE sac.tbImovel
(
  idImovel INT PRIMARY KEY NOT NULL,
  tpImovel CHAR NOT NULL,
  vlImovel NUMERIC(9,2) DEFAULT 0.00 NOT NULL,
  dsImovel VARCHAR(100) NOT NULL,
  nmCartorio VARCHAR(50) NOT NULL,
  nrRegistro INT NOT NULL,
  nrFolha VARCHAR(10) NOT NULL,
  stTipoImovel CHAR DEFAULT 0 NOT NULL,
  stvalorImovel CHAR DEFAULT 0 NOT NULL,
  stdescricaoImovel CHAR DEFAULT 0 NOT NULL,
  stCartorio CHAR DEFAULT 0 NOT NULL,
  stRegistro CHAR DEFAULT 0 NOT NULL,
  stNumeroFolha CHAR DEFAULT 0 NOT NULL,
  dsJustificativaAcompanhamento TEXT
);
CREATE TABLE sac.tbPlanilhaEtapa
(
  idPlanilhaEtapa INT PRIMARY KEY NOT NULL,
  Descricao VARCHAR(50) NOT NULL,
  tpCusto CHAR DEFAULT 'P',
  stEstado INTEGER DEFAULT 1,
  tpGrupo CHAR
);
CREATE TABLE sac.tbPlanilhaItens
(
  idPlanilhaItens INT PRIMARY KEY NOT NULL,
  Descricao VARCHAR(250) NOT NULL,
  idUsuario INT DEFAULT 0 NOT NULL
);
CREATE TABLE sac.tbAvaliacaoProposta
(
  idAvaliacaoProposta INT PRIMARY KEY NOT NULL,
  idProjeto INT NOT NULL,
  idTecnico INT NOT NULL,
  DtEnvio timestamp,
  DtAvaliacao timestamp,
  Avaliacao VARCHAR DEFAULT '  ' NOT NULL,
  ConformidadeOK int DEFAULT 0 NOT NULL,
  stEstado INTEGER DEFAULT 0 NOT NULL,
  dsResposta VARCHAR,
  dtResposta timestamp,
  idArquivo INT,
  idCodigoDocumentosExigidos INT,
  stEnviado CHAR,
  stProrrogacao NCHAR
);
CREATE INDEX IX_tbAvaliacaoProposta ON sac.tbAvaliacaoProposta (idProjeto);
CREATE TABLE sac.tbConfigurarPagamento
(
  idConfigurarPagamento INT PRIMARY KEY NOT NULL,
  nrDespachoInicial INT NOT NULL,
  nrDespachoFinal INT NOT NULL,
  dtConfiguracaoPagamento timestamp NOT NULL,
  stEstado INTEGER DEFAULT 1 NOT NULL,
  idUsuario INT DEFAULT 1 NOT NULL
);
CREATE TABLE sac.tbDepositoIdentificadoCaptacao
(
  idDepositoIdentificadoCaptacao INT PRIMARY KEY NOT NULL,
  dsInformacao VARCHAR(150),
  idUsuario INT
);
CREATE TABLE sac.tbDepositoIdentificadoCaptacaoOLD
(
  idDepositoIdentificadoCaptacao INT PRIMARY KEY NOT NULL,
  dsInformacao VARCHAR(150),
  idUsuario INT
);
CREATE TABLE sac.tbDepositoIdentificadoMovimentacao
(
  idDepositoIdentificadoMovimentacao INT PRIMARY KEY NOT NULL,
  dsInformacao VARCHAR,
  idUsuario INT
);
CREATE TABLE sac.Area
(
  Codigo VARCHAR(4) PRIMARY KEY NOT NULL,
  Descricao VARCHAR(50) NOT NULL
);
CREATE UNIQUE INDEX AK_Area ON sac.Area (Codigo);
CREATE TABLE sac.AtualizaOrgaoProjetosX
(
  idPronac INT PRIMARY KEY NOT NULL,
  Orgao INT NOT NULL
);
CREATE TABLE sac.Auditoria
(
  database_name VARCHAR(128),
  schema_name VARCHAR(128),
  class_type VARCHAR(2),
  object_name VARCHAR(128),
  statement VARCHAR(4000)
);
CREATE TABLE sac.Internet
(
  Tipo int NOT NULL,
  ChaveA VARCHAR(20) DEFAULT '',
  ChaveB VARCHAR(20) DEFAULT '',
  ChaveC VARCHAR(50) DEFAULT '',
  ChaveD VARCHAR(100),
  CampoA VARCHAR(100),
  CampoB VARCHAR(100),
  CampoC VARCHAR(100),
  ValorA MONEY DEFAULT 0,
  ValorB MONEY DEFAULT 0,
  ValorC MONEY DEFAULT 0,
  ValorD MONEY DEFAULT 0,
  ValorE MONEY DEFAULT 0
);
CREATE INDEX IX_Internet ON sac.Internet (Tipo, ChaveA, ChaveB, ChaveC, ChaveD);
CREATE TABLE sac.Intranet
(
  Tipo int NOT NULL,
  ChaveA VARCHAR(20) DEFAULT ' ',
  ChaveB VARCHAR(20) DEFAULT ' ',
  ChaveC VARCHAR(20) DEFAULT ' ',
  ChaveD VARCHAR(20) DEFAULT ' ',
  ChaveE VARCHAR(20) DEFAULT ' ',
  CampoA VARCHAR(300),
  CampoB VARCHAR(300),
  CampoC VARCHAR(300),
  CampoD VARCHAR(300),
  CampoE VARCHAR(300),
  QtdeA INT,
  QtdeB INT,
  QtdeC INT,
  QtdeD INT,
  QtdeE INT,
  ValorA MONEY DEFAULT 0,
  ValorB MONEY DEFAULT 0,
  ValorC MONEY DEFAULT 0,
  ValorD MONEY DEFAULT 0,
  ValorE MONEY DEFAULT 0,
  DataA timestamp,
  DataB timestamp,
  DataC timestamp,
  DataD timestamp,
  DataE timestamp
);
CREATE INDEX IX_Intranet ON sac.Intranet (Tipo);
CREATE TABLE sac.KitBanda
(
  Codigo SMALLINT PRIMARY KEY NOT NULL,
  Descricao VARCHAR(50) NOT NULL,
  Quantidade INT DEFAULT 0 NOT NULL,
  Kit CHAR DEFAULT 'N',
  Especificacao VARCHAR(50) NOT NULL,
  Afinacao VARCHAR(50) NOT NULL,
  Acabamento VARCHAR(50) NOT NULL,
  PrecoUnitario MONEY DEFAULT 0 NOT NULL
);
CREATE TABLE sac.tbPlanilhaUnidade
(
  idUnidade INT PRIMARY KEY NOT NULL,
  Sigla VARCHAR(20) NOT NULL,
  Descricao VARCHAR(50) NOT NULL
);
CREATE TABLE sac.tbReuniao
(
  idNrReuniao INT PRIMARY KEY NOT NULL,
  NrReuniao INT NOT NULL,
  DtInicio timestamp NOT NULL,
  DtFinal timestamp NOT NULL,
  DtFechamento timestamp,
  Mecanismo CHAR NOT NULL,
  stEstado INTEGER DEFAULT 0 NOT NULL,
  idUsuario INT NOT NULL,
  stPlenaria CHAR DEFAULT 'N' NOT NULL
);
CREATE INDEX IX_NrReuniao ON sac.tbReuniao (NrReuniao);
CREATE TABLE sac.tbTmpInconsistenciaCaptacao19022016
(
  idTipoInconsistencia INT NOT NULL,
  idTmpCaptacao INT NOT NULL
);
CREATE TABLE sac.tbTmpRelatorioConsolidado
(
  idPronac INT,
  dsObjetivosMetas VARCHAR(8000),
  dsEstrategiaAcao TEXT,
  vlLeiIncentivoFiscal NUMERIC(9,2),
  vlLeiIncentivoEstadual NUMERIC(9,2),
  vlLeiIncentivoMunicipal NUMERIC(9,2),
  vlRecursosProprios NUMERIC(9,2),
  vlRendimentoFinanceiro NUMERIC(9,2),
  idDcumentoFNC INT,
  idPlanoDistribuicao INT,
  idDocumentoPlanoDistribuicao INT,
  tpImovel CHAR,
  vlImovel NUMERIC(9,2),
  dsImovel VARCHAR(100),
  nmCartorio VARCHAR(50),
  nrRegistro INT,
  nrFolha VARCHAR(10),
  idDocumentoComprovanteExecucao INT,
  dsDestinacaoProduto TEXT,
  stFinsLucrativos CHAR,
  dsBeneficiario VARCHAR(8000),
  nrCNPJ CHAR(14),
  nrCPF CHAR(11),
  dsReceptorProduto TEXT,
  dsAcessoAcessibilidade VARCHAR(8000),
  qtPessoaAcessibilidade INT,
  dsPublicoAlvoAcessibilidade VARCHAR(8000),
  dsLocalAcessibilidade VARCHAR(8000),
  dsEstruturaSolucaoAcessibilidade VARCHAR(8000),
  dsAcessoDemocratizacao VARCHAR(8000),
  qtPessoaDemocratizacao INT,
  dsPublicoAlvoDemocratizacao VARCHAR(8000),
  dsLocalDemocratizacao VARCHAR(8000),
  dsEstruturaSolucaoDemocratizacao VARCHAR(8000),
  dsProduto VARCHAR(100),
  dsRepercussao TEXT,
  dsImpactoAmbiental TEXT,
  dsImpactoCultural TEXT,
  dsImpactoEconomico TEXT,
  dsImpactoSocial TEXT,
  stPrevisaoProjeto CHAR,
  dsTermoProjeto TEXT,
  idDocumentoAceiteObra INT,
  dsCronogramaFisico TEXT
);
CREATE TABLE sac.tbValidarItem
(
  idValidarItem INT PRIMARY KEY NOT NULL,
  dsJustificativa VARCHAR(8000) NOT NULL,
  dtValidacao timestamp NOT NULL,
  cdItemAvaliado CHAR NOT NULL,
  idDeParaPlanilhaAprovacao INT NOT NULL
);
CREATE TABLE sac.Veiculacao
(
  Codigo int PRIMARY KEY NOT NULL,
  Descricao VARCHAR(50) NOT NULL
);
CREATE TABLE sac.ImpactoX
(
  Codigo int PRIMARY KEY NOT NULL,
  Descricao VARCHAR(100) NOT NULL
);
CREATE TABLE sac.LinkBBX
(
  Codigo INT PRIMARY KEY NOT NULL,
  CgcCpf VARCHAR(14) NOT NULL
);
CREATE TABLE sac.LocalizacaoFisica
(
  Id INT PRIMARY KEY NOT NULL,
  IdPronac INT NOT NULL,
  Pronac VARCHAR(7) NOT NULL,
  TecnicoAntigo INT,
  TecnicoAtual INT,
  Localizacao VARCHAR(255) NOT NULL,
  DataCriacao timestamp DEFAULT NOW()
);
CREATE TABLE sac.Mecanismo
(
  Codigo CHAR PRIMARY KEY NOT NULL,
  Descricao VARCHAR(50) NOT NULL,
  Status INTEGER DEFAULT 1 NOT NULL
);
CREATE UNIQUE INDEX AK_Mecanismo ON sac.Mecanismo (Codigo);
CREATE TABLE sac.Metragem
(
  Codigo int PRIMARY KEY NOT NULL,
  Descricao VARCHAR(50) NOT NULL
);
CREATE TABLE sac.Modalidade
(
  Codigo VARCHAR(3) PRIMARY KEY NOT NULL,
  Descricao VARCHAR(50) NOT NULL
);
CREATE UNIQUE INDEX AK_Modalidade ON sac.Modalidade (Codigo);
CREATE TABLE sac.MotivoX
(
  Codigo int PRIMARY KEY NOT NULL,
  Descricao VARCHAR(50) NOT NULL
);
CREATE TABLE sac.NaturezaDespesa
(
  Codigo CHAR(8) PRIMARY KEY NOT NULL,
  Descricao VARCHAR(50) NOT NULL,
  Logon INT NOT NULL
);
CREATE TABLE sac.Fase
(
  Codigo int PRIMARY KEY NOT NULL,
  Descricao VARCHAR(50) NOT NULL
);
CREATE TABLE sac.Fonte
(
  Codigo CHAR(3) PRIMARY KEY NOT NULL,
  Descricao VARCHAR(50) NOT NULL,
  Logon INT NOT NULL
);
CREATE TABLE sac.Genero
(
  Codigo int PRIMARY KEY NOT NULL,
  Descricao VARCHAR(50) NOT NULL
);
CREATE TABLE sac.TetoRenuncia
(
  Ano CHAR(2) PRIMARY KEY NOT NULL,
  Decreto VARCHAR(15),
  DtPublicacao timestamp,
  ValorUfir MONEY,
  ValorReal MONEY
);
CREATE TABLE sac.tipo
(
  idtipo INT PRIMARY KEY NOT NULL,
  descricao VARCHAR(100) NOT NULL,
  stestado INTEGER DEFAULT 1 NOT NULL
);
CREATE SEQUENCE sac.tipo_idtipo_seq NO MINVALUE NO MAXVALUE NO CYCLE;
ALTER TABLE sac.tipo ALTER COLUMN idtipo SET DEFAULT nextval('sac.tipo_idtipo_seq');
ALTER SEQUENCE sac.tipo_idtipo_seq OWNED BY sac.tipo.idtipo;

CREATE TABLE sac.Verificacao
(
  idVerificacao INT PRIMARY KEY NOT NULL,
  idTipo INT NOT NULL,
  Descricao VARCHAR(100) NOT NULL,
  stEstado INTEGER DEFAULT 1 NOT NULL,
  CONSTRAINT FK_Verificacao_Tipo FOREIGN KEY (idTipo) REFERENCES sac.Tipo (idTipo)
);
CREATE TABLE sac.tbTextoEmail
(
  idTextoemail INT PRIMARY KEY NOT NULL,
  idAssunto INT NOT NULL,
  dsTexto VARCHAR NOT NULL,
  CONSTRAINT fk_tbTextoEmail_01 FOREIGN KEY (idAssunto) REFERENCES sac.Verificacao (idVerificacao)
);
CREATE TABLE sac.TipoDocumento
(
  Codigo INT PRIMARY KEY NOT NULL,
  Sigla VARCHAR(15) NOT NULL,
  Descricao VARCHAR(50) NOT NULL,
  Status INTEGER DEFAULT 0 NOT NULL,
  QtdeCampos int DEFAULT 1 NOT NULL
);
CREATE TABLE sac.Uf
(
  Uf CHAR(2) PRIMARY KEY NOT NULL,
  Descricao VARCHAR(30) NOT NULL,
  Regiao VARCHAR(15) NOT NULL,
  Fonte CHAR NOT NULL,
  CodUfIbge INT NOT NULL
);
CREATE UNIQUE INDEX AK_Uf ON sac.Uf (Uf);
CREATE INDEX IX_Uf ON sac.Uf (CodUfIbge);
CREATE TABLE sac.UgGestao
(
  Codigo CHAR(6) PRIMARY KEY NOT NULL,
  Descricao VARCHAR(100) NOT NULL,
  Logon INT NOT NULL
);
CREATE TABLE sac.UGR
(
  Codigo CHAR(6) PRIMARY KEY NOT NULL,
  Descricao VARCHAR(100) NOT NULL,
  Logon INT NOT NULL
);
CREATE TABLE sac.UnidadeOrcamentaria
(
  Uo VARCHAR(5) NOT NULL,
  Descricao VARCHAR(100) NOT NULL
);
CREATE TABLE sac.UnidadeX
(
  Sigla VARCHAR(15) PRIMARY KEY NOT NULL,
  Descricao VARCHAR(100) NOT NULL
);
CREATE UNIQUE INDEX UQ_Unidade_2__16 ON sac.UnidadeX (Sigla);
CREATE TABLE sac.ParecerVinculadasX
(
  idParecerVinculadas INT PRIMARY KEY NOT NULL,
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  Opcao int NOT NULL,
  Data timestamp NOT NULL,
  Lei8313 INTEGER NOT NULL,
  Artigo3 INTEGER NOT NULL,
  IncisoArtigo3 VARCHAR(25) NOT NULL,
  AlineaArtigo3 VARCHAR(25) NOT NULL,
  Artigo18 INTEGER NOT NULL,
  ParagrafoArtigo18 VARCHAR(25) NOT NULL,
  AlineaArtigo18 VARCHAR(25) NOT NULL,
  Artigo25 INTEGER NOT NULL,
  IncisoArtigo25 VARCHAR(25) NOT NULL,
  Artigo26 INTEGER NOT NULL,
  Lei5761 INTEGER DEFAULT 1 NOT NULL,
  Artigo2 INTEGER NOT NULL,
  IncisoArtigo2 VARCHAR(25) NOT NULL,
  Artigo4 INTEGER NOT NULL,
  IncisoArtigo4 VARCHAR(25) NOT NULL,
  FormulacaoA int DEFAULT 1 NOT NULL,
  FormulacaoB int DEFAULT 1 NOT NULL,
  FormulacaoC int DEFAULT 1 NOT NULL,
  ConteudoA int DEFAULT 1 NOT NULL,
  ConteudoB int DEFAULT 1 NOT NULL,
  ConteudoC int DEFAULT 1 NOT NULL,
  ConteudoD int DEFAULT 1 NOT NULL,
  ConteudoE int DEFAULT 1 NOT NULL,
  Parecer CHAR DEFAULT '2' NOT NULL,
  Analista VARCHAR(30) NOT NULL,
  Justificativa TEXT NOT NULL,
  CustoMercado INTEGER DEFAULT 0 NOT NULL,
  ValorA MONEY DEFAULT 0 NOT NULL,
  ValorB MONEY DEFAULT 0 NOT NULL,
  ValorC MONEY DEFAULT 0 NOT NULL,
  ValorA1 MONEY DEFAULT 0 NOT NULL,
  ValorB1 MONEY DEFAULT 0 NOT NULL,
  ValorC1 MONEY DEFAULT 0 NOT NULL,
  ValorA2 MONEY DEFAULT 0 NOT NULL,
  ValorB2 MONEY DEFAULT 0 NOT NULL,
  ValorC2 MONEY DEFAULT 0 NOT NULL,
  Enviar int DEFAULT 0 NOT NULL,
  Diligencia int DEFAULT 0 NOT NULL,
  SinteseProjeto TEXT NOT NULL,
  Orgao INT DEFAULT 0 NOT NULL,
  Logon INT DEFAULT 0 NOT NULL,
  idPRONAC INT NOT NULL
);
CREATE TABLE sac.Interessado
(
  CgcCpf VARCHAR(14) PRIMARY KEY NOT NULL,
  Nome VARCHAR(150) NOT NULL,
  tipoPessoa CHAR NOT NULL,
  Endereco VARCHAR(100) NOT NULL,
  Cidade VARCHAR(50) NOT NULL,
  Uf CHAR(2) NOT NULL,
  Cep VARCHAR(8) NOT NULL,
  TelefoneResidencial VARCHAR(12) DEFAULT ' ',
  TelefoneComercial VARCHAR(12) DEFAULT ' ',
  TelefoneCelular VARCHAR(12) DEFAULT ' ',
  TelefoneFax VARCHAR(12) DEFAULT ' ',
  Natureza CHAR DEFAULT ' ',
  Esfera CHAR DEFAULT ' ',
  Administracao CHAR DEFAULT ' ',
  Utilidade CHAR DEFAULT ' ',
  Responsavel VARCHAR(100) NOT NULL,
  EnderecoInternet VARCHAR(100) DEFAULT ' ',
  CorreioEletronico VARCHAR(100) DEFAULT ' ',
  Grupo INT DEFAULT 0,
  Loc_Codigo INT,
  Logon INT
);
CREATE UNIQUE INDEX AK_Interessado ON sac.Interessado (CgcCpf);
CREATE INDEX IX_Cidade ON sac.Interessado (Cidade);
CREATE INDEX IX_Nome ON sac.Interessado (Nome);
CREATE INDEX IX_Interessado_Uf ON sac.Interessado (Uf);
CREATE TABLE sac.RecibosDeCaptacao
(
  Nome VARCHAR(100) NOT NULL,
  CgcCpf VARCHAR(15) NOT NULL,
  Valor MONEY NOT NULL,
  DtRecibo timestamp NOT NULL,
  NrRecibo VARCHAR(10) NOT NULL,
  NrProjeto VARCHAR(7) NOT NULL,
  TipoOperacao VARCHAR(20) NOT NULL,
  Endereco VARCHAR(100) NOT NULL,
  Numero VARCHAR(15),
  Complemento VARCHAR(50),
  Bairro VARCHAR(100),
  CEP CHAR(8) NOT NULL,
  Cidade VARCHAR(100) NOT NULL,
  UF CHAR(2) NOT NULL
);
CREATE TABLE sac.RetornoBBDeAberturaDeContaPFB
(
  Contador CHAR(5),
  CPFCNPJ CHAR(14),
  DtNascimento CHAR(8),
  NomeCliente CHAR(60),
  Zeros CHAR(10),
  AnoProjeto CHAR(2),
  Sequencial VARCHAR(5),
  Agencia CHAR(5),
  GrupoSetex CHAR(3),
  ContaCorrente CHAR(12),
  Erros CHAR(3),
  Outros CHAR(23)
);
CREATE TABLE sac.RetornoBBDeAberturaDeContaPFL
(
  Contador CHAR(5),
  CPFCNPJ CHAR(14),
  DtNascimento CHAR(8),
  NomeCliente CHAR(60),
  Zeros CHAR(10),
  AnoProjeto CHAR(2),
  Sequencial VARCHAR(5),
  Agencia CHAR(5),
  GrupoSetex CHAR(3),
  ContaCorrente CHAR(12),
  Erros CHAR(3),
  Outros CHAR(23)
);
CREATE INDEX IX_RetornoBBDeAberturaDeContaPFL ON sac.RetornoBBDeAberturaDeContaPFL (AnoProjeto, Sequencial);
CREATE TABLE sac.RetornoBBDeAberturaDeContaPJB
(
  Contador CHAR(5),
  CPFCNPJ CHAR(14),
  DtNascimento CHAR(8),
  NomeCliente CHAR(60),
  Zeros CHAR(10),
  AnoProjeto CHAR(2),
  Sequencial VARCHAR(5),
  Agencia CHAR(5),
  GrupoSetex CHAR(3),
  ContaCorrente CHAR(12),
  Erros CHAR(3),
  Outros CHAR(23)
);
CREATE TABLE sac.RetornoBBDeAberturaDeContaPJL
(
  Contador CHAR(5),
  CPFCNPJ CHAR(14),
  DtNascimento CHAR(8),
  NomeCliente CHAR(60),
  Zeros CHAR(10),
  AnoProjeto CHAR(2),
  Sequencial VARCHAR(5),
  Agencia CHAR(5),
  GrupoSetex CHAR(3),
  ContaCorrente CHAR(12),
  Erros CHAR(3),
  Outros CHAR(23)
);
CREATE INDEX IX_RRetornoBBDeAberturaDeContaPJL ON sac.RetornoBBDeAberturaDeContaPJL (AnoProjeto, Sequencial);
CREATE TABLE sac.Segmento
(
  Codigo VARCHAR(4) PRIMARY KEY NOT NULL,
  Descricao VARCHAR(50) NOT NULL,
  idOrgao INT,
  stEstado INTEGER DEFAULT 1 NOT NULL
);
CREATE UNIQUE INDEX AK_Segmento ON sac.Segmento (Codigo);
CREATE TABLE sac.GuiaRecolhimento
(
  NumeroGuia INT PRIMARY KEY NOT NULL,
  CgcCpf VARCHAR(14) NOT NULL,
  DtRecolhimento timestamp NOT NULL,
  Valor MONEY NOT NULL,
  StatusGuia int DEFAULT 0 NOT NULL,
  DtFunarte timestamp,
  Logon INT NOT NULL,
  CONSTRAINT Fk_GuiaRecolhimentoInteressado FOREIGN KEY (CgcCpf) REFERENCES sac.Interessado (CgcCpf)
);
CREATE INDEX indGuiaRecolhimento ON sac.GuiaRecolhimento (CgcCpf);
CREATE TABLE sac.HistoricoAspectosFinanceirosX
(
  contador INT PRIMARY KEY NOT NULL,
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  ItemDespesaPT VARCHAR(50) NOT NULL,
  ValorDespesaPT MONEY NOT NULL,
  ItemDespesaPG VARCHAR(50) NOT NULL,
  ValorDespesaPG MONEY NOT NULL,
  ChequeExtratoN INT NOT NULL,
  ValorCheque MONEY NOT NULL,
  DataCheque timestamp NOT NULL,
  NotaFiscal VARCHAR(10) NOT NULL,
  ValorNF MONEY NOT NULL,
  DataNF timestamp NOT NULL,
  Logon INT NOT NULL
);
CREATE TABLE sac.HistoricoAspectosFisicosX
(
  contador INT PRIMARY KEY NOT NULL,
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  ItensFisicos VARCHAR(50) NOT NULL,
  Proposta INT NOT NULL,
  PrCont INT NOT NULL,
  logon INT NOT NULL
);
CREATE TABLE sac.tbTipoDocumento
(
  idTipoDocumento INT PRIMARY KEY NOT NULL,
  dsTipoDocumento VARCHAR(100) NOT NULL,
  stUpload INTEGER DEFAULT 1 NOT NULL,
  stEstado INTEGER DEFAULT 1 NOT NULL
);
CREATE TABLE sac.tbTipoEncaminhamento
(
  idTipoEncaminhamento int PRIMARY KEY NOT NULL,
  dsEncaminhamento VARCHAR(100) NOT NULL
);
CREATE TABLE sac.tbTipoInabilitado
(
  idTipoInabilitado INT PRIMARY KEY NOT NULL,
  dsTipoInabilitado VARCHAR(250) NOT NULL
);
CREATE TABLE sac.tbTipoInconsistencia
(
  idTipoInconsistencia INT PRIMARY KEY NOT NULL,
  dsTipoInconsistencia CHAR(20) NOT NULL
);
CREATE TABLE sac.tbTipoReadequacao
(
  idTipoReadequacao INT PRIMARY KEY NOT NULL,
  dsReadequacao VARCHAR(50) NOT NULL,
  stReadequacao INTEGER DEFAULT 1 NOT NULL,
  stPublicacaoDou INTEGER DEFAULT 0,
  tpAtributo CHAR DEFAULT 'T',
  qtCaracteres INT DEFAULT 5
);
CREATE TABLE sac.tbTmpCaptacao
(
  idTmpCaptacao INT PRIMARY KEY NOT NULL,
  nrAnoProjeto CHAR(2),
  nrSequencial CHAR(5),
  dtChegadaRecibo timestamp,
  nrCpfCnpjProponente CHAR(14),
  nrCpfCnpjIncentivador CHAR(14),
  dtCredito timestamp,
  vlValorCredito MONEY,
  cdPatrocinio CHAR,
  nrAgenciaProponente CHAR(10),
  nrContaProponente CHAR(20),
  tpValidacao CHAR(2)
);
CREATE TABLE sac.tbTmpCaptacaoOLD
(
  idTmpCaptacao INT PRIMARY KEY NOT NULL,
  nrAnoProjeto CHAR(2),
  nrSequencial CHAR(5),
  dtChegadaRecibo timestamp,
  nrCpfCnpjProponente CHAR(14),
  nrCpfCnpjIncentivador CHAR(14),
  dtCredito timestamp,
  vlValorCredito MONEY,
  cdPatrocinio CHAR,
  nrAgenciaProponente CHAR(10),
  nrContaProponente CHAR(20),
  tpValidacao CHAR(2)
);
CREATE TABLE sac.tbtmpDepositoIdentificado
(
  idtmpDepositoIdentificado INT PRIMARY KEY NOT NULL,
  tpRegistro CHAR,
  nrsequencial CHAR(4),
  nmCliente CHAR(5),
  dtGeracao CHAR(8),
  nrReferencia CHAR(6),
  nrEspacoBrancoHeader CHAR(126),
  nrcpfcnpjProponente CHAR(14),
  nrAgenciaProponente CHAR(14),
  nrdvAgencia CHAR,
  nrContaProponente CHAR(12),
  nrdvConta CHAR,
  nrcpfcnpjPatrocinador CHAR(14),
  dtCredito CHAR(8),
  vlCredito CHAR(17),
  nrEspacoBrancoDetalhe CHAR(22),
  cdPatrocinioDoacao CHAR,
  qtregisto CHAR(8),
  nrEspacoBrancoTrailer CHAR(141),
  dsInformacao VARCHAR(150)
);
CREATE TABLE sac.tbtmpDepositoIdentificadoOLD
(
  idtmpDepositoIdentificado INT PRIMARY KEY NOT NULL,
  tpRegistro CHAR,
  nrsequencial CHAR(4),
  nmCliente CHAR(5),
  dtGeracao CHAR(8),
  nrReferencia CHAR(6),
  nrEspacoBrancoHeader CHAR(126),
  nrcpfcnpjProponente CHAR(14),
  nrAgenciaProponente CHAR(14),
  nrdvAgencia CHAR,
  nrContaProponente CHAR(12),
  nrdvConta CHAR,
  nrcpfcnpjPatrocinador CHAR(14),
  dtCredito CHAR(8),
  vlCredito CHAR(17),
  nrEspacoBrancoDetalhe CHAR(22),
  cdPatrocinioDoacao CHAR,
  qtregisto CHAR(8),
  nrEspacoBrancoTrailer CHAR(141),
  dsInformacao VARCHAR(150)
);
CREATE TABLE sac.Populacao
(
  Contador INT PRIMARY KEY NOT NULL,
  CodUfIbge INT,
  Municipio VARCHAR(100),
  Situacao_1996 INT,
  Total INT,
  Homens INT,
  Mulheres INT,
  Urbana INT,
  Rural INT,
  TxCrescimentoanual INT,
  CodCompletoUF INT
);
CREATE INDEX IX_Populacao ON sac.Populacao (Municipio);
CREATE INDEX IX_Populacao_1 ON sac.Populacao (Contador);
CREATE TABLE sac.PreProjeto
(
  idPreProjeto INT PRIMARY KEY NOT NULL,
  idAgente INT NOT NULL,
  NomeProjeto VARCHAR(300) NOT NULL,
  Mecanismo INT DEFAULT 1 NOT NULL,
  AgenciaBancaria VARCHAR(5) DEFAULT '00000',
  AreaAbrangencia INTEGER DEFAULT 0 NOT NULL,
  DtInicioDeExecucao timestamp DEFAULT NOW(),
  DtFinalDeExecucao timestamp,
  Justificativa VARCHAR,
  NrAtoTombamento VARCHAR(25) DEFAULT ' ',
  DtAtoTombamento timestamp,
  EsferaTombamento int DEFAULT 0 NOT NULL,
  ResumoDoProjeto VARCHAR DEFAULT ' ',
  Objetivos VARCHAR,
  Acessibilidade VARCHAR,
  DemocratizacaoDeAcesso VARCHAR,
  EtapaDeTrabalho VARCHAR,
  FichaTecnica VARCHAR,
  Sinopse VARCHAR,
  ImpactoAmbiental VARCHAR,
  EspecificacaoTecnica VARCHAR,
  EstrategiadeExecucao VARCHAR,
  dtAceite timestamp,
  DtArquivamento timestamp,
  stEstado INTEGER DEFAULT 1 NOT NULL,
  stDataFixa INTEGER DEFAULT 0 NOT NULL,
  stPlanoAnual INTEGER DEFAULT 0 NOT NULL,
  idUsuario INT DEFAULT 0 NOT NULL,
  stTipoDemanda CHAR(2) DEFAULT 'NA' NOT NULL,
  idEdital INT
);
CREATE SEQUENCE sac.preprojeto_idpreprojeto_seq NO MINVALUE NO MAXVALUE NO CYCLE;
ALTER TABLE sac.preprojeto ALTER COLUMN idpreprojeto SET DEFAULT nextval('sac.preprojeto_idpreprojeto_seq');
ALTER SEQUENCE sac.preprojeto_idpreprojeto_seq OWNED BY sac.preprojeto.idpreprojeto;
CREATE TABLE sac.Orgaos
(
  Codigo INT PRIMARY KEY NOT NULL,
  Sigla VARCHAR(20) NOT NULL,
  idSecretaria INT,
  Vinculo INTEGER DEFAULT 0 NOT NULL,
  Status INTEGER DEFAULT 0 NOT NULL
);
CREATE UNIQUE INDEX AK_Orgaos ON sac.Orgaos (Codigo);
CREATE TABLE sac.SequencialProjetos
(
  Ano INT PRIMARY KEY NOT NULL,
  Sequencial INT NOT NULL
);
CREATE TABLE sac.Situacao
(
  Codigo CHAR(3) PRIMARY KEY NOT NULL,
  Descricao VARCHAR(150) NOT NULL,
  AreaAtuacao CHAR,
  StatusProjeto int DEFAULT 1 NOT NULL
);
CREATE UNIQUE INDEX AK_Situacao ON sac.Situacao (Codigo);
CREATE TABLE sac.SituacaoCarta
(
  Situacao CHAR(3) NOT NULL,
  NumeroCarta VARCHAR(3) NOT NULL,
  Sequencia int NOT NULL,
  CONSTRAINT PK_SituacaoCarta PRIMARY KEY (Situacao, NumeroCarta, Sequencia)
);
CREATE TABLE sac.SuporteGravacaoFinalizacao
(
  Codigo int PRIMARY KEY NOT NULL,
  Descricao VARCHAR(50) NOT NULL
);
CREATE TABLE sac.sysdiagrams
(
  name VARCHAR NOT NULL,
  principal_id INT NOT NULL,
  diagram_id INT PRIMARY KEY NOT NULL,
  version INT,
  definition bit
);
CREATE UNIQUE INDEX UK_principal_name ON sac.sysdiagrams (principal_id, name);
CREATE TABLE sac.Tabela
(
  Codigo int PRIMARY KEY NOT NULL,
  DadoNr INT NOT NULL
);
CREATE TABLE sac.Projetos
(
  IdPRONAC INT PRIMARY KEY NOT NULL,
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  UfProjeto CHAR(2) NOT NULL,
  Area CHAR NOT NULL,
  Segmento VARCHAR(4) NOT NULL,
  Mecanismo CHAR NOT NULL,
  NomeProjeto VARCHAR(300) NOT NULL,
  Processo VARCHAR(17),
  CgcCpf VARCHAR(14) NOT NULL,
  Situacao CHAR(3) NOT NULL,
  DtProtocolo timestamp NOT NULL,
  DtAnalise timestamp NOT NULL,
  Modalidade VARCHAR(3) DEFAULT ' ',
  OrgaoOrigem INT DEFAULT 0 NOT NULL,
  Orgao INT NOT NULL,
  DtSaida timestamp DEFAULT NULL,
  DtRetorno timestamp DEFAULT NULL,
  UnidadeAnalise VARCHAR(15) DEFAULT ' ',
  Analista VARCHAR(100) DEFAULT ' ',
  DtSituacao timestamp DEFAULT NULL,
  ResumoProjeto TEXT DEFAULT ' ',
  ProvidenciaTomada VARCHAR(500) DEFAULT ' ',
  Localizacao VARCHAR(20) DEFAULT ' ',
  DtInicioExecucao timestamp DEFAULT NULL,
  DtFimExecucao timestamp DEFAULT NULL,
  SolicitadoUfir MONEY DEFAULT 0,
  SolicitadoReal MONEY DEFAULT 0,
  SolicitadoCusteioUfir MONEY DEFAULT 0,
  SolicitadoCusteioReal MONEY DEFAULT 0,
  SolicitadoCapitalUfir MONEY DEFAULT 0,
  SolicitadoCapitalReal MONEY DEFAULT 0,
  Logon INT DEFAULT NULL,
  idProjeto INT,
  CONSTRAINT FK_Projetos_Segmento FOREIGN KEY (Segmento) REFERENCES sac.Segmento (Codigo),
  CONSTRAINT FK_Segmento FOREIGN KEY (Segmento) REFERENCES sac.Segmento (Codigo),
  CONSTRAINT FK_Proponente FOREIGN KEY (CgcCpf) REFERENCES sac.Interessado (CgcCpf),
  CONSTRAINT FK_Situacao FOREIGN KEY (Situacao) REFERENCES sac.Situacao (Codigo),
  CONSTRAINT FK_Orgao FOREIGN KEY (Orgao) REFERENCES sac.Orgaos (Codigo),
  CONSTRAINT FK_Projetos_preProjetos FOREIGN KEY (idProjeto) REFERENCES sac.PreProjeto (idPreProjeto)
);
CREATE UNIQUE INDEX AK_Projetos ON sac.Projetos (AnoProjeto, Sequencial);
CREATE INDEX indArea ON sac.Projetos (Area);
CREATE INDEX indCgcCpf ON sac.Projetos (CgcCpf);
CREATE INDEX indNomeProjeto ON sac.Projetos (NomeProjeto);
CREATE INDEX indOrgao ON sac.Projetos (Orgao);
CREATE INDEX indProcesso ON sac.Projetos (Processo);
CREATE INDEX indSegmento ON sac.Projetos (Segmento);
CREATE INDEX indSituacao ON sac.Projetos (Situacao);
CREATE INDEX indUF ON sac.Projetos (UfProjeto);
CREATE INDEX IX_Projetos ON sac.Projetos (idProjeto);
CREATE INDEX _dta_index_Projetos_6_1181403428__K1_K11_K6_K5_K10_2_3_8_25_26_28 ON sac.Projetos (IdPRONAC, Situacao, Segmento, Area, CgcCpf, AnoProjeto, Sequencial, NomeProjeto, DtInicioExecucao, DtFimExecucao, SolicitadoReal);

CREATE TABLE sac.tbDocumentosPreProjeto20100107X
(
  idDocumentosPreprojetos INT NOT NULL,
  CodigoDocumento INT NOT NULL,
  idProjeto INT NOT NULL,
  idPRONAC INT,
  Data timestamp NOT NULL,
  imDocumento VARCHAR NOT NULL,
  NoArquivo VARCHAR(100) NOT NULL,
  TaArquivo INT NOT NULL
);
CREATE TABLE sac.tbFiscalizacao
(
  idFiscalizacao INT PRIMARY KEY NOT NULL,
  IdPRONAC INT NOT NULL,
  dtInicioFiscalizacaoProjeto timestamp NOT NULL,
  dtFimFiscalizacaoProjeto timestamp NOT NULL,
  dtRespostaSolicitada timestamp,
  dsFiscalizacaoProjeto VARCHAR(8000) NOT NULL,
  tpDemandante CHAR NOT NULL,
  stFiscalizacaoProjeto CHAR NOT NULL,
  idAgente INT,
  idSolicitante INT NOT NULL,
  idUsuarioInterno SMALLINT,
  CONSTRAINT fk_tbFiscalizacao_Projetos FOREIGN KEY (IdPRONAC) REFERENCES sac.Projetos (IdPRONAC)
);

CREATE TABLE sac.tbRelatorioFiscalizacao
(
  idRelatorioFiscalizacao INT PRIMARY KEY NOT NULL,
  dsAcoesProgramadas VARCHAR(8000) NOT NULL,
  dsAcoesExecutadas VARCHAR(8000) NOT NULL,
  dsBeneficioAlcancado VARCHAR(8000) NOT NULL,
  dsDificuldadeEncontrada VARCHAR(8000) NOT NULL,
  stSiafi CHAR NOT NULL,
  stPrestacaoContas CHAR NOT NULL,
  stCumpridasNormas CHAR NOT NULL,
  stCumpridoPrazo CHAR NOT NULL,
  stApuracaoUFiscalizacao CHAR NOT NULL,
  stComprovacaoUtilizacaoRecursos CHAR NOT NULL,
  stCompatibilidadeDesembolsoEvolucao CHAR NOT NULL,
  stOcorreuDespesas CHAR NOT NULL,
  stPagamentoServidorPublico CHAR NOT NULL,
  stDespesaAdministracao CHAR NOT NULL,
  stTransferenciaRecurso CHAR NOT NULL,
  stDespesasPublicidade CHAR NOT NULL,
  stOcorreuAditamento CHAR NOT NULL,
  stAplicadosRecursos CHAR NOT NULL,
  stAplicacaoRecursosFinalidade CHAR NOT NULL,
  stSaldoAposEncerramento CHAR NOT NULL,
  stSaldoVerificacaoFNC CHAR NOT NULL,
  stProcessoDocumentado CHAR NOT NULL,
  stDocumentacaoCompleta CHAR NOT NULL,
  stConformidadeExecucao CHAR NOT NULL,
  stIdentificaProjeto CHAR NOT NULL,
  stDespesaAnterior CHAR NOT NULL,
  stDespesaPosterior CHAR NOT NULL,
  stDespesaCoincidem CHAR NOT NULL,
  stDespesaRelacionada CHAR NOT NULL,
  stComprovanteFiscal CHAR NOT NULL,
  stCienciaLegislativo CHAR NOT NULL,
  stExigenciaLegal CHAR NOT NULL,
  stMaterialInformativo CHAR NOT NULL,
  stFinalidadeEsperada CHAR NOT NULL,
  stPlanoTrabalho CHAR NOT NULL,
  stExecucaoAprovado CHAR NOT NULL,
  qtEmpregoDireto INT NOT NULL,
  qtEmpregoIndireto INT NOT NULL,
  dsEvidencia VARCHAR(8000) NOT NULL,
  dsRecomendacaoEquipe VARCHAR(8000) NOT NULL,
  dsConclusaoEquipe VARCHAR(8000) NOT NULL,
  dsParecerTecnico VARCHAR(8000) NOT NULL,
  stAvaliacao CHAR NOT NULL,
  idFiscalizacao INT,
  dsJustificativaDevolucao VARCHAR(8000),
  stRecursosCaptados CHAR DEFAULT 3,
  dsObservacao VARCHAR(5000) DEFAULT NULL,
  CONSTRAINT FK_tbRelatorioFiscalizacao_tbFiscalizacao FOREIGN KEY (idFiscalizacao) REFERENCES sac.tbFiscalizacao (idFiscalizacao)
);
CREATE TABLE sac.tbAnaliseDeConteudo
(
  idAnaliseDeConteudo INT PRIMARY KEY NOT NULL,
  idPronac INT NOT NULL,
  idProduto int NOT NULL,
  Lei8313 INTEGER DEFAULT 0,
  Artigo3 INTEGER DEFAULT 0,
  IncisoArtigo3 int,
  AlineaArtigo3 VARCHAR(50) DEFAULT '',
  Artigo18 INTEGER DEFAULT 0,
  AlineaArtigo18 VARCHAR(50) DEFAULT '',
  Artigo26 INTEGER DEFAULT 0,
  Lei5761 INTEGER DEFAULT 0,
  Artigo27 INTEGER DEFAULT 0,
  IncisoArtigo27_I INTEGER DEFAULT 0,
  IncisoArtigo27_II INTEGER DEFAULT 0,
  IncisoArtigo27_III INTEGER DEFAULT 0,
  IncisoArtigo27_IV INTEGER DEFAULT 0,
  TipoParecer int,
  ParecerFavoravel INTEGER DEFAULT 0,
  ParecerDeConteudo VARCHAR DEFAULT '',
  idParecer INT,
  idUsuario INT DEFAULT 0,
  CONSTRAINT FK_tbAnaliseDeConteudo_Projetos FOREIGN KEY (idPronac) REFERENCES sac.Projetos (IdPRONAC)
);
CREATE TABLE sac.tbDistribuicaoProduto
(
  idDistribuicaoProduto INT PRIMARY KEY NOT NULL,
  idPlanoDistribuicao INT NOT NULL,
  qtDistribuicao INT NOT NULL,
  idDocumento INT,
  dsDestinacaoProduto TEXT,
  stFinsLucrativos CHAR DEFAULT '((1,2))' NOT NULL,
  dsReceptorProduto TEXT,
  dsTamanhoDuracao VARCHAR(100),
  stPlanoDistribuicao CHAR DEFAULT 0 NOT NULL,
  stDocumento CHAR DEFAULT 0 NOT NULL,
  stDestinacaoProduto CHAR DEFAULT 0 NOT NULL,
  siFinsLucrativos CHAR DEFAULT 0 NOT NULL,
  stReceptorProduto CHAR DEFAULT 0 NOT NULL,
  dsJustificativaAcompanhamento TEXT--,
--   CONSTRAINT fk_tbDistribuicaoProduto_PlanoDistribuicaoProduto FOREIGN KEY (idPlanoDistribuicao) REFERENCES sac.PlanoDistribuicaoProduto (idPlanoDistribuicao)
);
CREATE TABLE sac.tbRelatorio
(
  idRelatorio INT PRIMARY KEY NOT NULL,
  idPRONAC INT NOT NULL,
  idAgenteProponente INT NOT NULL,
  idAgenteAvaliador INT,
  idDistribuicaoProduto INT,
  tpRelatorio CHAR,
  CONSTRAINT fk_tbRelatorio_Projetos FOREIGN KEY (idPRONAC) REFERENCES sac.Projetos (IdPRONAC),
  CONSTRAINT FK_tbRelatorio_tbDistribuicaoProduto FOREIGN KEY (idDistribuicaoProduto) REFERENCES sac.tbDistribuicaoProduto (idDistribuicaoProduto)
);

CREATE TABLE sac.tbRelatorioConsolidado
(
  idRelatorioConsolidado INT PRIMARY KEY NOT NULL,
  idRelatorio INT NOT NULL,
  vlLeiIncentivoFiscal NUMERIC(9,2) DEFAULT 0.00 NOT NULL,
  vlLeiIncentivoEstadual NUMERIC(9,2) NOT NULL,
  vlLeiIncentivoMunicipal NUMERIC(9,2) DEFAULT 0 NOT NULL,
  vlRecursosProprios NUMERIC(9,2) DEFAULT 0 NOT NULL,
  vlRendimentoFinanceiro NUMERIC(9,2) DEFAULT 0 NOT NULL,
  dsProduto VARCHAR(100),
  idImovel INT NOT NULL,
  stPrevisaoProjeto CHAR DEFAULT 1,
  idDocumento INT,
  stRelatorioConsolidado CHAR NOT NULL,
  dtCadastro timestamp,
  stObjetivosMetas CHAR DEFAULT 0,
  stEstrategiaAcao CHAR DEFAULT 0 NOT NULL,
  stCronogramaFisico CHAR DEFAULT 0 NOT NULL,
  stLeiIncentivoFiscal CHAR DEFAULT 0 NOT NULL,
  stLeiIncentivoEstadual CHAR DEFAULT 0 NOT NULL,
  stLeiIncentivoMunicipal CHAR DEFAULT 0 NOT NULL,
  stRecursosProprios CHAR DEFAULT 0 NOT NULL,
  stRendimentoFinanceiro CHAR DEFAULT 0 NOT NULL,
  stDocumento CHAR DEFAULT 0 NOT NULL,
  tpPrevisaoProjeto CHAR DEFAULT 0 NOT NULL,
  stTermoProjeto CHAR DEFAULT 0 NOT NULL,
  stProduto CHAR DEFAULT 0 NOT NULL,
  stRepercussao CHAR DEFAULT 0 NOT NULL,
  stImpactoAmbiental CHAR DEFAULT 0 NOT NULL,
  stImpactoCultural CHAR DEFAULT 0 NOT NULL,
  stImpactoEconomico CHAR DEFAULT 0 NOT NULL,
  stImpactoSocial CHAR DEFAULT 0 NOT NULL,
  CONSTRAINT FK_tbRelatorioConsolidado_tbRelatorio FOREIGN KEY (idRelatorio) REFERENCES sac.tbRelatorio (idRelatorio),
  CONSTRAINT FK_tbRelatorioConsolidado_tbImovel FOREIGN KEY (idImovel) REFERENCES sac.tbImovel (idImovel)
);
CREATE TABLE sac.ParecerVinculadasantX
(
  idParecerVinculadas INT PRIMARY KEY NOT NULL,
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  TipoParecer int NOT NULL,
  DtParecer timestamp NOT NULL,
  Lei8313 INTEGER DEFAULT 0 NOT NULL,
  Art8313 VARCHAR(25) NOT NULL,
  Paragrafo8313 VARCHAR(25) NOT NULL,
  Inciso8313 VARCHAR(25) NOT NULL,
  Alinea8313 VARCHAR(25) NOT NULL,
  Decreto1494 INTEGER DEFAULT 0 NOT NULL,
  Art1494 VARCHAR(25) NOT NULL,
  Paragrafo1494 VARCHAR(25) NOT NULL,
  Inciso1494 VARCHAR(25) NOT NULL,
  Alinea1494 VARCHAR(25) NOT NULL,
  Lei9874 INTEGER DEFAULT 1 NOT NULL,
  Art9874 VARCHAR(25) NOT NULL,
  Paragrafo9874 VARCHAR(25) NOT NULL,
  Inciso9874 VARCHAR(25) NOT NULL,
  Alinea9874 VARCHAR(25) NOT NULL,
  FormulacaoA int DEFAULT 1 NOT NULL,
  FormulacaoB int DEFAULT 1 NOT NULL,
  FormulacaoC int DEFAULT 1 NOT NULL,
  ConteudoA int DEFAULT 1 NOT NULL,
  ConteudoB int DEFAULT 1 NOT NULL,
  ConteudoC int DEFAULT 1 NOT NULL,
  ConteudoD int DEFAULT 1 NOT NULL,
  ConteudoE int DEFAULT 1 NOT NULL,
  ParecerFavoravel CHAR DEFAULT '0' NOT NULL,
  Parecerista VARCHAR(30) NOT NULL,
  Justificativa TEXT NOT NULL,
  CustoMercado INTEGER DEFAULT 0 NOT NULL,
  ValorA MONEY DEFAULT 0 NOT NULL,
  ValorB MONEY DEFAULT 0 NOT NULL,
  ValorC MONEY DEFAULT 0 NOT NULL,
  ValorA1 MONEY DEFAULT 0 NOT NULL,
  ValorB1 MONEY DEFAULT 0 NOT NULL,
  ValorC1 MONEY DEFAULT 0 NOT NULL,
  ValorA2 MONEY DEFAULT 0 NOT NULL,
  ValorB2 MONEY DEFAULT 0 NOT NULL,
  ValorC2 MONEY DEFAULT 0 NOT NULL,
  Prioridade int DEFAULT 0 NOT NULL,
  CriteriosAdotados TEXT NOT NULL,
  Enviar int DEFAULT 0 NOT NULL,
  SinteseProjeto TEXT NOT NULL,
  Logon INT DEFAULT 0 NOT NULL,
  CONSTRAINT FK_ParecerVinculadas_Projetos FOREIGN KEY (AnoProjeto, Sequencial) REFERENCES sac.Projetos (AnoProjeto, Sequencial)
);
CREATE INDEX IX_ParecerVinculadas ON sac.ParecerVinculadasantX (AnoProjeto, Sequencial);
CREATE TABLE sac.ProcessoMassificado
(
  Processo CHAR(5) PRIMARY KEY NOT NULL,
  MCI CHAR(9) NOT NULL,
  SequencialRemessa INT NOT NULL
);
CREATE TABLE sac.Produto
(
  Codigo int PRIMARY KEY NOT NULL,
  Descricao VARCHAR(100) NOT NULL,
  Area CHAR NOT NULL,
  Sintese TEXT,
  Idorgao INT,
  stEstado INTEGER
);
CREATE TABLE sac.ProgramaTrabalho
(
  Codigo CHAR(17) PRIMARY KEY NOT NULL,
  Descricao VARCHAR(100) NOT NULL,
  Logon INT NOT NULL
);
CREATE TABLE sac.ProjetoProduto
(
  Contador INT PRIMARY KEY NOT NULL,
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  CodigoProduto int NOT NULL,
  Opcao int DEFAULT 2 NOT NULL,
  NomeProduto VARCHAR(150) NOT NULL,
  QtdeProduzida INT DEFAULT 0 NOT NULL,
  QtdeRecebida INT DEFAULT 0 NOT NULL,
  QtdeExistente INT DEFAULT 0 NOT NULL,
  Localizacao int DEFAULT 9 NOT NULL,
  Logon INT NOT NULL,
  CONSTRAINT FK_ProjetoProduto_Projetos FOREIGN KEY (AnoProjeto, Sequencial) REFERENCES sac.Projetos (AnoProjeto, Sequencial),
  CONSTRAINT FK_ProjetoProduto_Produto FOREIGN KEY (CodigoProduto) REFERENCES sac.Produto (Codigo)
);
CREATE INDEX IX_ProjetoProduto ON sac.ProjetoProduto (AnoProjeto, Sequencial);
CREATE TABLE sac.ProjetosPontosCulturaX
(
  Processo VARCHAR(255),
  Pronac VARCHAR(255)
);


CREATE TABLE sac.dtproperties
(
  id INT NOT NULL,
  objectid INT,
  property VARCHAR(64) NOT NULL,
  value VARCHAR(255),
  uvalue VARCHAR(255),
  lvalue varchar,
  version INT DEFAULT 0 NOT NULL,
  CONSTRAINT pk_dtproperties PRIMARY KEY (id, property)
);
CREATE TABLE sac.Edital
(
  idEdital INT PRIMARY KEY NOT NULL,
  idOrgao INT DEFAULT 0 NOT NULL,
  NrEdital int,
  DtEdital timestamp NOT NULL,
  CelulaOrcamentaria VARCHAR(30),
  Objeto VARCHAR NOT NULL,
  Logon INT NOT NULL,
  qtAvaliador int DEFAULT 0 NOT NULL,
  stDistribuicao CHAR DEFAULT 'M' NOT NULL,
  stAdmissibilidade CHAR DEFAULT 'S' NOT NULL,
  cdTipoFundo INT DEFAULT 161 NOT NULL,
  idAti INT,
  idLinguagem INT,
  idModalidade INT,
  CONSTRAINT FK_Edital_Orgaos FOREIGN KEY (idOrgao) REFERENCES sac.Orgaos (Codigo)
);
CREATE TABLE sac.EditalProjeto
(
  idEditalProjeto INT PRIMARY KEY NOT NULL,
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  idEdital INT NOT NULL,
  Logon INT NOT NULL,
  CONSTRAINT FK_EditalProjeto_Projetos FOREIGN KEY (AnoProjeto, Sequencial) REFERENCES sac.Projetos (AnoProjeto, Sequencial),
  CONSTRAINT FK_EditalProjeto_Edital FOREIGN KEY (idEdital) REFERENCES sac.Edital (idEdital)
);
CREATE INDEX IX_EditalProjeto ON sac.EditalProjeto (AnoProjeto, Sequencial);
CREATE INDEX IX_EditalProjeto_1 ON sac.EditalProjeto (idEdital);

CREATE TABLE sac.EditalParcelas
(
  idEdital INT NOT NULL,
  NrParcela int NOT NULL,
  Faixa int DEFAULT 0,
  DiasParaLiberar INT DEFAULT 0,
  Valor MONEY DEFAULT 0 NOT NULL,
  ExigePC INTEGER DEFAULT 0 NOT NULL,
  Parcela int DEFAULT 0,
  Logon INT DEFAULT 0 NOT NULL,
  CONSTRAINT PK_EditalParcelas PRIMARY KEY (idEdital, NrParcela),
  CONSTRAINT FK_EditalParcelas_Edital FOREIGN KEY (idEdital) REFERENCES sac.Edital (idEdital)
);
CREATE INDEX IX_ParcelasDoEdital ON sac.EditalParcelas (idEdital);
CREATE TABLE sac.EditalRelatorio
(
  idRelatorio INT PRIMARY KEY NOT NULL,
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  idEdital INT NOT NULL,
  NrParcela int NOT NULL,
  DtRelatorio timestamp NOT NULL,
  Relatorio VARCHAR NOT NULL,
  Comprovado INTEGER DEFAULT 0 NOT NULL,
  Aprovado INTEGER DEFAULT 0 NOT NULL,
  Logon INT NOT NULL,
  CONSTRAINT FK_EditalRelatorio_Projetos FOREIGN KEY (AnoProjeto, Sequencial) REFERENCES sac.Projetos (AnoProjeto, Sequencial),
  CONSTRAINT FK_EditalRelatorio_EditalParcelas FOREIGN KEY (idEdital, NrParcela) REFERENCES sac.EditalParcelas (idEdital, NrParcela),
  CONSTRAINT FK_EditalRelatorio_Edital FOREIGN KEY (idEdital) REFERENCES sac.Edital (idEdital)
);
CREATE INDEX IX_EditalRelatorio ON sac.EditalRelatorio (AnoProjeto, Sequencial);
CREATE INDEX IX_EditalRelatorio_1 ON sac.EditalRelatorio (idEdital);
CREATE INDEX IX_EditalRelatorio_2 ON sac.EditalRelatorio (idEdital, NrParcela);
CREATE TABLE sac.Passagem
(
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  Evento VARCHAR(100) NOT NULL,
  EntidadePromotora VARCHAR(100) NOT NULL,
  Cidade VARCHAR(50) NOT NULL,
  Pais VARCHAR(30) NOT NULL,
  Trecho VARCHAR(100) NOT NULL,
  DtInicio timestamp,
  DtFinal timestamp,
  DtConcessao timestamp,
  QtePassagem INT DEFAULT 0 NOT NULL,
  NomeGrupo VARCHAR(100) NOT NULL,
  Repercussao int DEFAULT 0 NOT NULL,
  Realizacao int DEFAULT 0 NOT NULL,
  ValorNormalR MONEY DEFAULT 0 NOT NULL,
  ValorNormalUs MONEY DEFAULT 0 NOT NULL,
  ValorPromocionalR MONEY DEFAULT 0 NOT NULL,
  ValorPromocionalUs MONEY DEFAULT 0 NOT NULL,
  Documental0 INTEGER DEFAULT 0 NOT NULL,
  Documental1 INTEGER DEFAULT 0 NOT NULL,
  Documental2 INTEGER DEFAULT 0 NOT NULL,
  Documental3 INTEGER DEFAULT 0 NOT NULL,
  Documental4 INTEGER DEFAULT 0 NOT NULL,
  Documental5 INTEGER DEFAULT 0 NOT NULL,
  Documental6 INTEGER DEFAULT 0 NOT NULL,
  Documental7 INTEGER DEFAULT 0 NOT NULL,
  Documental8 INTEGER DEFAULT 0 NOT NULL,
  Proposta0 INTEGER DEFAULT 0 NOT NULL,
  Proposta1 INTEGER DEFAULT 0 NOT NULL,
  Proposta2 INTEGER DEFAULT 0 NOT NULL,
  Proposta3 INTEGER NOT NULL,
  Proposta4 INTEGER DEFAULT 0 NOT NULL,
  Proposta5 INTEGER DEFAULT 0 NOT NULL,
  Proposta6 INTEGER DEFAULT 0 NOT NULL,
  Proposta7 INTEGER DEFAULT 0 NOT NULL,
  ResumoCurriculo TEXT NOT NULL,
  ResumoParecer TEXT NOT NULL,
  Logon INT NOT NULL,
  CONSTRAINT PK_Passagem PRIMARY KEY (AnoProjeto, Sequencial),
  CONSTRAINT FK_PassagemProjetos FOREIGN KEY (AnoProjeto, Sequencial) REFERENCES sac.Projetos (AnoProjeto, Sequencial)
);
CREATE TABLE sac.PedidoProrrogacao
(
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  DtPedido timestamp NOT NULL,
  DtInicio timestamp NOT NULL,
  DtFinal timestamp NOT NULL,
  CONSTRAINT PK_PedidoProrrogacao PRIMARY KEY (AnoProjeto, Sequencial)
);
CREATE TABLE sac.PerfilAgenciaX
(
  Agencia VARCHAR(5),
  Nome VARCHAR(30),
  Exclusiva VARCHAR(1),
  Perfil CHAR
);
CREATE TABLE sac.PlanilhaEtapa
(
  idPlanilhaEtapa INT PRIMARY KEY NOT NULL,
  Descricao VARCHAR(50) NOT NULL
);
CREATE TABLE sac.PlanilhaUnidade
(
  idUnidade INT PRIMARY KEY NOT NULL,
  Sigla VARCHAR(20) NOT NULL,
  Descricao VARCHAR(50) NOT NULL
);
CREATE TABLE sac.Prorrogacao
(
  idProrrogacao INT PRIMARY KEY NOT NULL,
  idPronac INT,
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  DtPedido timestamp NOT NULL,
  DtInicio timestamp NOT NULL,
  DtFinal timestamp NOT NULL,
  Diligenciado CHAR DEFAULT 'N' NOT NULL,
  DtVencDiligencia timestamp,
  Observacao VARCHAR(250) NOT NULL,
  Atendimento CHAR DEFAULT 'A' NOT NULL,
  Restricao INTEGER DEFAULT 0 NOT NULL,
  idRecurso INT,
  Logon INT NOT NULL,
  idDocumento INT DEFAULT 0 NOT NULL,
  CONSTRAINT fk_Prorrogacao_02 FOREIGN KEY (idPronac) REFERENCES sac.Projetos (IdPRONAC),
  CONSTRAINT fk_Prorrogacao_01 FOREIGN KEY (AnoProjeto, Sequencial) REFERENCES sac.Projetos (AnoProjeto, Sequencial)
);
CREATE UNIQUE INDEX AK_Prorrogacao ON sac.Prorrogacao (AnoProjeto, Sequencial, DtPedido);
CREATE TABLE sac.PtRes
(
  Codigo CHAR(6) PRIMARY KEY NOT NULL,
  Descricao VARCHAR(100) NOT NULL,
  Logon INT NOT NULL
);

CREATE TABLE sac.tbProposta
(
  IdProposta INT PRIMARY KEY NOT NULL,
  tpProposta CHAR(2) NOT NULL,
  dtProposta timestamp NOT NULL,
  nmProjeto VARCHAR(300),
  cdMecanismo INT,
  nrAgenciaBancaria VARCHAR(5),
  stAreaAbrangencia INTEGER,
  dtInicioExecucao timestamp,
  dtFimExecucao timestamp,
  nrAtoTombamento VARCHAR(25),
  dtAtoTombamento timestamp,
  cdEsferaTombamento int,
  dsResumoProjeto VARCHAR(1000),
  dsObjetivos VARCHAR(8000),
  dsJustificativa VARCHAR(8000),
  dsAcessibilidade VARCHAR(8000),
  dsDemocratizacaoAcesso VARCHAR(8000),
  dsEtapaTrabalho VARCHAR(8000),
  dsFichaTecnica VARCHAR(8000),
  dsSinopse VARCHAR(8000),
  dsImpactoAmbiental VARCHAR(8000),
  dsEspecificacaoTecnica VARCHAR(8000),
  dsEstrategiaExecucao VARCHAR(8000),
  dtAceite timestamp,
  dtArquivamento timestamp,
  stEstado INTEGER,
  stDataFixa INTEGER,
  stPlanoAnual INTEGER,
  stTipoDemanda CHAR(2),
  idPedidoAlteracao INT NOT NULL
);
CREATE TABLE sac.tbReadequacao
(
  idReadequacao INT PRIMARY KEY NOT NULL,
  idPronac INT NOT NULL,
  idTipoReadequacao INT NOT NULL,
  dtSolicitacao timestamp NOT NULL,
  idSolicitante INT NOT NULL,
  dsJustificativa VARCHAR(8000) NOT NULL,
  dsSolicitacao VARCHAR,
  idDocumento INT,
  idAvaliador INT,
  dtAvaliador timestamp,
  dsAvaliacao VARCHAR(8000),
  stAtendimento CHAR DEFAULT 'N' NOT NULL,
  siEncaminhamento int DEFAULT 1 NOT NULL,
  stAnalise CHAR(2),
  idNrReuniao INT,
  stEstado INTEGER DEFAULT 0 NOT NULL,
  CONSTRAINT FK_tbReadequacao_Projetos FOREIGN KEY (idPronac) REFERENCES sac.Projetos (IdPRONAC),
  CONSTRAINT FK_tbReadequacao_tbTipoReadequacao FOREIGN KEY (idTipoReadequacao) REFERENCES sac.tbTipoReadequacao (idTipoReadequacao),
  CONSTRAINT FK_tbReadequacao_tbTipoEncaminhamento FOREIGN KEY (siEncaminhamento) REFERENCES sac.tbTipoEncaminhamento (idTipoEncaminhamento)
);
CREATE TABLE sac.tbPlanoDistribuicao
(
  idPlanoDistribuicao INT PRIMARY KEY NOT NULL,
  idReadequacao INT,
  idProduto int,
  cdArea VARCHAR(4),
  cdSegmento VARCHAR(4),
  idPosicaoLogo INT NOT NULL,
  qtProduzida INT NOT NULL,
  qtPatrocinador INT NOT NULL,
  qtProponente INT NOT NULL,
  qtOutros INT NOT NULL,
  qtVendaNormal INT NOT NULL,
  qtVendaPromocional INT NOT NULL,
  vlUnitarioNormal NUMERIC(11,2) NOT NULL,
  vlUnitarioPromocional NUMERIC(11,2) NOT NULL,
  stPrincipal INTEGER,
  tpSolicitacao CHAR NOT NULL,
  tpAnaliseTecnica CHAR DEFAULT 'N' NOT NULL,
  tpAnaliseComissao CHAR DEFAULT 'N' NOT NULL,
  stAtivo CHAR DEFAULT 'S' NOT NULL,
  idPronac INT NOT NULL,
  CONSTRAINT FK_tbPlanoDistribuicao_tbReadequacao FOREIGN KEY (idReadequacao) REFERENCES sac.tbReadequacao (idReadequacao),
  CONSTRAINT tbPlanoDistribuicao_Produto_FK1 FOREIGN KEY (idProduto) REFERENCES sac.Produto (Codigo),
  CONSTRAINT tbPlanoDistribuicao_Area_FK FOREIGN KEY (cdArea) REFERENCES sac.Area (Codigo),
  CONSTRAINT tbPlanoDistribuicao_Segmento_FK FOREIGN KEY (cdSegmento) REFERENCES sac.Segmento (Codigo),
  CONSTRAINT tbPlanoDistribuicao_Verificacao_FK FOREIGN KEY (idPosicaoLogo) REFERENCES sac.Verificacao (idVerificacao)
);
CREATE TABLE sac.tbPlanoDivulgacao
(
  idPlanoDivulgacao INT PRIMARY KEY NOT NULL,
  idReadequacao INT,
  idPeca INT NOT NULL,
  idVeiculo INT NOT NULL,
  tpSolicitacao CHAR NOT NULL,
  tpAnaliseTecnica CHAR DEFAULT 'N' NOT NULL,
  tpAnaliseComissao CHAR DEFAULT 'N' NOT NULL,
  stAtivo CHAR DEFAULT 'S' NOT NULL,
  idPronac INT NOT NULL,
  CONSTRAINT FK_tbPlanoDivulgacao_tbReadequacao FOREIGN KEY (idReadequacao) REFERENCES sac.tbReadequacao (idReadequacao)
);

CREATE TABLE sac.tbRecurso
(
  idRecurso INT PRIMARY KEY NOT NULL,
  IdPRONAC INT NOT NULL,
  dtSolicitacaoRecurso timestamp NOT NULL,
  dsSolicitacaoRecurso VARCHAR NOT NULL,
  idAgenteSolicitante INT NOT NULL,
  dtAvaliacao timestamp,
  dsAvaliacao VARCHAR,
  tpRecurso CHAR DEFAULT '1.Pedido de reconsideração;2.Recurso' NOT NULL,
  tpSolicitacao CHAR(2) NOT NULL,
  idAgenteAvaliador INT,
  stAtendimento CHAR DEFAULT 'N' NOT NULL,
  siFaseProjeto CHAR DEFAULT 2,
  siRecurso CHAR(2) DEFAULT 1 NOT NULL,
  stAnalise CHAR(2),
  idNrReuniao INT,
  stEstado INTEGER DEFAULT 0 NOT NULL,
  CONSTRAINT fktbRecurso_Projetos FOREIGN KEY (IdPRONAC) REFERENCES sac.Projetos (IdPRONAC)
);
CREATE INDEX IX_tbRecurso ON sac.tbRecurso (IdPRONAC);
CREATE TABLE sac.AberturaDeContaBancaria
(
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  ContaBloqueada INTEGER DEFAULT 0 NOT NULL,
  DtContaBloqueada timestamp,
  ContaLivre INTEGER DEFAULT 0 NOT NULL,
  DtContaLivre timestamp,
  DtNascimento timestamp,
  Orgao INT DEFAULT 0 NOT NULL,
  Logon INT NOT NULL,
  idAberturaDeConta INT,
  CONSTRAINT PK_AberturaDeContaBancaria PRIMARY KEY (AnoProjeto, Sequencial),
  CONSTRAINT FK_AberturaDeContaBancaria_Projetos FOREIGN KEY (AnoProjeto, Sequencial) REFERENCES sac.Projetos (AnoProjeto, Sequencial)
);
CREATE TABLE sac.AberturaDeContaSav39215
(
  informacao CHAR(150)
);
CREATE INDEX IX_AberturaDeContaSav39215 ON sac.AberturaDeContaSav39215 (informacao);
CREATE TABLE sac.AberturaDeContaSav39224
(
  informacao CHAR(150)
);
CREATE INDEX IX_AberturaDeContaSav39224 ON sac.AberturaDeContaSav39224 (informacao);
CREATE TABLE sac.AberturaDeContaSav39225
(
  informacao CHAR(150)
);
CREATE INDEX IX_AberturaDeContaSav39225 ON sac.AberturaDeContaSav39225 (informacao);
CREATE TABLE sac.AberturaDeContaSav39227
(
  informacao CHAR(150)
);
CREATE INDEX IX_AberturaDeContaSav39227 ON sac.AberturaDeContaSav39227 (informacao);
CREATE TABLE sac.AberturaDeContaSeficPF40979
(
  Informacao CHAR(150)
);
CREATE INDEX IX_AberturaDeContaSeficPF40979 ON sac.AberturaDeContaSeficPF40979 (Informacao);
CREATE TABLE sac.AberturaDeContaSeficPF40980
(
  Informacao CHAR(150)
);
CREATE INDEX IX_AberturaDeContaSeficPF40980 ON sac.AberturaDeContaSeficPF40980 (Informacao);
CREATE TABLE sac.AberturaDeContaSeficPJ40975
(
  Informacao CHAR(150)
);
CREATE INDEX IX_AberturaDeContaSeficPJ40975 ON sac.AberturaDeContaSeficPJ40975 (Informacao);
CREATE TABLE sac.AberturaDeContaSeficPJ40981
(
  Informacao CHAR(150)
);
CREATE INDEX IX_AberturaDeContaSeficPJ40981 ON sac.AberturaDeContaSeficPJ40981 (Informacao);
CREATE TABLE sac.Abrangencia
(
  idAbrangencia INT PRIMARY KEY NOT NULL,
  idProjeto INT NOT NULL,
  idPais INT DEFAULT 0 NOT NULL,
  idUF INT DEFAULT 0 NOT NULL,
  idMunicipioIBGE INT DEFAULT 0 NOT NULL,
  Usuario INT DEFAULT 0 NOT NULL,
  stAbrangencia INTEGER DEFAULT 1,
  siAbrangencia CHAR DEFAULT 0 NOT NULL,
  dsJustificativa VARCHAR(500),
  dtInicioRealizacao timestamp,
  dtFimRealizacao timestamp,
  CONSTRAINT FK_Abrangencia_PreProjeto FOREIGN KEY (idProjeto) REFERENCES sac.PreProjeto (idPreProjeto)
);
CREATE SEQUENCE sac.abrangencia_idabrangencia_seq NO MINVALUE NO MAXVALUE NO CYCLE;
ALTER TABLE sac.abrangencia ALTER COLUMN idabrangencia SET DEFAULT nextval('sac.abrangencia_idabrangencia_seq');
ALTER SEQUENCE sac.abrangencia_idabrangencia_seq OWNED BY sac.abrangencia.idabrangencia;
CREATE TABLE sac.AcaoProduto
(
  Entidade int NOT NULL,
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  CodigoProduto int NOT NULL,
  Opcao INTEGER DEFAULT 0 NOT NULL,
  NomeProduto VARCHAR(255) NOT NULL,
  QtdeProduzida INT DEFAULT 0 NOT NULL,
  QtdeRecebida INT DEFAULT 0 NOT NULL,
  QtdeExistente INT DEFAULT 0 NOT NULL,
  Localizacao INT DEFAULT 0 NOT NULL
);
CREATE INDEX IX_AcaoProduto ON sac.AcaoProduto (Entidade, AnoProjeto, Sequencial);
CREATE TABLE sac.AcaoProjeto
(
  Entidade int NOT NULL,
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  Tipo CHAR NOT NULL,
  Nome VARCHAR(250),
  Municipio VARCHAR(50),
  UF VARCHAR(2),
  Area CHAR,
  Segmento CHAR(2),
  DtCriacao timestamp,
  DtAprovacao timestamp,
  Sumario TEXT,
  Mecanismo CHAR,
  DescEntidade VARCHAR(100),
  CONSTRAINT PK_AcaoProjeto PRIMARY KEY (Entidade, AnoProjeto, Sequencial, Tipo)
);
CREATE TABLE sac.AgenciasBB
(
  AGENCIA CHAR(5),
  NOME VARCHAR(50),
  UF CHAR(2),
  PERFIL int,
  LOGRADOURO_COMPLETO VARCHAR(50),
  BAIRRO VARCHAR(50),
  MUNICIPIO VARCHAR(50)
);
CREATE TABLE sac.Aprovacao
(
  idAprovacao INT PRIMARY KEY NOT NULL,
  IdPRONAC INT,
  idParecer INT,
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  TipoAprovacao CHAR NOT NULL,
  DtAprovacao timestamp NOT NULL,
  ResumoAprovacao TEXT,
  PortariaAprovacao VARCHAR(10) DEFAULT ' ',
  DtPortariaAprovacao timestamp,
  DtPublicacaoAprovacao timestamp,
  DtInicioCaptacao timestamp,
  DtFimCaptacao timestamp,
  AprovadoUfir MONEY DEFAULT 0,
  AprovadoReal MONEY DEFAULT 0,
  AutorizadoUfir MONEY DEFAULT 0,
  AutorizadoReal MONEY DEFAULT 0,
  ConcedidoCusteioReal MONEY DEFAULT 0,
  ConcedidoCapitalReal MONEY DEFAULT 0,
  ContrapartidaReal MONEY DEFAULT 0,
  Logon INT NOT NULL,
  idProrrogacao INT DEFAULT NULL,
  IDREADEQUACAO INT,
  CONSTRAINT fk_Aprovacao_02 FOREIGN KEY (IdPRONAC) REFERENCES sac.Projetos (IdPRONAC),
  CONSTRAINT FK_AprovacaoProjetos FOREIGN KEY (AnoProjeto, Sequencial) REFERENCES sac.Projetos (AnoProjeto, Sequencial),
  CONSTRAINT FK_Aprovacao_Prorrogacao FOREIGN KEY (idProrrogacao) REFERENCES sac.Prorrogacao (idProrrogacao),
  CONSTRAINT fk_Aprovacao_01 FOREIGN KEY (idProrrogacao) REFERENCES sac.Prorrogacao (idProrrogacao),
  CONSTRAINT FK_TBREADEQUACAO_APROVACAO_IDADEQUACAO FOREIGN KEY (IDREADEQUACAO) REFERENCES sac.tbReadequacao (idReadequacao)
);
-- ALTER TABLE sac.Aprovacao ADD CONSTRAINT FK_Aprovacao_Aprovacao FOREIGN KEY (AnoProjeto, Sequencial, TipoAprovacao, DtAprovacao) REFERENCES sac.Aprovacao (AnoProjeto, Sequencial, TipoAprovacao, DtAprovacao);
CREATE UNIQUE INDEX UQ_Aprovacao_1__14 ON sac.Aprovacao (AnoProjeto, Sequencial, TipoAprovacao, DtAprovacao);
CREATE INDEX indDtAprovacao ON sac.Aprovacao (DtAprovacao);
CREATE INDEX IX_Aprovacao_1 ON sac.Aprovacao (PortariaAprovacao);
CREATE INDEX IX_Aprovacao ON sac.Aprovacao (AnoProjeto, Sequencial);
CREATE TABLE sac.AvaliacaoPC
(
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  Prestacao INTEGER DEFAULT 0 NOT NULL,
  Analista INT DEFAULT 0 NOT NULL,
  DtAnalise timestamp NOT NULL,
  Campo_0 int DEFAULT 9 NOT NULL,
  Campo_01 int DEFAULT 9 NOT NULL,
  Campo_02 int DEFAULT 9 NOT NULL,
  Campo_03 int DEFAULT 9 NOT NULL,
  Campo_04 int DEFAULT 9 NOT NULL,
  Campo_05 int DEFAULT 9 NOT NULL,
  Campo_06 int DEFAULT 9 NOT NULL,
  Campo_07 int DEFAULT 9 NOT NULL,
  Campo_08 int DEFAULT 9 NOT NULL,
  Campo_09 int DEFAULT 9 NOT NULL,
  Campo_010 int DEFAULT 9 NOT NULL,
  Campo_011 int DEFAULT 9 NOT NULL,
  Campo_012 int DEFAULT 9 NOT NULL,
  Campo_013 int DEFAULT 9 NOT NULL,
  Campo_014 int DEFAULT 9 NOT NULL,
  Campo_015 int DEFAULT 9 NOT NULL,
  Campo_016 int DEFAULT 9 NOT NULL,
  Campo_017 int DEFAULT 9 NOT NULL,
  Campo_018 int DEFAULT 9 NOT NULL,
  Campo_019 int DEFAULT 9 NOT NULL,
  Campo_020 int DEFAULT 9 NOT NULL,
  Campo_021 int DEFAULT 9 NOT NULL,
  Campo_022 int DEFAULT 9 NOT NULL,
  Campo_023 int DEFAULT 9 NOT NULL,
  Campo_024 int DEFAULT 9 NOT NULL,
  Campo_025 int DEFAULT 9 NOT NULL,
  Campo_026 int DEFAULT 9 NOT NULL,
  Resultado int DEFAULT 9 NOT NULL,
  Manifestacao int DEFAULT 9 NOT NULL,
  Pronunciamento int DEFAULT 9 NOT NULL,
  Justificativa TEXT NOT NULL,
  Providencia VARCHAR(1000) NOT NULL,
  Logon INT NOT NULL,
  CONSTRAINT PK_AvaliacaoPC PRIMARY KEY (AnoProjeto, Sequencial),
  CONSTRAINT FK_AvaliacaoPC_Projetos FOREIGN KEY (AnoProjeto, Sequencial) REFERENCES sac.Projetos (AnoProjeto, Sequencial)
);
CREATE TABLE sac.AvaliacaoProjeto
(
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  CodParametro int NOT NULL,
  CodPeso int NOT NULL,
  CONSTRAINT PK_AvaliacaoProjeto PRIMARY KEY (AnoProjeto, Sequencial, CodParametro, CodPeso)
);
CREATE TABLE sac.AvaliacaoProponente
(
  CgcCpf VARCHAR(14) NOT NULL,
  CodParametro int NOT NULL,
  CodPeso int NOT NULL,
  CONSTRAINT PK_AvaliacaoProponente PRIMARY KEY (CgcCpf, CodParametro, CodPeso)
);
CREATE TABLE sac.BancoAgencia
(
  Banco CHAR(3) NOT NULL,
  Agencia VARCHAR(5) NOT NULL,
  Descricao VARCHAR(50) NOT NULL,
  Endereco VARCHAR(100) NOT NULL,
  Bairro VARCHAR(50),
  Cidade VARCHAR(50),
  Uf CHAR(2) NOT NULL,
  Cep VARCHAR(8),
  Telefone VARCHAR(12),
  Fax VARCHAR(12),
  Perfil int NOT NULL
);
CREATE TABLE sac.BancoAgencia20100824
(
  Banco CHAR(3) NOT NULL,
  Agencia VARCHAR(5) NOT NULL,
  Descricao VARCHAR(50) NOT NULL,
  Endereco VARCHAR(100) NOT NULL,
  Bairro VARCHAR(50) NOT NULL,
  Cidade VARCHAR(50),
  Uf CHAR(2) NOT NULL,
  Cep VARCHAR(8),
  Telefone VARCHAR(12),
  Fax VARCHAR(12),
  Perfil int NOT NULL
);
CREATE TABLE sac.BancoAgencia_08
(
  Banco CHAR(3) NOT NULL
);
CREATE TABLE sac.BancoAgencia_BKP_04112009X
(
  Banco CHAR(3) NOT NULL,
  Agencia VARCHAR(5) NOT NULL,
  Descricao VARCHAR(50) NOT NULL,
  Endereco VARCHAR(100) NOT NULL,
  Bairro VARCHAR(50) NOT NULL,
  Cidade VARCHAR(50),
  Uf CHAR(2) NOT NULL,
  Cep VARCHAR(8),
  Telefone VARCHAR(12),
  Fax VARCHAR(12),
  Perfil int NOT NULL
);
CREATE TABLE sac.BancoAgenciaNovo
(
  Banco CHAR(3) NOT NULL,
  Agencia VARCHAR(5) NOT NULL,
  Descricao VARCHAR(50) NOT NULL,
  Cidade VARCHAR(50),
  Endereco VARCHAR(100) NOT NULL,
  Cep VARCHAR(8),
  Uf CHAR(2) NOT NULL,
  Perfil int NOT NULL
);
CREATE TABLE sac.BancoAgenciaNovo1
(
  Banco VARCHAR(255) NOT NULL,
  Agencia VARCHAR(255) NOT NULL,
  Descricao VARCHAR(255) NOT NULL,
  Cidade VARCHAR(255),
  Endereco VARCHAR(255) NOT NULL,
  Cep VARCHAR(255),
  Uf VARCHAR(255) NOT NULL,
  Perfil VARCHAR(255) NOT NULL
);
CREATE TABLE sac.Bandas
(
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  Finalidade int DEFAULT 0 NOT NULL,
  DtFundacao timestamp,
  NumeroComponentes SMALLINT DEFAULT 0 NOT NULL,
  Regente VARCHAR(100) NOT NULL,
  Indicacao INTEGER DEFAULT 0 NOT NULL,
  Emenda INTEGER DEFAULT 0 NOT NULL,
  PMunicipal INTEGER DEFAULT 0,
  PEstadual INTEGER DEFAULT 0,
  PRegional INTEGER DEFAULT 0,
  PNacional INTEGER DEFAULT 0,
  PInternacional INTEGER DEFAULT 0,
  Logon INT NOT NULL,
  CONSTRAINT PK_Bandas PRIMARY KEY (AnoProjeto, Sequencial),
  CONSTRAINT FK_BandasProjetos FOREIGN KEY (AnoProjeto, Sequencial) REFERENCES sac.Projetos (AnoProjeto, Sequencial)
);
CREATE TABLE sac.BcoAgMar2011$
(
  BCO VARCHAR(255),
  AGENCIA VARCHAR(255),
  DESCRIÇAO VARCHAR(255),
  UF VARCHAR(255),
  ENDEREÇO VARCHAR(255),
  BAIRRO VARCHAR(255),
  CIDADE VARCHAR(255),
  CEP VARCHAR(255),
  FONE FLOAT,
  FAX FLOAT,
  PERFIL FLOAT
);
CREATE TABLE sac.Bolsa
(
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  Pais VARCHAR(50) NOT NULL,
  Cidade VARCHAR(50) NOT NULL,
  Endereco VARCHAR(100) NOT NULL,
  Cep VARCHAR(15) NOT NULL,
  Telefone VARCHAR(15) NOT NULL,
  Instituicao VARCHAR(100) NOT NULL,
  InstCidade VARCHAR(50) NOT NULL,
  InstEndereco VARCHAR(100) NOT NULL,
  InstCep VARCHAR(15) NOT NULL,
  InstTelefone VARCHAR(15) NOT NULL,
  InstEmail VARCHAR(100) NOT NULL,
  Orientador VARCHAR(100) NOT NULL,
  DtInicioAjuda timestamp NOT NULL,
  DtFimAjuda timestamp NOT NULL,
  Portfolio TEXT,
  Logon INT NOT NULL,
  CONSTRAINT PK_Bolsa PRIMARY KEY (AnoProjeto, Sequencial),
  CONSTRAINT Fk_BolsaProjetos FOREIGN KEY (AnoProjeto, Sequencial) REFERENCES sac.Projetos (AnoProjeto, Sequencial)
);
CREATE TABLE sac.Captacao
(
  Idcaptacao INT PRIMARY KEY NOT NULL,
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  NumeroRecibo VARCHAR(5) NOT NULL,
  CgcCpfMecena VARCHAR(14) NOT NULL,
  TipoApoio CHAR NOT NULL,
  MedidaProvisoria CHAR DEFAULT '1' NOT NULL,
  DtChegadaRecibo timestamp NOT NULL,
  DtRecibo timestamp NOT NULL,
  CaptacaoReal MONEY NOT NULL,
  CaptacaoUfir MONEY NOT NULL,
  logon INT NOT NULL,
  IdProjeto INT,
  siTransferenciaRecurso CHAR,
  dtTransferenciaRecurso timestamp,
  isBemServico CHAR DEFAULT '0' NOT NULL,
  CONSTRAINT FK_CaptacaoProjetos_20160219 FOREIGN KEY (AnoProjeto, Sequencial) REFERENCES sac.Projetos (AnoProjeto, Sequencial),
  CONSTRAINT FK_Incentivador_20160219 FOREIGN KEY (CgcCpfMecena) REFERENCES sac.Interessado (CgcCpf)
);
CREATE INDEX IndCgcCpf_20160219 ON sac.Captacao (CgcCpfMecena);
CREATE INDEX IX_Captacao_20160219 ON sac.Captacao (AnoProjeto, Sequencial);
CREATE TABLE sac.CaptacaoConversao
(
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  NumeroRecibo INT NOT NULL,
  DtConversao timestamp NOT NULL,
  Valor MONEY NOT NULL,
  Logon INT NOT NULL,
  CONSTRAINT PK_CaptacaoConversao PRIMARY KEY (AnoProjeto, Sequencial, NumeroRecibo),
  CONSTRAINT FK_CaptacaoConversao_Projetos FOREIGN KEY (AnoProjeto, Sequencial) REFERENCES sac.Projetos (AnoProjeto, Sequencial)
);
CREATE TABLE sac.CaptacaoGuia
(
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  NumeroGuia INT NOT NULL,
  DtCaptacao timestamp NOT NULL,
  Observacao VARCHAR(250) DEFAULT 'NÆo informado' NOT NULL,
  CaptacaoReal MONEY NOT NULL,
  Logon INT DEFAULT 236 NOT NULL,
  CONSTRAINT PK_CaptacaoGuia PRIMARY KEY (AnoProjeto, Sequencial, NumeroGuia),
  CONSTRAINT FK_CaptacaoGuiaProjetos FOREIGN KEY (AnoProjeto, Sequencial) REFERENCES sac.Projetos (AnoProjeto, Sequencial),
  CONSTRAINT FK_CaptacaoGuiaRecolhimento FOREIGN KEY (NumeroGuia) REFERENCES sac.GuiaRecolhimento (NumeroGuia)
);
CREATE INDEX indNumeroGuia ON sac.CaptacaoGuia (NumeroGuia);
CREATE TABLE sac.CaptacaoOLD
(
  Idcaptacao INT PRIMARY KEY NOT NULL,
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  NumeroRecibo VARCHAR(5) NOT NULL,
  CgcCpfMecena VARCHAR(14) NOT NULL,
  TipoApoio CHAR NOT NULL,
  MedidaProvisoria CHAR DEFAULT '1' NOT NULL,
  DtChegadaRecibo timestamp NOT NULL,
  DtRecibo timestamp NOT NULL,
  CaptacaoReal MONEY NOT NULL,
  CaptacaoUfir MONEY NOT NULL,
  logon INT NOT NULL,
  IdProjeto INT,
  siTransferenciaRecurso CHAR,
  dtTransferenciaRecurso timestamp,
  isBemServico CHAR DEFAULT '0' NOT NULL,
  CONSTRAINT FK_CaptacaoProjetos FOREIGN KEY (AnoProjeto, Sequencial) REFERENCES sac.Projetos (AnoProjeto, Sequencial),
  CONSTRAINT FK_Incentivador FOREIGN KEY (CgcCpfMecena) REFERENCES sac.Interessado (CgcCpf)
);
CREATE INDEX IndCaptacaoOLDCgcCpf ON sac.CaptacaoOLD (CgcCpfMecena);
CREATE INDEX IX_Captacao ON sac.CaptacaoOLD (AnoProjeto, Sequencial);
CREATE TABLE sac.CaptacaoQuotas
(
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  AnoCav CHAR(4) NOT NULL,
  SequencialCav VARCHAR(4) NOT NULL,
  NIntegra VARCHAR(5) NOT NULL,
  CgcCpfSub VARCHAR(14) NOT NULL,
  DtIntegraliza timestamp NOT NULL,
  QtdQuotasIntegr INT NOT NULL,
  Observacao VARCHAR(255) NOT NULL,
  Logon INT NOT NULL,
  CONSTRAINT PK_CaptacaoQuotas PRIMARY KEY (AnoProjeto, Sequencial, AnoCav, SequencialCav, NIntegra),
  CONSTRAINT FkCaptacaoQuotasProjeto FOREIGN KEY (AnoProjeto, Sequencial) REFERENCES sac.Projetos (AnoProjeto, Sequencial)
);
CREATE TABLE sac.CarregaReciboCaptacaoDanca
(
  pronac CHAR(6),
  AnoProjeto CHAR(2),
  Sequencial CHAR(6),
  Operacao CHAR(10),
  valor MONEY,
  numerorecibo VARCHAR(5),
  datarecibo CHAR(10),
  nome VARCHAR(1000),
  CPFCNPJ CHAR(14),
  endereco VARCHAR(100),
  Cidade VARCHAR(100),
  UF CHAR(2),
  CEP CHAR(8),
  DDD CHAR(3),
  telefone CHAR(12),
  PFPJ CHAR
);
CREATE TABLE sac.CarregaReciboCaptacaoDanca2010
(
  pronac CHAR(6),
  AnoProjeto CHAR(2),
  Sequencial CHAR(6),
  Operacao CHAR(10),
  valor MONEY,
  numerorecibo VARCHAR(5),
  datarecibo CHAR(10),
  nome VARCHAR(1000),
  CPFCNPJ CHAR(14),
  endereco VARCHAR(100),
  Cidade VARCHAR(100),
  UF CHAR(2),
  CEP CHAR(8),
  DDD CHAR(3),
  telefone CHAR(12),
  PFPJ CHAR
);
CREATE TABLE sac.CarregaReciboCaptacaoDanca2012
(
  pronac CHAR(6),
  AnoProjeto CHAR(2),
  Sequencial CHAR(6),
  Operacao CHAR(10),
  valor MONEY,
  numerorecibo VARCHAR(5),
  datarecibo CHAR(10),
  nome VARCHAR(1000),
  CPFCNPJ CHAR(14),
  endereco VARCHAR(100),
  Cidade VARCHAR(100),
  UF CHAR(2),
  CEP CHAR(8),
  DDD CHAR(3),
  telefone CHAR(12),
  PFPJ CHAR
);
CREATE TABLE sac.CarregaReciboCaptacaoDanca2013
(
  pronac CHAR(6),
  AnoProjeto CHAR(2),
  Sequencial CHAR(6),
  Operacao CHAR(10),
  valor MONEY,
  numerorecibo VARCHAR(5),
  datarecibo CHAR(10),
  nome VARCHAR(1000),
  CPFCNPJ CHAR(14),
  endereco VARCHAR(100),
  Cidade VARCHAR(100),
  UF CHAR(2),
  CEP CHAR(8),
  DDD CHAR(3),
  telefone CHAR(12),
  PFPJ CHAR
);
CREATE TABLE sac.Carta
(
  Codigo VARCHAR(3) PRIMARY KEY NOT NULL,
  Descricao VARCHAR(50) NOT NULL,
  Status INTEGER DEFAULT 0 NOT NULL
);
CREATE UNIQUE INDEX AK_Carta ON sac.Carta (Codigo);
CREATE TABLE sac.CartaEventual
(
  Contador INT PRIMARY KEY NOT NULL,
  AnoProjeto CHAR(2),
  Sequencial VARCHAR(5),
  DtCartaEventual timestamp,
  Texto TEXT,
  Orgao INT NOT NULL,
  Logon INT NOT NULL,
  CONSTRAINT FK_CartaEventualProjetos FOREIGN KEY (AnoProjeto, Sequencial) REFERENCES sac.Projetos (AnoProjeto, Sequencial)
);
CREATE UNIQUE INDEX AK_CartaEventual ON sac.CartaEventual (Contador);
CREATE TABLE sac.CertidoesNegativas
(
  CgcCpf VARCHAR(14) NOT NULL,
  CodigoCertidao INT NOT NULL,
  DtEmissao timestamp NOT NULL,
  DtValidade timestamp NOT NULL,
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  Logon INT NOT NULL,
  idCertidoesnegativas INT PRIMARY KEY NOT NULL,
  cdProtocoloNegativa CHAR(15),
  cdSituacaoCertidao int,
  CONSTRAINT FK_CertidoesInteressado FOREIGN KEY (CgcCpf) REFERENCES sac.Interessado (CgcCpf)
);
CREATE TABLE sac.CNIC
(
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  NrReuniao SMALLINT NOT NULL,
  DtReuniao timestamp NOT NULL,
  TipoDePauta int NOT NULL,
  ResultadoDaAnalise int NOT NULL,
  Observacao TEXT NOT NULL,
  Logon INT NOT NULL,
  CONSTRAINT PK_CNIC PRIMARY KEY (AnoProjeto, Sequencial, NrReuniao),
  CONSTRAINT FK_CNIC_Projetos FOREIGN KEY (AnoProjeto, Sequencial) REFERENCES sac.Projetos (AnoProjeto, Sequencial)
);
CREATE TABLE sac.Complementacao
(
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  Pedido int NOT NULL,
  DtComplementacao timestamp DEFAULT NOW() NOT NULL,
  SolicitadoUfir MONEY DEFAULT 0 NOT NULL,
  SolicitadoReal MONEY DEFAULT 0 NOT NULL,
  SolicitadoCusteioUfir MONEY DEFAULT 0 NOT NULL,
  SolicitadoCusteioReal MONEY DEFAULT 0 NOT NULL,
  SolicitadoCapitalUfir MONEY DEFAULT 0 NOT NULL,
  SolicitadoCapitalReal MONEY DEFAULT 0 NOT NULL,
  Atendimento CHAR DEFAULT 'N' NOT NULL,
  Logon INT NOT NULL,
  CONSTRAINT PK_Complementacao PRIMARY KEY (AnoProjeto, Sequencial, Pedido, DtComplementacao),
  CONSTRAINT FK_ComplementacaoProjetos FOREIGN KEY (AnoProjeto, Sequencial) REFERENCES sac.Projetos (AnoProjeto, Sequencial)
);
CREATE UNIQUE INDEX AK_Complementacao ON sac.Complementacao (AnoProjeto, Sequencial, Pedido, DtComplementacao);
CREATE TABLE sac.Conselheiro
(
  Codigo int PRIMARY KEY NOT NULL,
  Nome VARCHAR(50) NOT NULL,
  Condicao VARCHAR(50),
  Status INTEGER DEFAULT 1 NOT NULL
);
CREATE UNIQUE INDEX UQ_Conselheiro ON sac.Conselheiro (Codigo);
CREATE TABLE sac.Consultor
(
  Codigo INT PRIMARY KEY NOT NULL,
  Nome VARCHAR(50) NOT NULL,
  Area CHAR NOT NULL,
  Status INTEGER DEFAULT 0 NOT NULL
);
CREATE TABLE sac.ConsultoresPC
(
  Contador INT PRIMARY KEY NOT NULL,
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  DtSaida timestamp,
  DtRetorno timestamp,
  Consultor INT NOT NULL,
  Observacao VARCHAR(255) NOT NULL,
  Logon INT NOT NULL,
  idPRONAC INT,
  idAgente INT,
  QtdeVolume INT DEFAULT 1 NOT NULL,
  CONSTRAINT FK_ConsultoresPCProjetos FOREIGN KEY (AnoProjeto, Sequencial) REFERENCES sac.Projetos (AnoProjeto, Sequencial)
);
CREATE INDEX IX_ConsultoresPC ON sac.ConsultoresPC (Consultor);
CREATE TABLE sac.ContaBancaria
(
  IdContaBancaria INT PRIMARY KEY NOT NULL,
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  Mecanismo CHAR NOT NULL,
  Banco CHAR(3) NOT NULL,
  Agencia VARCHAR(5) NOT NULL,
  ContaBloqueada VARCHAR(12) DEFAULT '000000000000' NOT NULL,
  DtLoteRemessaCB timestamp,
  LoteRemessaCB CHAR(5) DEFAULT '00000',
  OcorrenciaCB CHAR(3) DEFAULT '000',
  ContaLivre CHAR(12) DEFAULT '000000000000',
  DtLoteRemessaCL timestamp,
  LoteRemessaCL CHAR(5) DEFAULT '00000',
  OcorrenciaCL CHAR(3) DEFAULT '000',
  Logon INT NOT NULL,
  idPronac INT,
  CONSTRAINT Fk_ContaBancariaProjetos FOREIGN KEY (AnoProjeto, Sequencial) REFERENCES sac.Projetos (AnoProjeto, Sequencial),
  CONSTRAINT fk_ContaBancaria_01 FOREIGN KEY (idPronac) REFERENCES sac.Projetos (IdPRONAC)
);
CREATE UNIQUE INDEX IX_ContaBancaria ON sac.ContaBancaria (AnoProjeto, Sequencial, Mecanismo);
CREATE TABLE sac.Convenio
(
  Contador INT PRIMARY KEY NOT NULL,
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  Opcao int NOT NULL,
  NumeroConvenio VARCHAR(15) NOT NULL,
  DtConvenio timestamp,
  DtPublicacao timestamp,
  DtInicioExecucao timestamp,
  DtFinalExecucao timestamp,
  DtInicioVigencia timestamp,
  DtFinalVigencia timestamp,
  Objeto TEXT DEFAULT ' ',
  ValorConvenio MONEY DEFAULT 0,
  Logon INT NOT NULL,
  idProjeto INT,
  CONSTRAINT FK_ConvenioProjetos FOREIGN KEY (AnoProjeto, Sequencial) REFERENCES sac.Projetos (AnoProjeto, Sequencial),
  CONSTRAINT fk_Convenio__01 FOREIGN KEY (idProjeto) REFERENCES sac.Projetos (IdPRONAC)
);
CREATE INDEX indNumeroConvenio ON sac.Convenio (NumeroConvenio);
CREATE INDEX IX_Convenio ON sac.Convenio (AnoProjeto, Sequencial);
CREATE TABLE sac.CPBX
(
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  Cpb INT NOT NULL,
  DtCpb timestamp NOT NULL,
  Logon INT NOT NULL,
  Observacao VARCHAR(255) NOT NULL,
  CONSTRAINT PK_CPB PRIMARY KEY (AnoProjeto, Sequencial),
  CONSTRAINT FK_CpbProjetos FOREIGN KEY (AnoProjeto, Sequencial) REFERENCES sac.Projetos (AnoProjeto, Sequencial)
);
CREATE INDEX IndCbp ON sac.CPBX (Cpb);
CREATE TABLE sac.CriteriosX
(
  IdCriterios INT PRIMARY KEY NOT NULL,
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  DtCriterio timestamp NOT NULL,
  Prioridade int DEFAULT 0 NOT NULL,
  TipoApoio int DEFAULT 0 NOT NULL,
  Justificativa TEXT NOT NULL,
  Logon INT NOT NULL,
  IdParecerVinculdas INT,
  CONSTRAINT FK_Criterios_Projetos FOREIGN KEY (AnoProjeto, Sequencial) REFERENCES sac.Projetos (AnoProjeto, Sequencial),
  CONSTRAINT FK_Criterios_ParecerVinculadas FOREIGN KEY (IdParecerVinculdas) REFERENCES sac.ParecerVinculadasX (idParecerVinculadas)
);
CREATE TABLE sac.Curriculo
(
  CgcCpf VARCHAR(14) PRIMARY KEY NOT NULL,
  Nivel int NOT NULL,
  CadastroSav INTEGER DEFAULT 0 NOT NULL,
  Logon INT NOT NULL,
  CONSTRAINT FK_Curriculo_Interessado FOREIGN KEY (CgcCpf) REFERENCES sac.Interessado (CgcCpf)
);
CREATE TABLE sac.Dbf
(
  TipoRegistro CHAR,
  Ano CHAR(2),
  Sequencial VARCHAR(5),
  Informacao VARCHAR(250)
);
CREATE TABLE sac.Dbf_Agosto
(
  Informacao VARCHAR(250)
);
CREATE TABLE sac.Dbf_Dezembro
(
  Informacao VARCHAR(250)
);
CREATE TABLE sac.Dbf_Maio
(
  Informacao VARCHAR(250)
);
CREATE TABLE sac.dbresult
(
  Ano INT,
  Cap18 MONEY,
  Cap26 MONEY,
  Totcap MONEY,
  Totren MONEY,
  Totpri MONEY,
  B_A MONEY,
  C_A MONEY
);
CREATE TABLE sac.DeOrgaoParaOrgao
(
  DeOrgao INT NOT NULL,
  ParaOrgao INT NOT NULL
);
CREATE TABLE sac.DistribuicaoPassagem
(
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  CgcCpf VARCHAR(14) NOT NULL,
  RT VARCHAR(15) NOT NULL,
  Fatura VARCHAR(15) NOT NULL,
  VlConcedido MONEY DEFAULT 0 NOT NULL,
  Administrativa INTEGER DEFAULT 0 NOT NULL,
  DtEntregaBilhete timestamp,
  DtDevolucaoBilhete timestamp,
  DtRelatorio timestamp,
  Logon INT NOT NULL,
  CONSTRAINT PK_DistribuicaoPassagem PRIMARY KEY (AnoProjeto, Sequencial, CgcCpf),
  CONSTRAINT Fk_DistribuicaoPassagem FOREIGN KEY (AnoProjeto, Sequencial) REFERENCES sac.Passagem (AnoProjeto, Sequencial),
  CONSTRAINT FK_DistribuicaoProjeto FOREIGN KEY (AnoProjeto, Sequencial) REFERENCES sac.Projetos (AnoProjeto, Sequencial),
  CONSTRAINT FK_DistribuicaoInteressado FOREIGN KEY (CgcCpf) REFERENCES sac.Interessado (CgcCpf)
);
CREATE INDEX indDistribuicaoPassagemCgcCpf ON sac.DistribuicaoPassagem (CgcCpf);
CREATE TABLE sac.DocumentoPessoal
(
  idDocumentoPessoal INT PRIMARY KEY NOT NULL,
  AnoProjeto CHAR(2) DEFAULT 0 NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  Codigo INT NOT NULL,
  Seq int DEFAULT 0 NOT NULL,
  Campo1 VARCHAR(20),
  Campo2 VARCHAR(20),
  Campo3 VARCHAR(20),
  Campo4 VARCHAR(20),
  Campo5 VARCHAR(20),
  Status INTEGER DEFAULT 1,
  Logon INT NOT NULL,
  CONSTRAINT fk_DocumentoPessoal_01 FOREIGN KEY (AnoProjeto, Sequencial) REFERENCES sac.Projetos (AnoProjeto, Sequencial),
  CONSTRAINT FK_DocumentoPessoal_TipoDocumento FOREIGN KEY (Codigo) REFERENCES sac.TipoDocumento (Codigo)
);
CREATE INDEX IX_DocumentoPessoal ON sac.DocumentoPessoal (AnoProjeto, Sequencial, Seq);
CREATE TABLE sac.DocumentosExigidos
(
  Codigo INT PRIMARY KEY NOT NULL,
  Descricao VARCHAR(100) NOT NULL,
  Area CHAR NOT NULL,
  Opcao INT NOT NULL,
  stEstado INTEGER DEFAULT 1 NOT NULL,
  stUpload INTEGER DEFAULT 0
);
CREATE UNIQUE INDEX AK_DocumentosExigidos ON sac.DocumentosExigidos (Codigo);
CREATE TABLE sac.DocumentosProjeto
(
  Contador INT PRIMARY KEY NOT NULL,
  AnoProjeto CHAR(2),
  Sequencial VARCHAR(5),
  CodigoDocumento INT NOT NULL,
  idPRONAC INT,
  idProjeto INT,
  CONSTRAINT FK_DocProjetoProjetos FOREIGN KEY (AnoProjeto, Sequencial) REFERENCES sac.Projetos (AnoProjeto, Sequencial),
  CONSTRAINT FK_DocProjetosDocExigidos FOREIGN KEY (CodigoDocumento) REFERENCES sac.DocumentosExigidos (Codigo),
  CONSTRAINT FK_DocumentosProjeto_Projetos FOREIGN KEY (idPRONAC) REFERENCES sac.Projetos (IdPRONAC),
  CONSTRAINT FK_DocumentosProjeto_PreProjeto FOREIGN KEY (idProjeto) REFERENCES sac.PreProjeto (idPreProjeto)
);
CREATE UNIQUE INDEX AK_DocumentosProjeto ON sac.DocumentosProjeto (Contador);
CREATE INDEX indPronac ON sac.DocumentosProjeto (AnoProjeto, Sequencial);
CREATE TABLE sac.DocumentosProponente
(
  Contador INT PRIMARY KEY NOT NULL,
  CgcCpf VARCHAR(14),
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  CodigoDocumento INT NOT NULL,
  IdPRONAC INT,
  IdProjeto INT,
  CONSTRAINT fk_DocumentosProponente_03 FOREIGN KEY (CgcCpf) REFERENCES sac.Interessado (CgcCpf),
  CONSTRAINT fk_DocumentosProponente_04 FOREIGN KEY (CodigoDocumento) REFERENCES sac.DocumentosExigidos (Codigo),
  CONSTRAINT FK_DocumentosProponente_Projetos FOREIGN KEY (IdPRONAC) REFERENCES sac.Projetos (IdPRONAC),
  CONSTRAINT FK_DocumentosProponente_PreProjeto FOREIGN KEY (IdProjeto) REFERENCES sac.PreProjeto (idPreProjeto)
);
CREATE UNIQUE INDEX AK_DocumentosProponente ON sac.DocumentosProponente (Contador);
CREATE INDEX IndDocumentosProponenteCgcCpf ON sac.DocumentosProponente (CgcCpf);
CREATE TABLE sac.EditalDesembolso
(
  idDesembolso INT PRIMARY KEY NOT NULL,
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  idEdital INT NOT NULL,
  NrParcela int NOT NULL,
  Data timestamp NOT NULL,
  VlCapital MONEY DEFAULT 0 NOT NULL,
  VlCusteio MONEY DEFAULT 0 NOT NULL,
  Pagou INTEGER DEFAULT 0 NOT NULL,
  NrEmpenho VARCHAR(12) DEFAULT '' NOT NULL,
  NrOrdemBancaria VARCHAR(12) DEFAULT '' NOT NULL,
  NrEmpenhoCusteio VARCHAR(12) DEFAULT '' NOT NULL,
  NrOrdemBancariaCusteio VARCHAR(12) DEFAULT '' NOT NULL,
  Logon INT NOT NULL,
  CONSTRAINT FK_EditalDesembolso_Projetos FOREIGN KEY (AnoProjeto, Sequencial) REFERENCES sac.Projetos (AnoProjeto, Sequencial),
  CONSTRAINT FK_EditalDesembolso_EditalParcelas FOREIGN KEY (idEdital, NrParcela) REFERENCES sac.EditalParcelas (idEdital, NrParcela),
  CONSTRAINT FK_EditalDesembolso_Edital FOREIGN KEY (idEdital) REFERENCES sac.Edital (idEdital)
);
CREATE INDEX IX_EditalDesembolso ON sac.EditalDesembolso (AnoProjeto, Sequencial);
CREATE INDEX IX_EditalDesembolso_1 ON sac.EditalDesembolso (idEdital);
CREATE INDEX IX_EditalDesembolso_2 ON sac.EditalDesembolso (idEdital, NrParcela);
CREATE TABLE sac.EfeitosEconomicos
(
  Codigo int PRIMARY KEY NOT NULL,
  Descricao VARCHAR(100) NOT NULL
);
CREATE TABLE sac.EfeitosResultado
(
  Codigo INT PRIMARY KEY NOT NULL,
  Opcao int NOT NULL,
  Descricao VARCHAR(100) NOT NULL
);
CREATE TABLE sac.EfeitosSociais
(
  Codigo int PRIMARY KEY NOT NULL,
  Descricao VARCHAR(100) NOT NULL
);
CREATE TABLE sac.Empenho
(
  Contador INT PRIMARY KEY NOT NULL,
  UgGestao CHAR(6) NOT NULL,
  UgR CHAR(6) NOT NULL,
  PtRes CHAR(6) NOT NULL,
  ProgramaTrabalho CHAR(17) NOT NULL,
  FonteRecurso CHAR(3) NOT NULL,
  NaturezaDespesa CHAR(8) NOT NULL,
  NrEmpenho CHAR(12) NOT NULL,
  DtEmpenho timestamp NOT NULL,
  Valor MONEY NOT NULL,
  Logon INT NOT NULL,
  CONSTRAINT FK_Empenho_UgGestao FOREIGN KEY (UgGestao) REFERENCES sac.UgGestao (Codigo),
  CONSTRAINT FK_Empenho_UGR FOREIGN KEY (UgR) REFERENCES sac.UGR (Codigo),
  CONSTRAINT FK_Empenho_PtRes FOREIGN KEY (PtRes) REFERENCES sac.PtRes (Codigo),
  CONSTRAINT FK_Empenho_ProgramaTrabalho FOREIGN KEY (ProgramaTrabalho) REFERENCES sac.ProgramaTrabalho (Codigo),
  CONSTRAINT FK_Empenho_Fonte FOREIGN KEY (FonteRecurso) REFERENCES sac.Fonte (Codigo),
  CONSTRAINT FK_Empenho_ElementoDespesa FOREIGN KEY (NaturezaDespesa) REFERENCES sac.NaturezaDespesa (Codigo)
);
CREATE INDEX IX_Empenho ON sac.Empenho (NrEmpenho);
CREATE TABLE sac.EmpenhoProjeto
(
  CodigoEmpenho INT NOT NULL,
  CodigoConvenio INT NOT NULL,
  Logon INT NOT NULL,
  CONSTRAINT FK_EmpenhoProjeto_Empenho FOREIGN KEY (CodigoEmpenho) REFERENCES sac.Empenho (Contador),
  CONSTRAINT FK_EmpenhoProjeto_Convenio FOREIGN KEY (CodigoConvenio) REFERENCES sac.Convenio (Contador)
);
CREATE INDEX IX_EmpenhoProjeto ON sac.EmpenhoProjeto (CodigoEmpenho);
CREATE INDEX IX_EmpenhoProjeto_1 ON sac.EmpenhoProjeto (CodigoConvenio);
CREATE TABLE sac.Enquadramento
(
  IdEnquadramento INT PRIMARY KEY NOT NULL,
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  Enquadramento int DEFAULT 1 NOT NULL,
  DtEnquadramento timestamp NOT NULL,
  Observacao VARCHAR(255) NOT NULL,
  Logon INT NOT NULL,
  IdPRONAC INT NOT NULL,
  CONSTRAINT Fk_EnquadramentoProjetos FOREIGN KEY (AnoProjeto, Sequencial) REFERENCES sac.Projetos (AnoProjeto, Sequencial),
  CONSTRAINT FK_Enquadramento_Projetos FOREIGN KEY (IdPRONAC) REFERENCES sac.Projetos (IdPRONAC)
);
CREATE UNIQUE INDEX IX_Enquadramento ON sac.Enquadramento (IdPRONAC);
CREATE TABLE sac.EntregaProduto
(
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  DtEntrega timestamp NOT NULL,
  Observacao VARCHAR(255) NOT NULL,
  Logon INT NOT NULL,
  CONSTRAINT PK_EntregaProduto PRIMARY KEY (AnoProjeto, Sequencial),
  CONSTRAINT FK_EntregaProdutoProjetos FOREIGN KEY (AnoProjeto, Sequencial) REFERENCES sac.Projetos (AnoProjeto, Sequencial)
);
CREATE TABLE sac.Etiqueta
(
  Ano CHAR(4) NOT NULL,
  Mecanismo CHAR NOT NULL,
  Sequencial INT NOT NULL,
  CONSTRAINT PK_Etiqueta PRIMARY KEY (Ano, Mecanismo)
);
CREATE UNIQUE INDEX AK_Etiqueta ON sac.Etiqueta (Ano, Mecanismo);
CREATE TABLE sac.Evolucao
(
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  Fase int NOT NULL,
  DtInicio timestamp NOT NULL,
  DtTermino timestamp NOT NULL,
  InfRelevantes VARCHAR(250) NOT NULL,
  Logon INT NOT NULL,
  CONSTRAINT PK_Evolucao PRIMARY KEY (AnoProjeto, Sequencial, Fase),
  CONSTRAINT FK_EvolucaoProjetos FOREIGN KEY (AnoProjeto, Sequencial) REFERENCES sac.Projetos (AnoProjeto, Sequencial),
  CONSTRAINT FK_EvolucaoFase FOREIGN KEY (Fase) REFERENCES sac.Fase (Codigo)
);
CREATE TABLE sac.GeralExt
(
  NrReuniao INT,
  AreaCultural VARCHAR(50) NOT NULL,
  Projeto VARCHAR(7) NOT NULL,
  NomedoProjeto VARCHAR(300) NOT NULL,
  ResumodoProjeto TEXT,
  TipodeParecer CHAR NOT NULL,
  Parecer CHAR,
  ParecerTecnico TEXT,
  Proponente VARCHAR(150) NOT NULL,
  CNPJCPF VARCHAR(14) NOT NULL,
  Cidade VARCHAR(50) NOT NULL,
  UF CHAR(2) NOT NULL,
  Solicitado MONEY,
  Sugerido MONEY,
  Enquadramento int,
  Processo VARCHAR(17),
  Situacao VARCHAR(150) NOT NULL
);
CREATE TABLE sac.GrupoEmpresarial
(
  Codigo INT PRIMARY KEY NOT NULL,
  NomeGrupo VARCHAR(100) NOT NULL,
  Publica INTEGER DEFAULT 0 NOT NULL,
  Endereco VARCHAR(100) NOT NULL,
  Cidade VARCHAR(50) NOT NULL,
  UF VARCHAR(15) NOT NULL,
  CEP VARCHAR(8) NOT NULL,
  Pais VARCHAR(50) DEFAULT 'Brasil' NOT NULL,
  Presidente VARCHAR(100) NOT NULL
);
CREATE TABLE sac.HistoricoAspectosResultado
(
  Contador INT PRIMARY KEY NOT NULL,
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  Produto int NOT NULL,
  QtdProduto INT NOT NULL,
  Impacto int NOT NULL,
  QtdImpacto INT NOT NULL,
  EfeitosEconomicos int NOT NULL,
  QtdEconomicos INT NOT NULL,
  EfeitosSociais int NOT NULL,
  QtdSociais INT NOT NULL,
  Logon INT NOT NULL,
--   CONSTRAINT FK_HistoricoAspectosResultado_AspectosResultados FOREIGN KEY (AnoProjeto, Sequencial) REFERENCES sac.,
  CONSTRAINT FK_HistoricoAspectosResultado_Impacto FOREIGN KEY (Impacto) REFERENCES sac.ImpactoX (Codigo),
  CONSTRAINT FK_HistoricoAspectosResultado_EfeitosEconomicos FOREIGN KEY (EfeitosEconomicos) REFERENCES sac.EfeitosEconomicos (Codigo),
  CONSTRAINT FK_HistoricoAspectosResultado_EfeitosSociais FOREIGN KEY (EfeitosSociais) REFERENCES sac.EfeitosSociais (Codigo)
);
CREATE INDEX IX_HistoricoAspectosResultado ON sac.HistoricoAspectosResultado (AnoProjeto, Sequencial);
CREATE TABLE sac.HistoricoAutuacaoX
(
  Contador INT PRIMARY KEY NOT NULL,
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  DtAutuacao timestamp NOT NULL,
  Folhas INT NOT NULL,
  Providencia VARCHAR(250) NOT NULL,
  Logon INT NOT NULL--,
--   CONSTRAINT FK_HistoricoAutuacao_Autuacao FOREIGN KEY (AnoProjeto, Sequencial) REFERENCES
);
CREATE TABLE sac.HistoricoCarta
(
  idHistoricoCarta INT PRIMARY KEY NOT NULL,
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  NumeroCarta VARCHAR(3) NOT NULL,
  DtCarta timestamp NOT NULL,
  Status CHAR DEFAULT 'N' NOT NULL,
  Orgao INT NOT NULL,
  Logon INT NOT NULL,
  CONSTRAINT FK_HistoricoCartaProjetos FOREIGN KEY (AnoProjeto, Sequencial) REFERENCES sac.Projetos (AnoProjeto, Sequencial),
  CONSTRAINT fk_HistoricoCartaCarta_01 FOREIGN KEY (NumeroCarta) REFERENCES sac.Carta (Codigo)
);
CREATE UNIQUE INDEX AK_HistoricoCarta ON sac.HistoricoCarta (AnoProjeto, Sequencial, NumeroCarta, DtCarta);
CREATE INDEX IX_DtCarta ON sac.HistoricoCarta (DtCarta);
CREATE INDEX IX_NumeroCarta ON sac.HistoricoCarta (NumeroCarta);
CREATE INDEX IX_Orgao ON sac.HistoricoCarta (Orgao);
CREATE INDEX IX_Status ON sac.HistoricoCarta (Status);
CREATE TABLE sac.HistoricoDelecaoAprovacao
(
  Contador INT PRIMARY KEY NOT NULL,
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  TipoAprovacao CHAR NOT NULL,
  DtDelecao timestamp NOT NULL,
  NrPortaria VARCHAR(10) NOT NULL,
  Logon INT NOT NULL
);
CREATE INDEX IX_HistoricoDelecaoAprovacao ON sac.HistoricoDelecaoAprovacao (AnoProjeto, Sequencial, TipoAprovacao, DtDelecao);
CREATE TABLE sac.HistoricoInabilitado
(
  Contador INT PRIMARY KEY NOT NULL,
  CgcCpf VARCHAR(14) NOT NULL,
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  DtHabilitado timestamp NOT NULL,
  Orgao INT NOT NULL,
  Habilitado CHAR NOT NULL,
  Logon INT NOT NULL
);
CREATE INDEX IX_HistoricoInabilitado ON sac.HistoricoInabilitado (CgcCpf, AnoProjeto, Sequencial);
CREATE INDEX IX_HistoricoInabilitado_1 ON sac.HistoricoInabilitado (AnoProjeto, Sequencial);
CREATE TABLE sac.HistoricoSituacao
(
  Contador INT PRIMARY KEY NOT NULL,
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  DtSituacao timestamp,
  Situacao CHAR(3) NOT NULL,
  ProvidenciaTomada VARCHAR(500) DEFAULT ' ',
  Logon INT,
  CONSTRAINT FK_HistoricoSituacaoProjetos FOREIGN KEY (AnoProjeto, Sequencial) REFERENCES sac.Projetos (AnoProjeto, Sequencial),
  CONSTRAINT FK_HistoricioSituacaoSituacao FOREIGN KEY (Situacao) REFERENCES sac.Situacao (Codigo)
);
CREATE INDEX indAnoSequencial ON sac.HistoricoSituacao (AnoProjeto, Sequencial);
CREATE INDEX IndHistoricoSituacaoSituacao ON sac.HistoricoSituacao (Situacao);
CREATE TABLE sac.HistoricoUnidade
(
  Contador INT PRIMARY KEY NOT NULL,
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  DtSaida timestamp,
  UnidadeAnalise VARCHAR(15),
  DtRetorno timestamp,
  Logon INT NOT NULL,
  CONSTRAINT FK_HistoricoUnidadeProjetos FOREIGN KEY (AnoProjeto, Sequencial) REFERENCES sac.Projetos (AnoProjeto, Sequencial)
);
CREATE UNIQUE INDEX AK_HistoricoUnidade ON sac.HistoricoUnidade (Contador);
CREATE TABLE sac.Humanidades
(
  AnoProjeto CHAR(2) DEFAULT 0 NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  Opcao int NOT NULL,
  Tiragem INT DEFAULT 0 NOT NULL,
  Edicao int DEFAULT 0 NOT NULL,
  Periocidade int DEFAULT 0 NOT NULL,
  NrEdicao int DEFAULT 0 NOT NULL,
  Acervo INT DEFAULT 0 NOT NULL,
  Equipamento INT DEFAULT 0 NOT NULL,
  PdPatrocinio int DEFAULT 0 NOT NULL,
  PdDoacao int DEFAULT 0 NOT NULL,
  PdComercializacao int DEFAULT 0 NOT NULL,
  Logon INT NOT NULL,
  idHumanidades INT PRIMARY KEY NOT NULL,
  CONSTRAINT FK_Humanidades_Projetos FOREIGN KEY (AnoProjeto, Sequencial) REFERENCES sac.Projetos (AnoProjeto, Sequencial)
);
CREATE TABLE sac.Inabilitado
(
  CgcCpf VARCHAR(14) NOT NULL,
  AnoProjeto CHAR(2) DEFAULT 'XX' NOT NULL,
  Sequencial VARCHAR(5) DEFAULT 'XXXX' NOT NULL,
  Orgao INT NOT NULL,
  Logon INT NOT NULL,
  Habilitado CHAR DEFAULT 'N',
  idProjeto INT,
  idTipoInabilitado INT DEFAULT 8,
  dtInabilitado DATE,
  CONSTRAINT PK_Inabilitado PRIMARY KEY (CgcCpf, AnoProjeto, Sequencial),
  CONSTRAINT FK_InabilitadoProjetos FOREIGN KEY (AnoProjeto, Sequencial) REFERENCES sac.Projetos (AnoProjeto, Sequencial),
  CONSTRAINT FK_Inabilitado_01 FOREIGN KEY (idProjeto) REFERENCES sac.Projetos (IdPRONAC),
  CONSTRAINT FK_Inabilitado_tbTipoInabilitado FOREIGN KEY (idTipoInabilitado) REFERENCES sac.tbTipoInabilitado (idTipoInabilitado)
);
CREATE UNIQUE INDEX AK_Inabilitado ON sac.Inabilitado (CgcCpf, AnoProjeto, Sequencial);
CREATE TABLE sac.InformacaoTrimestralX
(
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  AnoRelatorio SMALLINT NOT NULL,
  FisicoFinanceiro int NOT NULL,
  Trimestre1 INTEGER DEFAULT 0 NOT NULL,
  Trimestre2 INTEGER DEFAULT 0 NOT NULL,
  Trimestre3 INTEGER DEFAULT 0 NOT NULL,
  Trimestre4 INTEGER DEFAULT 0 NOT NULL,
  Observacao VARCHAR(250) DEFAULT ' ',
  Logon INT NOT NULL,
  CONSTRAINT PK_InformacaoTrimestral_6__14 PRIMARY KEY (AnoProjeto, Sequencial, AnoRelatorio, FisicoFinanceiro),
  CONSTRAINT FK_InformacaoProjetos FOREIGN KEY (AnoProjeto, Sequencial) REFERENCES sac.Projetos (AnoProjeto, Sequencial)
);
CREATE TABLE sac.InstrumentoBanda
(
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  Opcao CHAR NOT NULL,
  Codigo SMALLINT NOT NULL,
  Logon INT NOT NULL,
  CONSTRAINT PK_InstrumentoBanda PRIMARY KEY (AnoProjeto, Sequencial, Opcao, Codigo),
  CONSTRAINT FK_InstrumentoBandaProjetos FOREIGN KEY (AnoProjeto, Sequencial) REFERENCES sac.Projetos (AnoProjeto, Sequencial),
  CONSTRAINT FK_InstrumentoBandaKitBanda FOREIGN KEY (Codigo) REFERENCES sac.KitBanda (Codigo)
);
CREATE TABLE sac.KitEntregue
(
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  DtAprovacao timestamp NOT NULL,
  Documento VARCHAR(25) NOT NULL,
  DtEntrega timestamp,
  ValorKit MONEY DEFAULT 0 NOT NULL,
  Logon INT NOT NULL,
  CONSTRAINT PK_KitEntregue PRIMARY KEY (AnoProjeto, Sequencial),
  CONSTRAINT FK_KitEntregueProjetos FOREIGN KEY (AnoProjeto, Sequencial) REFERENCES sac.Projetos (AnoProjeto, Sequencial)
);
CREATE TABLE sac.Lembrete
(
  Contador INT PRIMARY KEY NOT NULL,
  Logon INT NOT NULL,
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  DtLembrete timestamp NOT NULL,
  Lembrete VARCHAR(250) NOT NULL
);
CREATE INDEX IX_Lembrete ON sac.Lembrete (Logon, DtLembrete);
CREATE INDEX IX_Logon ON sac.Lembrete (Logon);
CREATE INDEX IX_NumeroProjeto ON sac.Lembrete (AnoProjeto, Sequencial);
CREATE TABLE sac.Liberacao
(
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  Mecanismo CHAR NOT NULL,
  DtLiberacao timestamp NOT NULL,
  DtDocumento timestamp NOT NULL,
  NumeroDocumento VARCHAR(5) NOT NULL,
  VlOutrasFontes MONEY DEFAULT 0 NOT NULL,
  Observacao VARCHAR(250) NOT NULL,
  CgcCpf VARCHAR(14) DEFAULT '',
  Permissao CHAR DEFAULT 'S' NOT NULL,
  Logon INT NOT NULL,
  VlLiberado MONEY DEFAULT 0 NOT NULL,
  CONSTRAINT PK_Liberacao PRIMARY KEY (AnoProjeto, Sequencial, Mecanismo),
  CONSTRAINT FK_LiberacaoProjetos FOREIGN KEY (AnoProjeto, Sequencial) REFERENCES sac.Projetos (AnoProjeto, Sequencial)
);
CREATE TABLE sac.NomeCampoDoc
(
  Codigo INT PRIMARY KEY NOT NULL,
  Campo1 VARCHAR(15) NOT NULL,
  TipoAtributo1 int,
  Campo2 VARCHAR(15) NOT NULL,
  TipoAtributo2 int,
  Campo3 VARCHAR(15) NOT NULL,
  TipoAtributo3 int,
  Campo4 VARCHAR(15) NOT NULL,
  TipoAtributo4 int,
  Campo5 VARCHAR(15) NOT NULL,
  TipoAtributo5 int,
  CONSTRAINT FK_NomeCampoDoc_TipoDocumento FOREIGN KEY (Codigo) REFERENCES sac.TipoDocumento (Codigo)
);
CREATE TABLE sac.OrcamentoDetalhado
(
  Contador INT PRIMARY KEY NOT NULL,
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  Mecanismo CHAR NOT NULL,
  Opcao CHAR NOT NULL,
  Orcamento int NOT NULL,
  SolicitadoUfir MONEY DEFAULT 0 NOT NULL,
  SolicitadoReal MONEY DEFAULT 0 NOT NULL,
  SugeridoUfir MONEY DEFAULT 0 NOT NULL,
  SugeridoReal MONEY DEFAULT 0 NOT NULL,
  AprovadoUfir MONEY DEFAULT 0 NOT NULL,
  AprovadoReal MONEY DEFAULT 0 NOT NULL,
  Logon INT NOT NULL
);
CREATE INDEX indOrcamentoDetalhadoPronac ON sac.OrcamentoDetalhado (AnoProjeto, Sequencial, Mecanismo, Opcao, Orcamento);
CREATE TABLE sac.OrdemBancaria
(
  Contador INT PRIMARY KEY NOT NULL,
  NrOrdemBancaria CHAR(12) NOT NULL,
  DtOrdemBancaria timestamp NOT NULL,
  Valor MONEY NOT NULL,
  Logon INT NOT NULL
);
CREATE INDEX IX_OrdemBancaria ON sac.OrdemBancaria (NrOrdemBancaria);
CREATE TABLE sac.OrdemBancariaProjeto
(
  CodigoOrdemBancaria INT NOT NULL,
  CodigoConvenio INT NOT NULL,
  Logon INT NOT NULL,
  CONSTRAINT FK_OrdemBancariaProjeto_OrdemBancaria FOREIGN KEY (CodigoOrdemBancaria) REFERENCES sac.OrdemBancaria (Contador),
  CONSTRAINT FK_OrdemBancariaProjeto_Convenio FOREIGN KEY (CodigoConvenio) REFERENCES sac.Convenio (Contador)
);
CREATE INDEX IX_OrdemBancariaProjeto ON sac.OrdemBancariaProjeto (CodigoOrdemBancaria);
CREATE INDEX IX_OrdemBancariaProjeto_1 ON sac.OrdemBancariaProjeto (CodigoConvenio);
CREATE TABLE sac.OrgaoAntesDaAncineX
(
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  Orgao INT NOT NULL,
  CONSTRAINT PK_OrgaoAntesDaAncine PRIMARY KEY (AnoProjeto, Sequencial)
);
CREATE TABLE sac.PagamentoBolsista
(
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  NumeroParcela int NOT NULL,
  CgcCpf VARCHAR(14) NOT NULL,
  DtParcela timestamp NOT NULL,
  ValorParcela MONEY NOT NULL,
  TaxaEscolar MONEY NOT NULL,
  Passagens MONEY NOT NULL,
  SeguroSaude MONEY NOT NULL,
  Logon INT NOT NULL,
  CONSTRAINT PK_PagamentoBolsista PRIMARY KEY (AnoProjeto, Sequencial, NumeroParcela),
  CONSTRAINT FK_PagamentoBolsista_Bolsa FOREIGN KEY (AnoProjeto, Sequencial) REFERENCES sac.Bolsa (AnoProjeto, Sequencial)
);
CREATE TABLE sac.Parecer
(
  IdParecer INT PRIMARY KEY NOT NULL,
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  TipoParecer CHAR NOT NULL,
  ParecerFavoravel CHAR DEFAULT '2',
  DtParecer timestamp NOT NULL,
  Parecerista VARCHAR(30),
  Conselheiro int,
  NumeroReuniao INT,
  ResumoParecer TEXT DEFAULT ' ',
  SugeridoUfir MONEY DEFAULT 0,
  SugeridoReal MONEY DEFAULT 0,
  SugeridoCusteioUfir MONEY DEFAULT 0,
  SugeridoCusteioReal MONEY DEFAULT 0,
  SugeridoCapitalUfir MONEY DEFAULT 0,
  SugeridoCapitalReal MONEY DEFAULT 0,
  Atendimento CHAR DEFAULT 'N' NOT NULL,
  Logon INT NOT NULL,
  IdPRONAC INT NOT NULL,
  idEnquadramento INT,
  stAtivo INTEGER DEFAULT 1 NOT NULL,
  idTipoAgente INT DEFAULT 1 NOT NULL,
  CONSTRAINT FK_ParecerProjetos FOREIGN KEY (AnoProjeto, Sequencial) REFERENCES sac.Projetos (AnoProjeto, Sequencial),
  CONSTRAINT FK_Parecer FOREIGN KEY (Conselheiro) REFERENCES sac.Conselheiro (Codigo),
  CONSTRAINT FK_Parecer_tbReuniao FOREIGN KEY (NumeroReuniao) REFERENCES sac.tbReuniao (idNrReuniao),
  CONSTRAINT FK_Parecer_Projetos FOREIGN KEY (IdPRONAC) REFERENCES sac.Projetos (IdPRONAC),
  CONSTRAINT FK_Parecer_Enquadramento FOREIGN KEY (idEnquadramento) REFERENCES sac.Enquadramento (IdEnquadramento)
);
CREATE UNIQUE INDEX AK_Parecer ON sac.Parecer (AnoProjeto, Sequencial, TipoParecer, DtParecer);
CREATE INDEX IX_NumeroReuniao ON sac.Parecer (NumeroReuniao);
CREATE INDEX _dta_index_Parecer_6_732737863__K21_K6_K19_K12_5 ON sac.Parecer (stAtivo, DtParecer, IdPRONAC, SugeridoReal, ParecerFavoravel);
CREATE INDEX _dta_index_Parecer_6_732737863__K19_K6_K21_K12_5 ON sac.Parecer (IdPRONAC, DtParecer, stAtivo, SugeridoReal, ParecerFavoravel);
CREATE INDEX _dta_index_Parecer_6_732737863__K22_K21_K6_K19_5_12 ON sac.Parecer (idTipoAgente, stAtivo, DtParecer, IdPRONAC, ParecerFavoravel, SugeridoReal);
CREATE INDEX _dta_index_Parecer_6_732737863__K19_K6_K21_K1_K4_5 ON sac.Parecer (IdPRONAC, DtParecer, stAtivo, IdParecer, TipoParecer, ParecerFavoravel);
CREATE INDEX _dta_index_Parecer_6_732737863__K21_K19_5 ON sac.Parecer (stAtivo, IdPRONAC, ParecerFavoravel);
CREATE INDEX IX_IdPronac ON sac.Parecer (IdPRONAC);
CREATE INDEX IX_AnoSequencial ON sac.Parecer (AnoProjeto, Sequencial, ParecerFavoravel, SugeridoReal);
CREATE TABLE sac.tbReadequacaoXParecer
(
  idReadequacaoXParecer INT PRIMARY KEY NOT NULL,
  idReadequacao INT NOT NULL,
  idParecer INT NOT NULL,
  CONSTRAINT FK_tbReadequacaoXParecer_tbReadequacao FOREIGN KEY (idReadequacao) REFERENCES sac.tbReadequacao (idReadequacao),
  CONSTRAINT FK_tbReadequacaoXParecer_Parecer FOREIGN KEY (idParecer) REFERENCES sac.Parecer (IdParecer)
);
CREATE TABLE sac.ParecerControle
(
  Contador INT PRIMARY KEY NOT NULL,
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  Controladores int NOT NULL,
  DocumentoSolicitacao int NOT NULL,
  NrDocumentoSolicitacao VARCHAR(20) NOT NULL,
  DtSolicitacao timestamp,
  Posicionamento int NOT NULL,
  Constatacao TEXT NOT NULL,
  DocumentoResposta int,
  NrDocumentoResposta VARCHAR(20),
  DtResposta timestamp,
  Resposta TEXT NOT NULL,
  Logon INT NOT NULL,
  CONSTRAINT FK_ParecerControle_Projetos FOREIGN KEY (AnoProjeto, Sequencial) REFERENCES sac.Projetos (AnoProjeto, Sequencial)
);
CREATE INDEX IX_ParecerControle ON sac.ParecerControle (AnoProjeto, Sequencial);
CREATE TABLE sac.ParecerOrcamentoantX
(
  idParecerOrcamento INT PRIMARY KEY NOT NULL,
  idParecerVinculadas INT NOT NULL,
  Item VARCHAR(100) NOT NULL,
  Solicitado MONEY NOT NULL,
  Sugerido MONEY NOT NULL,
  Aprovado MONEY DEFAULT 0 NOT NULL,
  Logon INT NOT NULL,
  CONSTRAINT FK_ParecerOrcamento_ParecerVinculadas FOREIGN KEY (idParecerVinculadas) REFERENCES sac.ParecerVinculadasantX (idParecerVinculadas)
);
CREATE INDEX IX_ParecerOrcamento ON sac.ParecerOrcamentoantX (idParecerVinculadas);
CREATE TABLE sac.ParecerOrcamentoX
(
  idParecerOrcamento INT PRIMARY KEY NOT NULL,
  idParecerVinculadas INT NOT NULL,
  Item VARCHAR(100) NOT NULL,
  Solicitado MONEY NOT NULL,
  Sugerido MONEY NOT NULL,
  JustificativaSugerido VARCHAR(250) NOT NULL,
  Aprovado MONEY DEFAULT 0 NOT NULL,
  JustificativaAprovado VARCHAR(250) NOT NULL,
  Logon INT NOT NULL,
  CONSTRAINT FK1_ParecerOrcamento_ParecerVinculadas FOREIGN KEY (idParecerVinculadas) REFERENCES sac.ParecerVinculadasX (idParecerVinculadas)
);
CREATE TABLE sac.PlanoDeDivulgacao
(
  idPlanoDivulgacao INT PRIMARY KEY NOT NULL,
  idProjeto INT NOT NULL,
  idPeca INT NOT NULL,
  idVeiculo INT NOT NULL,
  Usuario INT NOT NULL,
  siPlanoDeDivulgacao CHAR DEFAULT 0 NOT NULL,
  idDocumento INT,
  stPlanoDivulgacao INTEGER DEFAULT 1 NOT NULL,
  CONSTRAINT FK_PlanoDeDivulgacao_PreProjeto FOREIGN KEY (idProjeto) REFERENCES sac.PreProjeto (idPreProjeto),
  CONSTRAINT FK_PlanoDeDivulgacao_Verificacao FOREIGN KEY (idPeca) REFERENCES sac.Verificacao (idVerificacao),
  CONSTRAINT FK_PlanoDeDivulgacao_Verificacao1 FOREIGN KEY (idVeiculo) REFERENCES sac.Verificacao (idVerificacao)
);
CREATE SEQUENCE sac.planodedivulgacao_idplanodivulgacao_seq NO MINVALUE NO MAXVALUE NO CYCLE;
ALTER TABLE sac.planodedivulgacao ALTER COLUMN idplanodivulgacao SET DEFAULT nextval('sac.planodedivulgacao_idplanodivulgacao_seq');
ALTER SEQUENCE sac.planodedivulgacao_idplanodivulgacao_seq OWNED BY sac.planodedivulgacao.idplanodivulgacao;
CREATE TABLE sac.PlanoDistribuicaoProduto
(
  idPlanoDistribuicao INT PRIMARY KEY NOT NULL,
  idProjeto INT NOT NULL,
  idProduto int NOT NULL,
  Area CHAR,
  Segmento CHAR(2),
  idPosicaoDaLogo INT DEFAULT 0 NOT NULL,
  QtdeProduzida INT DEFAULT 0 NOT NULL,
  QtdePatrocinador INT DEFAULT 0 NOT NULL,
  QtdeProponente INT DEFAULT 0 NOT NULL,
  QtdeOutros INT DEFAULT 0 NOT NULL,
  QtdeVendaNormal INT DEFAULT 0 NOT NULL,
  QtdeVendaPromocional INT DEFAULT 0 NOT NULL,
  PrecoUnitarioNormal MONEY DEFAULT 0 NOT NULL,
  PrecoUnitarioPromocional MONEY DEFAULT 0 NOT NULL,
  stPrincipal INTEGER DEFAULT 0,
  Usuario INT DEFAULT 0 NOT NULL,
  dsJustificativaPosicaoLogo VARCHAR(8000),
  stPlanoDistribuicaoProduto INTEGER DEFAULT 1 NOT NULL,
  CONSTRAINT FK_PlanoDistribuicaoProduto_PreProjeto FOREIGN KEY (idProjeto) REFERENCES sac.PreProjeto (idPreProjeto),
  CONSTRAINT FK_PlanoDistribuicaoProduto_Produto FOREIGN KEY (idProduto) REFERENCES sac.Produto (Codigo),
  CONSTRAINT FK_PlanoDistribuicaoProduto_Verificacao FOREIGN KEY (idPosicaoDaLogo) REFERENCES sac.Verificacao (idVerificacao)
);
CREATE TABLE sac.PrestacaoContas
(
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  ReciboMecenato INTEGER DEFAULT 0 NOT NULL,
  ExecucaoReceitaDespesa INTEGER DEFAULT 0 NOT NULL,
  RelacaoPagamentos INTEGER DEFAULT 0 NOT NULL,
  RelatorioFisico INTEGER DEFAULT 0 NOT NULL,
  RelacaoBensCapital INTEGER DEFAULT 0 NOT NULL,
  RelacaoBensImoveis INTEGER DEFAULT 0 NOT NULL,
  ConciliacaoBancaria INTEGER DEFAULT 0 NOT NULL,
  ExtratoBancario INTEGER DEFAULT 0 NOT NULL,
  RecolhimentoFundo INTEGER DEFAULT 0 NOT NULL,
  RelatorioFinal INTEGER DEFAULT 0 NOT NULL,
  MaterialDivulgacao INTEGER DEFAULT 0 NOT NULL,
  Exemplares INTEGER DEFAULT 0 NOT NULL,
  Outros INTEGER DEFAULT 0 NOT NULL,
  DtApresentacaoPC timestamp,
  DtInicioRealizacao timestamp,
  DtfinalRealizacao timestamp,
  Observacao TEXT NOT NULL,
  ValorExecutado MONEY DEFAULT 0 NOT NULL,
  AplicacaoFinanceira MONEY DEFAULT 0 NOT NULL,
  OutrasFontes MONEY DEFAULT 0 NOT NULL,
  SaldoRecolhido MONEY DEFAULT 0 NOT NULL,
  Logon INT NOT NULL,
  CONSTRAINT PK_PrestacaoContas PRIMARY KEY (AnoProjeto, Sequencial),
  CONSTRAINT FK_PrestacaoContasProjetos FOREIGN KEY (AnoProjeto, Sequencial) REFERENCES sac.Projetos (AnoProjeto, Sequencial)
);
CREATE TABLE sac.QuotasCav
(
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  AnoCav VARCHAR(4) NOT NULL,
  SequencialCav VARCHAR(4) NOT NULL,
  SequencialIC VARCHAR(5) NOT NULL,
  PublPriv int,
  Garantia int,
  Taxa MONEY,
  CgcCorretora VARCHAR(14),
  InclusCancel int NOT NULL,
  Motivo int DEFAULT 0 NOT NULL,
  NOficio VARCHAR(15) NOT NULL,
  DtOficio timestamp NOT NULL,
  DtInicioDistribuicao timestamp,
  DtFimDistribuicao timestamp,
  TaxaParticipacaoSubscritor MONEY,
  QtdQuotas INT DEFAULT 0 NOT NULL,
  VlQuota MONEY DEFAULT 0 NOT NULL,
  Logon INT NOT NULL,
  CONSTRAINT PK_QuotasCav PRIMARY KEY (AnoProjeto, Sequencial, AnoCav, SequencialCav, SequencialIC),
  CONSTRAINT Fk_QuotasCavProjetos FOREIGN KEY (AnoProjeto, Sequencial) REFERENCES sac.Projetos (AnoProjeto, Sequencial)
);
CREATE TABLE sac.SegmentoAudiovisualMidia
(
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  Produtor VARCHAR(100),
  Diretor VARCHAR(100),
  Roteirista VARCHAR(100),
  Metragem int,
  Genero int,
  VeiculacaoPrevista int,
  SuporteGravacao int,
  Finalizacao int,
  DuracaoTipo int,
  DuracaoQtde SMALLINT,
  DuracaoCada SMALLINT,
  DuracaoTotal SMALLINT,
  Tiragem INT,
  Periocidade int,
  Texto INTEGER DEFAULT 0 NOT NULL,
  FotoArte INTEGER DEFAULT 0 NOT NULL,
  VideoFilme INTEGER DEFAULT 0 NOT NULL,
  Logon INT NOT NULL,
  CONSTRAINT PK_SegmentoAudiovisualMid1 PRIMARY KEY (AnoProjeto, Sequencial),
  CONSTRAINT FK_SegAudioMidiaProjetos FOREIGN KEY (AnoProjeto, Sequencial) REFERENCES sac.Projetos (AnoProjeto, Sequencial),
  CONSTRAINT Fk_SegAudioMidiaMetragem FOREIGN KEY (Metragem) REFERENCES sac.Metragem (Codigo),
  CONSTRAINT Fk_SegAudioMidiaGenero FOREIGN KEY (Genero) REFERENCES sac.Genero (Codigo),
  CONSTRAINT Fk_SegAudioMidiaVeiculacao FOREIGN KEY (VeiculacaoPrevista) REFERENCES sac.Veiculacao (Codigo),
  CONSTRAINT Fk_SegAudioMidiaSuporte FOREIGN KEY (SuporteGravacao) REFERENCES sac.SuporteGravacaoFinalizacao (Codigo),
  CONSTRAINT Fk_SegAudioMidiaFinalizacao FOREIGN KEY (Finalizacao) REFERENCES sac.SuporteGravacaoFinalizacao (Codigo)
);
CREATE TABLE sac.SegmentoAudiovisualOutros
(
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  Campo1 INTEGER NOT NULL,
  Campo2 INTEGER NOT NULL,
  Campo3 INTEGER NOT NULL,
  Campo4 INTEGER NOT NULL,
  Campo5 int,
  DtInicioRealizacao timestamp,
  DtFimRealizacao timestamp,
  Logon INT NOT NULL,
  CONSTRAINT PK_SegmentoAudivisualOutr1 PRIMARY KEY (AnoProjeto, Sequencial),
  CONSTRAINT FK_SegAudioOutrosProjetos FOREIGN KEY (AnoProjeto, Sequencial) REFERENCES sac.Projetos (AnoProjeto, Sequencial)
);
CREATE TABLE sac.tbAbrangencia
(
  idAbrangencia INT PRIMARY KEY NOT NULL,
  idReadequacao INT,
  idPais INT NOT NULL,
  idUF INT,
  idMunicipioIBGE INT,
  tpSolicitacao CHAR NOT NULL,
  tpAnaliseTecnica CHAR DEFAULT 'N' NOT NULL,
  tpAnaliseComissao CHAR DEFAULT 'N' NOT NULL,
  stAtivo CHAR DEFAULT 'S' NOT NULL,
  idPronac INT NOT NULL,
  CONSTRAINT FK_tbAbrangencia_tbReadequacao FOREIGN KEY (idReadequacao) REFERENCES sac.tbReadequacao (idReadequacao)
);
CREATE TABLE sac.tbAcertaHistoricoX
(
  anoprojeto CHAR(2),
  sequencial VARCHAR(5),
  ultimadtsituacao timestamp,
  contador INT,
  situacao CHAR(3),
  logon INT
);
CREATE TABLE sac.tbAcesso
(
  idAcesso INT PRIMARY KEY NOT NULL,
  idRelatorio INT,
  dsAcesso VARCHAR(8000),
  dsPublicoAlvo VARCHAR(8000),
  qtPessoa INT,
  dsLocal VARCHAR(8000),
  dsEstruturaSolucao VARCHAR(8000),
  tpAcesso CHAR,
  stAcesso CHAR DEFAULT 0 NOT NULL,
  stQtPessoa CHAR DEFAULT 0,
  stPublicoAlvo CHAR DEFAULT 0,
  stLocal CHAR DEFAULT 0,
  stEstrutura CHAR,
  dsJustificativaAcesso TEXT,
  CONSTRAINT FK_tbAcesso_tbRelatorio FOREIGN KEY (idRelatorio) REFERENCES sac.tbRelatorio (idRelatorio)
);
CREATE TABLE sac.tbAnaliseAprovacao
(
  idAnaliseAprovacao INT PRIMARY KEY NOT NULL,
  tpAnalise CHAR(2) NOT NULL,
  dtAnalise timestamp NOT NULL,
  idAnaliseConteudo INT NOT NULL,
  IdPRONAC INT NOT NULL,
  idProduto int NOT NULL,
  stLei8313 INTEGER,
  stArtigo3 INTEGER,
  nrIncisoArtigo3 int,
  dsAlineaArt3 VARCHAR(50),
  stArtigo18 INTEGER,
  dsAlineaArtigo18 VARCHAR(50),
  stArtigo26 INTEGER,
  stLei5761 INTEGER,
  stArtigo27 INTEGER,
  stIncisoArtigo27_I INTEGER,
  stIncisoArtigo27_II INTEGER,
  stIncisoArtigo27_III INTEGER,
  stIncisoArtigo27_IV INTEGER,
  stAvaliacao INTEGER,
  dsAvaliacao VARCHAR,
  idAgente INT,
  idAnaliseAprovacaoPai INT,
CONSTRAINT FK_tbAnaliseAprovacao_tbAnaliseDeConteudo FOREIGN KEY (idAnaliseConteudo) REFERENCES sac.tbAnaliseDeConteudo (idAnaliseDeConteudo),
CONSTRAINT fk_tbAnaliseAprovacao_02 FOREIGN KEY (IdPRONAC) REFERENCES sac.Projetos (IdPRONAC),
CONSTRAINT fk_tbAnaliseAprovacao_01 FOREIGN KEY (idAnaliseAprovacaoPai) REFERENCES sac.tbAnaliseAprovacao (idAnaliseAprovacao)
);
CREATE INDEX IXIDPronac ON sac.tbAnaliseDeConteudo (idPronac DESC);
CREATE TABLE sac.tbAporteCaptacao
(
  idAporteCaptacao INT PRIMARY KEY NOT NULL,
  IdPRONAC INT NOT NULL,
  idVerificacao INT NOT NULL,
  CNPJCPF VARCHAR(14) NOT NULL,
  idContaBancaria INT NOT NULL,
  idUsuarioInterno SMALLINT NOT NULL,
  dtCredito timestamp NOT NULL,
  vlDeposito MONEY NOT NULL,
  nrLote INT NOT NULL,
  dtLote timestamp NOT NULL,
  CONSTRAINT fktbAporteCaptacao01 FOREIGN KEY (IdPRONAC) REFERENCES sac.Projetos (IdPRONAC),
  CONSTRAINT fkAporteCaptacao02 FOREIGN KEY (idVerificacao) REFERENCES sac.Verificacao (idVerificacao),
  CONSTRAINT FKtbAporteCaptacao04 FOREIGN KEY (idContaBancaria) REFERENCES sac.ContaBancaria (IdContaBancaria)
);
CREATE INDEX ixtbAporteCaptacao01 ON sac.tbAporteCaptacao (idAporteCaptacao, IdPRONAC);
CREATE TABLE sac.tbArquivamento
(
  idArquivamento INT PRIMARY KEY NOT NULL,
  idPronac INT NOT NULL,
  Data timestamp NOT NULL,
  Edificio INT NOT NULL,
  Andar INT,
  Sala VARCHAR(50),
  Armario VARCHAR(50),
  Prateleira VARCHAR(50),
  CaixaInicio INT NOT NULL,
  CaixaFinal INT NOT NULL,
  stAcao INTEGER DEFAULT 0 NOT NULL,
  stEstado INTEGER DEFAULT 1 NOT NULL,
  idUsuario INT NOT NULL,
  dsJustificativa VARCHAR,
CONSTRAINT FK_tbArquivamento_Projetos FOREIGN KEY (idPronac) REFERENCES sac.Projetos (IdPRONAC)
);
CREATE INDEX IX_tbArquivamento ON sac.tbArquivamento (idPronac);
CREATE TABLE sac.tbArquivoFiscalizacao
(
  idArquivoFiscalizacao INT PRIMARY KEY NOT NULL,
  idFiscalizacao INT NOT NULL,
  idArquivo INT NOT NULL,
  CONSTRAINT fk_tbArquivoFiscalizacao_tbFiscalizacao FOREIGN KEY (idFiscalizacao) REFERENCES sac.tbFiscalizacao (idFiscalizacao)
);
CREATE TABLE sac.tbAssinantes
(
  idAssinantes INT PRIMARY KEY NOT NULL,
  idOrgao INT NOT NULL,
  idAgente INT NOT NULL,
  idCargo INT NOT NULL,
  stEstado INTEGER DEFAULT 1 NOT NULL,
  CONSTRAINT FK_tbAssinantes_Orgaos FOREIGN KEY (idOrgao) REFERENCES sac.Orgaos (Codigo)
);
CREATE TABLE sac.tbAssinantesPrestacao
(
  idAssinantesPrestacao INT PRIMARY KEY NOT NULL,
  nmAssinante VARCHAR(255) NOT NULL,
  tpCargo INT DEFAULT 1 NOT NULL,
  dtCadastro timestamp NOT NULL,
  idUsuario INT NOT NULL,
  stAtivo INT DEFAULT 1 NOT NULL
);
CREATE TABLE sac.tbAvaliacaoFiscalizacao
(
  idAvaliacaoFiscalizacao INT PRIMARY KEY NOT NULL,
  idRelatorioFiscalizacao INT NOT NULL,
  idAvaliador INT NOT NULL,
  dtAvaliacaoFiscalizacao timestamp NOT NULL,
  dsParecer VARCHAR(8000) NOT NULL,
  CONSTRAINT FK_tbAvaliacaoFiscalizacao_tbRelatorioFiscalizacao FOREIGN KEY (idRelatorioFiscalizacao) REFERENCES sac.tbRelatorioFiscalizacao (idRelatorioFiscalizacao)
);
CREATE TABLE sac.tbBeneficiario
(
  idBeneficiario INT PRIMARY KEY NOT NULL,
  idRelatorio INT NOT NULL,
  nrCNPJ CHAR(14),
  nrCPF CHAR(11),
  dsBeneficiario VARCHAR(8000) NOT NULL,
  dsPublicoAlvo VARCHAR(8000),
  dsEntrega VARCHAR(8000),
  tpBeneficiario CHAR NOT NULL,
  stCNPJ CHAR DEFAULT 0 NOT NULL,
  stCPF CHAR DEFAULT 0 NOT NULL,
  stPublicoAlvo CHAR DEFAULT 0 NOT NULL,
  dsJustificativaAcompanhamento VARCHAR(600),
  CONSTRAINT FK_tbBeneficiario_tbRelatorio FOREIGN KEY (idRelatorio) REFERENCES sac.tbRelatorio (idRelatorio)
);
CREATE TABLE sac.tbBeneficiarioProdutoCultural
(
  idBeneficiarioProdutoCultural INT PRIMARY KEY NOT NULL,
  IdPRONAC INT NOT NULL,
  idAgente INT NOT NULL,
  idPlanoDistribuicao INT NOT NULL,
  idDocumento INT NOT NULL,
  qtRecebida INT NOT NULL,
  idTipoBeneficiario INT NOT NULL,
  CONSTRAINT fk_tbBeneficiarioProdutoCultural_02 FOREIGN KEY (IdPRONAC) REFERENCES sac.Projetos (IdPRONAC),
  CONSTRAINT fk_tbBeneficiarioProdutoCultural_03 FOREIGN KEY (idPlanoDistribuicao) REFERENCES sac.PlanoDistribuicaoProduto (idPlanoDistribuicao),
  CONSTRAINT fk_tbBeneficiarioProdutoCultural_04 FOREIGN KEY (idTipoBeneficiario) REFERENCES sac.Verificacao (idVerificacao)
);
CREATE TABLE sac.tbBensDoados
(
  idBensDoados INT PRIMARY KEY NOT NULL,
  idPronac INT NOT NULL,
  dtCadastroDoacao timestamp NOT NULL,
  idItemOrcamentario INT,
  tpBem CHAR NOT NULL,
  idAgente INT NOT NULL,
  qtBensDoados INT NOT NULL,
  dsObservacao VARCHAR(1000),
  idDocumentoDoacao INT NOT NULL,
  idDocumentoAceite INT NOT NULL,
  idUsuarioCadastrador INT NOT NULL,
  CONSTRAINT FK_tbBensDoados_Projetos FOREIGN KEY (idPronac) REFERENCES sac.Projetos (IdPRONAC)
);
CREATE TABLE sac.tbComprovanteBeneficiario
(
  idComprovanteBeneficiario INT PRIMARY KEY NOT NULL,
  idRelatorio INT NOT NULL,
  idDocumento INT,
  stDocumento CHAR DEFAULT 0 NOT NULL,
  dsJustificativaAcompanhamento TEXT,
  CONSTRAINT FK_tbComprovanteBeneficiario_tbRelatorio FOREIGN KEY (idRelatorio) REFERENCES sac.tbRelatorio (idRelatorio)
);
CREATE TABLE sac.tbComprovanteExecucao
(
  idComprovanteExecucao INT PRIMARY KEY NOT NULL,
  idDocumento INT NOT NULL,
  idRelatorio INT,
  stDocumento CHAR DEFAULT 0 NOT NULL,
  dsJustificativaAcompanhamento TEXT,
  CONSTRAINT FK_tbComprovanteExecucao_tbRelatorio FOREIGN KEY (idRelatorio) REFERENCES sac.tbRelatorio (idRelatorio)
);
CREATE TABLE sac.tbComprovanteTrimestral
(
  idComprovanteTrimestral INT PRIMARY KEY NOT NULL,
  IdPRONAC INT NOT NULL,
  dtComprovante timestamp NOT NULL,
  dtInicioPeriodo timestamp NOT NULL,
  dtFimPeriodo timestamp NOT NULL,
  dsEtapasExecutadas VARCHAR,
  dsAcessibilidade VARCHAR,
  dsDemocratizacaoAcesso VARCHAR,
  dsImpactoAmbiental VARCHAR,
  siComprovanteTrimestral CHAR DEFAULT 1 NOT NULL,
  nrComprovanteTrimestral int NOT NULL,
  idCadastrador CHAR(14) NOT NULL,
  dsParecerTecnico VARCHAR,
  dsRecomendacao VARCHAR,
  idTecnicoAvaliador SMALLINT,
CONSTRAINT fk_tbComprovanteTrimestral_02 FOREIGN KEY (IdPRONAC) REFERENCES sac.Projetos (IdPRONAC)
);
CREATE INDEX IX_tbComprovanteTrimestral ON sac.tbComprovanteTrimestral (IdPRONAC);
CREATE TABLE sac.tbComunicados
(
  idComunicado INT PRIMARY KEY NOT NULL,
  Comunicado VARCHAR NOT NULL,
  idSistema INT NOT NULL,
  stOpcao INTEGER DEFAULT 0,
  stEstado INTEGER DEFAULT 1 NOT NULL,
  dtInicioVigencia timestamp,
  dtTerminoVigencia timestamp,
  idEdital INT,
CONSTRAINT FK_tbComunicados_tbComunicados FOREIGN KEY (idComunicado) REFERENCES sac.tbComunicados (idComunicado)
);

CREATE TABLE sac.tbConfigurarPagamentoXtbAssinantes
(
  idConfigurarPagamento INT NOT NULL,
  idAssinantes INT NOT NULL,
  nrOrdenacao int NOT NULL,
  CONSTRAINT FK_tbConfigurarPagamentoXtbAssinantes_tbConfigurarPagamento FOREIGN KEY (idConfigurarPagamento) REFERENCES sac.tbConfigurarPagamento (idConfigurarPagamento),
  CONSTRAINT FK_tbConfigurarPagamentoXtbAssinantes_tbAssinantes FOREIGN KEY (idAssinantes) REFERENCES sac.tbAssinantes (idAssinantes)
);
CREATE INDEX IX_tbConfigurarPagamentoXtbAssinantes ON sac.tbConfigurarPagamentoXtbAssinantes (idConfigurarPagamento);
CREATE INDEX IX_tbConfigurarPagamentoXtbAssinantes_1 ON sac.tbConfigurarPagamentoXtbAssinantes (idAssinantes);
CREATE TABLE sac.tbCumprimentoObjeto
(
  idCumprimentoObjeto INT PRIMARY KEY NOT NULL,
  idPronac INT NOT NULL,
  dtCadastro timestamp NOT NULL,
  dsEtapasConcluidas VARCHAR,
  dsMedidasAcessibilidade VARCHAR,
  dsMedidasFruicao VARCHAR,
  dsMedidasPreventivas VARCHAR,
  dsInformacaoAdicional VARCHAR,
  dsOrientacao VARCHAR,
  dsConclusao VARCHAR,
  stResultadoAvaliacao CHAR,
  idUsuarioCadastrador INT,
  idTecnicoAvaliador INT,
  siCumprimentoObjeto CHAR DEFAULT 1 NOT NULL,
  idChefiaImediata INT,
  qtEmpregosDiretos INT DEFAULT 0,
  qtEmpregosIndiretos INT DEFAULT 0,
  dsGeracaoEmpregos VARCHAR,
  DtEnvioDaPrestacaoContas timestamp,
CONSTRAINT FK_tbCumprimentoObjeto_Projetos FOREIGN KEY (idPronac) REFERENCES sac.Projetos (IdPRONAC)
);

CREATE TABLE sac.tbDescricaoRelatorioConsolidado
(
  idDescricaoRelatorioConsolidado INT PRIMARY KEY NOT NULL,
  idRelatorioConsolidado INT,
  dsObjetivosMetas TEXT,
  dsEstrategiaAcao TEXT,
  dsCronogramaFisico TEXT,
  dsRepercussao TEXT,
  dsImpactoAmbiental TEXT,
  dsImpactoCultural TEXT,
  dsImpactoEconomico TEXT,
  dsImpactoSocial TEXT,
  dsTermoProjeto TEXT,
  dsJustificativaAcompanhamento TEXT,
  dsJustificativaImpactoAmbiental TEXT,
  CONSTRAINT FK_tbDsRelatorioConsolidado_tbRelatorioConsolidado FOREIGN KEY (idRelatorioConsolidado) REFERENCES sac.tbRelatorioConsolidado (idRelatorioConsolidado)
);
CREATE TABLE sac.tbDeslocamento
(
  idDeslocamento INT PRIMARY KEY NOT NULL,
  idProjeto INT NOT NULL,
  idPaisOrigem INT NOT NULL,
  idUFOrigem INT,
  idMunicipioOrigem INT,
  idPaisDestino INT,
  idUFDestino INT,
  idMunicipioDestino INT,
  Qtde INT DEFAULT 0 NOT NULL,
  idUsuario INT NOT NULL,
  CONSTRAINT FK_tbDeslocamento_tbDeslocamento FOREIGN KEY (idProjeto) REFERENCES sac.PreProjeto (idPreProjeto)
);
CREATE SEQUENCE sac.tbdeslocamento_iddeslocamento_seq NO MINVALUE NO MAXVALUE NO CYCLE;
ALTER TABLE sac.tbdeslocamento ALTER COLUMN iddeslocamento SET DEFAULT nextval('sac.tbdeslocamento_iddeslocamento_seq');
ALTER SEQUENCE sac.tbdeslocamento_iddeslocamento_seq OWNED BY sac.tbdeslocamento.iddeslocamento;
CREATE TABLE sac.tbDespacho
(
  idDespacho INT PRIMARY KEY NOT NULL,
  idPronac INT,
  idProposta INT NOT NULL,
  Tipo INT NOT NULL,
  Data timestamp NOT NULL,
  Despacho VARCHAR NOT NULL,
  stEstado INTEGER DEFAULT 0 NOT NULL,
  idUsuario INT NOT NULL,
CONSTRAINT FK_tbDespacho_tbDespacho FOREIGN KEY (idPronac) REFERENCES sac.Projetos (IdPRONAC),
CONSTRAINT FK_tbDespacho_PreProjeto FOREIGN KEY (idProposta) REFERENCES sac.PreProjeto (idPreProjeto),
CONSTRAINT FK_tbDespacho_Verificacao FOREIGN KEY (Tipo) REFERENCES sac.Verificacao (idVerificacao)
);
CREATE INDEX IX_tbDespacho ON sac.tbDespacho (idPronac);
CREATE INDEX IX_tbDespacho_1 ON sac.tbDespacho (idProposta);
CREATE TABLE sac.tbDiligencia
(
  idDiligencia INT PRIMARY KEY NOT NULL,
  idPronac INT NOT NULL,
  idTipoDiligencia INT NOT NULL,
  DtSolicitacao timestamp NOT NULL,
  Solicitacao VARCHAR NOT NULL,
  idSolicitante INT NOT NULL,
  DtResposta timestamp,
  Resposta VARCHAR,
  idProponente INT,
  stEstado INTEGER DEFAULT 0 NOT NULL,
  idPlanoDistribuicao INT,
  idArquivo INT,
  idCodigoDocumentosExigidos INT,
  idProduto int,
  stProrrogacao CHAR,
  stEnviado CHAR,
CONSTRAINT FK_tbDiligencia_tbDiligencia FOREIGN KEY (idPronac) REFERENCES sac.Projetos (IdPRONAC),
CONSTRAINT FK_tbDiligencia_tbTipoDiligencia FOREIGN KEY (idTipoDiligencia) REFERENCES sac.Verificacao (idVerificacao),
CONSTRAINT fk_tbDiligencia_03 FOREIGN KEY (idPlanoDistribuicao) REFERENCES sac.PlanoDistribuicaoProduto (idPlanoDistribuicao),
CONSTRAINT FK_tbDiligencia_DocumentosExigidos FOREIGN KEY (idCodigoDocumentosExigidos) REFERENCES sac.DocumentosExigidos (Codigo),
CONSTRAINT FK_tbDiligencia_Produto FOREIGN KEY (idProduto) REFERENCES sac.Produto (Codigo)
);
CREATE INDEX IX_tbDiligencia ON sac.tbDiligencia (idPronac);
CREATE TABLE sac.tbDiligenciaxArquivo
(
  idDiligencia INT NOT NULL,
  idArquivo INT NOT NULL,
  CONSTRAINT fk_tbDiligenciaxArquivo_02 FOREIGN KEY (idDiligencia) REFERENCES sac.tbDiligencia (idDiligencia)
);
CREATE TABLE sac.tbDistribuirParecer
(
  idDistribuirParecer INT PRIMARY KEY NOT NULL,
  idPRONAC INT NOT NULL,
  idProduto INT DEFAULT 0 NOT NULL,
  TipoAnalise int DEFAULT 0 NOT NULL,
  idOrgao INT,
  DtEnvio timestamp,
  idAgenteParecerista INT,
  DtDistribuicao timestamp,
  DtDevolucao timestamp,
  Observacao VARCHAR DEFAULT '',
  stEstado INTEGER DEFAULT 0,
  stPrincipal INTEGER DEFAULT 0,
  FecharAnalise CHAR DEFAULT 0,
  DtRetorno timestamp,
  idUsuario INT,
  stDiligenciado INTEGER DEFAULT 0,
CONSTRAINT FK_tbDistribuirParecer_Projetos FOREIGN KEY (idPRONAC) REFERENCES sac.Projetos (IdPRONAC)
);
CREATE INDEX IX_tbDistribuirParecer ON sac.tbDistribuirParecer (idPRONAC, stEstado, FecharAnalise);
CREATE INDEX IX_tbDistribuirParecer_idProduto ON sac.tbDistribuirParecer (idProduto);
CREATE INDEX IX_tbDistribuirParecer_idPronac ON sac.tbDistribuirParecer (idPRONAC);
CREATE INDEX IX_tbDistribuirParecer_AgenteParecerista ON sac.tbDistribuirParecer (idAgenteParecerista);
CREATE INDEX IX_tbDistribuirParecer_FecharAnalise ON sac.tbDistribuirParecer (FecharAnalise);
CREATE INDEX IX_tbDistribuirParecer_stPrincipal ON sac.tbDistribuirParecer (stPrincipal);
CREATE INDEX IX_tbDistribuirParecer_stEstado ON sac.tbDistribuirParecer (stEstado);
CREATE INDEX IX_tbDistribuirParecer_TipoAnalise ON sac.tbDistribuirParecer (TipoAnalise);
CREATE TABLE sac.tbDistribuirProjeto
(
  idDistribuirProjeto INT PRIMARY KEY NOT NULL,
  tpDistribuicao CHAR DEFAULT 'A' NOT NULL,
  IdPRONAC INT NOT NULL,
  idUnidade INT NOT NULL,
  dtEnvio timestamp NOT NULL,
  idAvaliador INT,
  dtDistribuicao timestamp,
  dtDevolucao timestamp,
  dsObservacao VARCHAR(1000) DEFAULT '',
  stFecharAnalise INTEGER DEFAULT 0,
  dtFechamento timestamp,
  stEstado INTEGER DEFAULT 0,
  idUsuario INT NOT NULL,
  CONSTRAINT FK_tbDistribuirProjeto FOREIGN KEY (IdPRONAC) REFERENCES sac.Projetos (IdPRONAC),
  CONSTRAINT FK_tbDistribuirProjeto_Projetos FOREIGN KEY (IdPRONAC) REFERENCES sac.Projetos (IdPRONAC)
);
CREATE TABLE sac.tbDistribuirReadequacao
(
  idDistribuirReadequacao INT PRIMARY KEY NOT NULL,
  idReadequacao INT NOT NULL,
  idUnidade INT NOT NULL,
  DtEncaminhamento timestamp NOT NULL,
  idAvaliador INT,
  DtEnvioAvaliador timestamp,
  dsOrientacao VARCHAR(1000),
  DtRetornoAvaliador timestamp,
  stValidacaoCoordenador INTEGER DEFAULT 0,
  DtValidacaoCoordenador timestamp,
  idCoordenador INT,
  CONSTRAINT FK_tbDistribuirReadequacao_tbReadequacao FOREIGN KEY (idReadequacao) REFERENCES sac.tbReadequacao (idReadequacao)
);
CREATE INDEX IX_tbDistribuirReadequacao ON sac.tbDistribuirReadequacao (idReadequacao);
CREATE TABLE sac.tbDocumento
(
  idDocumento INT PRIMARY KEY NOT NULL,
  idPronac INT NOT NULL,
  stEstado int NOT NULL,
  idTipoDocumento INT NOT NULL,
  idUsuario SMALLINT NOT NULL,
  dtDocumento timestamp NOT NULL,
  NoArquivo VARCHAR(130),
  TaArquivo INT,
  idUsuarioJuntada SMALLINT,
  dtJuntada timestamp,
  idUnidadeCadastro SMALLINT DEFAULT 0,
  CodigoCorreio VARCHAR(13),
  biDocumento varchar,
  imDocumento VARCHAR,
CONSTRAINT FK_tbDocumento_Projetos FOREIGN KEY (idPronac) REFERENCES sac.Projetos (IdPRONAC),
CONSTRAINT FK_tbDocumento_tbTipoDocumento FOREIGN KEY (idTipoDocumento) REFERENCES sac.tbTipoDocumento (idTipoDocumento)
);
CREATE INDEX IX_tbDocumento ON sac.tbDocumento (idPronac);
CREATE TABLE sac.tbDocumento20100107X
(
  idDocumento INT PRIMARY KEY NOT NULL,
  idPronac INT NOT NULL,
  stEstado int NOT NULL,
  imDocumento VARCHAR,
  idTipoDocumento INT NOT NULL,
  idUsuario SMALLINT NOT NULL,
  dtDocumento timestamp NOT NULL,
  NoArquivo VARCHAR(100),
  TaArquivo INT,
  idUsuarioJuntada SMALLINT,
  dtJuntada timestamp,
  idUnidadeCadastro SMALLINT,
  CodigoCorreio VARCHAR(13)
);
CREATE TABLE sac.tbDocumentoAceitacao
(
  idDocumentoAceitacao INT PRIMARY KEY NOT NULL,
  idDocumento INT,
  idRelatorioConsolidado INT NOT NULL,
  stDocumento CHAR DEFAULT 0 NOT NULL,
  dsJustificativaAcompanhamento TEXT,
  CONSTRAINT fk_tbDocumentoAceitacao_tbRelatorioConsolidado FOREIGN KEY (idRelatorioConsolidado) REFERENCES sac.tbRelatorioConsolidado (idRelatorioConsolidado)
);
CREATE TABLE sac.tbDocumentosAgentes
(
  idDocumentosAgentes INT PRIMARY KEY NOT NULL,
  CodigoDocumento INT NOT NULL,
  idAgente INT NOT NULL,
  Data timestamp NOT NULL,
  NoArquivo VARCHAR(130),
  TaArquivo INT NOT NULL,
  imDocumento VARCHAR,
CONSTRAINT FK_tbDocAgentesDocExigidos FOREIGN KEY (CodigoDocumento) REFERENCES sac.DocumentosExigidos (Codigo)
);
CREATE INDEX IX_tbDocumentosAgentes ON sac.tbDocumentosAgentes (idAgente);
CREATE SEQUENCE sac.tbdocumentosagentes_iddocumentosagentes_seq NO MINVALUE NO MAXVALUE NO CYCLE;
ALTER TABLE sac.tbdocumentosagentes ALTER COLUMN iddocumentosagentes SET DEFAULT nextval('sac.tbdocumentosagentes_iddocumentosagentes_seq');
ALTER SEQUENCE sac.tbdocumentosagentes_iddocumentosagentes_seq OWNED BY sac.tbdocumentosagentes.iddocumentosagentes;
CREATE TABLE sac.tbDocumentosAgentes20100107X
(
  idDocumentosAgentes INT NOT NULL,
  CodigoDocumento INT NOT NULL,
  idAgente INT NOT NULL,
  Data timestamp NOT NULL,
  imDocumento VARCHAR NOT NULL,
  NoArquivo VARCHAR(100) NOT NULL,
  TaArquivo INT NOT NULL
);
CREATE TABLE sac.tbDocumentosPreProjeto
(
  idDocumentosPreprojetos INT PRIMARY KEY NOT NULL,
  CodigoDocumento INT NOT NULL,
  idProjeto INT NOT NULL,
  idPRONAC INT,
  Data timestamp NOT NULL,
  NoArquivo VARCHAR(130),
  TaArquivo INT NOT NULL,
  biDocumento varchar,
  dsDocumento VARCHAR(1000),
  imDocumento VARCHAR,
CONSTRAINT FK_tbDocPreProjetosDocExigidos FOREIGN KEY (CodigoDocumento) REFERENCES sac.DocumentosExigidos (Codigo),
CONSTRAINT FK_tbDocumentosPreProjeto_PreProjeto FOREIGN KEY (idProjeto) REFERENCES sac.PreProjeto (idPreProjeto),
CONSTRAINT FK_tbDocumentosPreProjeto_Projetos FOREIGN KEY (idPRONAC) REFERENCES sac.Projetos (IdPRONAC)
);
CREATE INDEX IX_tbDocumentosPreProjeto ON sac.tbDocumentosPreProjeto (idProjeto);
CREATE SEQUENCE sac.tbdocumentospreprojeto_iddocumentospreprojetos_seq NO MINVALUE NO MAXVALUE NO CYCLE;
ALTER TABLE sac.tbdocumentospreprojeto ALTER COLUMN iddocumentospreprojetos SET DEFAULT nextval('sac.tbdocumentospreprojeto_iddocumentospreprojetos_seq');
ALTER SEQUENCE sac.tbdocumentospreprojeto_iddocumentospreprojetos_seq OWNED BY sac.tbdocumentospreprojeto.iddocumentospreprojetos;
CREATE TABLE sac.tbGerarPagamentoParecerista
(
  idGerarPagamentoParecerista INT PRIMARY KEY NOT NULL,
  idConfigurarPagamento INT NOT NULL,
  dtGeracaoPagamento timestamp NOT NULL,
  dtEfetivacaoPagamento timestamp,
  dtOrdemBancaria timestamp,
  nrOrdemBancaria CHAR(12),
  nrDespacho VARCHAR(10) NOT NULL,
  siPagamento CHAR NOT NULL,
  vlTotalPagamento DECIMAL(18,2) NOT NULL,
  idUsuario INT NOT NULL,
  CONSTRAINT FK_tbGerarPagamentoParecerista_tbConfigurarPagamento FOREIGN KEY (idConfigurarPagamento) REFERENCES sac.tbConfigurarPagamento (idConfigurarPagamento)
);
CREATE TABLE sac.tbHistoricoAlteracaoDocumento
(
  idHistoricoAlteracaoDocumento INT PRIMARY KEY NOT NULL,
  idDocumento INT NOT NULL,
  idHistoricoAlteracaoProjeto INT NOT NULL,
  idDocumentosExigidos INT NOT NULL,
  CONSTRAINT fk_tbHistoricoAlteracaoProjetoxtbDocumento_DocumentosExigidos FOREIGN KEY (idDocumentosExigidos) REFERENCES sac.DocumentosExigidos (Codigo)
);
CREATE TABLE sac.tbHistoricoAlteracaoProjeto
(
  idHistoricoAlteracaoProjeto INT PRIMARY KEY NOT NULL,
  cdArea CHAR,
  cdSegmento CHAR(4),
  nmProjeto VARCHAR(300),
  cdSituacao CHAR(3),
  cdOrgao INT,
  dtInicioExecucao timestamp,
  dtFimExecucao timestamp,
  idLogon INT,
  idDocumento INT,
  idPRONAC INT,
  idEnquadramento INT,
  dtHistoricoAlteracaoProjeto timestamp NOT NULL,
  dsHistoricoAlteracaoProjeto TEXT NOT NULL,
  cgccpf VARCHAR(14),
  dsProvidenciaTomada VARCHAR(300),
  CONSTRAINT FK_tbHistoricoAlteracaoProjeto_Projetos FOREIGN KEY (idPRONAC) REFERENCES sac.Projetos (IdPRONAC)
);
CREATE TABLE sac.tbHistoricoContaBloqueada
(
  idHistoricoContaBloqueada INT NOT NULL,
  idContaBancaria INT NOT NULL,
  siContaBloqueada INTEGER NOT NULL,
  tpContaBloqueada INTEGER NOT NULL,
  dtBloqueio timestamp NOT NULL,
  dtDesbloqueio timestamp,
  dsJustificativa VARCHAR(500) NOT NULL,
  stContaBloqueada INTEGER NOT NULL,
  idArquivo INT NOT NULL,
  idUsuarioInterno SMALLINT NOT NULL,
  CONSTRAINT pktbContaBloqueada PRIMARY KEY (idHistoricoContaBloqueada, idContaBancaria),
  CONSTRAINT fktbContaBloqueada01 FOREIGN KEY (idContaBancaria) REFERENCES sac.ContaBancaria (IdContaBancaria)
);
CREATE INDEX ixtbHistoricoContaBloqueada01 ON sac.tbHistoricoContaBloqueada (idContaBancaria, siContaBloqueada);
CREATE TABLE sac.tbHistoricoDevolucaoFiscalizacao
(
  idHistoricoDevolucao INT PRIMARY KEY NOT NULL,
  idRelatorioFiscalizacao INT NOT NULL,
  dsJustificativaDevolucao VARCHAR(8000) NOT NULL,
  dtEnvioDevolucao timestamp NOT NULL,
  stDevolucao CHAR DEFAULT '0' NOT NULL,
  CONSTRAINT FK_tbHistoricoDevolucaoFiscalizacao_tbRelatorioFiscalizacao FOREIGN KEY (idRelatorioFiscalizacao) REFERENCES sac.tbRelatorioFiscalizacao (idRelatorioFiscalizacao)
);
CREATE TABLE sac.tbHistoricoDocumento
(
  idHistorico INT PRIMARY KEY NOT NULL,
  idPronac INT NOT NULL,
  idDocumento INT,
  idOrigem INT,
  idUnidade INT NOT NULL,
  dtTramitacaoEnvio timestamp,
  idUsuarioEmissor SMALLINT DEFAULT 0 NOT NULL,
  meDespacho VARCHAR(250) DEFAULT ' ',
  idLote INT,
  dtTramitacaoRecebida timestamp,
  idUsuarioReceptor SMALLINT,
  Acao int,
  stEstado INTEGER NOT NULL,
  dsJustificativa VARCHAR,
CONSTRAINT FK_tbHistorico_Projetos FOREIGN KEY (idPronac) REFERENCES sac.Projetos (IdPRONAC),
CONSTRAINT FK_tbHistorico_tbDocumento FOREIGN KEY (idDocumento) REFERENCES sac.tbDocumento (idDocumento),
CONSTRAINT FK_tbHistorico_tbLote FOREIGN KEY (idLote) REFERENCES sac.tbLote (idLote)
);
CREATE INDEX IX_tbHistoricoDocumento ON sac.tbHistoricoDocumento (idPronac);
CREATE INDEX IX_tbHistoricoDocumento_1 ON sac.tbHistoricoDocumento (idPronac, idDocumento);
CREATE TABLE sac.tbHistoricoEmail
(
  idHistoricoEmail INT PRIMARY KEY NOT NULL,
  idProjeto INT,
  idPRONAC INT,
  idAvaliacaoProposta INT,
  idTextoEmail INT DEFAULT 0,
  DtEmail timestamp NOT NULL,
  stEstado INTEGER DEFAULT 0 NOT NULL,
  idUsuario INT DEFAULT 0 NOT NULL,
  CONSTRAINT FK_tbHistoricoEmail_Projetos FOREIGN KEY (idPRONAC) REFERENCES sac.Projetos (IdPRONAC),
  CONSTRAINT FK_tbHistoricoEmail_tbAprovacaoProposta FOREIGN KEY (idAvaliacaoProposta) REFERENCES sac.tbAvaliacaoProposta (idAvaliacaoProposta),
  CONSTRAINT FK_tbHistoricoEmail_tbTextoEmail FOREIGN KEY (idTextoEmail) REFERENCES sac.tbTextoEmail (idTextoemail)
);
CREATE TABLE sac.tbHistoricoExclusaoConta
(
  idHistoricoExclusaoConta INT PRIMARY KEY NOT NULL,
  idContaBancaria INT NOT NULL,
  Banco CHAR(3) NOT NULL,
  Agencia VARCHAR(5) NOT NULL,
  ContaBloqueada CHAR(12) NOT NULL,
  ContaLivre CHAR(12) NOT NULL,
  DtExclusao timestamp NOT NULL,
  Motivo VARCHAR(500) NOT NULL,
  idUsuario INT NOT NULL,
  CONSTRAINT FK_tbHistoricoExclusaoConta_ContaBancaria FOREIGN KEY (idContaBancaria) REFERENCES sac.ContaBancaria (IdContaBancaria)
);
CREATE INDEX IX_tbHistoricoExclusaoConta ON sac.tbHistoricoExclusaoConta (idContaBancaria);
CREATE TABLE sac.tINTEGERensPlanilhaProduto
(
  idItensPlanilhaProduto INT PRIMARY KEY NOT NULL,
  idProduto INT NOT NULL,
  idPlanilhaEtapa INT NOT NULL,
  idPlanilhaItens INT NOT NULL,
  idUsuario INT,
  CONSTRAINT FK_tINTEGERensPlanilhaProduto_tbPlanilhaEtapa FOREIGN KEY (idPlanilhaEtapa) REFERENCES sac.tbPlanilhaEtapa (idPlanilhaEtapa),
  CONSTRAINT FK_tINTEGERensPlanilhaProduto_tbPlanilhaItens FOREIGN KEY (idPlanilhaItens) REFERENCES sac.tbPlanilhaItens (idPlanilhaItens)
);
CREATE UNIQUE INDEX IX_tINTEGERensPlanilhaProduto ON sac.tINTEGERensPlanilhaProduto (idProduto, idPlanilhaEtapa, idPlanilhaItens);
CREATE TABLE sac.tbLancamentoBancario
(
  idLancamentoBancario INT PRIMARY KEY NOT NULL,
  idPronac INT,
  idMovimentacaoBancariaItem INT NOT NULL,
  stContaLancamento INTEGER DEFAULT 0 NOT NULL,
  nrAgenciaLancamento CHAR(5) NOT NULL,
  nrContaLancamento CHAR(12) NOT NULL,
  cdLancamento CHAR(4) NOT NULL,
  dsLancamento VARCHAR(25) NOT NULL,
  nrLancamento CHAR(10) NOT NULL,
  dtLancamento timestamp NOT NULL,
  vlLancamento DECIMAL(16,2) NOT NULL,
  stLancamento CHAR NOT NULL,
  CONSTRAINT FK_tbLancamentoBancario_Projetos FOREIGN KEY (idPronac) REFERENCES sac.Projetos (IdPRONAC),
  CONSTRAINT FK_tbLancamentoBancario_tbMovimentacaoBancaria FOREIGN KEY (idMovimentacaoBancariaItem) REFERENCES sac.tbMovimentacaoBancariaItem (idMovimentacaoBancariaItem)
);
CREATE INDEX IX_tbLancamentoBancario ON sac.tbLancamentoBancario (idPronac);
CREATE INDEX IX_tbLancamentoBancario_1 ON sac.tbLancamentoBancario (idPronac, nrLancamento);
CREATE INDEX IX_tbLancamentoBancario_2 ON sac.tbLancamentoBancario (nrLancamento);
CREATE TABLE sac.tbLaudoFinal
(
  idLaudoFinal INT PRIMARY KEY NOT NULL,
  idPronac INT NOT NULL,
  nmCoordIncentivos VARCHAR(255) NOT NULL,
  nmCoordPrestacao VARCHAR(255) NOT NULL,
  nmDiretor VARCHAR(255) NOT NULL,
  nmSecretario VARCHAR(255) NOT NULL,
  dtLaudoFinal timestamp NOT NULL
);
CREATE TABLE sac.tbLogomarca
(
  idLogomarca INT PRIMARY KEY NOT NULL,
  idPlanoDivulgacao INT NOT NULL,
  dsPosicao VARCHAR(8000),
  idDocumento INT,
  CONSTRAINT fk_tbLogomarca_PlanoDeDivulgacao FOREIGN KEY (idPlanoDivulgacao) REFERENCES sac.PlanoDeDivulgacao (idPlanoDivulgacao)
);
CREATE TABLE sac.tbModeloTermoDecisao
(
  idModeloTermoDecisao INT PRIMARY KEY NOT NULL,
  idOrgao INT NOT NULL,
  idVerificacao INT NOT NULL,
  stModeloTermoDecisao INTEGER NOT NULL,
  meModeloTermoDecisao VARCHAR NOT NULL,
CONSTRAINT fktbModeloTermoDecisao01 FOREIGN KEY (idOrgao) REFERENCES sac.Orgaos (Codigo),
CONSTRAINT fktbModeloTermoDecisao02 FOREIGN KEY (idVerificacao) REFERENCES sac.Verificacao (idVerificacao)
);
CREATE TABLE sac.tbMovimentacao
(
  idMovimentacao INT PRIMARY KEY NOT NULL,
  idProjeto INT NOT NULL,
  Movimentacao INT NOT NULL,
  DtMovimentacao timestamp NOT NULL,
  stEstado INTEGER DEFAULT 0 NOT NULL,
  Usuario INT DEFAULT 0 NOT NULL,
  CONSTRAINT FK_tbMovimentacao_PreProjeto FOREIGN KEY (idProjeto) REFERENCES sac.PreProjeto (idPreProjeto),
  CONSTRAINT FK_Movimentacao_PreProjeto FOREIGN KEY (idProjeto) REFERENCES sac.PreProjeto (idPreProjeto),
  CONSTRAINT FK_Movimentacao_Verificacao FOREIGN KEY (Movimentacao) REFERENCES sac.Verificacao (idVerificacao)
);
CREATE INDEX IX_Movimentacao ON sac.tbMovimentacao (idProjeto);
CREATE SEQUENCE sac.tbmovimentacao_idmovimentacao_seq NO MINVALUE NO MAXVALUE NO CYCLE;
ALTER TABLE sac.tbmovimentacao ALTER COLUMN idmovimentacao SET DEFAULT nextval('sac.tbmovimentacao_idmovimentacao_seq');
ALTER SEQUENCE sac.tbmovimentacao_idmovimentacao_seq OWNED BY sac.tbmovimentacao.idmovimentacao;
CREATE TABLE sac.tbMovimentacaoBancariaItemxTipoInconsistencia
(
  idMovimentacaoBancariaItem INT NOT NULL,
  idTipoInconsistencia INT NOT NULL,
  CONSTRAINT pk_tbMovimentacaoBancariaItemxTipoInconsistencia PRIMARY KEY (idMovimentacaoBancariaItem, idTipoInconsistencia),
  CONSTRAINT FK_tbMovimentacaoBancariaItemxTipoInconsistencia_tbMovimentacaoBancariaItem FOREIGN KEY (idMovimentacaoBancariaItem) REFERENCES sac.tbMovimentacaoBancariaItem (idMovimentacaoBancariaItem),
  CONSTRAINT FK_tbMovimentacaoBancariaItemxTipoInconsistencia_tbTipoInconsistencia FOREIGN KEY (idTipoInconsistencia) REFERENCES sac.tbTipoInconsistencia (idTipoInconsistencia)
);
CREATE TABLE sac.tbOpinarProjeto
(
  idOpinarProjeto INT PRIMARY KEY NOT NULL,
  idPronac INT NOT NULL,
  idVisao INT NOT NULL,
  siFaseProjeto CHAR NOT NULL,
  dtOpiniao timestamp NOT NULL,
  stQuestionamento_1 CHAR DEFAULT '0',
  stQuestionamento_2 CHAR DEFAULT '0',
  stQuestionamento_3 CHAR DEFAULT '0',
  dsComentario VARCHAR(250),
  dsEmail VARCHAR(100),
  CONSTRAINT FK_tbOpinarProjeto_Projetos FOREIGN KEY (idPronac) REFERENCES sac.Projetos (IdPRONAC),
  CONSTRAINT FK_tbOpinarProjeto_Verificacao FOREIGN KEY (idVisao) REFERENCES sac.Verificacao (idVerificacao)
);
CREATE TABLE sac.tbOrgaoFiscalizador
(
  idOrgaoFiscalizador INT PRIMARY KEY NOT NULL,
  idOrgao INT NOT NULL,
  idFiscalizacao INT NOT NULL,
  dsObservacao VARCHAR(8000),
  dtRecebimentoResposta timestamp,
  dtConfirmacaoFiscalizacao timestamp,
  idResponsavelConfirmacao INT,
  idParecerista INT,
  CONSTRAINT fk_tbOrgaoFiscalizador_Orgaos FOREIGN KEY (idOrgao) REFERENCES sac.Orgaos (Codigo),
  CONSTRAINT fk_tbOrgaoFiscalizador_tbFiscalizacao FOREIGN KEY (idFiscalizacao) REFERENCES sac.tbFiscalizacao (idFiscalizacao)
);
CREATE TABLE sac.tbPagamentoPareceristaXArquivo
(
  idGerarPagamentoParecerista INT NOT NULL,
  idArquivo INT NOT NULL,
  siArquivo CHAR DEFAULT 1 NOT NULL,
  CONSTRAINT FK_tbPagamentoPareceristaXArquivo_tbGerarPagamentoParecerista FOREIGN KEY (idGerarPagamentoParecerista) REFERENCES sac.tbGerarPagamentoParecerista (idGerarPagamentoParecerista)
);
CREATE TABLE sac.tbPagarParecerista
(
  idPagarParecerista INT PRIMARY KEY NOT NULL,
  idNrReuniao INT,
  idParecerista INT NOT NULL,
  idPronac INT NOT NULL,
  idProduto int NOT NULL,
  idUnidadeAnalise INT,
  idGerarPagamentoParecerista INT,
  vlPagamento DECIMAL(18,2) NOT NULL,
  CONSTRAINT FK_tbPagarParecerista_Projetos FOREIGN KEY (idPronac) REFERENCES sac.Projetos (IdPRONAC),
  CONSTRAINT FK_tbPagarParecerista_Produto FOREIGN KEY (idProduto) REFERENCES sac.Produto (Codigo),
  CONSTRAINT FK_tbPagarParecerista_tbGerarPagamentoParecerista1 FOREIGN KEY (idGerarPagamentoParecerista) REFERENCES sac.tbGerarPagamentoParecerista (idGerarPagamentoParecerista)
);
CREATE INDEX IX_tbPagarParecerista_1 ON sac.tbPagarParecerista (idProduto);
CREATE INDEX IX_tbPagarParecerista ON sac.tbPagarParecerista (idPronac);
CREATE TABLE sac.tbParecerConsolidado
(
  idParecerConsolidado INT PRIMARY KEY NOT NULL,
  dsParecer VARCHAR(8000) NOT NULL,
  idUsuario INT,
  idDocumento INT,
  idRelatorioConsolidado INT NOT NULL,
  stRelatorioFinal CHAR NOT NULL,
  idAvaliador INT,
  idPerfilAvaliador INT NOT NULL,
  CONSTRAINT fk_tbParecerConsolidado_tbRelatorioConsolidado FOREIGN KEY (idRelatorioConsolidado) REFERENCES sac.tbRelatorioConsolidado (idRelatorioConsolidado)
);

CREATE TABLE sac.tbPlanilhaProposta
(
  idPlanilhaProposta INT PRIMARY KEY NOT NULL,
  idProjeto INT NOT NULL,
  idProduto int DEFAULT 0 NOT NULL,
  idEtapa INT NOT NULL,
  idPlanilhaItem INT DEFAULT 0 NOT NULL,
  Descricao VARCHAR(100) NOT NULL,
  Unidade INT NOT NULL,
  Quantidade REAL DEFAULT 0 NOT NULL,
  Ocorrencia DECIMAL(4) NOT NULL,
  ValorUnitario MONEY NOT NULL,
  QtdeDias INT DEFAULT 0 NOT NULL,
  TipoDespesa int DEFAULT 0 NOT NULL,
  TipoPessoa int DEFAULT 0 NOT NULL,
  Contrapartida int DEFAULT 0 NOT NULL,
  FonteRecurso int DEFAULT 0 NOT NULL,
  UfDespesa INT NOT NULL,
  MunicipioDespesa INT NOT NULL,
  idUsuario INT DEFAULT 0 NOT NULL,
  dsJustificativa VARCHAR(500),
  CONSTRAINT FK_tbPlanilhaProposta_PreProjeto FOREIGN KEY (idProjeto) REFERENCES sac.PreProjeto (idPreProjeto),
  CONSTRAINT FK_tbPlanilhaProposta_tbPlanilhaProposta FOREIGN KEY (idProjeto) REFERENCES sac.PreProjeto (idPreProjeto),
  CONSTRAINT FK_tbPlanilhaProposta_tbPlanilhaEtapa FOREIGN KEY (idEtapa) REFERENCES sac.tbPlanilhaEtapa (idPlanilhaEtapa),
  CONSTRAINT FK_tbPlanilhaProposta_tbPlanilhaItens FOREIGN KEY (idPlanilhaItem) REFERENCES sac.tbPlanilhaItens (idPlanilhaItens),
  CONSTRAINT FK_tbPlanilhaProposta_tbPlanilhaUnidade FOREIGN KEY (Unidade) REFERENCES sac.tbPlanilhaUnidade (idUnidade)
);
CREATE INDEX IX_tbPlanilhaProposta_idProjeto ON sac.tbPlanilhaProposta (idProjeto);
CREATE INDEX IX_tbPlanilhaProposta_idProduto ON sac.tbPlanilhaProposta (idProduto);
CREATE INDEX IX_tbPlanilhaProposta_idEtapa ON sac.tbPlanilhaProposta (idEtapa);
CREATE INDEX IX_tbPlanilhaProposta_idItem ON sac.tbPlanilhaProposta (idPlanilhaItem);
CREATE INDEX IX_tbPlanilhaProposta_Unidade ON sac.tbPlanilhaProposta (Unidade);
CREATE TABLE sac.tbPlanilhaProjeto
(
  idPlanilhaProjeto INT PRIMARY KEY NOT NULL,
  idPlanilhaProposta INT,
  idPRONAC INT NOT NULL,
  idProduto int NOT NULL,
  idEtapa INT NOT NULL,
  idPlanilhaItem INT DEFAULT 0 NOT NULL,
  Descricao VARCHAR(100) NOT NULL,
  idUnidade INT NOT NULL,
  Quantidade REAL NOT NULL,
  Ocorrencia DECIMAL(4) NOT NULL,
  ValorUnitario MONEY NOT NULL,
  QtdeDias INT NOT NULL,
  TipoDespesa int NOT NULL,
  TipoPessoa int NOT NULL,
  Contrapartida int NOT NULL,
  FonteRecurso int NOT NULL,
  UfDespesa INT NOT NULL,
  MunicipioDespesa INT NOT NULL,
  Justificativa VARCHAR,
  idParecer INT,
  idUsuario INT DEFAULT 0,
  CONSTRAINT FK_tbPlanilhaProjeto_tbPlanilhaProposta FOREIGN KEY (idPlanilhaProposta) REFERENCES sac.tbPlanilhaProposta (idPlanilhaProposta),
  CONSTRAINT FK_tbPlanilhaProjeto_Projetos FOREIGN KEY (idPRONAC) REFERENCES sac.Projetos (IdPRONAC),
  CONSTRAINT FK_tbPlanilhaProjeto_tbPlanilhaEtapa FOREIGN KEY (idEtapa) REFERENCES sac.tbPlanilhaEtapa (idPlanilhaEtapa),
  CONSTRAINT FK_tbPlanilhaProjeto_tbPlanilhaProjeto FOREIGN KEY (idPlanilhaItem) REFERENCES sac.tbPlanilhaItens (idPlanilhaItens),
  CONSTRAINT FK_tbPlanilhaUnidade_tbPlanilhaProjeto FOREIGN KEY (idUnidade) REFERENCES sac.tbPlanilhaUnidade (idUnidade)
);
CREATE INDEX IX_tbPlanilhaProjeto_idPronac ON sac.tbPlanilhaProjeto (idPRONAC);
CREATE INDEX IX_tbPlanilhaProjeto_idEtapa ON sac.tbPlanilhaProjeto (idEtapa);
CREATE INDEX IX_tbPlanilhaProjeto_idProduto ON sac.tbPlanilhaProjeto (idProduto);
CREATE INDEX IX_tbPlanilhaProjeto_idItemPlanilha ON sac.tbPlanilhaProjeto (idPlanilhaItem);
CREATE INDEX IX_tbPlanilhaProjeto_idUnidade ON sac.tbPlanilhaProjeto (idUnidade);
CREATE INDEX IX_tbPlanilhaProjeto_idPlanilhaProjeto ON sac.tbPlanilhaProjeto (idPlanilhaProjeto);
CREATE INDEX IX_tbPlanilhaProjeto_idPlanilhaProposta ON sac.tbPlanilhaProjeto (idPlanilhaProposta);
CREATE INDEX IX_tbPlanilhaProjeto_FonteRecurso ON sac.tbPlanilhaProjeto (FonteRecurso);
CREATE INDEX IX_tbPlanilhaProjeto_idPronac_Fonte_Produto ON sac.tbPlanilhaProjeto (idPRONAC, FonteRecurso, idProduto);
CREATE TABLE sac.tbPlanilhaAprovacao
(
  idPlanilhaAprovacao INT PRIMARY KEY NOT NULL,
  tpPlanilha CHAR(2) NOT NULL,
  dtPlanilha timestamp NOT NULL,
  idPlanilhaProjeto INT,
  idPlanilhaProposta INT,
  IdPRONAC INT NOT NULL,
  idProduto int NOT NULL,
  idEtapa INT NOT NULL,
  idPlanilhaItem INT NOT NULL,
  dsItem VARCHAR(100) NOT NULL,
  idUnidade INT NOT NULL,
  qtItem REAL NOT NULL,
  nrOcorrencia DECIMAL(4) NOT NULL,
  vlUnitario MONEY NOT NULL,
  qtDias INT NOT NULL,
  tpDespesa int NOT NULL,
  tpPessoa int NOT NULL,
  nrContraPartida int NOT NULL,
  nrFonteRecurso int NOT NULL,
  idUFDespesa INT NOT NULL,
  idMunicipioDespesa INT NOT NULL,
  dsJustificativa VARCHAR(8000),
  idAgente INT,
  idPlanilhaAprovacaoPai INT,
  idReadequacao INT,
  tpAcao CHAR,
  idRecursoDecisao INT,
  stAtivo CHAR DEFAULT 'S' NOT NULL,
  CONSTRAINT FK_tbPlanilhaAprovacao_tbPlanilhaProjeto FOREIGN KEY (idPlanilhaProjeto) REFERENCES sac.tbPlanilhaProjeto (idPlanilhaProjeto),
  CONSTRAINT FK_tbPlanilhaAprovacao_tbPlanilhaProposta FOREIGN KEY (idPlanilhaProposta) REFERENCES sac.tbPlanilhaProposta (idPlanilhaProposta),
  CONSTRAINT fk_tbPlanilhaAprovacao_02 FOREIGN KEY (IdPRONAC) REFERENCES sac.Projetos (IdPRONAC),
  CONSTRAINT FK_tbPlanilhaAprovacao_tbPlanilhaEtapa FOREIGN KEY (idEtapa) REFERENCES sac.tbPlanilhaEtapa (idPlanilhaEtapa),
  CONSTRAINT FK_tbPlanilhaAprovacao_tbPlanilhaItens FOREIGN KEY (idPlanilhaItem) REFERENCES sac.tbPlanilhaItens (idPlanilhaItens),
  CONSTRAINT FK_tbPlanilhaAprovacao_tbPlanilhaUnidade FOREIGN KEY (idUnidade) REFERENCES sac.tbPlanilhaUnidade (idUnidade),
  CONSTRAINT fk_tbPlanilhaAprovacao_01 FOREIGN KEY (idPlanilhaAprovacaoPai) REFERENCES sac.tbPlanilhaAprovacao (idPlanilhaAprovacao)
);
CREATE INDEX ix_tbPlanilhaAprovaAtivo ON sac.tbPlanilhaAprovacao (stAtivo, IdPRONAC, idEtapa, idUnidade, qtItem, nrOcorrencia, vlUnitario);
CREATE INDEX ix_tbPlanilhaAprovacao_EtapaAtivo ON sac.tbPlanilhaAprovacao (idEtapa, stAtivo, IdPRONAC, idUnidade, qtItem, nrOcorrencia, vlUnitario);
CREATE INDEX IX_tbPlanilhaAprovacao_idProduto ON sac.tbPlanilhaAprovacao (idProduto);
CREATE INDEX IX_tbPlanilhaAprovacao_idPlanilhaItem ON sac.tbPlanilhaAprovacao (idPlanilhaItem);
CREATE INDEX IX_tbPlanilhaAprovacao_idMunicipioDespesa ON sac.tbPlanilhaAprovacao (idMunicipioDespesa);
CREATE INDEX IX_tbPlanilhaAprovacao_idPronac ON sac.tbPlanilhaAprovacao (IdPRONAC);
CREATE INDEX IX_tbPlanilhaAprovacao_idPlanilha_Projeto ON sac.tbPlanilhaAprovacao (idPlanilhaProjeto);
CREATE INDEX IX_tbPlanilhaAprovacao_idPlanilhaProposta ON sac.tbPlanilhaAprovacao (idPlanilhaProposta);
CREATE INDEX IX_tbPlanilhaAprovacao_idUnidade ON sac.tbPlanilhaAprovacao (idUnidade);
CREATE INDEX IX_tbPlanilhaAprovacao_FonteRecurso ON sac.tbPlanilhaAprovacao (nrFonteRecurso);
CREATE INDEX IX_idPronac_Itens ON sac.tbPlanilhaAprovacao (IdPRONAC, idPlanilhaItem);
CREATE INDEX IX_Itens ON sac.tbPlanilhaAprovacao (idPlanilhaItem);
CREATE INDEX IX_Etapa ON sac.tbPlanilhaAprovacao (idEtapa);

CREATE TABLE sac.tbCumprimentoObjetoXArquivo
(
  idCumprimentoObjetoXArquivo INT PRIMARY KEY NOT NULL,
  idCumprimentoObjeto INT NOT NULL,
  idArquivo INT NOT NULL,
  idPosicao int NOT NULL,
  CONSTRAINT FK_tbCumprimentoObjetoXArquivo_tbCumprimentoObjeto FOREIGN KEY (idCumprimentoObjeto) REFERENCES sac.tbCumprimentoObjeto (idCumprimentoObjeto)
);
CREATE TABLE sac.tbDeParaPlanilhaAprovacao
(
  idDeParaPlanilhaAprovacao INT PRIMARY KEY NOT NULL,
  idPlanilhaAprovacaoFilho INT NOT NULL,
  idPlanilhaAprovacao INT NOT NULL,
  CONSTRAINT FK_tbDeParaPlanilhaAprovacao_tbPlanilhaAprovacao1 FOREIGN KEY (idPlanilhaAprovacaoFilho) REFERENCES sac.tbPlanilhaAprovacao (idPlanilhaAprovacao),
  CONSTRAINT FK_tbDeParaPlanilhaAprovacao_tbPlanilhaAprovacao FOREIGN KEY (idPlanilhaAprovacao) REFERENCES sac.tbPlanilhaAprovacao (idPlanilhaAprovacao)
);
CREATE TABLE sac.tbPlanilhaDesembolso
(
  idDesembolso INT PRIMARY KEY NOT NULL,
  idProjeto INT NOT NULL,
  idPlanilhaItem INT NOT NULL,
  Data timestamp NOT NULL,
  Valor MONEY NOT NULL,
  CONSTRAINT FK_PlanilhaDesembolso_Projetos FOREIGN KEY (idProjeto) REFERENCES sac.Projetos (IdPRONAC),
  CONSTRAINT FK_tbPlanilhaDesembolso_PreProjeto FOREIGN KEY (idProjeto) REFERENCES sac.PreProjeto (idPreProjeto)
);
CREATE TABLE sac.tbPlanilhaJustificativa
(
  idPlanilhaOrcamento INT PRIMARY KEY NOT NULL,
  idPlanilhaProjeto INT NOT NULL,
  Data timestamp NOT NULL,
  VlSugerido MONEY DEFAULT 0 NOT NULL,
  OperacaoSugerida INTEGER DEFAULT 0 NOT NULL,
  JustificativaSugerida VARCHAR(250) DEFAULT '' NOT NULL,
  VlAprovado MONEY DEFAULT 0 NOT NULL,
  OperacaoAprovada INTEGER DEFAULT 0 NOT NULL,
  JustificativaAprovada VARCHAR(250) DEFAULT '' NOT NULL,
  stEstado INTEGER DEFAULT 0 NOT NULL,
  idUsuario INT NOT NULL,
  CONSTRAINT FK_tbPlanilhaJustificativa_tbPlanilhaProjeto FOREIGN KEY (idPlanilhaProjeto) REFERENCES sac.tbPlanilhaProjeto (idPlanilhaProjeto)
);
CREATE TABLE sac.tbRecursoXPlanilhaAprovacao
(
  idRecurso INT NOT NULL,
  idPlanilhaAprovacao INT NOT NULL,
  stRecursoAprovacao CHAR,
  dsJustificativa VARCHAR(600),
  CONSTRAINT pk_tbRecursoXPlanilhaAprovacao PRIMARY KEY (idRecurso, idPlanilhaAprovacao),
  CONSTRAINT fk_tbRecursoXPlanilhaAprovacao_02 FOREIGN KEY (idRecurso) REFERENCES sac.tbRecurso (idRecurso),
  CONSTRAINT fktbRecursoXPlanilhaAprovacao_tbPlanilhaAprovacao FOREIGN KEY (idPlanilhaAprovacao) REFERENCES sac.tbPlanilhaAprovacao (idPlanilhaAprovacao)
);
CREATE TABLE sac.tbRelatorioTecnico
(
  idRelatorioTecnico INT PRIMARY KEY NOT NULL,
  meRelatorio TEXT NOT NULL,
  dtRelatorio timestamp NOT NULL,
  IdPRONAC INT NOT NULL,
  idAgente INT NOT NULL,
  cdGrupo INT NOT NULL,
  siManifestacao INTEGER DEFAULT '1' NOT NULL,
  CONSTRAINT fk_tbRelatorioTecnico_Projetos FOREIGN KEY (IdPRONAC) REFERENCES sac.Projetos (IdPRONAC)
);
CREATE TABLE sac.tbRelatorioTrimestral
(
  idRelatorioTrimestral INT PRIMARY KEY NOT NULL,
  idRelatorio INT NOT NULL,
  dsParecer VARCHAR(8000),
  dsObjetivosMetas VARCHAR(8000),
  dtCadastro timestamp,
  stRelatorioTrimestral CHAR,
  nrRelatorioTrimestral INT NOT NULL,
  CONSTRAINT fk_tbRelatorioTrimestral_tbRelatorio FOREIGN KEY (idRelatorio) REFERENCES sac.tbRelatorio (idRelatorio)
);
CREATE TABLE sac.tbSaldoBancario
(
  idSaldoBancario INT PRIMARY KEY NOT NULL,
  idPronac INT NOT NULL,
  idMovimentacaoBancariaItem INT NOT NULL,
  stContaLancamento INTEGER NOT NULL,
  siSaldoBancario INTEGER NOT NULL,
  nrAgenciaSaldoBancario CHAR(5) NOT NULL,
  nrContaSaldoBancario CHAR(12) NOT NULL,
  dtSaldoBancario timestamp NOT NULL,
  vlSaldoBancario DECIMAL(16,2) NOT NULL,
  stSaldoBancario CHAR NOT NULL,
  CONSTRAINT FK_tbSaldoBancario_Projetos FOREIGN KEY (idPronac) REFERENCES sac.Projetos (IdPRONAC),
  CONSTRAINT FK_tbSaldoBancario_tbMovimentacaoBancaria FOREIGN KEY (idMovimentacaoBancariaItem) REFERENCES sac.tbMovimentacaoBancariaItem (idMovimentacaoBancariaItem)
);
CREATE INDEX IX_tbSaldoBancario ON sac.tbSaldoBancario (idPronac);
CREATE TABLE sac.tbSecretario
(
  idSecretario INT PRIMARY KEY NOT NULL,
  idOrgao INT NOT NULL,
  nmSecretario VARCHAR(100) NOT NULL,
  dsCargo VARCHAR(50) NOT NULL,
  CONSTRAINT fktbSecretario01 FOREIGN KEY (idOrgao) REFERENCES sac.Orgaos (Codigo)
);
CREATE TABLE sac.tbSolicitarItem
(
  idSolicitarItem INT PRIMARY KEY NOT NULL,
  idPlanilhaItens INT,
  NomeDoItem VARCHAR(250),
  Descricao VARCHAR(250),
  idProduto int NOT NULL,
  idEtapa INT NOT NULL,
  idAgente INT NOT NULL,
  DtSolicitacao timestamp NOT NULL,
  Resposta VARCHAR,
  DtResposta timestamp,
  stEstado int DEFAULT 0 NOT NULL,
CONSTRAINT FK_tbSolicitarItem_Produto FOREIGN KEY (idProduto) REFERENCES sac.Produto (Codigo),
CONSTRAINT FK_tbSolicitarItem_tbPlanilhaEtapa FOREIGN KEY (idEtapa) REFERENCES sac.tbPlanilhaEtapa (idPlanilhaEtapa)
);
CREATE TABLE sac.tbTermoAceiteObra
(
  idTermoAceiteObra INT PRIMARY KEY NOT NULL,
  idPronac INT NOT NULL,
  dtCadastroTermo timestamp NOT NULL,
  dsDescricaoTermoAceite VARCHAR(2000) NOT NULL,
  idDocumentoTermo INT NOT NULL,
  idUsuarioCadastrador INT NOT NULL,
  stConstrucaoCriacaoRestauro INTEGER DEFAULT 0 NOT NULL,
  CONSTRAINT FK_tbTermoAceiteObra_Projetos FOREIGN KEY (idPronac) REFERENCES sac.Projetos (IdPRONAC)
);
CREATE TABLE sac.tbTmpInconsistenciaCaptacao
(
  idTipoInconsistencia INT NOT NULL,
  idTmpCaptacao INT NOT NULL,
  CONSTRAINT pk_tbTmpInconsistenciaCaptacao_20160219 PRIMARY KEY (idTipoInconsistencia, idTmpCaptacao),
  CONSTRAINT fk_tbTmpInconsistenciaCaptacao_tbTipoInconsistencia_20160219 FOREIGN KEY (idTipoInconsistencia) REFERENCES sac.tbTipoInconsistencia (idTipoInconsistencia),
  CONSTRAINT fk_tbTmpInconsistenciaCaptacao_tbTmpCaptacao_20160219 FOREIGN KEY (idTmpCaptacao) REFERENCES sac.tbTmpCaptacao (idTmpCaptacao)
);
CREATE TABLE sac.tbTmpInconsistenciaCaptacaoOLD
(
  idTipoInconsistencia INT NOT NULL,
  idTmpCaptacao INT NOT NULL,
  CONSTRAINT pk_tbTmpInconsistenciaCaptacao PRIMARY KEY (idTipoInconsistencia, idTmpCaptacao),
  CONSTRAINT fk_tbTmpInconsistenciaCaptacao_tbTipoInconsistencia FOREIGN KEY (idTipoInconsistencia) REFERENCES sac.tbTipoInconsistencia (idTipoInconsistencia),
  CONSTRAINT fk_tbTmpInconsistenciaCaptacao_tbTmpCaptacao FOREIGN KEY (idTmpCaptacao) REFERENCES sac.tbTmpCaptacaoOLD (idTmpCaptacao)
);
CREATE TABLE sac.tbVerificaProjeto
(
  idVerificaProjeto INT PRIMARY KEY NOT NULL,
  IdPRONAC INT NOT NULL,
  idOrgao INT NOT NULL,
  idAprovacao INT NOT NULL,
  idUsuario INT NOT NULL,
  stAnaliseProjeto CHAR NOT NULL,
  dtRecebido timestamp,
  dtFinalizado timestamp,
  dtPortaria timestamp,
  stAtivo CHAR NOT NULL,
  CONSTRAINT FK_tbVerificaProjeto_Projetos FOREIGN KEY (IdPRONAC) REFERENCES sac.Projetos (IdPRONAC),
  CONSTRAINT FK_tbVerificaProjeto_Orgaos FOREIGN KEY (idOrgao) REFERENCES sac.Orgaos (Codigo),
  CONSTRAINT FK_tbVerificaProjeto_Aprovacao FOREIGN KEY (idAprovacao) REFERENCES sac.Aprovacao (idAprovacao)
);
CREATE TABLE sac.Tempestividade
(
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  Campo_01 int DEFAULT 9 NOT NULL,
  Campo_02 int DEFAULT 9 NOT NULL,
  Campo_03 int DEFAULT 9 NOT NULL,
  Campo_04 int DEFAULT 9 NOT NULL,
  Campo_05 int DEFAULT 9 NOT NULL,
  Campo_06 int DEFAULT 9 NOT NULL,
  Campo_07 int DEFAULT 9 NOT NULL,
  Campo_08 int DEFAULT 9 NOT NULL,
  Campo_09 int DEFAULT 9 NOT NULL,
  Campo_10 int DEFAULT 9 NOT NULL,
  Justificativas TEXT NOT NULL,
  AnaliseFinanceira timestamp NOT NULL,
  AnaliseJuridica timestamp NOT NULL,
  Formalizacao timestamp NOT NULL,
  Celebracao timestamp NOT NULL,
  Publicacao timestamp NOT NULL,
  Execucao timestamp NOT NULL,
  EntregaPC timestamp NOT NULL,
  AnalisePC timestamp NOT NULL,
  Manifestacao timestamp NOT NULL,
  Logon INT NOT NULL,
  CONSTRAINT PK_Tempestividade PRIMARY KEY (AnoProjeto, Sequencial),
  CONSTRAINT FK_Tempestividade_Projetos FOREIGN KEY (AnoProjeto, Sequencial) REFERENCES sac.Projetos (AnoProjeto, Sequencial)
);
CREATE TABLE sac.TermoAditivo
(
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  NumeroTermo VARCHAR(15) NOT NULL,
  DtTermoAditivo timestamp,
  DtPublicacao timestamp,
  DtInicioVigencia timestamp,
  DtFinalVigencia timestamp,
  DtInicioExecucao timestamp,
  DtFinalExecucao timestamp,
  Motivo TEXT,
  logon INT,
  CONSTRAINT PK_TermoAditivo PRIMARY KEY (AnoProjeto, Sequencial, NumeroTermo),
  CONSTRAINT FK_TermoProjetos FOREIGN KEY (AnoProjeto, Sequencial) REFERENCES sac.Projetos (AnoProjeto, Sequencial)
);
CREATE UNIQUE INDEX AK_TermoAditivo ON sac.TermoAditivo (AnoProjeto, Sequencial, NumeroTermo);
CREATE TABLE sac.TermoCompromisso
(
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  DtTermo timestamp NOT NULL,
  DtChegadaTermo timestamp NOT NULL,
  Observacao VARCHAR(250) NOT NULL,
  Logon INT NOT NULL,
  CONSTRAINT PK_TermoCompromisso PRIMARY KEY (AnoProjeto, Sequencial),
  CONSTRAINT FK_TermoCompromisso_Projetos FOREIGN KEY (AnoProjeto, Sequencial) REFERENCES sac.Projetos (AnoProjeto, Sequencial)
);
CREATE TABLE sac.ValidadeDocumentos
(
  CgcCpf VARCHAR(14) NOT NULL,
  CodigoDocumento INT NOT NULL,
  DtValidade timestamp NOT NULL,
  Logon INT NOT NULL,
  CONSTRAINT PK_ValidadeDocumentos PRIMARY KEY (CgcCpf, CodigoDocumento),
  CONSTRAINT FK_ValidadeDocumentosExigidos FOREIGN KEY (CodigoDocumento) REFERENCES sac.DocumentosExigidos (Codigo)
);
CREATE TABLE sac.VerificacaoPecaxVeiculo
(
  idVerificacaoPecaxVeiculo INT PRIMARY KEY NOT NULL,
  idVerificacaoPeca INT NOT NULL,
  idVerificacaoVeiculo INT NOT NULL,
  CONSTRAINT FK_VerificacaoPecaxVeiculo_Verificacao FOREIGN KEY (idVerificacaoPeca) REFERENCES sac.Verificacao (idVerificacao),
  CONSTRAINT FK_VerificacaoPecaxVeiculo_Verificacao1 FOREIGN KEY (idVerificacaoVeiculo) REFERENCES sac.Verificacao (idVerificacao)
);
CREATE SEQUENCE sac.verificacaopecaxveiculo_idverificacaopecaxveiculo_seq NO MINVALUE NO MAXVALUE NO CYCLE;
ALTER TABLE sac.verificacaopecaxveiculo ALTER COLUMN idverificacaopecaxveiculo SET DEFAULT nextval('sac.verificacaopecaxveiculo_idverificacaopecaxveiculo_seq');
ALTER SEQUENCE sac.verificacaopecaxveiculo_idverificacaopecaxveiculo_seq OWNED BY sac.verificacaopecaxveiculo.idverificacaopecaxveiculo;
CREATE TABLE sac.DBA_CaptacaoAnoUfMunicipio
(
  ANO_CAPTACAO INT,
  REGIAO VARCHAR(15) NOT NULL,
  UF CHAR(2) NOT NULL,
  MUNICIPIO VARCHAR(50) NOT NULL,
  PRONAC VARCHAR(7) NOT NULL,
  VALOR_CAPTADO MONEY
);
CREATE TABLE sac.DBA_CEP_SALIC
(
  cep VARCHAR(8),
  logradouro VARCHAR(185),
  tipo_logradouro VARCHAR(36),
  bairro VARCHAR(72),
  cidade VARCHAR(72),
  uf VARCHAR(2),
  idCidadeMunicipios VARCHAR(6),
  dsCidadeMunicipios VARCHAR(100),
  idCidadeUF VARCHAR(6),
  dsCidadeUF VARCHAR(100)
);
CREATE TABLE sac.DBA_Geral
(
  PROJETO_PRONAC INT NOT NULL,
  PROJETO_PROJETO INT,
  PROJETO_ANO CHAR(2) NOT NULL,
  PROJETO_SEQUENCIAL VARCHAR(5) NOT NULL,
  PROJETO_UF CHAR(2) NOT NULL,
  AREA_CODIGO VARCHAR(4) NOT NULL,
  AREA_DESCRICAO VARCHAR(50) NOT NULL,
  SEGMENTO_DESCRICAO VARCHAR(50) NOT NULL,
  PROJETO_MECANISMO CHAR NOT NULL,
  PROJETO_NOME VARCHAR(300) NOT NULL,
  PROJETO_RESUMO TEXT,
  SITUACAO_CODIGO CHAR(3) NOT NULL,
  SITUACAO_DESCRICAO VARCHAR(150) NOT NULL,
  PROPONENTE_CODIGO VARCHAR(14),
  PROPONENTE_TIPO CHAR,
  PROPONENTE_NOME VARCHAR(150),
  PROPONENTE_CIDADE VARCHAR(50),
  PROPONENTE_UF CHAR(2),
  PROPONENTE_CELULAR VARCHAR(12),
  PROPONENTE_COMERCIAL VARCHAR(12),
  PROPONENTE_EMAIL VARCHAR(100),
  PROPONENTE_RESPONSAVEL VARCHAR(100),
  PROJETO_DATA_INICIO_EXECUCAO timestamp,
  PROJETO_DATA_FIM_EXECUCAO timestamp,
  PROJETO_DATA_INICIO_EXECUCAO_FORMATADO VARCHAR(4000),
  PROJETO_DATA_FIM_EXECUCAO_FORMATADO VARCHAR(4000),
  PROJETO_VALOR_SOLICITADO_REAL MONEY,
  APROVACAO_DATA_APROVACAO timestamp,
  APROVACAO_DATA_APROVACAO_FORMATADO VARCHAR(4000),
  APROVACAO_DATA_INICIO_CAPTACAO timestamp,
  APROVACAO_DATA_FIM_CAPTACAO timestamp,
  APROVACAO_DATA_INICIO_CAPTACAO_FORMATADO VARCHAR(4000),
  APROVACAO_DATA_FIM_CAPTACAO_FORMATADO VARCHAR(4000),
  APROVACAO_VALOR_APROVADO_REAL MONEY,
  CAPTACAO_NUMERO_RECIBO VARCHAR(5),
  CAPTACAO_TIPO_APOIO CHAR,
  CAPTACAO_DATA_RECIBO timestamp,
  CAPTACAO_DATA_RECIBO_FORMATADO VARCHAR(4000),
  CAPTACAO_VALOR_CAPTACAO_REAL MONEY,
  INVESTIDOR_CODIGO VARCHAR(14),
  INVESTIDOR_TIPO CHAR,
  INVESTIDOR_NOME VARCHAR(150),
  INVESTIDOR_CIDADE VARCHAR(50),
  INVESTIDOR_UF CHAR(2),
  MUNICIPIO_ABRANGENCIA VARCHAR(100),
  UF_MUNICIPIO_ABRANGENCIA CHAR(2),
  CONVENIO_VALOR MONEY
);
CREATE TABLE sac.DBA_Geral_2008
(
  PRONAC VARCHAR (7) NOT NULL,
  OME_DO_PROJETO VARCHAR(8000),
  SINTESE_DO_PROJETO VARCHAR(8000),
  VALOR_SOLICITADO VARCHAR(4000),
  VALOR_APROVADO VARCHAR(4000),
  VALOR_CAPTADO VARCHAR(4000),
  CIDADE_DE_ABRANGENCIA VARCHAR(100),
  UF_DE_ABRANGENCIA CHAR(2),
  AREA VARCHAR(50) NOT NULL,
  SEGMENTO VARCHAR(50) NOT NULL,
  SITUACAO VARCHAR(150) NOT NULL,
  ATA_DE_INICIO_DA_EXECUCAO VARCHAR(4000),
  DATA_DE_TERMINO_DA_EXECUCAO VARCHAR(4000),
  NOME_DO_PROPONENTE VARCHAR(8000),
  TIPO_DO_PROPONENTE VARCHAR(11),
  CNPJ_OU_CPF_DO_PROPONENTE VARCHAR(14),
  CIDADE_DO_PROPONENTE VARCHAR(50),
  UF_DO_PROPONENTE CHAR(2),
  EMAIL_DO_PROPONENTE VARCHAR(100),
  TELEFONE_COMERCIAL_DO_PROPONENTE VARCHAR(12),
  TELEFONE_CELULAR_DO_PROPONENTE VARCHAR(12),
  RESPONSAVEL_PELO_PROPONENTE VARCHAR(100),
  NOME_DO_INVESTIDOR VARCHAR(8000),
  TIPO_DO_INVESTIDOR VARCHAR(11),
  CNPJ_OU_CPF_DO_INVESTIDOR VARCHAR(14),
  CIDADE_DO_INVESTIDOR VARCHAR(50),
  UF_DO_INVESTIDOR CHAR(2),
  VALOR_INVESTIDO VARCHAR(4000)
);
CREATE TABLE sac.DBA_Geral_2009
(
  PRONAC VARCHAR(7) NOT NULL,
  NOME_DO_PROJETO VARCHAR(8000),
  SINTESE_DO_PROJETO VARCHAR(8000),
  VALOR_SOLICITADO VARCHAR(4000),
  VALOR_APROVADO VARCHAR(4000),
  VALOR_CAPTADO VARCHAR(4000),
  CIDADE_DE_ABRANGENCIA VARCHAR(100),
  UF_DE_ABRANGENCIA CHAR(2),
  AREA VARCHAR(50) NOT NULL,
  SEGMENTO VARCHAR(50) NOT NULL,
  SITUACAO VARCHAR(150) NOT NULL,
  DATA_DE_INICIO_DA_EXECUCAO VARCHAR(4000),
  DATA_DE_TERMINO_DA_EXECUCAO VARCHAR(4000),
  NOME_DO_PROPONENTE VARCHAR(8000),
  IPO_DO_PROPONENTE VARCHAR(11),
  CNPJ_OU_CPF_DO_PROPONENTE VARCHAR(14),
  CIDADE_DO_PROPONENTE VARCHAR(50),
  UF_DO_PROPONENTE CHAR(2),
  EMAIL_DO_PROPONENTE VARCHAR(100),
  TELEFONE_COMERCIAL_DO_PROPONENTE VARCHAR(12),
  TELEFONE_CELULAR_DO_PROPONENTE VARCHAR(12),
  RESPONSAVEL_PELO_PROPONENTE VARCHAR(100),
  NOME_DO_INVESTIDOR VARCHAR(8000),
  TIPO_DO_INVESTIDOR VARCHAR(11),
  CNPJ_OU_CPF_DO_INVESTIDOR VARCHAR(14),
  CIDADE_DO_INVESTIDOR VARCHAR(50),
  UF_DO_INVESTIDOR CHAR(2),
  VALOR_INVESTIDO VARCHAR(4000)
);
CREATE TABLE sac.DBA_Geral_2010
(
  PRONAC VARCHAR(7) NOT NULL,
   NOME_DO_PROJETO VARCHAR(8000),
   SINTESE_DO_PROJETO VARCHAR(8000),
   VALOR_SOLICITADO VARCHAR(4000),
   VALOR_APROVADO VARCHAR(4000),
   VALOR_CAPTADO VARCHAR(4000),
   CIDADE_DE_ABRANGENCIA VARCHAR(100),
   UF_DE_ABRANGENCIA CHAR(2),
   AREA VARCHAR(50) NOT NULL,
   SEGMENTO VARCHAR(50) NOT NULL,
   SITUACAO VARCHAR(150) NOT NULL,
   DATA_DE_INICIO_DA_EXECUCAO VARCHAR(4000),
   DATA_DE_TERMINO_DA_EXECUCAO VARCHAR(4000),
   NOME_DO_PROPONENTE VARCHAR(8000),
   TIPO_DO_PROPONENTE VARCHAR(11),
   CNPJ_OU_CPF_DO_PROPONENTE VARCHAR(14),
   CIDADE_DO_PROPONENTE VARCHAR(50),
   UF_DO_PROPONENTE CHAR(2),
   EMAIL_DO_PROPONENTE VARCHAR(100),
   TELEFONE_COMERCIAL_DO_PROPONENTE VARCHAR(12),
   TELEFONE_CELULAR_DO_PROPONENTE VARCHAR(12),
   RESPONSAVEL_PELO_PROPONENTE VARCHAR(100),
   NOME_DO_INVESTIDOR VARCHAR(8000),
   TIPO_DO_INVESTIDOR VARCHAR(11),
   CNPJ_OU_CPF_DO_INVESTIDOR VARCHAR(14),
   CIDADE_DO_INVESTIDOR VARCHAR(50),
   UF_DO_INVESTIDOR CHAR(2),
   VALOR_INVESTIDO VARCHAR(4000)
);
CREATE TABLE sac.DBA_Geral_2011
(
   PRONAC VARCHAR(7) NOT NULL,
   NOME_DO_PROJETO VARCHAR(8000),
   SINTESE_DO_PROJETO VARCHAR(8000),
   VALOR_SOLICITADO VARCHAR(4000),
   VALOR_APROVADO VARCHAR(4000),
   VALOR_CAPTADO VARCHAR(4000),
   CIDADE_DE_ABRANGENCIA VARCHAR(100),
   UF_DE_ABRANGENCIA CHAR(2),
   AREA VARCHAR(50) NOT NULL,
   SEGMENTO VARCHAR(50) NOT NULL,
   SITUACAO VARCHAR(150) NOT NULL,
   DATA_DE_INICIO_DA_EXECUCAO VARCHAR(4000),
   DATA_DE_TERMINO_DA_EXECUCAO VARCHAR(4000),
   NOME_DO_PROPONENTE VARCHAR(8000),
   TIPO_DO_PROPONENTE VARCHAR(11),
   CNPJ_OU_CPF_DO_PROPONENTE VARCHAR(14),
   CIDADE_DO_PROPONENTE VARCHAR(50),
   UF_DO_PROPONENTE CHAR(2),
   EMAIL_DO_PROPONENTE VARCHAR(100),
   TELEFONE_COMERCIAL_DO_PROPONENTE VARCHAR(12),
   TELEFONE_CELULAR_DO_PROPONENTE VARCHAR(12),
   RESPONSAVEL_PELO_PROPONENTE VARCHAR(100),
   NOME_DO_INVESTIDOR VARCHAR(8000),
   TIPO_DO_INVESTIDOR VARCHAR(11),
   CNPJ_OU_CPF_DO_INVESTIDOR VARCHAR(14),
   CIDADE_DO_INVESTIDOR VARCHAR(50),
   UF_DO_INVESTIDOR CHAR(2),
   VALOR_INVESTIDO VARCHAR(4000)
);
CREATE TABLE sac.DBA_Geral_2012
(
   PRONAC VARCHAR(7) NOT NULL,
   NOME_DO_PROJETO VARCHAR(8000),
   SINTESE_DO_PROJETO VARCHAR(8000),
   VALOR_SOLICITADO VARCHAR(4000),
   VALOR_APROVADO VARCHAR(4000),
   VALOR_CAPTADO VARCHAR(4000),
   CIDADE_DE_ABRANGENCIA VARCHAR(100),
   UF_DE_ABRANGENCIA CHAR(2),
   AREA VARCHAR(50) NOT NULL,
   SEGMENTO VARCHAR(50) NOT NULL,
   SITUACAO VARCHAR(150) NOT NULL,
   DATA_DE_INICIO_DA_EXECUCAO VARCHAR(4000),
   DATA_DE_TERMINO_DA_EXECUCAO VARCHAR(4000),
   NOME_DO_PROPONENTE VARCHAR(8000),
   TIPO_DO_PROPONENTE VARCHAR(11),
   CNPJ_OU_CPF_DO_PROPONENTE VARCHAR(14),
   CIDADE_DO_PROPONENTE VARCHAR(50),
   UF_DO_PROPONENTE CHAR(2),
   EMAIL_DO_PROPONENTE VARCHAR(100),
   TELEFONE_COMERCIAL_DO_PROPONENTE VARCHAR(12),
   TELEFONE_CELULAR_DO_PROPONENTE VARCHAR(12),
   RESPONSAVEL_PELO_PROPONENTE VARCHAR(100),
   NOME_DO_INVESTIDOR VARCHAR(8000),
   TIPO_DO_INVESTIDOR VARCHAR(11),
   CNPJ_OU_CPF_DO_INVESTIDOR VARCHAR(14),
   CIDADE_DO_INVESTIDOR VARCHAR(50),
   UF_DO_INVESTIDOR CHAR(2),
   VALOR_INVESTIDO VARCHAR(4000)
);
CREATE TABLE sac.DBA_Geral_2013
(
   PRONAC VARCHAR(7) NOT NULL,
   NOME_DO_PROJETO VARCHAR(8000),
   SINTESE_DO_PROJETO VARCHAR(8000),
   VALOR_SOLICITADO VARCHAR(4000),
   VALOR_APROVADO VARCHAR(4000),
   VALOR_CAPTADO VARCHAR(4000),
   CIDADE_DE_ABRANGENCIA VARCHAR(100),
   UF_DE_ABRANGENCIA CHAR(2),
   AREA VARCHAR(50) NOT NULL,
   SEGMENTO VARCHAR(50) NOT NULL,
   SITUACAO VARCHAR(150) NOT NULL,
   DATA_DE_INICIO_DA_EXECUCAO VARCHAR(4000),
   DATA_DE_TERMINO_DA_EXECUCAO VARCHAR(4000),
   NOME_DO_PROPONENTE VARCHAR(8000),
   TIPO_DO_PROPONENTE VARCHAR(11),
   CNPJ_OU_CPF_DO_PROPONENTE VARCHAR(14),
   CIDADE_DO_PROPONENTE VARCHAR(50),
   UF_DO_PROPONENTE CHAR(2),
   EMAIL_DO_PROPONENTE VARCHAR(100),
   TELEFONE_COMERCIAL_DO_PROPONENTE VARCHAR(12),
   TELEFONE_CELULAR_DO_PROPONENTE VARCHAR(12),
   RESPONSAVEL_PELO_PROPONENTE VARCHAR(100),
   NOME_DO_INVESTIDOR VARCHAR(8000),
   TIPO_DO_INVESTIDOR VARCHAR(11),
   CNPJ_OU_CPF_DO_INVESTIDOR VARCHAR(14),
   CIDADE_DO_INVESTIDOR VARCHAR(50),
   UF_DO_INVESTIDOR CHAR(2),
   VALOR_INVESTIDO VARCHAR(4000)
);
CREATE TABLE sac.DBA_Geral_2014
(
  PRONAC VARCHAR(7) NOT NULL,
  NOME_DO_PROJETO VARCHAR(8000),
  SINTESE_DO_PROJETO VARCHAR(8000),
  VALOR_SOLICITADO VARCHAR(4000),
  VALOR_APROVADO VARCHAR(4000),
  VALOR_CAPTADO VARCHAR(4000),
  CIDADE_DE_ABRANGENCIA VARCHAR(100),
  UF_DE_ABRANGENCIA CHAR(2),
  AREA VARCHAR(50) NOT NULL,
  SEGMENTO VARCHAR(50) NOT NULL,
  SITUACAO VARCHAR(150) NOT NULL,
  DATA_DE_INICIO_DA_EXECUCAO VARCHAR(4000),
  DATA_DE_TERMINO_DA_EXECUCAO VARCHAR(4000),
  NOME_DO_PROPONENTE VARCHAR(8000),
  TIPO_DO_PROPONENTE VARCHAR(11),
  CNPJ_OU_CPF_DO_PROPONENTE VARCHAR(14),
  CIDADE_DO_PROPONENTE VARCHAR(50),
  UF_DO_PROPONENTE CHAR(2),
  EMAIL_DO_PROPONENTE VARCHAR(100),
  TELEFONE_COMERCIAL_DO_PROPONENTE VARCHAR(12),
  TELEFONE_CELULAR_DO_PROPONENTE VARCHAR(12),
  RESPONSAVEL_PELO_PROPONENTE VARCHAR(100),
  NOME_DO_INVESTIDOR VARCHAR(8000),
  TIPO_DO_INVESTIDOR VARCHAR(11),
  CNPJ_OU_CPF_DO_INVESTIDOR VARCHAR(14),
  CIDADE_DO_INVESTIDOR VARCHAR(50),
  UF_DO_INVESTIDOR CHAR(2),
  VALOR_INVESTIDO VARCHAR(4000)
);
CREATE TABLE sac.DBA_Geral_FRED
(
  PROJETO_PRONAC INT NOT NULL,
  PROJETO_PROJETO INT,
  PROJETO_ANO CHAR(2) NOT NULL,
  PROJETO_SEQUENCIAL VARCHAR(5) NOT NULL,
  NU_PRONAC VARCHAR(7) NOT NULL,
  PROJETO_UF CHAR(2) NOT NULL,
  AREA_DESCRICAO VARCHAR(50) NOT NULL,
  SEGMENTO_DESCRICAO VARCHAR(50) NOT NULL,
  MECANISMO VARCHAR(50) NOT NULL,
  PROJETO_NOME VARCHAR(300) NOT NULL,
  PROJETO_RESUMO TEXT,
  SITUACAO_CODIGO CHAR(3) NOT NULL,
  SITUACAO_DESCRICAO VARCHAR(150) NOT NULL,
  PROPONENTE_CODIGO VARCHAR(14),
  PROPONENTE_TIPO CHAR,
  PROPONENTE_NOME VARCHAR(150),
  PROPONENTE_CIDADE VARCHAR(50),
  PROPONENTE_UF CHAR(2),
  PROPONENTE_CELULAR VARCHAR(12),
  PROPONENTE_COMERCIAL VARCHAR(12),
  PROPONENTE_EMAIL VARCHAR(100),
  PROPONENTE_RESPONSAVEL VARCHAR(100),
  PROJETO_DATA_INICIO_EXECUCAO timestamp,
  PROJETO_DATA_FIM_EXECUCAO timestamp,
  PROJETO_DATA_INICIO_EXECUCAO_FORMATADO VARCHAR(4000),
  PROJETO_DATA_FIM_EXECUCAO_FORMATADO VARCHAR(4000),
  PROJETO_VALOR_SOLICITADO_REAL MONEY,
  APROVACAO_DATA_APROVACAO timestamp,
  APROVACAO_DATA_APROVACAO_FORMATADO VARCHAR(4000),
  APROVACAO_DATA_INICIO_CAPTACAO timestamp,
  APROVACAO_DATA_FIM_CAPTACAO timestamp,
  APROVACAO_DATA_INICIO_CAPTACAO_FORMATADO VARCHAR(4000),
  APROVACAO_DATA_FIM_CAPTACAO_FORMATADO VARCHAR(4000),
  APROVACAO_VALOR_APROVADO_REAL MONEY,
  CAPTACAO_NUMERO_RECIBO VARCHAR(5),
  CAPTACAO_TIPO_APOIO CHAR,
  CAPTACAO_DATA_RECIBO timestamp,
  CAPTACAO_DATA_RECIBO_FORMATADO VARCHAR(4000),
  CAPTACAO_VALOR_CAPTACAO_REAL MONEY,
  INVESTIDOR_CODIGO VARCHAR(14),
  INVESTIDOR_TIPO CHAR,
  INVESTIDOR_NOME VARCHAR(150),
  INVESTIDOR_CIDADE VARCHAR(50),
  INVESTIDOR_UF CHAR(2),
  MUNICIPIO_ABRANGENCIA VARCHAR(100),
  UF_MUNICIPIO_ABRANGENCIA CHAR(2),
  CONVENIO_VALOR MONEY,
  ENQUADRAMENTO VARCHAR(9)
);
CREATE TABLE sac.DBA_Geral_Sem_Abrangencia
(
  PROJETO_PRONAC INT NOT NULL,
  PROJETO_PROJETO INT,
  PROJETO_ANO CHAR(2) NOT NULL,
  PROJETO_SEQUENCIAL VARCHAR(5) NOT NULL,
  PROJETO_UF CHAR(2) NOT NULL,
  AREA_DESCRICAO VARCHAR(50) NOT NULL,
  SEGMENTO_DESCRICAO VARCHAR(50) NOT NULL,
  PROJETO_MECANISMO CHAR NOT NULL,
  PROJETO_NOME VARCHAR(300) NOT NULL,
  SITUACAO_DESCRICAO VARCHAR(150) NOT NULL,
  PROPONENTE_CODIGO VARCHAR(14),
  PROPONENTE_NOME VARCHAR(150),
  PROPONENTE_CIDADE VARCHAR(50),
  PROJETO_DATA_INICIO_EXECUCAO timestamp,
  PROJETO_DATA_FIM_EXECUCAO timestamp,
  PROJETO_DATA_INICIO_EXECUCAO_FORMATADO VARCHAR(4000),
  PROJETO_DATA_FIM_EXECUCAO_FORMATADO VARCHAR(4000),
  PROJETO_VALOR_SOLICITADO_REAL MONEY,
  APROVACAO_DATA_INICIO_CAPTACAO VARCHAR(4000),
  APROVACAO_DATA_FIM_CAPTACAO VARCHAR(4000),
  APROVACAO_VALOR_APROVADO_REAL MONEY,
  CAPTACAO_NUMERO_RECIBO VARCHAR(5),
  CAPTACAO_TIPO_APOIO CHAR,
  CAPTACAO_DATA_RECIBO VARCHAR(4000),
  CAPTACAO_VALOR_CAPTACAO_REAL MONEY,
  INVESTIDOR_CODIGO VARCHAR(14),
  INVESTIDOR_NOME VARCHAR(150)
);
CREATE TABLE sac.DBA_Qtd_Apresentado_Aprovado_Ano_Mes
(
  ANO INT,
  MES INT,
  QUANTIDADE_APRESENTACAO INT,
  QUANTIDADE_APROVACAO INT,
  QUANTIDADE_CAPTACAO INT
);
CREATE TABLE sac.DBA_QTD_APRESENTADO_APROVADO_CAPTADO
(
  ANO INT,
  REGIAO VARCHAR(15) NOT NULL,
  UF CHAR(2) NOT NULL,
  SETOR VARCHAR(50) NOT NULL,
  QTD_APRESENTACAO INT,
  QTD_APROVACAO INT,
  QTD_CAPTACAO INT
);
CREATE TABLE sac.DBA_QTD_PROPONENTES_INABILITADOS
(
  QTD INT
);
CREATE TABLE sac.DBA_QTD_PROPOSTAS_ENVIADAS_SAV
(
  ANO INT,
  QTDE INT
);
CREATE TABLE sac.DBA_Relacao_Projetos
(
  PRONAC VARCHAR(7) NOT NULL,
  PROJETO_NOME VARCHAR,
  PROPONENTE_CODIGO VARCHAR(14),
  PROPONENTE_NOME VARCHAR,
  PROPONENTE_CIDADE VARCHAR(50),
  MECANISMO VARCHAR(50),
  SEGMENTO VARCHAR(50),
  ENQUADRAMENTO VARCHAR(9),
  ANO_APRESENTACAO INT,
  MES_APRESENTACAO INT,
  VALOR_APROVADO MONEY,
  VALOR_APOIADO MONEY,
  SITUACAO_CODIGO CHAR(3) NOT NULL,
  SITUACAO_DESCRICAO VARCHAR(150),
  ANO_SITUACAO INT,
  MES_SITUACAO INT,
  CIDADE_EXECUCAO VARCHAR
);
CREATE TABLE sac.DBA_Relacao_Projetos_FRED
(
  PRONAC VARCHAR(7) NOT NULL,
  ANO_PROJETO CHAR(2) NOT NULL,
  PROJETO_NOME VARCHAR,
  PROPONENTE_CODIGO VARCHAR(14),
  PROPONENTE_NOME VARCHAR,
  PROPONENTE_CIDADE VARCHAR(50),
  MECANISMO VARCHAR(50),
  SEGMENTO VARCHAR(50),
  AREA VARCHAR(50),
  TIPO_APOIO VARCHAR(10),
  ENQUADRAMENTO VARCHAR(9),
  SECRETARIA VARCHAR(5),
  MEDIDA_PROV CHAR,
  ANO_APRESENTACAO INT,
  MES_APRESENTACAO INT,
  DATA_APROVACAO VARCHAR(4000),
  VALOR_SOLICITADO_REAL MONEY,
  VALOR_APROVADO MONEY,
  VALOR_APOIADO MONEY,
  VALOR_CAPTADO MONEY,
  CAPTACAO_NUMERO_RECIBO VARCHAR(5),
  INVESTIDOR_CODIGO VARCHAR(14),
  INVESTIDOR_TIPO VARCHAR(15),
  INVESTIDOR_NOME VARCHAR(150),
  INVESTIDOR_CIDADE VARCHAR(50),
  INVESTIDOR_UF CHAR(2),
  SITUACAO_CODIGO CHAR(3) NOT NULL,
  SITUACAO_DESCRICAO VARCHAR(150),
  ANO_SITUACAO INT,
  MES_SITUACAO INT,
  CIDADE_EXECUCAO VARCHAR
);
CREATE TABLE sac.DBA_Relacao_Projetos_FRED_AGENTES
(
  PRONAC VARCHAR(7) NOT NULL,
  ANO_PROJETO CHAR(2) NOT NULL,
  PROJETO_NOME VARCHAR,
  PROPONENTE_CODIGO VARCHAR(14),
  MECANISMO VARCHAR(50),
  SEGMENTO VARCHAR(50),
  AREA VARCHAR(50),
  TIPO_APOIO VARCHAR(10),
  ENQUADRAMENTO VARCHAR(9),
  SECRETARIA VARCHAR(5),
  MEDIDA_PROV CHAR,
  ANO_APRESENTACAO INT,
  MES_APRESENTACAO INT,
  DATA_APROVACAO VARCHAR(4000),
  VALOR_SOLICITADO_REAL MONEY,
  VALOR_APROVADO MONEY,
  VALOR_APOIADO MONEY,
  VALOR_CAPTADO MONEY,
  CAPTACAO_NUMERO_RECIBO VARCHAR(5),
  INVESTIDOR_CODIGO VARCHAR(14),
  SITUACAO_CODIGO CHAR(3) NOT NULL,
  SITUACAO_DESCRICAO VARCHAR(150),
  ANO_SITUACAO INT,
  MES_SITUACAO INT,
  CIDADE_EXECUCAO VARCHAR
);
CREATE TABLE sac.DBA_Relacao_Projetos_FRED_TCU
(
  ANO INT,
  PRONAC VARCHAR(7) NOT NULL,
  PROJETO_NOME VARCHAR,
  UF_PROJETO CHAR(2) NOT NULL,
  AREA VARCHAR(50) NOT NULL,
  SEGMENTO VARCHAR(50) NOT NULL,
  MECANISMO VARCHAR(50) NOT NULL,
  PROCESSO VARCHAR(17),
  CNPJ_CPF VARCHAR(14) NOT NULL,
  NOME_PROPONENTE VARCHAR(150),
  SITUACAO CHAR(3) NOT NULL,
  MODALIDADE VARCHAR(3),
  ORGAO_ORIGEM VARCHAR(20) NOT NULL,
  ORGAO VARCHAR(20) NOT NULL,
  VALOR_SOLICITADO MONEY,
  VALOR_CAPTADO MONEY,
  ENQUADRAMENTO VARCHAR(9),
  SECRETARIA VARCHAR(5)
);
CREATE TABLE sac.DBA_Relacao_Projetos_FRED_TCU_APROVADOS
(
  DATA VARCHAR(4000),
  PRONAC VARCHAR(7) NOT NULL,
  PROJETO_NOME VARCHAR,
  UF_PROJETO CHAR(2) NOT NULL,
  AREA VARCHAR(50) NOT NULL,
  SEGMENTO VARCHAR(50) NOT NULL,
  MECANISMO VARCHAR(50) NOT NULL,
  PROCESSO VARCHAR(17),
  CNPJ_CPF VARCHAR(14) NOT NULL,
  NOME_PROPONENTE VARCHAR(150),
  SITUACAO CHAR(3) NOT NULL,
  MODALIDADE VARCHAR(3),
  ORGAO_ORIGEM VARCHAR(20) NOT NULL,
  ORGAO VARCHAR(20) NOT NULL,
  VALOR_SOLICIDADO MONEY,
  VALOR_APROVADO MONEY,
  ENQUADRAMENTO VARCHAR(9),
  SECRETARIA VARCHAR(5)
);
CREATE TABLE sac.DBA_Relacao_Projetos_Geral
(
  PRONAC VARCHAR(7) NOT NULL,
  PROJETO_NOME VARCHAR,
  PROPONENTE_CODIGO VARCHAR(14),
  PROPONENTE_NOME VARCHAR,
  PROPONENTE_CIDADE VARCHAR(50),
  MECANISMO VARCHAR(50),
  SEGMENTO VARCHAR(50),
  ENQUADRAMENTO VARCHAR(9),
  ANO_APRESENTACAO INT,
  MES_APRESENTACAO INT,
  VALOR_APROVADO MONEY,
  VALOR_APOIADO MONEY,
  SITUACAO_CODIGO CHAR(3) NOT NULL,
  SITUACAO_DESCRICAO VARCHAR(150),
  ANO_SITUACAO INT,
  MES_SITUACAO INT
);
CREATE TABLE sac.DBA_TCU_CAPTACAO_SAV
(
  ANO INT,
  QTD INT,
   Valor_Captado_SAV FLOAT
);
CREATE TABLE sac.DBA_TCU_CAPTACAO_SEFIC
(
  ANO INT,
  QTD INT,
   Valor_Captado_SEFIC MONEY
);
CREATE TABLE sac.DBA_Total_Investido_Investidor_Ano
(
  CNPJ_CPF VARCHAR(14) NOT NULL,
  ANO_INVESTIMENTO INT,
  VALOR_INVESTIDO_TOTAL FLOAT
);
CREATE TABLE sac.DBA_Valor_Solicitado_Captado_Projeto
(
  PROJETO_ENQUADRAMENTO int NOT NULL,
  PROJETO_ANO CHAR(2) NOT NULL,
  PROJETO_SEQUENCIAL VARCHAR(5) NOT NULL,
  PROJETO_AREA CHAR NOT NULL,
  PROJETO_SITUACAO CHAR(3) NOT NULL,
  VALOR_SOLICITADO MONEY,
  VALOR_CAPTADO MONEY,
  ANO_RECIBO INT
);
CREATE TABLE sac.DBA_Valor_Solicitado_Captado_Projeto_SAV
(
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  Situacao CHAR(3) NOT NULL,
  ANO_PROTOCOLO INT,
  VALOR_APROVADO MONEY,
  VALOR_CAPTADO MONEY NOT NULL
);
CREATE TABLE sac.Exibicao1
(
  Uf CHAR(2),
  Regiao VARCHAR(50)
);
CREATE TABLE sac.IncentivoPorCgcCpf
(
  CgcCpf VARCHAR(14) NOT NULL,
  ApoioReal MONEY
);
CREATE TABLE sac.vAcaoProjeto
(
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  Solicitado MONEY,
  Aprovado MONEY
);
CREATE TABLE sac.vAnexoAproCondicional
(
  NumeroReuniao INT,
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  NomeProjeto VARCHAR(300) NOT NULL,
  UfProjeto CHAR(2) NOT NULL,
  Nome VARCHAR(150) NOT NULL,
  Area CHAR NOT NULL,
  Situacao CHAR(3) NOT NULL,
  ResumoAprovacao TEXT,
  AutorizadoReal MONEY
);
CREATE TABLE sac.vAnexoAproInicialPorReuniao
(
  NumeroReuniao INT,
  Cidade VARCHAR(50) NOT NULL,
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  ValorAprovado MONEY
);
CREATE TABLE sac.vAnexoComplementacaoPorReuniao
(
  NumeroReuniao INT,
  Cidade VARCHAR(50) NOT NULL,
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  ValorAprovado MONEY
);
CREATE TABLE sac.vAnexoReducaoPorReuniao
(
  NumeroReuniao INT,
  Cidade VARCHAR(50) NOT NULL,
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  ValorAprovado MONEY
);
CREATE TABLE sac.vAnexoRetiradosPautaPorReuniao
(
  NumeroReuniao INT,
  Cidade VARCHAR(50) NOT NULL,
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL
);
CREATE TABLE sac.vApoioAnoAreaTPArt1
(
  Ano INT,
  Area CHAR NOT NULL,
  TipoPessoa CHAR NOT NULL,
  Quantidade INT,
  ApoioReal MONEY
);
CREATE TABLE sac.vApoioAnoAreaTPArt3
(
  Ano INT,
  Area CHAR NOT NULL,
  TipoPessoa CHAR NOT NULL,
  Quantidade INT,
  ApoioReal MONEY
);
CREATE TABLE sac.vApoioAnoAreaTrimArt1
(
  Ano INT,
  Area CHAR NOT NULL,
  Trimestre1 MONEY,
  Trimestre2 MONEY,
  Trimestre3 MONEY,
  Trimestre4 MONEY
);
CREATE TABLE sac.vApoioAnoAreaTrimArt3
(
  Ano INT,
  Area CHAR NOT NULL,
  Trimestre1 MONEY,
  Trimestre2 MONEY,
  Trimestre3 MONEY,
  Trimestre4 MONEY
);
CREATE TABLE sac.vApoioAnoUFTrimArt1
(
  Ano INT,
  UF CHAR(2) NOT NULL,
  Trimestre1 MONEY NOT NULL,
  Trimestre2 MONEY NOT NULL,
  Trimestre3 MONEY NOT NULL,
  Trimestre4 MONEY NOT NULL
);
CREATE TABLE sac.vApoioAnoUFTrimArt3
(
  Ano INT,
  UF CHAR(2) NOT NULL,
  Trimestre1 MONEY,
  Trimestre2 MONEY,
  Trimestre3 MONEY,
  Trimestre4 MONEY
);
CREATE TABLE sac.vApoioAnoUfTrimMecAudio
(
  Ano INT,
  UF CHAR(2) NOT NULL,
  Trimestre1 MONEY,
  Trimestre2 MONEY,
  Trimestre3 MONEY,
  Trimestre4 MONEY
);
CREATE TABLE sac.vApoioGeralAnoAreaTrim
(
  Ano INT,
  Area CHAR NOT NULL,
  Lei INT NOT NULL,
  Trimestre1 MONEY,
  Trimestre2 MONEY,
  Trimestre3 MONEY,
  Trimestre4 MONEY
);
CREATE TABLE sac.vApoioInvesAnoMesProj
(
  Ano INT,
  Mes INT,
  CgcCpf VARCHAR(14) NOT NULL,
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  ApoioReal MONEY
);
CREATE TABLE sac.vApoioInvesAnoProj
(
  Ano INT,
  CgcCpf VARCHAR(14) NOT NULL,
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  ApoioReal MONEY
);
CREATE TABLE sac.vApoioInvestidorAno
(
  Ano INT,
  CgcCpf VARCHAR(14) NOT NULL,
  ApoioReal MONEY
);
CREATE TABLE sac.vApoioInvestidorAnoMes
(
  Ano INT,
  Mes INT,
  CgcCpf VARCHAR(14) NOT NULL,
  ApoioReal MONEY
);
CREATE TABLE sac.vApoioInvestidorGeralAno
(
  Ano INT,
  CgcCpf VARCHAR(14) NOT NULL,
  ApoioReal MONEY
);
CREATE TABLE sac.vApoioPorAnoAreaMes
(
  Ano INT,
  Area CHAR NOT NULL,
  Mes1 MONEY,
  Mes2 MONEY,
  Mes3 MONEY,
  Mes4 MONEY,
  Mes5 MONEY,
  Mes6 MONEY,
  Mes7 MONEY,
  Mes8 MONEY,
  Mes9 MONEY,
  Mes10 MONEY,
  Mes11 MONEY,
  Mes12 MONEY
);
CREATE TABLE sac.vApoioPorAnoAreaTPMA
(
  Ano INT,
  Area CHAR NOT NULL,
  Lei INT NOT NULL,
  TipoPessoa CHAR NOT NULL,
  Quantidade INT,
  ApoioReal MONEY
);
CREATE TABLE sac.vApoioPorAnoAreaTrimestre
(
  Ano INT,
  Area CHAR NOT NULL,
  Trimestre1 MONEY,
  Trimestre2 MONEY,
  Trimestre3 MONEY,
  Trimestre4 MONEY
);
CREATE TABLE sac.vApoioPorAnoUfTrimestre
(
  Ano INT,
  UF CHAR(2) NOT NULL,
  Trimestre1 MONEY,
  Trimestre2 MONEY,
  Trimestre3 MONEY,
  Trimestre4 MONEY
);
CREATE TABLE sac.vApoioPorArea
(
  Ano INT,
  Area CHAR NOT NULL,
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  ApoioUfir MONEY,
  ApoioReal MONEY
);
CREATE TABLE sac.vApoioPorMecena
(
  Ano INT,
  Mes INT,
  CgcCpf VARCHAR(14) NOT NULL,
  ApoioUfir MONEY,
  ApoioReal MONEY
);
CREATE TABLE sac.vApoioPorMecenaAno
(
  Ano INT,
  CgcCpf VARCHAR(14) NOT NULL,
  ApoioUfir MONEY,
  ApoioReal MONEY
);
CREATE TABLE sac.vApoioPorMecenaProjeto
(
  Ano INT,
  Mes INT,
  CgcCpf VARCHAR(14) NOT NULL,
  Mp CHAR NOT NULL,
  TipoApoio CHAR NOT NULL,
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  ApoioUfir MONEY,
  ApoioReal MONEY
);
CREATE TABLE sac.vApoioRenunciaPrivadoAno
(
  Ano VARCHAR(20),
  Captacao MONEY,
  Renuncia MONEY,
  PercRen MONEY,
  Privado MONEY,
  PercPri MONEY
);
CREATE TABLE sac.vApoioSubsAnoMesProj
(
  Ano INT,
  Mes INT,
  CgcCpfSub VARCHAR(14) NOT NULL,
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  ApoioReal MONEY
);
CREATE TABLE sac.vApoioSubsAnoProj
(
  Ano INT,
  CgcCpfSub VARCHAR(14) NOT NULL,
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  ApoioReal MONEY
);
CREATE TABLE sac.vApoioSubscritorAno
(
  Ano INT,
  CgcCpfSub VARCHAR(14) NOT NULL,
  ApoioReal MONEY
);
CREATE TABLE sac.vApoioSubscritorAnoMes
(
  Ano INT,
  Mes INT,
  CgcCpfSub VARCHAR(14) NOT NULL,
  ApoioReal MONEY
);
CREATE TABLE sac.vAproCap
(
  Mecanismo CHAR NOT NULL,
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  NomeProjeto VARCHAR(300) NOT NULL,
  Segmento VARCHAR(4) NOT NULL,
  UfProjeto CHAR(2) NOT NULL,
  Situacao CHAR(3) NOT NULL,
  Solicitado MONEY,
  Aprovado MONEY,
  Captado MONEY
);
CREATE TABLE sac.vAproCapSaldo
(
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  Aprovado MONEY,
  Captado MONEY,
  Saldo MONEY
);
CREATE TABLE sac.vAprovacaoInicial
(
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  DtAprovacao timestamp NOT NULL,
  PortariaAprovacao VARCHAR(10),
  DtPublicacaoAprovacao timestamp,
  DtInicioCaptacao timestamp,
  DtFimCaptacao timestamp,
  AprovadoUfir MONEY,
  AprovadoReal MONEY,
  AutorizadoUfir MONEY,
  AutorizadoReal MONEY,
  ConcedidoCusteioReal MONEY,
  ConcedidoCapitalReal MONEY
);
CREATE TABLE sac.vAprovadoCaptado
(
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  Aprovado MONEY,
  Captado MONEY
);
CREATE TABLE sac.vAprovadoCaptadoRenuncia
(
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  Aprovado MONEY,
  Captado MONEY,
  Renuncia MONEY
);
CREATE TABLE sac.vAprovadoCaptadoRenunciaAno
(
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  Aprovado MONEY,
  Captado MONEY,
  Renuncia MONEY
);
CREATE TABLE sac.vAprovadoLeisPorAno
(
  Ano INT,
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  AproMec MONEY,
  AproArt1 MONEY,
  AproArt3 MONEY,
  CompMec MONEY,
  CompArt1 MONEY,
  CompArt3 MONEY,
  ReduMec MONEY,
  ReduArt1 MONEY,
  ReduArt3 MONEY
);
CREATE TABLE sac.vAprovadoMunicipio
(
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  NomeProjeto VARCHAR(300) NOT NULL,
  Area CHAR NOT NULL,
  Proponente VARCHAR(150) NOT NULL,
  DtAprovacao timestamp NOT NULL,
  AprovadoReal MONEY
);
CREATE TABLE sac.vArt3Joao
(
  cgccpf VARCHAR(14) NOT NULL,
  Ano CHAR(2) NOT NULL,
  Seq VARCHAR(5) NOT NULL,
  NomeProjeto VARCHAR(300) NOT NULL,
  Aprovado MONEY,
  Captado MONEY,
  Saldo MONEY,
  DtFinal timestamp
);
CREATE TABLE sac.vAvaliacaoAcompBandas
(
  Regiao VARCHAR(15) NOT NULL,
  UF CHAR(2) NOT NULL,
  Ident INT NOT NULL,
  Tipo int NOT NULL,
  A INT,
  B INT,
  C INT,
  D INT,
  E VARCHAR(1) NOT NULL
);
CREATE TABLE sac.vBoucinhas
(
  Proponente VARCHAR(100) NOT NULL,
  NomeProjeto VARCHAR(100) NOT NULL,
  Mecanismo VARCHAR(50) NOT NULL,
  Area VARCHAR(50) NOT NULL,
  Segmento VARCHAR(50) NOT NULL,
  Regiao VARCHAR(15) NOT NULL,
  UFProjeto CHAR(2) NOT NULL,
  Cidade VARCHAR(50) NOT NULL,
  AnoEntradaProjeto INT,
  AnoAutorizacao INT,
  AnoRepasse INT,
  ValorSolicitado MONEY,
  ValorAutorizado MONEY,
  ValorConveniado MONEY,
  Natureza VARCHAR(13) NOT NULL,
  Esfera VARCHAR(13) NOT NULL
);
CREATE TABLE sac.vCadastrarDirigente
(
  CNPJCPF VARCHAR(14) NOT NULL,
  CNPJCPFSuperior VARCHAR(14),
  idNome INT,
  TipoNome INT,
  Nome VARCHAR(100),
  idVinculoPrincipal INT,
  Usuario INT NOT NULL
);
CREATE TABLE sac.vCadastrarInternet
(
  idInternet INT NOT NULL,
  idAgente INT NOT NULL,
  TipoInternet INT NOT NULL,
  Descricao VARCHAR(100) NOT NULL,
  Status INTEGER NOT NULL,
  Divulgar INTEGER NOT NULL,
  Usuario INT NOT NULL
);
CREATE TABLE sac.vCadastrarProponente
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
  DivulgarEndereco INTEGER,
  Correspondencia INTEGER,
  Usuario INT NOT NULL
);
CREATE TABLE sac.vCadastrarTelefones
(
  idTelefone INT NOT NULL,
  idAgente INT NOT NULL,
  TipoTelefone INT NOT NULL,
  UF INT NOT NULL,
  DDD INT NOT NULL,
  Numero VARCHAR(12) NOT NULL,
  Divulgar INTEGER NOT NULL,
  Usuario INT NOT NULL
);
CREATE TABLE sac.vCapAnoProjetoArt1
(
  Ano INT,
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  ApoioReal MONEY
);
CREATE TABLE sac.vCapAnoProjetoArt3
(
  Ano INT,
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  ApoioReal MONEY
);
CREATE TABLE sac.vCapGeralIncentivador
(
  CgcCpf VARCHAR(14) NOT NULL,
  ApoioReal MONEY
);
CREATE TABLE sac.vCapGeralIncentProj
(
  Lei INT NOT NULL,
  CgcCpf VARCHAR(14) NOT NULL,
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  ApoioReal MONEY
);
CREATE TABLE sac.vCapGeralPorAnoProjeto
(
  Lei INT NOT NULL,
  Ano INT,
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  ApoioReal MONEY
);
CREATE TABLE sac.vCapGeralPorArea
(
  Ano INT,
  Lei INT NOT NULL,
  Area CHAR NOT NULL,
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  ApoioReal MONEY
);
CREATE TABLE sac.vCapGeralPorAreaSegmento
(
  Ano INT,
  Lei INT NOT NULL,
  Area CHAR NOT NULL,
  Segmento VARCHAR(4) NOT NULL,
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  ApoioReal MONEY
);
CREATE TABLE sac.vCapGeralPorProjeto
(
  Lei INT NOT NULL,
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  ApoioReal MONEY
);
CREATE TABLE sac.vCapGeralPorUF
(
  Ano INT,
  Lei INT NOT NULL,
  UF CHAR(2) NOT NULL,
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  ApoioReal MONEY
);
CREATE TABLE sac.vCapGeralProjetoIncentivo
(
  Lei INT NOT NULL,
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  CgcCpf VARCHAR(14) NOT NULL,
  ApoioReal MONEY
);
CREATE TABLE sac.vCapGerPorAnoProjIncent
(
  Ano INT,
  Lei INT NOT NULL,
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  CgcCpf VARCHAR(14) NOT NULL,
  ApoioReal MONEY
);
CREATE TABLE sac.vCapMecAudioAnoProjeto
(
  Ano INT,
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  ApoioReal MONEY
);
CREATE TABLE sac.vCapMecAudioProjeto
(
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  ApoioReal MONEY
);
CREATE TABLE sac.vCaptacaoAnoUFAreaSegmentoSemestre
(
  Ano INT,
  UFProjeto CHAR(2) NOT NULL,
  Area CHAR NOT NULL,
  Segmento VARCHAR(4) NOT NULL,
  Semestre_1 MONEY,
  Semestre_2 MONEY,
  Total MONEY
);
CREATE TABLE sac.vCaptacaoPorMPTipoApoioMecena
(
  Ano INT,
  Mes INT,
  MP CHAR NOT NULL,
  TipoApoio CHAR NOT NULL,
  CgcCpf VARCHAR(14) NOT NULL,
  ApoioUfir MONEY,
  ApoioReal MONEY
);
CREATE TABLE sac.vCaptado
(
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  CaptacaoUfir MONEY,
  CaptacaoReal MONEY
);
CREATE TABLE sac.vCaptadoAno
(
  AnoRecibo INT,
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  MedidaProvisoria CHAR NOT NULL,
  CaptacaoUfir MONEY,
  CaptacaoReal MONEY
);
CREATE TABLE sac.vCaptadoAnoProjeto
(
  AnoRecibo INT,
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  CaptacaoReal MONEY
);
CREATE TABLE sac.vCartaAudio
(
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL
);
CREATE TABLE sac.vCartaPropAudio
(
  Nome VARCHAR(150) NOT NULL,
  Endereco VARCHAR(100) NOT NULL,
  Cidade VARCHAR(50) NOT NULL,
  UF CHAR(2) NOT NULL,
  CEP VARCHAR(8) NOT NULL
);
CREATE TABLE sac.VCartas
(
  Orgao INT NOT NULL,
  Logon INT NOT NULL,
  DTCarta timestamp NOT NULL,
  Area CHAR NOT NULL,
  Segmento VARCHAR(4) NOT NULL,
  Numero VARCHAR(3) NOT NULL,
  Situacao CHAR(3) NOT NULL,
  Ano CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  NomeProjeto VARCHAR(300) NOT NULL,
  Processo VARCHAR(17),
  CgcCpf VARCHAR(14) NOT NULL,
  Nome VARCHAR(150) NOT NULL,
  Responsavel VARCHAR(100) NOT NULL,
  Endereco VARCHAR(100) NOT NULL,
  Cep VARCHAR(8) NOT NULL,
  UF CHAR(2) NOT NULL,
  Cidade VARCHAR(50) NOT NULL,
  UnidadeAnalise VARCHAR(15)
);
CREATE TABLE sac.vCelulaOrcamentaria
(
  Codigo INT NOT NULL,
  PT VARCHAR(17) NOT NULL,
  PTRES VARCHAR(6) NOT NULL,
  FT VARCHAR(3) NOT NULL,
  ED VARCHAR(8) NOT NULL
);
CREATE TABLE sac.vChecaProponente
(
  CgcCpf VARCHAR(14) NOT NULL,
  Quant INT,
  Solicitado MONEY,
  Aprovado MONEY,
  Captado MONEY
);
CREATE TABLE sac.vChecaQteInabilitacao
(
  CgcCpf VARCHAR(14) NOT NULL,
  Quant INT
);
CREATE TABLE sac.vCi
(
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  CI VARCHAR(20),
  OrgaoExpedidor VARCHAR(20),
  DtExpedicao VARCHAR(20)
);
CREATE TABLE sac.vCiset
(
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  NomeProjeto VARCHAR(100) NOT NULL,
  Processo CHAR(15) NOT NULL,
  UFProjeto CHAR(2) NOT NULL,
  Area VARCHAR(50) NOT NULL,
  Segmento VARCHAR(50) NOT NULL,
  Mecanismo VARCHAR(50) NOT NULL,
  Situacao VARCHAR(50) NOT NULL,
  Solicitado MONEY,
  Aprovado MONEY,
  Captado MONEY,
  aCaptar MONEY,
  DtParecer timestamp NOT NULL,
  Parecerista VARCHAR(30),
  ResumoParecer TEXT,
  CgcCpf VARCHAR(14) NOT NULL,
  Nome VARCHAR(100) NOT NULL,
  DtAprovacao timestamp NOT NULL,
  PortariaAprovacao VARCHAR(10),
  DtPortariaAprovacao timestamp,
  DtPublicacaoAprovacao timestamp,
  DtInicioCaptacao timestamp,
  DtFimCaptacao timestamp
);
CREATE TABLE sac.vCnpjCpf
(
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  CgcCpf VARCHAR(20)
);
CREATE TABLE sac.vCnpjValido
(
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  NomeProjeto VARCHAR(100) NOT NULL,
  CgcCpf VARCHAR(14) NOT NULL,
  CnpjValido INT,
  Nome VARCHAR(100) NOT NULL
);
CREATE TABLE sac.vComparativoAno
(
  Ano VARCHAR(20),
  Q_Apresentado INT,
  T_Apresentado MONEY,
  Q_Aprovado INT,
  T_Aprovado MONEY,
  Q_Captado INT,
  T_Captado MONEY
);
CREATE TABLE sac.vComparativoAnoMec
(
  Ano VARCHAR(20),
  Q_Apresentado INT,
  T_Apresentado MONEY,
  Q_Aprovado INT,
  T_Aprovado MONEY,
  Q_Captado INT,
  T_Captado MONEY
);
CREATE TABLE sac.vcontabancaria
(
  idcontabancaria INT NOT NULL,
  anoprojeto CHAR(2) NOT NULL,
  sequencial VARCHAR(5) NOT NULL,
  mecanismo CHAR NOT NULL,
  banco CHAR(3) NOT NULL,
  agencia INT,
  digito_agencia VARCHAR(1),
  conta INT,
  digito_conta VARCHAR(1),
  dtloteremessa timestamp,
  loteremessa CHAR(5),
  logon INT NOT NULL,
  idpronac INT,
  tipo VARCHAR(24) NOT NULL
);
CREATE TABLE sac.vContaCorrente
(
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  Banco VARCHAR(20),
  Agencia VARCHAR(20),
  ContaCorrente VARCHAR(20),
  Artigo VARCHAR(20)
);
CREATE TABLE sac.vConvenioAnoArea
(
  Ano INT,
  Area CHAR NOT NULL,
  Segmento VARCHAR(4) NOT NULL,
  ValorConveniol MONEY
);
CREATE TABLE sac.vConvenioAnoUf
(
  Ano INT,
  Regiao VARCHAR(15) NOT NULL,
  Descricao VARCHAR(30) NOT NULL,
  ValorConvenio MONEY
);
CREATE TABLE sac.vConveniosDoc
(
  NumeroConvenio VARCHAR(15) NOT NULL,
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  Objeto TEXT,
  DtFinalVigencia timestamp,
  NomeProjeto VARCHAR(100) NOT NULL,
  Processo CHAR(15) NOT NULL,
  Total MONEY,
  ContraPartidaReal MONEY,
  Banco VARCHAR(20),
  Agencia VARCHAR(20),
  ContaCorrente VARCHAR(20),
  Praca VARCHAR(50) NOT NULL,
  ufPraca CHAR(2) NOT NULL,
  CI VARCHAR(20),
  OrgaoExpedidor VARCHAR(20),
  CgcCpf VARCHAR(14) NOT NULL,
  Proponente VARCHAR(100) NOT NULL,
  Endereco VARCHAR(100) NOT NULL,
  Cidade VARCHAR(50) NOT NULL,
  Uf CHAR(2) NOT NULL,
  Responsavel VARCHAR(100) NOT NULL,
  cusPT VARCHAR(17),
  cusPTRES VARCHAR(6),
  cusFT VARCHAR(3),
  cusED VARCHAR(8),
  ValorCusteio MONEY,
  capPT VARCHAR(17),
  capPTRES VARCHAR(6),
  capFT VARCHAR(3),
  capED VARCHAR(8),
  ValorCapital MONEY,
  CpfResponsavel VARCHAR(20)
);
CREATE TABLE sac.vCustoDoProjeto
(
  TipoPrestacao INTEGER NOT NULL,
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  CustoProjeto MONEY
);
CREATE TABLE sac.vDadosComplementaresDeCartas
(
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  NomeProjeto VARCHAR(300) NOT NULL,
  Processo VARCHAR(17),
  Area CHAR NOT NULL,
  Segmento VARCHAR(4) NOT NULL,
  CgcCpf VARCHAR(14) NOT NULL,
  Proponente VARCHAR(150) NOT NULL,
  Responsavel VARCHAR(100) NOT NULL,
  Endereco VARCHAR(100) NOT NULL,
  Cep VARCHAR(8) NOT NULL,
  Uf CHAR(2) NOT NULL,
  Cidade VARCHAR(50) NOT NULL,
  NrCarta VARCHAR(3) NOT NULL,
  DtCarta timestamp NOT NULL,
  Orgao INT NOT NULL,
  DtAprovacao timestamp,
  NrPortaria VARCHAR(10),
  DtPortaria timestamp,
  DtPublicacao timestamp,
  DtInicioCaptacao timestamp,
  DtFimCaptacao timestamp,
  SaldoACaptar MONEY
);
CREATE TABLE sac.vDadosConvenio
(
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  DtConvenio timestamp,
  NrConvenio VARCHAR(15),
  DtInicioVigencia timestamp,
  DtFimVigencia timestamp,
  VlConvenio MONEY,
  Contador INT NOT NULL
);
CREATE TABLE sac.vDadosPortariaAprovacao
(
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  Numero VARCHAR(10),
  DtPortaria timestamp,
  DtPublicacao timestamp
);
CREATE TABLE sac.vDiligencias
(
  NrProjeto VARCHAR(7) NOT NULL,
  NomeProjeto VARCHAR(300) NOT NULL,
  Controladores int NOT NULL,
  DocumentoSolicitacao int NOT NULL,
  NrDocumentoSolicitacao VARCHAR(20) NOT NULL,
  DtSolicitacao timestamp,
  Posicionamento int NOT NULL,
  Constatacao TEXT NOT NULL,
  DocumentoResposta int,
  NrDocumentoResposta VARCHAR(20),
  DtResposta timestamp,
  Resposta TEXT NOT NULL
);
CREATE TABLE sac.vDocumentoExigido
(
  AnoProjeto CHAR(2),
  Sequencial VARCHAR(5),
  DocumentoExigido VARCHAR(100) NOT NULL
);
CREATE TABLE sac.vDtPrimeiraUltimaCaptacao
(
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  DtPrimeira timestamp,
  DtUltima timestamp
);
CREATE TABLE sac.vEmpresasIncentivadorasAno
(
  Ano INT,
  CgcCpf VARCHAR(14) NOT NULL,
  Empresa VARCHAR(150) NOT NULL,
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  ApoioReal MONEY
);
CREATE TABLE sac.vEtiquetaEneida
(
  CgcCpf VARCHAR(14) NOT NULL,
  Nome VARCHAR(150) NOT NULL
);
CREATE TABLE sac.vFnAproCapProj
(
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  AproMec MONEY,
  CapMec MONEY,
  AproArt1 MONEY,
  CapArt1 MONEY,
  AproArt3 MONEY,
  CapArt3 MONEY,
  AproConv MONEY,
  CapConv MONEY,
  AproContra MONEY,
  CapContra MONEY
);
CREATE TABLE sac.vFnAproCapProjMec
(
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  AproMec MONEY,
  CapMec MONEY
);
CREATE TABLE sac.vFnIncCapRenuncia
(
  Ano INT,
  CgcCpf VARCHAR(14) NOT NULL,
  Captacao MONEY,
  Renuncia MONEY
);
CREATE TABLE sac.vFnOrcDetalhado
(
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  ProMec MONEY,
  ComMec MONEY,
  ProArt1 MONEY,
  ComArt1 MONEY,
  ProArt3 MONEY,
  ComArt3 MONEY
);
CREATE TABLE sac.vFnTotalAproCapProj
(
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  Aprovado MONEY,
  Captado MONEY
);
CREATE TABLE sac.vForaDaPortaria
(
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  NomeProjeto VARCHAR(300) NOT NULL,
  TipoAprovacao CHAR NOT NULL,
  DtAprovacao timestamp NOT NULL,
  DiasVencido INT,
  Situacao CHAR(3) NOT NULL,
  Orgao INT NOT NULL
);
CREATE TABLE sac.vFsac3485
(
  Proponente VARCHAR(150) NOT NULL,
  Endereco VARCHAR(100) NOT NULL,
  Cidade VARCHAR(50) NOT NULL,
  UF CHAR(2) NOT NULL,
  Cep VARCHAR(8) NOT NULL,
  NomeProjeto VARCHAR(300) NOT NULL
);
CREATE TABLE sac.vFunarte
(
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  NomeProjeto VARCHAR(300),
  TipoParecer VARCHAR(22) NOT NULL,
  DtParecer timestamp NOT NULL,
  Parecerista VARCHAR(30),
  Aprovado MONEY
);
CREATE TABLE sac.vGerentes
(
  org_codigo SMALLINT NOT NULL,
  org_sigla CHAR(12) NOT NULL,
  org_estrutura VARCHAR(100),
  org_nome VARCHAR(80) NOT NULL,
  org_gerente INT NOT NULL,
  org_nomegerente VARCHAR(80) NOT NULL,
  fun_descricao VARCHAR(100) NOT NULL,
  fun_funcaoorgao VARCHAR(100) NOT NULL,
  usu_codigo SMALLINT,
  usu_identificacao CHAR(16),
  usu_nome VARCHAR(20),
  usu_pessoa INT,
  usu_orgao SMALLINT,
  usu_sala VARCHAR(20),
  usu_ramal SMALLINT,
  usu_nivel int
);
CREATE TABLE sac.vGuiaNaoVinculada
(
  NumeroGuia INT NOT NULL,
  CgcCpf VARCHAR(14) NOT NULL,
  DtRecolhimento timestamp NOT NULL,
  Valor MONEY NOT NULL
);
CREATE TABLE sac.vGuiasDeRecolhimento
(
  Vinculo VARCHAR(13) NOT NULL,
  NumeroGuia INT NOT NULL,
  CgcCpf VARCHAR(14) NOT NULL,
  Subscritor VARCHAR(150) NOT NULL,
  DtRecolhimento timestamp NOT NULL,
  DtVencimento timestamp,
  Dias INT,
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  NomeProjeto VARCHAR(300) NOT NULL,
  Valor MONEY NOT NULL
);
CREATE TABLE sac.vIncentivador
(
  Area CHAR NOT NULL,
  CgcCpf VARCHAR(14) NOT NULL,
  ApoioUfir MONEY,
  ApoioReal MONEY
);
CREATE TABLE sac.vIncentivadorAno
(
  Ano INT,
  CgcCpf VARCHAR(14) NOT NULL,
  Mp CHAR NOT NULL,
  TipoApoio CHAR NOT NULL,
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  ApoioUfir MONEY,
  ApoioReal MONEY
);
CREATE TABLE sac.vIncentivadorAudioAno
(
  Ano INT,
  CgcCpf VARCHAR(14) NOT NULL,
  ApoioReal MONEY
);
CREATE TABLE sac.vIncentivadoresMA
(
  Ano INT,
  CgcCpf VARCHAR(14) NOT NULL
);
CREATE TABLE sac.vIncentivadorPessoaAno
(
  Ano INT,
  Regiao VARCHAR(15) NOT NULL,
  UF VARCHAR(30) NOT NULL,
  TipoPessoa CHAR NOT NULL,
  Nome VARCHAR(150) NOT NULL,
  ApoioReal MONEY
);
CREATE TABLE sac.vIncentivadorPorProjeto
(
  CgcCpf VARCHAR(14) NOT NULL,
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  ApoioReal MONEY
);
CREATE TABLE sac.vInvestimentoMinc
(
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  CgcCpf VARCHAR(14) NOT NULL,
  Bolsa MONEY,
  Passagem MONEY,
  Convenio MONEY
);
CREATE TABLE sac.vLiberado
(
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  DtLiberacao timestamp,
  Permissao CHAR
);
CREATE TABLE sac.vMaioresIncentivadoresProjetos
(
  Ano INT,
  Incentivador VARCHAR(14) NOT NULL,
  AnoProjeto CHAR(2),
  Sequencial VARCHAR(5),
  Valor MONEY
);
CREATE TABLE sac.vMostraConvenio
(
  Opcao VARCHAR(11) NOT NULL,
  NrConvenio VARCHAR(15) NOT NULL,
  DtConvenio VARCHAR(10) NOT NULL,
  DtPublicacao VARCHAR(10) NOT NULL,
  DtInicioExecucao VARCHAR(10) NOT NULL,
  DtFinalExecucao VARCHAR(10) NOT NULL,
  DtInicioVigencia VARCHAR(10) NOT NULL,
  DtFinalVigencia VARCHAR(10) NOT NULL,
  ValorConvenio MONEY,
  Objeto TEXT,
  Usuario VARCHAR(20) NOT NULL,
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL
);
CREATE TABLE sac.vMostraEmpenho
(
  NrEmpenho CHAR(12) NOT NULL,
  DtEmpenho timestamp NOT NULL,
  Valor MONEY NOT NULL,
  UgGestao CHAR(6) NOT NULL,
  UGR CHAR(6) NOT NULL,
  PtRes CHAR(6) NOT NULL,
  ProgramaTrabalho CHAR(17) NOT NULL,
  FonteRecurso CHAR(3) NOT NULL,
  NaturezaDespesa CHAR(8) NOT NULL,
  Usuario VARCHAR(20) NOT NULL,
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL
);
CREATE TABLE sac.vMostraOrdemBancaria
(
  NrOrdemBancaria CHAR(12) NOT NULL,
  DtOrdemBancaria timestamp NOT NULL,
  Valor MONEY NOT NULL,
  Usuario VARCHAR(20) NOT NULL,
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL
);
CREATE TABLE sac.vNacional
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
  Status INTEGER NOT NULL,
  Divulgar INTEGER NOT NULL,
  Usuario INT NOT NULL
);
CREATE TABLE sac.vNegativaInabilitado
(
  hORGAO INT NOT NULL,
  hNUMEROCARTA VARCHAR(3) NOT NULL,
  hDTCARTA timestamp NOT NULL,
  hANOPROJETO CHAR(2) NOT NULL,
  hSEQUENCIAL VARCHAR(5) NOT NULL,
  pNOMEPROJETO VARCHAR(300) NOT NULL,
  pAREA CHAR NOT NULL,
  pPROCESSO VARCHAR(17),
  iANOPROJETO CHAR(2),
  iSEQUENCIAL VARCHAR(5),
  iORGAO INT,
  hLogon INT NOT NULL
);
CREATE TABLE sac.vNegativaInabilitadoEtiqueta
(
  hORGAO INT NOT NULL,
  hNUMEROCARTA VARCHAR(3) NOT NULL,
  hDTCARTA timestamp NOT NULL,
  hANOPROJETO CHAR(2) NOT NULL,
  hSEQUENCIAL VARCHAR(5) NOT NULL,
  pNOMEPROJETO VARCHAR(300) NOT NULL,
  pCGCCPF VARCHAR(14) NOT NULL,
  hLogon INT NOT NULL
);
CREATE TABLE sac.vNuncaCaptou
(
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  TempoSemCaptar INT
);
CREATE TABLE sac.vOutrasInformacoesAudio
(
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  Produtor VARCHAR(100),
  Diretor VARCHAR(100),
  Roteirista VARCHAR(100),
  Metragem VARCHAR(50),
  Genero VARCHAR(50),
  Veiculacao VARCHAR(50),
  SuporteGravacao VARCHAR(50),
  Finalizacao VARCHAR(50),
  DuracaoTipo int,
  DuracaoQtde SMALLINT,
  DuracaoCada SMALLINT,
  DuracaoTotal SMALLINT
);
CREATE TABLE sac.vParecerInicial
(
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  TipoParecer CHAR NOT NULL,
  DtParecer timestamp NOT NULL,
  Parecer TEXT
);
CREATE TABLE sac.vPautaDeReuniaoCNIC
(
  NrProjeto VARCHAR(7) NOT NULL,
  NomeProjeto VARCHAR(300) NOT NULL,
  Processo VARCHAR(17),
  Orgao VARCHAR(20) NOT NULL,
  ResumoProjeto TEXT,
  Solicitado MONEY,
  TipoParecer CHAR NOT NULL,
  NrReuniao INT,
  ParecerTecnico TEXT,
  Sugerido MONEY,
  CgcCpf VARCHAR(14) NOT NULL,
  Proponente VARCHAR(150) NOT NULL,
  Area VARCHAR(50) NOT NULL,
  Enquadramento int,
  Situacao VARCHAR(150) NOT NULL,
  Cidade VARCHAR(50) NOT NULL,
  UF CHAR(2) NOT NULL,
  ParecerFavoravel CHAR
);
CREATE TABLE sac.vPautaDeReuniaoConsolidacaoFNC
(
  TipoApoio VARCHAR(17),
  Prioridade VARCHAR(5),
  Regiao VARCHAR(15) NOT NULL,
  UF CHAR(2) NOT NULL,
  Area VARCHAR(50) NOT NULL,
  Situacao VARCHAR(50) NOT NULL,
  Qtde INT,
  Solicitado MONEY,
  Sugerido MONEY
);
CREATE TABLE sac.vPautaDeReuniaoFNC
(
  NrProjeto VARCHAR(7) NOT NULL,
  NomeProjeto VARCHAR(200) NOT NULL,
  Processo CHAR(15) NOT NULL,
  ResumoProjeto TEXT,
  ParecerTecnico TEXT,
  Proponente VARCHAR(100) NOT NULL,
  Area VARCHAR(50) NOT NULL,
  Situacao VARCHAR(50) NOT NULL,
  Cidade VARCHAR(50) NOT NULL,
  UF CHAR(2) NOT NULL,
  Regiao VARCHAR(15) NOT NULL,
  Prioridade VARCHAR(10),
  Justificativa TEXT NOT NULL,
  TipoApoio int NOT NULL,
  Solicitado MONEY,
  Sugerido MONEY,
  UnidadeAnalise VARCHAR(15),
  Segmento VARCHAR(50) NOT NULL
);
CREATE TABLE sac.vPautaDeReuniaoFNCIntercambio
(
  NrProjeto VARCHAR(7) NOT NULL,
  NomeProjeto VARCHAR(200) NOT NULL,
  Evento VARCHAR(100) NOT NULL,
  EntidadePromotora VARCHAR(100) NOT NULL,
  CidadeEvento VARCHAR(50) NOT NULL,
  Pais VARCHAR(30) NOT NULL,
  Processo VARCHAR(17) NOT NULL,
  ResumoProjeto TEXT,
  ParecerTecnico TEXT NOT NULL,
  Proponente VARCHAR(100) NOT NULL,
  Area VARCHAR(50) NOT NULL,
  Cidade VARCHAR(50) NOT NULL,
  UF CHAR(2) NOT NULL,
  ResumoCurriculo TEXT NOT NULL,
  VlNormal MONEY NOT NULL,
  VlPromocional MONEY NOT NULL,
  VlNormalTotal MONEY,
  VlPromocionalTotal MONEY,
  DtInicio timestamp
);
CREATE TABLE sac.vPortariaAprovado
(
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  DtAprovacao timestamp
);
CREATE TABLE sac.vPortariaComplementacao
(
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  TipoAprovacao CHAR NOT NULL,
  DtAprovacao timestamp
);
CREATE TABLE sac.vPortariaNormal
(
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  TipoAprovacao CHAR NOT NULL,
  DtAprovacao timestamp
);
CREATE TABLE sac.vPortariaProrrogacao
(
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  TipoAprovacao CHAR NOT NULL,
  DtAprovacao timestamp
);
CREATE TABLE sac.vPortariaReducao
(
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  TipoAprovacao CHAR NOT NULL,
  DtAprovacao timestamp
);
CREATE TABLE sac.vPrazoCaptacao
(
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  DtInicioCaptacao timestamp,
  DtFimCaptacao timestamp
);
CREATE TABLE sac.vPrestacao
(
  Area CHAR NOT NULL,
  Segmento VARCHAR(4) NOT NULL,
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  NomeProjeto VARCHAR(300) NOT NULL,
  DtInicioRealizacao timestamp,
  DtFinalRealizacao timestamp,
  ValorExecutado MONEY NOT NULL,
  AplicacaoFinanceira MONEY NOT NULL,
  OutrasFontes MONEY NOT NULL,
  SaldoRecolhido MONEY NOT NULL
);
CREATE TABLE sac.vProjetoAvaliado
(
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  Classificacao VARCHAR(21) NOT NULL,
  Peso INT
);
CREATE TABLE sac.vProjetoDiligencia
(
  AnoProjeto CHAR(2),
  Sequencial VARCHAR(5),
  Diligencia VARCHAR(100) NOT NULL
);
CREATE TABLE sac.vProjetosEmAnalise
(
  Regiao VARCHAR(15) NOT NULL,
  UF VARCHAR(30) NOT NULL,
  NrProjeto VARCHAR(7) NOT NULL,
  NomeProjeto VARCHAR(300) NOT NULL,
  Area VARCHAR(50) NOT NULL,
  Segmento VARCHAR(50) NOT NULL,
  Orgao VARCHAR(20) NOT NULL,
  Mecanismo VARCHAR(50) NOT NULL,
  Situacao VARCHAR(150) NOT NULL,
  DtSaida timestamp,
  UnidadeAnalise VARCHAR(15),
  QtdeDias INT
);
CREATE TABLE sac.vProjetosEmExecucao
(
  Regiao VARCHAR(15) NOT NULL,
  UF VARCHAR(30) NOT NULL,
  NrProjeto VARCHAR(7) NOT NULL,
  NomeProjeto VARCHAR(300) NOT NULL,
  Area VARCHAR(50) NOT NULL,
  Segmento VARCHAR(50) NOT NULL,
  Aprovado MONEY,
  Captado MONEY,
  SaldoACaptar MONEY,
  CodigoArea CHAR NOT NULL,
  Municipio VARCHAR(50) NOT NULL,
  Enquadramento int NOT NULL
);
CREATE TABLE sac.vProjetosExecutados
(
  Ano INT,
  Regiao VARCHAR(15) NOT NULL,
  UF VARCHAR(30) NOT NULL,
  NrProjeto VARCHAR(7) NOT NULL,
  NomeProjeto VARCHAR(300) NOT NULL,
  Area CHAR NOT NULL,
  Segmento VARCHAR(4) NOT NULL,
  Captado MONEY
);
CREATE TABLE sac.vProjPrazoDeCaptVencNoExerc
(
  NrProjeto VARCHAR(7) NOT NULL,
  NomeProjeto VARCHAR(300) NOT NULL,
  Situacao VARCHAR(150) NOT NULL,
  DtFimCaptacao timestamp,
  Aprovado MONEY,
  Captado MONEY,
  Saldo MONEY
);
CREATE TABLE sac.vPropApreAproExec
(
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  NomeProjeto VARCHAR(300) NOT NULL,
  Aprovado MONEY,
  Captado MONEY,
  Executado CHAR(2),
  CgCCpf VARCHAR(14) NOT NULL,
  Area CHAR NOT NULL,
  Segmento VARCHAR(4) NOT NULL,
  Mecanismo CHAR NOT NULL,
  Situacao CHAR(3) NOT NULL
);
CREATE TABLE sac.vProponenteAnoUF
(
  Ano INT,
  CgcCpf VARCHAR(14) NOT NULL,
  Nome VARCHAR(150) NOT NULL,
  UF CHAR(2) NOT NULL
);
CREATE TABLE sac.vProponenteAvaliado
(
  CgcCpf VARCHAR(14) NOT NULL,
  Proponente VARCHAR(150) NOT NULL,
  Classificacao VARCHAR(21) NOT NULL,
  Peso INT
);
CREATE TABLE sac.vProponenteCaptacaoAnoUF
(
  Ano INT,
  CgcCpf VARCHAR(14) NOT NULL,
  Nome VARCHAR(150) NOT NULL,
  UF CHAR(2) NOT NULL,
  Captacao MONEY
);
CREATE TABLE sac.vProponenteComMaisDeCincoProj
(
  CgcCpf VARCHAR(14) NOT NULL,
  Proponente VARCHAR(150) NOT NULL,
  Qtde INT
);
CREATE TABLE sac.vProponenteMecenato
(
  CgcCpf VARCHAR(14) NOT NULL,
  Nome VARCHAR(150) NOT NULL,
  Responsavel VARCHAR(100) NOT NULL,
  Endereco VARCHAR(100) NOT NULL,
  Cidade VARCHAR(50) NOT NULL,
  Uf CHAR(2) NOT NULL,
  Cep VARCHAR(8) NOT NULL
);
CREATE TABLE sac.vProponentePorArea
(
  Area CHAR NOT NULL,
  CgcCpf VARCHAR(14) NOT NULL
);
CREATE TABLE sac.vProponenteProjetos
(
  CgcCpf VARCHAR(14) NOT NULL,
  Nome VARCHAR(150) NOT NULL,
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  CaptacaoUfir MONEY NOT NULL,
  CaptacaoReal MONEY NOT NULL
);
CREATE TABLE sac.vProponenteProjetosOngs
(
  CgcCpf VARCHAR(14) NOT NULL,
  Nome VARCHAR(150) NOT NULL,
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  CaptacaoReal MONEY NOT NULL
);
CREATE TABLE sac.vProponenteTipoPessoa
(
  CgcCpf VARCHAR(20),
  TipoPessoa VARCHAR(1),
  Proponente VARCHAR(200),
  Mecanismo VARCHAR(200),
  Solicitado MONEY,
  Aprovado MONEY,
  Captado MONEY,
  Saldo MONEY
);
CREATE TABLE sac.vPropProjInabilitados
(
  CgcCpf VARCHAR(14) NOT NULL,
  Nome VARCHAR(150) NOT NULL,
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  NomeProjeto VARCHAR(300) NOT NULL,
  Orgao VARCHAR(20) NOT NULL,
  Situacao VARCHAR(150) NOT NULL
);
CREATE TABLE sac.vPropQueReceberBandaDeNovo
(
  CgcCpf VARCHAR(14) NOT NULL,
  Proponente VARCHAR(150) NOT NULL,
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  NomeProjeto VARCHAR(300) NOT NULL,
  Situacao CHAR(3) NOT NULL,
  DescSituacao VARCHAR(150) NOT NULL
);
CREATE TABLE sac.vPropRecebeuBanda
(
  CgcCpf VARCHAR(14) NOT NULL,
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL
);
CREATE TABLE sac.vPropUFAreaSeg
(
  UF CHAR(2) NOT NULL,
  Area CHAR NOT NULL,
  Segmento VARCHAR(4) NOT NULL,
  CgcCpf VARCHAR(14) NOT NULL
);
CREATE TABLE sac.vProrrogacao
(
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  DtAprovacao timestamp,
  DtInicioCaptacao timestamp,
  DtFimCaptacao timestamp,
  PortariaAprovacao VARCHAR(10),
  DtPortariaAprovacao timestamp,
  DtPublicacaoAprovacao timestamp,
  SaldoACaptar MONEY
);
CREATE TABLE sac.vQtdeProjAproAnoUF
(
  Ano INT,
  UF CHAR(2) NOT NULL
);
CREATE TABLE sac.vRankBanda
(
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  NomeProjeto VARCHAR(300) NOT NULL,
  UF CHAR(2) NOT NULL,
  Municipio VARCHAR(50) NOT NULL,
  DtProtocolo timestamp NOT NULL,
  TempoDemanda INT,
  PontoDemanda INT NOT NULL,
  DtFundacao timestamp,
  Antiguidade INT,
  PontoAntiguidade INT NOT NULL,
  Finalidade int NOT NULL,
  PontoFinalidade INT NOT NULL,
  Indicacao INTEGER NOT NULL,
  PontoIndicacao INT NOT NULL,
  Emenda INTEGER NOT NULL,
  PontoEmenda INT NOT NULL
);
CREATE TABLE sac.vRenunciaAnoAreaGeral
(
  Ano INT,
  Lei INT NOT NULL,
  Area1 NUMERIC(38,5),
  Area2 NUMERIC(38,5),
  Area3 NUMERIC(38,5),
  Area4 NUMERIC(38,5),
  Area5 NUMERIC(38,5),
  Area6 NUMERIC(38,5),
  Area7 NUMERIC(38,5)
);
CREATE TABLE sac.vRenunciaAnoAreaUFGeral
(
  Ano INT,
  UF CHAR(2) NOT NULL,
  Lei INT NOT NULL,
  Area1 NUMERIC(38,5),
  Area2 NUMERIC(38,5),
  Area3 NUMERIC(38,5),
  Area4 NUMERIC(38,5),
  Area5 NUMERIC(38,5),
  Area6 NUMERIC(38,5),
  Area7 NUMERIC(38,5)
);
CREATE TABLE sac.vRenunciaFiscal
(
  Ano INT,
  Trim1 NUMERIC(38,5),
  Trim2 NUMERIC(38,5),
  Trim3 NUMERIC(38,5),
  Trim4 NUMERIC(38,5)
);
CREATE TABLE sac.vRenunciaFiscalAnoArea
(
  Ano INT,
  Area1 NUMERIC(38,5),
  Area2 NUMERIC(38,5),
  Area3 NUMERIC(38,5),
  Area4 NUMERIC(38,5),
  Area5 NUMERIC(38,5),
  Area6 NUMERIC(38,5),
  Area7 NUMERIC(38,5)
);
CREATE TABLE sac.vRenunciaFiscalGeral
(
  Ano INT,
  Lei INT NOT NULL,
  Trim1 NUMERIC(38,5),
  Trim2 NUMERIC(38,5),
  Trim3 NUMERIC(38,5),
  Trim4 NUMERIC(38,5)
);
CREATE TABLE sac.vRenunciaGeralPorAnoProjeto
(
  Ano INT,
  Lei INT NOT NULL,
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  Captado MONEY,
  Renuncia NUMERIC(38,5)
);
CREATE TABLE sac.vRenunciaPorAno
(
  Ano INT,
  CgcCpf VARCHAR(14) NOT NULL,
  Mp CHAR NOT NULL,
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  TipoApoio CHAR NOT NULL,
  ApoioUfir MONEY,
  ApoioReal MONEY
);
CREATE TABLE sac.vRenunciaPorIncentivador
(
  CgcCpf VARCHAR(14) NOT NULL,
  Renuncia MONEY
);
CREATE TABLE sac.vRicardoReis
(
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  NomeProjeto VARCHAR(300) NOT NULL,
  Processo VARCHAR(17)
);
CREATE TABLE sac.vSaldoAprovado
(
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  DtAprovacao timestamp,
  DtInicioCaptacao timestamp,
  DtFimCaptacao timestamp,
  AproMec MONEY,
  AproArt1 MONEY,
  AproArt3 MONEY,
  AproConv MONEY,
  AproContra MONEY,
  CompMec MONEY,
  CompArt1 MONEY,
  CompArt3 MONEY,
  CompConv MONEY,
  ReduMec MONEY,
  ReduArt1 MONEY,
  ReduArt3 MONEY,
  ReduConv MONEY
);
CREATE TABLE sac.vSaldoAprovadoPorAno
(
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  Ano INT,
  DtAprovacao timestamp,
  AproMec MONEY,
  AproArt1 MONEY,
  AproArt3 MONEY,
  AproCusteio MONEY,
  CompMec MONEY,
  CompArt1 MONEY,
  CompArt3 MONEY,
  ReduMec MONEY,
  ReduArt1 MONEY,
  ReduArt3 MONEY
);
CREATE TABLE sac.vSaldoQuotasCav
(
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  Integralizado MONEY,
  Cancelado MONEY
);
CREATE TABLE sac.vSegmento
(
  Area VARCHAR(1),
  Codigo VARCHAR(4) NOT NULL,
  Segmento VARCHAR(50) NOT NULL,
  idOrgao INT
);
CREATE TABLE sac.vTempoSemCaptar
(
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  TempoSemCaptar INT
);
CREATE TABLE sac.vTermoAditivo
(
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  DtFinalVigencia timestamp
);
CREATE TABLE sac.vTermoDeCompromisso
(
  NrProjeto VARCHAR(7) NOT NULL,
  NomeProjeto VARCHAR(300) NOT NULL,
  ResumoProjeto TEXT,
  Nome VARCHAR(150) NOT NULL,
  Uf CHAR(2) NOT NULL,
  Cidade VARCHAR(50) NOT NULL,
  CgcCpf VARCHAR(14) NOT NULL,
  Solicitado MONEY,
  Aprovado MONEY,
  DtInicioCaptacao timestamp,
  DtFimCaptacao timestamp
);
CREATE TABLE sac.vTodaAprovacao
(
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  DtProtocolo timestamp NOT NULL,
  DtAprovacao timestamp NOT NULL,
  Tipo CHAR NOT NULL,
  Portaria VARCHAR(10),
  DtPublicacao timestamp,
  DtInicioCaptacao timestamp,
  DtFimCaptacao timestamp,
  Mecenato MONEY,
  Artigo1 MONEY,
  Custeio MONEY,
  Artigo3 MONEY,
  Contrapartida MONEY
);
CREATE TABLE sac.vTotalAprovado
(
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  DtAprovacao timestamp,
  DtInicioCaptacao timestamp,
  DtFimCaptacao timestamp,
  AprovadoUfir MONEY,
  AprovadoReal MONEY,
  ConcedidoCusteioReal MONEY,
  ConcedidoCapitalReal MONEY,
  ContrapartidaReal MONEY
);
CREATE TABLE sac.vTotalCaptado
(
  AnoRecibo INT,
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  CaptacaoUfir MONEY,
  CaptacaoReal MONEY
);
CREATE TABLE sac.vUltimaFase
(
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  Fase int,
  DtInicio timestamp,
  DtTermino timestamp
);
CREATE TABLE sac.vUsuarios
(
  usu_codigo SMALLINT NOT NULL,
  usu_identificacao CHAR(16) NOT NULL,
  usu_nome VARCHAR(20) NOT NULL,
  usu_pessoa INT NOT NULL,
  usu_orgao SMALLINT,
  usu_sala VARCHAR(20),
  usu_ramal SMALLINT,
  usu_nivel int NOT NULL,
  usu_nome_completo VARCHAR(80) NOT NULL,
  org_codigo SMALLINT,
  org_sigla CHAR(12),
  org_estrutura VARCHAR(100),
  org_nome VARCHAR(80) NOT NULL,
  org_gerente INT NOT NULL,
  org_nomegerente VARCHAR(80) NOT NULL,
  fun_descricao VARCHAR(100) NOT NULL,
  fun_funcaoorgao VARCHAR(100) NOT NULL
);
CREATE TABLE sac.vValoresProjeto
(
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  Solicitado MONEY,
  Aprovado MONEY,
  Captado MONEY,
  Saldo MONEY
);
CREATE TABLE sac.vVerSePodeProrrogar
(
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  TempoSemCaptar INT
);
CREATE TABLE sac.VW_IdentificadorLog
(
  IdentificadorLog INT NOT NULL,
  NomeBancoDados VARCHAR(256) NOT NULL,
  TipoEvento VARCHAR(50) NOT NULL,
  NomeObjeto VARCHAR(256) NOT NULL,
  TipoObjeto VARCHAR(25) NOT NULL,
  ComandoSQL VARCHAR NOT NULL,
  DataEvento timestamp NOT NULL,
  UsuarioLogin VARCHAR(256) NOT NULL,
  Hostname VARCHAR(256)
);
CREATE TABLE sac.vwAgentesSeusProjetos
(
  Ordem INT NOT NULL,
  IdPRONAC INT NOT NULL,
  NomeProjeto VARCHAR(300) NOT NULL,
  CgcCpf VARCHAR(14) NOT NULL,
  Situacao CHAR(3) NOT NULL,
  DtInicioDeExecucao timestamp,
  DtFinalDeExecucao timestamp,
  Mecanismo CHAR NOT NULL,
  Pronac VARCHAR(7) NOT NULL,
  idAgente INT NOT NULL,
  NomeProponente VARCHAR(100),
  Descricao VARCHAR(150) NOT NULL,
  idSolicitante INT NOT NULL,
  IdUsuario INT NOT NULL
);
CREATE TABLE sac.vwAlterarAnalistaProposta
(
  idProposta INT NOT NULL,
  NomeProjeto VARCHAR(300) NOT NULL,
  Orgao INT NOT NULL,
  idTecnico INT NOT NULL,
  Tecnico VARCHAR(100)
);
CREATE TABLE sac.vwAlterarOrgao
(
  PRONAC VARCHAR(7) NOT NULL,
  NomeProjeto VARCHAR(300) NOT NULL,
  Orgao INT NOT NULL,
  Logon INT
);
CREATE TABLE sac.vwAlterarProjeto
(
  idPronac INT NOT NULL,
  PRONAC VARCHAR(7) NOT NULL,
  NomeProjeto VARCHAR(300) NOT NULL,
  CgcCpf VARCHAR(14) NOT NULL,
  Proponente VARCHAR(150) NOT NULL,
  Area CHAR NOT NULL,
  Segmento VARCHAR(4) NOT NULL,
  DtSituacao timestamp,
  Situacao CHAR(3) NOT NULL,
  ProvidenciaTomada VARCHAR(500),
  DtInicioExecucao timestamp,
  DtFimExecucao timestamp,
  idUsuario INT
);
CREATE TABLE sac.vwAlterarSituacao
(
  PRONAC VARCHAR(7) NOT NULL,
  NomeProjeto VARCHAR(300) NOT NULL,
  DtSituacao timestamp,
  Situacao CHAR(3) NOT NULL,
  ProvidenciaTomada VARCHAR(500),
  Logon INT
);
CREATE TABLE sac.vwAnaliseDeConteudo
(
  idAnaliseDeConteudo INT NOT NULL,
  idPronac INT NOT NULL,
  NomeProjeto VARCHAR(300) NOT NULL,
  idProduto int NOT NULL,
  Produto VARCHAR(100) NOT NULL
);
CREATE TABLE sac.vwAnaliseDeCusto
(
  idPronac INT NOT NULL,
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  NomeProjeto VARCHAR(300) NOT NULL,
  idProduto int NOT NULL,
  idPlanilhaProjeto INT NOT NULL,
  Produto VARCHAR(100),
  Etapa VARCHAR(61),
  Item VARCHAR(250) NOT NULL,
  Unidade VARCHAR(50) NOT NULL,
  Quantidade REAL NOT NULL,
  Ocorrencia DECIMAL(4) NOT NULL,
  ValorUnitario MONEY NOT NULL,
  VlTotal REAL,
  QtdeDias INT NOT NULL,
  TipoDespesa VARCHAR(13),
  TipoPessoa VARCHAR(13),
  Contrapartida VARCHAR(13),
  FonteRecurso VARCHAR(100) NOT NULL,
  UF CHAR(2) NOT NULL,
  Municipio VARCHAR(100) NOT NULL,
  Data timestamp,
  VlCorte MONEY NOT NULL,
  JustificativaSugerida VARCHAR(250),
  VlSugerido REAL,
  idUsuario INT
);
CREATE TABLE sac.vwAnaliseDocumentalPorTecnico
(
  idProjeto INT NOT NULL,
  NomeProjeto VARCHAR(300) NOT NULL,
  Tecnico VARCHAR(100),
  DtEnvio timestamp,
  DtMovimentacao timestamp NOT NULL,
  DtAvaliacao timestamp,
  Dias INT,
  idOrgao INT,
  ConformidadeOK int NOT NULL
);
CREATE TABLE sac.vwAnaliseVisualPorTecnico
(
  idProjeto INT NOT NULL,
  NomeProjeto VARCHAR(300) NOT NULL,
  Tecnico VARCHAR(100),
  DtEnvio timestamp,
  DtMovimentacao timestamp,
  idOrgao INT,
  ConformidadeOK int NOT NULL,
  QtdeDias INT
);
CREATE TABLE sac.vwAnexarComprovantes
(
  idArquivo INT NOT NULL,
  nmArquivo VARCHAR(255) NOT NULL,
  sgExtensao VARCHAR(5) NOT NULL,
  dtEnvio timestamp NOT NULL,
  stAtivo CHAR NOT NULL,
  biArquivo bit NOT NULL,
  idDocumento INT NOT NULL,
  idTipoDocumento INT NOT NULL,
  dsDocumento VARCHAR(400),
  idPronac INT NOT NULL,
  stAtivoDocumentoProjeto CHAR
);
CREATE TABLE sac.vwAnexarDocumentoAgente
(
  idArquivo INT NOT NULL,
  nmArquivo VARCHAR(255) NOT NULL,
  sgExtensao VARCHAR(5) NOT NULL,
  nrTamanho VARCHAR(10),
  dtEnvio timestamp NOT NULL,
  stAtivo CHAR NOT NULL,
  biArquivo bit NOT NULL,
  idDocumento INT NOT NULL,
  idTipoDocumento INT NOT NULL,
  dsDocumento VARCHAR(400),
  idAgente INT NOT NULL,
  stAtivoDocumentoAgente INTEGER NOT NULL
);
CREATE TABLE sac.vwAnexarDocumentoDiligencia
(
  idArquivo INT NOT NULL,
  nmArquivo VARCHAR(255) NOT NULL,
  sgExtensao VARCHAR(5) NOT NULL,
  nrTamanho VARCHAR(10),
  dtEnvio timestamp NOT NULL,
  stAtivo CHAR NOT NULL,
  biArquivo bit NOT NULL,
  idDocumento INT NOT NULL,
  idTipoDocumento INT NOT NULL,
  dsDocumento VARCHAR(400),
  idPronac INT NOT NULL,
  stAtivoDocumentoProjeto CHAR,
  idDiligencia INT NOT NULL
);
CREATE TABLE sac.vwAnexarDocumentoEdital
(
  idPreProjeto INT NOT NULL,
  idEdital INT,
  NomeProjeto VARCHAR(300) NOT NULL,
  nmArquivo VARCHAR(255) NOT NULL,
  dsTipoPadronizado CHAR(100),
  sgExtensao VARCHAR(5) NOT NULL,
  idArquivo INT NOT NULL,
  dtEnvio timestamp NOT NULL,
  biArquivo bit NOT NULL,
  nrTamanho VARCHAR(10),
  stAtivo CHAR NOT NULL
);
CREATE TABLE sac.vwAnexarMarca
(
  nmArquivo VARCHAR(255) NOT NULL,
  sgExtensao VARCHAR(5) NOT NULL,
  dtEnvio timestamp NOT NULL,
  stAtivo CHAR NOT NULL,
  biArquivo bit NOT NULL,
  idTipoDocumento INT NOT NULL,
  dsDocumento VARCHAR(400),
  idPronac INT NOT NULL,
  stAtivoDocumentoProjeto CHAR
);
CREATE TABLE sac.vwApagarDocumento
(
  idPronac INT NOT NULL,
  idDocumento INT NOT NULL,
  NrProjeto VARCHAR(7) NOT NULL,
  NomeProjeto VARCHAR(300) NOT NULL,
  dtDocumento timestamp NOT NULL,
  noArquivo VARCHAR(130)
);
CREATE TABLE sac.vwApoioEstadoMacro
(
  codigo_estado INT NOT NULL,
  sigla_estado CHAR(2) NOT NULL,
  codigo_macro CHAR(4) NOT NULL,
  nome_macro VARCHAR(100) NOT NULL,
  total_cidade_macro INT
);
CREATE TABLE sac.vwApoioProjetosLiberados
(
  codigo_projeto INT,
  ano_liberacao_projeto INT,
  sigla_estado_projeto CHAR(2) NOT NULL,
  sigla_estado CHAR(2) NOT NULL,
  codigo_cidade VARCHAR(6),
  codigo_macro CHAR(4) NOT NULL
);
CREATE TABLE sac.vwArea
(
  Codigo CHAR NOT NULL,
  Descricao VARCHAR(50) NOT NULL
);
CREATE TABLE sac.vwAssuntoEmail
(
  idVerificacao INT NOT NULL,
  idTipo INT NOT NULL,
  Assunto VARCHAR(100) NOT NULL,
  stEstado INTEGER NOT NULL
);
CREATE TABLE sac.vwAtualizarContaBancaria
(
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  idContaBancaria INT NOT NULL,
  PRONAC VARCHAR(7) NOT NULL,
  NomeProjeto VARCHAR(300) NOT NULL,
  Banco CHAR(3) NOT NULL,
  Agencia VARCHAR(5) NOT NULL,
  ContaBloqueada CHAR(12) NOT NULL,
  ContaLivre CHAR(12),
  idUsuario INT NOT NULL
);
CREATE TABLE sac.vwAvaliarProposta
(
  idProjeto INT NOT NULL,
  NomeProposta VARCHAR(300) NOT NULL,
  CNPJCPF VARCHAR(14) NOT NULL,
  idAgente INT NOT NULL,
  idUsuario INT NOT NULL,
  Tecnico VARCHAR(100),
  idSecretaria INT,
  DtAdmissibilidade timestamp,
  idAvaliacaoProposta INT NOT NULL,
  idMovimentacao INT NOT NULL,
  CodSituacao INT NOT NULL,
  Situacao VARCHAR(100) NOT NULL,
  TipoDemanda CHAR(2) NOT NULL,
  idOrgao INT
);
CREATE TABLE sac.vwAvaliarPropostaEdital
(
  idProjeto INT NOT NULL,
  NomeProposta VARCHAR(300) NOT NULL,
  CNPJCPF VARCHAR(14) NOT NULL,
  idAgente INT NOT NULL,
  idUsuario INT NOT NULL,
  Tecnico VARCHAR(100),
  idSecretaria INT,
  DtAdmissibilidade timestamp,
  idAvaliacaoProposta INT NOT NULL,
  idMovimentacao INT NOT NULL,
  CodSituacao INT NOT NULL,
  Situacao VARCHAR(24),
  TipoDemanda CHAR(2) NOT NULL,
  idOrgao INT NOT NULL,
  idEdital INT,
  Edital VARCHAR(200) NOT NULL
);
CREATE TABLE sac.vwCadastrarParecerista
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
  DivulgarEndereco INTEGER,
  Correspondencia INTEGER,
  Usuario INT NOT NULL
);
CREATE TABLE sac.vwCancelarContaBancaria
(
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  idContaBancaria INT NOT NULL,
  PRONAC VARCHAR(7) NOT NULL,
  NomeProjeto VARCHAR(300) NOT NULL,
  Banco CHAR(3) NOT NULL,
  Agencia VARCHAR(5) NOT NULL,
  ContaBloqueada CHAR(12) NOT NULL,
  DtLoteRemessaCB timestamp,
  LoteRemessaCB CHAR(5),
  OcorrenciaCB CHAR(3),
  ContaLivre CHAR(12),
  DtLoteRemessaCL timestamp,
  OcorrenciaCL CHAR(3),
  LoteRemessaCL CHAR(5),
  Motivo VARCHAR(8000),
  idUsuario INT NOT NULL
);
CREATE TABLE sac.vwCaptacaoProjetos
(
  anoprojeto CHAR(2) NOT NULL,
  sequencial VARCHAR(5) NOT NULL,
  captacao MONEY
);
CREATE TABLE sac.vwCompatilizar_Despesa_DeINTEGERoNaConta
(
  idPronac INT NOT NULL,
  Pronac VARCHAR(7) NOT NULL,
  NomeProjeto VARCHAR(300) NOT NULL,
  idPlanilhaAprovacao INT NOT NULL,
  idComprovantePagamento INT NOT NULL,
  nrDocumentoDePagamento VARCHAR(20),
  dtPagamento timestamp NOT NULL,
  tpFormaDePagamento int,
  vlComprovado DECIMAL(12,2),
  dsLancamento VARCHAR(25),
  dtLancamento timestamp,
  vlDeINTEGERado DECIMAL(16,2),
  stLancamento CHAR,
  vlDiferenca DECIMAL(17,2)
);
CREATE TABLE sac.vwConciliacaoBancaria
(
  idPronac INT,
  Pronac VARCHAR(20),
  NomeProjeto VARCHAR(300),
  ItemOrcamentario VARCHAR(300),
  CNPJCPF VARCHAR(20),
  Fornecedor VARCHAR(300),
  idComprovantePagamento INT,
  nrDocumentoDePagamento VARCHAR(20),
  dtPagamento timestamp,
  vlPagamento MONEY,
  vlComprovado MONEY,
  dsLancamento VARCHAR(300),
  dtLancamento timestamp,
  vlDeINTEGERado MONEY,
  vlDiferenca MONEY
);
CREATE TABLE sac.vwConformidadeDocumentalTecnico
(
  idProjeto INT NOT NULL,
  NomeProjeto VARCHAR(300) NOT NULL,
  idTecnico INT NOT NULL,
  DtMovimentacao timestamp NOT NULL,
  Descricao VARCHAR(100) NOT NULL
);
CREATE TABLE sac.vwConformidadeVisualTecnico
(
  idProjeto INT NOT NULL,
  NomeProjeto VARCHAR(300) NOT NULL,
  Tecnico INT,
  DtMovimentacao timestamp NOT NULL,
  Descricao VARCHAR(100) NOT NULL
);
CREATE TABLE sac.vwConsultaProjetoSimplificada
(
  IdPRONAC INT NOT NULL,
  Pronac VARCHAR(7) NOT NULL,
  NomeProjeto VARCHAR(300) NOT NULL,
  CNPJCPF VARCHAR(14) NOT NULL,
  Proponente VARCHAR(100),
  CodigoSituacao CHAR(3) NOT NULL,
  Situacao VARCHAR(150) NOT NULL,
  UFProjeto CHAR(2) NOT NULL,
  Area VARCHAR(50) NOT NULL,
  Segmento VARCHAR(50) NOT NULL,
  ResumoProjeto TEXT,
  Enquadramento VARCHAR(14) NOT NULL,
  stConta VARCHAR(4) NOT NULL,
  dtFimCaptacao timestamp,
  DtFimExecucao timestamp,
  Agencia VARCHAR(5),
  Conta CHAR(12),
  ValorAprovado MONEY,
  ValorProjeto MONEY,
  ValorCaptado MONEY,
  VlComprovado DECIMAL(12,2),
  PercCaptado MONEY NOT NULL
);
CREATE TABLE sac.vwCortesSugeridos
(
  idPronac INT NOT NULL,
  idProduto int NOT NULL,
  idPlanilhaProjeto INT NOT NULL,
  Item VARCHAR(250) NOT NULL,
  VlSolicitado FLOAT,
  VlReduzido MONEY,
  VlSugerido FLOAT,
  JustificativaSugerida VARCHAR(250) NOT NULL,
  Situacao VARCHAR(13) NOT NULL,
  idUsuario INT NOT NULL
);
CREATE TABLE sac.vwCortesSugeridosAtual
(
  idPronac INT NOT NULL,
  idProduto int NOT NULL,
  idPlanilhaProjeto INT NOT NULL,
  Item VARCHAR(250) NOT NULL,
  VlSolicitado REAL,
  VlReduzido REAL,
  VlSugerido REAL,
  Justificativa VARCHAR,
  Situacao VARCHAR(13) NOT NULL
);
CREATE TABLE sac.vwCulturaViva
(
  Regiao VARCHAR(15) NOT NULL,
  UF VARCHAR(30) NOT NULL,
  Proponente VARCHAR(100) NOT NULL,
  Cidade VARCHAR(50) NOT NULL,
  NrProjeto VARCHAR(7) NOT NULL,
  NomeProjeto VARCHAR(200) NOT NULL,
  Processo VARCHAR(17) NOT NULL,
  CgcCpf VARCHAR(14) NOT NULL,
  idSituacao CHAR(3) NOT NULL,
  Situacao VARCHAR(150) NOT NULL,
  idModalidade VARCHAR(3),
  Modalidade VARCHAR(50) NOT NULL,
  Custo MONEY,
  Pago MONEY,
  APagar MONEY,
  DtInicioVigencia timestamp,
  DtFinalVigencia timestamp
);
CREATE TABLE sac.vwDadosDeProjeto
(
  NrProjeto VARCHAR(7) NOT NULL,
  NomeProjeto VARCHAR(200) NOT NULL,
  UfProjeto CHAR(2) NOT NULL,
  Area VARCHAR(50) NOT NULL,
  Segmento VARCHAR(50) NOT NULL,
  Mecanismo VARCHAR(50) NOT NULL,
  Situacao VARCHAR(150) NOT NULL,
  DtSituacao VARCHAR(10),
  ProvidenciaTomada VARCHAR(255),
  DtProtocolo timestamp NOT NULL,
  DtAnalise timestamp NOT NULL,
  Analista VARCHAR(100),
  DtSaida timestamp,
  UnidadeAnalise VARCHAR(15),
  DtRetorno timestamp,
  Banco CHAR(3),
  Agencia VARCHAR(5),
  ContaBloqueada CHAR(12),
  DtLoteRemessaCB timestamp,
  OcorrenciaCB CHAR(3),
  ContaLivre CHAR(12),
  DtLoteRemessaCL timestamp,
  ValorSolicitado MONEY,
  ValorAprovado MONEY,
  ValorCaptado MONEY,
  CgcCPf VARCHAR(14) NOT NULL,
  Proponente VARCHAR(100) NOT NULL,
  Processo VARCHAR(21),
  DtLiberacao timestamp,
  NumeroDocumento VARCHAR(5),
  Unidade VARCHAR(100),
  ResumoProjeto TEXT
);
CREATE TABLE sac.vwDesconsolidarParecer
(
  idPronac INT NOT NULL,
  PRONAC VARCHAR(7) NOT NULL,
  NomeProjeto VARCHAR(300) NOT NULL,
  CodSituacao CHAR(3) NOT NULL,
  Situacao VARCHAR(150) NOT NULL,
  idSecretaria INT,
  idParecer INT NOT NULL,
  DtConsolidacao timestamp NOT NULL,
  ValorProposta MONEY,
  OutrasFontes MONEY,
  ValorSolicitado MONEY,
  ValorSugerido MONEY,
  Elaboracao MONEY,
  ValorParecer MONEY,
  PERC MONEY,
  Acima VARCHAR(12)
);
CREATE TABLE sac.vwDespacharLoteDocumento
(
  idUnidadeCadastro SMALLINT,
  idUnidade INT NOT NULL,
  idUsuarioEmissor SMALLINT NOT NULL,
  DespachoLote VARCHAR(250),
  Qtde INT
);
CREATE TABLE sac.vwDespacharLoteProjeto
(
  Orgao INT NOT NULL,
  idUnidade INT NOT NULL,
  idUsuarioEmissor SMALLINT NOT NULL,
  DespachoLote VARCHAR(250),
  Qtde INT
);
CREATE TABLE sac.vwDespacharProjeto
(
  idPronac INT NOT NULL,
  NrProjeto VARCHAR(7) NOT NULL,
  NomeProjeto VARCHAR(300) NOT NULL,
  Orgao INT NOT NULL,
  idHistorico INT NOT NULL,
  idUnidade INT NOT NULL,
  dtTramitacaoEnvio timestamp,
  stEstado INTEGER NOT NULL,
  idUsuarioEmissor SMALLINT NOT NULL,
  meDespacho VARCHAR(250)
);
CREATE TABLE sac.vwDevolverParecer
(
  idDistribuirParecer INT NOT NULL,
  idOrgao INT,
  idPronac INT NOT NULL,
  NrProjeto VARCHAR(7) NOT NULL,
  NomeProjeto VARCHAR(300) NOT NULL,
  idProduto INT NOT NULL,
  Produto VARCHAR(100) NOT NULL,
  idSecretaria INT,
  DescricaoAnalise VARCHAR(31),
  TipoAnalise int NOT NULL,
  Estado VARCHAR(18) NOT NULL,
  Orgao VARCHAR(100),
  DtRetorno timestamp
);
CREATE TABLE sac.vwDiligencia
(
  IdPRONAC INT NOT NULL,
  PRONAC VARCHAR(7) NOT NULL,
  NomeProjeto VARCHAR(300) NOT NULL,
  idDiligencia INT NOT NULL,
  idTipoDiligencia INT NOT NULL,
  TipoDiligencia VARCHAR(100) NOT NULL,
  DtSolicitacao timestamp NOT NULL,
  Solicitacao VARCHAR NOT NULL,
  idSolicitante INT NOT NULL,
  Solicitante VARCHAR(100),
  DtResposta timestamp,
  Resposta VARCHAR,
  idProponente INT,
  Proponente VARCHAR(100),
  stEstado INTEGER NOT NULL,
  idProduto INT NOT NULL,
  stDiligenciado INTEGER,
  idDistribuirParecer INT NOT NULL,
  Descricao VARCHAR(100) NOT NULL,
  Expr1 int
);
CREATE TABLE sac.vwDistribuirParecer
(
  idDistribuirParecer INT NOT NULL,
  idPRONAC INT NOT NULL,
  idProduto INT NOT NULL,
  TipoAnalise int NOT NULL,
  idOrgao INT,
  DtEnvio timestamp,
  idAgenteParecerista INT,
  DtDistribuicao timestamp,
  DtDevolucao timestamp,
  Observacao VARCHAR(8000),
  stEstado INTEGER,
  stPrincipal INTEGER,
  FecharAnalise CHAR,
  DtRetorno timestamp,
  idUsuario INT,
  stDiligenciado INTEGER
);
CREATE TABLE sac.vwDocumentos
(
  idPronac INT NOT NULL,
  Pronac VARCHAR(7) NOT NULL,
  NomeProjeto VARCHAR(200) NOT NULL,
  idDocumento INT NOT NULL,
  dsTipoDocumento VARCHAR(100) NOT NULL,
  dtDocumento timestamp NOT NULL,
  noArquivo VARCHAR(100),
  imDocumento VARCHAR,
  idOrigem SMALLINT,
  Origem VARCHAR(100),
  Processo VARCHAR(21),
  idHistorico INT NOT NULL,
  dtTramitacaoEnvio timestamp,
  dtTramitacaoRecebida timestamp,
  idDestino INT NOT NULL,
  Destino VARCHAR(100),
  idLote INT,
  idUsuarioEmissor SMALLINT NOT NULL,
  Emissor VARCHAR(100),
  idUsuarioReceptor SMALLINT,
  Receptor VARCHAR(100),
  Acao int,
  Situacao VARCHAR(10)
);
CREATE TABLE sac.vwDocumentosAEnviar
(
  NrProjeto VARCHAR(7) NOT NULL,
  NomeProjeto VARCHAR(200) NOT NULL,
  dsTipoDocumento VARCHAR(100) NOT NULL,
  idDocumento INT NOT NULL,
  idUsuario SMALLINT NOT NULL,
  dtDocumento timestamp NOT NULL,
  idUnidadeCadastro SMALLINT,
  Processo VARCHAR(21),
  Origem VARCHAR(100),
  idUsuarioEmissor SMALLINT NOT NULL,
  Emissor VARCHAR(100),
  idDestino INT NOT NULL,
  Destino VARCHAR(100)
);
CREATE TABLE sac.vwDocumentosAReceber
(
  NrProjeto VARCHAR(7) NOT NULL,
  NomeProjeto VARCHAR(200) NOT NULL,
  dsTipoDocumento VARCHAR(100) NOT NULL,
  dtDocumento timestamp NOT NULL,
  noArquivo VARCHAR(100),
  imDocumento VARCHAR,
  idOrigem INT NOT NULL,
  Origem VARCHAR(100),
  Processo VARCHAR(21),
  dtTramitacaoEnvio timestamp,
  idUnidade INT NOT NULL,
  idDestino VARCHAR(100),
  idLote INT,
  idUsuarioEmissor SMALLINT NOT NULL,
  Emissor VARCHAR(100)
);
CREATE TABLE sac.vwDocumentosEnviados
(
  NrProjeto VARCHAR(7) NOT NULL,
  NomeProjeto VARCHAR(200) NOT NULL,
  Processo VARCHAR(21),
  dsTipoDocumento VARCHAR(100) NOT NULL,
  dtDocumento timestamp NOT NULL,
  dtTramitacaoEnvio timestamp,
  idOrigem SMALLINT,
  Origem VARCHAR(100),
  idLote INT,
  idDestino INT NOT NULL,
  Destino VARCHAR(100),
  idUsuarioEmissor SMALLINT NOT NULL,
  Emissor VARCHAR(100)
);
CREATE TABLE sac.vwDocumentosExigidos
(
  Codigo INT NOT NULL,
  Descricao VARCHAR(100) NOT NULL,
  Opcao INT NOT NULL
);
CREATE TABLE sac.vwDocumentosExigidosApresentacaoProposta
(
  Codigo INT NOT NULL,
  Descricao VARCHAR(100) NOT NULL,
  Opcao INT NOT NULL
);
CREATE TABLE sac.vwDocumentosPendentes
(
  Contador INT NOT NULL,
  idProjeto INT,
  CodigoDocumento INT NOT NULL,
  Opcao INT NOT NULL
);
CREATE TABLE sac.vwDocumentosRecebidos
(
  NrProjeto VARCHAR(7) NOT NULL,
  NomeProjeto VARCHAR(200) NOT NULL,
  dsTipoDocumento VARCHAR(100) NOT NULL,
  dtDocumento timestamp NOT NULL,
  UnidadeCadastro VARCHAR(100),
  dtTramitacaoEnvio timestamp,
  dtTramitacaoRecebido timestamp,
  idUnidade INT NOT NULL,
  Unidade VARCHAR(100)
);
CREATE TABLE sac.vwDtNascimentoContas
(
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  DtNascimento timestamp,
  CgcCpf VARCHAR(14) NOT NULL
);
CREATE TABLE sac.vwEditalMinC
(
  cdTipoFundo INT NOT NULL,
  idClassificaDocumento INT NOT NULL,
  Edital VARCHAR(200) NOT NULL,
  Unidade INT NOT NULL,
  idEdital INT,
  DtCadastro timestamp,
  idPreProjeto INT NOT NULL,
  NomeProjeto VARCHAR(300) NOT NULL,
  proponente VARCHAR(150),
  Regiao VARCHAR(20),
  UF VARCHAR(100),
  Municipio VARCHAR(100),
  stEstadoAvaliacao int NOT NULL,
  DtEnvioMinC timestamp,
  stMovimentacao INT,
  Avaliacao VARCHAR,
  DtArquivamento timestamp,
  stEstado INTEGER NOT NULL,
  PRONAC VARCHAR(7),
  CNPJCPF VARCHAR(14)
);
CREATE TABLE sac.vwExtratoDaContaMovimentoConsolidado
(
  idPronac INT NOT NULL,
  Pronac VARCHAR(7) NOT NULL,
  NomeProjeto VARCHAR(300) NOT NULL,
  stContaLancamento INTEGER NOT NULL,
  TipoConta VARCHAR(12) NOT NULL,
  Agencia CHAR(5) NOT NULL,
  NrConta CHAR(12) NOT NULL,
  Codigo CHAR(4) NOT NULL,
  Lancamento VARCHAR(25) NOT NULL,
  vlLancamento DECIMAL(38,2),
  stLancamento CHAR NOT NULL
);
CREATE TABLE sac.vwExtratoDaMovimentacaoBancaria
(
  idPronac INT NOT NULL,
  Pronac VARCHAR(7) NOT NULL,
  NomeProjeto VARCHAR(300) NOT NULL,
  stContaLancamento INTEGER NOT NULL,
  Tipo VARCHAR(12) NOT NULL,
  Agencia CHAR(5) NOT NULL,
  NrConta CHAR(12) NOT NULL,
  cdLancamento CHAR(4) NOT NULL,
  Lancamento VARCHAR(25) NOT NULL,
  nrLancamento CHAR(10) NOT NULL,
  dtLancamento timestamp NOT NULL,
  vlLancamento DECIMAL(16,2) NOT NULL,
  stLancamento CHAR NOT NULL
);
CREATE TABLE sac.vwExtratoDeSaldoDasContasBancarias
(
  idPronac INT NOT NULL,
  Pronac VARCHAR(7) NOT NULL,
  NomeProjeto VARCHAR(300) NOT NULL,
  stContaLancamento INTEGER NOT NULL,
  Tipo VARCHAR(12) NOT NULL,
  Agencia CHAR(5) NOT NULL,
  NrConta CHAR(12) NOT NULL,
  TipoSaldo VARCHAR(13) NOT NULL,
  dtSaldoBancario timestamp NOT NULL,
  vlSaldoBancario DECIMAL(16,2) NOT NULL,
  stSaldoBancario CHAR NOT NULL
);
CREATE TABLE sac.vwFiscalizacao
(
  IdPRONAC INT NOT NULL,
  Pronac VARCHAR(7) NOT NULL,
  NomeProjeto VARCHAR(300) NOT NULL,
  stFiscalizacaoProjeto CHAR NOT NULL,
  dtInicioFiscalizacaoProjeto timestamp NOT NULL,
  dtFimFiscalizacaoProjeto timestamp NOT NULL,
  stFiscalizacao VARCHAR(22),
  dsAcoesProgramadas VARCHAR(8000) NOT NULL,
  dsAcoesExecutadas VARCHAR(8000) NOT NULL,
  dsBeneficioAlcancado VARCHAR(8000) NOT NULL,
  dsDificuldadeEncontrada VARCHAR(8000) NOT NULL,
  qtEmpregoDireto INT NOT NULL,
  qtEmpregoIndireto INT NOT NULL,
  qtTotalEmpregos INT,
  dsEvidencia VARCHAR(8000) NOT NULL,
  dsRecomendacaoEquipe VARCHAR(8000) NOT NULL,
  dsConclusaoEquipe VARCHAR(8000) NOT NULL,
  dsParecerTecnico VARCHAR(8000) NOT NULL,
  dsObservacao VARCHAR(5000),
  stAvaliacao CHAR NOT NULL,
  StatusAvaliacao VARCHAR(20)
);
CREATE TABLE sac.vwGerenciarProposta
(
  idProjeto INT NOT NULL,
  NomeProposta VARCHAR(300) NOT NULL,
  CNPJCPF VARCHAR(14) NOT NULL,
  idAgente INT NOT NULL,
  idUsuario INT NOT NULL,
  Tecnico VARCHAR(100),
  idSecretaria INT,
  DtAdmissibilidade timestamp,
  idAvaliacaoProposta INT NOT NULL,
  idMovimentacao INT NOT NULL,
  stTipoDemanda CHAR(2) NOT NULL,
  OrgaoEdital INT
);
CREATE TABLE sac.vwGerenciarPropostaEdital
(
  idProjeto INT NOT NULL,
  NomeProposta VARCHAR(300) NOT NULL,
  CNPJCPF VARCHAR(14) NOT NULL,
  idAgente INT NOT NULL,
  idUsuario INT NOT NULL,
  Tecnico VARCHAR(100),
  idSecretaria INT,
  DtAdmissibilidade timestamp,
  idAvaliacaoProposta INT NOT NULL,
  idMovimentacao INT NOT NULL,
  stTipoDemanda CHAR(2) NOT NULL,
  OrgaoEdital INT,
  idEdital INT,
  Edital VARCHAR(200) NOT NULL,
  Situacao VARCHAR(24)
);
CREATE TABLE sac.vwHistoricoAvaliacao
(
  idProjeto INT NOT NULL,
  idTecnico INT NOT NULL,
  usu_Nome VARCHAR(20) NOT NULL,
  DtAvaliacao timestamp,
  Avaliacao VARCHAR NOT NULL
);
CREATE TABLE sac.vwHistoricoDocumentos
(
  idPronac INT NOT NULL,
  Pronac VARCHAR(7) NOT NULL,
  NomeProjeto VARCHAR(200) NOT NULL,
  idDocumento INT NOT NULL,
  dsTipoDocumento VARCHAR(100) NOT NULL,
  dtDocumento timestamp NOT NULL,
  noArquivo VARCHAR(100),
  imDocumento VARCHAR,
  idOrigem SMALLINT,
  Origem VARCHAR(100),
  Processo VARCHAR(21),
  idHistorico INT NOT NULL,
  dtTramitacaoEnvio timestamp,
  dtTramitacaoRecebida timestamp,
  idDestino INT NOT NULL,
  Destino VARCHAR(100),
  idLote INT,
  idUsuarioEmissor SMALLINT NOT NULL,
  Emissor VARCHAR(100),
  idUsuarioReceptor SMALLINT,
  Receptor VARCHAR(100),
  Acao int,
  Situacao VARCHAR(10),
  CodigoCorreio VARCHAR(13)
);
CREATE TABLE sac.vwHistoricoTramitarProjeto
(
  idPronac INT NOT NULL,
  Pronac VARCHAR(7) NOT NULL,
  NomeProjeto VARCHAR(200) NOT NULL,
  idOrigem INT NOT NULL,
  Origem VARCHAR(100),
  Processo VARCHAR(21),
  meDespacho VARCHAR(250),
  idHistorico INT NOT NULL,
  dtTramitacaoEnvio timestamp,
  dtTramitacaoRecebida timestamp,
  idDestino INT NOT NULL,
  Destino VARCHAR(100),
  idLote INT,
  idUsuarioEmissor SMALLINT NOT NULL,
  Emissor VARCHAR(100),
  idUsuarioReceptor SMALLINT,
  Receptor VARCHAR(100),
  Acao int,
  Situacao VARCHAR(10)
);
CREATE TABLE sac.vwIncentivadores_Por_Ano_Projeto
(
  Ano INT,
  CNPJCPF VARCHAR(14) NOT NULL,
  PRONAC VARCHAR(7) NOT NULL,
  vlIncentivado MONEY
);
CREATE TABLE sac.vwIncentivadores_Por_Ano_TipoDePessoa_Enquadramento
(
  Ano INT,
  CgcCpfMecena VARCHAR(14) NOT NULL,
  Incentivador VARCHAR,
  TipoPessoa VARCHAR(8) NOT NULL,
  Enquadramento VARCHAR(9) NOT NULL,
  vlIncentivo MONEY
);
CREATE TABLE sac.vwInconsistenciaNaComprovacao
(
  idPronac INT,
  Pronac VARCHAR(20),
  NomeProjeto VARCHAR(300),
  ItemOrcamentario VARCHAR(300),
  CNPJCPF VARCHAR(20),
  Fornecedor VARCHAR(300),
  idComprovantePagamento INT,
  nrDocumentoDePagamento VARCHAR(20),
  dtPagamento timestamp,
  vlPagamento MONEY,
  vlComprovado MONEY,
  dsLancamento VARCHAR(300),
  dtLancamento timestamp,
  vlDeINTEGERado MONEY,
  vlDiferenca MONEY
);
CREATE TABLE sac.vwItemDuplicado
(
  CdAntigo INT,
  QtdePropostaCdAntigo INT,
  QtdeProjetoCdAntigo INT,
  Item VARCHAR(250) NOT NULL,
  CdNovo INT,
  QtdePropostaCdNovo INT,
  QtdeProjetoCdANovo INT
);
CREATE TABLE sac.vwItensOrcamentariosComprovados
(
  idPronac INT NOT NULL,
  Item VARCHAR(250) NOT NULL,
  qtFisicaAprovada FLOAT,
  idPlanilhaAprovacao INT NOT NULL,
  qtFisicaExecutada FLOAT NOT NULL,
  PerFisica FLOAT NOT NULL,
  vlAprovado FLOAT,
  vlExecutado MONEY,
  PercFinanceiro FLOAT,
  SaldoAExecutar FLOAT
);
CREATE TABLE sac.vwJuntarDocumento
(
  idunidade INT NOT NULL,
  Unidade VARCHAR(100),
  idPronac INT NOT NULL,
  NrProjeto VARCHAR(7) NOT NULL,
  NomeProjeto VARCHAR(200) NOT NULL,
  idDocumento INT NOT NULL,
  dtDocumento timestamp NOT NULL,
  noArquivo VARCHAR(100),
  imDocumento VARCHAR,
  stEstado int NOT NULL,
  idUsuarioJuntada SMALLINT,
  dsTipoDocumento VARCHAR(100) NOT NULL,
  dtTramitacaoEnvio timestamp,
  dtTramitacaoRecebido timestamp,
  idUsuarioReceptor SMALLINT,
  Processo VARCHAR(21)
);
CREATE TABLE sac.vwLiberacao
(
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  Pronac VARCHAR(7) NOT NULL,
  IdPRONAC INT NOT NULL,
  NomeProjeto VARCHAR(300) NOT NULL,
  Situacao CHAR(3) NOT NULL,
  CgcCpf VARCHAR(14) NOT NULL,
  Inabilitado VARCHAR(3) NOT NULL,
  Certidao VARCHAR(7) NOT NULL,
  Cadin VARCHAR(14) NOT NULL
);
CREATE TABLE sac.vwLoteDocumento
(
  idUnidadeCadastro SMALLINT,
  Unidade VARCHAR(100),
  idLote INT,
  stEstado int NOT NULL
);
CREATE TABLE sac.vwMemoriaDeCalculo
(
  idPronac INT NOT NULL,
  PRONAC VARCHAR(7) NOT NULL,
  ValorProposta MONEY,
  OutrasFontes MONEY,
  ValorSolicitado MONEY,
  Elaboracao MONEY,
  ValorSugerido MONEY,
  ValorParecer MONEY
);
CREATE TABLE sac.vwMetasComprovadas
(
  IdPRONAC INT NOT NULL,
  Etapa VARCHAR(61),
  qtFisicaAprovada FLOAT,
  qtFisicaExecutada FLOAT NOT NULL,
  PerFisica FLOAT NOT NULL,
  vlAprovado FLOAT,
  vlExecutado INT,
  PercFinanceiro FLOAT,
  SaldoAExecutar FLOAT
);
CREATE TABLE sac.vwNatureza
(
  idAgente INT NOT NULL,
  Direito int NOT NULL,
  Esfera int NOT NULL,
  Poder int NOT NULL,
  Administracao int NOT NULL,
  Usuario INT NOT NULL
);
CREATE TABLE sac.vwOrcamentoSolicitado
(
  idPronac INT NOT NULL,
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  NomeProjeto VARCHAR(300) NOT NULL,
  idProduto int NOT NULL,
  idPlanilhaProposta INT NOT NULL,
  Produto VARCHAR(100),
  Etapa VARCHAR(61),
  Item VARCHAR(250) NOT NULL,
  Unidade VARCHAR(50) NOT NULL,
  Quantidade REAL NOT NULL,
  Ocorrencia DECIMAL(4) NOT NULL,
  ValorUnitario MONEY NOT NULL,
  VlTotal REAL,
  QtdeDias INT NOT NULL,
  TipoDespesa VARCHAR(13),
  TipoPessoa VARCHAR(13),
  Contrapartida VARCHAR(13),
  FonteRecurso VARCHAR(100) NOT NULL,
  UF CHAR(2) NOT NULL,
  Municipio VARCHAR(100) NOT NULL,
  Justificativa VARCHAR(500)
);
CREATE TABLE sac.vwPainelCoordenadorAvaliacaoTrimestral
(
  IdPRONAC INT NOT NULL,
  Pronac VARCHAR(7) NOT NULL,
  NomeProjeto VARCHAR(300) NOT NULL,
  UfProjeto CHAR(2) NOT NULL,
  CgcCpf VARCHAR(14) NOT NULL,
  Orgao INT NOT NULL,
  dtInicioExecucao timestamp,
  dtFimExecucao timestamp,
  DtComprovante timestamp NOT NULL,
  DtInicioPeriodo timestamp NOT NULL,
  DtFimPeriodo timestamp NOT NULL,
  siComprovanteTrimestral CHAR NOT NULL,
  nrComprovanteTrimestral int NOT NULL,
  Diligencia int,
  idTecnicoAvaliador SMALLINT,
  dsParecerTecnico VARCHAR(3000)
);
CREATE TABLE sac.vwPainelCoordenadorReadequacaoAguardandoAnalise
(
  idPronac INT NOT NULL,
  idReadequacao INT NOT NULL,
  PRONAC VARCHAR(7) NOT NULL,
  NomeProjeto VARCHAR(300) NOT NULL,
  idOrgao INT NOT NULL,
  dtSolicitacao timestamp NOT NULL,
  tpReadequacao VARCHAR(50) NOT NULL,
  qtAguardandoDistribuicao INT
);
CREATE TABLE sac.vwPainelCoordenadorReadequacaoAnalisados
(
  idPronac INT NOT NULL,
  idReadequacao INT NOT NULL,
  PRONAC VARCHAR(7) NOT NULL,
  NomeProjeto VARCHAR(300) NOT NULL,
  dtEnvio timestamp NOT NULL,
  dtDistribuicao timestamp,
  dtDevolucao timestamp,
  qtDiasDistribuir INT,
  qtDiasAvaliar INT,
  qtTotalDiasAvaliar INT,
  tpReadequacao VARCHAR(50) NOT NULL,
  idTecnicoParecerista INT,
  nmTecnicoParecerista VARCHAR(20),
  idOrgao INT NOT NULL,
  sgUnidade VARCHAR(20) NOT NULL
);
CREATE TABLE sac.vwPainelCoordenadorReadequacaoEmAnalise
(
  idPronac INT NOT NULL,
  idReadequacao INT NOT NULL,
  PRONAC VARCHAR(7) NOT NULL,
  NomeProjeto VARCHAR(300) NOT NULL,
  dtSolicitacao timestamp NOT NULL,
  dtEncaminhamento timestamp NOT NULL,
  qtDiasEncaminhar INT,
  tpReadequacao VARCHAR(50) NOT NULL,
  siEncaminhamento int NOT NULL,
  dsEncaminhamento VARCHAR(100) NOT NULL,
  dtDistribuicao timestamp,
  qtDiasEmAnalise INT,
  idTecnicoParecerista INT,
  nmReceptor VARCHAR(20),
  nmTecnicoParecerista VARCHAR(20),
  idOrgao INT NOT NULL,
  sgUnidade VARCHAR(20) NOT NULL
);
CREATE TABLE sac.vwPainelCoordenadorVinculadasAguardandoAnalise
(
  IdPRONAC INT NOT NULL,
  NrProjeto VARCHAR(7) NOT NULL,
  NomeProjeto VARCHAR(300) NOT NULL,
  idProduto INT NOT NULL,
  Produto VARCHAR(100) NOT NULL,
  stPrincipal INTEGER,
  idArea CHAR,
  Area VARCHAR(50) NOT NULL,
  idSegmento CHAR(2),
  Segmento VARCHAR(50) NOT NULL,
  idDistribuirParecer INT NOT NULL,
  idOrgao INT,
  FecharAnalise CHAR,
  DtEnvioMincVinculada timestamp,
  qtDiasDistribuir INT,
  QtdeSecundarios INT,
  Valor FLOAT
);
CREATE TABLE sac.vwPainelCoordenadorVinculadasEmAnalise
(
  IdPRONAC INT NOT NULL,
  NrProjeto VARCHAR(7) NOT NULL,
  NomeProjeto VARCHAR(300) NOT NULL,
  idProduto INT NOT NULL,
  Produto VARCHAR(100) NOT NULL,
  idArea CHAR,
  Area VARCHAR(50) NOT NULL,
  idSegmento CHAR(2),
  Segmento VARCHAR(50) NOT NULL,
  idDistribuirParecer INT NOT NULL,
  idOrgao INT,
  DtEnvioMincVinculada timestamp,
  DtDistribuicao timestamp,
  qtDiasParaDistribuir INT,
  DtDevolucao timestamp,
  TempoTotalAnalise INT,
  TempoParecerista INT,
  TempoDiligencia INT,
  idAgenteParecerista INT,
  Parecerista VARCHAR(150) NOT NULL,
  QtdeSecundarios INT,
  dtEnvioDiligencia timestamp,
  dtRespostaDiligencia timestamp,
  qtDiligenciaProduto INT,
  Valor FLOAT,
  Observacao TEXT,
  stPrincipal INTEGER,
  FecharAnalise CHAR
);
CREATE TABLE sac.vwPainelCoordenadorVinculadasEmValidacao
(
  IdPRONAC INT NOT NULL,
  NrProjeto VARCHAR(7) NOT NULL,
  NomeProjeto VARCHAR(300) NOT NULL,
  idProduto INT NOT NULL,
  Produto VARCHAR(100) NOT NULL,
  idArea CHAR,
  Area VARCHAR(50) NOT NULL,
  idSegmento CHAR(2),
  Segmento VARCHAR(50) NOT NULL,
  idDistribuirParecer INT NOT NULL,
  Parecerista VARCHAR(150) NOT NULL,
  idOrgao INT,
  DtEnvioMincVinculada timestamp,
  DtDistribuicao timestamp,
  DtDevolucao timestamp,
  TempoTotalAnalise INT,
  TempoParecerista INT,
  TempoDiligencia INT,
  qtDiligenciaProduto INT,
  Valor FLOAT,
  Obs TEXT,
  stPrincipal INTEGER,
  FecharAnalise CHAR
);
CREATE TABLE sac.vwPainelCoordenadorVinculadasReanalisar
(
  IdPRONAC INT NOT NULL,
  NrProjeto VARCHAR(7) NOT NULL,
  NomeProjeto VARCHAR(300) NOT NULL,
  idProduto INT NOT NULL,
  Produto VARCHAR(100) NOT NULL,
  idArea CHAR,
  Area VARCHAR(50) NOT NULL,
  idSegmento CHAR(2),
  Segmento VARCHAR(50) NOT NULL,
  idDistribuirParecer INT NOT NULL,
  idOrgao INT,
  idAgenteParecerista INT,
  Parecerista VARCHAR(150),
  DtEnvioMincVinculada timestamp,
  qtDiasDistribuir INT,
  JustComponente VARCHAR(8000),
  JustDevolucaoPedido VARCHAR,
  JustSecretaria VARCHAR,
  Valor FLOAT,
  stPrincipal INTEGER,
  FecharAnalise CHAR
);
CREATE TABLE sac.vwPainelCoordenadorVinculadasValidados
(
  IdPRONAC INT NOT NULL,
  NrProjeto VARCHAR(7) NOT NULL,
  NomeProjeto VARCHAR(300) NOT NULL,
  idProduto INT NOT NULL,
  Produto VARCHAR(100) NOT NULL,
  idArea CHAR,
  Area VARCHAR(50) NOT NULL,
  idSegmento CHAR(2),
  Segmento VARCHAR(50) NOT NULL,
  idDistribuirParecer INT NOT NULL,
  Parecerista VARCHAR(150) NOT NULL,
  idOrgao INT,
  DtEnvioMincVinculada timestamp,
  DtDistribuicao timestamp,
  DtDevolucao timestamp,
  TempoTotalAnalise INT,
  TempoParecerista INT,
  TempoDiligencia INT,
  qtDiligenciaProduto INT,
  Valor FLOAT,
  Obs TEXT,
  stPrincipal INTEGER,
  FecharAnalise CHAR,
  TecnicoValidador VARCHAR(20) NOT NULL,
  dtValidacao timestamp
);
CREATE TABLE sac.vwPainelDeLiberacao
(
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  Pronac VARCHAR(7) NOT NULL,
  IdPRONAC INT NOT NULL,
  NomeProjeto VARCHAR(300) NOT NULL,
  CgcCpf VARCHAR(14) NOT NULL,
  PercentualCaptado MONEY,
  vlCaptado MONEY,
  Inabilitado VARCHAR(3) NOT NULL,
  Certidao VARCHAR(7) NOT NULL,
  Cadin VARCHAR(14) NOT NULL,
  idOrgao INT NOT NULL,
  idSecretaria INT NOT NULL,
  DtSituacao timestamp,
  Situacao VARCHAR(156) NOT NULL,
  ProvidenciaTomada VARCHAR(500)
);
CREATE TABLE sac.vwPainelReadequacaoCoordenadorParecerAguardandoAnalise
(
  idDistribuirReadequacao INT NOT NULL,
  idPRONAC INT NOT NULL,
  PRONAC VARCHAR(7) NOT NULL,
  NomeProjeto VARCHAR(300) NOT NULL,
  tpReadequacao VARCHAR(50) NOT NULL,
  dtEnvio timestamp NOT NULL,
  qtDiasAguardandoDistribuicao INT,
  idReadequacao INT NOT NULL,
  idOrgao INT NOT NULL
);
CREATE TABLE sac.vwPainelReadequacaoCoordenadorParecerAnalisados
(
  idDistribuirReadequacao INT NOT NULL,
  idPRONAC INT NOT NULL,
  PRONAC VARCHAR(7) NOT NULL,
  NomeProjeto VARCHAR(300) NOT NULL,
  tpReadequacao VARCHAR(50) NOT NULL,
  DtEnvio timestamp NOT NULL,
  dtDistribuicao timestamp,
  dtDevolucao timestamp,
  qtDiasDistribuir INT,
  qtDiasAvaliar INT,
  qtTotalDiasAvaliar INT,
  idTecnico INT,
  nmParecerista VARCHAR(20) NOT NULL,
  idReadequacao INT NOT NULL,
  idOrgao INT NOT NULL
);
CREATE TABLE sac.vwPainelReadequacaoCoordenadorParecerEmAnalise
(
  idPronac INT NOT NULL,
  idReadequacao INT NOT NULL,
  PRONAC VARCHAR(7) NOT NULL,
  NomeProjeto VARCHAR(300) NOT NULL,
  tpReadequacao VARCHAR(50) NOT NULL,
  dtDistribuicao timestamp,
  qtDiasEmAnalise INT,
  idAvaliador INT,
  nmParecerista VARCHAR(20),
  idOrgao INT NOT NULL
);
CREATE TABLE sac.vwPainelReadequacaoTecnico
(
  idPronac INT NOT NULL,
  idReadequacao INT NOT NULL,
  PRONAC VARCHAR(7) NOT NULL,
  NomeProjeto VARCHAR(300) NOT NULL,
  tpRedeaquacao VARCHAR(50) NOT NULL,
  dtDistribuicao timestamp,
  qtDiasAvaliacao INT,
  idTecnicoParecerista INT,
  idOrgao INT NOT NULL
);
CREATE TABLE sac.vwPainelTecnicoAvaliacaoTrimestral
(
  IdPRONAC INT NOT NULL,
  Pronac VARCHAR(7) NOT NULL,
  NomeProjeto VARCHAR(300) NOT NULL,
  UfProjeto CHAR(2) NOT NULL,
  CgcCpf VARCHAR(14) NOT NULL,
  Orgao INT NOT NULL,
  dtInicioExecucao timestamp,
  dtFimExecucao timestamp,
  DtComprovante timestamp NOT NULL,
  DtInicioPeriodo timestamp NOT NULL,
  DtFimPeriodo timestamp NOT NULL,
  siComprovanteTrimestral CHAR NOT NULL,
  nrComprovanteTrimestral int NOT NULL,
  Diligencia int,
  idTecnicoAvaliador SMALLINT
);
CREATE TABLE sac.vwParecerAnaliseDeConteudo
(
  idPronac INT NOT NULL,
  NrProjeto VARCHAR(7) NOT NULL,
  NomeProjeto VARCHAR(300) NOT NULL,
  Proponente VARCHAR(150) NOT NULL,
  Produto VARCHAR(100) NOT NULL,
  idProduto int NOT NULL,
  Lei8313 VARCHAR(3) NOT NULL,
  Artigo3 VARCHAR(3) NOT NULL,
  IncisoArtigo3 VARCHAR(3),
  AlineaArtigo3 VARCHAR(50),
  Artigo18 VARCHAR(3) NOT NULL,
  AlineaArtigo18 VARCHAR(50),
  Artigo26 VARCHAR(3) NOT NULL,
  Lei5761 VARCHAR(3) NOT NULL,
  Artigo27 VARCHAR(3) NOT NULL,
  IncisoArtigo27_I VARCHAR(3) NOT NULL,
  IncisoArtigo27_II VARCHAR(3) NOT NULL,
  IncisoArtigo27_III VARCHAR(3) NOT NULL,
  IncisoArtigo27_IV VARCHAR(3) NOT NULL,
  TipoParecer VARCHAR(14),
  ParecerFavoravel VARCHAR(3) NOT NULL,
  ParecerDeConteudo VARCHAR(8000),
  Parecerista VARCHAR(100)
);
CREATE TABLE sac.vwParecerFechadoOrgao
(
  idDistribuirParecer INT NOT NULL,
  idOrgao INT,
  idPronac INT NOT NULL,
  NrProjeto VARCHAR(7) NOT NULL,
  NomeProjeto VARCHAR(200) NOT NULL,
  idProduto INT NOT NULL,
  Produto VARCHAR(100) NOT NULL,
  dtDevolucao timestamp,
  DescricaoAnalise VARCHAR(20),
  TipoAnalise int NOT NULL,
  Area VARCHAR(50) NOT NULL,
  Segmento VARCHAR(50) NOT NULL
);
CREATE TABLE sac.vwPareceristasOrgao
(
  idParecerista INT NOT NULL,
  Nome VARCHAR(100) NOT NULL,
  idOrgao INT
);
CREATE TABLE sac.vwPecaDeDivulgacao
(
  idVerificacao INT NOT NULL,
  idTipo INT NOT NULL,
  PecaDeDivulgacao VARCHAR(100) NOT NULL,
  stEstado INTEGER NOT NULL
);
CREATE TABLE sac.vwPedidoDeProrrogacao
(
  idPronac INT NOT NULL,
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  PRONAC VARCHAR(7) NOT NULL,
  NomeProjeto VARCHAR(300) NOT NULL,
  CodSituacao CHAR(3) NOT NULL,
  Situacao VARCHAR(150) NOT NULL,
  DtInicioExecucao timestamp,
  DtFimExecucao timestamp,
  Mecanismo CHAR NOT NULL,
  idProrrogacao INT NOT NULL,
  DtPedido timestamp NOT NULL,
  DtInicio timestamp NOT NULL,
  DtFinal timestamp NOT NULL,
  Justificativa VARCHAR(250) NOT NULL,
  idUsuario INT NOT NULL,
  Atendimento CHAR NOT NULL
);
CREATE TABLE sac.vwPessoaJuridica_CNAE
(
  NR_CNPJ CHAR(14) NOT NULL,
  NM_RAZAO_SOCIAL VARCHAR(150) NOT NULL,
  NM_FANTASIA VARCHAR(150),
  CD_CNAE VARCHAR(12),
  DS_CNAE VARCHAR(500) NOT NULL,
  ST_CNAE CHAR
);
CREATE TABLE sac.vwPlanilhaAprovada
(
  idPronac INT NOT NULL,
  PRONAC VARCHAR(7) NOT NULL,
  NomeProjeto VARCHAR(300) NOT NULL,
  idProduto int NOT NULL,
  idPlanilhaAprovacao INT NOT NULL,
  Produto VARCHAR(100),
  Etapa VARCHAR(61),
  Item VARCHAR(250) NOT NULL,
  VlSolicitado REAL,
  JustProponente VARCHAR(500),
  VlSugerido REAL,
  JustParecerista VARCHAR,
  Unidade VARCHAR(50) NOT NULL,
  QtItem REAL NOT NULL,
  nrOcorrencia DECIMAL(4) NOT NULL,
  VlUnitario MONEY NOT NULL,
  QtDias INT NOT NULL,
  TpDespesa int NOT NULL,
  TpPessoa int NOT NULL,
  nrContrapartida int NOT NULL,
  idFonte int NOT NULL,
  FonteRecurso VARCHAR(100) NOT NULL,
  UF CHAR(2) NOT NULL,
  Municipio VARCHAR(100) NOT NULL,
  Aprovado FLOAT,
  JustComponente VARCHAR(8000)
);
CREATE TABLE sac.vwPlanilhaSugerida
(
  idPronac INT NOT NULL,
  PRONAC VARCHAR(7) NOT NULL,
  NomeProjeto VARCHAR(300) NOT NULL,
  idProduto int NOT NULL,
  idPlanilhaProjeto INT NOT NULL,
  Produto VARCHAR(100),
  Etapa VARCHAR(61),
  Item VARCHAR(250) NOT NULL,
  VlSolicitado REAL,
  JustificativaProponente VARCHAR(500),
  Unidade VARCHAR(50) NOT NULL,
  Quantidade REAL NOT NULL,
  Ocorrencia DECIMAL(4) NOT NULL,
  ValorUnitario MONEY NOT NULL,
  QtdeDias INT NOT NULL,
  TipoDespesa int NOT NULL,
  TipoPessoa int NOT NULL,
  Contrapartida int NOT NULL,
  idFonte int NOT NULL,
  FonteRecurso VARCHAR(100) NOT NULL,
  UF CHAR(2) NOT NULL,
  Municipio VARCHAR(100) NOT NULL,
  Sugerido REAL,
  Justificativa VARCHAR,
  idUsuario INT
);
CREATE TABLE sac.vwPlanilhaSugeridaCompleta
(
  idPronac INT NOT NULL,
  PRONAC VARCHAR(7) NOT NULL,
  NomeProjeto VARCHAR(300) NOT NULL,
  idProduto int NOT NULL,
  idPlanilhaProjeto INT NOT NULL,
  Produto VARCHAR(100),
  Etapa VARCHAR(61),
  Item VARCHAR(250) NOT NULL,
  VlSolicitado REAL,
  Unidade VARCHAR(50) NOT NULL,
  Quantidade REAL NOT NULL,
  Ocorrencia DECIMAL(4) NOT NULL,
  ValorUnitario MONEY NOT NULL,
  QtdeDias INT NOT NULL,
  TipoDespesa int NOT NULL,
  TipoPessoa int NOT NULL,
  Contrapartida int NOT NULL,
  idFonte int NOT NULL,
  FonteRecurso VARCHAR(100) NOT NULL,
  UF CHAR(2) NOT NULL,
  Municipio VARCHAR(100) NOT NULL,
  Sugerido FLOAT,
  Justificativa VARCHAR,
  idUsuario INT
);
CREATE TABLE sac.vwProjetoAEnviar
(
  idPronac INT NOT NULL,
  NrProjeto VARCHAR(7) NOT NULL,
  NomeProjeto VARCHAR(200) NOT NULL,
  idOrigem INT NOT NULL,
  Processo VARCHAR(21),
  Origem VARCHAR(100),
  idUsuarioEmissor SMALLINT NOT NULL,
  Emissor VARCHAR(100),
  idDestino INT NOT NULL,
  Destino VARCHAR(100)
);
CREATE TABLE sac.vwProjetoAreaSegmentoProduto
(
  idPronac INT NOT NULL,
  NrProjeto VARCHAR(7) NOT NULL,
  NomeProjeto VARCHAR(200) NOT NULL,
  idProduto INT NOT NULL,
  Produto VARCHAR(100) NOT NULL,
  Area VARCHAR(50) NOT NULL,
  Segmento VARCHAR(50) NOT NULL
);
CREATE TABLE sac.vwProjetoDistribuidoParecerista
(
  Diligencia int,
  idDistribuirParecer INT NOT NULL,
  IdPRONAC INT NOT NULL,
  PRONAC VARCHAR(7) NOT NULL,
  NomeProjeto VARCHAR(300) NOT NULL,
  idProduto INT NOT NULL,
  Produto VARCHAR(100),
  idAgenteParecerista INT,
  idOrgao INT,
  usu_codigo SMALLINT NOT NULL,
  DtDistribuicao timestamp,
  NrDias INT,
  Observacao VARCHAR(250),
  DescricaoAnalise VARCHAR(20),
  TipoAnalise int NOT NULL,
  Parecerista VARCHAR(100),
  stDiligenciado INTEGER
);
CREATE TABLE sac.vwProjetoDistribuidoVinculada
(
  idPronac INT NOT NULL,
  Pronac VARCHAR(7) NOT NULL,
  NomeProjeto VARCHAR(200) NOT NULL,
  idProduto INT NOT NULL,
  Produto VARCHAR(100) NOT NULL,
  DescricaoAnalise VARCHAR(42),
  TipoAnalise int NOT NULL,
  Orgao VARCHAR(100),
  idOrgao INT,
  DtEnvio timestamp,
  NrDias INT,
  Situacao VARCHAR(32)
);
CREATE TABLE sac.vwProjetoOrgao
(
  idDistribuirParecer INT NOT NULL,
  idOrgao INT,
  idPronac INT NOT NULL,
  NrProjeto VARCHAR(7) NOT NULL,
  NomeProjeto VARCHAR(300) NOT NULL,
  idProduto INT NOT NULL,
  Produto VARCHAR(100) NOT NULL,
  dtDevolucao timestamp,
  DescricaoAnalise VARCHAR(20),
  TipoAnalise int NOT NULL,
  Obs VARCHAR(100),
  Area VARCHAR(50) NOT NULL,
  Segmento VARCHAR(50) NOT NULL,
  DtEnvio timestamp
);
CREATE TABLE sac.vwProjetos_Na_Pauta_CNIC
(
  Analise VARCHAR(29),
  idAgente INT NOT NULL,
  IdPronac INT NOT NULL,
  Pronac VARCHAR(7) NOT NULL,
  NomeProjeto VARCHAR(300) NOT NULL,
  ResumoProjeto TEXT,
  Proponente VARCHAR(150) NOT NULL,
  UF CHAR(2) NOT NULL,
  Cidade VARCHAR(100),
  DtDistribuicao timestamp NOT NULL,
  Area CHAR NOT NULL,
  DescArea VARCHAR(50) NOT NULL,
  Segmento VARCHAR(4) NOT NULL,
  DescSegmento VARCHAR(50) NOT NULL,
  DtInicioExecucao timestamp,
  DtFimExecucao timestamp,
  Dias INT,
  idNrReuniao INT,
  NrReuniao INT,
  stAnalise VARCHAR(2),
  Avaliacao VARCHAR(12),
  SolicitadoReal MONEY
);
CREATE TABLE sac.vwProjetosAReceber
(
  NrProjeto VARCHAR(7) NOT NULL,
  NomeProjeto VARCHAR(200) NOT NULL,
  idOrigem INT NOT NULL,
  Origem VARCHAR(100),
  dtTramitacaoEnvio timestamp,
  idUnidade INT NOT NULL,
  idDestino VARCHAR(100),
  idLote INT,
  idUsuarioEmissor SMALLINT NOT NULL,
  Emissor VARCHAR(100)
);
CREATE TABLE sac.vwProjetosConsolidados
(
  PRONAC VARCHAR(7) NOT NULL,
  NomeProjeto VARCHAR(300) NOT NULL,
  ValorProposta MONEY,
  OutrasFontes MONEY,
  ValorSolicitado MONEY,
  ValorSugerido MONEY,
  Elaboracao MONEY,
  ValorParecer MONEY,
  Perc MONEY,
  Acima VARCHAR(12) NOT NULL
);
CREATE TABLE sac.vwProjetosDespachados
(
  idPronac INT NOT NULL,
  NrProjeto VARCHAR(7) NOT NULL,
  NomeProjeto VARCHAR(300) NOT NULL,
  Orgao INT NOT NULL,
  idUnidade INT NOT NULL,
  dtTramitacaoEnvio timestamp,
  stEstado INTEGER NOT NULL
);
CREATE TABLE sac.vwProjetosEnviados
(
  NrProjeto VARCHAR(7) NOT NULL,
  NomeProjeto VARCHAR(200) NOT NULL,
  Processo VARCHAR(21),
  dtTramitacaoEnvio timestamp,
  idOrigem INT NOT NULL,
  Origem VARCHAR(100),
  idLote INT,
  idDestino INT NOT NULL,
  Destino VARCHAR(100),
  idUsuarioEmissor SMALLINT NOT NULL,
  Emissor VARCHAR(100)
);
CREATE TABLE sac.vwProjetoSeusDocumentos
(
  idPronac INT NOT NULL,
  NrProjeto VARCHAR(7) NOT NULL,
  NomeProjeto VARCHAR(300) NOT NULL,
  dsTipoDocumento VARCHAR(100) NOT NULL,
  dtDocumento timestamp NOT NULL,
  imDocumento VARCHAR,
  noArquivo VARCHAR(130),
  Usuario VARCHAR(100),
  dtJuntada timestamp,
  idLote INT,
  Acao int
);
CREATE TABLE sac.vwProjetosRecebidos
(
  NrProjeto VARCHAR(7) NOT NULL,
  NomeProjeto VARCHAR(200) NOT NULL,
  dtTramitacaoEnvio timestamp,
  dtTramitacaoRecebido timestamp,
  idUnidade INT NOT NULL,
  Unidade VARCHAR(100),
  idLote INT
);
CREATE TABLE sac.vwProposta
(
  idPreProjeto INT NOT NULL,
  NomeProjeto VARCHAR(300) NOT NULL,
  idAgente INT NOT NULL,
  Proponente VARCHAR(150),
  ResumoDoProjeto VARCHAR(1000) NOT NULL,
  UF VARCHAR(100),
  PRONAC VARCHAR(7),
  Situacao VARCHAR(48),
  stTipoDemanda CHAR(2) NOT NULL
);
CREATE TABLE sac.vwPropostaEmAndamento
(
  idPreProjeto INT NOT NULL,
  NomeProjeto VARCHAR(300) NOT NULL,
  idAgente INT NOT NULL,
  Proponente VARCHAR(100),
  UF VARCHAR(100)
);
CREATE TABLE sac.vwPropostaParaProjeto
(
  idProjeto INT NOT NULL,
  NomeProposta VARCHAR(300) NOT NULL,
  CNPJCPF VARCHAR(14) NOT NULL,
  idOrgao INT NOT NULL,
  idUsuario INT NOT NULL
);
CREATE TABLE sac.vwPropostaProjetoSecretaria
(
  idPreProjeto INT NOT NULL,
  NomeProjeto VARCHAR(300) NOT NULL,
  Tecnico VARCHAR(100),
  DtEnvio timestamp,
  DtMovimentacao timestamp NOT NULL,
  DtAvaliacao timestamp,
  Dias INT,
  idOrgao INT,
  ConformidadeOK int NOT NULL,
  QtdeDiasAguardandoEnvio INT
);
CREATE TABLE sac.vwReceberLoteDocumento
(
  idUnidade INT NOT NULL,
  idLote INT,
  idUsuarioReceptor INT NOT NULL,
  Qtde INT
);
CREATE TABLE sac.vwReceberLoteProjeto
(
  idUnidade INT NOT NULL,
  idLote INT,
  idUsuarioReceptor INT NOT NULL,
  Qtde INT
);
CREATE TABLE sac.vwRedistribuirAnaliseVisual
(
  idProjeto INT NOT NULL,
  NomeProjeto VARCHAR(300) NOT NULL,
  Tecnico INT,
  DtMovimentacao timestamp NOT NULL,
  Descricao VARCHAR(100) NOT NULL
);
CREATE TABLE sac.vwSolicitarProrrogacaoPrazoCaptacao
(
  idPronac INT NOT NULL,
  AnoProjeto CHAR(2) NOT NULL,
  Sequencial VARCHAR(5) NOT NULL,
  PRONAC VARCHAR(7) NOT NULL,
  NomeProjeto VARCHAR(300) NOT NULL,
  CodSituacao CHAR(3) NOT NULL,
  Situacao VARCHAR(150) NOT NULL,
  DtInicioExecucao timestamp,
  DtFimExecucao timestamp,
  Mecanismo CHAR NOT NULL,
  idProrrogacao INT NOT NULL,
  DtPedido timestamp NOT NULL,
  DtInicio timestamp NOT NULL,
  DtFinal timestamp NOT NULL,
  Justificativa VARCHAR(250) NOT NULL,
  idUsuario INT NOT NULL,
  Atendimento CHAR NOT NULL,
  idDocumento INT NOT NULL
);
CREATE TABLE sac.vwTbCadastrarDocumento
(
  idPronac INT NOT NULL,
  NrProjeto VARCHAR(7) NOT NULL,
  idDocumento INT NOT NULL,
  stestado int NOT NULL,
  imDocumento VARCHAR,
  idTipoDocumento INT NOT NULL,
  dtDocumento timestamp NOT NULL,
  noArquivo VARCHAR(130),
  taArquivo INT,
  idUsuario SMALLINT NOT NULL,
  idUnidadeCadastro SMALLINT,
  idunidade INT NOT NULL,
  CodigoCorreio VARCHAR(13)
);
CREATE TABLE sac.vwtbDiligencia
(
  idDiligencia INT NOT NULL,
  idPronac INT NOT NULL,
  idTipoDiligencia INT NOT NULL,
  DtSolicitacao timestamp NOT NULL,
  Solicitacao VARCHAR NOT NULL,
  idSolicitante INT NOT NULL,
  DtResposta timestamp,
  Resposta VARCHAR,
  idProponente INT,
  stEstado INTEGER NOT NULL,
  idPlanoDistribuicao INT,
  idArquivo INT,
  idCodigoDocumentosExigidos INT,
  idProduto int,
  stProrrogacao CHAR,
  stEnviado CHAR
);
CREATE TABLE sac.vwTramitarProjeto
(
  idPronac INT NOT NULL,
  Pronac VARCHAR(7) NOT NULL,
  NomeProjeto VARCHAR(200) NOT NULL,
  idOrigem INT NOT NULL,
  Origem VARCHAR(100),
  Processo VARCHAR(21),
  meDespacho VARCHAR(250),
  idHistorico INT NOT NULL,
  dtTramitacaoEnvio timestamp,
  dtTramitacaoRecebida timestamp,
  idDestino INT NOT NULL,
  Destino VARCHAR(100),
  idLote INT,
  idUsuarioEmissor SMALLINT NOT NULL,
  Emissor VARCHAR(100),
  idUsuarioReceptor SMALLINT,
  Receptor VARCHAR(100),
  Acao int,
  Situacao VARCHAR(10)
);
CREATE TABLE sac.vwVeiculoDeDivulgacao
(
  idVerificacao INT NOT NULL,
  idTipo INT NOT NULL,
  VeiculoDeDivulgacao VARCHAR(100) NOT NULL,
  stEstado INTEGER NOT NULL
);
ALTER TABLE sac.tbdeslocamento ALTER COLUMN idmunicipioorigem TYPE VARCHAR(6) USING idmunicipioorigem::VARCHAR(6);

CREATE TABLE sac.tbItensPlanilhaProduto
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

CREATE SEQUENCE sac.verificacao_idverificacao_seq NO MINVALUE NO MAXVALUE NO CYCLE;
ALTER TABLE sac.verificacao ALTER COLUMN idverificacao SET DEFAULT nextval('sac.verificacao_idverificacao_seq');
ALTER SEQUENCE sac.verificacao_idverificacao_seq OWNED BY sac.verificacao.idverificacao;


drop TABLE sac.vwDocumentosExigidosApresentacaoProposta;

CREATE VIEW sac.vwDocumentosExigidosApresentacaoProposta
AS

  -- =========================================================================================
  -- Autor: Rômulo Menhô Barbosa
  -- Data de Criacao: 19/12/2012
  -- Descricao: Documentos exigidos pela proposta
  -- =========================================================================================

  SELECT Codigo, Descricao,Opcao
  FROM SAC.DocumentosExigidos
  WHERE Codigo not in (238,229,99,194,205) AND stUpload = 1 AND stEstado = 1;
-- CREATE FUNCTION Aritmetica(@idPlanilhaItem INT) RETURNS INT;
-- CREATE PROCEDURE DecriptografaObjetosBD(@ObjetoCriptografado SYSNAME, @SegurancaAlteracao INT);
-- CREATE PROCEDURE dt_addtosourcecontrol(@vchSourceSafeINI VARCHAR, @vchProjectName VARCHAR, @vchComment VARCHAR, @vchLoginName VARCHAR, @vchPassword VARCHAR);
-- CREATE PROCEDURE dt_addtosourcecontrol_u(@vchSourceSafeINI SYSNAME, @vchProjectName SYSNAME, @vchComment SYSNAME, @vchLoginName SYSNAME, @vchPassword SYSNAME);
-- CREATE PROCEDURE dt_adduserobject();
-- CREATE PROCEDURE dt_adduserobject_vcs(@vchProperty VARCHAR);
-- CREATE PROCEDURE dt_checkinobject(@chObjectType CHAR, @vchObjectName VARCHAR, @vchComment VARCHAR, @vchLoginName VARCHAR, @vchPassword VARCHAR, @iVCSFlags INT, @iActionFlag INT, @txStream1 TEXT, @txStream2 TEXT, @txStream3 TEXT);
-- CREATE PROCEDURE dt_checkinobject_u(@chObjectType CHAR, @vchObjectName SYSNAME, @vchComment SYSNAME, @vchLoginName SYSNAME, @vchPassword SYSNAME, @iVCSFlags INT, @iActionFlag INT, @txStream1 TEXT, @txStream2 TEXT, @txStream3 TEXT);
-- CREATE PROCEDURE dt_checkoutobject(@chObjectType CHAR, @vchObjectName VARCHAR, @vchComment VARCHAR, @vchLoginName VARCHAR, @vchPassword VARCHAR, @iVCSFlags INT, @iActionFlag INT);
-- CREATE PROCEDURE dt_checkoutobject_u(@chObjectType CHAR, @vchObjectName SYSNAME, @vchComment SYSNAME, @vchLoginName SYSNAME, @vchPassword SYSNAME, @iVCSFlags INT, @iActionFlag INT);
-- CREATE PROCEDURE dt_displayoaerror(@iObject INT, @iresult INT);
-- CREATE PROCEDURE dt_displayoaerror_u(@iObject INT, @iresult INT);
-- CREATE PROCEDURE dt_droppropertiesbyid(@id INT, @property VARCHAR);
-- CREATE PROCEDURE dt_dropuserobjectbyid(@id INT);
-- CREATE PROCEDURE dt_generateansiname(@name VARCHAR);
-- CREATE PROCEDURE dt_getobjwithprop(@property VARCHAR, @value VARCHAR);
-- CREATE PROCEDURE dt_getobjwithprop_u(@property VARCHAR, @uvalue SYSNAME);
-- CREATE PROCEDURE dt_getpropertiesbyid(@id INT, @property VARCHAR);
-- CREATE PROCEDURE dt_getpropertiesbyid_u(@id INT, @property VARCHAR);
-- CREATE PROCEDURE dt_getpropertiesbyid_vcs(@id INT, @property VARCHAR, @value VARCHAR);
-- CREATE PROCEDURE dt_getpropertiesbyid_vcs_u(@id INT, @property VARCHAR, @value SYSNAME);
-- CREATE PROCEDURE dt_isundersourcecontrol(@vchLoginName VARCHAR, @vchPassword VARCHAR, @iWhoToo INT);
-- CREATE PROCEDURE dt_isundersourcecontrol_u(@vchLoginName SYSNAME, @vchPassword SYSNAME, @iWhoToo INT);
-- CREATE PROCEDURE dt_removefromsourcecontrol();
-- CREATE PROCEDURE dt_setpropertybyid(@id INT, @property VARCHAR, @value VARCHAR, @lvalue IMAGE);
-- CREATE PROCEDURE dt_setpropertybyid_u(@id INT, @property VARCHAR, @uvalue SYSNAME, @lvalue IMAGE);
-- CREATE PROCEDURE dt_validateloginparams(@vchLoginName VARCHAR, @vchPassword VARCHAR);
-- CREATE PROCEDURE dt_validateloginparams_u(@vchLoginName SYSNAME, @vchPassword SYSNAME);
-- CREATE PROCEDURE dt_vcsenabled();
-- CREATE PROCEDURE dt_verstamp006();
-- CREATE PROCEDURE dt_verstamp007();
-- CREATE PROCEDURE dt_whocheckedout(@chObjectType CHAR, @vchObjectName VARCHAR, @vchLoginName VARCHAR, @vchPassword VARCHAR);
-- CREATE PROCEDURE dt_whocheckedout_u(@chObjectType CHAR, @vchObjectName SYSNAME, @vchLoginName SYSNAME, @vchPassword SYSNAME);
-- CREATE FUNCTION DtPrmeiraCaptacao(@AnoProjeto CHAR, @Sequencial VARCHAR) RETURNS timestamp;
-- CREATE PROCEDURE ExecutaJobBanco(@Job SMALLINT);
-- CREATE FUNCTION fn_diagramobjects();
-- CREATE FUNCTION fnApoioProponenteAnoArtigo(@Ano INT, @CgcCpf VARCHAR, @Artigo CHAR) RETURNS MONEY;
-- CREATE FUNCTION fnAproOrcDetComArt1(@AnoProjeto CHAR, @Sequencial VARCHAR) RETURNS MONEY;
-- CREATE FUNCTION fnAproOrcDetComArt3(@AnoProjeto CHAR, @Sequencial VARCHAR) RETURNS MONEY;
-- CREATE FUNCTION fnAproOrcDetComMec(@AnoProjeto CHAR, @Sequencial VARCHAR) RETURNS MONEY;
-- CREATE FUNCTION fnAproOrcDetProArt1(@AnoProjeto CHAR, @Sequencial VARCHAR) RETURNS MONEY;
-- CREATE FUNCTION fnAproOrcDetProArt3(@AnoProjeto CHAR, @Sequencial VARCHAR) RETURNS MONEY;
-- CREATE FUNCTION fnAproOrcDetProMec(@AnoProjeto CHAR, @Sequencial VARCHAR) RETURNS MONEY;
-- CREATE FUNCTION fnAproProjArt1(@AnoProjeto CHAR, @Sequencial VARCHAR) RETURNS MONEY;
-- CREATE FUNCTION fnAproProjArt3(@AnoProjeto CHAR, @Sequencial VARCHAR) RETURNS MONEY;
-- CREATE FUNCTION fnAproProjContra(@AnoProjeto CHAR, @Sequencial VARCHAR) RETURNS MONEY;
-- CREATE FUNCTION fnAproProjConv(@AnoProjeto CHAR, @Sequencial VARCHAR) RETURNS MONEY;
-- CREATE FUNCTION fnAproProjMec(@AnoProjeto CHAR, @Sequencial VARCHAR) RETURNS MONEY;
-- CREATE FUNCTION fnAprovadoAnoArea(@Ano INT, @Area CHAR) RETURNS MONEY;
-- CREATE FUNCTION fnAprovadoAnoProjeto(@Ano INT, @AnoProjeto CHAR, @Sequencial VARCHAR) RETURNS MONEY;
-- CREATE FUNCTION fnAprovadoAnoUFMec(@Ano INT, @UF CHAR) RETURNS MONEY;
-- CREATE FUNCTION fnAprovadoFNCProjeto(@AnoProjeto CHAR, @Sequencial VARCHAR) RETURNS MONEY;
-- CREATE FUNCTION fnAprovadoProjeto(@AnoProjeto CHAR, @Sequencial VARCHAR) RETURNS MONEY;
-- CREATE FUNCTION fnAprovadoProjetoPorAno(@Ano INT, @AnoProjeto CHAR, @Sequencial VARCHAR) RETURNS MONEY;
-- CREATE FUNCTION fnBandaConcedidaAno(@p_Ano INT, @p_AnoProjeto CHAR, @p_Sequencial VARCHAR) RETURNS MONEY;
-- CREATE FUNCTION fnBolsaConcedida(@p_ano CHAR, @p_seq VARCHAR) RETURNS MONEY;
-- CREATE FUNCTION fnBolsaConcedidaAno(@p_Ano INT, @p_AnoProjeto CHAR, @p_Sequencial VARCHAR) RETURNS MONEY;
-- CREATE FUNCTION fnCalcularElaboracaoAgenciamento(@idPronac INT) RETURNS MONEY;
-- CREATE FUNCTION fnCapProjArt1(@AnoProjeto CHAR, @Sequencial VARCHAR) RETURNS MONEY;
-- CREATE FUNCTION fnCapProjArt3(@AnoProjeto CHAR, @Sequencial VARCHAR) RETURNS MONEY;
-- CREATE FUNCTION fnCapProjConv(@AnoProjeto CHAR, @Sequencial VARCHAR) RETURNS MONEY;
-- CREATE FUNCTION fnCapProjMec(@AnoProjeto CHAR, @Sequencial VARCHAR) RETURNS MONEY;
-- CREATE FUNCTION fnCaptacaoAnoArtigoArea(@Ano INT, @Artigo CHAR, @Area CHAR) RETURNS MONEY;
-- CREATE FUNCTION fnCaptacaoAnoArtigoProjeto(@Ano INT, @Artigo CHAR) RETURNS MONEY;
-- CREATE FUNCTION fnCaptacaoAnoArtigoProjetoArea(@Ano INT, @Artigo CHAR, @Area CHAR) RETURNS MONEY;
-- CREATE FUNCTION fnCaptacaoAnoArtigoUfTipoPessoaIncentivador(@Ano INT, @Artigo CHAR, @UF CHAR, @TipoPessoa CHAR) RETURNS MONEY;
-- CREATE FUNCTION fnCaptacaoAnoProjeto(@Ano INT, @anoprojeto CHAR, @sequencial VARCHAR) RETURNS MONEY;
-- CREATE FUNCTION fnCaptacaoAnoReg(@Ano INT, @Regiao VARCHAR) RETURNS MONEY;
-- CREATE FUNCTION fnCaptacaoAnoRegArea(@Ano INT, @Regiao VARCHAR, @Area CHAR) RETURNS MONEY;
-- CREATE FUNCTION fnCaptacaoAnoRegAreaPFPJ(@Ano INT, @Area CHAR, @tipopessoa CHAR) RETURNS MONEY;
-- CREATE FUNCTION fnCaptacaoAnoRegAreaQtde(@Ano INT, @Regiao VARCHAR, @Area CHAR) RETURNS INT;
-- CREATE FUNCTION fnCaptacaoAnoRegAreaQtdePFPJ(@Ano INT, @Area CHAR, @tipopessoa CHAR) RETURNS INT;
-- CREATE FUNCTION fnCaptacaoAnoSit(@Ano INT, @Sit CHAR) RETURNS MONEY;
-- CREATE FUNCTION fnCaptacaoAnoSitArea(@Ano INT, @Sit CHAR, @area CHAR) RETURNS MONEY;
-- CREATE FUNCTION fnCaptacaoAnoUFArea(@Ano INT, @UF CHAR, @Area CHAR) RETURNS MONEY;
-- CREATE FUNCTION fnCaptacaoAnoUFAreaQtde(@Ano INT, @UF CHAR, @Area CHAR) RETURNS INT;
-- CREATE FUNCTION fnCaptacaoAteAnoArtigoProjeto(@Ano INT, @Artigo CHAR) RETURNS MONEY;
-- CREATE FUNCTION fnCaptacaoAteAnoArtigoProjetoArea(@Ano INT, @Artigo CHAR, @Area CHAR) RETURNS MONEY;
-- CREATE FUNCTION fnCaptacaoAteAnoSit(@Ano INT, @Sit CHAR) RETURNS MONEY;
-- CREATE FUNCTION fnCaptacaoAteAnoSitArea(@Ano INT, @Sit CHAR, @area CHAR) RETURNS MONEY;
-- CREATE FUNCTION fnCaptadoAnoAreaMec(@Ano INT, @Area CHAR) RETURNS MONEY;
-- CREATE FUNCTION fnCaptadoAnoAreaMecCE(@Ano INT, @Area CHAR, @mecanismo CHAR) RETURNS MONEY;
-- CREATE FUNCTION fnCaptadoAnoAreaMecSC(@Ano INT, @Area CHAR) RETURNS MONEY;
-- CREATE FUNCTION fnCaptadoAnoChegadaRecibo(@Ano INT) RETURNS MONEY;
-- CREATE FUNCTION fnCaptadoAnoMec(@Ano INT) RETURNS MONEY;
-- CREATE FUNCTION fnCaptadoAnoSituacao(@Ano INT, @Situacao CHAR) RETURNS MONEY;
-- CREATE FUNCTION fnCaptadoAnoSituacaoSav(@Ano INT, @Situacao CHAR) RETURNS MONEY;
-- CREATE FUNCTION fnCaptadoAnoSituacaoSefic(@Ano INT, @Situacao CHAR) RETURNS MONEY;
-- CREATE FUNCTION fnCaptadoAnoUfMec(@Ano INT, @UF CHAR) RETURNS MONEY;
-- CREATE FUNCTION fnCaptadoAreaAteAnoMec(@Ano INT, @Area CHAR) RETURNS MONEY;
-- CREATE FUNCTION fnCaptadoAreaMec(@Area CHAR) RETURNS MONEY;
-- CREATE FUNCTION fnCaptadoAteAnoSituacaoSav(@Ano INT, @Situacao CHAR) RETURNS MONEY;
-- CREATE FUNCTION fnCaptadoAteAnoSituacaoSavDT(@data timestamp, @Situacao CHAR) RETURNS MONEY;
-- CREATE FUNCTION fnCaptadoAteAnoSituacaoSefic(@Ano INT, @Situacao CHAR) RETURNS MONEY;
-- CREATE FUNCTION fnCaptadoAteAnoSituacaoSeficDT(@data timestamp, @Situacao CHAR) RETURNS MONEY;
-- CREATE FUNCTION fnCaptadoIncentivadorAnoTipoApoio(@cgccpf VARCHAR, @ano INT, @tpapoio CHAR) RETURNS MONEY;
-- CREATE FUNCTION fnCaptadoProjetoAno(@Ano INT, @AnoProjeto CHAR, @Sequencial VARCHAR) RETURNS MONEY;
-- CREATE FUNCTION fnCaptadoProjetoAnoMec(@CgcCpf VARCHAR, @anoprojeto CHAR, @sequencial VARCHAR, @Ano INT, @tpapoio CHAR) RETURNS MONEY;
-- CREATE FUNCTION fnCaptadoProponenteAnoMec(@Ano INT, @CgcCpf VARCHAR) RETURNS MONEY;
-- CREATE FUNCTION fnCaptadoProponenteAnoMecArea(@Ano INT, @CgcCpf VARCHAR, @Area CHAR) RETURNS MONEY;
-- CREATE FUNCTION fnCaptadoProponenteMec(@CgcCpf VARCHAR) RETURNS MONEY;
-- CREATE FUNCTION fnCaptadoProponenteMecArea(@CgcCpf VARCHAR, @Area CHAR) RETURNS MONEY;
-- CREATE FUNCTION fnCaptadoUfMec(@UF CHAR) RETURNS MONEY;
-- CREATE FUNCTION fnchecarConclusao(@idPronac INT) RETURNS INTEGER;
-- CREATE FUNCTION fnchecarDiligencia(@idPronac INT) RETURNS int;
-- CREATE FUNCTION fnChecarDistribuicaoProjeto(@p_idPronac INT, @p_idProduto int, @p_TipoAnalise int) RETURNS VARCHAR;
-- CREATE FUNCTION fnchecarOrgao(@idPronac INT, @idOrgao INT) RETURNS INT;
-- CREATE FUNCTION fnchecarValidacaoProdutoSecundario(@idPronac INT) RETURNS INTEGER;
-- CREATE FUNCTION fnCondicoesParaCaptar(@AnoProjeto CHAR, @Sequencial VARCHAR, @DtRecibo timestamp) RETURNS VARCHAR;
-- CREATE FUNCTION fnCondicoesParaComplementar(@AnoProjeto CHAR, @Sequencial VARCHAR, @Operacao int, @Getdate timestamp) RETURNS VARCHAR;
-- CREATE FUNCTION fnCondicoesParaProrrogar(@AnoProjeto CHAR, @Sequencial VARCHAR, @Getdate timestamp, @DtInicio timestamp, @DtFim timestamp, @Opcao CHAR) RETURNS VARCHAR;
-- CREATE FUNCTION fnCondicoesProrrogarConvenio(@AnoProjeto CHAR, @Sequencial VARCHAR, @Getdate timestamp, @DtInicioPro timestamp, @DtFimPro timestamp, @Opcao CHAR) RETURNS VARCHAR;
-- CREATE FUNCTION fnConsolidarTextoParecer(@idPronac INT) RETURNS VARCHAR;
-- CREATE FUNCTION fnConsolidarValor(@idPronac INT) RETURNS MONEY;
-- CREATE FUNCTION fnConsolidarValorOld(@idPronac INT) RETURNS MONEY;
-- CREATE FUNCTION fnCustoProjeto(@AnoProjeto CHAR, @Sequencial VARCHAR) RETURNS MONEY;
-- CREATE FUNCTION fnCustoProjetoPorAno(@Ano INT, @AnoProjeto CHAR, @Sequencial VARCHAR) RETURNS MONEY;
-- CREATE FUNCTION fnDataDeAprovacao(@AnoProjeto CHAR, @Sequencial VARCHAR, @Data timestamp) RETURNS timestamp;
-- CREATE FUNCTION fnDataDeFimCaptacao(@AnoProjeto CHAR, @Sequencial VARCHAR, @Data timestamp) RETURNS timestamp;
-- CREATE FUNCTION fnDataDeInicioCaptacao(@AnoProjeto CHAR, @Sequencial VARCHAR, @Data timestamp) RETURNS timestamp;
-- CREATE FUNCTION fnDataDePortaria(@AnoProjeto CHAR, @Sequencial VARCHAR, @Data timestamp) RETURNS timestamp;
-- CREATE FUNCTION fnDataDePublicacao(@AnoProjeto CHAR, @Sequencial VARCHAR, @Data timestamp) RETURNS timestamp;
-- CREATE FUNCTION fnDtAprovacao(@AnoProjeto CHAR, @Sequencial VARCHAR) RETURNS timestamp;
-- CREATE FUNCTION fnDtConsolidacaoParecer(@p_idPronac INT) RETURNS timestamp;
-- CREATE FUNCTION fnDtConvenio(@AnoProjeto CHAR, @Sequencial VARCHAR) RETURNS timestamp;
-- CREATE FUNCTION fnDtDevolucaoBilhete(@AnoProjeto CHAR, @Sequencial VARCHAR) RETURNS timestamp;
-- CREATE FUNCTION fnDtEntregaBilhete(@AnoProjeto CHAR, @Sequencial VARCHAR) RETURNS timestamp;
-- CREATE FUNCTION fnDtEnvioAvaliacao(@p_idProjeto INT) RETURNS timestamp;
-- CREATE FUNCTION fnDtEnvioVinculada(@p_idPronac INT) RETURNS timestamp;
-- CREATE FUNCTION fnDtInicioRelatorioTrimestral(@p_idPronac INT) RETURNS timestamp;
-- CREATE FUNCTION fnDtInicioVigencia(@AnoProjeto CHAR, @Sequencial VARCHAR) RETURNS timestamp;
-- CREATE FUNCTION fnDtLiberacaoConta(@AnoProjeto CHAR, @Sequencial VARCHAR) RETURNS timestamp;
-- CREATE FUNCTION fnDtPortariaAprovacao(@AnoProjeto CHAR, @Sequencial VARCHAR) RETURNS timestamp;
-- CREATE FUNCTION fnDtPortariaPublicacao(@AnoProjeto CHAR, @Sequencial VARCHAR) RETURNS timestamp;
-- CREATE FUNCTION fnDtPrestacaoContas(@AnoProjeto CHAR, @Sequencial VARCHAR) RETURNS timestamp;
-- CREATE FUNCTION fnDtPrimeiraCaptacao(@AnoProjeto CHAR, @Sequencial VARCHAR) RETURNS timestamp;
-- CREATE FUNCTION fnDtProrExerFiscal(@Ano INT, @AnoProjeto CHAR, @Sequencial VARCHAR) RETURNS timestamp;
-- CREATE FUNCTION fnDtPublicacao(@AnoProjeto CHAR, @Sequencial VARCHAR) RETURNS timestamp;
-- CREATE FUNCTION fnDtPublicacaoAprovacao(@AnoProjeto CHAR, @Sequencial VARCHAR) RETURNS timestamp;
-- CREATE FUNCTION fnDtRetornoMinC(@p_idPronac INT) RETURNS timestamp;
-- CREATE FUNCTION fnDtUltimaAprovacao(@AnoProjeto CHAR, @Sequencial VARCHAR) RETURNS timestamp;
-- CREATE FUNCTION fnDtUltimaCaptacao(@AnoProjeto CHAR, @Sequencial VARCHAR) RETURNS timestamp;
-- CREATE FUNCTION fnDtUltimaCarta(@AnoProjeto CHAR, @Sequencial VARCHAR, @NumeroCarta CHAR) RETURNS timestamp;
-- CREATE FUNCTION fnDtUltimaDiligenciaDocumental(@p_idProjeto INT) RETURNS timestamp;
-- CREATE FUNCTION fnDtUltimaEnvioAvaliacao(@p_idProjeto INT) RETURNS timestamp;
-- CREATE FUNCTION fnDtUltimaMovimentacao(@p_idProjeto INT) RETURNS timestamp;
-- CREATE FUNCTION fnDtUltimoEnvioVinculada(@p_idPronac INT) RETURNS timestamp;
-- CREATE FUNCTION fnEditalTotalPago(@AnoProjeto CHAR, @Sequencial VARCHAR) RETURNS MONEY;
-- CREATE FUNCTION fnEmpenhado(@NrEmpenho VARCHAR) RETURNS MONEY;
-- CREATE FUNCTION fnEmpenho(@NrEmpenho VARCHAR) RETURNS MONEY;
-- CREATE FUNCTION fnFimCaptacao(@AnoProjeto CHAR, @Sequencial VARCHAR) RETURNS timestamp;
-- CREATE FUNCTION fnFimVigencia(@AnoProjeto CHAR, @Sequencial VARCHAR) RETURNS timestamp;
-- CREATE FUNCTION fnFNCProjAprovAnoSegmento(@Ano INT, @Segmento CHAR) RETURNS MONEY;
-- CREATE FUNCTION fnFormataProcesso(@p_idPronac INT) RETURNS VARCHAR;
-- CREATE FUNCTION fnIdOrgaoSuperiorAnalista(@p_idTecnico INT) RETURNS INT;
-- CREATE FUNCTION fnIdOrgaoSuperiorTecnicoOrgaoPerfil(@p_idTecnico INT, @p_idOrgao INT, @p_idGrupo INT) RETURNS INT;
-- CREATE FUNCTION fnIdTecnicoMinc(@p_idProjeto INT) RETURNS INT;
-- CREATE FUNCTION fnIdTecnicoRedistribuir(@p_idProjeto INT) RETURNS INT;
-- CREATE FUNCTION fnIncentivador(@CgcCpf VARCHAR) RETURNS MONEY;
-- CREATE FUNCTION fnIncentivadorAno(@Ano VARCHAR, @CgcCpf VARCHAR) RETURNS MONEY;
-- CREATE FUNCTION fnIncentivadorAnoProjeto(@Ano VARCHAR, @CgcCpf VARCHAR, @AnoProjeto CHAR, @Sequencial VARCHAR) RETURNS MONEY;
-- CREATE FUNCTION fnInicioCaptacao(@AnoProjeto CHAR, @Sequencial VARCHAR) RETURNS timestamp;
-- CREATE FUNCTION fnlCaptadoAreaAno(@Ano INT, @Area CHAR) RETURNS MONEY;
-- CREATE FUNCTION fnLiberarLinks(@Acao int, @CNPJCPF_Proponente VARCHAR, @idUsuario_Logado INT, @idPronac INT) RETURNS VARCHAR;
-- CREATE FUNCTION fnMecProjApresAnoUFArea(@p_ano INT, @UF CHAR, @Area CHAR) RETURNS MONEY;
-- CREATE FUNCTION fnMecProjAprovAnoSegmento(@Ano INT, @Segmento CHAR) RETURNS MONEY;
-- CREATE FUNCTION fnMecProjAprovAnoUFArea(@Ano INT, @UF CHAR, @Area CHAR) RETURNS MONEY;
-- CREATE FUNCTION fnMecProjAprovAnoUFSegmento(@Ano INT, @UF CHAR, @Segmento CHAR) RETURNS MONEY;
-- CREATE FUNCTION fnMecProjCaptaAnoSegmento(@Ano INT, @Segmento CHAR) RETURNS INT;
-- CREATE FUNCTION fnMecProjCaptaAnoUFArea(@Ano INT, @UF CHAR, @Area CHAR) RETURNS INT;
-- CREATE FUNCTION fnMecProjCaptaAnoUFAreaSituacao(@Ano INT, @UF CHAR, @Area CHAR, @Situacao CHAR) RETURNS INT;
-- CREATE FUNCTION fnMecProjCaptaAnoUFSegmento(@Ano INT, @UF CHAR, @Segmento CHAR) RETURNS INT;
-- CREATE FUNCTION fnMecProjLiberacaoAnoUFArea(@Ano INT, @UF CHAR, @Area CHAR) RETURNS INT;
-- CREATE FUNCTION fnMecProjPrestApresAnoUFArea(@Ano INT, @UF CHAR, @Area CHAR) RETURNS INT;
-- CREATE FUNCTION fnNome(@p_idAgente INT) RETURNS VARCHAR;
-- CREATE FUNCTION fnNomeArquivo(@pString VARCHAR) RETURNS VARCHAR;
-- CREATE FUNCTION fnNomeDoProponente(@p_idPronac INT) RETURNS VARCHAR;
-- CREATE FUNCTION fnNomeParecerista(@p_idUsuario INT) RETURNS VARCHAR;
-- CREATE FUNCTION fnNomeResponsavel(@p_idUsuario INT) RETURNS VARCHAR;
-- CREATE FUNCTION fnNomeTecnicoMinc(@p_idTecnico INT) RETURNS VARCHAR;
-- CREATE FUNCTION fnNomeUsuario(@p_idAgente INT) RETURNS VARCHAR;
-- CREATE FUNCTION fnNrConvenio(@p_ano CHAR, @p_seq VARCHAR) RETURNS VARCHAR;
-- CREATE FUNCTION fnNrPortaria(@AnoProjeto CHAR, @Sequencial VARCHAR, @Data timestamp) RETURNS VARCHAR;
-- CREATE FUNCTION fnNrPortariaAprovacao(@AnoProjeto CHAR, @Sequencial VARCHAR) RETURNS VARCHAR;
-- CREATE FUNCTION fnNrReuniao(@idPronac INT) RETURNS INT;
-- CREATE FUNCTION fnOrgaoOrigemProjetos(@codigo INT) RETURNS VARCHAR;
-- CREATE FUNCTION fnOutrasFontes(@p_idPronac INT) RETURNS MONEY;
-- CREATE FUNCTION fnParecerFavoravel(@idPronac INT) RETURNS int;
-- CREATE FUNCTION fnPassagemConcedida(@p_ano CHAR, @p_seq VARCHAR) RETURNS MONEY;
-- CREATE FUNCTION fnPassagemConcedidaAno(@Ano INT, @p_AnoProjeto CHAR, @p_Seq VARCHAR) RETURNS MONEY;
-- CREATE FUNCTION fnPegarUnidade(@NrProjeto VARCHAR) RETURNS VARCHAR;
-- CREATE FUNCTION fnPercentualCaptado(@p_ano CHAR, @p_seq VARCHAR) RETURNS MONEY;
-- CREATE FUNCTION fnProponenteAtivoVlAprovado(@CgcCpf VARCHAR) RETURNS MONEY;
-- CREATE FUNCTION fnProponenteAtivoVlCaptado(@CgcCpf VARCHAR) RETURNS MONEY;
-- CREATE FUNCTION fnProponenteAtivoVlSolicitado(@CgcCpf VARCHAR) RETURNS MONEY;
-- CREATE FUNCTION fnProponenteVlAprovado(@CgcCpf VARCHAR) RETURNS MONEY;
-- CREATE FUNCTION fnProponenteVlCaptado(@CgcCpf VARCHAR) RETURNS MONEY;
-- CREATE FUNCTION fnProponenteVlSolicitado(@CgcCpf VARCHAR) RETURNS MONEY;
-- CREATE FUNCTION fnQtdeApresAnoCidadeUF(@ANO INT, @UF CHAR, @CIDADE VARCHAR) RETURNS INT;
-- CREATE FUNCTION fnQtdeApresProponente(@p_CgcCpf VARCHAR) RETURNS INT;
-- CREATE FUNCTION fnQtdeApresUF(@p_UF CHAR) RETURNS INT;
-- CREATE FUNCTION fnQtdeAprovadoAnoArea(@Ano INT, @Area CHAR) RETURNS MONEY;
-- CREATE FUNCTION fnQtdeAprovAnoCidadeUF(@ANO INT, @UF CHAR, @CIDADE VARCHAR) RETURNS INT;
-- CREATE FUNCTION fnQtdeAprovProponente(@p_CgcCpf VARCHAR) RETURNS INT;
-- CREATE FUNCTION fnQtdeAprovUF(@p_uf CHAR) RETURNS INT;
-- CREATE FUNCTION fnQtdeCaptaAnoCidadeUF(@ANO INT, @UF CHAR, @CIDADE VARCHAR) RETURNS MONEY;
-- CREATE FUNCTION fnQtdeCaptadoProponente(@CgcCpf VARCHAR) RETURNS INT;
-- CREATE FUNCTION fnQtdeCaptouUF(@UF CHAR) RETURNS INT;
-- CREATE FUNCTION fnQtdeCustoAno(@Ano INT) RETURNS INT;
-- CREATE FUNCTION fnQtdeDeMesesDaUltimaCaptacao(@p_ano CHAR, @p_seq CHAR, @Getdate timestamp) RETURNS VARCHAR;
-- CREATE FUNCTION fnQtdeDiasAnaliseParecerista(@p_idPronac INT, @p_idProduto INT, @p_Tipo INT) RETURNS INT;
-- CREATE FUNCTION fnQtdeElaboracaoAgenciamento(@idPronac INT) RETURNS INT;
-- CREATE FUNCTION fnQtdeFNCProjApresAnoSegmento(@Ano INT, @Segmento CHAR) RETURNS INT;
-- CREATE FUNCTION fnQtdeFNCProjAprovAnoSegmento(@Ano INT, @Segmento CHAR) RETURNS INT;
-- CREATE FUNCTION fnQtdeMecProjApresAnoSegmento(@Ano INT, @Segmento CHAR) RETURNS INT;
-- CREATE FUNCTION fnQtdeMecProjApresAnoUFArea(@Ano INT, @UF CHAR, @Area CHAR) RETURNS INT;
-- CREATE FUNCTION fnQtdeMecProjAprovAnoSegmento(@Ano INT, @Segmento CHAR) RETURNS INT;
-- CREATE FUNCTION fnQtdeMecProjAprovAnoUfArea(@Ano INT, @UF CHAR, @Area CHAR) RETURNS INT;
-- CREATE FUNCTION fnQtdeMecProjAprovAnoUfSegmento(@Ano INT, @UF CHAR, @Segmento CHAR) RETURNS INT;
-- CREATE FUNCTION fnQtdeMecProjCaptaAnoSegmento(@Ano INT, @Segmento CHAR) RETURNS INT;
-- CREATE FUNCTION fnQtdeMecProjCaptaAnoUfArea(@Ano INT, @UF CHAR, @Area CHAR) RETURNS INT;
-- CREATE FUNCTION fnQtdeMecProjCaptaAnoUFAreaSituacao(@Ano INT, @UF CHAR, @Area CHAR, @Situacao CHAR) RETURNS INT;
-- CREATE FUNCTION fnQtdeMecProjCaptaAnoUfSegmento(@Ano INT, @UF CHAR, @Segmento CHAR) RETURNS INT;
-- CREATE FUNCTION fnQtdeMecProjLiberacaoAnoUfArea(@Ano INT, @UF CHAR, @Area CHAR) RETURNS INT;
-- CREATE FUNCTION fnQtdeMecProjPrestApresAnoUfArea(@Ano INT, @UF CHAR, @Area CHAR) RETURNS INT;
-- CREATE FUNCTION fnQtdeProjApresPassagemUF(@Ano INT, @UF CHAR) RETURNS INT;
-- CREATE FUNCTION fnQtdeProjCaptouAnoArea(@Ano INT, @Area CHAR) RETURNS MONEY;
-- CREATE FUNCTION fnQtdeProjCaptouAnoAreaMec(@Ano INT, @Area CHAR) RETURNS MONEY;
-- CREATE FUNCTION fnQtdeProjContemPassagemUF(@Ano INT, @UF CHAR) RETURNS INT;
-- CREATE FUNCTION fnQtdeProjetoApresentado(@Ano INT) RETURNS MONEY;
-- CREATE FUNCTION fnQtdeProjetoApresentadoArea(@Ano INT) RETURNS MONEY;
-- CREATE FUNCTION fnQtdeProjetoApresentadoMec(@Ano INT) RETURNS MONEY;
-- CREATE FUNCTION fnQtdeProjetoAprovado(@Ano INT) RETURNS MONEY;
-- CREATE FUNCTION fnQtdeProjetoAprovadoMec(@Ano INT) RETURNS MONEY;
-- CREATE FUNCTION fnQtdeProjetoCaptou(@Ano INT) RETURNS MONEY;
-- CREATE FUNCTION fnQtdeProjetoCaptouCid(@anoprojeto CHAR, @sequencial VARCHAR) RETURNS INT;
-- CREATE FUNCTION fnQtdeProjetoCaptouMec(@Ano INT) RETURNS MONEY;
-- CREATE FUNCTION fnQtdeProjetoCaptouMecAno(@Ano INT, @AnoProjeto CHAR, @Sequencial VARCHAR) RETURNS MONEY;
-- CREATE FUNCTION fnQtdeReciboProjeto(@Ano CHAR, @Seq VARCHAR) RETURNS INT;
-- CREATE FUNCTION fnQtdeRelatorioTrimestral(@p_idPronac INT) RETURNS INT;
-- CREATE FUNCTION fnQtdeVezesItemProjeto(@p_Item INT) RETURNS INT;
-- CREATE FUNCTION fnQtdeVezesItemProposta(@p_Item INT) RETURNS INT;
-- CREATE FUNCTION fnRemoveCaracteresEspeciais(@p_Campo VARCHAR) RETURNS VARCHAR;
-- CREATE FUNCTION fnRenunciaAnoArtigo(@Ano INT, @Artigo CHAR) RETURNS MONEY;
-- CREATE FUNCTION fnRenunciaAnoArtigoArea(@Ano INT, @Artigo CHAR, @Area CHAR) RETURNS MONEY;
-- CREATE FUNCTION fnRenunciaAnoArtigoUfTipoPessoaIncentivador(@Ano INT, @Artigo CHAR, @UF CHAR, @TipoPessoa CHAR) RETURNS MONEY;
-- CREATE FUNCTION fnRenunciaAnoChegada(@Ano INT) RETURNS MONEY;
-- CREATE FUNCTION fnRenunciaAnoIncentivador(@Ano VARCHAR, @CgcCpf VARCHAR) RETURNS MONEY;
-- CREATE FUNCTION fnRenunciaAnoMec(@Ano INT) RETURNS MONEY;
-- CREATE FUNCTION fnRenunciaAnoProjeto(@Ano INT, @AnoProjeto CHAR, @Sequencial VARCHAR) RETURNS MONEY;
-- CREATE FUNCTION fnRenunciaAnoReg(@Ano INT, @Regiao VARCHAR) RETURNS MONEY;
-- CREATE FUNCTION fnRenunciaAnoRegArea(@Ano INT, @Regiao VARCHAR, @Area CHAR) RETURNS MONEY;
-- CREATE FUNCTION fnRenunciaAnoRegAreaQtde(@Ano INT, @Regiao VARCHAR, @Area CHAR) RETURNS INT;
-- CREATE FUNCTION fnRenunciaAnoSeg(@Ano INT, @Seg VARCHAR) RETURNS MONEY;
-- CREATE FUNCTION fnRenunciaAnoSegArea(@Ano INT, @Seg VARCHAR, @area CHAR) RETURNS MONEY;
-- CREATE FUNCTION fnRenunciaAnoSegAreaMed(@Ano INT, @Seg VARCHAR, @area CHAR, @medida CHAR) RETURNS MONEY;
-- CREATE FUNCTION fnRenunciaAnoSegAreaSEFIC(@Ano INT, @Seg VARCHAR) RETURNS MONEY;
-- CREATE FUNCTION fnRenunciaAnoSegAreaSEFICMed(@Ano INT, @Seg VARCHAR, @medida CHAR) RETURNS MONEY;
-- CREATE FUNCTION fnRenunciaAnoUFArea(@Ano INT, @UF CHAR, @Area CHAR) RETURNS MONEY;
-- CREATE FUNCTION fnRenunciaAnoUFAreaQtde(@Ano INT, @Uf CHAR, @Area CHAR) RETURNS INT;
-- CREATE FUNCTION fnRenunciaAnoUFMec(@Ano INT, @UF CHAR) RETURNS MONEY;
-- CREATE FUNCTION fnRenunciaAreaAno(@Ano INT, @Area CHAR) RETURNS MONEY;
-- CREATE FUNCTION fnRenunciaAreaAnoMec(@Ano INT, @Area CHAR) RETURNS MONEY;
-- CREATE FUNCTION fnRenunciaAreaAteAnoMec(@Ano INT, @Area CHAR) RETURNS MONEY;
-- CREATE FUNCTION fnRenunciaAreaMec(@Area CHAR) RETURNS MONEY;
-- CREATE FUNCTION fnRenunciaAtivoProponente(@CgcCpf VARCHAR) RETURNS MONEY;
-- CREATE FUNCTION fnRenunciaProjeto(@AnoProjeto CHAR, @Sequencial VARCHAR) RETURNS MONEY;
-- CREATE FUNCTION fnRenunciaProponenteAnoMec(@Ano INT, @CgcCpf VARCHAR) RETURNS MONEY;
-- CREATE FUNCTION fnRenunciaUFMec(@UF CHAR) RETURNS MONEY;
-- CREATE FUNCTION fnSaldoDeEmpenho(@AnoProjeto CHAR, @Sequencial VARCHAR) RETURNS MONEY;
-- CREATE FUNCTION fnSaldoDeOrdemBancaria(@AnoProjeto CHAR, @Sequencial VARCHAR) RETURNS MONEY;
-- CREATE FUNCTION fnSelecionarArea(@p_idProjeto INT) RETURNS CHAR;
-- CREATE FUNCTION fnSelecionarSegmento(@p_idProjeto INT) RETURNS CHAR;
-- CREATE FUNCTION fnSolicitadoNaProposta(@p_idProjeto INT) RETURNS MONEY;
-- CREATE FUNCTION fnSolicitadoProponente(@p_CgcCpf VARCHAR) RETURNS MONEY;
-- CREATE FUNCTION fnTotalAprovadoAno(@Ano INT) RETURNS MONEY;
-- CREATE FUNCTION fnTotalAprovadoAnoArea(@Ano INT) RETURNS MONEY;
-- CREATE FUNCTION fnTotalAprovadoAnoMec(@Ano INT) RETURNS MONEY;
-- CREATE FUNCTION fnTotalAprovadoProjeto(@AnoProjeto CHAR, @Sequencial VARCHAR) RETURNS MONEY;
-- CREATE FUNCTION fnTotalCaptacao(@p_ano CHAR, @p_seq CHAR) RETURNS MONEY;
-- CREATE FUNCTION fnTotalCaptadoAno(@Ano INT) RETURNS MONEY;
-- CREATE FUNCTION fnTotalCaptadoAnoMec(@Ano INT) RETURNS MONEY;
-- CREATE FUNCTION fnTotalCaptadoProjeto(@AnoProjeto CHAR, @Sequencial VARCHAR) RETURNS MONEY;
-- CREATE FUNCTION fnTotalCaptadoProjetoAno(@Ano INT, @AnoProjeto CHAR, @Sequencial VARCHAR) RETURNS MONEY;
-- CREATE FUNCTION fnTotalCaptadoProponente(@CgcCpf VARCHAR) RETURNS MONEY;
-- CREATE FUNCTION fnTotalComplementacaoProjeto(@AnoProjeto CHAR, @Sequencial VARCHAR) RETURNS MONEY;
-- CREATE FUNCTION fnTotalCustoAno(@Ano INT) RETURNS MONEY;
-- CREATE FUNCTION fnTotalSolicitadoAno(@p_ano INT) RETURNS MONEY;
-- CREATE FUNCTION fnTotalSolicitadoAnoMec(@p_ano INT) RETURNS MONEY;
-- CREATE FUNCTION fnTotalSolicitadoFNCAnoSegmento(@p_ano INT, @_Segmento CHAR) RETURNS MONEY;
-- CREATE FUNCTION fnTotalSolicitadoMecAnoSegmento(@p_ano INT, @_Segmento CHAR) RETURNS MONEY;
-- CREATE FUNCTION fnUsuariosDoPerfil(@p_idPerfil INT, @p_idOrgao INT);
-- CREATE FUNCTION fnValApresAnoCidadeUF(@ANO INT, @UF CHAR, @CIDADE VARCHAR) RETURNS MONEY;
-- CREATE FUNCTION fnValAprovAnoCidadeUF(@ANO INT, @UF CHAR, @CIDADE VARCHAR) RETURNS MONEY;
-- CREATE FUNCTION fnValCaptaAnoCidadeUF(@ANO INT, @UF CHAR, @CIDADE VARCHAR) RETURNS MONEY;
-- CREATE FUNCTION fnValorAprovado(@AnoProjeto CHAR, @Sequencial VARCHAR) RETURNS MONEY;
-- CREATE FUNCTION fnValorAprovadoConvenio(@AnoProjeto CHAR, @Sequencial VARCHAR) RETURNS MONEY;
-- CREATE FUNCTION fnValorConvenio(@p_ano CHAR, @p_seq VARCHAR) RETURNS MONEY;
-- CREATE FUNCTION fnValorConvenioAno(@Ano INT, @p_AnoProjeto CHAR, @p_Seq VARCHAR) RETURNS MONEY;
-- CREATE FUNCTION fnValorDaProposta(@p_idProjeto INT) RETURNS MONEY;
-- CREATE FUNCTION fnValorElaboracaoAgenciamento(@p_idPronac INT) RETURNS MONEY;
-- CREATE FUNCTION fnValorEmpenho(@NrEmpenho VARCHAR) RETURNS MONEY;
-- CREATE FUNCTION fnValorSolicitado(@p_ano CHAR, @p_seq VARCHAR) RETURNS MONEY;
-- CREATE FUNCTION fnValorSolicitadoPropostaEdital(@idPreProjeto INT) RETURNS MONEY;
-- CREATE FUNCTION fnValorSugerido(@p_idPronac INT) RETURNS MONEY;
-- CREATE FUNCTION fnVerificarPermissao(@Acao int, @CNPJCPF_Proponente VARCHAR, @idUsuario_Logado INT, @idProjeto INT) RETURNS INTEGER;
-- CREATE FUNCTION fnVlComprovadoDocumento(@p_idPronac INT, @p_nrDocumento VARCHAR) RETURNS DECIMAL;
-- CREATE FUNCTION fnVlComprovadoEtapa(@p_idPronac INT, @p_idEtapa INT) RETURNS INT;
-- CREATE FUNCTION fnVlComprovadoItem(@p_idPronac INT, @p_idPlanilhaItem INT) RETURNS MONEY;
-- CREATE FUNCTION fnVlComprovadoProjeto(@p_idPronac INT) RETURNS DECIMAL;
-- CREATE PROCEDURE paAbrirReuniao();
-- CREATE PROCEDURE paAgente(@p_idPronac INT);
-- CREATE PROCEDURE paAjustarParecer();
-- CREATE PROCEDURE paArquivarProposta();
-- CREATE PROCEDURE paChecarLimitesOrcamentario(@idProjeto INT, @Fase CHAR);
-- CREATE PROCEDURE paChecarPrazoDiligencia();
-- CREATE PROCEDURE paChecklistDeEnvioDeCumprimentoDeObjeto(@idPronac INT);
-- CREATE PROCEDURE paChecklistDeEnvioDeProposta(@idProjeto INT);
-- CREATE PROCEDURE paChecklistSolicitacaoProrrogacaoPrazo(@idPronac INT, @DtInicio timestamp, @DtFim timestamp, @Acao CHAR);
-- CREATE PROCEDURE paCobrarEnvioRelatorioTrimestral(@idPronac INT);
-- CREATE PROCEDURE paConsolidarParecer();
-- CREATE PROCEDURE paConsolidarParecerOld(@idPronac VARCHAR);
-- CREATE PROCEDURE paConsolidarProjetoVotadoNaCNIC(@p_idPronac INT, @p_idNrReuniao INT, @p_NrReuniao VARCHAR, @p_tpResultado CHAR, @p_ResultadoVotacao CHAR, @p_ParecerConsolidado VARCHAR, @p_TipoAprovavao INTEGER, @p_Situacao CHAR, @p_tpConsolidacaoVotacao CHAR, @p_tpTipoReadequacao INT);
-- CREATE PROCEDURE paCoordenadorDoPerfil(@p_idPerfil INT, @p_idOrgao INT);
-- CREATE PROCEDURE paDesconsolidarLoteParecer();
-- CREATE PROCEDURE paDesconsolidarParecer(@idPronac INT);
-- CREATE PROCEDURE paDocumentos(@p_idPronac INT);
-- CREATE PROCEDURE paDocumentosAnexados(@p_idPronac INT);
-- CREATE PROCEDURE paEncerrarCNIC(@idNrReuniao INT);
-- CREATE PROCEDURE paGeraProcessoSalic(@p_usu INT, @p_orgao INT, @p_proc VARCHAR);
-- CREATE PROCEDURE paGravarProjeto(@AnoProjeto CHAR, @Seq VARCHAR, @UFProjeto CHAR, @Area CHAR, @Segmento CHAR, @NomeProjeto VARCHAR, @Processo VARCHAR, @CgcCpf VARCHAR, @Orgao INT, @Modalidade VARCHAR, @Analista VARCHAR, @Situacao CHAR, @ProvidenciaTomada VARCHAR, @ResumoProjeto VARCHAR, @Mecanismo CHAR, @SolicitadoReal MONEY, @SolicitadoCusteioReal MONEY, @SolicitadoCapitalReal MONEY, @Logon INT, @DtProtocolo VARCHAR);
-- CREATE PROCEDURE paIncluirProjetoNaPauta(@idPronac INT, @idParecer INT, @idUsuario INT);
-- CREATE PROCEDURE paIncluirRecusarItem(@p_idSolicitarItem INT, @p_idUsuario INT, @p_Opcao INTEGER);
-- CREATE PROCEDURE paMatarItem(@p_Velho INT, @p_Novo INT);
-- CREATE PROCEDURE paProponente(@p_nrProjeto VARCHAR);
-- CREATE PROCEDURE paPropostaParaProjeto(@idProposta INT, @CNPJCPF VARCHAR, @idOrgao INT, @idUsuario INT, @Processo VARCHAR);
-- CREATE PROCEDURE paPropostaParaProjeto_ANT(@idProposta INT, @CNPJCPF VARCHAR, @idOrgao INT, @idUsuario INT);
-- CREATE PROCEDURE paRastrearAgente(@CNPJCPF VARCHAR);
-- CREATE PROCEDURE paReenviarProjetoComponente(@Pronac VARCHAR);
-- CREATE PROCEDURE paRegularidade(@CgcCpf VARCHAR);
-- CREATE PROCEDURE paTransformarPropostaEmProjeto(@idProposta INT, @CNPJCPF VARCHAR, @idOrgao INT, @idUsuario INT, @Processo VARCHAR);
-- CREATE PROCEDURE paTrocarItem(@p_Velho INT, @p_Novo INT);
-- CREATE PROCEDURE paUsuariosDoPerfil(@p_idPerfil INT, @p_idOrgao INT);
-- CREATE PROCEDURE paVerificarAtualizarSituacaoAprovacao(@idPronac INT);
-- CREATE PROCEDURE paVoltarProjetoFinalizadoComponente(@PRONAC VARCHAR);
-- CREATE FUNCTION Ponderada(@idPlanilhaItem INT) RETURNS INT;
-- CREATE PROCEDURE Reindexar();
-- CREATE PROCEDURE sAberturaDeContaSav();
-- CREATE PROCEDURE sAberturaDeContaSeficBloqueada();
-- CREATE PROCEDURE sAberturaDeContaSeficLivre();
-- CREATE PROCEDURE sAcaoProjeto(@Entidade INT, @Pronac VARCHAR);
-- CREATE PROCEDURE sAcaoProjetoFund(@Entidade INT);
-- CREATE PROCEDURE sAcertarSituacao();
-- CREATE PROCEDURE sAcertarSituacaoFnc();
-- CREATE PROCEDURE sAcharNumeroVago(@primeiro INT, @ultimo INT, @ano CHAR, @qtde INT);
-- CREATE PROCEDURE sAguardandoPortaria(@Orgao INT);
-- CREATE PROCEDURE sAndamentoProjeto(@Pronac VARCHAR);
-- CREATE PROCEDURE sApagarAprovacao(@AnoProjeto CHAR, @Sequencial VARCHAR, @TipoAprovacao CHAR, @DtAprovacao timestamp, @DtInicioCaptacao timestamp, @NrPortaria VARCHAR, @Logon INT);
-- CREATE PROCEDURE sApagarProrExercAnterior();
-- CREATE PROCEDURE sApoioPorAnoAreaMes(@Ano INT);
-- CREATE PROCEDURE sApoioPorAnoAreaSegmento(@Ano INT);
-- CREATE PROCEDURE sApoioPorAnoAreaTrimestre(@Ano INT);
-- CREATE PROCEDURE sApoioPorAnoRegiaoUFMes(@Ano INT);
-- CREATE PROCEDURE sApoioUFMunicipio(@UF CHAR, @Municipio VARCHAR, @Ano INT);
-- CREATE PROCEDURE sAproCapMecCidade();
-- CREATE PROCEDURE sAproCapMecenato(@Orgao INT);
-- CREATE PROCEDURE sAproCapRenRegiaoUF(@Ano INT);
-- CREATE PROCEDURE sAproCapRenuncia(@Ano INT);
-- CREATE PROCEDURE sAprovacao(@Pronac VARCHAR);
-- CREATE PROCEDURE sAprovadoChecarHabilitacao();
-- CREATE PROCEDURE sAprovadoCNIC(@NrReuniao VARCHAR);
-- CREATE PROCEDURE sArquivadoIrregularmente(@Area CHAR);
-- CREATE PROCEDURE sArquivarProjeto(@Logon INT, @Orgao INT);
-- CREATE PROCEDURE sAtivarDiagnostico();
-- CREATE PROCEDURE sAtivarDiagnosticoFnc();
-- CREATE PROCEDURE sAtivarRotinas();
-- CREATE PROCEDURE sAtualizaReuniaoCnic(@NrReuniao SMALLINT, @DtReuniao timestamp, @Logon INT);
-- CREATE PROCEDURE sAudiovisual(@Pronac VARCHAR);
-- CREATE PROCEDURE sAuditores(@CgcCpf VARCHAR);
-- CREATE PROCEDURE sAuditoresAreaUF(@Area CHAR);
-- CREATE PROCEDURE sAvaliarProjeto();
-- CREATE PROCEDURE sAvaliarProponente();
-- CREATE PROCEDURE sBancoProjeto(@NrProjeto VARCHAR);
-- CREATE PROCEDURE sBandaAnoRegiaoUFCidade(@Ano INT, @UF CHAR);
-- CREATE PROCEDURE sBandaAnoRegiaoUFMunicipio(@Ano INT, @UF CHAR);
-- CREATE PROCEDURE sBandaPorAnoRegiao(@Ano INT);
-- CREATE PROCEDURE sBandaPorAnoRegiaoUF(@Ano INT);
-- CREATE PROCEDURE sBolsaAnoArea(@Ano INT);
-- CREATE PROCEDURE sBolsaPorAnoAreaSegmento(@Ano INT);
-- CREATE PROCEDURE sBolsaPorAnoRegiao(@Ano INT);
-- CREATE PROCEDURE sBolsaPorAnoRegiaoUF(@Ano INT);
-- CREATE PROCEDURE sCapRenunciaAnoArea(@Ano INT);
-- CREATE PROCEDURE sCapRenunciaArea();
-- CREATE PROCEDURE sCapRenunciaChegadaRecibo();
-- CREATE PROCEDURE sCaptacaoRenunciaPrivadoAno();
-- CREATE PROCEDURE sCaptacaoRenunciaPrivadoAnoArea(@Ano INT);
-- CREATE PROCEDURE sCaptacaoRenunciaPrivadoAnoRegiao(@Ano INT);
-- CREATE PROCEDURE sCaptacaoRenunciaPrivadoAnoUf(@Ano INT);
-- CREATE PROCEDURE sCaptacaoRenunciaPrivadoArea();
-- CREATE PROCEDURE sCaptacaoRenunciaPrivadoRegiao();
-- CREATE PROCEDURE sCaptacaoRenunciaPrivadoUf();
-- CREATE PROCEDURE sCaptadoAnoArea(@Ano INT);
-- CREATE PROCEDURE sCaptadoAnoTrimestre();
-- CREATE PROCEDURE sCaptadoMunicipio(@Municipio VARCHAR);
-- CREATE PROCEDURE sCaptadoPorAnoAreaSegmento(@Ano INT);
-- CREATE PROCEDURE sCaptadoPorAnoRegiao(@Ano INT);
-- CREATE PROCEDURE sCaptadoPorAnoRegiaoUF(@Ano INT);
-- CREATE PROCEDURE sCaptadoRegiao();
-- CREATE PROCEDURE sCartasEnviadas(@Pronac VARCHAR);
-- CREATE PROCEDURE sCartasNaoEmitidas(@Numero VARCHAR, @Orgao INT);
-- CREATE PROCEDURE sCertidaoNegativa(@CgcCpf VARCHAR);
-- CREATE PROCEDURE sChecarCertidoesNegativas();
-- CREATE PROCEDURE sChecarInabilitacao();
-- CREATE PROCEDURE sCinematografia(@Metragem INT, @Fase INT, @Genero INT);
-- CREATE PROCEDURE sClassProjeto();
-- CREATE PROCEDURE sClassProponente();
-- CREATE PROCEDURE sComparativoAnoUF(@Ano INT);
-- CREATE PROCEDURE sConsulta(@Pronac VARCHAR, @UF VARCHAR, @NomeProjeto VARCHAR, @Ano VARCHAR);
-- CREATE PROCEDURE sConsultaProjeto(@Opcao int, @Campo_1 VARCHAR, @Campo_2 VARCHAR);
-- CREATE PROCEDURE sConsultaPronac(@AnoProjeto CHAR, @Sequencial VARCHAR);
-- CREATE PROCEDURE sConsultaRegularidade(@CgcCpf VARCHAR);
-- CREATE PROCEDURE sConsultaUf(@Uf CHAR);
-- CREATE PROCEDURE sConvenio(@Pronac VARCHAR);
-- CREATE PROCEDURE sConvenioAno();
-- CREATE PROCEDURE sConvenioAnoArea(@Ano INT);
-- CREATE PROCEDURE sConvenioAnoAreaSegmento(@Ano INT);
-- CREATE PROCEDURE sConvenioAnoRegiaoUFCidade(@Ano INT, @UF CHAR);
-- CREATE PROCEDURE sConvenioArea();
-- CREATE PROCEDURE sConvenioPorAnoRegiao(@Ano INT);
-- CREATE PROCEDURE sConvenioPorAnoRegiaoUF(@Ano INT);
-- CREATE PROCEDURE sConvenioRegiao();
-- CREATE PROCEDURE sConvenioRegiaoArea();
-- CREATE PROCEDURE sConvenioUf();
-- CREATE PROCEDURE sDadosBasicosProjeto(@Pronac VARCHAR);
-- CREATE PROCEDURE sDadosDaBanda(@CgcCpf VARCHAR);
-- CREATE PROCEDURE sDeclaracaoDeIncentivo(@CgcCpf VARCHAR);
-- CREATE PROCEDURE sDetalheDeCinematografia(@Pronac VARCHAR);
-- CREATE PROCEDURE sDetalheProjetoConveniado(@AnoProjeto CHAR, @Sequencial VARCHAR);
-- CREATE PROCEDURE sDetalheProjetoPassagem(@AnoProjeto CHAR, @Sequencial VARCHAR);
-- CREATE PROCEDURE sDezMaioresIncentivadoresUF(@Ano INT);
-- CREATE PROCEDURE sDiligProrNaoAtendida();
-- CREATE PROCEDURE sDocumentosPendentes(@Pronac VARCHAR);
-- CREATE PROCEDURE sDocumentosPendentes_1(@AnoProjeto CHAR, @Sequencial VARCHAR);
-- CREATE PROCEDURE sEnquadramento(@Pronac VARCHAR);
-- CREATE PROCEDURE sFimPrazoCaptacao(@Situacao CHAR);
-- CREATE PROCEDURE sGerarCartas(@Situacao CHAR, @Flag INT);
-- CREATE PROCEDURE sGravarContaCorrenteBBNaTabelaContaBancaria();
-- CREATE PROCEDURE sGravarContaCorrenteBBNaTabelaContaBancariaSav();
-- CREATE PROCEDURE sGuiaFunarte();
-- CREATE PROCEDURE sGuiaNaoVinculada();
-- CREATE PROCEDURE sHabilitarProjeto();
-- CREATE PROCEDURE sInabilitarProponente();
-- CREATE PROCEDURE sIncAlfabetica(@Ano INT, @TipoPessoa CHAR, @Quantidade INT);
-- CREATE PROCEDURE sIncentivadorAnoRegiaoUF(@Ano INT);
-- CREATE PROCEDURE sIncentivadoresGrupoAnoArea(@Ano INT);
-- CREATE PROCEDURE sIncentivadoresSeusProjetosAnoRegiaoUF(@CgcCpf VARCHAR, @Ano INT);
-- CREATE PROCEDURE sIncentivadoresUFMunicipio(@UF CHAR, @Municipio VARCHAR, @Ano INT);
-- CREATE PROCEDURE sIncentivadorPorAnoRegiao(@Ano INT);
-- CREATE PROCEDURE sIncentivadorSeusProjetos(@CgcCpf VARCHAR);
-- CREATE PROCEDURE sIncentivadorSeusProjetosAno(@CgcCpf VARCHAR, @Ano INT);
-- CREATE PROCEDURE sIncentivadorSeusProjetosAnoSituacao(@CgcCpf VARCHAR, @Ano INT);
-- CREATE PROCEDURE sIncentivosEstataisAnoAreaMec(@Ano INT);
-- CREATE PROCEDURE sIncentivosEstataisAnoGrupoMec();
-- CREATE PROCEDURE sIncentivosEstataisAnoUFMec(@Ano INT);
-- CREATE PROCEDURE sIncentivosEstataisAreaMec();
-- CREATE PROCEDURE sIncentivosEstataisMec();
-- CREATE PROCEDURE sIncentivosEstataisMecenatoAnoArea(@Ano INT);
-- CREATE PROCEDURE sIncentivosEstataisMecenatoAnoAreaSegmento(@Ano INT);
-- CREATE PROCEDURE sIncentivosEstataisMecenatoAnoRegiaoUF(@Ano INT);
-- CREATE PROCEDURE sIncentivosEstataisNomeMec();
-- CREATE PROCEDURE sIncentivosEstataisRegiaoMec();
-- CREATE PROCEDURE sIncluirPedido(@Pronac VARCHAR, @DtPedido VARCHAR, @DtInicio VARCHAR, @DtFinal VARCHAR);
-- CREATE PROCEDURE sIncPesAnoArea(@Ano INT, @Area CHAR, @Quantidade INT, @TipoPessoa CHAR);
-- CREATE PROCEDURE sIncPesAnoAreaSegmento(@Ano INT, @Area CHAR, @Segmento CHAR, @Quantidade INT, @TipoPessoa CHAR);
-- CREATE PROCEDURE sIncPesGeralAnoAreaSegmento(@Ano INT, @Area CHAR, @Segmento CHAR, @Quantidade INT, @TipoPessoa CHAR);
-- CREATE PROCEDURE sIncPesJuridicaAnoArea(@Ano INT, @Area CHAR, @Quantidade INT, @TipoPessoa CHAR);
-- CREATE PROCEDURE sInformacaoCaptacaoAnoArea(@Ano INT, @Area VARCHAR);
-- CREATE PROCEDURE sInformacaoConvenioAnoArea(@Ano INT, @Area VARCHAR);
-- CREATE PROCEDURE sInformacaoDaBanda(@CgcCpf VARCHAR);
-- CREATE PROCEDURE sInformacaoDoConveniado(@Conveniado VARCHAR);
-- CREATE PROCEDURE sInformacaoDoMunicipio(@Municipio VARCHAR, @UF CHAR);
-- CREATE PROCEDURE sInformacaoIncentivadores(@Incentivador VARCHAR, @Ano INT);
-- CREATE PROCEDURE sInformacaoParaReceita(@Ano INT);
-- CREATE PROCEDURE sInformacaoPassagemAnoArea(@Ano INT, @Area VARCHAR);
-- CREATE PROCEDURE sInformacaoProjeto(@Projeto VARCHAR);
-- CREATE PROCEDURE sInformacaoProponente(@Proponente VARCHAR);
-- CREATE PROCEDURE sInformacaoReceitaFederal(@Ano INT);
-- CREATE PROCEDURE sInformacaoReceitaFederal_nova(@Ano INT);
-- CREATE PROCEDURE sInformacaoReceitaFederalV3(@Ano INT);
-- CREATE PROCEDURE sInformacaoReceitaFederalxx(@Ano INT);
-- CREATE PROCEDURE sInserirRecibosDeCaptacao(@AnoProjeto CHAR, @Sequencial VARCHAR);
-- CREATE PROCEDURE sInserirRecibosDeCaptacao2009();
-- CREATE PROCEDURE sInserirRecibosDeCaptacao2010();
-- CREATE PROCEDURE sInstitutos(@Quant INT);
-- CREATE PROCEDURE sInstrumentoDaBanda(@CgcCpf VARCHAR);
-- CREATE PROCEDURE sInternet();
-- CREATE PROCEDURE sIntranet();
-- CREATE PROCEDURE sInvestEmpresaPublicaAno(@Ano INT);
-- CREATE PROCEDURE sInvestimentoMincAno(@Ano INT);
-- CREATE PROCEDURE sInvestimentoMincAnoArea(@Ano INT);
-- CREATE PROCEDURE sInvestimentoMincAnoAreaSeg(@Ano INT);
-- CREATE PROCEDURE sInvestimentoMincAnoMec(@Ano INT);
-- CREATE PROCEDURE sInvestimentoMincAnoMecanismo(@Ano INT);
-- CREATE PROCEDURE sInvestimentoMincAnoMod(@Ano INT);
-- CREATE PROCEDURE sInvestimentoMincAnoRegiao(@Ano INT);
-- CREATE PROCEDURE sInvestimentoMincAnoRegiaoUF(@Ano INT);
-- CREATE PROCEDURE sInvestimentoMincAnoRegioUF(@Ano INT);
-- CREATE PROCEDURE sInvestimentoMincAnoUF(@Ano INT);
-- CREATE PROCEDURE sLiberacaoConta(@Pronac VARCHAR);
-- CREATE PROCEDURE sLocalizacaoDeProcesso(@Processo CHAR);
-- CREATE PROCEDURE sMaioresInc(@Ano INT, @TipoPessoa CHAR, @Quantidade INT);
-- CREATE PROCEDURE sMaioresIncAnoTipoPessoaQtde(@Ano INT, @TipoPessoa CHAR, @Quantidade INT);
-- CREATE PROCEDURE sMaioresIncDadosCadastrais(@Ano INT, @Quant INT);
-- CREATE PROCEDURE sMaioresIncentivadores(@Ano INT, @Quant INT);
-- CREATE PROCEDURE sMaioresIncentivadoresArt1(@Ano INT, @Area CHAR, @Segmento CHAR, @Quantidade INT, @TipoPessoa CHAR);
-- CREATE PROCEDURE sMaioresIncentivadoresArt3(@Ano INT, @Area CHAR, @Segmento CHAR, @Quantidade INT, @TipoPessoa CHAR);
-- CREATE PROCEDURE sMaioresIncentivadoresMA(@Ano INT, @Area CHAR, @Segmento CHAR, @Quantidade INT, @TipoPessoa CHAR);
-- CREATE PROCEDURE sMaioresIncentivadoresProjetos(@Ano INT, @Area CHAR, @Segmento CHAR);
-- CREATE PROCEDURE sMaioresIncentivadoresProjetosMA(@Ano INT, @Area CHAR, @Segmento CHAR, @Quantidade INT, @TipoPessoa CHAR);
-- CREATE PROCEDURE sMaioresIncentivosProjetosMec(@Quant INT);
-- CREATE PROCEDURE sMaioresIncentivosProponenteMec(@Quant INT);
-- CREATE PROCEDURE sMaioresIncProjArt1(@Ano INT, @Quantidade INT, @Area CHAR, @Segmento CHAR);
-- CREATE PROCEDURE sMaioresIncRegiaoUf(@Ano INT, @TipoPessoa CHAR, @Quantidade INT);
-- CREATE PROCEDURE sMaioresIncUf(@Ano INT, @TipoPessoa CHAR, @Quantidade INT);
-- CREATE PROCEDURE sMaioresIncUfArt1(@Ano INT, @TipoPessoa CHAR, @Quantidade INT);
-- CREATE PROCEDURE sMaioresIncUfArt3(@Ano INT, @TipoPessoa CHAR, @Quantidade INT);
-- CREATE PROCEDURE sMaioresIncUfEndereco(@Ano INT, @Quantidade INT);
-- CREATE PROCEDURE sMaioresIncUfMA(@Ano INT, @TipoPessoa CHAR, @Quantidade INT);
-- CREATE PROCEDURE sMaioresProjetos(@Ano INT, @Quant INT);
-- CREATE PROCEDURE sMaioresProjetosFnc(@Quant INT);
-- CREATE PROCEDURE sMaioresProjetosMecenato(@Quant INT);
-- CREATE PROCEDURE sMaioresProjetosPorRegiaoUF(@Ano INT, @Quant INT);
-- CREATE PROCEDURE sMaioresProjInc(@Ano INT, @Quantidade INT, @Area CHAR, @Segmento CHAR);
-- CREATE PROCEDURE sMaioresProjIncArt1(@Ano INT, @Quantidade INT, @Area CHAR, @Segmento CHAR);
-- CREATE PROCEDURE sMaioresProjIncArt3(@Ano INT, @Quantidade INT, @Area CHAR, @Segmento CHAR);
-- CREATE PROCEDURE sMaioresProponenteProjetos(@Ano INT, @Area CHAR, @Segmento CHAR);
-- CREATE PROCEDURE sMaioresProponentes(@Ano INT, @Quant INT);
-- CREATE PROCEDURE sMaioresProponentesFnc(@Ano INT, @Quant INT);
-- CREATE PROCEDURE sMaioresProponentesMecenato(@Ano INT, @Quant INT);
-- CREATE PROCEDURE sMaioresPropProjArt1(@Ano INT, @Area CHAR, @Segmento CHAR);
-- CREATE PROCEDURE sMaioresPropProjArt3(@Ano INT, @Area CHAR, @Segmento CHAR);
-- CREATE PROCEDURE sMaioresPropProjMA(@Ano INT, @Area CHAR, @Segmento CHAR);
-- CREATE PROCEDURE sMaiorProjIncEstataisAnoMec(@Ano INT, @Quant INT);
-- CREATE PROCEDURE sModalidade(@Area CHAR, @Modalidade VARCHAR);
-- CREATE PROCEDURE sModalidadeAudiovisual(@Modalidade VARCHAR);
-- CREATE PROCEDURE sMudarSituacao(@Situacao CHAR);
-- CREATE PROCEDURE sOpcaoConsulta(@Opcao int, @Campo VARCHAR);
-- CREATE PROCEDURE sp_alterdiagram(@diagramname SYSNAME, @owner_id INT, @version INT, @definition bit);
-- CREATE PROCEDURE sp_creatediagram(@diagramname SYSNAME, @owner_id INT, @version INT, @definition bit);
-- CREATE PROCEDURE sp_dropdiagram(@diagramname SYSNAME, @owner_id INT);
-- CREATE PROCEDURE sp_helpdiagramdefinition(@diagramname SYSNAME, @owner_id INT);
-- CREATE PROCEDURE sp_helpdiagrams(@diagramname SYSNAME, @owner_id INT);
-- CREATE PROCEDURE sp_renamediagram(@diagramname SYSNAME, @owner_id INT, @new_diagramname SYSNAME);
-- CREATE PROCEDURE sp_upgraddiagrams();
-- CREATE PROCEDURE sPagamentoDoProjeto(@NrProjeto VARCHAR);
-- CREATE PROCEDURE spalimentatcu1();
-- CREATE PROCEDURE spalimentatcu2();
-- CREATE PROCEDURE sParecerTecnico(@Pronac VARCHAR);
-- CREATE PROCEDURE sParecerTecnicoVencido(@Orgao INT);
-- CREATE PROCEDURE sPassagemAnoArea(@Ano INT);
-- CREATE PROCEDURE sPassagemPorAnoAreaSegmento(@Ano INT);
-- CREATE PROCEDURE sPassagemPorAnoRegiao(@Ano INT);
-- CREATE PROCEDURE sPassagemPorAnoRegiaoUF(@Ano INT);
-- CREATE PROCEDURE spAtivarPlanilhaOrcamentaria(@idPronac INT);
-- CREATE PROCEDURE spCarregarMovimentacaoBancaria();
-- CREATE PROCEDURE spCnicPorLocalDeRealizacao(@p_Parametro int);
-- CREATE PROCEDURE spDadosBancario(@p_idPronac INT);
-- CREATE PROCEDURE spDepositoIdentificadoCaptacao();
-- CREATE PROCEDURE sPedidoProrrogacao(@Ano INT, @Orgao INT);
-- CREATE PROCEDURE spEnviarEmailCNIC(@idNrReuniao INT);
-- CREATE PROCEDURE spEnviarEmailPorProjeto(@idPronac INT, @idTextoEmail INT);
-- CREATE PROCEDURE spExtratoBancario(@p_idPronac INT);
-- CREATE PROCEDURE spExtratorQuestionario(@p_idEdital INT);
-- CREATE PROCEDURE spFechamentoCNIC(@idNrReuniao INT);
-- CREATE PROCEDURE spGeraDependencias();
-- CREATE PROCEDURE spGerarAprovacaoCNIC(@idNrReuniao INT);
-- CREATE PROCEDURE spGerarAprovacaoRecursoCNIC(@idNrReuniao INT);
-- CREATE PROCEDURE spItemOrcamentarioCustoMedio(@idProduto INT, @idPlanilhaItem INT, @idUnidade INT, @idUF INT);
-- CREATE PROCEDURE sPortariaNr(@NrPortaria VARCHAR);
-- CREATE PROCEDURE sPortarias(@Pronac VARCHAR);
-- CREATE PROCEDURE spPedidoProrrogacao();
-- CREATE PROCEDURE spPegarDadosCadastraisRFBGravarBancoAgentes(@p_CPFCNPJ VARCHAR, @p_Visao INT);
-- CREATE PROCEDURE spPegarDadosCadastraisRFBGravarBancoInteressado(@p_CPFCNPJ VARCHAR);
-- CREATE PROCEDURE spPlanilhaOrcamentaria(@idPronac INT, @TipoPlanilha CHAR);
-- CREATE PROCEDURE spPrepararPagamentoParecerista(@idNrReuniao INT);
-- CREATE PROCEDURE spQuantidadeRegistros();
-- CREATE PROCEDURE sProdutoProjeto(@CodigoProduto INT);
-- CREATE PROCEDURE sProdutoProjetoFund(@CodigoProduto INT);
-- CREATE PROCEDURE sProjetoParametro(@Ano INT, @Entidade VARCHAR, @Mecanismo CHAR, @UF VARCHAR, @Area CHAR);
-- CREATE PROCEDURE sProjetoPorNome(@Nome VARCHAR);
-- CREATE PROCEDURE sProjetoProduto(@Pronac VARCHAR);
-- CREATE PROCEDURE sProjetosDosMaioresIncAnoTipoPessoaQtde(@Ano INT, @CgcCpf VARCHAR);
-- CREATE PROCEDURE sProponente(@CgcCpf VARCHAR);
-- CREATE PROCEDURE sProponenteClassificado(@Classificacao CHAR);
-- CREATE PROCEDURE sProponenteProjetos(@CgcCpf VARCHAR);
-- CREATE PROCEDURE sProponenteSeusProjetos(@CgcCpf VARCHAR);
-- CREATE PROCEDURE sProrNaoAtenVenceuExerc();
-- CREATE PROCEDURE sProrrogacaoAutomatica();
-- CREATE PROCEDURE spSalicnew();
-- CREATE PROCEDURE spSelecionarParecerista(@p_Unidade INT, @p_Area CHAR, @p_Segmento CHAR, @p_vlProduto MONEY);
-- CREATE PROCEDURE spSelecionarPlanilhaOrcamentariaAtiva(@idPronac INT);
-- CREATE PROCEDURE spSubirArquivoDEB705();
-- CREATE PROCEDURE spTipoDeReadequacaoOrcamentaria(@idPronac INT);
-- CREATE PROCEDURE spu_coluna(@objectname VARCHAR);
-- CREATE PROCEDURE spValidarApresentacaoDeProjeto(@idProjeto INT);
-- CREATE PROCEDURE spValidarApresentacaoDeProjetoOld(@idProjeto INT);
-- CREATE PROCEDURE spValidarDepositoIdentificado(@p_UsuarioLogado INT);
-- CREATE PROCEDURE spValorItemProjeto();
-- CREATE PROCEDURE sQtdApresentados(@Ano INT);
-- CREATE PROCEDURE sQtdAprovados(@Ano INT);
-- CREATE PROCEDURE sQtdeProjetoCaptou();
-- CREATE PROCEDURE sQtdProrrogados(@Ano INT);
-- CREATE PROCEDURE sRecalculaDataDeParcelas(@AnoProjeto CHAR, @Sequencial VARCHAR);
-- CREATE PROCEDURE sRegularidade(@CgcCpf VARCHAR);
-- CREATE PROCEDURE sRelatorioDoProjeto(@NrProjeto VARCHAR);
-- CREATE PROCEDURE sRenomearRetornoBB();
-- CREATE PROCEDURE sRenunciaAnoRegiaoUF(@Ano INT);
-- CREATE PROCEDURE sRenunciaPorAnoRegiao(@Ano INT);
-- CREATE PROCEDURE sResTotalAvaliacaoProj(@AnoProjeto CHAR, @Sequencial VARCHAR);
-- CREATE PROCEDURE sResTotalAvaliacaoProp(@CgcCpf VARCHAR);
-- CREATE PROCEDURE sResultadoAvaliacaoProj(@AnoProjeto CHAR, @Sequencial VARCHAR);
-- CREATE PROCEDURE sResultadoAvaliacaoProp(@CgcCpf VARCHAR);
-- CREATE PROCEDURE sResultadoCnicArea(@NrReuniao INT);
-- CREATE PROCEDURE sResultadoCnicRegiaoUF(@NrReuniao INT);
-- CREATE PROCEDURE sRetiradosDePauta(@Orgao INT);
-- CREATE PROCEDURE sSituacoesDoProjeto(@NrProjeto VARCHAR);
-- CREATE PROCEDURE sValidadeDeCertidoesNegativas(@CgcCpf VARCHAR, @AnoProjeto CHAR, @Sequencial VARCHAR);
-- CREATE PROCEDURE sValidarSituacao(@AnoProjeto CHAR, @Sequencial VARCHAR);
-- CREATE PROCEDURE sVerificarRegularidade(@CgcCpf VARCHAR);
-- CREATE PROCEDURE sVerificarSaldoMec();
-- CREATE PROCEDURE sVerificaValidadeCertidaoNegativa(@CgcCpf VARCHAR);
-- CREATE PROCEDURE sVigenciaConvenio();
-- CREATE PROCEDURE sVoltarProjetoParaPauta();