CREATE TABLE sac.dbo.PreProjetoMeta (
  idPreProjetoMeta INT NOT NULL IDENTITY,
  idPreProjeto int NOT NULL,
  metaKey varchar(250) NOT NULL,
  metaValue varchar(2500),
  CONSTRAINT PreProjetoMeta_PK PRIMARY KEY (idPreProjetoMeta),
  CONSTRAINT PreProjetoMeta_PreProjeto_FK FOREIGN KEY (idPreProjeto) REFERENCES sac.dbo.PreProjeto(idPreProjeto)
);