<?php
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    public function _init($self){

        $controller = Zend_Controller_Front::getInstance();
        $controller->setControllerDirectory(array('default' => APPLICATION_PATH.'/application/default/controller', 'foo'=>APPLICATION_PATH. '/application/foo/controller'));
        $controller->dispatch();
    }
}
