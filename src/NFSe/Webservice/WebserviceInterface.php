<?php

namespace NFePHP\NFSe\Webservice;


interface WebserviceInterface
{
    public function envioRPS();

    public function envioLoteRPS();

    public function testeEnvioLoteRPS();

    public function consultaNFSe();

    public function consultaNFSeRecebidas();

    public function consultaNFSeEmitidas();

    public function consultaLote();

    public function consultaInformacoesLote();

    public function cancelamentoNFSe();

    public function consultaCNPJ();

}
