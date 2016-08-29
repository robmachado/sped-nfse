<?php

namespace NFePHP\NFSe\Models\Dsfnet\Factories;

use InvalidArgumentException;
use NFePHP\NFSe\Models\Dsfnet\Factory;
use NFePHP\NFSe\Models\Dsfnet\Factories\Header;

class Cancelar extends Factory
{
    public function render(
        $versao,
        $remetenteTipoDoc,
        $remetenteCNPJCPF,
        $transacao,
        $prestadorIM,
        $numeroNota,
        $codigoVerificacao,
        $motivo
    ) {
        $method = "Cancelar";
    }
}
