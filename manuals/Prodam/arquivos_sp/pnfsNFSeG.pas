unit pnfsNFSeG;

interface

uses
  SysUtils, Classes, Forms,
{$IFNDEF VER130}
  Variants,
{$ENDIF}
  pnfsNFSe, pnfsConversao, ACBrUtil, ACBrDFeUtil, StrUtils, pcnAuxiliar, ACBrNFSeConfiguracoes;

type

  TNFSeG = class
   private
     class function GetIdEntidadeEquiplano(const IBGE: Integer): String;
   protected

   public
     class function Gera_DadosMsgEnviarLote(Prefixo3, Prefixo4, Identificador,
                                    NameSpaceDad, VersaoDados, VersaoXML,
                                    NumeroLote, CNPJ, IM, QtdeNotas: String;
                                    Notas, TagI, TagF: AnsiString; AProvedor: TnfseProvedor = proNenhum): AnsiString;

     class function Gera_DadosMsgConsSitLote(Prefixo3, Prefixo4, NameSpaceDad,
                                     VersaoXML, Protocolo, CNPJ, IM: String;
                                     TagI, TagF: AnsiString; AProvedor: TnfseProvedor = proNenhum): AnsiString;

     class function Gera_DadosMsgConsLote(Prefixo3, Prefixo4, NameSpaceDad,
                                  VersaoXML, Protocolo, CNPJ, IM, senha, frase_secreta: String;
                                  TagI, TagF: AnsiString; AProvedor: TnfseProvedor = proNenhum; ARazaoSocial: String = ''): AnsiString;

     class function Gera_DadosMsgConsNFSeRPS(Prefixo3, Prefixo4, NameSpaceDad, VersaoXML,
                                     NumeroRps, SerieRps, TipoRps, CNPJ, IM, senha, frase_secreta: String;
                                     TagI, TagF: AnsiString; AProvedor: TnfseProvedor = proNenhum; ARazaoSocial: String = ''): AnsiString;

     class function Gera_DadosMsgConsNFSe(Prefixo3, Prefixo4, NameSpaceDad, VersaoXML,
                                  CNPJ, IM: String;
                                  DataInicial, DataFinal: TDateTime;
                                  TagI, TagF: AnsiString; NumeroNFSe: string = '';
                                  Senha : string = ''; FraseSecreta : string = '';
                                  AProvedor: TnfseProvedor = proNenhum;
                                  APagina: Integer = 1): AnsiString;

     class function Gera_DadosMsgCancelarNFSe(Prefixo4, NameSpaceDad, NumeroNFSe, CNPJ, IM,
                                      CodMunicipio, CodCancelamento: String;
                                      TagI, TagF: AnsiString; AProvedor: TnfseProvedor = proNenhum): AnsiString;

     class function Gera_DadosMsgGerarNFSe(Prefixo3, Prefixo4, Identificador,
                                   NameSpaceDad, VersaoDados, VersaoXML,
                                   NumeroLote, CNPJ, IM, QtdeNotas: String;
                                   Notas, TagI, TagF: AnsiString; AProvedor: TnfseProvedor = proNenhum): AnsiString;

     class function Gera_DadosMsgEnviarSincrono(Prefixo3, Prefixo4, Identificador,
                                        NameSpaceDad, VersaoDados, VersaoXML,
                                        NumeroLote, CNPJ, IM, QtdeNotas: String;
                                        Notas, TagI, TagF: AnsiString; AProvedor: TnfseProvedor = proNenhum): AnsiString;

     //-------------------------------------------------------------------------
     // As classes abaixo são exclusivas para o provedor DSF
     //-------------------------------------------------------------------------
     class function Gera_DadosMsgEnviarLoteDSF(Prefixo3, Prefixo4,Identificador, NameSpaceDad, VersaoXML,
                                               NumeroLote, CodCidade, CNPJ, IM, RazaoSocial, Transacao,
                                               QtdeNotas, ValorTotalServicos, ValorTotalDeducoes: String;
                                               DataInicial, DataFinal: TDateTime;
                                               Notas, TagI, TagF: AnsiString): AnsiString;

     class function Gera_DadosMsgConsLoteDSF(Prefixo3, Prefixo4, NameSpaceDad,
                                             VersaoXML, CodCidade, CNPJ, NumeroLote: String;
                                             TagI, TagF: AnsiString): AnsiString;

     class function Gera_DadosMsgConsNFSeRPSDSF(Prefixo3, Prefixo4, NameSpaceDad,VersaoXML,
                                                CodCidade, CNPJ, Transacao, NumeroLote: String;
                                                Notas, TagI, TagF: AnsiString): AnsiString;

     class function Gera_DadosMsgConsNFSeDSF(Prefixo3, Prefixo4, NameSpaceDad, VersaoXML, CodCidade,
                                             CNPJ, IM, NotaInicial: String; DataInicial, DataFinal: TDateTime;
                                             TagI, TagF: AnsiString): AnsiString;

     class function Gera_DadosMsgCancelarNFSeDSF(Prefixo3, Prefixo4, NameSpaceDad, VersaoXML,
                                                 CNPJ, Transacao, CodMunicipio, NumeroLote: String;
                                                 Notas, TagI, TagF: AnsiString): AnsiString;

     class function Gera_DadosMsgConsSeqRPSDSF(TagI, TagF: AnsiString; VersaoXML, CodCidade,
                                               IM, CNPJ, SeriePrestacao: String): AnsiString;


     //As classes abaixos são exclusivas do provedor Equiplano
     class function Gera_DadosMsgEnviarLoteEquiplano(VersaoXML, NumeroLote, QtdeRPS, CNPJ, IM: String;
                                                     CodCidade: Integer;
                                                     OptanteSimples: Boolean;
                                                     Notas, TagI, TagF: AnsiString): AnsiString;
     class function Gera_DadosMsgConsLoteEquiplano(CodCidade: Integer;
                                                   CNPJ, IM, Protocolo, NumeroLote: String;
                                                   TagI, TagF: AnsiString): AnsiString;
     class function Gera_DadosMsgConsNFSeRPSEquiplano(CodCidade: Integer;
                                                      NumeroRps, CNPJ, IM: String;
                                                      TagI, TagF: AnsiString): AnsiString;
     class function Gera_DadosMsgCancelarNFSeEquiplano(CodCidade: Integer;
                                                       CNPJ, IM, NumeroNFSe, MotivoCancelamento: String;
                                                       TagI, TagF: AnsiString): AnsiString;
     class function Gera_DadosMsgConsSitLoteEquiplano(CodCidade: Integer;
                                                      CNPJ, IM, Protocolo, NumeroLote: String;
                                                      TagI, TagF: AnsiString): AnsiString;
     class function Gera_DadosMsgCancelarNFSeFreire(Prefixo4, NameSpaceDad, NumeroNFSe, CNPJ, IM,
                                                    CodMunicipio, CodCancelamento, MotivoCancelamento: String;
                                                    TagI, TagF: AnsiString): AnsiString;

     //------------------------------------------------------------------------
     //------------- Métodos estáticos exclusivos do provedor SP --------------
     //------------------------------------------------------------------------

     class function Gera_DadosMsgGerarNFSeSP(Prefixo3, Prefixo4, Identificador,
                                   NameSpaceDad, VersaoDados, VersaoXML,
                                   NumeroLote, CNPJ, IM, QtdeNotas: String;
                                   Notas, TagI, TagF: AnsiString; AProvedor: TnfseProvedor = proNenhum): AnsiString;

     class function Gera_DadosMsgEnviarLoteSP(Prefixo3, Prefixo4,Identificador, NameSpaceDad, VersaoXML,
                                               NumeroLote, CodCidade, CNPJ, IM, RazaoSocial, Transacao,
                                               QtdeNotas, ValorTotalServicos, ValorTotalDeducoes: String;
                                               DataInicial, DataFinal: TDateTime;
                                               Notas, TagI, TagF: AnsiString): AnsiString;
                                               
     class function Gera_DadosMsgConsSitLoteSP(CNPJRemetente, NumeroLote: String):AnsiString;

     class function Gera_DadosMsgConsLoteSP(CNPJ, NumeroLote: String): AnsiString;

     class function Gera_DadosMsgConsNFSeRPSSP(Prefixo3, Prefixo4, NameSpaceDad,VersaoXML,
                                                CodCidade, CNPJ, Transacao, NumeroLote: String;
                                                Notas, TagI, TagF: AnsiString): AnsiString;

     class function Gera_DadosMsgConsNFSeSP(Prefixo3, Prefixo4, NameSpaceDad, VersaoXML, CodCidade,
                                             CNPJ, IM, NotaInicial: String; DataInicial, DataFinal: TDateTime;
                                             TagI, TagF: AnsiString): AnsiString;

     class function Gera_DadosMsgCancelarNFSeSP(CNPJ,Transacao,IM, NumeroNFse, Assinatura:String): AnsiString;

     class function Gera_DadosMsgConsSeqRPSSP(TagI, TagF: AnsiString; VersaoXML, CodCidade,
                                               IM, CNPJ, SeriePrestacao: String): AnsiString;

   published

   end;

