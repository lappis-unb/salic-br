<?php

class MinC_Db_Expr
{
    protected $_expression;

    public function __construct($function = null, $expression = null)
    {
        $this->$function($expression);
    }

    public function concat($a)
    {
        $db = Zend_Db_Table::getDefaultAdapter();
        if ($db instanceof Zend_Db_Adapter_Pdo_Mssql) {
            $this->_expression = ' + ';
            return;
        }
        $this->_expression = " || ";
    }

    /**
     * @return string The string of the SQL expression stored in this object.
     */
    public function __toString()
    {
        return $this->_expression;
    }

}
