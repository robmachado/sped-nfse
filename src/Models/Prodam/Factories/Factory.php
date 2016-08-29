<?php

namespace NFePHP\NFSe\Models\Prodam\Factories;

/**
 * Classe base para a construção dos XMLs relativos ao serviços
 * dos webservices da Cidade de São Paulo conforme o modelo Prodam
 *
 * @category  NFePHP
 * @package   NFePHP\NFSe\Models\Prodam\Factories\Factory
 * @copyright NFePHP Copyright (c) 2016
 * @license   http://www.gnu.org/licenses/lgpl.txt LGPLv3+
 * @license   https://opensource.org/licenses/MIT MIT
 * @license   http://www.gnu.org/licenses/gpl.txt GPLv3+
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 * @link      http://github.com/nfephp-org/sped-nfse for the canonical source repository
 */

use NFePHP\NFSe\Models\Factory as FactoryBase;

class Factory extends FactoryBase
{
    protected $xmlns= "http://www.prefeitura.sp.gov.br/nfe";
    protected $pathSchemes = '../../schemes/Prodam/';
}
