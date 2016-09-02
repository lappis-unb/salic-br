<?php

/**
 * spPlanilhaOrcamentaria
 *
 * @uses GenericModel
 * @author
 */
class spPlanilhaOrcamentaria extends GenericModel {

    protected $_banco = 'sac';
    protected $_schema = 'sac';
    protected $_name  = 'spPlanilhaOrcamentaria';

    /**
     * exec
     *
     * @name exec
     * @param $idPronac
     * @param $tipoPlanilha
     * @return mixed
     *
     * @author Ruy Junior Ferreira Silva <ruyjfs@gmail.com>
     * @author wouerner <wouerner@gmail.com>
     * @since  17/08/2016
     */
    public function exec($idPronac, $tipoPlanilha)
    {
        // tipoPlanilha = 0 : Planilha Orcamentaria da Proposta
        // tipoPlanilha = 1 : Planilha Orcamentaria do Proponente
        // tipoPlanilha = 2 : Planilha Orcamentaria do Parecerista
        // tipoPlanilha = 3 : Planilha Orcamentaria Aprovada Ativa
        // tipoPlanilha = 4 : Cortes Orcamentarios Aprovados
        // tipoPlanilha = 5 : Remanejamento menor que 20%
        // tipoPlanilha = 6 : Readequacao

        $tipoPlanilha = 6;
        switch($tipoPlanilha){
        case 0:
            return $this->planilhaOrcamentariaProposta($idPronac);
            break;
        case 1:
            return $this->orcamentariaProponente($idPronac);
            break;
        case 2:
            return $this->orcamentariaParecerista($idPronac);
            break;
        case 3:
            return $this->orcamentariaAprovadaAtiva($idPronac);
            break;
        case 4:
            return $this->cortesOrcamentariosAprovados($idPronac);
            break;
        case 5:
            return $this->remanejamentoMenor20($idPronac);
            break;
        case 6:
            return $this->readequacao($idPronac);
            break;
        default:
        }
    }

