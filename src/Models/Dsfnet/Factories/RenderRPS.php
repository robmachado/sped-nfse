<?php

namespace NFePHP\NFSe\Models\Dsfnet\Factories;

/**
 * Classe para a renderização dos RPS em XML
 * conforme o modelo DSFNET
 *
 * @category  NFePHP
 * @package   NFePHP\NFSe\Models\Dsfnet\RenderRPS
 * @copyright NFePHP Copyright (c) 2016
 * @license   http://www.gnu.org/licenses/lgpl.txt LGPLv3+
 * @license   https://opensource.org/licenses/MIT MIT
 * @license   http://www.gnu.org/licenses/gpl.txt GPLv3+
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 * @link      http://github.com/nfephp-org/sped-nfse for the canonical source repository
 */

use NFePHP\Common\Dom\Dom;
use NFePHP\NFSe\Models\Dsfnet\Rps;
use NFePHP\NFSe\Models\Signner;

class RenderRPS
{
    protected static $dom;
    protected static $priKey = '';
    
    public static function toXml($data = '', $priKey = '')
    {
        if ($data == '') {
            return '';
        }
        self::$priKey = $priKey;
        if (is_object($data)) {
            return self::render($data);
        } elseif (is_array($data)) {
            $xml = '';
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
    private static function render(Rps $rps = null)
    {
        self::$dom = new Dom();
        $root = self::$dom->createElement('RPS');
        $idAttribute = self::$dom->createAttribute('Id');
        $idAttribute->value = 'rps:'.$rps->numeroRPS;
        $root->appendChild($idAttribute);
        self::$dom->addChild(
            $root,
            'Assinatura',
            self::signstr($rps, self::$priKey),
            true,
            'Tag assinatura do RPS vazia',
            true
        );
        self::$dom->addChild(
            $root,
            'InscricaoMunicipalPrestador',
            $rps->inscricaoMunicipalPrestador,
            true,
            'Tag InscricaoMunicipalPrestador',
            false
        );
        self::$dom->addChild(
            $root,
            'RazaoSocialPrestador',
            $rps->razaoSocialPrestador,
            true,
            'Tag RazaoSocialPrestador',
            false
        );
        self::$dom->addChild(
            $root,
            'TipoRPS',
            $rps->tipoRPS,
            true,
            'Tag TipoRPS',
            false
        );
        self::$dom->addChild(
            $root,
            'SerieRPS',
            $rps->serieRPS,
            true,
            'Tag SerieRPS',
            false
        );
        self::$dom->addChild(
            $root,
            'NumeroRPS',
            $rps->numeroRPS,
            true,
            'Tag NumeroRPS',
            false
        );
        self::$dom->addChild(
            $root,
            'DataEmissaoRPS',
            $rps->dataEmissaoRPS,
            true,
            'Tag DataEmissaoRPS',
            false
        );
        self::$dom->addChild(
            $root,
            'SituacaoRPS',
            $rps->situacaoRPS,
            true,
            'Tag SituacaoRPS',
            false
        );
        self::$dom->addChild(
            $root,
            'SerieRPSSubstituido',
            $rps->serieRPSSubstituido,
            true,
            'Tag SerieRPSSubstituido',
            true
        );
        self::$dom->addChild(
            $root,
            'NumeroRPSSubstituido',
            $rps->numeroRPSSubstituido,
            true,
            'Tag NumeroRPSSubstituido',
            true
        );
        self::$dom->addChild(
            $root,
            'NumeroNFSeSubstituida',
            $rps->numeroNFSeSubstituida,
            true,
            'Tag NumeroNFSeSubstituida',
            true
        );
        self::$dom->addChild(
            $root,
            'DataEmissaoNFSeSubstituida',
            $rps->dataEmissaoNFSeSubstituida,
            true,
            'Tag DataEmissaoNFSeSubstituida',
            true
        );
        self::$dom->addChild(
            $root,
            'SeriePrestacao',
            $rps->seriePrestacao,
            true,
            'Tag SeriePrestacao',
            false
        );
        self::$dom->addChild(
            $root,
            'InscricaoMunicipalTomador',
            $rps->inscricaoMunicipalTomador,
            true,
            'Tag InscricaoMunicipalTomador',
            false
        );
        self::$dom->addChild(
            $root,
            'CPFCNPJTomador',
            $rps->cPFCNPJTomador,
            true,
            'Tag CPFCNPJTomador',
            false
        );
        self::$dom->addChild(
            $root,
            'RazaoSocialTomador',
            $rps->razaoSocialTomador,
            true,
            'Tag RazaoSocialTomador',
            false
        );
        self::$dom->addChild(
            $root,
            'TipoLogradouroTomador',
            $rps->tipoLogradouroTomador,
            true,
            'Tag TipoLogradouroTomador',
            false
        );
        self::$dom->addChild(
            $root,
            'LogradouroTomador',
            $rps->logradouroTomador,
            true,
            'Tag LogradouroTomador',
            false
        );
        self::$dom->addChild(
            $root,
            'NumeroEnderecoTomador',
            $rps->numeroEnderecoTomador,
            true,
            'Tag NumeroEnderecoTomador',
            false
        );
        self::$dom->addChild(
            $root,
            'ComplementoEnderecoTomador',
            $rps->complementoTomador,
            true,
            'Tag ComplementoEnderecoTomador',
            true
        );
        self::$dom->addChild(
            $root,
            'TipoBairroTomador',
            $rps->tipoBairroTomador,
            true,
            'Tag TipoBairroTomador',
            true
        );
        self::$dom->addChild(
            $root,
            'BairroTomador',
            $rps->bairroTomador,
            true,
            'Tag BairroTomador',
            true
        );
        self::$dom->addChild(
            $root,
            'CidadeTomador',
            $rps->cidadeTomador,
            true,
            'Tag CidadeTomador',
            true
        );
        self::$dom->addChild(
            $root,
            'CidadeTomadorDescricao',
            $rps->cidadeTomadorDescricao,
            true,
            'Tag CidadeTomadorDescricao',
            true
        );
        self::$dom->addChild(
            $root,
            'CEPTomador',
            $rps->cEPTomador,
            true,
            'Tag CEPTomador',
            true
        );
        self::$dom->addChild(
            $root,
            'EmailTomador',
            $rps->emailTomador,
            true,
            'Tag EmailTomador',
            true
        );
        self::$dom->addChild(
            $root,
            'CodigoAtividade',
            $rps->codigoAtividade,
            true,
            'Tag CodigoAtividade',
            false
        );
        self::$dom->addChild(
            $root,
            'AliquotaAtividade',
            $rps->aliquotaAtividade,
            true,
            'Tag AliquotaAtividade',
            false
        );
        self::$dom->addChild(
            $root,
            'TipoRecolhimento',
            $rps->tipoRecolhimento,
            true,
            'Tag TipoRecolhimento',
            false
        );
        self::$dom->addChild(
            $root,
            'MunicipioPrestacao',
            $rps->municipioPrestacao,
            true,
            'Tag MunicipioPrestacao',
            false
        );
        self::$dom->addChild(
            $root,
            'MunicipioPrestacaoDescricao',
            $rps->municipioPrestacaoDescricao,
            true,
            'Tag MunicipioPrestacaoDescricao',
            false
        );
        self::$dom->addChild(
            $root,
            'Operacao',
            $rps->operacao,
            true,
            'Tag Operacao',
            false
        );
        self::$dom->addChild(
            $root,
            'Tributacao',
            $rps->tributacao,
            true,
            'Tag Tributacao',
            false
        );
        self::$dom->addChild(
            $root,
            'ValorPIS',
            $rps->valorPIS,
            true,
            'Tag ValorPIS',
            false
        );
        self::$dom->addChild(
            $root,
            'ValorCOFINS',
            $rps->valorCOFINS,
            true,
            'Tag ValorCOFINS',
            false
        );
        self::$dom->addChild(
            $root,
            'ValorINSS',
            $rps->valorINSS,
            true,
            'Tag ValorINSS',
            false
        );
        self::$dom->addChild(
            $root,
            'ValorIR',
            $rps->valorIR,
            true,
            'Tag ValorIR',
            false
        );
        self::$dom->addChild(
            $root,
            'ValorCSLL',
            $rps->valorCSLL,
            true,
            'Tag ValorCSLL',
            false
        );
        self::$dom->addChild(
            $root,
            'AliquotaPIS',
            $rps->aliquotaPIS,
            true,
            'Tag AliquotaPIS',
            false
        );
        self::$dom->addChild(
            $root,
            'AliquotaCOFINS',
            $rps->aliquotaCOFINS,
            true,
            'Tag AliquotaCOFINS',
            false
        );
        self::$dom->addChild(
            $root,
            'AliquotaINSS',
            $rps->aliquotaINSS,
            true,
            'Tag AliquotaINSS',
            false
        );
        self::$dom->addChild(
            $root,
            'AliquotaIR',
            $rps->aliquotaIR,
            true,
            'Tag AliquotaIR',
            false
        );
        self::$dom->addChild(
            $root,
            'AliquotaCSLL',
            $rps->aliquotaCSLL,
            true,
            'Tag AliquotaCSLL',
            false
        );
        self::$dom->addChild(
            $root,
            'DescricaoRPS',
            $rps->descricaoRPS,
            true,
            'Tag DescricaoRPS',
            false
        );
        self::$dom->addChild(
            $root,
            'DDDPrestador',
            $rps->dDDPrestador,
            true,
            'Tag DDDPrestador',
            false
        );
        self::$dom->addChild(
            $root,
            'TelefonePrestador',
            $rps->telefonePrestador,
            true,
            'Tag TelefonePrestador',
            false
        );
        
        
        /*
            <DDDPrestador>011</DDDPrestador>
            <TelefonePrestador>80804040</TelefonePrestador>
            <DDDTomador>011</DDDTomador>
            <TelefoneTomador>20203030</TelefoneTomador>
            <MotCancelamento></MotCancelamento>
            <CPFCNPJIntermediario></CPFCNPJIntermediario>
            <Deducoes/>
            <Itens>
                <Item>
                    <DiscriminacaoServico>Descricao do Servico ...</DiscriminacaoServico>
                    <Quantidade>1.5555</Quantidade>
                    <ValorUnitario>155.5555</ValorUnitario>
                    <ValorTotal>241.96</ValorTotal>
                    <Tributavel>S</Tributavel>
                </Item>
            </Itens>
         */
        
        self::$dom->appendChild($root);
        $xml = str_replace('<?xml version="1.0" encoding="utf-8"?>', '', self::$dom->saveXML());
        return $xml;
    }
    
    /**
     * Cria a assinatura do RPS
     * @param Rps $rps
     * @param string $priKey
     * @return string
     */
    private static function signstr(Rps $rps, $priKey = '')
    {
        $content = str_pad($rps->prestadorIM, 11, '0', STR_PAD_LEFT);
        $content .= str_pad($rps->serieRPS, 5, ' ', STR_PAD_RIGHT);
        $content .= str_pad($rps->numeroRPS, 12, '0', STR_PAD_LEFT);
        $dt = new \DateTime($rps->dataEmissaoRPS);
        $content .= $dt->format('Ymd');
        $content .= str_pad($rps->tributacao, 2, ' ', STR_PAD_RIGHT);
        $content .= $rps->situacaoRPS;
        $content .= ($rps->tipoRecolhimento == 'A') ? 'N' : 'S';
        $valores = $this->calcValor();
        $content = str_pad(round($valores['valorFinal']*100, 0), 15, '0', STR_PAD_LEFT);
        $content = str_pad(round($valores['valorDeducao']*100, 0), 15, '0', STR_PAD_LEFT);
        $content .= str_pad($rps->codigoAtividade, 10, '0', STR_PAD_LEFT);
        $content .= str_pad($rps->cPFCNPJTomador, 14, '0', STR_PAD_LEFT);
        $signature = Signner::sign($content, $priKey);
        return $signature;
    }
    
    private function calcValor()
    {
        $valorItens = 0;
        foreach($rps->itens as $item) {
            $valorItens += $rps->item['valorTotal'];    
        }
        $valorDeducao = 0;
        foreach($rps->deducoes as $deducao) {
            $valorDeducao += $deducao['valorDeduzir'];
        }
        $valor = ($valorItens - $valorDeducao);
        return ['valorFinal' => $valor, 'valorItens' => $valorItens, 'valorDeducao' => $valorDeducao];
    }
}
