<?php
class ManterorcamentoDAO extends MinC_Db_Table_Abstract {

    protected $_schema = 'sac';
    protected $_name = '';

    public function __construct() {
        parent::__construct();
    }

    public function init(){
        parent::init();
    }

    public static function buscarPlanilhaOrcamentariaP($idPreProjeto) {
        $sql = "SELECT
					p.Codigo as CodigoProduto,
					pp.idEtapa as idEtapa,
					pp.idPlanilhaItem as idItem,
					te.Descricao as DescricaoEtapa,
					ti.Descricao as DescricaoItem,
					tu.Descricao as DescricaoUnidade,
					pp.Quantidade as Quantidade,
					pp.Ocorrencia as Ocorrencia,
					pp.ValorUnitario as ValorUnitario
					FROM SAC.dbo.PreProjeto pre
						INNER JOIN SAC.dbo.tbPlanilhaProposta pp ON (pre.idPreProjeto = pp.idProjeto)
						INNER JOIN SAC.dbo.Produto P ON (pp.idProduto = p.Codigo)
						INNER JOIN SAC..tbPlanilhaEtapa te ON te.idPlanilhaEtapa = pp.idEtapa
						INNER JOIN SAC..tbPlanilhaItens ti ON ti.idPlanilhaItens = pp.idPlanilhaItem
						INNER JOIN SAC..tbPlanilhaUnidade tu ON tu.idUnidade = pp.Unidade
					WHERE idPreProjeto = {$idPreProjeto}";

        $sql.= " ORDER BY p.Codigo ";

        try {
            $db = Zend_Db_Table::getDefaultAdapter();
            $db->setFetchMode(Zend_DB::FETCH_OBJ);
        }
        catch (Zend_Exception_Db $e) {
            $this->view->message = "Erro ao buscar Etapas: " . $e->getMessage();
        }
        //die($sql);
        return $db->fetchAll($sql);
    }

    public static function buscarPlanilhaOrcamentariaC($idPreProjeto) {
        $sql = "SELECT
                    pp.idEtapa as idEtapa,
                    te.Descricao as DescricaoEtapa,
                    pp.idPlanilhaItem as idItem,
                    ti.Descricao as DescricaoItem,
                    tu.Descricao as DescricaoUnidade,
                    pp.Quantidade as Quantidade,
                    pp.Ocorrencia as Ocorrencia,
                    pp.ValorUnitario as ValorUnitario,
                    uf.Descricao as Estado
                    FROM SAC.dbo.PreProjeto pre
                            INNER JOIN SAC.dbo.tbPlanilhaProposta pp ON (pre.idPreProjeto = pp.idProjeto)
                            INNER JOIN SAC..tbPlanilhaEtapa te ON te.idPlanilhaEtapa = pp.idEtapa
                            INNER JOIN SAC..tbPlanilhaItens ti ON ti.idPlanilhaItens = pp.idPlanilhaItem
                            LEFT JOIN SAC..tbPlanilhaUnidade tu ON tu.idUnidade = pp.Unidade
                            INNER JOIN SAC..Uf uf ON uf.CodUfIbge = pp.UfDespesa
                    WHERE idPreProjeto = {$idPreProjeto} and tpCusto = 'A'";

        $sql.= " ORDER BY te.Descricao ";

        try {
            $db = Zend_Db_Table::getDefaultAdapter();
            $db->setFetchMode(Zend_DB::FETCH_OBJ);
        }
        catch (Zend_Exception_Db $e) {
            $this->view->message = "Erro ao buscar Etapas: " . $e->getMessage();
        }
        //xd($sql);
        return $db->fetchAll($sql);
    }

    public static function buscarPlanilha($idPreProjeto) {
        $sql = "SELECT
                    pp.idEtapa as idEtapa,
                    pp.idPlanilhaItem as idItem,
                    ti.Descricao as DescricaoItem,
                    tu.Descricao as DescricaoUnidade,
                    pp.Quantidade as Quantidade,
                    pp.Ocorrencia as Ocorrencia,
                    pp.ValorUnitario as ValorUnitario,
                    uf.Descricao as Estado,
                    pp.dsJustificativa as Justificativa
                    FROM SAC.dbo.PreProjeto pre
                            INNER JOIN SAC.dbo.tbPlanilhaProposta pp ON (pre.idPreProjeto = pp.idProjeto)
                            INNER JOIN SAC..tbPlanilhaEtapa te ON te.idPlanilhaEtapa = pp.idEtapa
                            INNER JOIN SAC..tbPlanilhaItens ti ON ti.idPlanilhaItens = pp.idPlanilhaItem
                            LEFT JOIN SAC..tbPlanilhaUnidade tu ON tu.idUnidade = pp.Unidade
                            INNER JOIN SAC..Uf uf ON uf.CodUfIbge = pp.UfDespesa
                    WHERE idPreProjeto = {$idPreProjeto}";

        $sql.= " ORDER BY pp.idEtapa ";

        try {
            $db = Zend_Db_Table::getDefaultAdapter();
            $db->setFetchMode(Zend_DB::FETCH_OBJ);
        }
        catch (Zend_Exception_Db $e) {
            $this->view->message = "Erro ao buscar Etapas: " . $e->getMessage();
        }
        return $db->fetchAll($sql);
    }

