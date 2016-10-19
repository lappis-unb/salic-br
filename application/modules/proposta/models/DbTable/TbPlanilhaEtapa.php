<?php
/**
 * DAO tbPlanilhaEtapa
 * @author jeffersonassilva@gmail.com - XTI
 * @since 07/03/2014
 * @version 1.0
 * @package application
 * @subpackage application.model
 * @copyright � 2011 - Minist�rio da Cultura - Todos os direitos reservados.
 * @link http://www.cultura.gov.br
 */

class Proposta_Model_DbTable_TbPlanilhaEtapa extends MinC_Db_Table_Abstract
{
	protected $_schema = 'sac';
	protected $_name   = 'tbplanilhaetapa';
    protected $_primary = 'idplanilhaetapa';

    public  function buscarEtapasCusto() {
//        $sql = "SELECT
//		idplanilhaetapa ,
//		Descricao
//		FROM SAC..tbPlanilhaEtapa where tpcusto = 'A'";

//        $sql.= " ORDER BY Descricao ";
        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->from(
            array( $this->_name),
            array('idplanilhaetapa',
                'descricao'),
            $this->_schema
        );
        $select->where('tpcusto = ?', 'A');
        $select->order('descricao');

        try {
            $db = Zend_Db_Table::getDefaultAdapter();
            $db->setFetchMode(Zend_DB::FETCH_OBJ);
        }
        catch (Zend_Exception_Db $e) {
            $this->view->message = "Erro ao buscar Etapas: " . $e->getMessage();
        }
        //xd($sql);
        return $db->fetchAll($select);
    }

    public function listarCustosAdministrativos()
    {

//        $sql = $db->select()
//            ->from('tbplanilhaetapa', ['idplanilhaetapa as idEtapa', 'descricao as DescricaoEtapa'], $this->getSchema('sac'))
//            ->where("tpCusto = 'A' AND idPlanilhaEtapa <> 6")
//        ;

        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->from(
            array('pet'=>$this->_name),
            array(
                'idplanilhaetapa as idetapa',
                'descricao as descricaoEtapa'
            ),
            $this->_schema
        );
        $select->where('tpcusto = ?', 'A');
        $select->where('idplanilhaetapa != ? ', '6');

        $db = Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);
        return $db->fetchAll($select);
    }

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

} // fecha class