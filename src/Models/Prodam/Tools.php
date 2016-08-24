<?php

namespace NFePHP\NFSe\Models\Prodam;

/**
 * Classe para a comunicação com os webservices da Cidade de São Paulo
 * conforme o modelo Prodam
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

use NFePHP\NFSe\Models\Base\ToolsBase;
use NFePHP\NFSe\Models\Prodam\Rps;
use NFePHP\NFSe\Models\ToolsInterface;
use NFePHP\NFSe\Models\Prodam\Factories;

class Tools extends ToolsBase
{
    
    protected $versao = '1';
    protected $remetenteTipoDoc = '2';
    protected $remetenteCNPJCPF = '';

    /**
     * Endereços dos webservices
     * @var array
     */
    protected $url = [
        '2' => 'https://testenfe.prefeitura.sp.gov.br/ws/lotenfe.asmx',
        '1' => 'https://nfe.prefeitura.sp.gov.br/ws/lotenfe.asmx'
    ];
    
    protected $xmlnsxsd="http://www.w3.org/2001/XMLSchema";
    protected $xmlnsxsi="http://www.w3.org/2001/XMLSchema-instance";
    protected $xmlns= "http://www.prefeitura.sp.gov.br/nfe";
    
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
        $xml = Factories\EnvioRPS::render(
            $this->versao,
            $this->remetenteTipoDoc,
            $this->remetenteCNPJCPF,
            true,
            $rps,
            $this->oCertificate->priKey
        );
        $body = "<EnvioRPSRequest>";
        $body .= " <VersaoSchema>$this->versao</VersaoSchema>";
        $body .= " <MensagemXML>$xml</MensagemXML>";
        $body .= "</EnvioRPSRequest>";
        $method = 'EnvioRPS';
        $response = $this->envia($body, $method);
    }
    
    /**
     * Envio de lote de RPS
     * @param array $rpss
     */
    public function envioLoteRPS($rpss = array())
    {
        $fact = new Factories\EnvioRPS($this->oCertificate);
        $xml = $fact->render(
            $this->versao,
            $this->remetenteTipoDoc,
            $this->remetenteCNPJCPF,
            'true',
            $rpss
        );
        $body = "<EnvioLoteRPSRequest>";
        $body .= " <VersaoSchema>$this->versao</VersaoSchema>";
        $body .= " <MensagemXML>$xml</MensagemXML>";
        $body .= "</EnvioLoteRPSRequest>";
        $method = 'EnvioLoteRPS';
        $response = $this->envia($body, $method);
    }
    
    public function testeEnvioLoteRPS()
    {
        $method = 'TesteEnvioLoteRPS';
    }
    
    /**
     * Consulta as NFSe e/ou RPS
     * @param array $chavesNFSe array(array('prestadorIM'=>'', 'numeroNFSe'=>''))
     * @param array $chavesRPS array(array('prestadorIM'=>'', 'serieRPS'=>'', 'numeroRPS'=>''))
     */
    public function consultaNFSe($chavesNFSe = [], $chavesRPS = [])
    {
        $fact = new Factories\ConsultaNFSe($this->oCertificate);
        $xml = $fact->render(
            $this->versao,
            $this->remetenteTipoDoc,
            $this->remetenteCNPJCPF,
            '',
            $chavesNFSe,
            $chavesRPS
        );
        $body = "<ConsultaNFeRequest>";
        $body .= "<VersaoSchema>$this->versao</VersaoSchema>";
        $body .= "<MensagemXML>$xml</MensagemXML>";
        $body .= "</ConsultaNFeRequest>";
        $method = 'ConsultaNFe';
        $response = $this->envia($body, $method);
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
        $method = 'ConsultaNFeRecebidas';
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
        $body = "<ConsultaNFeRecebidasRequest>";
        $body .= "<VersaoSchema>$this->versao</VersaoSchema>";
        $body .= "<MensagemXML>$xml</MensagemXML>";
        $body .= "</ConsultaNFeRecebidasRequest>";
        $response = $this->envia($body, $method);
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
        $method = 'ConsultaNFeEmitidas';
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
        $body = "<ConsultaNFeEmitidasRequest>";
        $body .= "<VersaoSchema>$this->versao</VersaoSchema>";
        $body .= "<MensagemXML>$xml</MensagemXML>";
        $body .= "</ConsultaNFeEmitidasRequest>";
        $response = $this->envia($body, $method);
    }
    
    public function consultaLote()
    {
        $method = 'ConsultaLote';
    }
    
    public function consultaInformacoesLote()
    {
        $method = 'ConsultaInformacoesLote';
    }
    
    public function cancelamentoNFSe($prestadorIM = '', $numeroNFSe = '')
    {
        $fact = new Factories\CancelamentoNFSe($this->oCertificate);
        $xml = $fact->render(
            $this->versao,
            $this->remetenteTipoDoc,
            $this->remetenteCNPJCPF,
            'true',
            $prestadorIM,
            $numeroNFSe
        );
        $body = "<CancelamentoNFeRequest>";
        $body .= "<VersaoSchema>$this->versao</VersaoSchema>";
        $body .= "<MensagemXML>$xml</MensagemXML>";
        $body .= "</CancelamentoNFeRequest>";
        $method = 'CancelamentoNFe';
        $response = $this->envia($body, $method);
    }

    public function consultaCNPJ($cnpjContribuinte = '')
    {
        if ($cnpjContribuinte == '') {
            return '';
        }
        $method = 'ConsultaCNPJ';
        $fact = new Factories\ConsultaCNPJ($this->oCertificate);
        $xml = $fact->render(
            $this->versao,
            $this->remetenteTipoDoc,
            $this->remetenteCNPJCPF,
            '',
            $cnpjContribuinte
        );
        $body = "<ConsultaCNPJRequest>";
        $body .= "<VersaoSchema>$this->versao</VersaoSchema>";
        $body .= "<MensagemXML>$xml</MensagemXML>";
        $body .= "</ConsultaCNPJRequest>";
        $response = $this->envia($body, $method);
    }
    
    protected function envia($body, $method)
    {
        header("Content-type: text/xml");
        echo $body;
        die;
        $url = $this->url[$this->aConfig['tpAmb']];
        try {
            $this->setSSLProtocol('TLSv1');
            //$response = $this->oSoap->send($url, '', '', $body, $method);
        } catch (Exception $ex) {
            echo $ex;
        }
    }
}