    /**
     *
     */
    public static function buscarDadosEditarProdutos($idPreProjeto = null, $idEtapa = null, $idProduto = null, $idItem = null, $idPlanilhaProposta=null, $idUf = null, $municipio = null,
                                                                    $unidade = null, $qtd = null, $ocorrencia = null, $valor = null, $qtdDias = null, $fonte = null) {

        $db = Zend_Db_Table::getDefaultAdapter();

        $pp = [
                'pp.idPlanilhaProposta as idPlanilhaProposta',
                'pp.idEtapa as idEtapa',
                'pp.UfDespesa AS IdUf',
                'pp.MunicipioDespesa as Municipio',
                'pp.idPlanilhaItem AS idItem',
                'pp.FonteRecurso as Recurso',
                'pp.Quantidade as Quantidade',
                'pp.Ocorrencia as Ocorrencia',
                'pp.ValorUnitario as ValorUnitario',
                'CAST(pp.dsJustificativa AS TEXT) as Justificativa',
                'pp.QtdeDias as QtdDias',
                'pp.Unidade as Unidade',
        ];

        $sacSchema = parent::getSchema('sac');
        $sql = $db->select()->from(['pre' => 'PreProjeto' ],'pre.idPreProjeto as idProposta' , $sacSchema)
            ->join(['pp' => 'tbPlanilhaProposta'], 'pre.idPreProjeto = pp.idProjeto', $pp, $sacSchema)
            ->join(['p' => 'Produto'] , 'pp.idProduto = p.codigo', 'P.Codigo AS CodigoProduto', $sacSchema)
            ->join(['ti' => 'tbPlanilhaItens'], 'ti.idPlanilhaItens = pp.idPlanilhaItem', 'ti.Descricao as DescricaoItem', $sacSchema)
            ->join(['uf' => 'Uf' ], 'uf.CodUfIbge = pp.UfDespesa', 'uf.Descricao AS DescricaoUf', $sacSchema)
            ->join(['mun' => 'Municipios'], 'mun.idMunicipioIBGE = pp.MunicipioDespesa','mun.Descricao as DescricaoMunicipio', parent::getSchema('agentes'))
            ->join(['pe' => 'tbPlanilhaEtapa'], 'pp.idEtapa = pe.idPlanilhaEtapa', 'pe.Descricao as DescricaoEtapa', $sacSchema)
            ->join(['rec' => 'Verificacao'], 'rec.idVerificacao = pp.FonteRecurso', 'rec.Descricao as DescricaoRecurso', $sacSchema)
            ->join(['uni' => 'tbPlanilhaUnidade'], 'uni.idUnidade = pp.Unidade', 'uni.Descricao as DescricaoUnidade', $sacSchema)
            ->where('pp.idEtapa = ?', $idEtapa)
            ;

        if($idPreProjeto){
            $sql->where('pre.idPreProjeto = ?', $idPreProjeto);
        }
        if($idProduto){
            $sql->where('p.Codigo = ?', $idProduto);
        }
        if($idItem){
            $sql->where('pp.idPlanilhaItem = ?', $idItem);
        }
        if($idPlanilhaProposta){
            $sql->where('pre.idPreProjeto  = ?', $idPreProjeto);
        }

        if($idUf){
            $sql->where('pp.UfDespesa = ?', $idUf);
        }

           if($municipio){
            $sql->where('pp.MunicipioDespesa = ?', $municipio);
        }

        if($unidade){
            $sql->where('pp.Unidade = ?', $unidade);
        }

        if($qtd){
            $sql->where('pp.Quantidade = ?', $qtd);
        }

        if($ocorrencia){
            $sql->where('pp.Ocorrencia = ?', $ocorrencia);
        }

        if($valor){
            $sql->where('pp.ValorUnitario = ?', $valor);
        }

        if($qtdDias){
            $sql->where('pp.QtdeDias = ?', $qtdDias);
        }

        if($fonte){
            $sql->where('pp.FonteRecurso = ?', $fonte);
        }

        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        return $db->fetchAll($sql);
    }

    public static function buscarDadosCadastrarProdutos($idPreProjeto, $idProduto) {
        $sql = "
                SELECT DISTINCT
                pd.idProduto AS CodigoProduto,
                pd.idProjeto as idProposta
                FROM SAC.dbo.PlanoDistribuicaoProduto AS pd
                WHERE (pd.idProduto = $idProduto and pd.idProjeto = $idPreProjeto) AND pd.stPlanoDistribuicaoProduto = 1";

        //x($sql);
        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        return $db->fetchAll($sql);

    }

    public function listarDadosCadastrarCustos($idPreProjeto)
    {
        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);
        $sql = $db->select()
            ->from(['tpp' => 'tbplanilhaproposta'], 'tpp.idprojeto as idProposta', $this->getSchema('sac'))
            ->where('tpp.idProjeto = ?', $idPreProjeto)
            ->limit(1)
            ;

