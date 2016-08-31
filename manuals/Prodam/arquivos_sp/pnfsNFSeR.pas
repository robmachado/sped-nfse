unit pnfsNFSeR;

interface

uses
  SysUtils, Classes, Forms,
{$IFNDEF VER130}
  Variants,
{$ENDIF}
  pcnAuxiliar, pcnConversao, pcnLeitor, pnfsNFSe, pnfsConversao,
  ACBrUtil, ACBrNFSeUtil, ACBrDFeUtil;

type

 TLeitorOpcoes   = class;

 TNFSeR = class(TPersistent)
  private
    FLeitor: TLeitor;
    FNFSe: TNFSe;
    FSchema: TpcnSchema;
    FOpcoes: TLeitorOpcoes;
    FVersaoXML: String;
    FProvedor: TnfseProvedor;
    FTabServicosExt: Boolean;

    function LerRPS_ABRASF_V1: Boolean;
    function LerRPS_ABRASF_V2: Boolean;
    function LerRPS_IssDSF: Boolean;
    function LerRPS_Equiplano: Boolean;

    function LerNFSe_ABRASF_V1: Boolean;
    function LerNFSe_ABRASF_V2: Boolean;
    function LerNFSe_IssDSF: Boolean;
    function LerNFSe_Equiplano: Boolean;
    function LerNFSe_SP: Boolean;

    function LerRPS: Boolean;
    function LerNFSe: Boolean;
  public
    constructor Create(AOwner: TNFSe);
    destructor Destroy; override;
    function LerXml: boolean;
  published
    property Leitor: TLeitor         read FLeitor         write FLeitor;
    property NFSe: TNFSe             read FNFSe           write FNFSe;
    property schema: TpcnSchema      read Fschema         write Fschema;
    property Opcoes: TLeitorOpcoes   read FOpcoes         write FOpcoes;
    property VersaoXML: String       read FVersaoXML      write FVersaoXML;
    property Provedor: TnfseProvedor read FProvedor       write FProvedor;
    property TabServicosExt: Boolean read FTabServicosExt write FTabServicosExt;
  end;

 TLeitorOpcoes = class(TPersistent)
  private
    FPathArquivoMunicipios: string;
    FPathArquivoTabServicos: string;
  published
    property PathArquivoMunicipios: string read FPathArquivoMunicipios write FPathArquivoMunicipios;
    property PathArquivoTabServicos: string read FPathArquivoTabServicos write FPathArquivoTabServicos;
  end;

implementation

{ TNFSeR }

constructor TNFSeR.Create(AOwner: TNFSe);
begin
 FLeitor := TLeitor.Create;
 FNFSe   := AOwner;
 FOpcoes := TLeitorOpcoes.Create;
 FOpcoes.FPathArquivoMunicipios  := '';
 FOpcoes.FPathArquivoTabServicos := '';
end;

destructor TNFSeR.Destroy;
begin
 FLeitor.Free;
 FOpcoes.Free;

 inherited Destroy;
end;

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

function TNFSeR.LerRPS_ABRASF_V1: Boolean;
var
 item: Integer;
 ok  : Boolean;
begin
 if (Leitor.rExtrai(2, 'InfRps') <> '') or (Leitor.rExtrai(1, 'Rps') <> '')
  then begin
   NFSe.DataEmissaoRps           := Leitor.rCampo(tcDat, 'DataEmissao');

   if (Leitor.rExtrai(1, 'InfRps') <> '')
    then NFSe.DataEmissao        := Leitor.rCampo(tcDatHor, 'DataEmissao');

   NFSe.NaturezaOperacao         := StrToNaturezaOperacao(ok, Leitor.rCampo(tcStr, 'NaturezaOperacao'));
   NFSe.RegimeEspecialTributacao := StrToRegimeEspecialTributacao(ok, Leitor.rCampo(tcStr, 'RegimeEspecialTributacao'));
   NFSe.OptanteSimplesNacional   := StrToSimNao(ok, Leitor.rCampo(tcStr, 'OptanteSimplesNacional'));
   NFSe.IncentivadorCultural     := StrToSimNao(ok, Leitor.rCampo(tcStr, 'IncentivadorCultural'));
   NFSe.Status                   := StrToStatusRPS(ok, Leitor.rCampo(tcStr, 'Status'));

   if (Leitor.rExtrai(3, 'IdentificacaoRps') <> '') or (Leitor.rExtrai(2, 'IdentificacaoRps') <> '')
    then begin
     NFSe.IdentificacaoRps.Numero := Leitor.rCampo(tcStr, 'Numero');
     NFSe.IdentificacaoRps.Serie  := Leitor.rCampo(tcStr, 'Serie');
     NFSe.IdentificacaoRps.Tipo   := StrToTipoRPS(ok, Leitor.rCampo(tcStr, 'Tipo'));
     NFSe.InfID.ID                := SomenteNumeros(NFSe.IdentificacaoRps.Numero) + NFSe.IdentificacaoRps.Serie;
    end;

   if (Leitor.rExtrai(3, 'RpsSubstituido') <> '') or (Leitor.rExtrai(2, 'RpsSubstituido') <> '')
    then begin
     NFSe.RpsSubstituido.Numero := Leitor.rCampo(tcStr, 'Numero');
     NFSe.RpsSubstituido.Serie  := Leitor.rCampo(tcStr, 'Serie');
     NFSe.RpsSubstituido.Tipo   := StrToTipoRPS(ok, Leitor.rCampo(tcStr, 'Tipo'));
    end;

   if (Leitor.rExtrai(3, 'Servico') <> '') or (Leitor.rExtrai(2, 'Servico') <> '')
    then begin
     NFSe.Servico.ItemListaServico          := DFeUtil.LimpaNumero(Leitor.rCampo(tcStr, 'ItemListaServico'));

     // ALTERTADO POR TÚLIO DAPPER EM 25/03
     NFSe.Servico.CodigoCnae                := Leitor.rCampo(tcStr, 'CodigoCnae');

     NFSe.Servico.CodigoTributacaoMunicipio := Leitor.rCampo(tcStr, 'CodigoTributacaoMunicipio');
     NFSe.Servico.Discriminacao             := Leitor.rCampo(tcStr, 'Discriminacao');
     NFSe.Servico.Descricao                 := '';

     if VersaoXML = '1'
      then NFSe.Servico.CodigoMunicipio := Leitor.rCampo(tcStr, 'MunicipioPrestacaoServico')
      else NFSe.Servico.CodigoMunicipio := Leitor.rCampo(tcStr, 'CodigoMunicipio');

     Item := StrToIntDef(SomenteNumeros(Nfse.Servico.ItemListaServico), 0);
     if Item<100 then Item:=Item*100+1;

     NFSe.Servico.ItemListaServico := FormatFloat('0000', Item);
     NFSe.Servico.ItemListaServico := Copy(NFSe.Servico.ItemListaServico, 1, 2) + '.' +
                                      Copy(NFSe.Servico.ItemListaServico, 3, 2);

     if TabServicosExt
      then NFSe.Servico.xItemListaServico := NotaUtil.ObterDescricaoServico(SomenteNumeros(NFSe.Servico.ItemListaServico))
      else NFSe.Servico.xItemListaServico := CodigoToDesc(SomenteNumeros(NFSe.Servico.ItemListaServico));

     if length(NFSe.Servico.CodigoMunicipio) < 7
      then NFSe.Servico.CodigoMunicipio := Copy(NFSe.Servico.CodigoMunicipio, 1, 2) +
            FormatFloat('00000', StrToIntDef(Copy(NFSe.Servico.CodigoMunicipio, 3, 5), 0));

     if (Leitor.rExtrai(4, 'Valores') <> '') or (Leitor.rExtrai(3, 'Valores') <> '')
      then begin
       NFSe.Servico.Valores.ValorServicos          := Leitor.rCampo(tcDe2, 'ValorServicos');
       NFSe.Servico.Valores.ValorDeducoes          := Leitor.rCampo(tcDe2, 'ValorDeducoes');
       NFSe.Servico.Valores.ValorPis               := Leitor.rCampo(tcDe2, 'ValorPis');
       NFSe.Servico.Valores.ValorCofins            := Leitor.rCampo(tcDe2, 'ValorCofins');
       NFSe.Servico.Valores.ValorInss              := Leitor.rCampo(tcDe2, 'ValorInss');
       NFSe.Servico.Valores.ValorIr                := Leitor.rCampo(tcDe2, 'ValorIr');
       NFSe.Servico.Valores.ValorCsll              := Leitor.rCampo(tcDe2, 'ValorCsll');
       NFSe.Servico.Valores.IssRetido              := StrToSituacaoTributaria(ok, Leitor.rCampo(tcStr, 'IssRetido'));
       NFSe.Servico.Valores.ValorIss               := Leitor.rCampo(tcDe2, 'ValorIss');
       NFSe.Servico.Valores.OutrasRetencoes        := Leitor.rCampo(tcDe2, 'OutrasRetencoes');
       NFSe.Servico.Valores.BaseCalculo            := Leitor.rCampo(tcDe2, 'BaseCalculo');
       NFSe.Servico.Valores.Aliquota               := Leitor.rCampo(tcDe3, 'Aliquota');
       NFSe.Servico.Valores.ValorLiquidoNfse       := Leitor.rCampo(tcDe2, 'ValorLiquidoNfse');
       NFSe.Servico.Valores.ValorIssRetido         := Leitor.rCampo(tcDe2, 'ValorIssRetido');
       NFSe.Servico.Valores.DescontoCondicionado   := Leitor.rCampo(tcDe2, 'DescontoCondicionado');
       NFSe.Servico.Valores.DescontoIncondicionado := Leitor.rCampo(tcDe2, 'DescontoIncondicionado');
      end;

    end; // fim Servico

   if (Leitor.rExtrai(3, 'Prestador') <> '') or (Leitor.rExtrai(2, 'Prestador') <> '')
    then begin
     NFSe.Prestador.Cnpj               := Leitor.rCampo(tcStr, 'Cnpj');
     NFSe.Prestador.InscricaoMunicipal := Leitor.rCampo(tcStr, 'InscricaoMunicipal');
    end; // fim Prestador

   if (Leitor.rExtrai(3, 'Tomador') <> '') or (Leitor.rExtrai(2, 'Tomador') <> '')
    then begin
     NFSe.Tomador.RazaoSocial := Leitor.rCampo(tcStr, 'RazaoSocial');

     NFSe.Tomador.Endereco.Endereco := Leitor.rCampo(tcStr, 'Endereco');
     if Copy(NFSe.Tomador.Endereco.Endereco, 1, 10) = '<Endereco>'
      then NFSe.Tomador.Endereco.Endereco := Copy(NFSe.Tomador.Endereco.Endereco, 11, 125);

     NFSe.Tomador.Endereco.Numero      := Leitor.rCampo(tcStr, 'Numero');
     NFSe.Tomador.Endereco.Complemento := Leitor.rCampo(tcStr, 'Complemento');
     NFSe.Tomador.Endereco.Bairro      := Leitor.rCampo(tcStr, 'Bairro');

     if VersaoXML = '1'
      then begin
       NFSe.Tomador.Endereco.CodigoMunicipio := Leitor.rCampo(tcStr, 'Cidade');
       NFSe.Tomador.Endereco.UF              := Leitor.rCampo(tcStr, 'Estado');
      end
      else begin
       NFSe.Tomador.Endereco.CodigoMunicipio := Leitor.rCampo(tcStr, 'CodigoMunicipio');
       NFSe.Tomador.Endereco.UF              := Leitor.rCampo(tcStr, 'Uf');
      end;

     NFSe.Tomador.Endereco.CEP := Leitor.rCampo(tcStr, 'Cep');

     if length(NFSe.Tomador.Endereco.CodigoMunicipio)<7
      then NFSe.Tomador.Endereco.CodigoMunicipio := Copy(NFSe.Tomador.Endereco.CodigoMunicipio, 1, 2) +
            FormatFloat('00000', StrToIntDef(Copy(NFSe.Tomador.Endereco.CodigoMunicipio, 3, 5), 0));

     if NFSe.Tomador.Endereco.UF = ''
      then NFSe.Tomador.Endereco.UF := NFSe.PrestadorServico.Endereco.UF;

     NFSe.Tomador.Endereco.xMunicipio := CodCidadeToCidade(StrToIntDef(NFSe.Tomador.Endereco.CodigoMunicipio, 0));

     if Leitor.rExtrai(4, 'IdentificacaoTomador') <> ''
      then begin
       NFSe.Tomador.IdentificacaoTomador.InscricaoMunicipal := Leitor.rCampo(tcStr, 'InscricaoMunicipal');

       if Leitor.rExtrai(5, 'CpfCnpj') <> ''
        then begin
         if Leitor.rCampo(tcStr, 'Cpf')<>''
          then NFSe.Tomador.IdentificacaoTomador.CpfCnpj := Leitor.rCampo(tcStr, 'Cpf')
          else NFSe.Tomador.IdentificacaoTomador.CpfCnpj := Leitor.rCampo(tcStr, 'Cnpj');
        end;
      end;

     if Leitor.rExtrai(4, 'Contato') <> ''
      then begin
       NFSe.Tomador.Contato.Telefone := Leitor.rCampo(tcStr, 'Telefone');
       NFSe.Tomador.Contato.Email    := Leitor.rCampo(tcStr, 'Email');
      end;

    end; // fim Tomador

   if Leitor.rExtrai(3, 'IntermediarioServico') <> ''
    then begin
     NFSe.IntermediarioServico.RazaoSocial        := Leitor.rCampo(tcStr, 'RazaoSocial');
     NFSe.IntermediarioServico.InscricaoMunicipal := Leitor.rCampo(tcStr, 'InscricaoMunicipal');
     if Leitor.rExtrai(4, 'CpfCnpj') <> ''
      then begin
       if Leitor.rCampo(tcStr, 'Cpf')<>''
        then NFSe.IntermediarioServico.CpfCnpj := Leitor.rCampo(tcStr, 'Cpf')
        else NFSe.IntermediarioServico.CpfCnpj := Leitor.rCampo(tcStr, 'Cnpj');
      end;
    end;

   if Leitor.rExtrai(3, 'ConstrucaoCivil') <> ''
    then begin
     NFSe.ConstrucaoCivil.CodigoObra := Leitor.rCampo(tcStr, 'CodigoObra');
     NFSe.ConstrucaoCivil.Art        := Leitor.rCampo(tcStr, 'Art');
    end;

  end; // fim InfRps

 Result := True;
