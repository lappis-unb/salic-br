<?php
class CepTeste{
  protected $_name = 'tbLogradouroUf';
             
        
		public static function buscarCepTeste($cep) {
       		       			
		$sql = "SELECT 
					lor.cdCep,
					lor.nmLogradouro,
				    lor.nrLote,
				    lor.dsComplemento1,
					lor.cdComplemento1,
					lor.dsComplemento2,
					lor.cdComplemento2,
					loc.nmLocalidadeAbreviado,
					bro.nmBairro,
					loc.cdUf,
					lor.nmTipoLogradouro,
					loc.nmLocalidade
				FROM 
					bdcorporativo.scDNE.tbLocalidade loc 
					inner join bdcorporativo.scDNE.tbLogradouroUf lor 
						on lor.nrLocalidade = loc.nrLocalidade
						and lor.cdCep = ".$cep."
					left join bdcorporativo.scDNE.tbBairro bro 
						on bro.nrBairro = lor.nrInicioBairro 
						or bro.nrBairro = lor.nrFimBairro ";

			$db= Zend_Db_Table::getDefaultAdapter();
			$db->setFetchMode(Zend_DB::FETCH_OBJ);

			return $db->fetchAll($sql);
       	}
}

?>
