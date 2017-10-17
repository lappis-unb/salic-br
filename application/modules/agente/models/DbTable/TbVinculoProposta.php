<?php
class Agente_Model_DbTable_TbVinculoProposta extends MinC_Db_Table_Abstract
{
    protected $_name = 'tbVinculoProposta';
    protected $_schema = 'agentes';
    protected $_primary = 'idVinculoProposta';

    public function buscarResponsaveisProponentes($where = array())
    {
        $slct = $this->select();
        $slct->distinct();
        $slct->setIntegrityCheck(false);

        $slct->from(
            array('VP' => $this->_name),
            array('*')
        );

        $slct->joinInner(
            array('VI' => 'tbVinculo'), 'VI.idVinculo = VP.idVinculo',
            array('*')
        );

        foreach ($where as $coluna => $valor) {
            $slct->where($coluna, $valor);
        }

        return $this->fetchAll($slct);
    }

    public function retirarProjetosVinculos($siVinculoProposta, $idVinculo)
    {
        $where['idVinculo = ?'] = $idVinculo;

        return $this->update(array('siVinculoProposta' => $siVinculoProposta), $where);
    }
}

