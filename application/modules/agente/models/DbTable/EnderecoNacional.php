<?php

class Agente_Model_DbTable_EnderecoNacional extends MinC_Db_Table_Abstract
{
    protected $_schema = 'agentes';
    protected $_name = 'EnderecoNacional';
    protected $_primary = 'idEndereco';

    public function buscarEnderecos($idAgente = null)
    {
        $ve = array(
            've.Descricao as tipoendereco',
            've.idVerificacao as codtipoendereco',
        );

        $m = array(
            'm.Descricao as municipio',
            'm.idMunicipioIBGE as codmun',
        );

        $u = array(
            'u.Sigla as uf',
            'u.idUF as coduf'
        );

        $sql = $this->select()
            ->setIntegrityCheck(false)
            ->from(array('e' => 'EnderecoNacional'), $this->_getCols(), $this->_schema)
            ->joinLeft(array('ve' => 'Verificacao'), 've.idVerificacao = e.TipoEndereco', $ve, $this->_schema)
            ->joinLeft(array('m' => 'Municipios'), 'm.idMunicipioIBGE = e.Cidade', $m, $this->_schema)
            ->joinLeft(array('u' => 'UF'), 'u.idUF = m.idUFIBGE', $u, $this->_schema)
            ->joinLeft(array('vl' => 'Verificacao'), 'vl.idVerificacao = e.TipoLogradouro', array('vl.Descricao as dsTipoLogradouro'), $this->_schema)
            ->where('e.idAgente = ?', $idAgente)
            ->order(array('Status DESC'))
        ;

        return $this->fetchAll($sql);
    }

    public function mudaCorrespondencia($idAgente)
    {
        try {
            return $resultado = $this->update( array('Status' => 0), array('idAgente = ?' => $idAgente));
        } catch (Zend_Exception $e) {
            throw new Zend_Db_Exception("Erro ao alterar o Status dos endere&ccedil;os: " . $e->getMessage());
        }
    }
    
    public function novaCorrespondencia($idAgente)
    {
        try {
            $subSelect = $this->select()
                ->from($this->_name, array(new Zend_Db_Expr('min(idEndereco) as valor')), $this->_schema)
                ->where('idAgente = ?', $idAgente);

            $dados = array(
                'Status' => 1
            );

            $where['idAgente = ?'] = $idAgente;
            $where['idEndereco = ?']  = $subSelect;

            $this->update($dados, $where);
        } catch (Zend_Exception_Db $e) {
            $this->view->message = "Erro ao alterar o Status dos endere&ccedil;os: " . $e->getMessage();
        }
    }

    public function delete($idEndereco)
    {
        return parent::delete(array('idEndereco = ? '=> $idEndereco));
    }
}
