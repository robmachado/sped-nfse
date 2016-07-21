<?php

class NFSe{
	
	public $cnpj = ""; //CNPJ da empresa
	public $inscricaoMunicipal = ""; //Inscrição Municipal da Empresa
	public $caminhoCertificado = "certificado/certificado.pfx"; //Caminho do Certificado
	public $senhaCertificado = ""; //Senha do certificado
	public $codigoServico = ""; //Código do Serviço
	public $codigoAtividade = ""; // Código da Atividade
	public $codigoMunicipioIBGE = ""; //Código do municipio junto ao IBGE
	
	//Caminho do certificado em arquivo .PEM
	public $certKey = 'nfe/chaves/arquivo.pem';
	
	//Caminho da chave privada em arquivo .PEM
	public $priKey = 'nfe/chaves/arquivo.pem';
	
	//Caminho da chave publica em arquivo .PEM
	public $pubKey = 'nfe/chaves/arquivo.pem';
	
	public $caminhoSchemas;
	
	public $xmlGerado;
	public $xmlAssinado;
	public $xmlCabecalho;
	
	public $webservice = array(
		'homologacao' => 'https://homologacao.ginfes.com.br/ServiceGinfesImpl?wsdl',
		'producao' => 'https://producao.ginfes.com.br/ServiceGinfesImpl?wsdl'
	);
	
	public $urlGinfes = "http://www.ginfes.com.br/";
	public $schemasServicos = array(
		"Cabecalho" => "cabecalho_v03.xsd",
		"Tipo" => "tipos_v03.xsd",
		"TipoV2" => "tipos",
		"EnviarLoteRpsEnvio" => "servico_enviar_lote_rps_envio_v03.xsd",
		"ConsultarSituacaoLoteRpsEnvio" => "servico_consultar_situacao_lote_rps_envio_v03.xsd",
		"ConsultarLoteRpsEnvio" => "servico_consultar_lote_rps_envio_v03.xsd",
		"ConsultarNfseRpsEnvio" => "servico_consultar_nfse_rps_envio_v03.xsd",
		"CancelarNfseEnvio" => "servico_cancelar_nfse_envio_v03.xsd",
		"CancelarNfseEnvioV2" => "servico_cancelar_nfse_envio"
	);
	
	public $ValorServicos = '20.00';
	public $BaseCalculo = '20.00';
	public $aliquota_nota = '0.03'; //3%
	public $ValorLiquidoNfse = '20.00';
	public $ValorIss = '0.60';
	
	public $codigo_rps;
	public $numero_rps;
	
	public $codigo_lote;
	public $numero_lote;
	
