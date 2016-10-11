<?php

/**
 * IndexControllerTest
 *
 * @uses MinC
 * @uses _Test_ControllerActionTestCase
 * @author  wouerner <wouerner@gmail.com>
 */
class IndexControllerTest  extends MinC_Test_ControllerActionTestCase
{
    public function testCadastrarusuarioAction()
    {
        $this->dispatch('/autenticacao/index/cadastrarusuario');
        $this->assertModule('autenticacao');
        $this->assertController('index');
        $this->assertAction('cadastrarusuario');
    }

    public function testSolicitarsenhaAction()
    {
        $this->dispatch('/autenticacao/index/solicitarsenha');
        $this->assertModule('autenticacao');
        $this->assertController('index');
        $this->assertAction('solicitarsenha');
    }
}

