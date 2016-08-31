<?php

namespace NFePHP\NFSe\Models;

use NFePHP\Common\Certificate\Pkcs12;
use NFePHP\Common\Dom\Dom;
use Psr\Log\LoggerInterface;

class SoapClient
{
    const SSL_DEFAULT = 0; //default
    const SSL_TLSV1 = 1; //TLSv1
    const SSL_SSLV2 = 2; //SSLv2
    const SSL_SSLV3 = 3; //SSLv3
    const SSL_TLSV1_0 = 4; //TLSv1.0
    const SSL_TLSV1_1 = 5; //TLSv1.1
    const SSL_TLSV1_2 = 6; //TLSv1.2
    const SOAP_VERSION_1_1 = 1;
    const SOAP_VERSION_1_2 = 2;
    
    protected $soapinfo = [];
    protected $soaperrors = '';
    protected $soaptimeout = 20;
    protected $soapver = 2;
    protected $soapprotocol = 0;
    protected $usecdata = false;
    protected $logger;
    
    protected $proxyIP = '';
    protected $proxyPort = '';
    protected $proxyUser = '';
    protected $proxyPass = '';
    
    protected $tempdir = '';
    protected $prifile = '';
    protected $pubfile = '';
    protected $certfile = '';

    protected $ns = [
        1 => [
            'xmlns:soapenv'=>"http://schemas.xmlsoap.org/soap/envelope/"
        ],
        2 => [
            'xmlns:soap'=>"http://www.w3.org/2003/05/soap-envelope",
            'xmlns:xsi'=>"http://www.w3.org/2001/XMLSchema-instance",
            'xmlns:xsd'=>"http://www.w3.org/2001/XMLSchema"
        ]    
    ];
    
    protected $envelope = [
        1 => "soapenv",
        2 => "soap"
    ];

    public function __construct(Pkcs12 $pkcs = null, LoggerInterface $logger = null)
    {
        $this->logger = $logger;
        $this->tempdir = sys_get_temp_dir().DIRECTORY_SEPARATOR;
        $this->prifile = tempnam($this->tempdir, 'Pri').'.pem';
        $this->pubfile = tempnam($this->tempdir, 'Pub').'.pem';
        $this->certfile = tempnam($this->tempdir, 'Cert').'.pem';
        $this->saveTemporaryKeyFiles($pkcs);
        
    }
    
    public function __destruct()
    {
        $this->removeTemporaryKeyFiles();
    }
    
    public function soapVersion($version = self::SOAP_VERSION_1_2)
    {
        if ($version != 2) {
            $version = 1;
        }
        $this->soapver = $version;
    }
    
    public function soapTimeout($timesecs)
    {
        $this->soaptimeout = $timesecs;
    }
    
    public function soapProxy($ip, $port, $user, $password)
    {
        $this->proxyIP = $ip;
        $this->proxyPort = $port;
        $this->proxyUser = $user;
        $this->proxyPass = $password;        
    }
    
    public function soapEnvelopeUsingCDATA($cdata = false)
    {
        $this->usecdata = $cdata;
    }
    
    public function soapSecurityProtocol($protocol = self::SSL_DEFAULT)
    {
        $this->soapprotocol = $protocol;
    }
    
    public function soapEnvelope($header, $body)
    {
        if ($this->soapver == 1) {
            $envelope = "<soapenv:Envelope xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\"><soapenv:Header/><soapenv:Body>$body</soapenv:Body></soapenv:Envelope>";
        } else {
            $envelope = "<soap:Envelope xmlns:soap=\"http://www.w3.org/2003/05/soap-envelope\"><soap:Header/><soap:Body>$body</soap:Body></soap:Envelope>";
        }
        $dom = new Dom('1.0', 'utf-8');
        $dom->loadXMLString($envelope);
        if ($this->usecdata) {
            $this->addCdata($dom, $body);
        }
        return $dom->saveXML();
    }
    
    private function addCdata(Dom &$dom, $body)
    {
        $root = $dom->documentElement;
        $oldnode = $root->getElementsByTagName('Body')->item(0);
        $tag = $oldnode->tagName;
        $root->removeChild($oldnode);
        $newnode = $dom->createElement($tag);
        $cdatanode = $dom->createCDATASection($body);
        $newnode->appendChild($cdatanode);
        $root->appendChild($newnode);
    }

    public function soapSend($url, $port, $envelope, $params)
    {
        $oCurl = curl_init();
        $this->curlSetProxy($oCurl);
        curl_setopt($oCurl, CURLOPT_URL, $url);
        curl_setopt($oCurl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($oCurl, CURLOPT_CONNECTTIMEOUT, $this->soapTimeout);
        curl_setopt($oCurl, CURLOPT_VERBOSE, 1);
        curl_setopt($oCurl, CURLOPT_HEADER, 1);
        curl_setopt($oCurl, CURLOPT_SSLVERSION, $this->soapprotocol);
        curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($oCurl, CURLOPT_PORT, 443);
        curl_setopt($oCurl, CURLOPT_SSLCERT, $this->certfile);
        curl_setopt($oCurl, CURLOPT_SSLKEY, $this->prifile);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($oCurl, CURLOPT_POST, 1);
        curl_setopt($oCurl, CURLOPT_POSTFIELDS, $envelope);
        curl_setopt($oCurl, CURLOPT_HTTPHEADER, $params);
        //connect and send
        //$response = curl_exec($oCurl);
        $this->logger->debug($envelope);
        //$this->soapinfo = curl_getinfo($oCurl);
        //$this->soaperrors = curl_error($oCurl);
        curl_close($oCurl);
        return $response;
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
    
    private function saveTemporaryKeyFiles(Pkcs12 $pkcs = null)
    {
        if (is_object($pkcs)) {
            file_put_contents($this->prifile, $pkcs->priKey);
            file_put_contents($this->pubfile, $pkcs->pubKey);
            file_put_contents($this->certfile, $pkcs->certKey);
        }    
    }

    private function removeTemporaryKeyFiles()
    {
        $files = glob($this->tempdir.'*.pem');
        foreach($files as $file) {
            unlink($file);
        }
    }
}
