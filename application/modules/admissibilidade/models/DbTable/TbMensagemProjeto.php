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
        $select = $this->select()
                    ->setIntegrityCheck(false)
                    ->from($this->_name,
                        array('idMensagemProjeto',
                              'dtMensagem' => $this->getExpressionToChar('dtMensagem'),
                              'dsMensagem',
                              'stAtivo',
                              'cdTipoMensagem',
                              'idDestinatario',
                              'idRemetente',
                              'IdPRONAC',
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
                        $this->getSchema('tabelas'))
        ;
        parent::setWhere($select, $where);
        $select->order('dtMensagem DESC');
        return $this->fetchAll($select);
    }
}