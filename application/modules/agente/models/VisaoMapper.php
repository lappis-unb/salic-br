<?php

/**
 * Class Agente_Model_VisaoMapper
 *
 * @name Agente_Model_VisaoMapper
 * @package Modules/Agente
 * @subpackage Models
 * @version $Id$
 *
 * @author Ruy Junior Ferreira Silva <ruyjfs@gmail.com>
 * @since 01/09/2016
 *
 * @copyright © 2012 - Ministerio da Cultura - Todos os direitos reservados.
 * @link http://salic.cultura.gov.br
 */
class Agente_Model_VisaoMapper extends MinC_Db_Mapper
{
    public function __construct()
    {
        parent::setDbTable('Agente_Model_DbTable_Visao');
    }

    public function saveCustom($arrData)
    {
        /*
         * Validacao - Se for componente da comissao ele nao salva a visao
         * Regra o componente da comissao nao pode alterar sua visao.
         */
        if ($arrData['grupologado'] != 118) {
            $arrDataVisao = array(
                'idAgente' => $arrData['idAgente'],
                'Visao' => $arrData['Visao'],
                'usuario' => $arrData['IdUsuario'],
                'stativo' => 'A'
            );
            $arrVisao = $this->findBy(array('idAgente' => $arrData['idAgente'], 'Visao' => $arrData['Visao']));
            if ($arrVisao) {
                return $arrVisao['idVisao'];
            } else {
                return $this->save(new Agente_Model_Visao($arrDataVisao));
            }
        }
    }
}