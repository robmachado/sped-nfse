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

use InvalidArgumentException;
use NFePHP\NFSe\Models\Base\ConvertBase;
use NFePHP\Common\Strings\Strings;
use NFePHP\NFSe\Models\Prodam\Rps;

class Convert extends ConvertBase
{
    protected static $aRps = array();
    //os campos abaixo são usados basicamente para controle
    // e validação
    protected static $contTipos = [1=>0,2=>0,3=>0,5=>0,6=>0,9=>0];
    protected static $numRps = 0;
    protected static $tipo1;
    protected static $versao;
    protected static $prestadorIM;
    protected static $dtIni;
    protected static $dtFim;
    protected static $tipo9;
    protected static $num;
    protected static $valorTotalServicos;
    protected static $valorTotalDeducoes;

    public static function get($txt = '')
    {
        if (empty($txt)) {
            throw new InvalidArgumentException('Algum dado deve ser passado para converter.');
        }
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
        for ($x=0; $x<$total; $x++) {
            $aDados[$x] = str_replace("\r", '', $aDados[$x]);
            $aDados[$x] = Strings::cleanString($aDados[$x]);
            $tipo = substr($aDados[$x], 0, 1);
            self::$contTipos[$tipo] += 1;
        }
        //o numero de notas criadas será a quantidade de tipo 2 ou 3 ou 6
        self::$numRps = self::$contTipos['2']+self::$contTipos['3']+self::$contTipos['6'];
        for ($x=0; $x<self::$numRps; $x++) {
            self::$aRps[] = new Rps();
        }
        self::zArray2Rps($aDados);
    }
    
    protected static function validData()
    {
        if ((self::$contTipos['1'] == 0 || self::$contTipos['1'] > 0) ||
            (self::$contTipos['9'] == 0 || self::$contTipos['9'] > 0)
        ) {
            $msg = "No lote deve haver um e apenas um elemento do tipo 1 e do tipo 9.";
        }
        if ((self::$contTipos['2'] > 0 && self::$contTipos['3'] > 0) ||
            (self::$contTipos['6'] > 0 && self::$contTipos['3'] > 0)
        ) {
            $msg = "No mesmo lote não podem haver elementos do tipo 2 e 3."
                . "\nNem elementos do tipo 3 e 6 simultâneamente."
                . "\nMonte um lote para cada tipo separadamente.";
        }
        throw new InvalidArgumentException($msg);
    }

    /**
     * REGISTRO TIPO 1 – CABEÇALHO
     * Versão 001 e 002
     * @param string $dado
     */
    protected static function f1Entity($dado)
    {
        //5 campos
        $aFields = [
            ['tipo1',1,'N'],
            ['versao',3,'N'],
            ['prestadorIM',8,'N'],
            ['dtIni',8,'N'],
            ['dtFim',8,'N']
        ];
        self::extract($dado, $aFields);
    }
    
    private static function extract($dado, $aFields)
    {
        $ini = 0;
        $x = 0;
        $pos = 0;
        foreach ($aFields as $field) {
            $var = $field[0];
            self::$$var = substr($dado, $pos, $field[1]);
            $x++;
            $pos += $field[1];
        }
    }
    
    /**
     * REGISTRO TIPO 2 – DETALHE
     * Versão 001
     * @param type $dados
     */
    protected static function f2Entity($dados)
    {
        //26 campos
        $aFields = [];
    }
    
    /**
     * REGISTRO TIPO 3 - DETALHE (EXCLUSIVO PARA CUPONS)
     * Versão 001 e 002
     * @param string $dados
     */
    protected static function f3Entity($dados)
    {
        //14 campos
        $aFields = [];
    }
    
    /**
     * REGISTRO TIPO 5 – DETALHE DO INTERMEDIÁRIO DO SERVIÇO
     * Versão 001 e 002
     * @param string $dado
     */
    protected static function f5Entity($dado)
    {
        //5 campos
        $aFields = [];
    }
    
    /**
     * REGISTRO TIPO 6 – DETALHE
     * Versão 002
     * @param string $dado
     */
    protected static function f6Entity($dado)
    {
        //38 campos
        $aFields = [];
    }
    
    /**
     * REGISTRO TIPO 9 – RODAPÉ
     * Versão 001 e 002
     * @param string $dado
     */
    protected static function f9Entity($dado)
    {
        //4 campos
        $aFields = [
            ['tipo9',1,'N'],
            ['num',7,'N'],
            ['valorTotalServicos',15,'N'],
            ['valorTotalDeducoes',15,'N']
        ];
        self::extract($dado, $aFields);
    }
    
    /**
     * zArray2xml
     * Converte um lote de RPS em um array de txt em um ou mais RPS
     *
     * @param  array $aDados
     * @return string
     * @throws Exception\RuntimeException
     */
    protected static function zArray2Rps($aDados = array())
    {
        foreach ($aDados as $dado) {
            $metodo = 'f'.substr($dado, 0, 1).'Entity';
            if (! method_exists(__CLASS__, $metodo)) {
                $msg = "O txt tem um metodo não definido!! $dado";
                throw new Exception\RuntimeException($msg);
            }
            self::$metodo($dado);
        }
    }
}
