<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of VerificarSolicitacaodeReadequacoesDAO
 *
 * @author 01373930160
 */
class VerificarSolicitacaodeReadequacoesDAO extends MinC_Db_Table_Abstract {

    protected $_banco = "SAC";
    protected $_name = "Projetos";
    

    public function buscarProjetos($idPronac) {

        $sql = "select projetos.idProjeto,

                    projetos.IdPRONAC,
                    projetos.CgcCpf,
                    projetos.AnoProjeto+projetos.Sequencial as nrpronac,
                    projetos.NomeProjeto,
                    Agentes.Descricao,
                    Agentes.idAgente,
                    areaCultura.Codigo as 'codigoArea',
                    areaCultura.Descricao as 'areaCultura',
                    segmentoCultura.Codigo as 'codigoDescricao',
                    segmentoCultura.Descricao as 'segmentoCultura' from
                    sac.dbo.Projetos as projetos
                    inner join sac.dbo.Area as areaCultura
                    on projetos.Area = areaCultura.Codigo
                    left join sac.dbo.Segmento as segmentoCultura
                    on projetos.Segmento = segmentoCultura.Codigo
                    inner join sac.dbo.PreProjeto as pre
                    on projetos.idProjeto = pre.idPreProjeto
                    inner join agentes.dbo.Nomes as Agentes
                    on pre.idAgente = Agentes.idAgente

                where projetos.IdPRONAC = $idPronac";
        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        return $db->fetchAll($sql);
    }

    public function buscarProdutos($idPronac) {
//        $sql = "SELECT   DISTINCT  sac.dbo.Projetos.IdPRONAC, sac.dbo.Produto.Descricao,
//                      sac.dbo.Produto.Codigo AS idProduto
//FROM         sac.dbo.Produto INNER JOIN
//                      sac.dbo.PlanoDistribuicaoProduto ON sac.dbo.Produto.Codigo = sac.dbo.PlanoDistribuicaoProduto.idProduto CROSS JOIN
//                      sac.dbo.Projetos
//WHERE     (sac.dbo.Projetos.IdPRONAC = $idPronac) AND sac.dbo.PlanoDistribuicaoProduto.stPlanoDistribuicaoProduto = 1 ORDER BY idProduto ASC";
//
        $slct = $this->select();
        $slct->distinct();
        $slct->setIntegrityCheck(false);
        $slct->from(array('pr' => 'Projetos'),
                        array('IdPRONAC'),
                        "SAC"
                )
                ->joinInner(
                        array('prep' => 'PreProjeto'),
                        'prep.idPreProjeto = pr.idProjeto',
                        array('idPreprojeto'),
                        'SAC'
                )
                ->joinInner(
                        array('ag' => 'Agentes'),
                        'ag.idAgente = prep.idAgente',
                        array(
                            'TipoPessoa',
                            'idAgente'
                        ),
                        "agentes"
                )
                ->joinLeft(
                        array('tpap' => 'tbPedidoAlteracaoProjeto'),
                        'tpap.IdPRONAC = pr.IdPRONAC',
                        array(),
                        'bdcorporativo.scSAC'
                )
                ->joinLeft(
                        array('tpd' => 'tbPlanoDistribuicao'),
                        'tpd.idPedidoAlteracao = tpap.idPedidoAlteracao',
                        array('tpd.idProduto'),
                        'SAC'
                )
                ->joinLeft(
                        array('pd' => 'Produto'),
                        'tpd.idProduto = pd.Codigo',
                        array(
                            'pd.Descricao as produto'
                        ),
                        'SAC'
                )
                        
                ->where('pr.IdPRONAC= ?', $idPronac);
                
        return $this->fetchAll($slct);

//        $db= Zend_Db_Table::getDefaultAdapter();
//        $db->setFetchMode(Zend_DB::FETCH_OBJ);
//
//        return $db->fetchAll($sql);
    }

