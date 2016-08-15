-- noinspection SqlDialectInspectionForFile
-- DROP SCHEMA controledeacesso CASCADE;
-- CREATE SCHEMA controledeacesso;
-- ROLLBACK;
-- BEGIN;
-- COMMIT;

CREATE TABLE controledeacesso.dtproperties
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
CREATE TABLE controledeacesso.SGCacesso
(
  IdUsuario INT NOT NULL,
  Cpf CHAR(11) PRIMARY KEY NOT NULL,
  Nome VARCHAR(100) NOT NULL,
  DtNascimento TIMESTAMP NOT NULL,
  Email VARCHAR(60) NOT NULL,
  Senha CHAR(15) NOT NULL,
  DtCadastro TIMESTAMP NOT NULL,
  Situacao INT NOT NULL,
  DtSituacao TIMESTAMP NOT NULL
);
CREATE TABLE controledeacesso.SGCsistema
(
  IdSistema INT PRIMARY KEY NOT NULL,
  NomeSistema VARCHAR(20),
  DescricaoSistema VARCHAR(100)
);
CREATE TABLE controledeacesso.SGCusuarioXsistema
(
  IdUsuario INT NOT NULL,
  IdSistema INT NOT NULL,
  CONSTRAINT PK_SGCusuarioXsistema PRIMARY KEY (IdUsuario, IdSistema)
);
CREATE TABLE controledeacesso.sysdiagrams
(
  name VARCHAR NOT NULL,
  principal_id INT NOT NULL,
  diagram_id INT PRIMARY KEY NOT NULL,
  version INT,
  definition BIT
);
CREATE UNIQUE INDEX UK_principal_name ON controledeacesso.sysdiagrams (principal_id, name);
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
-- CREATE PROCEDURE dt_whocheckedout(@chObjectType CHAR, @vchObjectName VARCHAR, @vchLoginName VARCHAR, @vchPassword VARCHAR);
-- CREATE PROCEDURE dt_whocheckedout_u(@chObjectType CHAR, @vchObjectName SYSNAME, @vchLoginName SYSNAME, @vchPassword SYSNAME);
-- CREATE FUNCTION fn_diagramobjects();
-- CREATE PROCEDURE sp_alterdiagram(@diagramname SYSNAME, @owner_id INT, @version INT, @definition VARBINARY);
-- CREATE PROCEDURE sp_creatediagram(@diagramname SYSNAME, @owner_id INT, @version INT, @definition VARBINARY);
-- CREATE PROCEDURE sp_dropdiagram(@diagramname SYSNAME, @owner_id INT);
-- CREATE PROCEDURE sp_helpdiagramdefinition(@diagramname SYSNAME, @owner_id INT);
-- CREATE PROCEDURE sp_helpdiagrams(@diagramname SYSNAME, @owner_id INT);
-- CREATE PROCEDURE sp_renamediagram(@diagramname SYSNAME, @owner_id INT, @new_diagramname SYSNAME);
-- CREATE PROCEDURE sp_upgraddiagrams();
-- CREATE PROCEDURE spGerarSenhas(@p_idt CHAR);