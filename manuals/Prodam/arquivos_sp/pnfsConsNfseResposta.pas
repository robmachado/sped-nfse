unit pnfsConsNfseResposta;

interface

uses
  SysUtils, Classes, Forms,
  pcnAuxiliar, pcnConversao, pcnLeitor,
  pnfsConversao, pnfsNFSe, ACBrUtil, ACBrNFSeUtil, ACBrDFeUtil;

type

 TCompNfseCollection = class;
 TCompNfseCollectionItem = class;
 TMsgRetornoNfseCollection = class;
 TMsgRetornoNfseCollectionItem = class;

 TListaNfse2 = class(TPersistent)
  private
    FCompNfse : TCompNfseCollection;
    FMsgRetorno : TMsgRetornoNfseCollection;
    FPagina: Integer;
    procedure SetCompNfse(Value: TCompNfseCollection);
    procedure SetMsgRetorno(Value: TMsgRetornoNfseCollection);
  public
    constructor Create; reintroduce;
    destructor Destroy; override;
    property CompNfse: TCompNfseCollection         read FCompNfse   write SetCompNfse;
    property MsgRetorno: TMsgRetornoNfseCollection read FMsgRetorno write SetMsgRetorno;
    property Pagina: Integer                       read FPagina     write FPagina;
  end;

 TCompNfseCollection = class(TCollection)
  private
    function GetItem(Index: Integer): TCompNfseCollectionItem;
    procedure SetItem(Index: Integer; Value: TCompNfseCollectionItem);
  public
    constructor Create(AOwner: TListaNfse2);
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

 TMsgRetornoNfseCollection = class(TCollection)
  private
    function GetItem(Index: Integer): TMsgRetornoNfseCollectionItem;
    procedure SetItem(Index: Integer; Value: TMsgRetornoNfseCollectionItem);
  public
    constructor Create(AOwner: TListaNfse2);
    function Add: TMsgRetornoNfseCollectionItem;
    property Items[Index: Integer]: TMsgRetornoNfseCollectionItem read GetItem write SetItem; default;
  end;

 TMsgRetornoNfseCollectionItem = class(TCollectionItem)
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

 TretNfse = class(TPersistent)
  private
    FPathArquivoMunicipios: string;
    FPathArquivoTabServicos: string;
    FLeitor: TLeitor;
    FListaNfse: TListaNfse2;
    FProvedor: TnfseProvedor;
    FTabServicosExt: Boolean;
//    function ObterDescricaoServico(cCodigo: string): string;
  public
    constructor Create;
    destructor Destroy; override;
    function LerXml: boolean;
    function LerXml_provedorIssDsf: boolean;
  published
    property PathArquivoMunicipios: string  read FPathArquivoMunicipios  write FPathArquivoMunicipios;
    property PathArquivoTabServicos: string read FPathArquivoTabServicos write FPathArquivoTabServicos;
    property Leitor: TLeitor                read FLeitor                 write FLeitor;
    property ListaNfse: TListaNfse2         read FListaNfse              write FListaNfse;
    property Provedor: TnfseProvedor        read FProvedor               write FProvedor;
    property TabServicosExt: Boolean        read FTabServicosExt         write FTabServicosExt;
  end;

implementation

{ TListaNfse2 }

constructor TListaNfse2.Create;
begin
  FCompNfse   := TCompNfseCollection.Create(Self);
  FMsgRetorno := TMsgRetornoNfseCollection.Create(Self);
end;

destructor TListaNfse2.Destroy;
begin
  FCompNfse.Free;
  FMsgRetorno.Free;

  inherited;
end;

procedure TListaNfse2.SetCompNfse(Value: TCompNfseCollection);
begin
  FCompNfse.Assign(Value);
end;

procedure TListaNfse2.SetMsgRetorno(Value: TMsgRetornoNfseCollection);
begin
  FMsgRetorno.Assign(Value);
end;

{ TCompNfseCollection }

function TCompNfseCollection.Add: TCompNfseCollectionItem;
begin
  Result := TCompNfseCollectionItem(inherited Add);
  Result.create;
end;

constructor TCompNfseCollection.Create(AOwner: TListaNfse2);
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

{ TMsgRetornoNfseCollection }

