<?php

/**
 * Sobrescrita da classe para que possamos ajustar o schema de acordo com o Adapter utilizado.
 * Uma das nossas ações é tornar os valores e índices informadoes em minúsculo para funcionar
 * com os Adapters desejados.
 *
 * @author Wouerner <wouerner@gmail.com>
 * @author Vinícius Feitosa da Silva <viniciusfesil@mail.com>
 * @since 23/08/2016 15:43
 */
class MinC_Db_Table_Select extends Zend_Db_Table_Select
{
    /**
     * @param array|string|Zend_Db_Expr|Zend_Db_Table_Abstract $name
     * @param array|string|Zend_Db_Expr $cols
     * @param null $schema
     * @return Zend_Db_Table_Select
     */
    public function from($name, $cols = self::SQL_WILDCARD, $schema = null)
    {
        if ($schema && !is_array($name)) {
            $name = GenericModel::getStaticTableName($schema, $name);
            $schema = null;
        } elseif (is_array($name)) {
            $name = array_change_key_case($name);
            $name = array_map('strtolower', $name);
        }

        return parent::from($name, $cols, $schema);
    }

    /**
     * @param array|string|Zend_Db_Expr $name
     * @param string $cond
     * @param array|string $cols
     * @param null $schema
     * @return Zend_Db_Select
     */
    public function join($name, $cond, $cols = self::SQL_WILDCARD, $schema = null)
    {
        if ($schema && !is_array($name)) {
            $name = GenericModel::getStaticTableName($schema, $name);
            $schema = null;
        } elseif (is_array($name)) {
            $name = array_change_key_case($name);
            $name = array_map('strtolower', $name);
        }
        return parent::join($name, $cond, $cols, $schema);
    }

    /**
     * MinC_Db_Table_Select constructor.
     * @param Zend_Db_Table_Abstract $table
     * @return
     */
    public function __construct(Zend_Db_Table_Abstract $table)
    {
        return parent::__construct($table);
    }
}