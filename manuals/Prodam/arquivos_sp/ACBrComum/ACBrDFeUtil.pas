{******************************************************************************}
{ Projeto: Componente ACBrNFe                                                  }
{  Biblioteca multiplataforma de componentes Delphi para emissão de Nota Fiscal}
{ eletrônica - NFe - http://www.nfe.fazenda.gov.br                          }
{                                                                              }
{ Direitos Autorais Reservados (c) 2008 Wemerson Souto                         }
{                                       Daniel Simoes de Almeida               }
{                                       André Ferreira de Moraes               }
{                                                                              }
{ Colaboradores nesse arquivo:                                                 }
{                                                                              }
{  Você pode obter a última versão desse arquivo na pagina do Projeto ACBr     }
{ Componentes localizado em http://www.sourceforge.net/projects/acbr           }
{                                                                              }
{                                                                              }
{  Esta biblioteca é software livre; você pode redistribuí-la e/ou modificá-la }
{ sob os termos da Licença Pública Geral Menor do GNU conforme publicada pela  }
{ Free Software Foundation; tanto a versão 2.1 da Licença, ou (a seu critério) }
{ qualquer versão posterior.                                                   }
{                                                                              }
{  Esta biblioteca é distribuída na expectativa de que seja útil, porém, SEM   }
{ NENHUMA GARANTIA; nem mesmo a garantia implícita de COMERCIABILIDADE OU      }
{ ADEQUAÇÃO A UMA FINALIDADE ESPECÍFICA. Consulte a Licença Pública Geral Menor}
{ do GNU para mais detalhes. (Arquivo LICENÇA.TXT ou LICENSE.TXT)              }
{                                                                              }
{  Você deve ter recebido uma cópia da Licença Pública Geral Menor do GNU junto}
{ com esta biblioteca; se não, escreva para a Free Software Foundation, Inc.,  }
{ no endereço 59 Temple Street, Suite 330, Boston, MA 02111-1307 USA.          }
{ Você também pode obter uma copia da licença em:                              }
{ http://www.opensource.org/licenses/lgpl-license.php                          }
{                                                                              }
{ Daniel Simões de Almeida  -  daniel@djsystem.com.br  -  www.djsystem.com.br  }
{              Praça Anita Costa, 34 - Tatuí - SP - 18270-410                  }
{                                                                              }
{******************************************************************************}

{******************************************************************************
|* Historico
|*
|* 22/11/2012: Italo Junior e Isaque Pinheiro
|*  - Unificação de funções de mesma funcionalidade das units:
|* ACBrNFeUtil
|* ACBrNFEsUtil
|* ACBrCTeUtil
******************************************************************************}

{$I ACBr.inc}

unit ACBrDFeUtil;

interface

uses
  Classes, Forms,
  {$IFDEF FPC}
    LResources, Controls, Graphics, Dialogs,
  {$ELSE}
    StrUtils,
  {$ENDIF}
  SysUtils;

type
  EACBrDFeException = class(Exception);

  DFeUtil = class
   private
   protected

   public
     class function PosEx(const SubStr, S: AnsiString; Offset: Cardinal = 1): Integer;
     class function PosLast(const SubStr, S: AnsiString ): Integer;
     class function PadE(const AString : string; const nLen : Integer; const Caracter : Char = ' ') : String;
     class function PadD(const AString : string; const nLen : Integer; const Caracter : Char = ' ') : String;
     class function padC(const AString : string; const nLen : Integer; const Caracter : Char = ' ') : String;
     class function SeSenao(ACondicao: Boolean; ATrue, AFalse: Variant) : Variant;
     class function FormatFloat(AValue: Extended; const AFormat: string = ',0.00'): String;
     class function Poem_Zeros(const Texto : String; const Tamanho : Integer) : String;overload;
     class function Poem_Zeros(const Valor : Integer; const Tamanho : Integer) : String;overload;
     class function Poem_vazio(const Texto : String; const Tamanho : Integer) : String;
     class function LasString(AString: String): String;
     class function EstaVazio(const AValue: String): Boolean;overload;
     class procedure EstaVazio(const AValue, AMensagem: String);overload;
     class function NaoEstaVazio(AValue: String): Boolean;
     class function EstaZerado(AValue: Double): Boolean;overload;
     class function EstaZerado(AValue: Integer): Boolean;overload;
     class procedure EstaZerado(AValue: Integer; AMensagem: String);overload;
     class function NaoEstaZerado(AValue: Double): Boolean;overload;
     class function NaoEstaZerado(AValue: Integer): Boolean;overload;
     class function LimpaNumero(AValue: String): String;
     class function TrataString(const AValue: String): String;overload;
     class function TrataString(const AValue: String; const ATamanho: Integer): String;overload;
     class function CortaD(const AString: string; const ATamanho: Integer): String;
     class function CortaE(const AString: string; const ATamanho: Integer): String;
     class function FormatDate(const AString: string): String;
     class function FormatDateTime(const AString: string): string;
     class function StringToDate(const AString: string): TDateTime;
     class function StringToTime(const AString: string): TDateTime;
     class function TamanhoIgual(const AValue: String; const ATamanho: Integer): Boolean;overload;
     class procedure TamanhoIgual(const AValue: String; const ATamanho: Integer; AMensagem: String);overload;
     class function TamanhoIgual(const AValue: Integer; const ATamanho: Integer): Boolean;overload;
     class procedure TamanhoIgual(const AValue: Integer; const ATamanho: Integer; AMensagem: String);overload;
     class function TamanhoMenor(const AValue: String; const ATamanho: Integer): Boolean;
     class function FormatarCPF(AValue : String ): String;
     class function FormatarCNPJ(AValue : String ): String;
     class function FormatarCEP(AValue : String ): String;
     class function FormatarFone(AValue : String ): String;
     class function FormatarNumeroDocumentoFiscal(AValue : String ): String;
     class function FormatarChaveAcesso(AValue : String ): String;
     class function StringToFloat(AValue : String ) : Double;
     class function StringToFloatDef(const AValue: String; const DefaultValue: Double): Double;
     // Incluido por Italo em 29/11/2012
     class procedure ConfAmbiente;
     class function PathAplication: String;
     class function CollateBr(Str: String): String;
     class function UpperCase2(Str: String): String;
 //    class function PathWithDelim( const APath : String ) : String;
 //    class function RetornarConteudoEntre(const Frase, Inicio, Fim: string): string;
     class function ValidaUFCidade(const UF, Cidade: Integer): Boolean; overload;
     class procedure ValidaUFCidade(const UF, Cidade: Integer; const AMensagem: string); overload;
     class function FormatarPlaca(AValue: string): string;
     // Incluido por Italo em 09/08/2013
     class function FormatarCNPJCPF(AValue : String ): String;
     // Incluido por Italo em 13/08/2013
     class function Modulo11(Valor: string; Peso: Integer = 2; Base: Integer = 9): String;
     class function ValidaDIDSI(AValue: string): Boolean;
     class function ValidaDIRE(AValue: string): Boolean;
     class function ValidaRE(AValue: string): Boolean;
     class function ValidaDrawback(AValue: string): Boolean;
     class function ValidaSUFRAMA(AValue: string): Boolean;
     class function ValidaRECOPI(AValue: string): Boolean;
     class function ValidaNVE(AValue: string): Boolean;
   published

   end;

implementation

uses
 IniFiles, Variants, DateUtils, ACBrUtil, ACBrConsts;

class function DFeUtil.EstaVazio(const AValue: String): Boolean;
begin
  Result := (Trim(AValue) = '');
end;

class function DFeUtil.CortaD(const AString: string;
  const ATamanho: Integer): String;
begin
  Result := copy(AString,1,ATamanho);
end;

class function DFeUtil.CortaE(const AString: string;
  const ATamanho: Integer): String;
begin
  Result := AString;
  if Length(AString) > ATamanho then
    Result := copy(AString, Length(AString)-ATamanho+1, length(AString));
end;

class procedure DFeUtil.EstaVazio(const AValue, AMensagem: String);
begin
  if EstaVazio(AValue) then
    raise EACBrDFeException.Create(AMensagem);
end;

class function DFeUtil.EstaZerado(AValue: Double): Boolean;
begin
  Result := (AValue = 0);
end;

class function DFeUtil.EstaZerado(AValue: Integer): Boolean;
begin
  Result := (AValue = 0);
end;

class procedure DFeUtil.EstaZerado(AValue: Integer; AMensagem: String);
begin
  if EstaZerado(AValue) then
    raise EACBrDFeException.Create(AMensagem);
end;

class function DFeUtil.FormatarCEP(AValue: String): String;
begin
  // Proposta de Italo
  AValue := Poem_Zeros(LimpaNumero(AValue), 8);

  if StrToInt(AValue) = 0
    then Result := Space(9)
    else Result := copy(AValue, 1, 5) + '-' + copy(AValue, 6, 3);
end;

class function DFeUtil.FormatarCNPJ(AValue: String): String;
begin
  if Length(AValue) = 0 then
     Result := AValue
  else
   begin
     AValue := LimpaNumero(AValue);
     Result := copy(AValue,1,2) + '.' + copy(AValue,3,3) + '.' +
               copy(AValue,6,3) + '/' + copy(AValue,9,4) + '-' + copy(AValue,13,2) ;
   end;
end;

class function DFeUtil.FormatarCPF(AValue: String): String;
begin
  if Length(AValue) = 0 then
     Result := AValue
  else
   begin
      AValue := LimpaNumero(AValue);
     Result := copy(AValue,1,3) + '.' + copy(AValue,4 ,3) + '.' +
               copy(AValue,7,3) + '-' + copy(AValue,10,2) ;
   end;
end;

class function DFeUtil.FormatarFone(AValue: String): String;
var
  lTemp: string;
begin
  // Proposta de Italo
  AValue := IntToStr(StrToInt64Def(LimpaNumero(AValue), 0));
  Result := AValue;
  lTemp  := '';

  if NaoEstaVazio(AValue) then
  begin
    case length(AValue) of
       8: Result := '(  )' + copy(AValue, 1, 4) + '-' + copy(AValue, 5, 4);
       9: begin
            if copy(AValue, 1, 1) = '9' // Celulares da Municipio de São Paulo tem 9 Digitos e o primeiro é 9
              then Result := '(  )' + copy(AValue, 1, 5) + '-' + copy(AValue, 6, 4)
              else begin
               ltemp := '0' + copy(AValue, 1, 1);
               Result := '(' + lTemp + ')' + copy(AValue, 2, 4) + '-' + copy(AValue, 6, 4);;
              end;
         end;
       else
       begin
         AValue := Poem_Zeros(AValue, 12);
         if (copy(AValue, 1, 1) = '0') and (copy(AValue, 2, 1) = '0')
           then begin
             ltemp := copy(AValue, 3, 2);
             Result := '(' + lTemp + ')' + copy(AValue, 5, 4) + '-' + copy(AValue, 9, 4);;
           end
           else begin
             ltemp := copy(AValue, 2, 2);
             Result := '(' + lTemp + ')' + copy(AValue, 4, 5) + '-' + copy(AValue, 9, 4);;
           end;
       end;
    end;
  end;
end;

class function DFeUtil.FormatarNumeroDocumentoFiscal(AValue: String): String;
begin
  AValue := Poem_Zeros(AValue, 9);
  Result := copy(AValue,1,3) + '.' + copy(AValue,4,3)+ '.'+
            copy(AValue,7,3);
end;

class function DFeUtil.FormatDate(const AString: string): String;
var
  vTemp: TDateTime;
{$IFDEF VER140} //D6
{$ELSE}
  vFormatSettings : TFormatSettings;
{$ENDIF}
begin
  try
{$IFDEF VER140} //D6
    DateSeparator := '/';
    ShortDateFormat := 'dd/mm/yyyy';
{$ELSE}
    vFormatSettings.DateSeparator   := '-';
    vFormatSettings.ShortDateFormat := 'yyyy-mm-dd';
//    vTemp := StrToDate(AString, FFormato);
{$ENDIF}
    vTemp := StrToDate(AString);
    if vTemp = 0 then
      Result := ''
    else
      Result := DateToStr(vTemp);
  except
    Result := '';
  end;
end;

class function DFeUtil.FormatDateTime(const AString: string): string;
var
  vTemp : TDateTime;
{$IFDEF VER140} //delphi6
{$ELSE}
vFormatSettings: TFormatSettings;
{$ENDIF}
begin
  try
{$IFDEF VER140} //delphi6
    DateSeparator   := '/';
    ShortDateFormat := 'dd/mm/yyyy';
    ShortTimeFormat := 'hh:nn:ss';
{$ELSE}
    vFormatSettings.DateSeparator   := '-';
    vFormatSettings.ShortDateFormat := 'yyyy-mm-dd';
    //    vTemp := StrToDate(AString, FFormato);
{$ENDIF}
    vTemp := StrToDateTime(AString);
    if vTemp = 0 then
      Result := ''
    else
      Result := DateTimeToStr(vTemp);
  except
    Result := '';
  end;
end;

class function DFeUtil.FormatFloat(AValue: Extended;
  const AFormat: string): String;
{$IFDEF VER140} //D6
{$ELSE}
var
vFormatSettings: TFormatSettings;
{$ENDIF}
begin
{$IFDEF VER140} //D6
  DecimalSeparator  := ',';
  ThousandSeparator := '.';
  Result := SysUtils.FormatFloat(AFormat, AValue);
{$ELSE}
  vFormatSettings.DecimalSeparator  := ',';
  vFormatSettings.ThousandSeparator := '.';
  Result := SysUtils.FormatFloat(AFormat, AValue, vFormatSettings);
{$ENDIF}
end;

class function DFeUtil.LasString(AString: String): String;
begin
  Result := Copy(AString, Length(AString), Length(AString));
end;

class function DFeUtil.LimpaNumero(AValue: String): String;
var
  A : Integer ;
begin
  Result := '' ;
  For A := 1 to length(AValue) do
  begin
    {$IFDEF DELPHI12_UP}
    if CharInSet(AValue[A], ['0'..'9']) then
    {$ELSE}
    if (AValue[A] in ['0'..'9']) then
    {$ENDIF}
       Result := Result + AValue[A];
  end ;
end;

class function DFeUtil.NaoEstaVazio(AValue: String): Boolean;
begin
  Result := not(EstaVazio(AValue));
end;

class function DFeUtil.NaoEstaZerado(AValue: Double): Boolean;
begin
  Result := not(EstaZerado(AValue));
end;

class function DFeUtil.NaoEstaZerado(AValue: Integer): Boolean;
begin
  Result := not(EstaZerado(AValue));
end;

class function DFeUtil.padC(const AString: string; const nLen: Integer;
  const Caracter: Char): String;
Var nCharLeft : Integer;
    D : Double;
begin
  Result    := copy(AString,1,nLen);
  D         := (nLen - Length( Result )) / 2;
  nCharLeft := Trunc( D );
  Result    := PadE( StringOfChar(Caracter, nCharLeft)+Result, nLen, Caracter);
end;

class function DFeUtil.PadD(const AString: string; const nLen: Integer;
  const Caracter: Char): String;
begin
  Result := copy(AString,1,nLen);
  Result := StringOfChar(Caracter, (nLen - Length(Result))) + Result;
end;

class function DFeUtil.PadE(const AString: string; const nLen: Integer;
  const Caracter: Char): String;
begin
  Result := copy(AString, 1, nLen);
  Result := Result + StringOfChar(Caracter, (nLen - Length(Result)));
end;

class function DFeUtil.Poem_Zeros(const Texto: String;
  const Tamanho: Integer): String;
begin
  Result := PadD(Trim(Texto),Tamanho,'0');
end;

class function DFeUtil.Poem_vazio(const Texto: String;
  const Tamanho: Integer): String;
var i,r : Integer;
    str : String;
begin
  str := '';
  r   := tamanho - length(Texto);

  For i:=0 to r do
  str := str + chr(32);

  Result :=  Texto + str;
end;

class function DFeUtil.Poem_Zeros(const Valor, Tamanho: Integer): String;
begin
  Result := PadD(IntToStr(Valor), Tamanho, '0');
end;

class function DFeUtil.PosEx(const SubStr, S: AnsiString; Offset: Cardinal = 1): Integer;
var
  I, X           : Integer;
  Len, LenSubStr : Integer;
begin
  if Offset = 1 then
    Result := Pos(SubStr, S)
  else
  begin
    I := Offset;
    LenSubStr := Length(SubStr);
    Len := Length(S) - LenSubStr + 1;
    while I <= Len do
    begin
      if S[I] = SubStr[1] then
      begin
        X := 1;
        while (X < LenSubStr) and (S[I + X] = SubStr[X + 1]) do
          Inc(X);
        if (X = LenSubStr) then
        begin
          Result := I;
          exit;
        end;
      end;
      Inc(I);
    end;
    Result := 0;
  end;
end;

class function DFeUtil.PosLast(const SubStr, S: AnsiString): Integer;
var
  P : Integer;
begin
  Result := 0;
  P := Pos(SubStr, S);

  while P <> 0 do
  begin
    Result := P;
    P := PosEx(SubStr, S, P + 1);
  end;
end;

class function DFeUtil.SeSenao(ACondicao: Boolean; ATrue,
  AFalse: Variant): Variant;
begin
  Result := AFalse;
  if ACondicao then
    Result := ATrue;
end;

class function DFeUtil.StringToDate(const AString: string): TDateTime;
begin
  if (AString = '0') or (AString = '') then
     Result := 0
  else
     Result := StrToDate(AString);
end;

class function DFeUtil.StringToFloat(AValue: String): Double;
begin
  AValue := Trim( AValue );

  if DecimalSeparator <> '.' then
     AValue := StringReplace(AValue,'.',DecimalSeparator,[rfReplaceAll]);

  if DecimalSeparator <> ',' then
     AValue := StringReplace(AValue,',',DecimalSeparator,[rfReplaceAll]);

  Result := StrToFloat(AValue);
end;

class function DFeUtil.StringToFloatDef(const AValue: String;
  const DefaultValue: Double): Double;
begin
  try
     Result := StringToFloat( AValue ) ;
  except
     Result := DefaultValue ;
  end ;
end;

class function DFeUtil.StringToTime(const AString: string): TDateTime;
begin
  if (AString = '0') or (AString = '') then
     Result := 0
  else
     Result := StrToTime(AString);
end;

class function DFeUtil.TrataString(const AValue: String): String;
var
  A : Integer ;
begin
  Result := '' ;
  For A := 1 to length(AValue) do
  begin
    case Ord(AValue[A]) of
      60  : Result := Result + '&lt;';  //<
      62  : Result := Result + '&gt;';  //>
      38  : Result := Result + '&amp;'; //&
      34  : Result := Result + '&quot;';//"
      39  : Result := Result + '&#39;'; //'
      32  : begin          // Retira espaços duplos
              if ( Ord(AValue[Pred(A)]) <> 32 ) then
                 Result := Result + ' ';
            end;
      193 : Result := Result + 'A';//Á
      224 : Result := Result + 'a';//à
      226 : Result := Result + 'a';//â
      234 : Result := Result + 'e';//ê
      244 : Result := Result + 'o';//ô
      251 : Result := Result + 'u';//û
      227 : Result := Result + 'a';//ã
      245 : Result := Result + 'o';//õ
      225 : Result := Result + 'a';//á
      233 : Result := Result + 'e';//é
      237 : Result := Result + 'i';//í
      243 : Result := Result + 'o';//ó
      250 : Result := Result + 'u';//ú
      231 : Result := Result + 'c';//ç
      252 : Result := Result + 'u';//ü
      192 : Result := Result + 'A';//À
      194 : Result := Result + 'A';//Â
      202 : Result := Result + 'E';//Ê
      212 : Result := Result + 'O';//Ô
      219 : Result := Result + 'U';//Û
      195 : Result := Result + 'A';//Ã
      213 : Result := Result + 'O';//Õ
      201 : Result := Result + 'E';//É
      205 : Result := Result + 'I';//Í
      211 : Result := Result + 'O';//Ó
      218 : Result := Result + 'U';//Ú
      199 : Result := Result + 'C';//Ç
      220 : Result := Result + 'U';//Ü
    else
      Result := Result + AValue[A];
    end;
  end;
  Result := Trim(Result);
end;

class function DFeUtil.TamanhoIgual(const AValue: String;
  const ATamanho: Integer): Boolean;
begin
  Result := (Length(AValue)= ATamanho);
end;

class procedure DFeUtil.TamanhoIgual(const AValue: String;
  const ATamanho: Integer; AMensagem: String);
begin
  if not(TamanhoIgual(AValue, ATamanho)) then
    raise EACBrDFeException.Create(AMensagem);
end;

class function DFeUtil.TamanhoIgual(const AValue, ATamanho: Integer): Boolean;
begin
  Result := (Length(IntToStr(AValue))= ATamanho);
end;

class procedure DFeUtil.TamanhoIgual(const AValue, ATamanho: Integer;
  AMensagem: String);
begin
  if not(TamanhoIgual(AValue, ATamanho)) then
    raise EACBrDFeException.Create(AMensagem);
end;

class function DFeUtil.TamanhoMenor(const AValue: String;
  const ATamanho: Integer): Boolean;
begin
  Result := (Length(AValue) < ATamanho);
end;

class function DFeUtil.TrataString(const AValue: String;
  const ATamanho: Integer): String;
begin
  Result := TrataString(CortaD(AValue, ATamanho));
end;

class procedure DFeUtil.ConfAmbiente;
{$IFDEF VER140} //delphi6
{$ELSE}
var
vFormatSettings: TFormatSettings;
{$ENDIF}
begin
{$IFDEF VER140} //delphi6
  DecimalSeparator := ',';
{$ELSE}
  vFormatSettings.DecimalSeparator := ',';
{$ENDIF}
end;

class function DFeUtil.PathAplication: String;
begin
  Result := ExtractFilePath({$IFNDEF Framework}Application.ExeName{$ELSE}ParamStr(0){$ENDIF});
end;

class function DFeUtil.CollateBr(Str: String): String;
var
   i, wTamanho: integer;
   wChar, wResultado: Char;
begin
   result   := '';
   wtamanho := Length(Str);
   i        := 1;
   while (i <= wtamanho) do
   begin
      wChar := Str[i];
      case wChar of
         'á', 'â', 'ã', 'à', 'ä', 'å',
         'Á', 'Â', 'Ã', 'À', 'Ä', 'Å': wResultado := 'A';
         'é', 'ê', 'è', 'ë',
         'É', 'Ê', 'È', 'Ë': wResultado := 'E';
         'í', 'î', 'ì', 'ï',
         'Í', 'Î', 'Ì', 'Ï': wResultado := 'I';
         'ó', 'ô', 'õ', 'ò', 'ö',
         'Ó', 'Ô', 'Õ', 'Ò', 'Ö': wResultado := 'O';
         'ú', 'û', 'ù', 'ü',
         'Ú', 'Û', 'Ù', 'Ü': wResultado := 'U';
         'ç', 'Ç': wResultado := 'C';
         'ñ', 'Ñ': wResultado := 'N';
         'ý', 'ÿ', 'Ý', 'Y': wResultado := 'Y';
      else
         wResultado := wChar;
      end;
      i      := i + 1;
      Result := Result + wResultado;
   end;
   Result := UpperCase(Result);
end;

class function DFeUtil.UpperCase2(Str: String): String;
var
   i, wTamanho: integer;
   wChar, wResultado: Char;
begin
   result   := '';
   wtamanho := Length(Str);
   i        := 1;
   while (i <= wtamanho) do
   begin
      wChar := Str[i];
      case wChar of
         'á','Á': wResultado := 'Á';
         'ã','Ã': wResultado := 'Ã';
         'à','À': wResultado := 'À';
         'â','Â': wResultado := 'Â';
         'ä','Ä': wResultado := 'Ä';
         'å','Å': wResultado := 'Å';
         'é','É': wResultado := 'É';
         'è','È': wResultado := 'È';
         'ê','Ê': wResultado := 'Ê';
         'ë','Ë': wResultado := 'Ë';
         'í','Í': wResultado := 'Í';
         'ì','Ì': wResultado := 'Ì';
         'î','Î': wResultado := 'Î';
         'ï','Ï': wResultado := 'Ï';
         'ó','Ó': wResultado := 'Ó';
         'õ','Õ': wResultado := 'Õ';
         'ò','Ò': wResultado := 'Ò';
         'ô','Ô': wResultado := 'Ô';
         'ö','Ö': wResultado := 'Ö';
         'ú','Ú': wResultado := 'Ú';
         'ù','Ù': wResultado := 'Ù';
         'û','Û': wResultado := 'Û';
         'ü','Ü': wResultado := 'Ü';
         'ç', 'Ç': wResultado := 'Ç';
         'ñ', 'Ñ': wResultado := 'Ñ';
         'ý', 'ÿ', 'Ý', 'Y': wResultado := 'Y';
      else
         wResultado := wChar;
      end;
      i      := i + 1;
      Result := Result + wResultado;
   end;
   Result := UpperCase(Result);
end;

class function DFeUtil.ValidaUFCidade(const UF, Cidade: Integer): Boolean;
begin
  Result := (Copy(IntToStr(UF), 1, 2) = Copy(IntToStr(Cidade), 1, 2));
end;

class procedure DFeUtil.ValidaUFCidade(const UF, Cidade: Integer;
  const AMensagem: string);
begin
  if not (ValidaUFCidade(UF, Cidade)) then
    raise EACBrDFeException.Create(AMensagem);
end;

class function DFeUtil.FormatarPlaca(AValue: string): string;
begin
 Result := Copy(AValue, 1, 3) + '-' + Copy(AValue, 4, 4);
end;

class function DFeUtil.FormatarChaveAcesso(AValue: String): String;
begin
  AValue := DFeUtil.LimpaNumero(AValue);
  Result := copy(AValue,1,4)  + ' ' + copy(AValue,5,4)  + ' ' +
            copy(AValue,9,4)  + ' ' + copy(AValue,13,4) + ' ' +
            copy(AValue,17,4) + ' ' + copy(AValue,21,4) + ' ' +
            copy(AValue,25,4) + ' ' + copy(AValue,29,4) + ' ' +
            copy(AValue,33,4) + ' ' + copy(AValue,37,4) + ' ' +
            copy(AValue,41,4) ;
end;

class function DFeUtil.FormatarCNPJCPF(AValue: String): String;
begin
  // Incluido por Italo em 02/10/2013
  // proposto por Juliomar Marchetti
  AValue := OnlyNumber(AValue);
  if Length(AValue) = 0 then
     Result := AValue
  else
   begin
    if Length(AValue) = 14 then
     Result := FormatarCNPJ(AValue)
    else
     Result := FormatarCPF(AValue);
   end;
end;

// Incluido por Italo em 13/08/2013
class function DFeUtil.Modulo11(Valor: string; Peso: Integer = 2; Base: Integer = 9): String;
var
  Soma: integer;
  Contador, Digito: integer;
begin
  Soma := 0;
  for Contador := Length(Valor) downto 1 do
  begin
    Soma := Soma + (StrToInt(Valor[Contador]) * Peso);
    if Peso < Base then
      Peso := Peso + 1
    else
      Peso := 2;
  end;

  Digito := 11 - (Soma mod 11);
  if (Digito > 9) or (Digito = 1) then
    Digito := 0;

  Result := IntToStr(Digito);
end;

class function DFeUtil.ValidaDIDSI(AValue: string): Boolean;
var
 ano: Integer;
 sValue: String;
begin
 // AValue = TAANNNNNNND
 // Onde: T Identifica o tipo de documento ( 2 = DI e 4 = DSI )
 //       AA Ano corrente da geração do documento
 //       NNNNNNN Número sequencial dentro do Ano ( 7 ou 8 dígitos )
 //       D Dígito Verificador, Módulo 11, Pesos de 2 a 9
 AValue := LimpaNumero(AValue);
 ano := StrToInt(Copy(IntToStr(YearOf(Date)), 3, 2));
 if (length(AValue) < 11) or (length(AValue) > 12) then
   Result := False
 else if (copy(Avalue, 1, 1) <> '2') and (copy(Avalue, 1, 1) <> '4') then
        Result := False
      else if not ((StrToInt(copy(Avalue, 2, 2)) >= ano -1) and (StrToInt(copy(Avalue, 2, 2)) <= ano +1)) then
             Result := False
           else begin
             sValue := copy(AValue, 1, length(AValue)- 1);
             Result := copy(AValue, length(AValue), 1) = Modulo11(sValue);
           end;
end;

class function DFeUtil.ValidaDIRE(AValue: string): Boolean;
var
 ano: Integer;
begin
 // AValue = AANNNNNNNNNN
 // Onde: AA Ano corrente da geração do documento
 //       NNNNNNNNNN Número sequencial dentro do Ano ( 10 dígitos )
 AValue := LimpaNumero(AValue);
 ano := StrToInt(Copy(IntToStr(YearOf(Date)), 3, 2));
 if length(AValue) <> 12 then
   Result := False
 else
   Result := (StrToInt(copy(Avalue, 1, 2)) >= ano -1) and (StrToInt(copy(Avalue, 1, 2)) <= ano +1);
end;

class function DFeUtil.ValidaRE(AValue: string): Boolean;
var
 ano: Integer;
begin
 // AValue = AANNNNNNNSSS
 // Onde: AA Ano corrente da geração do documento
 //       NNNNNNN Número sequencial dentro do Ano ( 7 dígitos )
 //       SSS Serie do RE (001, 002, ...)
 AValue := LimpaNumero(AValue);
 ano := StrToInt(Copy(IntToStr(YearOf(Date)), 3, 2));
 if length(AValue) <> 12 then
   Result := False
 else if not ((StrToInt(copy(Avalue, 2, 2)) >= ano -1) and (StrToInt(copy(Avalue, 2, 2)) <= ano +1)) then
        Result := False
      else
        Result := (StrToInt(copy(Avalue, 10, 3)) >= 1) and (StrToInt(copy(Avalue, 10, 3)) <= 999);
end;

class function DFeUtil.ValidaDrawback(AValue: string): Boolean;
var
 ano: Integer;
begin
 // AValue = AAAANNNNNND
 // Onde: AAAA Ano corrente do registro
 //       NNNNNN Número sequencial dentro do Ano ( 6 dígitos )
 //       D Dígito Verificador, Módulo 11, Pesos de 2 a 9
 AValue := LimpaNumero(AValue);
 ano := StrToInt(Copy(IntToStr(YearOf(Date)), 3, 2));
 if length(AValue) = 11 then
   AValue := copy(AValue, 3, 9);
 if length(AValue) <> 9 then
   Result := False
 else if not ((StrToInt(copy(Avalue, 1, 2)) >= ano -1) and (StrToInt(copy(Avalue, 1, 2)) <= ano +1)) then
        Result := False
      else
        Result := copy(AValue, 9, 1) = Modulo11(AValue);
end;

class function DFeUtil.ValidaSUFRAMA(AValue: string): Boolean;
var
 SS, LL: Integer;
begin
 // AValue = SSNNNNLLD
 // Onde: SS Código do setor de atividade da empresa ( 01, 02, 10, 11, 20 e 60 )
 //       NNNN Número sequencial ( 4 dígitos )
 //       LL Código da localidade da Unidade Administrativa da Suframa ( 01 = Manaus, 10 = Boa Vista e 30 = Porto Velho )
 //       D Dígito Verificador, Módulo 11, Pesos de 2 a 9
 AValue := LimpaNumero(AValue);
 if length(AValue) < 9 then
   AValue := '0' + AValue;
 if length(AValue) <> 9 then
   Result := False
 else
  begin
   SS := StrToInt(copy(Avalue, 1, 2));
   LL := StrToInt(copy(Avalue, 7, 2));
   if not (SS in [01, 02, 10, 11, 20, 60]) then
     Result := False
   else if not (LL in [01, 10, 30]) then
          Result := False
        else
          Result := copy(AValue, 9, 1) = Modulo11(AValue);
 end;
end;

class function DFeUtil.ValidaRECOPI(AValue: string): Boolean;
begin
 // AValue = aaaammddhhmmssffffDD
 // Onde: aaaammdd Ano/Mes/Dia da autorização
 //       hhmmssffff Hora/Minuto/Segundo da autorização com mais 4 digitos da fração de segundo
 //       DD Dígitos Verificadores, Módulo 11, Pesos de 1 a 18 e de 1 a 19
 AValue := LimpaNumero(AValue);
 if length(AValue) <> 20 then
   Result := False
 else if copy(AValue, 19, 1) <> Modulo11(copy(AValue, 1, 18), 1, 18) then
        Result := False
      else Result := copy(AValue, 20, 1) = Modulo11(copy(AValue, 1, 19), 1, 19);
end;

class function DFeUtil.ValidaNVE(AValue: string): Boolean;
begin
 Result := True;
end;

end.
