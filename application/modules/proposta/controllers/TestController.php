<?php

class Proposta_TestController extends Proposta_GenericController {

    public function indexAction () {
        xd("Teste >> M&oacute;dulo " .  ucfirst($this->moduleName));
    }
}
