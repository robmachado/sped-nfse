unit pnfsConsLoteRpsResposta;

interface

uses
  SysUtils, Classes, Forms,
  pcnAuxiliar, pcnConversao, pcnLeitor,
  pnfsConversao, pnfsNFSe, ACBrUtil, ACBrNFSeUtil, ACBrDFeUtil;

type

 TCompNfseCollection = class;
 TCompNfseCollectionItem = class;
 TMsgRetornoLoteCollection = class;
 TMsgRetornoLoteCollectionItem = class;

 TListaNfse = class(TPersistent)
  private
    FSituacao: string;
    FCompNfse : TCompNfseCollection;
    FMsgRetorno : TMsgRetornoLoteCollection;
    procedure SetCompNfse(Value: TCompNfseCollection);
    procedure SetMsgRetorno(Value: TMsgRetornoLoteCollection);
  public
    constructor Create; reintroduce;
    destructor Destroy; override;
    property Situacao: string                      read FSituacao   write FSituacao;
    property CompNfse: TCompNfseCollection         read FCompNfse   write SetCompNfse;
    property MsgRetorno: TMsgRetornoLoteCollection read FMsgRetorno write SetMsgRetorno;
  end;

 TCompNfseCollection = class(TCollection)
  private
    function GetItem(Index: Integer): TCompNfseCollectionItem;
    procedure SetItem(Index: Integer; Value: TCompNfseCollectionItem);
  public
    constructor Create(AOwner: TListaNfse);
    function Add: TCompNfseCollectionItem;
    property Items[Index: Integer]: TCompNfseCollectionItem read GetItem write SetItem; default;
  end;

 TCompNfseCollectionItem = class(TCollectionItem)
  private
    FNfse: TNFSe;
    FNfseCancelamento: TConfirmacaoCancelamento;
    FNfseSubstituicao: TSubstituicaoNfse;
  public
    constructor Create; reintroduce;
    destructor Destroy; override;
  published
    property Nfse: TNFSe                                read FNfse             write FNfse;
    property NfseCancelamento: TConfirmacaoCancelamento read FNfseCancelamento write FNfseCancelamento;
    property NfseSubstituicao: TSubstituicaoNfse        read FNfseSubstituicao write FNfseSubstituicao;
  end;

 TMsgRetornoLoteCollection = class(TCollection)
  private
    function GetItem(Index: Integer): TMsgRetornoLoteCollectionItem;
    procedure SetItem(Index: Integer; Value: TMsgRetornoLoteCollectionItem);
  public
    constructor Create(AOwner: TListaNfse);
    function Add: TMsgRetornoLoteCollectionItem;
    property Items[Index: Integer]: TMsgRetornoLoteCollectionItem read GetItem write SetItem; default;
  end;

 TMsgRetornoLoteCollectionItem = class(TCollectionItem)
  private
    FCodigo : String;
    FMensagem : String;
    FCorrecao : String;
  public
    constructor Create; reintroduce;
    destructor Destroy; override;
  published
    property Codigo: string   read FCodigo   write FCodigo;
    property Mensagem: string read FMensagem write FMensagem;
    property Correcao: string read FCorrecao write FCorrecao;
  end;

 TretLote = class(TPersistent)
  private
    FPathArquivoMunicipios: string;
    FPathArquivoTabServicos: string;
    FLeitor: TLeitor;
    FListaNfse: TListaNfse;
    FProvedor: TnfseProvedor;
    FTabServicosExt: Boolean;
//    function ObterDescricaoServico(cCodigo: string): string;
  public
    constructor Create;
    destructor Destroy; override;
    function LerXml: boolean;
    function LerXml_provedorIssDsf: boolean;
    function LerXML_provedorEquiplano: Boolean;
    function LerXML_provedorSP: Boolean;
  published
    property PathArquivoMunicipios: string  read FPathArquivoMunicipios  write FPathArquivoMunicipios;
    property PathArquivoTabServicos: string read FPathArquivoTabServicos write FPathArquivoTabServicos;
    property Leitor: TLeitor                read FLeitor                 write FLeitor;
    property ListaNfse: TListaNfse          read FListaNfse              write FListaNfse;
    property Provedor: TnfseProvedor        read FProvedor               write FProvedor;
    property TabServicosExt: Boolean        read FTabServicosExt         write FTabServicosExt;
  end;

implementation

{ TListaNfse }

constructor TListaNfse.Create;
begin
  FCompNfse   := TCompNfseCollection.Create(Self);
  FMsgRetorno := TMsgRetornoLoteCollection.Create(Self);
end;

destructor TListaNfse.Destroy;
begin
  FCompNfse.Free;
  FMsgRetorno.Free;

  inherited;
end;

procedure TListaNfse.SetCompNfse(Value: TCompNfseCollection);
begin
  FCompNfse.Assign(Value);
end;

procedure TListaNfse.SetMsgRetorno(Value: TMsgRetornoLoteCollection);
begin
  FMsgRetorno.Assign(Value);
end;

{ TCompNfseCollection }

function TCompNfseCollection.Add: TCompNfseCollectionItem;
begin
  Result := TCompNfseCollectionItem(inherited Add);
  Result.create;
end;

constructor TCompNfseCollection.Create(AOwner: TListaNfse);
begin
  inherited Create(TCompNfseCollectionItem);
end;

function TCompNfseCollection.GetItem(
  Index: Integer): TCompNfseCollectionItem;
begin
  Result := TCompNfseCollectionItem(inherited GetItem(Index));
end;

procedure TCompNfseCollection.SetItem(Index: Integer;
  Value: TCompNfseCollectionItem);
begin
  inherited SetItem(Index, Value);
end;

{ TCompNfseCollectionItem }

constructor TCompNfseCollectionItem.Create;
begin
  FNfse             := TNFSe.Create;
  FNfseCancelamento := TConfirmacaoCancelamento.Create;
  FNfseSubstituicao := TSubstituicaoNfse.Create;
end;

destructor TCompNfseCollectionItem.Destroy;
begin
  FNfse.Free;
  FNfseCancelamento.Free;
  FNfseSubstituicao.Free;

  inherited;
end;

{ TMsgRetornoLoteCollection }

function TMsgRetornoLoteCollection.Add: TMsgRetornoLoteCollectionItem;
begin
  Result := TMsgRetornoLoteCollectionItem(inherited Add);
  Result.create;
end;

constructor TMsgRetornoLoteCollection.Create(AOwner: TListaNfse);
begin
  inherited Create(TMsgRetornoLoteCollectionItem);
end;

function TMsgRetornoLoteCollection.GetItem(
  Index: Integer): TMsgRetornoLoteCollectionItem;
begin
  Result := TMsgRetornoLoteCollectionItem(inherited GetItem(Index));
end;

procedure TMsgRetornoLoteCollection.SetItem(Index: Integer;
  Value: TMsgRetornoLoteCollectionItem);
begin
  inherited SetItem(Index, Value);
end;

{ TMsgRetornoLoteCollectionItem }

constructor TMsgRetornoLoteCollectionItem.Create;
begin

end;

destructor TMsgRetornoLoteCollectionItem.Destroy;
begin

  inherited;
end;

{ TretLote }

constructor TretLote.Create;
begin
  FLeitor                 := TLeitor.Create;
  FListaNfse              := TListaNfse.Create;
  FPathArquivoMunicipios  := '';
  FPathArquivoTabServicos := '';
end;

destructor TretLote.Destroy;
begin
  FLeitor.Free;
  FListaNfse.Free;
  inherited;
end;

function TretLote.LerXml: boolean;
var
  ok: boolean;
  iNivel,
  i, k, Item: Integer;
  VersaoXML: String;
