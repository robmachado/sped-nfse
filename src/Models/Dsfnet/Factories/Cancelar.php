<?php

namespace NFePHP\NFSe\Models\Dsfnet\Factories;

/**
 * Classe para a construção do XML relativo ao serviço de
 * Pedido de Cancelamento de NFSe dos webservices da
 * conforme o modelo DSFNET
 *
 * NOTA: Este processo está limitado a apneas uma NFSe por vez!!
 *
 * @category  NFePHP
 * @package   NFePHP\NFSe\Models\Dsfnet\Factories\Cancelar
 * @copyright NFePHP Copyright (c) 2016
 * @license   http://www.gnu.org/licenses/lgpl.txt LGPLv3+
 * @license   https://opensource.org/licenses/MIT MIT
 * @license   http://www.gnu.org/licenses/gpl.txt GPLv3+
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 * @link      http://github.com/nfephp-org/sped-nfse for the canonical source repository
 */

use NFePHP\NFSe\Models\Dsfnet\Factories\Factory;
use NFePHP\NFSe\Models\Dsfnet\Factories\Header;

class Cancelar extends Factory
{
    public function render(
        $versao,
        $remetenteCNPJCPF,
        $transacao = '',
        $codcidade = '',
        $prestadorIM = '',
        $tokenEnvio = '',
        $lote = '',
        $numero = '',
        $codigoverificacao = '',
        $motivocancelamento = ''
    ) {
        $method = "ReqCancelamentoNFSe";
        $content = "<ns1:$method "
            . "xmlns:ns1=\"http://localhost:8080/WsNFe2/lote\" "
            . "xmlns:tipos=\"http://localhost:8080/WsNFe2/tp\" "
            . "xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" "
            . "xsi:schemaLocation=\"http://localhost:8080/WsNFe2/lote "
            . "http://localhost:8080/WsNFe2/xsd/$method.xsd\""
            . ">";
        $content .= Header::render(
            $versao,
            $remetenteCNPJCPF,
            $transacao,
            $codcidade,
            '',
            $tokenEnvio
        );
        $content .= "<Lote Id=\"lote:$lote\">";
        $content .= "<Nota Id=\"nota:$numero\">";
        $content .= "<InscricaoMunicipalPrestador>$prestadorIM</InscricaoMunicipalPrestador>";
        $content .= "<NumeroNota>$numero</NumeroNota>";
        $content .= "<CodigoVerificacao>$codigoverificacao</CodigoVerificacao>";
        $content .= "<MotivoCancelamento>$motivocancelamento</MotivoCancelamento>";
        $content .= "</Nota>";
        $content .= "</Lote>";
        $content .= "</ns1:$method>";
        $body = $this->oCertificate->signXML($content, 'Lote', 'Id', $this->signAlgorithm);
        $body = $this->clear($body);
        $this->validar($versao, $body, $method, '');
        return $body;
    }
}