	public function gerarRPS($arquivoRPS, $dadosTomador = array(), $descricao_servico, $data_competencia){
		
		$xmlRPS = new DOMDocument("1.0", "utf-8");

		$xmlRPS->formatOutput = true;
				
		//Raiz do XML
		$root = $xmlRPS->createElementNS($this->urlGinfes . $this->schemasServicos['EnviarLoteRpsEnvio'], 'EnviarLoteRpsEnvio');
			
		//Nó LoteRps
		$LoteRps = $xmlRPS->createElementNS($this->urlGinfes . $this->schemasServicos['EnviarLoteRpsEnvio'], 'LoteRps');
		$IdLoteRps = $xmlRPS->createAttribute('Id');
		$IdLoteRps->value = $this->codigo_lote;
		
		//Filhos de LoteRps
		$NumeroLote = $xmlRPS->createElementNS($this->urlGinfes . $this->schemasServicos['Tipo'], 'tipos:NumeroLote', $this->numero_lote);

		$CnpjRPS = $xmlRPS->createElementNS($this->urlGinfes . $this->schemasServicos['Tipo'], 'Cnpj', $this->cnpj);

		$InscricaoMunicipalRPS = $xmlRPS->createElementNS($this->urlGinfes . $this->schemasServicos['Tipo'], 'InscricaoMunicipal', $this->inscricaoMunicipal);

		$QuantidadeRps = $xmlRPS->createElementNS($this->urlGinfes . $this->schemasServicos['Tipo'], 'QuantidadeRps', '1');
		
		//Nó ListaRps
		$ListaRps = $xmlRPS->createElementNS($this->urlGinfes . $this->schemasServicos['Tipo'], 'ListaRps');
		
		//Filhos de ListaRps
		$Rps = $xmlRPS->createElementNS($this->urlGinfes . $this->schemasServicos['Tipo'],'Rps');
		
		//Nó InfRps
		$InfRps = $xmlRPS->createElementNS($this->urlGinfes . $this->schemasServicos['Tipo'], 'InfRps');
		$IdRps = $xmlRPS->createAttribute('Id');
		$IdRps->value = $this->codigo_rps;
		
		//$DataEmissao = $xmlRPS->createElementNS($this->urlGinfes . $this->schemasServicos['Tipo'] ,'DataEmissao', date('Y-m-d').'T'.date('H:s:i'));
		
		$DataEmissao = $xmlRPS->createElementNS($this->urlGinfes . $this->schemasServicos['Tipo'] ,'DataEmissao', $data_competencia);
		
		/**********************************************
		Código de natureza da operação
		1 – Tributação no município
		2 - Tributação fora do município
		3 - Isenção
		4 - Imune
		5 –Exigibilidade suspensa por decisão judicial
		6 – Exigibilidade suspensa por procedimento
		administrativo
		**********************************************/
		$NaturezaOperacao = $xmlRPS->createElementNS($this->urlGinfes . $this->schemasServicos['Tipo'], 'NaturezaOperacao', 1);
		
		/*************************		
		Identificação de Sim/Não
		1 - Sim
		2 – Não
		*************************/
		$OptanteSimplesNacional = $xmlRPS->createElementNS($this->urlGinfes . $this->schemasServicos['Tipo'], 'OptanteSimplesNacional', 2);
		
		/*************************		
		Identificação de Sim/Não
		1 - Sim
		2 – Não
		*************************/
		$IncentivadorCultural = $xmlRPS->createElementNS($this->urlGinfes . $this->schemasServicos['Tipo'], 'IncentivadorCultural', 2);
		
		/*************************
		Código de status do RPS
		1 – Normal
		2 – Cancelado
		*************************/
		$Status = $xmlRPS->createElementNS($this->urlGinfes . $this->schemasServicos['Tipo'], 'Status', 1);
		
		$Servico = $xmlRPS->createElementNS($this->urlGinfes . $this->schemasServicos['Tipo'], 'Servico');
		$Prestador = $xmlRPS->createElementNS($this->urlGinfes . $this->schemasServicos['Tipo'], 'Prestador');
		$Tomador = $xmlRPS->createElementNS($this->urlGinfes . $this->schemasServicos['Tipo'], 'Tomador');
		
		//Nó IdentificacaoRps
		$IdentificacaoRps = $xmlRPS->createElementNS($this->urlGinfes . $this->schemasServicos['Tipo'], 'IdentificacaoRps');
		
		//Filhos IdentificacaoRps
		$IdentificacaoNumero = $xmlRPS->createElementNS($this->urlGinfes . $this->schemasServicos['Tipo'], 'Numero', $this->numero_rps);
		$IdentificacaoSerie = $xmlRPS->createElementNS($this->urlGinfes . $this->schemasServicos['Tipo'], 'Serie', 'NFSE');
		
		/********************************
		1 - RPS
		2 - Nota fiscal conjugada (Mista)
		3 - Cupom
		********************************/
		$IdentificacaoTipo = $xmlRPS->createElementNS($this->urlGinfes . $this->schemasServicos['Tipo'], 'Tipo', '1');
		
		//Filhos de Servico
		$Valores = $xmlRPS->createElementNS($this->urlGinfes . $this->schemasServicos['Tipo'], 'Valores');
		
		$ItemListaServico = $xmlRPS->createElementNS($this->urlGinfes . $this->schemasServicos['Tipo'], 'ItemListaServico', $this->codigoServico);
		
		$CodigoTribMun = $xmlRPS->createElementNS($this->urlGinfes . $this->schemasServicos['Tipo'], 'CodigoTributacaoMunicipio', $this->codigoAtividade);
		
		/*************************************************
		Não adicionar acentos na descriminação do serviço,
		caso coloque acentos será apresentado o erro E300
		(Erro ao converter documento em XML.)
		*************************************************/
		$Discriminacao = $xmlRPS->createElementNS($this->urlGinfes . $this->schemasServicos['Tipo'], 'Discriminacao', $descricao_servico);
		
		$CodigoMunicipio = $xmlRPS->createElementNS($this->urlGinfes . $this->schemasServicos['Tipo'], 'CodigoMunicipio', $this->codigoMunicipioIBGE);
		
		//Filhos de Valores
		$ValorServicos = $xmlRPS->createElementNS($this->urlGinfes . $this->schemasServicos['Tipo'], 'ValorServicos', $this->ValorServicos);
		$ValorDeducoes = $xmlRPS->createElementNS($this->urlGinfes . $this->schemasServicos['Tipo'], 'ValorDeducoes', '0.00');
		$ValorPis = $xmlRPS->createElementNS($this->urlGinfes . $this->schemasServicos['Tipo'], 'ValorPis', '0.00');
		$ValorCofins = $xmlRPS->createElementNS($this->urlGinfes . $this->schemasServicos['Tipo'], 'ValorCofins', '0.00');
		$ValorInss = $xmlRPS->createElementNS($this->urlGinfes . $this->schemasServicos['Tipo'], 'ValorInss', '0.00');
		$ValorIr = $xmlRPS->createElementNS($this->urlGinfes . $this->schemasServicos['Tipo'], 'ValorIr', '0.00');
		$ValorCsll = $xmlRPS->createElementNS($this->urlGinfes . $this->schemasServicos['Tipo'], 'ValorCsll', '0.00');

		/*************************		
		Identificação de Sim/Não
		1 - Sim
		2 – Não
		*************************/
		$IssRetido = $xmlRPS->createElementNS($this->urlGinfes . $this->schemasServicos['Tipo'], 'IssRetido', 2);
		
		$ValorIss = $xmlRPS->createElementNS($this->urlGinfes . $this->schemasServicos['Tipo'], 'ValorIss', $this->ValorIss);
		$OutrasRetencoes = $xmlRPS->createElementNS($this->urlGinfes . $this->schemasServicos['Tipo'], 'OutrasRetencoes', '0.00');
		$BaseCalculo = $xmlRPS->createElementNS($this->urlGinfes . $this->schemasServicos['Tipo'], 'BaseCalculo', $this->BaseCalculo);
		$Aliquota = $xmlRPS->createElementNS($this->urlGinfes . $this->schemasServicos['Tipo'], 'Aliquota', $this->aliquota_nota);
		$ValorLiquidoNfse = $xmlRPS->createElementNS($this->urlGinfes . $this->schemasServicos['Tipo'], 'ValorLiquidoNfse', $this->ValorLiquidoNfse);
		
		//Filhos de Prestador
		$Cnpj = $xmlRPS->createElementNS($this->urlGinfes . $this->schemasServicos['Tipo'], 'Cnpj', $this->cnpj);
		$InscricaoMunicipal = $xmlRPS->createElementNS($this->urlGinfes . $this->schemasServicos['Tipo'], 'InscricaoMunicipal', $this->inscricaoMunicipal);
		
		//Filhos de Tomador
		$IdentificacaoTomador = $xmlRPS->createElementNS($this->urlGinfes . $this->schemasServicos['Tipo'], 'IdentificacaoTomador');
		$RazaoSocial = $xmlRPS->createElementNS($this->urlGinfes . $this->schemasServicos['Tipo'], 'RazaoSocial', $dadosTomador['nome']);
		
		/******************
		DADOS DO TOMADOR
		******************/
		
		//Filhos de IdentificacaoTomador
		$CpfCnpj = $xmlRPS->createElementNS($this->urlGinfes . $this->schemasServicos['Tipo'], 'CpfCnpj');
				
		//Filhos de CpfCnpj
		$Cpf = $xmlRPS->createElementNS($this->urlGinfes . $this->schemasServicos['Tipo'], 'Cpf', $dadosTomador['cpf']);
		//Endereço Tomador
		/*
		$enderecoTomador = $xmlRPS->createElementNS($this->urlGinfes . $this->schemasServicos['Tipo'], 'Endereco');
		
		$enderecoTom = $xmlRPS->createElementNS($this->urlGinfes . $this->schemasServicos['Tipo'], 'Endereco', '');
		$numeroTom = $xmlRPS->createElementNS($this->urlGinfes . $this->schemasServicos['Tipo'], 'Numero', '');
		$complementoTom = $xmlRPS->createElementNS($this->urlGinfes . $this->schemasServicos['Tipo'], 'Complemento', '');
		$bairroTom = $xmlRPS->createElementNS($this->urlGinfes . $this->schemasServicos['Tipo'], 'Bairro', 'Paqueta');
		
		//Código do municipio junto ao IBGE
		$codMunTom = $xmlRPS->createElementNS($this->urlGinfes . $this->schemasServicos['Tipo'], 'CodigoMunicipio', '0');
		$ufTom = $xmlRPS->createElementNS($this->urlGinfes . $this->schemasServicos['Tipo'], 'Uf', '');
		$cepTom = $xmlRPS->createElementNS($this->urlGinfes . $this->schemasServicos['Tipo'], 'Cep', '');
		*/

		//adicionar filhos ao nó LoteRps 
		$LoteRps->appendChild($IdLoteRps);
		$LoteRps->appendChild($NumeroLote);
		$LoteRps->appendChild($CnpjRPS);
		$LoteRps->appendChild($InscricaoMunicipalRPS);	
		$LoteRps->appendChild($QuantidadeRps);
		$LoteRps->appendChild($ListaRps);
		
		//Adiciona filhos ao nó ListaRps
		$ListaRps->appendChild($Rps);
		
		//Adiciona filhos ao nó Rps
		$Rps->appendChild($InfRps);		
		
		//Adiciona filhos de InfRps
		$InfRps->appendChild($IdRps);
		$InfRps->appendChild($IdentificacaoRps);
		$InfRps->appendChild($DataEmissao);
		$InfRps->appendChild($NaturezaOperacao);
		$InfRps->appendChild($OptanteSimplesNacional);
		$InfRps->appendChild($IncentivadorCultural);
		$InfRps->appendChild($Status);
		$InfRps->appendChild($Servico);
		$InfRps->appendChild($Prestador);
		$InfRps->appendChild($Tomador);
		
		//Adiciona filhos de Servico
		$Servico->appendChild($Valores);
		$Servico->appendChild($ItemListaServico);
		$Servico->appendChild($CodigoTribMun);
		$Servico->appendChild($Discriminacao);
		$Servico->appendChild($CodigoMunicipio);
		
		//Adiciona filhos de Valores
		$Valores->appendChild($ValorServicos);
		$Valores->appendChild($ValorDeducoes);
		$Valores->appendChild($ValorPis);
		$Valores->appendChild($ValorCofins);
		$Valores->appendChild($ValorInss);
		$Valores->appendChild($ValorIr);
		$Valores->appendChild($ValorCsll);
		$Valores->appendChild($IssRetido);
		$Valores->appendChild($ValorIss);
		$Valores->appendChild($OutrasRetencoes);
		$Valores->appendChild($BaseCalculo);
		$Valores->appendChild($Aliquota);
		$Valores->appendChild($ValorLiquidoNfse);
		
		//Adiciona filhos de Prestador
		$Prestador->appendChild($Cnpj);
		$Prestador->appendChild($InscricaoMunicipal);
		
		//Adiciona filhos do endereço tomador
		/*
		$enderecoTomador->appendChild($enderecoTom);
		$enderecoTomador->appendChild($numeroTom);
		$enderecoTomador->appendChild($complementoTom);
		$enderecoTomador->appendChild($bairroTom);
		$enderecoTomador->appendChild($codMunTom);
		$enderecoTomador->appendChild($ufTom);
		$enderecoTomador->appendChild($cepTom);
		*/
		
		//Adiciona filhos do Tomador
		$Tomador->appendChild($IdentificacaoTomador);
		$Tomador->appendChild($RazaoSocial);
		//$Tomador->appendChild($enderecoTomador);
		
		//Adiciona filhos do IdentificacaoTomador
		$IdentificacaoTomador->appendChild($CpfCnpj);
		
		//Adiciona filhos de CpfCnpj
		$CpfCnpj->appendChild($Cpf);
		
		//Adiciona filhos de IdentificacaoRps
		$IdentificacaoRps->appendChild($IdentificacaoNumero);
		$IdentificacaoRps->appendChild($IdentificacaoSerie);
		$IdentificacaoRps->appendChild($IdentificacaoTipo);
			
		//Adiciona o nó LoteRps ao nó root		
		$root->appendChild($LoteRps);
		
		//$root->appendChild($xmltip);
		$xmlRPS->appendChild($root);
		
		return $xmlRPS->save($arquivoRPS);
		
		//$retornoXML = $xmlRPS->saveXML();
		
		//$this->xmlGerado = $retornoXML;
		
		//echo $retornoXML;
	}
		
