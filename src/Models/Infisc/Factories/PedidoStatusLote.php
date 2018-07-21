<?php

namespace NFePHP\NFSe\Models\Infisc\Factories;

use NFePHP\NFSe\Models\Infisc\Factories\Header;
use NFePHP\NFSe\Models\Infisc\Factories\Factory;

class PedidoStatusLote extends Factory
{
    public function render(
        $versao,
        $CNPJ,
        $protocolo
    ) {
        $method = "pedidoStatusLote";
        $xsd = 'SchemaCaxias-NFSe';
        $content = $this->requestFirstPart($method, $xsd);
        $content .= Header::render($CNPJ);
        $content .= "<cLote>$protocolo</cLote>";
        $content .= "</$method>";
        
        $body = Signer::sign(
            $this->certificate,
            $content,
            'pedidoStatusLote',
            '',
            $this->algorithm,
            [false,false,null,null]            
        );  
        $this->validar($versao, $body, 'Infisc', $xsd, '');
        
        //$this->validar($versao, $body, 'Infisc', $xsd, '');
        return $body;
    }
}
