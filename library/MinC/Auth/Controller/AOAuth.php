<?php

/**
 * Classe abstrata responsável por fornecer todo o fluxo necessário
 * para a implementação do OAuth de acordo com a necessidade.
 * @author Vinícius Feitosa da Silva <viniciusfesil@mail.com>
 * @author Cleber Santos <oclebersantos@gmail.com>
 * @since 20/10/16 14:58
 */
abstract class MinC_Auth_Controller_AOAuth extends Zend_Controller_Action
{
    /**
     * @var array
     */
    private $oauthConfig;

    /**
     * @author Vinícius Feitosa da Silva <viniciusfesil@mail.com>
     * @return void
     */
    public abstract function successAction();

    /**
     * @author Vinícius Feitosa da Silva <viniciusfesil@mail.com>
     * @return void
     */
    public abstract function errorAction();

    /**
     * @author Vinícius Feitosa da Silva <viniciusfesil@mail.com>
     * @return void
     */
    public function init()
    {
        $this->oauthConfig = $this->getOPAuthConfiguration();
        parent::init();
    }

    /**
     * @return array
     * @author Vinícius Feitosa da Silva <viniciusfesil@mail.com>
     * @return mixed
     */
    protected function getOPAuthConfiguration()
    {
        $oauthConfigArray = Zend_Registry::get("config")->toArray();
        if($oauthConfigArray && $oauthConfigArray['OAuth']['servicoHabilitado'] == true) {
            return $oauthConfigArray['OAuth'];
        }
    }

    /**
     * @author Vinícius Feitosa da Silva <viniciusfesil@mail.com>
     * @return void
     * @todo abstrair instância de "MinC_Auth_Adapter_LoginCidadao" para classe que implemente interface.
     */
    public function oauthresultAction()
    {
        $objAdapter = new MinC_Auth_Adapter_LoginCidadao();
        $auth = Zend_Auth::getInstance();
        $route = $this->getRedirectRoute();
        if ($auth->authenticate($objAdapter)->isValid()) {
            $auth->getStorage()->write((object)$auth->getIdentity());
            $this->redirect("{$route}success");
        }
        $this->redirect("{$route}error");
    }

    /**
     * @return string
     * @author Vinícius Feitosa da Silva <viniciusfesil@mail.com>
     */
    protected function getRedirectRoute()
    {
        $module = $this->getRequest()->getModuleName();
        $controller = $this->getRequest()->getControllerName();
        return "/{$module}/{$controller}/";
    }

    /**
     * @author Vinícius Feitosa da Silva <viniciusfesil@mail.com>
     * @return void
     */
    public function indexAction()
    {
        $this->start();
    }

    /**
     * @author Vinícius Feitosa da Silva <viniciusfesil@mail.com>
     * @return void
     */
    public function oauth2callbackAction()
    {
        $this->start();
    }

    /**
     * @author Vinícius Feitosa da Silva <viniciusfesil@mail.com>
     * @return void
     */
    protected function start()
    {
        $opauth = new Opauth($this->oauthConfig, false);
        $opauth->run();
    }

}