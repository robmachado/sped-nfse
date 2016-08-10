<?php

namespace NFePHP\NFSe\Models\Prodam;

/**
 * Classe para a conversão do TXT dos RPS no modelo PRODAM em XML
 * 
 * @category  NFePHP
 * @package   NFePHP\NFSe\Prodam\Convert
 * @copyright NFePHP Copyright (c) 2016
 * @license   http://www.gnu.org/licenses/lgpl.txt LGPLv3+
 * @license   https://opensource.org/licenses/MIT MIT
 * @license   http://www.gnu.org/licenses/gpl.txt GPLv3+
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 * @link      http://github.com/nfephp-org/sped-nfse for the canonical source repository
 */

use NFePHP\NFSe\Models\Base\ConvertBase;
use NFePHP\Common\Strings\Strings;

class Convert extends ConvertBase
{
    protected $aRps = array();
    protected $contTipos = ['1'=> 0,'2'=> 0,'3'=> 0,'5'=> 0,'6'=> 0,'9'=> 0];


    public static function get($txt = '')
    {
        $aRps = array();
        if (is_file($txt)) {
            //extrai cada linha do arquivo em um campo de matriz
            $aDados = file($txt, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES | FILE_TEXT);
        } elseif (is_array($txt)) {
            //carrega a matriz
            $aDados = $txt;
        } else {
            if (strlen($txt) > 0) {
                //carrega a matriz com as linha do arquivo
                $aDados = explode("\n", $txt);
            } else {
                return $aRps;
            }
        }
        $total = count($aDados);
        for($x=0; $x<$total; $x++) {
            $aDados[$x] = str_replace("\r", '', $aDados[$x]);
            $aDados[$x] = Strings::cleanString($aDados[$x]);
            $tipo = substr($dado, 0, 1);
            $this->contTipos[$tipo] += 1; 
        }
        $this->zArray2Rps($aDados);
    }
    
    /**
     * REGISTRO TIPO 1 – CABEÇALHO
     * Versão 002
     * @param string $dado
     */
    protected function f1Entity($dado)
    {
        //5 campos
        $aLen =  [ 1,  3,  8,  8,  8];
        $aTipo = ['N','N','N','N','N'];
        $aFields = ['tipo', 'versao', 'im', 'dtIni', 'dtFim'];
        $ini = 0;
        $x = 0;
        foreach($aLen as $len) {
            
            $x++;
        }
        
    }
    
    /**
     * REGISTRO TIPO 2 – DETALHE
     * Versão 001
     * @param type $dados
     */
    protected function f2Entity($dados)
    {
        //26 campos
        
    }
    
    /**
     * REGISTRO TIPO 3 - DETALHE (EXCLUSIVO PARA CUPONS)
     * Versão 002
     * @param string $dados
     */
    protected function f3Entity($dados)
    {
        //14 campos
    }
    
    /**
     * REGISTRO TIPO 5 – DETALHE DO INTERMEDIÁRIO DO SERVIÇO
     * Versão 002
     * @param string $dado
     */
    protected function f5Entity($dado)
    {
        //5 campos
    }
    
    /**
     * REGISTRO TIPO 6 – DETALHE
     * Versão 002
     * @param string $dado
     */
    protected function f6Entity($dado)
    {
        //38 campos
    }
    
    /**
     * REGISTRO TIPO 9 – RODAPÉ
     * @param string $dado
     */
    protected function f9Entity($dado)
    {
        //4 campos
        $aLen =  [ 1,  7,  15,  15];
        $aTipo = ['N','N','N','N'];
        $aFields = ['tipo', 'numero', 'valorTotalServicos', 'valorTotalDeducoes'];
        //2) Número de linhas de detalhe do arquivo  
        //   Número de linhas de detalhe (apenas Tipo 6 + Tipo 3) contidas no arquivo.
        //   Obs.: não considerar as linhas de detalhe Tipo 5.
        //   
        //3) Valor total dos serviços contido no arquivo
        //   Informe a soma dos valores dos serviços das linhas de detalhe 
        //   (Tipo 6 + Tipo 3) contidas no arquivo.
        //   
        //4) Valor total das deduções contidas no arquivo
        //   Informe a soma dos valores das deduções das linhas de detalhe
        //   (Tipo 6 + Tipo 3) contidas no arquivo.
    }
    
    /**
     * zArray2xml
     * Converte um lote de RPS em um array de txt em um ou mais RPS
     *
     * @param  array $aDados
     * @return string
     * @throws Exception\RuntimeException
     */
    protected function zArray2Rps($aDados = array())
    {
        foreach ($aDados as $dado) {
            
            $metodo = 'f'.substr($dado, 0, 1).'Entity';
            if (! method_exists($this, $metodo)) {
                $msg = "O txt tem um metodo não definido!! $dado";
                throw new Exception\RuntimeException($msg);
            }
            $this->$metodo($dado);
        }
    }
    
}