implementation

//uses
// IniFiles, Variants, ACBrConsts;

class function TNFSeG.Gera_DadosMsgEnviarLote(Prefixo3, Prefixo4,
  Identificador, NameSpaceDad, VersaoDados, VersaoXML, NumeroLote, CNPJ,
  IM, QtdeNotas: String; Notas, TagI, TagF: AnsiString; AProvedor: TnfseProvedor = proNenhum): AnsiString;
var
 DadosMsg: AnsiString;
begin
 if AProvedor = proBetha then Prefixo3 := '';
 if AProvedor in [proGovBR, proPronim, proPublica] then Identificador := '';

 DadosMsg := '<' + Prefixo3 + 'LoteRps' +

               // Inclui o Identificador ou não
               DFeUtil.SeSenao(AProvedor = proISSDigital,
                               '',
                               DFeUtil.SeSenao(Identificador <> '',
                                               ' ' + Identificador + '="' + DFeUtil.SeSenao(AProvedor = proTecnos,
                                                                                            Copy(Notas, Pos('<Rps Id="', Notas) + 9, 35),
                                                                                            NumeroLote
                                                                                           ) + '"',
                                               ''
                                              )
                              ) +

               // Inclui a versão ou não
               DFeUtil.SeSenao(AProvedor in [proAbaco, proBetha, proGinfes, proGoiania, proGovBR,
                                             {proISSDigital, }proIssCuritiba, proISSNET, proNatal, proActcon,
                                             proRecife, proRJ, proSimplISS, proThema, proTiplan,
                                             proAgili, proFISSLex, proSpeedGov{, proWebISS}, proPronim],
                               '',
                               DFeUtil.SeSenao(VersaoDados <> '', ' versao="' + VersaoDados + '"', '')
                              ) +

               // Inclui o Name Space ou não
               DFeUtil.SeSenao(AProvedor = proSimplISS,
                               ' ' + NameSpaceDad,
                               '>'
                              ) +

              '<' + Prefixo4 + 'NumeroLote>' +
                NumeroLote +
              '</' + Prefixo4 + 'NumeroLote>' +

              DFeUtil.SeSenao((VersaoXML = '2') or (AProvedor = proISSNet),

                '<' + Prefixo4 + 'CpfCnpj>' +
                DFeUtil.SeSenao(Length(OnlyNumber(Cnpj)) <= 11,
                 '<' + Prefixo4 + 'Cpf>' +
                   Cnpj +
                 '</' + Prefixo4 + 'Cpf>',
                 '<' + Prefixo4 + 'Cnpj>' +
                   Cnpj +
                 '</' + Prefixo4 + 'Cnpj>') +
                '</' + Prefixo4 + 'CpfCnpj>',

                '<' + Prefixo4 + 'Cnpj>' +
                  Cnpj +
                '</' + Prefixo4 + 'Cnpj>') +

              '<' + Prefixo4 + 'InscricaoMunicipal>' +
                IM +
              '</' + Prefixo4 + 'InscricaoMunicipal>' +
              '<' + Prefixo4 + 'QuantidadeRps>' +
                QtdeNotas +
              '</' + Prefixo4 + 'QuantidadeRps>' +
              '<' + Prefixo4 + 'ListaRps>' +
               Notas +
              '</' + Prefixo4 + 'ListaRps>' +
             '</' + Prefixo3 + 'LoteRps>';

 Result := TagI + DadosMsg + TagF;

 if AProvedor = proNenhum then Result := '';
end;

class function TNFSeG.Gera_DadosMsgConsSitLote(Prefixo3, Prefixo4,
  NameSpaceDad, VersaoXML, Protocolo, CNPJ, IM: String; TagI,
  TagF: AnsiString; AProvedor: TnfseProvedor = proNenhum): AnsiString;
var
 DadosMsg: AnsiString;
