<?php

namespace NFePHP\NFSe\Models\Prodam\Factories;

class Header
{
    public static function render(
        $versao,
        $remetenteTipoDoc,
        $remetenteCNPJCPF,
        $transacao = '',
        $cnpj = '',
        $cpf = '',
        $im = '',
        $dtIni = '',
        $dtFim = '',
        $pagina = '',
        $qtdRPS = 0,
        $valorTotalServicos = 0,
        $valorTotalDeducoes = 0,
        $numeroLote = '',
        $prestadorIM = ''    
    ) {
        $content = "<Cabecalho xmlns=\"\" Versao=\"$versao\">";
        $content .= "<CPFCNPJRemetente>";
        if ($remetenteTipoDoc == '2') {
            $content .= "<CNPJ>$remetenteCNPJCPF</CNPJ>";
        } else {
            $content .= "<CPF>$remetenteCNPJCPF</CPF>";
        }
        $content .= "</CPFCNPJRemetente>";
        if ($transacao != '') {
            $content .= "<transacao>$transacao</transacao>";
        }
        if ($cnpj != '') {
            $content .= "<CPFCNPJ><CNPJ>$cnpj</CNPJ></CPFCNPJ>";
        } elseif ($cpf != '') {
            $content .= "<CPFCNPJ><CPF>$cpf</CPF></CPFCNPJ>";
        }
        if ($im != '') {
            $content .= "<Inscricao>$im</Inscricao>";
        }
        if ($dtIni != '') {
            $content .= "<dtInicio>$dtIni</dtInicio>";
        }
        if ($dtFim != '') {
            $content .= "<dtFim>$dtFim</dtFim>";
        }
        if ($pagina != '') {
            $content .= "<NumeroPagina>$pagina</NumeroPagina>";
        }
        if ($qtdRPS != 0) {
            $content .= "<QtdRPS>$qtdRPS</QtdRPS>";
        }
        if ($valorTotalServicos != 0) {
            $content .= "<ValorTotalServicos>".number_format($valorTotalServicos, 2, '.', '')."</ValorTotalServicos>";
            $content .= "<ValorTotalDeducoes>".number_format($valorTotalDeducoes, 2, '.', '')."</ValorTotalDeducoes>";
        }
        if ($numeroLote != '') {
            $content .= "<NumeroLote>$numeroLote</NumeroLote>";
        }
        if ($prestadorIM != '') {
            $content .= "<InscricaoPrestador>$prestadorIM</InscricaoPrestador>";
        }
        $content .= "</Cabecalho>";
        return $content;
    }
}
