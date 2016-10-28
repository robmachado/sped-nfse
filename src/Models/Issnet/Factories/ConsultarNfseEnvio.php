<?php

namespace NFePHP\NFSe\Models\Issnet\Factories;

/**
 * Classe para a construção do XML relativo ao serviço de
 * Pedido de Consulta de NFSe em um período especifico para
 * os webservices conforme o modelo Issnet
 *
 * @category  NFePHP
 * @package   NFePHP\NFSe\Models\Prodam\Factories\ConsultarNFse
 * @copyright NFePHP Copyright (c) 2016
 * @license   http://www.gnu.org/licenses/lgpl.txt LGPLv3+
 * @license   https://opensource.org/licenses/MIT MIT
 * @license   http://www.gnu.org/licenses/gpl.txt GPLv3+
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 * @link      http://github.com/nfephp-org/sped-nfse for the canonical source repository
 */

use NFePHP\NFSe\Models\Issnet\Factories\Header;
use NFePHP\NFSe\Models\Issnet\Factories\Factory;

class ConsultarNfseEnvio extends Factory
{
    /**
     * Renderiza o pedido em seu respectivo xml e faz a validação com o XSD
     *
     * @param int    $remetenteTipoDoc
     * @param string $remetenteCNPJCPF
     * @param        $inscricaoMunicipal
     * @param date   $dtInicio
     * @param date   $dtFim
     *
     * @param int    $versao
     * @param string $numeroLote
     * @param string $cnpjTomador
     * @param string $cpfTomador
     * @param string $inscricaoMunicipalTomador
     *
     * @return string
     * @internal param int $versao
     * @internal param string $transacao
     * @internal param $cnpjContribuinte
     * @internal param string $cnpj
     * @internal param string $cpf
     * @internal param string $im
     * @internal param int $pagina
     */
    public function render(
        $remetenteTipoDoc,
        $remetenteCNPJCPF,
        $inscricaoMunicipal,
        $dtInicio,
        $dtFim,
        $versao = 1,
        $numeroLote = '',
        $cnpjTomador = '',
        $cpfTomador = '',
        $inscricaoMunicipalTomador = ''
    ) {
        $method = "ConsultarNfseEnvio";
        $content = $this->requestFirstPart($method);
        $content .= Header::render(
            $remetenteTipoDoc,
            $remetenteCNPJCPF,
            $inscricaoMunicipal,
            $dtInicio,
            $dtFim,
            $numeroLote,
            $cnpjTomador,
            $cpfTomador,
            $inscricaoMunicipalTomador
        );
        $content .= "</$method>";

        $body = $this->clear($content);
        $body = $this->signer($body, $method, '', [false,false,null,null]);
        $this->validar($versao, $body, 'Issnet', 'servico_consultar_nfse_envio', '');
        return $body;
    }
}
