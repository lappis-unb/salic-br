<?php
/**
 * Description of GenericModel
 *
 * @author augusto
 */
require_once 'Zend/Db/Table/Abstract.php';

abstract class MinC_Db_Table_Abstract extends Zend_Db_Table_Abstract
{

    private $_config;
    protected $_rowClass = "MinC_Db_Table_Row";

    public function init()
    {
        # Tratando o nome da tabela conforme o tipo de banco.
        $this->_name = self::getName($this->_name);
        $this->_banco = self::getBanco($this->_banco);
        $this->_schema = self::getSchema($this->_schema);
    }

    public function getPrimary()
    {
        return (isset($this->_primary))? $this->_primary : '';
    }

    public function getSequence()
    {
        return (isset($this->_sequence))? $this->_sequence : true;
    }

    /**
     * GenericModel constructor.
     *
     * @todo verificar um tipo de SET TEXTSIZE 2147483647 para usar com o Postgres tambem.
     */
    public function __construct()
    {
        # FECHANDO A CONEXAO EXISTENTE JA QUE UMA NOVA SERA ABERTA
        $dbAdapter = Zend_Db_Table::getDefaultAdapter();
        if ($dbAdapter instanceof Zend_Db_Adapter_Pdo_Mssql) {
            if (!empty($dbAdapter)) {
                $dbAdapter->closeConnection();
                unset ($dbAdapter);
            }

            if (!($this->_config instanceof Zend_Config_Ini)) {
//            $this->_config = new Zend_Config_Ini(
//                Zend_Registry::get('DIR_CONFIG'),
//                'conexao_' . strtolower($this->_banco),
//                array('allowModifications' => true,)
//            );
                $db = Zend_Db_Table::getDefaultAdapter();
                $arrConfig = $db->getConfig();

                $strDb = str_replace('.dbo', '', $this->_schema);
                $arrConfig['dbname'] = strtoupper($strDb);
                $this->_config = new Zend_Config(
                    array(
                        'db' => array(
                            'adapter' => 'PDO_MSSQL',
                            'params'  => array(
                                'username' => $arrConfig['username'],
                                'password' => $arrConfig['password'],
                                'dbname'   => $arrConfig['dbname'],
                                'host'     => $arrConfig['host'],
                                'port'     => $arrConfig['port'],
                                'charset'     => $arrConfig['charset'],
                            )
                        )
                    )
                );

                Zend_Registry::getInstance()->set('config', $this->_config);
                Zend_Db_Table::setDefaultAdapter(Zend_Db::factory($this->_config->db));

                # Setar o campo texto maior que 4096 caracteres aceitaveis por padrao no PHP
//                $this->_db->query('SET TEXTSIZE 2147483647');
            }
        }

        parent::__construct();
    }


    /**
     * @param string $strName
     * @return string
     *
     * @author Ruy Junior Ferreira Silva <ruyjfs@gmail.com>
     * @since 11/08/2016
     * @todo melhorar e amadurecer codigo
     */

    public function getBanco($strName = '')
    {
        $db = Zend_Db_Table::getDefaultAdapter();
        $strName = 'dbo';

        if (!($db instanceof Zend_Db_Adapter_Pdo_Mssql)) {
            $strName = $db->getConfig()['dbname'];
        }

        return $strName;
    }

    /**
     * @param $strName
     * @param null $strSchema
     *
     * @author Ruy Junior Ferreira Silva <ruyjfs@gmail.com>
     * @since 11/08/2016
     */
    public function getSchema($strSchema = null, $isReturnDb = true, $strNameDb = null)
    {
        $db = Zend_Db_Table::getDefaultAdapter();

        if ($db instanceof Zend_Db_Adapter_Pdo_Mssql) {
            if (is_null($strNameDb)) {
                $strNameDb = 'dbo';
            }

            if ($isReturnDb && strpos($strSchema, '.') === false) {
                if ($strSchema) {
                    $strSchema = $strSchema . "." . $strNameDb;
                } else {
                    $strSchema = $strNameDb;
                }
            } elseif (strpos($strSchema, '.') === false) {
                $strSchema = $strNameDb;
            }
        } else if (!$strSchema) {
            $strSchema = $this->_schema;
        }

        return $strSchema;
    }

