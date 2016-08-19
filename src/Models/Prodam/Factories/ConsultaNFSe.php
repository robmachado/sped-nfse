<?php

namespace NFePHP\NFSe\Models\Prodam\Factories;

class ConsultaNFSe
{
    public static function render(
        $versao,
        $cnpjRemetente,
        $imPrestador,
        $numeroNFSe,
        $serieRPS,
        $numeroRPS    
    ) {
        $content = "<PedidoConsultaNFe xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xmlns=\"http://www.prefeitura.sp.gov.br/nfe\">";
        $content .= "<Cabecalho Versao=\"$versao\">";
        $content .= "<CPFCNPJRemetente>";
        $content .= "<CNPJ>$cnpjRemetente</CNPJ>";
        $content .= "</CPFCNPJRemetente>";
        $content .= "</Cabecalho>";
        $content .= "<Detalhe>";
        $content .= "<ChaveNFe>";
        $content .= "<InscricaoPrestador>$imPrestador</InscricaoPrestador>";
        $content .= "<NumeroNFe>$numeroNFSe</NumeroNFe>";
        $content .= "</ChaveNFe>";
        $content .= "</Detalhe>";
        $content .= "<Detalhe>";
        $content .= "<ChaveRPS>";
        $content .= "<InscricaoPrestador>$imPrestador</InscricaoPrestador>";
        $content .= "<SerieRPS>$serieRPS</SerieRPS>";
        $content .= "<NumeroRPS>$numeroRPS</NumeroRPS>";
        $content .= "</ChaveRPS>";
        $content .= "</Detalhe>";
        $content .= "<PedidoConsultaNFe>";
        return $content;
    }
}
