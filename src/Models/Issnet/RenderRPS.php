<?php

namespace NFePHP\NFSe\Models\Issnet;

/**
 * Classe para a renderização dos RPS em XML
 * conforme o modelo ISSNET
 *
 * @category  NFePHP
 * @package   NFePHP\NFSe\Models\Issnet\RenderRPS
 * @copyright NFePHP Copyright (c) 2016
 * @license   http://www.gnu.org/licenses/lgpl.txt LGPLv3+
 * @license   https://opensource.org/licenses/MIT MIT
 * @license   http://www.gnu.org/licenses/gpl.txt GPLv3+
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 * @link      http://github.com/nfephp-org/sped-nfse for the canonical source repository
 */

use NFePHP\Common\Dom\Dom;
use NFePHP\NFSe\Models\Issnet\Rps;
use NFePHP\Common\Certificate;

class RenderRPS
{
    protected static $dom;
    protected static $certificate;
    protected static $algorithm;

    public static function toXml($data, Certificate $certificate, $algorithm = OPENSSL_ALGO_SHA1)
    {
        self::$certificate = $certificate;
        self::$algorithm = $algorithm;
        $xml = '';
        if (is_object($data)) {
            return self::render($data);
        } elseif (is_array($data)) {
            foreach ($data as $rps) {
                $xml .= self::render($rps);
            }
        }
        return $xml;
    }
    
    /**
     * Monta o xml com base no objeto Rps
     * @param Rps $rps
     * @return string
     */
    private static function render(Rps $rps)
    {
    }
}
