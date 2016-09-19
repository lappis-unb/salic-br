<?php
/**
 * DAO tbHistoricoExclusaoConta
 * @since 16/03/2011
 * @version 1.0
 * @package application
 * @subpackage application.model
 * @copyright � 2011 - Minist�rio da Cultura - Todos os direitos reservados.
 * @link http://www.cultura.gov.br
 */

class tbHistoricoExclusaoConta extends MinC_Db_Table_Abstract
{
	protected $_banco  = "SAC";
	protected $_schema = "dbo";
	protected $_name   = "tbHistoricoExclusaoConta";

        /**
	 * M�todo para cadastrar
	 * @access public
	 * @param array $dados
	 * @return integer (retorna o �ltimo id cadastrado)
	 */
	public function cadastrarDados($dados)
	{
		return $this->insert($dados);
	} // fecha m�todo cadastrarDados()



        /**
	 * M�todo para buscar o relat�rio consolidado
	 * @access public
	 * @param array $dados
	 * @return array dos dados cadastrados
	 */
        
} // fecha class