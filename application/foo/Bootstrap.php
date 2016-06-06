<?php
class Foo_Bootstrap extends Zend_Application_Module_Bootstrap
{
    public function _initTeste(){
        $resourceLoader = new Zend_Loader_Autoloader_Resource(array(
            'basePath'      => APPLICATION_PATH .'/foo',
            'namespace'     => 'Foo',
            'resourceTypes' => array(
                'model' => array(
                    'path'      => 'model/',
                    'namespace' => 'Model',
                ),
            ),
        ));
    }
}
