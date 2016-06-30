<?php

/**
 * PreProjeto
 *
 * @uses   Zend_Db_Table
 * @author wouerner <wouerner@gmail.com>
 */
class Proposta_Model_PreProjeto extends Zend_Db_Table
{
    protected $_name = "PreProjeto";
    protected $_primary = "idPreProjeto";

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
        $filtro = '';
        if (!empty($idAgenteCombo)){
            $filtro = " AND b.idAgente = $idAgenteCombo ";
        }

        $sql = "
            SELECT b.CNPJCPF, b.idAgente, dbo.Fnnome(b.idAgente) AS NomeProponente, a.idPreProjeto, a.NomeProjeto--,'Proponente - Pessoa Física' as TipoDeAgente
                FROM SAC.dbo.PreProjeto a
                    INNER JOIN AGENTES.dbo.Agentes b ON ( a.idAgente = b.idAgente )
                WHERE  a.idAgente = $idAgente
                    $filtro
                    AND a.stEstado = 1
                    AND NOT EXISTS(SELECT 1 FROM SAC.dbo.Projetos pr WHERE a.idPreProjeto = pr.idProjeto)
                    AND a.Mecanismo = '1'
            UNION ALL
            SELECT b.CNPJCPF, b.idAgente, dbo.Fnnome(b.idAgente) AS NomeProponente, a.idPreProjeto, a.NomeProjeto--,'Dirigente' as TipoDeAgente
                FROM SAC.dbo.PreProjeto a
                    INNER JOIN AGENTES.dbo.Agentes b ON ( a.idAgente = b.idAgente )
                    INNER JOIN AGENTES.dbo.Vinculacao c ON ( b.idAgente = c.idVinculoPrincipal )
                    INNER JOIN AGENTES.dbo.Agentes d ON ( c.idAgente = d.idAgente )
                    INNER JOIN CONTROLEDEACESSO.dbo.SGCacesso e ON ( d.CNPJCPF = e.Cpf )
                WHERE e.IdUsuario = $idResponsavel
                    $filtro
                    AND a.stEstado = 1
                    AND NOT EXISTS(SELECT 1 FROM SAC.dbo.Projetos f WHERE a.idPreProjeto = f.idProjeto)
                    AND a.Mecanismo = '1'
            UNION ALL
            SELECT  b.CNPJCPF, b.idAgente, dbo.Fnnome(b.idAgente) AS NomeProponente, a.idPreProjeto, a.NomeProjeto--,'Responsável' as TipoDeAgente
                FROM SAC.dbo.PreProjeto a
                    INNER JOIN AGENTES.dbo.Agentes b ON ( a.idAgente = b.idAgente )
                    INNER JOIN AGENTES.dbo.Nomes c ON ( b.idAgente = c.idAgente )
                    INNER JOIN CONTROLEDEACESSO.dbo.SGCacesso d ON ( a.idUsuario = d.IdUsuario )
                    INNER JOIN AGENTES.dbo.tbVinculoProposta e ON ( a.idPreProjeto = e.idPreProjeto )
                    INNER JOIN AGENTES.dbo.tbVinculo f ON ( e.idVinculo = f.idVinculo )
                WHERE f.idUsuarioResponsavel = $idResponsavel
                    $filtro
                    AND a.stEstado = 1
                    AND e.siVinculoProposta = 2
                    AND NOT EXISTS(SELECT 1 FROM SAC.dbo.Projetos z WHERE a.idPreProjeto = z.idProjeto)
                    AND a.Mecanismo = '1' ";

        $db = Zend_Registry::get('db');
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        return $db->fetchAll($sql);
    }
}
