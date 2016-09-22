<?php

abstract class MinC_Test_ControllerRestTestCase extends MinC_Test_Abstract
{
    protected $authorizationCode = null;

    protected $applicationKey = null;

    /**
     * @return null
     */
    public function getApplicationKey()
    {
        return $this->applicationKey;
    }

    /**
     * @param null $applicationKey
     * @return MinC_Test_ControllerRestTestCase
     */
    public function setApplicationKey($applicationKey)
    {
        $this->applicationKey = $applicationKey;
        return $this;
    }

    /**
     * @return null
     */
    public function getAuthorizationCode()
    {
        return $this->authorizationCode;
    }

    /**
     * @param null $authorizationCode
     * @return MinC_Test_ControllerRestTestCase
     */
    public function setAuthorizationCode($authorizationCode)
    {
        $this->authorizationCode = $authorizationCode;
        $this->getRequest()->setHeader('Authorization', $this->authorizationCode);
        return $this;
    }
    
    public function autenticar()
    {
        $this->resetRequest()->resetResponse();
        $this->inserirApplicationKey();
        $this->getRequest()->setMethod('POST')
            ->setRawBody(json_encode(array('usuario' => '23969156149', 'senha' => 'm239691')));
        $this->dispatch('proponente-autenticacao-rest');
        $responseBody = json_decode($this->getResponse()->getBody());
        $this->bootstrap();
        if (is_object($responseBody) && isset($responseBody->authorization))
            $this->setAuthorizationCode($responseBody->authorization);
        $this->inserirApplicationKey();
    }

    public function inserirApplicationKey($applicationKey = null)
    {
        if ($applicationKey) {
            $this->getRequest()->setHeader('ApplicationKey', $applicationKey);
        } else if ($this->getApplicationKey()){
            $this->getRequest()->setHeader('ApplicationKey', $this->getApplicationKey());
        } else if($this->getConfig() && $this->getConfig()->resources->view->service->salicMobileHash) {
            $this->setApplicationKey($this->getConfig()->resources->view->service->salicMobileHash);
            $this->getRequest()->setHeader('ApplicationKey', $this->getConfig()->resources->view->service->salicMobileHash);
        }
    }
}