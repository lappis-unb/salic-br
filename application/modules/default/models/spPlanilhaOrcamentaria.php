<?php

/**
 * Description of spPlanilhaOrcamentaria
 * Criado em 01/10/2013 - Jefferson Alessandro
 */
class spPlanilhaOrcamentaria extends GenericModel {
        
    protected $_banco = 'sac';
    protected $_schema = 'sac';
    protected $_name  = 'spPlanilhaOrcamentaria';

    /**
     * exec
     *
     * @name exec
     * @param $idPronac
     * @param $tipoPlanilha
     * @return mixed
     *
     * @author Ruy Junior Ferreira Silva <ruyjfs@gmail.com>
     * @since  17/08/2016
     */
    public function exec($idPronac, $tipoPlanilha){
        
        // tipoPlanilha = 0 : Planilha Orcamentaria da Proposta
        // tipoPlanilha = 1 : Planilha Orcamentaria do Proponente
        // tipoPlanilha = 2 : Planilha Orcamentaria do Parecerista
        // tipoPlanilha = 3 : Planilha Orcamentaria Aprovada Ativa
        // tipoPlanilha = 4 : Cortes Orcamentarios Aprovados
        // tipoPlanilha = 5 : Remanejamento menor que 20%
        // tipoPlanilha = 6 : Readequacao
        
        $sql = "exec ".$this->_schema.".".$this->_name." $idPronac, $tipoPlanilha";

        $db = Zend_Registry :: get('db');
        $db->setFetchMode(Zend_DB :: FETCH_OBJ);
        return $db->fetchAll($sql);
    }
}
?>