begin
  result := False;

  try
    Leitor.Arquivo := NotaUtil.RetirarPrefixos(Leitor.Arquivo);
    VersaoXML      := NotaUtil.VersaoXML(Leitor.Arquivo);
    Leitor.Grupo   := Leitor.Arquivo;

    k      := 0; //length(Prefixo4);
    iNivel := 0;

    // Alterado por Akai - L. Massao Aihara 31/10/2013
    if (leitor.rExtrai(1, 'ConsultarLoteRpsResposta') <> '') or
       (leitor.rExtrai(1, 'Consultarloterpsresposta') <> '') or
       (leitor.rExtrai(1, 'ConsultarLoteRpsResult') <> '') then
//    begin
      iNivel := 1;

      // Utilizado pelo provedor fintelISS
      ListaNfse.FSituacao := Leitor.rCampo(tcStr, 'Situacao');
      if ListaNfse.FSituacao = ''
       then ListaNfse.FSituacao := '4';

      // Ler a Lista de NFSe
      if leitor.rExtrai(iNivel + 1, 'ListaNfse') <> '' then
      begin
        i := 0;
        // Alterado por Rodrigo Cantelli
        while(Leitor.rExtrai(iNivel + 2, 'CompNfse', '', i + 1) <> '') or
              (Leitor.rExtrai(iNivel + 2, 'ComplNfse', '', i + 1) <> '') or
              (Leitor.rExtrai(iNivel + 2, 'tcCompNfse', '', i + 1) <> '') or
              ((FProvedor in [ProActcon]) and (Leitor.rExtrai(iNivel + 3, 'Nfse', '', i + 1) <> ''))
              do
        begin
          ListaNfse.FCompNfse.Add;

          // Grupo da TAG <Nfse> *************************************************
          if Leitor.rExtrai(iNivel + 3, 'Nfse','') <> ''
           then begin

            if (FProvedor in [ProActcon]) then Leitor.rExtrai(iNivel + 3, 'Nfse', '' , i + 1);

            if Pos('</NFSE>',uppercase(ListaNfse.FCompNfse[i].FNfse.XML))=0 then
               ListaNfse.FCompNfse[i].FNfse.XML:=ListaNfse.FCompNfse[i].FNfse.XML+'</Nfse>';

            ListaNfse.FCompNfse[i].FNfse.XML := {'<?xml version="1.0" encoding="UTF-8" standalone="yes" ?>'+}
                                                Leitor.Grupo {+
                                                '</Nfse>'};
            // Grupo da TAG <InfNfse> *****************************************************
            if Leitor.rExtrai(iNivel + 4, 'InfNfse') <> ''
             then begin
              ListaNfse.FCompNfse[i].FNfse.InfID.ID          := Leitor.rCampo(tcStr, 'Numero');
              ListaNfse.FCompNfse[i].FNFSe.Numero            := Leitor.rCampo(tcStr, 'Numero');
              ListaNfse.FCompNfse[i].FNFSe.CodigoVerificacao := Leitor.rCampo(tcStr, 'CodigoVerificacao');
              if FProvedor = proVitoria then
                ListaNfse.FCompNfse[i].FNFSe.DataEmissao       := Leitor.rCampo(tcDat, 'DataEmissao')
              else
                ListaNfse.FCompNfse[i].FNFSe.DataEmissao       := Leitor.rCampo(tcDatHor, 'DataEmissao');

              ListaNfse.FCompNfse[i].FNFSe.NaturezaOperacao         := StrToNaturezaOperacao(ok, Leitor.rCampo(tcStr, 'NaturezaOperacao'));
              ListaNfse.FCompNfse[i].FNFSe.RegimeEspecialTributacao := StrToRegimeEspecialTributacao(ok, Leitor.rCampo(tcStr, 'RegimeEspecialTributacao'));
              ListaNfse.FCompNfse[i].FNFSe.OptanteSimplesNacional   := StrToSimNao(ok, Leitor.rCampo(tcStr, 'OptanteSimplesNacional'));
              ListaNfse.FCompNfse[i].FNFSe.IncentivadorCultural     := StrToSimNao(ok, Leitor.rCampo(tcStr, 'IncentivadorCultural'));

              ListaNfse.FCompNfse[i].FNFSe.Competencia       := Leitor.rCampo(tcStr, 'Competencia');
              if FProvedor = proISSNet
               then ListaNfse.FCompNfse[i].FNFSe.NfseSubstituida   := ''
               else ListaNfse.FCompNfse[i].FNFSe.NfseSubstituida   := Leitor.rCampo(tcStr, 'NfseSubstituida');
              ListaNfse.FCompNfse[i].FNFSe.OutrasInformacoes := Leitor.rCampo(tcStr, 'OutrasInformacoes');
              ListaNfse.FCompNfse[i].FNFSe.ValorCredito      := Leitor.rCampo(tcDe2, 'ValorCredito');

              // Grupo da TAG <IdentificacaoRps> ********************************************
              if Leitor.rExtrai(iNivel + 5, 'IdentificacaoRps') <> ''
               then begin
                ListaNfse.FCompNfse[i].FNFSe.IdentificacaoRps.Numero := Leitor.rCampo(tcStr, 'Numero');
                ListaNfse.FCompNfse[i].FNFSe.IdentificacaoRps.Serie  := Leitor.rCampo(tcStr, 'Serie');
                ListaNfse.FCompNfse[i].FNFSe.IdentificacaoRps.Tipo   := StrToTipoRPS(ok, Leitor.rCampo(tcStr, 'Tipo'));
               end;

              // Grupo da TAG <RpsSubstituido> **********************************************
              if Leitor.rExtrai(iNivel + 5, 'RpsSubstituido') <> ''
               then begin
                ListaNfse.FCompNfse[i].FNFSe.RpsSubstituido.Numero := Leitor.rCampo(tcStr, 'Numero');
                ListaNfse.FCompNfse[i].FNFSe.RpsSubstituido.Serie  := Leitor.rCampo(tcStr, 'Serie');
                ListaNfse.FCompNfse[i].FNFSe.RpsSubstituido.Tipo   := StrToTipoRPS(ok, Leitor.rCampo(tcStr, 'Tipo'));
               end;

              // Grupo da TAG <Servico> *****************************************************
              if Leitor.rExtrai(iNivel + 5, 'Servico') <> ''
               then begin
                ListaNfse.FCompNfse[i].FNFSe.Servico.ItemListaServico          := DFeUtil.LimpaNumero(Leitor.rCampo(tcStr, 'ItemListaServico'));
                ListaNfse.FCompNfse[i].FNFSe.Servico.CodigoCnae                := Leitor.rCampo(tcStr, 'CodigoCnae');
                ListaNfse.FCompNfse[i].FNFSe.Servico.CodigoTributacaoMunicipio := Leitor.rCampo(tcStr, 'CodigoTributacaoMunicipio');
                ListaNfse.FCompNfse[i].FNFSe.Servico.Discriminacao             := Leitor.rCampo(tcStr, 'Discriminacao');

                if VersaoXML='1'
                 then ListaNfse.FCompNfse[i].FNFSe.Servico.CodigoMunicipio := Leitor.rCampo(tcStr, 'MunicipioPrestacaoServico')
                 else ListaNfse.FCompNfse[i].FNFSe.Servico.CodigoMunicipio := Leitor.rCampo(tcStr, 'CodigoMunicipio');

                Item := StrToInt(SomenteNumeros(ListaNfse.FCompNfse[i].FNfse.Servico.ItemListaServico));
                if Item<100 then Item:=Item*100+1;

                ListaNfse.FCompNfse[i].FNFSe.Servico.ItemListaServico := FormatFloat('0000', Item);
                ListaNfse.FCompNfse[i].FNFSe.Servico.ItemListaServico :=
                    Copy(ListaNfse.FCompNfse[i].FNFSe.Servico.ItemListaServico, 1, 2) + '.' +
                    Copy(ListaNfse.FCompNfse[i].FNFSe.Servico.ItemListaServico, 3, 2);

                if length(ListaNfse.FCompNfse[i].FNFSe.Servico.CodigoMunicipio)<7
                 then ListaNfse.FCompNfse[i].FNFSe.Servico.CodigoMunicipio :=
                       Copy(ListaNfse.FCompNfse[i].FNFSe.Servico.CodigoMunicipio, 1, 2) +
                       FormatFloat('00000', StrToIntDef(Copy(ListaNfse.FCompNfse[i].FNFSe.Servico.CodigoMunicipio, 3, 5), 0));

                if TabServicosExt
                 then ListaNfse.FCompNfse[i].FNFSe.Servico.xItemListaServico := NotaUtil.ObterDescricaoServico(SomenteNumeros(ListaNfse.FCompNfse[i].FNFSe.Servico.ItemListaServico))
                 else ListaNfse.FCompNfse[i].FNFSe.Servico.xItemListaServico := CodigoToDesc(SomenteNumeros(ListaNfse.FCompNfse[i].FNFSe.Servico.ItemListaServico));

                if Leitor.rExtrai(iNivel + 6, 'Valores') <> ''
                 then begin
                  ListaNfse.FCompNfse[i].FNFSe.Servico.Valores.ValorServicos          := Leitor.rCampo(tcDe2, 'ValorServicos');
                  ListaNfse.FCompNfse[i].FNFSe.Servico.Valores.ValorDeducoes          := Leitor.rCampo(tcDe2, 'ValorDeducoes');
                  ListaNfse.FCompNfse[i].FNFSe.Servico.Valores.ValorPis               := Leitor.rCampo(tcDe2, 'ValorPis');
                  ListaNfse.FCompNfse[i].FNFSe.Servico.Valores.ValorCofins            := Leitor.rCampo(tcDe2, 'ValorCofins');
                  ListaNfse.FCompNfse[i].FNFSe.Servico.Valores.ValorInss              := Leitor.rCampo(tcDe2, 'ValorInss');
                  ListaNfse.FCompNfse[i].FNFSe.Servico.Valores.ValorIr                := Leitor.rCampo(tcDe2, 'ValorIr');
                  ListaNfse.FCompNfse[i].FNFSe.Servico.Valores.ValorCsll              := Leitor.rCampo(tcDe2, 'ValorCsll');
                  ListaNfse.FCompNfse[i].FNFSe.Servico.Valores.IssRetido              := StrToSituacaoTributaria(ok, Leitor.rCampo(tcStr, 'IssRetido'));
                  ListaNfse.FCompNfse[i].FNFSe.Servico.Valores.ValorIss               := Leitor.rCampo(tcDe2, 'ValorIss');
                  ListaNfse.FCompNfse[i].FNFSe.Servico.Valores.OutrasRetencoes        := Leitor.rCampo(tcDe2, 'OutrasRetencoes');
                  ListaNfse.FCompNfse[i].FNFSe.Servico.Valores.BaseCalculo            := Leitor.rCampo(tcDe2, 'BaseCalculo');
                  ListaNfse.FCompNfse[i].FNFSe.Servico.Valores.Aliquota               := Leitor.rCampo(tcDe3, 'Aliquota');
                  ListaNfse.FCompNfse[i].FNFSe.Servico.Valores.ValorLiquidoNfse       := Leitor.rCampo(tcDe2, 'ValorLiquidoNfse');
                  ListaNfse.FCompNfse[i].FNFSe.Servico.Valores.ValorIssRetido         := Leitor.rCampo(tcDe2, 'ValorIssRetido');
                  ListaNfse.FCompNfse[i].FNFSe.Servico.Valores.DescontoCondicionado   := Leitor.rCampo(tcDe2, 'DescontoCondicionado');
                  ListaNfse.FCompNfse[i].FNFSe.Servico.Valores.DescontoIncondicionado := Leitor.rCampo(tcDe2, 'DescontoIncondicionado');
                 end;
               end;

              // Grupo da TAG <PrestadorServico> ********************************************
              if Leitor.rExtrai(iNivel + 5, 'PrestadorServico') <> ''
               then begin
                ListaNfse.FCompNfse[i].FNFSe.PrestadorServico.RazaoSocial  := Leitor.rCampo(tcStr, 'RazaoSocial');
                ListaNfse.FCompNfse[i].FNFSe.PrestadorServico.NomeFantasia := Leitor.rCampo(tcStr, 'NomeFantasia');

                ListaNfse.FCompNfse[i].FNFSe.PrestadorServico.Endereco.Endereco := Leitor.rCampo(tcStr, 'Endereco');
                if Copy(ListaNfse.FCompNfse[i].FNFSe.PrestadorServico.Endereco.Endereco, 1, 10 + k) = '<' + 'Endereco>'
                 then ListaNfse.FCompNfse[i].FNFSe.PrestadorServico.Endereco.Endereco := Copy(ListaNfse.FCompNfse[i].FNFSe.PrestadorServico.Endereco.Endereco, 11, 125);

                ListaNfse.FCompNfse[i].FNFSe.PrestadorServico.Endereco.Numero      := Leitor.rCampo(tcStr, 'Numero');
                ListaNfse.FCompNfse[i].FNFSe.PrestadorServico.Endereco.Complemento := Leitor.rCampo(tcStr, 'Complemento');
                ListaNfse.FCompNfse[i].FNFSe.PrestadorServico.Endereco.Bairro      := Leitor.rCampo(tcStr, 'Bairro');

                if VersaoXML='1'
                 then begin
                  ListaNfse.FCompNfse[i].FNFSe.PrestadorServico.Endereco.CodigoMunicipio := Leitor.rCampo(tcStr, 'Cidade');
                  ListaNfse.FCompNfse[i].FNFSe.PrestadorServico.Endereco.UF              := Leitor.rCampo(tcStr, 'Estado');
                 end
                 else begin
                  ListaNfse.FCompNfse[i].FNFSe.PrestadorServico.Endereco.CodigoMunicipio := Leitor.rCampo(tcStr, 'CodigoMunicipio');
                  ListaNfse.FCompNfse[i].FNFSe.PrestadorServico.Endereco.UF              := Leitor.rCampo(tcStr, 'Uf');
                 end;

                ListaNfse.FCompNfse[i].FNFSe.PrestadorServico.Endereco.CEP := Leitor.rCampo(tcStr, 'Cep');

                if length(ListaNfse.FCompNfse[i].FNFSe.PrestadorServico.Endereco.CodigoMunicipio)<7
                 then ListaNfse.FCompNfse[i].FNFSe.PrestadorServico.Endereco.CodigoMunicipio :=
                       Copy(ListaNfse.FCompNfse[i].FNFSe.PrestadorServico.Endereco.CodigoMunicipio, 1, 2) +
                       FormatFloat('00000', StrToIntDef(Copy(ListaNfse.FCompNfse[i].FNFSe.PrestadorServico.Endereco.CodigoMunicipio, 3, 5), 0));

                ListaNfse.FCompNfse[i].FNFSe.PrestadorServico.Endereco.xMunicipio := CodCidadeToCidade(StrToIntDef(ListaNfse.FCompNfse[i].FNFSe.PrestadorServico.Endereco.CodigoMunicipio, 0));

                if Leitor.rExtrai(iNivel + 6, 'Contato') <> ''
                 then begin
                  ListaNfse.FCompNfse[i].FNFSe.PrestadorServico.Contato.Telefone := Leitor.rCampo(tcStr, 'Telefone');
                  ListaNfse.FCompNfse[i].FNFSe.PrestadorServico.Contato.Email    := Leitor.rCampo(tcStr, 'Email');
                 end;

                if Leitor.rExtrai(iNivel + 6, 'IdentificacaoPrestador') <> ''
                 then begin
                  if VersaoXML='1'
                   then begin
                    if Leitor.rExtrai(iNivel + 7, 'CpfCnpj') <> ''
                     then begin
                      ListaNfse.FCompNfse[i].FNFSe.PrestadorServico.IdentificacaoPrestador.Cnpj := Leitor.rCampo(tcStr, 'Cpf');
                      if ListaNfse.FCompNfse[i].FNFSe.PrestadorServico.IdentificacaoPrestador.Cnpj = ''
                       then ListaNfse.FCompNfse[i].FNFSe.PrestadorServico.IdentificacaoPrestador.Cnpj := Leitor.rCampo(tcStr, 'Cnpj');
                     end;
                   end
                   else ListaNfse.FCompNfse[i].FNFSe.PrestadorServico.IdentificacaoPrestador.Cnpj := Leitor.rCampo(tcStr, 'Cnpj');
                  ListaNfse.FCompNfse[i].FNFSe.PrestadorServico.IdentificacaoPrestador.InscricaoMunicipal := Leitor.rCampo(tcStr, 'InscricaoMunicipal');
                 end;

               end;

              // Grupo da TAG <Prestador> ***************************************************
              if Leitor.rExtrai(iNivel + 5, 'Prestador') <> ''
               then begin
                ListaNfse.FCompNfse[i].FNFSe.Prestador.Cnpj               := Leitor.rCampo(tcStr, 'Cnpj');
                ListaNfse.FCompNfse[i].FNFSe.Prestador.InscricaoMunicipal := Leitor.rCampo(tcStr, 'InscricaoMunicipal');
               end;

              // Grupo da TAG <TomadorServico> **********************************************
              if Leitor.rExtrai(iNivel + 5, 'TomadorServico') <> ''
               then begin
                ListaNfse.FCompNfse[i].FNFSe.Tomador.RazaoSocial := Leitor.rCampo(tcStr, 'RazaoSocial');

                ListaNfse.FCompNfse[i].FNFSe.Tomador.Endereco.Endereco := Leitor.rCampo(tcStr, 'Endereco');
                if Copy(ListaNfse.FCompNfse[i].FNFSe.Tomador.Endereco.Endereco, 1, 10 + k) = '<' + 'Endereco>'
                 then ListaNfse.FCompNfse[i].FNFSe.Tomador.Endereco.Endereco := Copy(ListaNfse.FCompNfse[i].FNFSe.Tomador.Endereco.Endereco, 11, 125);

                ListaNfse.FCompNfse[i].FNFSe.Tomador.Endereco.Numero      := Leitor.rCampo(tcStr, 'Numero');
                ListaNfse.FCompNfse[i].FNFSe.Tomador.Endereco.Complemento := Leitor.rCampo(tcStr, 'Complemento');
                ListaNfse.FCompNfse[i].FNFSe.Tomador.Endereco.Bairro      := Leitor.rCampo(tcStr, 'Bairro');

                if VersaoXML='1'
                 then begin
                  ListaNfse.FCompNfse[i].FNFSe.Tomador.Endereco.CodigoMunicipio := Leitor.rCampo(tcStr, 'Cidade');
                  ListaNfse.FCompNfse[i].FNFSe.Tomador.Endereco.UF              := Leitor.rCampo(tcStr, 'Estado');
                 end
                 else begin
                  ListaNfse.FCompNfse[i].FNFSe.Tomador.Endereco.CodigoMunicipio := Leitor.rCampo(tcStr, 'CodigoMunicipio');
                  ListaNfse.FCompNfse[i].FNFSe.Tomador.Endereco.UF              := Leitor.rCampo(tcStr, 'Uf');
                 end;

                ListaNfse.FCompNfse[i].FNFSe.Tomador.Endereco.CEP := Leitor.rCampo(tcStr, 'Cep');

                if length(ListaNfse.FCompNfse[i].FNFSe.Tomador.Endereco.CodigoMunicipio)<7
                 then ListaNfse.FCompNfse[i].FNFSe.Tomador.Endereco.CodigoMunicipio :=
                       Copy(ListaNfse.FCompNfse[i].FNFSe.Tomador.Endereco.CodigoMunicipio, 1, 2) +
                       FormatFloat('00000', StrToIntDef(Copy(ListaNfse.FCompNfse[i].FNFSe.Tomador.Endereco.CodigoMunicipio, 3, 5), 0));

                if ListaNfse.FCompNfse[i].FNFSe.Tomador.Endereco.UF = ''
                 then ListaNfse.FCompNfse[i].FNFSe.Tomador.Endereco.UF := ListaNfse.FCompNfse[i].FNFSe.PrestadorServico.Endereco.UF;

                ListaNfse.FCompNfse[i].FNFSe.Tomador.Endereco.xMunicipio := CodCidadeToCidade(StrToIntDef(ListaNfse.FCompNfse[i].FNFSe.Tomador.Endereco.CodigoMunicipio, 0));

                if Leitor.rExtrai(iNivel + 6, 'Contato') <> ''
                 then begin
                  ListaNfse.FCompNfse[i].FNFSe.Tomador.Contato.Telefone := Leitor.rCampo(tcStr, 'Telefone');
                  ListaNfse.FCompNfse[i].FNFSe.Tomador.Contato.Email    := Leitor.rCampo(tcStr, 'Email');
                 end;

                if Leitor.rExtrai(iNivel + 6, 'IdentificacaoTomador') <> ''
                 then begin
                  ListaNfse.FCompNfse[i].FNFSe.Tomador.IdentificacaoTomador.InscricaoMunicipal := Leitor.rCampo(tcStr, 'InscricaoMunicipal');
                  if Leitor.rExtrai(iNivel + 7, 'CpfCnpj') <> ''
                   then begin
                    if Leitor.rCampo(tcStr, 'Cpf')<>''
                     then ListaNfse.FCompNfse[i].FNFSe.Tomador.IdentificacaoTomador.CpfCnpj := Leitor.rCampo(tcStr, 'Cpf')
                     else ListaNfse.FCompNfse[i].FNFSe.Tomador.IdentificacaoTomador.CpfCnpj := Leitor.rCampo(tcStr, 'Cnpj');
                   end;
                 end;

               end;

              // Grupo da TAG <Tomador> *****************************************************
              if Leitor.rExtrai(iNivel + 5, 'Tomador') <> ''
               then begin
                ListaNfse.FCompNfse[i].FNFSe.Tomador.RazaoSocial := Leitor.rCampo(tcStr, 'RazaoSocial');

                ListaNfse.FCompNfse[i].FNFSe.Tomador.Endereco.Endereco := Leitor.rCampo(tcStr, 'Endereco');
                if Copy(ListaNfse.FCompNfse[i].FNFSe.Tomador.Endereco.Endereco, 1, 10 + k) = '<' + 'Endereco>'
                 then ListaNfse.FCompNfse[i].FNFSe.Tomador.Endereco.Endereco := Copy(ListaNfse.FCompNfse[i].FNFSe.Tomador.Endereco.Endereco, 11, 125);

                ListaNfse.FCompNfse[i].FNFSe.Tomador.Endereco.Numero      := Leitor.rCampo(tcStr, 'Numero');
                ListaNfse.FCompNfse[i].FNFSe.Tomador.Endereco.Complemento := Leitor.rCampo(tcStr, 'Complemento');
                ListaNfse.FCompNfse[i].FNFSe.Tomador.Endereco.Bairro      := Leitor.rCampo(tcStr, 'Bairro');

                if VersaoXML='1'
                 then begin
                  ListaNfse.FCompNfse[i].FNFSe.Tomador.Endereco.CodigoMunicipio := Leitor.rCampo(tcStr, 'Cidade');
                  ListaNfse.FCompNfse[i].FNFSe.Tomador.Endereco.UF              := Leitor.rCampo(tcStr, 'Estado');
                 end
                 else begin
                  ListaNfse.FCompNfse[i].FNFSe.Tomador.Endereco.CodigoMunicipio := Leitor.rCampo(tcStr, 'CodigoMunicipio');
                  ListaNfse.FCompNfse[i].FNFSe.Tomador.Endereco.UF              := Leitor.rCampo(tcStr, 'Uf');
                 end;

                ListaNfse.FCompNfse[i].FNFSe.Tomador.Endereco.CEP := Leitor.rCampo(tcStr, 'Cep');

                if length(ListaNfse.FCompNfse[i].FNFSe.Tomador.Endereco.CodigoMunicipio)<7
                 then ListaNfse.FCompNfse[i].FNFSe.Tomador.Endereco.CodigoMunicipio :=
                       Copy(ListaNfse.FCompNfse[i].FNFSe.Tomador.Endereco.CodigoMunicipio, 1, 2) +
                       FormatFloat('00000', StrToIntDef(Copy(ListaNfse.FCompNfse[i].FNFSe.Tomador.Endereco.CodigoMunicipio, 3, 5), 0));

                if ListaNfse.FCompNfse[i].FNFSe.Tomador.Endereco.UF = ''
                 then ListaNfse.FCompNfse[i].FNFSe.Tomador.Endereco.UF := ListaNfse.FCompNfse[i].FNFSe.PrestadorServico.Endereco.UF;

                ListaNfse.FCompNfse[i].FNFSe.Tomador.Endereco.xMunicipio := CodCidadeToCidade(StrToIntDef(ListaNfse.FCompNfse[i].FNFSe.Tomador.Endereco.CodigoMunicipio, 0));

                if Leitor.rExtrai(iNivel + 6, 'Contato') <> ''
                 then begin
                  ListaNfse.FCompNfse[i].FNFSe.Tomador.Contato.Telefone := Leitor.rCampo(tcStr, 'Telefone');
                  ListaNfse.FCompNfse[i].FNFSe.Tomador.Contato.Email    := Leitor.rCampo(tcStr, 'Email');
                 end;

                if Leitor.rExtrai(iNivel + 6, 'IdentificacaoTomador') <> ''
                 then begin
                  ListaNfse.FCompNfse[i].FNFSe.Tomador.IdentificacaoTomador.InscricaoMunicipal := Leitor.rCampo(tcStr, 'InscricaoMunicipal');
                  if Leitor.rExtrai(iNivel + 7, 'CpfCnpj') <> ''
                   then begin
                    if Leitor.rCampo(tcStr, 'Cpf')<>''
                     then ListaNfse.FCompNfse[i].FNFSe.Tomador.IdentificacaoTomador.CpfCnpj := Leitor.rCampo(tcStr, 'Cpf')
                     else ListaNfse.FCompNfse[i].FNFSe.Tomador.IdentificacaoTomador.CpfCnpj := Leitor.rCampo(tcStr, 'Cnpj');
                   end;
                 end;

               end;

              // Grupo da TAG <IntermediarioServico> ****************************************
              if Leitor.rExtrai(iNivel + 5, 'IntermediarioServico') <> ''
               then begin
                ListaNfse.FCompNfse[i].FNFSe.IntermediarioServico.RazaoSocial        := Leitor.rCampo(tcStr, 'RazaoSocial');
                ListaNfse.FCompNfse[i].FNFSe.IntermediarioServico.InscricaoMunicipal := Leitor.rCampo(tcStr, 'InscricaoMunicipal');
                if Leitor.rExtrai(iNivel + 6, 'CpfCnpj') <> ''
                 then begin
                  if Leitor.rCampo(tcStr, 'Cpf')<>''
                   then ListaNfse.FCompNfse[i].FNFSe.IntermediarioServico.CpfCnpj := Leitor.rCampo(tcStr, 'Cpf')
                   else ListaNfse.FCompNfse[i].FNFSe.IntermediarioServico.CpfCnpj := Leitor.rCampo(tcStr, 'Cnpj');
                 end;
               end;

              // Grupo da TAG <OrgaoGerador> ************************************************
              if Leitor.rExtrai(iNivel + 5, 'OrgaoGerador') <> ''
               then begin
                ListaNfse.FCompNfse[i].FNFSe.OrgaoGerador.CodigoMunicipio := Leitor.rCampo(tcStr, 'CodigoMunicipio');
                ListaNfse.FCompNfse[i].FNFSe.OrgaoGerador.Uf              := Leitor.rCampo(tcStr, 'Uf');
               end;

              // Grupo da TAG <ConstrucaoCivil> *********************************************
              if Leitor.rExtrai(iNivel + 5, 'ConstrucaoCivil') <> ''
               then begin
                ListaNfse.FCompNfse[i].FNFSe.ConstrucaoCivil.CodigoObra := Leitor.rCampo(tcStr, 'CodigoObra');
                ListaNfse.FCompNfse[i].FNFSe.ConstrucaoCivil.Art        := Leitor.rCampo(tcStr, 'Art');
               end;

             end; // fim do InfNfse
           end; // fim do Nfse - Nivel 4


          // Grupo da TAG <NfseCancelamento> ********************************************
          if Leitor.rExtrai(iNivel + 3, 'NfseCancelamento') <> ''
           then begin
            ListaNfse.FCompNfse[i].NFSe.NfseCancelamento.DataHora := Leitor.rCampo(tcDatHor, 'DataHora');
            // provedor Betha sempre retorna a o grupo "NfseCancelamento" mesmo não estando cancelada,
            // o cancelamento deverá ser verificado na TAG especifica
            // Incluido por Roberto Godinho 13/11/20113
            if FProvedor = proBetha
             then begin
              Leitor.rExtrai(4,'InfConfirmacaoCancelamento');
              if StrToBool(Leitor.rCampo(tcStr, 'Sucesso'))
               then begin
                ListaNfse.CompNfse[i].NFSe.Status := srCancelado;
                ListaNfse.CompNfse[i].NFSe.NfseCancelamento.DataHora := Leitor.rCampo(tcDatHor, 'DataHora');
               end;
             end else
              begin
                // Incluido por Mauro Gomes
                // se não encontrou o campo DataHora, deve procurar pelo DataHoraCancelamento
                if (ListaNfse.FCompNfse[i].NFSe.NfseCancelamento.DataHora = 0) then
                   ListaNfse.FCompNfse[i].NFSe.NfseCancelamento.DataHora := Leitor.rCampo(tcDatHor, 'DataHoraCancelamento');
              end;
           end;

          // Grupo da TAG <NfseSubstituicao> ********************************************
          if Leitor.rExtrai(iNivel + 3, 'NfseSubstituicao') <> ''
           then begin
            ListaNfse.FCompNfse[i].FNfse.NfseSubstituidora := Leitor.rCampo(tcStr, 'NfseSubstituidora');
           end;

          inc(i);
        end; // fim do CompNfse - Nivel 3

      end; // fim do ListaNfse - Nivel 2

      // Ler a Lista de Mensagens
      if (leitor.rExtrai(iNivel + 1, 'ListaMensagemRetorno') <> '') or
         (leitor.rExtrai(iNivel + 1, 'Listamensagemretorno') <> '') or
         (leitor.rExtrai(iNivel + 1, 'ListaMensagemAlertaRetorno') <> '') or
         (leitor.rExtrai(iNivel + 1, 'ListaMensagemRetornoLote') <> '') then
      begin
        i := 0;
        while Leitor.rExtrai(iNivel + 2, 'MensagemRetorno', '', i + 1) <> '' do begin
          ListaNfse.FMsgRetorno.Add;
          ListaNfse.FMsgRetorno[i].FCodigo   := Leitor.rCampo(tcStr, 'Codigo');
          ListaNfse.FMsgRetorno[i].FMensagem := Leitor.rCampo(tcStr, 'Mensagem');
          ListaNfse.FMsgRetorno[i].FCorrecao := Leitor.rCampo(tcStr, 'Correcao');

          inc(i);
        end;

        if (ListaNfse.FMsgRetorno.Count <= 0) and (FProvedor = proDigifred) then begin
          i := 0;
          while Leitor.rExtrai(iNivel + 2, 'Codigo', '', i + 1) <> '' do begin
            ListaNfse.FMsgRetorno.Add;
            ListaNfse.FMsgRetorno[i].FCodigo := Leitor.rCampo(tcStr, 'Codigo');
            inc(i);
          end;

          i := 0;
          while Leitor.rExtrai(iNivel + 2, 'Mensagem', '', i + 1) <> '' do begin
            ListaNfse.FMsgRetorno[i].FMensagem := Leitor.rCampo(tcStr, 'Mensagem');
            inc(i);
          end;

          i := 0;
          while Leitor.rExtrai(iNivel + 2, 'Correcao', '', i + 1) <> '' do begin
            ListaNfse.FMsgRetorno[i].FCorrecao := Leitor.rCampo(tcStr, 'Correcao');
            inc(i);
          end;
        end;
      end;

      Result := True;
