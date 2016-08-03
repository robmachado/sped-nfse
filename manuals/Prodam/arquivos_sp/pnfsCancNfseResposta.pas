unit pnfsCancNfseResposta;

interface

uses
  SysUtils, Classes, Forms, 
  pcnAuxiliar, pcnConversao, pcnLeitor,
  pnfsConversao, pnfsNFSe, ACBrNFSeUtil;

type

 TMsgRetornoCancCollection = class;
 TMsgRetornoCancCollectionItem = class;
 TNotasCanceladasCollection = class;
 TNotasCanceladasCollectionItem = class;

 TInfCanc = class(TPersistent)
  private
    FPedido: TPedidoCancelamento;
    FDataHora: TDateTime;
    FSucesso: String;
    FMsgCanc: String;
    FMsgRetorno : TMsgRetornoCancCollection;
    FNotasCanceladas: TNotasCanceladasCollection;

    procedure SetMsgRetorno(Value: TMsgRetornoCancCollection);
  public
    constructor Create; reintroduce;
    destructor Destroy; override;
    property Pedido: TPedidocancelamento           read FPedido     write FPedido;
    property DataHora: TDateTime                   read FDataHora   write FDataHora;
    property Sucesso: String                       read FSucesso    write FSucesso;
    property MsgCanc: String                       read FMsgCanc    write FMsgCanc;
    property MsgRetorno: TMsgRetornoCancCollection read FMsgRetorno write SetMsgRetorno;
    property NotasCanceladas: TNotasCanceladasCollection read FNotasCanceladas write FNotasCanceladas;
  end;

 TMsgRetornoCancCollection = class(TCollection)
  private
    function GetItem(Index: Integer): TMsgRetornoCancCollectionItem;
    procedure SetItem(Index: Integer; Value: TMsgRetornoCancCollectionItem);
  public
    constructor Create(AOwner: TInfCanc);
    function Add: TMsgRetornoCancCollectionItem;
    property Items[Index: Integer]: TMsgRetornoCancCollectionItem read GetItem write SetItem; default;
  end;

 TMsgRetornoCancCollectionItem = class(TCollectionItem)
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

 TNotasCanceladasCollection = class(TCollection)
  private
    function GetItem(Index: Integer): TNotasCanceladasCollectionItem;
    procedure SetItem(Index: Integer; Value: TNotasCanceladasCollectionItem);
  public
    constructor Create(AOwner: TInfCanc);
    function Add: TNotasCanceladasCollectionItem;
    property Items[Index: Integer]: TNotasCanceladasCollectionItem read GetItem write SetItem; default;
  end;

 TNotasCanceladasCollectionItem = class(TCollectionItem)
  private
    FNumeroNota : String;
    FCodigoVerficacao : String;
    FInscricaoMunicipalPrestador : String;
  public
    constructor Create; reintroduce;
    destructor Destroy; override;
  published
    property NumeroNota: string read FNumeroNota  write FNumeroNota;
    property CodigoVerficacao: string read FCodigoVerficacao write FCodigoVerficacao;
    property InscricaoMunicipalPrestador: string read FInscricaoMunicipalPrestador write FInscricaoMunicipalPrestador;
  end;

 TretCancNFSe = class(TPersistent)
  private
    FLeitor: TLeitor;
    FInfCanc: TInfCanc;
  public
    constructor Create;
    destructor Destroy; override;
    function LerXml: boolean;
    function LerXml_provedorIssDsf: boolean;
    function LerXML_provedorEquiplano: Boolean;
    function LerXML_provedorSP: Boolean;
  published
    property Leitor: TLeitor   read FLeitor   write FLeitor;
    property InfCanc: TInfCanc read FInfCanc  write FInfCanc;
  end;

implementation

{ TInfCanc }

constructor TInfCanc.Create;
begin
  FPedido     := TPedidoCancelamento.Create;
  FMsgRetorno := TMsgRetornoCancCollection.Create(Self);
end;

destructor TInfCanc.Destroy;
begin
  FPedido.Free;
  FMsgRetorno.Free;

  inherited;
end;

procedure TInfCanc.SetMsgRetorno(Value: TMsgRetornoCancCollection);
begin
  FMsgRetorno.Assign(Value);
end;

{ TMsgRetornoCancCollection }

function TMsgRetornoCancCollection.Add: TMsgRetornoCancCollectionItem;
begin
  Result := TMsgRetornoCancCollectionItem(inherited Add);
  Result.create;
end;

constructor TMsgRetornoCancCollection.Create(AOwner: TInfCanc);
begin
  inherited Create(TMsgRetornoCancCollectionItem);
