<?php

abstract class MinC_Test_ControllerActionTestCase extends MinC_Test_Abstract
{
    private $autenticado = false;

    /**
     * @return boolean
     */
    public function isAutenticado()
    {
        return $this->autenticado;
    }

    /**
     * @param boolean $autenticado
     * @return MinC_Test_ControllerActionTestCase
     */
    public function setAutenticado($autenticado)
    {
        $this->autenticado = $autenticado;
        return $this;
    }

    public function tearDown()
    {
        if ($this->isAutenticado())
            $this->logout();
        parent::tearDown();
    }

    public function autenticar($login = null , $senha = null)
    {
        $config = new Zend_Config_Ini(
            APPLICATION_PATH. '/configs/application.ini',
            APPLICATION_ENV
        );

        $this->resetRequest()
            ->resetResponse();

        $this->request->setMethod('POST')
            ->setPost(array(
                'Login' => ($login) ? $login : $config->login,
                'Senha' => ($senha) ? $senha : $config->password
            ));
        //$this->dispatch('/index/login');
        $this->dispatch('/autenticacao/index/login');

        $this->setAutenticado(true);
    }

    public function logout()
    {
        $this->resetRequest()->resetResponse();
        $this->dispatch('index/logout');
        $this->setAutenticado(false);
    }

}