	public function validarXML($arquivo, $schema){

		$return = array();
		
		if(!file_exists($arquivo) || !file_exists($this->caminhoSchemas.$schema)){
			$return['status'] = false;
			$return['mensagem'] = 'Arquivo e/ou Schema não encontrado';
		}
		else{
			
			libxml_use_internal_errors(true);
	
			$objDom = new DomDocument();
			
			$objDom->load($arquivo);
	
			if(!$objDom->schemaValidate($this->caminhoSchemas.$schema)) {
			
				$arrayAllErrors = libxml_get_errors();
				
				//var_dump($arrayAllErrors);
				
				$return['mensagem'] = "";
				
				foreach($arrayAllErrors as $key => $chave){
					$return['mensagem'] .= "Erro: ".$arrayAllErrors[$key]->code."<br/>";
					$return['mensagem'] .= "Mensagem: ".$arrayAllErrors[$key]->message."<br/>";
					$return['mensagem'] .= "Arquivo: ".$arrayAllErrors[$key]->file."<br/>";
					$return['mensagem'] .= "Linha: ".$arrayAllErrors[$key]->line."<br/><br/>";
				}
				
				$return['status'] = false;
			   
			} else {
				
				$return['status'] = true;
				$return['mensagem'] = 'XML obedece às regras definidas no arquivo XSD ('.$this->caminhoSchemas.$schema.')';
			}
		}
		
		return $return;
	}	

