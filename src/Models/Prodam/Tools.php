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
use NFePHP\NFSe\Models\Prodam\Factories\Signner;

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
    
    public function envioLoteRPS($rpss = array())
    {
        $xml = Factories\EnvioRPS::render(
            $this->versao,
            $this->remetenteTipoDoc,
            $this->remetenteCNPJCPF,
            true,
            $rpss,
            $this->oCertificate->priKey
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
    
    public function consultaNFSe($chavesNFSe = [], $chavesRPS = [])
    {
        $xml = Factories\ConsultaNFSe::render(
            $this->versao,
            $this->remetenteTipoDoc,
            $this->remetenteCNPJCPF,
            true,
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
    
    public function consultaNFSeRecebidas()
    {
        $method = 'ConsultaNFeRecebidas';
    }
    
    public function consultaNFSeEmitidas()
    {
        $method = 'ConsultaNFeEmitidas';
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
        if ($prestadorIM == '' || $numeroNFSe == '') {
            return '';
        }
        $xml = Factories\CancelamentoNFSe::render(
            $this->versao,
            $this->remetenteTipoDoc,
            $this->remetenteCNPJCPF,
            true,
            $prestadorIM,
            $numeroNFSe,
            $this->oCertificate->priKey
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
        //monta a mensagem basica
        $xml = Factories\ConsultaCNPJ::render(
            $this->versao,
            $this->remetenteTipoDoc,
            $this->remetenteCNPJCPF,
            true,
            $cnpjContribuinte
        );
        $body = "<ConsultaCNPJRequest xmlns=\"http://www.prefeitura.sp.gov.br/nfe\">";
        $body .= "<VersaoSchema>$this->versao</VersaoSchema>";
        $body .= "<MensagemXML>$xml</MensagemXML>";
        $body .= "</ConsultaCNPJRequest>";
        $body = $this->oCertificate->signXML($body, "Pedido$method", '', $algorithm = 'SHA1');
        
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
