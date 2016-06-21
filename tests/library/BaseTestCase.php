<?php
require_once 'Zend/Application.php';
require_once 'Zend/Test/PHPUnit/ControllerTestCase.php';

abstract class BaseTestCase extends Zend_Test_PHPUnit_ControllerTestCase
{
    protected $application;

    public function setUp()
    {
        $this->bootstrap = array($this, 'appBootstrap');
        parent::setUp();
    }

    public function appBootstrap()
    {
        $this->application = new Zend_Application('default', APPLICATION_PATH . '/configs/config.ini');
        $this->application->bootstrap();
    }

}
