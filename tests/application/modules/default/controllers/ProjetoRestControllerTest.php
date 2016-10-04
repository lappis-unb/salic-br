<?php

class ProjetoRestControllerTest extends MinC_Test_ControllerRestTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->inserirApplicationKey();
    }

    public function testIndex()
    {
        $this->dispatch('projeto-rest/');
        $this->assertResponseCode(200);
        $this->assertModule('default');
        $this->assertController('projeto-rest');
        $this->assertAction('index');
        $this->assertJson($this->getResponse()->getBody());
    
    }

    public function testGet()
    {
        $this->dispatch('projeto-rest/id/1');
        $this->assertResponseCode(200);
        $this->assertModule('default');
        $this->assertController('projeto-rest');
        $this->assertAction('get');
        $this->assertJson($this->getResponse()->getBody());
    }

}