begin
 if AProvedor = proBetha then Prefixo3 := '';

 DadosMsg := '<' + Prefixo3 + 'Prestador' +
               DFeUtil.SeSenao(AProvedor = proSimplISS, ' ' + NameSpaceDad, '>') +
               DFeUtil.SeSenao((VersaoXML = '2') or (AProvedor in [proISSNet, proActcon]),

                 '<' + Prefixo4 + 'CpfCnpj>' +
                  DFeUtil.SeSenao(Length(OnlyNumber(Cnpj)) <= 11,
                  '<' + Prefixo4 + 'Cpf>' +
                    Cnpj +
                  '</' + Prefixo4 + 'Cpf>',
                  '<' + Prefixo4 + 'Cnpj>' +
                    Cnpj +
                  '</' + Prefixo4 + 'Cnpj>') +
                 '</' + Prefixo4 + 'CpfCnpj>',

                 '<' + Prefixo4 + 'Cnpj>' +
                   Cnpj +
                 '</' + Prefixo4 + 'Cnpj>') +

               '<' + Prefixo4 + 'InscricaoMunicipal>' +
                 IM +
               '</' + Prefixo4 + 'InscricaoMunicipal>' +
              '</' + Prefixo3 + 'Prestador>' +
              '<' + Prefixo3 + 'Protocolo' +
               DFeUtil.SeSenao(AProvedor = proSimplISS, ' ' + NameSpaceDad, '>') +
                Protocolo +
              '</' + Prefixo3 + 'Protocolo>';

 Result := TagI + DadosMsg + TagF;

 if AProvedor in [proNenhum, pro4R, proAgili, proCoplan, profintelISS, proFiorilli,
                  proGoiania, proGovDigital, proISSDigital, proISSe, proProdata, proVirtual,
                  proSaatri, proFreire, proPVH, proVitoria, proTecnos] then Result := '';
end;

class function TNFSeG.Gera_DadosMsgConsLote(Prefixo3, Prefixo4,
  NameSpaceDad, VersaoXML, Protocolo, CNPJ, IM, senha, frase_secreta: String; TagI,
  TagF: AnsiString; AProvedor: TnfseProvedor = proNenhum; ARazaoSocial: String = ''): AnsiString;
var
 DadosMsg: AnsiString;
begin
 if AProvedor = proBetha then Prefixo3 := '';

 DadosMsg := '<' + Prefixo3 + 'Prestador' +
               DFeUtil.SeSenao(AProvedor = proSimplISS, ' ' + NameSpaceDad, '>') +

               DFeUtil.SeSenao((VersaoXML = '2') or (AProvedor in [proISSNet, proActcon]),

                 '<' + Prefixo4 + 'CpfCnpj>' +
                  DFeUtil.SeSenao(Length(OnlyNumber(Cnpj)) <= 11,
                  '<' + Prefixo4 + 'Cpf>' +
                    Cnpj +
                  '</' + Prefixo4 + 'Cpf>',
                  '<' + Prefixo4 + 'Cnpj>' +
                    Cnpj +
                  '</' + Prefixo4 + 'Cnpj>') +
                 '</' + Prefixo4 + 'CpfCnpj>',

                 '<' + Prefixo4 + 'Cnpj>' +
                   Cnpj +
                 '</' + Prefixo4 + 'Cnpj>') +
               DFeUtil.SeSenao(AProvedor = proTecnos, '<RazaoSocial>' + ARazaoSocial + '</RazaoSocial>' , '') +
               '<' + Prefixo4 + 'InscricaoMunicipal>' +
                 IM +
               '</' + Prefixo4 + 'InscricaoMunicipal>' +

              DFeUtil.SeSenao(AProvedor = proISSDigital,
               '<' + Prefixo4 + 'Senha>' +
                 Senha +
               '</' + Prefixo4 + 'Senha>' +
               '<' + Prefixo4 + 'FraseSecreta>' +
                 frase_secreta +
               '</' + Prefixo4 + 'FraseSecreta>', '') +

              '</' + Prefixo3 + 'Prestador>' +
              '<' + Prefixo3 + 'Protocolo' +
               DFeUtil.SeSenao(AProvedor = proSimplISS, ' ' + NameSpaceDad, '>') +
                Protocolo +
              '</' + Prefixo3 + 'Protocolo>';

 Result := TagI + DadosMsg + TagF;

 if AProvedor = proNenhum then Result := '';
end;

class function TNFSeG.Gera_DadosMsgConsNFSeRPS(Prefixo3, Prefixo4,
  NameSpaceDad, VersaoXML, NumeroRps, SerieRps, TipoRps, CNPJ, IM,
  senha, frase_secreta: String;
  TagI, TagF: AnsiString; AProvedor: TnfseProvedor = proNenhum; ARazaoSocial: String = ''): AnsiString;
var
 DadosMsg: AnsiString;
begin
 if AProvedor = proBetha then Prefixo3 := '';

 DadosMsg := '<' + Prefixo3 + 'IdentificacaoRps' +
               DFeUtil.SeSenao(AProvedor = proSimplISS, ' ' + NameSpaceDad, '>') +
              '<' + Prefixo4 + 'Numero>' +
                NumeroRps +
              '</' + Prefixo4 + 'Numero>' +
              '<' + Prefixo4 + 'Serie>' +
                SerieRps +
              '</' + Prefixo4 + 'Serie>' +
              '<' + Prefixo4 + 'Tipo>' +
                TipoRps +
              '</' + Prefixo4 + 'Tipo>' +
             '</' + Prefixo3 + 'IdentificacaoRps>' +
             '<' + Prefixo3 + 'Prestador' +
               DFeUtil.SeSenao(AProvedor = proSimplISS, ' ' + NameSpaceDad, '>') +

              DFeUtil.SeSenao((VersaoXML = '2') or (AProvedor in [proISSNet, proActcon]),

                '<' + Prefixo4 + 'CpfCnpj>' +
                  DFeUtil.SeSenao(Length(OnlyNumber(Cnpj)) <= 11,
                  '<' + Prefixo4 + 'Cpf>' +
                    Cnpj +
                  '</' + Prefixo4 + 'Cpf>',
                  '<' + Prefixo4 + 'Cnpj>' +
                    Cnpj +
                  '</' + Prefixo4 + 'Cnpj>') +
                '</' + Prefixo4 + 'CpfCnpj>',

                '<' + Prefixo4 + 'Cnpj>' +
                  Cnpj +
                '</' + Prefixo4 + 'Cnpj>') +
              DFeUtil.SeSenao(AProvedor = proTecnos, '<RazaoSocial>' + ARazaoSocial + '</RazaoSocial>', '') +
              '<' + Prefixo4 + 'InscricaoMunicipal>' +
                IM +
              '</' + Prefixo4 + 'InscricaoMunicipal>' +

              DFeUtil.SeSenao(AProvedor = proISSDigital,
               '<' + Prefixo4 + 'Senha>' +
                 Senha +
               '</' + Prefixo4 + 'Senha>' +
               '<' + Prefixo4 + 'FraseSecreta>' +
                 frase_secreta +
               '</' + Prefixo4 + 'FraseSecreta>', '') +

             '</' + Prefixo3 + 'Prestador>';

 Result := TagI + DadosMsg + TagF;

 if AProvedor = proNenhum then Result := '';
