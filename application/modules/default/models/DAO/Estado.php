<?php

/**
 * Modelo Estado
 * @author Equipe RUP - Politec
 * @since 29/03/2010
 * @version 1.0
 * @package application
 * @subpackage application.models
 * @copyright � 2010 - Minist�rio da Cultura - Todos os direitos reservados.
 * @link http://www.cultura.gov.br
 */
class Estado extends Zend_Db_Table
{
    protected $_banco = "agentes";
    protected $_name = 'uf';
    protected $_schema = 'agentes';

    /**
     * @var Zend_Db_Table
     */
    private static $instancia;

    /**
     * Responsável por implementar o Singleton, retornando apenas uma instancia da classe
     * utilizando uma chamada estática.
     * @return Zend_Db_Table
     * @author Vinícius Feitosa da Silva <viniciusfesil@mail.com>
     */
    public static function obterInstancia() {
        if(!self::$instancia) {
            self::$instancia = new Estado();
        }
        return self::$instancia;
    }

    /**
     * Método para buscar os estados
     * @access public
     * @param void
     * @return array
     * @author Vinícius Feitosa da Silva <viniciusfesil@mail.com>
     */
    public static function buscar()
    {
        $objEstado = self::obterInstancia();
        $sql = 'SELECT idUF AS id, Sigla AS descricao ';
        $sql .= 'FROM ' . GenericModel::getStaticTableName($objEstado->_schema, $objEstado->_name);
        $sql .= ' ORDER BY Sigla';


        try {
            $db = Zend_Db_Table::getDefaultAdapter();
            $db->setFetchMode(Zend_DB::FETCH_OBJ);
            return $db->fetchAll($sql);
        } catch (Zend_Exception_Db $objException) {
            throw new Exception("Erro ao buscar Estados: " . $objException->getMessage(), 0, $objException);
        }
    }

    /**
     * Método para buscar os estados de acordo com a região
     * @access public
     * @param void
     * @return array
     * @author Vinícius Feitosa da Silva <viniciusfesil@mail.com>
     */
    public static function buscarRegiao($regiao)
    {
        $objEstado = self::obterInstancia();
        $sql = 'SELECT idUF AS id, Descricao AS descricao 
			FROM ' . GenericModel::getStaticTableName($objEstado->_schema, $objEstado->_name) . " 
			WHERE Regiao = '{$regiao}'
			ORDER BY Sigla";

        try {
            $db = Zend_Registry::get('db');
            $db->setFetchMode(Zend_DB::FETCH_OBJ);

            return $db->fetchAll($sql);
        } catch (Zend_Exception_Db $objException) {
            throw new Exception("Erro ao buscar Estados: " . $objException->getMessage(), 0, $objException);
        }

    }
}