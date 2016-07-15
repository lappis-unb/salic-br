<?php

/**
 * Agente_Model_Orm_AgentesTable
 *
 * @uses GenericModel
 * @author  woeurner <wouerner@gmail.com>
 */
class Agente_Model_Orm_AgentesTable extends GenericModel
{
    protected $_name = 'agentes';
    protected $_primary = 'idAgente';
    protected $_schema = 'agentes.dbo';

    protected $_rowClass = 'Agente_Model_Vo_Agente';
}
