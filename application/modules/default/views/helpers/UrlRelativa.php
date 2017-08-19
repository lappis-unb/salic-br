<?php
/**
 * Pega o diretório principal da aplica&ccedil;&atilde;o
 * @author Equipe RUP - Politec
 * @since 29/03/2010
 * @version 1.0
 * @package application
 * @subpackage application.view.helpers
 * @copyright © 2010 - Minist&eacute;rio da Cultura - Todos os direitos reservados.
 * @link http://www.cultura.gov.br
 */

class Zend_View_Helper_UrlRelativa
{
	/**
	 * Pega o diretório raiz do sistema
	 * @access public
	 * @param void
	 * @return string
	 */
	public function UrlRelativa()
	{
		return getcwd();
	}
}