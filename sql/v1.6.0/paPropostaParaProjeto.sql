USE [sac]
GO

IF OBJECT_ID ('paPropostaParaProjeto', 'procedure') IS NOT NULL
DROP PROCEDURE paPropostaParaProjeto;
GO

SET ANSI_NULLS ON
GO
SET QUOTED_IDENTIFIER ON
GO

CREATE PROCEDURE [dbo].[paPropostaParaProjeto]
                @idProposta int,
                @CNPJCPF    varchar(14),
                @idOrgao    int,
                @idUsuario  int,
                @Processo   varchar(17)
AS
-- =========================================================================================
--  VERSÃO ATUALIZADA DO MINC10 EM 18/01/2010
-- =========================================================================================
-- Autor: Rômulo Menhô Barbosa
-- Data de Criação: 28/10/2008
-- Data de Alteração: 05/06/2009
-- Motivo: Mudança no modo de distribuir o projeto para a unidade de análise.
-- Descrição: Relação de Produtos por projetos
-- Data de Alteração: 24/06/2010
-- Motivo: Permitir a transformação de edital
-- Data de Alteração: 30/07/2010
-- Motivo: Ajustar a distribuição do projeto para a Unidade Vinculada
-- Data de Alteração: 06/04/2011
-- Motivo: Inibir o envio de email para editais
-- Data de Alteração: 25/07/2011
-- Motivo: Alterar a distribuição de tipo de análise para produtos 
-- Data de Alteração: 16/12/2011
-- Motivo: Inclusão da coluna stPrincipal ao Enviar análise para a Unidade Vinculada
-- Data de Alteração: 23/01/2014
-- Motivo: Inclusão CLÁUSULA TOP 1 NO SELECT para incluir registro na tabela Interessado.
--         Incluir po nome agente quando não houve o responsável (usuario = 0 na tabela agentes).
--Data de Alteração: 06/02/2014
-- Data de Alteração: 20/04/2016
-- Motivo: Inclusão do parâmetro @Processo para integração com o sistema SEI.
--         Exclusão da chamada da procedure paGeraProcessoSalic. Autor: Yuri Marques
-- =========================================================================================
SET NOCOUNT ON
DECLARE @Erro         int
DECLARE @Rows         int
DECLARE @Sequencial   int
DECLARE @AnoProjeto   char(2)
DECLARE @NrProjeto    varchar(5)
DECLARE @Ano          int
DECLARE @idPronac     int
--DECLARE @Processo     varchar(17)
DECLARE    @NomeProposta VARCHAR(200)
DECLARE    @Destinatario VARCHAR(100)
DECLARE @Email          VARCHAR(50)
DECLARE    @Mensagem      VARCHAR(MAX)
DECLARE @tableHTML    NVARCHAR(MAX)
DECLARE    @Retorno      INT
DECLARE @Flag         int
DECLARE @idEdital      int
DECLARE @idTextoEmail int
DECLARE @idVinculada  int

BEGIN TRAN
SET @Flag = 0
SET @idEdital = null

--============================================================================================================
-- Tabela temporária
--============================================================================================================

CREATE TABLE #Verificacao
       (
        idContador int IDENTITY(1,1),
        Descricao  varchar (250)
       )

--============================================================================================================
-- Verificar se é Edital
--============================================================================================================
SELECT @idEdital = idEdital FROM dbo.PreProjeto where idPreProjeto = @idProposta

--========================================================================================================
-- Inserir informações na tabela Interessado
--========================================================================================================
IF NOT EXISTS(SELECT * FROM Interessado WHERE CgcCpf = @CNPJCPF)
   BEGIN
     INSERT INTO Interessado
                (CgcCpf,TipoPessoa,Nome,Responsavel,Endereco,Cidade,UF,CEP,Natureza,Esfera,Administracao,Utilidade)
          SELECT TOP 1 p.CNPJCPF,
                 case 
                   when len(p.CNPJCPF)=11
                     then  '1'
                     else  '2'
                 end as TipoPessoa,
                 Nome,
                 case 
                   when a.Usuario = 0
                     then dbo.fnNome(a.idAgente)
                     else dbo.fnNomeResponsavel(p.Usuario)
                  END as Usuario,
                 p.Logradouro + ' - ' + p.Bairro,u.Municipio,u.UF,p.CEP,
                 case 
                   when Direito = 1
                     then '1'
                   when Direito = 2 or Direito = 35
                     then '2'
                  end as Direito,
                 case 
                   when Esfera = 3
                     then '1'
                   when Esfera = 4
                     then '2'
                   when Esfera = 5
                     then '3'
                  end as Esfera,
                 case 
                   when Administracao = 11
                     then '1'
                   when Administracao = 12
                     then '2'
                  end as Administracao,
                 case 
                   when Direito = 2
                     then '1'
                   when Direito = 35
                     then '2'
                  end as Utilidade
                FROM vCadastrarProponente p
                 INNER JOIN Agentes.dbo.Agentes a on (p.idAgente = a.idAgente)
                 INNER JOIN Agentes.dbo.EnderecoNacional e on (p.idAgente = e.idAgente and e.Status = 1)
                 INNER JOIN Agentes.dbo.vUFMunicipio u on (e.UF = u.idUF and e.Cidade = u.idMunicipio)
                 LEFT JOIN  vwNatureza n on (p.idAgente =n.idAgente)
                 WHERE p.CNPJCPF=@CNPJCPF and Correspondencia = 1
