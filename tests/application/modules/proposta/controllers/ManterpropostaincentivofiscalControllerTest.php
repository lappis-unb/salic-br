<?php

class ManterpropostaincentivofiscalControllerTest extends MinC_Test_ControllerActionTestCase
{

    public function testListarpropostaAction()
    {
        $this->autenticar();
        $this->request->setMethod('GET');
        $this->dispatch('/autenticacao/perfil/alterarperfil?codGrupo=1111&codOrgao=2222');
        $this->assertRedirectTo('/principalproponente', 'OK');
        //$this->dispatch('/principalproponente');
        //$this->assertModule('default');

        //$this->assertController('principalproponente');

        //$this->dispatch('/autenticacao/perfil/alterarperfil?codGrupo=1111&codOrgao=2222');
        //var_dump(Zend_Auth::getInstance()->getIdentity());
        //$this->assertNotRedirect();
        //$this->assertNotRedirect();
        //$this->assertQuery('body');
        //$this->dispatch('/proposta/manterpropostaincentivofiscal/listarproposta');
        //$this->assertModule('proposta');
        //$this->assertController('manterpropostaincentivofiscal');
        //$this->assertAction('listarproposta');
//$this->assertRedirectTo('/proposta/manterpropostaincentivofiscal/listarproposta', $message = '');
        //$this->assertQuery('html');
    }
}
