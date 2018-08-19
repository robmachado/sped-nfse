<?php
error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', 'On');
require_once '../../bootstrap.php';

use NFePHP\NFSe\NFSe;
use NFePHP\Common\Certificate;
use NFePHP\Common\Soap\SoapCurl;

$arr = [
    "atualizacao" => "2016-08-03 18:01:21",
    "tpAmb" => 2,
    "versao" => 1,
    "razaosocial" => "TRANSPORTES EXPRESSO 24 HORAS EIRELI ME",
    "cnpj" => "11770928000171",
    "cpf" => "",
    "im" => "",
    "cmun" => "4305108", //CAXIAS DO SUL
    "siglaUF" => "RS",
    "pathNFSeFiles" => "/dados/nfse",
    "proxyConf" => [
        "proxyIp" => "",
        "proxyPort" => "",
        "proxyUser" => "",
        "proxyPass" => ""
    ]
];

$configJson = json_encode($arr);
$contentpfx = file_get_contents('/var/www/transweb_resources/stores/11770928000171/certs/certificado11770928000171.pfx');

try {
    //com os dados do config e do certificado já obtidos e desconvertidos
    //a sua forma original e só passa-los para a classe 
    $nfse = new NFSe($configJson, Certificate::readPfx($contentpfx, '123456'));
    //Por ora apenas o SoapCurl funciona com IssNet
    $nfse->tools->loadSoapClass(new SoapCurl());
    //caso o mode debug seja ativado serão salvos em arquivos 
    //a requisicção SOAP e a resposta do webservice na pasta de 
    //arquivos temporarios do SO em sub pasta denominada "soap"
    $nfse->tools->setDebugSoapMode(true);

    $chave = '431177092800017198S00000000101201808101';
    $content = $nfse->tools->pedidoNFSe($chave);
    $response = $nfse->response->readReturn('return', $content);
//    echo $response->confirmaLote->sit;
//    echo $response->confirmaLote->mot;
//    echo "<pre>";
//    print_r($response);
//    echo "</pre>";
    //header("Content-type: text/xml");
    //echo $content;
    $dom = new DOMDocument('1.0', 'utf-8');
    $dom->loadXML($content);
    $node = $dom->getElementsByTagName('return')->item(0);
    $newdoc = new DOMDocument('1.0', 'utf-8');
    $newdoc->appendChild($newdoc->importNode($node, true));
    $xml = $newdoc->saveXML();
    header("Content-type: text/xml");
    echo $xml;
} catch (\NFePHP\Common\Exception\SoapException $e) {
    echo $e->getMessage();
} catch (NFePHP\Common\Exception\CertificateException $e) {
    echo $e->getMessage();
} catch (Exception $e) {
    echo $e->getMessage();
}