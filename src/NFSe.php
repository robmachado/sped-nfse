<?php
namespace NFePHP\NFSe;

/**
 * Classe para a instanciação das classes especificas de cada municipio
 * atendido pela API
 *
 * @category  NFePHP
 * @package   NFePHP\NFSe\NFSe
 * @copyright NFePHP Copyright (c) 2016
 * @license   http://www.gnu.org/licenses/lgpl.txt LGPLv3+
 * @license   https://opensource.org/licenses/MIT MIT
 * @license   http://www.gnu.org/licenses/gpl.txt GPLv3+
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 * @link      http://github.com/nfephp-org/sped-nfse for the canonical source repository
 */

use NFePHP\NFSe\Counties;
use NFePHP\NFSe\NFSeStatic;
use RuntimeException;

class NFSe
{
    protected $configJson;
    public $cMun;
    public $rps;
    public $convert;
    public $tools;
    public $pkcs;
    
    /**
     * Construtor da classe
     * @param string $config
     */
    public function __construct($config)
    {
        $configJson = $config;
        if (is_file($config)) {
            $this->configJson = file_get_contents($config);
        }
        $conf = json_decode($this->configJson);
        $this->cMun = $conf->cmun;
        $this->convert = NFSeStatic::convert($this->configJson);
        $this->rps = NFSeStatic::rps($this->configJson);
        $this->tools = NFSeStatic::tools($this->configJson);
    }
}
