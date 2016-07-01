<?php

class ProponenteRestControllerTest extends MinC_Test_ControllerRestTestCase
{
    public function testIndex()
    {
        $this->autenticar();
        $this->dispatch('proponente-rest');
        $this->assertResponseCode(200);
        $this->assertModule('default');
        $this->assertController('proponente-rest');
        $this->assertAction('index');
        $this->assertJson($this->getResponse()->getBody());
    }
}