    /**
     * @param string $strName
     * @param string $strSchema
     * @return string
     *
     * @author Ruy Junior Ferreira Silva <ruyjfs@gmail.com>
     * @since 11/08/2016
     *
     * @todo melhorar e amadurecer codigo
     */
    public function getName($strName = '', $strSchema = '')
    {
//        $db = Zend_Db_Table::getDefaultAdapter();

//        if ($strSchema === '') $strSchema = $this->_schema;
//        if ($strName === '') $strName = $this->_name;

//        if ($db->getConfig()['host'] != '10.1.20.44') {
//            $strName = ucfirst($strSchema) . '.dbo.' . $strName;
//            $strName = ucfirst($strName);
//        } else {
//            $strName = strtolower($strSchema) . '.' . $strName;
//            $strName = strtolower($strName);
//        }

        $strName = strtolower($strName);

        return $strName;
    }



    /**
     * @return string
     * @author Vinícius Feitosa da Silva <viniciusfesil@mail.com>
     */
    public function getTableName($schema = null, $tableName = null, $isReturnDb = true)
    {
        if ($schema === null) $schema = $this->getSchema($schema, $isReturnDb);
        if ($tableName === null) $tableName = $this->_name;

        return $schema . '.' . $this->getName($tableName);
    }

    /**
     * @return string
     * @author Vinícius Feitosa da Silva <viniciusfesil@mail.com>
     * @todo Implementar Inversão de controle + Singleton cascateado por Classes.
     */
    public static function getStaticTableName($schema = null, $tableName = null)
    {
        $db = Zend_Db_Table::getDefaultAdapter();

        if ($db instanceof Zend_Db_Adapter_Pdo_Mssql && $schema) {
            $arrayPedacos = explode('.', $schema);
            if (count($arrayPedacos) <= 1) {
                $schema = $schema . '.dbo';
            }
        }

        $tableName = strtolower($tableName);

        return $schema . '.' . $tableName;
    }



    public function __destruct()
    {
        $db = Zend_Db_Table::getDefaultAdapter();
        $db->closeConnection();
    }

    /**
     * Retorna registros do banco de dados
     * @param array $where - array com dados where no formato "nome_coluna_1"=>"valor_1","nome_coluna_2"=>"valor_2"
     * @param array $order - array com orders no formado "coluna_1 desc","coluna_2"...
     * @param int $tamanho - numero de registros que deve retornar
     * @param int $inicio - offset
     * @return Zend_Db_Table_Rowset_Abstract
     */
    public function buscar($where = array(), $order = array(), $tamanho = -1, $inicio = -1)
    {
        $select = $this->select();

        //adiciona quantos filtros foram enviados
        foreach ($where as $coluna => $valor) {
            $select->where($coluna, $valor);
        }


//        $select->where('cpf = ?', '00000000000');
        //adicionando linha order ao select
//        $select->order($order);

        // paginacao
//        if ($tamanho > -1) {
//            $tmpInicio = 0;
//            if ($inicio > -1) {
//                $tmpInicio = $inicio;
//            }
//            $select->limit($tamanho, $tmpInicio);
//        }
//        echo '<pre>';
//        var_dump(Zend_Db_Table::getDefaultAdapter());
//        var_dump($select->assemble());
//        var_dump($this->fetchAll($select));
//        var_dump($this->fetchAll($select)->toArray());
//        var_dump($this->fetchRow($select)->toArray());
//        exit;
//xd($slct->__toString());

        try {
            $this->fetchAll($select);
        } catch (Exception $e) {
            echo '<pre>';
            var_dump($select->assemble());
            var_dump($e->getMessage());
            exit;
        }

        return $this->fetchAll($select);
    }

    public function alterar($dados, $where, $dbg = false)
    {
        if ($dbg) {
            x($this->dbg($dados, $where));
        }
        $update = $this->update($dados, $where);
        return $update;
    }

    public function apagar($where)
    {
        $delete = $this->delete($where);
        return $delete;
    }

    public function inserir($dados, $dbg = null)
    {
        if ($dbg) {
            xd($this->dbg($dados));
        }
        $insert = $this->insert($dados);
        return $insert;
    }

