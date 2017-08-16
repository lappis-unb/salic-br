<?php 
class ReadequacaoProjetos extends Zend_Db_Table {

    
    public function buscarProjetos($idPronac) {
        $sql0 = " select  projetos.idProjeto,

                    projetos.IdPRONAC,
                    projetos.CgcCpf,
                    projetos.AnoProjeto+projetos.Sequencial as pronac,
                    projetos.NomeProjeto,
                    nomes.Descricao,
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
                    inner join agentes.dbo.Agentes as Agentes
                    on projetos.CgcCpf = Agentes.CNPJCPF
                    inner join agentes.dbo.Nomes as nomes
                    on Agentes.idAgente = nomes.idAgente
                    where
                    projetos.IdPRONAC = $idPronac";

        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);
        return $db->fetchAll($sql0);
    }

    public function buscarLocais($idProjeto) {

        $sql0 = "   select pais.idPais, uf.idUF, mp.idMunicipioIBGE as idMunicipioIBGE ,idAbrangencia,idProjeto,pais.Descricao,uf.Sigla as sigla,mp.Descricao as cidade from sac.dbo.Abrangencia as ab
                    left join agentes.dbo.Municipios as mp
                    on ab.idMunicipioIBGE = mp.idMunicipioIBGE
                    left join agentes.dbo.UF as uf
                    on ab.idUF = uf.idUF
                    inner join agentes.dbo.Pais as pais
                    on pais.idPais = ab.idPais
                    where ab.idProjeto = $idProjeto AND ab.stAbrangencia = 1 
                    ORDER BY pais.Descricao, uf.Sigla, mp.Descricao";

        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);
        return $db->fetchAll($sql0);
    }

    public function buscarLocaisExterior($idPedidoAlteracao) {

        $sql0 = " select pais.idPais, idAbrangencia,idAbrangenciaAntiga,pais.Descricao,'-' as sigla,'-'as cidade from sac.dbo.tbAbrangencia as ab
                    inner join agentes.dbo.Pais as pais
                    on pais.idPais = ab.idPais
                    where ab.idPedidoAlteracao = $idPedidoAlteracao
                    and ab.tpAcao!='E'and pais.Descricao!= 'Brasil'";

        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);
        return $db->fetchAll($sql0);
    }

    public function buscarLocais2($idPedidoAlteracao) {

        $sql0 = " select pais.idPais, uf.idUF, mp.idMunicipioIBGE as idMunicipioIBGE ,idAbrangencia,idAbrangenciaAntiga,pais.Descricao,uf.Sigla as sigla,mp.Descricao as cidade, ab.tpAcao from sac.dbo.tbAbrangencia as ab
                    left join agentes.dbo.Municipios as mp
                    on ab.idMunicipioIBGE = mp.idMunicipioIBGE
                    left join agentes.dbo.UF as uf
                    on ab.idUF = uf.idUF
                    inner join agentes.dbo.Pais as pais
                    on pais.idPais = ab.idPais
                    where ab.idPedidoAlteracao = $idPedidoAlteracao
                    and ab.tpAcao!='E'
                    ORDER BY pais.Descricao, uf.Sigla, mp.Descricao";

        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);
        return $db->fetchAll($sql0);
    }

    public function updateLocais($idPais, $idUF, $idMunicipioIBGE, $tpAcao, $idPedidoAlteracao, $idAbrangencia) {
        $objAcesso= new Acesso();
        $sql0 = "  update sac.dbo.tbAbrangencia set idPais = $idPais, idUF= $idUF,idMunicipioIBGE = $idMunicipioIBGE,tpAcao = '$tpAcao',dtRegistro = {$objAcesso->getDate()}
                    where idPedidoAlteracao = $idPedidoAlteracao and idAbrangencia= $idAbrangencia";
        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);
        return $db->fetchAll($sql0);
    }

    public function excluirLocais($idAbrangencia, $dsJustificativaExclusao) {
        $objAcesso= new Acesso();
        $sql0 = "  update sac.dbo.tbAbrangencia set tpAcao = 'E', dtRegistro = {$objAcesso->getDate()}, dsExclusao='".$dsJustificativaExclusao."'
                    where idAbrangencia= $idAbrangencia";
        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);
        return $db->fetchAll($sql0);
    }

    public function insertLocais($idPais, $idUF, $idMunicipioIBGE, $idPedidoAlteracao, $tpAcao = 'I') {
        $objAcesso= new Acesso();
        $sql0 = " insert into sac.dbo.tbAbrangencia (idPais,idUF,idMunicipioIBGE,tpAbrangencia,tpAcao,idPedidoAlteracao,dtRegistro)
                    values ($idPais,$idUF,$idMunicipioIBGE,'SA','$tpAcao',$idPedidoAlteracao,{$objAcesso->getDate()})";

        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);
        return $db->fetchAll($sql0);
    }

    public function buscarLocaisCadastrados($idPais, $idUF, $idMunicipioIBGE, $idPedidoAlteracao) {
        $sql0 = " select * from sac.dbo.tbAbrangencia where idPais = $idPais and idUF = $idUF and  idMunicipioIBGE = $idMunicipioIBGE and idPedidoAlteracao = $idPedidoAlteracao";

        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);
        return $db->fetchAll($sql0);
    }
    public function buscarLocaisCadastradosFinal($idPedidoAlteracao) {
        $sql0 = " select * from bdcorporativo.scsac.tbPedidoAlteracaoXTipoAlteracao where tpAlteracaoProjeto  = 4 and idPedidoAlteracao = $idPedidoAlteracao";

        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);
        return $db->fetchAll($sql0);
    }

    public function buscaridPedidoAlteracao($idPedidoAlteracao) {
        $sql0 = "  select * from bdcorporativo.scsac.tbPedidoAlteracaoXTipoAlteracao
                    where idPedidoAlteracao =  $idPedidoAlteracao";
        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);
        return $db->fetchAll($sql0);
    }

    public function buscarTipoAlteracaoInserido($idPedidoAlteracao) {
        $sql0 = " select * from   bdcorporativo.scsac.tbPedidoAlteracaoXTipoAlteracao WHERE tpAlteracaoProjeto = 7 AND idPedidoAlteracao = $idPedidoAlteracao";
        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);
        return $db->fetchAll($sql0);
    }

    public function buscarProdutobd($idPedidoAlteracao, $idProduto) {
        $sql0 = "SELECT *, plano.stPrincipal, CAST(plano.dsjustificativa AS TEXT) AS JustificativaProponente
					FROM sac.dbo.tbPlanoDistribuicao AS plano 
						INNER JOIN sac.dbo.Segmento AS segmento ON plano.cdSegmento = segmento.Codigo
						LEFT JOIN bdcorporativo.scsac.tbPedidoAlteracaoXTipoAlteracao AS x ON plano.idPedidoAlteracao = x.idPedidoAlteracao
					WHERE plano.idPedidoAlteracao = $idPedidoAlteracao and plano.idProduto = $idProduto ";
        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);
        return $db->fetchAll($sql0);
    }

    public function buscarID($idPronac) {
        $sql0 = " select p.idProjeto from sac.dbo.Projetos as p where p.IdPRONAC = $idPronac";
        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);
        return $db->fetchAll($sql0);
    }

    public function buscarProdutosAtual($idProjeto) {
        $sql0 = "   select  produto.Descricao,plano.idProduto, plano.stPrincipal from sac.dbo.PlanoDistribuicaoProduto as plano
                    inner join sac.dbo.Produto as produto
                    on plano.idProduto = produto.Codigo
                    inner join sac.dbo.Segmento as segmento
                    on plano.Segmento = segmento.Codigo
                    where idProjeto = $idProjeto AND plano.stPlanoDistribuicaoProduto = 1 
                    ORDER BY plano.stPrincipal DESC, produto.Descricao";
        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);
        return $db->fetchAll($sql0);
    }

    public function buscarProdutosOpcao($idProjeto, $idProduto) {
        $sql0 = "SELECT *, plano.stPrincipal, CAST(pla.dsjustificativa AS TEXT) AS JustificativaProponente FROM sac.dbo.PlanoDistribuicaoProduto AS plano 
					INNER JOIN sac.dbo.Produto AS produto ON plano.idProduto = produto.Codigo 
					INNER JOIN sac.dbo.Segmento AS segmento ON plano.Segmento = segmento.Codigo 
					LEFT JOIN sac.dbo.tbPlanoDistribuicao AS pla ON plano.idPlanoDistribuicao = pla.idPlanoDistribuicao 
					LEFT JOIN bdcorporativo.scsac.tbPedidoAlteracaoXTipoAlteracao AS x ON pla.idPedidoAlteracao = x.idPedidoAlteracao
				WHERE plano.idProjeto = $idProjeto and plano.idProduto = $idProduto AND plano.stPlanoDistribuicaoProduto = 1";

        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);
        return $db->fetchAll($sql0);
    }

    public function buscarProdutosAtiva($idProjeto, $idProduto) {
        $sql0 = "   select  produto.Descricao,plano.idProduto as idProduto, plano.stPrincipal from sac.dbo.PlanoDistribuicaoProduto as plano
                    inner join sac.dbo.Produto as produto
                    on plano.idProduto = produto.Codigo
                    where idProjeto = $idProjeto and idProduto = $idProduto AND plano.stPlanoDistribuicaoProduto = 1";
        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);
        return $db->fetchAll($sql0);
    }

    public function buscarProdutostabelaAtiva($idProjeto) {
        $sql0 = "   select  produto.Descricao,plano.idProduto as idProduto, plano.stPrincipal from sac.dbo.PlanoDistribuicaoProduto as plano
                    inner join sac.dbo.Produto as produto
                    on plano.idProduto = produto.Codigo
                    where idProjeto = $idProjeto  AND plano.stPlanoDistribuicaoProduto = 1";

        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);
        return $db->fetchAll($sql0);
    }

    public function buscarSolicitacao($idPronac) {
        $sql1 = "select MAX(idPedidoAlteracao)as idPedidoAlteracao   from bdcorporativo.scsac.tbPedidoAlteracaoProjeto where IdPRONAC = $idPronac";
        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);
        return $db->fetchAll($sql1);
    }

    public function buscarprodutoSolicitado($idPedidoAlteracao) {
        $sql1 = "select plano.idProduto,
                produto.Descricao as Descricao,
                plano.stPrincipal 
                from sac.dbo.tbPlanoDistribuicao as plano
                inner join sac.dbo.Produto as produto
                on plano.idProduto = produto.Codigo
                where idPedidoAlteracao = $idPedidoAlteracao and plano.tpAcao!='E' 
                order by plano.stPrincipal DESC, produto.Descricao";
//        die($sql1);
        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);
        return $db->fetchAll($sql1);
    }

    public static function inserirProdutoPlano($idProjeto, $idPedidoAlteracao, $idProduto) {
        $objAcesso= new Acesso();
        $sql = "insert into sac.dbo.tbPlanoDistribuicao
                (idPlanoDistribuicao, cdArea, cdSegmento, idPedidoAlteracao,idProduto,idPosicaoLogo,qtPatrocinador,qtProduzida,qtOutros,qtVendaNormal,qtVendaPromocional,vlUnitarioNormal,vlUnitarioPromocional,stPrincipal,tpAcao,tpPlanoDistribuicao,dtPlanoDistribuicao)
                select
                plano.idPlanoDistribuicao, plano.Area, plano.Segmento, pedido.idPedidoAlteracao,plano.idProduto,plano.idPosicaoDaLogo,plano.QtdePatrocinador,plano.QtdeProduzida,plano.QtdeOutros,plano.QtdeVendaNormal,plano.QtdeVendaPromocional,plano.PrecoUnitarioNormal,plano.PrecoUnitarioPromocional,stPrincipal,'N','S',{$objAcesso->getDate()}   from sac.dbo.PlanoDistribuicaoProduto as plano,
                bdcorporativo.scsac.tbPedidoAlteracaoProjeto as pedido
                inner join sac.dbo.Projetos as projetos
                on projetos.IdPRONAC = pedido.IdPRONAC
                where plano.idProjeto = $idProjeto and pedido.idPedidoAlteracao = $idPedidoAlteracao and plano.idProduto = $idProduto AND plano.stPlanoDistribuicaoProduto = 1";
        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        return $db->fetchAll($sql);
    }

    public function inserirSolicitacao($idPronac, $idSolicitante, $stPedido, $siVerificacao = 0) {
        $db = Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB :: FETCH_OBJ);

        $objAcesso= new Acesso();
        $sql = "INSERT INTO bdcorporativo.scsac.tbPedidoAlteracaoProjeto (IdPRONAC, idSolicitante,dtSolicitacao,stPedidoAlteracao, siVerificacao)
                VALUES ('$idPronac','$idSolicitante',{$objAcesso->getDate()},'$stPedido', '$siVerificacao')";


        
        $resultado = $db->query($sql);
        return $resultado;
    }

    public function buscarProdutos($idPronac) {
        $sql1 = " select
                    projetos.IdPRONAC,
                    projetos.AnoProjeto,
                    projetos.Sequencial,
                    projetos.NomeProjeto,
                    produto.Codigo,
                    produto.Descricao
                    from sac.dbo.Projetos as projetos
                    inner join sac.dbo.ProjetoProduto as  ProjetoProduto
                    on projetos.AnoProjeto = ProjetoProduto.AnoProjeto
                    and projetos.Sequencial = ProjetoProduto.Sequencial
                    inner join sac.dbo.Produto as produto
                    on ProjetoProduto.CodigoProduto = produto.Codigo
                    where projetos.IdPRONAC = $idPronac";

        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        return $db->fetchAll($sql1);
    }

    public function buscarDescricao() {
        $sql1 = "Select
                produto.Codigo,
                produto.Descricao 
                from sac.dbo.Produto as produto 
                ORDER BY produto.Descricao";
        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        return $db->fetchAll($sql1);
    }

    public function buscarprodutoAcao($idProduto, $idPedidoAlteracao) {
        $sql1 = "select * from sac.dbo.tbPlanoDistribuicao where idPedidoAlteracao = $idPedidoAlteracao and idProduto = $idProduto";
        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        return $db->fetchAll($sql1);
    }

    public function buscarprodutoPlano($idProjeto, $idProduto) {
        $sql1 = "SELECT p.idPlanoDistribuicao
					,p.idProjeto
					,p.idProduto
					,p.Area
					,p.Segmento
					,p.idPosicaoDaLogo
					,p.QtdeProduzida
					,p.QtdePatrocinador
					,p.QtdeProponente
					,p.QtdeOutros
					,p.QtdeVendaNormal
					,p.QtdeVendaNormal
					,p.QtdeVendaPromocional
					,p.PrecoUnitarioNormal
					,p.PrecoUnitarioPromocional
					,p.stPrincipal
					,p.Usuario
					,p.dsJustificativaPosicaoLogo
					,a.Descricao AS dsArea
					,s.Descricao AS dsSegmento
					,s.Descricao AS Descricao
					,pla.dsjustificativa AS Justificativa
					,CAST(pla.dsjustificativa AS TEXT) AS JustificativaProponente
				 FROM sac.dbo.PlanoDistribuicaoProduto AS p
					LEFT JOIN sac.dbo.Area AS a ON a.Codigo = p.Area
					LEFT JOIN sac.dbo.Segmento AS s ON s.Codigo = p.Segmento 
					LEFT JOIN sac.dbo.tbPlanoDistribuicao AS pla ON p.idPlanoDistribuicao = pla.idPlanoDistribuicao
				 WHERE p.idProjeto = $idProjeto AND p.idProduto = $idProduto AND p.stPlanoDistribuicaoProduto = 1";
        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        return $db->fetchAll($sql1);
    }

    public function buscarPosicao() {
        $sql1 = "select Verificacao.idVerificacao as idVerificacao, ltrim(Verificacao.Descricao)as Descricao from sac.dbo.Verificacao as Verificacao
                inner join sac.dbo.Tipo as Tipo
                on Verificacao.idTipo = Tipo.idTipo
                where Tipo.idTipo = 3 
                ORDER BY Verificacao.Descricao";
        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        return $db->fetchAll($sql1);
    }

    public function acaoProduto($idPronac, $idProduto) {
        $sql1 = "select
                projetos.IdPRONAC,
                projetos.AnoProjeto,
                projetos.Sequencial,
                projetos.NomeProjeto,
                produto.Codigo,
                produto.Descricao

                from sac.dbo.Projetos as projetos
                inner join sac.dbo.ProjetoProduto as  ProjetoProduto
                on projetos.AnoProjeto = ProjetoProduto.AnoProjeto
                and projetos.Sequencial = ProjetoProduto.Sequencial
                inner join sac.dbo.Produto as produto
                on ProjetoProduto.CodigoProduto = produto.Codigo
                where projetos.IdPRONAC = $idPronac
                and produto.Codigo = $idProduto";
        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        return $db->fetchAll($sql1);
    }

    public function buscarProdutosPlano($idProjeto) {
        $sql0 = "select idProduto from sac.dbo.PlanoDistribuicaoProduto  where idProjeto = $idProjeto AND stPlanoDistribuicaoProduto = 1";
        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);
        return $db->fetchAll($sql0);
    }

    public function compararProdutos($idPedidoAlteracao, $idProjeto, $idProduto) {
        $sql0 = "select * from sac.dbo.PlanoDistribuicaoProduto as plano
                inner join sac.dbo.tbPlanoDistribuicao as plano2
                on plano.idProduto = plano2.idProduto
                where idPedidoAlteracao = $idPedidoAlteracao and idProjeto = $idProjeto and plano.idProduto = $idProduto AND plano.stPlanoDistribuicaoProduto = 1";
        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);
        return $db->fetchAll($sql0);
    }

    public function solicitarAlteracao($idPronac) {
        $sql0 = "";
        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        return $db->fetchAll($sql0);
    }

    public function inserirProduto($idPedidoAlteracao, $idProdutoNovo, $areaCultural, $segmentoCultural, $idPosicaoLogo, $qtProduzida, $qtPatrocinador, $qtOutros, $qtVendaNormal, $qtVendaPromocional, $vlUnitarioNormal, $vlUnitarioPromocional, $areaCultural, $segmentoCultural, $dsJustificativa = null) {
        $db = Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB :: FETCH_OBJ);
        $sql = "insert into sac.dbo.tbPlanoDistribuicao
                (idPedidoAlteracao,idProduto,cdArea,cdSegmento,idPosicaoLogo,qtProduzida,qtPatrocinador,qtOutros,qtVendaNormal,qtVendaPromocional,vlUnitarioNormal,vlUnitarioPromocional,stPrincipal,tpAcao,tpPlanoDistribuicao,dtPlanoDistribuicao, dsjustificativa)
                values
                ('$idPedidoAlteracao','$idProdutoNovo','$areaCultural','$segmentoCultural','$idPosicaoLogo','$qtProduzida','$qtPatrocinador','$qtOutros','$qtVendaNormal','$qtVendaPromocional','$vlUnitarioNormal','$vlUnitarioPromocional',0,'I','S','" . date('Y-m-d H:i:s') . "', '".$dsJustificativa."')";
        $resultado = $db->fetchAll($sql);

        return $resultado;
    }

    public static function inserirPedidoTipo($idPedidoAlteracao, $justificativa) {


        $sql = "INSERT INTO
                bdcorporativo.scsac.tbPedidoAlteracaoXTipoAlteracao(idPedidoAlteracao, tpAlteracaoProjeto, dsJustificativa)
                VALUES     ($idPedidoAlteracao,7,'$justificativa')";
        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        return $db->fetchAll($sql);
    }

    public static function atualizaPedidoTipoAlteracao($idPedidoAlteracao, $justificativa) {

        $sql = "UPDATE bdcorporativo.scsac.tbPedidoAlteracaoXTipoAlteracao
                SET     idPedidoAlteracao = $idPedidoAlteracao, tpAlteracaoProjeto = 7 , dsJustificativa = '$justificativa' WHERE idPedidoAlteracao = $idPedidoAlteracao";
        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        return $db->fetchAll($sql);
    }

    public static function verificaPedidoTipoAlteracao($idPedidoAlteracao) {
        $sql = "select TOP 1 idPedidoAlteracao
                from bdcorporativo.scsac.tbPedidoAlteracaoXTipoAlteracao WHERE idPedidoAlteracao = $idPedidoAlteracao order by idPedidoAlteracao desc";
        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        return $db->fetchAll($sql);
    }

    public static function verificarBotao($idPedidoAlteracao) {
        $sql = "select * from bdcorporativo.scsac.tbPedidoAlteracaoProjeto where idPedidoAlteracao=$idPedidoAlteracao and stPedidoAlteracao = 'A'";
        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);
        return $db->fetchAll($sql);
    }

    public static function verificarMenu($idPronac) {
        $sql = "select stPedidoAlteracao from bdcorporativo.scsac.tbPedidoAlteracaoProjeto where idPronac = $idPronac";
        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);
        return $db->fetchAll($sql);
    }

    public static function verificarProposta($idPedidoAlteracao) {
        $sql = "select * from sac.dbo.tbProposta where idPedidoAlteracao = $idPedidoAlteracao";
        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        return $db->fetchAll($sql);
    }

    public static function inserirProposta($dsEspecificacaotecnica, $idPedidoAlteracao) {

        $objAcesso= new Acesso();
        $sql = "insert into sac.dbo.tbProposta (tpProposta,dtProposta,dsEspecificacaoTecnica,idPedidoAlteracao)
                values ('SA',{$objAcesso->getDate()},'$dsEspecificacaotecnica',$idPedidoAlteracao);";
        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        return $db->fetchAll($sql);
    }

    public static function alterarPedido($idPedidoAlteracao, $status) {

        $objAcesso= new Acesso();
        $sql = "update bdcorporativo.scsac.tbPedidoAlteracaoProjeto
                set dtSolicitacao = {$objAcesso->getDate()},stPedidoAlteracao= '$status'
                where idPedidoAlteracao = $idPedidoAlteracao";

        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        return $db->fetchAll($sql);
    }

    public static function inserirJustificativa($idPedidoAlteracao, $dsJustificativa, $status) {

        $sql = "insert into bdcorporativo.scsac.tbPedidoAlteracaoXTipoAlteracao(idPedidoAlteracao,tpAlteracaoProjeto,dsJustificativa)
                values ($idPedidoAlteracao,$status,'$dsJustificativa')";
        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        return $db->fetchAll($sql);
    }

    public static function alterarJustificativa($idPedidoAlteracao, $dsJustificativa) {

        $sql = "UPDATE bdcorporativo.scsac.tbPedidoAlteracaoXTipoAlteracao SET dsJustificativa = '".$dsJustificativa."' WHERE idPedidoAlteracao = '".$idPedidoAlteracao."';";
        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        return $db->fetchAll($sql);
    }

    public function alterarProduto($idPedidoAlteracao, $idProdutoNovo, $idPosicaoLogo, $qtProduzida, $qtPatrocinador, $qtOutros, $qtVendaNormal, $qtVendaPromocional, $vlUnitarioNormal, $vlUnitarioPromocional, $areaCultural, $segmentoCultural, $dsJustificativa = null) {

        $sql = "update sac.dbo.tbPlanoDistribuicao set idPedidoAlteracao = $idPedidoAlteracao,idPosicaoLogo = $idPosicaoLogo, qtProduzida=$qtProduzida, qtPatrocinador=$qtPatrocinador, qtOutros=$qtOutros, qtVendaNormal=$qtVendaNormal, qtVendaPromocional = $qtVendaPromocional  , vlUnitarioNormal=$vlUnitarioNormal, vlUnitarioPromocional=$vlUnitarioPromocional
                ,cdArea = $areaCultural,cdSegmento = $segmentoCultural,tpAcao = 'A', dsjustificativa = '".$dsJustificativa."' 
                where idPedidoAlteracao = $idPedidoAlteracao and idProduto = $idProdutoNovo";

        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        return $db->fetchAll($sql);
    }

    public function updateProduto($idPedidoAlteracao, $idProdutoNovo, $idPosicaoLogo, $qtProduzida, $qtPatrocinador, $qtOutros, $qtVendaNormal, $qtVendaPromocional, $vlUnitarioNormal, $vlUnitarioPromocional) {
        $sql = "update sac.dbo.tbPlanoDistribuicao set idPedidoAlteracao = $idPedidoAlteracao,idPosicaoLogo = $idPosicaoLogo, qtProduzida=$qtProduzida, qtPatrocinador=$qtPatrocinador, qtOutros=$qtOutros, qtVendaNormal=$qtVendaNormal, qtVendaPromocional = $qtVendaPromocional  , vlUnitarioNormal=$vlUnitarioNormal, vlUnitarioPromocional=$vlUnitarioPromocional,tpAcao = 'A'
                where idPedidoAlteracao = $idPedidoAlteracao and idProduto = $idProdutoNovo";

        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        return $db->fetchAll($sql);
    }

    public function alterarSolicitacao($idPedidoAlteracao, $stPedido) {
        $sql = "update bdcorporativo.scsac.tbPedidoAlteracaoProjeto set stPedidoAlteracao = '$stPedido'
                where idPedidoAlteracao = $idPedidoAlteracao";

        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        return $db->fetchAll($sql);
    }

    public function excluirProduto($idPedidoAlteracao, $idProduto, $dsJustificativa = null) {
        $objAcesso= new Acesso();
        $sql = "update sac.dbo.tbPlanoDistribuicao  set tpAcao = 'E',dtPlanoDistribuicao = {$objAcesso->getDate()}, dsjustificativa = '".$dsJustificativa."' 
                where idPedidoAlteracao = $idPedidoAlteracao and idProduto = $idProduto ";
        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        return $db->fetchAll($sql);
    }

    public function BuscarPrazo($idPedidoAlteracao, $tpProrrogacao) {
    	$sql = "SELECT idPedidoAlteracao
					,tpProrrogacao
					,CONVERT(CHAR(10), dtInicioNovoPrazo, 103) AS dtInicioNovoPrazo
					,CONVERT(CHAR(10), dtFimNovoPrazo, 103) AS dtFimNovoPrazo
				FROM bdcorporativo.scsac.tbProrrogacaoPrazo 
				WHERE idPedidoAlteracao = $idPedidoAlteracao AND tpProrrogacao = '$tpProrrogacao'";
        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        return $db->fetchAll($sql);
    }


	public function BuscarPrazoProjetos($idPronac) {
    
        $sql = "select CONVERT(CHAR(10), DtInicioExecucao,103) AS DtInicioExecucao
                    ,CONVERT(CHAR(10), DtFimExecucao,103) AS DtFimExecucao from Sac.dbo.Projetos WHERE IdPRONAC = $idPronac";

        

        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        return $db->fetchAll($sql);
    }



    public function BuscarPrazoProjetosCaptacao($pronac) {

        $sql = "SELECT TOP 1 CONVERT(CHAR(10), DtInicioCaptacao,103) AS DtInicioCaptacao
                    ,CONVERT(CHAR(10), DtFimCaptacao,103) AS DtFimCaptacao
            FROM sac.dbo.Aprovacao
            WHERE AnoProjeto+Sequencial = $pronac
            AND TipoAprovacao in (1,3)
            ORDER BY idAprovacao DESC";

        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        return $db->fetchAll($sql);
    }



    public function insertPrazo($idPedidoAlteracao, $dtInicioNovoPrazo, $dtFimNovoPrazo, $tpProrrogacao) {
        $sql = "insert into bdcorporativo.scsac.tbProrrogacaoPrazo (idPedidoAlteracao,dtInicioNovoPrazo,dtFimNovoPrazo,tpProrrogacao)
                values ($idPedidoAlteracao,'$dtInicioNovoPrazo','$dtFimNovoPrazo','$tpProrrogacao')";
        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        return $db->fetchAll($sql);
    }

    public function updatePrazo($idPedidoAlteracao, $dtInicioNovoPrazo, $dtFimNovoPrazo, $tpProrrogacao) {
        $sql = "update bdcorporativo.scsac.tbProrrogacaoPrazo set dtInicioNovoPrazo = '$dtInicioNovoPrazo',dtFimNovoPrazo ='$dtFimNovoPrazo'
                where idPedidoAlteracao = $idPedidoAlteracao and tpProrrogacao = '$tpProrrogacao'";
        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        return $db->fetchAll($sql);
    }

    /*     * ************************************************************************************************************************
     * Fun��o que retorna os dados da pesquisa de acordo com o perfil
     * *********************************************************************************************************************** */