	public function enviarXML($arquivoRPSAssinado, $servico){	
						
		$xmlEnvio = new DOMDocument("1.0");		

		//Carrega o xml a ser assinado
		$xmlEnvio->load($arquivoRPSAssinado);
		
		//Carrega o conteudo do XML
		$XmlAssinado = $xmlEnvio->saveXML();

		$objDom = new DomDocument();
		
		/* Carrega o arquivo XML */
		$objDom->load("nfe/cabecalho/cabecalho.xml");
		
		$cabecalho = $objDom->saveXML();
				
		try { 
			$options = array(
				'encoding'      => 'UTF-8',
				'verifypeer'    => false,
				'verifyhost'    => false,
				'soap_version'  => SOAP_1_2,
				'style'         => SOAP_DOCUMENT,
				'use'           => SOAP_LITERAL,
				'local_cert'    => $this->certKey,
				'trace'         => false,
				'compression'   => 0,
				'exceptions'    => true,
				'cache_wsdl'    => WSDL_CACHE_NONE,
		   	);
					 
			$soap_cliente = new SoapClient($this->webservice['producao'], $options);
			
			$param = array("arg0" => $cabecalho, "arg1" => $XmlAssinado);
			
			$retornoEnvio = $soap_cliente->__soapCall($servico, $param);

			return $retornoEnvio;
								
		} catch (SoapFault $E) {  
			return $E->faultcode.' ('.$E->faultstring.')'; 
		} 
		
		//echo $soap_cliente->__getLastResponse();		
	}
	