end;

function TMsgRetornoCancCollection.GetItem(
  Index: Integer): TMsgRetornoCancCollectionItem;
begin
  Result := TMsgRetornoCancCollectionItem(inherited GetItem(Index));
end;

procedure TMsgRetornoCancCollection.SetItem(Index: Integer;
  Value: TMsgRetornoCancCollectionItem);
begin
  inherited SetItem(Index, Value);
end;

{ TMsgRetornoCancCollectionItem }

constructor TMsgRetornoCancCollectionItem.Create;
begin

end;

destructor TMsgRetornoCancCollectionItem.Destroy;
begin

  inherited;
end;

{ TNotasCanceladasCollection }

function TNotasCanceladasCollection.Add: TNotasCanceladasCollectionItem;
begin
  Result := TNotasCanceladasCollectionItem(inherited Add);
  Result.create;
end;

constructor TNotasCanceladasCollection.Create(AOwner: TInfCanc);
begin
  inherited Create(TNotasCanceladasCollectionItem);
end;

function TNotasCanceladasCollection.GetItem(
  Index: Integer): TNotasCanceladasCollectionItem;
begin
  Result := TNotasCanceladasCollectionItem(inherited GetItem(Index));
end;

procedure TNotasCanceladasCollection.SetItem(Index: Integer;
  Value: TNotasCanceladasCollectionItem);
begin
  inherited SetItem(Index, Value);
end;

{ TNotasCanceladasCollectionItem }

constructor TNotasCanceladasCollectionItem.Create;
begin

end;

destructor TNotasCanceladasCollectionItem.Destroy;
begin

  inherited;
end;

{ TretCancNFSe }

constructor TretCancNFSe.Create;
begin
  FLeitor  := TLeitor.Create;
  FInfCanc := TInfCanc.Create;
end;

destructor TretCancNFSe.Destroy;
begin
  FLeitor.Free;
  FInfCanc.Free;
  inherited;
end;

function TretCancNFSe.LerXml: boolean;
var
  i: Integer;