//SQL DO COORDENADOR DE ACOMPANHAMENTO
    public static function retornaSQL($sqlDesejado, $tpAlteracao, $unidade_autorizada = null) {
        $sql = '';

        if ($sqlDesejado == "sqlCoordAcomp") {
            
            $sql = "SELECT DISTINCT a.IdPRONAC AS ID_PRONAC,
                        b.AnoProjeto+b.Sequencial AS PRONAC,
                        b.NomeProjeto AS NomeProjeto,
                        c.Descricao AS areaDesc,
                        d.Descricao AS segmentoDesc,
                        a.dtSolicitacao AS DataEnvio,
                        b.Orgao,
                        a.stPedidoAlteracao,
                        g.idPedidoAlteracao,
                        g.tpAlteracaoProjeto,
                        a.siVerificacao,
                        g.stVerificacao AS stItem

                        FROM bdcorporativo.scsac.tbPedidoAlteracaoProjeto               AS a
                        INNER JOIN sac.dbo.Projetos                                     AS b ON a.IdPRONAC = b.IdPRONAC
                        INNER JOIN sac.dbo.Area                                         AS c ON b.Area = c.Codigo
                        LEFT JOIN sac.dbo.Segmento					AS d ON b.Segmento = d.Codigo
                        INNER JOIN sac.dbo.Abrangencia					AS e ON b.idProjeto = e.idProjeto AND e.stAbrangencia = 1 
                        INNER JOIN agentes.dbo.Municipios				AS f ON e.idMunicipioIBGE = f.idMunicipioIBGE
                        INNER JOIN bdcorporativo.scsac.tbPedidoAlteracaoXTipoAlteracao	AS g ON A.idPedidoAlteracao = g.idPedidoAlteracao
                        INNER JOIN agentes.dbo.UF					AS h ON e.idUF = h.idUF

                        WHERE a.stPedidoAlteracao = 'I'
                        AND a.siVerificacao in (0,1)
                        AND (g.stVerificacao = 0 or g.stVerificacao is null)
                        AND g.tpAlteracaoProjeto = $tpAlteracao ";

			if ($unidade_autorizada == 166) :
				$sql.= " AND b.Area = 2 ";  // quando for SAV/CGAV/CAP pega somente os projetos da �rea de Audiovisual
			elseif ($unidade_autorizada == 272) :
				$sql.= " AND b.Area <> 2 "; // quando for SEFIC/GEAR/SACAV pega somente os projetos das �reas que n�o sejam de Audiovisual
			else :
				$sql.= " AND b.Area = 0 ";  // quando for diferente de SAV/CGAV/CAP e SAV/CGAV/CAP pega somente os projetos da �rea de Audiovisual
			endif;

			$sql.= " ORDER BY a.dtSolicitacao";

        } else if ($sqlDesejado == "sqlCoordAcompProdutos") {

            $sql = "SELECT DISTINCT a.IdPRONAC AS ID_PRONAC,
                        b.AnoProjeto+b.Sequencial AS PRONAC,
                        b.NomeProjeto AS NomeProjeto,
                        c.Descricao AS areaDesc,
                        d.Descricao AS segmentoDesc,
                        a.dtSolicitacao AS DataEnvio,
                        b.Orgao,
                        a.stPedidoAlteracao,
                        g.idPedidoAlteracao,
                        g.tpAlteracaoProjeto,
                        a.siVerificacao,
                        g.stVerificacao AS stItem,
                        CASE
						WHEN (g.tpAlteracaoProjeto) = 7
							THEN 'Produtos'
							ELSE 'Item de Custo'
						END AS Situacao

                        FROM bdcorporativo.scsac.tbPedidoAlteracaoProjeto               AS a
                        INNER JOIN sac.dbo.Projetos                                     AS b ON a.IdPRONAC = b.IdPRONAC
                        INNER JOIN sac.dbo.Area                                         AS c ON b.Area = c.Codigo
                        LEFT JOIN sac.dbo.Segmento					AS d ON b.Segmento = d.Codigo
                        INNER JOIN sac.dbo.Abrangencia					AS e ON b.idProjeto = e.idProjeto AND e.stAbrangencia = 1 
                        INNER JOIN agentes.dbo.Municipios				AS f ON e.idMunicipioIBGE = f.idMunicipioIBGE
                        INNER JOIN bdcorporativo.scsac.tbPedidoAlteracaoXTipoAlteracao	AS g ON A.idPedidoAlteracao = g.idPedidoAlteracao
                        INNER JOIN agentes.dbo.UF					AS h ON e.idUF = h.idUF
                        ,(SELECT MAX(tpAlteracaoProjeto) AS tpAlteracaoProjeto, idPedidoAlteracao
							  FROM bdcorporativo.scsac.tbPedidoAlteracaoXTipoAlteracao
							  WHERE tpAlteracaoProjeto IN (7, 10)
							  GROUP BY idPedidoAlteracao) AS tmp
                        WHERE a.stPedidoAlteracao = 'I'
                        AND a.siVerificacao in (0,1)
                        AND (g.stVerificacao = 0 or g.stVerificacao is null)
                        AND g.tpAlteracaoProjeto = $tpAlteracao
                        AND tmp.idPedidoAlteracao = g.idPedidoAlteracao
                        AND tmp.tpAlteracaoProjeto = g.tpAlteracaoProjeto ";

			if ($unidade_autorizada == 166) :
				$sql.= " AND b.Area = 2 ";  // quando for SAV/CGAV/CAP pega somente os projetos da �rea de Audiovisual
			elseif ($unidade_autorizada == 272) :
				$sql.= " AND b.Area <> 2 "; // quando for SEFIC/GEAR/SACAV pega somente os projetos das �reas que n�o sejam de Audiovisual
			else :
				$sql.= " AND b.Area = 0 ";  // quando for diferente de SAV/CGAV/CAP e SAV/CGAV/CAP pega somente os projetos da �rea de Audiovisual
			endif;

			$sql.= " ORDER BY a.dtSolicitacao ";

        } else if ($sqlDesejado == "sqlUFs") {

            $sql = "SELECT DISTINCT a.IdPRONAC AS ID_PRONAC,
                        b.NomeProjeto AS NomeProjeto,
                        h.Sigla

                        FROM bdcorporativo.scsac.tbPedidoAlteracaoProjeto		AS a
                        INNER JOIN sac.dbo.Projetos					AS b ON a.IdPRONAC = b.IdPRONAC
                        INNER JOIN sac.dbo.Area						AS c ON b.Area = c.Codigo
                        LEFT JOIN sac.dbo.Segmento					AS d ON b.Segmento = d.Codigo
                        INNER JOIN sac.dbo.Abrangencia					AS e ON b.idProjeto = e.idProjeto AND e.stAbrangencia = 1 
                        INNER JOIN agentes.dbo.Municipios				AS f ON e.idMunicipioIBGE = f.idMunicipioIBGE
                        INNER JOIN bdcorporativo.scsac.tbPedidoAlteracaoXTipoAlteracao	AS g ON A.idPedidoAlteracao = g.idPedidoAlteracao
                        INNER JOIN agentes.dbo.UF					AS h ON e.idUF = h.idUF

                        WHERE a.stPedidoAlteracao = 'I' ";

        } else if ($sqlDesejado == "sqlCoordAcompDev") {

            $sql = "SELECT DISTINCT a.IdPRONAC AS ID_PRONAC,
                        b.AnoProjeto+b.Sequencial AS PRONAC,
                        b.NomeProjeto AS NomeProjeto,
                        c.Descricao AS areaDesc,
                        d.Descricao AS segmentoDesc,
                        i.dtInicioAvaliacao AS DataEnvio,
                        i.dtFimAvaliacao AS DataFim,
                        j.idOrgao,
                        a.stPedidoAlteracao,
                        g.idPedidoAlteracao,
                        g.tpAlteracaoProjeto,
                        a.siVerificacao,
                        g.stVerificacao AS stItem,
                        i.idAvaliacaoItemPedidoAlteracao,
                        j.idAcaoAvaliacaoItemPedidoAlteracao AS idAcao,
                        j.stVerificacao AS stAcao

                    FROM bdcorporativo.scsac.tbPedidoAlteracaoProjeto                   AS a
                    INNER JOIN sac.dbo.Projetos                                         AS b ON a.IdPRONAC = b.IdPRONAC
                    INNER JOIN sac.dbo.Area                                             AS c ON b.Area = c.Codigo
                    LEFT JOIN sac.dbo.Segmento                                         AS d ON b.Segmento = d.Codigo
                    INNER JOIN sac.dbo.Abrangencia                                      AS e ON b.idProjeto = e.idProjeto AND e.stAbrangencia = 1 
                    INNER JOIN agentes.dbo.Municipios                                   AS f ON e.idMunicipioIBGE = f.idMunicipioIBGE
                    INNER JOIN bdcorporativo.scsac.tbPedidoAlteracaoXTipoAlteracao      AS g ON A.idPedidoAlteracao = g.idPedidoAlteracao
                    INNER JOIN agentes.dbo.UF                                           AS h ON e.idUF = h.idUF
                    INNER JOIN bdcorporativo.scsac.tbAvaliacaoItemPedidoAlteracao       AS i ON g.idPedidoAlteracao = i.idPedidoAlteracao and g.tpAlteracaoProjeto = i.tpAlteracaoProjeto
                    INNER JOIN bdcorporativo.scsac.tbAcaoAvaliacaoItemPedidoAlteracao   AS j ON i.idAvaliacaoItemPedidoAlteracao = j.idAvaliacaoItemPedidoAlteracao

                    WHERE a.stPedidoAlteracao = 'I'
                    AND a.siVerificacao in (0,1)
                    AND g.stVerificacao in (2,3)
                    AND g.tpAlteracaoProjeto = $tpAlteracao
                    AND j.stAtivo = 0
                    AND j.idTipoAgente = 3
                    AND i.stAvaliacaoItemPedidoAlteracao in ('AP','IN')";

			if ($unidade_autorizada == 166) :
				$sql.= " AND b.Area = 2 ";  // quando for SAV/CGAV/CAP pega somente os projetos da �rea de Audiovisual
			elseif ($unidade_autorizada == 272) :
				$sql.= " AND b.Area <> 2 "; // quando for SEFIC/GEAR/SACAV pega somente os projetos das �reas que n�o sejam de Audiovisual
			else :
				$sql.= " AND b.Area = 0 ";  // quando for diferente de SAV/CGAV/CAP e SAV/CGAV/CAP pega somente os projetos da �rea de Audiovisual
			endif;

        } else if ($sqlDesejado == "sqlAnaliseGeral") {

            $sql = "SELECT DISTINCT a.IdPRONAC AS ID_PRONAC,
                        b.AnoProjeto+b.Sequencial AS PRONAC,
                        b.NomeProjeto AS NomeProjeto,
                        c.Descricao AS areaDesc,
                        d.Descricao AS segmentoDesc,
                        a.dtSolicitacao AS DataEnvio,
                        b.Orgao,
                        a.stPedidoAlteracao,
                        g.idPedidoAlteracao,
                        g.tpAlteracaoProjeto,
                        a.siVerificacao,
                        g.stVerificacao AS stItem

                        FROM bdcorporativo.scsac.tbPedidoAlteracaoProjeto		AS a
                        INNER JOIN sac.dbo.Projetos					AS b ON a.IdPRONAC = b.IdPRONAC
                        INNER JOIN sac.dbo.Area						AS c ON b.Area = c.Codigo
                        LEFT JOIN sac.dbo.Segmento					AS d ON b.Segmento = d.Codigo
                        INNER JOIN sac.dbo.Abrangencia					AS e ON b.idProjeto = e.idProjeto AND e.stAbrangencia = 1 
                        INNER JOIN agentes.dbo.Municipios				AS f ON e.idMunicipioIBGE = f.idMunicipioIBGE
                        INNER JOIN bdcorporativo.scsac.tbPedidoAlteracaoXTipoAlteracao	AS g ON A.idPedidoAlteracao = g.idPedidoAlteracao
                        INNER JOIN agentes.dbo.UF					AS h ON e.idUF = h.idUF

                        WHERE a.stPedidoAlteracao = 'I'
                        AND a.siVerificacao in (0,1)
                        AND (g.stVerificacao = 0 or g.stVerificacao is null) ";

        } else if ($sqlDesejado == "sqlAnaliseGeralDev") {

            $sql = "SELECT DISTINCT a.IdPRONAC AS ID_PRONAC,
                        b.AnoProjeto+b.Sequencial AS PRONAC,
                        b.NomeProjeto AS NomeProjeto,
                        c.Descricao AS areaDesc,
                        d.Descricao AS segmentoDesc,
                        j.dtEncaminhamento AS DataEnvio,
                        j.idOrgao,
                        a.stPedidoAlteracao,
                        g.idPedidoAlteracao,
                        g.tpAlteracaoProjeto,
                        a.siVerificacao,
                        g.stVerificacao AS stItem,
                        i.idAvaliacaoItemPedidoAlteracao,
                        j.idAcaoAvaliacaoItemPedidoAlteracao AS idAcao,
                        j.stVerificacao AS stAcao

                        FROM bdcorporativo.scsac.tbPedidoAlteracaoProjeto                   AS a
                        INNER JOIN sac.dbo.Projetos                                         AS b ON a.IdPRONAC = b.IdPRONAC
                        INNER JOIN sac.dbo.Area                                             AS c ON b.Area = c.Codigo
                        LEFT JOIN sac.dbo.Segmento                                         AS d ON b.Segmento = d.Codigo
                        INNER JOIN sac.dbo.Abrangencia                                      AS e ON b.idProjeto = e.idProjeto AND e.stAbrangencia = 1 
                        INNER JOIN agentes.dbo.Municipios                                   AS f ON e.idMunicipioIBGE = f.idMunicipioIBGE
                        INNER JOIN bdcorporativo.scsac.tbPedidoAlteracaoXTipoAlteracao      AS g ON A.idPedidoAlteracao = g.idPedidoAlteracao
                        INNER JOIN agentes.dbo.UF                                           AS h ON e.idUF = h.idUF
                        INNER JOIN bdcorporativo.scsac.tbAvaliacaoItemPedidoAlteracao       AS i ON g.idPedidoAlteracao = i.idPedidoAlteracao and g.tpAlteracaoProjeto = i.tpAlteracaoProjeto
                        INNER JOIN bdcorporativo.scsac.tbAcaoAvaliacaoItemPedidoAlteracao   AS j ON i.idAvaliacaoItemPedidoAlteracao = j.idAvaliacaoItemPedidoAlteracao

                        WHERE a.stPedidoAlteracao = 'I'
                        AND a.siVerificacao in (0,1)
                        AND g.stVerificacao in (3,2)
                        AND j.stAtivo = 0
                        AND j.idTipoAgente = 3
                        AND i.stAvaliacaoItemPedidoAlteracao in ('AP','IN') ";
        }

        return $sql;
    }

    /*     * ***********************************************************************************************************************
     * SQL DO COORDENADOR DE PARECERISTA
     * *********************************************************************************************************************** */

    public static function retornaSQLCP($sqlDesejado, $tpAlteracao, $AgenteAcionado, $idOrgao = null) {

        if ($sqlDesejado == "sqlCoordParecerista") {

            $sql = "SELECT DISTINCT a.IdPRONAC AS ID_PRONAC,
                        b.AnoProjeto+b.Sequencial AS PRONAC,
                        b.NomeProjeto AS NomeProjeto,
                        c.Descricao AS areaDesc,
                        d.Descricao AS segmentoDesc,
                        j.dtEncaminhamento AS DataEnvio,
                        j.idOrgao,
                        a.stPedidoAlteracao,
                        g.idPedidoAlteracao,
                        g.tpAlteracaoProjeto,
                        a.siVerificacao,
                        g.stVerificacao AS stItem,
                        i.idAvaliacaoItemPedidoAlteracao,
                        j.idAcaoAvaliacaoItemPedidoAlteracao AS idAcao,
                        j.stVerificacao AS stAcao,
                        j.idAgenteAcionado

                        FROM bdcorporativo.scsac.tbPedidoAlteracaoProjeto                   AS a
                        INNER JOIN sac.dbo.Projetos                                         AS b ON a.IdPRONAC = b.IdPRONAC
                        INNER JOIN sac.dbo.Area                                             AS c ON b.Area = c.Codigo
                        LEFT JOIN sac.dbo.Segmento                                         AS d ON b.Segmento = d.Codigo
                        INNER JOIN sac.dbo.Abrangencia                                      AS e ON b.idProjeto = e.idProjeto AND e.stAbrangencia = 1 
                        INNER JOIN agentes.dbo.Municipios                                   AS f ON e.idMunicipioIBGE = f.idMunicipioIBGE
                        INNER JOIN bdcorporativo.scsac.tbPedidoAlteracaoXTipoAlteracao      AS g ON A.idPedidoAlteracao = g.idPedidoAlteracao
                        INNER JOIN agentes.dbo.UF                                           AS h ON e.idUF = h.idUF
                        INNER JOIN bdcorporativo.scsac.tbAvaliacaoItemPedidoAlteracao       AS i ON g.idPedidoAlteracao = i.idPedidoAlteracao
                        INNER JOIN bdcorporativo.scsac.tbAcaoAvaliacaoItemPedidoAlteracao   AS j ON i.idAvaliacaoItemPedidoAlteracao = j.idAvaliacaoItemPedidoAlteracao

                        WHERE a.stPedidoAlteracao = 'I'
                        AND a.siVerificacao in (0,1)
                        AND g.stVerificacao = 1
                        AND g.tpAlteracaoProjeto = $tpAlteracao
                        AND i.tpAlteracaoProjeto = $tpAlteracao
                        AND j.stAtivo = 0
                        AND j.idTipoAgente = 2
                        AND i.stAvaliacaoItemPedidoAlteracao = 'AG'
                        AND j.idAgenteAcionado = $AgenteAcionado ";

					if (!empty($idOrgao)) :
						$sql.= " AND j.idOrgao = $idOrgao ";
					endif;

					$sql .= " ORDER BY j.dtEncaminhamento";

        } else if ($sqlDesejado == "sqlCoordPareceristaGeral") {

            $sql = "SELECT DISTINCT a.IdPRONAC AS ID_PRONAC,
                        b.AnoProjeto+b.Sequencial AS PRONAC,
                        b.NomeProjeto AS NomeProjeto,
                        c.Descricao AS areaDesc,
                        d.Descricao AS segmentoDesc,
                        j.dtEncaminhamento AS DataEnvio,
                        j.idOrgao,
                        a.stPedidoAlteracao,
                        g.idPedidoAlteracao,
                        g.tpAlteracaoProjeto,
                        a.siVerificacao,
                        g.stVerificacao AS stItem,
                        i.idAvaliacaoItemPedidoAlteracao,
                        j.idAcaoAvaliacaoItemPedidoAlteracao AS idAcao,
                        j.stVerificacao AS stAcao

                    FROM bdcorporativo.scsac.tbPedidoAlteracaoProjeto                   AS a
                    INNER JOIN sac.dbo.Projetos                                         AS b ON a.IdPRONAC = b.IdPRONAC
                    INNER JOIN sac.dbo.Area                                             AS c ON b.Area = c.Codigo
                    LEFT JOIN sac.dbo.Segmento                                         AS d ON b.Segmento = d.Codigo
                    INNER JOIN sac.dbo.Abrangencia                                      AS e ON b.idProjeto = e.idProjeto AND e.stAbrangencia = 1 
                    INNER JOIN agentes.dbo.Municipios                                   AS f ON e.idMunicipioIBGE = f.idMunicipioIBGE
                    INNER JOIN bdcorporativo.scsac.tbPedidoAlteracaoXTipoAlteracao      AS g ON A.idPedidoAlteracao = g.idPedidoAlteracao
                    INNER JOIN agentes.dbo.UF                                           AS h ON e.idUF = h.idUF
                    INNER JOIN bdcorporativo.scsac.tbAvaliacaoItemPedidoAlteracao       AS i ON g.idPedidoAlteracao = i.idPedidoAlteracao
                    INNER JOIN bdcorporativo.scsac.tbAcaoAvaliacaoItemPedidoAlteracao   AS j ON i.idAvaliacaoItemPedidoAlteracao = j.idAvaliacaoItemPedidoAlteracao
                    ,(SELECT MAX(tpAlteracaoProjeto) AS tpAlteracaoProjeto, idPedidoAlteracao
							  FROM bdcorporativo.scsac.tbPedidoAlteracaoXTipoAlteracao
							  WHERE tpAlteracaoProjeto IN (7, 10)
							  GROUP BY idPedidoAlteracao) AS tmp

                    WHERE a.stPedidoAlteracao = 'I'
                    AND a.siVerificacao in (0,1)
                    AND g.stVerificacao in (1,2)
                    AND j.stAtivo = 0
                    AND j.idTipoAgente = 2
                    AND g.idPedidoAlteracao = tmp.idPedidoAlteracao
                    AND g.tpAlteracaoProjeto = tmp.tpAlteracaoProjeto ";

        } else if ($sqlDesejado == "sqlCoordPareceristaDev") {

            $sql = "SELECT DISTINCT a.IdPRONAC AS ID_PRONAC,
                        b.AnoProjeto+b.Sequencial AS PRONAC,
                        b.NomeProjeto AS NomeProjeto,
                        c.Descricao AS areaDesc,
                        d.Descricao AS segmentoDesc,
                        j.dtEncaminhamento AS DataEnvio,
                        j.idOrgao,
                        a.stPedidoAlteracao,
                        g.idPedidoAlteracao,
                        g.tpAlteracaoProjeto,
                        a.siVerificacao,
                        g.stVerificacao AS stItem,
                        i.idAvaliacaoItemPedidoAlteracao,
                        j.idAcaoAvaliacaoItemPedidoAlteracao AS idAcao,
                        j.stVerificacao AS stAcao,
                        i.stAvaliacaoItemPedidoAlteracao AS situacao

                    FROM bdcorporativo.scsac.tbPedidoAlteracaoProjeto                   AS a
                    INNER JOIN sac.dbo.Projetos                                         AS b ON a.IdPRONAC = b.IdPRONAC
                    INNER JOIN sac.dbo.Area                                             AS c ON b.Area = c.Codigo
                    LEFT JOIN sac.dbo.Segmento                                         AS d ON b.Segmento = d.Codigo
                    INNER JOIN sac.dbo.Abrangencia                                      AS e ON b.idProjeto = e.idProjeto AND e.stAbrangencia = 1 
                    INNER JOIN agentes.dbo.Municipios                                   AS f ON e.idMunicipioIBGE = f.idMunicipioIBGE
                    INNER JOIN bdcorporativo.scsac.tbPedidoAlteracaoXTipoAlteracao      AS g ON A.idPedidoAlteracao = g.idPedidoAlteracao
                    INNER JOIN agentes.dbo.UF                                           AS h ON e.idUF = h.idUF
                    INNER JOIN bdcorporativo.scsac.tbAvaliacaoItemPedidoAlteracao       AS i ON g.idPedidoAlteracao = i.idPedidoAlteracao and g.tpAlteracaoProjeto = i.tpAlteracaoProjeto
                    INNER JOIN bdcorporativo.scsac.tbAcaoAvaliacaoItemPedidoAlteracao   AS j ON i.idAvaliacaoItemPedidoAlteracao = j.idAvaliacaoItemPedidoAlteracao

                    WHERE a.stPedidoAlteracao = 'I'
                    AND a.siVerificacao in (0,1)
                    AND g.stVerificacao in (1,2)
                    AND g.tpAlteracaoProjeto = $tpAlteracao
                    AND j.stAtivo = 0
                    AND j.idTipoAgente = 2
                    AND i.stAvaliacaoItemPedidoAlteracao != 'AG' 
                    AND j.idAgenteAcionado = $AgenteAcionado  ";

					if (!empty($idOrgao)) :
						$sql.= " AND j.idOrgao = $idOrgao ";
					endif;
        }


        return $sql;
    }

    /*     * ***********************************************************************************************************************
     * SQL DO PARECERISTA
     * *********************************************************************************************************************** */

    public static function retornaSQLPar($sqlDesejado, $tpAlteracao, $idOrgao = null, $idAgente = null) {

        if ($sqlDesejado == "sqlParecerista") {

            $sql = "SELECT DISTINCT a.IdPRONAC AS ID_PRONAC,
                        b.AnoProjeto+b.Sequencial AS PRONAC,
                        b.NomeProjeto AS NomeProjeto,
                        c.Descricao AS areaDesc,
                        d.Descricao AS segmentoDesc,
                        j.dtEncaminhamento AS DataEnvio,
                        b.Orgao,
                        a.stPedidoAlteracao,
                        g.idPedidoAlteracao,
                        g.tpAlteracaoProjeto,
                        a.siVerificacao,
                        g.stVerificacao AS stItem,
                        i.idAvaliacaoItemPedidoAlteracao,
                        j.idAcaoAvaliacaoItemPedidoAlteracao,
                        j.stVerificacao AS stAcao

                    FROM bdcorporativo.scsac.tbPedidoAlteracaoProjeto                   AS a
                    INNER JOIN sac.dbo.Projetos                                         AS b ON a.IdPRONAC = b.IdPRONAC
                    INNER JOIN sac.dbo.Area                                             AS c ON b.Area = c.Codigo
                    LEFT JOIN sac.dbo.Segmento                                         AS d ON b.Segmento = d.Codigo
                    INNER JOIN sac.dbo.Abrangencia                                      AS e ON b.idProjeto = e.idProjeto AND e.stAbrangencia = 1 
                    INNER JOIN agentes.dbo.Municipios                                   AS f ON e.idMunicipioIBGE = f.idMunicipioIBGE
                    INNER JOIN bdcorporativo.scsac.tbPedidoAlteracaoXTipoAlteracao      AS g ON A.idPedidoAlteracao = g.idPedidoAlteracao
                    INNER JOIN agentes.dbo.UF                                           AS h ON e.idUF = h.idUF
                    INNER JOIN bdcorporativo.scsac.tbAvaliacaoItemPedidoAlteracao       AS i ON g.idPedidoAlteracao = i.idPedidoAlteracao and g.tpAlteracaoProjeto = i.tpAlteracaoProjeto
                    INNER JOIN bdcorporativo.scsac.tbAcaoAvaliacaoItemPedidoAlteracao   AS j ON i.idAvaliacaoItemPedidoAlteracao = j.idAvaliacaoItemPedidoAlteracao

                    WHERE a.stPedidoAlteracao = 'I'
                    AND a.siVerificacao = 1
                    AND g.stVerificacao = 1
                    AND g.tpAlteracaoProjeto = $tpAlteracao
                    AND j.stAtivo = 0
                    AND j.idTipoAgente = 1 ";

					if (!empty($idOrgao)) :
						$sql.= " AND j.idOrgao = $idOrgao ";
					endif;

					if (!empty($idAgente)) :
						$sql.= " AND j.idAgenteAcionado = $idAgente ";
					endif;

					$sql.= " ORDER BY j.dtEncaminhamento";

        } else if ($sqlDesejado == "sqlPareceristaGeral") {

            $sql = "SELECT DISTINCT a.IdPRONAC AS ID_PRONAC,
                        b.AnoProjeto+b.Sequencial AS PRONAC,
                        b.NomeProjeto AS NomeProjeto,
                        c.Descricao AS areaDesc,
                        d.Descricao AS segmentoDesc,
                        j.dtEncaminhamento AS DataEnvio,
                        b.Orgao,
                        a.stPedidoAlteracao,
                        g.idPedidoAlteracao,
                        g.tpAlteracaoProjeto,
                        a.siVerificacao,
                        g.stVerificacao AS stItem,
                        i.idAvaliacaoItemPedidoAlteracao,
                        j.idAcaoAvaliacaoItemPedidoAlteracao AS idAcao,
                        j.stVerificacao AS stAcao

                    FROM bdcorporativo.scsac.tbPedidoAlteracaoProjeto                   AS a
                    INNER JOIN sac.dbo.Projetos                                         AS b ON a.IdPRONAC = b.IdPRONAC
                    INNER JOIN sac.dbo.Area                                             AS c ON b.Area = c.Codigo
                    LEFT JOIN sac.dbo.Segmento                                         AS d ON b.Segmento = d.Codigo
                    INNER JOIN sac.dbo.Abrangencia                                      AS e ON b.idProjeto = e.idProjeto AND e.stAbrangencia = 1 
                    INNER JOIN agentes.dbo.Municipios                                   AS f ON e.idMunicipioIBGE = f.idMunicipioIBGE
                    INNER JOIN bdcorporativo.scsac.tbPedidoAlteracaoXTipoAlteracao      AS g ON A.idPedidoAlteracao = g.idPedidoAlteracao
                    INNER JOIN agentes.dbo.UF                                           AS h ON e.idUF = h.idUF
                    INNER JOIN bdcorporativo.scsac.tbAvaliacaoItemPedidoAlteracao       AS i ON g.idPedidoAlteracao = i.idPedidoAlteracao
                    INNER JOIN bdcorporativo.scsac.tbAcaoAvaliacaoItemPedidoAlteracao   AS j ON i.idAvaliacaoItemPedidoAlteracao = j.idAvaliacaoItemPedidoAlteracao

                    WHERE a.stPedidoAlteracao = 'I'
                    AND a.siVerificacao = 1
                    AND g.stVerificacao = 1
                    AND j.stAtivo = 0
                    AND j.idTipoAgente = 1 ";
        }

        return $sql;
    }

    /*     * ***********************************************************************************************************************
     * SQL DO T�CNICO
     * *********************************************************************************************************************** */

    public static function retornaSQLTec($sqlDesejado, $tpAlteracao, $agenteAcionado, $orgaoAcionado) {

        if ($sqlDesejado == "sqlTecnico") {
            $sql = "SELECT DISTINCT a.IdPRONAC AS ID_PRONAC,
                        b.AnoProjeto+b.Sequencial AS PRONAC,
                        b.NomeProjeto AS NomeProjeto,
                        c.Descricao AS areaDesc,
                        d.Descricao AS segmentoDesc,
                        j.dtEncaminhamento AS DataEnvio,
                        j.idOrgao,
                        b.Orgao,
                        a.stPedidoAlteracao,
                        g.idPedidoAlteracao,
                        g.tpAlteracaoProjeto,
                        a.siVerificacao,
                        g.stVerificacao AS stItem,
                        i.idAvaliacaoItemPedidoAlteracao,
                        j.idAcaoAvaliacaoItemPedidoAlteracao AS idAcao,
                        j.stVerificacao AS stAcao

                    FROM bdcorporativo.scsac.tbPedidoAlteracaoProjeto                           AS a
                    INNER JOIN sac.dbo.Projetos                                         AS b ON a.IdPRONAC = b.IdPRONAC
                    INNER JOIN sac.dbo.Area                                             AS c ON b.Area = c.Codigo
                    LEFT JOIN sac.dbo.Segmento                                         AS d ON b.Segmento = d.Codigo
                    INNER JOIN sac.dbo.Abrangencia                                      AS e ON b.idProjeto = e.idProjeto AND e.stAbrangencia = 1 
                    INNER JOIN agentes.dbo.Municipios                                   AS f ON e.idMunicipioIBGE = f.idMunicipioIBGE
                    INNER JOIN bdcorporativo.scsac.tbPedidoAlteracaoXTipoAlteracao      AS g ON A.idPedidoAlteracao = g.idPedidoAlteracao
                    INNER JOIN agentes.dbo.UF                                           AS h ON e.idUF = h.idUF
                    INNER JOIN bdcorporativo.scsac.tbAvaliacaoItemPedidoAlteracao       AS i ON g.idPedidoAlteracao = i.idPedidoAlteracao and g.tpAlteracaoProjeto = i.tpAlteracaoProjeto
                    INNER JOIN bdcorporativo.scsac.tbAcaoAvaliacaoItemPedidoAlteracao   AS j ON i.idAvaliacaoItemPedidoAlteracao = j.idAvaliacaoItemPedidoAlteracao

                    WHERE a.stPedidoAlteracao = 'I'
                    AND a.siVerificacao = 1
                    AND g.stVerificacao = 1
                    AND g.tpAlteracaoProjeto = $tpAlteracao
                    AND j.idOrgao = $orgaoAcionado
                    AND j.stAtivo = 0
                    AND j.idTipoAgente = 5
                    AND j.idAgenteAcionado = $agenteAcionado
                    ORDER BY j.dtEncaminhamento";

        } else if ($sqlDesejado == "sqlTecnicoGeral") {
            $sql = "SELECT DISTINCT a.IdPRONAC AS ID_PRONAC,
                        b.AnoProjeto+b.Sequencial AS PRONAC,
                        b.NomeProjeto AS NomeProjeto,
                        c.Descricao AS areaDesc,
                        d.Descricao AS segmentoDesc,
                        j.dtEncaminhamento AS DataEnvio,
                        j.idOrgao,
                        b.Orgao,
                        a.stPedidoAlteracao,
                        g.idPedidoAlteracao,
                        g.tpAlteracaoProjeto,
                        a.siVerificacao,
                        g.stVerificacao AS stItem,
                        i.idAvaliacaoItemPedidoAlteracao,
                        j.idAcaoAvaliacaoItemPedidoAlteracao AS idAcao,
                        j.stVerificacao AS stAcao

                    FROM bdcorporativo.scsac.tbPedidoAlteracaoProjeto                           AS a
                    INNER JOIN sac.dbo.Projetos                                         AS b ON a.IdPRONAC = b.IdPRONAC
                    INNER JOIN sac.dbo.Area                                             AS c ON b.Area = c.Codigo
                    LEFT JOIN sac.dbo.Segmento                                         AS d ON b.Segmento = d.Codigo
                    INNER JOIN sac.dbo.Abrangencia                                      AS e ON b.idProjeto = e.idProjeto AND e.stAbrangencia = 1 
                    INNER JOIN agentes.dbo.Municipios                                   AS f ON e.idMunicipioIBGE = f.idMunicipioIBGE
                    INNER JOIN bdcorporativo.scsac.tbPedidoAlteracaoXTipoAlteracao      AS g ON a.idPedidoAlteracao = g.idPedidoAlteracao
                    INNER JOIN agentes.dbo.UF                                           AS h ON e.idUF = h.idUF
                    INNER JOIN bdcorporativo.scsac.tbAvaliacaoItemPedidoAlteracao       AS i ON g.idPedidoAlteracao = i.idPedidoAlteracao and g.tpAlteracaoProjeto = i.tpAlteracaoProjeto
                    INNER JOIN bdcorporativo.scsac.tbAcaoAvaliacaoItemPedidoAlteracao   AS j ON i.idAvaliacaoItemPedidoAlteracao = j.idAvaliacaoItemPedidoAlteracao

                    WHERE a.stPedidoAlteracao = 'I'
                    AND a.siVerificacao = 1
                    AND j.idOrgao = $orgaoAcionado
                    AND g.stVerificacao = 1
                    AND j.stAtivo = 0
                    AND j.idTipoAgente = 5 ";
        };
        
        return $sql;
    }

    /*     * *********************************************************************
      SQL - PROPOSTA PEDAG�GICA
     * ********************************************************************* */

    public static function retornaSQLproposta($sqlDesejado, $id_Pronac, $tipoAlteracao=null, $planoDistribuicaoObrigatorio = null, $idPedidoAlteracao = null) {
        $sql = '';

        if ($sqlDesejado == "sqlproposta") {
            $sql = "SELECT *,
                        CAST(Solicitacao AS text) as Solicitacao,
                        CAST(Justificativa AS text) as Justificativa ,
                        CAST(dsEstrategiaExecucao AS text) as dsEstrategiaExecucao,
                        CAST(dsEspecificacaoSolicitacao AS text) as dsEspecificacaoSolicitacao,
                        CAST(dsJustificativaSolicitacao AS text) as dsJustificativaSolicitacao
                        FROM(
                        SELECT DISTINCT b.IdPRONAC,
                        c.AnoProjeto+c.Sequencial AS PRONAC,
                        c.NomeProjeto AS NomeProjeto,
                        c.CgcCpf AS CNPJCPF,
                        f.Nome AS proponente,
                        f.CgcCpf,
                        d.EstrategiadeExecucao as EspecificacaoTecnica,
                        d.EspecificacaoTecnica as EstrategiadeExecucao,
                        a.dsObjetivos AS Solicitacao,
                        a.dsJustificativa AS Justificativa,
                        a.dsEstrategiaExecucao,
                        a.dsEspecificacaoTecnica as dsEspecificacaoSolicitacao,
                        g.dsJustificativa as dsJustificativaSolicitacao

                    FROM sac.dbo.tbProposta AS a
                    INNER JOIN bdcorporativo.scsac.tbPedidoAlteracaoProjeto             AS b ON b.idPedidoAlteracao = a.idPedidoAlteracao
                    INNER JOIN sac.dbo.Projetos                                         AS c ON c.IdPRONAC = b.IdPRONAC
                    INNER JOIN sac.dbo.PreProjeto                                       AS d ON d.idPreProjeto = c.idProjeto
                    INNER JOIN agentes.dbo.Agentes                                      AS e ON e.idAgente = d.idAgente
                    INNER JOIN sac.dbo.vProponenteProjetos                              AS f ON c.CgcCpf = f.CgcCpf
                    INNER JOIN bdcorporativo.scsac.tbPedidoAlteracaoXTipoAlteracao      AS g ON g.idPedidoAlteracao = a.idPedidoAlteracao

                    WHERE b.IdPRONAC = $id_Pronac AND g.tpAlteracaoProjeto = 6) as tabela";

        }

        if ($sqlDesejado == "sqlpropostadev") {
            $sql = "SELECT *,
                        CAST(Justificativa AS text) as Justificativa ,
                        CAST(dsJustificativa AS text) as dsJustificativa,
                        CAST(dsEstrategiaExecucao AS text) as dsEstrategiaExecucao,
                        CAST(dsEspecificacaoTecnica AS text) as dsEspecificacaoTecnica,
                        CAST(Solicitacao AS text) as Solicitacao
                        FROM(
            SELECT DISTINCT b.IdPRONAC,
                        c.AnoProjeto+c.Sequencial AS PRONAC,
                        c.NomeProjeto AS NomeProjeto,
                        c.CgcCpf AS CNPJCPF,
                        j.Nome AS proponente,
                        d.EstrategiadeExecucao as EspecificacaoTecnica,
                        d.EspecificacaoTecnica as EstrategiadeExecucao,
                        a.dsObjetivos AS Solicitacao,
                        a.dsJustificativa AS Justificativa,
                        a.dsEstrategiaExecucao,
                        a.dsEspecificacaoTecnica,
                        f.idPedidoAlteracao,
                        g.tpAlteracaoProjeto,
                        h.idAvaliacaoItemPedidoAlteracao AS idAvaliacao,
                        i.idAcaoAvaliacaoItemPedidoAlteracao AS idAcao,
                        a.IdProposta,
                        i.idOrgao,
                        h.stAvaliacaoItemPedidoAlteracao,
                        g.dsJustificativa
                    FROM sac.dbo.tbProposta AS a
                    INNER JOIN bdcorporativo.scsac.tbPedidoAlteracaoProjeto             AS b ON b.idPedidoAlteracao = a.idPedidoAlteracao
                    INNER JOIN sac.dbo.Projetos                                         AS c ON c.IdPRONAC = b.IdPRONAC
                    INNER JOIN sac.dbo.PreProjeto                                       AS d ON d.idPreProjeto = c.idProjeto
                    INNER JOIN agentes.dbo.Agentes                                      AS e ON e.idAgente = d.idAgente
                    INNER JOIN bdcorporativo.scsac.tbPedidoAlteracaoProjeto             AS f ON f.IdPRONAC = c.IdPRONAC
                    INNER JOIN bdcorporativo.scsac.tbPedidoAlteracaoXTipoAlteracao      AS g ON g.idPedidoAlteracao = f.idPedidoAlteracao
                    INNER JOIN bdcorporativo.scsac.tbAvaliacaoItemPedidoAlteracao       AS h ON h.idPedidoAlteracao = g.idPedidoAlteracao and h.tpAlteracaoProjeto = g.tpAlteracaoProjeto
                    INNER JOIN bdcorporativo.scsac.tbAcaoAvaliacaoItemPedidoAlteracao   AS i ON i.idAvaliacaoItemPedidoAlteracao = h.idAvaliacaoItemPedidoAlteracao
                    INNER JOIN sac.dbo.vProponenteProjetos                              AS j ON c.CgcCpf = j.CgcCpf


                    WHERE b.IdPRONAC = $id_Pronac 
                    AND b.idPedidoAlteracao = $idPedidoAlteracao 
                    AND i.stAtivo = 0
                    AND H.tpAlteracaoProjeto = 6 ) as tabela";
        }

        if ($sqlDesejado == "sqlpropostaeditar") {
           
            $sql = "SELECT *,
            CAST(dsJustificativa AS text) as dsJustificativa,
            CAST(dsEstrategiaExecucao AS text) as dsEstrategiaExecucao,
            CAST(dsEspecificacaoTecnica AS text) as dsEspecificacaoTecnica,
            CAST(dsJustificativa AS text) as Justificativa  FROM
            (
            SELECT DISTINCT b.IdPRONAC,
                        c.AnoProjeto+c.Sequencial AS PRONAC,
                        c.NomeProjeto AS NomeProjeto,
                        c.CgcCpf AS CNPJCPF,
                        j.Nome AS proponente,
                        d.EstrategiadeExecucao as EspecificacaoTecnica,
                        d.EspecificacaoTecnica as EstrategiadeExecucao,
                        a.dsObjetivos AS Solicitacao,
                        a.dsJustificativa AS Justificativa,
                        a.dsEstrategiaExecucao,
                        a.dsEspecificacaoTecnica,
                        f.idPedidoAlteracao,
                        g.tpAlteracaoProjeto,
                        h.idAvaliacaoItemPedidoAlteracao AS idAvaliacao,
                        i.idAcaoAvaliacaoItemPedidoAlteracao AS idAcao,
                        a.IdProposta,
                        i.idOrgao,
                        h.stAvaliacaoItemPedidoAlteracao,
                        g.dsJustificativa
                    FROM sac.dbo.tbProposta AS a
                    INNER JOIN bdcorporativo.scsac.tbPedidoAlteracaoProjeto             AS b ON b.idPedidoAlteracao = a.idPedidoAlteracao
                    INNER JOIN sac.dbo.Projetos                                         AS c ON c.IdPRONAC = b.IdPRONAC
                    INNER JOIN sac.dbo.PreProjeto                                       AS d ON d.idPreProjeto = c.idProjeto
                    INNER JOIN agentes.dbo.Agentes                                      AS e ON e.idAgente = d.idAgente
                    INNER JOIN bdcorporativo.scsac.tbPedidoAlteracaoProjeto             AS f ON f.IdPRONAC = c.IdPRONAC
                    INNER JOIN bdcorporativo.scsac.tbPedidoAlteracaoXTipoAlteracao      AS g ON g.idPedidoAlteracao = f.idPedidoAlteracao
                    INNER JOIN bdcorporativo.scsac.tbAvaliacaoItemPedidoAlteracao       AS h ON h.idPedidoAlteracao = g.idPedidoAlteracao and h.tpAlteracaoProjeto = g.tpAlteracaoProjeto
                    INNER JOIN bdcorporativo.scsac.tbAcaoAvaliacaoItemPedidoAlteracao   AS i ON i.idAvaliacaoItemPedidoAlteracao = h.idAvaliacaoItemPedidoAlteracao
                    INNER JOIN sac.dbo.vProponenteProjetos                              AS j ON c.CgcCpf = j.CgcCpf
                    WHERE b.IdPRONAC = $id_Pronac 
                    AND b.idPedidoAlteracao = $idPedidoAlteracao 
                    AND g.stVerificacao = 1
                    AND i.idTipoAgente in (1,5)
                    AND i.stAtivo = 0
                    AND H.tpAlteracaoProjeto = 6 ) as tabela";
        }

        if ($sqlDesejado == "sqlConsultaReadequacaoInicial") {
            $sql = "SELECT a.idPedidoAlteracao,
                        a.idPlano,
                        b.IdPRONAC,
                        c.AnoProjeto+c.Sequencial AS PRONAC,
                        c.NomeProjeto,
                        c.CgcCpf AS CNPJCPF,
                        j.Descricao AS proponente,
                        d.Descricao AS Produto,
                        e.Descricao AS Area,
                        f.Descricao AS Segmento,
                        g.Descricao AS Posicao,
                        a.cdSegmento,
                        a.qtPatrocinador AS Patrocinador,
                        a.qtProduzida AS Beneficiarios,
                        a.qtOutros AS Divulgacao,
                        a.qtVendaNormal AS NormalTV,
                        a.qtVendaPromocional AS PromocionalTV,
                        a.vlUnitarioNormal AS NormalPU,
                        a.vlUnitarioPromocional AS PromocionalPU,
                        a.stPrincipal AS PrdotudoPrincpal,
                        a.tpAcao,
                        CAST(a.dsjustificativa AS TEXT) AS JustificativaProponente,
                        h.idAvaliacaoItemPedidoAlteracao

                    FROM sac.dbo.tbPlanoDistribuicao					AS a
                    INNER JOIN bdcorporativo.scsac.tbPedidoAlteracaoProjeto             AS b ON a.idPedidoAlteracao = b.idPedidoAlteracao
                    INNER JOIN sac.dbo.Projetos                                         AS c ON b.IdPRONAC = c.IdPRONAC
                    INNER JOIN sac.dbo.Produto                                          AS d ON a.idProduto = d.Codigo
                    INNER JOIN sac.dbo.Area						AS e ON a.cdArea = e.Codigo
                    LEFT JOIN sac.dbo.Segmento                                          AS f ON a.cdSegmento = f.Codigo
                    INNER JOIN sac.dbo.Verificacao					AS g ON a.idPosicaoLogo = g.idVerificacao
                    LEFT JOIN bdcorporativo.scsac.tbAvaliacaoItemPedidoAlteracao 	AS h ON h.idPedidoAlteracao = a.idPedidoAlteracao and h.tpAlteracaoProjeto in (7,10)
                    INNER JOIN agentes.dbo.Agentes                                      AS i ON c.CgcCpf = i.CNPJCPF
                    INNER JOIN agentes.dbo.Nomes                                        AS j ON i.idAgente = j.idAgente
                    WHERE c.IdPRONAC = $id_Pronac ";
                    
			if (!empty($idPedidoAlteracao)) :
				$sql.= "AND b.idPedidoAlteracao = $idPedidoAlteracao";
			endif;
        }

        if ($sqlDesejado == "sqlConsultaReadequacao") {
            $sql = "SELECT distinct a.idPedidoAlteracao,
                        a.idPlano,
                        b.IdPRONAC,
                        c.AnoProjeto+c.Sequencial AS PRONAC,
                        c.NomeProjeto,
                        c.CgcCpf AS CNPJCPF,
                        i.Nome AS proponente,
                        d.Descricao AS Produto,
                        e.Descricao AS Area,
                        f.Descricao AS Segmento,
                        g.Descricao AS Posicao,
                        a.cdSegmento,
                        a.qtPatrocinador AS Patrocinador,
                        a.qtProduzida AS Beneficiarios,
                        a.qtOutros AS Divulgacao,
                        a.qtVendaNormal AS NormalTV,
                        a.qtVendaPromocional AS PromocionalTV,
                        a.vlUnitarioNormal AS NormalPU,
                        a.vlUnitarioPromocional AS PromocionalPU,
                        a.stPrincipal AS PrdotudoPrincpal,
                        a.tpAcao,
                        h.idAvaliacaoItemPedidoAlteracao

                    FROM sac.dbo.tbPlanoDistribuicao					AS a
                    INNER JOIN bdcorporativo.scsac.tbPedidoAlteracaoProjeto             AS b ON a.idPedidoAlteracao = b.idPedidoAlteracao
                    INNER JOIN sac.dbo.Projetos                                         AS c ON b.IdPRONAC = c.IdPRONAC
                    INNER JOIN sac.dbo.Produto                                          AS d ON a.idProduto = d.Codigo
                    INNER JOIN sac.dbo.Area						AS e ON a.cdArea = e.Codigo
                    LEFT JOIN sac.dbo.Segmento                                         AS f ON a.cdSegmento = f.Codigo
                    INNER JOIN sac.dbo.Verificacao					AS g ON a.idPosicaoLogo = g.idVerificacao
                    LEFT JOIN bdcorporativo.scsac.tbAvaliacaoItemPedidoAlteracao 	AS h ON h.idPedidoAlteracao = a.idPedidoAlteracao and h.tpAlteracaoProjeto = 10
                    INNER JOIN sac.dbo.vProponenteProjetos				AS i ON c.CgcCpf = i.CgcCpf

                    WHERE c.IdPRONAC = $id_Pronac
                    AND h.idAvaliacaoItemPedidoAlteracao = (

                        SELECT TOP 1 h.idAvaliacaoItemPedidoAlteracao
                        FROM sac.dbo.tbPlanoDistribuicao				AS a
                        INNER JOIN bdcorporativo.scsac.tbPedidoAlteracaoProjeto		AS b ON a.idPedidoAlteracao = b.idPedidoAlteracao
                        INNER JOIN sac.dbo.Projetos					AS c ON b.IdPRONAC = c.IdPRONAC
                        INNER JOIN sac.dbo.Produto					AS d ON a.idProduto = d.Codigo
                        INNER JOIN sac.dbo.Area						AS e ON a.cdArea = e.Codigo
                        LEFT JOIN sac.dbo.Segmento					AS f ON a.cdSegmento = f.Codigo
                        INNER JOIN sac.dbo.Verificacao					AS g ON a.idPosicaoLogo = g.idVerificacao
                        LEFT JOIN bdcorporativo.scsac.tbAvaliacaoItemPedidoAlteracao 	AS h ON h.idPedidoAlteracao = a.idPedidoAlteracao and h.tpAlteracaoProjeto = 10
                        INNER JOIN sac.dbo.vProponenteProjetos				AS i ON c.CgcCpf = i.CgcCpf
                        WHERE c.IdPRONAC = $id_Pronac
                        ORDER BY h.idAvaliacaoItemPedidoAlteracao DESC)";
        }

        if ($sqlDesejado == "sqlConsultaReadequacaoEditar") {
            $sql = "SELECT a.idPedidoAlteracao,
                        a.idPlano,
                        b.IdPRONAC,
                        c.AnoProjeto+c.Sequencial AS PRONAC,
                        c.NomeProjeto,
                        c.CgcCpf AS CNPJCPF,
                        j.Descricao AS proponente,
                        d.Codigo AS idProduto,
                        d.Descricao AS Produto,
                        e.Descricao AS Area,
                        f.Descricao AS Segmento,
                        g.Descricao AS Posicao,
                        a.cdSegmento,
                        a.qtPatrocinador AS Patrocinador,
                        a.qtProduzida AS Beneficiarios,
                        a.qtOutros AS Divulgacao,
                        a.qtVendaNormal AS NormalTV,
                        a.qtVendaPromocional AS PromocionalTV,
                        a.vlUnitarioNormal AS NormalPU,
                        a.vlUnitarioPromocional AS PromocionalPU,
                        a.stPrincipal AS PrdotudoPrincpal,
                        a.tpAcao,
                        c.idProjeto,
                        h.idAvaliacaoItemPedidoAlteracao,
                        e.Codigo AS cdArea
                        ,(select TOP 1 bb.stAvaliacaoSubItemPedidoAlteracao
                                from bdcorporativo.scsac.tbAvaliacaoSubItemPlanoDistribuicao as aa
                                inner join bdcorporativo.scsac.tbAvaliacaoSubItemPedidoAlteracao as bb on aa.idAvaliacaoSubItemPedidoAlteracao = bb.idAvaliacaoSubItemPedidoAlteracao
                                where aa.idPlano = a.idPlano and aa.idAvaliacaoItemPedidoAlteracao = h.idAvaliacaoItemPedidoAlteracao ORDER BY aa.idAvaliacaoSubItemPedidoAlteracao desc) AS avaliacao
                        ,CAST((select TOP 1 bb.dsAvaliacaoSubItemPedidoAlteracao
                                from bdcorporativo.scsac.tbAvaliacaoSubItemPlanoDistribuicao as aa
                                inner join bdcorporativo.scsac.tbAvaliacaoSubItemPedidoAlteracao as bb on aa.idAvaliacaoSubItemPedidoAlteracao = bb.idAvaliacaoSubItemPedidoAlteracao
                                where aa.idPlano = a.idPlano and aa.idAvaliacaoItemPedidoAlteracao = h.idAvaliacaoItemPedidoAlteracao ORDER BY aa.idAvaliacaoSubItemPedidoAlteracao desc) AS TEXT) AS dsJustificativa


                    FROM sac.dbo.tbPlanoDistribuicao					AS a
                    INNER JOIN bdcorporativo.scsac.tbPedidoAlteracaoProjeto     	AS b ON a.idPedidoAlteracao = b.idPedidoAlteracao
                    INNER JOIN sac.dbo.Projetos                                         AS c ON b.IdPRONAC = c.IdPRONAC
                    INNER JOIN sac.dbo.Produto                                          AS d ON a.idProduto = d.Codigo
                    INNER JOIN sac.dbo.Area						AS e ON a.cdArea = e.Codigo
                    LEFT JOIN sac.dbo.Segmento                                          AS f ON a.cdSegmento = f.Codigo
                    INNER JOIN sac.dbo.Verificacao					AS g ON a.idPosicaoLogo = g.idVerificacao
                    INNER JOIN bdcorporativo.scsac.tbAvaliacaoItemPedidoAlteracao 	AS h ON h.idPedidoAlteracao = a.idPedidoAlteracao and h.tpAlteracaoProjeto in (7,10)
                    INNER JOIN agentes.dbo.Agentes                                      AS i ON c.CgcCpf = i.CNPJCPF
                    INNER JOIN agentes.dbo.Nomes                                        AS j ON i.idAgente = j.idAgente
                    ,(SELECT MAX(idPlano) AS idPlano, idProduto
                            FROM sac.dbo.tbPlanoDistribuicao
                            GROUP BY idProduto
                            HAVING MAX(idPlano) > 0) AS tmp
                    ,(SELECT MAX(idAvaliacaoItemPedidoAlteracao) AS idAvaliacaoItemPedidoAlteracao
                            FROM bdcorporativo.scsac.tbAvaliacaoItemPedidoAlteracao
                            WHERE stAvaliacaoItemPedidoAlteracao != 'AP'
                            AND stAvaliacaoItemPedidoAlteracao != 'IN'
                            GROUP BY idAvaliacaoItemPedidoAlteracao) AS tmp2

                    WHERE c.IdPRONAC = $id_Pronac
                    AND a.idProduto = tmp.idProduto
                    AND a.idPlano   = tmp.idPlano
                    AND h.idAvaliacaoItemPedidoAlteracao = tmp2.idAvaliacaoItemPedidoAlteracao
                    ORDER BY d.Descricao ";
            
        }


        if ($sqlDesejado == "sqlConsultaReadequacaoEditarParecerista") {
            $sql = "SELECT a.idPedidoAlteracao,
                        a.idPlano,
                        b.IdPRONAC,
                        c.AnoProjeto+c.Sequencial AS PRONAC,
                        c.NomeProjeto,
                        c.CgcCpf AS CNPJCPF,
                        j.Descricao AS proponente,
                        d.Codigo AS idProduto,
                        d.Descricao AS Produto,
                        e.Descricao AS Area,
                        f.Descricao AS Segmento,
                        g.Descricao AS Posicao,
                        a.cdSegmento,
                        a.qtPatrocinador AS Patrocinador,
                        a.qtProduzida AS Beneficiarios,
                        a.qtOutros AS Divulgacao,
                        a.qtVendaNormal AS NormalTV,
                        a.qtVendaPromocional AS PromocionalTV,
                        a.vlUnitarioNormal AS NormalPU,
                        a.vlUnitarioPromocional AS PromocionalPU,
                        a.stPrincipal AS PrdotudoPrincpal,
                        a.tpAcao,
                        c.idProjeto,
                        h.idAvaliacaoItemPedidoAlteracao,
                        e.Codigo AS cdArea
                        ,(select TOP 1 bb.stAvaliacaoSubItemPedidoAlteracao
                                from bdcorporativo.scsac.tbAvaliacaoSubItemPlanoDistribuicao as aa
                                inner join bdcorporativo.scsac.tbAvaliacaoSubItemPedidoAlteracao as bb on aa.idAvaliacaoSubItemPedidoAlteracao = bb.idAvaliacaoSubItemPedidoAlteracao
                                where aa.idPlano = a.idPlano and aa.idAvaliacaoItemPedidoAlteracao = h.idAvaliacaoItemPedidoAlteracao ORDER BY aa.idAvaliacaoSubItemPedidoAlteracao desc) AS avaliacao
                        ,CAST((select TOP 1 bb.dsAvaliacaoSubItemPedidoAlteracao
                                from bdcorporativo.scsac.tbAvaliacaoSubItemPlanoDistribuicao as aa
                                inner join bdcorporativo.scsac.tbAvaliacaoSubItemPedidoAlteracao as bb on aa.idAvaliacaoSubItemPedidoAlteracao = bb.idAvaliacaoSubItemPedidoAlteracao
                                where aa.idPlano = a.idPlano and aa.idAvaliacaoItemPedidoAlteracao = h.idAvaliacaoItemPedidoAlteracao ORDER BY aa.idAvaliacaoSubItemPedidoAlteracao desc) AS TEXT) AS dsJustificativa


                    FROM bdcorporativo.scsac.tbPedidoAlteracaoProjeto     	AS b
                    LEFT JOIN sac.dbo.tbPlanoDistribuicao					AS a ON b.idPedidoAlteracao = a.idPedidoAlteracao
                    INNER JOIN sac.dbo.Projetos                                         AS c ON b.IdPRONAC = c.IdPRONAC
                    INNER JOIN sac.dbo.Produto                                          AS d ON a.idProduto = d.Codigo
                    INNER JOIN sac.dbo.Area						AS e ON a.cdArea = e.Codigo
                    LEFT JOIN sac.dbo.Segmento                                          AS f ON a.cdSegmento = f.Codigo
                    INNER JOIN sac.dbo.Verificacao					AS g ON a.idPosicaoLogo = g.idVerificacao
                    INNER JOIN bdcorporativo.scsac.tbAvaliacaoItemPedidoAlteracao 	AS h ON h.idPedidoAlteracao = a.idPedidoAlteracao and h.tpAlteracaoProjeto in (7,10)
                    INNER JOIN agentes.dbo.Agentes                                      AS i ON c.CgcCpf = i.CNPJCPF
                    INNER JOIN agentes.dbo.Nomes                                        AS j ON i.idAgente = j.idAgente
                    ,(SELECT MAX(idPlano) AS idPlano, idProduto
                            FROM sac.dbo.tbPlanoDistribuicao
                            GROUP BY idProduto
                            HAVING MAX(idPlano) > 0) AS tmp
                    ,(SELECT MAX(idAvaliacaoItemPedidoAlteracao) AS idAvaliacaoItemPedidoAlteracao
                            FROM bdcorporativo.scsac.tbAvaliacaoItemPedidoAlteracao
                            WHERE stAvaliacaoItemPedidoAlteracao != 'AP'
                            AND stAvaliacaoItemPedidoAlteracao != 'IN'
                            GROUP BY idAvaliacaoItemPedidoAlteracao) AS tmp2

                    WHERE c.IdPRONAC = $id_Pronac
                    AND d.Codigo = tmp.idProduto";

                    if ($planoDistribuicaoObrigatorio) :
                   $sql.= " AND a.idPlano   = tmp.idPlano";
                    endif;
					
					$sql.= " AND h.idAvaliacaoItemPedidoAlteracao = tmp2.idAvaliacaoItemPedidoAlteracao ORDER BY d.Descricao ";
            
        }


        if ($sqlDesejado == "sqlConsultaReadequacaoProponente") {
            $sql = "SELECT a.idPedidoAlteracao,
                        a.idPlano,
                        b.IdPRONAC,
                        c.AnoProjeto+c.Sequencial AS PRONAC,
                        c.NomeProjeto,
                        c.CgcCpf AS CNPJCPF,
                        j.Descricao AS proponente,
                        d.Codigo AS idProduto,
                        d.Descricao AS Produto,
                        e.Descricao AS Area,
                        f.Descricao AS Segmento,
                        g.Descricao AS Posicao,
                        a.cdSegmento,
                        a.qtPatrocinador AS Patrocinador,
                        a.qtProduzida AS Beneficiarios,
                        a.qtOutros AS Divulgacao,
                        a.qtVendaNormal AS NormalTV,
                        a.qtVendaPromocional AS PromocionalTV,
                        a.vlUnitarioNormal AS NormalPU,
                        a.vlUnitarioPromocional AS PromocionalPU,
                        a.stPrincipal AS PrdotudoPrincpal,
                        a.tpAcao,
                        CAST(a.dsjustificativa AS TEXT) AS JustificativaProponente,
                        c.idProjeto,
                        h.idAvaliacaoItemPedidoAlteracao,
                        e.Codigo AS cdArea
                        ,(select TOP 1 bb.stAvaliacaoSubItemPedidoAlteracao
                                from bdcorporativo.scsac.tbAvaliacaoSubItemPlanoDistribuicao as aa
                                inner join bdcorporativo.scsac.tbAvaliacaoSubItemPedidoAlteracao as bb on aa.idAvaliacaoSubItemPedidoAlteracao = bb.idAvaliacaoSubItemPedidoAlteracao
                                where aa.idPlano = a.idPlano ORDER BY aa.idAvaliacaoSubItemPedidoAlteracao desc) AS avaliacao
                        ,(select TOP 1 bb.dsAvaliacaoSubItemPedidoAlteracao
                                from bdcorporativo.scsac.tbAvaliacaoSubItemPlanoDistribuicao as aa
                                inner join bdcorporativo.scsac.tbAvaliacaoSubItemPedidoAlteracao as bb on aa.idAvaliacaoSubItemPedidoAlteracao = bb.idAvaliacaoSubItemPedidoAlteracao
                                where aa.idPlano = a.idPlano ORDER BY aa.idAvaliacaoSubItemPedidoAlteracao desc) AS dsJustificativa


                    FROM sac.dbo.tbPlanoDistribuicao					AS a
                    INNER JOIN bdcorporativo.scsac.tbPedidoAlteracaoProjeto     	AS b ON a.idPedidoAlteracao = b.idPedidoAlteracao
                    INNER JOIN sac.dbo.Projetos                                         AS c ON b.IdPRONAC = c.IdPRONAC
                    INNER JOIN sac.dbo.Produto                                          AS d ON a.idProduto = d.Codigo
                    INNER JOIN sac.dbo.Area						AS e ON a.cdArea = e.Codigo
                    LEFT JOIN sac.dbo.Segmento                                 	AS f ON a.cdSegmento = f.Codigo
                    INNER JOIN sac.dbo.Verificacao					AS g ON a.idPosicaoLogo = g.idVerificacao
                    INNER JOIN bdcorporativo.scsac.tbAvaliacaoItemPedidoAlteracao 	AS h ON h.idPedidoAlteracao = a.idPedidoAlteracao and h.tpAlteracaoProjeto in (7,10)
                    INNER JOIN agentes.dbo.Agentes                                      AS i ON c.CgcCpf = i.CNPJCPF
                    INNER JOIN agentes.dbo.Nomes                                        AS j ON i.idAgente = j.idAgente
                    ,(SELECT MAX(idPlano) AS idPlano, idProduto
                            FROM sac.dbo.tbPlanoDistribuicao
                            GROUP BY idProduto
                            HAVING MAX(idPlano) > 0) AS tmp
                    ,(SELECT MAX(idAvaliacaoItemPedidoAlteracao) AS idAvaliacaoItemPedidoAlteracao
                            FROM bdcorporativo.scsac.tbAvaliacaoItemPedidoAlteracao
                            WHERE stAvaliacaoItemPedidoAlteracao != 'AP'
                            AND stAvaliacaoItemPedidoAlteracao != 'IN'
                            GROUP BY idAvaliacaoItemPedidoAlteracao) AS tmp2

                    WHERE c.IdPRONAC = $id_Pronac
                    AND a.idProduto = tmp.idProduto
                    AND a.idPlano   = tmp.idPlano
                    AND h.idAvaliacaoItemPedidoAlteracao = tmp2.idAvaliacaoItemPedidoAlteracao
                    ORDER BY d.Descricao ";
        }


        if ($sqlDesejado == "sqlConsultaReadequacaoParecerista") {
            $sql = "SELECT a.idPedidoAlteracao,
                        a.idPlano,
                        b.IdPRONAC,
                        c.AnoProjeto+c.Sequencial AS PRONAC,
                        c.NomeProjeto,
                        c.CgcCpf AS CNPJCPF,
                        j.Descricao AS proponente,
                        d.Codigo AS idProduto,
                        d.Descricao AS Produto,
                        e.Descricao AS Area,
                        f.Descricao AS Segmento,
                        g.Descricao AS Posicao,
                        a.cdSegmento,
                        a.qtPatrocinador AS Patrocinador,
                        a.qtProduzida AS Beneficiarios,
                        a.qtOutros AS Divulgacao,
                        a.qtVendaNormal AS NormalTV,
                        a.qtVendaPromocional AS PromocionalTV,
                        a.vlUnitarioNormal AS NormalPU,
                        a.vlUnitarioPromocional AS PromocionalPU,
                        a.stPrincipal AS PrdotudoPrincpal,
                        a.tpAcao,
                        CAST(a.dsjustificativa AS TEXT) AS JustificativaProponente,
                        c.idProjeto,
                        h.idAvaliacaoItemPedidoAlteracao,
                        e.Codigo AS cdArea
                        ,(select TOP 1 bb.stAvaliacaoSubItemPedidoAlteracao
                                from bdcorporativo.scsac.tbAvaliacaoSubItemPlanoDistribuicao as aa
                                inner join bdcorporativo.scsac.tbAvaliacaoSubItemPedidoAlteracao as bb on aa.idAvaliacaoSubItemPedidoAlteracao = bb.idAvaliacaoSubItemPedidoAlteracao
                                where aa.idPlano = a.idPlano ORDER BY aa.idAvaliacaoSubItemPedidoAlteracao desc) AS avaliacao
                        ,(select TOP 1 bb.dsAvaliacaoSubItemPedidoAlteracao
                                from bdcorporativo.scsac.tbAvaliacaoSubItemPlanoDistribuicao as aa
                                inner join bdcorporativo.scsac.tbAvaliacaoSubItemPedidoAlteracao as bb on aa.idAvaliacaoSubItemPedidoAlteracao = bb.idAvaliacaoSubItemPedidoAlteracao
                                where aa.idPlano = a.idPlano ORDER BY aa.idAvaliacaoSubItemPedidoAlteracao desc) AS dsJustificativa


                    FROM bdcorporativo.scsac.tbPedidoAlteracaoProjeto     	AS b
                    LEFT JOIN sac.dbo.tbPlanoDistribuicao					AS a ON b.idPedidoAlteracao = a.idPedidoAlteracao
                    INNER JOIN sac.dbo.Projetos                                         AS c ON b.IdPRONAC = c.IdPRONAC
                    INNER JOIN sac.dbo.Produto                                          AS d ON a.idProduto = d.Codigo
                    INNER JOIN sac.dbo.Area						AS e ON a.cdArea = e.Codigo
                    LEFT JOIN sac.dbo.Segmento                                 	AS f ON a.cdSegmento = f.Codigo
                    INNER JOIN sac.dbo.Verificacao					AS g ON a.idPosicaoLogo = g.idVerificacao
                    INNER JOIN bdcorporativo.scsac.tbAvaliacaoItemPedidoAlteracao 	AS h ON h.idPedidoAlteracao = a.idPedidoAlteracao and h.tpAlteracaoProjeto in (7,10)
                    INNER JOIN agentes.dbo.Agentes                                      AS i ON c.CgcCpf = i.CNPJCPF
                    INNER JOIN agentes.dbo.Nomes                                        AS j ON i.idAgente = j.idAgente
                    ,(SELECT MAX(idPlano) AS idPlano, idProduto
                            FROM sac.dbo.tbPlanoDistribuicao
                            GROUP BY idProduto
                            HAVING MAX(idPlano) > 0) AS tmp
                    ,(SELECT MAX(idAvaliacaoItemPedidoAlteracao) AS idAvaliacaoItemPedidoAlteracao
                            FROM bdcorporativo.scsac.tbAvaliacaoItemPedidoAlteracao
                            WHERE stAvaliacaoItemPedidoAlteracao != 'AP'
                            AND stAvaliacaoItemPedidoAlteracao != 'IN'
                            GROUP BY idAvaliacaoItemPedidoAlteracao) AS tmp2

                    WHERE c.IdPRONAC = $id_Pronac
                    AND d.Codigo = tmp.idProduto";
                   
                    if ($planoDistribuicaoObrigatorio) :
                   $sql.= " AND a.idPlano   = tmp.idPlano";
                    endif;

                   $sql.= " AND h.idAvaliacaoItemPedidoAlteracao = tmp2.idAvaliacaoItemPedidoAlteracao
                    ORDER BY d.Descricao ";
        }


        if ($sqlDesejado == "sqlConsultaReadequacaoDev") {
            $sql = "SELECT distinct a.idPedidoAlteracao,
                        a.idPlano,
                        b.IdPRONAC,
                        c.AnoProjeto+c.Sequencial AS PRONAC,
                        c.NomeProjeto,
                        c.CgcCpf AS CNPJCPF,
                        j.Descricao AS proponente,
                        d.Descricao AS Produto,
                        e.Descricao AS Area,
                        f.Descricao AS Segmento,
                        g.Descricao AS Posicao,
                        a.cdSegmento,
                        a.qtPatrocinador AS Patrocinador,
                        a.qtProduzida AS Beneficiarios,
                        a.qtOutros AS Divulgacao,
                        a.qtVendaNormal AS NormalTV,
                        a.qtVendaPromocional AS PromocionalTV,
                        a.vlUnitarioNormal AS NormalPU,
                        a.vlUnitarioPromocional AS PromocionalPU,
                        a.stPrincipal AS PrdotudoPrincpal,
                        a.tpAcao,
                        h.idAvaliacaoItemPedidoAlteracao,
                        h.dsAvaliacao
                        ,(select TOP 1 bb.stAvaliacaoSubItemPedidoAlteracao
                                from bdcorporativo.scsac.tbAvaliacaoSubItemPlanoDistribuicao as aa
                                inner join bdcorporativo.scsac.tbAvaliacaoSubItemPedidoAlteracao as bb on aa.idAvaliacaoSubItemPedidoAlteracao = bb.idAvaliacaoSubItemPedidoAlteracao
                                where aa.idPlano = a.idPlano ORDER BY aa.idAvaliacaoSubItemPedidoAlteracao desc) AS avaliacao
                        ,(select TOP 1 bb.dsAvaliacaoSubItemPedidoAlteracao
                                from bdcorporativo.scsac.tbAvaliacaoSubItemPlanoDistribuicao as aa
                                inner join bdcorporativo.scsac.tbAvaliacaoSubItemPedidoAlteracao as bb on aa.idAvaliacaoSubItemPedidoAlteracao = bb.idAvaliacaoSubItemPedidoAlteracao
                                where aa.idPlano = a.idPlano ORDER BY aa.idAvaliacaoSubItemPedidoAlteracao desc) AS dsJustificativa

                    FROM bdcorporativo.scsac.tbPedidoAlteracaoProjeto     	AS b 
                    LEFT JOIN sac.dbo.tbPlanoDistribuicao					AS a  ON a.idPedidoAlteracao = a.idPedidoAlteracao
                    INNER JOIN sac.dbo.Projetos                                         AS c ON b.IdPRONAC = c.IdPRONAC
                    INNER JOIN sac.dbo.Produto                                          AS d ON a.idProduto = d.Codigo
                    INNER JOIN sac.dbo.Area						AS e ON a.cdArea = e.Codigo
                    LEFT JOIN sac.dbo.Segmento                                          AS f ON a.cdSegmento = f.Codigo
                    INNER JOIN sac.dbo.Verificacao					AS g ON a.idPosicaoLogo = g.idVerificacao
                    INNER JOIN bdcorporativo.scsac.tbAvaliacaoItemPedidoAlteracao 	AS h ON h.idPedidoAlteracao = a.idPedidoAlteracao and h.tpAlteracaoProjeto in (7,10)
                    INNER JOIN agentes.dbo.Agentes                                      AS i ON c.CgcCpf = i.CNPJCPF
                    INNER JOIN agentes.dbo.Nomes                                        AS j ON i.idAgente = j.idAgente


                    ,(SELECT MIN(idPlano) AS idPlano, idProduto
                            FROM sac.dbo.tbPlanoDistribuicao
                            GROUP BY idProduto
                            HAVING MIN(idPlano) > 0) AS tmp
                    ,(SELECT MAX(idAvaliacaoItemPedidoAlteracao) AS idAvaliacaoItemPedidoAlteracao
                            FROM bdcorporativo.scsac.tbAvaliacaoItemPedidoAlteracao
                            WHERE stAvaliacaoItemPedidoAlteracao in ('AP','IN')  AND tpAlteracaoProjeto = 7) AS tmp2

                    WHERE c.IdPRONAC = $id_Pronac
                    AND a.idProduto = tmp.idProduto
                    --AND a.idPlano   = tmp.idPlano
                    --AND tmp2.idAvaliacaoItemPedidoAlteracao = h.idAvaliacaoItemPedidoAlteracao
                    ";
        }

        if ($sqlDesejado == "sqlConsultaReadequacaoDevParecerista") {
            $sql = "SELECT distinct a.idPedidoAlteracao,
                        a.idPlano,
                        b.IdPRONAC,
                        c.AnoProjeto+c.Sequencial AS PRONAC,
                        c.NomeProjeto,
                        c.CgcCpf AS CNPJCPF,
                        j.Descricao AS proponente,
                        d.Descricao AS Produto,
                        e.Descricao AS Area,
                        f.Descricao AS Segmento,
                        g.Descricao AS Posicao,
                        a.cdSegmento,
                        a.qtPatrocinador AS Patrocinador,
                        a.qtProduzida AS Beneficiarios,
                        a.qtOutros AS Divulgacao,
                        a.qtVendaNormal AS NormalTV,
                        a.qtVendaPromocional AS PromocionalTV,
                        a.vlUnitarioNormal AS NormalPU,
                        a.vlUnitarioPromocional AS PromocionalPU,
                        a.stPrincipal AS PrdotudoPrincpal,
                        a.tpAcao,
                        h.idAvaliacaoItemPedidoAlteracao,
                        h.dsAvaliacao
                        ,(select TOP 1 bb.stAvaliacaoSubItemPedidoAlteracao
                                from bdcorporativo.scsac.tbAvaliacaoSubItemPlanoDistribuicao as aa
                                inner join bdcorporativo.scsac.tbAvaliacaoSubItemPedidoAlteracao as bb on aa.idAvaliacaoSubItemPedidoAlteracao = bb.idAvaliacaoSubItemPedidoAlteracao
                                where aa.idPlano = a.idPlano ORDER BY aa.idAvaliacaoSubItemPedidoAlteracao desc) AS avaliacao
                        ,(select TOP 1 bb.dsAvaliacaoSubItemPedidoAlteracao
                                from bdcorporativo.scsac.tbAvaliacaoSubItemPlanoDistribuicao as aa
                                inner join bdcorporativo.scsac.tbAvaliacaoSubItemPedidoAlteracao as bb on aa.idAvaliacaoSubItemPedidoAlteracao = bb.idAvaliacaoSubItemPedidoAlteracao
                                where aa.idPlano = a.idPlano ORDER BY aa.idAvaliacaoSubItemPedidoAlteracao desc) AS dsJustificativa

                    FROM bdcorporativo.scsac.tbPedidoAlteracaoProjeto     	AS b 
                    LEFT JOIN sac.dbo.tbPlanoDistribuicao					AS a  ON a.idPedidoAlteracao = a.idPedidoAlteracao
                    INNER JOIN sac.dbo.Projetos                                         AS c ON b.IdPRONAC = c.IdPRONAC
                    INNER JOIN sac.dbo.Produto                                          AS d ON a.idProduto = d.Codigo
                    INNER JOIN sac.dbo.Area						AS e ON a.cdArea = e.Codigo
                    LEFT JOIN sac.dbo.Segmento                                          AS f ON a.cdSegmento = f.Codigo
                    INNER JOIN sac.dbo.Verificacao					AS g ON a.idPosicaoLogo = g.idVerificacao
                    INNER JOIN bdcorporativo.scsac.tbAvaliacaoItemPedidoAlteracao 	AS h ON h.idPedidoAlteracao = a.idPedidoAlteracao and h.tpAlteracaoProjeto in (7,10)
                    INNER JOIN agentes.dbo.Agentes                                      AS i ON c.CgcCpf = i.CNPJCPF
                    INNER JOIN agentes.dbo.Nomes                                        AS j ON i.idAgente = j.idAgente


                    ,(SELECT MIN(idPlano) AS idPlano, idProduto
                            FROM sac.dbo.tbPlanoDistribuicao
                            GROUP BY idProduto
                            HAVING MIN(idPlano) > 0) AS tmp
                    ,(SELECT MAX(idAvaliacaoItemPedidoAlteracao) AS idAvaliacaoItemPedidoAlteracao
                            FROM bdcorporativo.scsac.tbAvaliacaoItemPedidoAlteracao
                            WHERE stAvaliacaoItemPedidoAlteracao in ('AP','IN')  AND tpAlteracaoProjeto = 10) AS tmp2

                    WHERE c.IdPRONAC = $id_Pronac
                    AND a.idProduto = tmp.idProduto
                    AND a.idPlano   = tmp.idPlano
                    AND tmp2.idAvaliacaoItemPedidoAlteracao = h.idAvaliacaoItemPedidoAlteracao
                    ";
        }

        if ($sqlDesejado == "sqlListaArea") {
            $sql = "SELECT * FROM sac.dbo.Area ORDER BY Descricao";
        }
        if ($sqlDesejado == "sqlListaSegmento") {
            $sql = "SELECT * FROM sac.dbo.Segmento ORDER BY Descricao";
        }
        if ($sqlDesejado == "sqlConsultaNomeProjEditar") {
            $sql = "SELECT distinct b.IdPRONAC,
                        c.AnoProjeto+c.Sequencial AS PRONAC,
                        c.NomeProjeto AS NomeProjeto,
                        c.CgcCpf AS CNPJCPF,
                        j.Nome AS proponente,
                        d.EstrategiadeExecucao,
                        d.EspecificacaoTecnica,
                        a.dsObjetivos AS Solicitacao,
                        g.dsJustificativa AS Justificativa,
                        a.dsEstrategiaExecucao,
                        a.dsEspecificacaoTecnica,
                        f.idPedidoAlteracao,
                        g.tpAlteracaoProjeto,
                        h.idAvaliacaoItemPedidoAlteracao,
                        i.idAcaoAvaliacaoItemPedidoAlteracao,
                        a.IdProposta,
                        i.idOrgao,
                        h.stAvaliacaoItemPedidoAlteracao
                FROM bdcorporativo.scsac.tbPedidoAlteracaoProjeto AS b
                LEFT JOIN sac.dbo.tbProposta                                            AS a ON a.idPedidoAlteracao = b.idPedidoAlteracao
                INNER JOIN sac.dbo.Projetos 						AS c ON c.IdPRONAC = b.IdPRONAC
                INNER JOIN sac.dbo.PreProjeto 						AS d ON d.idPreProjeto = c.idProjeto
                INNER JOIN agentes.dbo.Agentes 						AS e ON e.idAgente = d.idAgente
                INNER JOIN bdcorporativo.scsac.tbPedidoAlteracaoProjeto 		AS f ON f.IdPRONAC = c.IdPRONAC
                INNER JOIN bdcorporativo.scsac.tbPedidoAlteracaoXTipoAlteracao 		AS g ON g.idPedidoAlteracao = f.idPedidoAlteracao
                INNER JOIN bdcorporativo.scsac.tbAvaliacaoItemPedidoAlteracao 		AS h ON h.idPedidoAlteracao = g.idPedidoAlteracao and h.tpAlteracaoProjeto = g.tpAlteracaoProjeto
                INNER JOIN bdcorporativo.scsac.tbAcaoAvaliacaoItemPedidoAlteracao 	AS i ON i.idAvaliacaoItemPedidoAlteracao = h.idAvaliacaoItemPedidoAlteracao
                INNER JOIN sac.dbo.vProponenteProjetos 					AS j ON c.CgcCpf = j.CgcCpf
                WHERE b.IdPRONAC = $id_Pronac 
                AND b.idPedidoAlteracao = $idPedidoAlteracao 
                AND g.stVerificacao in (1,2)
                AND i.idTipoAgente in (3,5)
                AND i.stAtivo = 0
                AND H.tpAlteracaoProjeto = $tipoAlteracao AND b.idPedidoAlteracao = $idPedidoAlteracao ";
        }

        return $sql;
    }

