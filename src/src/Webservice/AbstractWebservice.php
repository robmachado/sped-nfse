<?php
/**
 * Created by PhpStorm.
 * User: administrador
 * Date: 11/12/15
 * Time: 14:20
 */

namespace NFePHP\NFSe\Webservice;

use NFePHP\NFSe\Webservice\InterfaceWebservice;

abstract class AbstractWebservice implements InterfaceWebservice
{
    abstract public function envioRPS();

    abstract public function envioLoteRPS();

    abstract public function testeEnvioLoteRPS();

    abstract public function consultaNFSe();

    abstract public function consultaNFSeRecebidas();

    abstract public function consultaNFSeEmitidas();

    abstract public function consultaLote();

    abstract public function consultaInformacoesLote();

    abstract public function cancelamentoNFSe();

    abstract public function consultaCNPJ();
}