<?php

class ProponenteRestControllerTest extends BaseTestCase
{
  public function testAcessoIndex()
  {
    $this->dispatch('proponente-rest/');
    //$this->assertController('proponente-rest');
    //$this->assertAction('index');
  }

}
