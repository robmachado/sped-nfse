<?php

namespace NFePHP\NFSe\Tests;

use NFePHP\NFSe\NFSe;
use NFePHP\NFSe\NFSeStatic;
use NFePHP\Common\Certificate;

class NFSeTest extends NFSeTestCase
{
    public function testInstanciarNFSE()
    {
        $nfse = new NFse(
            $this->configJson,
            Certificate::readPfx($this->contentpfx, $this->passwordpfx)
        );
        $expected = 'NFePHP\NFSe\Counties\M3550308\Tools';
        $actual = get_class($nfse->tools);
        $this->assertEquals($expected, $actual);
        
        $expected = 'NFePHP\NFSe\Counties\M3550308\Rps';
        $actual = get_class($nfse->rps);
        $this->assertEquals($expected, $actual);
        
        $expected = 'NFePHP\NFSe\Counties\M3550308\Convert';
        $actual = get_class($nfse->convert);
        $this->assertEquals($expected, $actual);
        
        $expected = 'NFePHP\NFSe\Counties\M3550308\Response';
        $actual = get_class($nfse->response);
        $this->assertEquals($expected, $actual);
    }
}
