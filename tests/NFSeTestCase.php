<?php

namespace NFePHP\NFSe\Tests;

class NFSeTestCase extends \PHPUnit_Framework_TestCase
{
    public $fixturesPath = '';
    
    public function __construct()
    {
        $this->fixturesPath = dirname(__FILE__) . '/fixtures/';
    }
}