end;

function TNFSeR.LerRPS_ABRASF_V2: Boolean;
var
 item, i: Integer;
 ok  : Boolean;
begin
 // Para o provedor ISSDigital
 if (Leitor.rExtrai(2, 'ValoresServico') <> '')
  then begin
   NFSe.Servico.Valores.ValorServicos    := Leitor.rCampo(tcDe2, 'ValorServicos');
   NFSe.Servico.Valores.ValorLiquidoNfse := Leitor.rCampo(tcDe2, 'ValorLiquidoNfse');
   NFSe.Servico.Valores.ValorIss         := Leitor.rCampo(tcDe2, 'ValorIss');
  end;

   // Para o provedor ISSDigital
   if (Leitor.rExtrai(2, 'ListaServicos') <> '')
    then begin
     NFSe.Servico.Valores.IssRetido   := StrToSituacaoTributaria(ok, Leitor.rCampo(tcStr, 'IssRetido'));
     NFSe.Servico.ResponsavelRetencao := StrToResponsavelRetencao(ok, Leitor.rCampo(tcStr, 'ResponsavelRetencao'));
     NFSe.Servico.ItemListaServico    := DFeUtil.LimpaNumero(Leitor.rCampo(tcStr, 'ItemListaServico'));

     Item := StrToIntDef(SomenteNumeros(Nfse.Servico.ItemListaServico), 0);
     if Item<100 then Item:=Item*100+1;

     NFSe.Servico.ItemListaServico := FormatFloat('0000', Item);
     NFSe.Servico.ItemListaServico := Copy(NFSe.Servico.ItemListaServico, 1, 2) + '.' +
                                      Copy(NFSe.Servico.ItemListaServico, 3, 2);

     if TabServicosExt
      then NFSe.Servico.xItemListaServico := NotaUtil.ObterDescricaoServico(SomenteNumeros(NFSe.Servico.ItemListaServico))
      else NFSe.Servico.xItemListaServico := CodigoToDesc(SomenteNumeros(NFSe.Servico.ItemListaServico));

     //NFSe.Servico.Discriminacao       := Leitor.rCampo(tcStr, 'Discriminacao');
     NFSe.Servico.CodigoMunicipio     := Leitor.rCampo(tcStr, 'CodigoMunicipio');
     NFSe.Servico.CodigoPais          := Leitor.rCampo(tcInt, 'CodigoPais');
     NFSe.Servico.ExigibilidadeISS    := StrToExigibilidadeISS(ok, Leitor.rCampo(tcStr, 'ExigibilidadeISS'));
     NFSe.Servico.MunicipioIncidencia := Leitor.rCampo(tcInt, 'MunicipioIncidencia');

     NFSe.Servico.Valores.Aliquota    := Leitor.rCampo(tcDe3, 'Aliquota');

     //Se não me engano o maximo de servicos é 10...não?
     for I := 1 to 10
      do begin
       if (Leitor.rExtrai(2, 'Servico', 'Servico', i) <> '')
        then begin

         with NFSe.Servico.ItemServico.Add
          do begin
           Descricao       := Leitor.rCampo(tcStr, 'Discriminacao');

           if (Leitor.rExtrai(3, 'Valores') <> '')
            then begin
             ValorServicos := Leitor.rCampo(tcDe2, 'ValorServicos');
             ValorDeducoes := Leitor.rCampo(tcDe2, 'ValorDeducoes');
             ValorIss      := Leitor.rCampo(tcDe2, 'ValorIss');
             Aliquota      := Leitor.rCampo(tcDe3, 'Aliquota');
             BaseCalculo   := Leitor.rCampo(tcDe2, 'BaseCalculo');
            end;
         end;
        end else Break;
      end;

    end; // fim lista serviço

 if Leitor.rExtrai(2, 'InfDeclaracaoPrestacaoServico') <> ''
  then begin
   NFSe.Competencia              := Leitor.rCampo(tcStr, 'Competencia');
   NFSe.RegimeEspecialTributacao := StrToRegimeEspecialTributacao(ok, Leitor.rCampo(tcStr, 'RegimeEspecialTributacao'));
   NFSe.OptanteSimplesNacional   := StrToSimNao(ok, Leitor.rCampo(tcStr, 'OptanteSimplesNacional'));
   NFSe.IncentivadorCultural     := StrToSimNao(ok, Leitor.rCampo(tcStr, 'IncentivoFiscal'));
   NFSe.Producao                 := StrToSimNao(ok, Leitor.rCampo(tcStr, 'Producao'));

   if (Leitor.rExtrai(3, 'Rps') <> '')
    then begin
     NFSe.DataEmissaoRps := Leitor.rCampo(tcDat, 'DataEmissao');
     NFSe.Status         := StrToStatusRPS(ok, Leitor.rCampo(tcStr, 'Status'));

     if (Leitor.rExtrai(3, 'IdentificacaoRps') <> '')
      then begin
       NFSe.IdentificacaoRps.Numero := Leitor.rCampo(tcStr, 'Numero');
       NFSe.IdentificacaoRps.Serie  := Leitor.rCampo(tcStr, 'Serie');
       NFSe.IdentificacaoRps.Tipo   := StrToTipoRPS(ok, Leitor.rCampo(tcStr, 'Tipo'));
       NFSe.InfID.ID                := SomenteNumeros(NFSe.IdentificacaoRps.Numero) + NFSe.IdentificacaoRps.Serie;
      end;
    end;

   if (Leitor.rExtrai(3, 'Servico') <> '')
    then begin
     NFSe.Servico.Valores.IssRetido   := StrToSituacaoTributaria(ok, Leitor.rCampo(tcStr, 'IssRetido'));
     NFSe.Servico.ResponsavelRetencao := StrToResponsavelRetencao(ok, Leitor.rCampo(tcStr, 'ResponsavelRetencao'));
     NFSe.Servico.ItemListaServico    := DFeUtil.LimpaNumero(Leitor.rCampo(tcStr, 'ItemListaServico'));

     Item := StrToIntDef(SomenteNumeros(Nfse.Servico.ItemListaServico), 0);
     if Item<100 then Item:=Item*100+1;

     NFSe.Servico.ItemListaServico := FormatFloat('0000', Item);
     NFSe.Servico.ItemListaServico := Copy(NFSe.Servico.ItemListaServico, 1, 2) + '.' +
                                      Copy(NFSe.Servico.ItemListaServico, 3, 2);

     if TabServicosExt
      then NFSe.Servico.xItemListaServico := NotaUtil.ObterDescricaoServico(SomenteNumeros(NFSe.Servico.ItemListaServico))
      else NFSe.Servico.xItemListaServico := CodigoToDesc(SomenteNumeros(NFSe.Servico.ItemListaServico));

     NFSe.Servico.Discriminacao       := Leitor.rCampo(tcStr, 'Discriminacao');
     NFSe.Servico.Descricao           := '';
     NFSe.Servico.CodigoMunicipio     := Leitor.rCampo(tcStr, 'CodigoMunicipio');
     NFSe.Servico.CodigoPais          := Leitor.rCampo(tcInt, 'CodigoPais');
     NFSe.Servico.ExigibilidadeISS    := StrToExigibilidadeISS(ok, Leitor.rCampo(tcStr, 'ExigibilidadeISS'));
     NFSe.Servico.MunicipioIncidencia := Leitor.rCampo(tcInt, 'MunicipioIncidencia');

     // Provedor Goiania
     NFSe.Servico.CodigoTributacaoMunicipio := Leitor.rCampo(tcStr, 'CodigoTributacaoMunicipio');

     if (Leitor.rExtrai(4, 'Valores') <> '')
      then begin
       NFSe.Servico.Valores.ValorServicos          := Leitor.rCampo(tcDe2, 'ValorServicos');
       NFSe.Servico.Valores.ValorIss               := Leitor.rCampo(tcDe2, 'ValorIss');
       NFSe.Servico.Valores.Aliquota               := Leitor.rCampo(tcDe3, 'Aliquota');

       // Provedor Goiania
       NFSe.Servico.Valores.ValorCofins            := Leitor.rCampo(tcDe2, 'ValorCofins');
       NFSe.Servico.Valores.ValorInss              := Leitor.rCampo(tcDe2, 'ValorInss');
       NFSe.Servico.Valores.ValorIr                := Leitor.rCampo(tcDe2, 'ValorIr');
       NFSe.Servico.Valores.ValorCsll              := Leitor.rCampo(tcDe2, 'ValorCsll');
       NFSe.Servico.Valores.DescontoIncondicionado := Leitor.rCampo(tcDe3, 'DescontoIncondicionado');
      end;

    end; // fim serviço

   if (Leitor.rExtrai(3, 'Prestador') <> '')
    then begin
     NFSe.PrestadorServico.IdentificacaoPrestador.InscricaoMunicipal := Leitor.rCampo(tcStr, 'InscricaoMunicipal');
     NFSe.Prestador.InscricaoMunicipal := NFSe.PrestadorServico.IdentificacaoPrestador.InscricaoMunicipal;

     if VersaoXML = '1'
      then begin
       if Leitor.rExtrai(4, 'CpfCnpj') <> ''
        then begin
          NFSe.PrestadorServico.IdentificacaoPrestador.Cnpj := Leitor.rCampo(tcStr, 'Cpf');
          if NFSe.PrestadorServico.IdentificacaoPrestador.Cnpj = ''
           then NFSe.PrestadorServico.IdentificacaoPrestador.Cnpj := Leitor.rCampo(tcStr, 'Cnpj');
        end;
      end
      else begin
       NFSe.PrestadorServico.IdentificacaoPrestador.Cnpj := Leitor.rCampo(tcStr, 'Cnpj');
      end;

      NFSe.Prestador.Cnpj := NFSe.PrestadorServico.IdentificacaoPrestador.Cnpj;
    end; // fim Prestador

   if (Leitor.rExtrai(3, 'Tomador') <> '') or (Leitor.rExtrai(3, 'TomadorServico') <> '')
    then begin
     NFSe.Tomador.RazaoSocial := Leitor.rCampo(tcStr, 'RazaoSocial');
     NFSe.Tomador.IdentificacaoTomador.InscricaoEstadual  := Leitor.rCampo(tcStr, 'InscricaoEstadual');

     NFSe.Tomador.Endereco.Endereco := Leitor.rCampo(tcStr, 'Endereco');
     if Copy(NFSe.Tomador.Endereco.Endereco, 1, 10) = '<Endereco>'
      then NFSe.Tomador.Endereco.Endereco := Copy(NFSe.Tomador.Endereco.Endereco, 11, 125);

     NFSe.Tomador.Endereco.Numero      := Leitor.rCampo(tcStr, 'Numero');
     NFSe.Tomador.Endereco.Complemento := Leitor.rCampo(tcStr, 'Complemento');
     NFSe.Tomador.Endereco.Bairro      := Leitor.rCampo(tcStr, 'Bairro');

     if VersaoXML = '1'
      then begin
       NFSe.Tomador.Endereco.CodigoMunicipio := Leitor.rCampo(tcStr, 'Cidade');
       NFSe.Tomador.Endereco.UF              := Leitor.rCampo(tcStr, 'Estado');
      end
      else begin
       NFSe.Tomador.Endereco.CodigoMunicipio := Leitor.rCampo(tcStr, 'CodigoMunicipio');
       NFSe.Tomador.Endereco.UF              := Leitor.rCampo(tcStr, 'Uf');
      end;

     NFSe.Tomador.Endereco.CEP := Leitor.rCampo(tcStr, 'Cep');

     if length(NFSe.Tomador.Endereco.CodigoMunicipio)<7
      then NFSe.Tomador.Endereco.CodigoMunicipio := Copy(NFSe.Tomador.Endereco.CodigoMunicipio, 1, 2) +
        FormatFloat('00000', StrToIntDef(Copy(NFSe.Tomador.Endereco.CodigoMunicipio, 3, 5), 0));

     if NFSe.Tomador.Endereco.UF = ''
      then NFSe.Tomador.Endereco.UF := NFSe.PrestadorServico.Endereco.UF;

     NFSe.Tomador.Endereco.xMunicipio := CodCidadeToCidade(StrToIntDef(NFSe.Tomador.Endereco.CodigoMunicipio, 0));

     if (Leitor.rExtrai(4, 'IdentificacaoTomador') <> '')
      then begin
       NFSe.Tomador.IdentificacaoTomador.InscricaoMunicipal := Leitor.rCampo(tcStr, 'InscricaoMunicipal');

       if Leitor.rExtrai(5, 'CpfCnpj') <> ''
        then begin
         if Leitor.rCampo(tcStr, 'Cpf')<>''
          then NFSe.Tomador.IdentificacaoTomador.CpfCnpj := Leitor.rCampo(tcStr, 'Cpf')
          else NFSe.Tomador.IdentificacaoTomador.CpfCnpj := Leitor.rCampo(tcStr, 'Cnpj');
        end;
      end;

     if (Leitor.rExtrai(4, 'Contato') <> '')
      then begin
       NFSe.Tomador.Contato.Telefone := Leitor.rCampo(tcStr, 'Telefone');
       NFSe.Tomador.Contato.Email    := Leitor.rCampo(tcStr, 'Email');
      end;

    end; // fim Tomador

  end; // fim InfDeclaracaoPrestacaoServico

 Result := True;
