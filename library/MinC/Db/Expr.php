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
}