    /**
     * planilhaOrcamentariaProposta
     *
     * @param mixed $idPronac
     * @access public
     * @return void
     * @author wouerner <wouerner@gmail.com>
     */
    public function planilhaOrcamentariaProposta($idPronac)
    {
        $a = [
            'a.idPreProjeto as idPronac',
            new Zend_Db_Expr("' ' AS PRONAC"),
            'a.NomeProjeto',
            new Zend_Db_Expr(" CASE WHEN idProduto = 0
                       THEN 'Administração do Projeto'
                       ELSE c.Descricao
                  END as Produto"),
        ];

        $b = [
            'b.idProduto',
            'b.idPlanilhaProposta',
            'b.idEtapa',
            'b.Quantidade',
            'b.Ocorrencia',
            'b.ValorUnitario as vlUnitario',
            'ROUND((b.Quantidade * b.Ocorrencia * b.ValorUnitario),2) as vlSolicitado',
            'b.FonteRecurso as idFonte',
            new Zend_Db_Expr('convert(varchar(max),b.dsJustificativa) as JustProponente'),
            new Zend_Db_Expr('QtdeDias')
        ];

        $db = Zend_Db_Table::getDefaultAdapter();

        $sac = 'sac.dbo';

        $sql = $db->select()
            ->from(['a' => 'preprojeto '], $a, $sac)
            ->joinInner(['b' => 'tbplanilhaproposta'], '(a.idpreProjeto = b.idprojeto)', $b, $sac)
            ->joinLeft(['c' => 'produto'], '(b.idproduto = c.codigo)', null, $sac)
            ->JoinInner(['d' => 'tbplanilhaetapa '], '(b.idetapa = d.idplanilhaetapa)', 'd.Descricao as Etapa', $sac)
            ->joinInner(['e' => 'tbplanilhaunidade'], '(b.unidade = e.idunidade)', 'e.Descricao as Unidade', $sac)
            ->joinInner(['i' => 'tbplanilhaitens '], '(b.idplanilhaitem=i.idplanilhaitens)', 'i.Descricao as Item', $sac)
            ->joinInner(['x' => 'verificacao'], '(b.fonterecurso = x.idverificacao)', 'x.Descricao as FonteRecurso', $sac)
            ->joinInner(['f' => 'vufmunicipio '], '(b.ufdespesa = f.iduf and b.municipiodespesa = f.idmunicipio)', ['f.UF','f.Municipio'], 'agentes.dbo')
            ->where('a.idpreprojeto = ? ', $idPronac)
            ->order("x.Descricao")
            ->order("c.Descricao DESC")
            ->order("CONVERT(VARCHAR(8),d.idPlanilhaEtapa) + ' - ' + d.Descricao")
            ->order('f.UF')
            ->order('f.Municipio')
            ->order('i.Descricao');

        return $db->fetchAll($sql);
    }

    /**
     * orcamentariaProponente
     *
     * @param mixed $idPronac
     * @access public
     * @return void
     */
    public function orcamentariaProponente($idPronac)
    {
        $db = Zend_Db_Table::getDefaultAdapter();

        $a =[
            'a.idPronac',
            'a.AnoProjeto',
            'a.Sequencial AS PRONAC',
            'a.NomeProjeto',
            new Zend_Db_Expr('QtdeDias')
        ];

        $b =[
            'b.idProduto',
            'b.idPlanilhaProposta',
            new Zend_Db_Expr("
                CASE
                  WHEN idProduto = 0
                       THEN 'Administração do Projeto'
                       ELSE c.Descricao
                  END as Produto
            "),
            'b.idEtapa',
            'b.Quantidade',
            'b.Ocorrencia',
            'b.ValorUnitario as vlUnitario',
            "b.FonteRecurso as idFonte",
            new Zend_Db_Expr("ROUND((b.Quantidade * b.Ocorrencia * b.ValorUnitario),2) as vlSolicitado"),
            new Zend_Db_Expr("convert(varchar(max),b.dsJustificativa) as JustProponente")
        ];

        $sac = 'sac.dbo';
        $concat = MinC_Db_Expr::concat();

        $sql = $db->select()
            ->from(['a' => 'projetos'], $a, $sac)
            ->joinInner(['b' => 'tbplanilhaproposta'], 'a.idProjeto = b.idProjeto', $b, $sac)
            ->joinLeft(['c' => 'produto'], '(b.idproduto = c.codigo)', null, $sac)
            ->JoinInner(['d' => 'tbplanilhaetapa '], '(b.idetapa = d.idplanilhaetapa)', 'd.Descricao as Etapa', $sac)
            ->joinInner(['e' => 'tbplanilhaunidade'], '(b.unidade = e.idunidade)', 'e.descricao as Unidade', $sac)
            ->joinInner(['i' => 'tbplanilhaitens '], '(b.idplanilhaitem=i.idplanilhaitens)', 'i.descricao as Item', $sac)
            ->joinInner(['x' => 'verificacao'], '(b.fonterecurso = x.idverificacao)', 'x.descricao as FonteRecurso', $sac)
            ->joinInner(['f' => 'vufmunicipio '], '(b.ufdespesa = f.iduf and b.municipiodespesa = f.idmunicipio)', ['f.uf','f.municipio'], 'agentes.dbo')
            ->where('a.idpronac = ? ', $idPronac)
            ->order("x.Descricao")
            ->order("c.Descricao DESC")
            ->order("CONVERT(VARCHAR(8),d.idPlanilhaEtapa) $concat ' - '$concat  d.Descricao")
            ->order('f.UF')
            ->order('f.Municipio')
            ->order('i.Descricao');
            ;
        return $db->fetchAll($sql);
    }

    /**
     * orcamentariaParecerista
     *
     * @param mixed $idPronac
     * @access public
     * @return void
     */
    public function orcamentariaParecerista($idPronac)
    {
        $db = Zend_Db_Table::getDefaultAdapter();

        $concat = MinC_Db_Expr::concat();

        $a = [
            'a.idPronac',
            new Zend_Db_Expr("a.AnoProjeto $concat a.Sequencial as PRONAC"),
            'a.NomeProjeto',
        ];

        $b = [
            'b.idProduto',
            'b.idPlanilhaProjeto',
            new Zend_Db_Expr("
                     CASE
                       WHEN b.idProduto = 0
                            THEN 'Administração do Projeto'
                            ELSE c.Descricao
                       END as Produto
                 "),
            'b.idEtapa',
            'b.idPlanilhaItem',
            'b.UfDespesa as idUF',
            'b.MunicipioDespesa as idMunicipio',
            'b.Quantidade',
            'b.Ocorrencia',
            'b.ValorUnitario as vlUnitario',
            'b.QtdeDias',
            'b.FonteRecurso as idFonte',
            'b.idUsuario',
            new Zend_Db_Expr('convert(varchar(max),b.Justificativa) as JustParecerista'),
            new Zend_Db_Expr('round((b.quantidade * b.ocorrencia * b.valorunitario),2) as vlsugerido')
        ];

        $z = [
            new Zend_Db_Expr(' round((z.quantidade * z.ocorrencia * z.valorunitario),2) as vlsolicitado'),
            new Zend_Db_Expr('convert(varchar(max),z.dsjustificativa) as justproponente')
        ];

        $f =['f.uf', 'f.municipio'];

        $sac = 'sac.dbo';

        $sql = $db->select()
            ->from(['a' => 'projetos'], $a, $sac)
            ->joinInner(['b' => 'tbplanilhaprojeto'], '(a.idpronac = b.idpronac)', $b, $sac)
            ->joinInner(['z' => 'tbplanilhaproposta'], '(b.idplanilhaproposta=z.idplanilhaproposta)', $z, $sac)
            ->joinLeft(['c' => 'produto' ], '(b.idproduto = c.codigo)', null, $sac)
            ->joinInner(['d' => 'tbPlanilhaEtapa'], '(b.idEtapa = d.idPlanilhaEtapa)', 'd.Descricao as Etapa', $sac)
            ->joinInner(['e' => 'tbplanilhaunidade'], '(b.idunidade = e.idunidade)', 'e.descricao as unidade', $sac)
            ->joinInner(['i' => 'tbplanilhaitens' ], '(b.idplanilhaitem=i.idplanilhaitens)', 'i.descricao as item', $sac)
            ->joinInner(['x' => 'verificacao'  ], '(b.fonterecurso = x.idverificacao)', 'x.descricao as fonterecurso', $sac)
            ->joinInner(['f' => 'vufmunicipio' ], '(b.ufdespesa = f.iduf and b.municipiodespesa = f.idmunicipio)', $f, 'agentes.dbo')
            ->where('a.idPronac = ?', $idPronac)
            ->order('x.Descricao')
            ->order('c.Descricao DESC')
            ->order("CONVERT(VARCHAR(8),d.idPlanilhaEtapa) $concat ' - ' $concat d.Descricao")
            ->order('f.uf')
            ->order('f.Municipio')
            ->order('i.Descricao')
            ;
        return $db->fetchAll($sql);
    }

    /**
     * orcamentariaAprovadaAtiva
     *
     * @param mixed $idPronac
     * @access public
     * @return void
     */
    public function orcamentariaAprovadaAtiva($idPronac)
    {
        $db = Zend_Db_Table::getDefaultAdapter();

        $subA = [
            'sum(b1.vlComprovacao) AS vlPagamento',
        ];

        $subSQLA = $db->select()->from(['a1' => 'tbComprovantePagamentoxPlanilhaAprovacao'], $subA, 'BDCORPORATIVO.scSAC')
            ->join(['b1' => 'tbComprovantePagamento'], '(a1.idComprovantePagamento = b1.idComprovantePagamento)', null, 'BDCORPORATIVO.scSAC')
            ->join(['c1' => 'tbPlanilhaAprovacao'], '(a1.idPlanilhaAprovacao = c1.idPlanilhaAprovacao)', null, $this->schema)
            ->where("c1.stAtivo = 'S'")
            ->where("c1.idPlanilhaAprovacao = k.idPlanilhaAprovacao")
            ->where("(c1.idPronac = k.idPronac)")
            ->group("a1.idPlanilhaAprovacao")
        ;

        $subB = [
            'sum(b1.vlComprovacao) AS vlPagamento',
        ];

        $subSQLB = $db->select()->from(['a1' =>'tbComprovantePagamentoxPlanilhaAprovacao'], $subB, 'BDCORPORATIVO.scSAC')
            ->join(['b1' => 'tbComprovantePagamento'], '(a1.idComprovantePagamento = b1.idComprovantePagamento)', null, 'BDCORPORATIVO.scSAC')
            ->join(['c1' => 'tbPlanilhaAprovacao'], '(a1.idPlanilhaAprovacao = c1.idPlanilhaAprovacaoPai)', null, $this->schema)
            ->where("c1.stAtivo = 'S'")
            ->where("c1.idPlanilhaAprovacaoPai = k.idPlanilhaAprovacaoPai")
            ->where("(c1.idPronac = k.idPronac)")
            ->group("a1.idPlanilhaAprovacao")
        ;

        $a = [
            new Zend_Db_Expr("
                CASE
                    WHEN k.tpPlanilha = 'CO' THEN
                    ($subSQLA)
                   ELSE
                    ($subSQLB)
            END as vlComprovado"),
        new Zend_Db_Expr("CASE WHEN k.idProduto = 0 THEN 'Administração do Projeto' ELSE c.Descricao END as Produto"),
        'CONVERT(varchar(max), k.dsJustificativa) as JustComponente',
        'ROUND((b.Quantidade * b.Ocorrencia * b.ValorUnitario),2) as vlSugerido',
        'ROUND((k.QtItem * k.nrOcorrencia * k.VlUnitario),2) as vlAprovado',
        'ROUND((z.Quantidade * z.Ocorrencia * z.ValorUnitario),2) as vlSolicitado',
        new Zend_Db_Expr('a.AnoProjeto+a.Sequencial as PRONAC'),
        'a.NomeProjeto',
        'a.idPronac',
        'b.idEtapa',
        'convert(varchar(max),b.Justificativa) as JustParecerista',
        'convert(varchar(max),z.dsJustificativa) as JustProponente',
        'd.Descricao as Etapa',
        'e.Descricao as Unidade',
        'f.Municipio',
        'f.UF',
        'i.Descricao as Item',
        'k.QtDias as QtdeDias',
        'k.QtItem as Quantidade',
        'k.TpDespesa',
        'k.TpPessoa',
        'k.idMunicipioDespesa as idMunicipio',
        'k.idPlanilhaAprovacao',
        'k.idPlanilhaItem',
        'k.idProduto',
        'k.idUfDespesa as idUF',
        'k.nrContrapartida',
        'k.nrFonteRecurso as idFonte',
        'k.nrOcorrencia as Ocorrencia',
        'k.tpPlanilha',
        'k.vlUnitario',
        'x.Descricao as FonteRecurso',
        ];

        $sql = $db->select()->from(['a' => 'Projetos'], $a, $this->_schema)
            ->join(['b' => 'tbPlanilhaProjeto'], '(a.idPronac = b.idPronac)', null, $this->schema)
            ->join(['z' => 'tbPlanilhaProposta'], '(b.idPlanilhaProposta=z.idPlanilhaProposta)', null, $this->schema)
            ->join(['k' => 'tbPlanilhaAprovacao'], '(b.idPlanilhaProposta=k.idPlanilhaProposta)', null, $this->schema)
            ->joinLeft(['c' => 'Produto'], '(b.idProduto = c.Codigo)', null, $this->schema)
            ->join(['d' => 'tbPlanilhaEtapa'], '(k.idEtapa = d.idPlanilhaEtapa)', null, $this->schema)
            ->join(['e' => 'tbPlanilhaUnidade'], '(b.idUnidade = e.idUnidade)', null, $this->schema)
            ->join(['i' => 'tbPlanilhaItens'], '(b.idPlanilhaItem=i.idPlanilhaItens)', null, $this->schema)
            ->join(['x' => 'Verificacao'], '(b.FonteRecurso = x.idVerificacao)', null, $this->schema)
            ->join(['f' => 'vUfMunicipio'], '(b.UfDespesa = f.idUF and b.MunicipioDespesa = f.idMunicipio)', null, 'agentes.dbo')
            ->where("k.stAtivo = 'S'")
            ->where("a.idPronac = ?", $idPronac)
            ->order("x.Descricao")
            ->order("c.Descricao DESC")
            ->order("CONVERT(VARCHAR(8),d.idPlanilhaEtapa) + ' - ' + d.Descricao")
            ->order("f.UF")
            ->order("f.Municipio")
            ->order("i.Descricao")
            ;

        return $db->fetchAll($sql);
    }

    /**
     * cortesOrcamentariosAprovados
     *
     * @param mixed $idPronac
     * @access public
     * @return void
     */
    public function cortesOrcamentariosAprovados($idPronac)
    {
        $db = Zend_Db_Table::getDefaultAdapter();

        $a = [
            new Zend_Db_Expr("CASE WHEN k.idProduto = 0 THEN 'Administração do Projeto' ELSE c.Descricao END as Produto"),
            'ROUND((b.Quantidade * b.Ocorrencia * b.ValorUnitario),2) as vlSugerido',
            'ROUND((k.QtItem * k.nrOcorrencia * k.VlUnitario),2) as vlAprovado',
            'ROUND((z.Quantidade * z.Ocorrencia * z.ValorUnitario),2) as vlSolicitado',
            new Zend_Db_Expr('a.AnoProjeto+a.Sequencial as PRONAC'),
            'a.NomeProjeto',
            'a.idPronac',
            'b.idEtapa',
            'b.idPlanilhaProjeto',
            'convert(varchar(max),k.dsJustificativa) as JustComponente',
            'convert(varchar(max),b.Justificativa) as JustParecerista',
            'convert(varchar(max),z.dsJustificativa) as JustProponente',
            'd.Descricao as Etapa',
            'e.Descricao as Unidade',
            'f.Municipio',
            'f.UF',
            'i.Descricao as Item',
            'k.QtDias as QtdeDias',
            'k.QtItem as Quantidade',
            'k.TpDespesa',
            'k.TpPessoa',
            'k.idPlanilhaAprovacao',
            'k.idProduto',
            'k.nrContrapartida',
            'k.nrFonteRecurso as idFonte',
            'k.nrOcorrencia as Ocorrencia',
            'k.vlUnitario',
            'x.Descricao as FonteRecurso',
        ];

        $sql = $db->select()->from(['a' => 'Projetos'], $a, $this->_schema)
            ->join(['b' => 'tbPlanilhaProjeto'], '(a.idPronac = b.idPronac)', null, $this->schema)
            ->join(['z' => 'tbPlanilhaProposta'], '(b.idPlanilhaProposta=z.idPlanilhaProposta)', null, $this->schema)
            ->join(['k' => 'tbPlanilhaAprovacao'], '(b.idPlanilhaProposta=k.idPlanilhaProposta)', null, $this->schema)
            ->joinLeft(['c' => 'Produto'], '(b.idProduto = c.Codigo)', null, $this->schema)
            ->join(['d' => 'tbPlanilhaEtapa'], '(k.idEtapa = d.idPlanilhaEtapa)', null, $this->schema)
            ->join(['e' => 'tbPlanilhaUnidade'], '(b.idUnidade = e.idUnidade)', null, $this->schema)
            ->join(['i' => 'tbPlanilhaItens'], '(b.idPlanilhaItem=i.idPlanilhaItens)', null, $this->schema)
            ->join(['x' => 'Verificacao'], '(b.FonteRecurso = x.idVerificacao)', null, $this->schema)
            ->join(['f' => 'vUfMunicipio'], '(b.UfDespesa = f.idUF and b.MunicipioDespesa = f.idMunicipio)', null, 'agentes.dbo')
            ->where("a.idPronac = ?", $idPronac)
            ->where("k.stAtivo = 'S'")
            ->where("
                (ROUND((z.Quantidade * z.Ocorrencia * z.ValorUnitario),2) <> ROUND((b.Quantidade * b.Ocorrencia * b.ValorUnitario),2) OR
                     ROUND((z.Quantidade * z.Ocorrencia * z.ValorUnitario),2) <> ROUND((k.QtItem * k.nrOcorrencia * k.vlUnitario),2))
            ")
            ->order("x.Descricao")
            ->order('c.Descricao DESC')
            ->order("CONVERT(VARCHAR(8), d.idPlanilhaEtapa) + ' - ' + d.Descricao")
            ->order("f.UF")
            ->order("f.Municipio")
            ->order("i.Descricao")
        ;
        return $db->fetchAll($sql);
    }

    /**
     * remanejamentoMenor20
     *
     * @param mixed $idPronac
     * @access public
     * @return void
     */
    public function remanejamentoMenor20($idPronac)
    {
        $db = Zend_Db_Table::getDefaultAdapter();

        $sql = $db->select()->from(['tbPlanilhaAprovacao'], '*', $this->_schema)
            ->where('idPronac = ?', $idPronac)
            ->where("stAtivo = 'S'")
            ->where("tpPlanilha = 'RP'")
            ->limit(1)
            ;
        $planilha = $db->fetchAll($sql);

        if (empty($planilha)) {
            $subA = [
                "sum(b1.vlComprovacao) AS vlPagamento",
            ];

            $subSql = $db->select()->from(['a1' => 'tbComprovantePagamentoxPlanilhaAprovacao'], $subA, 'BDCORPORATIVO.scSAC')
                ->join(['b1' => 'tbComprovantePagamento'], '(a1.idComprovantePagamento = b1.idComprovantePagamento)', null, 'BDCORPORATIVO.scSAC')
                ->join(['c1' => 'tbPlanilhaAprovacao'], '(a1.idPlanilhaAprovacao = c1.idPlanilhaAprovacao)', null, $this->schema)
                ->where("c1.idPlanilhaItem = k.idPlanilhaItem")
                ->where("c1.idPronac = k.idPronac")
                ->group("c1.idPlanilhaItem")
            ;

            $a = [
                "($subSql) AS vlComprovado",
                new Zend_Db_Expr("CASE WHEN k.idProduto = 0 THEN 'Administração do Projeto' ELSE c.Descricao END as Produto"),
                "ROUND((k.QtItem * k.nrOcorrencia * k.VlUnitario),2) as vlAprovado",
                "(a.AnoProjeto+a.Sequencial) as PRONAC",
                "a.NomeProjeto",
                "a.idPronac",
                "d.Descricao as Etapa",
                "d.tpGrupo",
                "e.Descricao as Unidade",
                "f.Municipio",
                "f.UF",
                "i.Descricao as Item",
                "k.QtDias as QtdeDias",
                "k.QtItem as Quantidade",
                "k.dsJustificativa",
                "k.idAgente",
                "k.idEtapa",
                "k.idPlanilhaAprovacao",
                "k.idPlanilhaAprovacaoPai",
                "k.idProduto",
                "k.nrFonteRecurso as idFonte",
                "k.nrOcorrencia as Ocorrencia",
                "k.vlUnitario",
                "x.Descricao as FonteRecurso",
            ];

            $sql = $db->select()->from(['a' => 'Projetos'], $a, $this->_schema)
                ->join(['k' => 'tbPlanilhaAprovacao'], '(a.idPronac = k.idPronac)', null, $this->schema)
                ->join(['z' => 'tbPlanilhaProposta'], '(k.idPlanilhaProposta=z.idPlanilhaProposta)', null, $this->schema)
                ->join(['c' => 'Produto'], '(k.idProduto = c.Codigo)', null, $this->schema)
                ->join(['d' => 'tbPlanilhaEtapa'], '(k.idEtapa = d.idPlanilhaEtapa)', null, $this->schema)
                ->join(['e' => 'tbPlanilhaUnidade'], '(k.idUnidade = e.idUnidade)', null, $this->schema)
                ->join(['i' => 'tbPlanilhaItens'], '(k.idPlanilhaItem=i.idPlanilhaItens)', null, $this->schema)
                ->join(['x' => 'Verificacao'], '(k.nrFonteRecurso = x.idVerificacao)', null, $this->schema)
                ->join(['f' => 'vUfMunicipio'], '(k.idUfDespesa = f.idUF and k.idMunicipioDespesa = f.idMunicipio)', null, 'agentes.dbo')
                ->where("k.stAtivo = 'N'")
                ->where("k.tpPlanilha = 'RP'")
                ->where("((ROUND((k.qtItem * k.nrOcorrencia * k.vlUnitario),2) <> 0) OR (k.dsJustificativa IS NOT NULL))")
                ->where("a.idPronac = ?", $idPronac)
                ->order("x.Descricao")
                ->order('c.Descricao DESC')
                ->order("CONVERT(VARCHAR(8),d.idPlanilhaEtapa) + ' - ' + d.Descricao")
                ->order('f.UF')
                ->order('f.Municipio')
                ->order('i.Descricao')
            ;
        } else {

            $subA = [
                "sum(b1.vlComprovacao) AS vlPagamento",
            ];

            $subSql = $db->select()->from(['a1' => 'tbComprovantePagamentoxPlanilhaAprovacao'], $subA, 'BDCORPORATIVO.scSAC')
                ->join(['b1' => 'tbComprovantePagamento'], '(a1.idComprovantePagamento = b1.idComprovantePagamento)', null, 'BDCORPORATIVO.scSAC')
                ->join(['c1' => 'tbPlanilhaAprovacao'], '(a1.idPlanilhaAprovacao = c1.idPlanilhaAprovacao)', null, $this->schema)
                ->where("c1.idPlanilhaItem = k.idPlanilhaItem")
                ->where("c1.idPronac = k.idPronac")
                ->group("c1.idPlanilhaItem")
            ;

            $c = [
                "($subSql) AS vlComprovado",
                new Zend_Db_Expr("CASE WHEN k.idProduto = 0 THEN 'Administração do Projeto' ELSE c.Descricao END as Produto"),
                "ROUND((k.QtItem * k.nrOcorrencia * k.VlUnitario),2) as vlAprovado",
                "(a.AnoProjeto+a.Sequencial) as PRONAC",
                "a.NomeProjeto",
                "a.idPronac",
                "d.Descricao as Etapa",
                "d.tpGrupo",
                "e.Descricao as Unidade",
                "f.Municipio",
                "f.UF",
                "i.Descricao as Item",
                "k.QtDias as QtdeDias",
                "k.QtItem as Quantidade",
                "k.dsJustificativa",
                "k.idAgente",
                "k.idEtapa",
                "k.idPlanilhaAprovacao",
                "k.idPlanilhaAprovacaoPai",
                "k.idProduto",
                "k.nrFonteRecurso as idFonte",
                "k.nrOcorrencia as Ocorrencia",
                "k.vlUnitario",
                "x.Descricao as FonteRecurso",
            ];

            $sql = $db->select()->from(['a' => 'Projetos'], $c, $this->_schema)
                ->join(['k' => 'tbPlanilhaAprovacao'], '(a.idPronac = k.idPronac)', null, $this->schema)
                ->join(['z' => 'tbPlanilhaProposta'], '(k.idPlanilhaProposta=z.idPlanilhaProposta)', null, $this->schema)
                ->join(['c' => 'Produto'], '(k.idProduto = c.Codigo)', null, $this->schema)
                ->join(['d' => 'tbPlanilhaEtapa'], '(k.idEtapa = d.idPlanilhaEtapa)', null, $this->schema)
                ->join(['e' => 'tbPlanilhaUnidade'], '(k.idUnidade = e.idUnidade)', null, $this->schema)
                ->join(['i' => 'tbPlanilhaItens'], '(k.idPlanilhaItem=i.idPlanilhaItens)', null, $this->schema)
                ->join(['x' => 'Verificacao'], '(k.nrFonteRecurso = x.idVerificacao)', null, $this->schema)
                ->join(['f' => 'vUfMunicipio'], '(k.idUfDespesa = f.idUF and k.idMunicipioDespesa = f.idMunicipio)', null, 'agentes.dbo')
                ->where("k.stAtivo = 'S'")
                ->where("k.tpPlanilha = 'RP'")
                ->where("((ROUND((k.qtItem * k.nrOcorrencia * k.vlUnitario),2) <> 0) OR (k.dsJustificativa IS NOT NULL))")
                ->where("a.idPronac = ?", $idPronac)
                ->order("x.Descricao")
                ->order('c.Descricao DESC')
                ->order("CONVERT(VARCHAR(8),d.idPlanilhaEtapa) + ' - ' + d.Descricao")
                ->order('f.UF')
                ->order('f.Municipio')
                ->order('i.Descricao')
            ;
        }

        return $db->fetchAll($sql);
    }

    public function readequacao($idPronac)
    {
        $db = Zend_Db_Table::getDefaultAdapter();

        $a = ["*",];

        $sql = $db->select()->from(['a' => 'tbPlanilhaAprovacao'], $a, $this->_schema)
            ->join(['b' => 'tbReadequacao'], '(a.idPronac = b.idPronac)', null, $this->schema)
            ->where("a.idPronac = ?", $idPronac)
            ->where("a.stAtivo = 'N'")
            ->where("a.tpPlanilha = 'SR'")
            ->where("b.idTipoReadequacao = 2")
            ->where("b.siEncaminhamento <> 15")
            ->where("b.stEstado = 0")
            ->limit(1)
        ;

        $readequacao = $db->fetchAll($sql);

        if (!empty($readequacao)) {
            $subA = [
                "sum(b1.vlComprovacao) AS vlPagamento",
            ];

            $subSQL = $db->select()->from(['a1' => 'tbComprovantePagamentoxPlanilhaAprovacao'], $subA, 'BDCORPORATIVO.scSAC')
                ->join(['b1' => 'tbComprovantePagamento'], '(a1.idComprovantePagamento = b1.idComprovantePagamento)', null, 'BDCORPORATIVO.scSAC')
                ->join(['c1' => 'tbPlanilhaAprovacao'], '(a1.idPlanilhaAprovacao = c1.idPlanilhaAprovacao)', null, $this->schema)
                ->where("c1.idPlanilhaItem = k.idPlanilhaItem")
                ->where("c1.idPronac = k.idPronac")
                ->group("c1.idPlanilhaItem")
            ;

            $a = [
                "($subSQL) as vlComprovado",
                new Zend_Db_Expr("CASE WHEN k.idProduto = 0 THEN 'Administração do Projeto' ELSE c.Descricao END as Produto"),
                "ROUND((k.QtItem * k.nrOcorrencia * k.VlUnitario),2) as vlAprovado",
                "( a.AnoProjeto+a.Sequencial ) as PRONAC",
                "a.NomeProjeto",
                "a.idPronac",
                "d.Descricao as Etapa",
                "d.tpGrupo",
                "e.Descricao as Unidade",
                "f.Municipio",
                "f.UF",
                "i.Descricao as Item",
                "k.QtDias as QtdeDias",
                "k.QtItem as Quantidade",
                "k.dsJustificativa",
                "k.idAgente",
                "k.idEtapa",
                "k.idPlanilhaAprovacao",
                "k.idPlanilhaAprovacaoPai",
                "k.idProduto",
                "k.nrFonteRecurso as idFonte",
                "k.nrOcorrencia as Ocorrencia",
                "k.tpAcao",
                "k.vlUnitario",
                "x.Descricao as FonteRecurso",
            ];
            $sql = $db->select()->from(['a' => 'Projetos'], $a, $this->_schema)
                ->join(['k' => 'tbPlanilhaAprovacao'], '(a.idPronac = k.idPronac)', null, $this->schema)
                ->join(['c' => 'Produto'], '(k.idProduto = c.Codigo)', null, $this->schema)
                ->join(['d' => 'tbPlanilhaEtapa'], '(k.idEtapa = d.idPlanilhaEtapa)', null, $this->schema)
                ->join(['e' => 'tbPlanilhaUnidade'], '(k.idUnidade = e.idUnidade)', null, $this->schema)
                ->join(['i' => 'tbPlanilhaItens'], '(k.idPlanilhaItem=i.idPlanilhaItens)', null, $this->schema)
                ->join(['x' => 'Verificacao'], '(k.nrFonteRecurso = x.idVerificacao)', null, $this->schema)
                ->join(['f' => 'vUfMunicipio'], '(k.idUfDespesa = f.idUF and k.idMunicipioDespesa = f.idMunicipio)', null, 'agentes.dbo')
                ->where("k.stAtivo = 'N'")
                ->where("k.tpPlanilha = 'SR'")
                ->where("((ROUND((k.qtItem * k.nrOcorrencia * k.vlUnitario),2) <> 0) OR (k.dsJustificativa IS NOT NULL))")
                ->where("a.idPronac = ?", $idPronac)
                ->order("x.Descricao")
                ->order("c.Descricao DESC")
                ->order("f.UF")
                ->order("f.Municipio")
                ->order("i.Descricao")
                ->order("CONVERT(VARCHAR(8),d.idPlanilhaEtapa) + ' - ' + d.Descricao")
            ;
        } else {
            $subA = [
                "sum(b1.vlComprovacao) AS vlPagamento",
            ];

            $subSQL = $db->select()->from(['a1' => 'tbComprovantePagamentoxPlanilhaAprovacao'], $subA, 'BDCORPORATIVO.scSAC')
                ->join(['b1' => 'tbComprovantePagamento'], '(a1.idComprovantePagamento = b1.idComprovantePagamento)', null, 'BDCORPORATIVO.scSAC')
                ->join(['c1' => 'tbPlanilhaAprovacao'], '(a1.idPlanilhaAprovacao = c1.idPlanilhaAprovacao)', null, 'SAC.dbo')
                ->where("c1.idPlanilhaItem = k.idPlanilhaItem")
                ->where("c1.idPronac = k.idPronac")
                ->group("c1.idPlanilhaItem")
            ;

            $a = [
                "($subSQL) as vlComprovado",
                new Zend_Db_Expr("CASE WHEN k.idProduto = 0 THEN 'Administração do Projeto' ELSE c.Descricao END as Produto"),
                "ROUND((k.QtItem * k.nrOcorrencia * k.VlUnitario),2) as vlAprovado",
                "(a.AnoProjeto+a.Sequencial) as PRONAC",
                "a.NomeProjeto",
                "a.idPronac",
                "d.Descricao as Etapa",
                "d.tpGrupo",
                "e.Descricao as Unidade",
                "f.Municipio",
                "f.UF",
                "i.Descricao as Item",
                "k.QtDias as QtdeDias",
                "k.QtItem as Quantidade",
                "k.dsJustificativa",
                "k.idAgente",
                "k.idEtapa",
                "k.idPlanilhaAprovacao",
                "k.idPlanilhaAprovacaoPai",
                "k.idProduto",
                "k.nrFonteRecurso as idFonte",
                "k.nrOcorrencia as Ocorrencia",
                "k.tpAcao",
                "k.vlUnitario",
                "x.Descricao as FonteRecurso",
            ];

            $sql = $db->select()->from(['a' => 'Projetos'], $a, $this->_schema)
                ->join(['k' => 'tbPlanilhaAprovacao'], '(a.idPronac = k.idPronac)', null, $this->schema)
                ->join(['c' => 'Produto'], '(k.idProduto = c.Codigo)', null, $this->schema)
                ->join(['d' => 'tbPlanilhaEtapa'], '(k.idEtapa = d.idPlanilhaEtapa)', null, $this->schema)
                ->join(['e' => 'tbPlanilhaUnidade'], '(k.idUnidade = e.idUnidade)', null, $this->schema)
                ->join(['i' => 'tbPlanilhaItens'], '(k.idPlanilhaItem=i.idPlanilhaItens)', null, $this->schema)
                ->join(['x' => 'Verificacao'], '(k.nrFonteRecurso = x.idVerificacao)', null, $this->schema)
                ->join(['f' => 'vUfMunicipio'], '(k.idUfDespesa = f.idUF and k.idMunicipioDespesa = f.idMunicipio)', null, 'agentes.dbo')
                ->where("k.stAtivo = 'S'")
                ->where("k.tpPlanilha = 'SR'")
                ->where("k.tpAcao <> 'E'")
                ->where("((ROUND((k.qtItem * k.nrOcorrencia * k.vlUnitario),2) <> 0) OR (k.dsJustificativa IS NOT NULL))")
                ->where("a.idPronac = ? ", $idPronac)
                ->order("x.Descricao")
                ->order("c.Descricao DESC")
                ->order("f.UF")
                ->order("f.Municipio")
                ->order("i.Descricao")
                ->order("CONVERT(VARCHAR(8),d.idPlanilhaEtapa) + ' - ' + d.Descricao")
            ;
        }
        return $db->fetchAll($sql);
    }
}
