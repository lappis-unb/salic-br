<?php

/**
 * MinC_Db_Expr
 *
 * @author  wouerner <wouerner@gmail.com>
 */
class MinC_Db_Expr
{
    public static function concat()
    {
        $db = Zend_Db_Table::getDefaultAdapter();
        if ($db instanceof Zend_Db_Adapter_Pdo_Mssql) {
             return ' + ';
        }
        return " || ";
    }

    public static function date()
    {
        if (Zend_Db_Table::getDefaultAdapter() instanceof Zend_Db_Adapter_Pdo_Mssql) {
            return new Zend_Db_Expr('GETDATE()');
        } else {
            return new Zend_Db_Expr('NOW()');
        }
    }

    public function convertOrToChar($strColumn)
    {
        if (Zend_Db_Table::getDefaultAdapter() instanceof Zend_Db_Adapter_Pdo_Mssql) {
            return new Zend_Db_Expr('CONVERT(VARCHAR(max), ' . $strColumn . ')');
        } else {
            return new Zend_Db_Expr('(' . $strColumn .'::text )');
        }
    }
}
