
CREATE FUNCTION dbo.fnLiberarLinks(@Acao tinyint, @CNPJCPF_Proponente varchar(14),@idUsuario_Logado int,@idPronac int)
RETURNS varchar(100)
AS
-- =============================================================================================
-- Autor: Rômulo Menhô Barbosa
-- Data de Criação: 14/11/2012
-- Descrição: Liberar Links
-- =============================================================================================
-- Data de Alteração: 28/01/2013
-- Motivo: Considerar que o proponente poderá apenas salvar a resposta da diligência sem enviar.
--         Portanto enquanto não enviar o link deverá ficar disponível ao proponente.
-- =============================================================================================
-- Data de Alteração: 13/03/2013
-- Motivo: Retirar o link de readequação temporáriamente.
-- =============================================================================================
-- Data de Alteração: 31/07/2013
-- Motivo: Incluir os links de Análise, Execução e Prestação de Contas.
-- =============================================================================================
-- Data de Alteração: 30/10/2013
-- Motivo: Nova versão
-- =============================================================================================
-- Data de Alteração: 22/11/2013
-- Motivo: Ajustar link de recurso
-- =============================================================================================
-- Data de Alteração: 25/02/2014
-- Motivo: Ajustar link para remanejamento até 20%
-- =============================================================================================
-- Data de Alteração: 05/09/2014
-- Motivo: Ajustar link para relatório final de cumprimento do objeto.
-- =============================================================================================
-- Data de Alteração: 28/04/2015
-- Motivo: Incluir restrição na fase 2.
--         Não liberar link após a análise do componente e sim apenas o fechamento da reunião
--         da CNIC.
-- =============================================================================================
-- Data de Alteração: 12/06/2015
-- Motivo: Ajustar links para readequeação.
-- =============================================================================================
-- Data de Alteração: 29/06/2015
-- Motivo: Ajustar links para remanejamento até DE 20 na fase 5.
-- =============================================================================================
-- Data de Alteração: 24/09/2015
-- Motivo: Ajustar links para o proponente alterar comprovação financeira após ser diligenciado.
-- =============================================================================================
-- Data de Alteração: 30/09/2015
-- Motivo: Fase 4- período de execução >= getdate()
-- =============================================================================================
-- Data de Alteração: 15/04/2016.
-- Motivo: Incluir opções para inibir os links Marcas e Solicitação de Prorrogação na fase 5
--         Permitir comprovações para projetos nas situações E74 e E75.
-- =============================================================================================
-- Data de Alteração: 20/04/2016.
-- Motivo: Retirar a restrição de limite de 20% de captação da fase 4 e 5.
-- =============================================================================================
-- Data de Alteração: 28/04/2016.
-- Motivo: Permitir readequar planilha na fase 5 nas seguintes situações: E13,E15,E23,E74,E75
-- =============================================================================================
-- Data de Alteração: 29/04/2016.
-- Motivo: Só liberar o link do Relatório de Cumprimento de Objeto na fase 4 após todos os
--         Relatórios Trimestrais forem enviados.
-- =============================================================================================
-- Data de Alteração: 05/05/2016.
-- Motivo: Só liberar o link de Solicitar Prorrogação e Marcas nas fase 3 e 4.
-- =============================================================================================
BEGIN
DECLARE @Rows                  int
DECLARE @Fase                  char(1)
DECLARE @Pronac                varchar(7)
DECLARE @AnoProjeto            char(2)
DECLARE @Sequencial            varchar(5)
DECLARE @NrReuniao             int
DECLARE @idNrReuniao           int
DECLARE @qtAEnviar             int
DECLARE @qtEnviados            int
DECLARE @ContaLiberada         char(1)
DECLARE @NrPortaria            varchar(10)
DECLARE @DtFinalExecucao       datetime
DECLARE @DtSituacao            datetime
DECLARE @DtReuniao             datetime
DECLARE @Situacao              char(3)
DECLARE @idDiligencia          int
DECLARE @idRecurso             int
DECLARE @Permissao             char(1) -- 0.Sem permissão; 1.Acesso liberado
DECLARE @Diligencia            char(1) --  0.Respondida; 1. A responder
DECLARE @Recursos              char(1) --  0.Sem Recurso; 1. Solicitar Recursos
DECLARE @Readequacao           char(1) --  0.Sem Pedido de Readequacao; 1. Permitir Solicitar Readequação
DECLARE @PercentualCaptado     money
DECLARE @ComprovacaoFinanceira char(1) --  0.Sem comprovacao Financeira; 1. Permitir Realizar a Comprovação Financeira
DECLARE @RelatorioTrimestral   char(1) --  0.Sem Relatorio Trimestral; 1. Permitir cadastrar Relatorio Trimestral
DECLARE @RelatorioFinal        char(1) --  0.Sem Relatorio Final; 1. Permitir cadastrar Relatorio de cumprimento do objeto
DECLARE @Analise               char(1) --  0.Sem visualização; 1. Com visualização
DECLARE @Execucao              char(1) --  0.Sem visualização; 1. Com visualização
DECLARE @PrestacaoDeContas     char(1) --  0.Sem visualização; 1. Com visualização
DECLARE @Readequacao_20        char(1) --  0.Sem Pedido de Readequacao; 1. Permitir Solicitar Readequação
DECLARE @SolicitarProrrogacao  char(1) --  0.Sem visualização; 1. Com visualização
DECLARE @Marcas                char(1) --  0.Sem visualização; 1. Com visualização

