<?php

class Agente_Model_DbTable_TbCredenciamentoParecerista extends MinC_Db_Table_Abstract
{
    protected $_schema = 'agentes';
    protected $_name = 'tbCredenciamentoParecerista';
    protected $_primary = 'idCredenciamentoParecerista';

    public function carregar($idAgente)
    {
        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->from(array('c'=>$this->_name),
            array('c.idAgente', 'c.idCredenciamentoParecerista', 'c.siCredenciamento', 'c.qtPonto', 'c.idVerificacao'),
            $this->_schema
        );

        $select->joinInner(
            array('a'=>'Area'), 'a.Codigo = c.idCodigoArea::varchar',
            array('a.Descricao as area'),
            $this->getSchema('sac')
        );

        $select->joinInner(
            array('s'=>'Segmento'), 's.Codigo = c.idCodigoSegmento',
            array('s.Descricao as segmento'),
            $this->getSchema('sac')
        );

        $select->joinLeft(
            array('v'=>'Verificacao'), 'v.idVerificacao = c.idVerificacao',
            array('v.Descricao as nivel'),
            $this->_schema
        );

        $select->joinLeft(
            array('x'=>'Visao'), 'x.idAgente = c.idAgente',
            array('x.Visao'),
            $this->_schema
        );

        $select->where('c.idAgente = ?', $idAgente);
        $select->where('x.Visao = ?', 209);
        $select->order('a.Descricao');
        $select->order('s.Descricao');

        return $this->fetchAll($select);
    }

    public function QtdArea($idAgente)
    {
        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->from(
            array('A'=>$this->_name),
            array('count(distinct idCodigoArea) as qtd')
        );

        $select->where('A.idAgente = ?', $idAgente);
        $select->where('A.siCredenciamento = 1');
        
        return $this->fetchAll($select);
    }

    public function QtdSegmento($idAgente, $idSegmento)
    {
        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->from(
            array('A'=>$this->_name),
            array('count(distinct idCodigoSegmento) as qtd')
        );

        $select->where("A.idCodigoSegmento LIKE '".$idSegmento."%'");
        $select->where('A.idAgente = ?', $idAgente);
        $select->where('A.siCredenciamento = 1');
        
        return $this->fetchAll($select);
    }

    public function verificarCadastrado($idAgente, $idSegmento, $idArea)
    {
        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->from(array('A'=>$this->_name),
                      array('*')
        );

        $select->where('A.idCodigoArea = ?', $idArea);
        $select->where('A.idCodigoSegmento = ?', $idSegmento);
        $select->where('A.idAgente = ?', $idAgente);
        $select->where('A.siCredenciamento = 1');
        
        return $this->fetchAll($select);
    }


    public function inserirCredenciamento($dados)
    {
        $insert = $this->insert($dados);
        return $insert;
    }

    public function alteraCredenciamento($dados, $idCredenciamento)
    {
        $where = "idCredenciamentoParecerista = " . $idCredenciamento;
        
        return $this->update($dados, $where);
    }
    
    public function excluiCredenciamento($idCredenciamento)
    {
        $sql ="DELETE FROM agentes.tbCredenciamentoParecerista 
                WHERE idCredenciamentoParecerista = {$idCredenciamento}";
        
        $db = Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);
        if ($db->query($sql)) {
            return true;
        }
        return false;
    }
}
