<?php

namespace NFePHP\NFSe\Tests\Prodam;

use NFePHP\NFSe\Tests\NFSeTestCase;
use NFePHP\NFSe\Models\Prodam\Convert;

class ConvertTest extends NFSeTestCase
{
    /**
     * @covers NFePHP\NFSe\Models\Prodam\Convert::toRps
     * @covers NFePHP\NFSe\Models\Prodam\Convert::validTipos
     * @covers NFePHP\NFSe\Models\Prodam\Convert::loadRPS
     * @covers NFePHP\NFSe\Models\Prodam\Convert::loadTipo2
     * @covers NFePHP\NFSe\Models\Prodam\Convert::f1Entity
     * @covers NFePHP\NFSe\Models\Prodam\Convert::f2Entity
     * @covers NFePHP\NFSe\Models\Prodam\Convert::f9Entity
     * @covers NFePHP\NFSe\Models\Prodam\Convert::zArray2Rps
     * @covers NFePHP\NFSe\Models\Prodam\Convert::extract
     */
    public function testToRps()
    {
        $rpss = Convert::toRps($this->fixturesPath . '/Prodam/LoteRPS2.txt');
        $this->assertInstanceOf('\NFePHP\NFSe\Models\Prodam\Rps', $rpss[0]);
    }
    
    /**
     * @covers NFePHP\NFSe\Models\Prodam\Convert::toRps
     * @covers NFePHP\NFSe\Models\Prodam\Convert::validTipos
     * @covers NFePHP\NFSe\Models\Prodam\Convert::loadRPS
     * @covers NFePHP\NFSe\Models\Prodam\Convert::loadTipo2
     * @covers NFePHP\NFSe\Models\Prodam\Convert::f1Entity
     * @covers NFePHP\NFSe\Models\Prodam\Convert::f2Entity
     * @covers NFePHP\NFSe\Models\Prodam\Convert::f9Entity
     * @covers NFePHP\NFSe\Models\Prodam\Convert::zArray2Rps
     * @covers NFePHP\NFSe\Models\Prodam\Convert::extract
     * @expectedException InvalidArgumentException
     */
    public function testToRpsFail2And6Types()
    {
        $rpss = Convert::toRps($this->fixturesPath . '/Prodam/LoteRPS26_fail.txt');
    }
}
