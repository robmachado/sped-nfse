#sped-nfse

Framework para geração dos RPS e comunicação das NFSe com as Prefeituras Municipais.

A Nota Fiscal de Serviços Eletrônica - NFS-e é o documento fiscal de existência apenas digital que substituirá as tradicionais notas fiscais de serviços impressas.
A NFSe, implantada pelas Secretarias Municipais de Finanças, será emitida e armazenada eletronicamente em programa de computador, com o objetivo de materializar os fatos geradores do ISSQN – Imposto Sobre Serviços de Qualquer Natureza, por meio do registro eletrônico das prestações de serviços sujeitas à tributação do ISSQN.
Com a Nota Fiscal Eletrônica de Serviços você terá os seguintes benefícios:
- Redução de custos
- Redução de burocracia
- Incentivo ao relacionamento entre tomador e prestador
- Maior gerenciamento de notas emitidas e recebidas
- Economia de tempo e segurança com documentos de arrecadação

A emissão de NFSe depende de prévio cadastramento do emisso e da disponibilidade de certificado digital do tipo A1 (PKCS#12), emitido por certificadora no Brasil pertencente ao ICP-Brasil.

##EM DESENVOLVIMENTO !!

#Padrões

Existem muitos "padrões" diferentes para a emissão de NFSe, além disso, cada prefeitura pode fazer alterações no "padrão" escolhido, por isso, cada Prefeitura autorizadora deverá ser claramente identificada para que os códigos corretos sejam utilizados nas chamadas do framework. Isso eleva muito a complexidade desta API, e consequentemente sua manutenção.

- ABRASF
-- Curitiba (derivação ABRASF)
-- Rio de Janeiro (derivação ABRASF)
-- Belo Horizonte (derivação ABRASF)
-- Salvador (derivação ABRASF)


- WebISS
- Betha
- ISSintel
- GINFES
- IPM
- DSFNET
- ISS.Net
- Simpliss/GDN
- E-Governe
- Tiplan
- Governo Digital
- ISISS
- Equiplano
- Abaco
- ISS Web
- Prodam (São Paulo)