<?php

/**
 * Class Agente_Model_DbTable_UF
 *
 * @name Agente_Model_DbTable_UF
 * @package Modules/Agente
 * @subpackage Models/DbTable
 * @version $Id$
 *
 * @author Ruy Junior Ferreira Silva <ruyjfs@gmail.com>
 * @since 01/09/2016
 *
 * @copyright © 2012 - Ministerio da Cultura - Todos os direitos reservados.
 * @link http://salic.cultura.gov.br
 */
class Agente_Model_DbTable_UF extends MinC_Db_Table_Abstract
{
    protected $_banco = "agentes";
    protected $_name = 'uf';
    protected $_schema = 'agentes';

    /**
     * Método para buscar os estados
     * @access public
     * @param void
     * @return array
     * @author Vinícius Feitosa da Silva <viniciusfesil@mail.com>
     */
    public function buscar()
    {
        $objEstado = self::obterInstancia();
        $sql = 'select iduf as id, sigla as descricao ';
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
    public function buscarRegiao($regiao)
    {
        $objEstado = self::obterInstancia();
        $sql = 'SELECT idUF AS id, Descricao AS descricao 
			FROM ' . GenericModel::getStaticTableName($objEstado->_schema, $objEstado->_name) . " 
			WHERE Regiao = '{$regiao}'
			ORDER BY Sigla";

        try {
            $db= Zend_Db_Table::getDefaultAdapter();
            $db->setFetchMode(Zend_DB::FETCH_OBJ);

            return $db->fetchAll($sql);
        } catch (Zend_Exception_Db $objException) {
            throw new Exception("Erro ao buscar Estados: " . $objException->getMessage(), 0, $objException);
        }

    }
}