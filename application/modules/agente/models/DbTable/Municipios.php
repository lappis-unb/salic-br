<?php

/**
 * Class Agente_Model_DbTable_Municipios
 *
 * @name Agente_Model_DbTable_Municipios
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
class Agente_Model_DbTable_Municipios extends MinC_Db_Table_Abstract
{
    protected $_name = 'municipios';
    protected $_schema = 'agentes';

    public function buscar($idUF, $idCidade = null)
    {
//        $sql = "SELECT idMunicipioIBGE AS id, Descricao AS descricao ";
//        $sql.= "FROM AGENTES.dbo.Municipios ";
//        $sql.= "WHERE idUFIBGE = " . $idUF . " ";
//
//
//        $sql.= "ORDER BY Descricao";
        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->from(
            $this->_name,
            array('idmunicipioibge as id',
            'descricao as descricao'),
            $this->_schema
        );

        $select->where('idufibge = ?',$idUF);

        if (!empty($idCidade))
        {
            $select->where('idmunicipioibge = ?',$idCidade);
        }

        $select->order('descricao');

        try
        {
            $db = Zend_Db_Table::getDefaultAdapter();
            $db->setFetchMode(Zend_DB::FETCH_OBJ);
        }
        catch (Zend_Exception_Db $e)
        {
            $this->view->message = "Erro ao buscar Cidades: " . $e->getMessage();
        }
        //xd($sql);
        return $db->fetchAll($select);
    } // fecha buscar()
}