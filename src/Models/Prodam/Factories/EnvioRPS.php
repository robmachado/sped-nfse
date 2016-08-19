<?php

namespace NFePHP\NFSe\Models\Prodam\Factories;

use NFePHP\NFSe\Models\Prodam\Rps;
use NFePHP\NFSe\Models\Prodam\Factories\RenderRPS;

class EnvioRPS
{
    private static $dtIni = '';
    private static $dtFim = '';
    private static $qtdRPS = 0;
    private static $valorTotalServicos = 0;
    private static $valorTotalDeducoes = 0;
    
    public static function render(
        $versao,
        $remetenteTipoDoc,
        $remetenteCNPJCPF,
        $transacao = true,
        $data = '',
        $priKey = ''
    ) {
        if ($data == '') {
            return '';
        }
        $xmlRPS = '';
        $content = '';
        if (is_object($data)) {
            //foi passado um unico RPS
            $xmlRPS .= self::individual($content, $data, $priKey);
        } elseif (is_array($data)) {
            if (count($data) == 1) {
                $xmlRPS .= self::individual($content, $data, $priKey);
            } else {
                $xmlRPS .= self::lote($content, $data, $priKey);
            }
        }
        $content .= Header::render(
            $versao,
            $remetenteTipoDoc,
            $remetenteCNPJCPF,
            $transacao,
            '',
            self::$dtIni,
            self::$dtFim,
            self::$qtdRPS,
            self::$valorTotalServicos,
            self::$valorTotalDeducoes
        );
        $content .= $xmlRPS;
        return $content;
    }
    
    private static function individual(&$content, $data, $priKey = '')
    {
        $xmlRPS = '';
        //foi passado um unico RPS
        $content .= "<PedidoEnvioRPS "
            . "xmlns=\"http://www.prefeitura.sp.gov.br/nfe\">";
        $xmlRPS .= RenderRPS::toXml($data, $priKey);
        return $xmlRPS."</PedidoEnvioRPS>";
    }

    private static function lote(&$content, $data, $priKey = '')
    {
        $xmlRPS = '';
        //foi passado um lote de RPS
        self::totalizeRps($data);
        $content .= "<PedidoEnvioLoteRPS "
            . "xmlns=\"http://www.prefeitura.sp.gov.br/nfe\">";
        foreach ($data as $rps) {
            $xmlRPS .= RenderRPS::toXml($data, $priKey);
        }
        return $xmlRPS."</PedidoEnvioLoteRPS>";
    }
    
    private static function totalizeRps($data)
    {
        foreach ($data as $rps) {
            self::$valorTotalServicos += $rps->valorServicosRPS;
            self::$valorTotalDeducoes += $rps->valorDeducoesRPS;
            self::$qtdRPS++;
            if (self::$dtIni == '') {
                self::$dtIni = $rps->dtEmiRPS;
            }
            if (self::$dtFim == '') {
                self::$dtFim = $rps->dtEmiRPS;
            }
            if ($rps->dtEmiRPS <= self::$dtIni) {
                self::$dtIni = $rps->dtEmiRPS;
            }
            if ($rps->dtEmiRPS >= self::$dtFim) {
                self::$dtFim = $rps->dtEmiRPS;
            }
        }
    }
}
