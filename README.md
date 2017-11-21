# SALIC
[![Maintainability](https://api.codeclimate.com/v1/badges/ced465e01fa6da818967/maintainability)](https://codeclimate.com/github/lappis-unb/salic-br/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/ced465e01fa6da818967/test_coverage)](https://codeclimate.com/github/lappis-unb/salic-br/test_coverage)

Bem vindo(a) ao Sistema de Apoio às Leis de Incentivo à Cultura (SALIC). Este projeto tem como objetivo facilitar o cadastro de propostas culturais para o governo,
de forma que sejam mais acessíveis e simples de analisar.

A documentação do _software_ está dividida nos seguintes artefatos:
- Code of Conduct: linhas gerais do funcionamento da comunidade por trás do desenvolvimento do projeto.
- Contributing: quer ajudar e fazer parte da comunidade? Esse arquivo te dirá como =)
- Doc: nessa pasta, encontram-se outros guias úteis

Caso tenha percebido algum problema com o _software_, crie uma issue no repositório para nos ajudar!

# Docker

A aplicação utiliza um _docker_ para configurar um ambiente de desenvolvimento sem que seja necessário mais configurações por parte do usuário. Informações mais detalhadas sobre a utilização do docker podem ser encontradas na [documentação oficial](doc/Guia_utilizacao_docker.md).

Para criar um ambiente para trabalhar com o SALIC basta executar o comando abaixo. OBS: Para trocar entre os ambientes alterne os valores ```development``` e ```production``` para "APPLICATION_ENV":

```
  docker-compose up -d
```


Para parar o container basta digitar:

```
  docker-compose stop
```
