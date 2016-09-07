<?php

namespace NFePHP\NFSe\Models\Dsfnet;

/**
 * Classe para a comunicação com os webservices da
 * conforme o modelo DSFNET
 *
 * @category  NFePHP
 * @package   NFePHP\NFSe\Models\Dsfnet\Tools
 * @copyright NFePHP Copyright (c) 2016
 * @license   http://www.gnu.org/licenses/lgpl.txt LGPLv3+
 * @license   https://opensource.org/licenses/MIT MIT
 * @license   http://www.gnu.org/licenses/gpl.txt GPLv3+
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 * @link      http://github.com/nfephp-org/sped-nfse for the canonical source repository
 */

use NFePHP\NFSe\Models\Dsfnet\Rps;
use NFePHP\NFSe\Models\Dsfnet\Factories;
use NFePHP\NFSe\Models\Tools as ToolsBase;

class Tools extends ToolsBase
{
    /**
     * Construtor da classe Tools
     * @param string $config
     */
    public function __construct($config)
    {
        parent::__construct($config);
    }
    
    /**
     * Solicita cancelamento da NFSe
     * @param string $prestadorIM
     * @param string $numeroNFSe
     */
    public function cancelar($prestadorIM, $numeroLote, $numeroNota, $codigoVerificacao, $motivo, $tokenEnvio = null)
    {
        $this->method = 'cancelar';
        $fact = new Factories\Cancelar($this->oCertificate);
        $fact->setSignAlgorithm($this->signaturealgo);
        $xml = $fact->render(
            $this->versao,
            $this->remetenteCNPJCPF,
            'true',
            $this->codcidade,
            $prestadorIM,
            $tokenEnvio,
            $numeroLote,
            $numeroNota,
            $codigoVerificacao,
            $motivo
        );
        return $this->buildRequest($xml);
    }
    
    /**
     * Consulta Lote
     * @param string $numeroLote
     * @return string
     */
    public function consultarLote($numeroLote)
    {
        $this->method = 'consultarLote';
        $fact = new Factories\ConsultarLote($this->oCertificate);
        $fact->setSignAlgorithm($this->signaturealgo);
        $xml = $fact->render(
            $this->versao,
            $this->remetenteCNPJCPF,
            $this->codcidade,
            $numeroLote
        );
        return $this->buildRequest($xml);
    }
    
    /**
     * Consulta Lote de NFSe e/ou RPS
     * @param type $prestadorIM
     * @param type $nfse [0 => ['numero', 'codigoVerificacao']]
     * @param type $rps  [0 => ['numero', 'serie']]
     */
    public function consultarNFSeRps($prestadorIM, $lote, $nfse = [], $rps = [])
    {
        $this->method = 'consultarNFSeRps';
        $fact = new Factories\ConsultarNFSeRps($this->oCertificate);
        $fact->setSignAlgorithm($this->signaturealgo);
        $xml = $fact->render(
            $this->versao,
            $this->remetenteCNPJCPF,
            $this->codcidade,
            'true', //transacao
            $prestadorIM,
            $lote,
            $nfse,
            $rps
        );
        return $this->buildRequest($xml);
    }
    
    
    public function consultarNota($prestadorIM, $dtInicio, $dtFim, $notaInicial)
    {
        $this->method = 'consultarNota';
        $fact = new Factories\ConsultarNota($this->oCertificate);
        $fact->setSignAlgorithm($this->signaturealgo);
        $xml = $fact->render(
            $this->versao,
            $this->remetenteCNPJCPF,
            $this->codcidade,
            $prestadorIM,
            $dtInicio,
            $dtFim,
            $notaInicial
        );
        return $this->buildRequest($xml);
    }
    
    public function consultarSequencialRps($prestadorIM, $serieRPS)
    {
        $this->method = 'consultarSequencialRps';
        $fact = new Factories\ConsultarSequencialRps($this->oCertificate);
        $fact->setSignAlgorithm($this->signaturealgo);
        $xml = $fact->render(
            $this->versao,
            $this->remetenteCNPJCPF,
            $this->codcidade,
            $prestadorIM,
            $serieRPS
        );
        return $this->buildRequest($xml);
    }
    
    public function enviar($rpss, $numeroLote)
    {
        $this->method = 'enviar';
        /*
         * <lot:enviar>
         <mensagemXml>?</mensagemXml>
      </lot:enviar>
         */
    }
    
    public function enviarSincrono($rpss, $numeroLote)
    {
        $this->method = 'enviarSincrono';
        /*
         * <lot:enviarSincrono>
         <mensagemXml>?</mensagemXml>
      </lot:enviarSincrono>
         */
    }
    
    public function testeEnviar($rpss, $numeroLote)
    {
        $this->method = 'testeEnviar';
        /*
         * <lot:testeEnviar>
         <mensagemXml>?</mensagemXml>
      </lot:testeEnviar>
         */
    }
    
    /**
     * Monta o request da mansagem SOAP
     * @param string $body
     * @param string $method
     * @return string
     */
    protected function buildRequest($body)
    {
        $tag = $this->method;
        $request = "<$tag>";
        $request .= "<mensagemXML>$body</mensagemXML>";
        $request .= "</$tag>";
        if ($this->withcdata) {
            $request = $this->replaceNodeWithCdata($request, 'mensagemXML', $body);
        }
        return $request;
    }
    
}
