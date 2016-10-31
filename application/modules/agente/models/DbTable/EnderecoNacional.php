<?php

/**
 * Class Agente_Model_DbTable_EnderecoNacional
 *
 * @name Agente_Model_DbTable_EnderecoNacional
 * @package Modules/Agente
 * @subpackage Models/DbTable
 * @version $Id$
 *
 * @author Ruy Junior Ferreira Silva <ruyjfs@gmail.com>
 * @since 06/09/2016
 *
 * @copyright © 2012 - Ministerio da Cultura - Todos os direitos reservados.
 * @link http://salic.cultura.gov.br
 */
class Agente_Model_DbTable_EnderecoNacional extends MinC_Db_Table_Abstract
{
    /**
     * _schema
     *
     * @var string
     * @access protected
     */
    protected $_schema = 'agentes';

    /**
     * _name
     *
     * @var bool
     * @access protected
     */
    protected $_name = 'endereconacional';

    /**
     * _primary
     *
     * @var bool
     * @access protected
     */
    protected $_primary = 'idendereco';

    /**
     * Metodo para buscar os enderecos do agente
     *
     * @access public
     * @static
     * @param integer $idAgente
     * @return object
     */
    public function buscarEnderecos($idAgente = null)
    {
        $ve = [
            've.descricao as tipoendereco',
            've.idverificacao as codtipoendereco',
        ];

        $m = [
            'm.descricao as municipio',
            'm.idmunicipioibge as codmun',
        ];

        $u = [
            'u.sigla as uf',
            'u.iduf as coduf'
        ];

        $sql = $this->select()
            ->setIntegrityCheck(false)
            ->from(['e' => 'endereconacional'], $this->_getCols(), $this->_schema)
            ->joinLeft(['ve' => 'verificacao'], 've.idverificacao = e.tipoendereco', $ve, $this->_schema)
            ->joinLeft(['m' => 'municipios'], 'm.idmunicipioibge = e.cidade', $m, $this->_schema)
            ->joinLeft(['u' => 'uf'], 'U.iduf = e.uf', $u, $this->_schema)
            ->joinLeft(['vl' => 'verificacao'], 'vl.idverificacao = e.tipologradouro', ['vl.descricao as dstipologradouro'], $this->_schema)
            ->where('e.idagente = ?', $idAgente)
            ->order(['status DESC'])
        ;

        return $this->fetchAll($sql);
    }

}