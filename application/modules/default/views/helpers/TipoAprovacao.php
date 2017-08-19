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

class Zend_View_Helper_TipoAprovacao
{
	/**
	 * M&eacute;todo com a descri&ccedil;&atilde;o dos tipos de parecer
	 * @access public
	 * @param string $parecer
	 * @return string $descricao
	 */
	public function tipoAprovacao($parecer)
	{
		if ($parecer == 'AC')
		{
			$descricao = "Aprovado pelo Componente";
		}
		else if ($parecer == 'IC')
		{
			$descricao = "Indeferido pelo Componente";
		}
		else if ($parecer == 'AS')
		{
			$descricao = "Aprovado pela CNIC";
		}
		else if ($parecer == 'IS')
		{
			$descricao = "Indeferido pela CNIC";
		}
		else if ($parecer == 'AR')
		{
			$descricao = "Aprovado por AD-REFERENDUM";
		}
		return $descricao;
	} // fecha m&eacute;todo tipoParecer()

} // fecha class