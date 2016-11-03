<?php

namespace NFePHP\NFSe\Models\Issnet;

/**
 * Classe para a renderização dos RPS em XML
 * conforme o modelo ISSNET
 *
 * @category  NFePHP
 * @package   NFePHP\NFSe\Models\Issnet\RenderRPS
 * @copyright NFePHP Copyright (c) 2016
 * @license   http://www.gnu.org/licenses/lgpl.txt LGPLv3+
 * @license   https://opensource.org/licenses/MIT MIT
 * @license   http://www.gnu.org/licenses/gpl.txt GPLv3+
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 * @link      http://github.com/nfephp-org/sped-nfse for the canonical source repository
 */

use NFePHP\Common\Dom\Dom;
use NFePHP\NFSe\Models\Issnet\Rps;
use NFePHP\Common\Certificate;

class RenderRPS
{
    protected static $dom;
    protected static $certificate;
    protected static $algorithm;

    public static function toXml($data, $algorithm = OPENSSL_ALGO_SHA1)
    {
        //self::$certificate = $certificate;
        self::$algorithm = $algorithm;
        $xml = '';
        if (is_object($data)) {
            return self::render($data);
        } elseif (is_array($data)) {
            foreach ($data as $rps) {
                $xml .= self::render($rps);
            }
        }
        return $xml;
    }
    
