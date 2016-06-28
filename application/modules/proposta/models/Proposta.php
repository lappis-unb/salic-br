<?php
/**
 * Proposta
 *
 * @uses GenericModel
 * @package
 * @author  wouerner <wouerner@gmail.com>
 */
class Proposta_Model_Proposta extends GenericModel
{
    protected $_banco = "SAC";
    protected $_name = "PreProjeto";
    protected $_schema= "SAC.dbo";

    public $_totalRegistros = null;

    /**
     * Retorna registros do banco de dados
     * @param array $where - array com dados where no formato "nome_coluna_1"=>"valor_1","nome_coluna_2"=>"valor_2"
     * @param array $order - array com orders no formado "coluna_1 desc","coluna_2"...
     * @param int $tamanho - numero de registros que deve retornar
     * @param int $inicio - offset
     * @return Zend_Db_Table_Rowset_Abstract
     * @todo colocar padrão orm. Passar função para php SAC.dbo.fnNomeTecnicoMinc()
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

        /*if($dados->area){
        $slct->joinInner(
                        array("ar"=>"Area"),
                        "ar.Codigo = p.AreaAbrangencia",
                        array(),
                        "SAC.dbo"
                        );
        }*/

        if(($dados->area) || ($dados->segmento)){
        $slct->joinInner(
                        array("pdp"=>"PlanoDistribuicaoProduto"),
                        "pdp.idProjeto = p.idPreProjeto AND pdp.stPlanoDistribuicaoProduto = 1",
                        array(),
                        "SAC.dbo"
                        );
        }

        //if($dados->valor){
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
        //}
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
}
