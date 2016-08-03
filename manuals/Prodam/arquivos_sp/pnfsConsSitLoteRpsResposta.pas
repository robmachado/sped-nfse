unit pnfsConsSitLoteRpsResposta;

interface

uses
  SysUtils, Classes,
  pcnAuxiliar, pcnConversao, pcnLeitor, pnfsConversao, pnfsNFSe, ACBrNFSeUtil;

type

 TMsgRetornoSitCollection = class;
 TMsgRetornoSitCollectionItem = class;

 TInfSit = class(TPersistent)
  private
    FNumeroLote: string;
    FSituacao: string;
    FMsgRetorno : TMsgRetornoSitCollection;
    procedure SetMsgRetorno(Value: TMsgRetornoSitCollection);
  public
    constructor Create; reintroduce;
    destructor Destroy; override;
    property NumeroLote: string                   read FNumeroLote write FNumeroLote;
    property Situacao: string                     read FSituacao   write FSituacao;
    property MsgRetorno: TMsgRetornoSitCollection read FMsgRetorno write SetMsgRetorno;
  end;

 TMsgRetornoSitCollection = class(TCollection)
  private
    function GetItem(Index: Integer): TMsgRetornoSitCollectionItem;
    procedure SetItem(Index: Integer; Value: TMsgRetornoSitCollectionItem);
  public
    constructor Create(AOwner: TInfSit);
    function Add: TMsgRetornoSitCollectionItem;
    property Items[Index: Integer]: TMsgRetornoSitCollectionItem read GetItem write SetItem; default;
  end;

 TMsgRetornoSitCollectionItem = class(TCollectionItem)
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

 TretSitLote = class(TPersistent)
  private
    FLeitor: TLeitor;
    FInfSit: TInfSit;
  public
    constructor Create;
    destructor Destroy; override;
    function LerXml: boolean;
    function LerXML_provedorEquiplano: Boolean;
    function LerXML_provedorSP: Boolean;
  published
    property Leitor: TLeitor  read FLeitor   write FLeitor;
    property InfSit: TInfSit  read FInfSit   write FInfSit;
  end;

implementation

{ TInfSit }

constructor TInfSit.Create;
begin
  FMsgRetorno := TMsgRetornoSitCollection.Create(Self);
end;

destructor TInfSit.Destroy;
begin
  FMsgRetorno.Free;

  inherited;
end;

procedure TInfSit.SetMsgRetorno(Value: TMsgRetornoSitCollection);
begin
  FMsgRetorno.Assign(Value);
end;

{ TMsgRetornoSitCollection }

function TMsgRetornoSitCollection.Add: TMsgRetornoSitCollectionItem;
begin
  Result := TMsgRetornoSitCollectionItem(inherited Add);
  Result.create;
end;

constructor TMsgRetornoSitCollection.Create(AOwner: TInfSit);
begin
  inherited Create(TMsgRetornoSitCollectionItem);
end;

function TMsgRetornoSitCollection.GetItem(
  Index: Integer): TMsgRetornoSitCollectionItem;
begin
  Result := TMsgRetornoSitCollectionItem(inherited GetItem(Index));
end;

procedure TMsgRetornoSitCollection.SetItem(Index: Integer;
  Value: TMsgRetornoSitCollectionItem);
begin
  inherited SetItem(Index, Value);
end;

{ TMsgRetornoSitCollectionItem }

constructor TMsgRetornoSitCollectionItem.Create;
begin

end;

destructor TMsgRetornoSitCollectionItem.Destroy;
begin

  inherited;
end;

{ TretSitLote }

constructor TretSitLote.Create;
begin
  FLeitor := TLeitor.Create;
  FInfSit := TInfSit.Create;
end;

destructor TretSitLote.Destroy;
begin
  FLeitor.Free;
  FInfSit.Free;
  inherited;
end;

function TretSitLote.LerXml: boolean;
var
  i: Integer;
begin
  result := False;
  
  try
    // Incluido por Ricardo Miranda em 14/03/2013
    Leitor.Arquivo := NotaUtil.RetirarPrefixos(Leitor.Arquivo);
    Leitor.Arquivo := StringReplace(Leitor.Arquivo, ' xmlns=""', '', [rfReplaceAll]);
    Leitor.Grupo   := Leitor.Arquivo;

    // Alterado por Akai - L. Massao Aihara 31/10/2013
    if (leitor.rExtrai(1, 'ConsultarSituacaoLoteRpsResposta') <> '') or
       (leitor.rExtrai(1, 'Consultarsituacaoloterpsresposta') <> '') or
       (leitor.rExtrai(1, 'ConsultarLoteRpsResposta') <> '') or
       (leitor.rExtrai(1, 'ConsultarSituacaoLoteRpsResult') <> '') then
    begin
      InfSit.FNumeroLote := Leitor.rCampo(tcStr, 'NumeroLote');
      InfSit.FSituacao   := Leitor.rCampo(tcStr, 'Situacao');

      // FSituacao: 1 = Não Recebido
      //            2 = Não Processado
      //            3 = Processado com Erro
      //            4 = Processado com Sucesso

      // Ler a Lista de Mensagens
      if leitor.rExtrai(2, 'ListaMensagemRetorno') <> '' then
      begin
        i := 0;
        while Leitor.rExtrai(3, 'MensagemRetorno', '', i + 1) <> '' do
        begin
          InfSit.FMsgRetorno.Add;
          InfSit.FMsgRetorno[i].FCodigo   := Leitor.rCampo(tcStr, 'Codigo');
          InfSit.FMsgRetorno[i].FMensagem := Leitor.rCampo(tcStr, 'Mensagem');
          InfSit.FMsgRetorno[i].FCorrecao := Leitor.rCampo(tcStr, 'Correcao');

          inc(i);
        end;
      end;

      result := True;
    end;
  except
    result := False;
  end;
