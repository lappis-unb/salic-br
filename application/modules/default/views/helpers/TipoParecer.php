<?php
/**
 * Descri&ccedil;&atilde;o dos tipos de parecer da an&aacute;lise do projeto
 * @author Equipe RUP - Politec
 * @since 14/06/2010
 * @version 1.0
 * @package application
 * @subpackage application.view.helpers
 * @copyright © 2010 - Minist&eacute;rio da Cultura - Todos os direitos reservados.
 * @link http://www.cultura.gov.br
 */

class Zend_View_Helper_TipoParecer
{
	/**
	 * M&eacute;todo com a descri&ccedil;&atilde;o dos tipos de parecer
	 * @access public
	 * @param string $parecer
	 * @return string $descricao
	 */
	public function tipoParecer($parecer)
	{
		if ($parecer == 1)
		{
			$descricao = "Aprova&ccedil;&atilde;o Inicial";
		}
		else if ($parecer == 2)
		{
			$descricao = "Complementa&ccedil;&atilde;o";
		}
		else if ($parecer == 3)
		{
			$descricao = "Prorroga&ccedil;&atilde;o";
		}
		else if ($parecer == 4)
		{
			$descricao = "Redu&ccedil;&atilde;o";
		}
		else
		{
			$descricao = "";
		}

		return $descricao;
	} // fecha m&eacute;todo tipoParecer()

} // fecha class