        return $db->fetchAll($sql);
    }

    public static function buscarDadosCustos($array = array()) {
        $sql = "SELECT
                    pp.idEtapa as idEtapa,
                    pe.Descricao as DescricaoEtapa,
                    pp.idPlanilhaItem AS idItem,
                    ti.Descricao as DescricaoItem,
                    pp.UfDespesa AS IdUf,
                    uf.Descricao AS DescricaoUf,
                    pp.MunicipioDespesa as Municipio,
                    mun.Descricao as DescricaoMunicipio,
                    pp.FonteRecurso as Recurso,
                    rec.Descricao as DescricaoRecurso,
                    pp.Unidade as Unidade,
                    uni.Descricao as DescricaoUnidade,
                    pp.Quantidade as Quantidade,
                    pp.Ocorrencia as Ocorrencia,
                    pp.ValorUnitario as ValorUnitario,
                    pp.QtdeDias as QtdDias,
                    CAST(pp.dsJustificativa AS TEXT) as Justificativa
                  FROM SAC.dbo.PreProjeto AS pre
                        INNER JOIN SAC.dbo.tbPlanilhaProposta pp ON pre.idPreProjeto = pp.idProjeto
                        INNER JOIN SAC.dbo.tbPlanilhaItens ti ON ti.idPlanilhaItens = pp.idPlanilhaItem
                        INNER JOIN SAC.dbo.Uf AS uf ON uf.CodUfIbge = pp.UfDespesa
                        INNER JOIN AGENTES.dbo.Municipios mun ON mun.idMunicipioIBGE = pp.MunicipioDespesa
                        INNER JOIN SAC.dbo.tbPlanilhaEtapa pe ON pp.idEtapa = pe.idPlanilhaEtapa
                        LEFT JOIN SAC.dbo.Verificacao rec ON rec.idVerificacao = pp.FonteRecurso
                        LEFT JOIN SAC.dbo.tbPlanilhaUnidade uni ON uni.idUnidade = pp.Unidade
                WHERE (pre.idPreProjeto = {$array['idPreProjeto']} and  pp.idEtapa = {$array['etapa']} and pp.idPlanilhaItem = {$array['item']} )
                       and pp.idPlanilhaProposta = {$array['idPlanilhaProposta']}";
        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);
        //xd($sql);
        return $db->fetchAll($sql);

    }

    public static function buscarEtapasProdutos($idPreProjeto)
    {
        $db = Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        $sql = "SELECT
                    distinct
                    p.Codigo as CodigoProduto,
                    pp.idProduto as idProduto,
                    pp.idEtapa as idEtapa,
                    te.Descricao as DescricaoEtapa,
                    pre.idPreProjeto as idPreProjeto
                    FROM SAC.dbo.PreProjeto pre
                    INNER JOIN SAC.dbo.tbPlanilhaProposta pp ON (pre.idPreProjeto = pp.idProjeto)
                    INNER JOIN SAC.dbo.Produto p ON (pp.idProduto = p.Codigo)
                    INNER JOIN SAC..tbPlanilhaEtapa te on te.idPlanilhaEtapa = pp.idEtapa
                    WHERE idPreProjeto = {$idPreProjeto}";

        $sql.= " ORDER BY te.DescricaoEtapa ";

        $sql = " SELECT idPlanilhaEtapa as idEtapa, Descricao as DescricaoEtapa FROM SAC.dbo.tbPlanilhaEtapa WHERE tpCusto = 'P' ";

        try {
        }
        catch (Zend_Exception_Db $e) {
            $this->view->message = "Erro ao buscar Etapas: " . $e->getMessage();
        }
        return $db->fetchAll($sql);
    }

    public function listarEtapasProdutos($idPreProjeto)
    {
        $db = Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        //$sql = $db->select()
            //->from(['pre' => 'preprojeto'], 'pre.idpreprojeto as idPreProjeto', $this->getSchema('sac'))
            //->join(['pp' => 'tbplanilhaproposta'], '(pre.idpreprojeto = pp.idProjeto)', ['pp.idproduto as idProduto', 'pp.idetapa as idEtapa'], $this->getSchema('sac'))
            //->join(['p' => 'produto'], '(pp.idproduto = p.codigo)', 'p.codigo as CodigoProduto', $this->getSchema('sac'))
            //->join(['te' => 'tbplanilhaetapa'], 'te.idplanilhaetapa = pp.idetapa', 'te.descricao as DescricaoEtapa', $this->getSchema('sac'))
            //->where('idpreprojeto = ?', $idPreProjeto)
            //->order('te.DescricaoEtapa')
            //;
        //echo $sql;die;

        //$sql = "SELECT
                    //distinct
                    //p.Codigo as CodigoProduto,
                    //pp.idProduto as idProduto,
                    //pp.idEtapa as idEtapa,
                    //te.Descricao as DescricaoEtapa,
                    //pre.idPreProjeto as idPreProjeto
                    //FROM SAC.dbo.PreProjeto pre
                    //INNER JOIN SAC.dbo.tbPlanilhaProposta pp ON (pre.idPreProjeto = pp.idProjeto)
                    //INNER JOIN SAC.dbo.Produto p ON (pp.idProduto = p.Codigo)
                    //INNER JOIN SAC..tbPlanilhaEtapa te on te.idPlanilhaEtapa = pp.idEtapa
                    //WHERE idPreProjeto = {$idPreProjeto}";

        //$sql.= " ORDER BY te.DescricaoEtapa ";

        $sql = $db->select()
            ->from(['tbplanilhaetapa'], ['idplanilhaetapa as idEtapa', 'descricao as DescricaoEtapa'], $this->getSchema('sac'))
            ->where("tpCusto = 'P'")
            ;

        //$sql = " SELECT idPlanilhaEtapa as idEtapa, Descricao as DescricaoEtapa FROM SAC.dbo.tbPlanilhaEtapa WHERE tpCusto = 'P' ";

        return $db->fetchAll($sql);
    }

    public static function buscarEtapasProdutosPlanilha($idPreProjeto) {

        $sql = "SELECT
                distinct
                pp.idEtapa as idEtapa,
                te.Descricao as DescricaoEtapa,
                pre.idPreProjeto as idPreProjeto,
                pp.idProduto
                FROM SAC.dbo.PreProjeto pre
                INNER JOIN SAC.dbo.tbPlanilhaProposta pp ON (pre.idPreProjeto = pp.idProjeto)
                INNER JOIN SAC.dbo.Produto p ON (pp.idProduto = p.Codigo)
                INNER JOIN SAC..tbPlanilhaEtapa te on te.idPlanilhaEtapa = pp.idEtapa
                WHERE idPreProjeto = {$idPreProjeto}";

        $sql.= " ORDER BY te.Descricao ";

        try {
            $db = Zend_Db_Table::getDefaultAdapter();
            $db->setFetchMode(Zend_DB::FETCH_OBJ);
        }
        catch (Zend_Exception_Db $e) {
            $this->view->message = "Erro ao buscar Etapas: " . $e->getMessage();
        }
        //die($sql);
        return $db->fetchAll($sql);
    }

    public static function buscarPlanilhaEtapa($idPreProjeto) {


        $sql = "SELECT distinct
                pp.idEtapa as idEtapa,
                te.Descricao as DescricaoEtapa
                FROM SAC.dbo.PreProjeto pre
                        INNER JOIN SAC.dbo.tbPlanilhaProposta pp ON (pre.idPreProjeto = pp.idProjeto)
                        INNER JOIN SAC..tbPlanilhaEtapa te ON te.idPlanilhaEtapa = pp.idEtapa
                WHERE idPreProjeto = {$idPreProjeto}";

        $sql.= " ORDER BY pp.idEtapa ";

        try {
            $db = Zend_Db_Table::getDefaultAdapter();
            $db->setFetchMode(Zend_DB::FETCH_OBJ);
        }
        catch (Zend_Exception_Db $e) {
            $this->view->message = "Erro ao buscar Etapas: " . $e->getMessage();
        }
        //xd($sql);
        return $db->fetchAll($sql);
    }

    public static function buscarEtapasCadastrarProdutos() {

        $sql = "SELECT
                idplanilhaetapa ,
                Descricao
                FROM SAC.dbo.tbPlanilhaEtapa where tpCusto = 'P'";


        $sql.= " ORDER BY Descricao ";

        try {
            $db = Zend_Db_Table::getDefaultAdapter();
            $db->setFetchMode(Zend_DB::FETCH_OBJ);
        }
        catch (Zend_Exception_Db $e) {
            $this->view->message = "Erro ao buscar Etapas: " . $e->getMessage();
        }
        //die($sql);
        return $db->fetchAll($sql);
    }

    public static function buscarEtapasCusto() {
        $sql = "SELECT
		idplanilhaetapa ,
		Descricao
		FROM SAC..tbPlanilhaEtapa where tpcusto = 'A'";

        $sql.= " ORDER BY Descricao ";

        try {
            $db = Zend_Db_Table::getDefaultAdapter();
            $db->setFetchMode(Zend_DB::FETCH_OBJ);
        }
        catch (Zend_Exception_Db $e) {
            $this->view->message = "Erro ao buscar Etapas: " . $e->getMessage();
        }

        //xd($sql);
        return $db->fetchAll($sql);
    }

    public function listarEtapasCusto()
    {
        $db = Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        $sql = $db->select()
            ->from(['tbplanilhaetapa' ], ['idplanilhaetapa','descricao' ], $this->getSchema('sac'))
            ->where("tpcusto = 'A'")
            ->order('descricao')
            ;

        //$sql = "SELECT
		//idplanilhaetapa ,
		//Descricao
		//FROM SAC..tbPlanilhaEtapa where tpcusto = 'A'";

        //$sql.= " ORDER BY Descricao ";

        try {
        }
        catch (Zend_Exception_Db $e) {
            $this->view->message = "Erro ao buscar Etapas: " . $e->getMessage();
        }

        return $db->fetchAll($sql);
    }

    public static function buscarItensProdutos($idPreProjeto, $idItem = null) {

        $sql = "
            SELECT
            DISTINCT
            p.Codigo as CodigoProduto,
            pp.idEtapa as idEtapa,
            pp.idPlanilhaItem as idItem,
            ti.Descricao as DescricaoItem,
            pp.UfDespesa as IdUf,
            uf.Descricao as DescricaoUf,
            Uf.Uf as SiglaUF,
            municipio.Descricao as Municipio,
            mec.Descricao as DescricaoMecanismo,
            pp.idPlanilhaProposta,
            un.Descricao as Unidade,
            pp.Quantidade,
            pp.Ocorrencia,
            pp.ValorUnitario,
            pp.QtdeDias,
            veri.idVerificacao as idFonteRecurso,
            veri.Descricao as DescricaoFonteRecurso
            FROM SAC.dbo.PreProjeto pre
                INNER JOIN SAC.dbo.tbPlanilhaProposta pp ON (pre.idPreProjeto = pp.idProjeto)
                INNER JOIN SAC.dbo.Produto P ON (pp.idProduto = p.Codigo)
                INNER JOIN SAC.dbo.tbPlanilhaItens ti ON ti.idPlanilhaItens = pp.idPlanilhaItem
                INNER JOIN SAC.dbo.Uf uf ON uf.CodUfIbge = pp.UfDespesa
                INNER JOIN AGENTES.dbo.Municipios municipio ON municipio.idMunicipioIBGE = pp.MunicipioDespesa
                INNER JOIN SAC.dbo.Mecanismo mec ON mec.Codigo = pre.Mecanismo
                INNER JOIN SAC.dbo.tbPlanilhaUnidade un ON un.idUnidade = pp.Unidade
                INNER JOIN SAC.dbo.Verificacao veri ON veri.idVerificacao = pp.FonteRecurso
            WHERE idPreProjeto = {$idPreProjeto} ";

        if($idItem) {
            $sql .= " and  pp.idPlanilhaItem = " . $idItem ;
        }

        $sql.= " ORDER BY ti.Descricao ";



        try {
            $db = Zend_Db_Table::getDefaultAdapter();
            $db->setFetchMode(Zend_DB::FETCH_OBJ);
        }
        catch (Zend_Exception_Db $e) {
            $this->view->message = "Erro ao buscar Etapas: " . $e->getMessage();
        }
        //die($sql);
        return $db->fetchAll($sql);
    }

    /**
     * listarItensProdutos
     *
     * @param mixed $idPreProjeto
     * @param bool $idItem
     * @access public
     * @return void
     */
    public function listarItensProdutos($idPreProjeto, $idItem = null)
    {
        $db = Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        $pp = [
            'pp.idetapa as idEtapa',
            'pp.idplanilhaitem as idItem',
            'pp.ufdespesa as IdUf',
            'pp.quantidade',
            'pp.ocorrencia',
            'pp.valorunitario',
            'pp.qtdedias',
            'pp.idplanilhaproposta',
        ];

        $sql = $db->select()->distinct()
            ->from(['pre' => 'preprojeto'], null, $this->getSchema('sac'))
            ->join(['pp' => 'tbplanilhaproposta' ],  '(pre.idPreProjeto = pp.idProjeto)', $pp, $this->getSchema('sac'))
            ->join(['p' => 'produto'], '(pp.idProduto = p.codigo)', ['p.codigo as CodigoProduto'], $this->getSchema('sac'))
            ->join(['ti' => 'tbplanilhaitens'] , 'ti.idplanilhaitens = pp.idplanilhaitem', ['ti.descricao as DescricaoItem'], $this->getSchema('sac'))
            ->join(['uf' => 'uf'],  'uf.codufibge = pp.ufdespesa', ['uf.descricao as DescricaoUf', 'uf.uf as SiglaUF'], $this->getSchema('sac'))
            ->join(['municipio' => 'municipios'], 'municipio.idmunicipioibge = pp.municipiodespesa', ['municipio.descricao as Municipio' ], $this->getSchema('agentes'))
            ->join(['mec' => 'mecanismo'], 'mec.codigo = pre.mecanismo', ['mec.descricao as DescricaoMecanismo' ], $this->getSchema('sac'))
            ->join(['un' => 'tbplanilhaunidade'],  'un.idunidade = pp.unidade', 'un.descricao as Unidade', $this->getSchema('sac'))
            ->join(['veri' => 'verificacao'],  'veri.idverificacao = pp.fonterecurso', ['veri.idverificacao as idFonteRecurso', 'veri.descricao as DescricaoFonteRecurso' ], $this->getSchema('sac'))
            ->where('idpreprojeto = ?', $idPreProjeto)
            ->order('ti.descricao')
            ;

        if($idItem) {
            $sql->where("pp.idPlanilhaItem = ?", $idItem);
        }

        return $db->fetchAll($sql);
    }

    public static function buscarItensPlanilhaOrcamentaria($idPreProjeto) {

        $sql = "  SELECT
                DISTINCT
                        p.Codigo as CodigoProduto,
                        p.Descricao,
                        pp.idEtapa as idEtapa,
                        e.Descricao as DescricaoEtapa,
                        pp.idPlanilhaItem as idItem,
                        ti.Descricao as DescricaoItem,
                        pp.UfDespesa as IdUf,
                        uf.Descricao as DescricaoUf,
                        Uf.Uf as SiglaUF,
                        municipio.Descricao as Municipio,
                        mec.Descricao as DescricaoMecanismo,
                        pp.idPlanilhaProposta,
                        un.Descricao as Unidade,
                        pp.Quantidade,
                        pp.Ocorrencia,
                        pp.ValorUnitario,
                        pp.QtdeDias,
                veri.idVerificacao as idFonteRecurso,
                veri.Descricao as DescricaoFonteRecurso
                FROM SAC.dbo.PreProjeto pre
                        INNER JOIN SAC.dbo.tbPlanilhaProposta pp ON (pre.idPreProjeto = pp.idProjeto)
                        INNER JOIN SAC.dbo.Produto P ON (pp.idProduto = p.Codigo)
                        INNER JOIN SAC.dbo.tbPlanilhaEtapa e on (e.idPlanilhaEtapa = pp.idEtapa)
                        INNER JOIN SAC.dbo.tbPlanilhaItens ti ON ti.idPlanilhaItens = pp.idPlanilhaItem
                        INNER JOIN SAC.dbo.Uf uf ON uf.CodUfIbge = pp.UfDespesa
                        INNER JOIN AGENTES.dbo.Municipios municipio ON municipio.idMunicipioIBGE = pp.MunicipioDespesa
                        INNER JOIN SAC.dbo.Mecanismo mec ON mec.Codigo = pre.Mecanismo
                        INNER JOIN SAC.dbo.tbPlanilhaUnidade un ON un.idUnidade = pp.Unidade
                        INNER JOIN SAC.dbo.Verificacao veri ON veri.idVerificacao = pp.FonteRecurso
                WHERE idPreProjeto = {$idPreProjeto}";

        $sql.= " ORDER BY ti.Descricao ";



        try {
            $db = Zend_Db_Table::getDefaultAdapter();
            $db->setFetchMode(Zend_DB::FETCH_OBJ);
        }
        catch (Zend_Exception_Db $e) {
            $this->view->message = "Erro ao buscar Etapas: " . $e->getMessage();
        }
        //die($sql);
        return $db->fetchAll($sql);
    }

    public static function excluirItemProdutos($idPreProjeto) {

        $sql = "SELECT
                    DISTINCT
                    p.Codigo as CodigoProduto,
                    pp.idEtapa as idEtapa,
                    pp.idPlanilhaItem as idItem,
                    ti.Descricao as DescricaoItem,
                    pp.UfDespesa as IdUf,
                    uf.Descricao as DescricaoUf,
                    mec.Descricao as DescricaoMecanismo
                    FROM SAC.dbo.PreProjeto pre
                            INNER JOIN SAC.dbo.tbPlanilhaProposta pp ON (pre.idPreProjeto = pp.idProjeto)
                            INNER JOIN SAC.dbo.Produto P ON (pp.idProduto = p.Codigo)
                            INNER JOIN SAC..tbPlanilhaItens ti ON ti.idPlanilhaItens = pp.idPlanilhaItem
                            INNER JOIN SAC..Uf uf ON uf.CodUfIbge = pp.UfDespesa
                            INNER JOIN SAC..Mecanismo mec ON mec.Codigo = pre.Mecanismo
                    WHERE idPreProjeto = {$idPreProjeto}";

        $sql.= " ORDER BY ti.Descricao ";

        try {
            $db = Zend_Db_Table::getDefaultAdapter();
            $db->setFetchMode(Zend_DB::FETCH_OBJ);
        }
        catch (Zend_Exception_Db $e) {
            $this->view->message = "Erro ao buscar Etapas: " . $e->getMessage();
        }
        //die($sql);
        return $db->fetchAll($sql);
    }

    public static function buscarItens($idEtapa, $idproduto = null) {
    /*    $sql = "select distinct
					pp.idPlanilhaItem as idPlanilhaItens,
					right(i.Descricao,40) as Descricao
				from SAC.dbo.tbPlanilhaProposta pp
					inner join SAC.dbo.tbPlanilhaItens as i on pp.idPlanilhaItem = i.idPlanilhaItens
					inner join SAC.dbo.tbPlanilhaEtapa as e on pp.idEtapa = e.idPlanilhaEtapa
				where idEtapa = $idEtapa order by i.Descricao "; */

       $sql = "select distinct a.idPlanilhaItens,b.Descricao
               FROM SAC..tbItensPlanilhaProduto a
               INNER JOIN SAC..tbPlanilhaItens b on (a.idPlanilhaItens = b.idPlanilhaItens)
               WHERE idPlanilhaEtapa = ".$idEtapa." ";

       if ( !empty( $idproduto ) ) {
            $sql .= " and idProduto = ".$idproduto." ";
       }
       $sql .= " ORDER BY b.Descricao ";

//        if ( !empty( $idproduto ) ) {
//            $sql .= " and tbipp.idProduto = $idproduto";
//        }

        //$sql .= " order by 2 asc";
		//xd($sql);
        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);
        return $db->fetchAll($sql);
    }

    public static function buscarFonteRecurso() {
        $sql = "select Verificacao.idVerificacao, ltrim(Verificacao.Descricao) as VerificacaoDescricao
                from SAC.dbo.Verificacao as Verificacao
                inner join SAC.dbo.Tipo as Tipo
                on Verificacao.idTipo = Tipo.idTipo
                where Tipo.idTipo = 5";
        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        return $db->fetchAll($sql);
    }

    public static function buscarFontePlanilha($idPreProjeto) {
        $sql = "SELECT
                    DISTINCT
                        veri.idVerificacao as idFonteRecurso,
                    veri.Descricao as DescricaoFonteRecurso
                FROM SAC.dbo.PreProjeto pre
                left JOIN SAC.dbo.tbPlanilhaProposta pp ON (pre.idPreProjeto = pp.idProjeto)
                left JOIN SAC.dbo.PlanoDistribuicaoProduto pd ON (pre.idPreProjeto = pd.idProjeto AND pd.stPlanoDistribuicaoProduto = 1)
                    left JOIN SAC.dbo.Produto p ON (pd.idProduto = p.Codigo)
                left JOIN SAC.dbo.Verificacao veri ON veri.idVerificacao = pp.FonteRecurso
                WHERE idPreProjeto = $idPreProjeto and pd.idProduto != 0 and pp.idProduto != 0 and p.Codigo != 0";

        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        return $db->fetchAll($sql);
    }

    public static function buscarUnidade() {
        $sql = "select idUnidade, Sigla, Descricao
        FROM SAC.dbo.tbPlanilhaUnidade order by 3";
        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        return $db->fetchAll($sql);
    }

    public static function buscarProdutos($idPreProjeto) {

        $sql = "SELECT p.Codigo as CodigoProduto,
                    p.Descricao as DescricaoProduto,
                    pre.idPreProjeto as PreProjeto,
                    pre.idPreProjeto as idProposta
                    FROM SAC.dbo.PreProjeto pre
                    INNER JOIN SAC.dbo.PlanoDistribuicaoProduto pd ON (pre.idPreProjeto = pd.idProjeto AND pd.stPlanoDistribuicaoProduto = 1)
                    INNER JOIN SAC.dbo.Produto p ON (pd.idProduto = p.Codigo)
                   where idPreProjeto = {$idPreProjeto} group by p.Codigo, p.Descricao, idPreProjeto ";

        /*$sql = "SELECT p.Codigo as CodigoProduto,
                    p.Descricao as DescricaoProduto,
                    pre.idPreProjeto as PreProjeto,
                    pre.idPreProjeto as idProposta
                    FROM SAC.dbo.PreProjeto pre
                    INNER JOIN SAC.dbo.PlanoDistribuicaoProduto pd ON (pre.idPreProjeto = pd.idProjeto AND pd.stPlanoDistribuicaoProduto = 1)
                    INNER JOIN SAC.dbo.Produto p ON (pd.idProduto = p.Codigo)
                       where idPreProjeto = {$idPreProjeto} group by p.Codigo, p.Descricao, idPreProjeto";*/

        //xd($sql);
        $sql.= " ORDER BY p.Descricao ";

        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        return $db->fetchAll($sql);
    }

    public function listarProdutos($idPreProjeto)
    {
        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        $sql = $db->select()
            ->from(['pre' => 'preprojeto'], ['pre.idpreprojeto as PreProjeto', 'pre.idpreprojeto as idProposta'], $this->getSchema('sac'))
            ->join(['pd' => 'planodistribuicaoproduto'], '(pre.idpreprojeto = pd.idProjeto AND pd.stplanodistribuicaoproduto = 1)', null, $this->getSchema('sac'))
            ->join(['p' => 'produto'], '(pd.idproduto = p.codigo)', ['p.codigo as CodigoProduto', 'p.descricao as DescricaoProduto'],  $this->getSchema('sac'))
            ->where('idpreprojeto = ?', $idPreProjeto)
            ->group(['p.codigo', 'p.descricao', 'idpreprojeto'])
            ;

        return $db->fetchAll($sql);
    }

    public static function buscarProdutosPlanilha($idPreProjeto) {

        $sql = "SELECT
                    p.Codigo as CodigoProduto,
                    p.Descricao as DescricaoProduto,
                    pre.idPreProjeto as PreProjeto,
                    pre.idPreProjeto as idProposta,
                    pp.FonteRecurso
                FROM SAC.dbo.PreProjeto pre
                INNER JOIN SAC.dbo.tbPlanilhaProposta pp ON (pre.idPreProjeto = pp.idProjeto)
                INNER JOIN SAC.dbo.Produto p ON (pp.idProduto = p.Codigo)
                WHERE idPreProjeto = {$idPreProjeto}
                GROUP BY p.Codigo, p.Descricao, idPreProjeto, p.Codigo, pp.FonteRecurso ";

        //xd($sql);
        $sql.= " ORDER BY p.Descricao ";

        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        return $db->fetchAll($sql);
    }

    public static function buscarCustos($idPreProjeto,$tipoCusto, $idEtapa = null, $idItem = null, $idUf = null, $idMunicipio = null,
    									$fonte = null, $unidade = null, $quantidade = null, $ocorrencia = null, $vlunitario = null, $qtdDias = null, $dsJustificativa = null) {
        $sql = "select  tpp.idUsuario,
                        tpp.idProjeto as idProposta,
                        tpe.tpCusto as custo,
                        tpe.Descricao as etapa,
                        tpe.idPlanilhaEtapa as idEtapa,
                        tpi.Descricao as item,
                        tpi.idPlanilhaItens as idItem,
                        uf.Descricao as uf,
                        tpe.tpCusto,
                        mec.Descricao as mecanismo,
                        un.idUnidade as idUnidade,
                        un.Descricao as Unidade,
                        tpp.Quantidade,
                        tpp.Ocorrencia,
                        tpp.ValorUnitario,
                        tpp.QtdeDias,
                        veri.idVerificacao as idFonteRecurso,
                        veri.Descricao as DescricaoFonteRecurso,
                        tpp.dsJustificativa as Justificativa,
                        tpp.idPlanilhaProposta
                    FROM SAC..tbPlanilhaProposta tpp
                            left JOIN SAC..Produto pd on pd.Codigo = tpp.idProduto
                            INNER JOIN SAC..tbPlanilhaEtapa tpe on tpe.idPlanilhaEtapa = tpp.idEtapa
                            INNER JOIN SAC..tbPlanilhaItens tpi on tpi.idPlanilhaItens = tpp.idPlanilhaItem
                            INNER JOIN AGENTES..UF uf on uf.idUF = tpp.UfDespesa
                            INNER JOIN SAC..PreProjeto prep on prep.idPreProjeto = tpp.idProjeto
                            INNER JOIN SAC..Mecanismo mec on mec.Codigo = prep.Mecanismo
                            INNER JOIN SAC.dbo.tbPlanilhaUnidade un ON un.idUnidade = tpp.Unidade
                            INNER JOIN SAC.dbo.Verificacao veri ON veri.idVerificacao = tpp.FonteRecurso
                    WHERE tpe.tpCusto = '$tipoCusto' AND tpp.idProjeto = $idPreProjeto ";

        if($idEtapa){
        	$sql .= " AND tpe.idPlanilhaEtapa = $idEtapa";
        }
    	if($idItem){
        	$sql .= " AND tpi.idPlanilhaItens = $idItem";
        }

        if($idUf){
        	$sql .= " AND tpp.UfDespesa = $idUf";
        }

    	if($idMunicipio){
        	$sql .= " AND tpp.MunicipioDespesa = $idMunicipio";
        }

    	if($fonte){
        	$sql .= " AND veri.idVerificacao = $fonte";
        }

    	if($unidade){
        	$sql .= " AND un.idUnidade = $unidade";
        }

    	if($quantidade){
        	$sql .= " AND tpp.Quantidade = $quantidade";
        }

    	if($ocorrencia){
        	$sql .= " AND tpp.Ocorrencia = $ocorrencia";
        }

    	if($vlunitario){
        	$sql .= " AND tpp.ValorUnitario = $vlunitario";
        }

    	if($qtdDias){
        	$sql .= " AND tpp.QtdeDias = $qtdDias";
        }

        $sql.= " ORDER BY tpe.Descricao ";

        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        return $db->fetchAll($sql);
    }

    public static function buscarItensCustosAdministrativos($idPreProjeto,$tipoCusto) {
        $sql = "select
                tpp.idUsuario,
                tpp.idProjeto as idProposta,
                tpe.tpCusto as custo,
                tpe.Descricao as etapa,
                tpe.idPlanilhaEtapa as idEtapa,
                tpi.Descricao as DescricaoItem,
                tpi.idPlanilhaItens as idItem,
                uf.Descricao as DescricaoUf,
                municipio.Descricao as Municipio,
                uf.Sigla as SiglaUF,
                un.Descricao as Unidade,
                tpp.Quantidade,
                tpp.Ocorrencia,
                tpp.ValorUnitario,
                tpp.QtdeDias,
                tpe.tpCusto,
                mec.Descricao as mecanismo,
                tpp.idPlanilhaProposta,
                veri.idVerificacao as idFonteRecurso,
                veri.Descricao as DescricaoFonteRecurso
                FROM SAC..tbPlanilhaProposta tpp
                left JOIN SAC..Produto pd on pd.Codigo = tpp.idProduto
                INNER JOIN SAC..tbPlanilhaEtapa tpe on tpe.idPlanilhaEtapa = tpp.idEtapa
                INNER JOIN SAC..tbPlanilhaItens tpi on tpi.idPlanilhaItens = tpp.idPlanilhaItem
                INNER JOIN AGENTES..UF uf on uf.idUF = tpp.UfDespesa
                INNER JOIN AGENTES.dbo.Municipios municipio ON municipio.idMunicipioIBGE = tpp.MunicipioDespesa
                INNER JOIN SAC..PreProjeto prep on prep.idPreProjeto = tpp.idProjeto
                INNER JOIN SAC..Mecanismo mec on mec.Codigo = prep.Mecanismo
                INNER JOIN SAC.dbo.tbPlanilhaUnidade un ON un.idUnidade = tpp.Unidade
                INNER JOIN SAC.dbo.Verificacao veri ON veri.idVerificacao = tpp.FonteRecurso
                WHERE tpe.tpCusto = '$tipoCusto' AND tpp.idProjeto = {$idPreProjeto} ";

        $sql.= " ORDER BY tpe.Descricao ";

        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        return $db->fetchAll($sql);
    }

    /**
     * listarItensCustosAdministrativos
     *
     * @param mixed $idPreProjeto
     * @param mixed $tipoCusto
     * @access public
     * @return void
     */
    public function listarItensCustosAdministrativos($idPreProjeto, $tipoCusto)
    {
        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        $tpp = [
            'tpp.idusuario',
            'tpp.idprojeto as idProposta',
            'tpp.idplanilhaproposta',
            'tpp.quantidade',
            'tpp.ocorrencia',
            'tpp.valorunitario',
            'tpp.qtdedias',
        ];

        $tpe =[
            'tpe.tpcusto as custo',
            'tpe.descricao as etapa',
            'tpe.idplanilhaetapa as idEtapa',
            'tpe.tpcusto',
        ];

        $tpi = [
            'tpi.descricao as DescricaoItem',
            'tpi.idplanilhaitens as idItem',
        ];

        $uf =[
            'uf.descricao as DescricaoUf',
            'uf.sigla as SiglaUF',
        ];

        $veri =[
            'veri.idverificacao as idFonteRecurso',
            'veri.descricao as DescricaoFonteRecurso'
        ];

        $sql = $db->select()
            ->from(['tpp' => 'tbplanilhaproposta'], $tpp, $this->getSchema('sac'))
            ->joinLeft(['pd' => 'produto'], 'pd.codigo = tpp.idproduto', null, $this->getSchema('sac'))
            ->join(['tpe' => 'tbplanilhaetapa'], 'tpe.idplanilhaetapa = tpp.idetapa', $tpe, $this->getSchema('sac'))
            ->join(['tpi' => 'tbplanilhaitens'], 'tpi.idplanilhaitens = tpp.idplanilhaitem', $tpi, $this->getSchema('sac'))
            ->join(['uf' => 'uf'], 'uf.iduf = tpp.ufdespesa', $uf, $this->getSchema('agentes'))
            ->join(['municipio' => 'municipios'], 'municipio.idmunicipioibge = tpp.municipiodespesa','municipio.descricao as Municipio', $this->getSchema('agentes'))
            ->join(['prep' => 'preprojeto'], 'prep.idpreprojeto = tpp.idprojeto', null, $this->getSchema('sac'))
            ->join(['mec' => 'mecanismo'], 'mec.codigo = prep.mecanismo', 'mec.descricao as mecanismo', $this->getSchema('sac'))
            ->join(['un' => 'tbplanilhaunidade'], 'un.idunidade = tpp.unidade', 'un.descricao as Unidade', $this->getSchema('sac'))
            ->join(['veri' => 'verificacao'], 'veri.idverificacao = tpp.fonterecurso', $veri, $this->getSchema('sac'))
            ->where('tpe.tpcusto = ?', $tipoCusto)
            ->where('tpp.idprojeto = ?', $idPreProjeto)
            ->order('tpe.descricao')
            ;

        return $db->fetchAll($sql);
    }

    public static function buscarCustosAdministrativos()
    {
        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        $sql = $db->select()
            ->from('tbplanilhaetapa', ['idplanilhaetapa as idEtapa', 'descricao as DescricaoEtapa'], ('sac'))
            ->where("tpCusto = 'A' AND idPlanilhaEtapa <> 6")
            ;

        //$sql = "SELECT idPlanilhaEtapa as idEtapa, Descricao as DescricaoEtapa
            //FROM SAC.dbo.tbPlanilhaEtapa WHERE tpCusto = 'A' AND idPlanilhaEtapa <> 6";

        return $db->fetchAll($sql);
    }

    /**
     * listarCustosAdministrativos
     *
     * @access public
     * @return void
     * @author wouerner <wouerner@gmail.com>
     */
    public function listarCustosAdministrativos()
    {
        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        $sql = $db->select()
            ->from('tbplanilhaetapa', ['idplanilhaetapa as idEtapa', 'descricao as DescricaoEtapa'], $this->getSchema('sac'))
            ->where("tpCusto = 'A' AND idPlanilhaEtapa <> 6")
            ;

        return $db->fetchAll($sql);
    }

    public static function salvarNovoProduto($idProposta, $idProduto, $idEtapa, $idItem, $unidade, $quantidade, $ocorrencia, $vlunitario, $qtdDias, $fonte, $idUf, $idMunicipio, $justificativa,$idUsuario) {

        try {
            $dados = array(
                    'idProjeto'=>$idProposta,
                    'idProduto'=>$idProduto,
                    'idEtapa'=>$idEtapa,
                    'idPlanilhaItem'=>$idItem,
                    'Descricao'=>'',
                    'Unidade'=>$unidade,
                    'Quantidade'=>$quantidade,
                    'Ocorrencia'=>$ocorrencia,
                    'ValorUnitario'=>$vlunitario,
                    'QtdeDias'=>$qtdDias,
                    'TipoDespesa'=>0,
                    'TipoPessoa'=>0,
                    'Contrapartida'=>0,
                    'FonteRecurso'=>$fonte,
                    'UfDespesa'=>$idUf,
                    'MunicipioDespesa'=>$idMunicipio,
                    'dsJustificativa'=> substr($justificativa,0,450),
                    'idUsuario'=>$idUsuario
            );
            $db= Zend_Db_Table::getDefaultAdapter();
            //xd($dados);
            $db->insert('SAC.dbo.tbPlanilhaProposta', $dados);
            return $db->lastInsertId();
        }
        catch (Exception $e) {
            die("ERRO" . $e->getMessage());
        }

    }

    public static function salvarNovoCusto($idProposta, $idEtapa, $idItem, $unidade, $quantidade, $ocorrencia, $vlunitario, $qtdDias, $fonte, $idUf, $idMunicipio, $dsJustificativa ) {



    }

    public static function updateProdutos($dados, $where) {
        $sql = "UPDATE
                SAC..tbPlanilhaProposta pp
                SET ($idProposta, $idProduto, $idEtapa, $idItem, $unidade, $quantidade, $ocorrencia, $vlunitario, $qtdDias, $fonte, $idUf, $idMunicipio, $dsJustificativa, 462)
                        WHERE (pp.idProjeto = $idProposta and pp.idProduto = $idProduto and pp.idUsuario = 462) ";

        $db= Zend_Db_Table::getDefaultAdapter();
        $db->update('SAC.dbo.tbPlanilhaProposta',$dados, $where);


    }

    public static function editarPlanilhaProdutos($dados, $where) {
        try {
            $db= Zend_Db_Table::getDefaultAdapter();
            $db->setFetchMode(Zend_DB::FETCH_OBJ);
            $db->update('SAC.dbo.tbPlanilhaProposta', $dados, $where);
        }
        catch(Zend_Exception $e) {
            die("Erro:".$e->getMessage());
        }
    }

    public static function buscarUltimosDadosCadastrados() {
        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_ASSOC);

        $sql = "
			select
				top 1 *
			from
				SAC.dbo.tbPlanilhaProposta
			order by
				idPlanilhaProposta
			desc
        ";

        return $db->fetchAll($sql);
    }

    public static function excluirItensProdutos($idPlanilhaProposta) {

        $db= Zend_Db_Table::getDefaultAdapter();
        return $db->delete('SAC.dbo.tbPlanilhaProposta','idPlanilhaProposta =' .$idPlanilhaProposta);
    }

}

