<?php
class Proposta_Bootstrap extends Zend_Application_Module_Bootstrap
{
    public function _initPath()
    {
        require_once APPLICATION_PATH . '/modules/proposta/controllers/GenericController.php';
    }
}
