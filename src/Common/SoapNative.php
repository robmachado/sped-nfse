<?php

namespace NFePHP\NFSe\Common;

use SoapClient;
use NFePHP\NFSe\Common\SoapClient as LocalClient;
use NFePHP\Common\Certificate;
use Psr\Log\LoggerInterface;
use RuntimeException;
use SimpleXMLElement;

class SoapNative extends LocalClient
{
    protected $connection;
    
    public function soapSend(
        $url,
        $operation = '',
        $action = '',
        $soapver = SOAP_1_2,
        $parameters = [],
        $namespace = ''
    ) {
        $this->prepare($url, $soapver);
        try {
            $result = $this->connection->$operation($parameters);
            //por em log
            $lastH = $this->connection->__getLastRequestHeaders();
            $lastM = $this->connection->__getLastRequest();
            /* TIRAR ISSO DEPOIS => EXISTE APENAS PARA TESTES */
            file_put_contents('/tmp/natheaderhtml.txt', $lastH);
            file_put_contents('/tmp/natenvelope.xml', $lastM);
        } catch (SoapFault $e) {
            //por em log
            throw new RuntimeException($e->getMessage());
        } catch (Exception $e) {
            throw new $e;
        }
        //por em log
        return $result->RetornoXML;
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
    
    private function setproxy(&$params)
    {
        if ($this->proxyIP != '') {
            $pproxy1 = [
                'proxy_host' => $this->proxyIP,
                'proxy_port' => $this->proxyPORT
            ];
            array_push($params, $pproxy1);
        }
        
        if ($this->proxyPASS != '') {
            $pproxy2 = [
                'proxy_login' => $this->proxyUSER,
                'proxy_password' => $this->proxyPASS
            ];
            array_push($params, $pproxy2);
        }
    }
}
