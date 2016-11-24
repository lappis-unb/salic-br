-- Acrescenta "Recolhimento" na etapa de Custos de Produto
UPDATE SAC.dbo.tbPlanilhaEtapa
SET tpCusto='P'
WHERE idPlanilhaEtapa=5;

-- Renomeia o título das etapas

UPDATE SAC.dbo.tbPlanilhaEtapa
SET Descricao='Pré-Produção'
WHERE idPlanilhaEtapa=1;

UPDATE SAC.dbo.tbPlanilhaEtapa
SET Descricao='Produção'
WHERE idPlanilhaEtapa=2;

UPDATE SAC.dbo.tbPlanilhaEtapa
SET Descricao='Pós-Produção'
WHERE idPlanilhaEtapa=3;

-- Altera a coluna idPolicaoDaLogo
ALTER TABLE sac.dbo.PlanoDistribuicaoProduto ALTER COLUMN idPosicaoDaLogo INT;

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