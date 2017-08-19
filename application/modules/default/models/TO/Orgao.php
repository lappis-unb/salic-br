<?php
/*
 * Created on 20/05/2010
 * Thiago Lenin
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */
 
 class Orgao
 {
 	private $nomeOrgao;
 	
 	// Esta fun&ccedil;&atilde;o tem por objetivo instancia o objeto UnidadeFiscalizadora
	public function setUnidadeFiscalizadora (UnidadeFiscalizadora $unidadeFiscalizadora)
	{
		$this->unidadeFiscalizadora = $unidadeFiscalizadora;
	}
	
 	// Esta fun&ccedil;&atilde;o tem por objetivo instancia o objeto Email
	public function setEmail (Email $email)
	{
		$this->email = $email;
	}
	
	// Esta fun&ccedil;&atilde;o tem por objetivo instancia o objeto Uf
	public function setUf (Uf $uf)
	{
		$this->uf = $uf;
	}
 	
 	// $nomeOrgao
	public function setNomeOrgao($nomeOrgao)
	{
		$this->nomeOrgao = $nomeOrgao;
	}
	
	public function getNomeOrgao()
	{
		return $this->nomeOrgao;
	}
 }
?>
