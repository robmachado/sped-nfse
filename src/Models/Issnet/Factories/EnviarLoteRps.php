<?php

namespace NFePHP\NFSe\Models\Issnet\Factories;

class EnviarLoteRps
{
    public function render(
        $versao,
        $remetenteTipoDoc,
        $remetenteCNPJCPF,
        $inscricaoMunicipal,
        $lote,
        $rpss
    ) {
        $method = 'EnviarLoteRpsEnvio';
        $xsd = 'servico_enviar_lote_rps_envio';
        $numeroRps = count($rpss);
    }
}
