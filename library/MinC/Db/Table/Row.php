<?php

/**
 * @author: Wouerner <wouerner@gmail.com>
 * @author: Vinícius Feitosa da Silva <viniciusfesil@gmail.com>
 * @author: Jorge Luiz Júnior <juninhoecb@gmail.com>
 * @since: 17/08/16 19:53
 */
class MinC_Db_Table_Row extends Zend_Db_Table_Row
{
    protected function _getPrimaryKey($useDirty = true)
    {
        if (!is_array($this->_primary)) {
            require_once 'Zend/Db/Table/Row/Exception.php';
            throw new Zend_Db_Table_Row_Exception("The primary key must be set as an array");
        }
        $primary = array_flip($this->_primary);
        if ($useDirty) {
            $this->_data = array_change_key_case($this->_data);
            $array = array_intersect_key($this->_data, $primary);
        } else {
            $this->_cleanData = array_change_key_case($this->_cleanData);
            $array = array_intersect_key($this->_cleanData, $primary);
        }

        if (count($primary) != count($array)) {
            require_once 'Zend/Db/Table/Row/Exception.php';
            throw new Zend_Db_Table_Row_Exception("The specified Table '$this->_tableClass' does not have the same primary key as the Row");
        }
        return $array;
    }
}