<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of tbTitulacaoConselheiro
 *
 * @author 01610881125
 * @author wouerner <wouerner@gmail.com>
 */
class tbTitulacaoConselheiro extends GenericModel
{
    /* dados da tabela */
    protected $_banco   = "agentes";
    protected $_schema  = "agentes";
    protected $_name    = "tbtitulacaoconselheiro";

    public function buscarTitulacao($retornaSQL = false){
        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->from(
                        array('tc'=>$this->_schema.'.'.$this->_name),
                        array('tc.cdArea','tc.stTitular')
                      );

        $select->joinInner(
                            array('nm'=>'Nomes'),
                            'tc.idAgente = nm.idAgente',
                            array('nm.idAgente','Nome'=>'nm.Descricao'),
                            'AGENTES.dbo'
                           );
        $select->order('nm.Descricao');
       // xd($select->assemble());
        if($retornaSQL)
            return $select;
        else
            return $this->fetchAll($select);
    }

    /*
     * Buscar os conselheiros trazendo os dados da tabela Agentes.
     */
    public function buscarConselheirosTitulares(){
        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->from(
            array('a'=> $this->_name),
            array('a.idAgente AS id'),
            $this->_schema
        );

        $select->joinInner(
            array('b'=>'Nomes'), 'a.idAgente = b.idAgente',
            array('Nome'=>'b.Descricao AS nome'), 'AGENTES.dbo'
        );
        $select->where('a.stConselheiro = ?', "A");
        $select->order('b.Descricao');

        return $this->fetchAll($select);
    }

    /*
     * Buscar os conselheiros trazendo os dados da tabela de Usuario.
     */
    public function buscarConselheirosTitularesTbUsuarios(){
        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->from(
            array('a'=>$this->_schema.'.'.$this->_name),
            array(
                new Zend_Db_Expr('c.usu_codigo AS id, c.usu_nome AS nome')
            )
        );
        $select->joinInner(
            array('b'=>'Agentes'), 'a.idAgente = b.idAgente',
            array(), 'AGENTES.dbo'
        );
        $select->joinInner(
            array('c'=>'Usuarios'), 'c.usu_identificacao = b.CNPJCPF',
            array(), 'TABELAS.dbo'
        );
        $select->where('a.stConselheiro = ?', "A");
        $select->order('c.usu_nome');

        return $this->fetchAll($select);
    }

    public function alterarDados($dados, $where) {
        $where = "idAgente = " . $where;
        return $this->update($dados, $where);
    } // fecha método alterarDados()
}
