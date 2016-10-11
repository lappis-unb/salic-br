<?php
/**
 * DAO tbTipoReadequacao
 * @author jeffersonassilva@gmail.com - XTI
 * @since 28/02/2014
 * @version 1.0
 * @package application
 * @subpackage application.model
 * @copyright © 2011 - Ministério da Cultura - Todos os direitos reservados.
 * @link http://www.cultura.gov.br
 */

class tbTipoReadequacao extends GenericModel
{
	protected $_banco  = "SAC";
	protected $_schema = "dbo";
	protected $_name   = "tbTipoReadequacao";

    public function buscarTiposReadequacoesPermitidos($idPronac) {
        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->from( 
            array('a' => $this->_name),
            array(
                new Zend_Db_Expr("idTipoReadequacao, dsReadequacao"),
            )
        );

        $select->where('stReadequacao = ?', 0);
        $select->where('idTipoReadequacao not in (SELECT
                  b.idTipoReadequacao FROM SAC.dbo.tbReadequacao b WHERE idPronac = ? AND
                  b.siEncaminhamento != 15 AND b.stEstado = 0 )', $idPronac);


        $select->orWhere("a.stReadequacao = 1 AND a.idTipoReadequacao NOT IN (SELECT
                                                                 b.idTipoReadequacao FROM SAC.dbo.tbReadequacao b WHERE idPronac = ? AND
                                                                 b.siEncaminhamento NOT IN (1,12))", $idPronac);
        
        $select->order('2');
        
        //xd($select->assemble());
        return $this->fetchAll($select);
    }
	
} // fecha class