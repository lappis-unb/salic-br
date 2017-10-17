<?php

class Agente_Model_DbTable_Visao extends MinC_Db_Table_Abstract
{
    protected $_schema = 'agentes';
    protected $_name = 'Visao';
    protected $_primary = 'idVisao';

    public function buscarVisao($idAgente = null, $visao = null, $todasVisoes = false)
    {
        $db = Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);
        if ($todasVisoes) {
            $sql = 'select distinct "idVerificacao", "Descricao" from  "agentes"."Verificacao" where "IdTipo" = 16 and "Sistema" = 21';
            $dados = $db->fetchAll($sql);
        } else {
            $db = Zend_Db_Table::getDefaultAdapter();
            $db->setFetchMode(Zend_DB::FETCH_OBJ);

            $objSelect = $this->select();
            $objSelect->from(
                array('vis' => 'Visao'),
                array('idVisao', 'idAgente', 'Usuario', 'stAtivo', 'Visao'),
                $this->getSchema('agentes')
            );
            $objSelect->joinInner(
                array('ver' => 'Verificacao'),
                "ver.idVerificacao = vis.Visao",
                array('ver.Descricao', 'ver.idVerificacao'),
                $this->getSchema('agentes')
            );
            $objSelect->joinLeft(
                array('ttc' => 'tbTitulacaoConselheiro'),
                "ttc.idAgente =  vis.idAgente",
                array(),
                $this->getSchema('agentes')
            );
            $objSelect->joinLeft(
                array('ar' => 'Area'),
                "ttc.cdArea = ar.Codigo",
                array('Area' => 'ar.Descricao'),
                $this->getSchema('sac')
            );
            $objSelect->where('ver.IdTipo = ?', 16);
            $objSelect->where('Sistema = ?', 21);

            if (!empty($idAgente)) {
                $objSelect->where('vis.idAgente = ? ', $idAgente);
            }

            if (!empty($visao)) {
                $objSelect->where('vis.Visao = ? ', $visao);
            }
            $objSelect->order("2");
            $dados = $db->fetchAll($objSelect);

        }
        return $dados;
    }

    public function cadastrarVisao($dados)
    {
        $db = Zend_Db_Table::getDefaultAdapter();

        $schema = $this->getSchema('agentes') . '.' . $this->_name;
        $insert = $db->insert($schema, $dados);

        return $insert ? true : false;
    }

    public function alterarVisao($idAgente, $dados)
    {
        $where = "idAgente = " . $idAgente;

        $update = $this->update($dados, $where);
        if ($update) {
            return true;
        } else {
            return false;
        }
    }

    public function excluirVisao($idAgente)
    {
        $where = "idAgente = " . $idAgente; // condi��o para exclus�o
        $delete = $this->delete($where); // exclui
        if ($delete) {
            return true;
        }
    }
}
