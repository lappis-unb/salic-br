<?php

/**
 * Sobrescrita da classe para que possamos ajustar o schema de acordo com o Adapter utilizado.
 * Uma das nossas acoes e tornar os valores e indices informadoes em minusculo para funcionar
 * com os Adapters desejados.
 */
class MinC_Db_Table_Select extends Zend_Db_Table_Select
{

    protected $isUseSchema = true;

    protected $databaseAdapter;

    public function isUseSchema($isUseSchema)
    {
        $this->isUseSchema = $isUseSchema;
    }

    /**
     * MinC_Db_Table_Select constructor.
     * @param Zend_Db_Table_Abstract $table
     * @return
     */
    public function __construct(Zend_Db_Table_Abstract $table)
    {
        $this->databaseAdapter = Zend_Db_Table::getDefaultAdapter();
        return parent::__construct($table);
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
        if ($this->isUseSchema) {
            $schema = $this->getSchema($schema);
        }

        if ($this->databaseAdapter instanceof MinC_Db_Adapter_Pdo_Pgsql) {
            $cond = $this->databaseAdapter->treatWhereConditionsDoubleQuotes($cond);
        }

        return parent::join($name, $cond, $cols, $schema);
    }

    /**
     * @param array|string|Zend_Db_Expr $name
     * @param string $cond
     * @param array|string $cols
     * @param null $schema
     * @return Zend_Db_Select
     */
    public function joinInner($name, $cond, $cols = self::SQL_WILDCARD, $schema = null)
    {
        if ($this->isUseSchema) {
            $schema = $this->getSchema($schema);
        }

        if ($this->databaseAdapter instanceof MinC_Db_Adapter_Pdo_Pgsql) {
            $cond = $this->databaseAdapter->treatJoinConditionDoubleQuotes($cond);
        }

        return parent::joinInner($name, $cond, $cols, $schema);
    }

    /*
    * @param  array|string|Zend_Db_Expr $name The table name.
    * @param  string $cond Join on this condition.
    * @param  array|string $cols The columns to select from the joined table.
    * @param  string $schema The database name to specify, if any.
    * @return Zend_Db_Select This Zend_Db_Select object.
    */
    public function joinLeft($name, $cond, $cols = self::SQL_WILDCARD, $schema = null)
    {
        if ($this->isUseSchema) {
            $schema = $this->getSchema($schema);
        }

        if ($this->databaseAdapter instanceof MinC_Db_Adapter_Pdo_Pgsql) {
            $cond = $this->databaseAdapter->treatJoinConditionDoubleQuotes($cond);
            $cols = $this->databaseAdapter->treatColumnsDoubleQuotes($cols);
        }

        return parent::joinLeft($name, $cond, $cols, $schema);
    }

    /**
     * @param $strSchema
     * @return string
     */
    public function getSchema($strSchema)
    {
        if (!$strSchema) {
            $strSchema = $this->_info['schema'];
        }

        if ($this->databaseAdapter instanceof Zend_Db_Adapter_Pdo_Mssql) {
            if (!is_int(strpos($strSchema, '.')) && $strSchema != "dbo") {
                $strSchema = $strSchema . '.dbo';
            }
        }

        return $strSchema;
    }

    public function where($cond, $value = null, $type = null)
    {
        if ($this->databaseAdapter instanceof MinC_Db_Adapter_Pdo_Pgsql) {
            $cond = $this->databaseAdapter->treatWhereConditionsDoubleQuotes($cond);
        }

        $this->_parts[self::WHERE][] = $this->_where($cond, $value, $type, true);
        return $this;
    }
}