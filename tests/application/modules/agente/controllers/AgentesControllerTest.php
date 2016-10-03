<?php

class AgentesControllerTest extends MinC_Test_ControllerActionTestCase
{

    public function testIncluiragente()
    {
        $this->autenticar();
        $this->dispatch('agente/agentes/incluiragente?menuLateral=false&acao=prop');
        $this->assertModule('agente');
        $this->assertController('agentes');
        $this->assertAction('incluiragente');
        //$this->assertRedirect();
    }
}