end;

function TNFSeR.LerRPS_IssDSF: Boolean;
var
 ok  : Boolean;
 sOperacao, sTributacao: string;
begin
   VersaoXML := '1'; // para este provedor usar padrão "1".

   if (Leitor.rExtrai(1, 'Rps') <> '') then begin

      NFSe.DataEmissao := Leitor.rCampo(tcDatHor, 'DataEmissaoRPS');
      NFSe.Status      := StrToEnumerado(ok, Leitor.rCampo(tcStr, 'Status'),['N','C'],[srNormal, srCancelado]);

      NFSe.IdentificacaoRps.Numero := Leitor.rCampo(tcStr, 'NumeroRPS');
      NFSe.IdentificacaoRps.Serie  := Leitor.rCampo(tcStr, 'SerieRPS');
      NFSe.IdentificacaoRps.Tipo   := trRPS;//StrToTipoRPS(ok, Leitor.rCampo(tcStr, 'Tipo'));
      NFSe.InfID.ID                := SomenteNumeros(NFSe.IdentificacaoRps.Numero);// + NFSe.IdentificacaoRps.Serie;
      NFSe.SeriePrestacao          := Leitor.rCampo(tcStr, 'SeriePrestacao');

     	NFSe.Tomador.RazaoSocial              := Leitor.rCampo(tcStr, 'RazaoSocialTomador');

      NFSe.Tomador.Endereco.TipoLogradouro  := Leitor.rCampo(tcStr, 'TipoLogradouroTomador');
      NFSe.Tomador.Endereco.Endereco        := Leitor.rCampo(tcStr, 'LogradouroTomador');

      NFSe.Tomador.Endereco.Numero          := Leitor.rCampo(tcStr, 'NumeroEnderecoTomador');
      NFSe.Tomador.Endereco.Complemento     := Leitor.rCampo(tcStr, 'ComplementoEnderecoTomador');
      NFSe.Tomador.Endereco.TipoBairro      := Leitor.rCampo(tcStr, 'TipoBairroTomador');
      NFSe.Tomador.Endereco.Bairro          := Leitor.rCampo(tcStr, 'BairroTomador');
      NFSe.Tomador.Endereco.CEP             := Leitor.rCampo(tcStr, 'CEPTomador');
     	NFSe.Tomador.Endereco.CodigoMunicipio := CodSiafiToCodCidade( Leitor.rCampo(tcStr, 'CidadeTomador')) ;
     	NFSe.Tomador.Endereco.UF              := Leitor.rCampo(tcStr, 'Uf');
     	NFSe.Tomador.IdentificacaoTomador.InscricaoMunicipal := Leitor.rCampo(tcStr, 'InscricaoMunicipalTomador');
     	NFSe.Tomador.IdentificacaoTomador.CpfCnpj := Leitor.rCampo(tcStr, 'CPFCNPJTomador');
      NFSe.Tomador.IdentificacaoTomador.DocTomadorEstrangeiro := 'DocTomadorEstrangeiro';
      NFSe.Tomador.Contato.Email := Leitor.rCampo(tcStr, 'EmailTomador');

      NFSe.Servico.CodigoCnae := Leitor.rCampo(tcStr, 'CodigoAtividade');
      NFSe.Servico.Valores.Aliquota := Leitor.rCampo(tcDe3, 'AliquotaAtividade');

      NFSe.Servico.Valores.IssRetido := StrToEnumerado( ok, Leitor.rCampo(tcStr, 'TipoRecolhimento'),
                                                        ['A','R'], [ stNormal, stRetencao{, stSubstituicao}]);

      NFSe.Servico.CodigoMunicipio := CodSiafiToCodCidade( Leitor.rCampo(tcStr, 'MunicipioPrestacao'));

      sOperacao   := AnsiUpperCase(Leitor.rCampo(tcStr, 'Operacao'));
      sTributacao := AnsiUpperCase(Leitor.rCampo(tcStr, 'Tributacao'));


      if sOperacao[1] in ['A', 'B'] then begin

         if (sOperacao = 'A') and (sTributacao = 'N') then
            NFSe.NaturezaOperacao := noNaoIncidencia
         else if sTributacao = 'G' then
            NFSe.NaturezaOperacao := noTributacaoForaMunicipio
         else if sTributacao = 'T' then
            NFSe.NaturezaOperacao := noTributacaoNoMunicipio;
      end
      else if (sOperacao = 'C') and (sTributacao = 'C') then begin
         NFSe.NaturezaOperacao := noIsencao;
      end
      else if (sOperacao = 'C') and (sTributacao = 'F') then begin
         NFSe.NaturezaOperacao := noImune;
      end;

      NFSe.NaturezaOperacao := StrToEnumerado( ok,sTributacao, ['T','K'], [ NFSe.NaturezaOperacao, noSuspensaDecisaoJudicial ]);

      NFSe.OptanteSimplesNacional := StrToEnumerado( ok,sTributacao, ['T','H'], [ snNao, snSim ]);

      NFSe.DeducaoMateriais := StrToEnumerado( ok,sOperacao, ['A','B'], [ snNao, snSim ]);

      NFse.RegimeEspecialTributacao := StrToEnumerado( ok,sTributacao, ['T','M'], [ retNenhum, retMicroempresarioIndividual ]);


      //NFSe.Servico.Valores.ValorServicos          :=
      //NFSe.Servico.Valores.ValorDeducoes          :=
      NFSe.Servico.Valores.ValorPis               := Leitor.rCampo(tcDe2, 'ValorPIS');
      NFSe.Servico.Valores.ValorCofins            := Leitor.rCampo(tcDe2, 'ValorCOFINS');
      NFSe.Servico.Valores.ValorInss              := Leitor.rCampo(tcDe2, 'ValorINSS');
      NFSe.Servico.Valores.ValorIr                := Leitor.rCampo(tcDe2, 'ValorIR');
      NFSe.Servico.Valores.ValorCsll              := Leitor.rCampo(tcDe2, 'ValorCSLL');
      //NFSe.Servico.Valores.ValorIss               :=
      NFSe.Servico.Valores.AliquotaPIS            := Leitor.rCampo(tcDe2, 'AliquotaPIS');
      NFSe.Servico.Valores.AliquotaCOFINS         := Leitor.rCampo(tcDe2, 'AliquotaCOFINS');
      NFSe.Servico.Valores.AliquotaINSS           := Leitor.rCampo(tcDe2, 'AliquotaINSS');
      NFSe.Servico.Valores.AliquotaIR             := Leitor.rCampo(tcDe2, 'AliquotaIR');
      NFSe.Servico.Valores.AliquotaCSLL           := Leitor.rCampo(tcDe2, 'AliquotaCSLL');

      NFSe.OutrasInformacoes := Leitor.rCampo(tcStr, 'DescricaoRPS');

      NFSe.PrestadorServico.Contato.Telefone := Leitor.rCampo(tcStr, 'DDDPrestador') + Leitor.rCampo(tcStr, 'TelefonePrestador');
      NFSe.Tomador.Contato.Telefone          := Leitor.rCampo(tcStr, 'DDDTomador') + Leitor.rCampo(tcStr, 'TelefoneTomador');

      NFSE.MotivoCancelamento := Leitor.rCampo(tcStr, 'MotCancelamento');

      NFSe.IntermediarioServico.CpfCnpj := Leitor.rCampo(tcStr, 'CpfCnpjIntermediario');

  end; // fim Rps

 Result := True;
end;

function TNFSeR.LerRPS_Equiplano: Boolean;
var
  ok: Boolean;
  Item: Integer;
