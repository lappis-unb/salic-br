-- noinspection SqlDialectInspectionForFile
-- DROP SCHEMA tabelas CASCADE;
-- CREATE SCHEMA tabelas;
-- ROLLBACK;
-- BEGIN;
-- COMMIT;

CREATE SCHEMA IF NOT EXISTS tabelas AUTHORIZATION postgres;
CREATE TABLE tabelas.Sistemas
(
  sis_codigo SMALLINT PRIMARY KEY NOT NULL,
  sis_sigla VARCHAR(16),
  sis_nome VARCHAR(60) NOT NULL,
  sis_status SMALLINT NOT NULL,
  sis_seguranca CHAR(8) NOT NULL,
  sis_url VARCHAR(150),
  sis_controle TIMESTAMP NOT NULL
);
CREATE TABLE tabelas.Tipos_Pessoa
(
  tpe_codigo SMALLINT PRIMARY KEY NOT NULL,
  tpe_descricao VARCHAR(100) NOT NULL,
  tpe_pf_pj SMALLINT NOT NULL,
  tpe_direito SMALLINT NOT NULL,
  tpfnEncriptaSenhae_fim SMALLINT NOT NULL,
  tpe_status SMALLINT NOT NULL
);
CREATE UNIQUE INDEX sis_IDX_sigla ON tabelas.Sistemas (sis_sigla);
CREATE TABLE tabelas.Autorizados
(
  aut_identificacao CHAR(11) NOT NULL,
  aut_procedure VARCHAR(30) NOT NULL,
  aut_operacao VARCHAR(20) NOT NULL,
  aut_codigo INT NOT NULL,
  aut_status SMALLINT NOT NULL,
  CONSTRAINT PK_Autorizados PRIMARY KEY (aut_identificacao, aut_procedure, aut_operacao, aut_codigo)
);
CREATE TABLE tabelas.Avisos
(
  avi_destino SMALLINT NOT NULL,
  avi_codigo SMALLINT NOT NULL,
  avi_tipo SMALLINT NOT NULL,
  avi_data_inicio DATE,
  avi_data_limite DATE,
  avi_texto TEXT NOT NULL,
  CONSTRAINT avi_PK_descod PRIMARY KEY (avi_destino, avi_codigo)
);
CREATE TABLE tabelas.Bairros
(
  bai_codigo INT PRIMARY KEY NOT NULL,
  bai_localidade INT NOT NULL,
  bai_nome VARCHAR(60) NOT NULL,
  bai_status SMALLINT NOT NULL
);
CREATE INDEX bai_IDX_localidade ON tabelas.Bairros (bai_localidade);
CREATE TABLE tabelas.Cadastro
(
  Nome VARCHAR(80),
  Lotacao VARCHAR(100),
  Telefone CHAR(10),
  EMail VARCHAR(71)
);
CREATE TABLE tabelas.Categorias_Pessoa
(
  ctp_codigo SMALLINT PRIMARY KEY NOT NULL,
  ctp_descricao VARCHAR(100) NOT NULL,
  ctp_orgao_gerente SMALLINT,
  ctp_status SMALLINT NOT NULL
);
CREATE TABLE tabelas.Pessoas
(
  pes_codigo INT PRIMARY KEY NOT NULL,
  pes_categoria SMALLINT NOT NULL,
  pes_tipo SMALLINT NOT NULL,
  pes_esfera SMALLINT NOT NULL,
  pes_administracao SMALLINT NOT NULL,
  pes_utilidade_publica SMALLINT NOT NULL,
  pes_superior INT,
  pes_validade SMALLINT NOT NULL,
  pes_orgao_cadastrador SMALLINT NOT NULL,
  pes_usuario_cadastrador SMALLINT NOT NULL,
  pes_data_cadastramento DATE NOT NULL,
  pes_orgao_atualizador SMALLINT,
  pes_usuario_atualizador SMALLINT,
  pes_data_atualizacao TIMESTAMP,
  pes_controle TIMESTAMP NOT NULL,
  CONSTRAINT FK_Pessoas_Categorias_Pessoa FOREIGN KEY (pes_categoria) REFERENCES tabelas.Categorias_Pessoa (ctp_codigo),
  CONSTRAINT FK_Pessoas_Tipos_Pessoa FOREIGN KEY (pes_tipo) REFERENCES tabelas.Tipos_Pessoa (tpe_codigo)
);
CREATE TABLE tabelas.Usuarios
(
  usu_codigo SMALLINT PRIMARY KEY NOT NULL,
  usu_identificacao CHAR(16) NOT NULL,
  usu_nome VARCHAR(20) NOT NULL,
  usu_pessoa INT NOT NULL,
  usu_orgao SMALLINT,
  usu_sala VARCHAR(20),
  usu_ramal SMALLINT,
  usu_nivel SMALLINT NOT NULL,
  usu_exibicao CHAR NOT NULL,
  usu_SQL_login VARCHAR(20) NOT NULL,
  usu_SQL_senha CHAR NOT NULL,
  usu_duracao_senha SMALLINT NOT NULL,
  usu_data_validade TIMESTAMP NOT NULL,
  usu_limite_utilizacao TIMESTAMP NOT NULL,
  usu_senha CHAR(15) NOT NULL,
  usu_validacao CHAR(10) NOT NULL,
  usu_status SMALLINT NOT NULL,
  usu_seguranca CHAR(8) NOT NULL,
  usu_data_atualizacao TIMESTAMP NOT NULL,
  usu_conta_nt INT,
  usu_dica_intranet INT NOT NULL,
  usu_controle TIMESTAMP NOT NULL,
  usu_localizacao SMALLINT,
  usu_andar CHAR(10),
  usu_telefone CHAR(10),
  CONSTRAINT FK_Usuarios_Pessoas FOREIGN KEY (usu_pessoa) REFERENCES tabelas.Pessoas (pes_codigo)
);
CREATE INDEX _dta_index_Usuarios_c_11_1645248916__K2 ON tabelas.Usuarios (usu_identificacao);
CREATE UNIQUE INDEX usu_IDX_identificacao ON tabelas.Usuarios (usu_identificacao);
CREATE INDEX _dta_index_Usuarios_11_1645248916__K1_4_5 ON tabelas.Usuarios (usu_codigo, usu_pessoa, usu_orgao);
CREATE INDEX _dta_index_Usuarios_11_1645248916__K1_5 ON tabelas.Usuarios (usu_codigo, usu_orgao);
CREATE TABLE tabelas.cdpe
(
  Campo2 VARCHAR(255),
  Campo3 VARCHAR(11),
  Campo4 VARCHAR(255),
  Campo5 VARCHAR(255),
  Campo6 VARCHAR(255)
);
CREATE TABLE tabelas.dtproperties
(
  id INT NOT NULL,
  objectid INT,
  property VARCHAR(64) NOT NULL,
  value VARCHAR(255),
  uvalue VARCHAR(255),
  lvalue BYTEA,
  version INT DEFAULT 0 NOT NULL,
  CONSTRAINT pk_dtproperties PRIMARY KEY (id, property)
);
CREATE TABLE tabelas.ECT_Bairros
(
  ectb_estruturado CHAR(12) NOT NULL,
  ectb_hash CHAR(8) NOT NULL,
  ectb_abreviado VARCHAR(30) NOT NULL,
  ectb_cod_bairro INT PRIMARY KEY NOT NULL
);
CREATE INDEX ectb_IDX_esthas ON tabelas.ECT_Bairros (ectb_estruturado, ectb_hash);
CREATE TABLE tabelas.ECT_Localidades
(
  ectl_UF CHAR(2) NOT NULL,
  ectl_hash CHAR(8) NOT NULL,
  ectl_local VARCHAR(60) NOT NULL,
  ectl_tipo_local CHAR NOT NULL,
  ectl_estruturado CHAR(12) PRIMARY KEY NOT NULL
);
CREATE INDEX ectl_IDX_ufhash ON tabelas.ECT_Localidades (ectl_UF, ectl_hash);
CREATE TABLE tabelas.ECT_Recusados
(
  ectr_tipo CHAR NOT NULL,
  ectr_UF CHAR(2),
  ectr_cep5 VARCHAR(5),
  ectr_cep8 VARCHAR(8),
  ectr_local VARCHAR(60),
  ectr_tipo_local CHAR,
  ectr_nome VARCHAR(60),
  ectr_bairro VARCHAR(30),
  ectr_bairro_final VARCHAR(30),
  ectr_tipo_lograd VARCHAR(10)
);
CREATE TABLE tabelas.Emails
(
  eml_id INT PRIMARY KEY NOT NULL,
  eml_conta CHAR(30) NOT NULL,
  eml_email VARCHAR(30) NOT NULL,
  eml_dominio VARCHAR(40) NOT NULL,
  eml_tipo CHAR NOT NULL,
  eml_CPF CHAR(11) NOT NULL,
  eml_orgao INT NOT NULL,
  eml_aliases VARCHAR(100),
  eml_utilizacao VARCHAR(250),
  eml_status SMALLINT NOT NULL
);
CREATE UNIQUE INDEX eml_IDX_conta ON tabelas.Emails (eml_conta);
CREATE UNIQUE INDEX eml_IDX_emadom ON tabelas.Emails (eml_email, eml_dominio);
CREATE INDEX eml_IDX_cpf ON tabelas.Emails (eml_CPF);
CREATE TABLE tabelas.Estacoes
(
  est_codigo CHAR(10) NOT NULL,
  est_macaddress CHAR(12),
  est_sistema INT NOT NULL,
  est_inicio SMALLINT NOT NULL,
  est_final SMALLINT NOT NULL,
  est_status SMALLINT NOT NULL
);
CREATE TABLE tabelas.Eventos
(
  eve_datahora TIMESTAMP NOT NULL,
  eve_usuario CHAR(11) NOT NULL,
  eve_operacao INT NOT NULL,
  eve_codigo INT,
  eve_parametros VARCHAR(256),
  eve_resultado INT NOT NULL,
  eve_texto VARCHAR(256)
);
CREATE TABLE tabelas.Funcoes
(
  fun_codigo SMALLINT PRIMARY KEY NOT NULL,
  fun_descricao VARCHAR(40) NOT NULL,
  fun_status SMALLINT NOT NULL
);
CREATE TABLE tabelas.Grupos
(
  gru_codigo SMALLINT PRIMARY KEY NOT NULL,
  gru_sistema SMALLINT NOT NULL,
  gru_nome VARCHAR(60) NOT NULL,
  gru_status SMALLINT NOT NULL,
  CONSTRAINT FK_Grupos_Sistemas FOREIGN KEY (gru_sistema) REFERENCES tabelas.Sistemas (sis_codigo)
);
CREATE INDEX gru_IDX_sistema ON tabelas.Grupos (gru_sistema);
CREATE TABLE tabelas.Grupos_ARES
(
  gru_codigo SMALLINT NOT NULL,
  gru_sistema SMALLINT NOT NULL,
  gru_nome VARCHAR(60) NOT NULL,
  gru_status SMALLINT NOT NULL
);
CREATE TABLE tabelas.Grupos_minc11
(
  gru_codigo SMALLINT NOT NULL,
  gru_sistema SMALLINT NOT NULL,
  gru_nome VARCHAR(60) NOT NULL,
  gru_status SMALLINT NOT NULL
);
CREATE TABLE tabelas.GruposXServicos
(
  gxs_grupo INT NOT NULL,
  gxs_servico INT NOT NULL
----   ,
----   CONSTRAINT FK_GruposXServicos_Servicos_Intranet FOREIGN KEY (gxs_servico) REFERENCES
);
CREATE UNIQUE INDEX gxs_PK_grupserv ON tabelas.GruposXServicos (gxs_grupo, gxs_servico);
CREATE TABLE tabelas.Headers
(
  hdr_identificacao CHAR(12) PRIMARY KEY NOT NULL,
  hdr_numero INT NOT NULL,
  hdr_texto VARCHAR(255),
  hdr_data DATE,
  hdr_flag SMALLINT,
  hdr_controle TIMESTAMP NOT NULL
);
CREATE TABLE tabelas.Indice_Palavras
(
  ipl_palavra INT NOT NULL,
  ipl_posicao SMALLINT NOT NULL,
  ipl_tipo SMALLINT NOT NULL,
  ipl_codigo INT NOT NULL,
  CONSTRAINT ipl_PK_palpostipcod PRIMARY KEY (ipl_palavra, ipl_posicao, ipl_tipo, ipl_codigo)
);
CREATE INDEX ipl_IDX_paltip ON tabelas.Indice_Palavras (ipl_palavra, ipl_tipo);
CREATE INDEX ipl_IDX_tipcod ON tabelas.Indice_Palavras (ipl_tipo, ipl_codigo);
CREATE INDEX ipl_IDX_tippal ON tabelas.Indice_Palavras (ipl_tipo, ipl_palavra);
CREATE TABLE tabelas.Localidades
(
  loc_codigo INT PRIMARY KEY NOT NULL,
  loc_estruturado CHAR(12) NOT NULL,
  loc_tipo SMALLINT NOT NULL,
  loc_nome VARCHAR(60) NOT NULL,
  loc_sigla VARCHAR(8),
  loc_CEP VARCHAR(8),
  loc_status SMALLINT NOT NULL
);
CREATE INDEX loc_IDX_cep ON tabelas.Localidades (loc_CEP);
CREATE UNIQUE INDEX loc_IDX_estruturado ON tabelas.Localidades (loc_estruturado);
CREATE UNIQUE INDEX loc_IDX_tipest ON tabelas.Localidades (loc_tipo, loc_estruturado);
CREATE TABLE tabelas.LogonNt
(
  Name VARCHAR(50) PRIMARY KEY NOT NULL,
  Status BIGINT NOT NULL,
  Data TIMESTAMP
);
CREATE TABLE tabelas.Logradouros
(
  log_codigo INT PRIMARY KEY NOT NULL,
  log_localidade INT NOT NULL,
  log_tipo SMALLINT NOT NULL,
  log_nome VARCHAR(60) NOT NULL,
  log_limites VARCHAR(60),
  log_bairro INT,
  log_bairro_final INT,
  log_CEP VARCHAR(8),
  log_status SMALLINT NOT NULL
);
CREATE INDEX log_IDX_cep ON tabelas.Logradouros (log_CEP);
CREATE INDEX log_IDX_localidade ON tabelas.Logradouros (log_localidade);
CREATE TABLE tabelas.mei_Grupos_Intranet
(
  gri_codigo INT NOT NULL,
  gri_grupo VARCHAR(250) NOT NULL
);
CREATE TABLE tabelas.mei_GruposXServicos_Intranet
(
  gxs_grupo INT NOT NULL,
  gxs_servico INT NOT NULL
);
CREATE TABLE tabelas.mei_Servicos_Intranet
(
  srv_codigo INT NOT NULL,
  srv_titulo VARCHAR(250) NOT NULL,
  srv_link VARCHAR(500),
  srv_janela CHAR(50),
  srv_ordem CHAR(10) NOT NULL,
  srv_seguranca CHAR(10) NOT NULL,
  srv_ativo CHAR,
  srv_corfonte CHAR(7),
  srv_corfundo CHAR(7),
  srv_temsub CHAR NOT NULL
);
CREATE TABLE tabelas.mei_UsuariosXServicos_Intranet
(
  uxs_usuario INT NOT NULL,
  uxs_servicos INT NOT NULL
);
CREATE TABLE tabelas.Mensagens
(
  msg_codigo SMALLINT PRIMARY KEY NOT NULL,
  msg_descricao VARCHAR(255) NOT NULL,
  msg_botoes INT NOT NULL,
  msg_codigo_ajuda SMALLINT NOT NULL,
  msg_resposta SMALLINT NOT NULL
);
CREATE TABLE tabelas.Menus
(
  men_codigo SMALLINT PRIMARY KEY NOT NULL,
  men_sistema SMALLINT NOT NULL,
  men_modulo CHAR(12) NOT NULL,
  men_menu SMALLINT NOT NULL,
  men_opcao SMALLINT NOT NULL,
  men_nome VARCHAR(100) NOT NULL,
  men_exibicao CHAR NOT NULL,
  men_status SMALLINT NOT NULL,
  men_seguranca CHAR(8) NOT NULL,
  men_controle TIMESTAMP NOT NULL,
  men_aplicacao VARCHAR(100),
  CONSTRAINT FK_Menus_Sistemas FOREIGN KEY (men_sistema) REFERENCES tabelas.Sistemas (sis_codigo)
);
CREATE UNIQUE INDEX men_IDX_modmenopc ON tabelas.Menus (men_sistema, men_modulo, men_menu, men_opcao);
CREATE INDEX men_IDX_sistema ON tabelas.Menus (men_sistema);
CREATE TABLE tabelas.Menus_ARES
(
  men_codigo SMALLINT NOT NULL,
  men_sistema SMALLINT NOT NULL,
  men_modulo CHAR(12) NOT NULL,
  men_menu SMALLINT NOT NULL,
  men_opcao SMALLINT NOT NULL,
  men_nome VARCHAR(100) NOT NULL,
  men_exibicao CHAR NOT NULL,
  men_status SMALLINT NOT NULL,
  men_seguranca CHAR(8) NOT NULL,
  men_controle TIMESTAMP NOT NULL,
  men_aplicacao VARCHAR(100)
);
CREATE TABLE tabelas.Menus_minc11
(
  men_codigo SMALLINT NOT NULL,
  men_sistema SMALLINT NOT NULL,
  men_modulo CHAR(12) NOT NULL,
  men_menu SMALLINT NOT NULL,
  men_opcao SMALLINT NOT NULL,
  men_nome VARCHAR(100) NOT NULL,
  men_exibicao CHAR NOT NULL,
  men_status SMALLINT NOT NULL,
  men_seguranca CHAR(8) NOT NULL,
  men_controle TIMESTAMP NOT NULL,
  men_aplicacao VARCHAR(100)
);
CREATE TABLE tabelas.MenusXGrupos
(
  mxg_menu SMALLINT NOT NULL,
  mxg_grupo SMALLINT NOT NULL,
  CONSTRAINT mxg_PK_mengru PRIMARY KEY (mxg_menu, mxg_grupo),
  CONSTRAINT FK_MenusXGrupos_Menus FOREIGN KEY (mxg_menu) REFERENCES tabelas.Menus (men_codigo),
  CONSTRAINT FK_MenusXGrupos_Grupos FOREIGN KEY (mxg_grupo) REFERENCES tabelas.Grupos (gru_codigo)
);
CREATE INDEX mxg_IDX_grupo ON tabelas.MenusXGrupos (mxg_grupo);
CREATE INDEX mxg_IDX_menu ON tabelas.MenusXGrupos (mxg_menu);
CREATE TABLE tabelas.MenusXGrupos_ARES
(
  mxg_menu SMALLINT NOT NULL,
  mxg_grupo SMALLINT NOT NULL
);
CREATE TABLE tabelas.MenusXGrupos_minc11
(
  mxg_menu SMALLINT NOT NULL,
  mxg_grupo SMALLINT NOT NULL
);
CREATE TABLE tabelas.Meta_Dados
(
  met_tabela SMALLINT NOT NULL,
  met_codigo SMALLINT NOT NULL,
  met_nome VARCHAR(60) NOT NULL,
  met_ocorrencia SMALLINT NOT NULL,
  met_tamanho SMALLINT NOT NULL,
  met_tipo SMALLINT NOT NULL,
  met_criterio VARCHAR(255),
  met_indice_palavras SMALLINT NOT NULL,
  met_status SMALLINT NOT NULL,
  CONSTRAINT met_PK_tabcod PRIMARY KEY (met_tabela, met_codigo)
);
CREATE TABLE tabelas.Orgaos
(
  org_codigo SMALLINT PRIMARY KEY NOT NULL,
  org_pessoa INT NOT NULL,
  org_gerente INT NOT NULL,
  org_superior SMALLINT,
  org_sigla CHAR(12) NOT NULL,
  org_CEI INT,
  org_UF CHAR(2),
  org_tipo SMALLINT NOT NULL,
  org_status SMALLINT NOT NULL,
  org_controle TIMESTAMP NOT NULL,
  CONSTRAINT FK_Orgaos_Pessoas FOREIGN KEY (org_pessoa) REFERENCES tabelas.Pessoas (pes_codigo)
);
CREATE INDEX org_IDX_CEI ON tabelas.Orgaos (org_CEI);
CREATE INDEX idx_orgaos_org_pessoa ON tabelas.Orgaos (org_pessoa);
CREATE TABLE tabelas.Orgaos_ARES
(
  org_codigo SMALLINT NOT NULL,
  org_pessoa INT NOT NULL,
  org_gerente INT NOT NULL,
  org_superior SMALLINT,
  org_sigla CHAR(12) NOT NULL,
  org_CEI INT,
  org_UF CHAR(2),
  org_tipo SMALLINT NOT NULL,
  org_status SMALLINT NOT NULL,
  org_controle TIMESTAMP NOT NULL
);
CREATE TABLE tabelas.Orgaos_Subordinados
(
  sub_orgao SMALLINT NOT NULL,
  sub_superior SMALLINT NOT NULL,
  sub_nivel SMALLINT NOT NULL,
  CONSTRAINT sub_PK_orgsup PRIMARY KEY (sub_orgao, sub_superior)
);
CREATE INDEX sub_IDX_superior ON tabelas.Orgaos_Subordinados (sub_superior);
CREATE TABLE tabelas.OrgaosXUsuarios
(
  oxu_orgao SMALLINT NOT NULL,
  oxu_usuario SMALLINT NOT NULL,
  oxu_acesso SMALLINT NOT NULL,
  CONSTRAINT oxu_PK_orgusu PRIMARY KEY (oxu_orgao, oxu_usuario),
  CONSTRAINT FK_OrgaosXUsuarios_Orgaos FOREIGN KEY (oxu_orgao) REFERENCES tabelas.Orgaos (org_codigo),
  CONSTRAINT FK_OrgaosXUsuarios_Usuarios FOREIGN KEY (oxu_usuario) REFERENCES tabelas.Usuarios (usu_codigo)
);
CREATE INDEX oxu_IDX_orgao ON tabelas.OrgaosXUsuarios (oxu_orgao);
CREATE INDEX oxu_IDX_usuario ON tabelas.OrgaosXUsuarios (oxu_usuario);
CREATE TABLE tabelas.Palavras
(
  pal_codigo INT PRIMARY KEY NOT NULL,
  pal_texto VARCHAR(20) NOT NULL,
  pal_status SMALLINT NOT NULL
);
CREATE UNIQUE INDEX pal_IDX_texto ON tabelas.Palavras (pal_texto);
CREATE TABLE tabelas.Palavras2
(
  pal_codigo INT NOT NULL,
  pal_texto VARCHAR(20) NOT NULL,
  pal_status SMALLINT NOT NULL
);
CREATE UNIQUE INDEX idx_codigo ON tabelas.Palavras2 (pal_codigo);
CREATE INDEX idx_palavra ON tabelas.Palavras2 (pal_texto);
CREATE TABLE tabelas.Pessoa_Dados
(
  pdd_pessoa INT NOT NULL,
  pdd_meta_dado SMALLINT NOT NULL,
  pdd_sequencia SMALLINT NOT NULL,
  pdd_dado VARCHAR(80) NOT NULL,
  CONSTRAINT pdd_PK_pesmetseq PRIMARY KEY (pdd_pessoa, pdd_meta_dado, pdd_sequencia),
  CONSTRAINT FK_Pessoa_Dados_Pessoas FOREIGN KEY (pdd_pessoa) REFERENCES tabelas.Pessoas (pes_codigo)
);
CREATE INDEX pdd_IDX_dadmet ON tabelas.Pessoa_Dados (pdd_dado, pdd_meta_dado);
CREATE TABLE tabelas.Pessoa_Enderecos
(
  pen_pessoa INT NOT NULL,
  pen_tipo SMALLINT NOT NULL,
  pen_endereco VARCHAR(100) NOT NULL,
  pen_bairro VARCHAR(60),
  pen_CEP CHAR(8),
  pen_localidade CHAR(12) NOT NULL,
  CONSTRAINT pen_PK_pestip PRIMARY KEY (pen_pessoa, pen_tipo),
  CONSTRAINT FK_Pessoa_Enderecos_Pessoas FOREIGN KEY (pen_pessoa) REFERENCES tabelas.Pessoas (pes_codigo)
----   ,
----   CONSTRAINT FK_Pessoa_Enderecos_Localidades FOREIGN KEY (pen_localidade) REFERENCES
);
CREATE TABLE tabelas.Pessoa_Identificacoes
(
  pid_pessoa INT NOT NULL,
  pid_meta_dado SMALLINT NOT NULL,
  pid_sequencia SMALLINT NOT NULL,
  pid_identificacao VARCHAR(80) NOT NULL,
  CONSTRAINT pid_PK_pesmetseq PRIMARY KEY (pid_pessoa, pid_meta_dado, pid_sequencia),
  CONSTRAINT FK_Pessoa_Identificacoes_Pessoas FOREIGN KEY (pid_pessoa) REFERENCES tabelas.Pessoas (pes_codigo)
);
CREATE INDEX pid_IDX_idemet ON tabelas.Pessoa_Identificacoes (pid_identificacao, pid_meta_dado);
CREATE INDEX _dta_index_Pessoa_Identificacoes_11_263671987__K3_K1_K2_4 ON tabelas.Pessoa_Identificacoes (pid_sequencia, pid_pessoa, pid_meta_dado, pid_identificacao);
CREATE TABLE tabelas.pessoa_identificacoes_alteradas
(
  pia_datahora TIMESTAMP NOT NULL,
  pia_pessoa INT NOT NULL,
  pia_anterior VARCHAR(80),
  pia_atual VARCHAR(80)
);
CREATE TABLE tabelas.PessoasXFuncoes
(
  pxf_pessoa INT NOT NULL,
  pxf_funcao SMALLINT NOT NULL,
  pxf_entidade INT NOT NULL,
  pxf_status SMALLINT NOT NULL,
  CONSTRAINT pxf_PK_pesfunent PRIMARY KEY (pxf_pessoa, pxf_funcao, pxf_entidade),
  CONSTRAINT FK_PessoasXFuncoes_Pessoas FOREIGN KEY (pxf_pessoa) REFERENCES tabelas.Pessoas (pes_codigo),
  CONSTRAINT FK_PessoasXFuncoes_Funcoes FOREIGN KEY (pxf_funcao) REFERENCES tabelas.Funcoes (fun_codigo)
);
CREATE INDEX pxf_IDX_entidade ON tabelas.PessoasXFuncoes (pxf_entidade);
CREATE INDEX pxf_IDX_funcao ON tabelas.PessoasXFuncoes (pxf_funcao);
CREATE INDEX pxf_IDX_pessoa ON tabelas.PessoasXFuncoes (pxf_pessoa);
CREATE TABLE tabelas.Servicos_Intranet
(
  srv_codigo INT NOT NULL,
  srv_titulo VARCHAR(256) NOT NULL,
  srv_link VARCHAR(512) NOT NULL,
  srv_janela VARCHAR(50),
  srv_ordem CHAR(10) NOT NULL,
  srv_seguranca CHAR(10) NOT NULL
);
CREATE UNIQUE INDEX srv_PK_codigo ON tabelas.Servicos_Intranet (srv_codigo);
CREATE TABLE tabelas.Sistemas_ARES
(
  sis_codigo SMALLINT NOT NULL,
  sis_sigla VARCHAR(16),
  sis_nome VARCHAR(60) NOT NULL,
  sis_status SMALLINT NOT NULL,
  sis_seguranca CHAR(8) NOT NULL,
  sis_url VARCHAR(150),
  sis_controle TIMESTAMP NOT NULL
);
CREATE TABLE tabelas.tabelas
(
  tab_tipo SMALLINT NOT NULL,
  tab_codigo SMALLINT NOT NULL,
  tab_descricao VARCHAR(60) NOT NULL,
  tab_status SMALLINT NOT NULL,
  CONSTRAINT tab_PK_tipcod PRIMARY KEY (tab_tipo, tab_codigo)
);
CREATE TABLE tabelas.Table1
(
  a CHAR(10)
);
CREATE TABLE tabelas.temp_tbl
(
  tmp_texto CHAR(100) NOT NULL
);
CREATE TABLE tabelas.Tipos_Logradouro
(
  tlg_codigo SMALLINT PRIMARY KEY NOT NULL,
  tlg_sigla CHAR(10) NOT NULL,
  tlg_descricao VARCHAR(30) NOT NULL
);
CREATE UNIQUE INDEX tlg_IDX_sigla ON tabelas.Tipos_Logradouro (tlg_sigla);
CREATE TABLE tabelas.UnidadesOrcamentarias
(
  uor_exercicio SMALLINT NOT NULL,
  uor_codigo CHAR(6) NOT NULL,
  uor_descricao VARCHAR(60) NOT NULL,
  uor_sigla VARCHAR(10) NOT NULL,
  uor_fundo CHAR NOT NULL,
  uor_status SMALLINT NOT NULL,
  CONSTRAINT uor_PK_exercodi PRIMARY KEY (uor_exercicio, uor_codigo)
);
CREATE TABLE tabelas.Usuarios_ARES
(
  usu_codigo SMALLINT NOT NULL,
  usu_identificacao CHAR(16) NOT NULL,
  usu_nome VARCHAR(20) NOT NULL,
  usu_pessoa INT NOT NULL,
  usu_orgao SMALLINT,
  usu_sala VARCHAR(20),
  usu_ramal SMALLINT,
  usu_nivel SMALLINT NOT NULL,
  usu_exibicao CHAR NOT NULL,
  usu_SQL_login VARCHAR(20) NOT NULL,
  usu_SQL_senha CHAR NOT NULL,
  usu_duracao_senha SMALLINT NOT NULL,
  usu_data_validade TIMESTAMP NOT NULL,
  usu_limite_utilizacao TIMESTAMP NOT NULL,
  usu_senha CHAR(15) NOT NULL,
  usu_validacao CHAR(10) NOT NULL,
  usu_status SMALLINT NOT NULL,
  usu_seguranca CHAR(8) NOT NULL,
  usu_data_atualizacao TIMESTAMP NOT NULL,
  usu_conta_nt INT,
  usu_dica_intranet INT NOT NULL,
  usu_controle TIMESTAMP NOT NULL,
  usu_localizacao SMALLINT,
  usu_andar CHAR(10),
  usu_telefone CHAR(10)
);
CREATE TABLE tabelas.UsuExternoSistemas
(
  usuIdentificacao VARCHAR(11),
  usuNome VARCHAR(20),
  usuSenha VARCHAR(11),
  usuObs VARCHAR(50),
  usuStatus SMALLINT
);
CREATE TABLE tabelas.ListaInstitucionais
(
  Nome VARCHAR(71) NOT NULL
);
CREATE TABLE tabelas.ListaPessoais
(
  Nome VARCHAR(71) NOT NULL
);
CREATE TABLE tabelas.V_Distritos_Por_Palavra
(
  loc_nome VARCHAR(60) NOT NULL,
  loc_estruturado CHAR(12) NOT NULL,
  loc_tipo SMALLINT NOT NULL,
  loc_codigo INT NOT NULL,
  pal_texto VARCHAR(20) NOT NULL
);
CREATE TABLE tabelas.V_Emails
(
  eml_id INT NOT NULL,
  eml_conta CHAR(8) NOT NULL,
  eml_email VARCHAR(30) NOT NULL,
  eml_dominio VARCHAR(40) NOT NULL,
  eml_tipo CHAR NOT NULL,
  eml_CPF_responsavel CHAR(11) NOT NULL,
  eml_codigo_orgao INT,
  eml_nome_responsavel VARCHAR(80),
  eml_sala VARCHAR(20) NOT NULL,
  eml_ramal SMALLINT NOT NULL,
  eml_sigla_orgao CHAR(12),
  eml_nome_orgao VARCHAR(80),
  eml_orgao_estrutura VARCHAR(100),
  eml_codigo_titular INT,
  eml_CPF_titular CHAR(16),
  eml_nome_titular VARCHAR(80)
);
CREATE TABLE tabelas.V_Estados_Por_Palavra
(
  loc_nome VARCHAR(60) NOT NULL,
  loc_estruturado CHAR(12) NOT NULL,
  loc_tipo SMALLINT NOT NULL,
  loc_codigo INT NOT NULL,
  pal_texto VARCHAR(20) NOT NULL
);
CREATE TABLE tabelas.V_Hierarquia_Orgaos
(
  org_codigo SMALLINT NOT NULL,
  org_superior SMALLINT NOT NULL,
  org_nivel SMALLINT NOT NULL,
  org_sigla CHAR(12),
  org_status SMALLINT,
  org_nome VARCHAR(80),
  sup_sigla CHAR(12),
  sup_status SMALLINT,
  sup_nome VARCHAR(80)
);
CREATE TABLE tabelas.V_Localidade_Completa
(
  loc_codigo INT NOT NULL,
  loc_estruturado CHAR(12) NOT NULL,
  loc_tipo SMALLINT NOT NULL,
  loc_sigla VARCHAR(8),
  loc_cep VARCHAR(8),
  loc_pais VARCHAR(60),
  loc_estado VARCHAR(60),
  loc_municipio VARCHAR(60),
  loc_distrito VARCHAR(60)
);
CREATE TABLE tabelas.V_Logradouro_Completo
(
  log_codigo INT NOT NULL,
  log_tipo SMALLINT NOT NULL,
  log_nome VARCHAR(60) NOT NULL,
  log_localidade INT NOT NULL,
  log_limites VARCHAR(60),
  log_bairro INT,
  log_bairro_final INT,
  log_CEP VARCHAR(8),
  loc_estruturado CHAR(12),
  loc_tipo SMALLINT,
  loc_sigla VARCHAR(8),
  loc_CEP VARCHAR(8),
  loc_pais VARCHAR(60),
  loc_estado VARCHAR(60),
  loc_municipio VARCHAR(60),
  loc_distrito VARCHAR(60),
  tlg_descricao VARCHAR(30),
  bai_bairro VARCHAR(60),
  bai_bairro_final VARCHAR(60)
);
CREATE TABLE tabelas.V_Municipios_Por_Palavra
(
  loc_nome VARCHAR(60) NOT NULL,
  loc_estruturado CHAR(12) NOT NULL,
  loc_tipo SMALLINT NOT NULL,
  loc_codigo INT NOT NULL,
  pal_texto VARCHAR(20) NOT NULL
);
CREATE TABLE tabelas.V_Paises_Por_Palavra
(
  loc_nome VARCHAR(60) NOT NULL,
  loc_estruturado CHAR(12) NOT NULL,
  loc_tipo SMALLINT NOT NULL,
  loc_codigo INT NOT NULL,
  pal_texto VARCHAR(20) NOT NULL
);
CREATE TABLE tabelas.V_Pessoas_Nome_Completo
(
  pes_codigo INT NOT NULL,
  pes_tipo SMALLINT NOT NULL,
  pes_superior INT,
  pes_validade SMALLINT NOT NULL,
  pes_nome_completo VARCHAR(80),
  pes_nome_superior VARCHAR(80)
);
CREATE TABLE tabelas.V_Usuario_Completo
(
  usu_codigo SMALLINT NOT NULL,
  usu_identificacao CHAR(16) NOT NULL,
  usu_nome VARCHAR(20) NOT NULL,
  usu_pessoa INT NOT NULL,
  usu_orgao SMALLINT,
  usu_sala VARCHAR(20),
  usu_ramal SMALLINT,
  usu_nivel SMALLINT NOT NULL,
  usu_exibicao CHAR NOT NULL,
  usu_SQL_login VARCHAR(20) NOT NULL,
  usu_SQL_senha CHAR NOT NULL,
  usu_duracao_senha SMALLINT NOT NULL,
  usu_data_validade TIMESTAMP NOT NULL,
  usu_limite_utilizacao TIMESTAMP NOT NULL,
  usu_senha CHAR(15) NOT NULL,
  usu_status SMALLINT NOT NULL,
  usu_validacao CHAR(10) NOT NULL,
  usu_seguranca CHAR(8) NOT NULL,
  usu_flag_validacao VARCHAR(4) NOT NULL,
  usu_flag_seguranca VARCHAR(4) NOT NULL,
  usu_nome_completo VARCHAR(80),
  org_sigla CHAR(12),
  org_nome_completo VARCHAR(80),
  org_estrutura VARCHAR(100)
);
CREATE TABLE tabelas.V_Usuario_Orgaos_Grupos
(
  usu_codigo SMALLINT NOT NULL,
  usu_identificacao CHAR(16) NOT NULL,
  usu_nome VARCHAR(20) NOT NULL,
  usu_nome_completo VARCHAR(80) NOT NULL,
  org_codigo SMALLINT NOT NULL,
  org_pessoa INT NOT NULL,
  org_gerente INT NOT NULL,
  org_superior SMALLINT,
  org_sigla CHAR(12) NOT NULL,
  org_CEI INT,
  org_tipo SMALLINT NOT NULL,
  org_status SMALLINT NOT NULL,
  org_nome_completo VARCHAR(80) NOT NULL,
  uog_usuario SMALLINT NOT NULL,
  uog_orgao SMALLINT NOT NULL,
  uog_grupo SMALLINT NOT NULL,
  gru_sistema SMALLINT NOT NULL,
  gru_nome VARCHAR(60) NOT NULL,
  gru_status SMALLINT NOT NULL
);
CREATE TABLE tabelas.V_Usuarios
(
  usu_codigo SMALLINT NOT NULL,
  usu_identificacao CHAR(16) NOT NULL,
  usu_nome VARCHAR(20) NOT NULL,
  usu_pessoa INT NOT NULL,
  usu_orgao SMALLINT,
  usu_sala VARCHAR(20),
  usu_ramal SMALLINT,
  usu_nivel SMALLINT NOT NULL,
  usu_nome_completo VARCHAR(80)
);
CREATE TABLE tabelas.vwLdap
(
  adsPath VARCHAR(256),
  samaccountname VARCHAR(4000),
  displayname VARCHAR(4000),
  mail VARCHAR(4000),
  userAccountControl INT,
  department VARCHAR(4000),
  physicalDeliveryOfficeName VARCHAR(4000),
  manager VARCHAR(4000)
);
CREATE TABLE tabelas.vwMincUsuarios
(
  usu_codigo SMALLINT,
  login VARCHAR(4000),
  nome_completo VARCHAR(4000),
  area VARCHAR(100),
  funcao VARCHAR(100) NOT NULL,
  unidade VARCHAR(100),
  localizacao VARCHAR(21) NOT NULL,
  complemento VARCHAR(17),
  telefone CHAR(10),
  validade_senha TIMESTAMP,
  limite_utilizacao TIMESTAMP,
  ultima_atualizacao TIMESTAMP,
  email VARCHAR(4000),
  situacao SMALLINT
);
CREATE TABLE tabelas.vwPessoasUnidades
(
  tipo VARCHAR(1) NOT NULL,
  email VARCHAR(4000),
  nome VARCHAR(80),
  CPF CHAR(16),
  localizacao VARCHAR(21) NOT NULL,
  andar VARCHAR(17),
  telefone CHAR(10) NOT NULL,
  sala VARCHAR(20) NOT NULL,
  ramal VARCHAR(10) NOT NULL,
  orgao VARCHAR(100),
  lotacao VARCHAR(80),
  exercicio VARCHAR(80),
  funcao VARCHAR(100) NOT NULL,
  codfunc CHAR(4),
  contato VARCHAR(80),
  CPF_contato CHAR(16),
  titular VARCHAR(1) NOT NULL,
  CPF_titular VARCHAR(1) NOT NULL
);
CREATE TABLE tabelas.vwRelServidorHPImpressao
(
  Identificacao CHAR(16) NOT NULL,
  NomeFuncionario VARCHAR(80),
  SiglaLotacao VARCHAR(100),
  DescricaoLotacao VARCHAR(80)
);
CREATE TABLE tabelas.vwSerproEmail
(
  tipo VARCHAR(1) NOT NULL,
  email VARCHAR(71) NOT NULL,
  nome VARCHAR(80),
  CPF CHAR(16),
  sala VARCHAR(20) NOT NULL,
  ramal VARCHAR(10) NOT NULL,
  orgao VARCHAR(100),
  lotacao VARCHAR(80),
  exercicio VARCHAR(80),
  cargo VARCHAR(100) NOT NULL,
  funcao CHAR(4),
  codfunc VARCHAR(50),
  contato VARCHAR(80),
  CPF_contato CHAR(16),
  titular VARCHAR(80) NOT NULL,
  CPF_titular CHAR(16) NOT NULL
);
CREATE TABLE tabelas.vwUsuarios
(
  usu_codigo SMALLINT NOT NULL,
  usu_identificacao CHAR(16) NOT NULL,
  usu_nome VARCHAR(20) NOT NULL,
  usu_orgao SMALLINT,
  usu_lotacao VARCHAR(183),
  usu_status SMALLINT NOT NULL,
  gru_orgao SMALLINT,
  gru_sigla VARCHAR(4) NOT NULL,
  usu_localizacao VARCHAR(21) NOT NULL,
  usu_andar VARCHAR(17),
  usu_telefone CHAR(10) NOT NULL
);
CREATE TABLE tabelas.vwUsuariosOrgaosGrupos
(
  usu_codigo SMALLINT NOT NULL,
  usu_identificacao CHAR(16) NOT NULL,
  usu_nome VARCHAR(20) NOT NULL,
  usu_orgao SMALLINT,
  usu_orgaolotacao VARCHAR(100),
  usu_telefone CHAR(10),
  org_superior INT,
  uog_orgao SMALLINT NOT NULL,
  org_siglaautorizado VARCHAR(100),
  org_nomeautorizado VARCHAR(80) NOT NULL,
  sis_codigo SMALLINT NOT NULL,
  sis_sigla VARCHAR(16),
  sis_nome VARCHAR(60) NOT NULL,
  gru_codigo SMALLINT NOT NULL,
  gru_nome VARCHAR(60) NOT NULL,
  uog_status BIT NOT NULL,
  id_unico BIGINT
);
CREATE TABLE tabelas.UsuariosXOrgaosXGrupos
(
  uog_usuario INTEGER NOT NULL,
  uog_orgao SMALLINT NOT NULL,
  uog_grupo SMALLINT NOT NULL,
  uog_status INTEGER DEFAULT 1 NOT NULL,
  CONSTRAINT uog_PK_usuorggru PRIMARY KEY (uog_usuario, uog_orgao, uog_grupo),
  CONSTRAINT FK_UsuariosXOrgaosXGrupos_Usuarios FOREIGN KEY (uog_usuario) REFERENCES tabelas.Usuarios (usu_codigo),
  CONSTRAINT FK_UsuariosXOrgaosXGrupos_Orgaos FOREIGN KEY (uog_orgao) REFERENCES tabelas.Orgaos (org_codigo),
  CONSTRAINT FK_UsuariosXOrgaosXGrupos_Grupos FOREIGN KEY (uog_grupo) REFERENCES tabelas.Grupos (gru_codigo)
);
CREATE TABLE tabelas.UsuariosXGrupos_Intranet
(
  uxg_usuario SMALLINT NOT NULL,
  uxg_grupo SMALLINT NOT NULL,
  CONSTRAINT FK_UsuariosXGrupos_Intranet_Usuarios FOREIGN KEY (uxg_usuario) REFERENCES tabelas.Usuarios (usu_codigo)
);
CREATE UNIQUE INDEX uxg_PK_usuagrup ON tabelas.UsuariosXGrupos_Intranet (uxg_usuario, uxg_grupo);
CREATE INDEX uog_IDX_usuario ON tabelas.UsuariosXOrgaosXGrupos (uog_usuario);
CREATE INDEX uxg_IDX_grupo ON tabelas.UsuariosXOrgaosXGrupos (uog_grupo);
CREATE INDEX _dta_index_UsuariosXOrgaosXGrupos_11_1606296782__K1_K4_2_3 ON tabelas.UsuariosXOrgaosXGrupos (uog_usuario, uog_status, uog_orgao, uog_grupo);
CREATE INDEX _dta_index_UsuariosXOrgaosXGrupos_11_1606296782__K4_K1_K3_K2 ON tabelas.UsuariosXOrgaosXGrupos (uog_status, uog_usuario, uog_grupo, uog_orgao);
CREATE INDEX idx_uog_uog_orgao ON tabelas.UsuariosXOrgaosXGrupos (uog_orgao);
CREATE TABLE tabelas.UsuariosXOrgaosXGrupos_ARES
(
  uog_usuario SMALLINT NOT NULL,
  uog_orgao SMALLINT NOT NULL,
  uog_grupo SMALLINT NOT NULL
);