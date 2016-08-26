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
        $method = 'EnvioRPS';
        $fact = new Factories\EnvioRPS($this->oCertificate);
        $xml = $fact->render(
            $this->versao,
            $this->remetenteTipoDoc,
            $this->remetenteCNPJCPF,
            '',
            $rps
        );
        $response = $this->envia($xml, $method);
    }
    
    /**
     * Envio de lote de RPS
     * @param array $rpss
     */
    public function envioLoteRPS($rpss = array())
    {
        $method = 'EnvioLoteRPS';
        $fact = new Factories\EnvioRPS($this->oCertificate);
        $xml = $fact->render(
            $this->versao,
            $this->remetenteTipoDoc,
            $this->remetenteCNPJCPF,
            'true',
            $rpss
        );
        $response = $this->envia($xml, $method);
    }
    
    /**
     * Pedido de teste de envio de lote
     * @param array $rpss
     */
    public function testeEnvioLoteRPS($rpss = array())
    {
        $method = 'TesteEnvioLoteRPS';
        $fact = new Factories\EnvioRPS($this->oCertificate);
        $xml = $fact->render(
            $this->versao,
            $this->remetenteTipoDoc,
            $this->remetenteCNPJCPF,
            'true',
            $rpss
        );
        $response = $this->envia($xml, $method);
    }
    
    /**
     * Consulta as NFSe e/ou RPS
     * @param array $chavesNFSe array(array('prestadorIM'=>'', 'numeroNFSe'=>''))
     * @param array $chavesRPS array(array('prestadorIM'=>'', 'serieRPS'=>'', 'numeroRPS'=>''))
     */
    public function consultaNFSe($chavesNFSe = [], $chavesRPS = [])
    {
        $method = 'ConsultaNFe';
        $fact = new Factories\ConsultaNFSe($this->oCertificate);
        $xml = $fact->render(
            $this->versao,
            $this->remetenteTipoDoc,
            $this->remetenteCNPJCPF,
            '',
            $chavesNFSe,
            $chavesRPS
        );
        $response = $this->envia($xml, $method);
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
        $response = $this->envia($xml, $method);
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
        $response = $this->envia($xml, $method);
    }
    
    /**
     * Consulta Lote
     * @param string $numeroLote
     */
    public function consultaLote($numeroLote= '')
    {
        $method = 'ConsultaLote';
        $fact = new Factories\ConsultaLote($this->oCertificate);
        $xml = $fact->render(
            $this->versao,
            $this->remetenteTipoDoc,
            $this->remetenteCNPJCPF,
            '',
            $numeroLote    
        );
        $response = $this->envia($xml, $method);
    }
    
    /**
     * Pedido de informações de Lote
     * @param string $prestadorIM
     * @param string $numeroLote
     */
    public function consultaInformacoesLote($prestadorIM = '', $numeroLote= '')
    {
        $method = 'ConsultaInformacoesLote';
        $fact = new Factories\ConsultaInformacoesLote($this->oCertificate);
        $xml = $fact->render(
            $this->versao,
            $this->remetenteTipoDoc,
            $this->remetenteCNPJCPF,
            '',
            $prestadorIM,    
            $numeroLote    
        );
        $response = $this->envia($xml, $method);
    }
    
    /**
     * Solicita cancelamento da NFSe
     * @param string $prestadorIM
     * @param string $numeroNFSe
     */
    public function cancelamentoNFSe($prestadorIM = '', $numeroNFSe = '')
    {
        $method = 'CancelamentoNFe';
        $fact = new Factories\CancelamentoNFSe($this->oCertificate);
        $xml = $fact->render(
            $this->versao,
            $this->remetenteTipoDoc,
            $this->remetenteCNPJCPF,
            'true',
            $prestadorIM,
            $numeroNFSe
        );
        $response = $this->envia($xml, $method);
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
        $method = 'ConsultaCNPJ';
        $fact = new Factories\ConsultaCNPJ($this->oCertificate);
        $xml = $fact->render(
            $this->versao,
            $this->remetenteTipoDoc,
            $this->remetenteCNPJCPF,
            '',
            str_pad($cnpjContribuinte, 14, '0', STR_PAD_LEFT)
        );
        $response = $this->envia($xml, $method);
    }
    
    /**
     * Envia mensagem por SOAP
     * @param string $body
     * @param string $method
     */
    protected function envia($body, $method)
    {
        $tag = $method."Request";
        $request = "<$tag>";
        $request .= "<VersaoSchema>$this->versao</VersaoSchema>";
        $request .= "<MensagemXML>$body</MensagemXML>";
        $request .= "</$tag>";
        header("Content-type: text/xml");
        echo $request;
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