function TMsgRetornoNfseCollection.Add: TMsgRetornoNfseCollectionItem;
begin
  Result := TMsgRetornoNfseCollectionItem(inherited Add);
  Result.create;
end;

constructor TMsgRetornoNfseCollection.Create(AOwner: TListaNfse2);
begin
  inherited Create(TMsgRetornoNfseCollectionItem);
end;

function TMsgRetornoNfseCollection.GetItem(
  Index: Integer): TMsgRetornoNfseCollectionItem;
begin
  Result := TMsgRetornoNfseCollectionItem(inherited GetItem(Index));
end;

procedure TMsgRetornoNfseCollection.SetItem(Index: Integer;
  Value: TMsgRetornoNfseCollectionItem);
begin
  inherited SetItem(Index, Value);
end;

{ TMsgRetornoNfseCollectionItem }

constructor TMsgRetornoNfseCollectionItem.Create;
begin

end;

destructor TMsgRetornoNfseCollectionItem.Destroy;
begin

  inherited;
end;

{ TretNfse }

constructor TretNfse.Create;
begin
  FLeitor                 := TLeitor.Create;
  FListaNfse              := TListaNfse2.Create;
  FPathArquivoMunicipios  := '';
  FPathArquivoTabServicos := '';
end;

destructor TretNfse.Destroy;
begin
  FLeitor.Free;
  FListaNfse.Free;
  inherited;
end;

function TretNfse.LerXml: boolean;
var
  ok: boolean;
  i, k, Item: Integer;
  VersaoXML: String;
