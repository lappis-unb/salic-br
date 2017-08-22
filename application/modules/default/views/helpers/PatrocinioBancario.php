<?php
/**
 * Nomes dos tipos de Patrocinio Bancario
 * @author Equipe RUP - Politec
 * @since 19/02/2011
 * @version 1.0
 * @package application
 * @subpackage application.views.helpers
 * @copyright © 2011 - Minist&eacute;rio da Cultura - Todos os direitos reservados.
 * @link http://www.cultura.gov.br
 */

class Zend_View_Helper_PatrocinioBancario
{
	/**
	 * M&eacute;todo com os tipos de Patrocinio Bancario
	 * @access public
	 * @param integer $tipo
	 * @return string $dsTipo
	 */
	function patrocinioBancario($tipo)
	{
		if ($tipo == '2')
		{
			$dsTipo = "Doa&ccedil;&atilde;o";
		}
		else
		{
			$dsTipo = "Patroc&iacute;nio";
		}

		return $dsTipo;
	} // fecha m&eacute;todo patrocinioBancario()

} // fecha class