--=====================================================================================================
-- SETAR VARIÁVEIS
--=====================================================================================================
SET @Diligencia            = 0
SET @Recursos              = 0
SET @Readequacao           = 0
SET @PercentualCaptado     = 0
SET @ComprovacaoFinanceira = 0
SET @RelatorioTrimestral   = 0
SET @RelatorioFinal        = 0
SET @Analise               = 0
SET @Execucao              = 0
SET @PrestacaoDeContas     = 0
SET @Readequacao_20        = 0
SET @SolicitarProrrogacao  = 0
SET @Marcas                = 0

--=====================================================================================================
-- VERIFICAR PERMISSÃO
--=====================================================================================================
SELECT  @Permissao = SAC.dbo.fnVerificarPermissao(@Acao,@CNPJCPF_Proponente,@idUsuario_Logado,@idPronac)
--=====================================================================================================
-- PEGAR O PRONAC
--=====================================================================================================
SELECT @AnoProjeto = AnoProjeto, @Sequencial = Sequencial, @Situacao = Situacao, @DtSituacao = DtSituacao,
       @DtFinalExecucao = DtFimExecucao
       FROM Projetos WHERE idPronac = @idPronac
--=====================================================================================================
-- PEGAR O NÚMERO DA REUNIÃO DA CNIC QUE O PROJETO PARTICIPOU
--=====================================================================================================
SELECT @DtReuniao = dtFinal FROM BdCorporativo.scSAC.tbPauta a
       INNER JOIN tbReuniao b on (a.idNrReuniao = b.idNrReuniao)
       WHERE idPronac=@idPronac
--=====================================================================================================
-- VERIFICAR SE A CONTA FOI LIBERADA
--=====================================================================================================
SELECT @ContaLiberada = 'S' FROM LIBERACAO WHERE AnoProjeto = @AnoProjeto AND Sequencial = @Sequencial
SELECT @Rows = @@ROWCOUNT
IF @Rows = 0
   SET @ContaLiberada = 'N'
--=====================================================================================================
-- VERIFICAR ENVIO DE RELATÓRIO TRIMESTRAL
--=====================================================================================================
SELECT @qtAEnviar = dbo.fnQtdeRelatorioTrimestral(@idPronac)
SELECT @qtEnviados = COUNT(*) FROM SAC.DBO.tbComprovanteTrimestral WHERE siComprovanteTrimestral <> 1 and idPronac = @idPronac
--=====================================================================================================
-- VERIFICAR EXISTÊNCIA DE PORTARIA
--=====================================================================================================
SELECT @NrPortaria = SAC.dbo.fnNrPortariaAprovacao(@AnoProjeto,@Sequencial)
--=====================================================================================================
-- VERIFICAR PERCENTUAL DE CAPTAÇÃO
--=====================================================================================================
SELECT  @PercentualCaptado = ISNULL(SAC.dbo.fnPercentualCaptado(@AnoProjeto,@Sequencial),0)
--=====================================================================================================
-- VERIFICAR SE HÁ DILIGÊNCIA PARA RESPONDER.
--=====================================================================================================
SELECT  @idDiligencia = idDiligencia
        FROM tbDiligencia
        WHERE stEstado = 0 and ((DtResposta IS NULL AND stEnviado = 'S') or
                                (DtResposta IS not NULL AND stEnviado = 'N')) AND idPronac = @idPronac
