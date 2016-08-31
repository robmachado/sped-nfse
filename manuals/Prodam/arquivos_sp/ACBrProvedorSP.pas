{$I ACBr.inc}

unit ACBrProvedorSP;

interface

uses
  Classes, SysUtils,
  pnfsConversao, pcnAuxiliar,
  ACBrNFSeConfiguracoes, ACBrNFSeUtil, ACBrUtil, ACBrDFeUtil,
  {$IFDEF COMPILER6_UP} DateUtils {$ELSE} ACBrD5, FileCtrl {$ENDIF};

type
  { TACBrProvedorSP }

 TProvedorSP = class(TProvedorClass)
  protected
   { protected }
  private
   { private }
  public
   { public }
   Constructor Create;

   function GetConfigCidade(ACodCidade, AAmbiente: Integer): TConfigCidade; override;
   function GetConfigSchema(ACodCidade: Integer): TConfigSchema; override;
   function GetConfigURL(ACodCidade: Integer): TConfigURL; override;
   function GetURI(URI: String): String; override;
   function GetAssinarXML(Acao: TnfseAcao): Boolean; override;
   function GetValidarLote: Boolean; override;

   function Gera_TagI(Acao: TnfseAcao; Prefixo3, Prefixo4, NameSpaceDad, Identificador, URI: String): AnsiString; override;
   function Gera_CabMsg(Prefixo2, VersaoLayOut, VersaoDados, NameSpaceCab: String; ACodCidade: Integer): AnsiString; override;
   function Gera_DadosSenha(CNPJ, Senha: String): AnsiString; override;
   function Gera_TagF(Acao: TnfseAcao; Prefixo3: String): AnsiString; override;

   function GeraEnvelopeRecepcionarLoteRPS(URLNS: String; CabMsg, DadosMsg, DadosSenha: AnsiString): AnsiString; override;
   function GeraEnvelopeConsultarSituacaoLoteRPS(URLNS: String; CabMsg, DadosMsg, DadosSenha: AnsiString): AnsiString; override;
   function GeraEnvelopeConsultarLoteRPS(URLNS: String; CabMsg, DadosMsg, DadosSenha: AnsiString): AnsiString; override;
   function GeraEnvelopeConsultarNFSeporRPS(URLNS: String; CabMsg, DadosMsg, DadosSenha: AnsiString): AnsiString; override;
   function GeraEnvelopeConsultarNFSe(URLNS: String; CabMsg, DadosMsg, DadosSenha: AnsiString): AnsiString; override;
   function GeraEnvelopeCancelarNFSe(URLNS: String; CabMsg, DadosMsg, DadosSenha: AnsiString): AnsiString; override;
   function GeraEnvelopeGerarNFSe(URLNS: String; CabMsg, DadosMsg, DadosSenha: AnsiString): AnsiString; override;
   function GeraEnvelopeRecepcionarSincrono(URLNS: String; CabMsg, DadosMsg, DadosSenha: AnsiString): AnsiString; override;

   function GetSoapAction(Acao: TnfseAcao; NomeCidade: String): String; override;
   function GetRetornoWS(Acao: TnfseAcao; RetornoWS: AnsiString): AnsiString; override;

   function GeraRetornoNFSe(Prefixo: String; RetNFSe: AnsiString; NomeCidade: String): AnsiString; override;
   function GetLinkNFSe(ACodMunicipio, ANumeroNFSe: Integer; ACodVerificacao, AInscricaoM: String; AAmbiente: Integer): String; override;
  end;

implementation

{ TProvedorSP }

constructor TProvedorSP.Create;
begin
//
end;

function TProvedorSP.GetConfigCidade(ACodCidade,
  AAmbiente: Integer): TConfigCidade;
var
 ConfigCidade: TConfigCidade;
begin
 ConfigCidade.VersaoSoap:= '1.1'; //SP SOAP tem versao 1.1 e 1.2, esses envelopes e o provedor foram implementados no padrão 1.1
 ConfigCidade.Prefixo2:='';
 ConfigCidade.Prefixo3:='';
 ConfigCidade.Prefixo4:='';
 ConfigCidade.Identificador:='Id';
 case ACodCidade of
         3550308: begin
              ConfigCidade.NameSpaceEnvelope:='http://www.prefeitura.sp.gov.br/nfe';
         end;
 end;
 
 ConfigCidade.AssinaRPS  := False;
 ConfigCidade.AssinaLote := True;

 Result := ConfigCidade;
