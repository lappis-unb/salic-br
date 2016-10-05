<?php
require_once 'Zend/Test/PHPUnit/ControllerTestCase.php';

abstract class MinC_Test_Abstract extends Zend_Test_PHPUnit_ControllerTestCase
{
    protected $application;

    public function setUp()
    {
        $this->bootstrap = array($this, 'appBootstrap');
        parent::setUp();
        $this->resetRequest()->resetRequest();
    }

    public function appBootstrap()
    {
        $this->application = new Zend_Application(APPLICATION_ENV, APPLICATION_PATH . '/configs/application.ini');
        $this->application->bootstrap();
    }

    public function getConfig()
    {
        return Zend_Registry::getInstance()->get('config');
    }
}
