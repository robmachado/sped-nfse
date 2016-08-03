<?php

namespace NFePHP\NFSe;

interface NfseInferface
{
    public function assina();
    
    public function envioRPS();
    
    public function testeEnvioRPS();
    
    public function consultaNFSe();
    
    public function consultaNFSeRecebidas();
    
    public function consultaNFSeEmitidas();
    
    public function consultaLote();
    
    public function consultaInformacoesLote();
    
    public function cancelamentoNFSe();

    public function consultaCNPJ();
}