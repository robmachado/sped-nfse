<?php

namespace NFePHP\NFSe\Tests;

class NFSeTestCase extends \PHPUnit_Framework_TestCase
{
    public $fixturesPath = '';
    public $configJson = '';
    public $configJsonFail = '';
    
    public function __construct()
    {
        $this->fixturesPath = dirname(__FILE__) . '/fixtures/';
        
        $config = [
            "atualizacao" => "2016-08-03 18:01:21",
            "tpAmb" => 2,
            "versao" => 1,
            "razaosocial" => "Sua empresa ltda",
            "cnpj" => "99999090910270",
            "cpf" => "",
            "im" => "39111111",
            "cmun" => "3550308",
            "siglaUF" => "SP",
            "siteUrl" => "http://seusiteurl.com.br",
            "pathNFSeFiles" => "/dados/nfse",
            "pathCertsFiles" => $this->fixturesPath . "certs/",
            "certPfxName" => "certificado_teste.pfx",
            "certPassword" => "associacao",
            "certPhrase" => "",
            "aDocFormat" => [
                "format" =>"P",
                "paper" => "A4",
                "southpaw" => "1",
                "pathLogoFile" => $this->fixturesPath . "/images/logo.jpg",
                "logoPosition" => "L",
                "font" => "Times",
                "printer" => "hpteste"
            ],
            "aMailConf" => [
                "mailAuth" => "1",
                "mailFrom" => "seunome@seudominio.com.br",
                "mailSmtp" => "smpt,seudominio.com.br",
                "mailUser" => "seunome@seudominio.com.br",
                "mailPass" => "senha",
                "mailProtocol" => "ssl",
                "mailPort" => "465",
                "mailFromMail" => "seunome@seudominio.com.br",
                "mailFromName" => "Seu Nome",
                "mailReplayToMail" => "seunome@seudominio.com.br",
                "mailReplayToName" => "Seu Nome",
                "mailImapHost" => null,
                "mailImapPort" => null,
                "mailImapSecurity" => null,
                "mailImapNocerts" => null,
                "mailImapBox" => null
            ],
            "aProxyConf" => [
                "proxyIp" => "",
                "proxyPort" => "",
                "proxyUser" => "",
                "proxyPass" => ""
            ]    
        ];
        
        $this->configJson = json_encode($config);
        $config['cmun'] = '1702554';
        $this->configJsonFail = json_encode($config);
    }
}
