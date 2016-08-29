<?php

namespace NFePHP\NFSe\Tests\Models;

use NFePHP\NFSe\Models\Signner;
use NFePHP\NFSe\Tests\NFSeTestCase;

class SignnerTest extends NFSeTestCase
{
    public function testSign()
    {
        $priKeyFile = $this->fixturesPath
            . 'certs'
            . DIRECTORY_SEPARATOR
            . '99999090910270_priKEY.pem';
        
        $priKey = file_get_contents($priKeyFile);
        $string = "qwertyuiopasdfghjklçzxcvbnm1234567890";
        $signedString = Signner::sign($string, $priKey, OPENSSL_ALGO_SHA1);
        $expected = 'pxD6K+khudIFVerNHTgabndKw49obAQJJNbZ3oyLJtqL/2T/mx2DRuZjctFwuWzQqpBFqOnbRXo4JABrMEDTRH4sxOKlcktoPcuf6Zf6h3iGmG0GBwBEeoS0lBKRElXvC5UJHrMeqRr4akvCL2xAO7wTkDSsTxVUi631YbG8k6g=';
        $this->assertEquals($expected, $signedString);
    }
}