    public function buscaItem($idPronac, $idPlanilhaAprovacao, $idPlanilhaItem) {
        $sql = "SELECT tpa.idPlanilhaAprovacao,
                        tpa.idProduto,
                        tpa.IdPRONAC,
                        sac.dbo.tbPlanilhaEtapa.idPlanilhaEtapa,
                        sac.dbo.tbPlanilhaItens.idPlanilhaItens,
                        sac.dbo.tbPlanilhaUnidade.idUnidade,
                        agentes.dbo.Verificacao.idVerificacao,
                        tpa.qtItem,
                        tpa.nrOcorrencia,
                        tpa.vlUnitario,
                        tpa.qtDias,
                        CAST(tpa.dsJustificativa AS TEXT) AS dsJustificativa,
                        tpa.idPedidoAlteracao,
                        tpa.idAgente,
                        tpa.tpAcao,
                        agentes.dbo.Municipios.Descricao AS DescricaoMunicipio,
                        sac.dbo.tbPlanilhaItens.Descricao AS DescricaoItem,
                        agentes.dbo.UF.Descricao AS DescricaoUF,
                        agentes.dbo.Verificacao.Descricao AS DescricaoFonteRecurso,
                        sac.dbo.tbPlanilhaEtapa.Descricao AS DescricaoEtapa,
                        sac.dbo.tbPlanilhaUnidade.Descricao AS DescricaoUnidade,
                        prod.Descricao AS dsProduto
                        FROM sac.dbo.tbPlanilhaAprovacao tpa
                        INNER JOIN sac.dbo.tbPlanilhaEtapa ON tpa.idEtapa = sac.dbo.tbPlanilhaEtapa.idPlanilhaEtapa
                        INNER JOIN sac.dbo.tbPlanilhaItens ON tpa.idPlanilhaItem = sac.dbo.tbPlanilhaItens.idPlanilhaItens
                        INNER JOIN sac.dbo.tbPlanilhaUnidade ON tpa.idUnidade = sac.dbo.tbPlanilhaUnidade.idUnidade
                        INNER JOIN agentes.dbo.Verificacao ON tpa.nrFonteRecurso = agentes.dbo.Verificacao.idVerificacao
                        INNER JOIN agentes.dbo.UF ON tpa.idUFDespesa = agentes.dbo.UF.idUF
                        INNER JOIN agentes.dbo.Municipios ON tpa.idMunicipioDespesa = agentes.dbo.Municipios.idMunicipioIBGE
                        LEFT JOIN sac.dbo.Produto prod ON tpa.idProduto = prod.Codigo
                        WHERE (tpa.IdPRONAC = $idPronac) AND (tpa.idPlanilhaAprovacao = $idPlanilhaAprovacao) AND (tpa.idPlanilhaItem = $idPlanilhaItem)
                        and tpa.dtPlanilha in (select max(dtPlanilha) from sac.dbo.tbPlanilhaAprovacao where IdPRONAC=tpa.IdPRONAC
                        and idPlanilhaAprovacao=tpa.idPlanilhaAprovacao and idPlanilhaItem=tpa.idPlanilhaItem)";

        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        return $db->fetchAll($sql);
    }

