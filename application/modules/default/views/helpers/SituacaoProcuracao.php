<?php
/**
 * Situa&ccedil;&atilde;o da procura&ccedil;&atilde;o
 * @author Equipe RUP - Politec
 * @since 29/03/2010
 * @version 1.0
 * @package application
 * @subpackage application.view.helpers
 * @copyright 2010 - Minist�rio da Cultura - Todos os direitos reservados.
 * @link http://www.cultura.gov.br
 */

class Zend_View_Helper_SituacaoProcuracao
{
	/**
	 * Informa a situa&ccedil;&atilde;o da procura&ccedil;&atilde;o
	 * @access public
	 * @param integer $stvinculo
	 * @return string
	 */
	public function situacaoProcuracao($stvinculo)
	{
            if($stvinculo == 0){
                $return =  "Aguardando An&aacute;lise";
            }
            if($stvinculo == 1){
                $return =  "Aprovado";
            }
            if($stvinculo == 2){
                $return =  "Rejeitado";
            }
            return $return;
	} 

} 