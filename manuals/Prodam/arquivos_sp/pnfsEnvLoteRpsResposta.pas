unit pnfsEnvLoteRpsResposta;

interface

uses
  SysUtils, Classes,
  pcnAuxiliar, pcnConversao, pcnLeitor, pnfsConversao, ACBrNFSeUtil;

type

 TMsgRetornoEnvCollection = class;
 TMsgRetornoEnvCollectionItem = class;

  TInfRec = class
  private
    FNumeroLote: string;
    FDataRecebimento: TDateTime;
    FProtocolo: string;
    FSucesso: string;
    FMsgRetorno : TMsgRetornoEnvCollection;

    procedure SetMsgRetorno(Value: TMsgRetornoEnvCollection);
  public
    constructor Create; reintroduce;
    destructor Destroy; override;
    property NumeroLote: string                   read FNumeroLote      write FNumeroLote;
    property DataRecebimento: TDateTime           read FDataRecebimento write FDataRecebimento;
    property Protocolo: string                    read FProtocolo       write FProtocolo;
    property Sucesso: string                      read FSucesso         write FSucesso;
    property MsgRetorno: TMsgRetornoEnvCollection read FMsgRetorno      write SetMsgRetorno;
  end;

 TMsgRetornoEnvCollection = class(TCollection)
  private
    function GetItem(Index: Integer): TMsgRetornoEnvCollectionItem;
    procedure SetItem(Index: Integer; Value: TMsgRetornoEnvCollectionItem);
  public
    constructor Create(AOwner: TInfRec);
    function Add: TMsgRetornoEnvCollectionItem;
    property Items[Index: Integer]: TMsgRetornoEnvCollectionItem read GetItem write SetItem; default;
  end;

 TMsgRetornoEnvCollectionItem = class(TCollectionItem)
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

  TretEnvLote = class(TPersistent)
  private
    FLeitor: TLeitor;
    FInfRec: TInfRec;
  public
    constructor Create;
    destructor Destroy; override;
    function LerXml: boolean;
    function LerXml_provedorIssDsf: boolean;
    function LerXML_provedorEquiplano: Boolean;
    function LerXML_provedorSP: boolean;
  published
    property Leitor: TLeitor  read FLeitor   write FLeitor;
    property InfRec: TInfRec  read FInfRec   write FInfRec;
  end;

implementation

{ TInfRec }

constructor TInfRec.Create;
begin
  FMsgRetorno := TMsgRetornoEnvCollection.Create(Self);
end;

destructor TInfRec.Destroy;
begin
  FMsgRetorno.Free;

  inherited;
end;

procedure TInfRec.SetMsgRetorno(Value: TMsgRetornoEnvCollection);
begin
  FMsgRetorno.Assign(Value);
end;

{ TMsgRetornoEnvCollection }

function TMsgRetornoEnvCollection.Add: TMsgRetornoEnvCollectionItem;
begin
  Result := TMsgRetornoEnvCollectionItem(inherited Add);
  Result.create;
end;

constructor TMsgRetornoEnvCollection.Create(AOwner: TInfRec);
begin
  inherited Create(TMsgRetornoEnvCollectionItem);
end;

function TMsgRetornoEnvCollection.GetItem(
  Index: Integer): TMsgRetornoEnvCollectionItem;
begin
  Result := TMsgRetornoEnvCollectionItem(inherited GetItem(Index));
end;

procedure TMsgRetornoEnvCollection.SetItem(Index: Integer;
  Value: TMsgRetornoEnvCollectionItem);
begin
  inherited SetItem(Index, Value);
end;

{ TMsgRetornoEnvCollectionItem }

constructor TMsgRetornoEnvCollectionItem.Create;
begin

end;

destructor TMsgRetornoEnvCollectionItem.Destroy;
begin

  inherited;
end;

{ TretEnvLote }

constructor TretEnvLote.Create;
begin
  FLeitor := TLeitor.Create;
  FInfRec := TInfRec.Create
