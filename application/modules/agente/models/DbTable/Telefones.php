<?php

class Agente_Model_DbTable_Telefones extends MinC_Db_Table_Abstract
{
    protected $_schema = 'agentes';
    protected $_name = 'Telefones';
    protected $_primary = 'idTelefone';

    public function buscarFones($idAgente = null)
    {
        $db = Zend_Db_Table::getDefaultAdapter();

        $tl = array(
            'tl.idTelefone',
            'tl.TipoTelefone',
            'tl.Numero',
            'tl.Divulgar',
            new Zend_Db_Expr('
                    CASE
                    WHEN "tl"."TipoTelefone" = 22 or "tl"."TipoTelefone" = 24
                    THEN \'Residencial\'
                    WHEN "tl"."TipoTelefone" = 23 or "tl"."TipoTelefone" = 25
                    THEN \'Comercial\'
                    WHEN "tl"."TipoTelefone" = 26
                    THEN \'Celular\'
                    WHEN "tl"."TipoTelefone" = 27
                    THEN \'Fax\'
                    END as dstelefone
            ')
        );

        $ddd = array(
            'ddd.Codigo as ddd',
            'ddd.Codigo as codigo',
        );

        $sql = $this->select()->distinct()
            ->from(array('tl' => $this->_name), $tl, $this->_schema)
            ->join(array('uf' => 'UF'), 'uf.idUF = tl.UF', array('uf.Sigla as ufsigla'), $this->_schema)
            ->join(array('ag' => 'Agentes'), 'ag.idAgente = tl.idAgente', array('ag.idAgente'), $this->_schema)
            ->joinLeft(
                array('ddd' => 'DDD'),
                new Zend_Db_Expr('tl.DDD::varchar = ddd.Codigo'),
                $ddd,
                $this->_schema
            );

        if (!empty($idAgente)) { // busca de acordo com o id do agente
            $sql->where('tl.idAgente = ?', $idAgente);
        }

        $db->setFetchMode(Zend_DB::FETCH_OBJ);
        return $db->fetchAll($sql);
    }

}