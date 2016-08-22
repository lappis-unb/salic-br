<?php

/**
 * Modelo Tipologradouro
 * @author Equipe RUP - Politec
 * @since 29/03/2010
 * @version 1.0
 * @package application
 * @subpackage application.models
 * @copyright � 2010 - Minist�rio da Cultura - Todos os direitos reservados.
 * @link http://www.cultura.gov.br
 */
class Tipologradouro extends Zend_Db_Table
{
    protected $_banco = "agentes";
    protected $_name = 'verificacao';
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
    public static function obterInstancia()
    {
        if (!self::$instancia) {
            self::$instancia = new Tipologradouro();
        }
        return self::$instancia;
    }

    /**
     * Método para buscar todos os tipos de logradouros
     * @access public
     * @param void
     * @author Vinícius Feitosa da Silva <viniciusfesil@mail.com>
     * @return array
     */
    public static function buscar()
    {
        $objSingleton = self::obterInstancia();
        $sql = "SELECT idverificacao AS id, descricao ";
        $sql .= 'FROM  ' . GenericModel::getStaticTableName($objSingleton->_schema, $objSingleton->_name);
        $sql .= " WHERE idTipo = 13 ";
        $sql .= "ORDER BY descricao;";

        try {
            $db = Zend_Registry::get('db');
            $db->setFetchMode(Zend_DB::FETCH_OBJ);
        } catch (Zend_Exception_Db $objException) {
            throw new Exception("Erro ao buscar Tipos de Logradouro: " . $objException->getMessage(), 0, $objException);
        }

        return $db->fetchAll($sql);
    }
}