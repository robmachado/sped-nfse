<?php

namespace NFePHP\NFSe\Models\Prodam\Factories;

/**
 * Classe para a assinar uma string conforme o modelo Prodam
 *
 * @category  NFePHP
 * @package   NFePHP\NFSe\Models\Prodam\Factories\Signner
 * @copyright NFePHP Copyright (c) 2016
 * @license   http://www.gnu.org/licenses/lgpl.txt LGPLv3+
 * @license   https://opensource.org/licenses/MIT MIT
 * @license   http://www.gnu.org/licenses/gpl.txt GPLv3+
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 * @link      http://github.com/nfephp-org/sped-nfse for the canonical source repository
 */

class Signner
{
    /**
     * Assina uma string usando a chave privada e converte o resultado para base64
     * @param string $data
     * @param string $priKey
     * @param int $algorithm
     * @return string
     */
    public static function sign($data = '', $priKey = '', $algorithm = OPENSSL_ALGO_SHA1)
    {
        if ($data == '' || $priKey == '') {
            return '';
        }
        $signatureValue = '';
        $pkeyId = openssl_get_privatekey($priKey);
        openssl_sign($data, $signatureValue, $pkeyId, $algorithm);
        openssl_free_key($pkeyId);
        return base64_encode($signatureValue);
    }
}
