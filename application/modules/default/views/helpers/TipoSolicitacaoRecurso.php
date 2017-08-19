<?php
/**
 * Tipos de solicita&ccedil;&otilde;es dos recursos
 * @author emanuel.sampaio - Politec
 * @since 12/03/2011
 * @version 1.0
 * @package application
 * @subpackage application.view.helpers
 * @copyright © 2011 - Minist&eacute;rio da Cultura - Todos os direitos reservados.
 * @link http://www.cultura.gov.br
 */

class Zend_View_Helper_TipoSolicitacaoRecurso
{
	/**
	 * M&eacute;todo com os tipos de solicita&ccedil;&otilde;es dos recursos
	 * @access public
	 * @param string $tp
	 * @return string
	 */
	public function tipoSolicitacaoRecurso($tp)
	{
		$tp = trim($tp);

		if ($tp == 'PI')
		{
			$ds = "Projeto Indeferido";
		}
		else if ($tp == 'EN')
		{
			$ds = "Projeto Aprovado - Enquadramento";
		}
		else if ($tp == 'OR')
		{
			$ds = "Projeto Aprovado - Or&ccedil;amento";
		}
		else if ($tp == 'PP')
		{
			$ds = "Prorroga&ccedil;&atilde;o de Prazo de Capta&ccedil;&atilde;o";
		}
		else if ($tp == 'PE')
		{
			$ds = "Prorroga&ccedil;&atilde;o de Prazo de Execu&ccedil;&atilde;o";
		}
		else if ($tp == 'PC')
		{
			$ds = "Presta&ccedil;&atilde;o de Contas";
		}

		return $ds;
	} // fecha m&eacute;todo tipoSolicitacaoRecurso()

} // fecha class