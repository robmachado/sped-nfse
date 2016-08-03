unit AcbrAssinaRPSSP_TLB;

// ************************************************************************ //
// WARNING                                                                    
// -------                                                                    
// The types declared in this file were generated from data read from a       
// Type Library. If this type library is explicitly or indirectly (via        
// another type library referring to this type library) re-imported, or the   
// 'Refresh' command of the Type Library Editor activated while editing the   
// Type Library, the contents of this file will be regenerated and all        
// manual modifications will be lost.                                         
// ************************************************************************ //

// $Rev: 8291 $
// File generated on 22/04/2014 17:09:09 from Type Library described below.

// ************************************************************************  //
// Type Lib: c:\users\ariel.d\documents\visual studio 2010\Projects\AcbrAssinaRPSSP\AcbrAssinaRPSSP\bin\Debug\AcbrAssinaRPSSP.tlb (1)
// LIBID: {B5240241-204A-4AD9-B6DD-F373F39FDAA0}
// LCID: 0
// Helpfile: 
// HelpString: 
// DepndLst: 
//   (1) v2.0 stdole, (C:\Windows\SysWOW64\stdole2.tlb)
//   (2) v2.4 mscorlib, (C:\Windows\Microsoft.NET\Framework\v4.0.30319\mscorlib.tlb)
// Errors:
//   Error creating palette bitmap of (TCAssinaRPSSP) : Server mscoree.dll contains no icons
// ************************************************************************ //
// *************************************************************************//
// NOTE:                                                                      
// Items guarded by $IFDEF_LIVE_SERVER_AT_DESIGN_TIME are used by properties  
// which return objects that may need to be explicitly created via a function 
// call prior to any access via the property. These items have been disabled  
// in order to prevent accidental use from within the object inspector. You   
// may enable them by defining LIVE_SERVER_AT_DESIGN_TIME or by selectively   
// removing them from the $IFDEF blocks. However, such items must still be    
// programmatically created via a method of the appropriate CoClass before    
// they can be used.                                                          
{$TYPEDADDRESS OFF} // Unit must be compiled without type-checked pointers. 
{$WARN SYMBOL_PLATFORM OFF}
{$WRITEABLECONST ON}
{$VARPROPSETTER ON}
interface

uses Windows, ActiveX, Classes, Graphics, mscorlib_TLB, OleServer, StdVCL, Variants;
  


// *********************************************************************//
// GUIDS declared in the TypeLibrary. Following prefixes are used:        
//   Type Libraries     : LIBID_xxxx                                      
//   CoClasses          : CLASS_xxxx                                      
//   DISPInterfaces     : DIID_xxxx                                       
//   Non-DISP interfaces: IID_xxxx                                        
// *********************************************************************//
const
  // TypeLibrary Major and minor versions
  AcbrAssinaRPSSPMajorVersion = 1;
  AcbrAssinaRPSSPMinorVersion = 0;

  LIBID_AcbrAssinaRPSSP: TGUID = '{B5240241-204A-4AD9-B6DD-F373F39FDAA0}';

  IID_IAssinaRPSSP: TGUID = '{0095E40B-8274-4C73-AA46-B913A596C108}';
  CLASS_CAssinaRPSSP: TGUID = '{3A8B6B96-4F23-4EB5-B4B1-243BFBC41A39}';
type

// *********************************************************************//
// Forward declaration of types defined in TypeLibrary                    
// *********************************************************************//
  IAssinaRPSSP = interface;
  IAssinaRPSSPDisp = dispinterface;

// *********************************************************************//
// Declaration of CoClasses defined in Type Library                       
// (NOTE: Here we map each CoClass to its Default Interface)              
// *********************************************************************//
  CAssinaRPSSP = IAssinaRPSSP;


// *********************************************************************//
// Interface: IAssinaRPSSP
// Flags:     (4416) Dual OleAutomation Dispatchable
// GUID:      {0095E40B-8274-4C73-AA46-B913A596C108}
// *********************************************************************//
  IAssinaRPSSP = interface(IDispatch)
    ['{0095E40B-8274-4C73-AA46-B913A596C108}']
    function AssinarRPSSP(const serial: WideString; const original: WideString): WideString; safecall;
  end;

// *********************************************************************//
// DispIntf:  IAssinaRPSSPDisp
// Flags:     (4416) Dual OleAutomation Dispatchable
// GUID:      {0095E40B-8274-4C73-AA46-B913A596C108}
// *********************************************************************//
  IAssinaRPSSPDisp = dispinterface
    ['{0095E40B-8274-4C73-AA46-B913A596C108}']
    function AssinarRPSSP(const serial: WideString; const original: WideString): WideString; dispid 1610743808;
  end;

// *********************************************************************//
// The Class CoCAssinaRPSSP provides a Create and CreateRemote method to          
// create instances of the default interface IAssinaRPSSP exposed by              
// the CoClass CAssinaRPSSP. The functions are intended to be used by             
// clients wishing to automate the CoClass objects exposed by the         
// server of this typelibrary.                                            
// *********************************************************************//
  CoCAssinaRPSSP = class
    class function Create: IAssinaRPSSP;
    class function CreateRemote(const MachineName: string): IAssinaRPSSP;
  end;


