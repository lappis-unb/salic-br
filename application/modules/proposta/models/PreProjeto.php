<?php

/**
 * PreProjeto
 *
 * @uses   Zend_Db_Table
 * @author wouerner <wouerner@gmail.com>
 */
class Proposta_Model_PreProjeto extends MinC_Db_Table_Abstract
{
    protected $_schema= "sac";
    protected $_name = "preprojeto";
    protected $_primary = "idpreprojeto";
    protected $_banco = "sac";

    public $_totalRegistros = null;

    /**
     * __construct
     *
     * @access public
     * @return void
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * retirarProjetos
     *
     * @param mixed $idUsuario
     * @param mixed $idUsuarioR
     * @param mixed $idAgente
     * @static
     * @access public
     * @return void
     */
    public static function retirarProjetos($idUsuario, $idUsuarioR, $idAgente)
    {
        $db = Zend_Db_Table::getDefaultAdapter();

        $where['idUsuario = ?'] = $idUsuarioR;
        $where['idAgente = ?'] = $idAgente;

        return $db->update('SAC.dbo.PreProjeto', ['idUsuario' => $idUsuario], $where);
    }

    /**
     * retirarProjetosVinculos
     *
     * @param mixed $siVinculoProposta
     * @param mixed $idVinculo
     * @static
     * @access public
     * @return void
     */
    public static function retirarProjetosVinculos($siVinculoProposta, $idVinculo)
    {
        $db = Zend_Db_Table::getDefaultAdapter();

        $where['idVinculo = ? '] = $idVinculo;

        return $db->update('Agentes.dbo.tbVinculoProposta', ['siVinculoProposta' => $siVinculoProposta], $where);
    }

    /**
     * listaProjetos
     *
     * @param mixed $idUsuario
     * @static
     * @access public
     * @return void
     */
    public static function listaProjetos($idUsuario)
    {
        $db = Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        $subSql = $db->select()
            ->from(['pr' => 'projetos'], ['*'], 'SAC.dbo')
            ->where('pr.idProjeto = p.idPreProjeto');

        $p = [
            'p.idPreProjeto',
            'idagente',
            'NomeProjeto',
            'Mecanismo',
            'stTipoDemanda'
        ];

        $sql = $db->select()
            ->from(['p' => 'PreProjeto'], $p, 'SAC.dbo')
            ->where('stEstado = 1')
            ->where("stTipoDemanda like 'NA'")
            ->where('idUsuario = ?', $idUsuario)
            ->where(new Zend_Db_Expr("not exists ($subSql)"))
            ;

        return $db->fetchAll($sql);
    }

    /**
     * Retorna registros do banco de dados referente a Agentes(Proponente)
     * @param aray $where - array com dados where no formato "nome_coluna_1"=>"valor_1","nome_coluna_2"=>"valor_2"
     * @param array $order - array com orders no formado "coluna_1 desc","coluna_2"...
     * @param int $tamanho - numero de registros que deve retornar
     * @param int $inicio - offset
     * @return Zend_Db_Table_Rowset_Abstract
     */
    public function buscar($where=array(), $order=array(), $tamanho=-1, $inicio=-1)
    {
        $slct = $this->select();
        $slct->setIntegrityCheck(false);

//        "DtInicioDeExecucaoForm"=>"CONVERT(CHAR(10),DtInicioDeExecucao,103)",
//                                  "DtFinalDeExecucaoForm"=>"CONVERT(CHAR(10),DtFinalDeExecucao,103)",
//                                  "DtAtoTombamentoForm"=>"CONVERT(CHAR(10),DtAtoTombamento,103)",
//                                  "dtAceiteForm"=>"CONVERT(CHAR(10),dtAceite,103)",
//                                  "DtArquivamentoForm"=>"CONVERT(CHAR(10),DtArquivamento,103)",
//                                  "CAST(ResumoDoProjeto as TEXT) as ResumoDoProjeto",
//                                  "CAST(Objetivos as TEXT) as Objetivos",
//                                  "CAST(Justificativa as TEXT) as Justificativa",
//                                  "CAST(Acessibilidade as TEXT) as Acessibilidade",
//                                  "CAST(DemocratizacaoDeAcesso as TEXT) as DemocratizacaoDeAcesso",
//                                  "CAST(EtapaDeTrabalho as TEXT) as EtapaDeTrabalho",
//                                  "CAST(FichaTecnica as TEXT) as FichaTecnica",
//                                  "CAST(Sinopse as TEXT) as Sinopse",
//                                  "CAST(ImpactoAmbiental as TEXT) as ImpactoAmbiental",
//                                  "CAST(EspecificacaoTecnica as TEXT) as EspecificacaoTecnica",
//                                  "CAST(EstrategiadeExecucao as TEXT) as EstrategiadeExecucao"
        $slct->from($this, array("*",
                                  "dtiniciodeexecucaoform"=> $this->getExpressionToChar("dtiniciodeexecucao"),
                                  "dtfinaldeexecucaoform"=> $this->getExpressionToChar("dtfinaldeexecucao"),
                                  "dtatotombamentoform"=> $this->getExpressionToChar("dtatotombamento"),
                                  "dtaceiteform"=> $this->getExpressionToChar("dtaceite"),
                                  "dtarquivamentoform"=> $this->getExpressionToChar("dtarquivamento"),
                                ));

        //adiciona quantos filtros foram enviados
        foreach ($where as $coluna=>$valor)
        {
            $slct->where($coluna, $valor);
        }

        //adicionando linha order ao select
        $slct->order($order);

        // paginacao
        if ($tamanho > -1)
        {
                $tmpInicio = 0;
                if ($inicio > -1)
                {
                        $tmpInicio = $inicio;
                }
                $slct->limit($tamanho, $tmpInicio);
        }
        return $this->fetchAll($slct);
    }

    /**
     * Retorna registros do banco de dados referente a Agentes(Proponente)
     * @param array $where - array com dados where no formato "nome_coluna_1"=>"valor_1","nome_coluna_2"=>"valor_2"
     * @param array $order - array com orders no formado "coluna_1 desc","coluna_2"...
     * @param int $tamanho - numero de registros que deve retornar
     * @param int $inicio - offset
     * @return Zend_Db_Table_Rowset_Abstract
     */
    public function buscaCompleta($where=array(), $order=array(), $tamanho=-1, $inicio=-1)
    {

        $slct = $this->select();
        $slct->setIntegrityCheck(false);
        $slct->from(array('a' => $this->_name),
                            array("a.*",
                                  "a.ResumoDoProjeto"=>"CAST(a.ResumoDoProjeto AS TEXT) as ResumoDoProjeto",
                                  "a.Objetivos"=>"CAST(a.Objetivos AS TEXT) as Objetivos",
                                  "a.Justificativa"=>"CAST(a.Justificativa AS TEXT) as Justificativa",
                                  "a.Acessibilidade"=>"CAST(a.Acessibilidade AS TEXT) as Acessibilidade",
                                  "a.DemocratizacaoDeAcesso"=>"CAST(a.DemocratizacaoDeAcesso AS TEXT) as DemocratizacaoDeAcesso",
                                  "a.EtapaDeTrabalho"=>"CAST(a.EtapaDeTrabalho AS TEXT) as EtapaDeTrabalho",
                                  "a.FichaTecnica"=>"CAST(a.FichaTecnica AS TEXT) as FichaTecnica",
                                  "a.Sinopse"=>"CAST(a.Sinopse AS TEXT) as Sinopse",
                                  "a.ImpactoAmbiental"=>"CAST(a.ImpactoAmbiental AS TEXT) as ImpactoAmbiental",
                                  "a.EspecificacaoTecnica"=>"CAST(a.EspecificacaoTecnica AS TEXT) as EspecificacaoTecnica",
                                  "a.EstrategiadeExecucao"=>"CAST(a.EstrategiadeExecucao AS TEXT) as EstrategiadeExecucao",
                                  "a.DtInicioDeExecucaoForm"=>$this->getExpressionToChar(DtInicioDeExecucao),
//                                  "a.DtFinalDeExecucaoForm"=>"parent::getExpressionToChar(a.DtFinalDeExecucao)",
//                                  "a.DtAtoTombamentoForm"=>"parent::getExpressionToChar(a.DtAtoTombamento)",
//                                  "a.dtAceiteForm"=>"parent::getExpressionToChar(a.dtAceite)",
//                                  "a.DtArquivamentoForm"=> "parent::getExpressionToChar(a.DtArquivamento)"
                              ),
                          $this->_schema);

        $slct->joinInner(array('ag' => 'Agentes'),
                         'a.idAgente = ag.idAgente',
                         array("ag.CNPJCPF as CNPJCPF"),
                         $this->getSchema('agentes'));

        $slct->joinInner(array('m' => 'Nomes'),
                         'a.idAgente = m.idAgente',
                         array("m.Descricao as NomeAgente"),
                         $this->getSchema('agentes'));

        //adiciona quantos filtros foram enviados
        foreach ($where as $coluna=>$valor)
        {
            $slct->where($coluna, $valor);
        }
        $slct->where(new Zend_Db_Expr("NOT EXISTS(select 1 from sac.dbo.Projetos pr where a.idPreProjeto = pr.idProjeto)"));

        $slct->order($order);

        // paginacao
        if ($tamanho > -1)
        {
                $tmpInicio = 0;
                if ($inicio > -1)
                {
                        $tmpInicio = $inicio;
                }
                $slct->limit($tamanho, $tmpInicio);
        }
        echo '<pre>';
        echo($slct->assemble());
        exit;
        return $this->fetchAll($slct);
    }

    /**
     * Grava registro. Se seja passado um ID ele altera um registro existente
     * @param array $dados - array com dados referentes as colunas da tabela no formato "nome_coluna_1"=>"valor_1","nome_coluna_2"=>"valor_2"
     * @return ID do registro inserido/alterado ou FALSE em caso de erro
     */
    public function salvar($dados)
    {
        //DECIDINDO SE INCLUI OU ALTERA UM REGISTRO
        if(!empty ($dados['idpreprojeto'])){
            //UPDATE
            $rsPreProjeto = $this->find($dados['idpreprojeto'])->current();
        } else {
            //INSERT
            unset($dados['idpreprojeto']);
            return $this->insert($dados);
        }
        //ATRIBUINDO VALORES AOS CAMPOS QUE FORAM PASSADOS
       $rsPreProjeto->idagente               = $dados["idagente"];
       $rsPreProjeto->nomeprojeto            = $dados["nomeprojeto"];
       $rsPreProjeto->mecanismo              = $dados["mecanismo"];
       $rsPreProjeto->agenciabancaria        = $dados["agenciabancaria"];
       $rsPreProjeto->areaabrangencia        = $dados["areaabrangencia"];
       $rsPreProjeto->dtiniciodeexecucao     = $dados["dtiniciodeexecucao"];
       $rsPreProjeto->dtfinaldeexecucao      = $dados["dtfinaldeexecucao"];
       $rsPreProjeto->nratotombamento        = $dados["nratotombamento"];
       $rsPreProjeto->dtatotombamento        = $dados["dtatotombamento"];
       $rsPreProjeto->esferatombamento       = $dados["esferatombamento"];
       $rsPreProjeto->resumodoprojeto        = $dados["resumodoprojeto"];
       $rsPreProjeto->objetivos              = $dados["objetivos"];
       $rsPreProjeto->justificativa          = $dados["justificativa"];
       $rsPreProjeto->acessibilidade         = $dados["acessibilidade"];
       $rsPreProjeto->democratizacaodeacesso = $dados["democratizacaodeacesso"];
       $rsPreProjeto->etapadetrabalho        = $dados["etapadetrabalho"];
       $rsPreProjeto->fichatecnica           = $dados["fichatecnica"];
       $rsPreProjeto->sinopse                = $dados["sinopse"];
       $rsPreProjeto->impactoambiental       = $dados["impactoambiental"];
       $rsPreProjeto->especificacaotecnica   = $dados["especificacaotecnica"];
       $rsPreProjeto->estrategiadeexecucao   = $dados["estrategiadeexecucao"];
       $rsPreProjeto->dtaceite               = $dados["dtaceite"];
       $rsPreProjeto->dtarquivamento         = (isset($dados["dtarquivamento"])) ? $dados["dtarquivamento"] : null;
       $rsPreProjeto->stestado               = $dados["stestado"];
       $rsPreProjeto->stdatafixa             = $dados["stdatafixa"];
       $rsPreProjeto->stplanoanual           = $dados["stplanoanual"];
       $rsPreProjeto->idusuario              = $dados["idusuario"];
       $rsPreProjeto->sttipodemanda          = $dados["sttipodemanda"];
       $rsPreProjeto->idedital               = (isset($dados["idedital"])) ? $dados["idedital"] : null;

       //SALVANDO O OBJETO
       $id = $rsPreProjeto->save();

       if($id){
            return $id;
       }else{
            return false;
       }
    }

    /**
     * consultaTodosProjetos
     *
     * @param mixed $idAgente
     * @param mixed $idResponsavel
     * @param mixed $arrBusca
     * @static
     * @access public
     * @return void
     */
    public static function consultaTodosProjetos($idAgente, $idResponsavel, $arrBusca)
    {
        $db = Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        $a = [
            new Zend_Db_Expr('0 as Ordem'),
            'a.idPreProjeto',
            'a.NomeProjeto',
            'a.idUsuario',
            'a.idAgente'
        ];

        $subSql = $db->select()
            ->from(['pr' => 'projetos'], new Zend_Db_Expr('1'), 'sac.dbo')
            ->where('a.idpreprojeto = pr.idprojeto')
            ;

        $sql = $db->select()
            ->from(['a' => 'preprojeto'], $a, 'SAC.dbo')
            ->join(['ag' => 'agentes'], 'a.idagente = ag.idagente', 'ag.cnpjcpf AS CNPJCPF','AGENTES.dbo')
            ->join(['m' => 'nomes'], 'a.idagente = m.idagente', 'm.descricao AS NomeAgente','AGENTES.dbo')
            ->where('a.idAgente = ?', $idAgente)
            ->where(new Zend_Db_Expr("NOT EXISTS($subSql)"))
            ;

        foreach ($arrBusca as $value) {
            $sql->where($value);
        }

        $aSql = [
            new Zend_Db_Expr('1 as Ordem'),
            'a.idPreProjeto',
            'a.NomeProjeto',
            'a.idUsuario',
            'a.idAgente'
        ];

        $sql2 = $db->select()
            ->from(['a' => 'preprojeto'], $aSql, 'sac.dbo')
            ->join(['ag' => 'agentes'], '(a.idagente = ag.idagente)', 'ag.cnpjcpf AS CNPJCPF', 'AGENTES.dbo')
            ->join(['m' => 'nomes'], '(a.idagente = m.idagente)', 'm.descricao AS NomeAgente', 'AGENTES.dbo')
            ->join(['s' => 'SGCacesso'], 'a.idUsuario = s.IdUsuario', null, 'ControleDeAcesso.dbo')
            ->where('a.idusuario = ?', $idResponsavel)
            ->where('ag.CNPJCPF <> s.Cpf')
            ->where(new Zend_Db_Expr('NOT EXISTS(SELECT 1 FROM sac.dbo.projetos pr WHERE  a.idpreprojeto = pr.idprojeto)'))
            ;

        foreach ($arrBusca as $value) {
            $sql2->where($value);
        }

        $sql = $db->select()->union(array($sql, $sql2), Zend_Db_Select::SQL_UNION_ALL)
            ->order(new Zend_Db_Expr('1'))
            ->order('m.Descricao ASC')
            ;

        return $db->fetchAll($sql);
    }

