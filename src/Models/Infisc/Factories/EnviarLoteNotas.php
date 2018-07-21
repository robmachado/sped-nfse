<?php

namespace NFePHP\NFSe\Models\Infisc\Factories;

use NFePHP\NFSe\Models\Infisc\Factories\Header;
use NFePHP\NFSe\Models\Infisc\Factories\Factory;
use NFePHP\NFSe\Models\Infisc\RenderRPS;

class EnviarLoteNotas extends Factory
{
    public function render(
        $versao,        
        $CNPJ,
        $dhTrans,        
        $rpss
    ) {        
        $timezone = new \DateTimeZone('America/Cuiaba');
        $this->timezone = $timezone;     
        $xsd = 'SchemaCaxias-NFSe';
        $qtdRps = count($rpss);
        $content = "<envioLote versao=\"1.0\">";
            $content .= "<CNPJ>$CNPJ</CNPJ>";
            $content .= "<dhTrans>$dhTrans</dhTrans>";
        foreach ($rpss as $rps) {
            $content .= RenderRPS::toXml($rps, $this->timezone, $this->algorithm);
        }
        $content .= "</envioLote>";        
        $body = Signer::sign(
            $this->certificate,
            $content,
            'envioLote',
            '',
            $this->algorithm,
            [false,false,null,null]            
        );
        $body = $this->clear($body);    
        //error_log(print_r($body, TRUE) . PHP_EOL, 3, '/var/www/tests/sped-nfse/post.xml');
        $this->validar($versao, $body, 'Infisc', $xsd, '');
        return $body;
    }
}
