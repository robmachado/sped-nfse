<?php

namespace NFePHP\NFSe\Models\Prodam;

/**
 * Classe para a comunicação com os webservices
 * conforme o modelo PRODAM
 *
 * @category  NFePHP
 * @package   NFePHP\NFSe\Prodam\Tools
 * @copyright NFePHP Copyright (c) 2016
 * @license   http://www.gnu.org/licenses/lgpl.txt LGPLv3+
 * @license   https://opensource.org/licenses/MIT MIT
 * @license   http://www.gnu.org/licenses/gpl.txt GPLv3+
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 * @link      http://github.com/nfephp-org/sped-nfse for the canonical source repository
 */

use NFePHP\NFSe\Models\Prodam\Rps;
use NFePHP\NFSe\Models\Prodam\Factories;
use NFePHP\NFSe\Common\Tools as ToolsBase;

class Tools extends ToolsBase
{
    /**
     * Envio de apenas um RPS
     * @param \NFePHP\NFSe\Models\Prodam\RPS $rps
     */
    public function envioRPS(RPS $rps)
    {
        $this->method = 'EnvioRPS';
        $fact = new Factories\EnvioRPS($this->certificate);
        $fact->setSignAlgorithm($this->signaturealgo);
        $xml = $fact->render(
            $this->versao,
            $this->remetenteTipoDoc,
            $this->remetenteCNPJCPF,
            null,
            $rps
        );
        return $this->buildRequest($xml);
    }
    
    /**
     * Envio de lote de RPS
     * @param array $rpss
     */
    public function envioLoteRPS($rpss = array())
    {
        $this->method = 'EnvioLoteRPS';
        $fact = new Factories\EnvioRPS($this->certificate);
        $fact->setSignAlgorithm($this->signaturealgo);
        $xml = $fact->render(
            $this->versao,
            $this->remetenteTipoDoc,
            $this->remetenteCNPJCPF,
            'true',
            $rpss
        );
        return $this->buildRequest($xml);
    }
    
    /**
     * Pedido de teste de envio de lote
     * @param array $rpss
     */
    public function testeEnvioLoteRPS($rpss = array())
    {
        $this->method = 'TesteEnvioLoteRPS';
        $fact = new Factories\EnvioRPS($this->certificate);
        $fact->setSignAlgorithm($this->signaturealgo);
        $xml = $fact->render(
            $this->versao,
            $this->remetenteTipoDoc,
            $this->remetenteCNPJCPF,
            'true',
            $rpss
        );
        return $this->buildRequest($xml);
    }
    
    /**
     * Consulta as NFSe e/ou RPS
     * @param array $chavesNFSe array(array('prestadorIM'=>'', 'numeroNFSe'=>''))
     * @param array $chavesRPS array(array('prestadorIM'=>'', 'serieRPS'=>'', 'numeroRPS'=>''))
     */
    public function consultaNFSe($chavesNFSe = [], $chavesRPS = [])
    {
        $this->method = 'ConsultaNFe';
        $fact = new Factories\ConsultaNFSe($this->certificate);
        $fact->setSignAlgorithm($this->signaturealgo);
        $xml = $fact->render(
            $this->versao,
            $this->remetenteTipoDoc,
            $this->remetenteCNPJCPF,
            null,
            $chavesNFSe,
            $chavesRPS
        );
        return $this->buildRequest($xml);
    }
    
    /**
     * Consulta as NFSe Recebidas pelo Tomador no periodo
     * @param string $cnpjTomador
     * @param string $cpfTomador
     * @param string $imTomador
     * @param string $dtInicio
     * @param string $dtFim
     * @param string $pagina
     */
    public function consultaNFSeRecebidas(
        $cnpjTomador,
        $cpfTomador,
        $imTomador,
        $dtInicio,
        $dtFim,
        $pagina
    ) {
        $this->method = 'ConsultaNFeRecebidas';
        $fact = new Factories\ConsultaNFSePeriodo($this->certificate);
        $fact->setSignAlgorithm($this->signaturealgo);
        $xml = $fact->render(
            $this->versao,
            $this->remetenteTipoDoc,
            $this->remetenteCNPJCPF,
            null,
            $cnpjTomador,
            $cpfTomador,
            $imTomador,
            $dtInicio,
            $dtFim,
            $pagina
        );
        return $this->buildRequest($xml);
    }
    