end;

class function TNFSeG.Gera_DadosMsgConsNFSe(Prefixo3, Prefixo4,
  NameSpaceDad, VersaoXML, CNPJ, IM: String; DataInicial, DataFinal: TDateTime; TagI,
  TagF: AnsiString; NumeroNFSe: string = ''; Senha : string = '';
  FraseSecreta : string = ''; AProvedor: TnfseProvedor = proNenhum;
  APagina: Integer = 1): AnsiString;
var
 DadosMsg: AnsiString;
begin
 if AProvedor = proBetha then Prefixo3 := '';

 DadosMsg := '<' + Prefixo3 + 'Prestador' +
               DFeUtil.SeSenao(AProvedor = proSimplISS, ' ' + NameSpaceDad, '>') +
               DFeUtil.SeSenao((VersaoXML = '2') or (AProvedor in [proISSNet, proActcon] ),

                 '<' + Prefixo4 + 'CpfCnpj>' +
                  DFeUtil.SeSenao(Length(OnlyNumber(Cnpj)) <= 11,
                  '<' + Prefixo4 + 'Cpf>' +
                    Cnpj +
                  '</' + Prefixo4 + 'Cpf>',
                  '<' + Prefixo4 + 'Cnpj>' +
                    Cnpj +
                  '</' + Prefixo4 + 'Cnpj>') +
                 '</' + Prefixo4 + 'CpfCnpj>',

                 '<' + Prefixo4 + 'Cnpj>' +
                  Cnpj +
                 '</' + Prefixo4 + 'Cnpj>') +

               '<' + Prefixo4 + 'InscricaoMunicipal>' +
                IM +
               '</' + Prefixo4 + 'InscricaoMunicipal>' +

              DFeUtil.SeSenao(AProvedor = proISSDigital,
               '<' + Prefixo4 + 'Senha>' +
                 Senha +
               '</' + Prefixo4 + 'Senha>' +
               '<' + Prefixo4 + 'FraseSecreta>' +
                 FraseSecreta +
               '</' + Prefixo4 + 'FraseSecreta>', '') +

              '</' + Prefixo3 + 'Prestador>';

 if NumeroNFSe <> ''
  then DadosMsg := DadosMsg + '<' + Prefixo3 + 'NumeroNfse>' +
                               NumeroNFSe +
                              '</' + Prefixo3 + 'NumeroNfse>';

 if (DataInicial>0) and (DataFinal>0)
  then DadosMsg := DadosMsg + '<' + Prefixo3 + 'PeriodoEmissao>' +
                               '<' + Prefixo3 + 'DataInicial>' +
                                 FormatDateTime('yyyy-mm-dd', DataInicial) +
                               '</' + Prefixo3 + 'DataInicial>' +
                               '<' + Prefixo3 + 'DataFinal>' +
                                 FormatDateTime('yyyy-mm-dd', DataFinal) +
                               '</' + Prefixo3 + 'DataFinal>' +
                              '</' + Prefixo3 + 'PeriodoEmissao>';

 if AProvedor in [proFiorilli, profintelISS]
  then DadosMsg := DadosMsg + '<' + Prefixo3 + 'Pagina>' +
                                IntToStr(APagina) +
                              '</' + Prefixo3 + 'Pagina>';

 Result := TagI + DadosMsg + TagF;

 if AProvedor = proNenhum then Result := '';
end;

class function TNFSeG.Gera_DadosMsgCancelarNFSe(Prefixo4, NameSpaceDad, NumeroNFSe,
  CNPJ, IM, CodMunicipio, CodCancelamento: String; TagI,
  TagF: AnsiString; AProvedor: TnfseProvedor = proNenhum): AnsiString;
var
 DadosMsg: AnsiString;
begin
 case AProvedor of
  proGinfes: DadosMsg := '<Prestador>' +
                          '<' + Prefixo4 + 'Cnpj>' +
                            Cnpj +
                          '</' + Prefixo4 + 'Cnpj>' +
                          '<' + Prefixo4 + 'InscricaoMunicipal>' +
                            IM +
                          '</' + Prefixo4 + 'InscricaoMunicipal>' +
                         '</Prestador>' +
                         '<NumeroNfse>' +
                           NumeroNFSe +
                         '</NumeroNfse>';


  // Adicionado por Akai - L. Massao Aihara 31/10/2013
  proIssCuritiba: DadosMsg := '<InfPedidoCancelamento>' +
                               '<IdentificacaoNfse>' +
                                '<Cnpj>' + Cnpj + '</Cnpj>' +
                                '<InscricaoMunicipal>' + IM + '</InscricaoMunicipal>' +
                                '<Numero>' + NumeroNFse + '</Numero>' +
                               '</IdentificacaoNfse>' +
                              '</InfPedidoCancelamento>';


  else DadosMsg := '<' + Prefixo4 + 'IdentificacaoNfse>' +
                    '<' + Prefixo4 + 'Numero>' +
                      NumeroNFse +
                    '</' + Prefixo4 + 'Numero>' +

                    // alterado por Akai - L. Massao Aihara 12/11/2013
                   DFeUtil.SeSenao(AProvedor in [pro4R, proISSe, profintelISS, proFiorilli,proDigifred,
                                                 proVirtual, proISSDigital, proSaatri, proCoplan, proVitoria, proTecnos],

                    //Adicionei o SeSenao para poder cancelar nota onde o pretador é pessoa física (Cartório em Vitória-ES). - Eduardo Silva dos Santos - 11/01/2014 - DRD SISTEMAS
                    DFeUtil.SeSenao( length(Cnpj)=14,
                                                 ('<' + Prefixo4 + 'CpfCnpj>' +
                                                   '<' + Prefixo4 + 'Cnpj>' +
                                                    Cnpj +
                                                   '</' + Prefixo4 + 'Cnpj>' +
                                                  '</' + Prefixo4 + 'CpfCnpj>')
                                                    ,
                                                 ('<' + Prefixo4 + 'CpfCnpj>' +
                                                   '<' + Prefixo4 + 'Cpf>' +
                                                    Cnpj +
                                                   '</' + Prefixo4 + 'Cpf>' +
                                                  '</' + Prefixo4 + 'CpfCnpj>')
                                   ),

                    '<' + Prefixo4 + 'Cnpj>' +
                      Cnpj +
                    '</' + Prefixo4 + 'Cnpj>') +

                    '<' + Prefixo4 + 'InscricaoMunicipal>' +
                      IM +
                    '</' + Prefixo4 + 'InscricaoMunicipal>' +
                    '<' + Prefixo4 + 'CodigoMunicipio>' +
                      CodMunicipio +
                    '</' + Prefixo4 + 'CodigoMunicipio>' +
                   '</' + Prefixo4 + 'IdentificacaoNfse>' +
                   '<' + Prefixo4 + 'CodigoCancelamento>' +

                     // Codigo de Cancelamento
                     // 1 - Erro de emissão
                     // 2 - Serviço não concluido
                     // 3 - RPS Cancelado na Emissão

                     CodCancelamento +

                   '</' + Prefixo4 + 'CodigoCancelamento>' +
                   DFeUtil.SeSenao(AProvedor = proTecnos, '<Id>' + CNPJ + IM + IntToStrZero(StrToInt64(NumeroNFse), 16) + '</Id>', '') +
                  '</' + Prefixo4 + 'InfPedidoCancelamento>';
 end;

 Result := TagI + DadosMsg + TagF;

 if AProvedor = proNenhum then Result := '';
