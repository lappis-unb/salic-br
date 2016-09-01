<?php

/**
 * Class Agente_Model_AreaMapper
 *
 * @name Agente_Model_AreaMapper
 * @package Modules/Agente
 * @subpackage Models
 * @version $Id$
 *
 * @author Ruy Junior Ferreira Silva <ruyjfs@gmail.com>
 * @since 01/09/2016
 *
 * @copyright © 2012 - Ministerio da Cultura - Todos os direitos reservados.
 * @link http://salic.cultura.gov.br
 */
class Agente_Model_AreaMapper extends MinC_Db_Mapper
{
    public function __construct()
    {
        parent::setDbTable('Agente_Model_DbTable_Area');
    }

    /**
     * Retorna o resultado com chave e valor apenas.
     *
     * @name fetchPairs
     * @return array
     *
     * @author Ruy Junior Ferreira Silva <ruyjfs@gmail.com>
     * @since  01/09/2016
     */
    public function fetchPairs()
    {
        $table = $this->getDbTable();
        $select = $table->select()->setIntegrityCheck(false)->order(['descricao']);
        $resultSet = $table->fetchAll($select);
        $resultSet = ($resultSet)? $resultSet->toArray() : array();
        $entries   = array();
        foreach ($resultSet as $row) {
            $row = array_change_key_case($row);
            $entries[$row['codigo']] = $row['descricao'];
        }
        return $entries;
    }
}