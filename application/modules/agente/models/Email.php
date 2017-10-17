<?php

class Agente_Model_Email extends MinC_Db_Table_Abstract
{
    protected $_name = 'Internet';
    protected $_schema = 'agentes';
    protected $_primary = 'idInternet';

    public function buscar($idAgente)
    {

        try {
            $sql = "SELECT * ";
            $sql .= "FROM agentes.Internet ";
            $sql .= "WHERE idAgente = '" . $idAgente . "'";
            $db = Zend_Db_Table::getDefaultAdapter();
            $db->setFetchMode(Zend_DB::FETCH_OBJ);
            return $db->fetchAll($sql);
        } catch (Zend_Exception_Db $e) {
            throw $e;
        }

    }

    public static function cadastrar($dados)
    {
        try {
            $db = Zend_Db_Table::getDefaultAdapter();
            $db->setFetchMode(Zend_DB::FETCH_OBJ);
            $db->insert('agentes.Internet', $dados);
            $db->closeConnection();
            return true;
        } catch (Zend_Exception_Db $e) {
            throw $e;
        }
    }

    public function inserir($dados)
    {
        $db = Zend_Db_Table::getDefaultAdapter();

        try {
            $schema = $this->getSchema($this->_schema) . '.' . $this->_name;
            $inserir = $db->insert($schema, $dados);
            return true;
        } catch (Zend_Exception_Db $e) {
            $this->view->message = "Erro ao cadastrar E-mails do Proponente: " . $e->getMessage();
            return false;
        }
    }

    public static function excluir($id)
    {
        try {
            $sql = "DELETE FROM agentes.Internet WHERE idInternet = '$id'";

            $db = Zend_Db_Table::getDefaultAdapter();
            $db->setFetchMode(Zend_DB :: FETCH_OBJ);
        } catch (Zend_Exception_Db $e) {
            throw $e;
        }

        return $db->fetchAll($sql);
    }


    public static function excluirTodos($idAgente)
    {
        try {
            $sql = "DELETE FROM agentes.Internet WHERE idAgente =" . $idAgente;

            $db = Zend_Db_Table::getDefaultAdapter();
            $db->setFetchMode(Zend_DB :: FETCH_OBJ);
            $i = $db->query($sql);
        } catch (Zend_Exception_Db $e) {
            throw $e;
        }
    }

}
