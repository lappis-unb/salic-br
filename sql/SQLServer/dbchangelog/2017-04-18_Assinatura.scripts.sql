-- =======================================================

/**
 * Adição da coluna 'cdSituacao' na tabela 'sac.dbo.tbDocumentoAssinatura';
 */
ALTER TABLE sac.dbo.tbDocumentoAssinatura ADD cdSituacao INT DEFAULT 1 NULL;
EXEC sp_addextendedproperty 'MS_Description', '1 - Aberto     - Disponível para assinatura
2 - Finalizado - Fechado para assinatura', 'SCHEMA', 'dbo', 'TABLE', 'tbDocumentoAssinatura', 'COLUMN', 'cdSituacao';