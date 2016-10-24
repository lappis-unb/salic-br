<?php

/**
 * Created by PhpStorm.
 * @author Vinícius Feitosa da Silva <viniciusfesil@mail.com>
 * Date: 20/10/16
 * Time: 14:58
 */
abstract class MinC_Auth_Controller_Auth extends MinC_Controller_Action_Abstract
{
    private $oauthConfig;

    public abstract function successAction ();

    /**
     * @author Vinícius Feitosa da Silva <viniciusfesil@mail.com>
     * @return void
     */
    public function init()
    {
        $this->oauthConfig = $this->obterConfiguracoesOPAuth();
        parent::init();
    }

    /**
     * @author Vinícius Feitosa da Silva <viniciusfesil@mail.com>
     * @return void
     */
    public function indexAction()
    {
        $opauth = new Opauth($this->oauthConfig, false);
        $opauth->run();
    }



    /**
     * @return array
     * @author Vinícius Feitosa da Silva <viniciusfesil@mail.com>
     * @return mixed
     */
    private function obterConfiguracoesOPAuth()
    {
        $oauthConfig = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', "oauth");
        $oauthConfigArray = $oauthConfig->toArray();
        return $oauthConfigArray['config'];
    }

    /**
     * @author Vinícius Feitosa da Silva <viniciusfesil@mail.com>
     * @return void
     */
    public function oauth2callbackAction()
    {
        $objOpauth = new Opauth($this->oauthConfig);
        $objOpauth->run();
    }
}