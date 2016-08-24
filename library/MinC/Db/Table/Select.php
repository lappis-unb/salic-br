<?php

/**
 * Sobrescrita da classe para que possamos ajustar o schema de acordo com o Adapter utilizado.
 * Uma das nossas ações é tornar os valores e índices informadoes em minúsculo para funcionar
 * com os Adapters desejados.
 *
 * @author Wouerner <wouerner@gmail.com>
 * @author Vinícius Feitosa da Silva <viniciusfesil@mail.com>
 * @author Ruy Junior Ferreira Silva <ruyjfs@gmail.com>
 * @since 23/08/2016 15:43
 */
class MinC_Db_Table_Select extends Zend_Db_Table_Select
{
    /**
     * MinC_Db_Table_Select constructor.
     * @param Zend_Db_Table_Abstract $table
     * @return
     */
    public function __construct(Zend_Db_Table_Abstract $table)
    {
        return parent::__construct($table);
    }

    private function prepareToLowerCase($name)
    {
        if (is_string($name)) {
            $name = strtolower($name);
        } elseif (is_array($name)) {
            $name = array_change_key_case($name);
            $name = array_map('strtolower', $name);
        }

        return $name;
    }

    /**
     * @param array|string|Zend_Db_Expr|Zend_Db_Table_Abstract $name
     * @param array|string|Zend_Db_Expr $cols
     * @param null $schema
     * @return Zend_Db_Table_Select
     */
    public function from($name, $cols = self::SQL_WILDCARD, $schema = null)
    {
        $schema = $this->getSchema($schema);
        $name = $this->prepareToLowerCase($name);
        $cols = $this->prepareToLowerCase($cols);

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
        $schema = $this->getSchema($schema);
        $name = $this->prepareToLowerCase($name);
        $cond = $this->prepareToLowerCase($cond);
        $cols = $this->prepareToLowerCase($cols);

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
        $schema = $this->getSchema($schema);
        $name = $this->prepareToLowerCase($name);
        $cond = $this->prepareToLowerCase($cond);
        $cols = $this->prepareToLowerCase($cols);

        return parent::joinInner($name, $cond, $cols, $schema);
    }

    /**
     * @param array|string|Zend_Db_Expr $name
     * @param string $cond
     * @param array|string $cols
     * @param null $schema
     * @return Zend_Db_Select
     */
    public function joinLeft($name, $cond, $cols = self::SQL_WILDCARD, $schema = null)
    {
        $schema = $this->getSchema($schema);
        $name = $this->prepareToLowerCase($name);
        $cond = $this->prepareToLowerCase($cond);
        $cols = $this->prepareToLowerCase($cols);

        return parent::joinLeft($name, $cond, $cols, $schema);
    }

    /**
     * @param string $cond
     * @param null $value
     * @param null $type
     * @return Zend_Db_Select
     */
    public function where($cond, $value = null, $type = null)
    {
        $cond = $this->prepareToLowerCase($cond);

        return parent::where($cond, $value, $type);
    }

    /**
     * @param $strSchema
     * @return string
     */
    public function getSchema($strSchema)
    {
        $db = Zend_Db_Table::getDefaultAdapter();

        if ($db instanceof Zend_Db_Adapter_Pdo_Mssql) {
            if (!is_int(strpos($strSchema, '.'))) {
                $strSchema = $strSchema . '.dbo';
            }
        }

        return $strSchema;
    }
}