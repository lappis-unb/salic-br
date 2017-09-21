<?php

class Agente_DddController extends Zend_Controller_Action
{
    public function dddAction()
    {
        $this->_helper->layout->disableLayout(); // desabilita o Zend_Layout
        $post = Zend_Registry::get('post');
        $id = (int)$post->id;
        $this->view->ddds = Ddd::buscar($id);
    }

    public function comboAction()
    {
        $this->_helper->layout->disableLayout(); // desabilita o Zend_Layout

        // recebe o id via post
        $post = Zend_Registry::get('post');
        $id = (int)$post->id;

        $dddMapper = new Agente_Model_DDDMapper();
        $this->view->comboddds = $dddMapper->fetchPairs('Codigo', 'Codigo', array('idUF' => $id));
    }

    public function comboSalvarTelefoneAction()
    {
        $this->_helper->layout->disableLayout(); // desabilita o Zend_Layout

        // recebe o id via post
        $post = Zend_Registry::get('post');
        $id = (int)$post->id;

        $arrayDados = Ddd::buscar($id);
        $dados = array();
        $i = 0;
        if (count($arrayDados) > 0) {
            foreach ($arrayDados as $value) {
                $dados[$i]['id'] = $value->id;
                $dados[$i]['Descricao'] = $value->descricao;
                $i++;
            }
            $jsonEncode = json_encode($dados);
            $this->_helper->json(array('resposta' => true, 'conteudo' => $dados));
        } else {
            $this->_helper->json(array('resposta' => false));
        }
        $this->_helper->viewRenderer->setNoRender(TRUE);
    }
}