begin
  result := False;

  try
    // Incluido por Ricardo Miranda em 14/03/2013
    Leitor.Arquivo := NotaUtil.RetirarPrefixos(Leitor.Arquivo);
    Leitor.Grupo   := Leitor.Arquivo;

  { Incluído por Márcio Teixeira em 14/02/2013 para tratar os retornos do Ginfes.
    Fiz seguindo a seguinte idéia: se infCanc.DataHora tiver data, então foi
    cancelado com sucesso, caso contrário houve algum problema.
  }
  if Pos('www.ginfes.com.br', Leitor.Arquivo) <> 0
   then begin
    if (leitor.rExtrai(1, 'CancelarNfseResposta') <> '')
     then begin
      if AnsiLowerCase(Leitor.rCampo(tcStr, 'Sucesso')) = 'true'
       then begin
         infCanc.DataHora := Leitor.rCampo(tcDatHor, 'DataHora');
         InfCanc.Sucesso  := Leitor.rCampo(tcStr,    'Sucesso');  //Incluido por jrJunior82 09/05/2013
         InfCanc.MsgCanc  := Leitor.rCampo(tcStr,    'Mensagem'); //Incluido por jrJunior82 09/05/2013
       end
       else infCanc.DataHora := 0;

      InfCanc.FPedido.InfID.ID           := '';
      InfCanc.FPedido.CodigoCancelamento := '';

      if Leitor.rExtrai(1, 'MensagemRetorno') <> ''
       then
       if Pos('cancelada com sucesso', AnsiLowerCase(Leitor.rCampo(tcStr, 'Mensagem'))) = 0
        then begin
        InfCanc.FMsgRetorno.Add;
        InfCanc.FMsgRetorno[0].FCodigo   := Leitor.rCampo(tcStr, 'Codigo');
        InfCanc.FMsgRetorno[0].FMensagem := Leitor.rCampo(tcStr, 'Mensagem');
        InfCanc.FMsgRetorno[0].FCorrecao := Leitor.rCampo(tcStr, 'Correcao');
        end;
      end;

      Result := True;
    end
  else
    begin

      // Alterado por Akai - L. Massao Aihara 31/10/2013
      if (leitor.rExtrai(1, 'CancelarNfseResposta') <> '') or
         (leitor.rExtrai(1, 'Cancelarnfseresposta') <> '') or
         (leitor.rExtrai(1, 'CancelarNfseReposta') <> '') or
         (leitor.rExtrai(1, 'CancelarNfseResult') <> '') then
      begin
        infCanc.DataHora := Leitor.rCampo(tcDatHor, 'DataHora');
        if infCanc.DataHora = 0 then
          infCanc.DataHora := Leitor.rCampo(tcDatHor, 'DataHoraCancelamento');

        InfCanc.FPedido.InfID.ID := Leitor.rAtributo('InfPedidoCancelamento Id=');
        if InfCanc.FPedido.InfID.ID = '' then
          InfCanc.FPedido.InfID.ID := Leitor.rAtributo('InfPedidoCancelamento id=');

        InfCanc.FPedido.CodigoCancelamento := Leitor.rCampo(tcStr, 'CodigoCancelamento');


        if Leitor.rExtrai(2, 'IdentificacaoNfse') <> ''
         then begin
          InfCanc.FPedido.IdentificacaoNfse.Numero             := Leitor.rCampo(tcStr, 'Numero');
          InfCanc.FPedido.IdentificacaoNfse.Cnpj               := Leitor.rCampo(tcStr, 'Cnpj');
          InfCanc.FPedido.IdentificacaoNfse.InscricaoMunicipal := Leitor.rCampo(tcStr, 'InscricaoMunicipal');
          InfCanc.FPedido.IdentificacaoNfse.CodigoMunicipio    := Leitor.rCampo(tcStr, 'CodigoMunicipio');
         end;

        Leitor.Grupo := Leitor.Arquivo;

        InfCanc.FPedido.signature.URI             := Leitor.rAtributo('Reference URI=');
        InfCanc.FPedido.signature.DigestValue     := Leitor.rCampo(tcStr, 'DigestValue');
        InfCanc.FPedido.signature.SignatureValue  := Leitor.rCampo(tcStr, 'SignatureValue');
        InfCanc.FPedido.signature.X509Certificate := Leitor.rCampo(tcStr, 'X509Certificate');

        if (leitor.rExtrai(2, 'ListaMensagemRetorno') <> '') then
        begin
          i := 0;
          while Leitor.rExtrai(3, 'MensagemRetorno', '', i + 1) <> '' do
           begin
                InfCanc.FMsgRetorno.Add;
                InfCanc.FMsgRetorno[i].FCodigo   := Leitor.rCampo(tcStr, 'Codigo');
                InfCanc.FMsgRetorno[i].FMensagem := Leitor.rCampo(tcStr, 'Mensagem');
                InfCanc.FMsgRetorno[i].FCorrecao := Leitor.rCampo(tcStr, 'Correcao');

                inc(i);
            end;
        end;

        if (leitor.rExtrai(1, 'ListaMensagemRetorno') <> '') then begin    // Modificado para o Provedor Freire
           InfCanc.FMsgRetorno.Add;
           InfCanc.FMsgRetorno[0].FCodigo   := Leitor.rCampo(tcStr, 'Codigo');
           InfCanc.FMsgRetorno[0].FMensagem := Leitor.rCampo(tcStr, 'Mensagem');
           InfCanc.FMsgRetorno[0].FCorrecao := Leitor.rCampo(tcStr, 'Correcao');
        end;

        result := True;
      end;
    end;
  except
    result := False;
  end;
end;

function TretCancNFSe.LerXml_provedorIssDsf: boolean; //falta homologar
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

    if leitor.rExtrai(1, 'RetornoCancelamentoNFSe') <> '' then
    begin

      if (leitor.rExtrai(2, 'Cabecalho') <> '') then
      begin
         FInfCanc.FSucesso := Leitor.rCampo(tcStr, 'Sucesso');
      end;

      strAux := leitor.rExtrai(2, 'NotasCanceladas');
      if (strAux <> '') then
      begin
         i := 0 ;
         posI := pos('<Nota>', strAux);

         while ( posI > 0 ) do begin
            count := pos('</Nota>', strAux) + 6;

            inc(i);
            FInfCanc.FNotasCanceladas.Add;

            LeitorAux := TLeitor.Create;
            leitorAux.Arquivo := copy(strAux, PosI, count);
            leitorAux.Grupo   := leitorAux.Arquivo;

            FInfCanc.FNotasCanceladas[i].InscricaoMunicipalPrestador := LeitorAux.rCampo(tcStr,'InscricaoMunicipalPrestador');
            FInfCanc.FNotasCanceladas[i].NumeroNota                  := LeitorAux.rCampo(tcStr,'NumeroNota');
            FInfCanc.FNotasCanceladas[i].CodigoVerficacao            := LeitorAux.rCampo(tcStr,'CodigoVerificacao');

            LeitorAux.free;
                                                                                                                   
            Delete(strAux, PosI, count);                                                                           
            posI := pos('<Nota>', strAux);
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

               InfCanc.FMsgRetorno.Add;
               inc(i);
               
               LeitorAux := TLeitor.Create;
               leitorAux.Arquivo := copy(strAux, PosI, count);
               leitorAux.Grupo   := leitorAux.Arquivo;

               InfCanc.FMsgRetorno[i].FCodigo   := Leitor.rCampo(tcStr, 'Codigo');
               InfCanc.FMsgRetorno[i].FMensagem := 'Alerta - ' + Leitor.rCampo(tcStr, 'Descricao');


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

               InfCanc.FMsgRetorno.Add;
               inc(i);

               LeitorAux := TLeitor.Create;
               leitorAux.Arquivo := copy(strAux, PosI, count);
               leitorAux.Grupo   := leitorAux.Arquivo;

               InfCanc.FMsgRetorno[i].FCodigo   := Leitor.rCampo(tcStr, 'Codigo');
               InfCanc.FMsgRetorno[i].FMensagem := Leitor.rCampo(tcStr, 'Descricao');

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

