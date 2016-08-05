<?php

namespace NFePHP\NFSe;

/**
 * Classe para a instanciação das classes espcificas de cada municipio
 * atendido pela API
 *
 * @category  NFePHP
 * @package   NFePHP\NFSe\County
 * @copyright NFePHP Copyright (c) 2016
 * @license   http://www.gnu.org/licenses/lgpl.txt LGPLv3+
 * @license   https://opensource.org/licenses/MIT MIT
 * @license   http://www.gnu.org/licenses/gpl.txt GPLv3+
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 * @link      http://github.com/nfephp-org/sped-nfse for the canonical source repository
 */

use NFePHP\NFSe\Counties;
use RuntimeException;

class County
{
    /**
     * Instancia a classe usada na construção do RPS
     * para um municipio em particular
     *
     * @param string $config
     * @return \NFePHP\NFSe\Class
     */
    public static function rps($config = '')
    {
        $className = self::getClassName($config, 'Rps');
        return self::classCheck($className, $config);
    }

    /**
     * Instancia a classe usada na comunicação com o webservice
     * para um municipio em particular
     *
     * @param string $config
     * @return \NFePHP\NFSe\Class
     */
    public static function tools($config = '')
    {
        $className = self::getClassName($config, 'T');
        return self::classCheck($className, $config);
    }
    
    /**
     * Monta o nome das classes referentes a determinado municipio
     *
     * @param string $config
     * @param string $complement
     * @return string
     */
    private static function getClassName($config, $complement)
    {
        $configJson = $config;
        if (is_file($config)) {
            $configJson = file_get_contents($config);
        }
        $conf = json_decode($configJson);
        return "\NFePHP\NFSe\Counties\\$complement".$conf->cmun;
    }
    
    /**
     * Instancia e retorna a classe desejada
     *
     * @param string $className
     * @param string $config
     * @return \NFePHP\NFSe\className
     * @throws RuntimeException
     */
    private static function classCheck($className, $config)
    {
        if (class_exists($className)) {
            return new $className($config);
        } else {
            $msg = 'Este municipio não é atendido pela API.';
            throw new RuntimeException($msg);
        }
    }
}
