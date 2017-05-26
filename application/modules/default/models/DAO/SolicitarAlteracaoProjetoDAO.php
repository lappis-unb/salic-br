<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SolicitarAlteracaoProjetoDAO
 *
 * @author 01373930160
 */
class SolicitarAlteracaoProjetoDAO extends Zend_Db_Table
{


    public static function buscaProjetos($cpfCnpj = null)
    {
                $sql = "SELECT
                agentes.dbo.Agentes.idAgente, (SAC.dbo.Projetos.AnoProjeto +
                SAC.dbo.Projetos.Sequencial) AS nrPronac, SAC.dbo.Projetos.NomeProjeto,
                SAC.dbo.Projetos.Situacao, SAC.dbo.Projetos.DtSaida,
                      SAC.dbo.Projetos.DtRetorno, agentes.dbo.Agentes.CNPJCPF
                FROM         SAC.dbo.Projetos INNER JOIN
                      SAC.dbo.Aprovacao ON SAC.dbo.Projetos.IdPRONAC = SAC.dbo.Aprovacao.IdPRONAC INNER JOIN
                      agentes.dbo.Agentes ON SAC.dbo.Projetos.CgcCpf = agentes.dbo.Agentes.CNPJCPF
                      WHERE agentes.dbo.Agentes.CNPJCPF = $cpfCnpj";


                $db = Zend_Registry::get ( 'db' );
		$db->setFetchMode ( Zend_DB::FETCH_OBJ );

		return $db->fetchAll ( $sql );


    }

    	public function detalhesProjetos( $idPronac )
	{

                $sql = "select projetos.idProjeto,

                    projetos.IdPRONAC,
                    projetos.CgcCpf,
                    projetos.AnoProjeto+projetos.Sequencial as nrpronac,
                    projetos.NomeProjeto,
                    Agentes.Descricao,
                    areaCultura.Codigo as 'codigoArea',
                    areaCultura.Descricao as 'areaCultura',
                    segmentoCultura.Codigo as 'codigoDescricao',
                    segmentoCultura.Descricao as 'segmentoCultura' from
                    sac.dbo.Projetos as projetos
                    inner join SAC.dbo.Area as areaCultura
                    on projetos.Area = areaCultura.Codigo
                    left join SAC.dbo.Segmento as segmentoCultura
                    on projetos.Segmento = segmentoCultura.Codigo
                    inner join SAC.dbo.PreProjeto as pre
                    on projetos.idProjeto = pre.idPreProjeto
                    inner join agentes.dbo.Nomes as agentes
                    on pre.idAgente = Agentes.idAgente

                where projetos.IdPRONAC = $idPronac";
		$db = Zend_Registry::get ( 'db' );
		$db->setFetchMode ( Zend_DB::FETCH_OBJ );

		return $db->fetchAll ( $sql );

	}

        public function detalhesLocalizacao( $idPronac )
	{

                $sql = "SELECT
            Abrangencia.idUF, Abrangencia.idPais,
                            Abrangencia.idMunicipioIBGE, agentes.dbo.Uf.idUF AS UfLocal,
                             agentes.dbo.Uf.Descricao AS UfDescricao,
                      agentes.dbo.Municipios.idMunicipioIBGE AS idMunicipio,
            agentes.dbo.Municipios.Descricao AS DescicaoMunicipio, agentes.dbo.Pais.idPais AS idPais,
                      agentes.dbo.Pais.Descricao AS DescricaoPais, Projetos.IdPRONAC
            FROM         SAC.dbo.Abrangencia INNER JOIN
                                  SAC.dbo.PreProjeto ON Abrangencia.idProjeto = PreProjeto.idPreProjeto INNER JOIN
                                  SAC.dbo.Projetos ON PreProjeto.idPreProjeto = Projetos.idProjeto INNER JOIN
                                  agentes.dbo.Pais ON Abrangencia.idPais = agentes.dbo.Pais.idPais INNER JOIN
                                   agentes.dbo.Uf ON Abrangencia.idUF = agentes.dbo.Uf.idUF INNER JOIN
                                  agentes.dbo.Municipios ON Abrangencia.idMunicipioIBGE = agentes.dbo.Municipios.idMunicipioIBGE
            WHERE     (Projetos.IdPRONAC = $idPronac) AND SAC.dbo.Abrangencia.stAbrangencia = 1";
		$db = Zend_Registry::get ( 'db' );
		$db->setFetchMode ( Zend_DB::FETCH_OBJ );

		return $db->fetchAll ( $sql );

	}

}
?>
