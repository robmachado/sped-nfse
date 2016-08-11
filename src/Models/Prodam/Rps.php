<?php

namespace NFePHP\NFSe\Models\Prodam;

/**
 * Classe a construção do xml da NFSe para a Cidade de São Paulo
 * conforme o modelo Prodam
 *
 * @category  NFePHP
 * @package   NFePHP\NFSe\Prodam\Rps
 * @copyright NFePHP Copyright (c) 2016
 * @license   http://www.gnu.org/licenses/lgpl.txt LGPLv3+
 * @license   https://opensource.org/licenses/MIT MIT
 * @license   http://www.gnu.org/licenses/gpl.txt GPLv3+
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 * @link      http://github.com/nfephp-org/sped-nfse for the canonical source repository
 */

use InvalidArgumentException;
use NFePHP\Common\Strings\Strings;
use NFePHP\NFSe\Models\Base\RpsBase;

class Rps extends RpsBase
{
    
    //dados submetidos
    public $prestadorIM = '';
    public $serieRPS = '';
    public $numeroRPS = '';
    public $dtEmiRPS = '';
    public $tipoRPS = '';
    public $statusRPS = '';
    public $tributacaoRPS = '';
    public $valorServicosRPS = '';
    public $valorDeducoesRPS = '';
    public $valorPISRPS = '';
    public $valorCOFINSRPS = '';
    public $valorINSSRPS = '';
    public $valorIRRPS = '';
    public $valorCSLLRPS = '';
    public $valorCargaTributariaRPS = '';
    public $percentualCargaTributariaRPS = '';
    public $fonteCargaTributariaRPS = '';
    public $codigoCEIRPS = '';
    public $matriculaObraRPS = '';
    public $municipioPrestacaoRPS = '';
    public $codigoServicoRPS = '';
    public $aliquotaServicosRPS = '';
    public $issRetidoRPS = '';
    public $discriminacaoRPS = '';
    public $tomadorCPF = '';
    public $tomadorCNPJ = '';
    public $tomadorIE = '';
    public $tomadorIM = '';
    public $tomadorRazao  = '';
    public $tomadorTipoLogradouro = '';
    public $tomadorLogradouro = '';
    public $tomadorNumeroEndereco = '';
    public $tomadorComplementoEndereco = '';
    public $tomadorBairro = '';
    public $tomadorCodCidade = '';
    public $tomadorSiglaUF = '';
    public $tomadorCEP = '';
    public $tomadorEmail = '';
    public $intermediarioCNPJ = '';
    public $intermediarioCPF = '';
    public $intermediarioIM = '';
    public $intermediarioISSRetido = 'N';
    public $intermediarioEmail = '';
    public $intermediarioExists = false;
    public $assinaturaRPS = '';
    
    private $aTp = [
        'RPS' => 'Recibo Provisório de Serviços',
        'RPS-M' => 'Recibo Provisório de Serviços proveniente de Nota Fiscal Conjugada (Mista)',
        'RPS-C' => 'Cupom'
    ];
    
    private $aTrib = [
        'T' => 'Tributado em São Paulo',
        'F' => 'Tributado Fora de São Paulo',
        'A' => 'Tributado em São Paulo, porém Isento',
        'B' => 'Tributado Fora de São Paulo, porém Isento',
        'M' => 'Tributado em São Paulo, porém Imune',
        'N' => 'Tributado Fora de São Paulo, porém Imune',
        'X' => 'Tributado em São Paulo, porém Exigibilidade Suspensa',
        'V' => 'Tributado Fora de São Paulo, porém Exigibilidade Suspensa',
        'P' => 'Exportação de Serviços'
    ];
    
    public function render()
    {
    }
      
    public function prestador($im)
    {
        $this->prestadorIM = $im;
        $this->zAssinatura();
    }
    
    public function tomador(
        $razao,
        $cnpj = '',
        $cpf = '',
        $ie = '',
        $im = '',
        $email = ''
    ) {
        $this->tomadorRazao = Strings::cleanString($razao);
        $this->tomadorCPF = $cpf;
        $this->tomadorCNPJ = $cnpj;
        $this->tomadorIE = $ie;
        $this->tomadorIM = $im;
        $this->tomadorEmail = $email;
        $this->zAssinatura();
    }
    
    public function tomadorEndereco(
        $tipo = '',
        $logradouro = '',
        $numero = '',
        $complemento = '',
        $bairro = '',
        $cmun = '',
        $uf = '',
        $cep = ''
    ) {
        $this->tomadorTipoLogradouro = $tipo;
        $this->tomadorLogradouro = Strings::cleanString($logradouro);
        $this->tomadorNumeroEndereco = $numero;
        $this->tomadorComplementoEndereco = Strings::cleanString($complemento);
        $this->tomadorBairro = Strings::cleanString($bairro);
        $this->tomadorCodCidade = $cmun;
        $this->tomadorSiglaUF = $uf;
        $this->tomadorCEP = $cep;
    }
    
    public function intermediario(
        $cnpj = '',
        $cpf = '',
        $im = '',
        $issRetido = '',
        $email = ''
    ) {
        $this->intermediarioCNPJ = $cnpj;
        $this->intermediarioCPF = $cpf;
        $this->intermediarioIM = $im;
        $this->intermediarioISSRetido = $issRetido;
        $this->intermediarioEmail = $email;
        if ($cnpj != '' || $cpf != '' || $im != '' || $issRetido != '' || $email != '') {
            $this->intermediarioExists = true;
            $this->zAssinatura();
        }
    }

    public function serie($serie = '')
    {
        $this->serieRPS = $serie;
        $this->zAssinatura();
    }
    
