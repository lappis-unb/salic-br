# SALIC - Guia de utilização do Docker

Para facilitar o desenvolvimento e escabilidade da aplicação trabalhamos a plataforma Docker. Com essa plataforma temos a possibilidade de isolar serviços permitindo várias possibilidades de integrações, manutenções e melhorias.

Caso queira conhecer um pouco mais sobre a plataforma Docker é possível acessar uma apresentação criada pela equipe da UFABC que é simples e objetiva fornecendo o que você precisa saber para iniciar no mundo Docker, clicando [aqui](http://pt.slideshare.net/vinnyfs89/docker-essa-baleia-vai-te-conquistar?qid=aed7b752-f313-4515-badd-f3bf811c8a35&v=&b=&from_search=1).

## Docker Images

O Docker fornece "Images" que são imagens de sistemas operacionais, o qual podemos costumizar para que esteja de acordo com um cenário mais próximo possível do ambiente de produção, criando nossas próprias imagens ou receitas para que sejam geradas dinâmicamente.

As "Images" são como "templates". Com elas também é possível versionar estados do sistema operacional e gerar "Containers". 

## Docker Containers

Um container é a implementação de uma Imagem, quando ele é criado, é possível definir configurações para acesso e compartilhamento de conteúdo. 

## Docker Commands

Para executarmos ações na plataforma docker precisamos executar alguns comandos. Clicando [aqui](https://github.com/vinnyfs89/dockerCommands) você pode obter uma lista dos comandos mais utilizados do Docker.

## Imagens de Ambiente

As principais imagens utilizadas pelo SALIC são as listadas abaixo.

#### SALIC WEB - Devel - Apache | PHP 5 | Debian

DockerURL:
```
https://hub.docker.com/r/culturagovbr/web-apache-php5-debian/
```

Docker Run Command : 
```
docker run -it -v /var/www/:/var/www/ --name webserver-apache-php5-debian --add-host local.salic:127.0.0.1 --add-host local.salic.postgre:127.0.0.1 --add-host homolog.cultura.gov.br:10.0.0.13 --add-host mailapp.cultura.gov.br:192.168.11.44 --add-host id.cultura.gov.br:192.168.11.32 -p 80:80 culturagovbr/web-apache-php5-debian:latest
```

Docker Pull Command :
```
docker pull culturagovbr/web-apache-php5-debian
```

#### SALIC WEB - Production - Apache | PHP 5 | Debian

Docker Run Command :
```
docker run --name salic-web -it -v /var/www/:/var/www/ --add-host local.salic:127.0.0.1 --add-host local.salic.postgre:127.0.0.1 --add-host homolog.cultura.gov.br:10.0.0.13 --add-host mailapp.cultura.gov.br:192.168.10.25 --add-host id.cultura.gov.br:192.168.11.32 --add-host seihomolog.cultura.gov.br:192.168.11.26 --add-host sei.cultura.gov.br:192.168.11.6 -p 80:80 culturagovbr/salic-web
```

#### SALIC DB - PostgreSQL | Debian

Docker Run Command :
```
docker run --name salic-db -p 5432:5432 -e POSTGRES_PASSWORD=123456 -e POSTGRES_USER=postgres -e PGDATA=/var/lib/postgresql/data -v /var/www/db/postgresql/data:/var/www/project/db/data -d culturagovbr/salic-db 
```

## Outras Possibilidades

Abaixo temos algumas outras possibilidades: 

#### GRAYLOG

Docker Run Command : 
```
docker run -t -p 9000:9000 -p 12201:12201 -v /graylog2/data:/var/opt/graylog2/data -v /graylog2/logs:/var/log/graylog2 -e GRAYLOG_PASSWORD=123456 --name graylog graylog2/allinone
```

#### GRAYLOG SERVER

Docker Run Command : 
```
docker run -t -p 12900:12900 -p 12201:12201 -p 4001:4001 -e GRAYLOG_SERVER=true -v /var/log --name graylog-server --ulimit nofile=64000:64000 graylog2/allinone
```

#### GRAYLOG WEB

Docker Run Command : 
```
docker run -t -p 9000:9000 -e GRAYLOG_MASTER=172.17.0.4 -e GRAYLOG_WEB=true -e GRAYLOG_PASSWORD=123456 -v /var/log --name graylog-web graylog2/allinone
```

#### Building Dockerfile

Docker Build Command : 
```
docker build -t salic-web .
```

#### Utilizar Docker-Compose

Docker-Compose Up Command : 
```
docker-compose up -d
```