    /**
     * Monta o xml com base no objeto Rps
     * @param Rps $rps
     * @return string
     */
    private static function render(Rps $rps)
    {
        self::$dom = new Dom('1.0', 'utf-8');
        $root = self::$dom->createElement('tc:Rps');
        $infRPS = self::$dom->createElement('tc:InfRps');
        
        $identificacaoRps = self::$dom->createElement('tc:IdentificacaoRps');
        self::$dom->addChild(
            $identificacaoRps,
            'tc:Numero',
            $rps->infNumero,
            true,
            "Numero do RPS",
            true
        );
        self::$dom->addChild(
            $identificacaoRps,
            'tc:Serie',
            $rps->infSerie,
            true,
            "Serie do RPS",
            true
        );
        self::$dom->addChild(
            $identificacaoRps,
            'tc:Tipo',
            $rps->infTipo,
            true,
            "Tipo do RPS",
            true
        );
        self::$dom->appChild($infRPS, $identificacaoRps, 'Adicionando tag IdentificacaoRPS');
        
        self::$dom->addChild(
            $infRPS,
            'tc:DataEmissao',
            $rps->infDataEmissao->format('Y-m-d\TH:i:s'),
            true,
            'Data de Emissão do RPS',
            false
        );
        self::$dom->addChild(
            $infRPS,
            'tc:NaturezaOperacao',
            $rps->infNaturezaOperacao,
            true,
            'Natureza da operação',
            false
        );
        self::$dom->addChild(
            $infRPS,
            'tc:OptanteSimplesNacional',
            $rps->infOptanteSimplesNacional,
            true,
            'OptanteSimplesNacional',
            false
        );
        self::$dom->addChild(
            $infRPS,
            'tc:IncentivadorCultural',
            $rps->infIncentivadorCultural,
            true,
            'IncentivadorCultural',
            false
        );
        self::$dom->addChild(
            $infRPS,
            'tc:Status',
            $rps->infStatus,
            true,
            'Status',
            false
        );
        self::$dom->addChild(
            $infRPS,
            'tc:RegimeEspecialTributacao',
            $rps->infRegimeEspecialTributacao,
            true,
            'RegimeEspecialTributacao',
            false
        );
        
        $servico = self::$dom->createElement('tc:Servico');
        $valores = self::$dom->createElement('tc:Valores');
        self::$dom->addChild(
            $valores,
            'tc:ValorServicos',
            $rps->infValorServicos,
            true,
            'ValorServicos',
            false
        );
        self::$dom->addChild(
            $valores,
            'tc:ValorPis',
            $rps->infValorPis,
            true,
            'ValorPis',
            false
        );
        self::$dom->addChild(
            $valores,
            'tc:ValorCofins',
            $rps->infValorCofins,
            true,
            'ValorCofins',
            false
        );
        self::$dom->addChild(
            $valores,
            'tc:ValorInss',
            $rps->infValorInss,
            true,
            'ValorInss',
            false
        );
        self::$dom->addChild(
            $valores,
            'tc:ValorIr',
            $rps->infValorIr,
            true,
            'ValorIr',
            false
        );
        self::$dom->addChild(
            $valores,
            'tc:ValorCsll',
            $rps->infValorCsll,
            true,
            'ValorCsll',
            false
        );
        self::$dom->addChild(
            $valores,
            'tc:IssRetido',
            $rps->infIssRetido,
            true,
            'IssRetido',
            false
        );
        self::$dom->addChild(
            $valores,
            'tc:ValorIss',
            $rps->infValorIss,
            true,
            'ValorIss',
            false
        );
        self::$dom->addChild(
            $valores,
            'tc:BaseCalculo',
            $rps->infBaseCalculo,
            true,
            'BaseCalculo',
            false
        );
        self::$dom->addChild(
            $valores,
            'tc:Aliquota',
            $rps->infAliquota,
            true,
            'Aliquota',
            false
        );
        self::$dom->addChild(
            $valores,
            'tc:ValorLiquidoNfse',
            $rps->infValorLiquidoNfse,
            true,
            'ValorLiquidoNfse',
            false
        );
        self::$dom->addChild(
            $valores,
            'tc:DescontoIncondicionado',
            $rps->infDescontoIncondicionado,
            true,
            'DescontoIncondicionado',
            false
        );
        self::$dom->addChild(
            $valores,
            'tc:DescontoCondicionado',
            $rps->infDescontoCondicionado,
            true,
            'DescontoCondicionado',
            false
        );
        self::$dom->appChild($servico, $valores, 'Adicionando tag Valores em Servico');
        
        self::$dom->addChild(
            $servico,
            'tc:ItemListaServico',
            $rps->infItemListaServico,
            true,
            'ItemListaServico',
            false
        );
        self::$dom->addChild(
            $servico,
            'tc:CodigoCnae',
            $rps->infCodigoCnae,
            true,
            'CodigoCnae',
            false
        );
        self::$dom->addChild(
            $servico,
            'tc:CodigoTributacaoMunicipio',
            $rps->infCodigoTributacaoMunicipio,
            true,
            'CodigoTributacaoMunicipio',
            false
        );
        self::$dom->addChild(
            $servico,
            'tc:Discriminacao',
            $rps->infDiscriminacao,
            true,
            'Discriminacao',
            false
        );
        self::$dom->addChild(
            $servico,
            'tc:MunicipioPrestacaoServico',
            $rps->infMunicipioPrestacaoServico,
            true,
            'MunicipioPrestacaoServico',
            false
        );
        self::$dom->appChild($infRPS, $servico, 'Adicionando tag Servico');
        
        $prestador = self::$dom->createElement('tc:Prestador');
        $cpfCnpj = self::$dom->createElement('tc:CpfCnpj');
        if ($rps->infPrestador['tipo'] == 2) {
            self::$dom->addChild(
                $cpfCnpj,
                'tc:Cnpj',
                $rps->infPrestador['cnpjcpf'],
                true,
                'Prestador CNPJ',
                false
            );
        } else {
            self::$dom->addChild(
                $cpfCnpj,
                'tc:Cpf',
                $rps->infPrestador['cnpjcpf'],
                true,
                'Prestador CPF',
                false
            );
        }
        self::$dom->appChild($prestador, $cpfCnpj, 'Adicionando tag CpfCnpj em Prestador');
        self::$dom->addChild(
            $prestador,
            'tc:InscricaoMunicipal',
            $rps->infPrestador['im'],
            true,
            'InscricaoMunicipal',
            false
        );
        self::$dom->appChild($infRPS, $prestador, 'Adicionando tag Prestador em infRPS');
        
        $tomador = self::$dom->createElement('tc:Tomador');
        $identificacaoTomador = self::$dom->createElement('tc:IdentificacaoTomador');
        $cpfCnpjTomador = self::$dom->createElement('tc:CpfCnpj');
        if ($rps->infTomador['tipo'] == 2) {
            self::$dom->addChild(
                $cpfCnpjTomador,
                'tc:Cnpj',
                $rps->infTomador['cnpjcpf'],
                true,
                'Tomador CNPJ',
                false
            );
        } else {
            self::$dom->addChild(
                $cpfCnpjTomador,
                'tc:Cpf',
                $rps->infTomador['cnpjcpf'],
                true,
                'Tomador CPF',
                false
            );
        }
        self::$dom->appChild($identificacaoTomador, $cpfCnpjTomador, 'Adicionando tag CpfCnpj em IdentificacaTomador');
        self::$dom->appChild($tomador, $identificacaoTomador, 'Adicionando tag IdentificacaoTomador em Tomador');
        self::$dom->addChild(
            $tomador,
            'tc:RazaoSocial',
            $rps->infTomador['razao'],
            true,
            'RazaoSocial',
            false
        );
        
        $endereco = self::$dom->createElement('tc:Endereco');
        self::$dom->addChild(
            $endereco,
            'tc:Endereco',
            $rps->infTomadorEndereco['end'],
            true,
            'Endereco',
            false
        );
        self::$dom->addChild(
            $endereco,
            'tc:Numero',
            $rps->infTomadorEndereco['numero'],
            true,
            'Numero',
            false
        );
        self::$dom->addChild(
            $endereco,
            'tc:Complemento',
            $rps->infTomadorEndereco['complemento'],
            true,
            'Complemento',
            false
        );
        self::$dom->addChild(
            $endereco,
            'tc:Bairro',
            $rps->infTomadorEndereco['bairro'],
            true,
            'Bairro',
            false
        );
        self::$dom->addChild(
            $endereco,
            'tc:Cidade',
            $rps->infTomadorEndereco['cmun'],
            true,
            'Cidade',
            false
        );
        self::$dom->addChild(
            $endereco,
            'tc:Estado',
            $rps->infTomadorEndereco['uf'],
            true,
            'Estado',
            false
        );
        self::$dom->addChild(
            $endereco,
            'tc:Cep',
            $rps->infTomadorEndereco['cep'],
            true,
            'Cep',
            false
        );
        self::$dom->appChild($tomador, $endereco, 'Adicionando tag Endereco em Tomador');
        self::$dom->appChild($infRPS, $tomador, 'Adicionando tag Tomador em infRPS');
        
        self::$dom->appChild($root, $infRPS, 'Adicionando tag infRPS em RPS');
        self::$dom->appendChild($root);
        $xml = str_replace('<?xml version="1.0" encoding="utf-8"?>', '', self::$dom->saveXML());
        return $xml;
    }
}
