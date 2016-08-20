<?php

namespace NFePHP\NFSe\Models\Prodam\Factories;

use NFePHP\NFSe\Models\Prodam\Factories\Header;

class ConsultaCNPJ
{
    public static function render(
        $versao,
        $remetenteTipoDoc,
        $remetenteCNPJCPF,
        $transacao = true,
        $cnpjContribuinte = ''
    ) {
        $content = "<PedidoConsultaCNPJ "
                 . "xmlns=\"http://www.prefeitura.sp.gov.br/nfe\">";
        $content .= Header::render($versao, $remetenteTipoDoc, $remetenteCNPJCPF, $transacao);
        $content .= "<CNPJContribuinte>";
        $content .= "<CNPJ>$cnpjContribuinte</CNPJ>";
        $content .= "</CNPJContribuinte>";
        $content .= "</PedidoConsultaCNPJ>";
        return $content;
    }
}
