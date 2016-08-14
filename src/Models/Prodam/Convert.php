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
    protected static $f1 = [];
    protected static $f2 = [];
    protected static $f3 = [];
    protected static $f5 = [];
    protected static $f6 = [];
    protected static $f9 = [];
    
    protected static $bF = [
        ['tipo',1,'N', 0],//1
        ['tpRPS',5,'C', ''],//2
        ['serie',5,'N', 0],//3
        ['numero',12,'N', 0],//4
        ['dtEmi',8,'D', 'Y-m-d'],//5
        ['situacao',1,'C', ''],//6
        ['valor',15,'N', 2],//7
        ['deducoes',15,'N', 2],//8
        ['codigo',5,'N', 0],//9
        ['aliquota',4,'N', 4],//10
        ['issRetido',1,'C', ''],//11
        ['indTomador',1,'N', 0],//12
        ['cnpjcpfTomador',14,'N', 0]
    ];

    public static function toRps($txt = '')
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
        self::validTipos();
        //o numero de notas criadas será a quantidade de tipo 2 ou 3 ou 6
        self::$numRps = self::$contTipos['2']+self::$contTipos['3']+self::$contTipos['6'];
        for ($x=0; $x < self::$numRps; $x++) {
            self::$aRps[] = new Rps();
        }
        self::zArray2Rps($aDados);
        self::loadRPS();
        return self::$aRps;
    }
    
    protected static function validTipos()
    {
        $msg = '';
        if ((self::$contTipos['1'] == 0 || self::$contTipos['1'] > 1) ||
            (self::$contTipos['9'] == 0 || self::$contTipos['9'] > 1)
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
        if (!empty($msg)) {
            throw new InvalidArgumentException($msg);
        }
    }
    
    /**
     * Carrega os RPS contidos no txt
     */
    protected static function loadRPS()
    {
        if (count(self::$f2) > 0) {
            $fData = self::$f2;
        } elseif (count(self::$f3) > 0) {
            $fData = self::$f3;
        } else {
            $fData = self::$f6;
        }
        $x = 0;
        //return $fData[$x]['tpRPS'];
        foreach (self::$aRps as $rps) {
            $rps->tipo($fData[$x]['tpRPS']);
            $rps->serie($fData[$x]['serie']);
            $rps->data($fData[$x]['dtEmi']);
            $rps->numero($fData[$x]['numero']);
            $rps->prestador(self::$f1['prestadorIM']);
            $cnpj = '';
            $cpf = '';
            if ($fData[$x]['indTomador'] == '2') {
                $cnpj = $fData[$x]['cnpjcpfTomador'];
            } elseif ($fData[$x]['indTomador'] == '1') {
                $cpf = $fData[$x]['cnpjcpfTomador'];
            }
            $rps->tomador(
                $fData[$x]['razaoTomador'],
                $cnpj,
                $cpf,
                $fData[$x]['ieTomador'],
                $fData[$x]['imTomador'],
                $fData[$x]['emailTomador']
            );
            $rps->tomadorEndereco(
                $fData[$x]['tpEndTomador'],
                $fData[$x]['logradouroTomador'],
                $fData[$x]['numTomador'],
                $fData[$x]['cplTomador'],
                $fData[$x]['bairroTomador'],
                $fData[$x]['cidadeTomador'],
                $fData[$x]['ufTomador'],
                $fData[$x]['cepTomador']
            );
            $rps->codigoServico($fData[$x]['codigo']);
            $rps->discriminacao($fData[$x]['discriminacao']);
            $rps->aliquotaServico($fData[$x]['aliquota']);
            
            $x++;
        }
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
            ['tipo',1,'N', 0],
            ['versao',3, 'N', 0],
            ['prestadorIM',8,'N', 0],
            ['dtIni',8,'D', 'Y-m-d'],
            ['dtFim',8,'D', 'Y-m-d']
        ];
        self::$f1 = self::extract($dado, $aFields);
    }
    
    /**
     * REGISTRO TIPO 2 – DETALHE
     * Versão 001
     * @param string $dado
     */
    protected static function f2Entity($dado)
    {
        //26 campos
        $aFields = self::$bF;
        $aadFields = [
            ['imTomador',8,'N', 0],//14
            ['ieTomador',12,'N', 0],//15
            ['razaoTomador',75,'C', ''],//16
            ['tpEndTomador',3,'C', ''],//17
            ['logradouroTomador',50,'C', ''],//18
            ['numTomador',10,'C', ''],//19
            ['cplTomador',30,'C', ''],//20
            ['bairroTomador',30,'C', ''],//21
            ['cidadeTomador',50,'C', ''],//22
            ['ufTomador',2,'C', ''],//23
            ['cepTomador',8,'N', 0],//24
            ['emailTomador',75,'C', ''],//25
            ['discriminacao',1000,'C', '']//26
        ];
        $aFields = array_merge($aFields, $aadFields);
        self::$f2[] = self::extract($dado, $aFields);
    }
    
    /**
     * REGISTRO TIPO 3 - DETALHE (EXCLUSIVO PARA CUPONS)
     * Versão 001 e 002
     * @param string $dado
     */
    protected static function f3Entity($dado)
    {
        //14 campos
        $aFields = self::$bF;
        $aFields[] = ['discriminacao',1000,'C', ''];//14
        self::$f3[] = self::extract($dado, $aFields);
    }
    
    /**
     * REGISTRO TIPO 5 – DETALHE DO INTERMEDIÁRIO DO SERVIÇO
     * Versão 001 e 002
     * @param string $dado
     */
    protected static function f5Entity($dado)
    {
        //5 campos
        $aFields = [
            ['tipo',1,'N', 0],
            ['indicador',1,'N', 0],
            ['intermediarioCNPJ',14,'N', 0],
            ['intermediarioIM',8,'N', 0],
            ['intermediarioEmail',75,'C', '']
        ];
        self::$f5[] = self::extract($dado, $aFields);
    }
    
    /**
     * REGISTRO TIPO 6 – DETALHE
     * Versão 002
     * @param string $dado
     */
    protected static function f6Entity($dado)
    {
        //38 campos
        $aFields = self::$bF;
        $aadFields = [
            ['imTomador',8,'N', 0],//14
            ['ieTomador',12,'N', 0],//15
            ['razaoTomador',75,'C', ''],//16
            ['tpEndTomador',3,'C', ''],//17
            ['logradouroTomador',50,'C', ''],//18
            ['numTomador',10,'C', ''],//19
            ['cplTomador',30,'C', ''],//20
            ['bairroTomador',30,'C', ''],//21
            ['cidadeTomador',50,'C', ''],//22
            ['ufTomador',2,'C', ''],//23
            ['cepTomador',8,'N', 0],//24
            ['emailTomador',75,'C', ''],//25
            ['pis',15,'N', 2],//26
            ['cofins',15,'N', 2],//27
            ['inss',15,'N', 2],//28
            ['ir',15,'N', 2],//29
            ['cssl',15,'N', 2],//30
            ['cargaTribValor',15,'N', 2],//31
            ['cargaTribPerc',5,'N', 4],//32
            ['cargaTribFonte',10,'C', ''],//33
            ['cei',12,'N', 0],//34
            ['matriculaObra',12,'N', 0],//35
            ['cMunPrestacao',7,'N', 0],//36
            ['reservado',200,'C', ''],//37
            ['discriminacao',1000, 'C', '']//38
        ];
        $aFields = array_merge($aFields, $aadFields);
        self::$f6[] = self::extract($dado, $aFields);
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
            ['tipo',1,'N', 0],
            ['num',7,'N', 0],
            ['valorTotalServicos',15,'N', 2],
            ['valorTotalDeducoes',15,'N', 2]
        ];
        self::$f9 = self::extract($dado, $aFields);
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
    
    /**
     * Extrai os dados da string em campos de array
     * @param string $dado
     * @param array $aFields
     * @return array
     */
    private static function extract($dado, $aFields)
    {
        $ini = 0;
        $x = 0;
        $pos = 0;
        $aData = [];
        $len = strlen($dado);
        foreach ($aFields as $field) {
            if ($pos >= $len) {
                $aData[$field[0]] = '';
            } else {
                $tipo = $field[2];
                $df = substr($dado, $pos, $field[1]);
                if ($tipo == 'N') {
                    //converter representação
                    $df = $df/(10**$field[3]);
                    //formatar dado numerico
                    $df = number_format($df, $field[3], '.', '');
                } elseif ($tipo == 'C') {
                    //formatar dado string
                    if ($field[3] != '') {
                        $df = preg_replace($field[3], '', $df);
                    }
                } elseif ($tipo == 'D') {
                    //formatar dado data
                    $df = substr($df, 0, 4) . '-' . substr($df, 4, 2) . '-' . substr($df, 6, 2);
                }
                $aData[$field[0]] = $df;
            }
            $x++;
            $pos += $field[1];
        }
        return $aData;
    }
}
