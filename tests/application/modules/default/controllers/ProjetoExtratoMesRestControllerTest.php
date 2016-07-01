<?php

class ProjetoExtratoMesRestControllerTest extends MinC_Test_ControllerRestTestCase
{
    public function testIndex()
    {
        $this->autenticar();
        $this->dispatch('projeto-extrato-mes-rest');
        $this->assertResponseCode(200);
        $this->assertModule('default');
        $this->assertController('projeto-extrato-mes-rest');
        $this->assertAction('index');
        $this->assertJson($this->getResponse()->getBody());
    }
}