    /**
     * consultaprojetos
     *
     * @param mixed $idagente
     * @static
     * @access public
     * @return void
     */
    public static function consultaprojetos($idagente)
    {
        $db = Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        $sql = $db->select()->from('PreProjeto',['idPreProjeto', 'idagente', 'NomeProjeto', 'Mecanismo'],'SAC.dbo')
            ->where('idagente = ?',$idagente)
            ->order('nomeprojeto');

        return $db->fetchAll($sql);
    }

    /**
     * inserirProposta
     *
     * @param mixed $dados
     * @static
     * @access public
     * @return void
     */
    public static function inserirProposta($dados)
    {
        $db = Zend_Db_Table::getDefaultAdapter();
        $cadastrar = $db->insert("SAC.dbo.PreProjeto", $dados);

        if ($cadastrar)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * alterarDados
     *
     * @param mixed $dados
     * @param mixed $where
     * @static
     * @access public
     * @return void
     */
    public static function alterarDados($dados, $where)
    {
        $db = Zend_Db_Table::getDefaultAdapter();
        $cadastrar = $db->update("SAC.dbo.PreProjeto", $dados, $where);

        if ($cadastrar)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * listaUF
     *
     * @static
     * @access public
     * @return void
     */
    public static function listaUF()
    {
        $db = Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        $sql = $db->select()->from('UF',['*'],'AGENTES.dbo')->order('Sigla');

        return $db->fetchAll($sql);
    }

    /**
     * buscaIdAgente
     *
     * @param mixed $CNPJCPF
     * @static
     * @access public
     * @return void
     */
    public static function buscaIdAgente($CNPJCPF) {
        $db = Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        $sql = $db->select()->from('Agentes',['*'],'Agentes.dbo')->where('CNPJCPF = ?', $CNPJCPF);

        return $db->fetchAll($sql);
    }

    /**
     * inserirAgentes
     *
     * @param mixed $dadosAgentes
     * @static
     * @access public
     * @return void
     */
    public static function inserirAgentes($dadosAgentes) {
        $db = Zend_Db_Table::getDefaultAdapter();
        $Agentes = $db->insert("Agentes.dbo.Agentes", $dadosAgentes);
    }

    /**
     * inserirNomes
     *
     * @param mixed $dadosNomes
     * @static
     * @access public
     * @return void
     */
    public static function inserirNomes($dadosNomes) {
        $db = Zend_Db_Table::getDefaultAdapter();
        $Nomes = $db->insert("Agentes.dbo.Nomes", $dadosNomes);
    }

    /**
     * inserirEnderecoNacional
     *
     * @param mixed $dadosEnderecoNacional
     * @static
     * @access public
     * @return void
     */
    public static function inserirEnderecoNacional($dadosEnderecoNacional) {
        $db = Zend_Db_Table::getDefaultAdapter();
        $Nomes = $db->insert("Agentes.dbo.EnderecoNacional", $dadosEnderecoNacional);
    }

    /**
     * inserirVisao
     *
     * @param mixed $dadosVisao
     * @static
     * @access public
     * @return void
     */
    public static function inserirVisao($dadosVisao) {
        $db = Zend_Db_Table::getDefaultAdapter();
        $Nomes = $db->insert("Agentes.dbo.Visao", $dadosVisao);
    }

    /**
     * editarproposta
     *
     * @param mixed $idPreProjeto
     * @static
     * @access public
     * @return void
     */
    public static function editarproposta($idPreProjeto)
    {
        $db = Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        $sql = $db->select()->from('PreProjeto',['*'],'SAC.dbo')->where('idPreProjeto = ?', $idPreProjeto);

        return $db->fetchAll($sql);
    }

    /**
     * recuperarTecnicosOrgao
     *
     * @param mixed $idOrgaoSuperior
     * @access public
     * @return void
     */
    public function recuperarTecnicosOrgao($idOrgaoSuperior)
    {
        $db = $this->getAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        $sql = $db->select()->from('vwUsuariosOrgaosGrupos', ['usu_codigo', 'uog_orgao'], 'tabelas.dbo')
            ->where('sis_codigo=21')
            ->where('gru_codigo=92')
            ->where('uog_status = 1')
            ->where('uog_orgao = ?', $idOrgaoSuperior)
            ;

        return $db->fetchAll($sql);
    }

    /**
     * listarDiligenciasPreProjeto
     *
     * @param bool $consulta
     * @param bool $retornaSelect
     * @access public
     * @return void
     */
    public function listarDiligenciasPreProjeto($consulta = array(),$retornaSelect = false)
    {
        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->from(
                        ['pre' => $this->_name],
                        ['nomeProjeto' => 'pre.nomeprojeto', 'pronac'=>'pre.idpreprojeto' ],
                        $this->_schema
                     );

        $select
            ->joinInner(
                array('aval' => 'tbavaliacaoproposta'),
                'aval.idprojeto = pre.idpreprojeto',
                array(
                        'aval.stprorrogacao',
                        'iddiligencia'=>'aval.idavaliacaoproposta',
                        'datasolicitacao'=>'aval.dtavaliacao',
                        'dataresposta'=>'aval.dtresposta',
                        'solicitacao'=>'aval.avaliacao',
                        'resposta'=>'aval.dsresposta',
                        'aval.idcodigodocumentosexigidos',
                        'aval.stenviado'
                    ),
                $this->_schema
            );

        $select->joinLeft(
                array('arq' => 'tbarquivo'),
                'arq.idarquivo = aval.idarquivo',
                array(
                        'arq.nmarquivo',
                        'arq.idarquivo'
                    ),
                $this->getSchema('bdcorporativo', true, 'sccorp')
        );

        $select->joinLeft(
                array('a' => 'agentes'),
                'pre.idagente = a.idagente',
                array(
                        'a.idagente'
                    ),
                $this->getSchema('agentes')
        );

        $select->joinLeft(
                array('n' => 'nomes'),
                'a.idagente = n.idagente',
                array(
                        'n.descricao'
                    ),
                $this->getschema('agentes')
        );

        foreach ($consulta as $coluna=>$valor)
        {
            $select->where($coluna, $valor);
        }
//echo $select;die;
        if($retornaSelect)
        {

            return $select;
        }
        else
        {
        	return $this->fetchAll($select);
        }
    }

    /**
     * dadosPreProjeto
     *
     * @param bool $consulta
     * @access public
     * @return void
     */
    function dadosPreProjeto($consulta = array()){

        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->from(
                        array('pre'=>$this->_name),
                        array(
                                'nomeProjeto'=>'pre.NomeProjeto',
                                'pronac'=>'pre.idPreProjeto'
                             )
                     );

        foreach ($consulta as $coluna=>$valor)
        {
            $select->where($coluna, $valor);
        }

        return $this->fetchAll($select);

    }

    /**
     * buscarAgentePreProjeto
     *
     * @param bool $consulta
     * @access public
     *
     * @todo deletar depois, pois e so usar o findAll da abscract
     */
    public function buscarAgentePreProjeto($consulta = array())
    {
        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->from(
                    array('pre'=>$this->_name),
                    array(
                            'pre.idAgente'
                        ),
                        $this->_schema
                     );

        foreach ($consulta as $coluna=>$valor)
        {
            $select->where($coluna, $valor);
        }

        return $this->fetchAll($select);
    }

    /**
     * listaAvaliadores
     *
     * @param bool $where
     * @access public
     * @return void
     */
    public function listaAvaliadores($where=array()) {

        $slct = $this->select();
        $slct->setIntegrityCheck(false);
        $slct->from(
                array('pp' => $this->_name),
                array('pp.idPreProjeto')
        );
        $slct->joinInner(
                array('ave' => 'tbAvaliadorEdital'),
                'ave.idEdital = pp.idEdital',
                array('ave.idAvaliador'),
                'BDCORPORATIVO.scSAC'
        );
        $slct->joinInner(array('nom' => 'Nomes'),
                'nom.idAgente = ave.idAvaliador',
                array('nom.Descricao'),
                'AGENTES.dbo'
        );
        foreach ($where as $coluna => $valor) {
            $slct->where($coluna, $valor);
        }
        return $this->fetchAll($slct);
    }

    /**
     * listaApenasAvaliadores
     *
     * @param bool $where
     * @access public
     * @return void
     */
    public function listaApenasAvaliadores($where=array()) {

        $slct = $this->select();
        $slct->setIntegrityCheck(false);
        $slct->from(
                array('pp' => $this->_name),
                array('')
        );
        $slct->joinInner(
                array('ave' => 'tbAvaliadorEdital'),
                'ave.idEdital = pp.idEdital',
                array(new Zend_Db_Expr('distinct(ave.idAvaliador)')),
                'BDCORPORATIVO.scSAC'
        );
        $slct->joinInner(array('nom' => 'Nomes'),
                'nom.idAgente = ave.idAvaliador',
                array('nom.Descricao'),
                'AGENTES.dbo'
        );
        foreach ($where as $coluna => $valor) {
            $slct->where($coluna, $valor);
        }
        return $this->fetchAll($slct);
    }

    /**
     * buscarPropostaEditalCompleto
     *
     * @param bool $where
     * @access public
     * @return void
     */
    public function buscarPropostaEditalCompleto($where=array())
    {
        $slct = $this->select();
        $slct->setIntegrityCheck(false);
        $slct->from(
                array('p' => $this->_name),
                array('p.idPreProjeto,
                       p.idAgente,
                       p.NomeProjeto,
                       p.Mecanismo,
                       p.AgenciaBancaria,
                       p.DtInicioDeExecucao,
                       p.DtFinalDeExecucao,
                       p.stTipoDemanda,
                       p.idEdital,
                       CAST(p.ResumoDoProjeto as TEXT) as ResumoDoProjeto')

        );
        $slct->joinLeft(
                array('fd' => 'tbFormDocumento'),
                'fd.idEdital = p.idEdital OR p.idEdital IS NOT NULL',
                array('fd.nrFormDocumento', 'fd.nrVersaoDocumento'),
                'BDCORPORATIVO.scQuiz'
        );

        $slct->joinInner(array('nm' => 'Nomes'),
                'nm.idAgente = p.idAgente',
                array('nm.Descricao as nomeAgente'),
                'AGENTES.dbo'
        );
        foreach ($where as $coluna => $valor) {
            $slct->where($coluna, $valor);
        }

        return $this->fetchAll($slct);
    }

    /**
     * dadosProjetoDiligencia
     *
     * @param mixed $idProjeto
     * @access public
     * @return void
     */
    public function dadosProjetoDiligencia($idProjeto){

        $slct = $this->select();
        $slct->setIntegrityCheck(false);
        $slct->from(
                    array('p' => $this->_name),
                    array(
                        'idProjeto'=>'p.idPreProjeto',
                        'p.NomeProjeto'
                        )
            );
        $slct->joinInner(
                array("a"=>"Agentes"),
                "a.idAgente = p.idAgente",
                array(),
                'agentes.dbo'
                );
        $slct->joinInner(
                array("n"=>"Nomes"),
                "a.idAgente = n.idAgente",
                array('Destinatario'=>'Descricao'),
                'AGENTES.dbo'
                );
        $slct->joinInner(
                array("int"=>"Internet"),
                "a.idAgente = int.idAgente",
                array('Email'=>'Descricao'),
                'AGENTES.dbo'
                );

        $slct->where('p.idPreProjeto = ?',$idProjeto);
        $slct->where('a.Status = 0'); // Status do registro da pessoa, 0 - para ativo e 1 - para inativo

        return $this->fetchAll($slct);
    }

    /**
     * analiseDeCustos
     *
     * @param mixed $idPreProjeto
     * @static
     * @access public
     * @return void
     */
    public static function analiseDeCustos($idPreProjeto)
    {
        $db = Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        $a = [
            'a.idPreProjeto',
            'a.NomeProjeto',
        ];

        $d = [
            new Zend_Db_Expr("CONVERT(varchar(8),d.idPlanilhaEtapa) + ' - ' + d.Descricao AS Etapa"),
            'd.idPlanilhaEtapa'
        ];

        $z = [
            'z.idProduto',
            new Zend_Db_Expr("CASE WHEN z.idProduto = 0 THEN 'Administração do Projeto' ELSE c.Descricao END AS Produto"),
            new Zend_Db_Expr('z.Quantidade * z.Ocorrencia * z.ValorUnitario AS VlSolicitado'),
            'z.Quantidade',
            'z.Ocorrencia',
            'z.ValorUnitario',
            'z.QtdeDias',
            'z.TipoDespesa',
            'z.TipoPessoa',
            'z.Contrapartida',
            'z.dsJustificativa as JustificativaProponente',
            'z.FonteRecurso AS idFonte',
            new Zend_Db_Expr('ROUND(z.Quantidade * z.Ocorrencia * z.ValorUnitario, 2) AS Sugerido'),
            'z.idUsuario'
        ];

        $f = [
            'f.UF',
            'f.idUF',
            'f.Municipio',
            'f.idMunicipio',
        ];

        $sql = $db->select()->from(['a' => 'PreProjeto'], $a, 'SAC.dbo')
            ->join(['z' => 'tbPlanilhaProposta'],'z.idProjeto = a.idPreProjeto', $z, 'SAC.dbo')
            ->joinLeft(['c' => 'Produto'],'c.Codigo = z.idProduto', [],'SAC.dbo')
            ->join(['d' => 'tbPlanilhaEtapa'],'d.idPlanilhaEtapa = z.idEtapa', $d,'SAC.dbo')
            ->join(['e' => 'tbPlanilhaUnidade'],'e.idUnidade = z.Unidade', ['e.Descricao AS Unidade'],'SAC.dbo')
            ->join(['i' => 'tbPlanilhaItens'],'i.idPlanilhaItens = z.idPlanilhaItem', ['i.Descricao AS Item'],'SAC.dbo')
            ->join(['x' => 'Verificacao'], 'x.idVerificacao = z.FonteRecurso', ['x.Descricao AS FonteRecurso'],'SAC.dbo')
            ->join(['f' => 'vUFMunicipio'], 'f.idUF = z.UfDespesa AND f.idMunicipio = z.MunicipioDespesa', $f, 'AGENTES.dbo')
            ->where('a.idPreProjeto = ?', $idPreProjeto)
            ->order(['x.Descricao', 'Produto', 'Etapa', 'UF', 'Item']);

        return $db->fetchAll($sql);
    }

    /**
     * tecnicoTemProposta
     *
     * @param mixed $idTecnico
     * @access public
     * @return void
     */
    public function tecnicoTemProposta($idTecnico){

        $slct = $this->select();
        $slct->setIntegrityCheck(false);
        $slct->from(
                    array("ap" => "tbAvaliacaoProposta"),
                    array("*"),
                    "SAC.dbo"
            );
        $slct->joinInner(
                array("m"=>"tbMovimentacao"),
                "ap.idProjeto = m.idProjeto",
                array(),
                "SAC.dbo"
                );

        $condicao = new Zend_Db_Expr("select top 1 * from SAC..Projetos p where p.idProjeto = ap.idProjeto");
        $slct->where("ap.idTecnico = ?",$idTecnico);
        $slct->where("m.stEstado = 0");
        $slct->where("m.Movimentacao IN (96,97,128)");
        $slct->where("NOT EXISTS({$condicao})");

        $rs = $this->fetchAll($slct)->toArray();
        if(count($rs) > 0){
            return true;
        }

        return false;
    }

    /**
     * alteraproponente
     *
     * @param mixed $idPreProjeto
     * @param mixed $idAgente
     * @static
     * @access public
     * @return void
     */
    public static function alteraproponente($idPreProjeto, $idAgente)
    {
        $db = Zend_Db_Table::getDefaultAdapter();

        $where['idPreProjeto = ?'] = $idPreProjeto;

        return $db->update('SAC.dbo.PreProjeto', ['idAgente' => $idAgente], $where);
    }

    /**
     * alteraresponsavel
     *
     * @param mixed $idPreProjeto
     * @param mixed $idResponsavel
     * @static
     * @access public
     * @return void
     */
    public static function alteraresponsavel($idPreProjeto, $idResponsavel)
    {
        $db = Zend_Db_Table::getDefaultAdapter();

        $where['idPreProjeto = ?'] = $idPreProjeto;

        return $db->update('SAC.dbo.PreProjeto', ['idUsuario' => $idResponsavel], $where);
    }

    /**
     * BuscarPropostaProjetos Busca as propostas/projetos vinculados ao proponente
     *
     * @param bool $where
     * @access public
     * @return void
     */
    public function buscarPropostaProjetos($where=array())
    {
        $slct = $this->select();
        $slct->setIntegrityCheck(false);
        $slct->from(
                array('pp' => $this->_name),
                array('pp.idPreProjeto,
                       pp.idAgente,
                       pp.NomeProjeto,
                       pp.Mecanismo,
                       pp.AgenciaBancaria,
                       pp.DtInicioDeExecucao,
                       pp.DtFinalDeExecucao,
                       pp.stTipoDemanda,
                       pp.idUsuario,
                       pp.idEdital,
                       CAST(pp.ResumoDoProjeto as TEXT) as ResumoDoProjeto'),
            $this->_schema
        );

        $slct->joinLeft(
                array('resp' => 'SGCacesso'), 'resp.IdUsuario = pp.idUsuario',
                array('resp.Nome','resp.Cpf'),
            $this->getSchema('controledeacesso')
        );

        $slct->joinLeft(
                array('pr' => 'Projetos'), 'pp.idPreProjeto = pr.idProjeto',
                array('pr.idProjeto','(pr.AnoProjeto+pr.Sequencial) as PRONAC'),
            $this->_schema
        );

        foreach ($where as $coluna => $valor) {
            $slct->where($coluna, $valor);
        }

        $slct->order('pp.idPreProjeto');
        $slct->order('pp.NomeProjeto');

        return $this->fetchAll($slct);
    }

    /**
     * buscarPropProjVinculados
     *
     * @param  mixed $idAgenteProponente
     * @access public
     * @return void
     */
    public function buscarPropProjVinculados($idAgenteProponente){
        $slct = $this->select();
        $slct->setIntegrityCheck(false);
        $slct->from(
                array('pp' => $this->_name),
                array('pp.idPreProjeto',
                       'pp.idAgente',
                       'pp.NomeProjeto',
                       'pp.Mecanismo',
                       'pp.AgenciaBancaria',
                       'pp.DtInicioDeExecucao',
                       'pp.DtFinalDeExecucao',
                       'pp.stTipoDemanda',
                       'pp.idUsuario',
                       'pp.idEdital')
                );

        $slct->joinLeft(
                array('resp' => 'SGCacesso'), 'resp.IdUsuario = pp.idUsuario',
                array('resp.Nome','resp.Cpf'),'CONTROLEDEACESSO.dbo'
                );

        $slct->where('idAgente = ?', $idAgenteProponente);
        $slct->where('stEstado = ?', 1);
        $slct->where('NOT EXISTS(SELECT * FROM Projetos p WHERE p.idProjeto = pp.idPreProjeto)', '');
        $slct->order('pp.idPreProjeto');
        $slct->order('pp.NomeProjeto');

        return $this->fetchAll($slct);
    }

    /**
     * buscarVinculadosProponenteDirigentes
     *
     * @param mixed $arrayIdAgentes
     * @access public
     * @return void
     */
    public function buscarVinculadosProponenteDirigentes($arrayIdAgentes){
        $slct = $this->select();
        $slct->setIntegrityCheck(false);
        $slct->from(
                array('pp' => $this->_name),
                array('pp.idPreProjeto',
                       'pp.idAgente',
                       'pp.NomeProjeto',
                       'pp.Mecanismo',
                       'pp.AgenciaBancaria',
                       'pp.DtInicioDeExecucao',
                       'pp.DtFinalDeExecucao',
                       'pp.stTipoDemanda',
                       'pp.idUsuario',
                       'pp.idEdital'),
                $this->_schema
                );

        $slct->joinLeft(
                array('resp' => 'SGCacesso'), 'resp.IdUsuario = pp.idUsuario',
                array('resp.Nome','resp.Cpf'),
                $this->getSchema('controledeacesso')
                );

        $slct->where('idAgente in (?)', $arrayIdAgentes);
        $slct->where('stEstado = ?', 1);
        $slct->where('NOT EXISTS(SELECT * FROM Projetos p WHERE p.idProjeto = pp.idPreProjeto)', '');
        $slct->order('pp.idPreProjeto');
        $slct->order('pp.NomeProjeto');

        return $this->fetchAll($slct);
    }

    /**
     * gerenciarResponsaveisPendentes
     *
     * @param mixed $siVinculo
     * @param bool $idAgente
     * @static
     * @access public
     * @return void
     */
    public static function gerenciarResponsaveisPendentes($siVinculo, $idAgente = null)
    {
        $db = Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        $k = [
            'k.Cpf',
            'k.IdUsuario as idResponsavel',
            'k.Nome AS NomeResponsavel',
            'k.IdUsuario',
        ];

        $v = [
            'v.idVinculo',
            'v.siVinculo',
            'v.idUsuarioResponsavel',
        ];

        $sql = $db->select()->distinct()
            ->from(['a'=>'Agentes'], [],'AGENTES.dbo')
            ->joinLeft(['v' => 'tbVinculo'], 'a.idAgente = v.idAgenteProponente', $v,'AGENTES.dbo')
            ->join(['k' => 'SGCacesso'], 'k.IdUsuario = v.idUsuarioResponsavel',$k,'CONTROLEDEACESSO.dbo')
            ->where('a.idAgente= ?', $idAgente)
            ->where('v.siVinculo = ?', $siVinculo)
            ->where('a.CNPJCPF <> k.Cpf')
            ->order('k.Nome  ASC');

        return $db->fetchAll($sql);
    }

    /**
     * GerenciarResponsaveisVinculados
     *
     * @param mixed $siVinculo
     * @param bool $idAgente
     * @static
     * @access public
     * @return void
     */
    public static function gerenciarResponsaveisVinculados($siVinculo, $idAgente = null)
    {
        $db = Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        $sql = $db->select()->distinct()
            ->from(['j'=>'PreProjeto'], [], 'SAC.dbo')
            ->join(['a' => 'Agentes'], 'j.idAgente = a.idAgente', [],'AGENTES.dbo')
            ->join(['v' => 'tbVinculoProposta'], 'j.idPreProjeto = v.idPreProjeto', [],'AGENTES.dbo')
            ->join(['y' => 'tbVinculo'], 'v.idVinculo = y.idVinculo', ['y.idVinculo', 'y.siVinculo', 'y.idUsuarioResponsavel'],'AGENTES.dbo')
            ->join(['k' => 'SGCacesso'], 'k.IdUsuario = y.idUsuarioResponsavel', ['k.Cpf', 'k.IdUsuario as idResponsavel', 'k.Nome AS NomeResponsavel'],'CONTROLEDEACESSO.dbo')
            ->join(['r' => 'SGCacesso'], 'r.Cpf = a.CNPJCPF', ['r.IdUsuario'],'CONTROLEDEACESSO.dbo')
            ->where('j.idAgente= ?', $idAgente)
            ->where('y.siVinculo = ?', $siVinculo)
            ->where('a.CNPJCPF <> k.Cpf')
            ->order(['k.Nome  ASC'])
            ;

        return $db->fetchAll($sql);
    }

    /**
     * listarPropostasResultado
     *
     * @param mixed $idAgente
     * @param mixed $idResponsavel
     * @param mixed $idAgenteCombo
     * @static
     * @access public
     * @return void
     * @author wouerner <wouerner@gmail.com>
     */
    public static function listarPropostasResultado($idAgente, $idResponsavel, $idAgenteCombo)
    {
        $db = Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        $subSql = $db->select()
            ->from(['pr' => 'projetos'], ['idprojeto'], Proposta_Model_PreProjeto::getSchema('sac'))
            ->where('a.idpreprojeto = pr.idprojeto')
            ;

        $sql = $db->select()
            ->from(['a'=>'preprojeto'], ['a.idpreprojeto', 'a.nomeprojeto'],Proposta_Model_PreProjeto::getSchema('sac'))
            ->join(['b' => 'agentes'], 'a.idagente = b.idagente', ['b.cnpjcpf', 'b.idagente'], Proposta_Model_PreProjeto::getSchema('agentes'))
            ->joinleft(['n' => 'nomes'], 'n.idagente = b.idagente', ['n.descricao as nomeproponente'], Proposta_Model_PreProjeto::getSchema('agentes'))
            ->where('a.idagente = ? ', $idAgente)
            ->where('a.stestado = 1')
            ->where("NOT EXISTS($subSql)")
            ->where("a.mecanismo = '1'")
            ;

        $subSql = $db->select()
            ->from(['f' => 'projetos'], ['idprojeto'], Proposta_Model_PreProjeto::getSchema('sac'))
            ->where('a.idpreprojeto = f.idprojeto');

        $sql2 = $db->select()
            ->from(['a'=>'preprojeto'], ['a.idpreprojeto', 'a.nomeprojeto'], Proposta_Model_PreProjeto::getSchema('sac'))
            ->join(['b' => 'agentes'], 'a.idagente = b.idagente', ['b.cnpjcpf', 'b.idagente'], Proposta_Model_PreProjeto::getSchema('agentes'))
            ->join(['c' => 'vinculacao'], 'b.idagente = c.idvinculoprincipal', [], Proposta_Model_PreProjeto::getSchema('agentes'))
            ->join(['d' => 'agentes'], 'c.idagente = d.idagente', [], Proposta_Model_PreProjeto::getSchema('agentes'))
            ->join(['e' => 'sgcacesso'], 'd.cnpjcpf = e.cpf', [], Proposta_Model_PreProjeto::getSchema('controledeacesso'))
            ->joinleft(['n' => 'nomes'], 'n.idagente = b.idagente', ['n.descricao as nomeproponente'], Proposta_Model_PreProjeto::getSchema('agentes'))
            ->where('e.idusuario = ?',$idResponsavel)
            ->where('a.stestado = 1')
            ->where("NOT EXISTS($subSql)")
            ->where("a.mecanismo = '1'")
            ;

        $subSql = $db->select()
            ->from(['z' => 'projetos'], ['idprojeto'], Proposta_Model_PreProjeto::getSchema('sac'))
            ->where('a.idpreprojeto = z.idprojeto')
            ;

        $sql3 = $db->select()
            ->from(['a'=>'preprojeto'], ['a.idpreprojeto', 'a.nomeprojeto'], Proposta_Model_PreProjeto::getSchema('sac'))
            ->join(['b' => 'agentes'], 'a.idagente = b.idagente', ['b.cnpjcpf', 'b.idagente'], Proposta_Model_PreProjeto::getSchema('agentes'))
            ->join(['c' => 'nomes'], 'b.idagente = c.idagente', ['c.descricao as nomeproponente'], Proposta_Model_PreProjeto::getSchema('agentes'))
            ->join(['d' => 'sgcacesso'], 'a.idusuario = d.idusuario', [], Proposta_Model_PreProjeto::getSchema('controledeacesso'))
            ->join(['e' => 'tbvinculoproposta'], 'a.idpreprojeto = e.idpreprojeto', [], Proposta_Model_PreProjeto::getSchema('agentes'))
            ->join(['f' => 'tbvinculo'], 'e.idvinculo = f.idvinculo', [], Proposta_Model_PreProjeto::getSchema('agentes'))
            ->where('a.stestado = 1')
            ->where("NOT EXISTS($subSql)")
            ->where("a.mecanismo = '1'")
            ->where('e.sivinculoproposta = 2')
            ->where('f.idusuarioresponsavel = ?', $idResponsavel)
            ;

        if (!empty($idAgenteCombo)) {
            $sql->where('b.idagente = ?', $idAgenteCombo);
            $sql2->where('b.idagente = ?', $idAgenteCombo);
            $sql3->where('b.idagente = ?', $idAgenteCombo);
        }

        $sql = $db->select()->union(array($sql, $sql2,$sql3), Zend_Db_Select::SQL_UNION_ALL);

        return $db->fetchAll($sql);
    }

    /**
     * Método para buscar os Proponentes - Combo Listar Propostas
     * @access public
     * @param integer $idResponsavel
     * @return object
     */
    public function listarPropostasCombo($idResponsavel)
    {
        $db = Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        $sql = $db->select()
            ->from(['a'=>'preprojeto'], null, $this->_schema)
            ->join(['b' => 'agentes'], 'a.idagente = b.idagente', ['b.cnpjcpf', 'b.idagente'], $this->getSchema('agentes'))
            ->joinLeft(['n' => 'nomes'], 'n.idagente = b.idagente', ['n.descricao as nomeproponente'], $this->getSchema('agentes'))
            ->join(['c' => 'sgcacesso'], 'b.cnpjcpf = c.cpf', null, $this->getSchema('controledeacesso'))
            ->where('c.idusuario = ?', $idResponsavel)
        ;

        $sql2 = $db->select()
            ->from(['a'=>'preprojeto'], null, $this->_schema)
            ->join(['b' => 'agentes'], 'a.idagente = b.idagente', ['b.cnpjcpf', 'b.idagente'], $this->getSchema('agentes'))
            ->joinleft(['n' => 'nomes'], 'n.idagente = b.idagente', ['n.descricao as nomeproponente'], $this->getSchema('agentes'))
            ->join(['c' => 'tbvinculoproposta'], 'a.idpreprojeto = c.idpreprojeto', null, $this->getSchema('agentes'))
            ->join(['d' => 'tbvinculo'], 'c.idvinculo = d.idvinculo', null, $this->getSchema('agentes'))
            ->join(['f' => 'agentes'], 'd.idagenteproponente = f.idagente', null, $this->getSchema('agentes'))
            ->join(['e' => 'sgcacesso'], 'f.cnpjcpf = e.cpf', null, $this->getSchema('controledeacesso'))
            ->where('e.idusuario = ?', $idResponsavel)
            ->where('c.sivinculoproposta = 2')
            ;

        $sql3 = $db->select()
            ->from(['a'=>'agentes'], ['a.cnpjcpf', 'a.idagente'], $this->getSchema('agentes'))
            ->joinleft(['n' => 'nomes'], 'n.idagente = a.idagente', ['n.descricao as nomeproponente'], $this->getSchema('agentes'))
            ->join(['b' => 'vinculacao'], 'a.idagente = b.idvinculoprincipal', null, $this->getSchema('agentes'))
            ->join(['c' => 'agentes'], 'b.idagente = c.idagente', null, $this->getSchema('agentes'))
            ->join(['d' => 'sgcacesso'], 'c.cnpjcpf = d.cpf', null, $this->getSchema('controledeacesso'))
            ->where('d.idusuario = ?', $idResponsavel)
            ;

        $sql4 = $db->select()
            ->from(['a'=>'agentes'], ['a.cnpjcpf', 'a.idagente'], $this->getSchema('agentes'))
            ->joinleft(['n' => 'nomes'], 'n.idagente = a.idagente', ['n.descricao as nomeproponente'], $this->getSchema('agentes'))
            ->join(['b' => 'tbvinculo'], 'a.idagente = b.idagenteproponente', null, $this->getSchema('agentes'))
            ->join(['c' => 'sgcacesso'], 'b.idusuarioresponsavel = c.idusuario', null, $this->getSchema('controledeacesso'))
            ->where('b.sivinculo = 2')
            ->where('c.idusuario = ?', $idResponsavel)
            ;

        $sql5 = $db->select()
            ->from(['a'=>'agentes'], ['a.cnpjcpf', 'a.idagente'], $this->getSchema('agentes'))
            ->joinleft(['n' => 'nomes'], 'n.idagente = a.idagente', ['n.descricao as nomeproponente'], $this->getSchema('agentes'))
            ->join(['b' => 'sgcacesso'], 'a.cnpjcpf = b.cpf', null, $this->getSchema('controledeacesso'))
            ->where('b.idusuario = ?', $idResponsavel)
            ;

        $sql = $db->select()->union(array($sql, $sql2, $sql3, $sql4, $sql5))
            ->group(['a.cnpjcpf', 'a.idagente', 'n.descricao'])
            ->order(['3 asc']);

//        echo '<pre>';
//        print_r(str_replace('"', '', $sql->assemble()));
//        exit;

        return $db->fetchAll($sql);
    }

    /**
     * relatorioPropostas2
     *
     * @param bool $where
     * @param bool $having
     * @param bool $order
     * @param mixed $tamanho
     * @param mixed $inicio
     * @param bool $count
     * @param bool $dados
     * @access public
     * @return void
     */
    public function relatorioPropostas2($where=array(), $having=array(), $order=array(), $tamanho=-1, $inicio=-1, $count = false, $dados = null)
    {
        $slct = $this->select();
        $slct->distinct();
        $slct->setIntegrityCheck(false);
        $slct->from(
                    array("p"=>$this->_name),
                    array("idProjeto"=>"idPreProjeto", "NomeProposta"=>"NomeProjeto", "idAgente"),
                    "SAC.dbo"
                    );

		if(!($dados->proposta)){
        $slct->joinInner(
                        array("m"=>"tbMovimentacao"),
                        "p.idPreProjeto = m.idProjeto AND m.stEstado = 0",
                        array(),
                        "SAC.dbo"
                        );
        $slct->joinInner(
                        array("vr"=>"Verificacao"),
                        "m.movimentacao = vr.idVerificacao and vr.idTipo = 4",
                        array(),
                        "SAC.dbo"
                        );
        $slct->joinInner(
                        array("x"=>"tbAvaliacaoProposta"),
                        "p.idPreProjeto = x.idProjeto AND x.stEstado = 0",
                        array(),
                        "SAC.dbo"
                        );

		}
		if(($dados->uf) || ($dados->municipio)){
        $slct->joinInner(
                        array("ab"=>"Abrangencia"),
                        "p.idPreProjeto = ab.idProjeto AND ab.stAbrangencia = 1",
                        array(),
                        "SAC.dbo"
                        );
		}

		if($dados->uf){
        $slct->joinInner(
                        array("uf"=>"UF"),
                        "uf.idUF = ab.idUF",
                        array(),
                        "AGENTES.dbo"
                        );
		}

		if(($dados->uf) || ($dados->municipio)){
        $slct->joinInner(
                        array("mu"=>"Municipios"),
                        "mu.idMunicipioIBGE = ab.idMunicipioIBGE",
                        array(),
                        "AGENTES.dbo"
                        );
		}

        if(($dados->area) || ($dados->segmento)){
        $slct->joinInner(
                        array("pdp"=>"PlanoDistribuicaoProduto"),
                        "pdp.idProjeto = p.idPreProjeto AND pdp.stPlanoDistribuicaoProduto = 1",
                        array(),
                        "SAC.dbo"
                        );
        }

        $slct->joinLeft(
                        array("pp"=>"tbPlanilhaProposta"),
                        "pp.idProjeto = p.idPreProjeto",
                        array("valor"=>new Zend_Db_Expr("sum(Quantidade*Ocorrencia*ValorUnitario)")),
                        "SAC.dbo"
                        );
        $slct->joinInner(
                        array("ag"=>"agentes"),
                        "ag.idAgente = p.idAgente",
                        array("ag.CNPJCPF"),
                        "AGENTES.dbo"
                        );
        $slct->joinInner(
                        array("nm"=>"nomes"),
                        "nm.idAgente = p.idAgente",
                        array("nm.Descricao as Proponente"),
                        "AGENTES.dbo"
                        );

        //adiciona quantos filtros foram enviados
        foreach ($where as $coluna => $valor) {
            $slct->where($coluna, $valor);
        }

        $slct->group(array("p.idPreProjeto", "p.NomeProjeto", "p.idAgente", "ag.CNPJCPF", "nm.Descricao"));

        //adiciona quantos filtros foram enviados
        foreach ($having as $coluna => $valor) {
            $slct->having($coluna, $valor);
        }

        if($count){
            $this->_totalRegistros = $this->fetchAll($slct)->count();
            return $this->_totalRegistros;
        }

        //adicionando linha order ao select
        $slct->order($order);

        // paginacao
        if ($tamanho > -1) {
            $tmpInicio = 0;
            if ($inicio > -1) {
                $tmpInicio = $inicio;
            }
            $slct->limit($tamanho, $tmpInicio);
        }
        return $this->fetchAll($slct);
    }

    public function relatorioPropostas($where=array(), $having=array(), $order=array(), $tamanho=-1, $inicio=-1, $count = false){

        $slct = $this->select();
        $slct->distinct();
        $slct->setIntegrityCheck(false);
        $slct->from(
            array("p"=>$this->_name),
            array("idProjeto"=>"idPreProjeto", "NomeProposta"=>"NomeProjeto", "idAgente", "p.stEstado", "p.DtArquivamento"),
            "SAC.dbo"
        );

        $slct->joinInner(
            array("m"=>"tbMovimentacao"), "p.idPreProjeto = m.idProjeto AND m.stEstado = 0",
            array('m.Movimentacao', 'm.stEstado AS estadoMovimentacao'), "SAC.dbo"
        );
        $slct->joinInner(
            array("vr"=>"Verificacao"), "m.movimentacao = vr.idVerificacao and vr.idTipo = 4",
            array(), "SAC.dbo"
        );
        $slct->joinLeft(
            array("x"=>"tbAvaliacaoProposta"), "p.idPreProjeto = x.idProjeto AND x.stEstado = 0",
            array('x.ConformidadeOK', 'x.stEstado AS estadoAvaliacao'), "SAC.dbo"
        );

        if( isset($where['ab.idUF = ?']) || isset($where['ab.idMunicipioIBGE = ?'])){
            $slct->joinInner(
                array("ab"=>"Abrangencia"), "p.idPreProjeto = ab.idProjeto AND ab.stAbrangencia = 1",
                array(), "SAC.dbo"
            );
        }

        if(isset($where['ab.idUF = ?'])){
            $slct->joinInner(
                array("uf"=>"UF"), "uf.idUF = ab.idUF",
                array(), "AGENTES.dbo"
            );
        }

        if( isset($where['ab.idUF = ?']) || isset($where['ab.idMunicipioIBGE = ?'])){
            $slct->joinInner(
                array("mu"=>"Municipios"), "mu.idMunicipioIBGE = ab.idMunicipioIBGE",
                array(), "AGENTES.dbo"
            );
        }

        if( isset($where['pdp.Area = ?']) || isset($where['pdp.Segmento = ?'])){
            $slct->joinInner(
                array("pdp"=>"PlanoDistribuicaoProduto"), "pdp.idProjeto = p.idPreProjeto AND pdp.stPlanoDistribuicaoProduto = 1",
                array(), "SAC.dbo"
            );
        }

        $slct->joinLeft(
            array("pp"=>"tbPlanilhaProposta"), "pp.idProjeto = p.idPreProjeto",
            array("valor"=>new Zend_Db_Expr("sum(Quantidade*Ocorrencia*ValorUnitario)")), "SAC.dbo"
        );

        $slct->joinInner(
            array("ag"=>"agentes"), "ag.idAgente = p.idAgente",
            array("ag.CNPJCPF"), "AGENTES.dbo"
        );

        $slct->joinInner(
            array("nm"=>"nomes"), "nm.idAgente = p.idAgente",
            array(
                "nm.Descricao as Proponente"
            ), "AGENTES.dbo"
        );

        //adiciona quantos filtros foram enviados
        foreach ($where as $coluna => $valor) {
            $slct->where($coluna, $valor);
        }

        $slct->where('NOT EXISTS (SELECT * FROM Projetos z WHERE z.idProjeto = p.idPreProjeto)');
        $slct->group(array("p.idPreProjeto", "p.NomeProjeto", "p.idAgente", "p.stEstado", "p.DtArquivamento", "m.Movimentacao", "m.stEstado", "x.ConformidadeOK", "x.stEstado", "ag.CNPJCPF", "nm.Descricao"));

        //adiciona quantos filtros foram enviados
        foreach ($having as $coluna => $valor) {
            $slct->having($coluna, $valor);
        }

        if ($count) {
            return $this->fetchAll($slct)->count();
        }

        //adicionando linha order ao select
        $slct->order($order);

        // paginacao
        if ($tamanho > -1) {
            $tmpInicio = 0;
            if ($inicio > -1) {
                $tmpInicio = $inicio;
            }
            $slct->limit($tamanho, $tmpInicio);
        }

        return $this->fetchAll($slct);
    }

    /**
     * Retorna registros do banco de dados
     * @param array $where - array com dados where no formato "nome_coluna_1"=>"valor_1","nome_coluna_2"=>"valor_2"
     * @param array $order - array com orders no formado "coluna_1 desc","coluna_2"...
     * @param int $tamanho - numero de registros que deve retornar
     * @param int $inicio - offset
     * @return Zend_Db_Table_Rowset_Abstract
     */
    public function buscarPropostaAdmissibilidade($where=array(), $order=array(), $tamanho=-1, $inicio=-1)
    {
        $db = $this->getAdapter();
        $db->setFetchMode(Zend_DB :: FETCH_OBJ);

        $p = [
            'p.idPreProjeto AS idProjeto',
            'p.NomeProjeto AS NomeProposta',
            'p.idAgente'
        ];

        $m =[
            new Zend_Db_Expr('CONVERT(CHAR(20),m.DtMovimentacao, 120) AS DtMovimentacao'),
            new Zend_Db_Expr('DATEDIFF(d, m.DtMovimentacao, GETDATE()) AS diasDesdeMovimentacao'),
            'm.idMovimentacao',
            'm.Movimentacao AS CodSituacao',
        ];

        // Replace da função: SAC.dbo.fnIdOrgaoSuperiorAnalista(a.idTecnico)
        $orgao = $db->select()
            ->from(['vwUsuariosOrgaosGrupos'], 'org_superior', 'tabelas.dbo')
            ->where('usu_codigo = x.idTecnico')
            ->where('sis_codigo = 21')
            ->where('gru_codigo = 92')
            ->group('org_superior');

        //replace função: SAC.dbo.fnNomeTecnicoMinc(a.idTecnico)
        $tecnico = $db->select()
            ->from(['Usuarios'], 'usu_nome', 'tabelas.dbo')
            ->where('usu_codigo = x.idTecnico')
            ;

        $x =[
            new Zend_Db_Expr('CONVERT(CHAR(20),x.DtAvaliacao, 120) AS DtAdmissibilidade'),
            new Zend_Db_Expr('DATEDIFF(d, x.DtAvaliacao, GETDATE()) AS diasCorridos'),
            'x.idTecnico AS idUsuario',
            'x.DtAvaliacao',
            'x.idAvaliacaoProposta',
            "($tecnico) AS Tecnico",
            "($orgao) AS idSecretaria",
        ];

        $sql = $db->select()
            ->from(["p" => $this->_name], $p, "SAC.dbo")
            ->join(["m" => "tbMovimentacao"], 'p.idPreProjeto = m.idProjeto AND m.stEstado = 0', $m, "SAC.dbo")
            ->joinInner(["x" => "tbAvaliacaoProposta"], "p.idPreProjeto = x.idProjeto AND x.stEstado = 0", $x, "SAC.dbo")
            ->joinInner(["a" => "Agentes"], 'p.idAgente = a.idAgente', ['a.CNPJCPF'], 'AGENTES.dbo')
            ->joinInner(["y" => "Verificacao"], 'm.Movimentacao = y.idVerificacao', null, 'SAC.dbo')
            ->joinLeft(["ap1" => 'tbAvaliacaoProposta'], "p.idPreProjeto = ap1.idProjeto AND ap1.stEnviado = 'S'", [new Zend_Db_Expr('DATEDIFF(d, ap1.DtEnvio, GETDATE()) AS diasDiligencia')], 'SAC.dbo')
            ->joinLeft(["ap2" => 'tbAvaliacaoProposta'], "p.idPreProjeto = ap2.idProjeto AND ap2.stEnviado = 'S'", [new Zend_Db_Expr('DATEDIFF(d, ap2.dtResposta, GETDATE()) AS diasRespostaDiligencia')], 'SAC.dbo')
            ->where(
                new Zend_Db_Expr('NOT EXISTS
                    (
                    SELECT TOP (1) IdPRONAC, AnoProjeto, Sequencial, UfProjeto, Area, Segmento, Mecanismo, NomeProjeto, Processo, CgcCpf, Situacao, DtProtocolo, DtAnalise, Modalidade, Orgao, OrgaoOrigem, DtSaida, DtRetorno, UnidadeAnalise, Analista, DtSituacao, ResumoProjeto, ProvidenciaTomada, Localizacao, DtInicioExecucao, DtFimExecucao, SolicitadoUfir, SolicitadoReal, SolicitadoCusteioUfir, SolicitadoCusteioReal, SolicitadoCapitalUfir, SolicitadoCapitalReal, Logon, idProjeto
                    FROM SAC.dbo.Projetos AS u
                    WHERE (p.idPreProjeto = idProjeto)
                    )'
                )
            )
            ;

        // adicionando clausulas where
        foreach ($where as $coluna=>$valor)
        {
            $sql->where($coluna.'?', $valor);
        }

        // adicionando clausulas order
        $sql->order($order);

        // retornando os registros conforme objeto select
        return $db->fetchAll($sql);
    }

    /**
     * Retorna registros do banco de dados
     * @param array $where - array com dados where no formato "nome_coluna_1"=>"valor_1","nome_coluna_2"=>"valor_2"
     * @param array $order - array com orders no formado "coluna_1 desc","coluna_2"...
     * @param int $tamanho - numero de registros que deve retornar
     * @param int $inicio - offset
     * @return Zend_Db_Table_Rowset_Abstract
     */
    public function buscarPropostaAdmissibilidadeZend($where=array(), $order=array(), $tamanho=-1, $inicio=-1)
    {
        $slct = $this->select();
        $slct->setIntegrityCheck(false);
        $slct->from(
                    array("p"=>$this->_name),
                    array("idProjeto"=>"idPreProjeto", "NomeProposta"=>"NomeProjeto", "idAgente")
                    );
        $slct->joinInner(
                        array("m"=>"tbMovimentacao"),
                        "p.idPreProjeto = m.idProjeto AND m.stEstado = 0",
                        array("idMovimentacao", "CodSituacao"=>"m.Movimentacao", "DtMovimentacao"=>"CONVERT(CHAR(20),m.DtMovimentacao, 120)", "diasDesdeMovimentacao"=>"DATEDIFF(d, m.DtMovimentacao, GETDATE())"),
                        "SAC.dbo"
                        );
        $slct->joinLeft(
                        array("x"=>"tbAvaliacaoProposta"),
                        "p.idPreProjeto = x.idProjeto AND x.stEstado = 0",
                        array("idAvaliacaoProposta", "DtAdmissibilidade"=>"CONVERT(CHAR(20),x.DtAvaliacao, 120)", "diasCorridos"=>"DATEDIFF(d, x.DtAvaliacao, GETDATE())"),
                        "SAC.dbo"
                        );
        $slct->joinInner(
                        array("a"=>"Agentes"),
                        "p.idAgente = a.idAgente",
                        array("CNPJCPF"),
                        "AGENTES.dbo"
                        );
        $slct->joinInner(
                        array("y"=>"Verificacao"),
                        "m.Movimentacao = y.idVerificacao",
                        array("Situacao"=>"Descricao"),
                        "SAC.dbo"
                        );

        //adiciona quantos filtros foram enviados
        foreach ($where as $coluna => $valor) {
            $slct->where($coluna, $valor);
        }

        //adicionando linha order ao select
        $slct->order($order);

        $this->_totalRegistros = $this->fetchAll($slct)->count();
        // paginacao
        if ($tamanho > -1) {
            $tmpInicio = 0;
            if ($inicio > -1) {
                $tmpInicio = $inicio;
            }
            $slct->limit($tamanho, $tmpInicio);
        }
        return $this->fetchAll($slct);


    }

    /**
     * buscarTecnicosHistoricoAnaliseVisual
     *
     * @param mixed $idOrgao
     * @access public
     * @return void
     */
    public function buscarTecnicosHistoricoAnaliseVisual($idOrgao)
    {
        $db = Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        // Replace da função: SAC.dbo.fnIdOrgaoSuperiorAnalista(a.idTecnico)
        $subSql = $db->select()
            ->from(['vwUsuariosOrgaosGrupos'], 'org_superior', 'tabelas.dbo')
            ->where('usu_codigo = a.idTecnico')
            ->where('sis_codigo = 21')
            ->where('gru_codigo = 92')
            ->group('org_superior');

        $sql = $db->select()
            ->distinct()
            ->from(['a' => 'tbAvaliacaoProposta'], ['a.idTecnico'], "SAC.dbo")
            ->join(['p' => 'PreProjeto'], 'p.idPreProjeto = a.idProjeto', null, "SAC.dbo")
            ->join(['u' => 'Usuarios'], 'u.usu_codigo = a.idTecnico', 'u.usu_nome as Tecnico', 'TABELAS.dbo')
            ->where('ConformidadeOK<>1')
            ->where('p.stEstado = 1')
            ->where("($subSql) = ?", $idOrgao)
            ->order('u.usu_nome ASC')
            ;

        // retornando os registros conforme objeto select
        return $db->fetchAll($sql);
    }

    /**
     * buscarHistoricoAnaliseVisual
     *
     * @param mixed $idOrgao
     * @param bool $idTecnico
     * @param bool $situacao
     * @param bool $dtInicio
     * @param bool $dtFim
     * @access public
     * @return void
     */
    public function buscarHistoricoAnaliseVisual($idOrgao,$idTecnico=null,$situacao=null,$dtInicio=null,$dtFim=null)
    {
        $db = Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB :: FETCH_OBJ);

        $p = [
            'p.idPreProjeto',
            'p.NomeProjeto',
        ];

        //replace função: SAC.dbo.fnNomeTecnicoMinc(a.idTecnico)
        $tecnico = $db->select()
            ->from(['Usuarios'], 'usu_nome', 'tabelas.dbo')
            ->where('usu_codigo = a.idTecnico')
            ;

        // Replace da função: SAC.dbo.fnIdOrgaoSuperiorAnalista(a.idTecnico)
        $subSql = $db->select()
            ->from(['vwUsuariosOrgaosGrupos'], 'org_superior', 'tabelas.dbo')
            ->where('usu_codigo = a.idTecnico')
            ->where('sis_codigo = 21')
            ->where('gru_codigo = 92')
            ->group('org_superior');

        $a =[
            'a.idTecnico',
            "($tecnico) as Tecnico",
            'a.DtEnvio',
            new Zend_Db_Expr('CONVERT(CHAR(20),a.DtAvaliacao, 120) AS DtAvaliacao'),
            'a.idAvaliacaoProposta',
            'a.ConformidadeOK',
            'a.stEstado',
            "($subSql) as idOrgao"
        ];


        $sql = $db->select()
            ->from(['a' => 'tbAvaliacaoProposta'], $a, 'SAC.dbo')
            ->join(['p' => 'PreProjeto'], 'p.idPreProjeto = a.idProjeto', $p, 'SAC.dbo')
            ->where('ConformidadeOK<>1')
            ->where('p.stEstado = 1')
            ->where("($subSql) = ?", $idOrgao)
            ->order('p.idPreProjeto DESC')
            ->order('DtAvaliacao ASC')
            ->limit(20)
            ;

        if($idTecnico){
            $sql->where('a.idTecnico = ?', $idTecnico);
        }
        if($situacao){
            $sql->where('a.ConformidadeOK = ?', $situacao);
        }

        if($dtInicio){
            if($dtFim){
                $sql->where('a.DtAvaliacao > ? 00:00:00', $dtInicio);
                $sql->where('a.DtAvaliacao < ? 23:59:59', $dtFim);
            }else{
                $sql->where('a.DtAvaliacao > ? 00:00:00', $dtInicio);
                $sql->where('a.DtAvaliacao < ? 23:59:59', $dtInicio);
            }
        }

        // retornando os registros conforme objeto select
        return $db->fetchAll($sql);
    }

    /**
     * buscarAvaliacaoHistoricoAnaliseVisual
     *
     * @param mixed $idAvaliacao
     * @access public
     * @return void
     * @deprecated
     */
    public function buscarAvaliacaoHistoricoAnaliseVisual($idAvaliacao)
    {
        $db = $this->getAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        $sql = $db->select()
            ->from(['a' => 'tbAvaliacaoProposta'], ['a.Avaliacao'], $this->_schema)
            ->where('a.idAvaliacaoProposta = ?', $idAvaliacao);

        return $db->fetchAll($sql);
    }

    /**
     * Retorna registros do banco de dados
     * @param array $where - array com dados where no formato "nome_coluna_1"=>"valor_1","nome_coluna_2"=>"valor_2"
     * @param array $order - array com orders no formado "coluna_1 desc","coluna_2"...
     * @param int $tamanho - numero de registros que deve retornar
     * @param int $inicio - offset
     * @return Zend_Db_Table_Rowset_Abstract
     * @deprecated
     */
    public function buscarPropostaAnaliseVisualTecnico($where=array(), $order=array(), $tamanho=-1, $inicio=-1)
    {
        $db = $this->getAdapter();
        $vw = [
            'idProjeto',
            'NomeProjeto',
            'Tecnico',
            'idOrgao',
            new Zend_Db_Expr('CONVERT(CHAR(20),vw.DtEnvio, 120) AS DtEnvio'),
            'ConformidadeOK',
            new Zend_Db_Expr('CONVERT(CHAR(20),vw.DtMovimentacao, 120) AS DtMovimentacao'),
            'QtdeDias'
        ];

        $sql = $db->select()
            ->from(['vw' => 'vwAnaliseVisualPorTecnico'], $vw, $this->_schema);

        ($order) ? $sql->order($order) : null;

        foreach ($where as $coluna=>$valor) {
            $sql->where($coluna.' = ?', $valor);
        }

        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        return $db->fetchAll($sql);
    }

    /**
     * Retorna registros do banco de dados
     * @param array $where - array com dados where no formato "nome_coluna_1"=>"valor_1","nome_coluna_2"=>"valor_2"
     * @param array $order - array com orders no formado "coluna_1 desc","coluna_2"...
     * @param int $tamanho - numero de registros que deve retornar
     * @param int $inicio - offset
     * @return Zend_Db_Table_Rowset_Abstract
     */
    public function buscarPropostaAnaliseDocumentalTecnico($where=array(), $order=array(), $tamanho=-1, $inicio=-1)
    {
        $db = $this->getAdapter();
        $db->setFetchMode(Zend_DB :: FETCH_OBJ);

        //replace função: SAC.dbo.fnNomeTecnicoMinc(a.idTecnico)
        $tecnico = $db->select()
            ->from(['Usuarios'], 'usu_nome', 'tabelas.dbo')
            ->where('usu_codigo = a.idTecnico')
            ;

        // Replace da função: SAC.dbo.fnIdOrgaoSuperiorAnalista(a.idTecnico)
        $orgao = $db->select()
            ->from(['vwUsuariosOrgaosGrupos'], 'org_superior', 'tabelas.dbo')
            ->where('usu_codigo = a.idTecnico')
            ->where('sis_codigo = 21')
            ->where('gru_codigo = 92')
            ->group('org_superior');

        //Replace da função: sac.dbo.fnDtUltimaDiligenciaDocumental(a.idProjeto)
        $diligencia = $db->select()->from(['tbMovimentacao'], "max(DtMovimentacao)")->where('Movimentacao = 97')
            ->where('idProjeto = a.idProjeto')
            ;

        $a = [
            'a.idProjeto',
            "($tecnico) AS Tecnico",
            "($orgao) as idOrgao",
            new Zend_Db_Expr('CONVERT(CHAR(20), ($diligencia), 120) AS DtUltima')
        ];

        $sql = $db->select()
            ->from(['a' => 'tbAvaliacaoProposta'], $a,'sac.dbo')
            ->join(['p' => 'PreProjeto'], 'a.idProjeto=p.idPreProjeto', ['p.NomeProjeto'], 'sac.dbo')
            ->join(['d' => 'vwDocumentosPendentes'], 'a.idProjeto = d.idProjeto',['CodigoDocumento'], 'sac.dbo')
            ->join(['de'=>'DocumentosExigidos'], 'd.CodigoDocumento = de.Codigo', ['Descricao as Documento'], 'sac.dbo')
            ;

        // adicionando clausulas where
        foreach ($where as $coluna=>$valor)
        {
            $sql->where($coluna.'?',$valor);
        }

        $sql->order($order);

        // retornando os registros conforme objeto select
        return $db->fetchAll($sql);
    }

    /**
     * Retorna registros do banco de dados
     * @param array $where - array com dados where no formato "nome_coluna_1"=>"valor_1","nome_coluna_2"=>"valor_2"
     * @param array $order - array com orders no formado "coluna_1 desc","coluna_2"...
     * @param int $tamanho - numero de registros que deve retornar
     * @param int $inicio - offset
     * @return Zend_Db_Table_Rowset_Abstract
     * @deprecated
     */
    public function buscarPropostaAnaliseFinal($where=array(), $order=array(), $tamanho=-1, $inicio=-1)
    {
        $db = $this->getAdapter();
        $vw = [
            'idPreProjeto',
            'NomeProjeto',
            'Tecnico',
            'DtEnvio',
            'CONVERT(CHAR(20),DtMovimentacao, 120) AS DtMovimentacao',
            'DtAvaliacao',
            'Dias',
            'idOrgao',
            'ConformidadeOK',
            'QtdeDiasAguardandoEnvio'
        ];

        $sql = $db->select()
            ->from(['vw' => 'vwPropostaProjetoSecretaria'], $vw, $this->_schema);

        // adicionando clausulas where
        foreach ($where as $coluna => $valor) {
            $sql->where($coluna.' = ?', $valor);
        }

        // adicionando clausulas order
        ($order) ? $sql->order($order) : null;

        $db->setFetchMode(Zend_DB :: FETCH_OBJ);

        // retornando os registros conforme objeto select
        return $db->fetchAll($sql);
    }

    /**
     * buscarConformidadeVisualTecnico
     *
     * @param mixed $idPreProjeto
     * @access public
     * @return void
     */
    public function buscarConformidadeVisualTecnico($idPreProjeto)
    {
        $db = $this->getAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        $sql = $db->select()
            ->from(['vw' => 'vwConformidadeVisualTecnico'], 'tecnico', $this->_schema)
            ->where('idprojeto = ?', $idPreProjeto )
            ->query();

        // retornando os registros conforme objeto select
        return $sql->fetchAll();
    }

    /**
     * Retorna registros do banco de dados
     * @param array $where - array com dados where no formato "nome_coluna_1"=>"valor_1","nome_coluna_2"=>"valor_2"
     * @param array $order - array com orders no formado "coluna_1 desc","coluna_2"...
     * @param int $tamanho - numero de registros que deve retornar
     * @param int $inicio - offset
     * @return Zend_Db_Table_Rowset_Abstract
     */
    public function buscarVisual($idUsuario = null, $order=array(), $tamanho=-1, $inicio=-1)
    {
        $db = $this->getAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        $p =[
            'p.stTipoDemanda AS TipoDemanda',
            'p.idPreProjeto AS idProjeto',
            'p.NomeProjeto AS NomeProposta',
            'p.idAgente',
        ];

        //replace função: SAC.dbo.fnNomeTecnicoMinc(a.idTecnico)
        $tecnico = $db->select()
            ->from(['Usuarios'], 'usu_nome', 'tabelas.dbo')
            ->where('usu_codigo = x.idTecnico')
            ;

        // Replace da função: SAC.dbo.fnIdOrgaoSuperiorAnalista(a.idTecnico)
        $orgao = $db->select()
            ->from(['vwUsuariosOrgaosGrupos'], 'org_superior', 'tabelas.dbo')
            ->where('usu_codigo = x.idTecnico')
            ->where('sis_codigo = 21')
            ->where('gru_codigo = 92')
            ->group('org_superior');

        $x =[
            'x.idTecnico AS idUsuario',
            "($tecnico) AS Tecnico",
            "($orgao) AS idSecretaria",
            new Zend_Db_Expr('CONVERT(CHAR(20),x.DtAvaliacao, 120) AS DtAdmissibilidade'),
            new Zend_Db_Expr('DATEDIFF(d, x.DtAvaliacao, GETDATE()) AS diasCorridos'),
            'x.idAvaliacaoProposta',
        ];

        $m =[
            'm.idMovimentacao',
            'm.Movimentacao AS CodSituacao',
        ];

        $subSql = $db->select()->from('vwRedistribuirAnaliseVisual', ['idProjeto'], 'SAC.dbo');

        if ($idUsuario !== null) {
            $subSql->where("($orgao) = ?", $idUsuario);
        }

        $subSql2 = $db->select()
            ->from(['u' =>'Projetos'],
                ['IdPRONAC', 'AnoProjeto', 'Sequencial', 'UfProjeto', 'Area', 'Segmento', 'Mecanismo', 'NomeProjeto', 'Processo',
                'CgcCpf', 'Situacao', 'DtProtocolo', 'DtAnalise', 'Modalidade', 'Orgao', 'OrgaoOrigem', 'DtSaida', 'DtRetorno', 'UnidadeAnalise',
                'Analista', 'DtSituacao', 'ResumoProjeto', 'ProvidenciaTomada', 'Localizacao', 'DtInicioExecucao', 'DtFimExecucao', 'SolicitadoUfir',
                'SolicitadoReal', 'SolicitadoCusteioUfir', 'SolicitadoCusteioReal', 'SolicitadoCapitalUfir', 'SolicitadoCapitalReal', 'Logon', 'idProjeto'],
                'SAC.dbo')
            ->where('p.idPreProjeto = idProjeto')
            ->limit(1);

        $sql = $db->select()
            ->from(['p' => 'PreProjeto'], null, 'SAC.dbo')
            ->join(['m' => 'tbMovimentacao'], 'p.idPreProjeto = m.idProjeto AND m.stEstado = 0', $m, 'SAC.dbo')
            ->join(['x' => 'tbAvaliacaoProposta'], 'p.idPreProjeto = x.idProjeto AND x.stEstado = 0', $x, 'SAC.dbo')
            ->join(['a' => 'Agentes'], 'p.idAgente = a.idAgente', ['a.CNPJCPF'], 'AGENTES.dbo')
            ->join(['y' => 'Verificacao'], 'm.Movimentacao = y.idVerificacao', ['y.Descricao AS Situacao'], 'SAC.dbo')
            ->where('p.stEstado = 1')
            ->where('m.Movimentacao NOT IN(96,128)')
            ->where(new Zend_Db_Expr("p.idPreProjeto IN( $subSql )"))
            ->where(new Zend_Db_Expr("NOT EXISTS ( $subSql2)"))
            ->order($order)
            ;

        return $db->fetchAll($sql);
    }

    /**
     * Retorna registros do banco de dados
     * @param array $where - array com dados where no formato "nome_coluna_1"=>"valor_1","nome_coluna_2"=>"valor_2"
     * @param array $order - array com orders no formado "coluna_1 desc","coluna_2"...
     * @param int $tamanho - numero de registros que deve retornar
     * @param int $inicio - offset
     * @return Zend_Db_Table_Rowset_Abstract
     */
    public function buscarDocumental($idUsuario = null, $order=array(), $tamanho=-1, $inicio=-1)
    {
        $db = $this->getAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        $p =[
            'p.idPreProjeto AS idProjeto',
            'p.NomeProjeto AS NomeProposta',
            'p.idAgente',
            'p.stTipoDemanda AS TipoDemanda'
        ];

        //replace função: SAC.dbo.fnNomeTecnicoMinc(a.idTecnico)
        $tecnico = $db->select()
            ->from(['Usuarios'], 'usu_nome', 'tabelas.dbo')
            ->where('usu_codigo = x.idTecnico')
            ;

        // Replace da função: SAC.dbo.fnIdOrgaoSuperiorAnalista(a.idTecnico)
        $orgao = $db->select()
            ->from(['vwUsuariosOrgaosGrupos'], 'org_superior', 'tabelas.dbo')
            ->where('usu_codigo = x.idTecnico')
            ->where('sis_codigo = 21')
            ->where('gru_codigo = 92')
            ->group('org_superior');

        // Replace da função: SAC.dbo.fnIdOrgaoSuperiorAnalista(a.idTecnico)
        $orgaoSub = $db->select()
            ->from(['vwUsuariosOrgaosGrupos'], 'org_superior', 'tabelas.dbo')
            ->where('usu_codigo = idTecnico')
            ->where('sis_codigo = 21')
            ->where('gru_codigo = 92')
            ->group('org_superior');

        $x = [
            'x.idTecnico AS idUsuario',
            "($tecnico) AS Tecnico",
            "($orgao) AS idSecretaria",
            new Zend_Db_Expr('CONVERT(CHAR(20),x.DtAvaliacao, 120) AS DtAdmissibilidade'),
            new Zend_Db_Expr('DATEDIFF(d, x.DtAvaliacao, GETDATE()) AS diasCorridos'),
            'x.idAvaliacaoProposta',
        ];

        $m = [
            'm.idMovimentacao',
            'm.Movimentacao AS CodSituacao',
        ];

        $subSql = $db->select()
            ->from(['u' => 'Projetos'],[
            'IdPRONAC', 'AnoProjeto', 'Sequencial', 'UfProjeto', 'Area', 'Segmento', 'Mecanismo', 'NomeProjeto', 'Processo', 'CgcCpf', 'Situacao',
                    'DtProtocolo', 'DtAnalise', 'Modalidade', 'Orgao', 'OrgaoOrigem', 'DtSaida', 'DtRetorno', 'UnidadeAnalise', 'Analista', 'DtSituacao', 'ResumoProjeto',
                    'ProvidenciaTomada', 'Localizacao', 'DtInicioExecucao', 'DtFimExecucao', 'SolicitadoUfir', 'SolicitadoReal', 'SolicitadoCusteioUfir', 'SolicitadoCusteioReal',
                    'SolicitadoCapitalUfir', 'SolicitadoCapitalReal', 'Logon', 'idProjeto'
            ] ,'SAC.dbo')
            ->where('p.idPreProjeto = idProjeto')
            ->limit(1)
            ;

        $subSql2 = $db->select()
            ->from(['vwConformidadeDocumentalTecnico'], ['idProjeto'], 'SAC.dbo');

        if($idUsuario !== null){
            $subSql2->where(new Zend_Db_Expr("($orgaoSub) = ?"), $idUsuario);
        }

        $sql = $db->select()
            ->from(['p' => 'PreProjeto'], $p,'SAC.dbo')
            ->join(['m' => 'tbMovimentacao'], 'p.idPreProjeto = m.idProjeto AND m.stEstado = 0', $m, 'SAC.dbo')
            ->join(['x' => 'tbAvaliacaoProposta'], 'p.idPreProjeto = x.idProjeto AND x.stEstado = 0', $x, 'SAC.dbo')
            ->join(['a' => 'Agentes'], 'p.idPreProjeto = x.idProjeto AND x.stEstado = 0', 'a.CNPJCPF', 'AGENTES.dbo')
            ->join(['y' => 'Verificacao'], 'm.Movimentacao = y.idVerificacao', ['y.Descricao AS Situacao'], 'SAC.dbo')
            ->where('p.stEstado = 1')
            ->where('m.Movimentacao NOT IN(96,128)')
            ->where( new Zend_Db_Expr("NOT EXISTS ($subSql)"))
            ->where( new Zend_Db_Expr("p.idPreProjeto IN($subSql2)"))
            ->order($order)
        ;

        return $db->fetchAll($sql);
    }

    /**
     * transformarPropostaEmProjeto
     *
     * @param mixed $idPreProjeto
     * @param mixed $cnpjcpf
     * @param mixed $idOrgao
     * @param mixed $idUsuario
     * @param mixed $nrProcesso
     * @access public
     * @return void
     * @todo padrão ORM
     */
    public function transformarPropostaEmProjeto($idPreProjeto, $cnpjcpf, $idOrgao, $idUsuario, $nrProcesso)
    {
        $sql = "EXEC SAC.dbo.paPropostaParaProjeto {$idPreProjeto}, '{$cnpjcpf}', {$idOrgao}, {$idUsuario}, {$nrProcesso}";
        $db = Zend_Registry :: get('db');
        $db->setFetchMode(Zend_DB :: FETCH_OBJ);

        return $db->fetchAll($sql);
    }

    /**
     * buscaragencia
     *
     * @param mixed $codigo
     * @access public
     * @return void
     */
    public function buscaragencia($codigo)
    {
        $db = $this->getAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);
        $sql = $db->select()
            ->from(['b' => 'bancoagencia'], 'agencia', $this->_schema)
            ->where('b.agencia = ?', $codigo)
            ->query();

        return $sql->fetchAll();
    }

    /**
     * unidadeAnaliseProposta
     *
     * @param mixed $idPreProjeto
     * @access public
     * @return void
     */
    public function unidadeAnaliseProposta($idPreProjeto)
    {
        $slct = $this->select();
        $slct->setIntegrityCheck(false);
        $slct->from(array("p"=>$this->_name), array("*"));
        $slct->joinInner(array("ap"=>"tbAvaliacaoProposta"), "p.idPreProjeto = ap.idProjeto", array("*"), "SAC.dbo");

        $slct->where("ap.stEstado = ?", 0);
        $slct->where("p.idPreProjeto = ?", $idPreProjeto);

        return $this->fetchAll($slct);
    }

    /**
     * orgaoSecretaria
     *
     * @param mixed $idTecnico
     * @access public
     * @return void
     */
    public function orgaoSecretaria($idTecnico)
    {
        $db = $this->getAdapter();
        $db->setFetchMode(Zend_DB :: FETCH_OBJ);

        $sql = $this->select()
            ->setIntegrityCheck(false)
            ->from(['vwUsuariosOrgaosGrupos'], ['org_superior AS idOrgao'], 'tabelas.dbo')
            ->where('usu_codigo = ?', 4676)
            ->where('sis_codigo=21')
            ->where('gru_codigo = 92')
            ->order('org_superior')
            ;

        $orgao = $db->fetchAll($sql);

        $sql = $this->select()
            ->setIntegrityCheck(false)
            ->from(['Orgaos'], null, 'tabelas.dbo')
            ->join(['Pessoa_Identificacoes'], 'pid_pessoa = org_pessoa', ['pid_identificacao'],'Tabelas.dbo')
            ->where('pid_meta_dado = 1')
            ->where('pid_sequencia = 1')
            ->where('org_codigo = ?', 160)
            ;

        $identificacao = $db->fetchAll($sql);

        $orgao[0]->secretaria = $identificacao[0]->pid_identificacao;

        return $orgao;
    }

    /**
     * propostastransformadas
     *
     * @param mixed $idAgente
     * @access public
     * @return void
     */
    public function propostastransformadas($idAgente)
    {
        $slct = $this->select();
        $slct->setIntegrityCheck(false);
        $slct->from(array("pj"=>$this->_name), array("*"));
        $slct->joinInner(array("p"=>"Projetos"), "pj.idPreProjeto = p.idProjeto", array("*"), "SAC.dbo");

        $slct->where("pj.idAgente = ?", $idAgente);
        return $this->fetchAll($slct);
    }

    /**
     * propostasPorEdital
     *
     * @param bool $where
     * @param bool $order
     * @param mixed $tamanho
     * @param mixed $inicio
     * @param bool $count
     * @access public
     * @return void
     */
    public function propostasPorEdital($where=array(), $order=array(), $tamanho=-1, $inicio=-1, $count=false){
        $slct = $this->select();
        $slct->setIntegrityCheck(false);
        $slct->from(
                    array("p"=>$this->_name),
                    array("idProjeto"=>"idPreProjeto", "NomeProposta"=>"NomeProjeto", "idAgente", "DtCadastro"=>"CONVERT(CHAR(20),p.dtAceite, 120)"),
                    "SAC.dbo"
                    );
        $slct->joinLeft(
                        array("m"=>"tbMovimentacao"),
                        "p.idPreProjeto = m.idProjeto AND m.stEstado = 0",
                        array("idMovimentacao", "CodSituacao"=>"m.Movimentacao", "DtMovimentacao"=>"CONVERT(CHAR(20),m.DtMovimentacao, 120)"),
                        "SAC.dbo"
                        );
        $slct->joinLeft(
                        array("x"=>"tbAvaliacaoProposta"),
                        "p.idPreProjeto = x.idProjeto AND x.stEstado = 0",
                        array("ConformidadeOK"),
                        "SAC.dbo"
                        );
        $slct->joinLeft(
                        array("x1"=>"tbAvaliacaoProposta"),
                        "p.idPreProjeto = x1.idProjeto",
                        array("DtEnvioMinC"=>"CONVERT(CHAR(20),x1.DtEnvio , 120)"),
                        "SAC.dbo"
                        );
        $slct->joinLeft(
                        array("mv"=>"tbMovimentacao"),
                        "p.idPreProjeto = mv.idProjeto and mv.stEstado = 0",
                        array("stMovimentacao"=>"movimentacao"),
                        "SAC.dbo"
                        );
        $slct->joinLeft(
                        array("vr"=>"Verificacao"),
                        "mv.movimentacao = vr.idVerificacao and vr.idTipo = 4",
                        array("Movimentacao"=>"Descricao"),
                        "SAC.dbo"
                        );
        $slct->joinInner(
                        array("a"=>"Agentes"),
                        "p.idAgente = a.idAgente",
                        array("CNPJCPF"),
                        "AGENTES.dbo"
                        );
        $slct->joinInner(
                        array("n"=>"Nomes"),
                        "p.idAgente = n.idAgente",
                        array("NomeAgente"=>"Descricao"),
                        "AGENTES.dbo"
                        );
        $slct->joinInner(
                        array("e"=>"Edital"),
                        "e.idEdital = p.idEdital",
                        array("idOrgao"),
                        "SAC.dbo"
                        );
        $slct->joinInner(
                        array("fd"=>"tbFormDocumento"),
                        "fd.idEdital = p.idEdital AND idClassificaDocumento NOT IN (23,24,25)",
                        array("Edital"=>"nmFormDocumento", "idEdital"),
                        "BDCORPORATIVO.scQuiz"
                        );
        $slct->joinInner(
                        array("cl"=>"tbClassificaDocumento"),
                        "cl.idClassificaDocumento = fd.idClassificaDocumento",
                        array("idClassificaDocumento", "dsClassificaDocumento"),
                        "BDCORPORATIVO.scSAC"
                        );
        $slct->joinInner(
                        array("o"=>"Orgaos"),
                        "o.Codigo = e.idEdital",
                        array("SiglaOrgao"=>"Sigla"),
                        "SAC.dbo"
                        );
        $slct->joinInner(
                        array("ab"=>"Abrangencia"),
                        "p.idPreProjeto = ab.idProjeto AND ab.stAbrangencia = 1",
                        array(),
                        "SAC.dbo"
                        );
        $slct->joinInner(
                        array("uf"=>"UF"),
                        "uf.idUF = ab.idUF",
                        array("idUF", "SiglaUF"=>"Sigla", "NomeUF"=>"Descricao", "Regiao"),
                        "AGENTES.dbo"
                        );
        $slct->joinInner(
                        array("mu"=>"Municipios"),
                        "mu.idMunicipioIBGE = ab.idMunicipioIBGE",
                        array("NomeMunicipio"=>"Descricao"),
                        "AGENTES.dbo"
                        );
        $slct->joinInner(
                        array("vr2"=>"Verificacao"),
                        "e.cdTipoFundo = vr2.idVerificacao and vr2.idTipo = 15",
                        array("FundoNome"=>"Descricao", "idFundo"=>"idVerificacao"),
                        "SAC.dbo"
                        );

        if($count){
            $slct2 = $this->select();
            $slct2->setIntegrityCheck(false);
            $slct2->from(
                    array('p' => $this->_name),
                    array("total"=>"count(*)")
            );
            $slct2->joinLeft(
                            array("m"=>"tbMovimentacao"),
                            "p.idPreProjeto = m.idProjeto AND m.stEstado = 0",
                            array(),
                            "SAC.dbo"
                            );
            $slct2->joinLeft(
                            array("x"=>"tbAvaliacaoProposta"),
                            "p.idPreProjeto = x.idProjeto AND x.stEstado = 0",
                            array(),
                            "SAC.dbo"
                            );
            $slct2->joinLeft(
                            array("x1"=>"tbAvaliacaoProposta"),
                            "p.idPreProjeto = x1.idProjeto",
                            array(),
                            "SAC.dbo"
                            );
            $slct2->joinLeft(
                            array("mv"=>"tbMovimentacao"),
                            "p.idPreProjeto = mv.idProjeto and mv.stEstado = 0",
                            array(),
                            "SAC.dbo"
                            );
            $slct2->joinLeft(
                            array("vr"=>"Verificacao"),
                            "mv.movimentacao = vr.idVerificacao and vr.idTipo = 4",
                            array(),
                            "SAC.dbo"
                            );
            $slct2->joinInner(
                            array("a"=>"Agentes"),
                            "p.idAgente = a.idAgente",
                            array(),
                            "AGENTES.dbo"
                            );
            $slct2->joinInner(
                            array("n"=>"Nomes"),
                            "p.idAgente = n.idAgente",
                            array(),
                            "AGENTES.dbo"
                            );
            $slct2->joinInner(
                            array("e"=>"Edital"),
                            "e.idEdital = p.idEdital",
                            array(),
                            "SAC.dbo"
                            );
            $slct2->joinInner(
                            array("fd"=>"tbFormDocumento"),
                            "fd.idEdital = p.idEdital AND idClassificaDocumento NOT IN (23,24,25)",
                            array(),
                            "BDCORPORATIVO.scQuiz"
                            );
            $slct2->joinInner(
                            array("cl"=>"tbClassificaDocumento"),
                            "cl.idClassificaDocumento = fd.idClassificaDocumento",
                            array(),
                            "BDCORPORATIVO.scSAC"
                            );
            $slct2->joinInner(
                            array("o"=>"Orgaos"),
                            "o.Codigo = e.idEdital",
                            array(),
                            "SAC.dbo"
                            );
            $slct2->joinInner(
                            array("ab"=>"Abrangencia"),
                            "p.idPreProjeto = ab.idProjeto AND ab.stAbrangencia = 1",
                            array(),
                            "SAC.dbo"
                            );
            $slct2->joinInner(
                            array("uf"=>"UF"),
                            "uf.idUF = ab.idUF",
                            array(),
                            "AGENTES.dbo"
                            );
            $slct2->joinInner(
                            array("mu"=>"Municipios"),
                            "mu.idMunicipioIBGE = ab.idMunicipioIBGE",
                            array(),
                            "AGENTES.dbo"
                            );
            $slct2->joinInner(
                            array("vr2"=>"Verificacao"),
                            "e.cdTipoFundo = vr2.idVerificacao and vr2.idTipo = 15",
                            array(),
                            "SAC.dbo"
                            );

            //adiciona quantos filtros foram enviados
            foreach ($where as $coluna => $valor) {
                $slct2->where($coluna, $valor);
            }
            $rs = $this->fetchAll($slct2)->current();
            if($rs){ return $rs->total; }else{ return 0; }
        }

        //adiciona quantos filtros foram enviados
        foreach ($where as $coluna => $valor) {
            $slct->where($coluna, $valor);
        }

        //adicionando linha order ao select
        $slct->order($order);

        // paginacao
        if ($tamanho > -1) {
            $tmpInicio = 0;
            if ($inicio > -1) {
                $tmpInicio = $inicio;
            }
            $slct->limit($tamanho, $tmpInicio);
        }
        return $this->fetchAll($slct);
    }

    /**
     * checklistEnvioProposta - Checklist para enviar proposta para Minc
     *
     * @param mixed $idPreProjeto
     * @access public
     * @return void
     * @author wouerner <wouerner@gmail.com>
     */
    public function checklistEnvioProposta($idPreProjeto)
    {
        $validacao = new stdClass();
        $listaValidacao = [];

        $db = $this->getAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        $sql = $db->select()
            ->from(['PreProjeto'], 'idAgente', 'sac.dbo')
            ->where('idPreProjeto = ?', $idPreProjeto);

        $idAgente = $db->fetchAll($sql)[0]->idAgente;

        $sql = $db->select()
            ->from(['tbMovimentacao'], '*', 'sac.dbo')
            ->where('idProjeto = ?', $idPreProjeto)
            ->where('Movimentacao <> 95')
            ->where('stEstado = 0')
            ->limit(1);

        $movimentacao = $db->fetchAll($sql);

        if (!empty( $movimentacao )) {
            $validacao->Descricao = '<font color=blue><b>A PROPOSTA CULTURAL ENCONTRA-SE NO MINISTÉRIO DA CULTURA.</b></font>';
            $validacao->Observacao = '';
            $listaValidacao[] =  clone($validacao);
        } else {

            $sql = $db->select()
                ->from(['tbAvaliacaoProposta'], '*', 'sac.dbo')
                ->where('idProjeto = ?', $idPreProjeto)
                ;

            $avaliacaoProposta = $db->fetchAll($sql);
            if(( date('m') == 12 || date('m') == 1 ) && empty($avaliacaoProposta)) {
                $validacao->Descricao = 'Conforme Art 9º da Instrução Normativa nº 1, de 24 de junho de 2013, nenhuma proposta poder&aacute; ser enviada ao MinC nos meses de DEZEMBRO e JANEIRO!';
                $validacao->Observacao = '<font color=red><b>IMPEDIMENTO</b></font>';
                $listaValidacao[] =  clone($validacao);
            } else {
                $sql = $db->select()
                    ->from(['v' => 'vCadastrarProponente'], 'v.*', 'sac.dbo')
                    ->join(['p' => 'PreProjeto'], 'v.idAgente = p.idAgente', null, 'sac.dbo')
                    ->where('idpreprojeto = ?', $idPreProjeto)
                    ->where('Correspondencia = 1')
                    ->limit(1)
                    ;
                $vCadastrarProponente = $db->fetchAll($sql);

                //VERIFICAR AS INFORMAÇÕES DO PROPONENTE
                if (empty($vCadastrarProponente)) {
                    $validacao->Descricao = 'Dados cadastrais do proponente inexistente ou não h&aacute; endereço para correspondência selecionado.';
                    $validacao->Observacao = 'PENDENTE';
                    $listaValidacao[] =  clone($validacao);
                } else {
                    $validacao->Descricao = 'Dados cadastrais do proponente lan&ccedil;ado.';
                    $validacao->Observacao = 'OK';
                    $listaValidacao[] =  clone($validacao);
                }
                    //VERIFICAR A REGULARIDADE DO PROPONENTE
                    $sql = $db->select()
                        ->from(['v' => 'vCadastrarProponente'], 'v.*', 'sac.dbo')
                        ->join(['p' => 'PreProjeto'], 'v.idAgente = p.idAgente', null, 'sac.dbo')
                        ->join(['i' => 'Inabilitado '], 'v.CnpjCpf=i.CgcCpf', null, 'sac.dbo')
                        ->where('idpreprojeto = ?', $idPreProjeto)
                        ->where('v.CnpjCpf=i.CgcCpf')
                        ->where("Habilitado='N'")
                        ->limit(1)
                        ;

                    $regularidadeProponente = $db->fetchAll($sql);
                    if (!empty($regularidadeProponente)) {
                        $validacao->Descricao ='Proponente em situa&ccedil;&atilde;o IRREGULAR no Minist&eacute;rio da Cultura.';
                        $validacao->Observacao =  'PENDENTE';
                        $listaValidacao[] =  clone($validacao);
                    } else {
                        $validacao->Descricao ='Proponente em situa&ccedil;&atilde;o REGULAR no Minist&eacute;rio da Cultura.';
                        $validacao->Observacao =  'OK';
                        $listaValidacao[] =  clone($validacao);
                    }

                   //-- VERIFICAR SE HÁ OS EMAILS DO PROPONENTE CADASTRADOS
                    $sql = $db->select()
                        ->from(['v' => 'Internet'], 'v.*', 'agentes.dbo')
                        ->join(['p' => 'PreProjeto'], 'v.idAgente=p.idAgente', null, 'sac.dbo')
                        ->where('idpreprojeto= ?', $idPreProjeto)
                        ->where('Status=1')
                        ->limit(1)
                        ;
                    $verificarEmail = $db->fetchAll($sql);
                    if (empty($verificarEmail)){
                        $validacao->Descricao ='E-mail do proponente inexistente';
                        $validacao->Observacao =  'PENDENTE';
                        $listaValidacao[] =  clone($validacao);
                    } else {
                        $validacao->Descricao ='E-mail do proponente cadastrado.';
                        $validacao->Observacao =  'OK';
                        $listaValidacao[] =  clone($validacao);
                    }

                    //-- NO CASO DE PESSOA FÍSICA, VERIFICAR O LANÇAMENTO DA DATA DE NASCIMENTO
                    $sql = $db->select()
                        ->from(['v' => 'Agentes'], 'TipoPessoa', 'agentes.dbo')
                        ->where('idAgente = ?', $idAgente)
                        ;

                    $tipoPessoa = $db->fetchAll($sql)[0]->TipoPessoa;
                    if ($tipoPessoa == 0) {

                        $sql = $db->select()
                            ->from(['tbAgenteFisico'], 'DtNascimento', 'agentes.dbo')
                            ->where('idagente = ?', $idAgente)
                            ;

                        $dataNasc = $db->fetchAll($sql);

                        if (empty($dataNasc)) {
                            $validacao->Descricao ='Data de Nascimento inexistente.';
                            $validacao->Observacao =  'PENDENTE';
                            $listaValidacao[] = clone($validacao);
                        } else {
                            $validacao->Descricao ='Data de Nascimento cadastrada.';
                            $validacao->Observacao =  'OK';
                            $listaValidacao[] =  clone($validacao);
                        }
                    }

                     //-- NO CASO DE PESSOA JURÍDICA, VERIFICAR O LANÇAMENTO DA NATUREZA DO PROPONENTE
                    if ($tipoPessoa == 1) {
                        $sql = $db->select()
                            ->from(['n' => 'Natureza'], '*', 'agentes.dbo')
                            ->join(['p' => 'PreProjeto'], 'n.idAgente=p.idAgente', '*', 'sac.dbo')
                            ->where('idpreprojeto = ?', $idPreProjeto)
                            ->limit(1)
                            ;

                        $natureza = $db->fetchAll($sql);
                        if(empty($natureza)) {
                            $validacao->Descricao = 'Natureza do proponente.';
                            $validacao->Observacao =  'PENDENTE';
                            $listaValidacao[] =  clone($validacao);
                        } else {
                            $validacao->Descricao = 'Natureza do proponente cadastrada.';
                            $validacao->Observacao =  'OK';
                            $listaValidacao[] =  clone($validacao);
                        }

                        //-- VERIFICAR SE HÁ DIRIGENTE CADASTRADO
                        $sql = $db->select()
                            ->from(['v' => 'vCadastrarDirigente'], '*', 'sac.dbo')
                            ->join(['p' => 'PreProjeto'], 'v.idVinculoPrincipal=p.idAgente', '*', 'sac.dbo')
                            ->where('idPreProjeto= ?', $idPreProjeto)
                            ;

                        $dirigenteCadastrado = $db->fetchAll($sql);
                        if (empty($dirigenteCadastrado)) {
                            $validacao->Descricao = 'Cadastro de Dirigente.';
                            $validacao->Observacao = 'PENDENTE';
                            $listaValidacao[] =  clone($validacao);
                        } else {
                            $validacao->Descricao ='Cadastro de Dirigente lançado.'  ;
                            $validacao->Observacao =  'OK';
                            $listaValidacao[] =  clone($validacao);
                        }
                    }

                    //-- VERIFICAR SE O LOCAL DE REALIZAÇÃO ESTÁ CADASTRADO
     //IF NOT EXISTS(SELECT TOP 1 * FROM Abrangencia WHERE idProjeto = @idProjeto)

                    $sql = $db->select()
                        ->from(['Abrangencia'], '*', 'sac.dbo')
                        ->where('idProjeto = ?', $idPreProjeto)
                        ->limit(1);

                    $local = $db->fetchAll($sql);

                    if (empty($local)) {
                        $validacao->Descricao = 'O Local de realiza&ccedil;&atilde;o da proposta n&atilde;o foi preenchido.';
                        $validacao->Observacao = 'PENDENTE';
                        $listaValidacao[] =  clone($validacao);
                    } else {
                        $validacao->Descricao = 'Local de realizaç&atilde;o da proposta cadastrada.';
                        $validacao->Observacao = 'OK';
                        $listaValidacao[] =  clone($validacao);
                    }

                    //-- VERIFICAR SE O PLANO DE DIVULGAÇÃO ESTÁ PREENCHIDO
                    $sql = $db->select()
                        ->from(['PlanoDeDivulgacao'], '*', 'sac.dbo')
                        ->where('idProjeto = ?', $idPreProjeto)
                        ->limit(1);

                    $planoDivulgacao = $db->fetchAll($sql);

                    if (empty($planoDivulgacao)){
                        $validacao->Descricao = 'O Plano B&aacute;sico de Divulga&ccedil;&atilde;o n&atilde;o foi preenchido.';
                        $validacao->Observacao = 'PENDENTE';
                        $listaValidacao[] =  clone($validacao);
                    } else {
                        $validacao->Descricao = 'Plano B&aacute;sico de Divulga&ccedil;&atilde;o cadastrado.';
                        $validacao->Observacao = 'OK';
                        $listaValidacao[] =  clone($validacao);
                    }

                    //-- VERIFICAR SE EXISTE NO MÍNIMO 90 DIAS ENTRE A DATA DE ENVIO E O INÍCIO DO PERÍODO DE EXECUÇÃO DO PROJETO
                    $sql = $db->select()
                        ->from(['PreProjeto'], '*', 'sac.dbo')
                        ->where('idPreProjeto = ?', $idPreProjeto)
                        ->where('DATEDIFF(DAY,GETDATE(),DtInicioDeExecucao) < 90')
                        ->limit(1);

                    $minimo90 = $db->fetchAll($sql);

                    if (!empty($minimo90)) {
                        $validacao->Descricao = 'A diferen&ccedil;a em dias entre a data de envio do projeto ao MinC e a data de início de execu&ccedil;&atilde;o do projeto est&aacute; menor do que 90 dias.';
                        $validacao->Observacao = 'PENDENTE';
                        $listaValidacao[] =  clone($validacao);
                    } else {
                        $validacao->Descricao = 'Prazo de início de execu&ccedil;&atilde;o maior do que 90 dias.';
                        $validacao->Observacao = 'OK';
                        $listaValidacao[] =  clone($validacao);
                    }

                    //-- VERIFICAR SE O PLANO DE DISTRIBUIÇÃO DO PRODUTO ESTÁ PREENCHIDO
                    $sql = $db->select()
                        ->from(['PlanoDistribuicaoProduto'], '*', 'sac.dbo')
                        ->where('idProjeto =  ?', $idPreProjeto)
                        ->limit(1);

                    $planoDistribuicao = $db->fetchAll($sql);
                    if (empty($planoDistribuicao)){
                        $validacao->Descricao = 'O Plano Distribui&ccedil;&atilde;o de Produto n&atilde;o foi preenchido.';
                        $validacao->Observacao = 'PENDENTE';
                        $listaValidacao[] =  clone($validacao);
                    } else {
                        $validacao->Descricao = 'O Plano Distribui&ccedil;&atilde;o de Produto cadastrado.';
                        $validacao->Observacao = 'OK';
                        $listaValidacao[] =  clone($validacao);
                    }

                    //--Verificar a existência do produto principal
                    //SELECT @QtdeOutros=stPrincipal FROM PlanoDistribuicaoProduto  WHERE idProjeto = @idProjeto and stPrincipal = 1
                    $sql = $db->select()
                        ->from(['PlanoDistribuicaoProduto'], 'stPrincipal', 'sac.dbo')
                        ->where('idProjeto =  ?', $idPreProjeto)
                        ->where('stPrincipal = 1')
                        ;

                    $quantidade = count($db->fetchAll($sql));

                    if ($quantidade = 0){
                        $validacao->Descricao = 'N&atilde;o h&aacute; produto principal selecionado na proposta.';
                        $validacao->Observacao = 'PENDENTE';
                        $listaValidacao[] =  clone($validacao);
                    } else if($quantidade > 1) {
                        $validacao->Descricao = 'Só poder&aacute; haver um produto principal em cada proposta, a sua est&aacute; com mais de um produto.';
                        $validacao->Observacao = 'PENDENTE';
                        $listaValidacao[] =  clone($validacao);
                    }

                    //-- VERIFICAR SE EXISTE NA PLANILHA ORÇAMENTÁRIA ITENS DA FONTE INCENTIVO FISCAL FEDERAL.
                    $sql = $db->select()
                        ->from(['tbPlanilhaProposta'], '*', 'sac.dbo')
                        ->where('idProjeto =  ?', $idPreProjeto)
                        ->where('FonteRecurso = 109')
                        ->limit(1)
                        ;

                    $planilhaOrcamentaria = $db->fetchAll($sql);

                    if (empty($planilhaOrcamentaria)) {
                        $validacao->Descricao = 'N&atilde;o existe item or&ccedil;ament&aacute;rio referente a fonte de recurso - Incentivo Fiscal Federal.';
                        $validacao->Observacao = 'PENDENTE';
                        $listaValidacao[] =  clone($validacao);
                    } else {
                        $validacao->Descricao = 'Itens Or&ccedil;ament&aacute;rios com fontes de recurso - incentivo fiscal federal cadastrados.';
                        $validacao->Observacao = 'OK';
                        $listaValidacao[] =  clone($validacao);
                    }

     //-- VERIFICAR SE EXISTE NA PLANILHA ORÇAMENTÁRIA PARA CADA PRODUTO DESCRITO NO PLANO DE DISTRIBUIÇÃO DO PRODUTO
                    //IF EXISTS(SELECT * FROM PlanoDistribuicaoProduto pp WHERE idProjeto = @idProjeto and
                  //NOT EXISTS(SELECT * FROM tbPlanilhaProposta pl WHERE idProjeto = @idProjeto and pp.idProduto=pl.idProduto and idProduto <> 0))
                    $subSql = $db->select()
                        ->from(['pl' => 'tbPlanilhaProposta'], '*', 'sac.dbo')
                        ->where('idProjeto =  ?', $idPreProjeto)
                        ->where('pp.idProduto=pl.idProduto')
                        ->where('idProduto <> 0')
                        ;

                    $sql = $db->select()
                        ->from(['pp' => 'PlanoDistribuicaoProduto'], '*', 'sac.dbo')
                        ->where('idProjeto =  ?', $idPreProjeto)
                        ->where(new Zend_Db_Expr("NOT EXISTS($subSql)"))
                        ;

                    $planilhaProduto = $db->fetchAll($sql);

                    if (!empty($planilhaProduto)) {
                        $validacao->Descricao = 'Existe produto cadastrado sem a respectiva planilha orcament&aacute;ria cadastrada.';
                        $validacao->Observacao = 'PENDENTE';
                        $listaValidacao[] =  clone($validacao);
                    } else {
                        $validacao->Descricao = 'Todos os produtos com as respectivas planilhas or&ccedil;ament&aacute;rias cadastradas.';
                        $validacao->Observacao = 'OK';
                        $listaValidacao[] =  clone($validacao);
                    }

                    //-- VERIFICAR SE EXISTE NA PLANILHA ORÇAMENTÁRIA PARA OS CUSTOS ADMINISTRATIVOS DO PROJETO
                    $subSql = $db->select()
                        ->from(['pl' => 'tbPlanilhaProposta'], '*', 'sac.dbo')
                        ->where('idProjeto = ?', $idPreProjeto)
                        ->where('pl.idProduto = 0')
                        ->where('idEtapa = 4')
                        ->where('idPlanilhaItem != 5249')
                        ;

                    $sql = $db->select()
                        ->from(['pp' => 'PlanoDistribuicaoProduto'], '*', 'sac.dbo')
                        ->where('idProjeto =  ?', $idPreProjeto)
                        ->where(new Zend_Db_Expr("NOT EXISTS($subSql)"))
                        ;

                    $custoAdministrativos = $db->fetchAll($sql);

                    if (!empty($custoAdministrativos)){
                        $validacao->Descricao = 'A planilha de custos administrativos do projeto não est&aacute; cadastrada.';
                        $validacao->Observacao = 'PENDENTE';
                        $listaValidacao[] =  clone($validacao);
                    } else {
                        $validacao->Descricao = 'Planilha de custos administrativos cadastrada.';
                        $validacao->Observacao = 'OK';
                        $listaValidacao[] =  clone($validacao);
                    }

                    //--Pega o custo total do projeto
                    $sql = $db->select()
                        ->from(['tbPlanilhaProposta'], 'SUM(Quantidade * Ocorrencia * ValorUnitario) as total', 'sac.dbo')
                        ->where('idProjeto =  ?', $idPreProjeto)
                        ->where('idEtapa <> 4')
                        ->where('FonteRecurso = 109')
                        ;

                    $total = $db->fetchAll($sql);
                    $total = empty($total[0]->total) ? 0 : $db->fetchAll($sql)[0]->total;

                    //--pega o valor de custo administrativo
                    $sql = $db->select()
                        ->from(['tbPlanilhaProposta'], 'SUM(Quantidade * Ocorrencia * ValorUnitario) as total', 'sac.dbo')
                        ->where('idProjeto =  ?', $idPreProjeto)
                        ->where('idEtapa <> 4')
                        ->where('FonteRecurso = 109')
                        ->where('idPlanilhaItem <> 5249')
                        ;

                    $custoAdm = $db->fetchAll($sql);
                    $custoAdm = empty($custoAdm[0]->total) ? 0 : $db->fetchAll($sql)[0]->total;

                    if ($total != 0 && $custoAdm != 0) {
                        $resultadoPercentual = $custoAdm/$total*100;

                        if($resultadoPercentual > 15){
                            $validacao->Descricao = 'Custo administrativo superior a 15% do valor total do projeto.';
                            $validacao->Observacao = 'PENDENTE';
                            $listaValidacao[] =  clone($validacao);
                        } else {
                            $validacao->Descricao = 'Custo administrativo inferior a 15% do valor total do projeto.';
                            $validacao->Observacao = 'OK';
                            $listaValidacao[] =  clone($validacao);
                        }
                    }
                    //-- VERIFICAR O PERCENTUAL DA REMUNERAÇÃO PARA CAPTAÇÃO DE RECURSOS
                    $sql = $db->select()
                        ->from(['tbPlanilhaProposta'], 'SUM(Quantidade * Ocorrencia * ValorUnitario) as total', 'sac.dbo')
                        ->where('idProjeto =  ?', $idPreProjeto)
                        ->where('FonteRecurso = 109')
                        ->where('idPlanilhaItem <> 5249')
                        ;

                    $total = $db->fetchAll($sql);
                    $total = empty($total[0]->total) ? 0 : $db->fetchAll($sql)[0]->total;

                    //--pega o valor de remuneracao para captacao
                    $sql = $db->select()
                        ->from(['tbPlanilhaProposta'], 'SUM(Quantidade * Ocorrencia * ValorUnitario) as total', 'sac.dbo')
                        ->where('idProjeto =  ?', $idPreProjeto)
                        ->where('FonteRecurso = 109')
                        ->where('idPlanilhaItem = 5249')
                        ;

                    $custoAdm = $db->fetchAll($sql);
                    $custoAdm = empty($custoAdm[0]->total) ? 0 : $db->fetchAll($sql)[0]->total;

                    $resultadoPercentual = ($total == 0) ? 0 : ($custoAdm/$total *100);
                    if ($resultadoPercentual > 10 || $custoAdm >100000){
                        $validacao->Descricao = 'Remunera&ccedil;&atilde;o para capta&ccedil;&atilde;o de recursos superior a 10% do valor total do projeto, ou superior a  R$ 100.000,00.';
                        $validacao->Observacao = 'PENDENTE';
                        $listaValidacao[] =  clone($validacao);
                    } else {
                        $validacao->Descricao = 'Remunera&ccedil;&atilde;o para capta&ccedil;&atilde;o de recursos est&aacute; dentro dos par&atilde;metros permitidos.';
                        $validacao->Observacao = 'OK';
                        $listaValidacao[] =  clone($validacao);
                    }

                    //-- VERIFICAR O PERCENTUAL DA DIVULGAÇÃO E COMERCIALIZAÇÃO
                    $sql = $db->select()
                        ->from(['tbPlanilhaProposta'], 'SUM(Quantidade * Ocorrencia * ValorUnitario) as total', 'sac.dbo')
                        ->where('idProjeto =  ?', $idPreProjeto)
                        ->where('FonteRecurso = 109')
                        ->where('idEtapa <> 3')
                        ;

                    $total = $db->fetchAll($sql);
                    $total = empty($total[0]->total) ? 0 : $db->fetchAll($sql)[0]->total;

                    //--pega o valor de remuneração para captação
                    $sql = $db->select()
                        ->from(['tbPlanilhaProposta'], 'SUM(Quantidade * Ocorrencia * ValorUnitario) as total', 'sac.dbo')
                        ->where('idProjeto =  ?', $idPreProjeto)
                        ->where('FonteRecurso = 109')
                        ->where('idEtapa = 3')
                        ;

                    $custoAdm = $db->fetchAll($sql);
                    $custoAdm = empty($custoAdm[0]->total) ? 0 : $db->fetchAll($sql)[0]->total;

                    //--calcula o percentual
                    if($total != 0 && $custoAdm != 0){
                         $resultadoPercentual = $custoAdm/$total*100;
                         //IF @resultadoPercentual > 20
                         if ($resultadoPercentual > 20) {
                            $validacao->Descricao = 'Divulgação / Comercialização superior a 20% do valor total do projeto.';
                            $validacao->Observacao = 'PENDENTE';
                            $listaValidacao[] =  clone($validacao);
                         } else {
                            $validacao->Descricao = 'Divulgação / Comercialização est&aacute; dentro dos parâmetros permitidos.';
                            $validacao->Observacao = 'OK';
                            $listaValidacao[] =  clone($validacao);
                         }
                    }
            }
        }

        $sql = $db->select()
            ->from(['PreProjeto'], 'idUsuario', 'sac.dbo')
            ->where('idPreProjeto =  ?', $idPreProjeto)
            ;

        $usuario = $db->fetchAll($sql)[0]->idUsuario;

        $validado= true;
        foreach ($listaValidacao as $valido){
            if($valido->Observacao == 'PENDENTE') {
                $validado = false;
                break;
            }
        }

        if($validado) {
            $insert = $db->insert('sac.dbo.tbMovimentacao', [$idPreProjeto, 96, new Zend_Db_Expr('getdate()'), 0,$usuario]);

            $validacao->Descricao = '<font color=blue><b>A PROPOSTA CULTURAL FOI ENCAMINHADA COM SUCESSO AO MINISTÉRIO DA CULTURA.</b></font>';
            $validacao->Observacao = 'OK';
            $listaValidacao[] =  clone($validacao);
        } else {
            $validacao->Descricao = '<font color=red><b> A PROPOSTA CULTURAL N&Atilde;O FOI ENVIADA AO MINIST&Eacute;RIO DA CULTURA DEVIDO &Agrave;S PEND&Ecirc;NCIAS ASSINALADAS ACIMA.</b></font>';
            $validacao->Observacao = '';
            $listaValidacao[] =  clone($validacao);
        }

        return $listaValidacao;
    }
}
