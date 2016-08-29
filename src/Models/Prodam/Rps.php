<?php

namespace NFePHP\NFSe\Models\Prodam;

/**
 * Classe a montagem do RPS para a Cidade de São Paulo
 * conforme o modelo Prodam
 *
 * @category  NFePHP
 * @package   NFePHP\NFSe\Prodam\Rps
 * @copyright NFePHP Copyright (c) 2016
 * @license   http://www.gnu.org/licenses/lgpl.txt LGPLv3+
 * @license   https://opensource.org/licenses/MIT MIT
 * @license   http://www.gnu.org/licenses/gpl.txt GPLv3+
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 * @link      http://github.com/nfephp-org/sped-nfse for the canonical source repository
 */

use InvalidArgumentException;
use NFePHP\Common\Strings\Strings;
use NFePHP\NFSe\Models\Rps as RpsBase;

class Rps extends RpsBase
{
    public $versaoRPS = '';
    public $prestadorIM = '';
    public $serieRPS = '';
    public $numeroRPS = '';
    public $dtEmiRPS = '';
    public $tipoRPS = '';
    public $statusRPS = '';
    public $tributacaoRPS = '';
    public $valorServicosRPS = '';
    public $valorDeducoesRPS = '';
    public $valorPISRPS = '';
    public $valorCOFINSRPS = '';
    public $valorINSSRPS = '';
    public $valorIRRPS = '';
    public $valorCSLLRPS = '';
    public $valorCargaTributariaRPS = '';
    public $percentualCargaTributariaRPS = '';
    public $fonteCargaTributariaRPS = '';
    public $codigoCEIRPS = '';
    public $matriculaObraRPS = '';
    public $municipioPrestacaoRPS = '';
    public $codigoServicoRPS = '';
    public $aliquotaServicosRPS = '';
    public $issRetidoRPS = false;
    public $discriminacaoRPS = '';
    public $tomadorTipoDoc = '2';
    public $tomadorCNPJCPF = '';
    public $tomadorIE = '';
    public $tomadorIM = '';
    public $tomadorRazao  = '';
    public $tomadorTipoLogradouro = '';
    public $tomadorLogradouro = '';
    public $tomadorNumeroEndereco = '';
    public $tomadorComplementoEndereco = '';
    public $tomadorBairro = '';
    public $tomadorCodCidade = '';
    public $tomadorSiglaUF = '';
    public $tomadorCEP = '';
    public $tomadorEmail = '';
    public $intermediarioTipoDoc = '3';
    public $intermediarioCNPJCPF = '';
    public $intermediarioIM = '';
    public $intermediarioISSRetido = 'N';
    public $intermediarioEmail = '';
    
    private $aTp = [
        'RPS' => 'Recibo Provisório de Serviços',
        'RPS-M' => 'Recibo Provisório de Serviços proveniente de Nota Fiscal Conjugada (Mista)',
        'RPS-C' => 'Cupom'
    ];
    
    private $aTrib = [
        'T' => 'Tributado em São Paulo',
        'F' => 'Tributado Fora de São Paulo',
        'A' => 'Tributado em São Paulo, porém Isento',
        'B' => 'Tributado Fora de São Paulo, porém Isento',
        'M' => 'Tributado em São Paulo, porém Imune',
        'N' => 'Tributado Fora de São Paulo, porém Imune',
        'X' => 'Tributado em São Paulo, porém Exigibilidade Suspensa',
        'V' => 'Tributado Fora de São Paulo, porém Exigibilidade Suspensa',
        'P' => 'Exportação de Serviços'
    ];
    
    /**
     * Inscrição Municipal do Prestador do Serviço
     * @param type $im
     */
    public function prestador($im)
    {
        $this->prestadorIM = $im;
    }
    
    /**
     * Dados do Tomador do Serviço
     * @param string $razao
     * @param int $tipo
     * @param string $cnpjcpf
     * @param string $ie
     * @param string $im
     * @param string $email
     */
    public function tomador(
        $razao,
        $tipo = '2',
        $cnpjcpf = '',
        $ie = '',
        $im = '',
        $email = ''
    ) {
        $this->tomadorRazao = Strings::cleanString($razao);
        $this->tomadorTipoDoc = $tipo;
        if ($tipo == '2') {
            $cnpjcpf = str_pad($cnpjcpf, 14, '0', STR_PAD_LEFT);
        }
        $this->tomadorCNPJCPF = $cnpjcpf;
        $this->tomadorIE = $ie;
        $this->tomadorIM = $im;
        $this->tomadorEmail = $email;
    }
    