begin
  NFSe.IdentificacaoRps.Numero:= Leitor.rCampo(tcStr, 'nrRps');
  NFSe.IdentificacaoRps.Serie := Leitor.rCampo(tcStr, 'nrEmissorRps');
  NFSe.DataEmissao            := Leitor.rCampo(tcDatHor, 'dtEmissaoRps');
  NFSe.DataEmissaoRps         := Leitor.rCampo(tcDat, 'DataEmissao');
  NFSe.NaturezaOperacao       := StrToNaturezaOperacao(ok, Leitor.rCampo(tcStr, 'NaturezaOperacao'));

  NFSe.Servico.Valores.IssRetido       := StrToSituacaoTributaria(ok, Leitor.rCampo(tcStr, 'isIssRetido'));
  NFSe.Servico.Valores.ValorLiquidoNfse:= Leitor.rCampo(tcDe2, 'vlLiquidoRps');

  if (Leitor.rExtrai(2, 'tomador') <> '') then
    begin
      NFSe.Tomador.IdentificacaoTomador.CpfCnpj              := Leitor.rCampo(tcStr, 'nrDocumento');
      NFSe.Tomador.IdentificacaoTomador.DocTomadorEstrangeiro:= Leitor.rCampo(tcStr, 'dsDocumentoEstrangeiro');
      NFSe.Tomador.IdentificacaoTomador.InscricaoEstadual    := Leitor.rCampo(tcStr, 'nrInscricaoEstadual');
      NFSe.Tomador.RazaoSocial                               := Leitor.rCampo(tcStr, 'nmTomador');
      NFSe.Tomador.Contato.Email                             := Leitor.rCampo(tcStr, 'dsEmail');
      NFSe.Tomador.Endereco.Endereco                         := Leitor.rCampo(tcStr, 'dsEndereco');
      NFSe.Tomador.Endereco.Numero                           := Leitor.rCampo(tcStr, 'nrEndereco');
      NFSe.Tomador.Endereco.Complemento                      := Leitor.rCampo(tcStr, 'dsComplemento');
      NFSe.Tomador.Endereco.Bairro                           := Leitor.rCampo(tcStr, 'nmBairro');
      NFSe.Tomador.Endereco.CodigoMunicipio                  := Leitor.rCampo(tcStr, 'nrCidadeIbge');
      NFSe.Tomador.Endereco.UF                               := Leitor.rCampo(tcStr, 'nmUf');
      NFSe.Tomador.Endereco.xPais                            := Leitor.rCampo(tcStr, 'nmPais');
      NFSe.Tomador.Endereco.CEP                              := Leitor.rCampo(tcStr, 'nrCep');
      NFSe.Tomador.Contato.Telefone                          := Leitor.rCampo(tcStr, 'nrTelefone');
    end;

  if (Leitor.rExtrai(2, 'listaServicos') <> '') then
    begin
      NFSe.Servico.ItemListaServico            := Poem_Zeros( Leitor.rCampo(tcStr, 'nrServicoItem'), 2) +
                                                  Poem_Zeros( Leitor.rCampo(tcStr, 'nrServicoSubItem'), 2);

      Item := StrToIntDef(SomenteNumeros(Nfse.Servico.ItemListaServico), 0);
      if Item<100 then Item:=Item*100+1;

      NFSe.Servico.ItemListaServico := FormatFloat('0000', Item);
      NFSe.Servico.ItemListaServico := Copy(NFSe.Servico.ItemListaServico, 1, 2) + '.' +
                                       Copy(NFSe.Servico.ItemListaServico, 3, 2);

      if TabServicosExt
       then NFSe.Servico.xItemListaServico := NotaUtil.ObterDescricaoServico(SomenteNumeros(NFSe.Servico.ItemListaServico))
       else NFSe.Servico.xItemListaServico := CodigoToDesc(SomenteNumeros(NFSe.Servico.ItemListaServico));

      NFSe.Servico.Valores.ValorServicos       := Leitor.rCampo(tcDe2, 'vlServico');
      NFSe.Servico.Valores.Aliquota            := Leitor.rCampo(tcDe2, 'vlAliquota');
      NFSe.Servico.Valores.ValorDeducoes       := Leitor.rCampo(tcDe2, 'vlDeducao');
      NFSe.Servico.Valores.JustificativaDeducao:= Leitor.rCampo(tcStr, 'dsJustificativaDeducao');
      NFSe.Servico.Valores.BaseCalculo         := Leitor.rCampo(tcDe2, 'vlBaseCalculo');
      NFSe.Servico.Valores.ValorIss            := Leitor.rCampo(tcDe2, 'vlIssServico');
      NFSe.Servico.Discriminacao               := Leitor.rCampo(tcStr, 'dsDiscriminacaoServico');
    end;

  if (Leitor.rExtrai(2, 'retencoes') <> '') then
    begin
      NFSe.Servico.Valores.ValorCofins            := Leitor.rCampo(tcDe2, 'vlCofins');
      NFSe.Servico.Valores.ValorCsll              := Leitor.rCampo(tcDe2, 'vlCsll');
      NFSe.Servico.Valores.ValorInss              := Leitor.rCampo(tcDe2, 'vlInss');
      NFSe.Servico.Valores.ValorIr                := Leitor.rCampo(tcDe2, 'vlIrrf');
      NFSe.Servico.Valores.ValorPis               := Leitor.rCampo(tcDe2, 'vlPis');
      NFSe.Servico.Valores.ValorIssRetido         := Leitor.rCampo(tcDe2, 'vlIss');
      NFSe.Servico.Valores.AliquotaCofins         := Leitor.rCampo(tcDe2, 'vlAliquotaCofins');
      NFSe.Servico.Valores.AliquotaCsll           := Leitor.rCampo(tcDe2, 'vlAliquotaCsll');
      NFSe.Servico.Valores.AliquotaInss           := Leitor.rCampo(tcDe2, 'vlAliquotaInss');
      NFSe.Servico.Valores.AliquotaIr             := Leitor.rCampo(tcDe2, 'vlAliquotaIrrf');
      NFSe.Servico.Valores.AliquotaPis            := Leitor.rCampo(tcDe2, 'vlAliquotaPis');
    end;

  Result := True;
end;

function TNFSeR.LerRPS: Boolean;
var
 CM: String;
 Ok: Boolean;
begin
 if (Leitor.rExtrai(1, 'OrgaoGerador') <> '')
  then begin
   CM:= Leitor.rCampo(tcStr, 'CodigoMunicipio');
   FProvedor := StrToProvedor(Ok, CodCidadeToProvedor(StrToIntDef(CM, 0)));
  end
 else if (Leitor.rExtrai(1, 'Servico') <> '') //Adicionado porque estava pegando o CNPJ do Tomador para ConsultarNFSeporRps
  then begin
   CM:= Leitor.rCampo(tcStr, 'CodigoMunicipio');
   FProvedor := StrToProvedor(Ok, CodCidadeToProvedor(StrToIntDef(CM, 0)));
  end
 else FProvedor := proNenhum;

 if (Leitor.rExtrai(1, 'Rps') <> '') then
 begin
   case FProvedor of
    proAbaco,
    proBetha,
    proBHISS,
    proFISSLex,
    proGinfes,
    proGovBR,
    proISSCuritiba,
    proISSIntel,
    proISSNet,
    proNatal,
    proProdemge,
    proPronim,
    proPublica,
    proRecife,
    proRJ,
    proSimplISS,
    proSpeedGov,
    proThema,
    proTiplan,
    proWebISS: Result := LerRPS_ABRASF_V1;

    pro4R,
    proAgili,
    proCoplan,
    proDigifred,
    proFIntelISS,
    proFiorilli,
    proFreire,
    proGoiania,
    proGovDigital,
    proISSDigital,
    proISSe,
    proLink3,
    proMitra,
    proProdata,
    proPVH,
    proSaatri,
    proTecnos,
    ProVirtual,
    proVitoria: Result := LerRPS_ABRASF_V2;

    proIssDsf:  Result := LerRPS_IssDsf;

    proEquiplano: Result := LerRPS_Equiplano;
   end;
 end;

end;

////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////

function TNFSeR.LerNFSe_ABRASF_V1: Boolean;
var
 item: Integer;
 ok  : Boolean;
begin
  if (Leitor.rExtrai(3, 'IdentificacaoRps') <> '')
   then begin
    NFSe.IdentificacaoRps.Numero := Leitor.rCampo(tcStr, 'Numero');
    NFSe.IdentificacaoRps.Serie  := Leitor.rCampo(tcStr, 'Serie');
    NFSe.IdentificacaoRps.Tipo   := StrToTipoRPS(ok, Leitor.rCampo(tcStr, 'Tipo'));
    NFSe.InfID.ID                := SomenteNumeros(NFSe.IdentificacaoRps.Numero) + NFSe.IdentificacaoRps.Serie;
   end;

  if (Leitor.rExtrai(3, 'Servico') <> '')
   then begin
    NFSe.Servico.ItemListaServico := DFeUtil.LimpaNumero(Leitor.rCampo(tcStr, 'ItemListaServico'));

    Item := StrToIntDef(SomenteNumeros(Nfse.Servico.ItemListaServico), 0);
    if Item<100 then Item:=Item*100+1;

    NFSe.Servico.ItemListaServico := FormatFloat('0000', Item);
    NFSe.Servico.ItemListaServico := Copy(NFSe.Servico.ItemListaServico, 1, 2) + '.' +
                                     Copy(NFSe.Servico.ItemListaServico, 3, 2);

    if TabServicosExt
     then NFSe.Servico.xItemListaServico := NotaUtil.ObterDescricaoServico(SomenteNumeros(NFSe.Servico.ItemListaServico))
     else NFSe.Servico.xItemListaServico := CodigoToDesc(SomenteNumeros(NFSe.Servico.ItemListaServico));

    NFSe.Servico.CodigoCnae                := Leitor.rCampo(tcStr, 'CodigoCnae');
    NFSe.Servico.CodigoTributacaoMunicipio := Leitor.rCampo(tcStr, 'CodigoTributacaoMunicipio');
    NFSe.Servico.Discriminacao             := Leitor.rCampo(tcStr, 'Discriminacao');
    NFSe.Servico.Descricao                 := '';
    NFSe.Servico.CodigoMunicipio           := Leitor.rCampo(tcStr, 'CodigoMunicipio');

//    NFSe.Servico.ResponsavelRetencao       := StrToResponsavelRetencao(ok, Leitor.rCampo(tcStr, 'ResponsavelRetencao'));
//    NFSe.Servico.CodigoPais          := Leitor.rCampo(tcInt, 'CodigoPais');
//    NFSe.Servico.ExigibilidadeISS    := StrToExigibilidadeISS(ok, Leitor.rCampo(tcStr, 'ExigibilidadeISS'));
//    NFSe.Servico.MunicipioIncidencia := Leitor.rCampo(tcInt, 'MunicipioIncidencia');
//    if NFSe.Servico.MunicipioIncidencia =0
//     then NFSe.Servico.MunicipioIncidencia := Leitor.rCampo(tcInt, 'CodigoMunicipio');

    if (Leitor.rExtrai(4, 'Valores') <> '')
     then begin
      NFSe.Servico.Valores.ValorServicos          := Leitor.rCampo(tcDe2, 'ValorServicos');
      NFSe.Servico.Valores.ValorDeducoes          := Leitor.rCampo(tcDe2, 'ValorDeducoes');
      NFSe.Servico.Valores.ValorPis               := Leitor.rCampo(tcDe2, 'ValorPis');
      NFSe.Servico.Valores.ValorCofins            := Leitor.rCampo(tcDe2, 'ValorCofins');
      NFSe.Servico.Valores.ValorInss              := Leitor.rCampo(tcDe2, 'ValorInss');
      NFSe.Servico.Valores.ValorIr                := Leitor.rCampo(tcDe2, 'ValorIr');
      NFSe.Servico.Valores.ValorCsll              := Leitor.rCampo(tcDe2, 'ValorCsll');
      NFSe.Servico.Valores.IssRetido              := StrToSituacaoTributaria(ok, Leitor.rCampo(tcStr, 'IssRetido'));
      NFSe.Servico.Valores.ValorIss               := Leitor.rCampo(tcDe2, 'ValorIss');
      NFSe.Servico.Valores.OutrasRetencoes        := Leitor.rCampo(tcDe2, 'OutrasRetencoes');
      NFSe.Servico.Valores.BaseCalculo            := Leitor.rCampo(tcDe2, 'BaseCalculo');
      NFSe.Servico.Valores.Aliquota               := Leitor.rCampo(tcDe3, 'Aliquota');
      NFSe.Servico.Valores.ValorLiquidoNfse       := Leitor.rCampo(tcDe2, 'ValorLiquidoNfse');
      NFSe.Servico.Valores.ValorIssRetido         := Leitor.rCampo(tcDe2, 'ValorIssRetido');
      NFSe.Servico.Valores.DescontoCondicionado   := Leitor.rCampo(tcDe2, 'DescontoCondicionado');
      NFSe.Servico.Valores.DescontoIncondicionado := Leitor.rCampo(tcDe2, 'DescontoIncondicionado');
     end;

   end; // fim serviço

  if Leitor.rExtrai(3, 'PrestadorServico') <> ''
   then begin
    NFSe.PrestadorServico.RazaoSocial  := Leitor.rCampo(tcStr, 'RazaoSocial');
    NFSe.PrestadorServico.NomeFantasia := Leitor.rCampo(tcStr, 'NomeFantasia');

    NFSe.PrestadorServico.Endereco.Endereco := Leitor.rCampo(tcStr, 'Endereco');
    if Copy(NFSe.PrestadorServico.Endereco.Endereco, 1, 10) = '<Endereco>'
     then NFSe.PrestadorServico.Endereco.Endereco := Copy(NFSe.PrestadorServico.Endereco.Endereco, 11, 125);

    NFSe.PrestadorServico.Endereco.Numero      := Leitor.rCampo(tcStr, 'Numero');
    NFSe.PrestadorServico.Endereco.Complemento := Leitor.rCampo(tcStr, 'Complemento');
    NFSe.PrestadorServico.Endereco.Bairro      := Leitor.rCampo(tcStr, 'Bairro');

    if VersaoXML = '1'
     then begin
      NFSe.PrestadorServico.Endereco.CodigoMunicipio := Leitor.rCampo(tcStr, 'Cidade');
      NFSe.PrestadorServico.Endereco.UF              := Leitor.rCampo(tcStr, 'Estado');
     end
     else begin
      NFSe.PrestadorServico.Endereco.CodigoMunicipio := Leitor.rCampo(tcStr, 'CodigoMunicipio');
      NFSe.PrestadorServico.Endereco.UF              := Leitor.rCampo(tcStr, 'Uf');
     end;

