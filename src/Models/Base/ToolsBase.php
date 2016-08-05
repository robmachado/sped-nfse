<?php

namespace NFePHP\NFSe\Models\Base;

use NFePHP\NFSe\Models\Base\ToolsInferface;

class ToolsBase implements ToolsInferface
{
    public function __construct()
    {
        //passar os parametros de configuração
        //com as configurações carregar o certificado
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
