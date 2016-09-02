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

class Tools extends ToolsBase
{
    /**
     * Webservices URL
     * @var array
     */
    protected $url = [
        1 => '',
        2 => ''
    ];
    /**
     * County Namespace
     * @var string
     */
    protected $xmlns = '';
    /**
     * Soap Version
     * @var int
     */
    protected $soapversion = 1;
    /**
     * SIAFI County Cod 
     * @var int 
     */
    protected $codcidade = 0;
    
    /**
     * Construtor da classe Tools
     * @param string $config
     */
    public function __construct($config)
    {
        parent::__construct($config);
    }
    
    /**
     * Solicita cancelamento da NFSe
     * @param string $prestadorIM
     * @param string $numeroNFSe
     */
    public function cancelar($prestadorIM, $numeroLote, $numeroNota, $codigoVerificacao, $motivo, $tokenEnvio = null)
    {
        $this->method = 'cancelar';
        $fact = new Factories\Cancelar($this->oCertificate);
        $xml = $fact->render(
            $this->versao,
            $this->remetenteCNPJCPF,
            $transacao = 'true',
            $this->codcidade,    
            $prestadorIM,
            $tokenEnvio,
            $numeroLote,
            $numeroNota,
            $codigoVerificacao,
            $motivo
        );
        return $this->buildRequest($xml);
    }
    
    public function consultarLote($numeroLote)
    {
        /*
         * <lot:consultarLote>
         <mensagemXml>?</mensagemXml>
      </lot:consultarLote>
         */
    }
    
    /**
     *
     * @param type $prestadorIM
     * @param type $nfse ['numero', 'codigoVerificacao']
     * @param type $rps  ['numero', 'serie']
     */
    public function consultarNFSeRps($prestadorIM, $nfse = [], $rps = [])
    {
        /*
         *  <lot:consultarNFSeRps>
         <mensagemXml>?</mensagemXml>
      </lot:consultarNFSeRps>
         */
    }
    
    public function consultarNota($prestadorIM, $dtInicio, $dtFim, $notaInicial)
    {
        /*
         * <lot:consultarNota>
         <mensagemXml>?</mensagemXml>
      </lot:consultarNota>
         */
    }
    
    public function consultarSequencialRps($prestadorIM, $serieRPS)
    {
        /*
         * <lot:consultarSequencialRps>
         <mensagemXml>?</mensagemXml>
      </lot:consultarSequencialRps>
         */
    }
    
    public function enviar($rpss, $numeroLote)
    {
        /*
         * <lot:enviar>
         <mensagemXml>?</mensagemXml>
      </lot:enviar>
         */
    }
    
    public function enviarSincrono($rpss, $numeroLote)
    {
        /*
         * <lot:enviarSincrono>
         <mensagemXml>?</mensagemXml>
      </lot:enviarSincrono>
         */
    }
    
    public function testeEnviar($rpss, $numeroLote)
    {
        /*
         * <lot:testeEnviar>
         <mensagemXml>?</mensagemXml>
      </lot:testeEnviar>
         */
    }
    
    /**
     * Monta o request da mansagem SOAP
     * @param string $body
     * @param string $method
     * @return string
     */
    protected function buildRequest($body)
    {
        $tag = $this->method;
        $request = "<$tag>";
        $request .= "<mensagemXML>$body</mensagemXML>";
        $request .= "</$tag>";
        return $request;
    }
    
    /**
     * Envia mensagem por SOAP
     * @param string $body
     * @param string $method
     */
    public function envia($request)
    {
       
        
        header("Content-type: text/xml");
        echo $request;
        die;
        
        $url = $this->url[$this->aConfig['tpAmb']];
        try {
            $this->setSSLProtocol('TLSv1');
            //$response = $this->oSoap->send($url, '', '', $body, $this->method);
        } catch (Exception $ex) {
            echo $ex;
        }
    }
    
}
