<?php

/**
 * ManterpropostaincentivofiscalControllerTest
 *
 * @author  wouerner <wouerner@gmail.com>
 */
class ManterpropostaincentivofiscalControllerTest extends MinC_Test_ControllerActionTestCase
{
    /**
     * TestListarpropostaAction Verifica acesso a tela.
     *
     * @access public
     * @return void
     */
    public function testListarpropostaAction()
    {
        $this->autenticar();

        //reset para garantir respostas.
        $this->resetRequest()
            ->resetResponse();

        // trocar para perfil Proponente
        $this->request->setMethod('GET');
        $this->dispatch('/autenticacao/perfil/alterarperfil?codGrupo=1111&codOrgao=2222');
        $this->assertRedirectTo('/principalproponente');

        //reset para garantir respostas.
        $this->resetRequest()
            ->resetResponse();

        $this->dispatch('/proposta/manterpropostaincentivofiscal/listarproposta');
        $this->assertModule('proposta');
        $this->assertController('manterpropostaincentivofiscal');
        $this->assertAction('listarproposta');

        //verifica se tela carregou corretamente
        $this->assertQuery('div#titulo div');
    }
}