//finalizar
    public static function retornaSQLfinalprop($estrategia, $especificacao, $IdProposta) {
        $sql = "UPDATE sac.dbo.tbProposta
                SET dsEstrategiaExecucao = '" . $estrategia . "', dsJustificativa = '" . $especificacao . "'
                WHERE IdProposta = $IdProposta ";
        return $sql;
    }

    public static function retornaSQLfinalprop1($idPedidoAlteracao, $tpAlteracaoProjeto) {
        $sql = "UPDATE bdcorporativo.scsac.tbPedidoAlteracaoXTipoAlteracao
                SET stVerificacao = 2
                WHERE idPedidoAlteracao = $idPedidoAlteracao
                AND tpAlteracaoProjeto = $tpAlteracaoProjeto
                AND stVerificacao = 1 ";
        return $sql;
    }

    public static function consultarIdAvaliacao($idPedidoAlteracao) {
        $sql = "SELECT * FROM bdcorporativo.scsac.tbAvaliacaoItemPedidoAlteracao
                WHERE idPedidoAlteracao = $idPedidoAlteracao
                AND tpAlteracaoProjeto = 7";
                //AND stAvaliacaoItemPedidoAlteracao = 'EA' ";
        return $sql;
    }

    public static function consultarIdAcaoAvaliacao($idAvaliacaoPedidoAlteracao) {
        $sql = "SELECT idAcaoAvaliacaoItemPedidoAlteracao AS idAcaoAvaliacao, idOrgao FROM bdcorporativo.scsac.tbAcaoAvaliacaoItemPedidoAlteracao
                WHERE idAvaliacaoItemPedidoAlteracao = $idAvaliacaoPedidoAlteracao
                AND stAtivo = 0 ";
        return $sql;
    }

    public static function retornaSQLfinalprop2($idAvaliacao, $especificacao='.',$status, $tpAlteracaoProjeto = null) {
        $objAcesso= new Acesso();
        $sql = "UPDATE bdcorporativo.scsac.tbAvaliacaoItemPedidoAlteracao
                SET dtFimAvaliacao = {$objAcesso->getDate()}, stAvaliacaoItemPedidoAlteracao = '$status', dsAvaliacao = '$especificacao'
                WHERE idAvaliacaoItemPedidoAlteracao = $idAvaliacao ";

		if (!empty($tpAlteracaoProjeto)) :
			$sql.= " AND tpAlteracaoProjeto = $tpAlteracaoProjeto ";
		endif;

        return $sql;
    }

    public static function retornaSQLfinalprop3($idAcao) {
        $sql = "UPDATE bdcorporativo.scsac.tbAcaoAvaliacaoItemPedidoAlteracao
                SET stAtivo = 1
                WHERE idAcaoAvaliacaoItemPedidoAlteracao = $idAcao ";
        return $sql;
    }

    public static function retornaSQLfinalprop4($idAvaliacao, $idOrgao,$idAgenteRemetente,$idPerfilRemetente) {
        $objAcesso= new Acesso();
        $sql = "INSERT bdcorporativo.scsac.tbAcaoAvaliacaoItemPedidoAlteracao
                VALUES ('$idAvaliacao','','','3','$idOrgao','0','2',{$objAcesso->getDate()},'$idAgenteRemetente','$idPerfilRemetente')";
        return $sql;
    }

    /*     * *****************************************************************************
      SQL PARA INICIAR A SOLICITA��O DE PROPOSTA PEDAG�GICA
     * ***************************************************************************** */

    public static function stPropostaInicio($sqlDesejado, $idAvaliacao, $AgenteLogin) {

        $objAcesso= new Acesso();
        if ($sqlDesejado == "readequacaoEA") {
            $sql = "UPDATE bdcorporativo.scsac.tbAvaliacaoItemPedidoAlteracao
                    SET idAgenteAvaliador = $AgenteLogin, dtInicioAvaliacao = {$objAcesso->getDate()}, stAvaliacaoItemPedidoAlteracao = 'EA'
                    WHERE idAvaliacaoItemPedidoAlteracao = $idAvaliacao ";
        } else if ($sqlDesejado == "readequacaoAP") {
            $sql = "UPDATE bdcorporativo.scsac.tbAvaliacaoItemPedidoAlteracao
                    SET idAgenteAvaliador = $AgenteLogin, dtInicioAvaliacao = {$objAcesso->getDate()}, stAvaliacaoItemPedidoAlteracao = 'AP'
                    WHERE idAvaliacaoItemPedidoAlteracao = $idAvaliacao ";
        } else if ($sqlDesejado == "readequacaoIN") {
            $sql = "UPDATE bdcorporativo.scsac.tbAvaliacaoItemPedidoAlteracao
                    SET idAgenteAvaliador = $AgenteLogin, dtInicioAvaliacao = {$objAcesso->getDate()}, stAvaliacaoItemPedidoAlteracao = 'IN'
                    WHERE idAvaliacaoItemPedidoAlteracao = $idAvaliacao ";
        }

        return $sql;
    }

    public static function PropostaAltCampo($idAvaliacao) {

        $sql = "UPDATE bdcorporativo.scsac.tbAcaoAvaliacaoItemPedidoAlteracao
                SET stVerificacao = 1
                WHERE idAvaliacaoItemPedidoAlteracao = $idAvaliacao
                AND stAtivo = 0 ";
        return $sql;
    }

    public static function diligenciarProposta($IdPronac, $solicitacao, $AgenteLogin) {

        $objAcesso= new Acesso();
        $sql = "INSERT into sac.dbo.tbDiligencia
                VALUES ('$IdPronac','124',{$objAcesso->getDate()},'" . $solicitacao . "','$AgenteLogin','','','','0')";
        return $sql;
    }

    /***********************************************************************
      SQL PARA LISTAR OS IDs PLANOS PARA ALTERA��O DOS DADOS INDIVIDUALMENTE
     ********************************************************************** */

    public static function listaSQLidPlano($idPronac) {
        $sql = "SELECT distinct a.idPedidoAlteracao,
                    a.idPlano,
                    b.IdPRONAC,
                    c.AnoProjeto+c.Sequencial AS PRONAC,
                    c.NomeProjeto,
                    c.CgcCpf AS CNPJCPF,
                    i.Nome AS proponente,
                    d.Descricao AS Produto,
                    e.Descricao AS Area,
                    f.Descricao AS Segmento,
                    g.Descricao AS Posicao,
                    a.cdSegmento,
                    a.qtPatrocinador AS Patrocinador,
                    a.qtProduzida AS Beneficiarios,
                    a.qtOutros AS Divulgacao,
                    a.qtVendaNormal AS NormalTV,
                    a.qtVendaPromocional AS PromocionalTV,
                    a.vlUnitarioNormal AS NormalPU,
                    a.vlUnitarioPromocional AS PromocionalPU,
                    a.stPrincipal AS PrdotudoPrincpal,
                    a.tpAcao,
                    h.idAvaliacaoItemPedidoAlteracao

                FROM sac.dbo.tbPlanoDistribuicao				AS a
                INNER JOIN bdcorporativo.scsac.tbPedidoAlteracaoProjeto     	AS b ON a.idPedidoAlteracao = b.idPedidoAlteracao
                INNER JOIN sac.dbo.Projetos                                     AS c ON b.IdPRONAC = c.IdPRONAC
                INNER JOIN sac.dbo.Produto                                      AS d ON a.idProduto = d.Codigo
                INNER JOIN sac.dbo.Area						AS e ON a.cdArea = e.Codigo
                INNER JOIN sac.dbo.Segmento                                 	AS f ON a.cdSegmento = f.Codigo
                INNER JOIN sac.dbo.Verificacao					AS g ON a.idPosicaoLogo = g.idVerificacao
                INNER JOIN bdcorporativo.scsac.tbAvaliacaoItemPedidoAlteracao 	AS h ON h.idPedidoAlteracao = a.idPedidoAlteracao and h.tpAlteracaoProjeto in (7,10)
                INNER JOIN sac.dbo.vProponenteProjetos				AS i ON c.CgcCpf = i.CgcCpf

                WHERE c.IdPRONAC = $idPronac
                AND h.dsAvaliacao = '' or h.dsAvaliacao is NULL ";
        return $sql;
    }


    /*     * *********************************************************************
      SQL SALVAR OS DADOS NA TABELA tbPlanoDistribuicao
     * ********************************************************************* */

    public static function sqlsalvareadequacao($updateFrom, $sqldados, $where, $and1) {
        $sql = $updateFrom . " " . $sqldados . " " . $where . " " . $and1;
        return $sql;
    }

    /*     * *********************************************************************
      SQL
     * ********************************************************************* */

    public static function alteraStatusReadequacao($idPedidoAlt)
    {

        $sql = "SELECT *
                FROM bdcorporativo.scsac.tbAvaliacaoItemPedidoAlteracao
                WHERE idAvaliacaoItemPedidoAlteracao = $idPedidoAlt ";

        return $sql;
    }

    public static function alteraStatusProposta($idAvaliacao) {

        $sql = "SELECT *
                FROM bdcorporativo.scsac.tbAvaliacaoItemPedidoAlteracao
                WHERE idAvaliacaoItemPedidoAlteracao = $idAvaliacao ";
		
        return $sql;
    }

    /*     * *****************************************************************************
      SQL PARA INICIAR A SOLICITA��O DE READEQUA��O DE PRODUTOS
     * ***************************************************************************** */

    public static function stReadequacaoInicio($sqlDesejado, $idPedidoAlteracao, $idAgente) {

        $objAcesso= new Acesso();
        if ($sqlDesejado == "readequacaoEA") {
            $sql = "UPDATE bdcorporativo.scsac.tbAvaliacaoItemPedidoAlteracao
                    SET idAgenteAvaliador = $idAgente, dtInicioAvaliacao = {$objAcesso->getDate()}, stAvaliacaoItemPedidoAlteracao = 'EA'
                    WHERE idAvaliacaoItemPedidoAlteracao = $idPedidoAlteracao ";
        } else if ($sqlDesejado == "readequacaoAP") {
            $sql = "UPDATE bdcorporativo.scsac.tbAvaliacaoItemPedidoAlteracao
                    SET idAgenteAvaliador = $idAgente, dtInicioAvaliacao = {$objAcesso->getDate()}, stAvaliacaoItemPedidoAlteracao = 'AP'
                    WHERE idAvaliacaoItemPedidoAlteracao = $idPedidoAlteracao ";
        } else if ($sqlDesejado == "readequacaoIN") {
            $sql = "UPDATE bdcorporativo.scsac.tbAvaliacaoItemPedidoAlteracao
                    SET idAgenteAvaliador = $idAgente, dtInicioAvaliacao = {$objAcesso->getDate()}, stAvaliacaoItemPedidoAlteracao = 'IN'
                    WHERE idAvaliacaoItemPedidoAlteracao = $idPedidoAlteracao ";
        }

        return $sql;
    }

    /*     * **************************************************************************************
      SQL ALTERAR O STATUS DO CAMPO stVerificacao DA TABELA tbPedidoAlteracaoXTipoAlteracao
     * ************************************************************************************** */

    public static function readequacaoAltCampo($idPedido) {

        $sql = "UPDATE bdcorporativo.scsac.tbAcaoAvaliacaoItemPedidoAlteracao
                SET stVerificacao = 1
                WHERE idAvaliacaoItemPedidoAlteracao = $idPedido
                AND stAtivo = 0 ";
        return $sql;
    }

    public static function dadosAgentesOrgaoA($idorgao) {
//sis_codigo = 21 (Trata-se do c�digo do SalicWeb)
//gru_codigo = 129 (C�digo de T�cnico de acompanhamento)

        $sql = "SELECT a.usu_codigo, a.usu_nome, a.gru_nome AS Perfil, b.idAgente, a.gru_codigo AS idVerificacao
                FROM tabelas.dbo.vwUsuariosOrgaosGrupos a
                INNER JOIN agentes.dbo.Agentes AS b ON a.usu_identificacao = b.CNPJCPF
                WHERE sis_codigo = 21 and uog_orgao = $idorgao
                 AND gru_codigo in (121) AND a.uog_status = 1 
                ORDER BY usu_nome ";

        return $sql;
    }

    public static function dadosAgentesOrgaoB($idorgao) {
//sis_codigo = 21 (Trata-se do c�digo do SalicWeb)
//gru_codigo = 93 (C�digo de Coordenador de Parecerista)

        $sql = "SELECT a.usu_codigo, a.usu_nome, a.gru_nome AS Perfil, b.idAgente, a.gru_codigo AS idVerificacao
                FROM tabelas.dbo.vwUsuariosOrgaosGrupos a
                INNER JOIN agentes.dbo.Agentes AS b ON a.usu_identificacao = b.CNPJCPF
                WHERE sis_codigo = 21 and uog_orgao = $idorgao
                AND gru_codigo IN (121, 93) AND a.uog_status = 1 
                ORDER BY gru_codigo ";


        return $sql;
    }

    public static function dadosAgentesPerfil($idagente) {
        $sql = "SELECT DISTINCT
                    vuog.usu_codigo,
                    vuog.usu_nome,
                    d.idVerificacao,
                    d.Descricao AS Perfil,
                    ag.idAgente
                FROM TABELAS.dbo.vwUsuariosOrgaosGrupos vuog
                LEFT JOIN agentes.dbo.Agentes ag on (ag.CNPJCPF = vuog.usu_identificacao)
                LEFT JOIN agentes.dbo.Nomes AS b ON ag.idAgente = b.idAgente  and b.TipoNome = 18
                LEFT JOIN agentes.dbo.Visao AS c ON ag.idAgente = c.idAgente
                LEFT JOIN agentes.dbo.Verificacao AS d ON c.Visao = d.idVerificacao AND d.IdTipo = 16
                WHERE ag.idAgente = $idagente ";

        return $sql;
    }

    public static function retornaSQLlista($sqlDesejado, $idOrgao) {

        if ($sqlDesejado == "listasDeEncaminhamento") {

            $sql = "SELECT a.usu_codigo, a.usu_nome, a.gru_nome AS Perfil, b.idAgente, a.gru_codigo AS idVerificacao
                    FROM tabelas.dbo.vwUsuariosOrgaosGrupos a
                    INNER JOIN agentes.dbo.Agentes AS b ON a.usu_identificacao = b.CNPJCPF
                    WHERE sis_codigo = 21 and uog_orgao = $idOrgao
                    AND gru_codigo = 94 AND a.uog_status = 1 
                    ORDER BY usu_nome ";
          
        }

        if ($sqlDesejado == "listasDeEntidadesVinculadas") {

 			$sql = "SELECT * FROM sac.dbo.Orgaos
                    WHERE Status = 0 ";

			if (!empty($idOrgao)) :
				$sql.= " AND Codigo = '$idOrgao'";
			endif;

			$sql.= "ORDER BY Sigla";
                   
            //$sql = "select org_codigo as Codigo,org_sigla as Sigla from Orgaos ORDER BY org_sigla";
            //$sql = "SELECT DISTINCT uog_orgao as Codigo,org_siglaautorizado as Sigla FROM vwUsuariosOrgaosGrupos ORDER BY org_siglaautorizado";
        }
        if ($sqlDesejado == "listasDeEntidadesVinculadasPar") {
            $sql = "SELECT * FROM sac.dbo.Orgaos 
                    WHERE Vinculo = 1 AND Status = 0 AND idSecretaria IS NOT NULL 
                    ORDER BY Sigla";
        }
        if ($sqlDesejado == "listasDeEntidadesVinculadasEspecificas") {

            $sql = "SELECT * FROM sac.dbo.Orgaos
                    WHERE Vinculo = 1 AND Status = 0 AND idSecretaria IS NOT NULL 
                          AND Codigo in ({$idOrgao})
                    ORDER BY Sigla DESC";
            //$sql = "select org_codigo as Codigo,org_sigla as Sigla from Orgaos ORDER BY org_sigla";
            //$sql = "SELECT DISTINCT uog_orgao as Codigo,org_siglaautorizado as Sigla FROM vwUsuariosOrgaosGrupos ORDER BY org_siglaautorizado";
        }

        return $sql;
    }

    /*     * ***********************************************************
     * VERIFICA O PERFIL DO AGENTE ACIONADO
     * ************************************************************ */

    public static function retornaSQLPerfilAgente() {

        $sql = "";

        return $sql;
    }

    /*     * *********************************************************** */

