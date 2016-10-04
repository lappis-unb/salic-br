<?php

/**
 * Class Agente_Model_DbTable_Telefones
 *
 * @name Agente_Model_DbTable_Telefones
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
class Agente_Model_DbTable_Telefones extends MinC_Db_Table_Abstract
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
    protected $_name = 'telefones';

    /**
     * _primary
     *
     * @var bool
     * @access protected
     */
    protected $_primary = 'idtelefone';

}