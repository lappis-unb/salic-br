<?php
class Votante extends MinC_Db_Table_Abstract {

    protected $_banco = 'BDCORPORATIVO';
    protected $_name = 'BDCORPORATIVO.tbVotante';
    protected $_schema = 'SCsac';

    public function selecionarvotantes($idreuniao) {
            $select = $this->select();
            $select->setIntegrityCheck(false);
            $select->from(array('tbv' => $this->_schema . "." . $this->_name),
                    array
                        (
                        'tbv.idAgente'
                    )
            );
            $select->joinInner(
                    array('nm' => 'nomes'),
                    "nm.idagente = tbv.idagente",
                    array('nm.descricao'),
                    'agentes.dbo'
            );
            $select->where('tbv.idreuniao = ?', $idreuniao);
            $select->order('nm.descricao asc');
            return $this->fetchAll($select);
    }

}