//    NFSe.PrestadorServico.Endereco.CodigoPais := Leitor.rCampo(tcInt, 'CodigoPais');
    NFSe.PrestadorServico.Endereco.CEP        := Leitor.rCampo(tcStr, 'Cep');

    if length(NFSe.PrestadorServico.Endereco.CodigoMunicipio)<7
     then NFSe.PrestadorServico.Endereco.CodigoMunicipio := Copy(NFSe.PrestadorServico.Endereco.CodigoMunicipio, 1, 2) +
          FormatFloat('00000', StrToIntDef(Copy(NFSe.PrestadorServico.Endereco.CodigoMunicipio, 3, 5), 0));

    NFSe.PrestadorServico.Endereco.xMunicipio := CodCidadeToCidade(StrToIntDef(NFSe.PrestadorServico.Endereco.CodigoMunicipio, 0));

    if (Leitor.rExtrai(4, 'IdentificacaoPrestador') <> '')
     then begin
      NFSe.PrestadorServico.IdentificacaoPrestador.InscricaoMunicipal := Leitor.rCampo(tcStr, 'InscricaoMunicipal');

      if VersaoXML = '1'
       then begin
        if Leitor.rExtrai(5, 'CpfCnpj') <> ''
         then begin
          NFSe.PrestadorServico.IdentificacaoPrestador.Cnpj := Leitor.rCampo(tcStr, 'Cpf');
           if NFSe.PrestadorServico.IdentificacaoPrestador.Cnpj = ''
            then NFSe.PrestadorServico.IdentificacaoPrestador.Cnpj := Leitor.rCampo(tcStr, 'Cnpj');
         end;
       end
       else begin
        NFSe.PrestadorServico.IdentificacaoPrestador.Cnpj := Leitor.rCampo(tcStr, 'Cnpj');
       end;
      end;

    if Leitor.rExtrai(4, 'Contato') <> ''
     then begin
      NFSe.PrestadorServico.Contato.Telefone := Leitor.rCampo(tcStr, 'Telefone');
      NFSe.PrestadorServico.Contato.Email    := Leitor.rCampo(tcStr, 'Email');
     end;

   end; // fim PrestadorServico

  if Leitor.rExtrai(3, 'TomadorServico') <> ''
   then begin
    NFSe.Tomador.RazaoSocial := Leitor.rCampo(tcStr, 'RazaoSocial');

    NFSe.Tomador.Endereco.Endereco := Leitor.rCampo(tcStr, 'Endereco');
    if Copy(NFSe.Tomador.Endereco.Endereco, 1, 10) = '<Endereco>'
     then NFSe.Tomador.Endereco.Endereco := Copy(NFSe.Tomador.Endereco.Endereco, 11, 125);

    NFSe.Tomador.Endereco.Numero      := Leitor.rCampo(tcStr, 'Numero');
    NFSe.Tomador.Endereco.Complemento := Leitor.rCampo(tcStr, 'Complemento');
    NFSe.Tomador.Endereco.Bairro      := Leitor.rCampo(tcStr, 'Bairro');

    if VersaoXML = '1'
     then begin
      NFSe.Tomador.Endereco.CodigoMunicipio := Leitor.rCampo(tcStr, 'Cidade');
      NFSe.Tomador.Endereco.UF              := Leitor.rCampo(tcStr, 'Estado');
     end
     else begin
      NFSe.Tomador.Endereco.CodigoMunicipio := Leitor.rCampo(tcStr, 'CodigoMunicipio');
      NFSe.Tomador.Endereco.UF              := Leitor.rCampo(tcStr, 'Uf');
     end;

    NFSe.Tomador.Endereco.CEP := Leitor.rCampo(tcStr, 'Cep');

    if length(NFSe.Tomador.Endereco.CodigoMunicipio)<7
      then NFSe.Tomador.Endereco.CodigoMunicipio := Copy(NFSe.Tomador.Endereco.CodigoMunicipio, 1, 2) +
           FormatFloat('00000', StrToIntDef(Copy(NFSe.Tomador.Endereco.CodigoMunicipio, 3, 5), 0));

    if NFSe.Tomador.Endereco.UF = ''
     then NFSe.Tomador.Endereco.UF := NFSe.PrestadorServico.Endereco.UF;

     NFSe.Tomador.Endereco.xMunicipio := CodCidadeToCidade(StrToIntDef(NFSe.Tomador.Endereco.CodigoMunicipio, 0));

    if Leitor.rExtrai(4, 'IdentificacaoTomador') <> ''
     then begin
      NFSe.Tomador.IdentificacaoTomador.InscricaoMunicipal := Leitor.rCampo(tcStr, 'InscricaoMunicipal');
      if Leitor.rExtrai(5, 'CpfCnpj') <> ''
       then begin
        if Leitor.rCampo(tcStr, 'Cpf')<>''
         then NFSe.Tomador.IdentificacaoTomador.CpfCnpj := Leitor.rCampo(tcStr, 'Cpf')
         else NFSe.Tomador.IdentificacaoTomador.CpfCnpj := Leitor.rCampo(tcStr, 'Cnpj');
       end;
      end;

    if Leitor.rExtrai(4, 'Contato') <> ''
     then begin
      NFSe.Tomador.Contato.Telefone := Leitor.rCampo(tcStr, 'Telefone');
      NFSe.Tomador.Contato.Email    := Leitor.rCampo(tcStr, 'Email');
     end;
   end;

  if Leitor.rExtrai(3, 'IntermediarioServico') <> ''
   then begin
    NFSe.IntermediarioServico.RazaoSocial        := Leitor.rCampo(tcStr, 'RazaoSocial');
    NFSe.IntermediarioServico.InscricaoMunicipal := Leitor.rCampo(tcStr, 'InscricaoMunicipal');
    if Leitor.rExtrai(4, 'CpfCnpj') <> ''
     then begin
      if Leitor.rCampo(tcStr, 'Cpf')<>''
       then NFSe.IntermediarioServico.CpfCnpj := Leitor.rCampo(tcStr, 'Cpf')
       else NFSe.IntermediarioServico.CpfCnpj := Leitor.rCampo(tcStr, 'Cnpj');
     end;
   end;

  if Leitor.rExtrai(3, 'OrgaoGerador') <> ''
   then begin
    NFSe.OrgaoGerador.CodigoMunicipio := Leitor.rCampo(tcStr, 'CodigoMunicipio');
    NFSe.OrgaoGerador.Uf              := Leitor.rCampo(tcStr, 'Uf');
   end; // fim OrgaoGerador

  if Leitor.rExtrai(3, 'ConstrucaoCivil') <> ''
   then begin
    NFSe.ConstrucaoCivil.CodigoObra := Leitor.rCampo(tcStr, 'CodigoObra');
    NFSe.ConstrucaoCivil.Art        := Leitor.rCampo(tcStr, 'Art');
   end;

 Result := True;
end;

function TNFSeR.LerNFSe_ABRASF_V2: Boolean;
var
 item: Integer;
 ok  : Boolean;
