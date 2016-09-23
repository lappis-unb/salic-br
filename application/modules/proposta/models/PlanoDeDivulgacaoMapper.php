<?php

/**
 * Class Proposta_Model_PlanoDeDivulgacaoMapper
 *
 * @name Proposta_Model_PlanoDeDivulgacaoMapper
 * @package Modules/Agente
 * @subpackage Models
 * @version $Id$
 *
 * @author Ruy Junior Ferreira Silva <ruyjfs@gmail.com>
 * @since 20/09/2016
 *
 * @copyright © 2012 - Ministerio da Cultura - Todos os direitos reservados.
 * @link http://salic.cultura.gov.br
 */
class Proposta_Model_PlanoDeDivulgacaoMapper extends MinC_Db_Mapper
{
    public function __construct()
    {
        parent::setDbTable('Proposta_Model_DbTable_PlanoDeDivulgacao');
    }

    public function save(Proposta_Model_PlanoDeDivulgacao $model)
    {
        return parent::save($model);
    }
}