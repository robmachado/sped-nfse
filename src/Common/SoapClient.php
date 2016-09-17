<?php

namespace NFePHP\NFSe\Common;

class SoapClient
{
    protected $certificate;
    protected $soaptimeout = 20;
    
    protected $tempdir = '';
    protected $prifile = '';
    protected $pubfile = '';
    protected $certfile = '';
    protected $connection;

    public function __construct(Certificate $certificate, LoggerInterface $logger = null)
    {
        $this->logger = $logger;
        $this->certificate = $certificate;
        $this->saveTemporarilyKeyFiles();
    }
    
    public function __destruct()
    {
        $this->removeTemporarilyKeyFiles();
    }

    public function soapTimeout($timesecs)
    {
        $this->soaptimeout = $timesecs;
    }    
}
