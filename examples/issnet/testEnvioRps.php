<?php
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 'On');
require_once '../../bootstrap.php';

use NFePHP\NFSe\Models\Issnet\Rps;
use NFePHP\NFSe\NFSe;
use NFePHP\Common\Certificate;
use NFePHP\Common\Soap\SoapCurl;
use NFePHP\Common\Soap\SoapNative;
use NFePHP\NFSe\Models\Issnet\Response;

$arr = [
    "atualizacao" => "2016-08-03 18:01:21",
    "tpAmb" => 1,
    "versao" => 1,
    "razaosocial" => "SUA RAZAO SOCIAL LTDA",
    "cnpj" => "99999999999999",
    "cpf" => "",
    "im" => "99999999",
    "cmun" => "5103403",
    "siglaUF" => "SP",
    "pathNFSeFiles" => "/dados/nfse",
    "proxyConf" => [
        "proxyIp" => "",
        "proxyPort" => "",
        "proxyUser" => "",
        "proxyPass" => ""
    ]    
];
$configJson = json_encode($arr);
$contentpfx = file_get_contents('/var/www/sped/sped-nfse/certs/certificado.pfx');

try {

    $nfse = new NFSe($config, Certificate::readPfx($contentpfx, 'senha'));
    //use o cURL 
    $nfse->tools->setSoapClass(new SoapCurl());
    
    //Construção do RPS
    $rps = new Rps();
    $rps->prestador(
            2,
            '08111879000122',
            '13541'
    );
    $rps->tomador(1, '00531933333', '', 'CICLANO DETAL', '3569877','ciclano.tal@gmail.com');
    $rps->tomadorEndereco(
        'Rua Treze',
        '222',
        'sala 11',
        'Centro',
        '5103403',
        'MT',
        '78000109'
    );
    $rps->intermediario(2, '99999999999999', '222222', 'Teste');
    $rps->numero(1);
    $rps->serie('1');
    $rps->status($rps::STATUS_NORMAL);
    $rps->tipo($rps::TIPO_RPS);
    $rps->dataEmissao(new \DateTime());
    $rps->municipioPrestacaoServico(5103403);
    $rps->naturezaOperacao($rps::NATUREZA_INTERNA);
    $rps->itemListaServico(802);
    $rps->codigoCnae(8599601);
    $rps->codigoTributacaoMunicipio(8599601);
    $rps->discriminacao('REFERENTE PRESTAÇÃO DE SERVIÇO');
    $rps->rpsSubstituido('5555', 'A1', 1);
    $rps->regimeEspecialTributacao($rps::REGIME_MICROEMPRESA);
    $rps->optanteSimplesNacional($rps::SIM);
    $rps->incentivadorCultural($rps::NAO);
    $rps->issRetido($rps::NAO);
    $rps->aliquota(3.5);
    $rps->valorServicos(1695.00);
    $rps->valorDeducoes(0.00);
    $rps->outrasRetencoes(0.00);
    $rps->descontoCondicionado(0.00);
    $rps->descontoIncondicionado(0.00);
    //(Valor dos serviços - Valor das deduções - descontos incondicionados)
    $rps->baseCalculo(1695.00);
    $rps->valorIss(59.33);
    $rps->valorPis(0.00);
    $rps->valorCofins(0.00);
    $rps->valorCsll(0.00);
    $rps->valorInss(0.00);
    $rps->valorIr(0.00);
    //(ValorServicos - ValorPIS - ValorCOFINS - ValorINSS - ValorIR - ValorCSLL - OutrasRetençoes - ValorISSRetido - DescontoIncondicionado - DescontoCondicionado)
    $rps->valorLiquidoNfse(1695.00);
    $rps->construcaoCivil('1234', '234-4647-aa');
    
    //envio do RPS
    $response = $nfse->tools->recepcionarLoteRps(1, [$rps]);
    
    //apresentação do retorno
    header("Content-type: text/xml");
    echo $response;
    
} catch (\NFePHP\Common\Exception\SoapException $e) {
    echo $e->getMessage();
} catch (NFePHP\Common\Exception\CertificateException $e) {
    echo $e->getMessage();
} catch (Exception $e) {
    echo $e->getMessage();
}    
