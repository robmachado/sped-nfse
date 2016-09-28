<?php

namespace NFePHP\NFSe\Common;

/**
 * Classe base para tratar os retornos das consultas aos webservices
 *
 * @category  NFePHP
 * @package   NFePHP\NFSe\Common\Response
 * @copyright NFePHP Copyright (c) 2016
 * @license   http://www.gnu.org/licenses/lgpl.txt LGPLv3+
 * @license   https://opensource.org/licenses/MIT MIT
 * @license   http://www.gnu.org/licenses/gpl.txt GPLv3+
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 * @link      http://github.com/nfephp-org/sped-nfse for the canonical source repository
 */

use NFePHP\NFSe\Common\EntitiesCharacters;
use DOMDocument;
use stdClass;

class Response
{
    public static function readReturn($tag, $response, $withcdata = false)
    {
        if (trim($response) == '') {
            //throw
        }
        libxml_use_internal_errors(true);
        $dom = new DOMDocument('1.0', 'utf-8');
        $dom->loadXML($response);
        $errors = libxml_get_errors();
        libxml_clear_errors();
        if (! empty($errors)) {
            //throw
        }
        //foi retornado um xml continue
        $reason = self::checkForFault($dom);
        if ($reason != '') {
            //throw
        }
        //converte o xml em uma stdClass
        return self::xml2Obj($dom, $tag);
    }
    
    /**
     * Convert DOMDocument in stdClass
     * @param DOMDocument $dom
     * @param string $tag
     * @return \stdClass
     */
    protected static function xml2Obj(DOMDocument $dom, $tag)
    {
        $node = $dom->getElementsByTagName($tag)->item(0);
        $newdoc = new DOMDocument('1.0', 'utf-8');
        $newdoc->appendChild($newdoc->importNode($node, true));
        $xml = $newdoc->saveXML();
        $newdoc = null;
        $xml = str_replace('<?xml version="1.0" encoding="UTF-8"?>', '', $xml);
        $xml = str_replace('<?xml version="1.0" encoding="utf-8"?>', '', $xml);
        $xml = str_replace('&lt;?xml version="1.0" encoding="UTF-8"?&gt;', '', $xml);
        $xml = EntitiesCharacters::convert(html_entity_decode($xml));
        $resp = simplexml_load_string($xml, null, LIBXML_NOCDATA);
        $std = json_encode($resp);
        $std = str_replace('@attributes', 'attributes', $std);
        $std = json_decode($std);
        return $std;
    }

    /**
     * Verifica se o retorno é relativo a um ERRO SOAP
     * @param DOMDocument $dom
     * @return string
     */
    protected static function checkForFault(DOMDocument $dom)
    {
        $tagfault = $dom->getElementsByTagName('Fault')->item(0);
        if (empty($tagfault)) {
            return '';
        }
        $tagreason = $tagfault->getElementsByTagName('Reason')->item(0);
        if (! empty($tagreason)) {
            $reason = $tagreason->getElementsByTagName('Text')->item(0)->nodeValue;
            return $reason;
        }
        return 'Houve uma falha na comunicação.';
    }
}