begin
 if Leitor.rExtrai(3, 'ValoresNfse') <> ''
  then begin
   NFSe.Servico.Valores.BaseCalculo      := Leitor.rCampo(tcDe2, 'BaseCalculo');
   NFSe.Servico.Valores.Aliquota         := Leitor.rCampo(tcDe3, 'Aliquota');
   NFSe.Servico.Valores.ValorIss         := Leitor.rCampo(tcDe2, 'ValorIss');
   NFSe.Servico.Valores.ValorLiquidoNfse := Leitor.rCampo(tcDe2, 'ValorLiquidoNfse');
  end; // fim ValoresNfse

  if Leitor.rExtrai(3, 'PrestadorServico') <> ''
   then begin
    NFSe.PrestadorServico.RazaoSocial  := Leitor.rCampo(tcStr, 'RazaoSocial');
    NFSe.PrestadorServico.NomeFantasia := Leitor.rCampo(tcStr, 'NomeFantasia');

    NFSe.PrestadorServico.Endereco.Endereco := Leitor.rCampo(tcStr, 'Endereco');
    if Copy(NFSe.PrestadorServico.Endereco.Endereco, 1, 10) = '<Endereco>'
     then NFSe.PrestadorServico.Endereco.Endereco := Copy(NFSe.PrestadorServico.Endereco.Endereco, 11, 125);

    NFSe.PrestadorServico.Endereco.Numero      := Leitor.rCampo(tcStr, 'Numero');
    NFSe.PrestadorServico.Endereco.Complemento := Leitor.rCampo(tcStr, 'Complemento');
    NFSe.PrestadorServico.Endereco.Bairro      := Leitor.rCampo(tcStr, 'Bairro');

    if VersaoXML = '1'
     then begin
      NFSe.PrestadorServico.Endereco.CodigoMunicipio := Leitor.rCampo(tcStr, 'Cidade');
      NFSe.PrestadorServico.Endereco.UF              := Leitor.rCampo(tcStr, 'Estado');
     end
     else begin
      NFSe.PrestadorServico.Endereco.CodigoMunicipio := Leitor.rCampo(tcStr, 'CodigoMunicipio');
      NFSe.PrestadorServico.Endereco.UF              := Leitor.rCampo(tcStr, 'Uf');
     end;

    NFSe.PrestadorServico.Endereco.CodigoPais := Leitor.rCampo(tcInt, 'CodigoPais');
    NFSe.PrestadorServico.Endereco.CEP        := Leitor.rCampo(tcStr, 'Cep');

    if length(NFSe.PrestadorServico.Endereco.CodigoMunicipio)<7
     then NFSe.PrestadorServico.Endereco.CodigoMunicipio := Copy(NFSe.PrestadorServico.Endereco.CodigoMunicipio, 1, 2) +
          FormatFloat('00000', StrToIntDef(Copy(NFSe.PrestadorServico.Endereco.CodigoMunicipio, 3, 5), 0));

    NFSe.PrestadorServico.Endereco.xMunicipio := CodCidadeToCidade(StrToIntDef(NFSe.PrestadorServico.Endereco.CodigoMunicipio, 0));

    if (Leitor.rExtrai(4, 'IdentificacaoPrestador') <> '')
     then begin
      NFSe.PrestadorServico.IdentificacaoPrestador.InscricaoMunicipal := Leitor.rCampo(tcStr, 'InscricaoMunicipal');

      if (VersaoXML = '1') or (FProvedor = proFiorilli)
       then begin
        if Leitor.rExtrai(5, 'CpfCnpj') <> ''
         then begin
          NFSe.PrestadorServico.IdentificacaoPrestador.Cnpj := Leitor.rCampo(tcStr, 'Cpf');
           if NFSe.PrestadorServico.IdentificacaoPrestador.Cnpj = ''
            then NFSe.PrestadorServico.IdentificacaoPrestador.Cnpj := Leitor.rCampo(tcStr, 'Cnpj');
         end;
       end
       else begin
        NFSe.PrestadorServico.IdentificacaoPrestador.Cnpj := Leitor.rCampo(tcStr, 'Cnpj');
       end;
      end;

    if Leitor.rExtrai(4, 'Contato') <> ''
     then begin
      NFSe.PrestadorServico.Contato.Telefone := Leitor.rCampo(tcStr, 'Telefone');
      NFSe.PrestadorServico.Contato.Email    := Leitor.rCampo(tcStr, 'Email');
     end;

   end; // fim PrestadorServico

 if Leitor.rExtrai(3, 'EnderecoPrestadorServico') <> ''
  then begin
   NFSe.PrestadorServico.Endereco.Endereco := Leitor.rCampo(tcStr, 'Endereco');
   if Copy(NFSe.PrestadorServico.Endereco.Endereco, 1, 10) = '<Endereco>'
    then NFSe.PrestadorServico.Endereco.Endereco := Copy(NFSe.PrestadorServico.Endereco.Endereco, 11, 125);

   NFSe.PrestadorServico.Endereco.Numero      := Leitor.rCampo(tcStr, 'Numero');
   NFSe.PrestadorServico.Endereco.Complemento := Leitor.rCampo(tcStr, 'Complemento');
   NFSe.PrestadorServico.Endereco.Bairro      := Leitor.rCampo(tcStr, 'Bairro');

   if VersaoXML = '1'
    then begin
     NFSe.PrestadorServico.Endereco.CodigoMunicipio := Leitor.rCampo(tcStr, 'Cidade');
     NFSe.PrestadorServico.Endereco.UF              := Leitor.rCampo(tcStr, 'Estado');
    end
    else begin
     NFSe.PrestadorServico.Endereco.CodigoMunicipio := Leitor.rCampo(tcStr, 'CodigoMunicipio');
     NFSe.PrestadorServico.Endereco.UF              := Leitor.rCampo(tcStr, 'Uf');
    end;

   NFSe.PrestadorServico.Endereco.CodigoPais := Leitor.rCampo(tcInt, 'CodigoPais');
   NFSe.PrestadorServico.Endereco.CEP        := Leitor.rCampo(tcStr, 'Cep');

   if length(NFSe.PrestadorServico.Endereco.CodigoMunicipio)<7
    then NFSe.PrestadorServico.Endereco.CodigoMunicipio := Copy(NFSe.PrestadorServico.Endereco.CodigoMunicipio, 1, 2) +
           FormatFloat('00000', StrToIntDef(Copy(NFSe.PrestadorServico.Endereco.CodigoMunicipio, 3, 5), 0));

   NFSe.PrestadorServico.Endereco.xMunicipio := CodCidadeToCidade(StrToIntDef(NFSe.PrestadorServico.Endereco.CodigoMunicipio, 0));

  end; // fim EnderecoPrestadorServico

 if Leitor.rExtrai(3, 'OrgaoGerador') <> ''
  then begin
   NFSe.OrgaoGerador.CodigoMunicipio := Leitor.rCampo(tcStr, 'CodigoMunicipio');
   NFSe.OrgaoGerador.Uf              := Leitor.rCampo(tcStr, 'Uf');
  end; // fim OrgaoGerador

 if (Leitor.rExtrai(3, 'InfDeclaracaoPrestacaoServico') <> '') or (Leitor.rExtrai(3, 'DeclaracaoPrestacaoServico') <> '')
  then begin
   NFSe.Competencia              := Leitor.rCampo(tcStr, 'Competencia');
   NFSe.RegimeEspecialTributacao := StrToRegimeEspecialTributacao(ok, Leitor.rCampo(tcStr, 'RegimeEspecialTributacao'));
   NFSe.OptanteSimplesNacional   := StrToSimNao(ok, Leitor.rCampo(tcStr, 'OptanteSimplesNacional'));
   NFSe.IncentivadorCultural     := StrToSimNao(ok, Leitor.rCampo(tcStr, 'IncentivoFiscal'));

   if (Leitor.rExtrai(4, 'Rps') <> '')
    then begin
     NFSe.DataEmissaoRps := Leitor.rCampo(tcDat, 'DataEmissao');
     NFSe.Status         := StrToStatusRPS(ok, Leitor.rCampo(tcStr, 'Status'));

     if (Leitor.rExtrai(5, 'IdentificacaoRps') <> '')
      then begin
       NFSe.IdentificacaoRps.Numero := Leitor.rCampo(tcStr, 'Numero');
       NFSe.IdentificacaoRps.Serie  := Leitor.rCampo(tcStr, 'Serie');
       NFSe.IdentificacaoRps.Tipo   := StrToTipoRPS(ok, Leitor.rCampo(tcStr, 'Tipo'));
       NFSe.InfID.ID                := SomenteNumeros(NFSe.IdentificacaoRps.Numero) + NFSe.IdentificacaoRps.Serie;
      end;

     if (Leitor.rExtrai(5, 'RpsSubstituido') <> '')
      then begin
       NFSe.RpsSubstituido.Numero := Leitor.rCampo(tcStr, 'Numero');
       NFSe.RpsSubstituido.Serie  := Leitor.rCampo(tcStr, 'Serie');
       NFSe.RpsSubstituido.Tipo   := StrToTipoRPS(ok, Leitor.rCampo(tcStr, 'Tipo'));
      end;
    end
    else begin
     NFSe.DataEmissaoRps := Leitor.rCampo(tcDat, 'DataEmissao');
     NFSe.Status         := StrToStatusRPS(ok, Leitor.rCampo(tcStr, 'Status'));

     if (Leitor.rExtrai(4, 'IdentificacaoRps') <> '')
      then begin
       NFSe.IdentificacaoRps.Numero := Leitor.rCampo(tcStr, 'Numero');
       NFSe.IdentificacaoRps.Serie  := Leitor.rCampo(tcStr, 'Serie');
       NFSe.IdentificacaoRps.Tipo   := StrToTipoRPS(ok, Leitor.rCampo(tcStr, 'Tipo'));
       NFSe.InfID.ID                := SomenteNumeros(NFSe.IdentificacaoRps.Numero) + NFSe.IdentificacaoRps.Serie;
      end;

     if (Leitor.rExtrai(4, 'RpsSubstituido') <> '')
      then begin
       NFSe.RpsSubstituido.Numero := Leitor.rCampo(tcStr, 'Numero');
       NFSe.RpsSubstituido.Serie  := Leitor.rCampo(tcStr, 'Serie');
       NFSe.RpsSubstituido.Tipo   := StrToTipoRPS(ok, Leitor.rCampo(tcStr, 'Tipo'));
      end;
    end;

   if (Leitor.rExtrai(4, 'Servico') <> '')
    then begin
     NFSe.Servico.Valores.IssRetido   := StrToSituacaoTributaria(ok, Leitor.rCampo(tcStr, 'IssRetido'));
     NFSe.Servico.ResponsavelRetencao := StrToResponsavelRetencao(ok, Leitor.rCampo(tcStr, 'ResponsavelRetencao'));
     NFSe.Servico.ItemListaServico    := DFeUtil.LimpaNumero(Leitor.rCampo(tcStr, 'ItemListaServico'));

     Item := StrToIntDef(SomenteNumeros(Nfse.Servico.ItemListaServico), 0);
     if Item<100 then Item:=Item*100+1;

     NFSe.Servico.ItemListaServico := FormatFloat('0000', Item);
     NFSe.Servico.ItemListaServico := Copy(NFSe.Servico.ItemListaServico, 1, 2) + '.' +
                                      Copy(NFSe.Servico.ItemListaServico, 3, 2);

     if TabServicosExt
      then NFSe.Servico.xItemListaServico := NotaUtil.ObterDescricaoServico(SomenteNumeros(NFSe.Servico.ItemListaServico))
      else NFSe.Servico.xItemListaServico := CodigoToDesc(SomenteNumeros(NFSe.Servico.ItemListaServico));

     NFSe.Servico.CodigoCnae                := Leitor.rCampo(tcStr, 'CodigoCnae');
     NFSe.Servico.CodigoTributacaoMunicipio := Leitor.rCampo(tcStr, 'CodigoTributacaoMunicipio');
     NFSe.Servico.Discriminacao             := Leitor.rCampo(tcStr, 'Discriminacao');
     NFSe.Servico.Descricao                 := '';
     NFSe.Servico.CodigoMunicipio           := Leitor.rCampo(tcStr, 'CodigoMunicipio');
     NFSe.Servico.CodigoPais                := Leitor.rCampo(tcInt, 'CodigoPais');
     NFSe.Servico.ExigibilidadeISS          := StrToExigibilidadeISS(ok, Leitor.rCampo(tcStr, 'ExigibilidadeISS'));
     NFSe.Servico.MunicipioIncidencia       := Leitor.rCampo(tcInt, 'MunicipioIncidencia');
     if NFSe.Servico.MunicipioIncidencia = 0
      then NFSe.Servico.MunicipioIncidencia := Leitor.rCampo(tcInt, 'CodigoMunicipio');

     if (Leitor.rExtrai(5, 'Valores') <> '')
      then begin
        NFSe.Servico.Valores.ValorServicos          := Leitor.rCampo(tcDe2, 'ValorServicos');
        NFSe.Servico.Valores.ValorDeducoes          := Leitor.rCampo(tcDe2, 'ValorDeducoes');
        NFSe.Servico.Valores.ValorPis               := Leitor.rCampo(tcDe2, 'ValorPis');
        NFSe.Servico.Valores.ValorCofins            := Leitor.rCampo(tcDe2, 'ValorCofins');
        NFSe.Servico.Valores.ValorInss              := Leitor.rCampo(tcDe2, 'ValorInss');
        NFSe.Servico.Valores.ValorIr                := Leitor.rCampo(tcDe2, 'ValorIr');
        NFSe.Servico.Valores.ValorCsll              := Leitor.rCampo(tcDe2, 'ValorCsll');
        NFSe.Servico.Valores.OutrasRetencoes        := Leitor.rCampo(tcDe2, 'OutrasRetencoes');
        NFSe.Servico.Valores.ValorIss               := Leitor.rCampo(tcDe2, 'ValorIss');
        NFSe.Servico.Valores.BaseCalculo            := Leitor.rCampo(tcDe2, 'BaseCalculo');
        NFSe.Servico.Valores.Aliquota               := Leitor.rCampo(tcDe3, 'Aliquota');
        NFSe.Servico.Valores.ValorIssRetido         := Leitor.rCampo(tcDe2, 'ValorIssRetido');
        NFSe.Servico.Valores.DescontoCondicionado   := Leitor.rCampo(tcDe2, 'DescontoCondicionado');
        NFSe.Servico.Valores.DescontoIncondicionado := Leitor.rCampo(tcDe2, 'DescontoIncondicionado');
      end;
    end; // fim serviço

   if (Leitor.rExtrai(4, 'Prestador') <> '')
    then begin
     NFSe.Prestador.InscricaoMunicipal := Leitor.rCampo(tcStr, 'InscricaoMunicipal');

     if (VersaoXML = '1') or (FProvedor in [proFiorilli, proGoiania])
      then begin
       if Leitor.rExtrai(5, 'CpfCnpj') <> ''
        then begin
          NFSe.PrestadorServico.IdentificacaoPrestador.Cnpj := Leitor.rCampo(tcStr, 'Cpf');
          if NFSe.PrestadorServico.IdentificacaoPrestador.Cnpj = ''
           then NFSe.PrestadorServico.IdentificacaoPrestador.Cnpj := Leitor.rCampo(tcStr, 'Cnpj');
        end;
      end
      else begin
       NFSe.Prestador.Cnpj := Leitor.rCampo(tcStr, 'Cnpj');
      end;
    end; // fim Prestador

   if (Leitor.rExtrai(4, 'TomadorServico') <> '') or (Leitor.rExtrai(4, 'Tomador') <> '')
    then begin
     NFSe.Tomador.RazaoSocial := Leitor.rCampo(tcStr, 'RazaoSocial');

     NFSe.Tomador.Endereco.Endereco := Leitor.rCampo(tcStr, 'Endereco');
     if Copy(NFSe.Tomador.Endereco.Endereco, 1, 10) = '<Endereco>'
      then NFSe.Tomador.Endereco.Endereco := Copy(NFSe.Tomador.Endereco.Endereco, 11, 125);

     NFSe.Tomador.Endereco.Numero      := Leitor.rCampo(tcStr, 'Numero');
     NFSe.Tomador.Endereco.Complemento := Leitor.rCampo(tcStr, 'Complemento');
     NFSe.Tomador.Endereco.Bairro      := Leitor.rCampo(tcStr, 'Bairro');

     if VersaoXML = '1'
      then begin
       NFSe.Tomador.Endereco.CodigoMunicipio := Leitor.rCampo(tcStr, 'Cidade');
       NFSe.Tomador.Endereco.UF              := Leitor.rCampo(tcStr, 'Estado');
      end
      else begin
       NFSe.Tomador.Endereco.CodigoMunicipio := Leitor.rCampo(tcStr, 'CodigoMunicipio');
       NFSe.Tomador.Endereco.UF              := Leitor.rCampo(tcStr, 'Uf');
      end;

     NFSe.Tomador.Endereco.CEP := Leitor.rCampo(tcStr, 'Cep');

     if length(NFSe.Tomador.Endereco.CodigoMunicipio) < 7
      then NFSe.Tomador.Endereco.CodigoMunicipio := Copy(NFSe.Tomador.Endereco.CodigoMunicipio, 1, 2) +
        FormatFloat('00000', StrToIntDef(Copy(NFSe.Tomador.Endereco.CodigoMunicipio, 3, 5), 0));

     if NFSe.Tomador.Endereco.UF = ''
      then NFSe.Tomador.Endereco.UF := NFSe.PrestadorServico.Endereco.UF;

     NFSe.Tomador.Endereco.xMunicipio := CodCidadeToCidade(StrToIntDef(NFSe.Tomador.Endereco.CodigoMunicipio, 0));

     if (Leitor.rExtrai(5, 'IdentificacaoTomador') <> '')
      then begin
       NFSe.Tomador.IdentificacaoTomador.InscricaoMunicipal := Leitor.rCampo(tcStr, 'InscricaoMunicipal');

       if Leitor.rExtrai(6, 'CpfCnpj') <> ''
        then begin
         if Leitor.rCampo(tcStr, 'Cpf')<>''
          then NFSe.Tomador.IdentificacaoTomador.CpfCnpj := Leitor.rCampo(tcStr, 'Cpf')
          else NFSe.Tomador.IdentificacaoTomador.CpfCnpj := Leitor.rCampo(tcStr, 'Cnpj');
        end;
      end;

     if (Leitor.rExtrai(5, 'Contato') <> '')
      then begin
       NFSe.Tomador.Contato.Telefone := Leitor.rCampo(tcStr, 'Telefone');
       NFSe.Tomador.Contato.Email    := Leitor.rCampo(tcStr, 'Email');
      end;
    end; // fim Tomador
  end; // fim InfDeclaracaoPrestacaoServico

 Result := True;
end;

function TNFSeR.LerNFSe_IssDSF: Boolean;
var ok  : Boolean;
    Item, posI, count: integer;
    sOperacao, sTributacao: string;
    strItem: ansiString;
    leitorItem : TLeitor;
