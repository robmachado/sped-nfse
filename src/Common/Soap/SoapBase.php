<?php

namespace NFePHP\NFSe\Common\Soap;

use NFePHP\Common\Certificate;
use Psr\Log\LoggerInterface;

class SoapBase
{
    const SSL_DEFAULT = 0; //default
    const SSL_TLSV1 = 1; //TLSv1
    const SSL_SSLV2 = 2; //SSLv2
    const SSL_SSLV3 = 3; //SSLv3
    const SSL_TLSV1_0 = 4; //TLSv1.0
    const SSL_TLSV1_1 = 5; //TLSv1.1
    const SSL_TLSV1_2 = 6; //TLSv1.2
    
    protected $soapprotocol = self::SSL_DEFAULT;

    protected $certificate;
    protected $soaptimeout = 20;
    protected $proxyIP = '';
    protected $proxyPort = '';
    protected $proxyUser = '';
    protected $proxyPass = '';

    protected $tempdir = '';
    protected $prifile = '';
    protected $pubfile = '';
    protected $certfile = '';

    public function __construct(Certificate $certificate = null, LoggerInterface $logger = null)
    {
        $this->logger = $logger;
        $this->certificate = $certificate;
        $this->saveTemporarilyKeyFiles();
    }
    
    public function __destruct()
    {
        $this->removeTemporarilyKeyFiles();
    }
    
    public function setCertificate(Certificate $certificate)
    {
        $this->certificate = $certificate;
        $this->saveTemporarilyKeyFiles();
    }
    
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function soapTimeout($timesecs)
    {
        $this->soaptimeout = $timesecs;
    }
    
    public function soapSecurityProtocol($protocol = self::SSL_DEFAULT)
    {
        $this->soapprotocol = $protocol;
    }
    
    public function soapProxy($ip, $port, $user, $password)
    {
        $this->proxyIP = $ip;
        $this->proxyPort = $port;
        $this->proxyUser = $user;
        $this->proxyPass = $password;
    }
    
    protected function saveTemporarilyKeyFiles()
    {
        if (is_object($this->certificate)) {
            $this->tempdir = sys_get_temp_dir().DIRECTORY_SEPARATOR.'certs'.DIRECTORY_SEPARATOR;
            if (! is_dir($this->tempdir)) {
                mkdir($this->tempdir);
            }
            $this->prifile = tempnam($this->tempdir, 'Pri').'.pem';
            $this->pubfile = tempnam($this->tempdir, 'Pub').'.pem';
            $this->certfile = tempnam($this->tempdir, 'Cert').'.pem';
            file_put_contents($this->prifile, $this->certificate->privateKey);
            file_put_contents($this->pubfile, $this->certificate->publicKey);
            file_put_contents($this->certfile, $this->certificate->privateKey.$this->certificate->publicKey);
        }
    }

    protected function removeTemporarilyKeyFiles()
    {
        unlink($this->prifile);
        unlink($this->pubfile);
        unlink($this->certfile);
        unlink(substr($this->prifile, 0, strlen($this->prifile)-4));
        unlink(substr($this->pubfile, 0, strlen($this->pubfile)-4));
        unlink(substr($this->certfile, 0, strlen($this->certfile)-4));
    }
}