    /**
     * Consulta das NFSe emitidas pelo prestador no período
     * @param string $cnpjPrestador
     * @param string $cpfPrestador
     * @param string $imPrestador
     * @param string $dtInicio
     * @param string $dtFim
     * @param string $pagina
     */
    public function consultaNFSeEmitidas(
        $cnpjPrestador,
        $cpfPrestador,
        $imPrestador,
        $dtInicio,
        $dtFim,
        $pagina
    ) {
        $this->method = 'ConsultaNFeEmitidas';
        $fact = new Factories\ConsultaNFSePeriodo($this->certificate);
        $fact->setSignAlgorithm($this->signaturealgo);
        $xml = $fact->render(
            $this->versao,
            $this->remetenteTipoDoc,
            $this->remetenteCNPJCPF,
            null,
            $cnpjPrestador,
            $cpfPrestador,
            $imPrestador,
            $dtInicio,
            $dtFim,
            $pagina
        );
        return $this->buildRequest($xml);
    }
    
    /**
     * Consulta Lote
     * @param string $numeroLote
     */
    public function consultaLote($numeroLote = '')
    {
        $this->method = 'ConsultaLote';
        $fact = new Factories\ConsultaLote($this->certificate);
        $fact->setSignAlgorithm($this->signaturealgo);
        $xml = $fact->render(
            $this->versao,
            $this->remetenteTipoDoc,
            $this->remetenteCNPJCPF,
            null,
            $numeroLote
        );
        return $this->buildRequest($xml);
    }
    
    /**
     * Pedido de informações de Lote
     * @param string $prestadorIM
     * @param string $numeroLote
     */
    public function consultaInformacoesLote($prestadorIM = '', $numeroLote = '')
    {
        $this->method = 'ConsultaInformacoesLote';
        $fact = new Factories\ConsultaInformacoesLote($this->certificate);
        $fact->setSignAlgorithm($this->signaturealgo);
        $xml = $fact->render(
            $this->versao,
            $this->remetenteTipoDoc,
            $this->remetenteCNPJCPF,
            null,
            $prestadorIM,
            $numeroLote
        );
        return $this->buildRequest($xml);
    }
    
    /**
     * Solicita cancelamento da NFSe
     * @param string $prestadorIM
     * @param string $numeroNFSe
     */
    public function cancelamentoNFSe($prestadorIM = '', $numeroNFSe = '')
    {
        $this->method = 'CancelamentoNFe';
        $fact = new Factories\CancelamentoNFSe($this->certificate);
        $fact->setSignAlgorithm($this->signaturealgo);
        $xml = $fact->render(
            $this->versao,
            $this->remetenteTipoDoc,
            $this->remetenteCNPJCPF,
            'true',
            $prestadorIM,
            $numeroNFSe
        );
        return $this->buildRequest($xml);
    }
    
    /**
     * Consulta CNPJ de contribuinte do ISS
     * @param string $cnpjContribuinte
     * @return string
     */
    public function consultaCNPJ($cnpjContribuinte = '')
    {
        if ($cnpjContribuinte == '') {
            return '';
        }
        $this->method = 'ConsultaCNPJ';
        $fact = new Factories\ConsultaCNPJ($this->certificate);
        $fact->setSignAlgorithm($this->signaturealgo);
        $xml = $fact->render(
            $this->versao,
            $this->remetenteTipoDoc,
            $this->remetenteCNPJCPF,
            null,
            str_pad($cnpjContribuinte, 14, '0', STR_PAD_LEFT)
        );
        return $this->buildRequest($xml);
    }
    
    /**
     * Monta o request da mansagem SOAP
     * @param string $body
     * @param string $method
     * @return string
     */
    protected function buildRequest($body)
    {
        $tag = $this->method."Request";
        $request = "<$tag>";
        $request .= "<VersaoSchema>$this->versao</VersaoSchema>";
        $request .= "<MensagemXML>$body</MensagemXML>";
        $request .= "</$tag>";
        if ($this->withcdata === true) {
            $request = $this->replaceNodeWithCdata($request, 'MensagemXML', $body);
        }
        return $request;
    }
}
