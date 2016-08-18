<?php

namespace NFePHP\NFSe\Models\Prodam\Factories;

use NFePHP\NFSe\Models\Prodam\Factories\Signner;

class CancelamentoNFSe
{
    public static function render(
        $versao,
        $cnpj,
        $im,
        $numero,
        $priKey
    ) {
        $signString = str_pad($im, 8, '0', STR_PAD_LEFT)
            . str_pad($numero, 12, '0', STR_PAD_LEFT);    
        $content = "<PedidoCancelamentoNFe xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\" xmlns=\"http://www.prefeitura.sp.gov.br/nfe\">";
        $content .= "<Cabecalho Versao=\"$versao\">";
        $content .= "<CPFCNPJRemetente>";
        $content .= "<CNPJ>$cnpj</CNPJ>";
        $content .= "</CPFCNPJRemetente>";
        $content .= "<transacao>true</transacao>";
        $content .= "</Cabecalho>";
        $content .= "<Detalhe xmlns=\"\">";
        $content .= "<ChaveNFe>";
        $content .= "<InscricaoPrestador>$im</InscricaoPrestador>";
        $content .= "<NumeroNFe>$numero</NumeroNFe>";
        $content .= "</ChaveNFe>";
        $content .= "<AssinaturaCancelamento>";
        $content .= Signner::sign($signString, $priKey);
        $content .= "</AssinaturaCancelamento>";
        $content .= "</Detalhe>";
        $content .= "</PedidoCancelamentoNFe>";
        return $content;
    }
}
