<?php

class IndexControllerTest extends MinC_Test_ControllerActionTestCase
{
    public function testLogin()
    {
        $this->autenticar();
        $this->assertModule('default');
        $this->assertController('index');
        $this->assertAction('login');
        $this->assertRedirect();
    }
    public function testLogout()
    {
        $this->autenticar();
        $this->logout();
        $this->assertRedirect();
    }
}