<?php

/**
 * Class Agente_MunicipiosController
 *
 * @name Agente_MunicipiosController
 * @package modules/agente
 * @subpackage controllers
 * @version $Id$
 *
 * @author Ruy Junior Ferreira Silva <ruyjfs@gmail.com>
 * @since 02/09/2016
 *
 * @copyright © 2012 - Ministerio da Cultura - Todos os direitos reservados.
 * @link http://salic.cultura.gov.br
 */
class Agente_MunicipiosController extends Zend_Controller_Action
{
    /**
     * Retorna os municipios conforme
     *
     * @name cidadeAction
     * @access public
     *
     * @author Ruy Junior Ferreira Silva <ruyjfs@gmail.com>
     * @since 02/09/2016
     */
    public function cidadeAction()
    {
//        $this->_helper->layout->disableLayout(); // desabilita o Zend_Layout

        // recebe o id via post
//        $post = Zend_Registry::get('post');
//        $id = (int) $post->id;

        // integracao MODELO e VISAO
//        $sql = " SELECT idMunicipioIBGE AS id, Descricao AS descricao ";
//        $sql.= " FROM " . $this->_schema . '.' . $this->_name;
//        $sql.= " WHERE idUFIBGE = " . $idUF . " ";
//        $sql.= " ORDER BY Descricao;";
//        $cidade = new Agente_Model_MunicipiosMapper();
//        $this->view->cidades = $cidade->buscar($id);
    }

    /**
     * Metodo para buscar as cidades de um estado
     *
     * @name comboAction
     * @access public
     *
     * @author Ruy Junior Ferreira Silva <ruyjfs@gmail.com>
     * @since 02/09/2016
     */
    public function comboAction()
    {
        $this->_helper->layout->disableLayout(); // desabilita o Zend_Layout

        // recebe o id via post
        $post = Zend_Registry::get('post');
        $id = (int) $post->id;

        // integracao MODELO e VISAO
        $municipioMapper = new Agente_Model_MunicipiosMapper();
        $this->view->combocidades = $municipioMapper->fetchPairs('idmunicipioibge', 'descricao', array('idufibge' => $id));
    }
}