end;

function TretSitLote.LerXML_provedorEquiplano: Boolean;
var
  i: Integer;
begin
  try
    // Incluido por Ricardo Miranda em 14/03/2013
    Leitor.Arquivo := NotaUtil.RetirarPrefixos(Leitor.Arquivo);
    Leitor.Arquivo := StringReplace(Leitor.Arquivo, ' xmlns=""', '', [rfReplaceAll]);
    Leitor.Grupo   := Leitor.Arquivo;

    InfSit.FNumeroLote := Leitor.rCampo(tcStr, 'nrLoteRps');
    InfSit.FSituacao   := Leitor.rCampo(tcStr, 'stLote');
		//1 - Aguardando processamento
		//2 - Não Processado, lote com erro
		//3 - Processado com sucesso
		//4 - Processado com avisos

    if leitor.rExtrai(1, 'mensagemRetorno') <> '' then
      begin
        i := 0;
        if (leitor.rExtrai(2, 'listaErros') <> '') then
          begin
            while Leitor.rExtrai(3, 'erro', '', i + 1) <> '' do
              begin
                InfSit.FMsgRetorno.Add;
                InfSit.FMsgRetorno[i].FCodigo   := Leitor.rCampo(tcStr, 'cdMensagem');
                InfSit.FMsgRetorno[i].FMensagem := Leitor.rCampo(tcStr, 'dsMensagem');
                InfSit.FMsgRetorno[i].FCorrecao := Leitor.rCampo(tcStr, 'dsCorrecao');

                inc(i);
              end;
          end;

        if (leitor.rExtrai(2, 'listaAlertas') <> '') then
          begin
            while Leitor.rExtrai(3, 'alerta', '', i + 1) <> '' do
              begin
                InfSit.FMsgRetorno.Add;
                InfSit.FMsgRetorno[i].FCodigo   := Leitor.rCampo(tcStr, 'cdMensagem');
                InfSit.FMsgRetorno[i].FMensagem := Leitor.rCampo(tcStr, 'dsMensagem');
                InfSit.FMsgRetorno[i].FCorrecao := Leitor.rCampo(tcStr, 'dsCorrecao');

                inc(i);
              end;
          end;
      end;

    result := True;
  except
    result := False;
  end;
end;

function TretSitLote.LerXML_provedorSP: Boolean;
var
  Sucesso, Erro, CodErro, DescErro:String;
begin
    //1 - Aguardando processamento
		//2 - Não Processado, lote com erro
		//3 - Processado com sucesso
		//4 - Processado com avisos
    Leitor.Arquivo := NotaUtil.RetirarPrefixos(Leitor.Arquivo);
    Leitor.Grupo   := Leitor.Arquivo;
    InfSit.FNumeroLote:='0';
    try
      Sucesso:=StringReplace(StringReplace(Leitor.rExtrai(1, 'Sucesso'), '<Sucesso>', '', [rfReplaceAll, rfIgnoreCase]), '</Sucesso>', '', [rfReplaceAll, rfIgnoreCase]);
      Erro:=Leitor.rExtrai(1, 'Erro');
      CodErro:=Leitor.rExtrai(2, 'Codigo');
      CodErro:=StringReplace(StringReplace(CodErro, '<Codigo>', '', [rfReplaceAll, rfIgnoreCase]), '</Codigo>', '', [rfReplaceAll, rfIgnoreCase]);
      DescErro:=Leitor.rExtrai(2, 'Descricao');
      DescErro:=StringReplace(StringReplace(DescErro, '<Descricao>', '', [rfReplaceAll, rfIgnoreCase]), '</Descricao>', '', [rfReplaceAll, rfIgnoreCase]);
      if UpperCase(Trim(Sucesso)) <> 'TRUE' then begin
        if Trim(CodErro) <> '' then begin
          InfSit.FMsgRetorno.Add;
          case StrToInt(Trim(CodErro)) of
            1105, 1107: begin //Lote não encontrado ou processado com erro
              InfSit.FMsgRetorno[0].FCodigo   := CodErro;
              InfSit.FMsgRetorno[0].FMensagem := DescErro;
              InfSit.FMsgRetorno[0].FCorrecao := '';
              InfSit.FSituacao   := '2';
            end;
          end;
        end;
    end;
      Result:=True;
    except
      Result:=False;
    end;
end;

end.

