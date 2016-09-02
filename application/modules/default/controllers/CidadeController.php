<?php
/**
 * CidadeController
 * @author Equipe RUP - Politec
 * @since 29/03/2010
 * @version 1.0
 * @package application
 * @subpackage application.controllers
 * @copyright c 2010 - Ministerio da Cultura - Todos os direitos reservados.
 * @link http://www.cultura.gov.br
 *
 * @todo verificar em qual modulo ficara este controller.
 */

class CidadeController extends Zend_Controller_Action
{
    /**
     * Retorna os municipios conforme
     *
     * @name cidadeAction
     * @access public
     *
     * @author Ruy Junior Ferreira Silva <ruyjfs@gmail.com>
     * @since  ${DATE}
     */
    public function cidadeAction()
    {
        $this->_helper->layout->disableLayout(); // desabilita o Zend_Layout

        // recebe o id via post
        $post = Zend_Registry::get('post');
        $id = (int) $post->id;

        // integracao MODELO e VISAO
//        $sql = " SELECT idMunicipioIBGE AS id, Descricao AS descricao ";
//        $sql.= " FROM " . $this->_schema . '.' . $this->_name;
//        $sql.= " WHERE idUFIBGE = " . $idUF . " ";
//        $sql.= " ORDER BY Descricao;";
        $cidade = new Agente_Model_MunicipiosMapper();
        $this->view->cidades = $cidade->buscar($id);
    }

    /**
     * Metodo para buscar as cidades de um estado
     * Busca como XML para o AJAX
     * @access public
     * @param void
     * @return void
     */
    public function comboAction()
    {
        $this->_helper->layout->disableLayout(); // desabilita o Zend_Layout

        // recebe o id via post
        $post = Zend_Registry::get('post');
        $id = (int) $post->id;

        // integracao MODELO e VISAO
        $cidade = new Cidade();
        $this->view->combocidades = $cidade->buscar($id);
    } // fecha comboAction()
}
