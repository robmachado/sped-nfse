<?php

namespace NFePHP\NFSe\Common;

use SoapClient;
use NFePHP\Common\Certificate;
use Psr\Log\LoggerInterface;
use RuntimeException;
use SimpleXMLElement;

class SoapNative
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
    
    public function soapSend($url, $operation, $version, $parameters)
    {
        $this->prepare($url);
        
        try {
            $result = $this->connection->$operation($parameters);
            $lastH = $this->connection->__getLastRequestHeaders();
            $lastM = $this->connection->__getLastRequest();
            
        } catch (SoapFault $e) {
            throw new RuntimeException($e->getMessage());
        } catch (Exception $e) {
            throw new $e;
        }
        return $lastH .'<BR>'. $lastM .'<BR>'. $result->RetornoXML;
    }

    protected function prepare($url, $soapver = SOAP_1_2)
    {
        $wsdl = "$url?WSDL";
        $params = [
            'local_cert' => $this->certfile,
            'passphrase' => '',
            'connection_timeout' => $this->soaptimeout,
            'encoding' => 'UTF-8',
            'verifypeer' => false,
            'verifyhost' => false,
            'soap_version' => $soapver,
            'trace' => true,
            'cache_wsdl' => WSDL_CACHE_NONE
        ];
        try {
            $this->connection = new SoapClient($wsdl, $params);
        } catch (SoapFault $e) {
            throw new RuntimeException($e->getMessage());
        } catch (Exception $e) {
            throw new $e;
        }
    }
    
    private function saveTemporarilyKeyFiles()
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

    private function removeTemporarilyKeyFiles()
    {
        unlink($this->prifile);
        unlink($this->pubfile);
        unlink($this->certfile);
        unlink(substr($this->prifile, 0, strlen($this->prifile)-4));
        unlink(substr($this->pubfile, 0, strlen($this->pubfile)-4));
        unlink(substr($this->certfile, 0, strlen($this->certfile)-4));
    }
}
