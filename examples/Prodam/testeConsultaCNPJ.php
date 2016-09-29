<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once '../../bootstrap.php';

use NFePHP\NFSe\NFSe;
use NFePHP\Common\Certificate;
use NFePHP\Common\Soap\SoapCurl;
use NFePHP\Common\Soap\SoapNative;
use NFePHP\NFSe\Models\Prodam\Response;

//tanto o config.json como o certificado.pfx podem estar
//armazenados em uma base de dados, então não é necessário 
///trabalhar com arquivos, estes abaixo servem apenas para 
//exemplos de desenvolvimento
$config = file_get_contents('../config/config.json');
$contentpfx = file_get_contents('/var/www/sped/sped-nfse/certs/certificado digital 27.07.16.pfx');

try {
    //com os dados do config e do certificado já obtidos e desconvertidos
    //a sua forma original e só passa-los para a classe 
    $nfse = new NFSe($config, Certificate::readPfx($contentpfx, 'prime2016'));
    //Aqui podemos escolher entre usar o SOAP nativo ou o cURL,
    //em ambos os casos os comandos são os mesmos pois observam
    //a mesma interface
    $nfse->tools->setSoapClass(new SoapCurl());
    
    
    $response = $nfse->tools->consultaCNPJ('08894935000170');
    
    
    /*
    $cnpj = '08894935000170';
    $cpf = '';
    $im = '36443573';
    $dtInicial = '2016-07-01';
    $dtFinal = '2016-08-01';
    $pagina = 1;
    $response = $nfse->tools->consultaNFSeEmitidas($cnpj, $cpf, $im, $dtInicial, $dtFinal, $pagina);
    echo "<pre>";
    print_r($response);
    echo "</pre>";
    die;
     * 
     */
    /*
    $cnpj = '08894935000170';
    $cpf = '';
    $im = '36443573';
    $dtInicial = '2016-07-01';
    $dtFinal = '2016-07-31';
    $pagina = 1;    
    $response = $nfse->tools->consultaNFSeRecebidas($cnpj, $cpf, $im, $dtInicial, $dtFinal, $pagina);
    echo "<pre>";
    print_r(Response::readReturn('RetornoXML', $response));
    echo "</pre>";
    die;
    */
    //$response = $nfse->tools->consultaInformacoesLote('39616924', '13456');
    //$response = $nfse->tools->consultaLote('221');
        
    //$request = $nfse->tools->consultaNFSe(
    //    [0=>['prestadorIM'=>'39616924','numeroNFSe'=>'222']],
    //    [0=>['prestadorIM'=>'39616924','serieRPS'=>'1', 'numeroRPS'=>'1234']]
    //);
    
    
    
    
    
    //$rpss = $nfse->convert->toRps('./rps.txt');
    //$response = $nfse->tools->envioRPS($rpss[0]);
    //$response = $nfse->tools->envioLoteRPS($rpss);
    //$response = $nfse->tools->testeEnvioLoteRPS($rpss);
    //$response = $nfse->tools->cancelamentoNFSe('39616924', '22');
    
    //file_put_contents('consultaemitidas.xml', $response);
    header("Content-type: text/xml");
    echo $response;
    
    //echo "<pre>";
    //print_r($response);
    //echo "</pre>";
    //echo $response->RetornoConsultaCNPJ->Cabecalho->attributes->Versao.'<BR>';
    //echo $response->RetornoConsultaCNPJ->Cabecalho->Sucesso.'<BR>';
    //echo $response->RetornoConsultaCNPJ->Detalhe->InscricaoMunicipal.'<BR>';
    //echo $response->RetornoConsultaCNPJ->Detalhe->EmiteNFe.'<BR>';
    
} catch (\NFePHP\Common\Exception\SoapException $e) {
    echo $e->getMessage();
} catch (NFePHP\Common\Exception\CertificateException $e) {
    echo $e->getMessage();
} catch (Exception $e) {
    echo $e->getMessage();
}