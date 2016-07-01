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
        $this->resetRequest()
            ->resetResponse();

        $this->request->setMethod('POST')
            ->setPost(array(
                'Login' => ($login) ? $login : '23969156149',
                'Senha' => ($senha) ? $senha : 'r239691'
            ));
        $this->dispatch('/index/login');

        $this->setAutenticado(true);
    }

    public function logout()
    {
        $this->resetRequest()->resetResponse();
        $this->dispatch('index/logout');
        $this->setAutenticado(false);
    }

}