end;

class function TNFSeG.Gera_DadosMsgGerarNFSe(Prefixo3, Prefixo4,
  Identificador, NameSpaceDad, VersaoDados, VersaoXML, NumeroLote, CNPJ,
  IM, QtdeNotas: String; Notas, TagI, TagF: AnsiString; AProvedor: TnfseProvedor = proNenhum): AnsiString;
var
 DadosMsg: AnsiString;
begin
 if AProvedor = proBetha then Prefixo3 := '';

 case AProvedor of
  pro4R,
  profintelIss,
  proGoiania,
  proGovDigital,
  proIssDigital,
  proISSe,
  proProdata,
  proVitoria,
  proPVH,
  proAgili,
  proCoplan,
  proVirtual,
  proFreire,
  proSaatri,
  proFiorilli,
  proPublica: Result := TagI + Notas + TagF;
  else begin // proWebISS
   DadosMsg := '<' + Prefixo3 + 'LoteRps'+
                 DFeUtil.SeSenao(Identificador <> '', ' ' + Identificador + '="' + NumeroLote + '"', '') +
                 DFeUtil.SeSenao(VersaoDados <> '', ' versao="' + VersaoDados + '"', '') + '>' +
                '<' + Prefixo4 + 'NumeroLote>' +
                  NumeroLote +
                '</' + Prefixo4 + 'NumeroLote>' +

                DFeUtil.SeSenao((VersaoXML = '2') or (AProvedor = proISSNet),

                  '<' + Prefixo4 + 'CpfCnpj>' +
                   DFeUtil.SeSenao(Length(OnlyNumber(Cnpj)) <= 11,
                   '<' + Prefixo4 + 'Cpf>' +
                     Cnpj +
                   '</' + Prefixo4 + 'Cpf>',
                   '<' + Prefixo4 + 'Cnpj>' +
                     Cnpj +
                   '</' + Prefixo4 + 'Cnpj>') +
                  '</' + Prefixo4 + 'CpfCnpj>',

                  '<' + Prefixo4 + 'Cnpj>' +
                    Cnpj +
                  '</' + Prefixo4 + 'Cnpj>') +

                '<' + Prefixo4 + 'InscricaoMunicipal>' +
                  IM +
                '</' + Prefixo4 + 'InscricaoMunicipal>' +
                '<' + Prefixo4 + 'QuantidadeRps>' +
                  QtdeNotas +
                '</' + Prefixo4 + 'QuantidadeRps>' +
                '<' + Prefixo4 + 'ListaRps>' +
                 Notas +
                '</' + Prefixo4 + 'ListaRps>' +
               '</' + Prefixo3 + 'LoteRps>';

   Result := TagI + DadosMsg + TagF;
  end;
 end;

 {add-SP}
 if AProvedor in [proNenhum, proAbaco, proBetha, proBetim, proBHIss, proDigifred,
  proEquiplano, {proFiorilli, }proFIssLex, proGinfes, proGovBR, proIssCuritiba,
  proIssIntel, proIssNet, proNatal, proProdemge, proRecife, proRJ, proSimplIss,
  proThema, proTiplan, proIssDSF, proAgili, proSpeedGov, proPronim, proActcon,
  proSP] then Result := '';
end;

class function TNFSeG.Gera_DadosMsgEnviarSincrono(Prefixo3, Prefixo4,
  Identificador, NameSpaceDad, VersaoDados, VersaoXML, NumeroLote, CNPJ,
  IM, QtdeNotas: String; Notas, TagI, TagF: AnsiString; AProvedor: TnfseProvedor = proNenhum): AnsiString;
begin
 Result := Gera_DadosMsgEnviarLote(Prefixo3, Prefixo4, Identificador, NameSpaceDad,
                                   VersaoDados, VersaoXML, NumeroLote, CNPJ, IM,
                                   QtdeNotas, Notas, TagI, TagF, AProvedor);

 if AProvedor in [proNenhum, proAbaco, proBetha, proBetim, proBHISS, proDigifred,
     proEquiplano, profintelISS, proFISSLex, proGinfes, proGoiania, proGovBR,
     proGovDigital, proIssCuritiba, proISSDigital, proISSIntel, proISSNet, proNatal,
     proProdemge, proPublica, proRecife, proRJ, proSaatri, proFreire, proSimplISS, proThema,
     proTiplan, proWebISS, proProdata, proAgili, proSpeedGov, proPronim] then Result := '';
end;

//-------------------------------------------------------------------------
// As classes abaixo são exclusivas para o provedor DSF
//-------------------------------------------------------------------------
class function TNFSeG.Gera_DadosMsgEnviarLoteDSF(Prefixo3, Prefixo4,
  Identificador, NameSpaceDad, VersaoXML, NumeroLote, CodCidade, CNPJ, IM,
  RazaoSocial, Transacao, QtdeNotas, ValorTotalServicos,
  ValorTotalDeducoes: String; DataInicial, DataFinal: TDateTime; Notas,
  TagI, TagF: AnsiString): AnsiString;
var
 DadosMsg: AnsiString;
