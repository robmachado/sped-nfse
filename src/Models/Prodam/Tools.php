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

use NFePHP\Common\Certificate\Pkcs12;
use NFePHP\NFSe\Models\Prodam\Rps;
use NFePHP\NFSe\Models\Prodam\Factories;
use NFePHP\NFSe\Models\Tools as ToolsBase;

class Tools extends ToolsBase
{
    
    protected $versao = '1';
    protected $remetenteTipoDoc = '2';
    protected $remetenteCNPJCPF = '';
    protected $method = '';
    
    /**
     * Construtor da classe Tools
     * @param string $config
     */
    public function __construct($config)
    {
        parent::__construct($config);
        $this->versao = $this->aConfig['versao'];
        $this->remetenteCNPJCPF = $this->aConfig['cnpj'];
        if ($this->aConfig['cpf'] != '') {
            $this->remetenteTipoDoc = '1';
            $this->remetenteCNPJCPF = $this->aConfig['cpf'];
        }
    }
    
    /**
     * Envio de apenas um RPS
     * @param \NFePHP\NFSe\Models\Prodam\RPS $rps
     */
    public function envioRPS(RPS $rps)
    {
        $this->method = 'EnvioRPS';
        $fact = new Factories\EnvioRPS($this->oCertificate);
        $xml = $fact->render(
            $this->versao,
            $this->remetenteTipoDoc,
            $this->remetenteCNPJCPF,
            '',
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
        $fact = new Factories\EnvioRPS($this->oCertificate);
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
        $fact = new Factories\EnvioRPS($this->oCertificate);
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
        $fact = new Factories\ConsultaNFSe($this->oCertificate);
        $xml = $fact->render(
            $this->versao,
            $this->remetenteTipoDoc,
            $this->remetenteCNPJCPF,
            '',
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
        $fact = new Factories\ConsultaNFSePeriodo($this->oCertificate);
        $xml = $fact->render(
            $this->versao,
            $this->remetenteTipoDoc,
            $this->remetenteCNPJCPF,
            '',
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
        $fact = new Factories\ConsultaNFSePeriodo($this->oCertificate);
        $xml = $fact->render(
            $this->versao,
            $this->remetenteTipoDoc,
            $this->remetenteCNPJCPF,
            '',
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
        $fact = new Factories\ConsultaLote($this->oCertificate);
        $xml = $fact->render(
            $this->versao,
            $this->remetenteTipoDoc,
            $this->remetenteCNPJCPF,
            '',
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
        $fact = new Factories\ConsultaInformacoesLote($this->oCertificate);
        $xml = $fact->render(
            $this->versao,
            $this->remetenteTipoDoc,
            $this->remetenteCNPJCPF,
            '',
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
        $fact = new Factories\CancelamentoNFSe($this->oCertificate);
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
        $fact = new Factories\ConsultaCNPJ($this->oCertificate);
        $xml = $fact->render(
            $this->versao,
            $this->remetenteTipoDoc,
            $this->remetenteCNPJCPF,
            '',
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
        return $request;
    }

    /**
     * Envia mensagem por SOAP
     * @param string $body
     * @param string $method
     */
    public function envia($request)
    {
       
        
        header("Content-type: text/xml");
        echo $request;
        die;
        
        $url = $this->url[$this->aConfig['tpAmb']];
        try {
            $this->setSSLProtocol('TLSv1');
            //$response = $this->oSoap->send($url, '', '', $body, $this->method);
        } catch (Exception $ex) {
            echo $ex;
        }
    }
}
