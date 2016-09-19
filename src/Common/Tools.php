<?php

namespace NFePHP\NFSe\Common;

/**
 * Classe para base para a comunicação com os webservices
 *
 * @category  NFePHP
 * @package   NFePHP\NFSe\Models\Tools
 * @copyright NFePHP Copyright (c) 2016
 * @license   http://www.gnu.org/licenses/lgpl.txt LGPLv3+
 * @license   https://opensource.org/licenses/MIT MIT
 * @license   http://www.gnu.org/licenses/gpl.txt GPLv3+
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 * @link      http://github.com/nfephp-org/sped-nfse for the canonical source repository
 */

use NFePHP\Common\Certificate;
use League\Flysystem;
use DOMDocument;
use stdClass;
use NFePHP\NFSe\Common\Soap\SoapInterface;

abstract class Tools
{
    protected $config;
    protected $certificate;
    protected $soap;
    protected $method = '';

    protected $versao;
    protected $remetenteTipoDoc;
    protected $remetenteCNPJCPF;
            
    /**
     * Webservices URL
     * @var array
     */
    protected $url = [
        1 => '',
        2 => ''
    ];
    /**
     * County Namespace
     * @var string
     */
    protected $xmlns = '';
    /**
     * Soap Version
     * @var int
     */
    protected $soapversion = SOAP_1_2;
    /**
     * SIAFI County Cod
     * @var int
     */
    protected $codcidade = 0;
    /**
     * Indicates when use CDATA string on message
     * @var boolean
     */
    protected $withcdata = false;
    /**
     * Encription signature algorithm
     * @var string
     */
    protected $algorithm;
    /**
     * namespaces for soap envelope
     * @var array
     */    
    protected $namespaces = [];

    /**
     * Constructor
     * @param string $config
     */
    public function __construct(stdClass $config, Certificate $certificate)
    {
        $this->config = $config;
        $this->versao = $config->versao;
        $this->remetenteCNPJCPF = $config->cpf;
        $this->remetenteTipoDoc = 1;
        if ($config->cnpj != '') {
            $this->remetenteCNPJCPF = $config->cnpj;
            $this->remetenteTipoDoc = 2;
        }
        $this->certificate = $certificate;
    }
    
    public function setUseCdata($flag)
    {
        $this->withcdata = $flag;
    }
    
    public function setSoapClass(SoapInterface $soap)
    {
        $this->soap = $soap;
        $this->soap->setCertificate($this->certificate);
    }
    
    protected function replaceNodeWithCdata($xml, $nodename, $body, $param = [])
    {
        $dom = new DOMDocument('1.0', 'utf-8');
        $dom->loadXML($xml);
        $root = $dom->documentElement;
        if (!empty($param)) {
            $attrib = $dom->createAttribute($param[0]);
            $attrib->value = $param[1];
            $root->appendChild($attrib);
        }
        $oldnode = $root->getElementsByTagName($nodename)->item(0);
        $tag = $oldnode->tagName;
        $root->removeChild($oldnode);
        $newnode = $dom->createElement($tag);
        //$attrib = $dom->createAttribute("xsi:type");
        //$attrib->value = 'xsd:string';
        //$newnode->appendChild($attrib);
        $cdatanode = $dom->createCDATASection(htmlentities($body, ENT_NOQUOTES, 'UTF-8'));
        $newnode->appendChild($cdatanode);
        $root->appendChild($newnode);
        $xml = str_replace('<?xml version="1.0"?>', '', $dom->saveXML());
        return $xml;
    }
    
    abstract protected function sendRequest($url, $message);
}
