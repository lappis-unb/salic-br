<?php

/**
 * AgentesControllerTest
 *
 * @author wouerner <woeurner@gmail.como>
 */
class AgentesControllerTest extends MinC_Test_ControllerActionTestCase
{

    /**
     * TestIncluiragente Acesso a tela de incluir agente
     *
     * @access public
     * @return void
     */
    public function testIncluiragente()
    {
        $this->autenticar();

        //reset para garantir respostas.
        $this->resetRequest()
            ->resetResponse();

        $this->perfilParaProponente();

        //reset para garantir respostas.
        $this->resetRequest()
            ->resetResponse();

        $this->dispatch('agente/agentes/incluiragente?menuLateral=false&acao=prop');
        $this->assertModule('agente');
        $this->assertController('agentes');
        $this->assertAction('incluiragente');
    }
}