    public function dbg($dados, $where = null)
    {
        if (!$where) {
            $sql = "INSERT INTO " . $this->_name . " (";
            $keys = array_keys($dados);
            $sql .= implode(',', $keys);
            $sql .= ")\n values ('";
            $values = array_values($dados);
            $sql .= implode("','", $values);
            $sql .= "');";
        } else {
            $sql = "UPDATE " . $this->_name . " SET ";
            foreach ($dados as $coluna => $valor) {
                $sql .= $coluna . " = '" . $valor . "', \n";
            }
            $sql .= "\n" . $where;
        }
        xd($sql);
    }

    protected function _getCols()
    {
        if (null === $this->_cols) {
            $this->_setupMetadata();
            $this->_cols = array_keys($this->_metadata);
            foreach ($this->_cols as $indice => $coluna) {
                $this->_cols[$indice] = strtolower($coluna);
            }
        }

        return $this->_cols;
    }

    /**
     * Initialize primary key from metadata.
     * If $_primary is not defined, discover primary keys
     * from the information returned by describeTable().
     *
     * @return void
     * @throws Zend_Db_Table_Exception
     */
    protected function _setupPrimaryKey()
    {

        if (!$this->_primary) {
            $this->_setupMetadata();
            $this->_primary = array();
            foreach ($this->_metadata as $col) {
                if ($col['PRIMARY']) {
                    $this->_primary[$col['PRIMARY_POSITION']] = $col['COLUMN_NAME'];
                    if ($col['IDENTITY']) {
                        $this->_identity = $col['PRIMARY_POSITION'];
                    }
                }
            }
            // if no primary key was specified and none was found in the metadata
            // then throw an exception.
            if (empty($this->_primary)) {
                require_once 'Zend/Db/Table/Exception.php';
                throw new Zend_Db_Table_Exception('A table must have a primary key, but none was found');
            }
        } else if (!is_array($this->_primary)) {
            $this->_primary = array(1 => $this->_primary);
        } else if (isset($this->_primary[0])) {
            array_unshift($this->_primary, null);
            unset($this->_primary[0]);
        }

        $this->_primary[1] = strtolower($this->_primary[1]);

        $cols = $this->_getCols();
        if (!array_intersect((array)$this->_primary, $cols) == (array)$this->_primary) {
            require_once 'Zend/Db/Table/Exception.php';
            throw new Zend_Db_Table_Exception("Primary key column(s) ("
                . implode(',', (array)$this->_primary)
                . ") are not columns in this table ("
                . implode(',', $cols)
                . ")");
        }

        $primary = (array)$this->_primary;
        $pkIdentity = $primary[(int)$this->_identity];

        /**
         * Special case for PostgreSQL: a SERIAL key implicitly uses a sequence
         * object whose name is "<table>_<column>_seq".
         */
        if ($this->_sequence === true && $this->_db instanceof Zend_Db_Adapter_Pdo_Pgsql) {
            $this->_sequence = $this->_db->quoteIdentifier("{$this->_name}_{$pkIdentity}_seq");
            if ($this->_schema) {
                $this->_sequence = $this->_db->quoteIdentifier($this->_schema) . '.' . $this->_sequence;
            }
        }
    }

    public function insert(array $data)
    {
        $arrayIndicesMinusculos = array_change_key_case($data);
        return parent::insert($arrayIndicesMinusculos);
    }

    /**
     * @param Zend_Db_Table_Abstract::SELECT_WITHOUT_FROM_PART $withFromPart
     * @return MinC_Db_Table_Select
     * @author Wouerner <wouerner@gmail.com>
     * @author Vinícius Feitosa da Silva <viniciusfesil@mail.com>
     */
    public function select($withFromPart = self::SELECT_WITHOUT_FROM_PART)
    {
        require_once 'Zend/Db/Table/Select.php';
        $select = new MinC_Db_Table_Select($this);
        if ($withFromPart == self::SELECT_WITH_FROM_PART) {
            $select->from($this->info(self::NAME), $this->_getCols(), $this->info(self::SCHEMA));
        }
        return $select;
    }

