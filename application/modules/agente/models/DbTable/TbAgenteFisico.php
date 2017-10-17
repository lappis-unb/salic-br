<?php
class Agente_Model_DbTable_TbAgenteFisico extends MinC_Db_Table_Abstract
{
    protected $_name = 'tbAgenteFisico';
    protected $_schema = 'agentes';
    protected $_primary = 'idAgente';

    public function cadastrarDados($dados) {
        return $this->insert($dados);
    }

    public function alterarDados($dados, $where) {
        $where = "idAgente = " . $where;
        return $this->update($dados, $where);
    }

}