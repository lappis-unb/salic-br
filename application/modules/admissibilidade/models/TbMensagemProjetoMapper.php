<?php

/**
 * @name Agente_Model_TbMensagemProjetoMapper
 * @package Modules/Admissibilidade
 * @subpackage Models
 *
 * @author Ruy Junior Ferreira Silva <ruyjfs@gmail.com>
 * @since 01/09/2016
 *
 * @copyright © 2012 - Ministerio da Cultura - Todos os direitos reservados.
 * @link http://salic.cultura.gov.br
 */
class Admissibilidade_Model_TbMensagemProjetoMapper extends MinC_Db_Mapper
{

    public function __construct()
    {
        $this->setDbTable('Admissibilidade_Model_DbTable_TbMensagemProjeto');
    }

    public function isUniqueCpfCnpj($value)
    {
        return ($this->findBy(array("cnpjcpf" => $value))) ? true : false;
    }

    public function save( $model)
    {
        return parent::save($model);
    }
}
