# SPED-NFSE **EM DESENVOLVIMENTO NÃO USÁVEL**

[![Join the chat at https://gitter.im/nfephp-org/sped-nfse](https://badges.gitter.im/nfephp-org/sped-nfse.svg)](https://gitter.im/nfephp-org/sped-nfse?utm_source=badge&utm_medium=badge&utm_campaign=pr-badge&utm_content=badge)

**Framework para a integração com os sistemas de Notas Fiscais Eletrônicas de Serviços das Prefeituras Municipais**

*sped-nfse* é um framework para geração dos RPS e comunicação das NFSe com as Prefeituras Municipais.

[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Latest Version on Packagist][ico-version]][link-packagist]
[![License][ico-license]][link-packagist]
[![Total Downloads][ico-downloads]][link-downloads]

[![Issues][ico-issues]][link-issues]
[![Forks][ico-forks]][link-forks]
[![Stars][ico-stars]][link-stars]

# NOTA IMPORTANTE - LEIA COM MUITA ATENÇÃO
###As prefeituras **mudam de modelo de NFSe e alteram seu layout livremente e até a forma de acesso aos webservices**, isso é um FATO !!

###Isso torna esse pacote IMENSAMENTE COMPLEXO, se comparado a outros similares.

>###Outro detalhe muito importante que afeta pricipalmente o SEU APLICATIVO, que fará uso desse pacote, são os procedimentos diferenciados de cada Prefeitura em relação ao padrão adotado, como:
- campos diferentes (tamanho e estrutura)
- operações não existentes, ou com funcionamento diferente
- critérios de aceitabilidade dos dados diversos do padrão
- etc.

>***Pois bem, isso significa que o SEU aplicativo deverá lidar com cada uma dessas particularidades municipio por municipio, e não apenas modelo a modelo.***

Não existe nenhum padrão nacional na definição dos WebServices, e os municipios podem alterar o layout do XML ou o provedor sem qualquer critério e isto pode causar sérios problemas de acesso e validação, pois podemos não ter condições de adequação desse framework, seja devido a alterações técnicas, seja pela imposição de prazos.

Os usuários desse framework devem avaliar quais os riscos e quais são as responsabilidades que está assumindo ao oferecer o produto ao usuário final, que pode **PARAR DE FUNCIONAR A QUALQUER MOMENTO**, pois como dito anteriormente:

**"NÃO TEMOS COMO GARANTIR O FUNCIONAMENTO CASO ACONTEÇA ALGUMA ALTERAÇÃO NO LEIAUTE DO XML OU NO WEBSERVICE DE RECEPÇÃO DO RPS", evidentemente faremos o possível para adequar, mas não temos como garantir que teremos sucesso no caso da NFSe**

## RECOMENDAÇÃO

Apenas use esse framework se tiver conhecimentos suficientes para corrigir as falhas encontradas, caso contrario DESISTA e não INSISTA NISSO, pois provavelmente NÂO HAVERÁ NENHUM TIPO DE SUPORTE, gratuito ou mesmo PAGO.

***Você assume a responsabilidade por sua própria conta e risco.***

## DEFINIÇÃO

A Nota Fiscal de Serviços Eletrônica - NFS-e é o documento fiscal de existência apenas digital que substituirá as tradicionais notas fiscais de serviços impressas.
A NFSe, implantada pelas Secretarias Municipais de Finanças, será emitida e armazenada eletronicamente em programa de computador, com o objetivo de materializar os fatos geradores do ISSQN – Imposto Sobre Serviços de Qualquer Natureza, por meio do registro eletrônico das prestações de serviços sujeitas à tributação do ISSQN.
Com a Nota Fiscal Eletrônica de Serviços você terá os seguintes benefícios:
- Redução de custos
- Redução de burocracia
- Incentivo ao relacionamento entre tomador e prestador
- Maior gerenciamento de notas emitidas e recebidas
- Economia de tempo e segurança com documentos de arrecadação

A emissão de NFSe depende de prévio cadastramento do emissor e da disponibilidade de certificado digital do tipo A1 (PKCS#12), emitido por certificadora no Brasil pertencente ao ICP-Brasil.

##PACOTE EM DESENVOLVIMENTO, não usável ainda !!

##Padrões

Existem muitos "padrões" diferentes para a emissão de NFSe, além disso, cada prefeitura pode fazer alterações no "padrão" escolhido, por isso, cada Prefeitura autorizadora deverá ser claramente identificada para que os códigos corretos sejam utilizados nas chamadas do framework. Isso eleva muito a complexidade desta API, e consequentemente sua manutenção.

- Ábaco
- **ABRASF - em desenvolvimento**
- Ágili
- ArrecadaNet
- Assessor Público
- AWATAR
- BETHA
- BOANF
- BSIT-BR
- Cecam
- CENTI
- Comunix
- CONAM
- Consist
- COPLAN
- DB NFSE
- DEISS
- DigiFred
- **DSFNET - ALPHA-TESTS**
- Dueto
- DUETO 2.0
- E-Caucaia
- e-Governe ISS
- E-Nota Portal Público
- e-Receita
- E&L
- eISS
- Elotech
- Equiplano
- FacilitaISS
- FGMAISS
- FINTELISS
- FISS-LEX
- Freire
- GENERATIVA
- GINFES
- GLC Consultoria (Sumaré e Monte Mor)
- Goiânia
- Governa
- Governa TXT
- Governo Digital
- Governo Eletrônico
- INFISC
- INFISC – Santiago
- INFISC – Sapucaia
- INFISC Farroupilha
- IPM
- ISISS
- ISS Intel
- ISS On-line Supernova
- ISS Online AEG
- ISS Simples SPCONSIG
- ISS4R
- ISSE
- ISSNET
- ISSNFe On-line
- ISSWEB Camaçari
- ISSWEB Fiorilli
- JFISS Digital
- JGBAIAO
- Lençóis Paulista
- Lexsom
- Memory
- Metrópolis
- NF-Eletronica
- NF-em
- NFPSe
- NFSE-ECIDADES
- NFSeNET
- NFWEB
- Nota Blu
- **Nota Carioca (derivação ABRASF) - em desenvolvimento**
- Nota Natalense
- **Nota Salvador (derivação ABRASF) - em desenvolvimento**
- PMJP
- PortalFacil
- Prescon
- Primax Online
- **Prodam (NF Paulistana) - BETA-TESTS**
- PRODATA
- Pública
- RLZ
- SAATRI
- SEMFAZ
- SH3
- SIAM
- SIGCORP – TXT
- SIGCORP BAURU
- SIGCORP Ivaipora
- SIGCORP Londrina
- SIGCORP Rio Grande
- SIGCORP São Gonçalo
- SimplISS
- SJP
- SMARAPD SIL Tecnologia
- SMARAPD SIL Tecnologia WS
- Solução Pública
- System
- Tecnos
- Thema
- Tinus
- Tinus Upload
- TIPLAN
- Tributos Municipais
- WEB ISS

## Municipios atendidos pelo Framework

- **São Paulo (SP) PRODAM - BETA TESTS**
- Salvador (BA) ABRASF (modificado) - em desenvolvimento
- Rio de Janeiro (RJ) ABRASF (modificado) - em desenvolvimento
- Campinas (SP) DSFNET - em desenvolvimento
- São Luis (MA) DSFNET - em desenvolvimento
- Belem (PA) DSFNET - em desenvolvimento
- Campo Grande (MS) DSFNET - em desenvolvimento
- Sorocaba (SP) DSFNET - em desenvolvimento
- Teresina (PI) DSFNET - em desenvolvimento
- Uberlandia (MG) DSFNET - em desenvolvimento


## Install

Via Composer

``` bash
$ composer require nfephp-org/sped-nfse
```

## Usage
Em breve ....

## Change log

Acompanhe o [CHANGELOG](CHANGELOG.md) para maiores informações sobre as alterações recentes.

## Testing

``` bash
$ composer test
```

## Contributing

Para contribuir por favor observe o [CONTRIBUTING](CONTRIBUTING.md) e o  [Código de Conduta](CONDUCT.md) para detalhes.

E entre em contato comigo pelo [Gitter](https://gitter.im/nfephp-org/sped-nfse), pelo [Forum](https://groups.google.com/forum/#!forum/nfephp), por email ou pelo Hangout do Google, este projeto é muito complexo e requer muita ajuda EXPERIENTE e dedicada para poder se tornar realmente util e ser mantido.

## Security

Caso você encontre algum problema relativo a segurança, por favor envie um email diretamente aos mantenedores do pacote ao invés de abrir um ISSUE.

## Credits

- Roberto L. Machado <linux.rlm@gmail.com>

## License

Este patote está diponibilizado sob LGPLv3, GPLv3 ou MIT License (MIT). Leia  [Arquivo de Licença](LICENSE.md) para maiores informações.

[ico-stars]: https://img.shields.io/github/stars/nfephp-org/sped-nfse.svg?style=flat-square
[ico-forks]: https://img.shields.io/github/forks/nfephp-org/sped-nfse.svg?style=flat-square
[ico-issues]: https://img.shields.io/github/issues/nfephp-org/sped-nfse.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/nfephp-org/sped-nfse/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/nfephp-org/sped-nfse.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/nfephp-org/sped-nfse.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/nfephp-org/sped-nfse.svg?style=flat-square
[ico-version]: https://img.shields.io/packagist/v/nfephp-org/sped-nfse.svg?style=flat-square
[ico-license]: https://poser.pugx.org/nfephp-org/nfephp/license.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/nfephp-org/sped-nfse
[link-travis]: https://travis-ci.org/nfephp-org/sped-nfse
[link-scrutinizer]: https://scrutinizer-ci.com/g/nfephp-org/sped-nfse/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/nfephp-org/sped-nfse
[link-downloads]: https://packagist.org/packages/nfephp-org/sped-nfse
[link-author]: https://github.com/nfephp-org
[link-issues]: https://github.com/nfephp-org/sped-nfse/issues
[link-forks]: https://github.com/nfephp-org/sped-nfse/network
[link-stars]: https://github.com/nfephp-org/sped-nfse/stargazers
