<?php

namespace NFePHP\NFSe\Models\Prodam\Factories;

/**
 * Classe para a construção do XML relativo ao serviço de
 * Pedido de Envio de NFSe dos webservices da
 * Cidade de São Paulo conforme o modelo Prodam
 *
 * @category  NFePHP
 * @package   NFePHP\NFSe\Models\Prodam\Factories\EnvioRPS
 * @copyright NFePHP Copyright (c) 2016
 * @license   http://www.gnu.org/licenses/lgpl.txt LGPLv3+
 * @license   https://opensource.org/licenses/MIT MIT
 * @license   http://www.gnu.org/licenses/gpl.txt GPLv3+
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 * @link      http://github.com/nfephp-org/sped-nfse for the canonical source repository
 */

use NFePHP\NFSe\Common\Signner;
use NFePHP\NFSe\Models\Prodam\Rps;
use NFePHP\NFSe\Models\Prodam\Factories\Factory;
use NFePHP\NFSe\Models\Prodam\RenderRPS;

class EnvioRPS extends Factory
{
    private $dtIni = '';
    private $dtFim = '';
    private $qtdRPS = 0;
    private $valorTotalServicos = 0;
    private $valorTotalDeducoes = 0;
    
    /**
     * Renderiza o pedido em seu respectivo xml e faz
     * a validação com o xsd
     * @param int $versao
     * @param int $remetenteTipoDoc
     * @param string $remetenteCNPJCPF
     * @param string $transacao
     * @param Rps | array $data
     * @return string
     */
    public function render(
        $versao,
        $remetenteTipoDoc,
        $remetenteCNPJCPF,
        $transacao = 'true',
        $data = ''
    ) {
        if ($data == '') {
            return '';
        }
        $xmlRPS = '';
        $method = "PedidoEnvioRPS";
        $content = $this->requestFirstPart($method);
        if (is_object($data)) {
            $xmlRPS .= $this->individual($content, $data);
        } elseif (is_array($data)) {
            if (count($data) == 1) {
                $xmlRPS .= $this->individual($content, $data);
            } else {
                $method = "PedidoEnvioLoteRPS";
                $content = $this->requestFirstPart($method);
                $xmlRPS .= $this->lote($content, $data);
            }
        }
        $content .= Header::render(
            $versao,
            $remetenteTipoDoc,
            $remetenteCNPJCPF,
            $transacao,
            null,
            null,
            null,
            $this->dtIni,
            $this->dtFim,
            null,
            $this->qtdRPS,
            $this->valorTotalServicos,
            $this->valorTotalDeducoes
        );
        $content .= $xmlRPS."</$method>";
        //$body = $this->oCertificate->signXML($content, $method, '', $algorithm = 'SHA1');
        $body = $this->clear($body);
        //$this->validar($versao, $body, $method);
        return $body;
    }
    
    /**
     * Processa quando temos apenas um RPS
     * @param string $content
     * @param Rps $data
     * @return string
     */
    private function individual(&$content, $data)
    {
        return RenderRPS::toXml($data, $algorithm, $this->certificate);
    }
    
    /**
     * Processa vários Rps dentro de um array
     * @param string $content
     * @param array $data
     * @return string
     */
    private function lote(&$content, $data)
    {
        $xmlRPS = '';
        $this->totalizeRps($data);
        foreach ($data as $rps) {
            $xmlRPS .= RenderRPS::toXml($data, $this->oCertificate->priKey);
        }
        return $xmlRPS;
    }
    
    /**
     * Totaliza os campos necessários para a montagem do cabeçalho
     * quando envio de Lote de RPS
     * @param array $rpss
     */
    private function totalizeRps($rpss)
    {
        foreach ($rpss as $rps) {
            $this->valorTotalServicos += $rps->valorServicosRPS;
            $this->valorTotalDeducoes += $rps->valorDeducoesRPS;
            $this->qtdRPS++;
            if ($this->dtIni == '') {
                $this->dtIni = $rps->dtEmiRPS;
            }
            if ($this->dtFim == '') {
                $this->dtFim = $rps->dtEmiRPS;
            }
            if ($rps->dtEmiRPS <= $this->dtIni) {
                $this->dtIni = $rps->dtEmiRPS;
            }
            if ($rps->dtEmiRPS >= $this->dtFim) {
                $this->dtFim = $rps->dtEmiRPS;
            }
        }
    }
}
