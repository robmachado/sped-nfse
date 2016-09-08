<?php

namespace NFePHP\NFSe\Counties\M3509502;

/**
 * Classe para a comunicação com os webservices da
 * Cidade de Campinas SP
 * conforme o modelo DSFNET
 *
 * @category  NFePHP
 * @package   NFePHP\NFSe\Counties\M3509502\Tools
 * @copyright NFePHP Copyright (c) 2016
 * @license   http://www.gnu.org/licenses/lgpl.txt LGPLv3+
 * @license   https://opensource.org/licenses/MIT MIT
 * @license   http://www.gnu.org/licenses/gpl.txt GPLv3+
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 * @link      http://github.com/nfephp-org/sped-nfse for the canonical source repository
 */

use NFePHP\NFSe\Models\Dsfnet\Tools as ToolsDsfnet;

class Tools extends ToolsDsfnet
{
    /**
     * Webservices URL
     * @var array
     */
    protected $url = [
        1 => 'http://issdigital.campinas.sp.gov.br/WsNFe2/LoteRps.jws',
        2 => ''
    ];
    /**
     * County Namespace
     * @var string
     */
    protected $xmlns = 'http://proces.wsnfe2.dsfnet.com.br';
    /**
     * Soap Version
     * @var int
     */
    protected $soapversion = 1;
    /**
     * Soap port
     * @var int
     */
    protected $soapport = 443;
    /**
     * SIAFI County Cod
     * @var int
     */
    protected $codcidade = 6291;
    /**
     * Indicates when use CDATA string on message
     * @var boolean
     */
    protected $withcdata = false;
    /**
     * Encription signature algorithm
     * @var string
     */
    protected $signaturealgo = 'SHA1';
}