END
ELSE
   BEGIN
     UPDATE Interessado
        SET Endereco = e.Logradouro + ' - ' + e.Bairro,
            Cidade   = u.Municipio,
            UF       = u.UF,
            CEP      = e.CEP,
            Natureza  = case 
                        when n.Direito = 1
                          then '1'
                        when n.Direito = 2 or n.Direito = 35
                          then '2'
                       end,
            Esfera   = case 
                        when n.Esfera = 3
                          then '1'
                        when n.Esfera = 4
                          then '2'
                        when n.Esfera = 5
                         then '3'
                       end,
            Administracao = case 
                             when n.Administracao = 11
                              then '1'
                             when n.Administracao = 12
                              then '2'
                            end,
            Utilidade = case 
                         when n.Direito = 2
                           then '1'
                         when n.Direito = 35
                           then '2'
                        end
            FROM Interessado i
                 INNER JOIN Agentes.dbo.Agentes a on (i.CgcCpf = a.CNPJCPF)
                 INNER JOIN Agentes.dbo.EnderecoNacional e on (a.idAgente = e.idAgente and e.Status = 1)
                 INNER JOIN Agentes.dbo.vUFMunicipio u on (e.UF = u.idUF and e.Cidade = u.idMunicipio)
                 LEFT JOIN  vwNatureza n on (a.idAgente =n.idAgente)
                 WHERE i.CGCCPF=@CNPJCPF and e.Status = 1
   END

SELECT @Rows = @@ROWCOUNT, @Erro = @@ERROR
   
IF @Erro <> 0
   BEGIN
     INSERT INTO #Verificacao
        VALUES ('1.Erro ao tentar incluir ou alterar %d registros na tabela Interessado.')
     SET @Flag = 1
   END
IF @Rows < 1
   BEGIN
     INSERT INTO #Verificacao       
        VALUES ('2.Não há registro para incluir / alterar registro na tabela Interessado.')
     SET @Flag = 1
   END
IF @Rows <> 1
   BEGIN
     INSERT INTO #Verificacao       
        VALUES ('3.Não é possível incluir / alterar mais de um registro na tabela Interessado.')
     SET @Flag = 1
   END

--========================================================================================================
-- Inserir informações na tabela Projetos
--========================================================================================================
SET @Ano = year(getdate())

UPDATE SequencialProjetos
   SET @Sequencial = Sequencial = Sequencial + 1
   WHERE Ano = YEAR(GETDATE())

SELECT @Rows = @@ROWCOUNT, @Erro = @@ERROR

IF @Erro <> 0
   BEGIN
     INSERT INTO #Verificacao
        VALUES ('4.Erro ao tentar atualizar registros na tabela SequencialProjetos.')
     SET @Flag = 1 
   END

IF @Rows = 0
   BEGIN
     INSERT INTO SequencialProjetos (Ano,Sequencial)
                             VALUES (@Ano ,1)
     SET @Sequencial = 1
   END

IF @Erro <> 0
   BEGIN
     INSERT INTO #Verificacao       
        VALUES ('5.Não é possível incluir ou alterar mais de um registro na tabela SequencialProjetos.')
     SET @Flag = 1 
   END

SET @AnoProjeto = substring(convert(varchar(10),getdate(),111),3,2)

IF @Sequencial > 9999
   BEGIN
     SET @NrProjeto = SUBSTRING(RTRIM(LTRIM(STR(@Sequencial + 100000))), 2, 5)
   END