//    end;
  except
    result := False;
  end;
end;

function TretLote.LerXml_provedorIssDsf: boolean;  //falta homologar
var
  i, posI, count: Integer;
  VersaoXML: String;
  strAux: AnsiString;
  leitorAux: TLeitor;
begin
  result := False;

  try
    Leitor.Arquivo := NotaUtil.RetirarPrefixos(Leitor.Arquivo);
    VersaoXML      := '1';
    Leitor.Grupo   := Leitor.Arquivo;

    if leitor.rExtrai(1, 'RetornoConsultaLote') <> '' then
    begin

      if (leitor.rExtrai(2, 'Cabecalho') <> '') then
      begin
         if (Leitor.rCampo(tcStr, 'Sucesso') = 'true') then
         begin
            if (Leitor.rCampo(tcInt, 'QtdNotasProcessadas') > 0) then
            begin

               strAux := leitor.rExtrai(2, 'ListaNFSe');
               if (strAux <> '') then
               begin
                  i := 0 ;
                  posI := pos('<ConsultaNFSe>', strAux);

                  while ( posI > 0 ) do begin
                     count := pos('</ConsultaNFSe>', strAux) + 14;

                     ListaNfse.FCompNfse.Add;
                     inc(i);

                     LeitorAux := TLeitor.Create;
                     leitorAux.Arquivo := copy(strAux, PosI, count);
                     leitorAux.Grupo   := leitorAux.Arquivo;

                     ListaNfse.FCompNfse[i].FNFSe.Numero            := leitorAux.rCampo(tcStr, 'NumeroNFe');
                     ListaNfse.FCompNfse[i].FNFSe.CodigoVerificacao := leitorAux.rCampo(tcStr, 'CodigoVerificacao');
                     ListaNfse.FCompNfse[i].FNFSe.DataEmissaoRps    := leitorAux.rCampo(tcDatHor, 'DataEmissaoRPS');

                     LeitorAux.free;

                     Delete(strAux, PosI, count);
                     posI := pos('<ConsultaNFSe>', strAux);
                  end;
               end;
            end;
         end;
      end;

      i := 0 ;
      if (leitor.rExtrai(2, 'Alertas') <> '') then
      begin
         strAux := leitor.rExtrai(2, 'Alertas');
         if (strAux <> '') then
         begin
            posI := pos('<Alerta>', strAux);

            while ( posI > 0 ) do begin
               count := pos('</Alerta>', strAux) + 7;

               ListaNfse.FMsgRetorno.Add;
               inc(i);

               LeitorAux := TLeitor.Create;
               leitorAux.Arquivo := copy(strAux, PosI, count);
               leitorAux.Grupo   := leitorAux.Arquivo;

               ListaNfse.FMsgRetorno[i].FCodigo  := leitorAux.rCampo(tcStr, 'Codigo');
               ListaNfse.FMsgRetorno[i].Mensagem := leitorAux.rCampo(tcStr, 'Descricao');

               LeitorAux.free;

               Delete(strAux, PosI, count);
               posI := pos('<Alerta>', strAux);
            end;
         end;
      end;

      if (leitor.rExtrai(2, 'Erros') <> '') then
      begin

         strAux := leitor.rExtrai(2, 'Erros');
         if (strAux <> '') then
         begin
            //i := 0 ;
            posI := pos('<Erro>', strAux);

            while ( posI > 0 ) do begin
               count := pos('</Erro>', strAux) + 6;

               ListaNfse.FMsgRetorno.Add;
               inc(i);

               LeitorAux := TLeitor.Create;
               leitorAux.Arquivo := copy(strAux, PosI, count);
               leitorAux.Grupo   := leitorAux.Arquivo;

               ListaNfse.FMsgRetorno[i].FCodigo  := leitorAux.rCampo(tcStr, 'Codigo');
               ListaNfse.FMsgRetorno[i].Mensagem := leitorAux.rCampo(tcStr, 'Descricao');

               LeitorAux.free;

               Delete(strAux, PosI, count);
               posI := pos('<Erro>', strAux);
            end;
         end;
      end;
      Result := True;
    end;
  except
    result := False;
  end;
