<?php

class PocMigrateDBFunctionsToAppModelsTest extends PHPUnit_Framework_TestCase
{

    private $_arrayObjectStack;

    public function setUp()
    {
        $std = new stdClass();
        $std->testFieldOne = 1;
        $this->_arrayObjectStack = [
            $std,
            $std,
            $std,
            $std,
            $std,
            $std
        ];

        parent::setUp();
    }

    public function testInjectAttribute()
    {
        foreach ($this->_arrayObjectStack as &$item) {
            $item->testFieldOne = 2;
        }
        $this->assertTrue(is_array($this->_arrayObjectStack));
        $this->assertFalse(!is_object($this->_arrayObjectStack));
    }
}
