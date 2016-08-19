<?php

namespace NFePHP\NFSe\Models\Prodam;


class EnvioRPS
{
    public static function render()
    {
        /**
         * <PedidoEnvioLoteRPS xmlns="http://www.prefeitura.sp.gov.br/nfe"
xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
xmlns:xsd="http://www.w3.org/2001/XMLSchema">-
<Cabecalho xmlns="" Versao="1">-
<CPFCNPJRemetente>
  <CNPJ>99999998000228</CNPJ>
</CPFCNPJRemetente>
<transacao>false</transacao>
<dtInicio>2015-01-01</dtInicio>
<dtFim>2015-01-26</dtFim>
<QtdRPS>2</QtdRPS>
<ValorTotalServicos>201</ValorTotalServicos>
<ValorTotalDeducoes>0</ValorTotalDeducoes></Cabecalho>-
<RPS xmlns="">
<Assinatura>
ZcljGWsQ1aQ5ajIU1VjOvJesyXAj760QuyHYEM+92wbSRdCuLdwNk1VJ2dj1iZOlmDzhK+dxF8C0CEi5oR8K1krYvVP/JeOVPXyQQXynMm/qr9hGdiwE1rY9wlBg/IqiVJZLlhOATuyXO4vpepVAF8GlXdcnofqnh7qFnPGGUsgvoFe7xXCl/yp7IJ351fhrRr35UpVEzlDlQb+M8W018tfeIP0hSe0L6QZTqMI05DcvnItLLWOe5EA8I/LGCyIjkeLpZWG9k3PeRimLQzvcnpYHqxPu0x+Xm7O3tZMrgjdbDz3EODvczNfcHzdIjT9l9T3De4pntwlwgMLBtK5Ezg==</Assinatura>-
<ChaveRPS>
  <InscricaoPrestador>39617106</InscricaoPrestador>
  <SerieRPS>BB</SerieRPS>
  <NumeroRPS>4102</NumeroRPS>
</ChaveRPS>
<TipoRPS>RPS</TipoRPS>
<DataEmissao>2015-01-20</DataEmissao>
<StatusRPS>N</StatusRPS>
<TributacaoRPS>T</TributacaoRPS>
<ValorServicos>100</ValorServicos>
<ValorDeducoes>0</ValorDeducoes>
<ValorPIS>1.01</ValorPIS>
<ValorCOFINS>1.02</ValorCOFINS>
<ValorINSS>1.03</ValorINSS>
<ValorIR>1.04</ValorIR>
<ValorCSLL>1.05</ValorCSLL>
<CodigoServico>7811</CodigoServico>
<AliquotaServicos>0.05</AliquotaServicos>
<ISSRetido>false</ISSRetido>-
<CPFCNPJTomador>
  <CPF>99999999727</CPF>
</CPFCNPJTomador>
<RazaoSocialTomador>ANTONIO PRUDENTE</RazaoSocialTomador>-
<EnderecoTomador>
  <TipoLogradouro>RUA</TipoLogradouro>
  <Logradouro>PEDRO AMERICO</Logradouro>
  <NumeroEndereco>1</NumeroEndereco>
  <ComplementoEndereco>1 ANDAR</ComplementoEndereco>
  <Bairro>CENTRO</Bairro>
  <Cidade>3550308</Cidade>
  <UF>SP</UF>
  <CEP>00001045</CEP>
</EnderecoTomador>
<EmailTomador>teste@teste.com</EmailTomador>
<Discriminacao>Nota Fiscal de Teste Emitida por Cliente
Web</Discriminacao>
<ValorCargaTributaria>30.25</ValorCargaTributaria>
<PercentualCargaTributaria>15.12</PercentualCargaTributaria>
<FonteCargaTributaria>IBPT</FonteCargaTributaria></RPS>-
<RPS xmlns="">
<Assinatura>
RZh+XUmvgpyHJJFzGidFiR5gQ0t0KSXtrC/Rjp3SnusVlQYJyf/4uOUsJCwyF+JT6EDXmt53FkD2++XoFNmt446yB2dn9zCsbFQTgUT9lUB3XNnnDi7ANJY+l2KkCjqWhS6iwVvgauryXZByuk+Rq3HINkbv+/wRcGQkaXDSN1VDvYe+V+R7UDYpgxU4IF2yIdsAAMWxbZlq12PUCvjCPHEbQiR+p+rmuzq3IJdDCDJTU6Bub4gecpk1b1LqUNlIBSsv6cSGrZYflxETnzca1nHQUQI+9yBHNQWmm74uSdqvNXVejtR6tzLdrDLvXm5IN1iVJOL4XU/8+bq+FYqYfg==</Assinatura>-
<ChaveRPS>
  <InscricaoPrestador>39617106</InscricaoPrestador>
  <SerieRPS>BC</SerieRPS>
  <NumeroRPS>4103</NumeroRPS>
</ChaveRPS>
<TipoRPS>RPS</TipoRPS>
<DataEmissao>2015-01-21</DataEmissao>
<StatusRPS>N</StatusRPS>
<TributacaoRPS>F</TributacaoRPS>
<ValorServicos>101</ValorServicos>
<ValorDeducoes>0</ValorDeducoes>
<ValorPIS>2.01</ValorPIS>
<ValorCOFINS>2.02</ValorCOFINS>
<ValorINSS>2.03</ValorINSS>
<ValorIR>2.04</ValorIR>
<ValorCSLL>2.05</ValorCSLL>
<CodigoServico>7811</CodigoServico>
<AliquotaServicos>0.05</AliquotaServicos>
<ISSRetido>false</ISSRetido>-
<CPFCNPJTomador>
  <CPF>99999999727</CPF>
</CPFCNPJTomador>
<RazaoSocialTomador>ANTONIO PRUDENTE</RazaoSocialTomador>-
<EnderecoTomador>
  <TipoLogradouro>RUA</TipoLogradouro>
  <Logradouro>PEDRO AMERICO</Logradouro>
  <NumeroEndereco>1</NumeroEndereco>
  <ComplementoEndereco>1 ANDAR</ComplementoEndereco>
  <Bairro>CENTRO</Bairro>
  <Cidade>3550308</Cidade>
  <UF>SP</UF>
  <CEP>00001045</CEP>
</EnderecoTomador>
<EmailTomador>teste@teste.com</EmailTomador>
<Discriminacao>Nota Fiscal 2 de Teste Emitida por Cliente
Web</Discriminacao>
<ValorCargaTributaria>20.21</ValorCargaTributaria>
<PercentualCargaTributaria>17.14</PercentualCargaTributaria>
<FonteCargaTributaria>IBPT</FonteCargaTributaria>
<MunicipioPrestacao>1200013</MunicipioPrestacao></RPS>-

         */
    }
}
