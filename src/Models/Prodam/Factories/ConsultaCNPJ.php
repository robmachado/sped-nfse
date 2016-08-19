<?php

namespace NFePHP\NFSe\Models\Prodam\Factories;

class ConsultaCNPJ
{
    public static function render(
        $versao,
        $cnpjRemetente,
        $cnpjContribuinte   
    ) {
        $content = "<PedidoConsultaCNPJ xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xmlns=\"http://www.prefeitura.sp.gov.br/nfe\">";
        $content .= "<Cabecalho Versao=\"$versao\">";
        $content .= "<CPFCNPJRemetente>";
        $content .= "<CNPJ>$cnpjRemetente</CNPJ>";
        $content .= "</CPFCNPJRemetente>";
        $content .= "</Cabecalho>";
        $content .= "<CNPJContribuinte>";
        $content .= "<CNPJ>$cnpjContribuinte</CNPJ>";
        $content .= "</CNPJContribuinte>";
        $content .= "<PedidoConsultaCNPJ>";
        return $content;
    }
}