end;

function TProvedorSP.GetConfigSchema(ACodCidade: Integer): TConfigSchema;
var
 ConfigSchema: TConfigSchema;
begin

ConfigSchema.VersaoCabecalho := '1';
 ConfigSchema.VersaoDados     := '2.2';
 ConfigSchema.VersaoXML       := '1';
 ConfigSchema.NameSpaceXML    := 'http://www.prefeitura.sp.gov.br/nfe';
 ConfigSchema.Cabecalho       := 'PedidoEnvioLoteRPS_v01.xsd';
 ConfigSchema.ServicoEnviar   := 'PedidoEnvioLoteRPS_v01.xsd';
 ConfigSchema.ServicoConSit   := 'PedidoConsultaLote_v01.xsd';
 ConfigSchema.ServicoConLot   := 'PedidoConsultaLote_v01.xsd';
 ConfigSchema.ServicoConRps   := 'PedidoConsultaNFe_v01.xsd';
 ConfigSchema.ServicoConNfse  := 'PedidoConsultaNFe_v01.xsd';
 ConfigSchema.ServicoCancelar := 'PedidoCancelamentoNFe_v01.xsd';
 ConfigSchema.ServicoGerar    := 'PedidoEnvioRPS_v01.xsd';
 ConfigSchema.DefTipos        := 'TiposNFe_v01.xsd';
 Result := ConfigSchema;
end;

function TProvedorSP.GetConfigURL(ACodCidade: Integer): TConfigURL;
var
 ConfigURL: TConfigURL;
begin
 ConfigURL.HomNomeCidade         := '';    //São Paulo SP
 //ConfigURL.HomRecepcaoLoteRPS    := 'https://nfe.prefeitura.sp.gov.br/ws/lotenfe.asmx'
 ConfigURL.HomRecepcaoLoteRPS    := 'https://nfe.prefeitura.sp.gov.br/ws/lotenfe.asmx';
 ConfigURL.HomConsultaLoteRPS    := 'https://nfe.prefeitura.sp.gov.br/ws/lotenfe.asmx';
 ConfigURL.HomConsultaNFSeRPS    := 'https://nfe.prefeitura.sp.gov.br/ws/lotenfe.asmx';
 ConfigURL.HomConsultaSitLoteRPS := 'https://nfe.prefeitura.sp.gov.br/ws/lotenfe.asmx';
 ConfigURL.HomConsultaNFSe       := 'https://nfe.prefeitura.sp.gov.br/ws/lotenfe.asmx';
 ConfigURL.HomCancelaNFSe        := 'https://nfe.prefeitura.sp.gov.br/ws/lotenfe.asmx';

 ConfigURL.ProNomeCidade         := ''; //São Paulo SP
 ConfigURL.ProRecepcaoLoteRPS    := 'https://nfe.prefeitura.sp.gov.br/ws/lotenfe.asmx';
 ConfigURL.ProConsultaLoteRPS    := 'https://nfe.prefeitura.sp.gov.br/ws/lotenfe.asmx';
 ConfigURL.ProConsultaNFSeRPS    := 'https://nfe.prefeitura.sp.gov.br/ws/lotenfe.asmx';
 ConfigURL.ProConsultaSitLoteRPS := 'https://nfe.prefeitura.sp.gov.br/ws/lotenfe.asmx';
 ConfigURL.ProConsultaNFSe       := 'https://nfe.prefeitura.sp.gov.br/ws/lotenfe.asmx';
 ConfigURL.ProCancelaNFSe        := 'https://nfe.prefeitura.sp.gov.br/ws/lotenfe.asmx';
 Result := ConfigURL;
end;

function TProvedorSP.GetURI(URI: String): String;
begin
 Result := URI;
end;

function TProvedorSP.GetAssinarXML(Acao: TnfseAcao): Boolean;
begin
 case Acao of
   acRecepcionar: Result := False;
   acConsSit:     Result := True;
   acConsLote:    Result := True;
   acConsNFSeRps: Result := True;
   acConsNFSe:    Result := True;
   acCancelar:    Result := True;
   acGerar:       Result := False;
   else           Result := False;
 end;