end;

destructor TretEnvLote.Destroy;
begin
  FLeitor.Free;
  FinfRec.Free;
  inherited;
end;

function TretEnvLote.LerXml: boolean;
var
  i: Integer;
  iNivel: Integer;
begin
  result := False;

  try
    // Incluido por Ricardo Miranda em 14/03/2013
    Leitor.Arquivo := NotaUtil.RetirarPrefixos(Leitor.Arquivo);
    Leitor.Grupo   := Leitor.Arquivo;

//    if (leitor.rExtrai(1, 'EnviarLoteRpsResposta') <> '') or
       // Incluido por João Paulo Delboni em 22/04/2013 - Retorno do provedor 4R
//       (leitor.rExtrai(1, 'EnviarLoteRpsSincronoResposta') <> '') then
//    begin
      infRec.FNumeroLote      := Leitor.rCampo(tcStr, 'NumeroLote');
      infRec.FDataRecebimento := Leitor.rCampo(tcDatHor, 'DataRecebimento');
      infRec.FProtocolo       := Leitor.rCampo(tcStr, 'Protocolo');

      // Ler a Lista de Mensagens
      // Alterado em 06/09/2013 por Italo Jurisato Junior
      // de 0 -> 1
      iNivel := 1; 
      if leitor.rExtrai(2, 'ListaMensagemRetorno') <> '' then
        iNivel := 3
      else if leitor.rExtrai(1, 'ListaMensagemRetorno') <> '' then
        iNivel := 2;

//      if leitor.rExtrai(2, 'ListaMensagemRetorno') <> '' then
//      begin
        i := 0;
        while Leitor.rExtrai(iNivel, 'MensagemRetorno', '', i + 1) <> '' do
        begin
          InfRec.FMsgRetorno.Add;
          InfRec.FMsgRetorno[i].FCodigo   := Leitor.rCampo(tcStr, 'Codigo');
          InfRec.FMsgRetorno[i].FMensagem := Leitor.rCampo(tcStr, 'Mensagem');
          InfRec.FMsgRetorno[i].FCorrecao := Leitor.rCampo(tcStr, 'Correcao');

          inc(i);
        end;

//        if i = 0 then
//          InfRec.FMsgRetorno.Add;
//      end;

      Result := True;
//    end;
  except
    result := False;
  end;
end;

function TretEnvLote.LerXml_provedorIssDsf: boolean;
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

         FInfRec.FSucesso := Leitor.rCampo(tcStr, 'Sucesso');
         if (FInfRec.FSucesso = 'true') then
         begin
            FInfRec.FNumeroLote :=  Leitor.rCampo(tcStr, 'NumeroLote');
            FInfRec.Protocolo   :=  Leitor.rCampo(tcStr, 'NumeroLote');
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

               FInfRec.FMsgRetorno.Add;
               inc(i);

               LeitorAux := TLeitor.Create;
               leitorAux.Arquivo := copy(strAux, PosI, count);
               leitorAux.Grupo   := leitorAux.Arquivo;

               FInfRec.FMsgRetorno[i].FCodigo  := leitorAux.rCampo(tcStr, 'Codigo');
               FInfRec.FMsgRetorno[i].Mensagem := leitorAux.rCampo(tcStr, 'Descricao');

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

               FInfRec.FMsgRetorno.Add;
               inc(i);

               LeitorAux := TLeitor.Create;
               leitorAux.Arquivo := copy(strAux, PosI, count);
               leitorAux.Grupo   := leitorAux.Arquivo;

               FInfRec.FMsgRetorno[i].FCodigo  := leitorAux.rCampo(tcStr, 'Codigo');
               FInfRec.FMsgRetorno[i].Mensagem := leitorAux.rCampo(tcStr, 'Descricao');

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

function TretEnvLote.LerXML_provedorSP: boolean;
var
  Sucesso, Lote, DataRecebimento:String;
  Fmt     : TFormatSettings;
