<?php

/**
 * AgentesControllerTest
 *
 * @author wouerner <woeurner@gmail.como>
 * @author anderson <anderson.asp.si@gmail.com>
 */
class RespostaControllerTest extends MinC_Test_ControllerActionTestCase
{

    /**
     * TestIncluiragente Acesso a tela de incluir agente
     *
     * @access public
     * @return void
     */
    public function testIndex()
    {
        $this->autenticar();


        $this->mudarPerfil2();

        //reset para garantir respostas.
        $this->resetRequest()
            ->resetResponse();

        $this->dispatch('/admissibilidade/admissibilidade/exibirpropostacultural?idPreProjeto=237778');
        $this->assertModule('default');
        $this->assertController('admissibilidade');
        $this->assertAction('exibirpropostacultural');
    }
}
