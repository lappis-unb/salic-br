<?php

class ProjetoExtratoRestControllerTest extends MinC_Test_ControllerRestTestCase
{
    public function testIndex()
    {
        $this->autenticar();
        $this->dispatch('projeto-extrato-rest');
        $this->assertResponseCode(200);
        $this->assertModule('default');
        $this->assertController('projeto-extrato-rest');
        $this->assertAction('index');
        $this->assertJson($this->getResponse()->getBody());
    }
}