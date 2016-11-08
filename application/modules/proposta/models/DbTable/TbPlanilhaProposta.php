<?php

/**
 * Class Proposta_Model_DbTable_TbDeslocamento
 *
 * @name Proposta_Model_DbTable_TbDeslocamento
 * @package Modules/Agente
 * @subpackage Models/DbTable
 *
 * @author Ruy Junior Ferreira Silva <ruyjfs@gmail.com>
 * @since 18/10/2016
 *
 * @link http://salic.cultura.gov.br
 */
class Proposta_Model_DbTable_TbPlanilhaProposta extends MinC_Db_Table_Abstract
{
    /**
     * _schema
     *
     * @var string
     * @access protected
     */
    protected $_schema = 'sac';

    /**
     * _name
     *
     * @var bool
     * @access protected
     */
    protected $_name = 'tbplanilhaproposta';

    /**
     * _primary
     *
     * @var bool
     * @access protected
     */
    protected $_primary = 'idplanilhaproposta';


    public function salvarNovoProduto($idProposta, $idProduto, $idEtapa, $idItem, $unidade, $quantidade, $ocorrencia, $vlunitario, $qtdDias, $fonte, $idUf, $idMunicipio, $justificativa,$idUsuario) {

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

            return $this->insert($dados);
        }
        catch (Exception $e) {
            die("ERRO" . $e->getMessage());
        }

    }

    public function buscarDadosEditarProdutos($idPreProjeto = null, $idEtapa = null, $idProduto = null, $idItem = null, $idPlanilhaProposta=null, $idUf = null, $municipio = null,
                                                     $unidade = null, $qtd = null, $ocorrencia = null, $valor = null, $qtdDias = null, $fonte = null) {

        $db = Zend_Db_Table::getDefaultAdapter();

        $pp = [
            'pp.idplanilhaproposta as idPlanilhaProposta',
            'pp.idetapa as idEtapa',
            'pp.ufdespesa AS IdUf',
            'pp.municipiodespesa as Municipio',
            'pp.idplanilhaitem AS idItem',
            'pp.fonterecurso as Recurso',
            'pp.quantidade as Quantidade',
            'pp.ocorrencia as Ocorrencia',
            'pp.valorunitario as ValorUnitario',
            'CAST(pp.dsjustificativa AS TEXT) as Justificativa',
            'pp.qtdedias as QtdDias',
            'pp.unidade as Unidade',
        ];

        $sacSchema = $this->_schema;
        $sql = $db->select()->from(['pre' => 'preprojeto' ],'pre.idpreprojeto as idProposta' , $sacSchema)
            ->join(['pp' => 'tbplanilhaproposta'], 'pre.idpreprojeto = pp.idprojeto', $pp, $sacSchema)
            ->join(['p' => 'produto'] , 'pp.idproduto = p.codigo', 'p.codigo AS CodigoProduto', $sacSchema)
            ->join(['ti' => 'tbplanilhaitens'], 'ti.idplanilhaitens = pp.idplanilhaitem', 'ti.descricao as DescricaoItem', $sacSchema)
            ->join(['uf' => 'uf' ], 'uf.CodUfIbge = pp.ufdespesa', 'uf.descricao AS DescricaoUf', $sacSchema)
            ->join(['mun' => 'municipios'], 'mun.idmunicipioibge = pp.municipiodespesa','mun.descricao as DescricaoMunicipio', $this->getSchema('agentes'))
            ->join(['pe' => 'tbplanilhaetapa'], 'pp.idetapa = pe.idplanilhaetapa', 'pe.descricao as DescricaoEtapa', $sacSchema)
            ->join(['rec' => 'verificacao'], 'rec.idverificacao = pp.fonterecurso', 'rec.descricao as DescricaoRecurso', $sacSchema)
            ->join(['uni' => 'tbplanilhaunidade'], 'uni.idunidade = pp.unidade', 'uni.descricao as DescricaoUnidade', $sacSchema)
            ->where('pp.idetapa = ?', $idEtapa)
        ;

        if($idPreProjeto){
            $sql->where('pre.idpreprojeto = ?', $idPreProjeto);
        }
        if($idProduto){
            $sql->where('p.codigo = ?', $idProduto);
        }
        if($idItem){
            $sql->where('pp.idplanilhaitem = ?', $idItem);
        }
        if($idPlanilhaProposta){
            $sql->where('pre.idpreprojeto  = ?', $idPreProjeto);
        }

        if($idUf){
            $sql->where('pp.ufdespesa = ?', $idUf);
        }

        if($municipio){
            $sql->where('pp.municipiodespesa = ?', $municipio);
        }

        if($unidade){
            $sql->where('pp.unidade = ?', $unidade);
        }

        if($qtd){
            $sql->where('pp.quantidade = ?', $qtd);
        }

        if($ocorrencia){
            $sql->where('pp.ocorrencia = ?', $ocorrencia);
        }

        if($valor){
            $sql->where('pp.valorunitario = ?', $valor);
        }

        if($qtdDias){
            $sql->where('pp.qtdedias = ?', $qtdDias);
        }

        if($fonte){
            $sql->where('pp.fonterecurso = ?', $fonte);
        }

        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        return $db->fetchAll($sql);
    }

    public function buscarUltimosDadosCadastrados() {

        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_ASSOC);

        $sql = $db->select()
            ->from($this->_name, '*' , $this->_schema)
            ->limit(1)
            ->order('idPlanilhaProposta DESC');
//        $sql = "
//			select
//				top 1 *
//			from
//				SAC.dbo.tbPlanilhaProposta
//			order by
//				idPlanilhaProposta
//			desc
//        ";

        return $db->fetchAll($sql);
    }

}