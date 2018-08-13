<?php
namespace NFePHP\NFSe\Models\Infisc;

/**
 * Classe para a renderização dos RPS em XML
 * conforme o modelo ISSNET
 *
 * @category  NFePHP
 * @package   NFePHP\NFSe\Models\Infisc\RenderRPS
 * @copyright NFePHP Copyright (c) 2016
 * @license   http://www.gnu.org/licenses/lgpl.txt LGPLv3+
 * @license   https://opensource.org/licenses/MIT MIT
 * @license   http://www.gnu.org/licenses/gpl.txt GPLv3+
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 * @link      http://github.com/nfephp-org/sped-nfse for the canonical source repository
 */
use NFePHP\Common\DOMImproved as Dom;
use NFePHP\NFSe\Models\Infisc\Rps;
use NFePHP\Common\Certificate;

class RenderRPS
{

    /**
     * @var DOMImproved
     */
    protected static $dom;

    /**
     * @var Certificate
     */
    protected static $certificate;

    /**
     * @var int
     */
    protected static $algorithm;

    public static function toXml($data, $algorithm = OPENSSL_ALGO_SHA1)
    {        
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
        $root = self::$dom->createElement('NFS-e');
        $infRPS = self::$dom->createElement('infNFSe');
        $infRPS->setAttribute("versao", "1.1");
        $identificacaoRps = self::$dom->createElement('Id');
        self::$dom->addChild(
            $identificacaoRps, 'cNFS-e', $rps->Id->cNFSe, true, "Numero Aleatório", true
        );
        self::$dom->addChild(
            $identificacaoRps, 'mod', $rps->Id->mod, true, "Modelo do RPS", true
        );
        self::$dom->addChild(
            $identificacaoRps,
            'serie',
            $rps->Id->serie,
            true,
            "Série do RPS",
            true
        );
        self::$dom->addChild(
            $identificacaoRps,
            'nNFS-e',
            $rps->Id->nNFSe,
            true,
            "Número da nota",
            true
        );
        self::$dom->addChild(
            $identificacaoRps,
            'dEmi',
            $rps->Id->dEmi,
            true,
            "Data de emissão",
            true
        );
        self::$dom->addChild(
            $identificacaoRps,
            'hEmi',
            $rps->Id->hEmi,
            true,
            "Hora de emissão",
            true
        );
        self::$dom->addChild(
            $identificacaoRps,
            'tpNF',
            $rps->Id->tpNF,
            true,
            "Tipo de nota",
            true
        );
        self::$dom->addChild(
            $identificacaoRps,
            'refNF',
            $rps->Id->refNF,
            true,
            "Chave",
            true
        );
        self::$dom->addChild(
            $identificacaoRps,
            'tpEmis',
            $rps->Id->tpEmis,
            true,
            "Tipo de emissão",
            true
        );
        self::$dom->addChild(
            $identificacaoRps,
            'ambienteEmi',
            $rps->Id->ambienteEmi,
            true,
            "Ambiente",
            true
        );
        self::$dom->addChild(
            $identificacaoRps,
            'formaEmi',
            $rps->Id->formaEmi,
            true,
            "Forma de emissão",
            true
        );
        self::$dom->addChild(
            $identificacaoRps,
            'empreitadaGlobal',
            $rps->Id->empreitadaGlobal,
            true,
            "Empreitada Global",
            true
        );
        self::$dom->appChild($infRPS, $identificacaoRps, 'Adicionando tag IdentificacaoRPS');
//        $rps->infDataEmissao->setTimezone(self::$timezone);
//        self::$dom->addChild(
//            $infRPS,
//            'tc:DataEmissao',
//            $rps->infDataEmissao->format('Y-m-d\TH:i:s'),
//            true,
//            'Data de Emissão do RPS',
//            false
//        );
//        self::$dom->addChild(
//            $infRPS,
//            'tc:NaturezaOperacao',
//            $rps->infNaturezaOperacao,
//            true,
//            'Natureza da operação',
//            false
//        );
//        self::$dom->addChild(
//            $infRPS,
//            'tc:OptanteSimplesNacional',
//            $rps->infOptanteSimplesNacional,
//            true,
//            'OptanteSimplesNacional',
//            false
//        );
//        self::$dom->addChild(
//            $infRPS,
//            'tc:IncentivadorCultural',
//            $rps->infIncentivadorCultural,
//            true,
//            'IncentivadorCultural',
//            false
//        );
//        self::$dom->addChild(
//            $infRPS,
//            'tc:Status',
//            $rps->infStatus,
//            true,
//            'Status',
//            false
//        );
//        
//        if (!empty($rps->infRpsSubstituido['numero'])) {
//            $rpssubs = self::$dom->createElement('tc:RpsSubstituido');
//            self::$dom->addChild(
//                $rpssubs,
//                'tc:Numero',
//                $rps->infRpsSubstituido['numero'],
//                true,
//                'Numero',
//                false
//            );
//            self::$dom->addChild(
//                $rpssubs,
//                'tc:Serie',
//                $rps->infRpsSubstituido['serie'],
//                true,
//                'Serie',
//                false
//            );
//            self::$dom->addChild(
//                $rpssubs,
//                'tc:Tipo',
//                $rps->infRpsSubstituido['tipo'],
//                true,
//                'tipo',
//                false
//            );
//            self::$dom->appChild($infRPS, $rpssubs, 'Adicionando tag RpsSubstituido em infRps');
//        }
//        
//        self::$dom->addChild(
//            $infRPS,
//            'tc:RegimeEspecialTributacao',
//            $rps->infRegimeEspecialTributacao,
//            true,
//            'RegimeEspecialTributacao',
//            false
//        );
//        $servico = self::$dom->createElement('tc:Servico');
//        $valores = self::$dom->createElement('tc:Valores');
//        self::$dom->addChild(
//            $valores,
//            'tc:ValorServicos',
//            $rps->infValorServicos,
//            true,
//            'ValorServicos',
//            false
//        );
//        self::$dom->addChild(
//            $valores,
//            'tc:ValorDeducoes',
//            $rps->infValorDeducoes,
//            false,
//            'ValorDeducoes',
//            false
//        );
//        self::$dom->addChild(
//            $valores,
//            'tc:ValorPis',
//            $rps->infValorPis,
//            false,
//            'ValorPis',
//            false
//        );
//        self::$dom->addChild(
//            $valores,
//            'tc:ValorCofins',
//            $rps->infValorCofins,
//            false,
//            'ValorCofins',
//            false
//        );
//        self::$dom->addChild(
//            $valores,
//            'tc:ValorInss',
//            $rps->infValorInss,
//            false,
//            'ValorInss',
//            false
//        );
//        self::$dom->addChild(
//            $valores,
//            'tc:ValorIr',
//            $rps->infValorIr,
//            false,
//            'ValorIr',
//            false
//        );
//        self::$dom->addChild(
//            $valores,
//            'tc:ValorCsll',
//            $rps->infValorCsll,
//            false,
//            'ValorCsll',
//            false
//        );
//        self::$dom->addChild(
//            $valores,
//            'tc:IssRetido',
//            $rps->infIssRetido,
//            true,
//            'IssRetido',
//            false
//        );
//        self::$dom->addChild(
//            $valores,
//            'tc:ValorIss',
//            $rps->infValorIss,
//            false,
//            'ValorIss',
//            false
//        );
//        self::$dom->addChild(
//            $valores,
//            'tc:ValorIssRetido',
//            $rps->infValorIssRetido,
//            false,
//            'ValorIssRetido',
//            false
//        );
//        self::$dom->addChild(
//            $valores,
//            'tc:OutrasRetencoes',
//            $rps->infOutrasRetencoes,
//            false,
//            'OutrasRetencoes',
//            false
//        );
//        self::$dom->addChild(
//            $valores,
//            'tc:BaseCalculo',
//            $rps->infBaseCalculo,
//            false,
//            'BaseCalculo',
//            false
//        );
//        self::$dom->addChild(
//            $valores,
//            'tc:Aliquota',
//            number_format($rps->infAliquota, 2, '.', ''),
//            false,
//            'Aliquota',
//            false
//        );
//        self::$dom->addChild(
//            $valores,
//            'tc:ValorLiquidoNfse',
//            $rps->infValorLiquidoNfse,
//            false,
//            'ValorLiquidoNfse',
//            false
//        );
//        self::$dom->addChild(
//            $valores,
//            'tc:DescontoIncondicionado',
//            $rps->infDescontoIncondicionado,
//            false,
//            'DescontoIncondicionado',
//            false
//        );
//        self::$dom->addChild(
//            $valores,
//            'tc:DescontoCondicionado',
//            $rps->infDescontoCondicionado,
//            false,
//            'DescontoCondicionado',
//            false
//        );
//        self::$dom->appChild($servico, $valores, 'Adicionando tag Valores em Servico');
//        
//        self::$dom->addChild(
//            $servico,
//            'tc:ItemListaServico',
//            $rps->infItemListaServico,
//            true,
//            'ItemListaServico',
//            false
//        );
//        self::$dom->addChild(
//            $servico,
//            'tc:CodigoCnae',
//            $rps->infCodigoCnae,
//            true,
//            'CodigoCnae',
//            false
//        );
//        self::$dom->addChild(
//            $servico,
//            'tc:CodigoTributacaoMunicipio',
//            $rps->infCodigoTributacaoMunicipio,
//            true,
//            'CodigoTributacaoMunicipio',
//            false
//        );
//        self::$dom->addChild(
//            $servico,
//            'tc:Discriminacao',
//            $rps->infDiscriminacao,
//            true,
//            'Discriminacao',
//            false
//        );
//        self::$dom->addChild(
//            $servico,
//            'tc:MunicipioPrestacaoServico',
//            $rps->infMunicipioPrestacaoServico,
//            true,
//            'MunicipioPrestacaoServico',
//            false
//        );
//        self::$dom->appChild($infRPS, $servico, 'Adicionando tag Servico');
//
        
        $prestador = self::$dom->createElement('prest');
        self::$dom->addChild(
            $prestador,
            'CNPJ',
            $rps->prest->CNPJ,
            true,
            "CNPJ",
            true
        );        
        self::$dom->addChild(
            $prestador,
            'xNome',
            $rps->prest->xNome,
            true,
            'Razão Social',
            false
        );
        self::$dom->addChild(
            $prestador,
            'IM',
            $rps->prest->IM,
            true,
            'Inscrição Municipal',
            false
        );                
        
        $endereco = self::$dom->createElement('end');
        self::$dom->addChild(
            $endereco,
            'xLgr',
            $rps->prest->end->xLgr,
            true,
            'Logradouro',
            false
        );        
        self::$dom->addChild(
            $endereco,
            'nro',
            $rps->prest->end->nro,
            true,
            'Numero',
            false
        );
        self::$dom->addChild(
            $endereco,
            'xCpl',
            $rps->prest->end->xCpl,
            true,
            'Complemento',
            false
        );
        self::$dom->addChild(
            $endereco,
            'xBairro',
            $rps->prest->end->xBairro,
            true,
            'Bairro',
            false
        );
        self::$dom->addChild(
            $endereco,
            'cMun',
            $rps->prest->end->cMun,
            true,
            'Cidade',
            false
        );
        self::$dom->addChild(
            $endereco,
            'xMun',
            $rps->prest->end->xMun,
            true,
            'Cidade',
            false
        );
        self::$dom->addChild(
            $endereco,
            'UF',
            $rps->prest->end->UF,
            true,
            'Estado',
            false
        );
        self::$dom->addChild(
            $endereco,
            'CEP',
            $rps->prest->end->CEP,
            true,
            'Cep',
            false
        );
        self::$dom->addChild(
            $endereco,
            'cPais',
            $rps->prest->end->cPais,
            true,
            'País',
            false
        );        
        self::$dom->addChild(
            $endereco,
            'xPais',
            $rps->prest->end->xPais,
            true,
            'País',
            false
        );
        
        self::$dom->appChild($prestador, $endereco, 'Adicionando tag Endereco do Prestador');
        //Fim endereço
        
        self::$dom->addChild(
            $prestador,
            'regimeTrib',
            $rps->prest->regimeTrib,
            true,
            'Regime',
            false
        );
        self::$dom->appChild($infRPS, $prestador, 'Adicionando tag Prestador em infRPS');        
                       
        $tomador = self::$dom->createElement('TomS');                
        if (!empty($rps->TomS->CPNJ)) {
            self::$dom->addChild(
                $tomador,
                'CNPJ',
                $rps->TomS->CPNJ,
                true,
                'Tomador CNPJ',
                false
            );
        } else {            
            self::$dom->addChild(
                $tomador,
                'CPF',
                $rps->TomS->CPF,
                true,
                'Tomador CPF',
                false
            );
        }
        self::$dom->addChild(
            $tomador,
            'xNome',
            $rps->TomS->xNome,
            true,
            'Razao Social',
            false
        );
        
        $ender = self::$dom->createElement('ender');
        self::$dom->addChild(
            $ender,
            'xLgr',
            $rps->TomS->ender->xLgr,
            true,
            'Logradouro',
            false
        );        
        self::$dom->addChild(
            $ender,
            'nro',
            $rps->TomS->ender->nro,
            true,
            'Numero',
            false
        );
        self::$dom->addChild(
            $ender,
            'xCpl',
            $rps->TomS->ender->xCpl,
            true,
            'Complemento',
            false
        );
        self::$dom->addChild(
            $ender,
            'xBairro',
            $rps->TomS->ender->xBairro,
            true,
            'Bairro',
            false
        );
        self::$dom->addChild(
            $ender,
            'cMun',
            $rps->TomS->ender->cMun,
            true,
            'Cidade',
            false
        );
        self::$dom->addChild(
            $ender,
            'xMun',
            $rps->TomS->ender->xMun,
            true,
            'Cidade',
            false
        );
        self::$dom->addChild(
            $ender,
            'UF',
            $rps->TomS->ender->UF,
            true,
            'Estado',
            false
        );
        self::$dom->addChild(
            $ender,
            'CEP',
            $rps->TomS->ender->CEP,
            true,
            'Cep',
            false
        );
        self::$dom->addChild(
            $ender,
            'cPais',
            $rps->TomS->ender->cPais,
            true,
            'País',
            false
        );        
        self::$dom->addChild(
            $ender,
            'xPais',
            $rps->TomS->ender->xPais,
            true,
            'País',
            false
        );
        
        self::$dom->appChild($tomador, $ender, 'Adicionando tag Endereco do Prestador');
        //Fim endereço tomador        
        self::$dom->appChild($infRPS, $tomador, 'Adicionando tag Tomador em infRPS');        
        
        //Transportadora        
        $transportadora = self::$dom->createElement('transportadora');                        
        self::$dom->addChild(
            $transportadora,
            'xNomeTrans',
            $rps->transportadora->xNomeTrans,
            true,
            'Razao Social',
            false
        );
        self::$dom->addChild(
            $transportadora,
            'xCpfCnpjTrans',
            $rps->transportadora->xCpfCnpjTrans,
            true,
            'CPF ou CNPJ',
            false
        );
        self::$dom->addChild(
            $transportadora,
            'xInscEstTrans',
            $rps->transportadora->xInscEstTrans,
            true,
            'IE',
            false
        );
        self::$dom->addChild(
            $transportadora,
            'xPlacaTrans',
            $rps->transportadora->xPlacaTrans,
            true,
            'Placa',
            false
        );
        self::$dom->addChild(
            $transportadora,
            'xEndTrans',
            $rps->transportadora->xEndTrans,
            true,
            'Endereço',
            false
        );
        self::$dom->addChild(
            $transportadora,
            'cMunTrans',
            $rps->transportadora->cMunTrans,
            true,
            'Código Cidade',
            false
        );
        self::$dom->addChild(
            $transportadora,
            'xMunTrans',
            $rps->transportadora->xMunTrans,
            true,
            'Cidade',
            false
        );
        self::$dom->addChild(
            $transportadora,
            'xUfTrans',
            $rps->transportadora->xUfTrans,
            true,
            'UF',
            false
        );
        self::$dom->addChild(
            $transportadora,
            'cPaisTrans',
            $rps->transportadora->cPaisTrans,
            true,
            'País',
            false
        );
        self::$dom->addChild(
            $transportadora,
            'xPaisTrans',
            $rps->transportadora->xPaisTrans,
            true,
            'País',
            false
        );
        self::$dom->addChild(
            $transportadora,
            'vTipoFreteTrans',
            $rps->transportadora->vTipoFreteTrans,
            true,
            'Tipo frete',
            false
        );
        self::$dom->appChild($infRPS, $transportadora, 'Adicionando tag Transportadora em infRPS');        
        
        
        //Detalhamento dos serviços        
        foreach ($rps->det as $d) {            
            $det = self::$dom->createElement('det');                        
            self::$dom->addChild(
                $det,
                'nItem',
                $d->nItem,
                true,
                'Número do Item',
                false
            );      
                    
            //Serviço da NFS-e
            $serv = self::$dom->createElement('serv');
            self::$dom->addChild(
                $serv,
                'cServ',
                $rps->serv[$d->nItem]->cServ,
                true,
                'Código Municipal do serviço',
                false
            );
            self::$dom->addChild(
                $serv,
                'cLCServ',
                $rps->serv[$d->nItem]->cLCServ,
                true,
                'Código do Serviço',
                false
            );
            self::$dom->addChild(
                $serv,
                'xServ',
                $rps->serv[$d->nItem]->xServ,
                true,
                'Discriminação do Serviço',
                false
            );
            self::$dom->addChild(
                $serv,
                'localTributacao',
                $rps->serv[$d->nItem]->localTributacao,
                true,
                'Local tributação IBGE',
                false
            );
            self::$dom->addChild(
                $serv,
                'localVerifResServ',
                $rps->serv[$d->nItem]->localVerifResServ,
                true,
                'Local verificação do serviço',
                false
            );
            self::$dom->addChild(
                $serv,
                'uTrib',
                $rps->serv[$d->nItem]->uTrib,
                true,
                'Unidade',
                false
            );
            self::$dom->addChild(
                $serv,
                'qTrib',
                $rps->serv[$d->nItem]->qTrib,
                true,
                'Quantidade',
                false
            );
            self::$dom->addChild(
                $serv,
                'vUnit',
                $rps->serv[$d->nItem]->vUnit,
                true,
                'Valor unitário',
                false
            );
            self::$dom->addChild(
                $serv,
                'vServ',
                $rps->serv[$d->nItem]->vServ,
                true,
                'Valor do Serviço',
                false
            );
            self::$dom->addChild(
                $serv,
                'vDesc',
                $rps->serv[$d->nItem]->vDesc,
                true,
                'Desconto',
                false
            );
            self::$dom->addChild(
                $serv,
                'vBCISS',
                $rps->serv[$d->nItem]->vBCISS,
                true,
                'BaseISSQN',
                false
            );
            self::$dom->addChild(
                $serv,
                'pISS',
                $rps->serv[$d->nItem]->pISS,
                true,
                'ISS',
                false
            );
            self::$dom->addChild(
                $serv,
                'vISS',
                $rps->serv[$d->nItem]->vISS,
                true,
                'Valor iss',
                false
            );
            self::$dom->addChild(
                $serv,
                'vBCINSS',
                $rps->serv[$d->nItem]->vBCINSS,
                true,
                'Base INSS',
                false
            );
            self::$dom->addChild(
                $serv,
                'pRetINSS',
                $rps->serv[$d->nItem]->pRetINSS,
                true,
                'Retenção INSS',
                false
            );
            self::$dom->addChild(
                $serv,
                'vRetINSS',
                $rps->serv[$d->nItem]->vRetINSS,
                true,
                'Retenção INSS',
                false
            );
            self::$dom->addChild(
                $serv,
                'vRed',
                $rps->serv[$d->nItem]->vRed,
                true,
                'Valor redução ISS',
                false
            );
            self::$dom->addChild(
                $serv,
                'vBCRetIR',
                $rps->serv[$d->nItem]->vBCRetIR,
                true,
                'Retenção IR',
                false
            );
            self::$dom->addChild(
                $serv,
                'pRetIR',
                $rps->serv[$d->nItem]->pRetIR,
                true,
                '',
                false
            );
            self::$dom->addChild(
                $serv,
                'vRetIR',
                $rps->serv[$d->nItem]->vRetIR,
                true,
                '',
                false
            );
            self::$dom->addChild(
                $serv,
                'vBCCOFINS',
                $rps->serv[$d->nItem]->vBCCOFINS,
                true,
                'Base Cofins',
                false
            );
            self::$dom->addChild(
                $serv,
                'pRetCOFINS',
                $rps->serv[$d->nItem]->pRetCOFINS,
                true,
                'Retenção Cofins',
                false
            );
            self::$dom->addChild(
                $serv,
                'vRetCOFINS',
                $rps->serv[$d->nItem]->pRetCOFINS,
                true,
                '',
                false
            );
            self::$dom->addChild(
                $serv,
                'vBCCSLL',
                $rps->serv[$d->nItem]->vBCCSLL,
                true,
                'Base CSLL',
                false
            );
            self::$dom->addChild(
                $serv,
                'pRetCSLL',
                $rps->serv[$d->nItem]->pRetCSLL,
                true,
                '',
                false
            );
            self::$dom->addChild(
                $serv,
                'vRetCSLL',
                $rps->serv[$d->nItem]->vRetCSLL,
                true,
                '',
                false
            );
            self::$dom->addChild(
                $serv,
                'vBCPISPASEP',
                $rps->serv[$d->nItem]->vBCPISPASEP,
                true,
                '',
                false
            );
            self::$dom->addChild(
                $serv,
                'pRetPISPASEP',
                $rps->serv[$d->nItem]->pRetPISPASEP,
                true,
                '',
                false
            );
            self::$dom->addChild(
                $serv,
                'vRetPISPASEP',
                $rps->serv[$d->nItem]->vRetPISPASEP,
                true,
                '',
                false
            );
            self::$dom->addChild(
                $serv,
                'totalAproxTribServ',
                $rps->serv[$d->nItem]->totalAproxTribServ,
                true,
                '',
                false
            );        
            self::$dom->appChild($det, $serv, 'Adicionando tag Endereco do Prestador');    
            self::$dom->appChild($infRPS, $det, 'Adicionando tag Transportadora em infRPS');            
        }
        
         //Totais
        $total = self::$dom->createElement('total');                        
        self::$dom->addChild(
            $total,
            'vServ',
            $rps->total->vServ,
            true,
            'Valor Serviço',
            false
        );
        self::$dom->addChild(
            $total,
            'vRedBCCivil',
            $rps->total->vRedBCCivil,
            true,
            'Valor Serviço',
            false
        );
        self::$dom->addChild(
            $total,
            'vDesc',
            $rps->total->vDesc,
            true,
            'Valor Desconto',
            false
        );
        self::$dom->addChild(
            $total,
            'vtNF',
            $rps->total->vtNF,
            true,
            'Valor Nota',
            false
        );
        self::$dom->addChild(
            $total,
            'vtLiq',
            $rps->total->vtLiq,
            true,
            'Valor Total Liquido',
            false
        );
        //Serviço da NFS-e
        $ISS = self::$dom->createElement('ISS');
        self::$dom->addChild(
            $ISS,
            'vBCISS',
            $rps->serv->vBCISS,
            true,
            'Valor total da base cálculo ISSQN',
            false
        );
        self::$dom->addChild(
            $ISS,
            'vISS',
            $rps->serv->vISS,
            true,
            'Valor total ISS',
            false
        );
        
        self::$dom->appChild($total, $ISS, 'Adicionando tag ISS');
        self::$dom->appChild($infRPS, $total, 'Adicionando tag Total em infRPS');    
                

        self::$dom->appChild($root, $infRPS, 'Adicionando tag infRPS em RPS');
        self::$dom->appendChild($root);
        $xml = str_replace('<?xml version="1.0" encoding="utf-8"?>', '', self::$dom->saveXML());
        return $xml;
    }
}
