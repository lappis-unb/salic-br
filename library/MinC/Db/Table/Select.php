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
    public function join(
        $name,
        $cond,
        $cols = self::SQL_WILDCARD,
        $schema = null
    ) {
    
        if ($this->isUseSchema) {
            $schema = $this->getSchema($schema);
        }

        if ($this->databaseAdapter instanceof MinC_Db_Adapter_Pdo_Pgsql) {
            $cond = $this->databaseAdapter->treatConditionDoubleQuotes($cond);
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
            $cond = $this->databaseAdapter->treatConditionDoubleQuotes($cond);
        }

        return parent::joinInner($name, $cond, $cols, $schema);
    }

    /**
     * @param  array|string|Zend_Db_Expr $name The table name.
     * @param  string $cond Join on this condition.
     * @param  array|string $cols The columns to select from the joined table.
     * @param  string $schema The database name to specify, if any.
     * @return Zend_Db_Select This Zend_Db_Select object.
     */
    public function joinLeft(
        $name,
        $cond,
        $cols = self::SQL_WILDCARD,
        $schema = null
    ) {
    
        if ($this->isUseSchema) {
            $schema = $this->getSchema($schema);
        }

        if ($this->databaseAdapter instanceof MinC_Db_Adapter_Pdo_Pgsql) {
            $cond = $this->databaseAdapter->treatConditionDoubleQuotes($cond);
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
            $cond = $this->databaseAdapter->treatConditionDoubleQuotes($cond);
        }

        $this->_parts[self::WHERE][] = $this->_where($cond, $value, $type, true);
        return $this;
    }

    protected function _renderColumns($sql)
    {
        if (!count($this->_parts[self::COLUMNS])) {
            return null;
        }

        $columns = array();
        foreach ($this->_parts[self::COLUMNS] as $columnEntry) {
            list($correlationName, $column, $alias) = $columnEntry;
            if ($column instanceof Zend_Db_Expr) {
                $columns[] = $this->_adapter->quoteColumnAs($column, $alias, true);
            } else {
                if ($column == self::SQL_WILDCARD) {
                    $column = new Zend_Db_Expr(self::SQL_WILDCARD);
                    $alias = null;
                }
                if (empty($correlationName)) {
                    $columns[] = $this->_adapter->quoteColumnAs($column, $alias, true);
                } else {
                    $columns[] = $this->_adapter->quoteColumnAs(array($correlationName, $column), $alias, true);
                }
            }
        }

        return $sql .= ' ' . implode(', ', $columns);
    }

    protected function _getQuotedTable($tableName, $correlationName = null)
    {
        if ($this->databaseAdapter instanceof MinC_Db_Adapter_Pdo_Pgsql) {
            return $this->databaseAdapter->quoteTableAs($tableName, $correlationName, true);
        }
        return $this->_adapter->quoteTableAs($tableName, $correlationName, true);
    }

    public function assemble()
    {
        if ($this->databaseAdapter instanceof MinC_Db_Adapter_Pdo_Pgsql) {
            $sql = self::SQL_SELECT;
            foreach (array_keys(self::$_partsInit) as $part) {
                $method = '_render' . ucfirst($part);
                if (method_exists($this, $method)) {
                    $sql = $this->$method($sql);
                }
            }
            $sql = str_ireplace('.dbo', '', $sql);
//
//            $arrayWhere = explode('WHERE', $sql);
//            if(count($arrayWhere) > 1) {
//                $andString = '';
//                $arrayAndCondition = explode('AND', $arrayWhere[1]);
//                if(count($arrayAndCondition) > 1) {
//                    foreach($arrayAndCondition as $andConditions) {
//                        if(!empty($andString)) {
//                            $andString .= 'AND';
//                        }
//                        $andString .= $this->databaseAdapter->treatWhereConditionsDoubleQuotes($andConditions);
//                    }
//                    $andString = ' AND ' . $andString;
//                }
//                $sql = $arrayWhere[0] . ' WHERE ' . $this->databaseAdapter->treatWhereConditionsDoubleQuotes($arrayWhere[1]);
//                $sql .= $andString;
//            }

            return $sql;
        } else {
            return parent::assemble();
        }
    }

    protected function _join(
        $type,
        $name,
        $cond,
        $cols,
        $schema = null
    ) {
        if ($this->databaseAdapter instanceof MinC_Db_Adapter_Pdo_Pgsql) {
            if (!in_array($type, self::$_joinTypes) && $type != self::FROM) {
                /**
                 * @see Zend_Db_Select_Exception
                 */
                require_once 'Zend/Db/Select/Exception.php';
                throw new Zend_Db_Select_Exception("Invalid join type '$type'");
            }
    
            if (count($this->_parts[self::UNION])) {
                require_once 'Zend/Db/Select/Exception.php';
                throw new Zend_Db_Select_Exception("Invalid use of table with " . self::SQL_UNION);
            }
    
            if (empty($name)) {
                $correlationName = $tableName = '';
            } elseif (is_array($name)) {
                // Must be array($correlationName => $tableName) or array($ident, ...)
                foreach ($name as $_correlationName => $_tableName) {
                    if (is_string($_correlationName)) {
                        // We assume the key is the correlation name and value is the table name
                        $tableName = $_tableName;
                        $correlationName = $_correlationName;
                    } else {
                        // We assume just an array of identifiers, with no correlation name
                        $tableName = $_tableName;
                        $correlationName = $this->_uniqueCorrelation($tableName);
                    }
                    break;
                }
            } elseif ($name instanceof Zend_Db_Expr|| $name instanceof Zend_Db_Select) {
                $tableName = $name;
                $correlationName = $this->_uniqueCorrelation('t');
            } elseif (preg_match('/^(.+)\s+AS\s+(.+)$/i', $name, $m)) {
                $tableName = $m[1];
                $correlationName = $m[2];
            } else {
                $tableName = $name;
                $correlationName = $this->_uniqueCorrelation($tableName);
            }
    
            // Schema from table name overrides schema argument
            // if (!is_object($tableName) && false !== strpos($tableName, '.')) {
            //     list($schema, $tableName) = explode('.', $tableName);
            // }
    
            $lastFromCorrelationName = null;
            if (!empty($correlationName)) {
                if (array_key_exists($correlationName, $this->_parts[self::FROM])) {
                    /**
                     * @see Zend_Db_Select_Exception
                     */
                    require_once 'Zend/Db/Select/Exception.php';
                    throw new Zend_Db_Select_Exception("You cannot define a correlation name '$correlationName' more than once");
                }
    
                if ($type == self::FROM) {
                    // append this from after the last from joinType
                    $tmpFromParts = $this->_parts[self::FROM];
                    $this->_parts[self::FROM] = array();
                    // move all the froms onto the stack
                    while ($tmpFromParts) {
                        $currentCorrelationName = key($tmpFromParts);
                        if ($tmpFromParts[$currentCorrelationName]['joinType'] != self::FROM) {
                            break;
                        }
                        $lastFromCorrelationName = $currentCorrelationName;
                        $this->_parts[self::FROM][$currentCorrelationName] = array_shift($tmpFromParts);
                    }
                } else {
                    $tmpFromParts = array();
                }
                $this->_parts[self::FROM][$correlationName] = array(
                    'joinType'      => $type,
                    'schema'        => $schema,
                    'tableName'     => $tableName,
                    'joinCondition' => $cond
                    );
                while ($tmpFromParts) {
                    $currentCorrelationName = key($tmpFromParts);
                    $this->_parts[self::FROM][$currentCorrelationName] = array_shift($tmpFromParts);
                }
            }
    
            // add to the columns from this joined table
            if ($type == self::FROM && $lastFromCorrelationName == null) {
                $lastFromCorrelationName = true;
            }
            $this->_tableCols(
                $correlationName, 
                $cols, 
                $lastFromCorrelationName
            );
    
            return $this;
        } else {
            return parent::_join(
                $type,
                $name,
                $cond,
                $cols,
                $schema
            );
        }
    }

    private function _uniqueCorrelation($name)
    {
        if ($this->databaseAdapter instanceof MinC_Db_Adapter_Pdo_Pgsql) {
            $c = $name;
            if (is_array($name)) {
                $c = end($name);
            }
            for ($i = 2; array_key_exists($c, $this->_parts[self::FROM]); ++$i) {
                $c = $name . '_' . (string) $i;
            }
            return $c;
        } else {
            return parent::_uniqueCorrelation($name);
        }
    }
}