begin
  result := False;
  
  try
    Leitor.Arquivo := NotaUtil.RetirarPrefixos(Leitor.Arquivo);
    VersaoXML      := NotaUtil.VersaoXML(Leitor.Arquivo);
    Leitor.Grupo   := Leitor.Arquivo;

    k        := 0; //length(Prefixo4);

    // Alterado por Akai - L. Massao Aihara 31/10/2013
    if (leitor.rExtrai(1, 'ConsultarNfseResposta') <> '') or
       (leitor.rExtrai(1, 'Consultarnfseresposta') <> '') or
       (leitor.rExtrai(1, 'ConsultarNfseServicoPrestadoResposta') <> '') or
       (leitor.rExtrai(1, 'ConsultarNfseResult') <> '') then
    begin
      // Ler a Lista de NFSe
      if leitor.rExtrai(2, 'ListaNfse') <> '' then
      begin
        // Incluido por Italo em 15/07/2013
        // Será utilizado para realizar as consultas as NFSe
        // quando o provedor por Fiorilli e fintelISS
        ListaNfse.FPagina := Leitor.rCampo(tcInt, 'ProximaPagina');

        i := 0;
        // Alterado por Rodrigo Cantelli
        while (Leitor.rExtrai(3, 'CompNfse', '', i + 1) <> '') or
              (Leitor.rExtrai(3, 'ComplNfse', '', i + 1) <> '') do
        begin
          ListaNfse.FCompNfse.Add;

          // Grupo da TAG <Nfse> *************************************************
          if Leitor.rExtrai(4, 'Nfse') <> ''
           then begin
            // Grupo da TAG <InfNfse> *****************************************************
            if Leitor.rExtrai(5, 'InfNfse') <> ''
             then begin
              ListaNfse.FCompNfse[i].FNfse.InfID.ID          := Leitor.rCampo(tcStr, 'Numero');
              ListaNfse.FCompNfse[i].FNFSe.Numero            := Leitor.rCampo(tcStr, 'Numero');
              ListaNfse.FCompNfse[i].FNFSe.CodigoVerificacao := Leitor.rCampo(tcStr, 'CodigoVerificacao');
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
              if Leitor.rExtrai(6, 'IdentificacaoRps') <> ''
               then begin
                ListaNfse.FCompNfse[i].FNFSe.IdentificacaoRps.Numero := Leitor.rCampo(tcStr, 'Numero');
                ListaNfse.FCompNfse[i].FNFSe.IdentificacaoRps.Serie  := Leitor.rCampo(tcStr, 'Serie');
                ListaNfse.FCompNfse[i].FNFSe.IdentificacaoRps.Tipo   := StrToTipoRPS(ok, Leitor.rCampo(tcStr, 'Tipo'));
               end;

              // Grupo da TAG <RpsSubstituido> **********************************************
              if Leitor.rExtrai(6, 'RpsSubstituido') <> ''
               then begin
                ListaNfse.FCompNfse[i].FNFSe.RpsSubstituido.Numero := Leitor.rCampo(tcStr, 'Numero');
                ListaNfse.FCompNfse[i].FNFSe.RpsSubstituido.Serie  := Leitor.rCampo(tcStr, 'Serie');
                ListaNfse.FCompNfse[i].FNFSe.RpsSubstituido.Tipo   := StrToTipoRPS(ok, Leitor.rCampo(tcStr, 'Tipo'));
               end;

              // Grupo da TAG <Servico> *****************************************************
              if Leitor.rExtrai(6, 'Servico') <> ''
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

                if Leitor.rExtrai(7, 'Valores') <> ''
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
              if Leitor.rExtrai(6, 'PrestadorServico') <> ''
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

                if Leitor.rExtrai(7, 'Contato') <> ''
                 then begin
                  ListaNfse.FCompNfse[i].FNFSe.PrestadorServico.Contato.Telefone := Leitor.rCampo(tcStr, 'Telefone');
                  ListaNfse.FCompNfse[i].FNFSe.PrestadorServico.Contato.Email    := Leitor.rCampo(tcStr, 'Email');
                 end;

                if Leitor.rExtrai(7, 'IdentificacaoPrestador') <> ''
                 then begin
                  if VersaoXML='1'
                   then begin
                    if Leitor.rExtrai(8, 'CpfCnpj') <> ''
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
              if Leitor.rExtrai(6, 'Prestador') <> ''
               then begin
                ListaNfse.FCompNfse[i].FNFSe.Prestador.Cnpj               := Leitor.rCampo(tcStr, 'Cnpj');
                ListaNfse.FCompNfse[i].FNFSe.Prestador.InscricaoMunicipal := Leitor.rCampo(tcStr, 'InscricaoMunicipal');
               end;

              // Grupo da TAG <TomadorServico> **********************************************
              if Leitor.rExtrai(6, 'TomadorServico') <> ''
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

                if Leitor.rExtrai(7, 'Contato') <> ''
                 then begin
                  ListaNfse.FCompNfse[i].FNFSe.Tomador.Contato.Telefone := Leitor.rCampo(tcStr, 'Telefone');
                  ListaNfse.FCompNfse[i].FNFSe.Tomador.Contato.Email    := Leitor.rCampo(tcStr, 'Email');
                 end;

                if Leitor.rExtrai(7, 'IdentificacaoTomador') <> ''
                 then begin
                  ListaNfse.FCompNfse[i].FNFSe.Tomador.IdentificacaoTomador.InscricaoMunicipal := Leitor.rCampo(tcStr, 'InscricaoMunicipal');
                  if Leitor.rExtrai(8, 'CpfCnpj') <> ''
                   then begin
                    if Leitor.rCampo(tcStr, 'Cpf')<>''
                     then ListaNfse.FCompNfse[i].FNFSe.Tomador.IdentificacaoTomador.CpfCnpj := Leitor.rCampo(tcStr, 'Cpf')
                     else ListaNfse.FCompNfse[i].FNFSe.Tomador.IdentificacaoTomador.CpfCnpj := Leitor.rCampo(tcStr, 'Cnpj');
                   end;
                 end;

               end;

              // Grupo da TAG <Tomador> *****************************************************
              if Leitor.rExtrai(6, 'Tomador') <> ''
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
//                ListaNfse.FCompNfse[i].FNFSe.Tomador.Endereco.xMunicipio := UpperCase(TiraAcentos(Utf8ToAnsi(ObterNomeMunicipio(ListaNfse.FCompNfse[i].FNFSe.Tomador.Endereco.UF,
//                                                         StrToIntDef(ListaNfse.FCompNfse[i].FNFSe.Tomador.Endereco.CodigoMunicipio, 0)))));

                if Leitor.rExtrai(7, 'Contato') <> ''
                 then begin
                  ListaNfse.FCompNfse[i].FNFSe.Tomador.Contato.Telefone := Leitor.rCampo(tcStr, 'Telefone');
                  ListaNfse.FCompNfse[i].FNFSe.Tomador.Contato.Email    := Leitor.rCampo(tcStr, 'Email');
                 end;

                if Leitor.rExtrai(7, 'IdentificacaoTomador') <> ''
                 then begin
                  ListaNfse.FCompNfse[i].FNFSe.Tomador.IdentificacaoTomador.InscricaoMunicipal := Leitor.rCampo(tcStr, 'InscricaoMunicipal');
                  if Leitor.rExtrai(8, 'CpfCnpj') <> ''
                   then begin
                    if Leitor.rCampo(tcStr, 'Cpf')<>''
                     then ListaNfse.FCompNfse[i].FNFSe.Tomador.IdentificacaoTomador.CpfCnpj := Leitor.rCampo(tcStr, 'Cpf')
                     else ListaNfse.FCompNfse[i].FNFSe.Tomador.IdentificacaoTomador.CpfCnpj := Leitor.rCampo(tcStr, 'Cnpj');
                   end;
                 end;

               end;

              // Grupo da TAG <IntermediarioServico> ****************************************
              if Leitor.rExtrai(6, 'IntermediarioServico') <> ''
               then begin
                ListaNfse.FCompNfse[i].FNFSe.IntermediarioServico.RazaoSocial        := Leitor.rCampo(tcStr, 'RazaoSocial');
                ListaNfse.FCompNfse[i].FNFSe.IntermediarioServico.InscricaoMunicipal := Leitor.rCampo(tcStr, 'InscricaoMunicipal');
                if Leitor.rExtrai(7, 'CpfCnpj') <> ''
                 then begin
                  if Leitor.rCampo(tcStr, 'Cpf')<>''
                   then ListaNfse.FCompNfse[i].FNFSe.IntermediarioServico.CpfCnpj := Leitor.rCampo(tcStr, 'Cpf')
                   else ListaNfse.FCompNfse[i].FNFSe.IntermediarioServico.CpfCnpj := Leitor.rCampo(tcStr, 'Cnpj');
                 end;
               end;

              // Grupo da TAG <OrgaoGerador> ************************************************
              if Leitor.rExtrai(6, 'OrgaoGerador') <> ''
               then begin
                ListaNfse.FCompNfse[i].FNFSe.OrgaoGerador.CodigoMunicipio := Leitor.rCampo(tcStr, 'CodigoMunicipio');
                ListaNfse.FCompNfse[i].FNFSe.OrgaoGerador.Uf              := Leitor.rCampo(tcStr, 'Uf');
               end;

              // Grupo da TAG <ConstrucaoCivil> *********************************************
              if Leitor.rExtrai(6, 'ConstrucaoCivil') <> ''
               then begin
                ListaNfse.FCompNfse[i].FNFSe.ConstrucaoCivil.CodigoObra := Leitor.rCampo(tcStr, 'CodigoObra');
                ListaNfse.FCompNfse[i].FNFSe.ConstrucaoCivil.Art        := Leitor.rCampo(tcStr, 'Art');
               end;

             end; // fim do InfNfse
           end; // fim do Nfse - Nivel 4


          // Grupo da TAG <NfseCancelamento> ********************************************
          if Leitor.rExtrai(4, 'NfseCancelamento') <> ''
           then begin
            ListaNfse.FCompNfse[i].NFSe.NfseCancelamento.DataHora := Leitor.rCampo(tcDatHor, 'DataHora');
            // provedor Betha sempre retorna a o grupo "NfseCancelamento" mesmo não estando cancelada,
            // o cancelamento deverá ser verificado na TAG especifica
            // Incluido por Roberto Godinho 13/11/20113
            if FProvedor = proBetha then
            begin
              Leitor.rExtrai(4,'InfConfirmacaoCancelamento');
              if StrToBool(Leitor.rCampo(tcStr, 'Sucesso'))then
                ListaNfse.FCompNfse[i].NFSe.Status := srCancelado;
            end;

            // Incluido por Mauro Gomes
            // se não encontrou o campo DataHora, deve procurar pelo DataHoraCancelamento
            if (ListaNfse.FCompNfse[i].NFSe.NfseCancelamento.DataHora = 0) then
               ListaNfse.FCompNfse[i].NFSe.NfseCancelamento.DataHora := Leitor.rCampo(tcDatHor, 'DataHoraCancelamento');
           end;

          // Grupo da TAG <NfseSubstituicao> ********************************************
          if Leitor.rExtrai(4, 'NfseSubstituicao') <> ''
           then begin
            ListaNfse.FCompNfse[i].FNfse.NfseSubstituidora := Leitor.rCampo(tcStr, 'NfseSubstituidora');
           end;

          inc(i);
        end; // fim do CompNfse - Nivel 3

      end; // fim do ListaNfse - Nivel 2

      // Ler a Lista de Mensagens
      if (leitor.rExtrai(2, 'ListaMensagemRetorno') <> '') or
         (leitor.rExtrai(2, 'Listamensagemretorno') <> '') then
      begin
        i := 0;
        while Leitor.rExtrai(3, 'MensagemRetorno', '', i + 1) <> '' do
        begin
          ListaNfse.FMsgRetorno.Add;
          ListaNfse.FMsgRetorno[i].FCodigo   := Leitor.rCampo(tcStr, 'Codigo');
          ListaNfse.FMsgRetorno[i].FMensagem := Leitor.rCampo(tcStr, 'Mensagem');
          ListaNfse.FMsgRetorno[i].FCorrecao := Leitor.rCampo(tcStr, 'Correcao');

          inc(i);
        end;
      end;

      Result := True;
    end;
  except
    result := False;
  end;