    /**
     *
     * @name findBy
     * @param array $where
     * @return array
     *
     * @author Ruy Junior Ferreira Silva <ruyjfs@gmail.com>
     * @author wouerner <wouerner@gmail.com>
     * @since  05/09/2016
     */
    public function findBy(array $where) {
        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->from($this->_name, $this->_getCols(), $this->_schema);
        foreach ($where as $columnName => $columnValue) {
            $select->where($columnName . ' = ?', trim($columnValue));
        }
        $result = $this->fetchRow($select);
        return ($result)? $result->toArray() : array();
    }

    /**
     * Deleta varios registros conforme o where.
     *
     * @name deleteBy
     * @param array $arrWhere - Array com os parametros para o where, onde chave do array e a coluna da tabela e o valor do array e o valor da coluna.
     * @return int - Quantidade de rows deletadas.
     * @throws Exception
     *
     * @author Ruy Junior Ferreira Silva <ruyjfs@gmail.com>
     * @since  06/09/2016
     */
    public function deleteBy(array $arrWhere) {

        if (empty($arrWhere)) throw new Exception('Parametro where vazio, voce nao vai querer deletar tudo da tabela vai?');
        $arrWhereNew = array();
        foreach ($arrWhere as $columnName => $columnValue) {
            $arrWhereNew[] = $this->getAdapter()->quoteInto($columnName . ' = ?', trim($columnValue));
        }
        return $this->delete($arrWhereNew);
    }

    /**
     *
     * @name findAll
     * @param array $where
     * @return array
     *
     * @author Ruy Junior Ferreira Silva <ruyjfs@gmail.com>
     * @since  05/09/2016
     */
    public function findAll(array $where = array(), array $order = array()) {
        $select = $this->select();
        $select->setIntegrityCheck(false);
        foreach ($where as $columnName => $columnValue) {
            $select->where($columnName . ' = ?', trim($columnValue));
        }
        if ($order) {
            $select->order($order);
        }
        $result = $this->fetchAll($select);
        return ($result)? $result->toArray() : array();
    }

    /**
     * @param Zend_Db_Table_Abstract::SELECT_WITHOUT_FROM_PART $withFromPart
     * @return MinC_Db_Table_Select
     * @author Wouerner <wouerner@gmail.com>
     * @author Ruy Junior Ferreira Silva <ruyjfs@gmail.com>
     * @author Vinícius Feitosa da Silva <viniciusfesil@mail.com>
     * @return string
     */
    public static function getConcatExpression()
    {
        $db = Zend_Db_Table::getDefaultAdapter();
        if ($db instanceof Zend_Db_Adapter_Pdo_Mssql) {
            return ' + ';
        }
        return " || ";
    }

    public function getExpressionDate()
    {
        if ($this->getAdapter() instanceof Zend_Db_Adapter_Pdo_Mssql) {
            return new Zend_Db_Expr('GETDATE()');
        } else {
            return new Zend_Db_Expr('NOW()');
        }
    }

    public function getExpressionToChar($strColumn, $strFormat = 'DD/MM/YYYY')
    {

        if ($this->getAdapter() instanceof Zend_Db_Adapter_Pdo_Mssql) {
            return new Zend_Db_Expr('CONVERT(CHAR(10), ' . $strColumn . ' , 103)');
        } else {
            return new Zend_Db_Expr('TO_CHAR(' . $strColumn . ', \'' . $strFormat . '\')');
        }
    }

    /**
     * Retorna o resultado com chave e valor apenas.
     *
     * @name fetchPairs
     * @param string $key
     * @param string $value
     * @param array $where
     * @param string $order
     * @return array
     *
     * @author Ruy Junior Ferreira Silva <ruyjfs@gmail.com>
     * @since  01/09/2016
     */
    public function fetchPairs($key, $value , array $where = [], $order = '')
    {
        if (empty($order)) $order = $value;

        $select = $this->select()
            ->setIntegrityCheck(false)
            ->order($order);

        foreach ($where as $column => $columnValue) {
            if (is_array($columnValue)) {
                $select->where( $column. ' IN (?)', $columnValue);
            } else {
                $select->where( $column. ' = ?', $columnValue);
            }
        }

        $resultSet = $this->fetchAll($select);
        $resultSet = ($resultSet)? $resultSet->toArray() : array();
        $entries   = array();
        foreach ($resultSet as $row) {
            $row = array_change_key_case($row);
            $entries[$row[$key]] = $row[$value];
        }
        return $entries;
    }

}