end;

function TProvedorSP.GetValidarLote: Boolean;
begin
 Result := True;
end;

function TProvedorSP.Gera_TagI(Acao: TnfseAcao; Prefixo3, Prefixo4,
  NameSpaceDad, Identificador, URI: String): AnsiString;
begin
 case Acao of
   acRecepcionar: Result := '<' + Prefixo3 + 'PedidoEnvioLoteRPS' + NameSpaceDad + '>';// xmlns;
   acConsSit:     Result := '<' + Prefixo3 + 'p1:PedidoConsultaLote' + NameSpaceDad + '>'; //p1:
   acConsLote:    Result := '<' + Prefixo3 + 'p1:PedidoConsultaLote' + NameSpaceDad + '>';

   acConsNFSeRps: Result := '<' + Prefixo3 + 'PedidoConsultaNFe' + NameSpaceDad;
   acConsNFSe:    Result := '<' + Prefixo3 + 'p1:PedidoConsultaNFe' + NameSpaceDad;

   acCancelar:    Result := '<' + Prefixo3 + 'PedidoCancelamentoNFe' + NameSpaceDad;

   acGerar:       Result := '<' + Prefixo3 + 'PedidoEnvioRPS' + NameSpaceDad;
 end;
end;

function TProvedorSP.Gera_CabMsg(Prefixo2, VersaoLayOut, VersaoDados,
  NameSpaceCab: String; ACodCidade: Integer): AnsiString;
begin
  Result := '<' + Prefixo2 + 'cabecalho' +
            ' versao="'  + VersaoLayOut + '"' +
            ' xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"' +
            ' xmlns:xsd="http://www.w3.org/2001/XMLSchema"' + NameSpaceCab +
           '</' + Prefixo2 + 'cabecalho>';
end;

function TProvedorSP.Gera_DadosSenha(CNPJ, Senha: String): AnsiString;
begin
 Result := '';
end;

function TProvedorSP.Gera_TagF(Acao: TnfseAcao; Prefixo3: String): AnsiString;
begin
 case Acao of
   acRecepcionar: Result := '</' + Prefixo3 + 'PedidoEnvioLoteRPS>';
   acConsSit:     Result := '</' + Prefixo3 + 'p1:PedidoConsultaLote>'; //Consulta do lote
   acConsLote:    Result := '</' + Prefixo3 + 'p1:PedidoConsultaLote>';
   acConsNFSeRps: Result := '</' + Prefixo3 + 'PedidoConsultaNFe>';
   acConsNFSe:    Result := '</' + Prefixo3 + 'p1:PedidoConsultaNFe>';
   acCancelar:    Result := '</' + Prefixo3 + 'PedidoCancelamentoNFe>';
   acGerar:       Result := '</' + Prefixo3 + 'PedidoEnvioRPS>';
 end;
end;

function TProvedorSP.GeraEnvelopeRecepcionarLoteRPS(URLNS: String;
  CabMsg, DadosMsg, DadosSenha: AnsiString): AnsiString;
var
 TagCab, TagDados: String;
begin
  if DadosSenha = 'homologacao' then
    Result := '<?xml version="1.0" encoding="utf-8"?>' +
             '<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" ' +
             'xmlns:xsd="http://www.w3.org/2001/XMLSchema" ' +
             'xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">' +
             '<soap:Body>' +
               '<TesteEnvioLoteRPSRequest xmlns="http://www.prefeitura.sp.gov.br/nfe">' +
                 '<VersaoSchema>1</VersaoSchema>' +
                 '<MensagemXML> ' +
                   '<![CDATA[' +  DadosMsg +  ']]>'+
                 '</MensagemXML>' +
               '</TesteEnvioLoteRPSRequest>'+
             '</soap:Body>' +
           '</soap:Envelope>'
  else
    Result := '<?xml version="1.0" encoding="utf-8"?>' +
             '<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" ' +
             'xmlns:xsd="http://www.w3.org/2001/XMLSchema" ' +
             'xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">' +
             '<soap:Body>' +
               '<EnvioLoteRPSRequest xmlns="http://www.prefeitura.sp.gov.br/nfe">' +
                 '<VersaoSchema>1</VersaoSchema>' +
                 '<MensagemXML> ' +
                   '<![CDATA[' +  DadosMsg +  ']]>'+
                 '</MensagemXML>' +
               '</EnvioLoteRPSRequest>'+
             '</soap:Body>' +
           '</soap:Envelope>'