end;

function TretNfse.LerXml_provedorIssDsf: boolean; //falta homologar
var
  ok: boolean;
  i, Item, posI, count: Integer;
  sOperacao, sTributacao, VersaoXML: String;
  strAux, strItem: AnsiString;
  leitorAux, leitorItem:TLeitor;
begin
  result := False;

  try
    Leitor.Arquivo := NotaUtil.RetirarPrefixos(Leitor.Arquivo);
    VersaoXML      := '1';
    Leitor.Grupo   := Leitor.Arquivo;
    if leitor.rExtrai(1, 'RetornoConsultaNotas') <> '' then
    begin
      if (leitor.rExtrai(2, 'Notas') <> '') then
      begin

         strAux := leitor.rExtrai(2, 'Notas');
         if (strAux <> '') then
         begin
            i := 0 ;
            posI := pos('<Nota>', strAux);

            while ( posI > 0 ) do begin
               count := pos('</Nota>', strAux) + 6;

               ListaNfse.FCompNfse.Add;
               inc(i);

               LeitorAux := TLeitor.Create;
               leitorAux.Arquivo := copy(strAux, PosI, count);
               leitorAux.Grupo   := leitorAux.Arquivo;

               ListaNfse.FCompNfse[i].FNFSe.Numero            := LeitorAux.rCampo(tcStr, 'NumeroNota');
               ListaNfse.FCompNfse[i].FNFSe.CodigoVerificacao := LeitorAux.rCampo(tcStr, 'CodigoVerificacao');

               ListaNfse.FCompNfse[i].FNFSe.DataEmissaoRps    := LeitorAux.rCampo(tcDatHor, 'DataEmissaoRPS');
               ListaNfse.FCompNfse[i].FNFSe.DataEmissao       := LeitorAux.rCampo(tcDatHor, 'DataProcessamento');
               ListaNfse.FCompNfse[i].FNFSe.Status            := StrToEnumerado(ok, LeitorAux.rCampo(tcStr, 'SituacaoRPS'),['N','C'],[srNormal, srCancelado]);

               ListaNfse.FCompNfse[i].NFSe.IdentificacaoRps.Numero := LeitorAux.rCampo(tcStr, 'NumeroRPS');
               ListaNfse.FCompNfse[i].NFSe.IdentificacaoRps.Serie  := LeitorAux.rCampo(tcStr, 'SerieRPS');
               ListaNfse.FCompNfse[i].NFSe.IdentificacaoRps.Tipo   := trRPS; //StrToTipoRPS(ok, leitorAux.rCampo(tcStr, 'Tipo'));
               ListaNfse.FCompNfse[i].NFSe.InfID.ID                := SomenteNumeros(ListaNfse.FCompNfse[i].NFSe.IdentificacaoRps.Numero);// + NFSe.IdentificacaoRps.Serie;
               ListaNfse.FCompNfse[i].NFSe.SeriePrestacao          := LeitorAux.rCampo(tcStr, 'SeriePrestacao');

               ListaNfse.FCompNfse[i].NFSe.Tomador.IdentificacaoTomador.InscricaoMunicipal := LeitorAux.rCampo(tcStr, 'InscricaoMunicipalTomador');
               ListaNfse.FCompNfse[i].NFSe.Tomador.IdentificacaoTomador.CpfCnpj := LeitorAux.rCampo(tcStr, 'CPFCNPJTomador');
               ListaNfse.FCompNfse[i].NFSe.Tomador.RazaoSocial              := LeitorAux.rCampo(tcStr, 'RazaoSocialTomador');
               ListaNfse.FCompNfse[i].NFSe.Tomador.Endereco.TipoLogradouro  := LeitorAux.rCampo(tcStr, 'TipoLogradouroTomador');
               ListaNfse.FCompNfse[i].NFSe.Tomador.Endereco.Endereco        := LeitorAux.rCampo(tcStr, 'LogradouroTomador');
               ListaNfse.FCompNfse[i].NFSe.Tomador.Endereco.Numero          := LeitorAux.rCampo(tcStr, 'NumeroEnderecoTomador');
               ListaNfse.FCompNfse[i].NFSe.Tomador.Endereco.Complemento     := LeitorAux.rCampo(tcStr, 'ComplementoEnderecoTomador');
               ListaNfse.FCompNfse[i].NFSe.Tomador.Endereco.TipoBairro      := LeitorAux.rCampo(tcStr, 'TipoBairroTomador');
               ListaNfse.FCompNfse[i].NFSe.Tomador.Endereco.Bairro          := LeitorAux.rCampo(tcStr, 'BairroTomador');
               ListaNfse.FCompNfse[i].NFSe.Tomador.Endereco.CodigoMunicipio := CodSiafiToCodCidade( LeitorAux.rCampo(tcStr, 'CidadeTomador')) ;
               ListaNfse.FCompNfse[i].NFSe.Tomador.Endereco.CEP             := LeitorAux.rCampo(tcStr, 'CEPTomador');
               ListaNfse.FCompNfse[i].NFSe.Tomador.Contato.Email := LeitorAux.rCampo(tcStr, 'EmailTomador');

               ListaNfse.FCompNfse[i].NFSe.Servico.CodigoCnae := LeitorAux.rCampo(tcStr, 'CodigoAtividade');
               ListaNfse.FCompNfse[i].NFSe.Servico.Valores.Aliquota := LeitorAux.rCampo(tcDe3, 'AliquotaAtividade');

               ListaNfse.FCompNfse[i].NFSe.Servico.Valores.IssRetido := StrToEnumerado( ok, LeitorAux.rCampo(tcStr, 'TipoRecolhimento'),
                                                                 ['A','R'], [ stNormal, stRetencao{, stSubstituicao}]);

               ListaNfse.FCompNfse[i].NFSe.Servico.CodigoMunicipio := CodSiafiToCodCidade( LeitorAux.rCampo(tcStr, 'MunicipioPrestacao'));

               sOperacao   := AnsiUpperCase(LeitorAux.rCampo(tcStr, 'Operacao'));
               sTributacao := AnsiUpperCase(LeitorAux.rCampo(tcStr, 'Tributacao'));

               if sOperacao[1] in ['A', 'B'] then begin

                  if (sOperacao = 'A') and (sTributacao = 'N') then
                     ListaNfse.FCompNfse[i].NFSe.NaturezaOperacao := noNaoIncidencia
                  else if sTributacao = 'G' then
                     ListaNfse.FCompNfse[i].NFSe.NaturezaOperacao := noTributacaoForaMunicipio
                  else if sTributacao = 'T' then
                     ListaNfse.FCompNfse[i].NFSe.NaturezaOperacao := noTributacaoNoMunicipio;
               end
               else if (sOperacao = 'C') and (sTributacao = 'C') then begin
                  ListaNfse.FCompNfse[i].NFSe.NaturezaOperacao := noIsencao;
               end
               else if (sOperacao = 'C') and (sTributacao = 'F') then begin
                  ListaNfse.FCompNfse[i].NFSe.NaturezaOperacao := noImune;
               end;

               ListaNfse.FCompNfse[i].NFSe.NaturezaOperacao := StrToEnumerado( ok,sTributacao, ['T','K'], [ ListaNfse.FCompNfse[i].NFSe.NaturezaOperacao, noSuspensaDecisaoJudicial ]);

               ListaNfse.FCompNfse[i].NFSe.OptanteSimplesNacional := StrToEnumerado( ok,sTributacao, ['T','H'], [ snNao, snSim ]);

               ListaNfse.FCompNfse[i].NFSe.DeducaoMateriais := StrToEnumerado( ok,sOperacao, ['A','B'], [ snNao, snSim ]);

               ListaNfse.FCompNfse[i].NFse.RegimeEspecialTributacao := StrToEnumerado( ok,sTributacao, ['T','M'], [ retNenhum, retMicroempresarioIndividual ]);

               ListaNfse.FCompNfse[i].NFSe.Servico.Valores.ValorPis        := LeitorAux.rCampo(tcDe2, 'ValorPIS');
               ListaNfse.FCompNfse[i].NFSe.Servico.Valores.ValorCofins     := LeitorAux.rCampo(tcDe2, 'ValorCOFINS');
               ListaNfse.FCompNfse[i].NFSe.Servico.Valores.ValorInss       := LeitorAux.rCampo(tcDe2, 'ValorINSS');
               ListaNfse.FCompNfse[i].NFSe.Servico.Valores.ValorIr         := LeitorAux.rCampo(tcDe2, 'ValorIR');
               ListaNfse.FCompNfse[i].NFSe.Servico.Valores.ValorCsll       := LeitorAux.rCampo(tcDe2, 'ValorCSLL');
               ListaNfse.FCompNfse[i].NFSe.Servico.Valores.AliquotaPIS     := LeitorAux.rCampo(tcDe2, 'AliquotaPIS');
               ListaNfse.FCompNfse[i].NFSe.Servico.Valores.AliquotaCOFINS  := LeitorAux.rCampo(tcDe2, 'AliquotaCOFINS');
               ListaNfse.FCompNfse[i].NFSe.Servico.Valores.AliquotaINSS    := LeitorAux.rCampo(tcDe2, 'AliquotaINSS');
               ListaNfse.FCompNfse[i].NFSe.Servico.Valores.AliquotaIR      := LeitorAux.rCampo(tcDe2, 'AliquotaIR');
               ListaNfse.FCompNfse[i].NFSe.Servico.Valores.AliquotaCSLL    := LeitorAux.rCampo(tcDe2, 'AliquotaCSLL');

               ListaNfse.FCompNfse[i].NFSe.OutrasInformacoes := leitorAux.rCampo(tcStr, 'DescricaoRPS');

               ListaNfse.FCompNfse[i].NFSe.PrestadorServico.Contato.Telefone := LeitorAux.rCampo(tcStr, 'DDDPrestador') + LeitorAux.rCampo(tcStr, 'TelefonePrestador');
               ListaNfse.FCompNfse[i].NFSe.Tomador.Contato.Telefone          := LeitorAux.rCampo(tcStr, 'DDDTomador') + LeitorAux.rCampo(tcStr, 'TelefoneTomador');

               ListaNfse.FCompNfse[i].NFSE.MotivoCancelamento := leitorAux.rCampo(tcStr, 'MotCancelamento');

               ListaNfse.FCompNfse[i].NFSe.IntermediarioServico.CpfCnpj := LeitorAux.rCampo(tcStr, 'CPFCNPJIntermediario');

               if (leitorAux.rExtrai(2, 'Deducoes') <> '') then
               begin
                  strItem := leitorAux.rExtrai(2, 'Deducoes');
                  if (strItem <> '') then
                  begin
                     Item := 0 ;
                     posI := pos('<Deducao>', strItem);

                     while ( posI > 0 ) do begin
                        count := pos('</Deducao>', strItem) + 9;

                        ListaNfse.FCompNfse[i].FNfse.Servico.Deducao.Add;
                        inc(Item);

                        leitorItem := TLeitor.Create;
                        leitorItem.Arquivo := copy(strItem, PosI, count);
                        leitorItem.Grupo := leitorItem.Arquivo;

                        ListaNfse.FCompNfse[i].FNfse.Servico.Deducao[Item].DeducaoPor  :=
                           StrToEnumerado( ok,leitorItem.rCampo(tcStr, 'DeducaoPor'),
                                           ['','Percentual','Valor'],
                                           [ dpNenhum,dpPercentual, dpValor ]);

                        ListaNfse.FCompNfse[i].FNfse.Servico.Deducao[Item].TipoDeducao :=
                           StrToEnumerado( ok,leitorItem.rCampo(tcStr, 'TipoDeducao'),
                                           ['', 'Despesas com Materiais', 'Despesas com Sub-empreitada'],
                                           [ tdNenhum, tdMateriais, tdSubEmpreitada ]);

                        ListaNfse.FCompNfse[i].FNfse.Servico.Deducao[Item].CpfCnpjReferencia := leitorItem.rCampo(tcStr, 'CPFCNPJReferencia');
                        ListaNfse.FCompNfse[i].FNfse.Servico.Deducao[Item].NumeroNFReferencia := leitorItem.rCampo(tcStr, 'NumeroNFReferencia');
                        ListaNfse.FCompNfse[i].FNfse.Servico.Deducao[Item].ValorTotalReferencia := leitorItem.rCampo(tcDe2, 'ValorTotalReferencia');
                        ListaNfse.FCompNfse[i].FNfse.Servico.Deducao[Item].PercentualDeduzir := leitorItem.rCampo(tcDe2, 'PercentualDeduzir');
                        ListaNfse.FCompNfse[i].FNfse.Servico.Deducao[Item].ValorDeduzir := leitorItem.rCampo(tcDe2, 'ValorDeduzir');

                        leitorItem.free;
                        Delete(strItem, PosI, count);
                        posI := pos('<Deducao>', strItem);
                     end;
                  end;
               end;

               if (leitorAux.rExtrai(2, 'Itens') <> '') then
               begin

                  strItem := leitorAux.rExtrai(2, 'Itens');
                  if (strItem <> '') then
                  begin
                     Item := 0 ;
                     posI := pos('<Item>', strItem);

                     while ( posI > 0 ) do begin
                        count := pos('</Item>', strItem) + 6;

                        ListaNfse.FCompNfse[i].FNfse.Servico.ItemServico.Add;
                        inc(Item);

                        leitorItem := TLeitor.Create;
                        leitorItem.Arquivo := copy(strItem, PosI, count);
                        leitorItem.Grupo := leitorItem.Arquivo;

                        ListaNfse.FCompNfse[i].FNfse.Servico.ItemServico[Item].Descricao  := leitorItem.rCampo(tcStr, 'DiscriminacaoServico');
                        ListaNfse.FCompNfse[i].FNfse.Servico.ItemServico[Item].Quantidade := leitorItem.rCampo(tcStr, 'Quantidade');
                        ListaNfse.FCompNfse[i].FNfse.Servico.ItemServico[Item].ValorUnitario := leitorItem.rCampo(tcStr, 'ValorUnitario');
                        ListaNfse.FCompNfse[i].FNfse.Servico.ItemServico[Item].ValorTotal := leitorItem.rCampo(tcStr, 'ValorTotal');
                        ListaNfse.FCompNfse[i].FNfse.Servico.ItemServico[Item].Tributavel := StrToEnumerado( ok,leitorItem.rCampo(tcStr, 'Tributavel'), ['N','S'], [ snNao, snSim ]);

                        leitorItem.free;
                        Delete(strItem, PosI, count);
                        posI := pos('<Item>', strItem);
                     end;
                  end;
               end;

               LeitorAux.free;

               Delete(strAux, PosI, count);
               posI := pos('<Nota>', strAux);
            end;
         end;
     end;
    end;
  except
    result := False;
  end;
end;
{
function TretNfse.ObterDescricaoServico(cCodigo: string): string;
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
   i      := 0;
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
end.

