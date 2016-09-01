<?php

/**
 * Class MinC_Db_Mapper
 *
 * @name MinC_Db_Mapper
 * @package default
 * @subpackage controllers
 * @version $Id$
 *
 * @author Ruy Junior Ferreira Silva <ruyjfs@gmail.com>
 * @since ${DATE}
 *
 * @copyright © 2012 - Ministerio da Cultura - Todos os direitos reservados.
 * @link http://salic.cultura.gov.br
 */
class MinC_Db_Mapper
{
    public $_dbTable;

    public function setDbTable($dbTable)
    {
        if (is_string($dbTable)) {
            $dbTable = new $dbTable();
        }
        if (!$dbTable instanceof Zend_Db_Table_Abstract) {
            throw new Exception('Invalid table data gateway provided');
        }
        $this->_dbTable = $dbTable;
        return $this;
    }

    public function getDbTable()
    {
//        if (null === $this->_dbTable) {
//            $this->setDbTable('Agente_Model_DbTable_Agentes');
//        }
        return $this->_dbTable;
    }

    public function save(Agente_Model_Agentes $agentes)
    {
//        $data = array(
//            'email'   => $agentes->getEmail(),
//            'comment' => $agentes->getComment(),
//            'created' => date('Y-m-d H:i:s'),
//        );
//
//        if (null === ($id = $agentes->getId())) {
//            unset($data['id']);
//            $this->getDbTable()->insert($data);
//        } else {
//            $this->getDbTable()->update($data, array('id = ?' => $id));
//        }
    }

    public function find()
    {
        echo '<pre>';
        var_dump('haha');
        exit;
        $result = $this->getDbTable()->find($id);
//        if (0 == count($result)) {
//            return;
//        }
//        $row = $result->current();
//        $agentes->setId($row->id)
//            ->setEmail($row->email)
//            ->setComment($row->comment)
//            ->setCreated($row->created);
        return $result;
    }

    public function fetchAll()
    {
        $resultSet = $this->getDbTable()->fetchAll();
//        $entries   = array();
//        foreach ($resultSet as $row) {
//            $entry = new Agente_Model_Agentes();
//            $entry->setId($row->id)
//                ->setEmail($row->email)
//                ->setComment($row->comment)
//                ->setCreated($row->created);
//            $entries[] = $entry;
//        }
//        echo '<pre>';
//        var_dump('$entry');
//        var_dump($entry);
//        exit;
//        return $entries;
        return $resultSet;
    }
}