end;

function TProvedorSP.GeraEnvelopeConsultarSituacaoLoteRPS(
  URLNS: String; CabMsg, DadosMsg, DadosSenha: AnsiString): AnsiString;
var
 TagCab, TagDados: String;
begin
  //Mesma que a consulta de Lote
  Result:='<?xml version="1.0" encoding="utf-8"?>'+
          '<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">'+
          '<soap:Body>'+
          '<ConsultaLoteRequest xmlns="http://www.prefeitura.sp.gov.br/nfe">'+
          '<VersaoSchema>1</VersaoSchema>'+
          '<MensagemXML>'+
          '<![CDATA[' +  DadosMsg +  ']]>'+
          '</MensagemXML>'+
          '</ConsultaLoteRequest>'+
          '</soap:Body>'+
          '</soap:Envelope>';
end;

function TProvedorSP.GeraEnvelopeConsultarLoteRPS(URLNS: String;
  CabMsg, DadosMsg, DadosSenha: AnsiString): AnsiString;
var
 TagCab, TagDados: String;
begin
  Result := '<?xml version="1.0" encoding="utf-8"?>'+
           '<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" '+
           'xmlns:xsd="http://www.w3.org/2001/XMLSchema" ' +
           'xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">'+
           '<soap:Body>'+
             '<ConsultaLoteRequest xmlns="http://www.prefeitura.sp.gov.br/nfe">'+
               '<VersaoSchema>1</VersaoSchema>'+
               '<MensagemXML>' +
                 '<![CDATA[' +  DadosMsg +  ']]>'+
               '</MensagemXML>'+
             '</ConsultaLoteRequest>'+
           '</soap:Body>'+
         '</soap:Envelope>';
end;

function TProvedorSP.GeraEnvelopeConsultarNFSeporRPS(URLNS: String;
  CabMsg, DadosMsg, DadosSenha: AnsiString): AnsiString;
var
 TagCab, TagDados: String;
begin
 Result:='<?xml version="1.0" encoding="utf-8"?>'+
          '<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">'+
          '<soap:Body>'+
          '<ConsultaNFeRequest xmlns="http://www.prefeitura.sp.gov.br/nfe">'+
          '<VersaoSchema>1</VersaoSchema>'+
          '<MensagemXML>'+
          '<![CDATA[' +  DadosMsg +  ']]>'+
          '</MensagemXML>'+
          '</ConsultaNFeRequest>'+
          '</soap:Body>'+
          '</soap:Envelope>';
end;

function TProvedorSP.GeraEnvelopeConsultarNFSe(URLNS: String; CabMsg,
  DadosMsg, DadosSenha: AnsiString): AnsiString;
var
 TagCab, TagDados: String;
begin
  Result:='<?xml version="1.0" encoding="utf-8"?>'+
          '<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">'+
          '<soap:Body>'+
          '<ConsultaNFeRequest xmlns="http://www.prefeitura.sp.gov.br/nfe">'+
          '<VersaoSchema>1</VersaoSchema>'+
          '<MensagemXML>'+
          '<![CDATA[' +  DadosMsg +  ']]>'+
          '</MensagemXML>'+
          '</ConsultaNFeRequest>'+
          '</soap:Body>'+
          '</soap:Envelope>';
end;

function TProvedorSP.GeraEnvelopeCancelarNFSe(URLNS: String; CabMsg,
  DadosMsg, DadosSenha: AnsiString): AnsiString;
var
 TagDados: String;
begin
   Result:='<?xml version="1.0" encoding="utf-8"?>' +
           '<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" ' +
           'xmlns:xsd="http://www.w3.org/2001/XMLSchema" ' +
           'xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">' +
           '<soap:Body>' +
             '<CancelamentoNFeRequest xmlns="http://www.prefeitura.sp.gov.br/nfe">' +
               '<VersaoSchema>1</VersaoSchema>' +
               '<MensagemXML> ' +
                 '<![CDATA[' +  DadosMsg +  ']]>'+
               '</MensagemXML>' +
             '</CancelamentoNFeRequest>'+
           '</soap:Body>' +
         '</soap:Envelope>';
