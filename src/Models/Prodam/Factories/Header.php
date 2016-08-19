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
        if ($remetenteTipoDoc == '1') {
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
            $content .= "<ValorTotalServicos>$valorTotalServicos</ValorTotalServicos>";
            $content .= "<ValorTotalDeducoes>$valorTotalDeducoes</ValorTotalDeducoes>";
        }
        $content .= "</Cabecalho>";
        return $content;
    }
}
