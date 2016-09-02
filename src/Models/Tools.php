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
    protected $method = '';
    
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
}
