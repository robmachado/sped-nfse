<?php

namespace NFePHP\NFSe\Models\Dsfnet\Factories;

/**
 * Classe base para a construção dos XMLs relativos ao serviços
 * dos webservices conforme o modelo Dsfnet
 *
 * @category  NFePHP
 * @package   NFePHP\NFSe\Models\Dsfnet\Factories\Factory
 * @copyright NFePHP Copyright (c) 2016
 * @license   http://www.gnu.org/licenses/lgpl.txt LGPLv3+
 * @license   https://opensource.org/licenses/MIT MIT
 * @license   http://www.gnu.org/licenses/gpl.txt GPLv3+
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 * @link      http://github.com/nfephp-org/sped-nfse for the canonical source repository
 */

use NFePHP\NFSe\Models\Factory as FactoryModel;

class Factory extends FactoryModel
{
    protected $pathSchemes = '../../schemes/Dsfnet/';
}
