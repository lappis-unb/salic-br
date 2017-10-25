# SALIC
[![Maintainability](https://api.codeclimate.com/v1/badges/ced465e01fa6da818967/maintainability)](https://codeclimate.com/github/lappis-unb/salic-br/maintainability)
[![Test Coverage](https://api.codeclimate.com/v1/badges/ced465e01fa6da818967/test_coverage)](https://codeclimate.com/github/lappis-unb/salic-br/test_coverage)

Bem vindo/a &agrave; documenta&ccedil;&atilde;o do SALIC! Aqui voc&ecirc; vai encontrar diversas documenta&ccedil;&otilde;es sobre o processo de desenvolvimento do SALIC, versionameno e publica&ccedil;&atilde;o.

* [Esquema de desenvolvimento e banco](doc/Esquema_de_desenvolvimento_e_banco.md)
* [Guia de operação e desenvolvimento](doc/Guia_de_operacao-desenvolvimento.md)
* [Regras de versionamento](doc/Regras_versionamento.md)
* [Roteiro de publicação de releases](doc/Roteiro_de_publicacao_de_releases.md)


## Docker

Utilizamos o Docker como plataforma de desenvolvimento com o intuito de garantir o mesmo ambiente de desenvolvimento 
independentemente do Sistema Operacional(SO) utilizado. Informaçoes mais detalhadas sobre a utilização do docker clique
[aqui](doc/Guia_utilizacao_docker.md).

Para criar um ambiente para trabalhar com o SALIC basta executar o comando abaixo. OBS: Para trocar entre os ambientes alterne os valores ```development``` e ```production``` para "APPLICATION_ENV":

```
  docker-compose up -d
```


Para parar o container basta digitar:

```
  docker-compose stop
```
