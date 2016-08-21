<?php

namespace NFePHP\NFSe\Models\Prodam\Factories;

class Signner
{
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