begin
 DadosMsg := '<Cabecalho>' +
               '<CodCidade>'            + CodCidade   + '</CodCidade>' +
               '<CPFCNPJRemetente>'     + Cnpj        + '</CPFCNPJRemetente>' +
               '<RazaoSocialRemetente>' + RazaoSocial + '</RazaoSocialRemetente>' +
               '<transacao>'            + Transacao   + '</transacao>' +
               '<dtInicio>' + FormatDateTime('yyyy-mm-dd', DataInicial) + '</dtInicio>' +
               '<dtFim>'    + FormatDateTime('yyyy-mm-dd', DataFinal) + '</dtFim>' +
               '<QtdRPS>'               + QtdeNotas               + '</QtdRPS>' +
               '<ValorTotalServicos>'   + ValorTotalServicos + '</ValorTotalServicos>' +
               '<ValorTotalDeducoes>'   + ValorTotalDeducoes + '</ValorTotalDeducoes>' +
               '<Versao>'               + VersaoXML          + '</Versao>' +
               '<MetodoEnvio>'          + 'WS'               + '</MetodoEnvio>' +
             '</Cabecalho>' +
             '<Lote ' + Identificador + '="Lote:' + NumeroLote + '">' +
                Notas +
             '</Lote>';

  Result := TagI + DadosMsg + TagF;
end;

class function TNFSeG.Gera_DadosMsgConsLoteDSF(Prefixo3, Prefixo4,
  NameSpaceDad, VersaoXML, CodCidade, CNPJ, NumeroLote: String; TagI,
  TagF: AnsiString): AnsiString;
var
 DadosMsg: AnsiString;
begin
 DadosMsg := '<Cabecalho>' +
               '<CodCidade>' + CodCidade + '</CodCidade>' +
               '<CPFCNPJRemetente>' + Cnpj + '</CPFCNPJRemetente>' +
               '<Versao>' + VersaoXML + '</Versao>' +
               '<NumeroLote>' + NumeroLote + '</NumeroLote>' +
             '</Cabecalho>';

 Result := TagI + DadosMsg + TagF;
end;

class function TNFSeG.Gera_DadosMsgConsNFSeRPSDSF(Prefixo3, Prefixo4,
  NameSpaceDad, VersaoXML, CodCidade, CNPJ, Transacao, NumeroLote: String;
  Notas, TagI, TagF: AnsiString): AnsiString;
var
 DadosMsg: AnsiString;
begin
  DadosMsg := '<Cabecalho>' +
               '<CodCidade>' + CodCidade + '</CodCidade>' +
               '<CPFCNPJRemetente>' + Cnpj + '</CPFCNPJRemetente>' +
               '<transacao>' + Transacao + '</transacao>' +
               '<Versao>' + VersaoXML + '</Versao>' +
             '</Cabecalho>' +
             '<Lote  Id="Lote:' + NumeroLote + '">' +
                Notas +
             '</Lote>';

 Result := TagI + DadosMsg + TagF;
end;

class function TNFSeG.Gera_DadosMsgConsNFSeDSF(Prefixo3, Prefixo4,
  NameSpaceDad, VersaoXML, CodCidade, CNPJ, IM, NotaInicial: String;
  DataInicial, DataFinal: TDateTime; TagI, TagF: AnsiString): AnsiString;
var
 DadosMsg: AnsiString;
begin
 DadosMsg := '<Cabecalho Id="Consulta:notas">' +
               '<CodCidade>'         + CodCidade    + '</CodCidade>' +
               '<CPFCNPJRemetente>'  + CNPJ         + '</CPFCNPJRemetente>' +
               '<InscricaoMunicipalPrestador>' + IM + '</InscricaoMunicipalPrestador>' +

               '<dtInicio>' +
                 FormatDateTime('yyyy-mm-dd', DataInicial) +
               '</dtInicio>' +

               '<dtFim>' +
                 FormatDateTime('yyyy-mm-dd', DataFinal) +
               '</dtFim>' +

               '<NotaInicial>' + NotaInicial + '</NotaInicial>' +
               '<Versao>'      + VersaoXML   + '</Versao>' +
             '</Cabecalho>';

 Result := TagI + DadosMsg + TagF;
end;

class function TNFSeG.Gera_DadosMsgCancelarNFSeDSF(Prefixo3, Prefixo4,
  NameSpaceDad, VersaoXML, CNPJ, Transacao, CodMunicipio,
  NumeroLote: String; Notas, TagI, TagF: AnsiString): AnsiString;
var
 DadosMsg: AnsiString;
begin

 DadosMsg := '<Cabecalho>' +
		         '<CodCidade>'        + CodMunicipio + '</CodCidade>' +
		         '<CPFCNPJRemetente>' + CNPJ      + '</CPFCNPJRemetente> ' +
		         '<transacao>'        + Transacao + '</transacao>' +
		         '<Versao>'           + VersaoXML + '</Versao>' +
	          '</Cabecalho>' +
             '<Lote Id="Lote:' + NumeroLote + '">' +
                Notas +
             '</Lote>';

 Result := TagI + DadosMsg + TagF;
end;

class function TNFSeG.Gera_DadosMsgConsSeqRPSDSF(TagI, TagF: AnsiString;
  VersaoXML, CodCidade, IM, CNPJ, SeriePrestacao: String): AnsiString;
var
 DadosMsg: AnsiString;
begin
 //consultar sequencial RPS
 DadosMsg := '<Cabecalho>' +
               '<CodCid>' + CodCidade + '</CodCid>' +
               '<IMPrestador>' + IM + '</IMPrestador>' +
               '<CPFCNPJRemetente>' + CNPJ + '</CPFCNPJRemetente>' +
               '<SeriePrestacao>' + SeriePrestacao + '</SeriePrestacao>' +
               '<Versao>' + VersaoXML + '</Versao>' +
             '</Cabecalho>';

 Result := TagI + DadosMsg + TagF;
end;

class function TNFSeG.GetIdEntidadeEquiplano(const IBGE: Integer): String;
begin
  case IBGE of
    4102307: Result:= '23'; // Balsa Nova/PR
    4104501: Result:= '50'; // Capanema/PR
    4104659: Result:= '141';// Carambei/PR
    4107207: Result:= '68'; // Dois Vizinhos/PR
    4108403: Result:= '35'; // Francisco Beltrao/PR
    4109807: Result:= '332';// Ibipora/PR
    4120606: Result:= '28'; // Prudentopolis/PR
    4122008: Result:= '19'; // Rio Azul/PR
    4123501: Result:= '54'; // Santa Helena/PR
    4126306: Result:= '61'; // Senges/PR
    4127106: Result:= '260';// Telemaco Borba/PR
    4127700: Result:= '136';// Toledo/PR
  else
    Result:= '';
  end;
