<?php

class Agente_Model_DbTable_TbInformacaoProfissional extends MinC_Db_Table_Abstract
{

    protected $_name = 'tbInformacaoProfissional';
    protected $_schema = 'agentes';
    protected $_primary = 'idInformacaoProfissional';

    public function BuscarInfo($idAgente, $situacao)
    {
        $select = $this->select();
        $select->setIntegrityCheck(false);

        $select->from(array('a' => $this->_name),
            array(
                '*',
                $this->getExpressionToChar('dtInicioVinculo') . ' as dtinicio',
                $this->getExpressionToChar('dtFimVinculo') . ' as dtfim'
            ),
            $this->_schema
        );

        $select->joinLeft(
            array('d' => 'scCorp.tbDocumento'), 'd.idDocumento = a.idDocumento',
            array('*'),
            $this->getSchema('bdcorporativo')
        );

        $select->joinLeft(
            array('ta' => 'scCorp.tbArquivo'), 'ta.idArquivo = d.idArquivo',
            array('*'),
            $this->getSchema('bdcorporativo')
        );

        $select->joinLeft(
            array('tai' => 'scCorp.tbArquivoImagem'), 'tai.idArquivo = ta.idArquivo',
            array('*'),
            $this->getSchema('bdcorporativo')
        );

        if (!empty($situacao)) {
            $select->where('a.siInformacao = ?', $situacao);
        }

        $select->where('a.idAgente = ?', $idAgente);

        $select->order('a.dtInicioVinculo');

        return $this->fetchAll($select);
    }

    public function AnosExperiencia($idAgente)
    {
        $select = $this->select();
        $select->setIntegrityCheck(false);

        $select->from(array('A' => $this->_name),
            array('DATEDIFF ( YEAR , dtInicioVinculo , dtFimVinculo ) as qtdAnos')
        );

        $select->where('A.idAgente = ?', $idAgente);

        return $this->fetchAll($select);

    }


    public function inserirInfo($dados)
    {
        $insert = $this->insert($dados);
        return $insert;
    }

    public function alteraInfo($dados, $idInfo)
    {

        $where = "idInformacaoProfissional = " . $idInfo;

        return $this->update($dados, $where);
    }


}

?>
