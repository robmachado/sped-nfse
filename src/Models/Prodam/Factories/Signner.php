<?php

namespace NFePHP\NFSe\Models\Prodam\Factories;

class Signner
{
    public static function sign($data, $priKey)
    {
        $signatureValue = '';
        $pkeyId = openssl_get_privatekey($priKey);
        openssl_sign($data, $signatureValue, $pkeyId, OPENSSL_ALGO_SHA1);
        openssl_free_key($pkeyId);
        return base64_encode($signatureValue);
    }
}