SELECT @Rows = @@ROWCOUNT
IF @Rows <> 0
   SET @Diligencia = 1
--=====================================================================================================
-- VERIFICAR SE HÁ RECURSO
--=====================================================================================================

IF (DATEDIFF(DAY,@DtReuniao,GETDATE()) < = 11
       AND @Situacao in ('A14','A16','A17','A20','A23','A24','A41','A42','D02','D03','D14')
       AND NOT EXISTS(SELECT TOP 1 * FROM tbRecurso WHERE stEstado = 1 and siFaseProjeto = 2 and siRecurso = 0 AND idPronac = @idPronac)
       AND NOT EXISTS(SELECT  TOP 1 * FROM tbRecurso WHERE stEstado = 0 AND siRecurso <> 0 AND idPronac = @idPronac))
   OR (NOT EXISTS(SELECT  TOP 1 * FROM tbRecurso WHERE stEstado = 0 AND tpRecurso = 2 AND idPronac = @idPronac)
       AND @Situacao in ('A14','A16','A17','A20','A23','A24','A41','A42','D02','D03','D14')
       AND (ISNULL(DATEDIFF(DAY,(SELECT dtFinal FROM sac.dbo.TBRecurso a
                                       INNER JOIN tbReuniao b on (a.idNrReuniao = b.idNrReuniao)
                                       WHERE a.tpRecurso = 1 AND a.siRecurso <> 0 AND a.stEstado = 1 AND a.idPronac = @idPronac),GETDATE()),90)) <= 10)
      BEGIN
     SELECT @Rows = @@ROWCOUNT
     IF @Rows = 0
        SET @Recursos = 1
   END

--=====================================================================================================
-- IDENTIFICAR A FASE DO PROJETO
--=====================================================================================================
-- FASE 2: DA TRANSFORMAÇÃO DA PROPOSTA EM PROJETO ATÉ O ENCERRAMENTO DA CNIC.
IF (@NrPortaria IS NULL  OR @NrPortaria = '')
    AND @Situacao NOT IN ('B11','B14','C10','C20','C30','D20')
    AND NOT EXISTS(SELECT * FROM BDCorporativo.scsac.tbPauta a
                            INNER JOIN sac.dbo.tbReuniao b on (a.idNrReuniao = b.idNrReuniao)
                            WHERE b.stEstado = 0 and a.idPronac = @idPronac)
   BEGIN
     SET @Fase = 2
     SET @Analise = 1
   END
ELSE
IF @NrPortaria IS NULL
  BEGIN
     SET @Fase = 2
     SET @Analise = 0
  END
ELSE
--=====================================================================================================
-- FASE 3 : DA PUBLICAÇÃO DA PORTARIA DE APROVAÇÃO ATÉ A LIBERAÇÃO DA CONTA
--=====================================================================================================
IF (@NrPortaria IS NOT NULL OR @NrPortaria <> '') AND @ContaLiberada = 'N'
   BEGIN
	 SET @Fase = 3
	 SET @Analise = 1
	 SET @Execucao = 1
	 SET @PrestacaoDeContas = 0
	 SET @SolicitarProrrogacao  = 0
     SET @Marcas                = 0
   END
