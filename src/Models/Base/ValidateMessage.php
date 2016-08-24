<?php

namespace NFePHP\NFSe\Models\Base;

use \DOMDocument;

class ValidateMessage
{
    public static $errors = [];
    
    public static function validate($xml = '', $xsd = '')
    {
        if (empty($xml) || empty($xsd)) {
            return false;
        }
        libxml_use_internal_errors(true);
        libxml_clear_errors();
        $dom = new DOMDocument('1.0', 'utf-8');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = false;
        $dom->loadXML($xml, LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG);
        libxml_clear_errors();
        if (! $dom->schemaValidate($xsd)) {
            $aIntErrors =   libxml_get_errors();
            foreach ($aIntErrors as $intError) {
                self::$errors[] = $intError->message . "\n";
            }
            return false;
        }
        return true;
    }
}
