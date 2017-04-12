<?php

class MinC_Application_Resource_Paginator extends Zend_Application_Resource_ResourceAbstract
{
    public function init()
    {
        Zend_View_Helper_PaginationControl::setDefaultViewPartial('paginacao/paginacaoMinc.phtml');
    }
}