begin
 //provedorIssDSF
 if Pos('<Notas>', Leitor.Arquivo) > 0
  then begin
   VersaoXML := '1'; // para este provedor usar padrão "1".

   FNFSe.Numero            := Leitor.rCampo(tcStr, 'NumeroNota');
   FNFSe.CodigoVerificacao := Leitor.rCampo(tcStr, 'CodigoVerificacao');

   FNFSe.DataEmissaoRps    := Leitor.rCampo(tcDatHor, 'DataEmissaoRPS');
   FNFSe.DataEmissao       := Leitor.rCampo(tcDatHor, 'DataProcessamento');
   FNFSe.Status            := StrToEnumerado(ok, Leitor.rCampo(tcStr, 'SituacaoRPS'),['N','C'],[srNormal, srCancelado]);

   NFSe.IdentificacaoRps.Numero := Leitor.rCampo(tcStr, 'NumeroRPS');
   NFSe.IdentificacaoRps.Serie  := Leitor.rCampo(tcStr, 'SerieRPS');
   NFSe.IdentificacaoRps.Tipo   := trRPS; //StrToTipoRPS(ok, leitorAux.rCampo(tcStr, 'Tipo'));
   NFSe.InfID.ID                := SomenteNumeros(NFSe.IdentificacaoRps.Numero);// + NFSe.IdentificacaoRps.Serie;
   NFSe.SeriePrestacao          := Leitor.rCampo(tcStr, 'SeriePrestacao');

   NFSe.Tomador.IdentificacaoTomador.InscricaoMunicipal := Leitor.rCampo(tcStr, 'InscricaoMunicipalTomador');
   NFSe.Tomador.IdentificacaoTomador.CpfCnpj := Leitor.rCampo(tcStr, 'CPFCNPJTomador');
   NFSe.Tomador.RazaoSocial              := Leitor.rCampo(tcStr, 'RazaoSocialTomador');
   NFSe.Tomador.Endereco.TipoLogradouro  := Leitor.rCampo(tcStr, 'TipoLogradouroTomador');
   NFSe.Tomador.Endereco.Endereco        := Leitor.rCampo(tcStr, 'LogradouroTomador');
   NFSe.Tomador.Endereco.Numero          := Leitor.rCampo(tcStr, 'NumeroEnderecoTomador');
   NFSe.Tomador.Endereco.Complemento     := Leitor.rCampo(tcStr, 'ComplementoEnderecoTomador');
   NFSe.Tomador.Endereco.TipoBairro      := Leitor.rCampo(tcStr, 'TipoBairroTomador');
   NFSe.Tomador.Endereco.Bairro          := Leitor.rCampo(tcStr, 'BairroTomador');
   NFSe.Tomador.Endereco.CodigoMunicipio := CodSiafiToCodCidade( Leitor.rCampo(tcStr, 'CidadeTomador')) ;
   NFSe.Tomador.Endereco.CEP             := Leitor.rCampo(tcStr, 'CEPTomador');
   NFSe.Tomador.Contato.Email := Leitor.rCampo(tcStr, 'EmailTomador');

   NFSe.Servico.CodigoCnae := Leitor.rCampo(tcStr, 'CodigoAtividade');
   NFSe.Servico.Valores.Aliquota := Leitor.rCampo(tcDe3, 'AliquotaAtividade');

   NFSe.Servico.Valores.IssRetido := StrToEnumerado( ok, Leitor.rCampo(tcStr, 'TipoRecolhimento'),
                              ['A','R'], [ stNormal, stRetencao{, stSubstituicao}]);

   NFSe.Servico.CodigoMunicipio := CodSiafiToCodCidade( Leitor.rCampo(tcStr, 'MunicipioPrestacao'));

   sOperacao   := AnsiUpperCase(Leitor.rCampo(tcStr, 'Operacao'));
   sTributacao := AnsiUpperCase(Leitor.rCampo(tcStr, 'Tributacao'));

   if sOperacao[1] in ['A', 'B'] then begin
      if NFSe.Servico.CodigoMunicipio = NFSe.PrestadorServico.Endereco.CodigoMunicipio then
         NFSe.NaturezaOperacao := noTributacaoNoMunicipio      // ainda estamos
      else                                                    // em análise sobre
         NFSe.NaturezaOperacao := noTributacaoForaMunicipio;   // este ponto
   end
   else if (sOperacao = 'C') and (sTributacao = 'C') then begin
      NFSe.NaturezaOperacao := noIsencao;
   end
   else if (sOperacao = 'C') and (sTributacao = 'F') then begin
      NFSe.NaturezaOperacao := noImune;
   end
   else if (sOperacao = 'A') and (sTributacao = 'N') then begin
      NFSe.NaturezaOperacao := noNaoIncidencia;
   end;

   NFSe.NaturezaOperacao := StrToEnumerado( ok,sTributacao, ['T','K'], [ NFSe.NaturezaOperacao, noSuspensaDecisaoJudicial ]);

   NFSe.OptanteSimplesNacional := StrToEnumerado( ok,sTributacao, ['T','H'], [ snNao, snSim ]);

   NFSe.DeducaoMateriais := StrToEnumerado( ok,sOperacao, ['A','B'], [ snNao, snSim ]);

   NFse.RegimeEspecialTributacao := StrToEnumerado( ok,sTributacao, ['T','M'], [ retNenhum, retMicroempresarioIndividual ]);

   NFSe.Servico.Valores.ValorPis        := Leitor.rCampo(tcDe2, 'ValorPIS');
   NFSe.Servico.Valores.ValorCofins     := Leitor.rCampo(tcDe2, 'ValorCOFINS');
   NFSe.Servico.Valores.ValorInss       := Leitor.rCampo(tcDe2, 'ValorINSS');
   NFSe.Servico.Valores.ValorIr         := Leitor.rCampo(tcDe2, 'ValorIR');
   NFSe.Servico.Valores.ValorCsll       := Leitor.rCampo(tcDe2, 'ValorCSLL');
   NFSe.Servico.Valores.AliquotaPIS     := Leitor.rCampo(tcDe2, 'AliquotaPIS');
   NFSe.Servico.Valores.AliquotaCOFINS  := Leitor.rCampo(tcDe2, 'AliquotaCOFINS');
   NFSe.Servico.Valores.AliquotaINSS    := Leitor.rCampo(tcDe2, 'AliquotaINSS');
   NFSe.Servico.Valores.AliquotaIR      := Leitor.rCampo(tcDe2, 'AliquotaIR');
   NFSe.Servico.Valores.AliquotaCSLL    := Leitor.rCampo(tcDe2, 'AliquotaCSLL');

   NFSe.OutrasInformacoes := Leitor.rCampo(tcStr, 'DescricaoRPS');

   NFSe.PrestadorServico.Contato.Telefone := Leitor.rCampo(tcStr, 'DDDPrestador') + Leitor.rCampo(tcStr, 'TelefonePrestador');
   NFSe.Tomador.Contato.Telefone          := Leitor.rCampo(tcStr, 'DDDTomador') + Leitor.rCampo(tcStr, 'TelefoneTomador');

   NFSE.MotivoCancelamento := Leitor.rCampo(tcStr, 'MotCancelamento');

   NFSe.IntermediarioServico.CpfCnpj := Leitor.rCampo(tcStr, 'CPFCNPJIntermediario');

   if (Leitor.rExtrai(2, 'Deducoes') <> '') then
   begin
      strItem := Leitor.rExtrai(2, 'Deducoes');
      if (strItem <> '') then
      begin
         Item := 0 ;
         posI := pos('<Deducao>', strItem);

         while ( posI > 0 ) do begin
            count := pos('</Deducao>', strItem) + 14;

            FNfse.Servico.Deducao.Add;
            inc(Item);

            leitorItem := TLeitor.Create;
            leitorItem.Arquivo := copy(strItem, PosI, count);
            leitorItem.Grupo := leitorItem.Arquivo;

            FNfse.Servico.Deducao[Item].DeducaoPor  :=
               StrToEnumerado( ok,leitorItem.rCampo(tcStr, 'DeducaoPor'),
                               ['','Percentual','Valor'],
                               [ dpNenhum,dpPercentual, dpValor ]);

            FNfse.Servico.Deducao[Item].TipoDeducao :=
               StrToEnumerado( ok,leitorItem.rCampo(tcStr, 'TipoDeducao'),
                               ['', 'Despesas com Materiais', 'Despesas com Sub-empreitada'],
                               [ tdNenhum, tdMateriais, tdSubEmpreitada ]);

            FNfse.Servico.Deducao[Item].CpfCnpjReferencia := leitorItem.rCampo(tcStr, 'CPFCNPJReferencia');
            FNfse.Servico.Deducao[Item].NumeroNFReferencia := leitorItem.rCampo(tcStr, 'NumeroNFReferencia');
            FNfse.Servico.Deducao[Item].ValorTotalReferencia := leitorItem.rCampo(tcDe2, 'ValorTotalReferencia');
            FNfse.Servico.Deducao[Item].PercentualDeduzir := leitorItem.rCampo(tcDe2, 'PercentualDeduzir');
            FNfse.Servico.Deducao[Item].ValorDeduzir := leitorItem.rCampo(tcDe2, 'ValorDeduzir');

            leitorItem.free;
            Delete(strItem, PosI, count);
            posI := pos('<Deducao>', strItem);
         end;
      end;
   end;

   if (Leitor.rExtrai(2, 'Itens') <> '') then
   begin

      strItem := Leitor.rExtrai(2, 'Itens');
      if (strItem <> '') then
      begin
         Item := 0 ;
         posI := pos('<Item>', strItem);

         while ( posI > 0 ) do begin
            count := pos('</Item>', strItem) + 14;

            FNfse.Servico.ItemServico.Add;
            inc(Item);

            leitorItem := TLeitor.Create;
            leitorItem.Arquivo := copy(strItem, PosI, count);
            leitorItem.Grupo := leitorItem.Arquivo;

            FNfse.Servico.ItemServico[Item].Descricao  := leitorItem.rCampo(tcStr, 'DiscriminacaoServico');
            FNfse.Servico.ItemServico[Item].Quantidade := leitorItem.rCampo(tcStr, 'Quantidade');
            FNfse.Servico.ItemServico[Item].ValorUnitario := leitorItem.rCampo(tcStr, 'ValorUnitario');
            FNfse.Servico.ItemServico[Item].ValorTotal := leitorItem.rCampo(tcStr, 'ValorTotal');
            FNfse.Servico.ItemServico[Item].Tributavel := StrToEnumerado( ok,leitorItem.rCampo(tcStr, 'Tributavel'), ['N','S'], [ snNao, snSim ]);

            leitorItem.free;
            Delete(strItem, PosI, count);
            posI := pos('<Item>', strItem);
         end;
      end;
   end;
  end;

 Result := True;
end;

function TNFSeR.LerNFSe_SP: Boolean;
var
  ok: Boolean;
  Fmt: TFormatSettings;
  Aux: String;
  function Remover(Str, AStr:String):String;
  begin
    Result:=StringReplace(Str,'<'+AStr+'>', '', [rfReplaceAll, rfIgnoreCase]);
    Result:=StringReplace(Result,'</'+AStr+'>', '', [rfReplaceAll, rfIgnoreCase]);
  end;
