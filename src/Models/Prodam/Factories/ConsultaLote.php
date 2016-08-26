<?php

namespace NFePHP\NFSe\Models\Prodam\Factories;

use InvalidArgumentException;
use NFePHP\NFSe\Models\Prodam\Factories\Header;
use NFePHP\NFSe\Models\Prodam\Factories\Factory;

class ConsultaLote extends Factory
{
    public function render(
        $versao,
        $remetenteTipoDoc,
        $remetenteCNPJCPF,
        $transacao = '',
        $numeroLote = ''
    ) {
        $method = "PedidoConsultaLote";
        $content = "<$method "
            . "xmlns:xsd=\""
            . $this->xmlnsxsd
            . "\" xmlns=\""
            . $this->xmlns
            . "\" xmlns:xsi=\""
            . $this->xmlnsxsi
            . "\">";
        $content .= Header::render(
            $versao,
            $remetenteTipoDoc,
            $remetenteCNPJCPF,
            $transacao,
            '',
            '',
            '',
            '',
            '',
            '',
            0,
            0,
            0,
            $numeroLote
        );
        $content .= "</$method>";
        $body = $this->oCertificate->signXML($content, $method, '', $algorithm = 'SHA1');
        $body = $this->clear($body);
        $this->validar($versao, $body, $method);
        return $body;
    }
}