// *********************************************************************//
// OLE Server Proxy class declaration
// Server Object    : TCAssinaRPSSP
// Help String      : 
// Default Interface: IAssinaRPSSP
// Def. Intf. DISP? : No
// Event   Interface: 
// TypeFlags        : (2) CanCreate
// *********************************************************************//
{$IFDEF LIVE_SERVER_AT_DESIGN_TIME}
  TCAssinaRPSSPProperties= class;
{$ENDIF}
  TCAssinaRPSSP = class(TOleServer)
  private
    FIntf: IAssinaRPSSP;
{$IFDEF LIVE_SERVER_AT_DESIGN_TIME}
    FProps: TCAssinaRPSSPProperties;
    function GetServerProperties: TCAssinaRPSSPProperties;
{$ENDIF}
    function GetDefaultInterface: IAssinaRPSSP;
  protected
    procedure InitServerData; override;
  public
    constructor Create(AOwner: TComponent); override;
    destructor  Destroy; override;
    procedure Connect; override;
    procedure ConnectTo(svrIntf: IAssinaRPSSP);
    procedure Disconnect; override;
    function AssinarRPSSP(const serial: WideString; const original: WideString): WideString;
    property DefaultInterface: IAssinaRPSSP read GetDefaultInterface;
  published
{$IFDEF LIVE_SERVER_AT_DESIGN_TIME}
    property Server: TCAssinaRPSSPProperties read GetServerProperties;
{$ENDIF}
  end;

{$IFDEF LIVE_SERVER_AT_DESIGN_TIME}
// *********************************************************************//
// OLE Server Properties Proxy Class
// Server Object    : TCAssinaRPSSP
// (This object is used by the IDE's Property Inspector to allow editing
//  of the properties of this server)
// *********************************************************************//
 TCAssinaRPSSPProperties = class(TPersistent)
  private
    FServer:    TCAssinaRPSSP;
    function    GetDefaultInterface: IAssinaRPSSP;
    constructor Create(AServer: TCAssinaRPSSP);
  protected
  public
    property DefaultInterface: IAssinaRPSSP read GetDefaultInterface;
  published
  end;
{$ENDIF}


procedure Register;

resourcestring
  dtlServerPage = '(none)';

  dtlOcxPage = '(none)';

implementation

uses ComObj;

class function CoCAssinaRPSSP.Create: IAssinaRPSSP;
begin
  Result := CreateComObject(CLASS_CAssinaRPSSP) as IAssinaRPSSP;
end;

class function CoCAssinaRPSSP.CreateRemote(const MachineName: string): IAssinaRPSSP;
begin
  Result := CreateRemoteComObject(MachineName, CLASS_CAssinaRPSSP) as IAssinaRPSSP;
end;

procedure TCAssinaRPSSP.InitServerData;
const
  CServerData: TServerData = (
    ClassID:   '{3A8B6B96-4F23-4EB5-B4B1-243BFBC41A39}';
    IntfIID:   '{0095E40B-8274-4C73-AA46-B913A596C108}';
    EventIID:  '';
    LicenseKey: nil;
    Version: 500);
begin
  ServerData := @CServerData;
end;

procedure TCAssinaRPSSP.Connect;
var
  punk: IUnknown;
begin
  if FIntf = nil then
  begin
    punk := GetServer;
    Fintf:= punk as IAssinaRPSSP;
  end;
end;

procedure TCAssinaRPSSP.ConnectTo(svrIntf: IAssinaRPSSP);
begin
  Disconnect;
  FIntf := svrIntf;
end;

procedure TCAssinaRPSSP.DisConnect;
begin
  if Fintf <> nil then
  begin
    FIntf := nil;
  end;
end;

function TCAssinaRPSSP.GetDefaultInterface: IAssinaRPSSP;
begin
  if FIntf = nil then
    Connect;
  Assert(FIntf <> nil, 'DefaultInterface is NULL. Component is not connected to Server. You must call "Connect" or "ConnectTo" before this operation');
  Result := FIntf;
end;

constructor TCAssinaRPSSP.Create(AOwner: TComponent);
begin
  inherited Create(AOwner);
{$IFDEF LIVE_SERVER_AT_DESIGN_TIME}
  FProps := TCAssinaRPSSPProperties.Create(Self);
{$ENDIF}
end;

destructor TCAssinaRPSSP.Destroy;
begin
{$IFDEF LIVE_SERVER_AT_DESIGN_TIME}
  FProps.Free;
{$ENDIF}
  inherited Destroy;
end;

{$IFDEF LIVE_SERVER_AT_DESIGN_TIME}
function TCAssinaRPSSP.GetServerProperties: TCAssinaRPSSPProperties;
begin
  Result := FProps;
end;
{$ENDIF}

function TCAssinaRPSSP.AssinarRPSSP(const serial: WideString; const original: WideString): WideString;
begin
  Result := DefaultInterface.AssinarRPSSP(serial, original);
end;

{$IFDEF LIVE_SERVER_AT_DESIGN_TIME}
constructor TCAssinaRPSSPProperties.Create(AServer: TCAssinaRPSSP);
begin
  inherited Create;
  FServer := AServer;
end;

function TCAssinaRPSSPProperties.GetDefaultInterface: IAssinaRPSSP;
begin
  Result := FServer.DefaultInterface;
end;

{$ENDIF}

procedure Register;
begin
  RegisterComponents(dtlServerPage, [TCAssinaRPSSP]);
end;

end.
