<?php
	
	//echo dirname(__FILE__);
	
	//exit;

	header("Content-Type: text/plain");
	
	include_once("classes/NFSe.class.php");
	
	$nfse = new NFSe();
	
	$arquivoRPS = 'rps/rps_1.xml';
	$arquivoRPSAssinado = 'rps_assinado/rps_1_assinado.xml';
	
	$dadosTomador = array('cpf' => '');
	
	//Gera o XML da RPS
	if($nfse->gerarRPS($arquivoRPS, $dadosTomador)){
		
		echo '<p>Arquivo gerado com sucesso</p>';
		
		//Valida se o XML da RPS é valido perante ao schema XSD
		$retornoValidacao = $nfse->validarXML($arquivoRPS, 'Schemas/servico_enviar_lote_rps_envio_v03.xsd');
		
		if($retornoValidacao['status'] == true){
			
			echo '<p>'.$retornoValidacao['mensagem'].'</p>';
			
			//Assina o xml da RPS
			if($nfse->assinarXML($arquivoRPS, $arquivoRPSAssinado, 'EnviarLoteRpsEnvio', array('EnviarLoteRpsEnvio'))){
				echo '<p>Arquivo assinado com sucesso</p>';
				
				//Valida o XML da RPS Assinado
				$retornoValidacaoAss = $nfse->validarXML($arquivoRPSAssinado, 'Schemas/servico_enviar_lote_rps_envio_v03.xsd');
				
				if($retornoValidacaoAss['status'] == true){
					echo '<p>'.$retornoValidacaoAss['mensagem'].'</p>';
					
					//Faz o envio da RPS assinada para a Ginfes	
					$nfse->enviarXML($arquivoRPSAssinado, 'RecepcionarLoteRpsV3');
				}
				else{
					echo '<p>'.$retornoValidacaoAss['mensagem'].'</p>';
				}
			}
			else{
				echo '<p>Erro ao assinar o arquivo</p>';	
			}		
			
		}
		else{
			echo '<p>'.$retornoValidacao['mensagem'].'</p>';	
		}
	}
	else{
		echo '<p>Erro ao gerar o arquivo XML de RPS</p>';	
	}
	/*
	
	$xml_consulta = 'consulta_lote/'.time().'_consulta_6083435.xml';
	$xml_consulta_assinado = 'consulta_lote/'.time().'_consulta_6083435.xml';
	
	$nfse->ConsultarSituacaoLoteRps($xml_consulta, '');
	
	$retornoValidacao = $nfse->validarXML($xml_consulta, 'servico_consultar_situacao_lote_rps_envio_v03.xsd');
	
	echo "\n\n\n".$retornoValidacao['mensagem']."\n\n\n";

	echo $nfse->assinarXML($xml_consulta, $xml_consulta_assinado, 'ConsultarSituacaoLoteRpsEnvio', array('ConsultarSituacaoLoteRpsEnvio'));
	
	$retornoValidacao = $nfse->validarXML('xml_consulta_assinado.xml', 'servico_consultar_situacao_lote_rps_envio_v03.xsd');
	
	echo "\n\n\n".$retornoValidacao['mensagem']."\n\n\n";
	
	$nfse->enviarXML($xml_consulta_assinado, 'ConsultarSituacaoLoteRpsV3');
	*/
	
	//$nfse->cabecalho();
	//$nfse->validarCabecalhoXML();

	
	
	