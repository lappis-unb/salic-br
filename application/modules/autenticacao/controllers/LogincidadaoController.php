<?php

/**
 * Classe responsável por fazer a autenticação Utilizando o Login Cidadão.
 * @author Vinícius Feitosa da Silva <viniciusfesil@mail.com>
 * @since 06/10/16 11:25
 */
class Autenticacao_LogincidadaoController extends MinC_Auth_Controller_AOAuth
{
    /**
     * @author Vinícius Feitosa da Silva <viniciusfesil@mail.com>
     * @return void
     */
    public function successAction()
    {
        $this->_helper->viewRenderer->setNoRender(true);
        $this->_helper->flashMessenger->addMessage("Sucesso!");
        $this->redirect("/principal");
    }

    /**
     * @author Vinícius Feitosa da Silva <viniciusfesil@mail.com>
     * @return void
     * @todo Implementar esse método
     */
    public function errorAction()
    {}
}