	public function enviarXML_V2($arquivoRPSAssinado, $servico){	
						
		$xmlEnvio = new DOMDocument("1.0");		

		//Carrega o xml a ser assinado
		$xmlEnvio->load($arquivoRPSAssinado);
		
		//Carrega o conteudo do XML
		$XmlAssinado = $xmlEnvio->saveXML();
				
		try { 
			$options = array(
				'encoding'      => 'UTF-8',
				'verifypeer'    => false,
				'verifyhost'    => false,
				'soap_version'  => SOAP_1_2,
				'style'         => SOAP_DOCUMENT,
				'use'           => SOAP_LITERAL,
				'local_cert'    => $this->certKey,
				'trace'         => false,
				'compression'   => 0,
				'exceptions'    => true,
				'cache_wsdl'    => WSDL_CACHE_NONE,
		   	);
					 
			$soap_cliente = new SoapClient($this->webservice['producao'], $options);
			
			$param = array("arg0" => $XmlAssinado);
			
			$retornoEnvio = $soap_cliente->__soapCall($servico, $param);

			return $retornoEnvio;
								
		} catch (SoapFault $E) {  
			return $E->faultcode.' ('.$E->faultstring.')'; 
		} 
		
		//echo $soap_cliente->__getLastResponse();		
	}
	
	public function gerarArquivosPem(){
		$caminhoCertificado = file_get_contents($this->caminhoCertificado);
		
        openssl_pkcs12_read($caminhoCertificado, $x509certdata, $this->senhaCertificado);
		
		//Chave publica
		$key = $x509certdata['pkey'];
		$pub = $x509certdata['cert'];
		
		$certificado = $pub."\r\n".$key;
		
		//Salva chave primaria
		file_put_contents($this->cnpj.'_priKEY.pem', $x509certdata['pkey']);
		
		//Salva chave publica
		file_put_contents($this->cnpj.'_pubKEY.pem', $x509certdata['cert']);
		
		//Salva o certificado
        file_put_contents($this->cnpj.'_certKEY.pem', $x509certdata['pkey']."\r\n".$x509certdata['cert']);
				
		$pidkey = openssl_pkey_get_private($x509certdata['pkey'], "12345678");		
	}
	
	public function cabecalho(){
		
		$xmlCab = new DOMDocument("1.0", "utf-8");

		$xmlCab->formatOutput = true;

		$cabecalho = $xmlCab->createElementNS($this->urlGinfes . $this->schemasServicos['Cabecalho'], 'ns1:cabecalho');
		
		$Versao = $xmlCab->createAttribute('versao');
		$Versao->value = 3;
		
		$versaoDados = $xmlCab->createElement('versaoDados', 3);
		
		//adicionar filhos ao nó LoteRps 
		$xmlCab->appendChild($cabecalho);
		
		//adicionar filhos ao nó cabecalho 
		$cabecalho->appendChild($Versao);
		$cabecalho->appendChild($versaoDados);		
		
		$xmlCab->save('cabecalho/cabecalho.xml');
		
		$retornoXMLCabecalho = $xmlCab->saveXML();
		
		$this->xmlCabecalho = $retornoXMLCabecalho;
		
		//echo $retornoXMLCabecalho;
	}
	
	public function validarCabecalhoXML(){
		libxml_use_internal_errors(true);

		//Cria um novo objeto da classe DomDocument
		$objDom = new DomDocument();
		
		//Carrega o arquivo XML
		$objDom->load("cabecalho.xml");
		
		//Tenta validar os dados utilizando o arquivo XSD
		if(!$objDom->schemaValidate("Schemas/cabecalho_v03.xsd")) {
					
			$arrayAllErrors = libxml_get_errors();
		   			
			var_dump($arrayAllErrors);
		   
		}
		else {
			echo "XML obedece às regras definidas no arquivo XSD!";
		}
	}
		
	/**
	* zCleanPubKey
	* Remove a informação de inicio e fim do certificado 
	* contido no formato PEM, deixando o certificado (chave publica) pronta para ser
	* anexada ao xml da NFe
	* @return string contendo o certificado limpo
	*/
    protected function zCleanPubKey()
    {
		//inicializa variavel
		$data = '';
		
		//carregar a chave publica
		$pubKey = file_get_contents($this->pubKey);
		
		//carrega o certificado em um array usando o LF como referencia
		$arCert = explode("\n", $pubKey);
		foreach ($arCert as $curData) {
			//remove a tag de inicio e fim do certificado
			if (strncmp($curData, '-----BEGIN CERTIFICATE', 22) != 0 &&
					strncmp($curData, '-----END CERTIFICATE', 20) != 0 ) {
				//carrega o resultado numa string
				$data .= trim($curData);
			}
		}

		return $data;
    }
	
