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
    public $versaoRPS = '';
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
    public $tomadorTipoDoc = '2';
    public $tomadorCNPJCPF = '';
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
    public $intermediarioCNPJCPF = '';
    public $intermediarioIM = '';
    public $intermediarioISSRetido = '';
    public $intermediarioEmail = '';
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
    }
    
    public function tomador(
        $razao,
        $tipo = '2',
        $cnpjcpf = '',
        $ie = '',
        $im = '',
        $email = ''
    ) {
        $this->tomadorRazao = Strings::cleanString($razao);
        $this->tomadorTipoDoc = $tipo;
        $this->tomadorCNPJCPF = $cnpjcpf;
        $this->tomadorIE = $ie;
        $this->tomadorIM = $im;
        $this->tomadorEmail = $email;
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
        $im = '',
        $issRetido = '',
        $email = ''
    ) {
        $this->intermediarioCNPJCPF = $cnpj;
        $this->intermediarioIM = $im;
        $this->intermediarioISSRetido = $issRetido;
        $this->intermediarioEmail = $email;
    }

    public function versao($versao)
    {
        $versao = preg_replace('/[^0-9]/', '', $versao);
        $this->versaoRPS = $versao;
    }
    
    public function serie($serie = '')
    {
        $serie = substr(trim($serie), 0, 5);
        $this->serieRPS = $serie;
    }
    
    public function numero($numero = 0)
    {
        if (!is_numeric($numero) || $numero <= 0) {
            $msg = 'O numero do RPS deve ser maior ou igual a 1';
            throw new InvalidArgumentException($msg);
        }
        $this->numeroRPS = $numero;
    }
    
    public function data($data)
    {
        
        $this->dtEmiRPS = $data;
    }
    
    public function status($status = 'N')
    {
        if (!$this->zValidData(['N' => 0, 'C' => 1], $status)) {
            $msg = 'O status pode ser apenas N-normal ou C-cancelado.';
            throw new InvalidArgumentException($msg);
        }
        $this->statusRPS = $status;
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
    }
    
    public function codigoServico($cod = '')
    {
        $this->codigoServicoRPS =  $cod;
    }
            
    public function valorServicos($valor = 0.00)
    {
        $this->valorServicosRPS = number_format($valor, 2, '.', '');
    }
    
    public function valorDeducoes($valor = 0.00)
    {
        $this->valorDeducoesRPS = number_format($valor, 2, '.', '');
    }
    
    public function aliquotaServico($valor = 0.0000)
    {
        if ($valor > 1 || $valor < 0) {
            $msg = 'Voce deve indicar uma aliquota em fração ex. 0.12.';
            throw new InvalidArgumentException($msg);
        }
        $this->aliquotaServicosRPS = number_format($valor, 4, '.', '');
    }
    
    public function issRetido($flag = 'N')
    {
        if (!$this->zValidData(['S' => 0, 'N' => 1], $flag)) {
            $msg = 'Voce deve indicar S ou N para informar se existe retenção de ISS.';
            throw new InvalidArgumentException($msg);
        }
        $this->issRetidoRPS = $flag;
    }
    
    public function discriminacao($desc = '')
    {
        $this->discriminacaoRPS = Strings::cleanString(trim($desc));
    }
    
    public function cargaTributaria($valor = 0.00, $percentual = 0.0000, $fonte = '')
    {
        $this->valorCargaTributariaRPS = number_format($valor, 2, '.', '');
        $this->percentualCargaTributariaRPS = number_format($valor, 4, '.', '');
        $this->fonteCargaTributariaRPS = substr(Strings::cleanString($fonte), 0, 10);
    }
    
    public function valorPIS($valor = 0.00)
    {
        $this->valorPISRPS = number_format($valor, 2, '.', '');
    }
    
    public function valorCOFINS($valor = 0.00)
    {
        $this->valorCOFINSRPS = number_format($valor, 2, '.', '');
    }
    
    public function valorINSS($valor = 0.00)
    {
        $this->valorINSSRPS = number_format($valor, 2, '.', '');
    }
    
    public function valorIR($valor = 0.00)
    {
        $this->valorIRRPS = number_format($valor, 2, '.', '');
    }
    
    public function valorCSLL($valor = 0.00)
    {
        $this->valorCSLLRPS = number_format($valor, 2, '.', '');
    }
    
    public function codigoCEI($cod = '')
    {
        $this->codigoCEIRPS = $cod;
    }
    
    public function matriculaObra($matricula = '')
    {
        $this->matriculaObraRPS = $matricula;
    }
    
    public function municipioPrestacao($cmun = '')
    {
        $this->municipioPrestacaoRPS = $cmun;
    }
}
