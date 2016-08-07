<?php

	if(!isset($_GET['protocolo']) || !is_numeric($_GET['protocolo'])){
		header('location: nfe_protocolos.php');
	}
	
	$protocolo = $_GET['protocolo'];


	
	//NFSE
	include_once("classes/NFSe.class.php");

	$nfse = new NFSe();

	$xml_consulta = 'nfe/consulta_lote/'.time().'_consulta_'.$protocolo.'.xml';
	$xml_consulta_assinado = 'nfe/consulta_lote_assinado/'.time().'_consulta_protocolo_'.$protocolo.'.xml';
		
	if($nfse->ConsultarSituacaoLoteRps($xml_consulta, $protocolo)){
		
		$retornoValidacao = $nfse->validarXML($xml_consulta, 'servico_consultar_situacao_lote_rps_envio_v03.xsd');
		
		if($retornoValidacao['status'] == true){
								
			echo "<p>".$retornoValidacao['mensagem']."</p>";
		
			$retornoAssinatura = $nfse->assinarXML($xml_consulta, $xml_consulta_assinado, 'ConsultarSituacaoLoteRpsEnvio', array('ConsultarSituacaoLoteRpsEnvio'));
			
			if($retornoAssinatura){						
			
				$retornoValidacao = $nfse->validarXML('xml_consulta_assinado.xml', 'servico_consultar_situacao_lote_rps_envio_v03.xsd');
				
				if($retornoValidacao['status'] == true){
				
					echo '<p>'.$retornoValidacao['mensagem']."</p>";
				
					$nfse->enviarXML($xml_consulta_assinado, 'ConsultarSituacaoLoteRpsV3');
				}
				else{
					
					echo '<p>'.$retornoValidacao['mensagem']."</p>";	
				}
			}
			else{
				echo '<p>Erro ao gerar XML assinado</p>';	
			}
		}
		else{
			echo '<p>'.$retornoValidacao['mensagem'].'</p>';
		}
	}
	else{
		echo '<p>Erro ao gerar o arquivo XML de consulta.</p>';	
	}
?>
			 