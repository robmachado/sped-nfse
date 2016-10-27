<?php

namespace NFePHP\NFSe\Models\Issnet\Factories;

/**
 * Classe para a construção dos cabaçalhos XML relativo aos serviços
 * dos webservices do modelo Issnet
 *
 * @category  NFePHP
 * @package   NFePHP\NFSe\Models\Issnet\Factories\Header
 * @copyright NFePHP Copyright (c) 2016
 * @license   http://www.gnu.org/licenses/lgpl.txt LGPLv3+
 * @license   https://opensource.org/licenses/MIT MIT
 * @license   http://www.gnu.org/licenses/gpl.txt GPLv3+
 * @author    Roberto L. Machado <linux.rlm at gmail dot com>
 * @link      http://github.com/nfephp-org/sped-nfse for the canonical source repository
 */

use NFePHP\NFSe\Common\Header as HeaderBase;

class Header extends HeaderBase
{
	/**
	 * Renderiza as tag do cabecalho
	 * @param int $versao
	 * @param int $remetenteTipoDoc
	 * @param string $remetenteCNPJCPF
	 * @param string $transacao
	 * @param string $cnpj
	 * @param string $cpf
	 * @param string $im
	 * @param date $dtIni
	 * @param date $dtFim
	 * @param int $pagina
	 * @param int $qtdRPS
	 * @param float $valorTotalServicos
	 * @param float $valorTotalDeducoes
	 * @param string $numeroLote
	 * @param string $prestadorIM
	 * @return string
	 */
	public static function render(
		$versao = null,
		$remetenteTipoDoc = null,
		$remetenteCNPJCPF = null,
		$transacao = null,
		$cnpj = null,
		$cpf = null,
		$im = null,
		$dtIni = null,
		$dtFim = null,
		$pagina = null,
		$qtdRPS = null,
		$valorTotalServicos = null,
		$valorTotalDeducoes = null,
		$numeroLote = null,
		$prestadorIM = null
	) {
		$content = "";//"<Cabecalho xmlns=\"\" Versao=\"$versao\">";
		$content .= "<Prestador>";
		$content .= "<CpfCnpj>";
		if ($remetenteTipoDoc == '2') {
			$content .= self::check('Cnpj', $remetenteCNPJCPF);
		} else {
			$content .= self::check('Cpf', $remetenteCNPJCPF);
		}
		$content .= "</CpfCnpj>";
		$content .= self::check('InscricaoMunicipal', $im);
		$content .= "</Prestador>";

		$content .= self::check('NumeroNfse', $numeroLote);
		$content .= "<PeriodoEmissao>";
		$content .= self::check('DataInicial', $dtIni);
		$content .= self::check('DataFinal', $dtFim);
		$content .= "</PeriodoEmissao>";

		$content .= "<Tomador>";
		if ($cnpj != '') {
			$content .= "<CpfCnpj><Cnpj>$cnpj</Cnpj></CpfCnpj>";
		} elseif ($cpf != '') {
			$content .= "<CpfCnpj><Cpf>$cpf</Cpf></CpfCnpj>";
		}
		$content .= self::check('InscricaoMunicipal', $prestadorIM);
		$content .= "</Tomador>";

//		$content .= "<IntermediarioServico>";
//		$content .= "<CpfCnpj>";
//		$content .= "<Cnpj>38693524000188</Cnpj>";
//		$content .= "<CpfCnpj>";
//		$content .= "<RazaoSocial>sdfsfsdfsf</RazaoSocial>";
//		$content .= "<InscricaoMunicipal>812005</InscricaoMunicipal>";
//		$content .= "</IntermediarioServico>";

//		$content .= self::check('transacao', $transacao);
		$content .= self::check('NumeroPagina', $pagina);

		if ($valorTotalServicos != 0) {
			$content .= self::check('QtdRPS', $qtdRPS);
			$content .= "<ValorTotalServicos>".number_format($valorTotalServicos, 2, '.', '')."</ValorTotalServicos>";
			$content .= "<ValorTotalDeducoes>".number_format($valorTotalDeducoes, 2, '.', '')."</ValorTotalDeducoes>";
		}
//		$content .= "</Cabecalho>";
		return $content;
	}
}