    public static function buscarProdutosItens($idPronac = null, $idEtapa = null, $idPlanilhaAprovacao=null, $situacao=null, $idProduto=null) {

        $sql = "SELECT a.IdPRONAC,
                    a.idPlanilhaAprovacao,
                    b.Descricao,
                    c.Descricao AS DescricaoEtapa,
                    d.Descricao AS DescricaoItem,
                    e.Descricao AS DescricaoProduto,
                    a.idUFDespesa,
                    a.idMunicipioDespesa,
                    g.Descricao AS DescricaoMunicipio,
                    f.Descricao AS DescricaoUF,
                    h.idAgente,
                    a.tpPlanilha,
                    b.idUnidade AS Unidade,
                    c.idPlanilhaEtapa,
                    d.idPlanilhaItens AS idPlanilhaItem,
                    f.idUF AS UF,
                    e.Codigo,
                    g.idMunicipioIBGE,
                    a.nrOcorrencia,
                    a.vlUnitario,
                    a.qtDias,
                    a.qtItem,
                    a.idUnidade,
                    a.tpAcao ,
                    CAST(a.dsJustificativa as TEXT) AS dsjustificativa,
                    a.stAtivo,
                    (a.nrOcorrencia * a.vlUnitario * a.qtDias) AS Total,
                    a.idProduto
            FROM sac.dbo.tbPlanilhaAprovacao        AS a
            INNER JOIN sac.dbo.tbPlanilhaUnidade    AS b ON a.idUnidade = b.idUnidade
            INNER JOIN sac.dbo.tbPlanilhaEtapa      AS c ON a.idEtapa = c.idPlanilhaEtapa
            INNER JOIN sac.dbo.tbPlanilhaItens      AS d ON a.idPlanilhaItem = d.idPlanilhaItens
            INNER JOIN sac.dbo.Produto              AS e ON a.idProduto = e.Codigo
            INNER JOIN agentes.dbo.UF               AS f ON a.idUFDespesa = f.idUF
            INNER JOIN agentes.dbo.Municipios       AS g ON a.idMunicipioDespesa = g.idMunicipioIBGE
            INNER JOIN agentes.dbo.Agentes          AS h ON a.idAgente = h.idAgente

            WHERE a.stAtivo = '$situacao'
            AND a.tpAcao is not null
            AND a.idPedidoAlteracao is not null
            AND a.tpPlanilha = 'PA'";
        if (!empty($idPronac) and !empty($idEtapa)) {
            $sql .= " AND  a.idEtapa = $idEtapa AND a.IdPRONAC = $idPronac ";
        }
        if (!empty($idPlanilhaAprovacao)) {
            $sql .=" AND a.idPlanilhaAprovacao=$idPlanilhaAprovacao";
        }
        if (!empty($idProduto)) {
            $sql .=" AND idProduto = $idProduto";
        }

        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);
        return $db->fetchAll($sql);
    }

    public static function buscarProdutosItensParecerista($idPronac = null, $idEtapa = null, $idPlanilhaAprovacao=null, $situacao=null, $idProduto=null) {

        $sql = "SELECT a.IdPRONAC,
                    a.idPlanilhaAprovacao,
                    b.Descricao,
                    c.Descricao AS DescricaoEtapa,
                    d.Descricao AS DescricaoItem,
                    e.Descricao AS DescricaoProduto,
                    a.idUFDespesa,
                    a.idMunicipioDespesa,
                    g.Descricao AS DescricaoMunicipio,
                    f.Descricao AS DescricaoUF,
                    h.idAgente,
                    a.tpPlanilha,
                    b.idUnidade AS Unidade,
                    c.idPlanilhaEtapa,
                    d.idPlanilhaItens AS idPlanilhaItem,
                    f.idUF AS UF,
                    e.Codigo,
                    g.idMunicipioIBGE,
                    a.nrOcorrencia,
                    a.vlUnitario,
                    a.qtDias,
                    a.qtItem,
                    a.idUnidade,
                    a.tpAcao ,
                    CAST(a.dsJustificativa as TEXT) AS dsjustificativa,
                    a.stAtivo,
                    (a.nrOcorrencia * a.vlUnitario * a.qtDias) AS Total,
                    a.idProduto
            FROM sac.dbo.tbPlanilhaAprovacao        AS a
            INNER JOIN sac.dbo.tbPlanilhaUnidade    AS b ON a.idUnidade = b.idUnidade
            INNER JOIN sac.dbo.tbPlanilhaEtapa      AS c ON a.idEtapa = c.idPlanilhaEtapa
            INNER JOIN sac.dbo.tbPlanilhaItens      AS d ON a.idPlanilhaItem = d.idPlanilhaItens
            INNER JOIN sac.dbo.Produto              AS e ON a.idProduto = e.Codigo
            INNER JOIN agentes.dbo.UF               AS f ON a.idUFDespesa = f.idUF
            INNER JOIN agentes.dbo.Municipios       AS g ON a.idMunicipioDespesa = g.idMunicipioIBGE
            INNER JOIN agentes.dbo.Agentes          AS h ON a.idAgente = h.idAgente 

            WHERE a.stAtivo = '$situacao'
            AND a.tpAcao is not null
            AND a.idPedidoAlteracao is not null
            AND a.tpPlanilha = 'SR'";
        if (!empty($idPronac) and !empty($idEtapa)) {
            $sql .= " AND  a.idEtapa = $idEtapa AND a.IdPRONAC = $idPronac ";
        }
        if (!empty($idPlanilhaAprovacao)) {
            $sql .=" AND a.idPlanilhaAprovacao=$idPlanilhaAprovacao";
        }
        if (!empty($idProduto)) {
            $sql .=" AND idProduto = $idProduto";
        }
//        

        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);
        return $db->fetchAll($sql);
    }

    public static function inserirCopiaPlanilha($idPronac, $idPedidoAlteracao) {
        $objAcesso= new Acesso();
        $sql = "insert into sac.dbo.tbPlanilhaAprovacao

                    SELECT
                    'tpPlanilha' = 'PA',
                    {$objAcesso->getDate()},
                    idPlanilhaProjeto,
                    idPlanilhaProposta,
                    IdPRONAC,
                    idProduto,
                    idEtapa,
                    idPlanilhaItem,
                    dsItem,
                    idUnidade,
                    qtItem,
                    nrOcorrencia,
                    vlUnitario,
                    qtDias,
                    tpDespesa,
                    tpPessoa,
                    nrContraPartida,
                    nrFonteRecurso,
                    idUFDespesa,
                    idMunicipioDespesa,
                    'dsJustificativa' = '',
                    idAgente,
                    idPlanilhaAprovacao,
                    $idPedidoAlteracao,
                    tpAcao,
                    idRecursoDecisao,
                    'stAtivo' = 'N'

                    FROM         sac.dbo.tbPlanilhaAprovacao
                    WHERE     (IdPRONAC = $idPronac) AND (stAtivo = 'N') AND (tpPlanilha = 'SR')

                    ";
        //die($sql);

        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        return $db->fetchAll($sql);
    }

    public static function copiaAprovada($idPronac, $idProduto, $idEtapa, $idPlanilhaItem) {
        $sql = "insert into sac.dbo.tbPlanilhaAprovacao

                    SELECT
                    'tpPlanilha' = 'PA',
                    dtPlanilha,
                    idPlanilhaProjeto,
                    idPlanilhaProposta,
                    IdPRONAC,
                    idProduto,
                    idEtapa,
                    idPlanilhaItem,
                    dsItem,
                    idUnidade,
                    qtItem,
                    nrOcorrencia,
                    vlUnitario,
                    qtDias,
                    tpDespesa,
                    tpPessoa,
                    nrContraPartida,
                    nrFonteRecurso,
                    idUFDespesa,
                    idMunicipioDespesa,
                    dsJustificativa,
                    idAgente,
                    idPlanilhaAprovacaoPai,
                    idPedidoAlteracao,
                    'tpAcao' = 'N',
                    idRecursoDecisao,
                    'stAtivo' = 'S'

                    FROM         sac.dbo.tbPlanilhaAprovacao
                    WHERE     (IdPRONAC = $idPronac) AND (idProduto = $idProduto) AND (idEtapa = $idEtapa) AND (idPlanilhaItem = $idPlanilhaItem) AND (stAtivo = 'S') AND (tpPlanilha <> 'PA') AND (tpPlanilha <> 'SR')

                    ";
        

        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        return $db->fetchAll($sql);
    }

    public static function verificaSubItem($idAvaliacaoSubItem, $where=null) {

        $sql = " SELECT * FROM bdcorporativo.scSac.tbAvaliacaoSubItemPedidoAlteracao WHERE idAvaliacaoItemPedidoAlteracao = $idAvaliacaoSubItem ";
        if($where){
            $sql .=  $where;
        }
        
        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        return $db->fetchAll($sql);
    }

    public static function verificaSubItemPedidoAlteracao($idAvaliacaoSubItem, $idPedidoAlteracao) {

        $sql = " SELECT stAvaliacaoSubItemPedidoAlteracao as stAvaliacao FROM bdcorporativo.scSac.tbAvaliacaoSubItemPedidoAlteracao
                WHERE idAvaliacaoItemPedidoAlteracao = $idPedidoAlteracao AND idAvaliacaoItemPedidoAlteracao = $idAvaliacaoSubItem";

        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        return $db->fetchAll($sql);
    }
  
    public static function verificaPedidoAlteracao($idPRONAC) {

        $sql = " SELECT idPedidoAlteracao FROM bdcorporativo.scSac.tbPedidoAlteracaoProjeto WHERE idPRONAC = $idPRONAC ";

        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        return $db->fetchAll($sql);
    }

    public static function verificaMudancaOrcamentaria($idPronac) {




        $sql = "select (select SUM (qtItem * nrOcorrencia * vlUnitario)
                            from sac.dbo.tbPlanilhaAprovacao
                            WHERE IdPRONAC = $idPronac and stAtivo= 'S') as totalS,
                            (select SUM (qtItem * nrOcorrencia * vlUnitario)
                            from sac.dbo.tbPlanilhaAprovacao
                            WHERE IdPRONAC = $idPronac and stAtivo= 'N' and tpAcao <> 'E') as totalN
                            ";

        //die($sql);
        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        return $db->fetchAll($sql);
    }

    public static function buscaIdAvaliacaoItemPedidoAlteracao($idPedidoAlteracao, $tpAlteracaoProjeto = null) {

        $sql = "select idAvaliacaoItemPedidoAlteracao from bdcorporativo.scsac.tbAvaliacaoItemPedidoAlteracao WHERE idPedidoAlteracao = $idPedidoAlteracao";
        if (!empty($tpAlteracaoProjeto)) :
        	$sql.= " AND tpAlteracaoProjeto = " . $tpAlteracaoProjeto;
        endif;
        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        return $db->fetchAll($sql);
    }

    public static function buscaIdAvaliacaoSubItemPedidoAlteracao($idItemAvaliacaoItemPedidoAlteracao) {

        $sql = "select TOP 1 idAvaliacaoSubItemPedidoAlteracao from bdcorporativo.scsac.tbAvaliacaoSubItemPedidoAlteracao WHERE idAvaliacaoItemPedidoAlteracao = $idItemAvaliacaoItemPedidoAlteracao ORDER BY idAvaliacaoSubItemPedidoAlteracao DESC ";
         $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        return $db->fetchAll($sql);
    }

    public static function buscaAvaliacoesSubItemPedidoAlteracao($idPedidoAlteracao, $idPlanilhaAprovacao, $idAvaliacaoItemPedidoAlteracao) {

        $sql = "SELECT b.*, c.* from bdcorporativo.scsac.tbAvaliacaoItemPedidoAlteracao AS a
                INNER JOIN bdcorporativo.scsac.tbAvaliacaoSubItemCusto AS b ON a.idAvaliacaoItemPedidoAlteracao = b.idAvaliacaoItemPedidoAlteracao
                INNER JOIN bdcorporativo.scsac.tbAvaliacaoSubItemPedidoAlteracao AS c ON c.idAvaliacaoSubItemPedidoAlteracao = b.idAvaliacaoSubItemPedidoAlteracao
                WHERE a.idPedidoAlteracao = $idPedidoAlteracao
                AND a.tpAlteracaoProjeto = 7
                AND b.idPlanilhaAprovacao = $idPlanilhaAprovacao
                AND a.idAvaliacaoItemPedidoAlteracao = $idAvaliacaoItemPedidoAlteracao
                AND b.idAvaliacaoItemPedidoAlteracao = $idAvaliacaoItemPedidoAlteracao";
        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        return $db->fetchRow($sql);
    }

    public static function buscarProdutosItensSemProduto($idPronac = null, $idEtapa = null, $idPlanilhaAprovacao=null, $situacao=null, $idProduto=null) {


        $sql = "SELECT  sac.dbo.tbPlanilhaAprovacao.IdPRONAC, sac.dbo.tbPlanilhaAprovacao.idPlanilhaAprovacao, sac.dbo.tbPlanilhaUnidade.Descricao,
                        sac.dbo.tbPlanilhaEtapa.Descricao AS DescricaoEtapa,
                      sac.dbo.tbPlanilhaItens.Descricao AS DescricaoItem,  sac.dbo.tbPlanilhaAprovacao.idUFDespesa,
                      sac.dbo.tbPlanilhaAprovacao.idMunicipioDespesa, agentes.dbo.Municipios.Descricao AS DescricaoMunicipio,
                      agentes.dbo.UF.Descricao AS DescricaoUF, agentes.dbo.Agentes.idAgente, sac.dbo.tbPlanilhaAprovacao.tpPlanilha,
                      sac.dbo.tbPlanilhaUnidade.idUnidade AS Unidade, sac.dbo.tbPlanilhaEtapa.idPlanilhaEtapa, sac.dbo.tbPlanilhaItens.idPlanilhaItens AS idPlanilhaItem, agentes.dbo.UF.idUF AS UF,
                      agentes.dbo.Municipios.idMunicipioIBGE, sac.dbo.tbPlanilhaAprovacao.nrOcorrencia,
                      sac.dbo.tbPlanilhaAprovacao.vlUnitario, sac.dbo.tbPlanilhaAprovacao.qtDias, sac.dbo.tbPlanilhaAprovacao.qtItem,
                      sac.dbo.tbPlanilhaAprovacao.idUnidade,sac.dbo.tbPlanilhaAprovacao.tpAcao , CAST(sac.dbo.tbPlanilhaAprovacao.dsJustificativa AS TEXT) AS dsjustificativa,
                      sac.dbo.tbPlanilhaAprovacao.stAtivo,
                      (sac.dbo.tbPlanilhaAprovacao.nrOcorrencia * sac.dbo.tbPlanilhaAprovacao.vlUnitario * sac.dbo.tbPlanilhaAprovacao.qtDias) AS Total
                        FROM         sac.dbo.tbPlanilhaAprovacao INNER JOIN
                      sac.dbo.tbPlanilhaUnidade ON sac.dbo.tbPlanilhaAprovacao.idUnidade = sac.dbo.tbPlanilhaUnidade.idUnidade INNER JOIN
                      sac.dbo.tbPlanilhaEtapa ON sac.dbo.tbPlanilhaAprovacao.idEtapa = sac.dbo.tbPlanilhaEtapa.idPlanilhaEtapa INNER JOIN
                      sac.dbo.tbPlanilhaItens ON sac.dbo.tbPlanilhaAprovacao.idPlanilhaItem = sac.dbo.tbPlanilhaItens.idPlanilhaItens INNER JOIN
                      agentes.dbo.UF ON sac.dbo.tbPlanilhaAprovacao.idUFDespesa = agentes.dbo.UF.idUF INNER JOIN
                      agentes.dbo.Municipios ON sac.dbo.tbPlanilhaAprovacao.idMunicipioDespesa = agentes.dbo.Municipios.idMunicipioIBGE INNER JOIN
                      agentes.dbo.Agentes ON sac.dbo.tbPlanilhaAprovacao.idAgente = agentes.dbo.Agentes.idAgente WHERE sac.dbo.tbPlanilhaAprovacao.stAtivo = '$situacao' and sac.dbo.tbPlanilhaAprovacao.tpAcao is not null and sac.dbo.tbPlanilhaAprovacao.idPedidoAlteracao is not null AND sac.dbo.tbPlanilhaAprovacao.tpPlanilha = 'PA'";
        if (!empty($idPronac) and !empty($idEtapa)) {
            $sql .= " AND  sac.dbo.tbPlanilhaAprovacao.idEtapa = $idEtapa AND sac.dbo.tbPlanilhaAprovacao.IdPRONAC = $idPronac ";
        }
        if (!empty($idPlanilhaAprovacao)) {
            $sql .=" AND sac.dbo.tbPlanilhaAprovacao.idPlanilhaAprovacao=$idPlanilhaAprovacao";
        }


        //die( "<pre>" . $sql) ;


        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        return $db->fetchAll($sql);
    }



    public static function atualizaPlanilhaAprovacao($idPlanilhaAprovacao, $tpAcao)
    {


        $sql = "UPDATE    sac.dbo.tbPlanilhaAprovacao
                SET   tpAcao = '$tpAcao'
        WHERE     (idPlanilhaAprovacao = $idPlanilhaAprovacao) AND tpPlanilha = 'PA' AND stAtivo = 'N'";

//die();
        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        return $db->fetchAll($sql);

    }



    public static function atualizaAvaliacaoSubItemPedidoAlteracao($idItemAvaliacaoItemPedidoAlteracao, $stAvaliacaoSubItemPedidoAlteracao, $dsAvaliacaoSubItemPedidoAlteracao) {

        $sql = "UPDATE bdcorporativo.SCsAC.tbAvaliacaoSubItemPedidoAlteracao
        set stAvaliacaoSubItemPedidoAlteracao = '$stAvaliacaoSubItemPedidoAlteracao' where idAvaliacaoItemPedidoAlteracao = $idItemAvaliacaoItemPedidoAlteracao";

        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        return $db->fetchAll($sql);
    }

    public static function atualizaAvaliacaoItemPedidoAlteracao($dsJustificativaAvaliador, $stDeferimento, $idPedidoAlteracao) {

        $objAcesso= new Acesso();
        $sql = "UPDATE bdcorporativo.scSac.tbAvaliacaoItemPedidoAlteracao
        SET stAvaliacaoSubItemPedidoAlteracao = $stDeferimento , dsAvaliacaoSubItemPedidoAlteracao = '$dsJustificativaAvaliador', dtInicioAvaliacao = {$objAcesso->getDate()}
        WHERE idPedidoAlteracao = $idPedidoAlteracao";
    }

    

    public static function inserirAvaliacaoSubItemPedidoAlteracao($dsJustificativaAvaliador, $stDeferimento, $idPedidoAlteracao, $idAvaliacaoSubItemPedidoAlteracao) {

        $sql = "INSERT INTO bdcorporativo.scSac.tbAvaliacaoSubItemPedidoAlteracao
        (idAvaliacaoItemPedidoAlteracao, stAvaliacaoSubItemPedidoAlteracao, dsAvaliacaoSubItemPedidoAlteracao)
            VALUES ($idAvaliacaoSubItemPedidoAlteracao, '$stDeferimento', '$dsJustificativaAvaliador')";
        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        return $db->fetchAll($sql);
    }

    public static function inserirAvaliacaoSubItemCusto($idItemAvaliacaoItemPedidoAlteracao, $idAvaliacaoSubItemPedidoAlteracao, $idPlanilhaAprovacao) {

        $sql = "INSERT INTO bdcorporativo.scSac.tbAvaliacaoSubItemCusto
                (idAvaliacaoItemPedidoAlteracao, idAvaliacaoSubItemPedidoAlteracao , idPlanilhaAprovacao)
                VALUES ($idItemAvaliacaoItemPedidoAlteracao, $idAvaliacaoSubItemPedidoAlteracao,  $idPlanilhaAprovacao)";$db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        return $db->fetchAll($sql);
    }

    public static function deletaPlanilhaAprovacaoExcluida($idPlanilhaAprovacao, $idProduto = null, $idEtapa = null, $idPronac = null, $idItem = null) {


        $sql = "DELETE FROM sac.dbo.tbPlanilhaAprovacao WHERE tpPlanilha = 'PA'";

        if ( !empty( $idPlanilhaAprovacao ) )
        {
             $sql .= " AND idPlanilhaAprovacao = $idPlanilhaAprovacao";
        }
        if ( !empty( $idPronac ) )
        {
             $sql .= " AND idPRONAC = $idPronac";
        }
        if ( !empty( $idEtapa ) )
        {
             $sql .= " AND idEtapa = $idEtapa";
        }
        if ( !empty( $idProduto ) )
        {
             $sql .= " AND idProduto = $idProduto";
        }
        if ( !empty( $idItem ) )
        {
             $sql .= " AND idPlanilhaItem = $idItem";
        }

        
        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        return $db->fetchAll($sql);
    }

    public static function atualizaPedidoAlteracao($idPronac, $idAgente, $idPedido, $dsAvaliacao, $tipoAlteracao = null) {




        $sql = "UPDATE    bdcorporativo.scsac.tbAvaliacaoItemPedidoAlteracao
                        SET stAvaliacaoItemPedidoAlteracao = '$tipoAlteracao', dsAvaliacao = '$dsAvaliacao' WHERE idPedidoAlteracao = $idPedido";


        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        return $db->fetchAll($sql);
    }

    public static function verificaPlanilhaAprovacao($idPronac) {
        $sql = "select * from sac.dbo.tbPlanilhaAprovacao WHERE idPRONAC = $idPronac AND tpPlanilha = 'PA'";
        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        return $db->fetchAll($sql);
    }

    public static function verificaStatus($idPedidoAlteracao, $tpAlteracaoProjeto = null) {
        $sql = "SELECT stAvaliacaoItemPedidoAlteracao FROM bdcorporativo.scsac.tbAvaliacaoItemPedidoAlteracao WHERE idPedidoAlteracao = $idPedidoAlteracao ";

		if (!empty($tpAlteracaoProjeto)) :
			$sql.= "AND tpAlteracaoProjeto = " . $tpAlteracaoProjeto;
		endif;

        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        return $db->fetchAll($sql);
    }

    public static function verificaStatusItemDeCusto($idPedidoAlteracao, $tpAlteracaoProjeto = null) {
        $sql = "SELECT a.*
                FROM bdcorporativo.scsac.tbAvaliacaoItemPedidoAlteracao AS a
                INNER JOIN bdcorporativo.scsac.tbAcaoAvaliacaoItemPedidoAlteracao AS b on a.idAvaliacaoItemPedidoAlteracao = b.idAvaliacaoItemPedidoAlteracao
                WHERE a.idPedidoAlteracao = $idPedidoAlteracao AND a.tpAlteracaoProjeto = 10 AND b.stAtivo = 0";

		if (!empty($tpAlteracaoProjeto)) :
			$sql.= "AND tpAlteracaoProjeto = " . $tpAlteracaoProjeto;
		endif;

        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        return $db->fetchRow($sql);
    }

    public static function verificaStatusFinal($idPedidoAlteracao) {
        $sql = "SELECT stAvaliacaoItemPedidoAlteracao as stAvaliacao FROM bdcorporativo.scsac.tbAvaliacaoItemPedidoAlteracao WHERE idPedidoAlteracao = $idPedidoAlteracao";

        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        return $db->fetchAll($sql);
    }

    public static function verificaAnalise( $idPlanilhaAprovacao, $idAvaliacaoItemPedidoAlteracao ) {
        $sql = " SELECT a.stAvaliacaoSubItemPedidoAlteracao as stAvaliacao, CAST (dsAvaliacaoSubItemPedidoAlteracao as TEXT) as dsAvaliacaoSubItemPedidoAlteracao
                FROM bdcorporativo.scsac.tbAvaliacaoSubItemPedidoAlteracao AS a
                INNER JOIN bdcorporativo.scsac.tbAvaliacaoSubItemCusto AS b ON a.idAvaliacaoItemPedidoAlteracao = b.idAvaliacaoItemPedidoAlteracao
                        AND a.idAvaliacaoSubItemPedidoAlteracao = b.idAvaliacaoSubItemPedidoAlteracao
                WHERE b.idPlanilhaAprovacao = $idPlanilhaAprovacao AND a.idAvaliacaoItemPedidoAlteracao = $idAvaliacaoItemPedidoAlteracao AND b.idAvaliacaoItemPedidoAlteracao = $idAvaliacaoItemPedidoAlteracao ";

        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        return $db->fetchAll($sql);
    }

    public static function verificaAvaliacaoAnalise() {
        $sql = "SELECT * FROM bdcorporativo.scsac.tbPedidoAlteracaoProjeto tpa
    INNER JOIN bdcorporativo.scsac.tbAvaliacaoItemPedidoAlteracao tai ON tpa.idPedidoAlteracao = tai.idPedidoAlteracao
    INNER JOIN bdcorporativo.scsac.tbAvaliacaoSubItemCusto tsi ON tsi.idAvaliacaoItemPedidoAlteracao = tai.idAvaliacaoItemPedidoAlteracao
    INNER JOIN bdcorporativo.scsac.tbAvaliacaoSubItemPedidoAlteracao tsu ON tsu.idAvaliacaoSubItemPedidoAlteracao = tsi.idAvaliacaoSubItemPedidoAlteracao";

        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        return $db->fetchAll($sql);
    }

    public function buscarUltimoRemetente($idacao) {
        $sql = "SELECT TOP 1 idAgenteRemetente AS idAgenteRemetente
                                    FROM bdcorporativo.scsac.tbAcaoAvaliacaoItemPedidoAlteracao
                                    WHERE idAvaliacaoItemPedidoAlteracao = $idacao
                                            AND idPerfilRemetente = 93
                                    ORDER BY dtEncaminhamento DESC";
        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_ASSOC);
        return $db->fetchRow($sql);
    }

    public function buscarUltimoRemetenteCoordParecerista($idacao) {
        $sql = "SELECT TOP 1 idAgenteRemetente AS idAgenteRemetente
                                    FROM bdcorporativo.scsac.tbAcaoAvaliacaoItemPedidoAlteracao
                                    WHERE idAvaliacaoItemPedidoAlteracao = $idacao
                                    ORDER BY dtEncaminhamento DESC";
        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_ASSOC);
        return $db->fetchRow($sql);
    }

    public function buscarUltimoRemetenteCoordPareceristaSemBD($idacao) {
        $sql = "SELECT TOP 1 idAgenteRemetente AS idAgenteRemetente
                                    FROM bdcorporativo.scsac.tbAcaoAvaliacaoItemPedidoAlteracao
                                    WHERE idAvaliacaoItemPedidoAlteracao = $idacao
                                    ORDER BY dtEncaminhamento DESC";
        return $sql;
    }

    public function buscarOrgao($idacao){
        $sql = "select idorgao from bdcorporativo.scSac.tbAcaoAvaliacaoItemPedidoAlteracao where idAvaliacaoItemPedidoAlteracao=$idacao";
        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_ASSOC);

        return $db->fetchRow($sql);
    }

    public function buscarOrgaoSemDB($idacao){
        $sql = "select idorgao from bdcorporativo.scSac.tbAcaoAvaliacaoItemPedidoAlteracao where idAvaliacaoItemPedidoAlteracao=$idacao";
        return $sql;
    }

    public function buscarEtapa() {
        $sql = "select idPlanilhaEtapa, Descricao, tpCusto from sac.dbo.tbPlanilhaEtapa";
        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        return $db->fetchAll($sql);
    }

    public function atualizarStatus($dados, $where) {
        
        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);
        $alterar = $db->update("bdcorporativo.scsac.tbavaliacaoitempedidoalteracao", $dados, $where);
        return $alterar;
    }
    
    public function atualizarPedido($dados, $where) {
        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);
        $db->update("bdcorporativo.scSac.tbPedidoAlteracaoProjeto", $dados, $where);
    }

    public function atualizarTipoAlteracao($dados, $where) {

        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);
        $alterar = $db->update("bdcorporativo.scSac.tbPedidoAlteracaoXTipoAlteracao", $dados, $where);
    }
    
    public function atualizarAvaliacaopedido($dados, $where) {

        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);
        $alterar = $db->update("bdcorporativo.scSac.tbAvaliacaoItemPedidoAlteracao", $dados, $where);
    }
    
    public function atualizarAvaliacaoAcao($dados, $where) {

        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);
        $alterar = $db->update("bdcorporativo.scSac.tbAcaoAvaliacaoItemPedidoAlteracao", $dados, $where);
    }
    
    public function insertAvaliacaoAcao($dados) {

        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);
        $alterar = $db->insert("bdcorporativo.scSac.tbAcaoAvaliacaoItemPedidoAlteracao", $dados);
    }

}

?>
