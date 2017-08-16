<?php
/**
 * tbProcuracao
 * @author jefferson.silva - XTI
 * @since 25/10/2012
 * @version 1.0
 * @link http://www.cultura.gov.br
 */

class tbProcuracao extends MinC_Db_Table_Abstract
{
    protected $_banco  = "AGENTES";
    protected $_schema = "agentes";
    protected $_name   = "tbProcuracao";

    public function listarProcuracoesPendentes(){
    $select = $this->select();
    $select->setIntegrityCheck(false);
    $select->from(
            array('a' => $this->_name),
            array('idProcuracao','dtProcuracao','dsJustificativa')
    );
    $select->joinInner(
            array('b' => 'Agentes'), "a.idAgente = b.idAgente",
            array('idAgente'), 'agentes'
    );
    $select->joinInner(
            array('c' => 'Nomes'), "b.idAgente = c.idAgente",
            array('Descricao as Procurador'), 'agentes'
    );
    $select->joinInner(
            array('d' => 'tbDocumento'), "d.idDocumento = a.idDocumento",
            array('idArquivo'), 'bdcorporativo.scCorp'
    );

    $select->where('a.siProcuracao = ?', 0);
    return $this->fetchAll($select);
}

}