end;

class function TNFSeG.Gera_DadosMsgEnviarLoteEquiplano(VersaoXML, NumeroLote, QtdeRPS, CNPJ,
  IM: String; CodCidade: Integer; OptanteSimples: Boolean; Notas, TagI, TagF: AnsiString): AnsiString;
begin
  Result:= TagI +
             '<lote>' +
               '<nrLote>' + NumeroLote + '</nrLote>' +
               '<qtRps>' + QtdeRPS + '</qtRps>' +
               '<nrVersaoXml>' + VersaoXML + '</nrVersaoXml>' +
               '<prestador>' +
                 '<nrCnpj>' + CNPJ + '</nrCnpj>' +
                 '<nrInscricaoMunicipal>' +  IM + '</nrInscricaoMunicipal>' +
                 '<isOptanteSimplesNacional>' + IfThen(OptanteSimples, '1', '2') + '</isOptanteSimplesNacional>' +
                 '<idEntidade>' + GetIdEntidadeEquiplano(CodCidade) + '</idEntidade>' +
               '</prestador>' +
               '<listaRps>' +
                 Notas +
               '</listaRps>' +
             '</lote>' +
           TagF;
end;

class function TNFSeG.Gera_DadosMsgConsLoteEquiplano(CodCidade: Integer;
  CNPJ, IM, Protocolo, NumeroLote: String; TagI, TagF: AnsiString): AnsiString;
begin
  Result:= TagI +
             '<prestador>' +
               '<nrInscricaoMunicipal>' + IM + '</nrInscricaoMunicipal>' +
               '<cnpj>' + CNPJ + '</cnpj>' +
               '<idEntidade>' + GetIdEntidadeEquiplano(CodCidade) + '</idEntidade>' +
             '</prestador>' +
             '<nrLoteRps>' + NumeroLote + '</nrLoteRps>' +
             //'<nrProtocolo>' + Protocolo + '</nrProtocolo>' +
           TagF;
end;

class function TNFSeG.Gera_DadosMsgConsNFSeRPSEquiplano(CodCidade: Integer;
  NumeroRps, CNPJ, IM: String; TagI, TagF: AnsiString): AnsiString;
begin
  Result:= TagI +
             '<rps>' +
               '<nrRps>' + NumeroRps + '</nrRps>' +
               '<nrEmissorRps>1</nrEmissorRps>' +
             '</rps>' +
             '<prestador>' +
               '<nrInscricaoMunicipal>' + IM + '</nrInscricaoMunicipal>' +
               '<cnpj>' + CNPJ + '</cnpj>' +
               '<idEntidade>' + GetIdEntidadeEquiplano(CodCidade) + '</idEntidade>' +
             '</prestador>' +
           TagF;
end;

class function TNFSeG.Gera_DadosMsgCancelarNFSeEquiplano(
  CodCidade: Integer; CNPJ, IM, NumeroNFSe, MotivoCancelamento: String;
  TagI, TagF: AnsiString): AnsiString;
begin
  Result:= TagI +
             '<prestador>' +
               '<nrInscricaoMunicipal>' + IM + '</nrInscricaoMunicipal>' +
               '<cnpj>' + CNPJ + '</cnpj>' +
               '<idEntidade>' + GetIdEntidadeEquiplano(CodCidade) + '</idEntidade>' +
             '</prestador>' +
             '<nrNfse>' + NumeroNFSe + '</nrNfse>' +
             '<dsMotivoCancelamento>' + MotivoCancelamento + '</dsMotivoCancelamento>' +
           TagF;
end;

class function TNFSeG.Gera_DadosMsgCancelarNFSeFreire(Prefixo4, NameSpaceDad, NumeroNFSe, CNPJ, IM, CodMunicipio, CodCancelamento,
  MotivoCancelamento: String; TagI, TagF: AnsiString): AnsiString;
var
 DadosMsg: AnsiString;
begin

   DadosMsg := '<' + Prefixo4 + 'IdentificacaoNfse>' +
           '<' + Prefixo4 + 'Numero>' +
             NumeroNFse +
           '</' + Prefixo4 + 'Numero>' +
           '<' + Prefixo4 + 'CpfCnpj>' +
            '<' + Prefixo4 + 'Cnpj>' +
             Cnpj +
            '</' + Prefixo4 + 'Cnpj>' +
           '</' + Prefixo4 + 'CpfCnpj>' +
           '<' + Prefixo4 + 'InscricaoMunicipal>' +
             IM +
           '</' + Prefixo4 + 'InscricaoMunicipal>' +
           '<' + Prefixo4 + 'CodigoMunicipio>' +
             CodMunicipio +
           '</' + Prefixo4 + 'CodigoMunicipio>' +
          '</' + Prefixo4 + 'IdentificacaoNfse>' +
          '<' + Prefixo4 + 'CodigoCancelamento>' +
            CodCancelamento +
          '</' + Prefixo4 + 'CodigoCancelamento>' +
          '<' + Prefixo4 + 'MotivoCancelamento>' +
            MotivoCancelamento +
          '</' + Prefixo4 + 'MotivoCancelamento>'+
         '</' + Prefixo4 + 'InfPedidoCancelamento>';

   Result := TagI + DadosMsg + TagF;
end;

class function TNFSeG.Gera_DadosMsgConsSitLoteEquiplano(CodCidade: Integer;
  CNPJ, IM, Protocolo, NumeroLote: String; TagI,
  TagF: AnsiString): AnsiString;
begin
  Result:= TagI +
             '<prestador>' +
               '<nrInscricaoMunicipal>' + IM + '</nrInscricaoMunicipal>' +
               '<cnpj>' + CNPJ + '</cnpj>' +
               '<idEntidade>' + GetIdEntidadeEquiplano(CodCidade) + '</idEntidade>' +
             '</prestador>' +
             '<nrLoteRps>' + NumeroLote + '</nrLoteRps>' +
             //'<nrProtocolo>' + Protocolo + '</nrProtocolo>' +
           TagF;
end;

//--------------------------------------------------------------------------
//---------------Métodos estáticos exclusivos do provedor SP----------------
//--------------------------------------------------------------------------

class function TNFSeG.Gera_DadosMsgConsSitLoteSP(CNPJRemetente, NumeroLote: String):AnsiString;
var
  DadosMsg:AnsiString;
  CNPJ: String;
