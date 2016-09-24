<?php

namespace NFePHP\NFSe\Common;

/**
 * Classe para base para a comunicação com os webservices
 *
 * @category  NFePHP
 * @package   NFePHP\NFSe\Common\Tools
 * @copyright NFePHP Copyright (c) 2016
 * @license   http://www.gnu.org/licenses/lgpl.txt LGPLv3+
 * @license   https://opensource.org/licenses/MIT MIT
 * @license   http://www.gnu.org/licenses/gpl.txt GPLv3+
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 * @link      http://github.com/nfephp-org/sped-nfse for the canonical source repository
 */

use NFePHP\Common\Certificate;
use NFePHP\Common\Soap\SoapInterface;
use NFePHP\NFSe\Common\EntitiesCharacters;
use League\Flysystem;
use DOMDocument;
use stdClass;

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
    
    /**
     * Set to true if CData is used in XML message
     * @param boolean $flag
     */
    public function setUseCdata($flag)
    {
        $this->withcdata = $flag;
    }
    
    /**
     * Load the chosen soap class
     * @param SoapInterface $soap
     */
    public function setSoapClass(SoapInterface $soap)
    {
        $this->soap = $soap;
        $this->soap->loadCertificate($this->certificate);
    }
    
    /**
     * Send request to webservice
     * @param string $message
     * @return string
     */
    abstract protected function sendRequest($url, $message);
    
    /**
     * Convert string xml message to cdata string
     * @param string $message
     * @param boolean $withcdata
     * @return string
     */
    protected function stringTransform($message)
    {
        return EntitiesCharacters::unconvert(htmlentities($message, ENT_NOQUOTES));
    }
}
