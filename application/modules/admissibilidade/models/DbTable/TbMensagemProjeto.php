<?php

/**
 * @name Admissibilidade_Model_DbTable_Gerenciarparecertecnico
 * @package modules/admissibilidade
 * @subpackage models/DbTable
 *
 * @author Ruy Junior Ferreira Silva <ruyjfs@gmail.com>
 * @since 05/12/2016
 *
 * @link http://salic.cultura.gov.br
 */
class Admissibilidade_Model_DbTable_TbMensagemProjeto extends MinC_Db_Table_Abstract{
    protected $_schema    = 'bdcorporativo.scsac';
    protected $_name      = 'tbMensagemProjeto';
    protected $_primary   = 'idMensagemProjeto';
    public function getAllBy($where = array())
    {
//        $selectSub = $this->select()
//            ->setIntegrityCheck(false)->from(array('tmp2' => $this->_name),
//                array('count(tmp2.idMensagemOrigem)'),
//                $this->_schema)->where("tmp2.idMensagemOrigem = {$this->_name}.idMensagemProjeto");
        $select = $this->select()
                    ->setIntegrityCheck(false)
                    ->from($this->_name,
                        array('idMensagemProjeto',
                              'dtMensagem' => $this->getExpressionToChar('dtMensagem') . $this->getExpressionConcat() . " ' ' " . $this->getExpressionConcat()  .  $this->getExpressionToChar('dtMensagem', 108),
                              'dsMensagem',
                              'stAtivo',
                              'cdTipoMensagem',
                              'idDestinatario',
                              'idRemetente',
                              'IdPRONAC',
                              'qtdResposta' => "(SELECT count(tmp2.idMensagemOrigem) FROM {$this->_schema}.{$this->_name} tmp2 WHERE tmp2.idMensagemOrigem = {$this->_name}.idMensagemProjeto)",
                              'idMensagemOrigem'),
                        $this->_schema)
                    ->joinLeft(
                        array('usuariosDestinatario' => 'usuarios'),
                        'tbMensagemProjeto.idDestinatario = usuariosDestinatario.usu_codigo',
                        'usu_nome as usu_nome_destinatario',
                        $this->getSchema('tabelas'))
                    ->joinLeft(
                        array('usuariosRemetente' => 'usuarios'),
                        'tbMensagemProjeto.idRemetente = usuariosRemetente.usu_codigo',
                        'usu_nome as usu_nome_remetente',
                        $this->getSchema('tabelas'));
        parent::setWhere($select, $where);
        $select->order('dtMensagem DESC');
        return $this->fetchAll($select);
    }
}