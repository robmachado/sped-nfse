<?php

namespace NFePHP\NFSe\Models\Prodam;


class ConsultaPeriodo
{
    public static function render(
        $versao,
        $remetenteTipoDoc,
        $remetenteCNPJCPF,
        $im,
        $dtIni,
        $dtFim,
        $pagina
    ) {
        $content = "<PedidoConsultaNFePeriodo xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xmlns=\"http://www.prefeitura.sp.gov.br/nfe\">";
        $content .= "<Cabecalho Versao=\"$versao\">";
        $content .= "<CPFCNPJRemetente>";
        $content .= "<CPF>$remetenteCNPJCPF</CPF>";
        $content .= "</CPFCNPJRemetente>";
        $content .= "<CPFCNPJ>";
        $content .= "<CPF>???</CPF>";
        $content .= "</CPFCNPJ>";
        $content .= "<Inscricao>$im</Inscricao>";
        $content .= "<dtInicio>$dtIni</dtInicio>";
        $content .= "<dtFim>$dtFim</dtFim>";
        $content .= "<NumeroPagina>$pagina</NumeroPagina>";
        $content .= "</Cabecalho>";
        return $content;
    }
}
