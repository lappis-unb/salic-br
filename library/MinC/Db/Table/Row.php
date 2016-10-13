<?php

/**
 * @author: Wouerner <wouerner@gmail.com>
 * @author: Vinicius Feitosa da Silva <viniciusfesil@gmail.com>
 * @author: Jorge Luiz Junior <juninhoecb@gmail.com>
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

    /**
     * Set row field value
     *
     * @param  string $columnName The column key.
     * @param  mixed  $value      The value for the property.
     * @return void
     * @throws Zend_Db_Table_Row_Exception
     */
    public function __set($columnName, $value)
    {
        $columnName = $this->_transformColumn($columnName);
        if (!array_key_exists(strtolower($columnName), array_change_key_case($this->_data))) {
            require_once 'Zend/Db/Table/Row/Exception.php';
            throw new Zend_Db_Table_Row_Exception("Specified column \"$columnName\" is not in the row");
        }
        $this->_data[$columnName] = $value;
        $this->_modifiedFields[$columnName] = true;
    }
}
