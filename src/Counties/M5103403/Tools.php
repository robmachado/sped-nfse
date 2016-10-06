<?php

namespace NFePHP\NFSe\Counties\M5103403;

/**
 * Classe para a comunicação com os webservices da
 * Cidade de Cuiabá MS
 * conforme o modelo ISSNET
 *
 * @category  NFePHP
 * @package   NFePHP\NFSe\Counties\M5103403\Tools
 * @copyright NFePHP Copyright (c) 2016
 * @license   http://www.gnu.org/licenses/lgpl.txt LGPLv3+
 * @license   https://opensource.org/licenses/MIT MIT
 * @license   http://www.gnu.org/licenses/gpl.txt GPLv3+
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 * @link      http://github.com/nfephp-org/sped-nfse for the canonical source repository
 */

use NFePHP\NFSe\Models\Issnet\Tools as ToolsModel;

class Tools extends ToolsModel
{
    /**
     * Webservices URL
     * @var array
     */
    protected $url = [
        1 => '',
        2 => ''
    ];
    /**
     * County Namespace
     * @var string
     */
    protected $xmlns = '';
    
    /**
     * Soap Version
     * @var int
     */
    protected $soapversion = SOAP_1_2;
    /**
     * SIAFI County Cod
     * @var int
     */
    protected $codcidade = 0;
    /**
     * Indicates when use CDATA string on message
     * @var boolean
     */
    protected $withcdata = false;
    /**
     * Encription signature algorithm
     * @var string
     */
    protected $algorithm = OPENSSL_ALGO_SHA1;
    /**
     * Version of schemas
     * @var int
     */
    protected $versao = 1;
    /**
     * namespaces for soap envelope
     * @var array
     */
    protected $namespaces = [
        1 => [
        ],
        2  => [
        ]
    ];
}
