<?php

namespace NFePHP\NFSe\Common;

/**
 * Basic Abstract Class, for all derived classes from NFSe models
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
use Psr\Log\LoggerInterface;
use DOMDocument;
use stdClass;

abstract class Tools
{
    /**
     * configuration values
     * @var \stdClass
     */
    protected $config;
    /**
     * Certificate::class
     * @var \NFePHP\Common\Certificate
     */
    protected $certificate;
    /**
     * Soap::class
     * @var \NFePHP\Common\Soap\SoapInterface
     */
    protected $soap;
    /**
     * Method from webservice
     * @var string
     */
    protected $method = '';
    /**
     * Logger::class
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;
    /**
     * Version of XSD
     * @var int
     */
    protected $versao;
    /**
     * Type of document
     * @var int
     */
    protected $remetenteTipoDoc;
    /**
     * Document
     * @var string
     */
    protected $remetenteCNPJCPF;
    /**
     * Company Name
     * @var string
     */
    protected $remetenteRazao;
    /**
     * Webservices URL's
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
     * @var int
     */
    protected $algorithm = OPENSSL_ALGO_SHA1;
    /**
     * Namespaces for soap envelope
     * @var array
     */
    protected $namespaces = [];
    
    /**
     * Constructor
     * @param stdClass $config
     * @param \NFePHP\Common\Certificate $certificate
     */
    public function __construct(stdClass $config, Certificate $certificate)
    {
        $this->config = $config;
        $this->versao = $config->versao;
        $this->remetenteCNPJCPF = $config->cpf;
        $this->remetenteRazao = $config->razaosocial;
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
     * @param \NFePHP\Common\Soap\SoapInterface $soap
     */
    public function setSoapClass(SoapInterface $soap)
    {
        $this->soap = $soap;
        $this->soap->loadCertificate($this->certificate);
    }
    
    /**
     * Load the cohsen logger class
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function setLoggerClass(LoggerInterface $logger)
    {
        $this->logger = $logger;
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
     * @return string
     */
    protected function stringTransform($message)
    {
        return EntitiesCharacters::unconvert(htmlentities($message, ENT_NOQUOTES));
    }
}