ELSE
   BEGIN
     SET @NrProjeto = SUBSTRING(RTRIM(LTRIM(STR(@Sequencial + 100000))), 3, 4)
   END
--===========================================================================================================
-- Pegar o nº do processo
--==========================================================================================================
--EXEC dbo.paGeraProcessoSalic @idUsuario,@idOrgao,@Processo output
--===========================================================================================================
-- Inserir projetos
--==========================================================================================================
IF @idEdital IS NULL --Para incentivo fiscal
BEGIN
    INSERT INTO Projetos
         (AnoProjeto,Sequencial,UFProjeto,Area,Segmento,Mecanismo,NomeProjeto,CgcCpf,Situacao,DtProtocolo,DtAnalise,
          OrgaoOrigem,Orgao,DtSituacao,ProvidenciaTomada,ResumoProjeto,DtInicioExecucao,DtFimExecucao,SolicitadoReal,
          idProjeto,Processo,Logon)
        SELECT TOP 1 @AnoProjeto,@NrProjeto,u.Sigla,dbo.fnSelecionarArea(idPreProjeto),dbo.fnSelecionarSegmento(idPreProjeto),
               Mecanismo,NomeProjeto,a.CNPJCPF,'B11',getdate(),getdate(),@idOrgao,@idOrgao,getdate(),
               'Proposta transformada em projeto cultural',ResumoDoProjeto,DtInicioDeExecucao,DtFinalDeExecucao,
               dbo.fnSolicitadoNaProposta(idPreProjeto),idPreProjeto,@Processo,@idUsuario
               FROM PreProjeto p
               INNER JOIN Agentes.dbo.Agentes a on (p.idAgente = a.idAgente)
               INNER JOIN Agentes.dbo.EnderecoNacional e on (a.idAgente = e.idAgente and e.Status = 1)
               INNER JOIN Agentes.dbo.UF u on (e.UF = u.idUF)
               WHERE idPreProjeto  = @idProposta and
                     NOT EXISTS(SELECT TOP 1 * FROM Projetos x WHERE p.idPreProjeto = x.idProjeto)
END
ELSE --Para edital
BEGIN

    INSERT INTO Projetos
         (AnoProjeto,Sequencial,UFProjeto,Area,Segmento,Mecanismo,NomeProjeto,CgcCpf,Situacao,DtProtocolo,DtAnalise,
          OrgaoOrigem,Orgao,DtSituacao,ProvidenciaTomada,ResumoProjeto,DtInicioExecucao,DtFimExecucao,SolicitadoReal,
          idProjeto,Processo,Logon)
        SELECT TOP 1 @AnoProjeto,@NrProjeto,u.Sigla,'7','76',
               '2',NomeProjeto,a.CNPJCPF,'G36',getdate(),getdate(),@idOrgao,@idOrgao,getdate(),
               'Proposta transformada em projeto cultural',ResumoDoProjeto,DtInicioDeExecucao,DtFinalDeExecucao,
               dbo.fnValorSolicitadoPropostaEdital(idPreProjeto),idPreProjeto,@Processo,@idUsuario
               FROM PreProjeto p
               INNER JOIN Agentes.dbo.Agentes a on (p.idAgente = a.idAgente)
               INNER JOIN Agentes.dbo.EnderecoNacional e on (a.idAgente = e.idAgente and e.Status = 1)
               INNER JOIN Agentes.dbo.UF u on (e.UF = u.idUF)
               WHERE idPreProjeto  = @idProposta and
                     NOT EXISTS(SELECT TOP 1 * FROM Projetos x WHERE p.idPreProjeto = x.idProjeto)
END


SELECT @Rows = @@ROWCOUNT, @Erro = @@ERROR

IF @Erro <> 0
   BEGIN
     INSERT INTO #Verificacao
        VALUES ('6.Erro ao tentar incluir %d registros na tabela Projetos.')
     SET @Flag = 1 
   END

IF @Rows <> 1
   BEGIN
     INSERT INTO #Verificacao
        VALUES ('7.Não é possível incluir mais de %d registros na tabela Projeto.')
     SET @Flag = 1 
   END

--========================================================================================================
-- Pegar o idPronac
--========================================================================================================
SELECT  @idPronac = idPronac FROM Projetos WHERE AnoProjeto = @AnoProjeto and Sequencial = @NrProjeto