//SQL PARA ENCAMINHAR DE COORDENADOR DE ACOMPANHAMENTO PARA COORDENADOR DE PARECERISTA
    public static function retornaSQLencaminhar($sqlDesejado, $ID_PRONAC, $idPedidoAlteracao, $tpAlteracaoProjeto, $justificativa, $Orgao, $idAgenteReceber) {
        if ($sqlDesejado == "sqlAlteraVariavelAltProj") {

            $sql = "UPDATE bdcorporativo.scsac.tbPedidoAlteracaoProjeto
                    SET siVerificacao = 1
                    WHERE idPedidoAlteracao = $idPedidoAlteracao
                    AND IdPRONAC = $ID_PRONAC ";
        }

        if ($sqlDesejado == "sqlAlteraVariavelTipoAlt") {

            $sql = "UPDATE bdcorporativo.scsac.tbPedidoAlteracaoXTipoAlteracao
                    SET stVerificacao = 1
                    WHERE idPedidoAlteracao = $idPedidoAlteracao
                    AND tpAlteracaoProjeto = $tpAlteracaoProjeto ";
        }

        if ($sqlDesejado == "sqlCoordAcompEncaminhar") {

            $sql = "INSERT bdcorporativo.scsac.tbAvaliacaoItemPedidoAlteracao
                    VALUES ('$idPedidoAlteracao','$tpAlteracaoProjeto','','','','AG','')";
        }

        if ($sqlDesejado == "sqlRecuperarRegistro") {

            $sql = "SELECT TOP 1 idAvaliacaoItemPedidoAlteracao
                    FROM bdcorporativo.scsac.tbAvaliacaoItemPedidoAlteracao
                    WHERE idPedidoAlteracao = '$idPedidoAlteracao'
                    AND tpAlteracaoProjeto = '$tpAlteracaoProjeto'
                    ORDER BY 1 DESC ";
        }
        return $sql;
    }

