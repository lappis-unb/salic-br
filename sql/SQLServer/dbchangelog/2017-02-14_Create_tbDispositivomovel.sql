CREATE TABLE SAC.dbo.tbDispositivoMovel (
	idDispositivoMovel int NOT NULL IDENTITY(1,1),
	idRegistration varchar(255) NOT NULL,
	dtRegistration datetime NOT NULL DEFAULT '(getdate())',
	nrCPF char(11),
	dtAcesso datetime,
	CONSTRAINT PK_idDispositivoMovel PRIMARY KEY (idDispositivoMovel)
)

