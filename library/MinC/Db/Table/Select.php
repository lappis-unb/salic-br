<?php

/**
 * Sobrescrita da classe para que possamos ajustar o schema de acordo com o Adapter utilizado.
 * Uma das nossas acoes e tornar os valores e indices informadoes em minusculo para funcionar
 * com os Adapters desejados.
 *
 * @author Wouerner <wouerner@gmail.com>
 * @author Vinicius Feitosa da Silva <viniciusfesil@mail.com>
 * @author Ruy Junior Ferreira Silva <ruyjfs@gmail.com>
 * @since 23/08/2016 15:43
 */
class MinC_Db_Table_Select extends Zend_Db_Table_Select
{

    protected $isUseSchema = true;

    public function isUseSchema($isUseSchema) {
        $this->isUseSchema = $isUseSchema;
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

    /**
     * @param array|string|Zend_Db_Expr|Zend_Db_Table_Abstract $name
     * @param array|string|Zend_Db_Expr $cols
     * @param null $schema
     * @return Zend_Db_Table_Select
     */
    //public function from($name, $cols = self::SQL_WILDCARD, $schema = null)
    //{
        //if($this->isUseSchema) {
            //$schema = $this->getSchema($schema);
        //}

        //return parent::from($name, $cols, $schema);
    //}

    /**
     * @param array|string|Zend_Db_Expr $name
     * @param string $cond
     * @param array|string $cols
     * @param null $schema
     * @return Zend_Db_Select
     */
    public function join($name, $cond, $cols = self::SQL_WILDCARD, $schema = null)
    {
        if($this->isUseSchema) {
            $schema = $this->getSchema($schema);
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
        if($this->isUseSchema) {
            $schema = $this->getSchema($schema);
        }

        return parent::joinInner($name, $cond, $cols, $schema);
    }

    /**
     * @param array|string|Zend_Db_Expr $name
     * @param string $cond
     * @param array|string $cols
     * @param null $schema
     * @return Zend_Db_Select
     */
    //public function joinLeft($name, $cond, $cols = self::SQL_WILDCARD, $schema = null)
    //{
        //if($this->isUseSchema) {
            //$schema = $this->getSchema($schema);
        //}

        //return parent::joinLeft($name, $cond, $cols, $schema);
    //}


    /**
     * @param $strSchema
     * @return string
     */
    public function getSchema($strSchema)
    {
        if(!$strSchema) {
            $strSchema = $this->_info['schema'];
        }

        $db = Zend_Db_Table::getDefaultAdapter();
        if ($db instanceof Zend_Db_Adapter_Pdo_Mssql) {
            if (!is_int(strpos($strSchema, '.')) && $strSchema != "dbo") {
                $strSchema = $strSchema . '.dbo';
            }
        }

        return $strSchema;
    }
}
