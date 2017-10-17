<?php

class Agente_Model_DbTable_TbAusencia extends MinC_Db_Table_Abstract
{
    protected $_schema = 'agentes';
    protected $_name = 'tbAusencia';
    protected $_primary = 'idAusencia';

    public function carregarAusencia($idAgente, $ano, $tipo, $mes)
    {
        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->from(array('a'=>$this->_name),
                      array('*', $this->getExpressionToChar('dtInicioAusencia') . ' as dtinicio',
                            $this->getExpressionToChar('dtFimAusencia') . ' as dtfim',
                            $this->getExpressionDateDiff('dtInicioAusencia', 'dtFimAusencia') . ' as qtddias'
                      ),
                        $this->_schema
                      );

        $select->joinInner(
                array('n'=>'Nomes'), 'n.idAgente = a.idAgente',
                array('n.Descricao as nome'),
            $this->_schema
        );

        $select->joinInner(
                array('tp'=>'tbTipoAusencia'), 'tp.idTipoAusencia = a.idTipoAusencia',
                array('tp.nmAusencia as tipoausencia'),
            $this->_schema
        );

        $select->joinLeft(
                array('d'=>'scCorp.tbDocumento'), 'd.idDocumento = a.idDocumento',
                array('*'),
            $this->getschema('bdcorporativo')
        );

        $select->joinLeft(
                array('ta'=>'scCorp.tbArquivo'), 'ta.idArquivo = d.idArquivo',
                array('*'),
            $this->getschema('bdcorporativo')
        );

        $select->joinLeft(
                array('tai'=>'scCorp.tbArquivoImagem'), 'tai.idArquivo = ta.idArquivo',
                array('*'),
            $this->getschema('bdcorporativo')
        );
        $select->where('a.idTipoAusencia = ?', $tipo);
        if (!empty($idAgente)) {
            $select->where('a.idAgente = ?', $idAgente);
        }
        $select->where($this->getExpressionDatePart('dtInicioAusencia', 'year') . ' = ?', $ano);
        if (!empty($mes)) {
            $select->where($this->getExpressionDatePart('dtInicioAusencia') . ' = ?', $mes);
        }
        $select->order('a.idAlteracao');
        $select->order('a.idAusencia');
        return $this->fetchAll($select);
    }
    
    public function BuscarAusenciaPainel($ano)
    {
        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->from(array('A'=>$this->_name),
                      array('*','CONVERT(CHAR(10), dtInicioAusencia, 103) as dtInicio',
                            'CONVERT(CHAR(10), dtFimAusencia, 103) as dtFim',
                            'DATEDIFF ( DAY , dtInicioAusencia , dtFimAusencia ) as qtdDias'),
                        $this->_schema
                      );

        $select->joinInner(
                array('N'=>'Nomes'), 'N.idAgente = A.idAgente',
                array('N.Descricao as Nome'),
            $this->_schema
        );

        $select->joinInner(
                array('TP'=>'tbTipoAusencia'), 'TP.idTipoAusencia = A.idTipoAusencia',
                array('TP.nmAusencia as tipoAusencia'),
            $this->_schema
        );

        $select->joinLeft(
                array('D'=>'tbDocumento'), 'D.idDocumento = A.idDocumento',
                array('*'),
            $this->getSchema('bdcorporativo', true, 'sccorp')
        );

        $select->joinLeft(
                array('TA'=>'tbArquivo'), 'TA.idArquivo = D.idArquivo',
                array('*'),
            $this->getSchema('bdcorporativo', true, 'sccorp')
        );

        $select->joinLeft(
                array('TAI'=>'tbArquivoImagem'), 'TAI.idArquivo = TA.idArquivo',
                array('*'),
            $this->getSchema('bdcorporativo', true, 'sccorp')
        );
        
        $select->where('A.idTipoAusencia = ?', 2);
        
        $select->where('A.siAusencia = ?', 0);

        $select->where('YEAR(dtInicioAusencia) = ?', $ano);
        
        $select->order('A.idAlteracao');
        $select->order('A.idAusencia');
        

        return $this->fetchAll($select);
    }

    

    public function UltimoRegistro()
    {
        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->from(array('A'=>$this->_name),
                        array('MAX(idAusencia) as id')
        );
        
        return $this->fetchAll($select);
    }
    
    public function BuscarAusenciaRepetida($idAgente, $dtInicio, $dtFim)
    {
        
        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->from(array('A'=>$this->_name),
                      array('*','CONVERT(CHAR(10), dtInicioAusencia, 103) as dtInicio',
                            'CONVERT(CHAR(10), dtFimAusencia, 103) as dtFim',
                            'DATEDIFF ( DAY , dtInicioAusencia , dtFimAusencia ) as qtdDias')
                      );

        $select->joinInner(
                array('TP'=>'tbTipoAusencia'), 'TP.idTipoAusencia = A.idTipoAusencia',
                array('TP.nmAusencia as tipoAusencia')
        );
        
        
        $select->where('A.idAgente = ?', $idAgente);
        $select->where("'{$dtInicio}' BETWEEN dtInicioAusencia AND dtFimAusencia");
        $select->orWhere('A.idAgente = ?', $idAgente);
        $select->where("'{$dtFim}' BETWEEN dtInicioAusencia AND dtFimAusencia");
        
        $select->order('A.siAusencia');
        
        return $this->fetchAll($select);
    }
    
    public function BuscarAusenciaAtiva($idAgente, $dtAtual, $tipoAusencia)
    {
        
        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->from(array('A'=>$this->_name),
                      array('*')
                      );

        $select->joinInner(
                array('TP'=>'tbTipoAusencia'), 'TP.idTipoAusencia = A.idTipoAusencia',
                array('TP.nmAusencia as tipoAusencia')
        );
        
        $select->where('A.idAgente = ?', $idAgente);
        $select->where('A.idTipoAusencia = ?', $tipoAusencia);
        if ($tipoAusencia == 1) {
            $select->where('A.dtInicioAusencia >= ?', new Zend_Db_Expr('DATEADD(DAY, -10, '.$this->getDate().')'));
        } else {
            $select->where('A.dtInicioAusencia <= ?', $this->getExpressionDate());
        }
        
        $select->where('A.dtFimAusencia >= ?', $this->getExpressionDate());
        $select->where('A.siAusencia = ?', 1);
        
        return $this->fetchAll($select);
    }
    

    public function inserirAusencia($dados)
    {
        $insert = $this->insert($dados);
        return $insert;
    }

    public function alteraAusencia($dados, $idAusencia)
    {
        
        $where = "idAusencia = " . $idAusencia;
        
        return $this->update($dados, $where);
    }
}