    /**
     * Endereço do Tomador do serviço
     * @param string $tipo
     * @param string $logradouro
     * @param string $numero
     * @param string $complemento
     * @param string $bairro
     * @param int $cmun
     * @param string $uf
     * @param string $cep
     */
    public function tomadorEndereco(
        $tipo = '',
        $logradouro = '',
        $numero = '',
        $complemento = '',
        $bairro = '',
        $cmun = '',
        $uf = '',
        $cep = ''
    ) {
        $this->tomadorTipoLogradouro = $tipo;
        $this->tomadorLogradouro = Strings::cleanString($logradouro);
        $this->tomadorNumeroEndereco = $numero;
        $this->tomadorComplementoEndereco = Strings::cleanString($complemento);
        $this->tomadorBairro = Strings::cleanString($bairro);
        $this->tomadorCodCidade = $cmun;
        $this->tomadorSiglaUF = $uf;
        $this->tomadorCEP = $cep;
    }
    
    /**
     * Dados do intermediário
     * @param int $tipo
     * @param string $cnpj
     * @param string $im
     * @param string $email
     */
    public function intermediario(
        $tipo = '',
        $cnpj = '',
        $im = '',
        $email = ''
    ) {
        $this->intermediarioTipoDoc = $tipo;
        $this->intermediarioCNPJCPF = $cnpj;
        $this->intermediarioIM = $im;
        $this->intermediarioEmail = strtolower($email);
    }
    
    /**
     * Versão do layout usado 1 ou 2
     * @param int $versao
     */
    public function versao($versao)
    {
        $versao = preg_replace('/[^0-9]/', '', $versao);
        $this->versaoRPS = $versao;
    }
    
    /**
     * Série do RPS
     * @param string $serie
     */
    public function serie($serie = '')
    {
        $serie = substr(trim($serie), 0, 5);
        $this->serieRPS = $serie;
    }
    
    /**
     * Numero do RPS
     * @param int $numero
     * @throws InvalidArgumentException
     */
    public function numero($numero = 0)
    {
        if (!is_numeric($numero) || $numero <= 0) {
            $msg = 'O numero do RPS deve ser maior ou igual a 1';
            throw new InvalidArgumentException($msg);
        }
        $this->numeroRPS = $numero;
    }
    
    /**
     * Data do RPS
     * @param type $data
     */
    public function data($data)
    {
        $this->dtEmiRPS = $data;
    }
    
    /**
     * Status do RPS Normal ou Cancelado
     * @param string $status
     * @throws InvalidArgumentException
     */
    public function status($status = 'N')
    {
        if (!$this->zValidData(['N' => 0, 'C' => 1], $status)) {
            $msg = 'O status pode ser apenas N-normal ou C-cancelado.';
            throw new InvalidArgumentException($msg);
        }
        $this->statusRPS = $status;
    }
    
    /**
     * Tipo do RPS
     * RPS – Recibo Provisório de Serviços
     * RPS-M – Recibo Provisório de Serviços proveniente de Nota Fiscal Conjugada (Mista);
     * RPS-C – Cupom
     *
     * @param string $tipo
     */
    public function tipo($tipo = '')
    {
        if (!$this->zValidData($this->aTp, $tipo)) {
            $msg = 'O tipo deve ser informado com um código válido';
            throw new InvalidArgumentException($msg);
        }
        $this->tipoRPS = $tipo;
    }
    
    /**
     * Tributação
     * T – Tributado em São Paulo
     * F – Tributado Fora de São Paulo
     * A – Tributado em São Paulo, porém Isento
     * B – Tributado Fora de São Paulo, porém Isento
     * M – Tributado em São Paulo, porém Imune
     * N – Tributado Fora de São Paulo, porém Imune
     * X – Tributado em São Paulo, porém Exigibilidade Suspensa
     * V – Tributado Fora de São Paulo, porém Exigibilidade Suspensa
     * P – Exportação de Serviços
     *
     * @param string $tributacao
     */
    public function tributacao($tributacao = '')
    {
        if (!$this->zValidData($this->aTrib, $tributacao)) {
            $msg = 'A tributação deve ser informada com um código válido';
            throw new InvalidArgumentException($msg);
        }
        $this->tributacaoRPS = $tributacao;
    }
    