end;

function TretLote.LerXML_provedorSP: Boolean;
var
  Ini, Sucesso, Erro, CodErro, DescErro, Aux, Mestre:String;
  QtdNotas, I: Integer;
  Fmt: TFormatSettings;
  ok: Boolean;
  function Remover(Str, AStr:String):String;
  begin
    Result:=StringReplace(Str,'<'+AStr+'>', '', [rfReplaceAll, rfIgnoreCase]);
    Result:=StringReplace(Result,'</'+AStr+'>', '', [rfReplaceAll, rfIgnoreCase]);
  end;
begin
    //1 - Aguardando processamento
		//2 - Não Processado, lote com erro
		//3 - Processado com sucesso
		//4 - Processado com avisos

    Leitor.Arquivo := NotaUtil.RetirarPrefixos(Leitor.Arquivo);
    Leitor.Grupo   := Leitor.Arquivo;
    Sucesso:=LowerCase(Leitor.rCampo(tcStr, 'Sucesso'));
    Mestre:=Leitor.Arquivo;
    I:=0;
    try
    //NOVO INI
      if Sucesso = 'true' then begin //lote autorizado
        ListaNfse.Situacao:='3';
        ListaNfse.FCompNfse.Add;
        Leitor.Grupo:=Leitor.rExtrai(1, 'NFe');

        fmt.ShortDateFormat:='yyyy-mm-dd';
        fmt.DateSeparator  :='-';
        fmt.LongTimeFormat :='hh:nn:ss';
        fmt.TimeSeparator  :=':';

        ListaNfse.FCompNfse[i].FNfse.XML :=Leitor.Grupo;
        ListaNfse.FCompNfse[I].FNFSe.Status:= StrToEnumerado(ok, Leitor.rCampo(tcStr, 'StatusNFe'),['N','C'],[srNormal, srCancelado]);
        if Leitor.rCampo(tcStr, 'DataCancelamento') <> '' then begin
          ListaNfse.FCompNfse[I].FNFSe.NfseCancelamento.DataHora:=StrToDateTime(StringReplace(Leitor.rCampo(tcStr, 'DataCancelamento'), 'T', ' ', [rfReplaceAll]), fmt);
        end;
        //Dados da nota
        Aux:=Leitor.Grupo;
        Leitor.Grupo:=Leitor.rCampo(tcStr, 'ChaveNFe');
        ListaNfse.FCompNfse[I].FNFSe.Numero:=Leitor.rCampo(tcInt, 'NumeroNFe');
        ListaNfse.FCompNfse[I].FNFSe.Prestador.InscricaoMunicipal:=Leitor.rCampo(tcInt, 'InscricaoPrestador');
        ListaNfse.FCompNfse[I].FNFSe.CodigoVerificacao:=Leitor.rCampo(tcStr, 'CodigoVerificacao');
        Leitor.Grupo:=Aux;

        ListaNfse.FCompNfse[I].FNFSe.DataEmissao:=StrToDateTime(StringReplace(Leitor.rCampo(tcStr, 'DataEmissaoNFe'), 'T', ' ', [rfReplaceAll]), fmt);
        ListaNfse.FCompNfse[I].FNFSe.NumeroLote:=Leitor.rCampo(tcInt, 'NumeroLote');

        Aux:=Leitor.Grupo;
        Leitor.Grupo:=Leitor.rCampo(tcStr, 'ChaveRPS');
        ListaNfse.FCompNfse[I].FNFSe.IdentificacaoRps.Serie:=Leitor.rCampo(tcInt, 'SerieRPS');
        ListaNfse.FCompNfse[I].FNFSe.IdentificacaoRps.Numero:=Leitor.rCampo(tcInt, 'NumeroRPS');
        Leitor.Grupo:=Aux;

        ListaNfse.FCompNfse[I].FNFSe.DataEmissaoRps:=StrToDate(Leitor.rCampo(tcStr, 'DataEmissaoRPS'), fmt);
        ListaNfse.FCompNfse[I].FNFSe.PrestadorServico.IdentificacaoPrestador.Cnpj:=Remover(Remover(Leitor.rCampo(tcStr, 'CPFCNPJPrestador'), 'CPF'), 'CNPJ');

        //Prestador
        ListaNfse.FCompNfse[I].FNFSe.PrestadorServico.RazaoSocial:=Leitor.rCampo(tcStr, 'RazaoSocialPrestador');

        Aux:=Leitor.Grupo;
        Leitor.Grupo:=Leitor.rCampo(tcStr, 'EnderecoPrestador');
        ListaNfse.FCompNfse[I].FNFSe.PrestadorServico.Endereco.Endereco:=Leitor.rCampo(tcStr, 'Logradouro');
        ListaNfse.FCompNfse[I].FNFSe.PrestadorServico.Endereco.TipoLogradouro:=Leitor.rCampo(tcStr, 'TipoLogradouro');
        ListaNfse.FCompNfse[I].FNFSe.PrestadorServico.Endereco.Numero:=Leitor.rCampo(tcStr, 'NumeroEndereco');
        ListaNfse.FCompNfse[I].FNFSe.PrestadorServico.Endereco.Complemento:=Leitor.rCampo(tcStr, 'ComplementoEndereco');
        ListaNfse.FCompNfse[I].FNFSe.PrestadorServico.Endereco.Bairro:=Leitor.rCampo(tcStr, 'Bairro');
        ListaNfse.FCompNfse[I].FNFSe.PrestadorServico.Endereco.UF:=Leitor.rCampo(tcStr, 'UF');
        ListaNfse.FCompNfse[I].FNFSe.PrestadorServico.Endereco.CEP:=Leitor.rCampo(tcStr, 'CEP');
        ListaNfse.FCompNfse[I].FNFSe.PrestadorServico.Endereco.CodigoMunicipio:=Leitor.rCampo(tcStr, 'Cidade');
        ListaNfse.FCompNfse[I].FNFSe.PrestadorServico.Endereco.xMunicipio:=CodCidadeToCidade(StrToIntDef(ListaNfse.FCompNfse[I].FNFSe.PrestadorServico.Endereco.CodigoMunicipio, 0));
        Leitor.Grupo:=Aux;

        //Endereço do serviço vai ser o município do prestador
        ListaNfse.FCompNfse[I].FNFSe.Servico.CodigoMunicipio:=ListaNfse.FCompNfse[I].FNFSe.PrestadorServico.Endereco.CodigoMunicipio;

        ListaNfse.FCompNfse[I].FNFSe.PrestadorServico.Contato.Email:=Leitor.rCampo(tcStr, 'EmailPrestador');
        //ver o que é statusnfe, tributacaonfe, opcaosimples
        {OpcaoSimples
          0 - Não optante
          1 - Optante Simples federal 1.0%
          2 - Optante Simples federal 0.5%
          3 - Optante Simples municipal
          4 - Optante Simples nacional
        }
        if Leitor.rCampo(tcInt, 'OpcaoSimples') = '4' then
          ListaNfse.FCompNfse[I].FNFSe.OptanteSimplesNacional:=snSim
        else ListaNfse.FCompNfse[I].FNFSe.OptanteSimplesNacional:=snNao;

        ListaNfse.FCompNfse[I].FNFSe.PrestadorServico.Contato.Email:=Leitor.rCampo(tcStr, 'EmailPrestador');
        //ver o que é statusnfe, tributacaonfe, opcaosimples
        //Valores
        //Valores
        ListaNfse.FCompNfse[I].FNFSe.Servico.Valores.ValorServicos:=Leitor.rCampo(tcDe2, 'ValorServicos');
        ListaNfse.FCompNfse[I].FNFSe.Servico.Valores.ValorDeducoes:=Leitor.rCampo(tcDe2, 'ValorDeducoes');
        ListaNfse.FCompNfse[I].FNFSe.Servico.Valores.ValorPis:=Leitor.rCampo(tcDe2, 'ValorPIS');
        ListaNfse.FCompNfse[I].FNFSe.Servico.Valores.ValorCofins:=Leitor.rCampo(tcDe2, 'ValorCOFINS');
        ListaNfse.FCompNfse[I].FNFSe.Servico.Valores.ValorInss:=Leitor.rCampo(tcDe2, 'ValorINSS');
        ListaNfse.FCompNfse[I].FNFSe.Servico.Valores.ValorIr:=Leitor.rCampo(tcDe2, 'ValorIR');
        ListaNfse.FCompNfse[I].FNFSe.Servico.Valores.ValorCsll:=Leitor.rCampo(tcDe2, 'ValorCSLL');

        ListaNfse.FCompNfse[I].FNFSe.Servico.Valores.ValorDeducoes:=Leitor.rCampo(tcDe2, 'ValorDeducoes');
        ListaNfse.FCompNfse[I].FNFSe.Servico.Valores.Aliquota:=Leitor.rCampo(tcDe2, 'AliquotaServicos');
        ListaNfse.FCompNfse[I].FNFSe.Servico.Valores.ValorIss:=Leitor.rCampo(tcDe2, 'ValorISS');
        ///////////////////////////////////////VERIFICAR///////////////////////////
        //NFSe.Servico.Valores.IssRetido:=Leitor.rCampo(tcInt, 'ISSRetido');

        ListaNfse.FCompNfse[I].FNFSe.Tomador.IdentificacaoTomador.CpfCnpj:=Remover(Remover(Leitor.rCampo(tcStr, 'CPFCNPJTomador'), 'CPF'), 'CNPJ');
        ListaNfse.FCompNfse[I].FNFSe.Tomador.RazaoSocial:=Leitor.rCampo(tcStr, 'RazaoSocialTomador');

        Aux:=Leitor.Grupo;
        Leitor.Grupo:=Leitor.rCampo(tcStr, 'EnderecoTomador');
        ListaNfse.FCompNfse[I].FNFSe.Tomador.Endereco.TipoLogradouro:=Leitor.rCampo(tcStr, 'TipoLogradouro');
        ListaNfse.FCompNfse[I].FNFSe.Tomador.Endereco.Endereco:=Leitor.rCampo(tcStr, 'Logradouro');
        ListaNfse.FCompNfse[I].FNFSe.Tomador.Endereco.Numero:=Leitor.rCampo(tcStr, 'NumeroEndereco');
        ListaNfse.FCompNfse[I].FNFSe.Tomador.Endereco.Complemento:=Leitor.rCampo(tcStr, 'ComplementoEndereco');
        ListaNfse.FCompNfse[I].FNFSe.Tomador.Endereco.Bairro:=Leitor.rCampo(tcStr, 'Bairro');
     	  ListaNfse.FCompNfse[I].FNFSe.Tomador.Endereco.CodigoMunicipio:=Leitor.rCampo(tcStr, 'Cidade');
    	  ListaNfse.FCompNfse[I].FNFSe.Tomador.Endereco.xMunicipio:=CodCidadeToCidade(StrToIntDef(ListaNfse.FCompNfse[I].FNFSe.Tomador.Endereco.CodigoMunicipio, 0));
        ListaNfse.FCompNfse[I].FNFSe.Tomador.Endereco.UF:=Leitor.rCampo(tcStr, 'UF');
        ListaNfse.FCompNfse[I].FNFSe.Tomador.Endereco.CEP:=Leitor.rCampo(tcStr, 'CEP');
        Leitor.Grupo:=Aux;

        ListaNfse.FCompNfse[I].FNFSe.Servico.Discriminacao:=Leitor.rCampo(tcStr, 'Discriminacao');
        ListaNfse.FCompNfse[I].FNFSe.Servico.CodigoTributacaoMunicipio:=Leitor.rCampo(tcInt, 'CodigoServico');

        Leitor.Arquivo:=Mestre;
        Leitor.Grupo:=Mestre;

        if Leitor.rExtrai(1, 'Alerta') <> '' then begin //processado com avisos
          Leitor.Arquivo:=Leitor.rExtrai(1, 'Alerta');
          ListaNfse.MsgRetorno.Add;
          ListaNfse.FMsgRetorno[I].FCodigo  := Leitor.rCampo(tcInt, 'Codigo');
          ListaNfse.FMsgRetorno[I].Mensagem := Leitor.rCampo(tcStr, 'Descricao');
          ListaNfse.Situacao:='4';
        end;
      end else if Sucesso = 'false' then begin //lote não autorizado
        if Leitor.rExtrai(1, 'Erro') <> '' then begin
          Leitor.Grupo:=Leitor.rExtrai(1, 'Erro');
          ListaNfse.MsgRetorno.Add;
          ListaNfse.FMsgRetorno[I].FCodigo  := Leitor.rCampo(tcInt, 'Codigo');
          ListaNfse.FMsgRetorno[I].Mensagem := Leitor.rCampo(tcStr, 'Descricao');
          ListaNfse.Situacao:='2';
        end else Result:=False;
      end;

    //NOVO FIM