function TretCancNFSe.LerXML_provedorSP: Boolean;
var
  Sucesso:String;
  I: Integer;
begin
  Leitor.Arquivo := NotaUtil.RetirarPrefixos(Leitor.Arquivo);
  Leitor.Grupo   := Leitor.Arquivo;
  try
    Sucesso:=LowerCase(Leitor.rCampo(tcStr, 'Sucesso'));
    I:=0;
    if Sucesso = 'true' then begin
      InfCanc.FSucesso := Leitor.rCampo(tcStr, 'Sucesso');
      InfCanc.FDataHora:= Now;
      if Leitor.rExtrai(1, 'Alerta') <> '' then begin
        Leitor.Arquivo:=Leitor.rExtrai(1, 'Alerta');
        InfCanc.MsgRetorno.Add;
        InfCanc.FMsgRetorno[I].FCodigo  := Leitor.rCampo(tcInt, 'Codigo');
        InfCanc.FMsgRetorno[I].Mensagem := Leitor.rCampo(tcStr, 'Descricao');
        InfCanc.FSucesso:=Sucesso;
      end;
    end else if Sucesso = 'false' then begin //lote não autorizado
      if Leitor.rExtrai(1, 'Erro') <> '' then begin
        Leitor.Grupo:=Leitor.rExtrai(1, 'Erro');
        InfCanc.MsgRetorno.Add;
        InfCanc.FMsgRetorno[I].FCodigo  := Leitor.rCampo(tcInt, 'Codigo');
        InfCanc.FMsgRetorno[I].Mensagem := Leitor.rCampo(tcStr, 'Descricao');
        InfCanc.FSucesso:=Sucesso;
      end else Result:=False;
    end;
    Result:=True;
  except
    Result:=False;
  end;
end;

function TretCancNFSe.LerXML_provedorEquiplano: Boolean;
var
  i: Integer;
begin
  try
    Leitor.Arquivo := NotaUtil.RetirarPrefixos(Leitor.Arquivo);
    Leitor.Grupo   := Leitor.Arquivo;

    InfCanc.FSucesso := Leitor.rCampo(tcStr, 'Sucesso');
    InfCanc.FDataHora:= Leitor.rCampo(tcDatHor, 'dtCancelamento');

    if leitor.rExtrai(1, 'mensagemRetorno') <> '' then
      begin
        i := 0;
        if (leitor.rExtrai(2, 'listaErros') <> '') then
          begin
            while Leitor.rExtrai(3, 'erro', '', i + 1) <> '' do
              begin
                InfCanc.FMsgRetorno.Add;
                InfCanc.FMsgRetorno[i].FCodigo   := Leitor.rCampo(tcStr, 'cdMensagem');
                InfCanc.FMsgRetorno[i].FMensagem := Leitor.rCampo(tcStr, 'dsMensagem');
                InfCanc.FMsgRetorno[i].FCorrecao := Leitor.rCampo(tcStr, 'dsCorrecao');

                inc(i);
              end;
          end;

        if (leitor.rExtrai(2, 'listaAlertas') <> '') then
          begin
            while Leitor.rExtrai(3, 'alerta', '', i + 1) <> '' do
              begin
                InfCanc.FMsgRetorno.Add;
                InfCanc.FMsgRetorno[i].FCodigo   := Leitor.rCampo(tcStr, 'cdMensagem');
                InfCanc.FMsgRetorno[i].FMensagem := Leitor.rCampo(tcStr, 'dsMensagem');
                InfCanc.FMsgRetorno[i].FCorrecao := Leitor.rCampo(tcStr, 'dsCorrecao');

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

