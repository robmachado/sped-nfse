<?php

namespace NFePHP\NFSe\Models\Prodam;

/**
 * Classe para a comunicação com os webservices da Cidade de São Paulo
 * conforme o modelo Prodam
 *
 * @category  NFePHP
 * @package   NFePHP\NFSe\Prodam\Tools
 * @copyright NFePHP Copyright (c) 2016
 * @license   http://www.gnu.org/licenses/lgpl.txt LGPLv3+
 * @license   https://opensource.org/licenses/MIT MIT
 * @license   http://www.gnu.org/licenses/gpl.txt GPLv3+
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 * @link      http://github.com/nfephp-org/sped-nfse for the canonical source repository
 */

use NFePHP\NFSe\Models\Base\ToolsBase;
use NFePHP\NFSe\Models\Base\ToolsInterface;

class Tools extends ToolsBase implements ToolsInferface
{
    /**
     * Cabeçalho do RPS
     * @var string
     */
    protected $cabecalho;
    
    //quando mais de um RPS for carregado
    //as variaveis abaixo devem ser carregadas
    protected $transacao = false;
    protected $dtInicio;
    protected $dtFim;
    protected $qtdRPS;
    protected $valorTotalServicos = 0.0;
    protected $valorTotalDeducoes = 0.0;
    
    /**
     * Construtor no cabeçalho
     */
    protected function cabecalho($numRPS = 1)
    {
        $versao = $this->aConfig['versao'];
        $cpf = $this->aConfig['cpf'];
        $cnpj = $this->aConfig['cnpj'];
        $this->cabecalho = "<Cabecalho Versao=\"$versao\"><CPFCNPJRemetente>";
        if ($cnpj != '') {
            $this->cabecalho .= "<CNPJ>$cnpj</CNPJ>";
        } else {
            $this->cabecalho .= "<CPF>$cpf</CPF>";
        }
        $this->cabecalho .= "</CPFCNPJRemetente>";
        if ($this->transacao) {        
            $this->cabecalho .= "<transacao>true</transacao>"
                . "<dtInicio>$this->dtInicio</dtInicio>"
                . "<dtFim>$this->dtFim</dtFim>"
                . "<QtdRPS>$this->qtdRPS</QtdRPS>"
                . "<ValorTotalServicos>$this->valorTotalServicos</ValorTotalServicos>"
                . "<ValorTotalDeducoes>$this->valorTotalDeducoes</ValorTotalDeducoes>";
        }
        $this->cabecalho .= "</Cabecalho>";
    }
}
