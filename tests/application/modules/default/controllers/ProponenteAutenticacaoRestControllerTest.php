<?php

class ProponenteAutenticacaoRestControllerTest extends MinC_Test_ControllerRestTestCase
{
    public function testPostLogin()
    {
        $this->resetRequest()->resetResponse();
        $this->inserirApplicationKey();
        $this->getRequest()->setMethod('POST')
            ->setRawBody(json_encode(array('usuario' => '23969156149', 'senha' => 'm239691')));
        $this->dispatch('proponente-autenticacao-rest');
        $this->assertResponseCode(200);
        $this->assertModule('default');
        $this->assertController('proponente-autenticacao-rest');
        $this->assertAction('post');
        $this->assertJson($this->getResponse()->getBody());
    }
}