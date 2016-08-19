<?php

namespace NFePHP\NFSe\Models\Prodam\Factories;

use InvalidArgumentException;
use NFePHP\NFSe\Models\Prodam\Factories\Header;

class ConsultaNFSe
{
    public static function render(
        $versao,
        $remetenteTipoDoc,
        $remetenteCNPJCPF,
        $transacao = true,
        $chavesNFSe = [],
        $chavesRPS = []
    ) {
        $content = "<PedidoConsultaNFe "
                . "xmlns=\"http://www.prefeitura.sp.gov.br/nfe\">";
        $content .= Header::render($versao, $remetenteTipoDoc, $remetenteCNPJCPF, $transacao);
        //minimo 1 e maximo de 50 objetos podem ser consultados
        $total = count($chavesNFSe) + count($chavesRPS);
        if ($total == 0 || $total > 50) {
            $msg = "Na consulta deve haver pelo menos uma chave e no máximo 50. Fornecido: $total";
            throw new InvalidArgumentException($msg);
        }
        //para cada chave montar um detalhe
        foreach ($chavesNFSe as $chave) {
            $content .= "<Detalhe>";
            $content .= "<ChaveNFe>";
            $content .= "<InscricaoPrestador>".$chave['prestadorIM']."</InscricaoPrestador>";
            $content .= "<NumeroNFe>".$chave['numero']."</NumeroNFe>";
            $content .= "</ChaveNFe>";
            $content .= "</Detalhe>";
        }
        foreach ($chavesRPS as $chave) {
            $content .= "<Detalhe>";
            $content .= "<ChaveRPS>";
            $content .= "<InscricaoPrestador>".$chave['prestadorIM']."</InscricaoPrestador>";
            $content .= "<SerieRPS>".$chave['serie']."</SerieRPS>";
            $content .= "<NumeroRPS>".$chave['numero']."</NumeroRPS>";
            $content .= "</ChaveRPS>";
            $content .= "</Detalhe>";
        }
        $content .= "</PedidoConsultaNFe>";
        return $content;
    }
}
