<?php

namespace NFePHP\NFSe\Models\Prodam\Factories;

use InvalidArgumentException;
use NFePHP\Common\Dom\ValidXsd;
use NFePHP\Common\Certificate\Pkcs12;

class Factory
{
    protected $xmlnsxsd="http://www.w3.org/2001/XMLSchema";
    protected $xmlnsxsi="http://www.w3.org/2001/XMLSchema-instance";
    protected $xmlns= "http://www.prefeitura.sp.gov.br/nfe";
    protected $oCertificate;
    protected $pathSchemes = '../../schemes/Prodam/';
    
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
    
    /**
     * Executa a validação da mensagem XML com base no XSD
     * @param int $versao
     * @param string $body
     * @param string $method
     * @return boolean
     * @throws InvalidArgumentException
     */
    public function validar($versao, $body, $method = '')
    {
        $ver = str_pad($versao, 2, '0', STR_PAD_LEFT);
        $flag = false;
        $flag = ValidXsd::validar(
            $body,
            $this->pathSchemes."v$ver".DIRECTORY_SEPARATOR.$method."_v$ver.xsd"
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
