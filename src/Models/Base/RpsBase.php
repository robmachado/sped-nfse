<?php

namespace NFePHP\NFSe\Models\Base;

use InvalidArgumentException;
use NFePHP\Common\Strings\Strings;

class RpsBase
{
    public $versao = 1;
    protected $priKey = '';
    
    public function __construct($config)
    {
        $configJson = $config;
        if (is_file($config)) {
            $configJson = file_get_contents($config);
        }
        $conf = json_decode($configJson);
        $this->remetenteRazao = Strings::cleanString($conf->razaosocial);
        $this->remetenteCNPJ = $conf->cnpj;
        $this->remetenteCPF = $conf->cpf;
        $this->remetenteIM = $conf->im;
        $this->remetenteCMun = $conf->cmun;
        $this->remetenteCertPath = $conf->certPath;
        $this->remetenteCertPfx = $conf->certPfx;
        $this->remetenteCertPass = $conf->certPass;
        $this->remetenteCertPhrase = $conf->certPhrase;
        $this->versao = $conf->versao;
        $mark = $conf->cnpj;
        if ($conf->cnpj == '') {
            $mark = $conf->cpf;
        }
        $priKeyFile = $conf->certPath . $mark . '_priKEY.pem';
        if (is_file($priKeyFile)) {
            $this->priKey = file_get_contents($priKeyFile);
        }
    }
    
    protected function zValidData($array, $data)
    {
        return array_key_exists($data, $array);
    }
}
