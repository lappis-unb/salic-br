<?php
/**
 * Helper para verificar a diligencia do projeto
 * @author Equipe RUP - Politec
 * @since 16/09/2011
 * @version 1.0
 * @package application
 * @subpackage application.view.helpers
 * @copyright � 2011 - Minist&eacute;rio da Cultura - Todos os direitos reservados.
 * @link http://www.cultura.gov.br
 */

class Zend_View_Helper_VerificarDiligenciaProjeto
{
	/**
	 * M&eacute;todo para verificar a diligencia do projeto
	 * @access public
	 * @param integer $idPronac
	 * @return string
	 */
	public function verificarDiligenciaProjeto($idPronac = null)
	{
		if (isset($idPronac) && !empty($idPronac)) :

			$Diligencia = new Diligencia();

			// busca a situa&ccedil;&atilde;o do projeto
			$buscarDiligencia = $Diligencia->buscar(array('IdPRONAC = ?' => $idPronac))->current();

			if (count($buscarDiligencia) > 0) :
				return $buscarDiligencia->idTipoDiligencia;
			else :
				return 0;
			endif;

		else :
			return 0;
		endif;
	} // fecha m&eacute;todo verificarDiligenciaProjeto()

} // fecha class