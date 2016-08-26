<?php

/**
 * Modelo Tipoendereco
 * @author Equipe RUP - Politec
 *
 * @since 29/03/2010
 * @version 1.0
 * @package application
 * @subpackage application.models
 * @copyright � 2010 - Minist�rio da Cultura - Todos os direitos reservados.
 * @link http://www.cultura.gov.br
 */
class Tipoendereco extends Zend_Db_Table
{
    protected $_banco = 'agentes';
    protected $_schema = 'agentes';
    protected $_name = 'verificacao';

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
            self::$instancia = new Tipoendereco();
        }
        return self::$instancia;
    }

    /**
     * Método para buscar todos os tipos de endereços
     * @access public
     * @author Vinícius Feitosa da Silva <viniciusfesil@mail.com>
     * @param void
     * @return array
     */
    public static function buscar()
    {
        $objEstado = self::obterInstancia();
        $sql = "SELECT idVerificacao AS id, descricao ";
        $sql .= "FROM  " . GenericModel::getStaticTableName($objEstado->_schema, $objEstado->_name);
        $sql .= " WHERE idtipo = 2 ";
        $sql .= "ORDER BY descricao;";

        try {
            $db = Zend_Db_Table::getDefaultAdapter();
            $db->setFetchMode(Zend_DB::FETCH_OBJ);
            return $db->fetchAll($sql);
        } catch (Zend_Exception_Db $objException) {
            throw new Exception("Erro ao buscar Tipos de Endere�os: " . $objException->getMessage(), 0, $objException);
        }

    }
}