	public function assinarXML($arquivoRPS, $arquivoRPSAssinado, $noInsercaoAss = "", $nosParaAss = array())
    {
		//Nós a ser assinado
		$signDataNodes = $nosParaAss;
		
		//Nó pai que será adicionada os nós da assinatura
		$appendNode = $noInsercaoAss;
		
		$uriId = "";
	
        $file = fopen($this->certKey, 'r'); //This->_cert o arquivo que contém a chave de certificação pública
        $contentCert = fread($file, filesize($this->certKey));
        fclose($file);

        $pkeyid = openssl_pkey_get_private($contentCert);
		
		//Carrega o XML a ser assinado		
		$xmlNSign = new DomDocument();
		
		$xmlNSign->load($arquivoRPS);
		
		$xml_sem_assinatura = $xmlNSign->saveXML();

        $order = array("\r\n", "\n", "\r", "\t");
        $replace = '';
        $docxml = str_replace($order, $replace, $xml_sem_assinatura);
		
        $xmldoc = new DOMDocument();
        $xmldoc->preservWhiteSpace = false;
        $xmldoc->formatOutput = false;

        if ($xmldoc->loadXML($docxml, LIBXML_NOBLANKS | LIBXML_NOEMPTYTAG)) {
            $root = $xmldoc->getElementsByTagName($appendNode)->item(0);
        } else {
            echo "Erro ao carregar XML, provavel erro na passagem do parâmetro docXML!!\n";
            exit;
        }

        $signData = null;
        if (count($signDataNodes) > 0) {
            foreach ($signDataNodes as $signDataNode) {
                $node = $xmldoc->getElementsByTagName($signDataNode)->item(0);
				
                $signData .= $node->C14N(false, false, null, null);
            }
        } else {
            $signData = $xmldoc->C14N(false, false, null, null);
        }

        //monta a tag da assinatura digital
        $Signature = $xmldoc->createElementNS("http://www.w3.org/2000/09/xmldsig#", 'dsig:Signature');
		
		$IdSignature = $xmldoc->createAttribute("Id");
		$IdSignature->value = $this->codigo_rps;
		
		$Signature->appendChild($IdSignature);
		
        $root->appendChild($Signature);
        $SignedInfo = $xmldoc->createElementNS("http://www.w3.org/2000/09/xmldsig#", 'SignedInfo');
        $Signature->appendChild($SignedInfo);

        //Cannocalization
        $newNode = $xmldoc->createElementNS("http://www.w3.org/2000/09/xmldsig#", 'CanonicalizationMethod');
        $SignedInfo->appendChild($newNode);
        $newNode->setAttribute('Algorithm', "http://www.w3.org/TR/2001/REC-xml-c14n-20010315");

        //SignatureMethod
        $newNode = $xmldoc->createElementNS("http://www.w3.org/2000/09/xmldsig#", 'SignatureMethod');
        $SignedInfo->appendChild($newNode);
        $newNode->setAttribute('Algorithm', "http://www.w3.org/2000/09/xmldsig#rsa-sha1");

        //calcular o hash dos dados
        $hashValue = hash('sha1', $signData, true);

        //converte o valor para base64 para serem colocados no xml
        $digValue = base64_encode($hashValue);

        //Reference
        $Reference = $xmldoc->createElementNS("http://www.w3.org/2000/09/xmldsig#", 'Reference');
        $SignedInfo->appendChild($Reference);

        //$Reference->setAttribute('URI', ($uriId) ? '#' . $uriId : '');
		$Reference->setAttribute('URI', '');

        //Transforms
        $Transforms = $xmldoc->createElementNS("http://www.w3.org/2000/09/xmldsig#", 'Transforms');
        $Reference->appendChild($Transforms);

        //Transform
        $newNode = $xmldoc->createElementNS("http://www.w3.org/2000/09/xmldsig#", 'Transform');
        $Transforms->appendChild($newNode);
        $newNode->setAttribute('Algorithm', "http://www.w3.org/2000/09/xmldsig#enveloped-signature");

        //Transform
        $newNode = $xmldoc->createElementNS("http://www.w3.org/2000/09/xmldsig#", 'Transform');
        $Transforms->appendChild($newNode);
        $newNode->setAttribute('Algorithm', "http://www.w3.org/TR/2001/REC-xml-c14n-20010315");

        //DigestMethod
        $newNode = $xmldoc->createElementNS("http://www.w3.org/2000/09/xmldsig#", 'DigestMethod');
        $Reference->appendChild($newNode);
        $newNode->setAttribute('Algorithm', "http://www.w3.org/2000/09/xmldsig#sha1");

        //DigestValue
        $newNode = $xmldoc->createElementNS("http://www.w3.org/2000/09/xmldsig#", 'DigestValue', $digValue);
        $Reference->appendChild($newNode);

        // extrai os dados a serem assinados para uma string
        $dados = $SignedInfo->C14N(false, false, NULL, NULL);

        //inicializa a variavel que irá receber a assinatura
        $signature = '';
		
        //executa a assinatura digital usando o resource da chave privada
        openssl_sign($dados, $signature, $pkeyid);
        openssl_free_key($pkeyid);

        //codifica assinatura para o padrao base64
        $signatureValue = base64_encode($signature);

        //SignatureValue
        $newNode = $xmldoc->createElementNS("http://www.w3.org/2000/09/xmldsig#", 'SignatureValue', $signatureValue);
        $Signature->appendChild($newNode);

        //KeyInfo
        $KeyInfo = $xmldoc->createElementNS("http://www.w3.org/2000/09/xmldsig#", 'KeyInfo');
        $Signature->appendChild($KeyInfo);

        //X509Data
        $X509Data = $xmldoc->createElement('X509Data');
        $KeyInfo->appendChild($X509Data);
		
		$pubKeyLimpo = $this->zCleanPubKey();

        //X509Certificate
        $newNode = $xmldoc->createElement('X509Certificate', str_replace(array("\r", "\n"), '', $pubKeyLimpo));
        $X509Data->appendChild($newNode);
		
		return $xmldoc->save($arquivoRPSAssinado);
		
		//$xmlAssinado = $xmldoc->saveXML();		
		
		//echo $xmlAssinado;
    }
	
