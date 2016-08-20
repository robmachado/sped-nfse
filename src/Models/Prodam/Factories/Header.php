<?php

namespace NFePHP\NFSe\Models\Prodam\Factories;

class Header
{
    public static function render(
        $versao,
        $remetenteTipoDoc,
        $remetenteCNPJCPF,
        $transacao = true,
        $im = '',
        $dtIni = '',
        $dtFim = '',
        $qtdRPS = 0,
        $valorTotalServicos = 0,
        $valorTotalDeducoes = 0
    ) {
        $content = "<Cabecalho Versao=\"$versao\">";
        $content .= "<CPFCNPJRemetente>";
        if ($remetenteTipoDoc == '2') {
            $content .= "<CNPJ>$remetenteCNPJCPF</CNPJ>";
        } else {
            $content .= "<CPF>$remetenteCNPJCPF</CPF>";
        }
        $content .= "</CPFCNPJRemetente>";
        $txtTrans = 'true';
        if ($transacao || $transacao == 'true') {
            $txtTrans = 'true';
        }
        $content .= "<transacao>$txtTrans</transacao>";
        if ($im != '') {
            $content .= "<Inscricao>$im</Inscricao>";
        }
        if ($dtIni != '') {
            $content .= "<dtInicio>$dtIni</dtInicio>";
        }
        if ($dtFim != '') {
            $content .= "<dtFim>$dtFim</dtFim>";
        }
        if ($qtdRPS != 0) {
            $content .= "<QtdRPS>$qtdRPS</QtdRPS>";
        }
        if ($valorTotalServicos != 0) {
            $content .= "<ValorTotalServicos>".number_format($valorTotalServicos, 2, '.', '')."</ValorTotalServicos>";
            $content .= "<ValorTotalDeducoes>".number_format($valorTotalDeducoes, 2, '.', '')."</ValorTotalDeducoes>";
        }
        $content .= "</Cabecalho>";
        return $content;
    }
}