//      if Leitor.rExtrai(2, 'RetornoEnvioLoteRPS') <> '' then QtNotas:=Leitor.rExtrai(2, 'QtdNotasProcessadas');
 //     QtdNotas:=Leitor.rCampo(tcInt, 'QtdNotasProcessadas');
    //InfSit.FNumeroLote:='0';


      //<QtdNotasProcessadas>1</QtdNotasProcessadas>
    {  Sucesso:=StringReplace(StringReplace(Leitor.rExtrai(1, 'Sucesso'), '<Sucesso>', '', [rfReplaceAll, rfIgnoreCase]), '</Sucesso>', '', [rfReplaceAll, rfIgnoreCase]);
      Erro:=Leitor.rExtrai(1, 'Erro');
      CodErro:=Leitor.rExtrai(2, 'Codigo');
      CodErro:=StringReplace(StringReplace(CodErro, '<Codigo>', '', [rfReplaceAll, rfIgnoreCase]), '</Codigo>', '', [rfReplaceAll, rfIgnoreCase]);
      DescErro:=Leitor.rExtrai(2, 'Descricao');
      DescErro:=StringReplace(StringReplace(DescErro, '<Descricao>', '', [rfReplaceAll, rfIgnoreCase]), '</Descricao>', '', [rfReplaceAll, rfIgnoreCase]);
      if UpperCase(Trim(Sucesso)) <> 'TRUE' then begin
        if Trim(CodErro) <> '' then begin
          ListaNfse.FMsgRetorno.Add;
          ListaNfse.FMsgRetorno[0].FCodigo  := CodErro;
          ListaNfse.FMsgRetorno[0].Mensagem := DescErro;
          {case StrToInt(Trim(CodErro)) of
            1105: begin //Lote não encontrado ou processado com erro
              InfSit.FSituacao   := '2';
            end;
          end;
        end;
      end else begin
        //InfSit.FSituacao:=3;
        //if  then

      end; }
      Result:=True;
    except
      Result:=False;
    end;
end;

{
function TretLote.ObterDescricaoServico(cCodigo: string): string;
var
 i           : integer;
 PathArquivo : string;
 List        : TstringList;
begin
 result := '';

 if FPathArquivoTabServicos = ''
  then FPathArquivoTabServicos := NotaUtil.PathWithDelim(ExtractFileDir(application.ExeName)) + 'TabServicos\';

 PathArquivo := FPathArquivoTabServicos + 'TabServicos.txt';
 if (FileExists(PathArquivo)) and (cCodigo <> '')
  then begin
   List := TstringList.Create;
   List.LoadFromFile(PathArquivo);
   i := 0;
   while (i < list.count) and (result = '') do
    begin
     if pos(cCodigo, List[i]) > 0
      then result := Trim(stringReplace(list[i], ccodigo, '', []));
     inc(i);
   end;
   List.free;
  end;
end;
}

function TretLote.LerXML_provedorEquiplano: Boolean;
var
  i: Integer;
begin
  try
    Leitor.Arquivo := NotaUtil.RetirarPrefixos(Leitor.Arquivo);
    Leitor.Grupo   := Leitor.Arquivo;

    if leitor.rExtrai(1, 'listaNfse') <> '' then
      begin
        i:= 0;
        while leitor.rExtrai(2, 'nfse', '', i + 1) <> '' do
          begin
            ListaNfse.FCompNfse.Add;
            ListaNfse.FCompNfse[i].FNFSe.Numero                 := leitor.rCampo(tcStr, 'nrNfse');
            ListaNfse.FCompNfse[i].FNFSe.CodigoVerificacao      := leitor.rCampo(tcStr, 'cdAutenticacao');
            ListaNfse.FCompNfse[i].FNFSe.DataEmissao            := leitor.rCampo(tcDatHor, 'dtEmissaoNfs');
            ListaNfse.FCompNfse[i].FNFSe.IdentificacaoRps.Numero:= leitor.rCampo(tcStr, 'nrRps');
            if Leitor.rExtrai(3, 'cancelamento') <> '' then
            begin
              ListaNfse.FCompNfse[i].NFSe.NfseCancelamento.DataHora:= Leitor.rCampo(tcDatHor, 'dtCancelamento');
              ListaNfse.FCompNfse[i].NFSe.MotivoCancelamento       := Leitor.rCampo(tcStr, 'dsCancelamento');
              ListaNfse.FCompNfse[i].NFSe.Status := srCancelado;
            end;
            inc(i);
          end;
      end;

    if leitor.rExtrai(1, 'mensagemRetorno') <> '' then
      begin
        i := 0;
        if (leitor.rExtrai(2, 'listaErros') <> '') then
          begin
            while Leitor.rExtrai(3, 'erro', '', i + 1) <> '' do
              begin
                ListaNfse.FMsgRetorno.Add;
                ListaNfse.FMsgRetorno[i].FCodigo   := Leitor.rCampo(tcStr, 'cdMensagem');
                ListaNfse.FMsgRetorno[i].FMensagem := Leitor.rCampo(tcStr, 'dsMensagem');
                ListaNfse.FMsgRetorno[i].FCorrecao := Leitor.rCampo(tcStr, 'dsCorrecao');

                inc(i);
              end;
          end;

        if (leitor.rExtrai(2, 'listaAlertas') <> '') then
          begin
            while Leitor.rExtrai(3, 'alerta', '', i + 1) <> '' do
              begin
                ListaNfse.FMsgRetorno.Add;
                ListaNfse.FMsgRetorno[i].FCodigo   := Leitor.rCampo(tcStr, 'cdMensagem');
                ListaNfse.FMsgRetorno[i].FMensagem := Leitor.rCampo(tcStr, 'dsMensagem');
                ListaNfse.FMsgRetorno[i].FCorrecao := Leitor.rCampo(tcStr, 'dsCorrecao');

                inc(i);
              end;
          end;
      end;

    Result := True;
  except
    result := False;
  end;
end;

end.