	public function ConsultarSituacaoLoteRps($arquivoDeSaida, $protocoloParaConsulta){
		
		$xmlRPS = new DOMDocument("1.0", "utf-8");

		$xmlRPS->formatOutput = true;
				
		//Raiz do XML
		$root = $xmlRPS->createElementNS($this->urlGinfes . $this->schemasServicos['ConsultarSituacaoLoteRpsEnvio'], 'ConsultarSituacaoLoteRpsEnvio');

		$IdConsulta = $xmlRPS->createAttribute('Id');
		$IdConsulta->value = $this->codigo_rps;
		
		$Prestador = $xmlRPS->createElementNS($this->urlGinfes . $this->schemasServicos['ConsultarSituacaoLoteRpsEnvio'], 'Prestador');
		
		$Cnpj = $xmlRPS->createElementNS($this->urlGinfes . $this->schemasServicos['Tipo'], 'tipos:Cnpj', $this->cnpj);
		
		$InscricaoMunicipal = $xmlRPS->createElementNS($this->urlGinfes . $this->schemasServicos['Tipo'], 'tipos:InscricaoMunicipal', $this->inscricaoMunicipal);
		
		$Protocolo = $xmlRPS->createElementNS($this->urlGinfes . $this->schemasServicos['ConsultarSituacaoLoteRpsEnvio'], 'Protocolo', $protocoloParaConsulta);
		
		$Prestador->appendChild($Cnpj);
		$Prestador->appendChild($InscricaoMunicipal);
		
		$root->appendChild($Prestador);
		$root->appendChild($Protocolo);
		
		$root->appendChild($IdConsulta);
		
		$xmlRPS->appendChild($root);
		
		$xmlRPS->save($arquivoDeSaida);
		
		$retorno = $xmlRPS->saveXML();
		
		return $retorno;		
	}
	
	public function ConsultarLoteRps($arquivoDeSaida, $protocoloParaConsulta){
		
		$xmlRPS = new DOMDocument("1.0", "utf-8");

		$xmlRPS->formatOutput = true;
				
		//Raiz do XML
		$root = $xmlRPS->createElementNS($this->urlGinfes . $this->schemasServicos['ConsultarLoteRpsEnvio'], 'ConsultarLoteRpsEnvio');
		
		$IdConsulta = $xmlRPS->createAttribute('Id');
		$IdConsulta->value = $this->codigo_rps;
		
		$Prestador = $xmlRPS->createElementNS($this->urlGinfes . $this->schemasServicos['ConsultarLoteRpsEnvio'], 'Prestador');
		
		$Cnpj = $xmlRPS->createElementNS($this->urlGinfes . $this->schemasServicos['Tipo'], 'tipos:Cnpj', $this->cnpj);
		
		$InscricaoMunicipal = $xmlRPS->createElementNS($this->urlGinfes . $this->schemasServicos['Tipo'], 'tipos:InscricaoMunicipal', $this->inscricaoMunicipal);
		
		$Protocolo = $xmlRPS->createElementNS($this->urlGinfes . $this->schemasServicos['ConsultarLoteRpsEnvio'], 'Protocolo', $protocoloParaConsulta);
		
		$Prestador->appendChild($Cnpj);
		$Prestador->appendChild($InscricaoMunicipal);
				
		$root->appendChild($Prestador);
		$root->appendChild($Protocolo);
		
		$root->appendChild($IdConsulta);
		
		$xmlRPS->appendChild($root);
		
		$xmlRPS->save($arquivoDeSaida);
		
		$retorno = $xmlRPS->saveXML();
		
		return $retorno;		
	}
	
	public function ConsultarNfsePorRps($arquivoDeSaida, $numeroRps){
		
		$xmlRPS = new DOMDocument("1.0", "utf-8");

		$xmlRPS->formatOutput = true;
				
		//Raiz do XML
		$root = $xmlRPS->createElementNS($this->urlGinfes . $this->schemasServicos['ConsultarNfseRpsEnvio'], 'ConsultarNfseRpsEnvio');		

		$IdentificacaoRps = $xmlRPS->createElementNS($this->urlGinfes . $this->schemasServicos['ConsultarNfseRpsEnvio'], 'IdentificacaoRps');
		
		$Numero = $xmlRPS->createElementNS($this->urlGinfes . $this->schemasServicos['Tipo'], 'tipos:Numero', $numeroRps);
		
		$Serie = $xmlRPS->createElementNS($this->urlGinfes . $this->schemasServicos['Tipo'], 'tipos:Serie', 'NFSE');
		
		/*
		1 - RPS
		2 - Nota Fiscal Conjugada (Mista)
		3 - Cupom
		*/
		$Tipo = $xmlRPS->createElementNS($this->urlGinfes . $this->schemasServicos['Tipo'], 'tipos:Tipo', '1');		
		
		$Prestador = $xmlRPS->createElementNS($this->urlGinfes . $this->schemasServicos['ConsultarNfseRpsEnvio'], 'Prestador');
		
		$Cnpj = $xmlRPS->createElementNS($this->urlGinfes . $this->schemasServicos['Tipo'], 'tipos:Cnpj', $this->cnpj);
		
		$InscricaoMunicipal = $xmlRPS->createElementNS($this->urlGinfes . $this->schemasServicos['Tipo'], 'tipos:InscricaoMunicipal', $this->inscricaoMunicipal);		
		
		$Prestador->appendChild($Cnpj);
		$Prestador->appendChild($InscricaoMunicipal);

		$IdentificacaoRps->appendChild($Numero);
		$IdentificacaoRps->appendChild($Serie);
		$IdentificacaoRps->appendChild($Tipo);		
		
		$root->appendChild($IdentificacaoRps);
		$root->appendChild($Prestador);
		
		$xmlRPS->appendChild($root);
		
		$xmlRPS->save($arquivoDeSaida);
		
		$retorno = $xmlRPS->saveXML();
		
		return $retorno;		
	}
	
