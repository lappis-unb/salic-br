<?php

class MinC_Db_Table_Row extends Zend_Db_Table_Row
{
    public function __get($columnName){
        try{
            return parent::__get($columnName);
        }catch(Zend_Db_Table_Row_Exception $ex){
            return parent::__get(strtolower($columnName));
        }
    }
}
