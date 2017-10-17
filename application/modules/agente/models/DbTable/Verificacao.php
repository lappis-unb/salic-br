<?php

class Agente_Model_DbTable_Verificacao extends MinC_Db_Table_Abstract
{
    protected $_primary = 'idVerificacao';
    protected $_schema = 'agentes';
    protected $_name = 'Verificacao';

    const PROPOSTA_PARA_ANALISE_INICIAL = 96;
    const PROPOSTA_EM_CONFORMIDADE_VISUAL_OU_ANALISE_DOCUMENTAL = 97;
    const PROPOSTA_EM_ANALISE_FINAL = 128;

    function combosNatureza($idTipo)
    {
        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->from(
            array($this->_name), // 'v' => $this->_schema . '.' . $this->_name),
            array('idVerificacao', 'Descricao')
        );
        $select->where('idTipo = ?', $idTipo);
        return $this->fetchAll($select);

    }

}