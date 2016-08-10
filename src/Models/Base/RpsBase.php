<?php

namespace NFePHP\NFSe\Models\Base;

/**
 * Classe base para a construção do xml da NFSe e RPS
 *
 * @category  NFePHP
 * @package   NFePHP\NFSe\Base\RpsBase
 * @copyright NFePHP Copyright (c) 2016
 * @license   http://www.gnu.org/licenses/lgpl.txt LGPLv3+
 * @license   https://opensource.org/licenses/MIT MIT
 * @license   http://www.gnu.org/licenses/gpl.txt GPLv3+
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 * @link      http://github.com/nfephp-org/sped-nfse for the canonical source repository
 */

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
    }
    
    protected function zValidData($array, $data)
    {
        return array_key_exists($data, $array);
    }
}
