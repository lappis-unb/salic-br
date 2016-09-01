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

        $tipoPlanilha = 3;
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

    public function orcamentariaAprovadaAtiva($idPronac){

        //SELECT a.idPronac,a.AnoProjeto+a.Sequencial as PRONAC,a.NomeProjeto,k.idProduto,k.idPlanilhaAprovacao,k.tpPlanilha,
             //CASE
               //WHEN k.idProduto = 0
                    //THEN 'Administração do Projeto'
                    //ELSE c.Descricao
               //END as Produto,
             //b.idEtapa,d.Descricao as Etapa,k.idPlanilhaItem,i.Descricao as Item,k.idUfDespesa as idUF,k.idMunicipioDespesa as idMunicipio,
             //ROUND((z.Quantidade * z.Ocorrencia * z.ValorUnitario),2) as vlSolicitado,convert(varchar(max),z.dsJustificativa) as JustProponente,
             //ROUND((b.Quantidade * b.Ocorrencia * b.ValorUnitario),2) as vlSugerido,convert(varchar(max),b.Justificativa) as JustParecerista,
             //e.Descricao as Unidade,k.QtItem as Quantidade,k.nrOcorrencia as Ocorrencia,k.vlUnitario,k.QtDias as QtdeDias,
             //k.TpDespesa,k.TpPessoa,k.nrContrapartida,k.nrFonteRecurso as idFonte,x.Descricao as FonteRecurso,f.UF,f.Municipio,
             //ROUND((k.QtItem * k.nrOcorrencia * k.VlUnitario),2) as vlAprovado,
             //CASE
               //WHEN k.tpPlanilha = 'CO'
                  //THEN (SELECT sum(b1.vlComprovacao) AS vlPagamento
                    //FROM BDCORPORATIVO.scSAC.tbComprovantePagamentoxPlanilhaAprovacao AS a1
                    //INNER JOIN BDCORPORATIVO.scSAC.tbComprovantePagamento AS b1 ON (a1.idComprovantePagamento = b1.idComprovantePagamento)
                    //INNER JOIN SAC.dbo.tbPlanilhaAprovacao AS c1 ON (a1.idPlanilhaAprovacao = c1.idPlanilhaAprovacao)
                    //WHERE c1.stAtivo = 'S' AND c1.idPlanilhaAprovacao = k.idPlanilhaAprovacao AND (c1.idPronac = k.idPronac)
                    //GROUP BY a1.idPlanilhaAprovacao)
                  //ELSE
                     //(SELECT sum(b1.vlComprovacao) AS vlPagamento
                    //FROM BDCORPORATIVO.scSAC.tbComprovantePagamentoxPlanilhaAprovacao AS a1
                    //INNER JOIN BDCORPORATIVO.scSAC.tbComprovantePagamento AS b1 ON (a1.idComprovantePagamento = b1.idComprovantePagamento)
                    //INNER JOIN SAC.dbo.tbPlanilhaAprovacao AS c1 ON (a1.idPlanilhaAprovacao = c1.idPlanilhaAprovacaoPai)
                    //WHERE c1.stAtivo = 'S' AND c1.idPlanilhaAprovacaoPai = k.idPlanilhaAprovacaoPai AND (c1.idPronac = k.idPronac)
                    //GROUP BY a1.idPlanilhaAprovacao)
                 //END as vlComprovado,
             //CONVERT(varchar(max),k.dsJustificativa) as JustComponente
        //
       //FROM Projetos a
       //inner join tbplanilhaprojeto b on (a.idpronac = b.idpronac)
       //inner join tbplanilhaproposta z on (b.idplanilhaproposta=z.idplanilhaproposta)
       //inner join tbplanilhaaprovacao k on (b.idplanilhaproposta=k.idplanilhaproposta)
       //left join produto c on (b.idproduto = c.codigo)
       //inner join tbplanilhaetapa d on (k.idetapa = d.idplanilhaetapa)
       //inner join tbplanilhaunidade e on (b.idunidade = e.idunidade)
       //inner join tbplanilhaitens i on (b.idplanilhaitem=i.idplanilhaitens)
       //inner join verificacao x on (b.fonterecurso = x.idverificacao)
       //inner join agentes.dbo.vufmunicipio f on (b.ufdespesa = f.iduf and b.municipiodespesa = f.idmunicipio)
       //
       //where k.stativo = 's' and a.idpronac = @idpronac
       //order by x.descricao,c.descricao desc,convert(varchar(8),d.idplanilhaetapa) + ' - ' + d.descricao,f.uf,f.municipio,i.descricao

        $sql = $db->select()
            ->from(['a' => 'projetos'], $a, $sac)
            ->joinInner(['b' => 'tbplanilhaprojeto'], '(a.idpronac = b.idpronac)', $b, $sac)
            ->joinInner(['z' => 'tbplanilhaproposta'], '(b.idplanilhaproposta=z.idplanilhaproposta)', $b, $sac)
            ->joinInner(['k' => 'tbplanilhaaprovacao'], '(b.idplanilhaproposta=k.idplanilhaproposta)', $b, $sac)
            ->joinLeft(['c' => 'produto'], '(b.idproduto = c.codigo)', $b, $sac)
            ->joinInner(['d' => 'tbplanilhaetapa'], '(k.idetapa = d.idplanilhaetapa)', $b, $sac)
            ->joinInner(['e' => 'tbplanilhaunidade'], '(b.idunidade = e.idunidade)', $b, $sac)
            ->joinInner(['i' => 'tbplanilhaitens'], '(b.idplanilhaitem=i.idplanilhaitens)', $b, $sac)
            ->joinInner(['x' => 'verificacao'], '(b.fonterecurso = x.idverificacao)', $b, $sac)
            ->joinInner(['f' => 'vufmunicipio'], '(b.ufdespesa = f.iduf and b.municipiodespesa = f.idmunicipio)', $b, 'agentes.dbo')
            ->where("k.stativo = 's'")
            ->where('a.idpronac = ?', $idpronac)
            ->order('x.descricao')
            ->order("c.descricao desc")
            ->order("convert(varchar(8),d.idplanilhaetapa) + ' - ' + d.descricao")
            ->order('f.uf')
            ->order('f.municipio')
            ->order('i.descricao')
            ;
    }

    public function cortesOrcamentariosAprovados($idPronac)
    {
     //SELECT a.idPronac,a.AnoProjeto+a.Sequencial as PRONAC,a.NomeProjeto,k.idProduto,k.idPlanilhaAprovacao,b.idPlanilhaProjeto,
             //CASE
               //WHEN k.idProduto = 0
                    //THEN 'Administração do Projeto'
                    //ELSE c.Descricao
               //END as Produto,
             //b.idEtapa,d.Descricao as Etapa,i.Descricao as Item,
             //ROUND((z.Quantidade * z.Ocorrencia * z.ValorUnitario),2) as vlSolicitado,convert(varchar(max),z.dsJustificativa) as JustProponente,
             //ROUND((b.Quantidade * b.Ocorrencia * b.ValorUnitario),2) as vlSugerido,convert(varchar(max),b.Justificativa) as JustParecerista,
             //e.Descricao as Unidade,k.QtItem as Quantidade,k.nrOcorrencia as Ocorrencia,k.vlUnitario,k.QtDias as QtdeDias,
             //k.TpDespesa,k.TpPessoa,k.nrContrapartida,k.nrFonteRecurso as idFonte,x.Descricao as FonteRecurso,f.UF,f.Municipio,
             //ROUND((k.QtItem * k.nrOcorrencia * k.VlUnitario),2) as vlAprovado,
       //convert(varchar(max),k.dsJustificativa) as JustComponente
       //FROM Projetos a
       //INNER JOIN tbPlanilhaProjeto b on (a.idPronac = b.idPronac)
       //INNER JOIN tbPlanilhaProposta z on (b.idPlanilhaProposta=z.idPlanilhaProposta)
       //INNER JOIN tbPlanilhaAprovacao k on (b.idPlanilhaProposta=k.idPlanilhaProposta)
       //LEFT JOIN Produto c on (b.idProduto = c.Codigo)
       //INNER JOIN tbPlanilhaEtapa d on (k.idEtapa = d.idPlanilhaEtapa)
       //INNER JOIN tbPlanilhaUnidade e on (b.idUnidade = e.idUnidade)
       //INNER JOIN tbPlanilhaItens i on (b.idPlanilhaItem=i.idPlanilhaItens)
       //INNER JOIN Verificacao x on (b.FonteRecurso = x.idVerificacao)
       //INNER JOIN agentes.dbo.vUfMunicipio f on (b.UfDespesa = f.idUF and b.MunicipioDespesa = f.idMunicipio)
       //WHERE k.stAtivo = 'S'
            //AND (ROUND((z.Quantidade * z.Ocorrencia * z.ValorUnitario),2) <> ROUND((b.Quantidade * b.Ocorrencia * b.ValorUnitario),2) OR
                 //ROUND((z.Quantidade * z.Ocorrencia * z.ValorUnitario),2) <> ROUND((k.QtItem * k.nrOcorrencia * k.vlUnitario),2))
            //AND a.idPronac = @idPronac
       //ORDER BY x.Descricao,c.Descricao DESC,CONVERT(VARCHAR(8),d.idPlanilhaEtapa) + ' - ' + d.Descricao,f.UF,f.Municipio,i.Descricao
    }

    public function remanejamentoMenor20($idPronac){
      //IF NOT EXISTS( SELECT TOP 1 * FROM tbPlanilhaAprovacao WHERE idPronac = @idPronac AND stAtivo = 'S' AND tpPlanilha = 'RP')
         //BEGIN
           //SELECT a.idPronac,a.AnoProjeto+a.Sequencial as PRONAC,a.NomeProjeto,k.idProduto,k.idPlanilhaAprovacao,k.idPlanilhaAprovacaoPai,
                 //CASE
                   //WHEN k.idProduto = 0
                        //THEN 'Administração do Projeto'
                        //ELSE c.Descricao
                   //END as Produto,
                 //k.idEtapa,d.Descricao as Etapa,d.tpGrupo,i.Descricao as Item,k.nrFonteRecurso as idFonte,x.Descricao as FonteRecurso,
                 //e.Descricao as Unidade,k.QtItem as Quantidade,k.nrOcorrencia as Ocorrencia,k.vlUnitario,
                 //ROUND((k.QtItem * k.nrOcorrencia * k.VlUnitario),2) as vlAprovado,
                 //(SELECT sum(b1.vlComprovacao) AS vlPagamento
                         //FROM BDCORPORATIVO.scSAC.tbComprovantePagamentoxPlanilhaAprovacao AS a1
                         //INNER JOIN BDCORPORATIVO.scSAC.tbComprovantePagamento AS b1 ON (a1.idComprovantePagamento = b1.idComprovantePagamento)
                         //INNER JOIN SAC.dbo.tbPlanilhaAprovacao AS c1 ON (a1.idPlanilhaAprovacao = c1.idPlanilhaAprovacao)
                         //WHERE c1.idPlanilhaItem = k.idPlanilhaItem AND c1.idPronac = k.idPronac
                         //GROUP BY c1.idPlanilhaItem) as vlComprovado,
                 //k.QtDias as QtdeDias,f.UF,f.Municipio,k.dsJustificativa,k.idAgente
               //FROM Projetos a
               //INNER JOIN tbPlanilhaAprovacao k on (a.idPronac = k.idPronac)
               //INNER JOIN tbPlanilhaProposta z on (k.idPlanilhaProposta=z.idPlanilhaProposta)
               //LEFT JOIN Produto c on (k.idProduto = c.Codigo)
               //INNER JOIN tbPlanilhaEtapa d on (k.idEtapa = d.idPlanilhaEtapa)
               //INNER JOIN tbPlanilhaUnidade e on (k.idUnidade = e.idUnidade)
               //INNER JOIN tbPlanilhaItens i on (k.idPlanilhaItem=i.idPlanilhaItens)
               //INNER JOIN Verificacao x on (k.nrFonteRecurso = x.idVerificacao)
               //INNER JOIN agentes.dbo.vUfMunicipio f on (k.idUfDespesa = f.idUF and k.idMunicipioDespesa = f.idMunicipio)
               //WHERE k.stAtivo = 'N'
                    //AND k.tpPlanilha = 'RP'
                    //AND ((ROUND((k.qtItem * k.nrOcorrencia * k.vlUnitario),2) <> 0)
                         //OR (k.dsJustificativa IS NOT NULL))
                    //AND a.idPronac = @idPronac
               //ORDER BY x.Descricao,c.Descricao DESC,CONVERT(VARCHAR(8),d.idPlanilhaEtapa) + ' - ' + d.Descricao,f.UF,f.Municipio,i.Descricao
         //END
        //ELSE
         //BEGIN
           //SELECT a.idPronac,a.AnoProjeto+a.Sequencial as PRONAC,a.NomeProjeto,k.idProduto,k.idPlanilhaAprovacao,k.idPlanilhaAprovacaoPai,
				 //CASE
				   //WHEN k.idProduto = 0
						//THEN 'Administração do Projeto'
						//ELSE c.Descricao
				   //END as Produto,
				 //k.idEtapa,d.Descricao as Etapa,d.tpGrupo,i.Descricao as Item,k.nrFonteRecurso as idFonte,x.Descricao as FonteRecurso,
				 //e.Descricao as Unidade,k.QtItem as Quantidade,k.nrOcorrencia as Ocorrencia,k.vlUnitario,
				 //ROUND((k.QtItem * k.nrOcorrencia * k.VlUnitario),2) as vlAprovado,
				 //(SELECT sum(b1.vlComprovacao) AS vlPagamento
                         //FROM BDCORPORATIVO.scSAC.tbComprovantePagamentoxPlanilhaAprovacao AS a1
                         //INNER JOIN BDCORPORATIVO.scSAC.tbComprovantePagamento AS b1 ON (a1.idComprovantePagamento = b1.idComprovantePagamento)
                         //INNER JOIN SAC.dbo.tbPlanilhaAprovacao AS c1 ON (a1.idPlanilhaAprovacao = c1.idPlanilhaAprovacao)
                         //WHERE c1.idPlanilhaItem = k.idPlanilhaItem AND c1.idPronac = k.idPronac
                         //GROUP BY c1.idPlanilhaItem) as vlComprovado,
				 //k.QtDias as QtdeDias,f.UF,f.Municipio,k.dsJustificativa,k.idAgente
			   //FROM Projetos a
			   //INNER JOIN tbPlanilhaAprovacao k on (a.idPronac = k.idPronac)
			   //INNER JOIN tbPlanilhaProposta z on (k.idPlanilhaProposta=z.idPlanilhaProposta)
			   //LEFT JOIN Produto c on (k.idProduto = c.Codigo)
			   //INNER JOIN tbPlanilhaEtapa d on (k.idEtapa = d.idPlanilhaEtapa)
			   //INNER JOIN tbPlanilhaUnidade e on (k.idUnidade = e.idUnidade)
			   //INNER JOIN tbPlanilhaItens i on (k.idPlanilhaItem=i.idPlanilhaItens)
			   //INNER JOIN Verificacao x on (k.nrFonteRecurso = x.idVerificacao)
			   //INNER JOIN agentes.dbo.vUfMunicipio f on (k.idUfDespesa = f.idUF and k.idMunicipioDespesa = f.idMunicipio)
			   //WHERE k.stAtivo = 'S'
					//AND k.tpPlanilha = 'RP'
					//AND ((ROUND((k.qtItem * k.nrOcorrencia * k.vlUnitario),2) <> 0)
						 //OR (k.dsJustificativa IS NOT NULL))
					//AND a.idPronac = @idPronac
			   //ORDER BY x.Descricao,c.Descricao DESC,CONVERT(VARCHAR(8),d.idPlanilhaEtapa) + ' - ' + d.Descricao,f.UF,f.Municipio,i.Descricao
	   //END
   //END
    }

    public function readequacao($idPronac){
    //BEGIN
          //IF EXISTS(SELECT TOP 1 * FROM tbPlanilhaAprovacao a
                                   //INNER JOIN tbReadequacao b on (a.idPronac = b.idPronac)
                                   //WHERE a.idPronac = @idPronac
                                         //AND a.stAtivo = 'N'
                                         //AND a.tpPlanilha = 'SR'
                                         //AND b.idTipoReadequacao = 2
                                         //AND b.siEncaminhamento <> 15
                                         //AND b.stEstado = 0)
                                          //--AND b.siEncaminhamento IN (1,3,4,5,6,7,8,10,12,14))
             //BEGIN
               //SELECT a.idPronac,a.AnoProjeto+a.Sequencial as PRONAC,a.NomeProjeto,k.idProduto,k.idPlanilhaAprovacao,k.idPlanilhaAprovacaoPai,
                     //CASE
                       //WHEN k.idProduto = 0
                            //THEN 'Administração do Projeto'
                            //ELSE c.Descricao
                       //END as Produto,
                     //k.idEtapa,d.Descricao as Etapa,d.tpGrupo,i.Descricao as Item,k.nrFonteRecurso as idFonte,x.Descricao as FonteRecurso,
                     //e.Descricao as Unidade,k.QtItem as Quantidade,k.nrOcorrencia as Ocorrencia,k.vlUnitario,
                     //ROUND((k.QtItem * k.nrOcorrencia * k.VlUnitario),2) as vlAprovado,
                     //(SELECT sum(b1.vlComprovacao) AS vlPagamento
                             //FROM BDCORPORATIVO.scSAC.tbComprovantePagamentoxPlanilhaAprovacao AS a1
                             //INNER JOIN BDCORPORATIVO.scSAC.tbComprovantePagamento AS b1 ON (a1.idComprovantePagamento = b1.idComprovantePagamento)
                             //INNER JOIN SAC.dbo.tbPlanilhaAprovacao AS c1 ON (a1.idPlanilhaAprovacao = c1.idPlanilhaAprovacao)
                             //WHERE c1.idPlanilhaItem = k.idPlanilhaItem AND c1.idPronac = k.idPronac
                             //GROUP BY c1.idPlanilhaItem) as vlComprovado,
                     //k.QtDias as QtdeDias,f.UF,f.Municipio,k.dsJustificativa,k.idAgente,k.tpAcao
                   //FROM Projetos a
                   //INNER JOIN tbPlanilhaAprovacao k on (a.idPronac = k.idPronac)
                   //LEFT JOIN Produto c on (k.idProduto = c.Codigo)
                   //INNER JOIN tbPlanilhaEtapa d on (k.idEtapa = d.idPlanilhaEtapa)
                   //INNER JOIN tbPlanilhaUnidade e on (k.idUnidade = e.idUnidade)
                   //INNER JOIN tbPlanilhaItens i on (k.idPlanilhaItem=i.idPlanilhaItens)
                   //INNER JOIN Verificacao x on (k.nrFonteRecurso = x.idVerificacao)
                   //INNER JOIN agentes.dbo.vUfMunicipio f on (k.idUfDespesa = f.idUF and k.idMunicipioDespesa = f.idMunicipio)
                   //WHERE k.stAtivo = 'N'
                           //AND k.tpPlanilha = 'SR'
                        //AND ((ROUND((k.qtItem * k.nrOcorrencia * k.vlUnitario),2) <> 0)
                             //OR (k.dsJustificativa IS NOT NULL))
                        //AND a.idPronac = @idPronac
                   //ORDER BY x.Descricao,c.Descricao DESC,CONVERT(VARCHAR(8),d.idPlanilhaEtapa) + ' - ' + d.Descricao,f.UF,f.Municipio,i.Descricao
             //END
          //ELSE
             //BEGIN
               //SELECT a.idPronac,a.AnoProjeto+a.Sequencial as PRONAC,a.NomeProjeto,k.idProduto,k.idPlanilhaAprovacao,k.idPlanilhaAprovacaoPai,
                     //CASE
                       //WHEN k.idProduto = 0
                            //THEN 'Administração do Projeto'
                            //ELSE c.Descricao
                       //END as Produto,
                     //k.idEtapa,d.Descricao as Etapa,d.tpGrupo,i.Descricao as Item,k.nrFonteRecurso as idFonte,x.Descricao as FonteRecurso,
                     //e.Descricao as Unidade,k.QtItem as Quantidade,k.nrOcorrencia as Ocorrencia,k.vlUnitario,
                     //ROUND((k.QtItem * k.nrOcorrencia * k.VlUnitario),2) as vlAprovado,
                     //(SELECT sum(b1.vlComprovacao) AS vlPagamento
                             //FROM BDCORPORATIVO.scSAC.tbComprovantePagamentoxPlanilhaAprovacao AS a1
                             //INNER JOIN BDCORPORATIVO.scSAC.tbComprovantePagamento AS b1 ON (a1.idComprovantePagamento = b1.idComprovantePagamento)
                             //INNER JOIN SAC.dbo.tbPlanilhaAprovacao AS c1 ON (a1.idPlanilhaAprovacao = c1.idPlanilhaAprovacao)
                             //WHERE c1.idPlanilhaItem = k.idPlanilhaItem AND c1.idPronac = k.idPronac
                             //GROUP BY c1.idPlanilhaItem) as vlComprovado,
                     //k.QtDias as QtdeDias,f.UF,f.Municipio,k.dsJustificativa,k.idAgente,k.tpAcao
                   //FROM Projetos a
                   //INNER JOIN tbPlanilhaAprovacao k on (a.idPronac = k.idPronac)
                   //LEFT JOIN Produto c on (k.idProduto = c.Codigo)
                   //INNER JOIN tbPlanilhaEtapa d on (k.idEtapa = d.idPlanilhaEtapa)
                   //INNER JOIN tbPlanilhaUnidade e on (k.idUnidade = e.idUnidade)
                   //INNER JOIN tbPlanilhaItens i on (k.idPlanilhaItem=i.idPlanilhaItens)
                   //INNER JOIN Verificacao x on (k.nrFonteRecurso = x.idVerificacao)
                   //INNER JOIN agentes.dbo.vUfMunicipio f on (k.idUfDespesa = f.idUF and k.idMunicipioDespesa = f.idMunicipio)
                   //WHERE k.stAtivo = 'S'
                        //AND k.tpPlanilha = 'SR'
                        //AND k.tpAcao <> 'E'
                        //AND ((ROUND((k.qtItem * k.nrOcorrencia * k.vlUnitario),2) <> 0)
                             //OR (k.dsJustificativa IS NOT NULL))
                        //AND a.idPronac = @idPronac
                   //ORDER BY x.Descricao,c.Descricao DESC,CONVERT(VARCHAR(8),d.idPlanilhaEtapa) + ' - ' + d.Descricao,f.UF,f.Municipio,i.Descricao
           //END
       //END
    }
}
