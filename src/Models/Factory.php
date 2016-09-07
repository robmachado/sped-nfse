<?php

namespace NFePHP\NFSe\Models;

/**
 * Classe base para a construção dos XMLs relativos ao serviços
 * dos webservices
 *
 * @category  NFePHP
 * @package   NFePHP\NFSe\Models\Factory
 * @copyright NFePHP Copyright (c) 2016
 * @license   http://www.gnu.org/licenses/lgpl.txt LGPLv3+
 * @license   https://opensource.org/licenses/MIT MIT
 * @license   http://www.gnu.org/licenses/gpl.txt GPLv3+
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 * @link      http://github.com/nfephp-org/sped-nfse for the canonical source repository
 */

use InvalidArgumentException;
use NFePHP\Common\Dom\ValidXsd;
use NFePHP\Common\Certificate\Pkcs12;

class Factory
{
    protected $oCertificate;
    protected $pathSchemes = '../../schemes/';
    protected $xml = '';
    protected $signAlgorithm = 'SHA1';
    
    /**
     * Construtor recebe a classe de certificados
     * @param Pkcs12 $pkcs
     */
    public function __construct(Pkcs12 $pkcs)
    {
        $this->oCertificate = $pkcs;
    }
    
    /**
     * Remove os marcadores de XML
     * @param string $body
     * @return string
     */
    public function clear($body)
    {
        $body = str_replace('<?xml version="1.0"?>', '', $body);
        $body = str_replace('<?xml version="1.0" encoding="utf-8"?>', '', $body);
        $body = str_replace('<?xml version="1.0"  encoding="UTF-8"?>', '', $body);
        return $body;
    }
    
    public function setSignAlgorithm($algo = 'SHA1')
    {
        $this->signAlgorithm = $algo;
    }
    
    /**
     * Executa a validação da mensagem XML com base no XSD
     * @param int $versao
     * @param string $body
     * @param string $method
     * @return boolean
     * @throws InvalidArgumentException
     */
    public function validar($versao, $body, $method = '', $suffix = 'v')
    {
        $ver = str_pad($versao, 2, '0', STR_PAD_LEFT);
        $flag = false;
        $schema = $this->pathSchemes."v$ver".DIRECTORY_SEPARATOR.$method.".xsd";
        if ($suffix) {
            $schema = $this->pathSchemes."v$ver".DIRECTORY_SEPARATOR.$method."_v$ver.xsd";
        }
        $flag = ValidXsd::validar(
            $body,
            $schema
        );
        if (!$flag) {
            $msg = "O XML falhou ao ser validado:\n";
            foreach (ValidXsd::$errors as $error) {
                $msg .= $error."\n";
            }
            throw new InvalidArgumentException($msg);
        }
        return $flag;
    }
}
