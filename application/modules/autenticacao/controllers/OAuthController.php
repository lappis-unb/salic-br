<?php

/**
 * Classe responsável por fazer a autenticação no sistema através de outros sistemas.
 * @author Vinícius Feitosa da Silva <viniciusfesil@mail.com>
 * @since 06/10/16 11:25
 */
class Autenticacao_OAuthController extends MinC_Controller_Action_Abstract
{
    public function init()
    {
        Zend_Layout::startMvc(array('layout' => 'layout_login'));
        parent::init();
    }

    public function indexAction()
    {
        
    }
}