    /**
     * Código do serviço prestado
     * @param string $cod
     */
    public function codigoServico($cod = '')
    {
        $this->codigoServicoRPS =  $cod;
    }
    
    /**
     * Valor dos Serviços prestados
     * @param float $valor
     */
    public function valorServicos($valor = 0.00)
    {
        $this->valorServicosRPS = number_format($valor, 2, '.', '');
    }
    
    /**
     * Valor das deduções aplicáveis ao serviço
     * @param float $valor
     */
    public function valorDeducoes($valor = 0.00)
    {
        $this->valorDeducoesRPS = number_format($valor, 2, '.', '');
    }
    
    /**
     * Aliquota do ISS do serviço
     * @param float $valor
     * @throws InvalidArgumentException
     */
    public function aliquotaServico($valor = 0.0000)
    {
        if ($valor > 1 || $valor < 0) {
            $msg = 'Voce deve indicar uma aliquota em fração ex. 0.12.';
            throw new InvalidArgumentException($msg);
        }
        $this->aliquotaServicosRPS = number_format($valor, 4, '.', '');
    }
    
    /**
     * Indicador de retenção de ISS
     * 1 - iss retido pelo tomador
     * 2 - sem retenção
     * 3 - iss retido pelo intermediário
     * @param type $flag
     */
    public function issRetido($flag = '2')
    {
        $this->issRetidoRPS = false;
        $this->intermediarioISSRetido = 'N';
        if ($flag == 1) {
            $this->issRetidoRPS = true;
        }
        if ($flag == 3) {
            $this->issRetidoRPS = true;
            $this->intermediarioISSRetido = 'S';
        }
    }
    
    /**
     * Discriminação do serviço prestado
     * @param string $desc
     */
    public function discriminacao($desc = '')
    {
        $this->discriminacaoRPS = Strings::cleanString(trim($desc));
    }
    
    /**
     * Carga tributária total estimada
     * Daods normalmente obtidos no IBPT
     * @param float $valor
     * @param float $percentual
     * @param string $fonte
     */
    public function cargaTributaria($valor = 0.00, $percentual = 0.0000, $fonte = '')
    {
        $this->valorCargaTributariaRPS = number_format($valor, 2, '.', '');
        $this->percentualCargaTributariaRPS = number_format($valor, 4, '.', '');
        $this->fonteCargaTributariaRPS = substr(Strings::cleanString($fonte), 0, 10);
    }
    
    /**
     * Valor referente ao recolhimento do PIS
     * @param type $valor
     */
    public function valorPIS($valor = 0.00)
    {
        $this->valorPISRPS = number_format($valor, 2, '.', '');
    }
    
    /**
     * Valor referente ao recolhimento da COFINS
     * @param float $valor
     */
    public function valorCOFINS($valor = 0.00)
    {
        $this->valorCOFINSRPS = number_format($valor, 2, '.', '');
    }
    
    /**
     * Valor referente ao recolhimento da contribuição ao INSS
     * @param float $valor
     */
    public function valorINSS($valor = 0.00)
    {
        $this->valorINSSRPS = number_format($valor, 2, '.', '');
    }
    
    /**
     * Valor refenrente ao IR (Imposto de Renda)
     * @param float $valor
     */
    public function valorIR($valor = 0.00)
    {
        $this->valorIRRPS = number_format($valor, 2, '.', '');
    }
    
    /**
     * Valor referente a CSLL (contribuição Sobre o Lucro Líquido)
     * @param float $valor
     */
    public function valorCSLL($valor = 0.00)
    {
        $this->valorCSLLRPS = number_format($valor, 2, '.', '');
    }
    
    /**
     * Código Matricula no CEI (Cadastro Especifico do INSS)
     * @param string $cod
     */
    public function codigoCEI($cod = '')
    {
        $this->codigoCEIRPS = $cod;
    }
    
    /**
     * Identificaçao ou número de matricula da Obra Civil
     * @param string $matricula
     */
    public function matriculaObra($matricula = '')
    {
        $this->matriculaObraRPS = $matricula;
    }
    
    /**
     * Código IBGE para o municio onde o serviço
     * foi prestado
     * @param int $cmun
     */
    public function municipioPrestacao($cmun = '')
    {
        $this->municipioPrestacaoRPS = $cmun;
    }
}
