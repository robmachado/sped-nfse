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
    
    protected $soaptimeout = 20;
    protected $soapprotocol = self::SSL_DEFAULT;
    protected $logger;
    
    protected $proxyIP = '';
    protected $proxyPort = '';
    protected $proxyUser = '';
    protected $proxyPass = '';
    
    protected $tempdir = '';
    protected $prifile = '';
    protected $pubfile = '';
    protected $certfile = '';

    public function __construct(Pkcs12 $pkcs = null, LoggerInterface $logger = null)
    {
        $this->logger = $logger;
        $this->saveTemporaryKeyFiles($pkcs);
    }
    
    public function __destruct()
    {
        $this->removeTemporaryKeyFiles();
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
    
    public function soapSecurityProtocol($protocol = self::SSL_DEFAULT)
    {
        $this->soapprotocol = $protocol;
    }
    
    /**
     *
     * @param type $url
     * @param type $port
     * @param type $envelope
     * @param type $params
     * @return string
     * @throws \RuntimeException
     */
    public function soapSend($url, $port, $envelope, $params)
    {
        $soapinfo = array();
        $soaperror = '';
        $response = '';
        
        $oCurl = curl_init();
        $this->curlSetProxy($oCurl);
        curl_setopt($oCurl, CURLOPT_URL, $url);
        curl_setopt($oCurl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($oCurl, CURLOPT_CONNECTTIMEOUT, $this->soapTimeout);
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
        curl_setopt($oCurl, CURLOPT_POST, 1);
        curl_setopt($oCurl, CURLOPT_POSTFIELDS, $envelope);
        curl_setopt($oCurl, CURLOPT_HTTPHEADER, $params);
        
        //log sended data
        //$this->logger->debug($envelope);
        
        //connect and send
        //$response = curl_exec($oCurl);
        
        //$soapinfo = curl_getinfo($oCurl);
        //$soaperrors = curl_error($oCurl);
        
        //log soap info
        //log soaperrors if exists
        //log soap response ever
        
        curl_close($oCurl);
        
        if (!empty($soapinfo)) {
            if ($soapinfo["http_code"] != '200') {
                //fail
                //so log error and other messages
                $msg = "Falha na comunicação.[".$soapinfo["http_code"]."";
                throw new \RuntimeException($msg);
            }
        }
        
        
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
            $this->tempdir = sys_get_temp_dir().DIRECTORY_SEPARATOR;
            $this->prifile = tempnam($this->tempdir, 'Pri').'.pem';
            $this->pubfile = tempnam($this->tempdir, 'Pub').'.pem';
            $this->certfile = tempnam($this->tempdir, 'Cert').'.pem';
            
            file_put_contents($this->prifile, $pkcs->priKey);
            file_put_contents($this->pubfile, $pkcs->pubKey);
            file_put_contents($this->certfile, $pkcs->certKey);
        }
    }

    private function removeTemporaryKeyFiles()
    {
        $files = glob($this->tempdir.'*.pem');
        foreach ($files as $file) {
            unlink($file);
        }
    }
}
