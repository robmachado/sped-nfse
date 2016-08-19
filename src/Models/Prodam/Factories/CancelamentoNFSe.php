<?php

namespace NFePHP\NFSe\Models\Prodam\Factories;

use NFePHP\NFSe\Models\Prodam\Factories\Signner;

class CancelamentoNFSe
{
    public static function render(
        $versao,
        $remetenteTipoDoc,
        $remetenteCNPJCPF,
        $transacao = true,
        $prestadorIM = '',
        $numeroNFSe = '',
        $priKey = ''
    ) {
        $signString = str_pad($prestadorIM, 8, '0', STR_PAD_LEFT)
            . str_pad($numeroNFSe, 12, '0', STR_PAD_LEFT);
        $content = "<PedidoCancelamentoNFe "
            . "xmlns=\"http://www.prefeitura.sp.gov.br/nfe\">";
        $content .= Header::render($versao, $remetenteTipoDoc, $remetenteCNPJCPF, $transacao);
        $content .= "<Detalhe>";
        $content .= "<ChaveNFe>";
        $content .= "<InscricaoPrestador>$prestadorIM</InscricaoPrestador>";
        $content .= "<NumeroNFe>$numeroNFSe</NumeroNFe>";
        $content .= "</ChaveNFe>";
        $content .= "<AssinaturaCancelamento>";
        $content .= Signner::sign($signString, $priKey);
        $content .= "</AssinaturaCancelamento>";
        $content .= "</Detalhe>";
        $content .= "</PedidoCancelamentoNFe>";
        return $content;
    }
}
