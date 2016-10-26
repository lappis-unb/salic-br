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
	protected $_name   = 'tbPlanilhaEtapa';
	protected $_primary   = 'idplanilhaetapa';

    public function listarEtapasProdutos($idPreProjeto)
    {
        $db = Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        $sql = $db->select()
            ->from(['tbplanilhaetapa'], ['idplanilhaetapa as idEtapa', 'descricao as DescricaoEtapa'], $this->getSchema('sac'))
            ->where("tpCusto = 'P'")
        ;

        //$sql = " SELECT idPlanilhaEtapa as idEtapa, Descricao as DescricaoEtapa FROM SAC.dbo.tbPlanilhaEtapa WHERE tpCusto = 'P' ";

        return $db->fetchAll($sql);
    }

    public  function buscarEtapasCadastrarProdutos() {

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

    public  function buscarEtapasCusto() {
//        $sql = "SELECT
//		idplanilhaetapa ,
//		Descricao
//		FROM SAC..tbPlanilhaEtapa where tpcusto = 'A'";
//
//        $sql.= " ORDER BY Descricao ";

        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->from(
            array($this->_name),
            array(
                'idplanilhaetapa',
                'descricao'
            ),
            $this->_schema
        );
        $select->where('tpcusto = ?','A');
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

    public  function buscarCustosAdministrativos()
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

} // fecha class