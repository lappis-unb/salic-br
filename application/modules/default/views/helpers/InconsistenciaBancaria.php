<?php
/**
 * Nomes dos tipos de Inconsistências Banc&aacute;rias
 * @author Equipe RUP - Politec
 * @since 11/02/2010
 * @version 1.0
 * @package application
 * @subpackage application.views.helpers
 * @copyright © 2011 - Minist&eacute;rio da Cultura - Todos os direitos reservados.
 * @link http://www.cultura.gov.br
 */

class Zend_View_Helper_InconsistenciaBancaria
{
	/**
	 * M&eacute;todo com os tipos de inconsistências banc&aacute;rias
	 * @access public
	 * @param integer $tipo
	 * @return string $dsTipo
	 */
	function inconsistenciaBancaria($tipo)
	{
		if ($tipo == 1)
		{
			$dsTipo = "O Período de Execu&ccedil;&atilde;o n&atilde;o est&aacute; vigente.";
		}
		else if ($tipo == 2)
		{
			$dsTipo = "O Período de Capta&ccedil;&atilde;o n&atilde;o est&aacute; vigente.";
		}
		else if ($tipo == 3)
		{
			$dsTipo = "Incentivador n&atilde;o cadastrado.";
		}
		else if ($tipo == 4)
		{
			$dsTipo = "Tipo de Depósito n&atilde;o foi informado.";
		}
		else if ($tipo == 5)
		{
			$dsTipo = "N&atilde;o foi possível encontrar o E-mail do Proponente.";
		}
		else if ($tipo == 6)
		{
			$dsTipo = "Proponente n&atilde;o cadastrado.";
		}
		else if ($tipo == 7)
		{
			$dsTipo = "Agência e Conta Banc&aacute;ria n&atilde;o cadastrada.";
		}
		else if ($tipo == 8)
		{
			$dsTipo = "O Projeto n&atilde;o possui Enquadramento.";
		}
		else if ($tipo == 9)
		{
			$dsTipo = "N&atilde;o existe Projeto associado a Conta.";
		}
		else
		{
			$dsTipo = " ";
		}

		return $dsTipo;
	} // fecha m&eacute;todo inconsistenciaBancaria()

} // fecha class