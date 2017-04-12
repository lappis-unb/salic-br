CREATE VIEW [dbo].[vSegmento]
(Area,Codigo,Segmento,idOrgao, tp_enquadramento) AS
(
	SELECT  CASE 
       WHEN substring(Codigo,1,1)='8' THEN '2' 
                                                ELSE substring(Codigo,1,1) 
                 END as Area,Codigo,Descricao,idOrgao, tp_enquadramento
    FROM Segmento  
    WHERE stEstado = 1
)