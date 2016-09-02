<?php

namespace NFePHP\NFSe\Counties\M3304557;

/**
 * Classe para a comunicação com os webservices da
 * Cidade do Rio de Janeiro RJ
 * conforme o modelo ABRASF
 *
 * @category  NFePHP
 * @package   NFePHP\NFSe\Counties\M3304557\Tools
 * @copyright NFePHP Copyright (c) 2016
 * @license   http://www.gnu.org/licenses/lgpl.txt LGPLv3+
 * @license   https://opensource.org/licenses/MIT MIT
 * @license   http://www.gnu.org/licenses/gpl.txt GPLv3+
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 * @link      http://github.com/nfephp-org/sped-nfse for the canonical source repository
 */

use NFePHP\NFSe\Models\Abrasf\Tools as ToolsAbrasf;

class Tools extends ToolsAbrasf
{
    /**
     * Webservices URL
     * @var array
     */
    protected $url = [
        1 => [
            'EnvioLoteRPS'=>"",
            'ConsultaSituacaoLoteRPS'=>"",
            'ConsultaLoteRPS'=>"",
            'ConsultaNfseRPS'=>"",
            'ConsultaNFse'=>""
        ],
        2 => [
            'EnvioLoteRPS'=>"",
            'ConsultaSituacaoLoteRPS'=>"",
            'ConsultaLoteRPS'=>"",
            'ConsultaNfseRPS'=>"",
            'ConsultaNFse'=>""
        ]
    ];
    
     /**
     * County Namespace
     * @var string
     */
    protected $xmlns= "";
    /**
     * Soap Version
     * @var int
     */
    protected $soapversion = 2;
}
