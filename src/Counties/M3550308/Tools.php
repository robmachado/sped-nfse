<?php

namespace NFePHP\NFSe\Counties\M3550308;

/**
 * Classe para a comunicação com os webservices da
 * Cidade de São Paulo SP
 * conforme o modelo PRODAM
 *
 * @category  NFePHP
 * @package   NFePHP\NFSe\Counties\M3550308\Tools
 * @copyright NFePHP Copyright (c) 2016
 * @license   http://www.gnu.org/licenses/lgpl.txt LGPLv3+
 * @license   https://opensource.org/licenses/MIT MIT
 * @license   http://www.gnu.org/licenses/gpl.txt GPLv3+
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 * @link      http://github.com/nfephp-org/sped-nfse for the canonical source repository
 */

use NFePHP\NFSe\Models\Prodam\Tools as ToolsProdam;

class Tools extends ToolsProdam
{
    /**
     * Endereços dos webservices
     * @var array
     */
    protected $url = [
        '2' => 'https://testenfe.prefeitura.sp.gov.br/ws/lotenfe.asmx',
        '1' => 'https://nfe.prefeitura.sp.gov.br/ws/lotenfe.asmx'
    ];
    /**
     * County Namespace
     * @var string
     */
    protected $xmlns= "http://www.prefeitura.sp.gov.br/nfe";
    
}