begin
  try
    Result:=False;
    Leitor.Grupo:=Leitor.rExtrai(1, 'NFe');
    NFSe.XML :=Leitor.Grupo;

    fmt.ShortDateFormat:='yyyy-mm-dd';
    fmt.DateSeparator  :='-';
    fmt.LongTimeFormat :='hh:nn:ss';
    fmt.TimeSeparator  :=':';

    //Dados da nota
    NFSe.Status      := StrToEnumerado(ok, Leitor.rCampo(tcStr, 'StatusNFe'),['N','C'],[srNormal, srCancelado]);
    if Leitor.rCampo(tcStr, 'DataCancelamento') <> '' then begin
      NFSe.NfseCancelamento.DataHora:=StrToDateTime(StringReplace(Leitor.rCampo(tcStr, 'DataCancelamento'), 'T', ' ', [rfReplaceAll]), fmt);
    end;
    Aux:=Leitor.Grupo;
    Leitor.Grupo:=Leitor.rCampo(tcStr, 'ChaveNFe');
    NFSe.Numero:=Leitor.rCampo(tcInt, 'NumeroNFe');
    NFse.PrestadorServico.IdentificacaoPrestador.InscricaoMunicipal:=Leitor.rCampo(tcInt, 'InscricaoPrestador');
    NFSe.CodigoVerificacao:=Leitor.rCampo(tcStr, 'CodigoVerificacao');
    Leitor.Grupo:=Aux;

    NFSe.DataEmissao:=StrToDateTime(StringReplace(Leitor.rCampo(tcStr, 'DataEmissaoNFe'), 'T', ' ', [rfReplaceAll]), fmt);
    NFSe.NumeroLote:=Leitor.rCampo(tcInt, 'NumeroLote');
    NFSe.IdentificacaoRps.Tipo:=trRPS;

    Aux:=Leitor.Grupo;
    Leitor.Grupo:=Leitor.rCampo(tcStr, 'ChaveRPS');
    NFSe.IdentificacaoRps.Serie:=Leitor.rCampo(tcInt, 'SerieRPS');
    NFSe.IdentificacaoRps.Numero:=Leitor.rCampo(tcInt, 'NumeroRPS');
    Leitor.Grupo:=Aux;

    NFSe.DataEmissaoRps:=StrToDate(Leitor.rCampo(tcStr, 'DataEmissaoRPS'), fmt);
    NFSe.PrestadorServico.IdentificacaoPrestador.Cnpj:=Remover(Remover(Leitor.rCampo(tcStr, 'CPFCNPJPrestador'), 'CPF'), 'CNPJ');

    //Prestador
    NFSe.PrestadorServico.RazaoSocial:=Leitor.rCampo(tcStr, 'RazaoSocialPrestador');

    Aux:=Leitor.Grupo;
    Leitor.Grupo:=Leitor.rCampo(tcStr, 'EnderecoPrestador');
    NFSe.PrestadorServico.Endereco.Endereco:=Leitor.rCampo(tcStr, 'Logradouro');
    NFSe.PrestadorServico.Endereco.TipoLogradouro:=Leitor.rCampo(tcStr, 'TipoLogradouro');
    NFSe.PrestadorServico.Endereco.Numero:=Leitor.rCampo(tcStr, 'NumeroEndereco');
    NFSe.PrestadorServico.Endereco.Complemento:=Leitor.rCampo(tcStr, 'ComplementoEndereco');
    NFSe.PrestadorServico.Endereco.Bairro:=Leitor.rCampo(tcStr, 'Bairro');
    NFSe.PrestadorServico.Endereco.CodigoMunicipio:=Leitor.rCampo(tcStr, 'Cidade');
    NFse.PrestadorServico.Endereco.xMunicipio:=CodCidadeToCidade(StrToIntDef(NFSe.PrestadorServico.Endereco.CodigoMunicipio, 0));
    NFSe.PrestadorServico.Endereco.UF:=Leitor.rCampo(tcStr, 'UF');
    NFSe.PrestadorServico.Endereco.CEP:=Leitor.rCampo(tcStr, 'CEP');
    Leitor.Grupo:=Aux;

    //Endereço do serviço vai ser o município do prestador
    NFSe.Servico.CodigoMunicipio:=NFSe.PrestadorServico.Endereco.CodigoMunicipio;
    NFSe.PrestadorServico.Contato.Email:=Leitor.rCampo(tcStr, 'EmailPrestador');
    //ver o que é statusnfe, tributacaonfe, opcaosimples
    {OpcaoSimples
      0 - Não optante
      1 - Optante Simples federal 1.0%
      2 - Optante Simples federal 0.5%
      3 - Optante Simples municipal
      4 - Optante Simples nacional
    }
    if Leitor.rCampo(tcInt, 'OpcaoSimples') = '4' then
      NFSe.OptanteSimplesNacional:=snSim
    else NFSe.OptanteSimplesNacional:=snNao;


    //Valores
    NFSe.Servico.Valores.ValorServicos:=Leitor.rCampo(tcDe2, 'ValorServicos');
    NFse.Servico.Valores.ValorDeducoes:=Leitor.rCampo(tcDe2, 'ValorDeducoes');
    NFse.Servico.Valores.ValorPis:=Leitor.rCampo(tcDe2, 'ValorPIS');
    NFse.Servico.Valores.ValorCofins:=Leitor.rCampo(tcDe2, 'ValorCOFINS');
    NFse.Servico.Valores.ValorInss:=Leitor.rCampo(tcDe2, 'ValorINSS');
    NFse.Servico.Valores.ValorIr:=Leitor.rCampo(tcDe2, 'ValorIR');
    NFse.Servico.Valores.ValorCsll:=Leitor.rCampo(tcDe2, 'ValorCSLL');

    NFse.Servico.Valores.ValorDeducoes:=Leitor.rCampo(tcDe2, 'ValorDeducoes');
    NFSe.Servico.Valores.Aliquota:=Leitor.rCampo(tcDe2, 'AliquotaServicos');
    NFSe.Servico.Valores.ValorIss:=Leitor.rCampo(tcDe2, 'ValorISS');
    ///////////////////////////////////////VERIFICAR///////////////////////////
    //NFSe.Servico.Valores.IssRetido:=Leitor.rCampo(tcInt, 'ISSRetido');

    NFSe.Tomador.IdentificacaoTomador.CpfCnpj:=Remover(Remover(Leitor.rCampo(tcStr, 'CPFCNPJTomador'), 'CPF'), 'CNPJ');
    NFSe.Tomador.RazaoSocial:=Leitor.rCampo(tcStr, 'RazaoSocialTomador');

    Aux:=Leitor.Grupo;
    Leitor.Grupo:=Leitor.rCampo(tcStr, 'EnderecoTomador');
    NFSe.Tomador.Endereco.TipoLogradouro:=Leitor.rCampo(tcStr, 'TipoLogradouro');
    NFSe.Tomador.Endereco.Endereco:=Leitor.rCampo(tcStr, 'Logradouro');
    NFSe.Tomador.Endereco.Numero:=Leitor.rCampo(tcStr, 'NumeroEndereco');
    NFSe.Tomador.Endereco.Complemento:=Leitor.rCampo(tcStr, 'ComplementoEndereco');
    NFSe.Tomador.Endereco.Bairro:=Leitor.rCampo(tcStr, 'Bairro');
    NFSe.Tomador.Endereco.CodigoMunicipio:=Leitor.rCampo(tcStr, 'Cidade');
    NFse.Tomador.Endereco.xMunicipio:=CodCidadeToCidade(StrToIntDef(NFSe.Tomador.Endereco.CodigoMunicipio, 0));
    NFSe.Tomador.Endereco.UF:=Leitor.rCampo(tcStr, 'UF');
    NFSe.Tomador.Endereco.CEP:=Leitor.rCampo(tcStr, 'CEP');
    Leitor.Grupo:=Aux;

    NFSe.Tomador.Contato.Email:=Leitor.rCampo(tcStr, 'EmailTomador');
    NFSe.Servico.Discriminacao:=Leitor.rCampo(tcStr, 'Discriminacao');
    NFSe.Servico.CodigoTributacaoMunicipio:=Leitor.rCampo(tcInt, 'CodigoServico');
    Result:=True;
  except
    Result:=False;
  end;
end;

function TNFSeR.LerNFSe_Equiplano: Boolean;
begin
 // Falta Implementar

 Result := True;
end;

function TNFSeR.LerNFSe: Boolean;
var
 ok  : Boolean;
 CM: String;
begin
 if (Leitor.rExtrai(1, 'OrgaoGerador') <> '')
  then begin
   CM:= Leitor.rCampo(tcStr, 'CodigoMunicipio');
   FProvedor := StrToProvedor(Ok, CodCidadeToProvedor(StrToIntDef(CM, 0)));
  end;

 if CM = ''
  then begin
   if (Leitor.rExtrai(1, 'Servico') <> '')
    then begin
     CM:= Leitor.rCampo(tcStr, 'CodigoMunicipio');
     FProvedor := StrToProvedor(Ok, CodCidadeToProvedor(StrToIntDef(CM, 0)));
    end
    else FProvedor := proNenhum;
  end;

 if (Leitor.rExtrai(1, 'Nfse') <> '') or (Pos('Nfse versao="2.01"', Leitor.Arquivo) > 0) then
 begin
   if (Leitor.rExtrai(2, 'InfNfse') <> '') or (Leitor.rExtrai(1, 'InfNfse') <> '')
    then begin
     NFSe.Numero            := Leitor.rCampo(tcStr, 'Numero');
     NFSe.CodigoVerificacao := Leitor.rCampo(tcStr, 'CodigoVerificacao');

     if FProvedor in [proFreire, proVitoria]
      then NFSe.DataEmissao := Leitor.rCampo(tcDat, 'DataEmissao')
      else NFSe.DataEmissao := Leitor.rCampo(tcDatHor, 'DataEmissao');

     // Alterado por Leonardo Gregianin 11/01/2014: Tratar erro de conversão de tipo no Provedor Ábaco
  	 if Leitor.rCampo(tcStr, 'DataEmissaoRps') <> '0000-00-00' then
	     NFSe.DataEmissaoRps := Leitor.rCampo(tcDat, 'DataEmissaoRps');

     NFSe.NaturezaOperacao         := StrToNaturezaOperacao(ok, Leitor.rCampo(tcStr, 'NaturezaOperacao'));
     NFSe.RegimeEspecialTributacao := StrToRegimeEspecialTributacao(ok, Leitor.rCampo(tcStr, 'RegimeEspecialTributacao'));
     NFSe.OptanteSimplesNacional   := StrToSimNao(ok, Leitor.rCampo(tcStr, 'OptanteSimplesNacional'));
     NFSe.Competencia              := Leitor.rCampo(tcStr, 'Competencia');
     NFSe.OutrasInformacoes        := Leitor.rCampo(tcStr, 'OutrasInformacoes');
     NFSe.ValorCredito             := Leitor.rCampo(tcDe2, 'ValorCredito');

     if FProvedor = proVitoria
      then NFSe.IncentivadorCultural := StrToSimNao(ok, Leitor.rCampo(tcStr, 'IncentivoFiscal'))
      else NFSe.IncentivadorCultural := StrToSimNao(ok, Leitor.rCampo(tcStr, 'IncentivadorCultural'));

     if FProvedor = proISSNet
      then FNFSe.NfseSubstituida := ''
      else begin
       NFSe.NfseSubstituida := Leitor.rCampo(tcStr, 'NfseSubstituida');
       if NFSe.NfseSubstituida = ''
        then NFSe.NfseSubstituida := Leitor.rCampo(tcStr, 'NfseSubstituta');
      end;

    end;
 end;

 case FProvedor of
  proAbaco,
  proActcon,
  proBetha,
  proBHISS,
  proFISSLex,
  proGinfes,
  proGovBR,
  proISSCuritiba,
  proISSIntel,
  proISSNet,
  proNatal,
  proProdemge,
  proPronim,
  proPublica,
  proRecife,
  proRJ,
  proSimplISS,
  proSpeedGov,
  proThema,
  proTiplan,
  proWebISS: Result := LerNFSe_ABRASF_V1;

  pro4R,
  proAgili,
  proCoplan,
  proDigifred,
  proFIntelISS,
  proFiorilli,
  proFreire,
  proGoiania,
  proGovDigital,
  proISSDigital,
  proISSe,
  proLink3,
  proMitra,
  proProdata,
  proPVH,
  proSaatri,
  proTecnos,
  ProVirtual,
  proVitoria: Result := LerNFSe_ABRASF_V2;

  proIssDsf:  Result := LerNFSe_IssDsf;

  proEquiplano: Result := LerNFSe_Equiplano;
  {add-SP}
  proSP: Result:= LerNFSe_SP;
 end;

 if Leitor.rExtrai(1, 'NfseCancelamento') <> ''
  then begin
   NFSe.NfseCancelamento.DataHora := Leitor.rCampo(tcDatHor, 'DataHora');
   if NFSe.NfseCancelamento.DataHora = 0
    then NFSe.NfseCancelamento.DataHora := Leitor.rCampo(tcDatHor, 'DataHoraCancelamento');
   NFSe.NfseCancelamento.Pedido.CodigoCancelamento := Leitor.rCampo(tcStr, 'CodigoCancelamento');
  end;
end;

function TNFSeR.LerXml: boolean;
begin
 Result := False;

 if Pos('<Nfse', Leitor.Arquivo) > 0
  then Result := LerNFSe
  else if (Pos('<Rps', Leitor.Arquivo) > 0) or (Pos('<rps', Leitor.Arquivo) > 0)
        then Result := LerRPS
        else if Pos('<Notas>', Leitor.Arquivo) > 0
              then Result := LerNFSe_IssDSF
             else if Pos('<NFe', Leitor.Arquivo) > 0
              then Result := LerNFSe_SP;
                  

 // Grupo da TAG <signature> ***************************************************
 Leitor.Grupo := Leitor.Arquivo;

 NFSe.signature.URI             := Leitor.rAtributo('Reference URI=');
 NFSe.signature.DigestValue     := Leitor.rCampo(tcStr, 'DigestValue');
 NFSe.signature.SignatureValue  := Leitor.rCampo(tcStr, 'SignatureValue');
 NFSe.signature.X509Certificate := Leitor.rCampo(tcStr, 'X509Certificate');
end;

end.