	public function CancelarNota($arquivoDeSaida, $numeroNota){
		
		$xmlRPS = new DOMDocument("1.0", "utf-8");

		$xmlRPS->formatOutput = true;
				
		//CancelarNfseEnvio		
				
		//Raiz do XML
		$root = $xmlRPS->createElementNS($this->urlGinfes . $this->schemasServicos['CancelarNfseEnvio'], 'CancelarNfseEnvio');		

		$Pedido = $xmlRPS->createElementNS(null, 'Pedido');
		
		$xmlns = $xmlRPS->createAttribute('xmlns');
		$xmlns->value = null;
		
		$InfPedidoCancelamento = $xmlRPS->createElementNS($this->urlGinfes . $this->schemasServicos['Tipo'], 'tipos:InfPedidoCancelamento');
		
		$Id = $xmlRPS->createAttribute('Id');
		$Id->value = $this->codigo_rps;
				
		$IdentificacaoNfse = $xmlRPS->createElementNS($this->urlGinfes . $this->schemasServicos['Tipo'], 'tipos:IdentificacaoNfse');
		
		/*
		1 - Erro de Emissao
		2 - Serviço não Concluido
		3 - RPS Cancelado na Emissão
		*/
		$CodigoCancelamento = $xmlRPS->createElementNS($this->urlGinfes . $this->schemasServicos['Tipo'], 'tipos:CodigoCancelamento', '3');
		
		$Numero = $xmlRPS->createElementNS($this->urlGinfes . $this->schemasServicos['Tipo'], 'tipos:Numero', $numeroNota);
		
		$Cnpj = $xmlRPS->createElementNS($this->urlGinfes . $this->schemasServicos['Tipo'], 'tipos:Cnpj', $this->cnpj);
		
		$InscricaoMunicipal = $xmlRPS->createElementNS($this->urlGinfes . $this->schemasServicos['Tipo'], 'tipos:InscricaoMunicipal', $this->inscricaoMunicipal);
		
		$CodigoMunicipio = $xmlRPS->createElementNS($this->urlGinfes . $this->schemasServicos['Tipo'], 'tipos:CodigoMunicipio', $this->codigoMunicipioIBGE);
		
		$IdentificacaoNfse->appendChild($Numero);
		$IdentificacaoNfse->appendChild($Cnpj);
		$IdentificacaoNfse->appendChild($InscricaoMunicipal);
		$IdentificacaoNfse->appendChild($CodigoMunicipio);
		
		$InfPedidoCancelamento->appendChild($IdentificacaoNfse);
		$InfPedidoCancelamento->appendChild($CodigoCancelamento);
		
		$InfPedidoCancelamento->appendChild($Id);
			
		$Pedido->appendChild($InfPedidoCancelamento);
		
		$Pedido->appendChild($xmlns);
		
		$root->appendChild($Pedido);
		
		$xmlRPS->appendChild($root);
		
		$xmlRPS->save($arquivoDeSaida);
		
		$retorno = $xmlRPS->saveXML();
		
		return $retorno;		
	}
	
	//Cancela Nota V2 $arquivoDeSaida, $numeroNota
	//Para cancelar a nota a Ginfes utiliza a ver~soa 2 do schema
	public function cancelarNotaV2($arquivoDeSaida, $numeroNota) {

		$xmlRPS = new DOMDocument("1.0", "utf-8");

        $xmlRPS->formatOutput = true;

        $root = $xmlRPS->createElementNS($this->urlGinfes . $this->schemasServicos['CancelarNfseEnvioV2'], 'CancelarNfseEnvio');

        $Prestador = $xmlRPS->createElementNS(null, 'Prestador');
        $NumeroNfse = $xmlRPS->createElementNS(null, 'NumeroNfse', $numeroNota);

        $Cnpj = $xmlRPS->createElement('tipos:Cnpj', $this->cnpj);
        $InscricaoMunicipal = $xmlRPS->createElement('tipos:InscricaoMunicipal', $this->inscricaoMunicipal);

        $Prestador->appendChild($Cnpj);
        $Prestador->appendChild($InscricaoMunicipal);
		
        $root->appendChild($xmlRPS->createAttribute('xmlns:tipos'))->appendChild($xmlRPS->createTextNode($this->urlGinfes . $this->schemasServicos['TipoV2']));

        $root->appendChild($Prestador);
        $root->appendChild($NumeroNfse);

        $xmlRPS->appendChild($root);
		
		$xmlRPS->save($arquivoDeSaida);
		
		$retorno = $xmlRPS->saveXML();
		
		return $retorno;	
    }
}