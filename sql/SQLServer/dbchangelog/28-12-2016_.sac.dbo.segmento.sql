/* ADIÇÃO DA COLUNA TP_ENQUADRAMENTO - onde os valores são : 1 para artigo 26 e 2 para artigo 18 */
ALTER TABLE sac.dbo.Segmento ADD tp_enquadramento CHAR(1) NULL;