ELSE
--=====================================================================================================
-- FASE 4 : DA LIBERAÇÃO DA CONTA ATÉ O DATA FINAL DO PERÍODO DE EXECUÇÃO
--=====================================================================================================
IF @ContaLiberada = 'S' AND CONVERT(CHAR(8),@DtFinalExecucao,112) > = CONVERT(CHAR(8),GETDATE(),112)
   BEGIN
     SET @Analise = 1
     SET @Execucao = 1
     SET @PrestacaoDeContas = 1
     SET @ComprovacaoFinanceira = 1
     SET @RelatorioTrimestral = 1
     SET @Readequacao = 1
 	 SET @SolicitarProrrogacao  = 1
     SET @Marcas                = 1
    --===============================================================================================
     -- CHECAR SE EXISTE READEQUAÇÃO DE 20%
     --===============================================================================================
     IF NOT EXISTS(SELECT TOP 1 * FROM SAC.DBO.tbReadequacao a
                                  INNER JOIN SAC.DBO.tbTipoReadequacao b on (a.idTipoReadequacao = b.idTipoReadequacao)
                                  WHERE a.idPronac = @idPronac AND b.idTipoReadequacao = 1)
        BEGIN
          SET @Readequacao_20 = 1
        END
     ELSE
         BEGIN
           SET @Readequacao_20 = 0
         END

     --===============================================================================================
     -- CHECAR SE EXISTE RELATORIO DE CUMPRIMENTO DO OBJETO PARA SER ENVIADO
     --===============================================================================================
     IF EXISTS(SELECT TOP 1 * FROM tbCumprimentoObjeto WHERE siCumprimentoObjeto <> 1 AND idPronac = @idPronac)
        BEGIN
          SET @Readequacao_20 = 0
          SET @Readequacao = 0
          SET @ComprovacaoFinanceira = 0
          SET @RelatorioTrimestral = 0
          SET @RelatorioFinal = 0
        END
     ELSE
        BEGIN
          --===============================================================================================
          -- CHECAR SE EXISTE RELATORIO TRIMESTRAL PARA SER ENVIADO
          --===============================================================================================
	      IF (@qtAEnviar - @qtEnviados) = 0
             BEGIN
               SET @RelatorioTrimestral = 0
		       SET @RelatorioFinal = 1
             END
          ELSE
	         BEGIN
               SET @RelatorioTrimestral = 1
	           SET @RelatorioFinal = 0
             END
        END

	 SET @Fase = 4
   END
ELSE
--=====================================================================================================
-- FASE 5 : PRESTAÇÃO DE CONTAS DO PROPONENTE - RELATÓRIO DE CUMPRIMENTO DO OBJETO
--=====================================================================================================
IF @ContaLiberada = 'S' AND CONVERT(CHAR(8),GETDATE(),112) > CONVERT(CHAR(8),@DtFinalExecucao,112)
    BEGIN
      --===============================================================================================
      -- SETAR VARIÁVEIS
      --===============================================================================================
      SET @Analise = 1
      SET @Execucao = 1
      SET @PrestacaoDeContas = 1
      SET @Marcas = 0
      SET @SolicitarProrrogacao  = 0
      SET @Readequacao = 0
	  SET @Readequacao_20 = 0
      SET @ComprovacaoFinanceira = 1
      SET @RelatorioTrimestral = 0
      SET @RelatorioFinal = 1

      --===============================================================================================
      -- EXCESSÃO PARA AJUSTAR PLANILHA PARA PRESTAR CONTAS
      --===============================================================================================
	  IF @Situacao IN ('E13','E15','E23','E74','E75')
         BEGIN
	       SET @Readequacao_20 = 1
		   SET @Readequacao = 1
         END
     --===============================================================================================
     -- CHECAR SE EXISTE RELATORIO DE CUMPRIMENTO DO OBJETO PARA SER ENVIADO
     --===============================================================================================
      IF EXISTS(SELECT TOP 1 * FROM tbCumprimentoObjeto WHERE siCumprimentoObjeto <> 1 AND idPronac = @idPronac)
         BEGIN
           SET @ComprovacaoFinanceira = 0
           SET @RelatorioFinal = 0
		   IF @Diligencia = 1
	          BEGIN
                SET @ComprovacaoFinanceira = 1
		      END
         END
	  ELSE
	     BEGIN
           --===============================================================================================
           -- CHECAR SE EXISTE READEQUAÇÃO DE 20%
           --===============================================================================================
           IF NOT EXISTS(SELECT TOP 1 *
		                        FROM SAC.DBO.tbReadequacao a
                                INNER JOIN SAC.DBO.tbTipoReadequacao b on (a.idTipoReadequacao = b.idTipoReadequacao)
                                WHERE a.idPronac = @idPronac AND b.idTipoReadequacao = 1)
              BEGIN
                SET @Readequacao_20 = 1
              END
           ELSE
              BEGIN
                SET @Readequacao_20 = 0
              END
	     END
      SET @Fase = 5
	END
--=====================================================================================================
-- RESULTADO
--=====================================================================================================
RETURN   (@Permissao + ' - ' + @Fase + ' - ' + @Diligencia + ' - ' + @Recursos       + ' - ' + @Readequacao          + ' - ' + @ComprovacaoFinanceira + ' - '+
          @RelatorioTrimestral + ' - '+ @RelatorioFinal + ' - '+ @Analise + ' - '+ @Execucao + ' - '+ @PrestacaoDeContas + ' - '+ @Readequacao_20+
		  ' - '+ @Marcas + ' - ' + @SolicitarProrrogacao)

END



