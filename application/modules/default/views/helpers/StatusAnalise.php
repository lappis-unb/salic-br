<?php
/**
 * Tipos de status da an&aacute;lise para Verificar a Readequa&ccedil;&atilde;o
 * @author emanuel.sampaio - Politec
 * @since 30/08/2011
 * @version 1.0
 * @package application
 * @subpackage application.view.helpers
 * @copyright © 2011 - Minist&eacute;rio da Cultura - Todos os direitos reservados.
 * @link http://www.cultura.gov.br
 */

class Zend_View_Helper_StatusAnalise
{
	/**
	 * M&eacute;todo com os status da an&aacute;lise
	 * @access public
	 * @param string $cor
	 * @return string
	 */
	public function statusAnalise($cor)
	{
		$cor = trim($cor);

		if ($cor == 'vermelho')
		{
			$ds = '20 dias de atraso no recebimento da solicita&ccedil;&atilde;o (data inicial)';
		}
		else if ($cor == 'amarelo')
		{
			$ds = '>= 10 e < 20 dias de atraso no recebimento da solicita&ccedil;&atilde;o (data inicial)';
		}
		else if ($cor == 'verde')
		{
			$ds = '< 10 dias de atraso no recebimento da solicita&ccedil;&atilde;o (data inicial)';
		}
		else
		{
			$ds = ' ';
		}

		return $ds;
	} // fecha m&eacute;todo statusAnalise()

} // fecha class