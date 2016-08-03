<?php

namespace NFePHP\NFSe;

use InvalidArgumentException;

class Rps
{
    protected $serieRPS;
    protected $numeroRPS;
    protected $tipoRPS;
    protected $dtEmiRPS;
    protected $statusRPS;
    protected $tributacaoRPS;
    protected $valorServicosRPS;
    protected $valorDeducoesRPS;
    protected $codigoServicoRPS;
    protected $aliquotaServicosRPS;
    protected $issRetidoRPS;
    protected $discriminacaoRPS;
    
    protected $tomadorCPF;
    protected $tomadorCNPJ;
    protected $tomadorRazao;
    protected $tomadorTipoLogradouro;
    protected $tomadorLogradouro;
    protected $tomadorNumeroEndereco;
    protected $tomadorComplementoEndereco;
    protected $tomadorBairro;
    protected $tomadorCodCidade;
    protected $tomadorSiglaUF;
    protected $tomadorCEP;
    protected $tomadorEmail;

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
        'P' => 'Exportação de Serviçosv'
    ];
    
    public function render()
    {
        
    }
    
    
    public function tomador($razao, $cnpj = '', $cpf = '', $email = '')
    {
        
    }
    
    public function tomadorEndereco(
        $tipo,
        $logradouro,
        $numero,
        $complemento,
        $bairro,
        $cmun,
        $uf,
        $cep
    ) {
        
    }
    
    public function serie($serie = '')
    {
        $this->serieRPS = $serie;
    }
    
    public function numero($numero = 0)
    {
        if (!is_numeric($numero) || $numero <= 0) {
            $msg = 'O numero deve ser maior ou igual a 1';
            throw new InvalidArgumentException($msg);
        }
        $this->numeroRPS = $numero;
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
        if (!$this->validData($this->aTp, $tipo)) {
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
        if (!$this->validData($this->aTrib, $tributacao)) {
            $msg = 'A tributação deve ser informada com um código válido';
            throw new InvalidArgumentException($msg);
        }
        $this->tributacaoRPS = $tributacao;
    }
    
    protected function tagAssinatura()
    {
        
    }
    
    protected function validData($array, $data)
    {
        return array_key_exists($data, $array);
    }
}