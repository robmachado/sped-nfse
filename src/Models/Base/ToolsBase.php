<?php

namespace NFePHP\NFSe\Models\Base;

/**
 *
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