begin
  CNPJ:=CNPJRemetente;
  DadosMsg:='<Cabecalho xmlns="" Versao="1">' +
             '<CPFCNPJRemetente>';
             if Length(SomenteNumeros(CNPJ))<=11 then
              DadosMsg := DadosMsg + '<CPF>' +  CNPJ + '</CPF>'
             else
              DadosMsg := DadosMsg + '<CNPJ>' +  CNPJ + '</CNPJ>';
              DadosMsg := DadosMsg + '</CPFCNPJRemetente>' +
             '<NumeroLote>' + NumeroLote + '</NumeroLote>' +
             '</Cabecalho>';
  Result :=  DadosMsg;
end;

class function TNFSeG.Gera_DadosMsgGerarNFSeSP(Prefixo3, Prefixo4, Identificador,
                               NameSpaceDad, VersaoDados, VersaoXML,
                               NumeroLote, CNPJ, IM, QtdeNotas: String;
                               Notas, TagI, TagF: AnsiString; AProvedor: TnfseProvedor = proNenhum): AnsiString;
var
  DadosMsg:AnsiString;
begin
  DadosMsg :=  ' <Cabecalho Versao="1" xmlns="">' +
                  '<CPFCNPJRemetente>';
    if Length(SomenteNumeros(CNPJ))<=11 then
      DadosMsg := DadosMsg + '<CPF>' +  CNPJ + '</CPF>'
    else
      DadosMsg := DadosMsg + '<CNPJ>' +  CNPJ + '</CNPJ>';
    DadosMsg := DadosMsg + '</CPFCNPJRemetente>' +
             '</Cabecalho>' +
                           Notas;
  Result := TagI + DadosMsg + TagF;
end;

class function TNFSeG.Gera_DadosMsgEnviarLoteSP(Prefixo3, Prefixo4,Identificador, NameSpaceDad, VersaoXML,
                                           NumeroLote, CodCidade, CNPJ, IM, RazaoSocial, Transacao,
                                           QtdeNotas, ValorTotalServicos, ValorTotalDeducoes: String;
                                           DataInicial, DataFinal: TDateTime;
                                           Notas, TagI, TagF: AnsiString): AnsiString;
var
 DadosMsg: AnsiString;
begin
  DadosMsg := DadosMsg + //'<?xml version="1.0" encoding="UTF-8"?> ' +
                      '<PedidoEnvioLoteRPS xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns="http://www.prefeitura.sp.gov.br/nfe">' +
                      '<Cabecalho xmlns="" Versao="1">' +
                      '<CPFCNPJRemetente>';

  if Length(SomenteNumeros(CNPJ))<=11 then
    DadosMsg := DadosMsg + '<CPF>' +  CNPJ + '</CPF>'
  else
    DadosMsg := DadosMsg + '<CNPJ>' +  CNPJ + '</CNPJ>';

  DadosMsg := DadosMsg + '</CPFCNPJRemetente>' +
                      '<transacao>'+ LowerCase(Transacao) +'</transacao>'  +
                      '<dtInicio>' + FormatDateTime('yyyy-mm-dd',date) + '</dtInicio>' +
                      '<dtFim>' + FormatDateTime('yyyy-mm-dd',date) + '</dtFim>' +
                      '<QtdRPS>' + QtdeNotas +'</QtdRPS>' +
                      '<ValorTotalServicos>' + StringReplace(ValorTotalServicos, ',','.', [rfReplaceAll]) + '</ValorTotalServicos>' +
                      '<ValorTotalDeducoes>' + StringReplace(ValorTotalDeducoes, ',','.', [rfReplaceAll])  + '</ValorTotalDeducoes>' +
                      '</Cabecalho>';
  DadosMsg :=  DadosMsg + Notas;
  Result   := DadosMsg;

end;

class function TNFSeG.Gera_DadosMsgConsLoteSP(CNPJ, NumeroLote: String): AnsiString;
var
  DadosMsg:AnsiString;
begin
  DadosMsg :='<Cabecalho Versao="1" xmlns="">' +
             '<CPFCNPJRemetente>';
             if Length(SomenteNumeros(CNPJ))<=11 then
              DadosMsg := DadosMsg + '<CPF>' +  CNPJ + '</CPF>'
             else
              DadosMsg := DadosMsg + '<CNPJ>' +  CNPJ + '</CNPJ>';
              DadosMsg := DadosMsg + '</CPFCNPJRemetente>' +
             '<NumeroLote>' + NumeroLote + '</NumeroLote>' +
             '</Cabecalho>';
  Result :=  DadosMsg;
end;

class function TNFSeG.Gera_DadosMsgConsNFSeRPSSP(Prefixo3, Prefixo4, NameSpaceDad,VersaoXML,
                                            CodCidade, CNPJ, Transacao, NumeroLote: String;
                                            Notas, TagI, TagF: AnsiString): AnsiString;
begin
  //
end;

class function TNFSeG.Gera_DadosMsgConsNFSeSP(Prefixo3, Prefixo4, NameSpaceDad, VersaoXML, CodCidade,
                                         CNPJ, IM, NotaInicial: String; DataInicial, DataFinal: TDateTime;
                                         TagI, TagF: AnsiString): AnsiString;
begin
  //
end;

class function TNFSeG.Gera_DadosMsgCancelarNFSeSP(CNPJ,Transacao,IM, NumeroNFse, Assinatura:String): AnsiString;
var
  DadosMsg:AnsiString;
begin
  	DadosMsg :=   '<Cabecalho Versao="1" xmlns="">' +
				  '<CPFCNPJRemetente>';

	if Length(SomenteNumeros(CNPJ))<=11 then
    DadosMsg := DadosMsg + '<CPF>' +  CNPJ + '</CPF>'
	else
    DadosMsg := DadosMsg + '<CNPJ>' +  CNPJ + '</CNPJ>';
  DadosMsg := DadosMsg + '</CPFCNPJRemetente>' +
     '<transacao>' + LowerCase(Transacao) + '</transacao>' +
     '</Cabecalho>' +
     '<Detalhe xmlns="">' +
     '<ChaveNFe>' +
      '<InscricaoPrestador>' + IM + '</InscricaoPrestador>' +
      '<NumeroNFe>'+NumeroNFSe+'</NumeroNFe>'  +
     '</ChaveNFe>'+
     '<AssinaturaCancelamento>'+Assinatura+'</AssinaturaCancelamento>' + //Assinatura do provedor SP para cancelamento de NFSe
     '</Detalhe>';
	Result :=  DadosMsg ;
end;

class function TNFSeG.Gera_DadosMsgConsSeqRPSSP(TagI, TagF: AnsiString; VersaoXML, CodCidade,
                                           IM, CNPJ, SeriePrestacao: String): AnsiString;
begin
  //
end;


end.
