<?php

class Manual_Bootstrap extends Zend_Application_Module_Bootstrap
{
    public function _initPath()
    {
        require_once APPLICATION_PATH . '/modules/default/views/helpers/StatusCompFisicaProjeto.php';
        require_once APPLICATION_PATH . '/modules/default/views/helpers/ConverterBytes.php';
    }
}