<?php
/**
 * Tipos de atendimentos dos recursos
 * @author emanuel.sampaio - Politec
 * @since 12/03/2011
 * @version 1.0
 * @package application
 * @subpackage application.view.helpers
 * @copyright � 2011 - Minist&eacute;rio da Cultura - Todos os direitos reservados.
 * @link http://www.cultura.gov.br
 */

class Zend_View_Helper_TipoAtendimentoRecurso
{
	/**
	 * M&eacute;todo com os tipos de atendimentos dos recursos
	 * @access public
	 * @param string $tp
	 * @return string
	 */
	public function tipoAtendimentoRecurso($tp)
	{
		$tp = trim($tp);

		if ($tp == 'D')
		{
			$ds = "Deferido";
		}
		else if ($tp == 'I')
		{
			$ds = "Indeferido";
		}
		else
		{
			$ds = "N&atilde;o avaliado";
		}

		return $ds;
	} // fecha m&eacute;todo tipoAtendimentoRecurso()

} // fecha class