    public function numero($numero = 0)
    {
        if (!is_numeric($numero) || $numero <= 0) {
            $msg = 'O numero deve ser maior ou igual a 1';
            throw new InvalidArgumentException($msg);
        }
        $this->numeroRPS = $numero;
        $this->zAssinatura();
    }
    
    public function data($data)
    {
        $this->dtEmiRPS = $data;
        $this->zAssinatura();
    }
    
    public function status($status = 'N')
    {
        if (!$this->zValidData(['N' => 0, 'C' => 1], $status)) {
            $msg = 'O status pode ser apenas N-normal ou C-cancelado.';
            throw new InvalidArgumentException($msg);
        }
        $this->statusRPS = $status;
        $this->zAssinatura();
    }
    
    /**
     * Tipo do RPS
     * RPS – Recibo Provisório de Serviços
     * RPS-M – Recibo Provisório de Serviços proveniente de Nota Fiscal Conjugada (Mista);
     * RPS-C – Cupom
     *
     * @param string $tipo
     */
    public function tipo($tipo = '')
    {
        if (!$this->zValidData($this->aTp, $tipo)) {
            $msg = 'O tipo deve ser informado com um código válido';
            throw new InvalidArgumentException($msg);
        }
        $this->tipoRPS = $tipo;
    }
    
    /**
     * Tributação
     * T – Tributado em São Paulo
     * F – Tributado Fora de São Paulo
     * A – Tributado em São Paulo, porém Isento
     * B – Tributado Fora de São Paulo, porém Isento
     * M – Tributado em São Paulo, porém Imune
     * N – Tributado Fora de São Paulo, porém Imune
     * X – Tributado em São Paulo, porém Exigibilidade Suspensa
     * V – Tributado Fora de São Paulo, porém Exigibilidade Suspensa
     * P – Exportação de Serviços
     *
     * @param string $tributacao
     */
    public function tributacao($tributacao = '')
    {
        if (!$this->zValidData($this->aTrib, $tributacao)) {
            $msg = 'A tributação deve ser informada com um código válido';
            throw new InvalidArgumentException($msg);
        }
        $this->tributacaoRPS = $tributacao;
        $this->zAssinatura();
    }
    
    public function codigoServico($cod)
    {
        $this->codigoServicoRPS =  $cod;
    }
            
    public function valorServicos($valor)
    {
        $this->valorServicosRPS = $valor;
        $this->zAssinatura();
    }
    
    public function valorDeducoes($valor)
    {
        $this->valorDeducoesRPS = $valor;
        $this->zAssinatura();
    }
    
    public function aliquotaServico($valor)
    {
        $this->aliquotaServicosRPS = $valor;
    }
    
    public function issRetido($valor = 'N')
    {
        if (!$this->zValidData(['S' => 0, 'N' => 1], $valor)) {
            $msg = 'Voce deve indicar S ou N para informar se existe retenção de ISS.';
            throw new InvalidArgumentException($msg);
        }
        $this->issRetidoRPS = $valor;
        $this->zAssinatura();
    }
    
    public function discriminacao($desc)
    {
        $this->discriminacaoRPS = Strings::cleanString($desc);
    }
    
    public function valorCargaTributaria($valor)
    {
        $this->valorCargaTributariaRPS = $valor;
    }
    
    public function percentualCargaTributaria($valor)
    {
        $this->percentualCargaTributariaRPS = $valor;
    }
    
    public function fonteCargaTributaria($fonte)
    {
        $this->fonteCargaTributariaRPS = $fonte;
    }
    
    public function valorPIS($valor)
    {
        $this->valorPISRPS = $valor;
    }
    
    public function valorCOFINS($valor)
    {
        $this->valorCOFINSRPS = $valor;
    }
    public function valorINSS($valor)
    {
        $this->valorINSSRPS = $valor;
    }
    
    public function valorIR($valor)
    {
        $this->valorIRRPS = $valor;
    }
    
    public function valorCSLL($valor)
    {
        $this->valorCSLLRPS = $valor;
    }
    
    public function codigoCEI($cod)
    {
        $this->codigoCEIRPS = $cod;
    }
    
    public function matriculaObra($matricula)
    {
        $this->matriculaObraRPS = $matricula;
    }
    
    public function municipioPrestacao($cmun)
    {
        $this->municipioPrestacaoRPS = $cmun;
    }
    
    /**
     * Constroi a string que será a assinatura do RPS
     */
    protected function zAssinatura()
    {
        $content = sprintf('%08s', $this->prestadorIM) .
            sprintf('%-5s', $this->serieRPS) .
            sprintf('%012s', $this->numeroRPS) .
            str_replace("-", "", $this->dtEmiRPS) .
            $this->tributacaoRPS .
            $this->statusRPS .
            $this->issRetidoRPS .
            sprintf('%015s', str_replace(array('.', ','), '', number_format($this->valorServicosRPS, 2))) .
            sprintf('%015s', str_replace(array('.', ','), '', number_format($this->valorDeducoesRPS, 2))) .
            sprintf('%05s', $this->codigoServicoRPS);
            
        if ($this->tomadorCNPJ != '') {
            $content .= '1' . sprintf('%014s', $this->tomadorCNPJ);
        } elseif ($this->tomadorCPF != '') {
            $content .= '2' . sprintf('%014s', $this->tomadorCPF);
        } else {
            $content .= '3' . sprintf('%014s', '0');
        }
        if ($this->intermediarioExists) {
            if ($this->intermediarioCNPJ != '') {
                $content .= '1' . sprintf('%014s', $this->intermediarioCNPJ);
            } elseif ($this->intermediarioCPF != '') {
                $content .= '2' . sprintf('%014s', $this->intermediarioCPF);
            } else {
                $content .= '3' . sprintf('%014s', '0');
            }
            $content .= $this->intermediarioISSRetido;
        }
        $this->assinaturaRPS = $content;
    }
}
