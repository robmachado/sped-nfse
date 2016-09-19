<?php

namespace NFePHP\NFSe\Common\Soap;

use NFePHP\Common\Certificate;
use Psr\Log\LoggerInterface;

interface SoapInterface
{
    public function setCertificate(Certificate $certificate);
    public function setLogger(LoggerInterface $logger);
    public function soapTimeout($timesecs);
    public function soapSecurityProtocol($protocol = self::SSL_DEFAULT);
    public function soapProxy($ip, $port, $user, $password);
    public function soapSend(
        $url,
        $operation = '',
        $action = '',
        $soapver = SOAP_1_2,
        $parameters = [],
        $namespaces = []
    );
}
