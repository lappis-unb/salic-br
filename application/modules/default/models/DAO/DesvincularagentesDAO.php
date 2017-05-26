<?php
/**
 * Modelo Disvincular Agentes
 * @author Equipe RUP - Politec
 * @since 07/06/2010
 * @version 1.0
 * @package application
 * @subpackage application.models
 * @copyright � 2010 - Minist�rio da Cultura - Todos os direitos reservados.
 * @link http://www.cultura.gov.br
 */

class DesvincularagentesDAO extends Zend_Db_Table {


	public static function buscaragentes($proponente, $cnpjcpfsuperior)
       {
	       $sql = "SELECT a.CNPJCPF,n.Descricao AS NomeAgente, ver.Descricao as vinculo
                       FROM agentes.dbo.Agentes as a   
                           INNER JOIN agentes.dbo.Visao v on (a.idAgente = v.idAgente) 
                           INNER JOIN agentes.dbo.Nomes as n on (a.idAgente = n.idAgente) and (n.TipoNome =18  or n.TipoNome = 19) 
                           INNER JOIN agentes.dbo.Vinculacao as vin on (a.idAgente = vin.idAgente) and (a.Usuario = vin.Usuario)
                           INNER JOIN agentes.dbo.Verificacao as ver on ver.idVerificacao = '198'
                           WHERE n.Descricao = " . $proponente . "  or a.CNPJCPFSuperior = " . $cnpjcpfsuperior . ""; 
	
           $db = Zend_Db_Table::getDefaultAdapter();
           $db->setFetchMode(Zend_DB :: FETCH_OBJ);
           $resultado = $db->fetchAll($sql);
           return $resultado;
       }

    

    public function buscaentidade($nome, $cnpjcpf) {

        $sql = "SELECT tb.idAgente as idVinculoPrincipal,tb.CNPJCPF,nm.Descricao AS nomeentidade, ve.Descricao as vinculo
                FROM (SELECT idAgente, CASE WHEN convert(NUMERIC,ag.CNPJCPF) = 0
                        THEN Ag.CNPJCPFSuperior ELSE ag.CNPJCPF END AS CNPJCPF, TipoPessoa
                        FROM agentes.dbo.Agentes ag) AS tb
			                INNER JOIN agentes.dbo.Nomes nm ON nm.idAgente = tb.idAgente
			                INNER JOIN agentes.dbo.visao vi ON vi.idAgente = tb.idAgente
			                INNER JOIN agentes.dbo.Verificacao ve ON ve.idVerificacao = vi.Visao
			                WHERE vi.Visao = '144' AND ";
        
        
        if ($nome && $cnpjcpf) 
        {
            $sql.= "(nm.Descricao like '%".$nome."%') AND tb.CNPJCPF = convert(NUMERIC,'" . $cnpjcpf . "')";
        }
        else 
        {
            if ($nome) 
            {
                $sql.= "nm.Descricao like '%".$nome."%'";
            }
            else 
            {
                $sql.= "tb.CNPJCPF = convert(NUMERIC,'" . $cnpjcpf . "')";
            }
        }

        $db = Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB :: FETCH_OBJ);
        $resultado = $db->fetchAll($sql);

        return $resultado;
    }


  /*  public static function agentesvinculados($idVinculadoPrincipal) {

        $sql = "SELECT ag.CNPJCPF,nm.Descricao AS NomeAgente, ag.idAgente, ve.Descricao as vinculo
                FROM agentes.dbo.Agentes as ag
                    INNER JOIN agentes.dbo.Nomes as nm on (ag.idAgente = nm.idAgente) and (nm.TipoNome = 18  or nm.TipoNome = 19)
                    INNER JOIN agentes.dbo.Vinculacao as v on (v.idVinculoPrincipal = $idVinculadoPrincipal)
                        INNER JOIN agentes.dbo.visao vi ON vi.idAgente = ag.idAgente
                        INNER JOIN agentes.dbo.Verificacao ve ON ve.idVerificacao = vi.Visao
                WHERE vi.Visao = '198'";

        $db = Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB :: FETCH_OBJ);
        $resultado = $db->fetchAll($sql);

        return $resultado;

    }
*/
	
	/****************************************************************************************************/
	
	
    public static function agentesvinculados($idVinculoPrincipal)
	{



        $sql = "SELECT    a.idAgente, a.CNPJCPF, a.CNPJCPFSuperior, n.idNome, n.TipoNome, n.Descricao AS NomeAgente, a.Usuario, ve.Descricao as vinculo

FROM         agentes.dbo.Agentes AS a LEFT OUTER JOIN

                      agentes.dbo.Nomes AS n ON a.idAgente = n.idAgente LEFT OUTER JOIN

                      agentes.dbo.Vinculacao AS v ON a.idAgente = v.idAgente

                      INNER JOIN agentes.dbo.visao vi ON vi.idAgente = a.idAgente
	              INNER JOIN agentes.dbo.Verificacao ve ON ve.idVerificacao = vi.Visao

WHERE     (a.TipoPessoa = 0) AND (n.TipoNome = 18)  and v.idVinculoPrincipal = $idVinculoPrincipal";
	
		$db = Zend_Db_Table::getDefaultAdapter();
		$db->setFetchMode(Zend_DB :: FETCH_OBJ);
		$resultado = $db->fetchAll($sql);
		return $resultado;
								
	}
	
	
    public static function desvincularagentes($idagente, $idVinculoPrincipal) {

        $sql = "DELETE FROM agentes.dbo.Vinculacao WHERE idAgente = ".$idagente." AND idVinculoPrincipal = ".$idVinculoPrincipal;

        $db = Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB :: FETCH_OBJ);
        
        $resultado = $db->query($sql);

    }
}