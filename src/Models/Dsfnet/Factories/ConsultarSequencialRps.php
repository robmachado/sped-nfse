<?php

namespace NFePHP\NFSe\Models\Dsfnet\Factories;

/**
 * Classe para a construção do XML relativo ao serviço de
 * Pedido de Consulta da Sequencia de RPS dos webservices
 * conforme o modelo DSFNET
 *
 * @category  NFePHP
 * @package   NFePHP\NFSe\Models\Dsfnet\Factories\ConsultarSequencialRps
 * @copyright NFePHP Copyright (c) 2016
 * @license   http://www.gnu.org/licenses/lgpl.txt LGPLv3+
 * @license   https://opensource.org/licenses/MIT MIT
 * @license   http://www.gnu.org/licenses/gpl.txt GPLv3+
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 * @link      http://github.com/nfephp-org/sped-nfse for the canonical source repository
 */

use NFePHP\NFSe\Models\Dsfnet\Factories\Factory;
use NFePHP\NFSe\Models\Dsfnet\Factories\Header;

class ConsultarSequencialRps extends Factory
{
    public function render(
        $versao,
        $remetenteCNPJCPF,
        $codcid,
        $prestadorIM,
        $serieRPS
    ) {
        $method = "ConsultaSeqRps";
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
            '', //$transacao
            '', //$codcidade
            $codcid,
            '', //$token
            $prestadorIM,
            $serieRPS
        );
        $content .= "</ns1:$method>";
        $this->validar($versao, $content, $method, '');
        return $content;
    }
}
