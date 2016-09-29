<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once '../../bootstrap.php';

use NFePHP\NFSe\NFSe;
use NFePHP\Common\Certificate;
use NFePHP\Common\Soap\SoapCurl;
use NFePHP\Common\Soap\SoapNative;

//Para cada Prefeitura 

//tanto o config.json como o certificado.pfx podem estar
//armazenados em uma base de dados, então não é necessário 
///trabalhar com arquivos, estes abaixo servem apenas para 
//exemplos de desenvolvimento
$arr = [
    "atualizacao" => "2016-08-03 18:01:21",
    "tpAmb" => 1,
    "versao" => 1,
    "razaosocial" => "SUA RAZAO SOCIAL LTDA",
    "cnpj" => "99999999999999",
    "cpf" => "",
    "im" => "99999999",
    "cmun" => "3550308",
    "siglaUF" => "SP",
    "pathNFSeFiles" => "\/dados\/nfse",
    "proxyConf" => [
        "proxyIp" => "",
        "proxyPort" => "",
        "proxyUser" => "",
        "proxyPass" => ""
    ]    
];
$configJson = json_encode($arr);
//esse certificado pode estar em uma base de dados para isso não esqueça de converter para base64
//ao gravar na base e desconverter para usar
$contentpfx = file_get_contents('/var/www/sped/sped-nfse/certs/certificado.pfx');

try {
    //com os dados do config e do certificado já obtidos e descompactados e desconvertidos
    //a sua forma original e só passa-los para a classe 
    $nfse = new NFSe($configJson, Certificate::readPfx($contentpfx, 'senha'));
    
    //Aqui podemos escolher entre usar o SOAP nativo ou o cURL,
    //em ambos os casos os comandos são os mesmos pois observam
    //a mesma interface
    $nfse->tools->setSoapClass(new SoapCurl());
    
    //aqui está o comando para a consulta do CNPJ
    $response = $nfse->tools->consultaCNPJ('08894935000170');
    //será retornado o XML de resposta do webservice
    
    //mostra o xml retornado
    header("Content-type: text/xml");
    echo $response;
    
    //esse XML poderá ser convertido em uma stdClass para facilitar a extração dos 
    //dados para uso da aplicação
    //para isso usamos a classe Response::readReturn($tag, $response)
    //passando o nome da tag desejada, e o xml 
    $responseClass = $nfse->response->readReturn('RetornoConsultaCNPJ', $response);
    
} catch (\NFePHP\Common\Exception\SoapException $e) {
    echo $e->getMessage();
} catch (NFePHP\Common\Exception\CertificateException $e) {
    echo $e->getMessage();
} catch (Exception $e) {
    echo $e->getMessage();
}