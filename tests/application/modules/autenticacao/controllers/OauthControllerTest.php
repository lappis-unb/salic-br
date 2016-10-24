<?php

/**
 * Class OauthControllerTest
 * @author Vinícius Feitosa da Silva <viniciusfesil@mail.com>
 * @since 14/10/2016 12:20
 *
 * Dica : Para executar o teste somente para esse arquivo execute o comando a seguir na raiz do projeto:
 *
 *     ./vendor/bin/phpunit --bootstrap tests/application/Bootstrap.php UnitTest tests/application/modules/autenticacao/controllers/OauthControllerTest.php
 *
 */
class OauthControllerTest extends MinC_Test_ControllerActionTestCase {

    public function testIndex()
    {
        $this->dispatch('/autenticacao/oauth');
        $this->assertModule('autenticacao');
        $this->assertController('oauth');
        $this->assertAction('index');
        $this->assertRedirect('/autenticacao/oauth/logincidadao');
    }

    public function testLogincidadaoAction() {
        $this->dispatch('/autenticacao/oauth/logincidadao');
    }
}
