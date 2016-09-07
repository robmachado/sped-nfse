<?php

namespace NFePHP\NFSe\Models;

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

use NFePHP\Common\Base\BaseTools;
use NFePHP\Common\Files;
use NFePHP\Common\Dom\Dom;

class Tools extends BaseTools
{
    
    protected $versao = '1';
    protected $remetenteTipoDoc = '2';
    protected $remetenteCNPJCPF = '';
    protected $remetenteRazao = '';
    protected $method = '';
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
    protected $soapversion = 1;
    /**
     * SIAFI County Cod
     * @var int
     */
    protected $codcidade = 0;
    protected $withcdata = false;
    protected $signaturealgo= 'SHA1';
    
    /**
     * Namespace for XMLSchema
     * @var string
     */
    protected $xmlnsxsd="http://www.w3.org/2001/XMLSchema";
    /**
     * Namespace for XMLSchema-instance
     * @var string
     */
    protected $xmlnsxsi="http://www.w3.org/2001/XMLSchema-instance";

    public function __construct($config)
    {
        parent::__construct($config);
        $this->versao = $this->aConfig['versao'];
        $this->remetenteCNPJCPF = $this->aConfig['cnpj'];
        $this->remetenteRazao = $this->aConfig['razaosocial'];
        if ($this->aConfig['cpf'] != '') {
            $this->remetenteTipoDoc = '1';
            $this->remetenteCNPJCPF = $this->aConfig['cpf'];
        }
    }
    
    protected function replaceNodeWithCdata($xml, $nodename, $body)
    {
        $dom = new Dom('1.0', 'utf-8');
        $dom->loadXMLString($xml);
        $root = $dom->documentElement;
        $oldnode = $root->getElementsByTagName($nodename)->item(0);
        $tag = $oldnode->tagName;
        $root->removeChild($oldnode);
        $newnode = $dom->createElement($tag);
        $cdatanode = $dom->createCDATASection($body);
        $newnode->appendChild($cdatanode);
        $root->appendChild($newnode);
        return $dom->saveXML();
    }
    
    /**
     * Envia mensagem por SOAP
     * @param string $body
     * @param string $method
     */
    public function envia($request)
    {
       
        
        header("Content-type: text/xml");
        echo $request;
        die;
        
        $url = $this->url[$this->aConfig['tpAmb']];
        try {
            $this->setSSLProtocol('TLSv1');
            //$response = $this->oSoap->send($url, '', '', $body, $this->method);
        } catch (Exception $ex) {
            echo $ex;
        }
    }
}
