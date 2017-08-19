<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
*/

/**
 * Description of StatusLocalRealizacaoProjeto
 *
 * @author 01129075125
 */
class Zend_View_Helper_StatusSituacaoProjeto
{

    public function StatusSituacaoProjeto($status)
    {
        switch ($status)
        {
            case 'E10': $status = "E10 - Aguarda Capta&ccedil;&atilde;o de Recursos";
            case 'E11': $status = "E11 - Encerrado Prazo de Capta&ccedil;&atilde;o";
            case 'E12': $status = "E12 - Capta&ccedil;&atilde;o Parcial";
            case 'E13': $status = "E13 - Encerrado prazo de capta&ccedil;&atilde;o - Projeto em execu&ccedil;&atilde;o";
            case 'E15': $status = "E15 - Encerrado prazo de presta&ccedil;&atilde;o de contas";
            case 'E16': $status = "E16 - Encerrado prazo/capta&ccedil;&atilde;o - Proponente inabilitado";
        }
        return  $status;
    }


}
?>
