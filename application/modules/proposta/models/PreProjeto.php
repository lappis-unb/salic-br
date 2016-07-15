<?php

/**
 * PreProjeto
 *
 * @uses   Zend_Db_Table
 * @author wouerner <wouerner@gmail.com>
 */
class Proposta_Model_PreProjeto extends Zend_Db_Table
{
    protected $_banco = "SAC";
    protected $_schema= "SAC.dbo";
    protected $_name = "PreProjeto";
    protected $_primary = "idPreProjeto";

    public $_totalRegistros = null;

    /**
     * __construct
     *
     * @access public
     * @return void
     */
    public function __construct() {
        $db = new Conexao(Zend_Registry::get('DIR_CONFIG'), "conexao_sac");
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
     * @todo colocar padrão orm
     */
    public static function retirarProjetos($idUsuario, $idUsuarioR, $idAgente)
    {
        $sql = "UPDATE SAC.dbo.PreProjeto SET idUsuario = ".$idUsuario." WHERE idUsuario = ".$idUsuarioR." and idAgente = ".$idAgente;

        $db = Zend_Registry::get('db');
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        return $db->query($sql);
    }

    /**
     * retirarProjetosVinculos
     *
     * @param mixed $siVinculoProposta
     * @param mixed $idVinculo
     * @static
     * @access public
     * @return void
     * @todo colocar padrão orm
     */
    public static function retirarProjetosVinculos($siVinculoProposta, $idVinculo)
    {
        $sql = "UPDATE Agentes.dbo.tbVinculoProposta SET siVinculoProposta = $siVinculoProposta WHERE idVinculo = $idVinculo";

        $db = Zend_Registry::get('db');
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        return $db->query($sql);
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
        $slct->from($this, array("*",
                                  "DtInicioDeExecucaoForm"=>"CONVERT(CHAR(10),DtInicioDeExecucao,103)",
                                  "DtFinalDeExecucaoForm"=>"CONVERT(CHAR(10),DtFinalDeExecucao,103)",
                                  "DtAtoTombamentoForm"=>"CONVERT(CHAR(10),DtAtoTombamento,103)",
                                  "dtAceiteForm"=>"CONVERT(CHAR(10),dtAceite,103)",
                                  "DtArquivamentoForm"=>"CONVERT(CHAR(10),DtArquivamento,103)",
                                  "CAST(ResumoDoProjeto as TEXT) as ResumoDoProjeto",
                                  "CAST(Objetivos as TEXT) as Objetivos",
                                  "CAST(Justificativa as TEXT) as Justificativa",
                                  "CAST(Acessibilidade as TEXT) as Acessibilidade",
                                  "CAST(DemocratizacaoDeAcesso as TEXT) as DemocratizacaoDeAcesso",
                                  "CAST(EtapaDeTrabalho as TEXT) as EtapaDeTrabalho",
                                  "CAST(FichaTecnica as TEXT) as FichaTecnica",
                                  "CAST(Sinopse as TEXT) as Sinopse",
                                  "CAST(ImpactoAmbiental as TEXT) as ImpactoAmbiental",
                                  "CAST(EspecificacaoTecnica as TEXT) as EspecificacaoTecnica",
                                  "CAST(EstrategiadeExecucao as TEXT) as EstrategiadeExecucao"
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
                                  "a.DtInicioDeExecucaoForm"=>"CONVERT(CHAR(10),DtInicioDeExecucao,103)",
                                  "a.DtFinalDeExecucaoForm"=>"CONVERT(CHAR(10),DtFinalDeExecucao,103)",
                                  "a.DtAtoTombamentoForm"=>"CONVERT(CHAR(10),DtAtoTombamento,103)",
                                  "a.dtAceiteForm"=>"CONVERT(CHAR(10),dtAceite,103)",
                                  "a.DtArquivamentoForm"=>"CONVERT(CHAR(10),DtArquivamento,103)"
                                ));

        $slct->joinInner(array('ag' => 'Agentes'),
                         'a.idAgente = ag.idAgente',
                         array("ag.CNPJCPF as CNPJCPF"),
                         'AGENTES.dbo');

        $slct->joinInner(array('m' => 'Nomes'),
                         'a.idAgente = m.idAgente',
                         array("m.Descricao as NomeAgente"),
                         'AGENTES.dbo');

        //adiciona quantos filtros foram enviados
        foreach ($where as $coluna=>$valor)
        {
            $slct->where($coluna, $valor);
        }
        $slct->where(new Zend_Db_Expr("NOT EXISTS(select 1 from SAC.dbo.Projetos pr where a.idPreProjeto = pr.idProjeto)"));

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
     * Grava registro. Se seja passado um ID ele altera um registro existente
     * @param array $dados - array com dados referentes as colunas da tabela no formato "nome_coluna_1"=>"valor_1","nome_coluna_2"=>"valor_2"
     * @return ID do registro inserido/alterado ou FALSE em caso de erro
     */
    public function salvar($dados)
    {

        //DECIDINDO SE INCLUI OU ALTERA UM REGISTRO
        if(isset($dados['idPreProjeto']) && !empty ($dados['idPreProjeto'])){
            //UPDATE
            $rsPreProjeto = $this->find($dados['idPreProjeto'])->current();
        }else{
            //INSERT
            $dados['idPreProjeto'] = null;
            return $this->insert($dados);
        }

        //ATRIBUINDO VALORES AOS CAMPOS QUE FORAM PASSADOS
       $rsPreProjeto->idAgente              = $dados["idAgente"];
       $rsPreProjeto->NomeProjeto           = $dados["NomeProjeto"];
       $rsPreProjeto->Mecanismo             = $dados["Mecanismo"];
       $rsPreProjeto->AgenciaBancaria       = $dados["AgenciaBancaria"];
       $rsPreProjeto->AreaAbrangencia       = $dados["AreaAbrangencia"];
       $rsPreProjeto->DtInicioDeExecucao    = $dados["DtInicioDeExecucao"];
       $rsPreProjeto->DtFinalDeExecucao     = $dados["DtFinalDeExecucao"];
       $rsPreProjeto->NrAtoTombamento       = $dados["NrAtoTombamento"];
       $rsPreProjeto->DtAtoTombamento       = $dados["DtAtoTombamento"];
       $rsPreProjeto->EsferaTombamento      = $dados["EsferaTombamento"];
       $rsPreProjeto->ResumoDoProjeto       = $dados["ResumoDoProjeto"];
       $rsPreProjeto->Objetivos             = $dados["Objetivos"];
       $rsPreProjeto->Justificativa         = $dados["Justificativa"];
       $rsPreProjeto->Acessibilidade        = $dados["Acessibilidade"];
       $rsPreProjeto->DemocratizacaoDeAcesso = $dados["DemocratizacaoDeAcesso"];
       $rsPreProjeto->EtapaDeTrabalho       = $dados["EtapaDeTrabalho"];
       $rsPreProjeto->FichaTecnica          = $dados["FichaTecnica"];
       $rsPreProjeto->Sinopse               = $dados["Sinopse"];
       $rsPreProjeto->ImpactoAmbiental      = $dados["ImpactoAmbiental"];
       $rsPreProjeto->EspecificacaoTecnica  = $dados["EspecificacaoTecnica"];
       $rsPreProjeto->EstrategiadeExecucao  = $dados["EstrategiadeExecucao"];
       $rsPreProjeto->dtAceite              = $dados["dtAceite"];
       $rsPreProjeto->DtArquivamento        = (isset($dados["DtArquivamento"])) ? $dados["DtArquivamento"] : null;
       $rsPreProjeto->stEstado              = $dados["stEstado"];
       $rsPreProjeto->stDataFixa            = $dados["stDataFixa"];
       $rsPreProjeto->stPlanoAnual          = $dados["stPlanoAnual"];
       $rsPreProjeto->idUsuario             = $dados["idUsuario"];
       $rsPreProjeto->stTipoDemanda         = $dados["stTipoDemanda"];
       $rsPreProjeto->idEdital              = (isset($dados["idEdital"])) ? $dados["idEdital"] : null;

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
     * @todo colocar padrão orm
     */
    public static function consultaTodosProjetos($idAgente, $idResponsavel, $arrBusca) {

        $sql = "SELECT 0 as Ordem,a.idPreProjeto,a.NomeProjeto,ag.cnpjcpf AS CNPJCPF,m.descricao AS NomeAgente,a.idUsuario,a.idAgente
                FROM   preprojeto AS a
                       INNER JOIN AGENTES.dbo.agentes ag on (a.idagente = ag.idagente)
                       INNER JOIN AGENTES.dbo.nomes    m on (a.idagente = m.idagente)
                WHERE  a.idAgente = $idAgente ";

        foreach ($arrBusca as $value) {
            $sql .= ' AND '.$value.' ';
        }

        $sql .= " AND NOT EXISTS(SELECT 1 FROM   sac.dbo.projetos pr WHERE  a.idpreprojeto = pr.idprojeto)
                UNION ALL
                SELECT 1 as Ordem,a.idPreProjeto,a.NomeProjeto,ag.cnpjcpf AS CNPJCPF,m.descricao AS NomeAgente,a.idUsuario,a.idAgente
                FROM   preprojeto AS a
                       INNER JOIN AGENTES.dbo.agentes            ag on (a.idagente = ag.idagente)
                       INNER JOIN AGENTES.dbo.nomes              m   on (a.idagente = m.idagente)
                       INNER JOIN ControleDeAcesso.dbo.SGCacesso s  on (a.idUsuario = s.IdUsuario)
                WHERE  a.idusuario = $idResponsavel and ag.CNPJCPF <> s.Cpf
                       AND ( NOT EXISTS(SELECT 1 FROM   sac.dbo.projetos pr WHERE  a.idpreprojeto = pr.idprojeto) )";

        foreach ($arrBusca as $value) {
            $sql .= ' AND '.$value.' ';
        }

        $sql .= "ORDER  BY 1,m.Descricao ASC";

        $db = Zend_Registry::get('db');
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

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

        $db = Zend_Registry::get('db');
        $db->setFetchMode(Zend_DB::FETCH_OBJ);
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
    public static function alterarDados($dados, $where) {

        $db = Zend_Registry::get('db');
        $db->setFetchMode(Zend_DB::FETCH_OBJ);
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

        $sql = $db->select()->from('UF',['*'],'AGENTES.dbo')
            ->order('Sigla');

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
     * @todo Esse modelo não deveria fazer insert, essa função e do modelo Agente_Model_Agentes
     */
    public static function inserirAgentes($dadosAgentes) {
        $db = Zend_Registry::get('db');
        $db->setFetchMode(Zend_DB::FETCH_OBJ);
        $Agentes = $db->insert("Agentes.dbo.Agentes", $dadosAgentes);
    }

    /**
     * inserirNomes
     *
     * @param mixed $dadosNomes
     * @static
     * @access public
     * @return void
     * @todo vericar model correta para inserir nomes
     */
    public static function inserirNomes($dadosNomes) {
        $db = Zend_Registry::get('db');
        $db->setFetchMode(Zend_DB::FETCH_OBJ);
        $Nomes = $db->insert("Agentes.dbo.Nomes", $dadosNomes);
    }

    /**
     * inserirEnderecoNacional
     *
     * @param mixed $dadosEnderecoNacional
     * @static
     * @access public
     * @return void
     * @todo verificar model correta para inserir endereço
     */
    public static function inserirEnderecoNacional($dadosEnderecoNacional) {
        $db = Zend_Registry::get('db');
        $db->setFetchMode(Zend_DB::FETCH_OBJ);
        $Nomes = $db->insert("Agentes.dbo.EnderecoNacional", $dadosEnderecoNacional);
    }

    /**
     * inserirVisao
     *
     * @param mixed $dadosVisao
     * @static
     * @access public
     * @return void
     * @todo verificar mode correta para visao
     */
    public static function inserirVisao($dadosVisao) {
        $db = Zend_Registry::get('db');
        $db->setFetchMode(Zend_DB::FETCH_OBJ);
        $Nomes = $db->insert("Agentes.dbo.Visao", $dadosVisao);
    }

    /**
     * editarproposta
     *
     * @param mixed $idPreProjeto
     * @static
     * @access public
     * @return void
     * @todo colocar padrão orm
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
                        array('pre'=>$this->_name),
                        array(
                                'nomeProjeto'=>'pre.NomeProjeto',

                                'pronac'=>'pre.idPreProjeto'
                             )
                     );

        $select->joinInner(
                array('aval' => 'tbAvaliacaoProposta'),
                'aval.idProjeto = pre.idPreProjeto',
                array(
                        'aval.stProrrogacao',
                        'idDiligencia'=>'aval.idAvaliacaoProposta',
                        'dataSolicitacao'=>'CONVERT(VARCHAR,aval.DtAvaliacao,120)',
                        'dataResposta'=>'CONVERT(VARCHAR,aval.dtResposta,120)',
                        'Solicitacao'=>'aval.Avaliacao',
                        'Resposta'=>'aval.dsResposta',
                        'aval.idCodigoDocumentosExigidos',
                        'aval.stEnviado'
                    )
        );

        $select->joinLeft(
                array('arq' => 'tbArquivo'),
                'arq.idArquivo = aval.idArquivo',
                array(
                        'arq.nmArquivo',
                        'arq.idArquivo'
                    ),
                'BDCORPORATIVO.scCorp'
        );

        $select->joinLeft(
                array('a' => 'AGENTES'),
                'pre.idAgente = a.idAgente',
                array(
                        'a.idAgente'
                    ),
                'AGENTES.dbo'
        );
        $select->joinLeft(
                array('n' => 'NOMES'),
                'a.idAgente = n.idAgente',
                array(
                        'n.Descricao'
                    ),
                'AGENTES.dbo'
        );

        foreach ($consulta as $coluna=>$valor)
        {
            $select->where($coluna, $valor);
        }

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
     * @return void
     */
    public function buscarAgentePreProjeto($consulta = array()){
        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->from(
                        array('pre'=>$this->_name),
                        array(
                                'pre.idAgente'
                             )
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
     * @todo colocar padrão orm
     */
    public static function alteraproponente($idPreProjeto, $idAgente)
    {
        $sql = "UPDATE SAC.dbo.PreProjeto SET idAgente = ".$idAgente." WHERE idPreProjeto = $idPreProjeto ";

        $db = Zend_Registry::get('db');
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        return $db->fetchAll($sql);
    }

    /**
     * alteraresponsavel
     *
     * @param mixed $idPreProjeto
     * @param mixed $idResponsavel
     * @static
     * @access public
     * @return void
     * @todo colocar padrão orm
     */
    public static function alteraresponsavel($idPreProjeto, $idResponsavel)
    {
        $sql = "UPDATE SAC.dbo.PreProjeto SET idUsuario = ".$idResponsavel." WHERE idPreProjeto = $idPreProjeto ";

        $db = Zend_Registry::get('db');
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        return $db->fetchAll($sql);
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
                       CAST(pp.ResumoDoProjeto as TEXT) as ResumoDoProjeto')

        );

        $slct->joinLeft(
                array('resp' => 'SGCacesso'), 'resp.IdUsuario = pp.idUsuario',
                array('resp.Nome','resp.Cpf'),'CONTROLEDEACESSO.dbo'
        );

        $slct->joinLeft(
                array('pr' => 'Projetos'), 'pp.idPreProjeto = pr.idProjeto',
                array('pr.idProjeto','(pr.AnoProjeto+pr.Sequencial) as PRONAC')
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
                       'pp.idEdital')
                );

        $slct->joinLeft(
                array('resp' => 'SGCacesso'), 'resp.IdUsuario = pp.idUsuario',
                array('resp.Nome','resp.Cpf'),'CONTROLEDEACESSO.dbo'
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
     * @todo colocar padrão orm
     */
    public static function gerenciarResponsaveisVinculados($siVinculo, $idAgente = null)
    {
        $db = Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        $sql = $db->select()->distinct()
            ->from(['j'=>'PreProjeto'], [],'SAC.dbo')
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
     * @todo colocar padrão orm
     */
    public static function listarPropostasResultado($idAgente, $idResponsavel, $idAgenteCombo)
    {
        $db = Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        $sql = $db->select()
            ->from(['a'=>'PreProjeto'], ['a.idPreProjeto', 'a.NomeProjeto'],'SAC.dbo')
            ->join(['b' => 'Agentes'], 'a.idAgente = b.idAgente', ['b.CNPJCPF', 'b.idAgente'],'AGENTES.dbo')
            ->joinLeft(['n' => 'Nomes'], 'n.idAgente = b.idAgente', ['n.descricao as NomeProponente'], 'AGENTES.dbo')
            ->where('a.idAgente = ? ', $idAgente)
            ->where('a.stEstado = 1')
            ->where(new Zend_Db_Expr('NOT EXISTS(SELECT 1 FROM SAC.dbo.Projetos pr WHERE a.idPreProjeto = pr.idProjeto)'))
            ->where("a.Mecanismo = '1'")
            ;

        $sql2 = $db->select()
            ->from(['a'=>'PreProjeto'], ['a.idPreProjeto', 'a.NomeProjeto'], 'SAC.dbo')
            ->join(['b' => 'Agentes'], 'a.idAgente = b.idAgente', ['b.CNPJCPF', 'b.idAgente'],'AGENTES.dbo')
            ->join(['c' => 'Vinculacao'], 'b.idAgente = c.idVinculoPrincipal', [],'AGENTES.dbo')
            ->join(['d' => 'Agentes'], 'c.idAgente = d.idAgente', [],'AGENTES.dbo')
            ->join(['e' => 'SGCacesso'], 'd.CNPJCPF = e.Cpf', [],'CONTROLEDEACESSO.dbo')
            ->joinLeft(['n' => 'Nomes'], 'n.idAgente = b.idAgente', ['n.descricao as NomeProponente'], 'AGENTES.dbo')
            ->where('e.IdUsuario = ?',$idResponsavel)
            ->where('a.stEstado = 1')
            ->where(new Zend_Db_Expr('NOT EXISTS(SELECT 1 FROM SAC.dbo.Projetos f WHERE a.idPreProjeto = f.idProjeto)'))
            ->where("a.Mecanismo = '1'")
            ;

        $sql3 = $db->select()
            ->from(['a'=>'PreProjeto'], ['a.idPreProjeto', 'a.NomeProjeto'], 'SAC.dbo')
            ->join(['b' => 'Agentes'], 'a.idAgente = b.idAgente', ['b.CNPJCPF', 'b.idAgente'], 'AGENTES.dbo')
            ->join(['c' => 'Nomes'], 'b.idAgente = c.idAgente', ['c.descricao as NomeProponente'], 'AGENTES.dbo')
            ->join(['d' => 'SGCacesso'], 'a.idUsuario = d.IdUsuario', [], 'CONTROLEDEACESSO.dbo')
            ->join(['e' => 'tbVinculoProposta'], 'a.idPreProjeto = e.idPreProjeto', [], 'AGENTES.dbo')
            ->join(['f' => 'tbVinculo'], 'e.idVinculo = f.idVinculo', [], 'AGENTES.dbo')
            ->where('a.stEstado = 1')
            ->where(new Zend_Db_Expr('NOT EXISTS(SELECT 1 FROM SAC.dbo.Projetos z WHERE a.idPreProjeto = z.idProjeto)'))
            ->where("a.Mecanismo = '1'")
            ->where('e.siVinculoProposta = 2')
            ->where('f.idUsuarioResponsavel = ?', $idResponsavel)
            ;

        if (!empty($idAgenteCombo)) {
            $sql->where('b.idAgente = ?', $idAgenteCombo);
            $sql2->where('b.idAgente = ?', $idAgenteCombo);
            $sql3->where('b.idAgente = ?', $idAgenteCombo);
        }

        $sql = "
            $sql
            UNION ALL
            $sql2
            UNION ALL
            $sql3
            ";

        return $db->fetchAll($sql);
    }

    /**
     * Método para buscar os Proponentes - Combo Listar Propostas
     * @access public
     * @param integer $idResponsavel
     * @return object
     * @todo colocar padrão orm. Retirar função SAC.dbo.fnNome()
     */
    public function listarPropostasCombo($idResponsavel)
    {
        $sql = "
            SELECT b.CNPJCPF, b.idAgente, SAC.dbo.fnNome(b.idAgente) AS NomeProponente
                FROM SAC.dbo.PreProjeto AS a
                INNER JOIN AGENTES.dbo.Agentes AS b ON a.idAgente = b.idAgente
                INNER JOIN CONTROLEDEACESSO.dbo.SGCacesso AS c ON b.CNPJCPF = c.Cpf
            WHERE c.IdUsuario = '$idResponsavel'
            UNION
            SELECT b.CNPJCPF, b.idAgente, SAC.dbo.fnNome(b.idAgente) AS NomeProponente
                FROM SAC.dbo.PreProjeto AS a
                INNER JOIN AGENTES.dbo.Agentes AS b ON a.idAgente = b.idAgente
                INNER JOIN AGENTES.dbo.tbVinculoProposta AS c ON a.idPreProjeto = c.idPreProjeto
                INNER JOIN AGENTES.dbo.tbVinculo AS d ON c.idVinculo = d.idVinculo
                INNER JOIN AGENTES.dbo.Agentes AS f ON d.idAgenteProponente = f.idAgente
                INNER JOIN CONTROLEDEACESSO.dbo.SGCacesso AS e ON f.CNPJCPF = e.Cpf
                WHERE c.siVinculoProposta = 2
                AND e.IdUsuario = '$idResponsavel'
            UNION
            SELECT a.CNPJCPF, a.idAgente, SAC.dbo.fnNome(a.idAgente) AS NomeProponente
                FROM AGENTES.dbo.Agentes AS a
                INNER JOIN AGENTES.dbo.Vinculacao AS b ON a.idAgente = b.idVinculoPrincipal
                INNER JOIN AGENTES.dbo.Agentes AS c ON b.idAgente = c.idAgente
                INNER JOIN CONTROLEDEACESSO.dbo.SGCacesso AS d ON c.CNPJCPF = d.Cpf
                WHERE d.IdUsuario = '$idResponsavel'
            UNION
            SELECT a.CNPJCPF, a.idAgente, SAC.dbo.fnNome(a.idAgente) AS NomeProponente
                FROM AGENTES.dbo.Agentes AS a
                INNER JOIN AGENTES.dbo.tbVinculo AS b ON a.idAgente = b.idAgenteProponente
                INNER JOIN CONTROLEDEACESSO.dbo.SGCacesso AS c ON b.idUsuarioResponsavel = c.IdUsuario
                WHERE b.siVinculo = 2
                AND c.IdUsuario = '$idResponsavel'
            UNION
            SELECT a.CNPJCPF, a.idAgente, SAC.dbo.fnNome(a.idAgente) AS NomeProponente
                FROM AGENTES.dbo.Agentes AS a
                INNER JOIN CONTROLEDEACESSO.dbo.SGCacesso AS b ON a.CNPJCPF = b.cpf
                WHERE b.IdUsuario = '$idResponsavel'
            GROUP BY a.CNPJCPF, a.idAgente, SAC.dbo.fnNome(a.idAgente)
            ORDER BY 3 ASC ";

        $db = Zend_Registry::get('db');
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

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
     * @todo colocar padrão orm. Passar função para php SAC.dbo.fnNomeTecnicoMinc(). Passar para model PreProjeto
     */
    public function buscarPropostaAdmissibilidade($where=array(), $order=array(), $tamanho=-1, $inicio=-1)
    {

        $db = $this->getAdapter();

        $meuWhere = "";
        // adicionando clausulas where
        foreach ($where as $coluna=>$valor)
        {
            $meuWhere .= $coluna.$valor." AND ";
        }

        $meuOrder = "";
        // adicionando clausulas order
        foreach ($order as $valor)
        {
            if($meuOrder != ""){ $meuOrder .= " , "; }else{ $meuOrder = " ORDER BY "; }
            $meuOrder .= $valor;
        }

        $sql = "
        SELECT DISTINCT
        p.idPreProjeto AS idProjeto,
        p.NomeProjeto AS NomeProposta,
        a.CNPJCPF,
        p.idAgente,
        x.idTecnico AS idUsuario,
        SAC.dbo.fnNomeTecnicoMinc(x.idTecnico) AS Tecnico,
        SAC.dbo.fnIdOrgaoSuperiorAnalista(x.idTecnico) AS idSecretaria,
        CONVERT(CHAR(20),x.DtAvaliacao, 120) AS DtAdmissibilidade,
        DATEDIFF(d, x.DtAvaliacao, GETDATE()) AS diasCorridos,
        DATEDIFF(d, ap1.DtEnvio, GETDATE()) AS diasDiligencia,
        DATEDIFF(d, ap2.dtResposta, GETDATE()) AS diasRespostaDiligencia,
        CONVERT(CHAR(20),m.DtMovimentacao, 120) AS DtMovimentacao,
        DATEDIFF(d, m.DtMovimentacao, GETDATE()) AS diasDesdeMovimentacao,
        x.idAvaliacaoProposta,
        m.idMovimentacao,
        m.Movimentacao AS CodSituacao,
        y.Descricao AS Situacao,
        p.stTipoDemanda AS TipoDemanda,
        x.DtAvaliacao
        FROM SAC.dbo.PreProjeto AS p
        INNER JOIN SAC.dbo.tbMovimentacao AS m ON p.idPreProjeto = m.idProjeto AND m.stEstado = 0
        INNER JOIN SAC.dbo.tbAvaliacaoProposta AS x ON p.idPreProjeto = x.idProjeto AND x.stEstado = 0
        INNER JOIN AGENTES.dbo.Agentes AS a ON p.idAgente = a.idAgente
        INNER JOIN SAC.dbo.Verificacao AS y ON m.Movimentacao = y.idVerificacao
        LEFT JOIN SAC.dbo.tbAvaliacaoProposta as ap1 ON p.idPreProjeto = ap1.idProjeto AND ap1.stEnviado = 'S'
        LEFT JOIN SAC.dbo.tbAvaliacaoProposta as ap2 ON p.idPreProjeto = ap2.idProjeto AND ap2.stEnviado = 'S'
        WHERE
        {$meuWhere}
        (p.stEstado = 1)
        AND
        (
        NOT EXISTS
            (
            SELECT TOP (1) IdPRONAC, AnoProjeto, Sequencial, UfProjeto, Area, Segmento, Mecanismo, NomeProjeto, Processo, CgcCpf, Situacao, DtProtocolo, DtAnalise, Modalidade, Orgao, OrgaoOrigem, DtSaida, DtRetorno, UnidadeAnalise, Analista, DtSituacao, ResumoProjeto, ProvidenciaTomada, Localizacao, DtInicioExecucao, DtFimExecucao, SolicitadoUfir, SolicitadoReal, SolicitadoCusteioUfir, SolicitadoCusteioReal, SolicitadoCapitalUfir, SolicitadoCapitalReal, Logon, idProjeto
            FROM SAC.dbo.Projetos AS u
            WHERE (p.idPreProjeto = idProjeto)
            )
        )
        ".$meuOrder."
        ";

        $db->setFetchMode(Zend_DB :: FETCH_OBJ);

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
     * @todo colocar no padrão orm. Remover função: SAC.dbo.fnIdOrgaoSuperiorAnalista(a.idTecnico)
     */
    public function buscarTecnicosHistoricoAnaliseVisual($idOrgao)
    {
        $db = $this->getAdapter();

        $sql = "
            SELECT distinct
                    a.idTecnico,
                    u.usu_nome as Tecnico
            FROM SAC.dbo.tbAvaliacaoProposta a
            INNER JOIN SAC.dbo.PreProjeto p
                  ON (p.idPreProjeto = a.idProjeto)
            INNER JOIN TABELAS.dbo.Usuarios u
                  ON u.usu_codigo = a.idTecnico
            WHERE
                ConformidadeOK<>1
                and p.stEstado = 1
                and SAC.dbo.fnIdOrgaoSuperiorAnalista(a.idTecnico) = {$idOrgao}
            ORDER BY u.usu_nome ASC
        ";

        $db->setFetchMode(Zend_DB :: FETCH_OBJ);

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
     * @todo colocar padrão orm. Remover SAC.dbo.fnIdOrgaoSuperiorAnalista()
     */
    public function buscarHistoricoAnaliseVisual($idOrgao,$idTecnico=null,$situacao=null,$dtInicio=null,$dtFim=null)
    {
        $meuWhere = "";
        if($idTecnico){
            $meuWhere .= " AND a.idTecnico = {$idTecnico}";
        }
        if($situacao){
            $meuWhere .= " AND a.ConformidadeOK = {$situacao}";
        }

        if($dtInicio){
            if($dtFim){
                $meuWhere .= " AND a.DtAvaliacao > '".$dtInicio." 00:00:00'";
                $meuWhere .= " AND a.DtAvaliacao < '".$dtFim." 23:59:59'";
            }else{
                $meuWhere .= " AND a.DtAvaliacao > '".$dtInicio." 00:00:00'";
                $meuWhere .= " AND a.DtAvaliacao < '".$dtInicio." 23:59:59'";
            }
        }

        $sql = "
        SELECT TOP 20 p.idPreProjeto,
                p.NomeProjeto,
                a.idTecnico,SAC.dbo.fnNomeTecnicoMinc(a.idTecnico) as Tecnico,
                a.DtEnvio,
                CONVERT(CHAR(20),a.DtAvaliacao, 120) AS DtAvaliacao,
                a.idAvaliacaoProposta,
                a.ConformidadeOK,a.stEstado,
                SAC.dbo.fnIdOrgaoSuperiorAnalista(a.idTecnico) as idOrgao
        from SAC.dbo.tbAvaliacaoProposta a
        inner join SAC.dbo.PreProjeto p on (p.idPreProjeto = a.idProjeto)
        where ConformidadeOK<>1 and p.stEstado = 1 and SAC.dbo.fnIdOrgaoSuperiorAnalista(a.idTecnico) = {$idOrgao}
        {$meuWhere}
        order by p.idPreProjeto DESC, DtAvaliacao ASC
        ";

        $db = Zend_Registry :: get('db');
        $db->setFetchMode(Zend_DB :: FETCH_OBJ);

        // retornando os registros conforme objeto select
        return $db->fetchAll($sql);
    }

    /**
     * buscarAvaliacaoHistoricoAnaliseVisual
     *
     * @param mixed $idAvaliacao
     * @access public
     * @return void
     * @todo Esse metodo deve ser removido dessa model.
     * @deprecated
     */
    public function buscarAvaliacaoHistoricoAnaliseVisual($idAvaliacao)
    {
        $db = $this->getAdapter();

        $sql = $db->select()
            ->from(['a' => 'tbAvaliacaoProposta'], ['a.Avaliacao'], $this->_schema)
            ->where('a.idAvaliacaoProposta = ?', $idAvaliacao);

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
     * @todo Retirar esse metodo dessa model
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

        $meuWhere = "";
        // adicionando clausulas where
        foreach ($where as $coluna=>$valor) {
            $sql->where($coluna.' = ?', $valor);
        }

        $db->setFetchMode(Zend_DB::FETCH_OBJ);

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
     * @todo colocar padrão orm. Retiar função sac.dbo.fnDtUltimaDiligenciaDocumental()
     */
    public function buscarPropostaAnaliseDocumentalTecnico($where=array(), $order=array(), $tamanho=-1, $inicio=-1)
    {
        $meuWhere = "";
        // adicionando clausulas where
        foreach ($where as $coluna=>$valor)
        {
            if($meuWhere == ""){ $meuWhere = " WHERE "; }else{ $meuWhere .= " AND "; }
            $meuWhere .= $coluna.$valor;
        }

        $meuOrder = "";
        // adicionando clausulas order
        foreach ($order as $valor)
        {
            if($meuOrder != ""){ $meuOrder .= " , "; }else{ $meuOrder = " ORDER BY "; }
            $meuOrder .= $valor;
        }

        $sql = "
                SELECT a.idProjeto,
                       p.NomeProjeto,
                       sac.dbo.fnNomeTecnicoMinc(a.idTecnico) as Tecnico,
                       sac.dbo.fnIdOrgaoSuperiorAnalista(a.idTecnico) as idOrgao,
                       CodigoDocumento,
                       Descricao as Documento,
                       CONVERT(CHAR(20),sac.dbo.fnDtUltimaDiligenciaDocumental(a.idProjeto), 120) AS DtUltima
                FROM sac.dbo.tbAvaliacaoProposta a
                     INNER JOIN sac.dbo.PreProjeto p on (a.idProjeto=p.idPreProjeto)
                     INNER JOIN sac.dbo.vwDocumentosPendentes d on (a.idProjeto = d.idProjeto)
                     INNER JOIN sac.dbo.DocumentosExigidos on (CodigoDocumento = Codigo)
                {$meuWhere}
                {$meuOrder}
                ";

        $db = Zend_Registry :: get('db');
        $db->setFetchMode(Zend_DB :: FETCH_OBJ);

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
     * @todo Retirar metodo dessa model
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
     * @todo Retirar metodo dessa model
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
     * @todo colocar padrão orm: SAC.dbo.fnIdOrgaoSuperiorAnalista(Tecnico)
     */
    public function buscarVisual($idUsuario = null, $order=array(), $tamanho=-1, $inicio=-1)
    {
        $meuWhere = "";
        // adicionando clausulas where
        if($idUsuario !== null){
            $meuWhere .= " WHERE SAC.dbo.fnIdOrgaoSuperiorAnalista(Tecnico) = {$idUsuario} ";
        }

        $meuOrder = "";
        // adicionando clausulas order
        foreach ($order as $valor)
        {
            if($meuOrder != ""){ $meuOrder .= " , "; }else{ $meuOrder = " ORDER BY "; }
            $meuOrder .= $valor;
        }


        $sql = "
        SELECT
        p.idPreProjeto AS idProjeto,
        p.NomeProjeto AS NomeProposta,
        a.CNPJCPF,
        p.idAgente,
        x.idTecnico AS idUsuario,
        SAC.dbo.fnNomeTecnicoMinc(x.idTecnico) AS Tecnico,
        SAC.dbo.fnIdOrgaoSuperiorAnalista(x.idTecnico) AS idSecretaria,
        CONVERT(CHAR(20),x.DtAvaliacao, 120) AS DtAdmissibilidade,
        DATEDIFF(d, x.DtAvaliacao, GETDATE()) AS diasCorridos,
        x.idAvaliacaoProposta,
        m.idMovimentacao,
        m.Movimentacao AS CodSituacao,
        y.Descricao AS Situacao,
        p.stTipoDemanda AS TipoDemanda
        FROM SAC.dbo.PreProjeto AS p
        INNER JOIN SAC.dbo.tbMovimentacao AS m ON p.idPreProjeto = m.idProjeto AND m.stEstado = 0
        INNER JOIN SAC.dbo.tbAvaliacaoProposta AS x ON p.idPreProjeto = x.idProjeto AND x.stEstado = 0
        INNER JOIN AGENTES.dbo.Agentes AS a ON p.idAgente = a.idAgente
        INNER JOIN SAC.dbo.Verificacao AS y ON m.Movimentacao = y.idVerificacao
        WHERE
        (p.stEstado = 1)
        AND m.Movimentacao NOT IN(96,128)
        AND
        (
        NOT EXISTS
            (
            SELECT TOP (1) IdPRONAC, AnoProjeto, Sequencial, UfProjeto, Area, Segmento, Mecanismo, NomeProjeto, Processo, CgcCpf, Situacao, DtProtocolo, DtAnalise, Modalidade, Orgao, OrgaoOrigem, DtSaida, DtRetorno, UnidadeAnalise, Analista, DtSituacao, ResumoProjeto, ProvidenciaTomada, Localizacao, DtInicioExecucao, DtFimExecucao, SolicitadoUfir, SolicitadoReal, SolicitadoCusteioUfir, SolicitadoCusteioReal, SolicitadoCapitalUfir, SolicitadoCapitalReal, Logon, idProjeto
            FROM SAC.dbo.Projetos AS u
            WHERE (p.idPreProjeto = idProjeto)
            )
        )
        AND p.idPreProjeto IN(
            SELECT
            idProjeto
            FROM SAC.dbo.vwRedistribuirAnaliseVisual
            {$meuWhere}
        )
        ".$meuOrder."
        ";

        $db = Zend_Registry :: get('db');
        $db->setFetchMode(Zend_DB :: FETCH_OBJ);

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
     * @todo colocar padrão orm
     */
    public function buscarDocumental($idUsuario = null, $order=array(), $tamanho=-1, $inicio=-1)
    {
        $meuWhere = "";
        // adicionando clausulas where
        if($idUsuario !== null){
            $meuWhere .= " WHERE SAC.dbo.fnIdOrgaoSuperiorAnalista(idTecnico) = {$idUsuario} ";
        }

        $meuOrder = "";
        // adicionando clausulas order
        foreach ($order as $valor)
        {
            if($meuOrder != ""){ $meuOrder .= " , "; }else{ $meuOrder = " ORDER BY "; }
            $meuOrder .= $valor;
        }


        $sql = "
        SELECT
        p.idPreProjeto AS idProjeto,
        p.NomeProjeto AS NomeProposta,
        a.CNPJCPF,
        p.idAgente,
        x.idTecnico AS idUsuario,
        SAC.dbo.fnNomeTecnicoMinc(x.idTecnico) AS Tecnico,
        SAC.dbo.fnIdOrgaoSuperiorAnalista(x.idTecnico) AS idSecretaria,
        CONVERT(CHAR(20),x.DtAvaliacao, 120) AS DtAdmissibilidade,
        DATEDIFF(d, x.DtAvaliacao, GETDATE()) AS diasCorridos,
        x.idAvaliacaoProposta,
        m.idMovimentacao,
        m.Movimentacao AS CodSituacao,
        y.Descricao AS Situacao,
        p.stTipoDemanda AS TipoDemanda
        FROM SAC.dbo.PreProjeto AS p
        INNER JOIN SAC.dbo.tbMovimentacao AS m ON p.idPreProjeto = m.idProjeto AND m.stEstado = 0
        INNER JOIN SAC.dbo.tbAvaliacaoProposta AS x ON p.idPreProjeto = x.idProjeto AND x.stEstado = 0
        INNER JOIN AGENTES.dbo.Agentes AS a ON p.idAgente = a.idAgente
        INNER JOIN SAC.dbo.Verificacao AS y ON m.Movimentacao = y.idVerificacao
        WHERE
        (p.stEstado = 1)
        AND m.Movimentacao NOT IN(96,128)
        AND
        (
        NOT EXISTS
            (
            SELECT TOP (1) IdPRONAC, AnoProjeto, Sequencial, UfProjeto, Area, Segmento, Mecanismo, NomeProjeto, Processo, CgcCpf, Situacao, DtProtocolo, DtAnalise, Modalidade, Orgao, OrgaoOrigem, DtSaida, DtRetorno, UnidadeAnalise, Analista, DtSituacao, ResumoProjeto, ProvidenciaTomada, Localizacao, DtInicioExecucao, DtFimExecucao, SolicitadoUfir, SolicitadoReal, SolicitadoCusteioUfir, SolicitadoCusteioReal, SolicitadoCapitalUfir, SolicitadoCapitalReal, Logon, idProjeto
            FROM SAC.dbo.Projetos AS u
            WHERE (p.idPreProjeto = idProjeto)
            )
        )
        AND p.idPreProjeto IN(
            SELECT
            idProjeto
            FROM SAC.dbo.vwConformidadeDocumentalTecnico
            {$meuWhere}
        )
        ".$meuOrder."
        ";

        $db = Zend_Registry :: get('db');
        $db->setFetchMode(Zend_DB :: FETCH_OBJ);

        // retornando os registros conforme objeto select
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
     */
    public function transformarPropostaEmProjeto($idPreProjeto, $cnpjcpf, $idOrgao, $idUsuario, $nrProcesso)
    {
        $sql = "EXEC SAC.dbo.paPropostaParaProjeto {$idPreProjeto}, '{$cnpjcpf}', {$idOrgao}, {$idUsuario}, {$nrProcesso}";
        $db = Zend_Registry :: get('db');
        $db->setFetchMode(Zend_DB :: FETCH_OBJ);

        // retornando os registros conforme objeto select
        return $db->fetchAll($sql);
    }

    /**
     * buscaragencia
     *
     * @param mixed $codigo
     * @access public
     * @return void
     * @todo Retirar metodo dessa model
     */
    public function buscaragencia($codigo)
    {
        $db = $this->getAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        $sql = $db->select()
            ->from(['b' => 'BancoAgencia'], 'Agencia', $this->_schema)
            ->where('b.Agencia = ?', $codigo)
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
     * @todo colocar padrão orm. retirar função SAC.dbo.fnIdOrgaoSuperiorAnalista()
     */
    public function orgaoSecretaria($idTecnico)
    {
        $sql = "select SAC.dbo.fnIdOrgaoSuperiorAnalista({$idTecnico}) as idOrgao,tabelas.dbo.fnDadosOrgao(SAC.dbo.fnIdOrgaoSuperiorAnalista({$idTecnico}),'nome completo') as secretaria";

        $db = Zend_Registry :: get('db');
        $db->setFetchMode(Zend_DB :: FETCH_OBJ);

        // retornando os registros conforme objeto select
        return $db->fetchAll($sql);
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
}
