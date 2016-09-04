<?php

namespace NFePHP\NFSe\Models\Dsfnet;

/**
 * Classe a construção do xml da NFSe
 * conforme o modelo DSFNET
 *
 * @category  NFePHP
 * @package   NFePHP\NFSe\Models\Dsfnet\Rps
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
    public $versaoRPS;
    public $tipoRPS; //Padrão "RPS"
    public $serieRPS;//Padrão "NF"
    public $numeroRPS;
    public $dataEmissaoRPS;
    public $situacaoRPS; //Situação da RPS "N"-Normal "C"-Cancelada
    
    public $inscricaoMunicipalPrestador;
    public $razaoSocialPrestador;
    public $dDDPrestador;
    public $telefonePrestador;
    
    public $serieRPSSubstituido;
    public $numeroRPSSubstituido;
    public $numeroNFSeSubstituida;
    public $dataEmissaoNFSeSubstituida; //Preencher com "1900-01-01"
    
    public $seriePrestacao; //preencha o campo com o valor '99'
    
    public $inscricaoMunicipalTomador;
    public $cPFCNPJTomador;
    public $razaoSocialTomador;
    public $docTomadorEstrangeiro;
    public $dDDTomador;
    public $telefoneTomador;
    
    public $tipoLogradouroTomador;
    public $logradouroTomador;
    public $numeroEnderecoTomador;
    public $complementoTomador;
    public $tipoBairroTomador;
    public $bairroTomador;
    public $cidadeTomador; //Código da Cidade do Tomador - Padrão SIAF
    public $cidadeTomadorDescricao;
    public $cEPTomador;
    public $emailTomador;
    
    public $itens = [];
    
    public $codigoAtividade;
    public $aliquotaAtividade;
    public $tipoRecolhimento;
    public $municipioPrestacao;
    public $municipioPrestacaoDescricao;
    public $operacao;
    
    public $tributacao;
    public $valorPIS;
    public $valorCOFINS;
    public $valorINSS;
    public $valorIR;
    public $valorCSLL;
    public $aliquotaPIS;
    public $aliquotaCOFINS;
    public $aliquotaINSS;
    public $aliquotaIR;
    public $aliquotaCSLL;
    public $descricaoRPS;
    
    public $motCancelamento;
    
    public $cpfCnpjIntermediario;
    
    public $deducoes = [];

    protected $aTributacao = [
        'C' => 'Isenta de ISS',
        'E' => 'Não Incidência no Município',
        'F' => 'Imune',
        'K' => 'Exigibilidd Susp.Dec.J/Proc.A',
        'N' => 'Não Tributável',
        'T' => 'Tributável',
        'G' => 'Tributável Fixo',
        'H' => 'Tributável S.N.',
        'M' => 'Micro Empreendedor Individual (MEI).'
    ];
    
    protected $aDeducao = [
        'A' => 'Sem Dedução',
        'B' => 'Com Dedução/Materiais',
        'C' => 'Imune/Isenta de ISSQN',
        'D' => 'Devolução / Simples Remessa',
        'J' => 'Intermediação*'
    ];
    
    protected $aTipoRecolhimento = [
        "A" => 'A Recolher',
        "R" => 'Retido na Fonte'
    ];
    
    /**
     * Versão do layout usado 1
     * @param int $versao
     */
    public function versaoRPS($versao)
    {
        $versao = preg_replace('/[^0-9]/', '', $versao);
        $this->versaoRPS = $versao;
    }
    
    /**
     * Tipo do RPS
     * RPS – Recibo Provisório de Serviços
     * @param string $tipo
     */
    public function tipoRPS($tipo = 'RPS')
    {
        $this->tipoRPS = $tipo;
    }
    
    /**
     * Série do RPS
     * @param string $serie
     */
    public function serie($serie = 'NF')
    {
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
     * Formato YYYY-mm-ddTHH:ii:ss
     * @param type $data
     */
    public function data($data = '')
    {
        $dt = new \DateTime($data);
        $dtf = $dt->format('Y-m-d\TH:i:s');
        $this->dataEmissaoRPS = $dtf;
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
        $this->situacaoRPS = $status;
    }
    
    public function prestador($im, $razao, $ddd = '', $telefone = '')
    {
        $this->inscricaoMunicipalPrestador = $im;
        $this->razaoSocialPrestador = $razao;
        $this->dDDPrestador = $ddd;
        $this->telefonePrestador = $telefone;
    }
    
    public function substituido($serieRPS = '', $numeroRPS = '', $numeroNFSe = '', $dataNFSe = '')
    {
        $this->serieRPSSubstituido = $serieRPS;
        $this->numeroRPSSubstituido = $numeroRPS;
        $this->numeroNFSeSubstituida = $numeroNFSe;
        $this->dataEmissaoNFSeSubstituida = $dataNFSe;
    }
    
    public function seriePrestacao($serie = 99)
    {
        $this->seriePrestacao = $serie;
    }
    
    public function tomador(
        $im,
        $cpfcnpj,
        $razao,
        $docEstrangeiro = '',
        $ddd = '',
        $telefone = ''
    ) {
        $this->inscricaoMunicipalTomador = $im;
        $this->cPFCNPJTomador = $cpfcnpj;
        $this->razaoSocialTomador = $razao;
        $this->docTomadorEstrangeiro = $docEstrangeiro;
        $this->dDDTomador = $ddd;
        $this->telefoneTomador = $telefone;
    }
    
    public function tomadorEndereco(
        $tipoLogradouro,
        $logradouro,
        $numero,
        $complemento,
        $tipoBairro,
        $bairro,
        $codigoSIAF,
        $cidade,
        $cep,
        $email
    ) {
        $this->tipoLogradouroTomador = $tipoLogradouro;
        $this->logradouroTomador = $logradouro;
        $this->numeroEnderecoTomador = $numero;
        $this->complementoTomador = $complemento;
        $this->tipoBairroTomador = $tipoBairro;
        $this->bairroTomador = $bairro;
        $this->cidadeTomador = $codigoSIAF;
        $this->cidadeTomadorDescricao = $cidade;
        $this->cEPTomador = $cep;
        $this->emailTomador = $email;
    }
    
    public function itemServico(
        $discriminacao,
        $quantidade,
        $valorUnitario,
        $valorTotal,
        $tributavel
    ) {
        $this->itens[] = [
            'discriminacao' => $discriminacao,
            'quantidade' => $quantidade,
            'valorUnitario' => $valorUnitario,
            'valorTotal' => $valorTotal,
            'tributavel' => $tributavel
        ];
    }
    
    public function operacaoRPS($operacao)
    {
        $this->operacao = $operacao;
    }
    
    public function descricao($descricao)
    {
        $this->descricaoRPS = $descricao;
    }
    
    public function codigoAtividadeRPS($codigo, $aliquota)
    {
        $this->codigoAtividade = $codigo;
        $this->aliquotaAtividade = $aliquota;
    }
    
    public function recolhimento($tipo)
    {
        $this->tipoRecolhimento = $tipo;
    }
    
    public function localPrestacao($codmunicipio, $municipio)
    {
        $this->municipioPrestacao = $codmunicipio;
        $this->municipioPrestacaoDescricao = $municipio;
    }
    
    public function tributacaoServico(
        $tributacao,
        $valorPIS,
        $valorCOFINS,
        $valorINSS,
        $valorIR,
        $valorCSLL,
        $aliquotaPIS,
        $aliquotaCOFINS,
        $aliquotaINSS,
        $aliquotaIR,
        $aliquotaCSLL
    ) {
        $this->tributacao = $tributacao;
        $this->valorPIS = $valorPIS;
        $this->valorCOFINS = $valorCOFINS;
        $this->valorINSS = $valorINSS;
        $this->valorIR = $valorIR;
        $this->valorCSLL = $valorCSLL;
        $this->aliquotaPIS = $aliquotaPIS;
        $this->aliquotaCOFINS = $aliquotaCOFINS;
        $this->aliquotaINSS = $aliquotaINSS;
        $this->aliquotaIR = $aliquotaIR;
        $this->aliquotaCSLL = $aliquotaCSLL;
    }
    
    public function cancelamento($motivo)
    {
        $this->motCancelamento = $motivo;
    }
    
    public function intermediario($cpfcnpj)
    {
        $this->cpfCnpjIntermediario = $cpfcnpj;
    }

    public function deducao(
        $deducaoPor,
        $tipoDeducao,
        $cpfcnpjReferencia,
        $numeroNFReferencia,
        $valorTotalReferencia,
        $percentualDeduzir,
        $valorDeduzir
    ) {
        $this->deducoes[] = [
            'deducaoPor' => $deducaoPor,
            'tipoDeducao' => $tipoDeducao,
            'cpfcnpjReferencia' => $cpfcnpjReferencia,
            'numeroNFReferencia' => $numeroNFReferencia,
            'valorTotalReferencia' => $valorTotalReferencia,
            'percentualDeduzir' => $percentualDeduzir,
            'valorDeduzir' => $valorDeduzir
        ];
    }
}
