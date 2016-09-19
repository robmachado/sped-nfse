<?php

namespace NFePHP\NFSe\Common\Soap;

use NFePHP\NFSe\Common\Soap\SoapBase;
use NFePHP\NFSe\Common\Soap\SoapInterface;
use RuntimeException;

class SoapCurl extends SoapBase implements SoapInterface
{
  
    protected $responseHead = '';
    protected $responseBody = '';
    
    /**
     * sendSoap
     * @param string $url
     * @param string $operation
     * @param string $action
     * @param int $soapver
     * @param array $parameters
     * @param array $namespaces
     * @return string
     */
    public function soapSend(
        $url,
        $operation = '',
        $action = '',
        $soapver = SOAP_1_2,
        $parameters = [],
        $namespaces = []
    ) {
        $soapinfo = array();
        $soaperror = '';
        $response = '';
        
        $envelope = $this->mkEnvSoap1($operation, $parameters, $namespaces);
        if ($soapver == SOAP_1_2) {
            $envelope = $this->mkEnvSoap2($operation, $parameters, $namespaces);
        }
        
        $msgSize = strlen($envelope);
        /* TIRAR ISSO DEPOIS => EXISTE APENAS PARA TESTES */
        file_put_contents('/tmp/envelope.xml', $envelope);
        
        $curlparams = [
            "Content-Type: application/soap+xml;"
                . "charset=utf-8;",
            "Content-length: $msgSize"
        ];
        if (!empty($action)) {
            $curlparams[0] .= "action=$action";
        }
        $oCurl = curl_init();
        $this->curlSetProxy($oCurl);
        curl_setopt($oCurl, CURLOPT_URL, $url);
        curl_setopt($oCurl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        curl_setopt($oCurl, CURLOPT_CONNECTTIMEOUT, $this->soaptimeout);
        curl_setopt($oCurl, CURLOPT_VERBOSE, 1);
        curl_setopt($oCurl, CURLOPT_HEADER, 1);
        curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($oCurl, CURLOPT_SSLVERSION, $this->soapprotocol);
        curl_setopt($oCurl, CURLOPT_SSLCERT, $this->certfile);
        curl_setopt($oCurl, CURLOPT_SSLKEY, $this->prifile);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
        //pode ser vazio quando quizer pegar o WSDL apenas
        if (! empty($envelope)) {
            curl_setopt($oCurl, CURLOPT_POST, 1);
            curl_setopt($oCurl, CURLOPT_POSTFIELDS, $envelope);
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
    
    protected function mkEnvSoap1($operation, $parameters, $namespaces)
    {
        if (empty($operation)) {
            return '';
        }
        $request = $this->mkRequest($operation, $parameters);
        $envelope = "<soapenv:Envelope";
        foreach($namespaces as $key => $value) {
            $envelope .= " $key=\"$value\"";
        }
        $envelope .= "><soapenv:Body>$request</soapenv:Body>"
                . "</soapenv:Envelope>";
        return $envelope;
    }
    
    protected function mkEnvSoap2($operation, $parameters, $namespaces)
    {
        if (empty($operation)) {
            return '';
        }
        $request = $this->mkRequest($operation, $parameters);
        $envelope = "<soap:Envelope";
        foreach($namespaces as $key => $value) {
            $envelope .= " $key=\"$value\"";
        }    
        $envelope .= "><soap:Body>$request</soap:Body>"
            . "</soap:Envelope>";
        return $envelope;
    }
    
    protected function mkRequest($operation, $parameters)
    {
        $tag = $operation . "Request";
        $request = "<$tag>";
        foreach ($parameters as $key => $value) {
            if ($key == 'MensagemXML') {
                $value = htmlentities($value, ENT_NOQUOTES);
            }
            $request .= "<$key>$value</$key>";
        }
        $request .= "</$tag>";
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
            curl_setopt($oCurl, CURLOPT_PROXY, $this->proxyIP.':'.$this->proxyPort);
            if ($this->proxyUser != '') {
                curl_setopt($oCurl, CURLOPT_PROXYUSERPWD, $this->proxyUser.':'.$this->proxyPass);
                curl_setopt($oCurl, CURLOPT_PROXYAUTH, CURLAUTH_BASIC);
            }
        }
    }
}
