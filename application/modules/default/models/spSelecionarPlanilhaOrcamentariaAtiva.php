<?php

/**
 * Description of spSelecionarPlanilhaOrcamentariaAtiva
 * Criado em 26/01/2016 - Fernão Lara
 */
class spSelecionarPlanilhaOrcamentariaAtiva extends GenericModel {
        
    protected $_banco = 'SAC';
    protected $_name  = 'spSelecionarPlanilhaOrcamentariaAtiva';

    public function exec($idPronac){
      $sql = "exec ".$this->_banco.".dbo.".$this->_name." $idPronac";
      return $this->getAdapter()->fetchOne($sql);
    }
}
?>
