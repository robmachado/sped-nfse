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

class Tools extends ToolsBase
{
    /**
     * Endereços dos webservices
     * @var array
     */
    protected $url = [
        '2' => 'https://testenfe.prefeitura.sp.gov.br/ws/lotenfe.asmx',
        '1' => 'https://nfe.prefeitura.sp.gov.br/ws/lotenfe.asmx'
    ];
    
    /**
     * Cabeçalho do RPS
     * @var string
     */
    protected $cabecalho;
    
    //quando mais de um RPS for carregado
    //as variaveis abaixo devem ser carregadas
    protected $transacao = false;
    protected $dtInicio;
    protected $dtFim;
    protected $qtdRPS;
    protected $valorTotalServicos = 0.0;
    protected $valorTotalDeducoes = 0.0;
    
    public function __construct($config)
    {
        parent::__construct($config);
    }
    
    /**
     * Construtor no cabeçalho
     */
    protected function cabecalho($numRPS = 1)
    {
        $versao = $this->aConfig['versao'];
        $cpf = $this->aConfig['cpf'];
        $cnpj = $this->aConfig['cnpj'];
        $this->cabecalho = "<Cabecalho Versao=\"$versao\"><CPFCNPJRemetente>";
        if ($cnpj != '') {
            $this->cabecalho .= "<CNPJ>$cnpj</CNPJ>";
        } else {
            $this->cabecalho .= "<CPF>$cpf</CPF>";
        }
        $this->cabecalho .= "</CPFCNPJRemetente>";
        if ($this->transacao) {
            $this->cabecalho .= "<transacao>true</transacao>"
                . "<dtInicio>$this->dtInicio</dtInicio>"
                . "<dtFim>$this->dtFim</dtFim>"
                . "<QtdRPS>$this->qtdRPS</QtdRPS>"
                . "<ValorTotalServicos>$this->valorTotalServicos</ValorTotalServicos>"
                . "<ValorTotalDeducoes>$this->valorTotalDeducoes</ValorTotalDeducoes>";
        }
        $this->cabecalho .= "</Cabecalho>";
    }
    
    public function envioRPS(RPS $rps)
    {
        $method = 'EnvioRPS';
    }
    
    public function envioLoteRPS($rpss = array())
    {
        $method = '';
        
        foreach ($rpss as $rps) {
            echo '<pre>';
            print_r($rps);
            echo '</pre><BR>';
        }
        //um array de objetos Prodam\Rps paqra formar um lote de envio
    }
    
    public function assina()
    {
    }
    
    
    public function testeEnvioRPS()
    {
    }
    
    public function consultaNFSe()
    {
    }
    
    public function consultaNFSeRecebidas()
    {
    }
    
    public function consultaNFSeEmitidas()
    {
    }
    
    public function consultaLote()
    {
    }
    
    public function consultaInformacoesLote()
    {
    }
    
    public function cancelamentoNFSe()
    {
    }

    public function consultaCNPJ()
    {
    }
    /**
     * Constroi a string que será a assinatura do RPS
     */
    protected function zSignRps(Rps $rps)
    {
        $content = sprintf('%08s', $rps->prestadorIM) .
            sprintf('%-5s', $rps->serieRPS) .
            sprintf('%012s', $rps->numeroRPS) .
            str_replace("-", "", $rps->dtEmiRPS) .
            $rps->tributacaoRPS .
            $rps->statusRPS .
            $rps->issRetidoRPS .
            sprintf('%015s', str_replace(array('.', ','), '', number_format($rps->valorServicosRPS, 2))) .
            sprintf('%015s', str_replace(array('.', ','), '', number_format($rps->valorDeducoesRPS, 2))) .
            sprintf('%05s', $rps->codigoServicoRPS);
            
        if ($rps->tomadorCNPJ != '') {
            $content .= '1' . sprintf('%014s', $rps->tomadorCNPJ);
        } elseif ($rps->tomadorCPF != '') {
            $content .= '2' . sprintf('%014s', $rps->tomadorCPF);
        } else {
            $content .= '3' . sprintf('%014s', '0');
        }
        if ($rps->intermediarioExists) {
            if ($rps->intermediarioCNPJ != '') {
                $content .= '1' . sprintf('%014s', $rps->intermediarioCNPJ);
            } elseif ($this->intermediarioCPF != '') {
                $content .= '2' . sprintf('%014s', $rps->intermediarioCPF);
            } else {
                $content .= '3' . sprintf('%014s', '0');
            }
            $content .= $rps->intermediarioISSRetido;
        }
        $pkeyId = openssl_get_privatekey(file_get_contents($this->privateKey));
        openssl_sign($content, $signatureValue, $pkeyId, OPENSSL_ALGO_SHA1);
        openssl_free_key($pkeyId);
        return base64_encode($signatureValue);
        //$dom->getElementsByTagName('Assinatura')->item(0)->nodeValue = 'AAAAAAAAAAAAAAAAAAAAAAAAAAAAA';
    }
}
