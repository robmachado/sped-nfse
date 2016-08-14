<?php
namespace NFePHP\NFSe;

/**
 * Classe para a instanciação das classes espcificas de cada municipio
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
use RuntimeException;

class NFSe2
{
    
    protected $configJson;
    public $cMun;
    public $rps;
    public $convert;
    public $tools;
    
    public function __construct($config)
    {
        $configJson = $config;
        if (is_file($config)) {
            $this->configJson = file_get_contents($config);
        }
        $conf = json_decode($this->configJson);
        $this->cMun = $conf->cmun;
        $this->convert = $this->convertClass();
        $this->rps = $this->rpsClass();
        $this->tools = $this->toolsClass();
    }
    
    /**
     * Instancia a classe usada na conversão dos arquivos txt em RPS
     * @param string $config
     * @return \NFePHP\NFSe\className
     */
    public function convertClass()
    {
        $className = $this->getClassName('Convert');
        return $this->classCheck($className, '');
    }
    
    /**
     * Instancia a classe usada na construção do RPS
     * para um municipio em particular
     *
     * @param string $config
     * @return \NFePHP\NFSe\className
     */
    public function rpsClass()
    {
        $className = $this->getClassName('Rps');
        return $this->classCheck($className, '');
    }

    /**
     * Instancia a classe usada na comunicação com o webservice
     * para um municipio em particular
     *
     * @param string $config
     * @return \NFePHP\NFSe\className
     */
    public function toolsClass()
    {
        $className = $this->getClassName('Tools');
        return $this->classCheck($className, $this->configJson);
    }
    
    /**
     * Monta o nome das classes referentes a determinado municipio
     *
     * @param string $complement
     * @return string
     */
    private function getClassName($complement)
    {
        return "\NFePHP\NFSe\Counties\\$complement". $this->cMun;
    }
    
    /**
     * Instancia e retorna a classe desejada
     *
     * @param string $className
     * @param string $config
     * @return \NFePHP\NFSe\className
     * @throws RuntimeException
     */
    private function classCheck($className, $config = '')
    {
        $flag = class_exists($className);
        if ($flag) {
            return new $className($config);
        } else {
            $msg = 'Este municipio não é atendido pela API.';
            throw new RuntimeException($msg);
        }
    }
}