IF @idEdital IS NULL --Validações que não servem pra Edital (Incentivo Fiscal)
BEGIN
    --========================================================================================================
    -- Inserir informações na tabela tbDistribuirParecer 
    --========================================================================================================
    -- Pegar a vinculada baseada no segmento cultural da unidade que avalia o produto principal
    SELECT @idVinculada = idOrgao FROM PlanoDistribuicaoProduto t
           INNER JOIN vSegmento s on (t.Segmento = s.Codigo)
           WHERE t.stPrincipal = 1 and idProjeto = @idProposta

    -- Análise de Conteúdo
    INSERT INTO tbDistribuirParecer
               (idPronac,idProduto,TipoAnalise,idOrgao,DtEnvio)
           SELECT @idPronac,idProduto,0,@idVinculada,getdate() FROM PlanoDistribuicaoProduto WHERE idProjeto = @idProposta

    SELECT @Rows = @@ROWCOUNT, @Erro = @@ERROR
       
    IF @Erro <> 0
       BEGIN
         INSERT INTO #Verificacao       
            VALUES ('8.Erro ao tentar incluir %d registros na tabela tbDistribuirParecer.')
         SET @Flag = 1 
       END

    -- Análise de Custo do Produto
    INSERT INTO tbDistribuirParecer
               (idPronac,idProduto,TipoAnalise,idOrgao,DtEnvio,stPrincipal)
           SELECT @idPronac,idProduto,1,@idVinculada,getdate(),stPrincipal FROM PlanoDistribuicaoProduto WHERE idProjeto = @idProposta

    SELECT @Rows = @@ROWCOUNT, @Erro = @@ERROR
       
    IF @Erro <> 0
       BEGIN
         INSERT INTO #Verificacao       
            VALUES ('9.Erro ao tentar incluir %d registros na tabela tbDistribuirParecer.')
         SET @Flag = 1 
       END

    -- Análise de Custo Administrativo do Projeto
    INSERT INTO tbDistribuirParecer
               (idPronac,idProduto,TipoAnalise,idOrgao,DtEnvio)
           SELECT @idPronac,idProduto,2,@idVinculada,getdate() FROM PlanoDistribuicaoProduto WHERE idProjeto = @idProposta and stPrincipal = 1

    SELECT @Rows = @@ROWCOUNT, @Erro = @@ERROR

    IF @Erro <> 0
       BEGIN
         INSERT INTO #Verificacao       
            VALUES ('10.Erro ao tentar incluir %d registros na tabela tbDistribuirParecer.')
         SET @Flag = 1 
       END

    --========================================================================================================
    -- Inserir informações na tabela tbPlanilhaProjeto
    --========================================================================================================
    INSERT INTO tbPlanilhaProjeto
               (idPlanilhaProposta,idPronac,idProduto,idEtapa,idPlanilhaItem,Descricao,idUnidade,
                Quantidade,Ocorrencia,ValorUnitario,QtdeDias,TipoDespesa,TipoPessoa,Contrapartida,FonteRecurso,UFDespesa,
                MunicipioDespesa,idUsuario)
           SELECT idPlanilhaProposta,@idPronac,idProduto,idEtapa,idPlanilhaItem,Descricao,
                  CASE
                    WHEN Unidade = 0
                      THEN 1
                      ELSE Unidade
                   end as Unidade,
                Quantidade,Ocorrencia,ValorUnitario,QtdeDias,TipoDespesa,TipoPessoa,Contrapartida,FonteRecurso,UFDespesa,
                MunicipioDespesa,0
                FROM tbPlanilhaProposta 
                WHERE idProjeto = @idProposta

    SELECT @Rows = @@ROWCOUNT, @Erro = @@ERROR
       
    IF @Erro <> 0
       BEGIN
         INSERT INTO #Verificacao       
            VALUES ('11.Erro ao tentar incluir %d registros na tabela tbPlanilhaProjeto.')
         SET @Flag = 1 
       END

    --========================================================================================================
    -- Inserir informações na tabela tbAnaliseDeConteudo
    --========================================================================================================
    INSERT INTO tbAnaliseDeConteudo
               (idPronac,idProduto)
           SELECT @idPronac,idProduto FROM tbPlanilhaProposta 
                  WHERE idProjeto = @idProposta AND idProduto <> 0
                  GROUP BY idProduto

    SELECT @Rows = @@ROWCOUNT, @Erro = @@ERROR
       
    IF @Erro <> 0
       BEGIN
         INSERT INTO #Verificacao       
            VALUES ('12.Erro ao tentar incluir %d registros na tabela tbPlanilhaProjeto.')
         SET @Flag = 1 
       END

    --========================================================================================================
    -- Inserir informações na tabela ContaBancaria
    --========================================================================================================
    INSERT INTO ContaBancaria
               (AnoProjeto,Sequencial,Mecanismo,Banco,Agencia,Logon)
           SELECT @AnoProjeto,@NrProjeto,Mecanismo,'001',AgenciaBancaria,@idUsuario 
                  FROM PreProjeto WHERE idPreProjeto = @idProposta

    SELECT @Rows = @@ROWCOUNT, @Erro = @@ERROR
       
    IF @Erro <> 0
       BEGIN
         INSERT INTO #Verificacao       
            VALUES ('13.Erro ao tentar incluir %d registros na tabela ContaBancaria.')
         SET @Flag = 1 
       END

    IF @Rows <> 1
       BEGIN
         INSERT INTO #Verificacao       
            VALUES ('14.Não é possível incluir mais de %d registros na ContaBancaria.')
         SET @Flag = 1 
       END
   
