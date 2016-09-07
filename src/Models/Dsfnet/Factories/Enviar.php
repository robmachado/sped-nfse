<?php

namespace NFePHP\NFSe\Models\Dsfnet\Factories;

/**
 * Classe para a construção do XML relativo ao serviço de
 * Pedido de Envio de Lote de RPS dos webservices
 * conforme o modelo DSFNET
 *
 * @category  NFePHP
 * @package   NFePHP\NFSe\Models\Dsfnet\Factories\Enviar
 * @copyright NFePHP Copyright (c) 2016
 * @license   http://www.gnu.org/licenses/lgpl.txt LGPLv3+
 * @license   https://opensource.org/licenses/MIT MIT
 * @license   http://www.gnu.org/licenses/gpl.txt GPLv3+
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 * @link      http://github.com/nfephp-org/sped-nfse for the canonical source repository
 */

use NFePHP\NFSe\Models\Dsfnet\Factories\Factory;
use NFePHP\NFSe\Models\Dsfnet\Factories\Header;
use NFePHP\NFSe\Models\Dsfnet\RenderRPS;

class Enviar extends Factory
{
    public function render($rpss, $numeroLote)
    {
        $method = 'ReqEnvioLoteRPS';
        $content = $this->requestFirstPart($method);
        $content .= Header::render(
            $versao,
            $remetenteCNPJCPF,
            $transacao
        );
        $content .= "<Lote Id=\"lote:$numeroLote\">";
        foreach ($rpss as $rps) {
            $content .= RenderRPS::toXml($rps, $this->oCertificate->priKey);
        }
        $content .= "</Lote>";
        $content .= "</ns1:$method>";
        $body = $this->oCertificate->signXML($content, 'Lote', 'Id', $this->signAlgorithm);
        $body = $this->clear($body);
        //$this->validar($versao, $body, $method, '');
        return $body;
    }
}
