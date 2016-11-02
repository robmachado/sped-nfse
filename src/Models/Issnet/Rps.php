<?php

namespace NFePHP\NFSe\Models\Issnet;

/**
 * Classe a construção do xml da NFSe
 * conforme o modelo ISSNET
 *
 * @category  NFePHP
 * @package   NFePHP\NFSe\Models\Issnet\Rps
 * @copyright NFePHP Copyright (c) 2016
 * @license   http://www.gnu.org/licenses/lgpl.txt LGPLv3+
 * @license   https://opensource.org/licenses/MIT MIT
 * @license   http://www.gnu.org/licenses/gpl.txt GPLv3+
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 * @link      http://github.com/nfephp-org/sped-nfse for the canonical source repository
 */

use InvalidArgumentException;
use NFePHP\Common\Strings\Strings;
use NFePHP\NFSe\Common\Rps as RpsBase;

class Rps extends RpsBase
{
    /**
     * @var array
     */
    public $infPrestador = ['tipo' => '', 'cnpjcpf' => '' , 'im' => ''];
    /**
     * @var array
     */
    public $infTomador = ['tipo' => '', 'cnpjcpf' => '' , 'im' => '', 'razao' => ''];
    /**
     * @var array
     */
    public $infTomadorEndereco = [
        'end' => '',
        'numero' => '',
        'complemento' => '',
        'bairro' => '',
        'cmun' => '',
        'uf' => '',
        'cep' => ''
    ];
    public $infNumero;
    public $infSerie;
    public $infTipo;
    public $infDataEmissao;
    public $infNaturezaOperacao;
    public $infOptanteSimplesNacional;
    public $infIncentivadorCultural;
    public $infStatus;
    public $infRegimeEspecialTributacao;
    public $infValorServicos;
    public $infValorPis;
    public $infValorCofins;
    public $infValorInss;
    public $infValorIr;
    public $infValorCsll;
    public $infIssRetido;
    public $infValorIss;
    public $infBaseCalculo;
    public $infAliquota;
    public $infValorLiquidoNfse;
    public $infDescontoIncondicionado;
    public $infDescontoCondicionado;
    public $infItemListaServico;
    public $infCodigoCnae;
    public $infCodigoTributacaoMunicipio;
    public $infDiscriminacao;
    public $infMunicipioPrestacaoServico;
    
    public function prestador($tipo, $cnpjcpf, $im)
    {
        $this->prestador = [
            'tipo' => $tipo,
            'cnpjcpf' => $cnpjcpf,
            'im' => $im
        ];
    }
    
    public function tomador($tipo, $cnpjcpf, $im, $razao)
    {
        $this->tomador = [
            'tipo' => $tipo,
            'cnpjcpf' => $cnpjcpf,
            'im' => $im,
            'razao' => $razao
        ];
    }
    
    public function tomadorEndereco($end, $numero, $complemento, $bairro, $cmun, $uf, $cep)
    {
        $this->tomadorEndereco = [
            'end' => $end,
            'numero' => $numero,
            'complemento' => $complemento,
            'bairro' => $bairro,
            'cmun' => $cmun,
            'uf' => $uf,
            'cep' => $cep
        ];
    }
    
    public function numero($value)
    {
        $this->infNumero = $value;
    }
    
    public function serie($value)
    {
        $this->infSerie = $value;
    }
    
    public function tipo($value)
    {
        $this->infTipo = $value;
    }
    
    public function dataEmissao($value)
    {
        $this->infDataEmissao = $value;
    }
    
    public function naturezaOperacao($value)
    {
        $this->infNaturezaOperacao = $value;
    }
    
    public function optanteSimplesNacional($value)
    {
        $this->infOptanteSimplesNacional = $value;
    }
    
    public function incentivadorCultural($value)
    {
        $this->infIncentivadorCultural = $value;
    }
    
    public function status($value)
    {
        $this->infStatus = $value;
    }
    
    public function regimeEspecialTributacao($value)
    {
        $this->infRegimeEspecialTributacao = $value;
    }
    
    public function valorServicos($value)
    {
        $this->infValorServicos = $value;
    }
    
    public function valorPis($value)
    {
        $this->infValorPis = $value;
    }
    
    public function valorCofins($value)
    {
        $this->infValorCofins = $value;
    }
    
    public function valorInss($value)
    {
        $this->infValorInss = $value;
    }
    
    public function valorIr($value)
    {
        $this->infValorIr = $value;
    }
    
    public function valorCsll($value)
    {
        $this->infValorCsll = $value;
    }
    
    public function issRetido($value)
    {
        $this->infIssRetido = $value;
    }
    
    public function valorIss($value)
    {
        $this->infValorIss = $value;
    }
    
    public function baseCalculo($value)
    {
        $this->infBaseCalculo = $value;
    }
    
    public function aliquota($value)
    {
        $this->infAliquota = $value;
    }
    
    public function valorLiquidoNfse($value)
    {
        $this->infValorLiquidoNfse = $value;
    }
    
    public function descontoIncondicionado($value)
    {
        $this->infDescontoIncondicionado = $value;
    }
    
    public function descontoCondicionado($value)
    {
        $this->infDescontoCondicionado = $value;
    }
    
    public function itemListaServico($value)
    {
        $this->infItemListaServico = $value;
    }
    
    public function codigoCnae($value)
    {
        $this->infCodigoCnae = $value;
    }
    
    public function codigoTributacaoMunicipio($value)
    {
        $this->infCodigoTributacaoMunicipio = $value;
    }
    
    public function discriminacao($value)
    {
        $this->infDiscriminacao = $value;
    }
    
    public function municipioPrestacaoServico($value)
    {
        $this->infMunicipioPrestacaoServico = $value;
    }
}
