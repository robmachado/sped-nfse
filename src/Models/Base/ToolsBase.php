<?php

namespace NFePHP\NFSe\Models\Base;

/**
 * Classe para base para a comunicação com os webservices
 *
 * @category  NFePHP
 * @package   NFePHP\NFSe\Base\ToolsBase
 * @copyright NFePHP Copyright (c) 2016
 * @license   http://www.gnu.org/licenses/lgpl.txt LGPLv3+
 * @license   https://opensource.org/licenses/MIT MIT
 * @license   http://www.gnu.org/licenses/gpl.txt GPLv3+
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 * @link      http://github.com/nfephp-org/sped-nfse for the canonical source repository
 */

use NFePHP\NFSe\Models\Base\ToolsInferface;
use NFePHP\Common\Base\BaseTools;
use NFePHP\Common\Files;

class ToolsBase extends BaseTools implements ToolsInferface
{
  
    protected $saveLog = true;
    
    public function __construct($config)
    {
        parent::__construct($config);
    }
    
    public function setSaveLogs()
    {
        
    }
    
    public function assina()
    {
    }

    public function envioRPS()
    {
        //unico ou em lote
    }
    
    public function testeEnvioRPS()
    {
    }
    
    public function consultaNFSe()
    {
    }
    
    public function consultaNFSeRecebidas()
    {
    }
    
    public function consultaNFSeEmitidas()
    {
    }
    
    public function consultaLote()
    {
    }
    
    public function consultaInformacoesLote()
    {
    }
    
    public function cancelamentoNFSe()
    {
    }
    
    public function consultaCNPJ()
    {
    }
}
