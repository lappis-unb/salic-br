/* INCLUSÃO DE TÍTULO A SER UTILIZADO NO ENVIO DE E-MAIL */
INSERT INTO sac.dbo.Verificacao (idVerificacao, idTipo, Descricao, stEstado)
     VALUES (620, 6, 'Enquadramento de projeto cultural', 1);

/* INCLUSÃO DE CORPO A SER UTILIZADO NO ENVIO DE E-MAIL */
INSERT INTO sac.dbo.tbTextoEmail(idTextoemail, idAssunto, dsTexto)
     VALUES (23, 620, '<p>Prezado Proponente,</p>');

--select * from sac.dbo.tbTextoEmail where idTextoemail = 23