begin
  try
    Leitor.Arquivo := NotaUtil.RetirarPrefixos(Leitor.Arquivo);
    Leitor.Grupo   := Leitor.Arquivo;
    //Teve que ser feito desta forma a leitura porque com o rCampo não lia certo...
    Lote:=Leitor.rCampo(tcInt, 'NumeroLote');
    DataRecebimento:=Leitor.rCampo(tcStr, 'DataEnvioLote');
    DataRecebimento:=StringReplace(DataRecebimento, 'T', ' ', [rfReplaceAll]);

    fmt.ShortDateFormat:='yyyy-mm-dd';
    fmt.DateSeparator  :='-';
    fmt.LongTimeFormat :='hh:nn:ss';
    fmt.TimeSeparator  :=':';

    infRec.FNumeroLote      := Lote;
    infRec.FDataRecebimento := StrToDateTime(DataRecebimento, fmt);
    infRec.Protocolo       := Lote; //Usar o mesmo número de lote do que protocolo, já que ele não retorna nenhum protocolo
    Sucesso:=LowerCase(Trim(Leitor.rCampo(tcStr, 'Sucesso')));
    if Sucesso <> 'true' then begin
      InfRec.FMsgRetorno.Add;
      InfRec.FMsgRetorno[0].FCodigo  := StringReplace(StringReplace(Leitor.rExtrai(1, 'Codigo'), '<Codigo>', '', [rfReplaceAll]), '</Codigo>', '', [rfReplaceAll]);// Leitor.rCampo(tcStr, 'Codigo');
      InfRec.FMsgRetorno[0].FMensagem:= StringReplace(StringReplace(Leitor.rExtrai(1, 'Descricao'), '<Descricao>', '', [rfReplaceAll]), '</Descricao>', '', [rfReplaceAll]);
      InfRec.FMsgRetorno[0].FCorrecao:= Leitor.rCampo(tcStr, 'Alerta');
    end;
    Result:=True;
  except
    Result:=False;
  end;
end;

function TretEnvLote.LerXML_provedorEquiplano: Boolean;
var
  i: Integer;
begin
  try
    Leitor.Arquivo := NotaUtil.RetirarPrefixos(Leitor.Arquivo);
    Leitor.Grupo   := Leitor.Arquivo;

    infRec.FNumeroLote      := Leitor.rCampo(tcStr, 'nrLote');
    infRec.FDataRecebimento := Leitor.rCampo(tcDatHor, 'dtRecebimento');
    infRec.FProtocolo       := Leitor.rCampo(tcStr, 'nrProtocolo');

    if leitor.rExtrai(1, 'mensagemRetorno') <> '' then
    begin
      i := 0;
      if (leitor.rExtrai(2, 'listaErros') <> '') then
      begin
        while Leitor.rExtrai(3, 'erro', '', i + 1) <> '' do
        begin
          InfRec.FMsgRetorno.Add;
          InfRec.FMsgRetorno[i].FCodigo  := Leitor.rCampo(tcStr, 'cdMensagem');
          InfRec.FMsgRetorno[i].FMensagem:= Leitor.rCampo(tcStr, 'dsMensagem');
          InfRec.FMsgRetorno[i].FCorrecao:= Leitor.rCampo(tcStr, 'dsCorrecao');

          inc(i);
        end;
      end;

      if (leitor.rExtrai(2, 'listaAlertas') <> '') then
      begin
        while Leitor.rExtrai(3, 'alerta', '', i + 1) <> '' do
        begin
          InfRec.FMsgRetorno.Add;
          InfRec.FMsgRetorno[i].FCodigo  := Leitor.rCampo(tcStr, 'cdMensagem');
          InfRec.FMsgRetorno[i].FMensagem:= Leitor.rCampo(tcStr, 'dsMensagem');
          InfRec.FMsgRetorno[i].FCorrecao:= Leitor.rCampo(tcStr, 'dsCorrecao');

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

