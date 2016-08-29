<?php

namespace NFePHP\NFSe\Models\Dsfnet;

/**
 * Classe para a comunicação com os webservices da
 * conforme o modelo DSFNET
 *
 * @category  NFePHP
 * @package   NFePHP\NFSe\Models\Dsfnet\Tools
 * @copyright NFePHP Copyright (c) 2016
 * @license   http://www.gnu.org/licenses/lgpl.txt LGPLv3+
 * @license   https://opensource.org/licenses/MIT MIT
 * @license   http://www.gnu.org/licenses/gpl.txt GPLv3+
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 * @link      http://github.com/nfephp-org/sped-nfse for the canonical source repository
 */

use NFePHP\NFSe\Models\Dsfnet\Rps;
use NFePHP\NFSe\Models\Dsfnet\Factories;
use NFePHP\NFSe\Models\Tools as ToolsBase;

class Tools
{
    
    public function cancelar($prestadorIM, $numeroNota, $codigoVerificacao, $motivo)
    {
    }
    
    public function consultarLote($numeroLote)
    {
    }
    
    /**
     *
     * @param type $prestadorIM
     * @param type $nfse ['numero', 'codigoVerificacao']
     * @param type $rps  ['numero', 'serie']
     */
    public function consultarNFSeRps($prestadorIM, $nfse = [], $rps = [])
    {
    }
    
    public function consultarNota($prestadorIM, $dtInicio, $dtFim, $notaInicial)
    {
    }
    
    public function consultarSequencialRps($prestadorIM, $serieRPS)
    {
    }
    
    public function enviar($rpss, $numeroLote)
    {
    }
    
    public function enviarSincrono($rpss, $numeroLote)
    {
    }
    
    public function testeEnviar($rpss, $numeroLote)
    {
    }
}
