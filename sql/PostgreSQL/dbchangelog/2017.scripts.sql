/**
  * Alteração do tamanhao da coluna "Senha" para 30
  */
ALTER TABLE controledeacesso."SGCacesso" ALTER COLUMN "Senha" TYPE CHAR(32) USING "Senha"::CHAR(32);