//SQL PARA GERAR UMA A��O NA TABELA tbAcaoAvaliacaoItemPedidoAlteracao
    public static function retornaSQLtbAcao($idAvaliacaoItemPedidoAlteracao, $justificativa, $tipoAg, $Orgao, $idAgenteReceber, $idAgenteRemente, $idPerfilRemetente) {
        $objAcesso= new Acesso();
        $sql = "INSERT INTO bdcorporativo.scsac.tbAcaoAvaliacaoItemPedidoAlteracao
                VALUES ('$idAvaliacaoItemPedidoAlteracao','$idAgenteReceber','" . $justificativa . "','$tipoAg','$Orgao','0','0',{$objAcesso->getDate()},'$idAgenteRemente','$idPerfilRemetente')";
        return $sql;
    }

    /*     * ***********************************************************************
     * //SQL PARA ENCAMINHAR DE COORDENADOR PARECERISTA PARA PARECERISTA
     * *********************************************************************** */

    public static function retornaSQLencaminharParecerista($sqlDesejado, $idAvaliacaoItemPedidoAlteracao, $idAcao, $stAcao, $justificativa, $agenteNovo, $Orgao, $idAgenteRemetente, $idPerfilRemetente) {

        if ($sqlDesejado == "sqlAlteraVariavel") {

            $sql = "UPDATE bdcorporativo.scsac.tbAcaoAvaliacaoItemPedidoAlteracao
                    SET stAtivo = 1
                    WHERE idAcaoAvaliacaoItemPedidoAlteracao = $idAcao
                    AND idAvaliacaoItemPedidoAlteracao = $idAvaliacaoItemPedidoAlteracao ";
        }

        $objAcesso= new Acesso();
        if ($sqlDesejado == "sqlCoordPareceristaEncaminhar") {

            $sql = "INSERT INTO bdcorporativo.scsac.tbAcaoAvaliacaoItemPedidoAlteracao
                    VALUES ('$idAvaliacaoItemPedidoAlteracao','$agenteNovo','" . $justificativa . "','1','$Orgao','0','0',{$objAcesso->getDate()}, '$idAgenteRemetente', '$idPerfilRemetente')";
        }

        return $sql;
    }

    public static function retornaSQLReencaminharPar($idPedidoAlteracao, $tpAlteracaoProjeto) {
        $sql = "UPDATE bdcorporativo.scsac.tbPedidoAlteracaoXTipoAlteracao
                SET stVerificacao = 1
                WHERE idPedidoAlteracao = $idPedidoAlteracao
                AND tpAlteracaoProjeto = $tpAlteracaoProjeto ";
        return $sql;
    }

    public static function reencaminharPar($idPedidoAlteracao, $tpAlteracaoProjeto) {
        $sql = "INSERT bdcorporativo.scsac.tbAvaliacaoItemPedidoAlteracao
                VALUES ('$idPedidoAlteracao','$tpAlteracaoProjeto','','','','AG','')";
        return $sql;
    }

    public static function reencaminharPar1($idAcao) {
        $sql = "UPDATE bdcorporativo.scsac.tbAcaoAvaliacaoItemPedidoAlteracao
                SET stAtivo = 1
                WHERE idAcaoAvaliacaoItemPedidoAlteracao = $idAcao ";
        return $sql;
    }

    public static function reencaminharPar2($idPedidoAlteracao, $tpAlteracaoProjeto) {
        $sql = "SELECT TOP 1 idAvaliacaoItemPedidoAlteracao
                FROM bdcorporativo.scsac.tbAvaliacaoItemPedidoAlteracao
                WHERE idPedidoAlteracao = $idPedidoAlteracao
                AND tpAlteracaoProjeto = $tpAlteracaoProjeto
                ORDER BY 1 DESC";
        return $sql;
    }

    public static function verificaProdutos($idPedidoAlteracao) {
        $sql = "SELECT * FROM sac.dbo.tbplanodistribuicao WHERE idPedidoAlteracao = $idPedidoAlteracao ";
        return $sql;
    }

    public static function verificaPedidoAlteracaoProjetoProduto($idPronac) {
        $sql = "select * from bdcorporativo.scSac.tbPedidoAlteracaoProjeto tpa inner join
        bdcorporativo.scSac.tbPedidoAlteracaoXTipoAlteracao paxta on tpa.idPedidoAlteracao = paxta.idPedidoAlteracao WHERE idPronac = $idPronac AND tpAlteracaoProjeto = 7";
        return $sql;
    }


    public static function reencaminharPar3($idAvaliacaoItemPedidoAlteracao, $idAgente, $justificativa, $Orgao, $idAgenteRemetente, $idPerfilRemetente) {
        $objAcesso= new Acesso();
        $sql = "INSERT INTO bdcorporativo.scsac.tbAcaoAvaliacaoItemPedidoAlteracao
                VALUES ('$idAvaliacaoItemPedidoAlteracao','$idAgente','" . $justificativa . "','1','$Orgao','0','0',{$objAcesso->getDate()}, $idAgenteRemetente, $idPerfilRemetente)";
        return $sql;
    }

    public static function reencaminharPar4() {
        $sql = "SELECT TOP 1 idAvaliacaoItemPedidoAlteracao
                FROM bdcorporativo.scsac.tbAvaliacaoItemPedidoAlteracao
                WHERE idPedidoAlteracao = $idPedidoAlteracao
                AND tpAlteracaoProjeto = $tpAlteracaoProjeto
                ORDER BY 1 DESC ";
        return $sql;
    }

    public static function reencaminharPar5($idAvaliacaoItemPedidoAlteracao, $idAgenteLogado, $justificativa, $Orgao, $idPerfil, $idAgente, $idGrupo) {
        $objAcesso= new Acesso();
        $sql = "INSERT INTO bdcorporativo.scsac.tbAcaoAvaliacaoItemPedidoAlteracao
                VALUES ('$idAvaliacaoItemPedidoAlteracao','$idAgenteLogado','" . $justificativa . "','$idPerfil','$Orgao','0','0',{$objAcesso->getDate()}, '$idAgente', '$idGrupo')";
        return $sql;
    }

    /*     * ***********************************************************************
      //SQLs PARA FINALIZA��O DA READEQUA��O DE PRODUTO
     * *********************************************************************** */

    public static function retornaSQLfinalizarPar($idPedidoAlteracao,$situacao,$justificativa) {
        $objAcesso= new Acesso();
        $sql = "UPDATE bdcorporativo.scsac.tbAvaliacaoItemPedidoAlteracao
                SET stAvaliacaoItemPedidoAlteracao = '".$situacao."', dtFimAvaliacao = {$objAcesso->getDate()}, dsAvaliacao = '".$justificativa."'
                WHERE idPedidoAlteracao = $idPedidoAlteracao
                AND dtFimAvaliacao = '1900-01-01 00:00:00.000' ";
        return $sql;
    }

    public static function retornaSQLfinalizarPar2($idPedidoAlteracao) {
        $sql = "SELECT a.idAvaliacaoItemPedidoAlteracao, a.idAgenteAvaliador, idOrgao
                FROM bdcorporativo.scsac.tbAvaliacaoItemPedidoAlteracao AS a
                INNER JOIN bdcorporativo.scsac.tbAcaoAvaliacaoItemPedidoAlteracao AS b ON a.idAvaliacaoItemPedidoAlteracao = b.idAvaliacaoItemPedidoAlteracao
                WHERE idPedidoAlteracao = $idPedidoAlteracao
                AND stAtivo = 0 ";
        return $sql;
    }

    public static function retornaSQLfinalizarPar3($idAvaliacaoItemPedidoAlteracao) {
        $sql = "UPDATE bdcorporativo.scsac.tbAcaoAvaliacaoItemPedidoAlteracao
                SET stAtivo = 1
                WHERE idAvaliacaoItemPedidoAlteracao = $idAvaliacaoItemPedidoAlteracao
                AND stAtivo = 0 ";
        return $sql;
    }

    public static function retornaSQLfinalizarPar4($idAvaliacaoItemPedidoAlteracao, $idAgenteAvaliador, $idOrgao, $idAgenteRemetente, $idGrupoRemetente) {
        $objAcesso= new Acesso();
        $sql = "INSERT INTO bdcorporativo.scsac.tbAcaoAvaliacaoItemPedidoAlteracao
                VALUES ('$idAvaliacaoItemPedidoAlteracao','$idAgenteAvaliador','','3','$idOrgao','0','2',{$objAcesso->getDate()}, $idAgenteRemetente, $idGrupoRemetente) ";
        return $sql;
    }

    //serve somente para o item de custo (IC)
    public static function retornaSQLfinalizarPar4IC($idAvaliacaoItemPedidoAlteracao, $idAgenteAvaliador, $idOrgao, $idAgenteRemetente, $idGrupoRemetente) {
        $objAcesso= new Acesso();
        $sql = "INSERT INTO bdcorporativo.scsac.tbAcaoAvaliacaoItemPedidoAlteracao
                VALUES ('$idAvaliacaoItemPedidoAlteracao','$idAgenteAvaliador','','2','$idOrgao','0','2',{$objAcesso->getDate()}, $idAgenteRemetente, $idGrupoRemetente) ";
        return $sql;
    }

    public static function retornaSQLfinalizarParST($idAvaliacaoItemPedidoAlteracao) {
        $sql = "select * from bdcorporativo.scsac.tbAvaliacaoItemPedidoAlteracao where idAvaliacaoItemPedidoAlteracao = $idAvaliacaoItemPedidoAlteracao ";
        return $sql;
    }

    public static function retornaSQLfinalizarParST2($idPedidoAlteracao, $tpAlteracaoProjeto) {
        $sql = "UPDATE bdcorporativo.scsac.tbPedidoAlteracaoXTipoAlteracao
                SET stVerificacao = 2
                WHERE idPedidoAlteracao = $idPedidoAlteracao
                AND tpAlteracaoProjeto = $tpAlteracaoProjeto ";
        return $sql;
    }

    /*     * ***********************************************************************
     * SQL PARA LISTAR O HIST�RICO
     * *********************************************************************** */

    public static function retornaSQLHistorico($sqlDesejado) {

        if ($sqlDesejado == "sqlListarHistorico") {

            $sql = "SELECT distinct e.IdPRONAC, e.NomeProjeto, b.idPedidoAlteracao, a.dtEncaminhamento, a.idOrgao, f.Sigla, a.idTipoAgente, a.dsObservacao, stAtivo, a.idAcaoAvaliacaoItemPedidoAlteracao AS idAcao, b.tpAlteracaoProjeto
                    FROM bdcorporativo.scsac.tbAcaoAvaliacaoItemPedidoAlteracao AS a
                    INNER JOIN bdcorporativo.scsac.tbAvaliacaoItemPedidoAlteracao AS b ON a.idAvaliacaoItemPedidoAlteracao = b.idAvaliacaoItemPedidoAlteracaO
                    INNER JOIN bdcorporativo.scsac.tbPedidoAlteracaoXTipoAlteracao AS c ON b.idPedidoAlteracao = c.idPedidoAlteracao
                    INNER JOIN bdcorporativo.scsac.tbPedidoAlteracaoProjeto AS d ON c.idPedidoAlteracao = d.idPedidoAlteracao
                    INNER JOIN sac.dbo.Projetos AS e ON d.IdPRONAC = e.IdPRONAC
                    INNER JOIN sac.dbo.Orgaos AS f ON a.idOrgao = f.Codigo ";
        }

        if ($sqlDesejado == "sqlListarHistoricoUnico") {

            $sql = "SELECT distinct e.IdPRONAC, e.NomeProjeto, b.idPedidoAlteracao, a.dtEncaminhamento, a.idOrgao, f.Sigla, a.idTipoAgente, a.dsObservacao, c.tpAlteracaoProjeto, stAtivo, a.idAcaoAvaliacaoItemPedidoAlteracao AS idAcao, b.tpAlteracaoProjeto, b.idAvaliacaoItemPedidoAlteracao
                    FROM bdcorporativo.scsac.tbAcaoAvaliacaoItemPedidoAlteracao AS a
                    INNER JOIN bdcorporativo.scsac.tbAvaliacaoItemPedidoAlteracao AS b ON a.idAvaliacaoItemPedidoAlteracao = b.idAvaliacaoItemPedidoAlteracaO
                    INNER JOIN bdcorporativo.scsac.tbPedidoAlteracaoXTipoAlteracao AS c ON b.idPedidoAlteracao = c.idPedidoAlteracao and c.tpAlteracaoProjeto = b.tpAlteracaoProjeto
                    INNER JOIN bdcorporativo.scsac.tbPedidoAlteracaoProjeto AS d ON c.idPedidoAlteracao = d.idPedidoAlteracao
                    INNER JOIN sac.dbo.Projetos AS e ON d.IdPRONAC = e.IdPRONAC
                    INNER JOIN sac.dbo.Orgaos AS f ON a.idOrgao = f.Codigo
                    WHERE stAtivo = 0 ";
        }
        return $sql;
    }

    public static function retornaSQLHistoricoLista($idavaliacao) {

        $sql = "
        SELECT *, CAST(dsObservacao AS text) as dsObservacao FROM
            (
        SELECT distinct e.IdPRONAC, e.NomeProjeto, b.idPedidoAlteracao, a.dtEncaminhamento, a.idOrgao, f.Sigla, a.idTipoAgente, a.dsObservacao, stAtivo, a.idAcaoAvaliacaoItemPedidoAlteracao AS idAcao, b.tpAlteracaoProjeto, i.usu_nome AS Remetente, g.gru_nome AS perfilRemetente, k.usu_nome AS Destinatario, l.dsTipoAgente AS perfilDestinatario
                FROM bdcorporativo.scsac.tbAcaoAvaliacaoItemPedidoAlteracao AS a
                INNER JOIN bdcorporativo.scsac.tbAvaliacaoItemPedidoAlteracao AS b ON a.idAvaliacaoItemPedidoAlteracao = b.idAvaliacaoItemPedidoAlteracaO
                INNER JOIN bdcorporativo.scsac.tbPedidoAlteracaoXTipoAlteracao AS c ON b.idPedidoAlteracao = c.idPedidoAlteracao
                INNER JOIN bdcorporativo.scsac.tbPedidoAlteracaoProjeto AS d ON c.idPedidoAlteracao = d.idPedidoAlteracao
                INNER JOIN sac.dbo.Projetos AS e ON d.IdPRONAC = e.IdPRONAC
                INNER JOIN sac.dbo.Orgaos AS f ON a.idOrgao = f.Codigo
                INNER JOIN TABELAS.dbo.Grupos AS g ON g.gru_codigo = a.idPerfilRemetente
                INNER JOIN agentes.dbo.Agentes AS h ON h.idAgente = a.idAgenteRemetente
                INNER JOIN TABELAS.dbo.Usuarios AS i ON i.usu_identificacao = h.CNPJCPF
                LEFT JOIN agentes.dbo.Agentes AS j ON j.idAgente = a.idAgenteAcionado
                LEFT JOIN TABELAS.dbo.Usuarios AS k ON k.usu_identificacao = j.CNPJCPF
                INNER JOIN bdcorporativo.scsac.tbTipoAgente AS l ON l.idTipoAgente = a.idTipoAgente
                where b.idAvaliacaoItemPedidoAlteracao = $idavaliacao ) as minhaTabela";
        return $sql;
    }

    /*     * ***********************************************************************
     * SQL PARA DEVOLVER MINC (TELA DE COORDENADOR DE PARECERISTA)
     * *********************************************************************** */

    public static function retornaSQLdevolverMinc($idAcao) {
        $sql = "UPDATE bdcorporativo.scsac.tbAcaoAvaliacaoItemPedidoAlteracao
                SET stAtivo = 1
                WHERE idAcaoAvaliacaoItemPedidoAlteracao = $idAcao ";
        return $sql;
    }

    public static function retornaSQLdevolverMinc2($idAcao) {
        $sql = "SELECT idAvaliacaoItemPedidoAlteracao, idOrgao
                FROM bdcorporativo.scsac.tbAcaoAvaliacaoItemPedidoAlteracao
                WHERE idAcaoAvaliacaoItemPedidoAlteracao = $idAcao ";
        return $sql;
    }

    public static function retornaSQLdevolverMinc3($id) {
        $sql = "SELECT idPedidoAlteracao, tpAlteracaoProjeto
                FROM bdcorporativo.scsac.tbAvaliacaoItemPedidoAlteracao
                WHERE idAvaliacaoItemPedidoAlteracao = $id ";
        return $sql;
    }

    public static function retornaSQLdevolverMinc4($idPedidoAlt, $tpAlt) {
        $sql = "UPDATE bdcorporativo.scsac.tbPedidoAlteracaoXTipoAlteracao
                SET stVerificacao = 3
                WHERE idPedidoAlteracao = $idPedidoAlt
                AND tpAlteracaoProjeto = $tpAlt ";
        return $sql;
    }

    public static function retornaSQLdevolverMinc5($id, $idOrgao, $idAgenteRemetente, $idPerfilRemetente) {
        $objAcesso= new Acesso();
        $sql = "INSERT INTO bdcorporativo.scsac.tbAcaoAvaliacaoItemPedidoAlteracao
                VALUES ('$id','','','3','$idOrgao','0','3',{$objAcesso->getDate()},'$idAgenteRemetente','$idPerfilRemetente') ";
        return $sql;
    }

    /*     * *****************************************************************
     * SQL PARA FINALIZAR GERAL (TELA DE COORDENADOR DE ACOMPANHAMENTO)
     * ****************************************************************** */

    public static function retornaSQLfinalizaGeral($idAcao) {
        $sql = "UPDATE bdcorporativo.scsac.tbAcaoAvaliacaoItemPedidoAlteracao
                SET stAtivo = 1
                WHERE idAcaoAvaliacaoItemPedidoAlteracao = $idAcao ";
        return $sql;
    }

    public static function retornaSQLfinalizaGeral2($idAcao) {
        $sql = "SELECT idAvaliacaoItemPedidoAlteracao, idOrgao, dsObservacao
                FROM bdcorporativo.scsac.tbAcaoAvaliacaoItemPedidoAlteracao
                WHERE idAcaoAvaliacaoItemPedidoAlteracao = $idAcao ";
        return $sql;
    }

    public static function retornaSQLfinalizaGeral3($id) {
        $sql = "SELECT idPedidoAlteracao, tpAlteracaoProjeto, stAvaliacaoItemPedidoAlteracao, idAgenteAvaliador 
                FROM bdcorporativo.scsac.tbAvaliacaoItemPedidoAlteracao
                WHERE idAvaliacaoItemPedidoAlteracao = $id ";
        return $sql;
    }

    public static function retornaSQLfinalizaGeral4($idPedidoAlt, $tpAlt) {
        $sql = "UPDATE bdcorporativo.scsac.tbPedidoAlteracaoXTipoAlteracao
                SET stVerificacao = 4
                WHERE idPedidoAlteracao = $idPedidoAlt
                AND tpAlteracaoProjeto = $tpAlt ";
        return $sql;
    }

    public static function retornaSQLfinalizaGeral5($id, $idOrgao, $idAgenteRemetente, $idPerfilRemetente) {
        $objAcesso= new Acesso();
        $sql = "INSERT INTO bdcorporativo.scsac.tbAcaoAvaliacaoItemPedidoAlteracao
                VALUES ('$id','','','4','$idOrgao','1','4',{$objAcesso->getDate()},'$idAgenteRemetente','$idPerfilRemetente') ";
        return $sql;
    }

    public static function retornaSQLfinalizaGeral6($idPedidoAlt) {
        $sql = "SELECT IdPRONAC
                FROM bdcorporativo.scsac.tbPedidoAlteracaoProjeto
                WHERE idPedidoAlteracao = $idPedidoAlt ";
        return $sql;
    }

    //Alterar o TipoParecer da Tabela sac.dbo.Parecer
    public static function AlteraTipoParecer($idPronac) {
        $sql = " update sac.dbo.Parecer set TipoParecer = 2 where idPRONAC = $idPronac";
        return $sql;
    }




    // Atualiza os dados originais ap�s a finaliza��o do coordenador de acompanhamento
    public static function finalizacaoCoordAcomp($tabela, $campo, $dados, $where, $id) {
        $sql = "UPDATE ".$tabela."
                SET ".$campo." = '".$dados."'
                WHERE ".$where." = $id ";
        return $sql;
    }

    public static function ConsultaFinalPropPedag($id_Pronac){
        $sql = "SELECT DISTINCT b.IdPRONAC,
                        d.idPreProjeto,
                        c.AnoProjeto+c.Sequencial AS PRONAC,
                        c.NomeProjeto AS NomeProjeto,
                        c.CgcCpf AS CNPJCPF,
                        f.Nome AS proponente,
                        f.CgcCpf,
                        d.EstrategiadeExecucao,
                        d.EspecificacaoTecnica,
                        a.dsObjetivos AS Solicitacao,
                        a.dsJustificativa AS Justificativa,
                        a.dsEstrategiaExecucao,
                        a.dsEspecificacaoTecnica,
                        g.stAvaliacaoItemPedidoAlteracao,
                        i.dsJustificativa

                    FROM sac.dbo.tbProposta AS a
                    INNER JOIN bdcorporativo.scsac.tbPedidoAlteracaoProjeto             AS b ON b.idPedidoAlteracao = a.idPedidoAlteracao
                    INNER JOIN sac.dbo.Projetos                                         AS c ON c.IdPRONAC = b.IdPRONAC
                    INNER JOIN sac.dbo.PreProjeto                                       AS d ON d.idPreProjeto = c.idProjeto
                    INNER JOIN agentes.dbo.Agentes                                      AS e ON e.idAgente = d.idAgente
                    INNER JOIN sac.dbo.vProponenteProjetos                              AS f ON c.CgcCpf = f.CgcCpf
                    INNER JOIN bdcorporativo.scsac.tbAvaliacaoItemPedidoAlteracao       AS g ON g.idPedidoAlteracao = b.idPedidoAlteracao
                    INNER JOIN bdcorporativo.scsac.tbAcaoAvaliacaoItemPedidoAlteracao   AS h ON g.idAvaliacaoItemPedidoAlteracao = h.idAvaliacaoItemPedidoAlteracao
                    INNER JOIN bdcorporativo.scsac.tbPedidoAlteracaoXTipoAlteracao	AS i ON i.idPedidoAlteracao = g.idPedidoAlteracao

                    WHERE b.IdPRONAC = ".$id_Pronac."
                    AND h.stAtivo = 1
                    AND g.tpAlteracaoProjeto = 6 ";
        return $sql;
    }
   

	public static function buscarJustificativaFinalParecerista($idAvaliacaoItemPedidoAlteracao)
	{
		$sql = "SELECT CAST(dsObservacao AS TEXT) AS dsObservacao, idAgenteRemetente  
				FROM bdcorporativo.scsac.tbAcaoAvaliacaoItemPedidoAlteracao 
				WHERE idAvaliacaoItemPedidoAlteracao = $idAvaliacaoItemPedidoAlteracao AND idTipoAgente = 2 AND stVerificacao = 2";
		
		return $sql;
	}
}

?>