END

--================================================================================================================
-- Carregar informações de email
--================================================================================================================
IF NOT EXISTS (SELECT TOP 1 * FROM tbHistoricoEmail WHERE idPronac = @idPronac and idTextoEmail = 12 and
 (CONVERT(char(10),(DtEmail),111) = CONVERT(char(10),getdate(),111)))                                                             
   BEGIN
--================================================================================================================
-- Gravar o email enviado na tabela de Histórico de Email
--================================================================================================================
IF @idEdital IS NULL --(Incentivo Fiscal)
   BEGIN
     SET @idTextoEmail = 12

     INSERT INTO tbHistoricoEmail
                (idPronac,idTextoemail,DtEmail,stEstado,idUsuario)
         VALUES (@idPronac,@idTextoEmail,getdate(),1,@idUsuario)
      
     SELECT @Rows = @@ROWCOUNT, @Erro = @@ERROR
          
     IF @Erro <> 0
        BEGIN
          INSERT INTO #Verificacao       
            VALUES ('15.Erro ao inserir registros %d na tabela tbHistoricoEmail.')
          SET @Flag = 1 
        END
     
     IF @Rows <> 1
        BEGIN
          INSERT INTO #Verificacao       
            VALUES ('16.Não é permitido inserir %d registros ao mesmo tempo na tabela tbHistoricoEmail.')
          SET @Flag = 1 
       END

     SELECT @Mensagem = dsTexto FROM tbTextoEmail WHERE idTextoEmail = @idTextoEmail
   
     SELECT @NomeProposta=NomeProjeto,@Destinatario=Nome          
            FROM Projetos p
            INNER JOIN Interessado i on (p.CgcCpf = i.CgcCpf)
            WHERE idPronac = @idPronac
--================================================================================================================
-- Enviar email ao proponente
--================================================================================================================
     DECLARE MyCursor CURSOR FOR
     SELECT Descricao FROM agentes.dbo.Internet i
            INNER JOIN PreProjeto p on (i.idAgente = p.idAgente)
            WHERE p.idPreProjeto = @idProposta  and i.idAgente=p.idAgente and Status = 1 
          
     SET @Mensagem = '<b>Projeto: ' + CONVERT(VARCHAR(10),@AnoProjeto+@NrProjeto) + ' - ' + @NomeProposta + '<br> Proponente: ' + 
          @Destinatario + '<br> </b>' + @Mensagem

     OPEN MyCursor
     
     WHILE @@FETCH_STATUS = @@FETCH_STATUS 
       BEGIN
         FETCH NEXT FROM MyCursor INTO @Email
         IF (@@FETCH_STATUS = -2)          CONTINUE
         IF (@@FETCH_STATUS = -1)          BREAK

         EXEC msdb.dbo.sp_send_dbmail    @profile_name = 'PerfilGrupoPRONAC',
                                        @recipients = @Email,
                                        @body = @Mensagem,
                                        @body_format = 'HTML',
                                           @subject = 'Projeto Cultural',
                                        @exclude_query_output =1;
                                        
       END
     CLOSE MyCursor
     DEALLOCATE MyCursor
   END    
END
IF @Flag = 1
   BEGIN
    SELECT 1 as Flag,Descricao as Mensagem FROM #Verificacao
    ROLLBACK TRAN
   END
ELSE
  BEGIN
    COMMIT TRAN
    SELECT 0 as Flag,@AnoProjeto+@NrProjeto as Mensagem
  END
GO

GRANT EXECUTE ON dbo.paPropostaParaProjeto TO usuarios_internet
GO

