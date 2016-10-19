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
     * Metodo para buscar os estados
     * @access public
     * @param void
     * @return array
     * @author Vinicius Feitosa da Silva <viniciusfesil@mail.com>
     */
    public function buscar()
    {

        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->from(
            array('uf' => $this->_name),
            array('iduf as id',
                'sigla as descricao'
                ),
            $this->_schema
        );

        $select->order('sigla');


        try {
            $db = Zend_Db_Table::getDefaultAdapter();
            $db->setFetchMode(Zend_DB::FETCH_OBJ);
            return $db->fetchAll($select);
        } catch (Zend_Exception_Db $objException) {
            throw new Exception("Erro ao buscar Estados: " . $objException->getMessage(), 0, $objException);
        }
    }

    /**
     * Metodo para buscar os estados de acordo com a regiao
     * @access public
     * @param void
     * @return array
     * @author Vinicius Feitosa da Silva <viniciusfesil@mail.com>
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