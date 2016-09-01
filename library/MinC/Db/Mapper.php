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

    /**
     *
     * @name save
     * @param $model
     *
     * @author Ruy Junior Ferreira Silva <ruyjfs@gmail.com>
     * @since  01/09/2016
     *
     * @todo deixar a pk podendo ser array, atualmente so pode sendo string utilizando o reset.
     */
    public function save($model)
    {
        $table = $this->getDbTable();
        $pk = reset($table->getPrimary());
        $method = 'get' . ucfirst($pk);
        $pkValue = $model->$method();
        $data = array_filter($model->toArray());
        if (null === ($id = $pkValue)) {
            unset($data[$pk]);
            $this->getDbTable()->insert($data);
        } else {
            $this->getDbTable()->update($data, array('id = ?' => $id));
        }
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

    /**
     * Retorna o resultado com chave e valor apenas.
     *
     * @name fetchPairs
     * @param string $key
     * @param string $value
     * @param array $where
     * @param string $order
     * @return array
     *
     * @author Ruy Junior Ferreira Silva <ruyjfs@gmail.com>
     * @since  01/09/2016
     */
    public function fetchPairs($key, $value , array $where = [], $order = '')
    {
        if (empty($order)) $order = $value;

        $table = $this->getDbTable();
        $select = $table->select()
            ->setIntegrityCheck(false)
            ->order($order);

        foreach ($where as $column => $columnValue) {
            $select->where( $column. ' = ?', $columnValue);
        }

        $resultSet = $table->fetchAll($select);
        $resultSet = ($resultSet)? $resultSet->toArray() : array();
        $entries   = array();
        foreach ($resultSet as $row) {
            $row = array_change_key_case($row);
            $entries[$row[$key]] = $row[$value];
        }
        return $entries;
    }

    public function fetchAll()
    {
        $resultSet = $this->getDbTable()->fetchAll();
//        $entries   = array();
//        foreach ($resultSet as $row) {
//            $entry = new Agente_Model_DbTable_Agentes();
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