end;

function TProvedorSP.GeraEnvelopeGerarNFSe(URLNS: String; CabMsg,
  DadosMsg, DadosSenha: AnsiString): AnsiString;
begin
Result := '<?xml version="1.0" encoding="utf-8"?>' +
           '<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" ' +
           'xmlns:xsd="http://www.w3.org/2001/XMLSchema" ' +
           'xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">' +
           '<soap:Body>' +
             '<EnvioRPSRequest xmlns="http://www.prefeitura.sp.gov.br/nfe">' +
               '<VersaoSchema>1</VersaoSchema>' +
               '<MensagemXML> ' +
                 '<![CDATA[' +  DadosMsg +  ']]>'+
               '</MensagemXML>' +
             '</EnvioRPSRequest>'+
           '</soap:Body>' +
         '</soap:Envelope>';
end;

function TProvedorSP.GeraEnvelopeRecepcionarSincrono(URLNS: String;
  CabMsg, DadosMsg, DadosSenha: AnsiString): AnsiString;
begin
 Result := '';
end;

function TProvedorSP.GetSoapAction(Acao: TnfseAcao; NomeCidade: String): String;
begin
 case Acao of
   //acRecepcionarTesteEnvio é simplesmente para quando a soapaction for para um teste de rps
   acRecepcionarTesteEnvio: Result := 'http://www.prefeitura.sp.gov.br/nfe/ws/testeenvio';
   acRecepcionar: Result := 'http://www.prefeitura.sp.gov.br/nfe/ws/envioLoteRPS'; //http://www.prefeitura.sp.gov.br/nfe/envioLoteRPS';
   acConsSit:     Result := 'http://www.prefeitura.sp.gov.br/nfe/ws/consultaLote';
   acConsLote:    Result := 'http://www.prefeitura.sp.gov.br/nfe/ws/consultaLote';
   acConsNFSeRps: Result := 'http://www.prefeitura.sp.gov.br/nfe/ws/consultaNFe';
   acConsNFSe:    Result := 'http://www.prefeitura.sp.gov.br/nfe/ws/consultaNFe';
   acCancelar:    Result := 'http://www.prefeitura.sp.gov.br/nfe/ws/cancelamentoNFe';
   acGerar:       Result := 'http://www.prefeitura.sp.gov.br/nfe/ws/envioLoteRPS';
 end;
end;

function TProvedorSP.GetRetornoWS(Acao: TnfseAcao; RetornoWS: AnsiString): AnsiString;
begin
 case Acao of
   acRecepcionarTesteEnvio: Result := SeparaDados( RetornoWS, 'RetornoEnvioLoteRPS', True );
   acRecepcionar:           Result := SeparaDados( RetornoWS, 'RetornoEnvioLoteRPS', True );
   acConsSit:               Result := SeparaDados( RetornoWS, 'RetornoConsulta', True ); //RetornoWS;
   acConsLote:              Result := SeparaDados( RetornoWS, 'RetornoConsulta' );
   acConsNFSeRps:           Result := SeparaDados( RetornoWS, 'RetornoConsulta' );
   acConsNFSe:              Result := SeparaDados( RetornoWS, 'RetornoConsulta' );
   acCancelar:              Result := SeparaDados( RetornoWS, 'RetornoCancelamentoNFe' );
   acGerar:                 Result := SeparaDados( RetornoWS, 'return' );
 end;
end;

function TProvedorSP.GeraRetornoNFSe(Prefixo: String;
  RetNFSe: AnsiString; NomeCidade: String): AnsiString;
begin
 Result := '<?xml version="1.0" encoding="UTF-8"?>' +
           '<' + Prefixo + 'CompNfse xmlns="http://www.abrasf.org.br/nfse.xsd">' +
             RetNfse +
           '</' + Prefixo + 'CompNfse>';
end;

function TProvedorSP.GetLinkNFSe(ACodMunicipio, ANumeroNFSe: Integer;
  ACodVerificacao, AInscricaoM: String; AAmbiente: Integer): String;
begin
  if AAmbiente = 1  then begin
   case ACodMunicipio of
    3157807: Result := '';
   else Result := '';
   end;
  end
  else Result := '';
end;

end.
