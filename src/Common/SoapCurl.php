<?php

namespace NFePHP\NFSe\Common;

use NFePHP\Common\Certificate;
use NFePHP\Common\Dom\Dom;
use Psr\Log\LoggerInterface;
use RuntimeException;

class SoapCurl
{
    const SSL_DEFAULT = 0; //default
    const SSL_TLSV1 = 1; //TLSv1
    const SSL_SSLV2 = 2; //SSLv2
    const SSL_SSLV3 = 3; //SSLv3
    const SSL_TLSV1_0 = 4; //TLSv1.0
    const SSL_TLSV1_1 = 5; //TLSv1.1
    const SSL_TLSV1_2 = 6; //TLSv1.2
    
    protected $soaptimeout = 20;
    protected $soapport = 443;
    protected $soapprotocol = self::SSL_DEFAULT;
    protected $logger;
    
    protected $responseHead = '';
    protected $responseBody = '';
    
    protected $proxyIP = '';
    protected $proxyPort = '';
    protected $proxyUser = '';
    protected $proxyPass = '';
    
    protected $certificate;
    protected $tempdir = '';
    protected $prifile = '';
    protected $pubfile = '';
    protected $certfile = '';

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
    
    public function soapPort($port)
    {
        $this->soapport = $port;
    }
    
    public function soapProxy($ip, $port, $user, $password)
    {
        $this->proxyIP = $ip;
        $this->proxyPort = $port;
        $this->proxyUser = $user;
        $this->proxyPass = $password;
    }
    
    public function soapSecurityProtocol($protocol = self::SSL_DEFAULT)
    {
        $this->soapprotocol = $protocol;
    }
    
    /**
     * sendSoap
     * @param type $url
     * @param type $port
     * @param type $envelope
     * @param type $params
     * @return string
     * @throws \RuntimeException
     */
    public function soapSend($url, $operation, $soapver, $parameters)
    {
        $soapinfo = array();
        $soaperror = '';
        $response = '';
        
        $port = 443;
        
        $envelope = $this->mkEnvSoap2($operation, $parameters);
        $msgSize = strlen($envelope);
        
        $curlparams = [
            "Content-Type: application/soap+xml;"
                . "charset=utf-8;"
                . "action=\"http://www.prefeitura.sp.gov.br/nfe/ws/". lcfirst($operation) ."\"",
            "Content-length: $msgSize"
        ];
        
        $oCurl = curl_init();
        $this->curlSetProxy($oCurl);
        curl_setopt($oCurl, CURLOPT_URL, $url);
        curl_setopt($oCurl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($oCurl, CURLOPT_CONNECTTIMEOUT, $this->soaptimeout);
        curl_setopt($oCurl, CURLOPT_VERBOSE, 1);
        curl_setopt($oCurl, CURLOPT_HEADER, 1);
        curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($oCurl, CURLOPT_PORT, $port);
        if ($port == 443) {
            curl_setopt($oCurl, CURLOPT_SSLVERSION, $this->soapprotocol);
            curl_setopt($oCurl, CURLOPT_SSLCERT, $this->certfile);
            curl_setopt($oCurl, CURLOPT_SSLKEY, $this->prifile);
        }
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
        if (!empty($envelope)) {
            curl_setopt($oCurl, CURLOPT_POST, 1);
            curl_setopt($oCurl, CURLOPT_POSTFIELDS, $envelope);
        }
        if (!empty($curlparams)) {
            curl_setopt($oCurl, CURLOPT_HTTPHEADER, $curlparams);
        }
        
        //log sended data
        //$this->logger->debug($envelope);
        
        //connect and send
        $response = curl_exec($oCurl);
        
        $soapinfo = curl_getinfo($oCurl);
        $soaperrors = curl_error($oCurl);
        $headsize = curl_getinfo($oCurl, CURLINFO_HEADER_SIZE);
        
        //log soap info
        //log soaperrors if exists
        //log soap response ever
        
        curl_close($oCurl);
        /*
        if (!empty($soapinfo)) {
            if ($soapinfo["http_code"] != '200') {
                $msg = "Falha na comunicação.[".$soapinfo["http_code"]."] ".$response;
                throw new \RuntimeException($msg);
            }
        }
         * 
         */
        return $this->stripHtmlPart($response, $headsize);
    }
    
    private function mkEnvSoap1($operation, $parameters)
    {
        $request = $this->mkRequest($operation, $parameters);
        $envelope = "<soapenv:Envelope "
                . "xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\" "
                . "xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" "
                . "xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\">"
                . "<soapenv:Header/>"
                . "<soapenv:Body>"
                . $request
                . "</soapenv:Body>"
                . "</soapenv:Envelope>";
        return $envelope;
    }
    
    private function mkEnvSoap2($operation, $parameters)
    {
        $request = $this->mkRequest($operation, $parameters);
        $envelope = "<soap:Envelope "
            . "xmlns:soap=\"http://www.w3.org/2003/05/soap-envelope\" "
            . "xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" "
            . "xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\">"
            . "<soap:Header/>"
            . "<soap:Body>$request</soap:Body>"
            . "</soap:Envelope>";
        echo $envelope;
        die;
        return $envelope;
    }
    
    private function mkRequest($operation, $parameters)
    {
        $request = "<$operation>";
        foreach ($parameters as $key => $value) {
            $request .= "<$key>$value</$key>";
        }
        $request .= "</$operation>";
        return $request;
    }
    
    private function stripHtmlPart($response, $headsize)
    {
        $this->responseHead = trim(substr($response, 0, $headsize));
        $this->responseBody = trim(substr($response, $headsize));
        $xPos = stripos($this->responseBody, "<");
        $lenresp = strlen($this->responseBody);
        $xml = '';
        if ($xPos !== false) {
            $xml = substr($this->responseBody, $xPos, $lenresp-$xPos);
        }
        $test = simplexml_load_string($xml, 'SimpleXmlElement', LIBXML_NOERROR+LIBXML_ERR_FATAL+LIBXML_ERR_NONE);
        if ($test === false) {
            $xml = '';
        }
        return $xml;
    }
    
    private function curlSetProxy(&$oCurl)
    {
        if ($this->proxyIP != '') {
            curl_setopt($oCurl, CURLOPT_HTTPPROXYTUNNEL, 1);
            curl_setopt($oCurl, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
            curl_setopt($oCurl, CURLOPT_PROXY, $this->proxyIP.':'.$this->proxyPORT);
            if ($this->proxyPASS != '') {
                curl_setopt($oCurl, CURLOPT_PROXYUSERPWD, $this->proxyUSER.':'.$this->proxyPASS);
                curl_setopt($oCurl, CURLOPT_PROXYAUTH, CURLAUTH_BASIC);
            }
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
        $files = glob($this->tempdir.'*');
        foreach ($files as $file) {
            unlink($file);
        }
    }
}
