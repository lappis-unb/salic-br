<?php
/*Teste*/
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of tbRelatorioTecnico
 *
 * @author Emerson Silva
 */ 
class tbRelatorioTecnico extends MinC_Db_Table_Abstract {
	
    protected $_name   = 'tbRelatorioTecnico';
    protected $_schema = 'dbo';
    protected $_banco  = 'SAC';
    
	public function insertParecerTecnico($data){
        $insertparecertecnico = $this->insert($data);
        return $insertparecertecnico;
    
    }	    
		
    
}

	
?>
