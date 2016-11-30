## Guia para utilização do Login Cidadão no Salic

### Sobre


Foi criada uma estrutura no SALIC para que seja possível implementar o protocolo OAuth e realizar uma integração com o sistema Login Cidadão "id.cultura.gov.br";

Esse recurso não só garante simplicidade e menos etapas para acesso ao sistema, mas permite mais segurança para quem o utiliza.

Tudo isso é possível porque agora basta permitir que a aplicação acesse seus dados através do Login Cidadão e a aplicação já se encarregará de realizar todo o processo para tratar e validar o usuário que está logando no sistema.

### Requisitos técnicos

Para que essa integração funcione corretamente, certifique-se que:

- A tabela "ControleDeAcesso.dbo.SGCAcesso"(SQL Server) ou "salic.controledeacesso.sgcacesso"(Postgres) de possui a coluna "id_login_cidadao";
- A extensão "curl" está instalada e habilitada no servidor que está hosperando sua aplicação;
- Carregar os pacotes existentes no arquivo "composer.json" localizado na raiz do projeto. Para isso, basta executar o comando "composer update" que as dependências serão baixadas.

### Configurações

Foi utilizada a biblioteca "OPAuth" para que fosse possível implementar corretamente o protocolo OAuth no sistema. A biblioteca trabalha com o uso de Strategies e um padrão de rotas, portanto foi criada uma estrutura localizada em "./library/MinC/Auth" contendo todos os requisitos necessários para o funcionamento correto da biblioteca utilizando o Zend Framework 1.

##### Controllers

Dentro da pasta "./application/modules/autenticacao/controllers/" temos a classe "Autenticacao_LogincidadaoController" que herda da classe "MinC_Auth_Controller_AOAuth". A classe "MinC_Auth_Controller_AOAuth" fornece os itens básicos para o tratamento da autenticação utilizando a biblioteca OPauth.

##### Arquivo de configuração

A aplicação necessita que no arquivo de configuração "./application/configs/application.ini" contenha as definições abaixo:

    [oauth_development : development]
    OAuth.servicoHabilitado = true
    OAuth.path = "/autenticacao/"
    OAuth.callback_url = "/autenticacao/logincidadao/oauthresult"
    OAuth.security_salt = 'aaaa'
    OAuth.strategy_dir = APPLICATION_PATH "/../library/MinC/Auth/OAuth/Strategy/"
    OAuth.Strategy.LoginCidadao.application_url_base = "http://local.salic"
    OAuth.Strategy.LoginCidadao.oauth_url_base = "http://id.cultura.gov.br"
    OAuth.Strategy.LoginCidadao.client_id = "xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx"
    OAuth.Strategy.LoginCidadao.client_secret = "xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx"
    
Vamos aos detalhes das configurações acima:
- [oauth_development : development] 
  - Significa que temos um grupo chamado "oauth_development" e estas configurações são específicas para o enviroment "development";
- OAuth.servicoHabilitado = true
  - Esta configuração é utilizada para habilitar/desabilitar o serviço.
- OAuth.strategy_dir = APPLICATION_PATH "/../library/MinC/Auth/OAuth/Strategy/"
  - Aqui é definido o diretório onde estão as estratégias que serão utilizadas pela biblioteca OPAuth
- OAuth.Strategy.LoginCidadao.application_url_base = "http://local.salic"
  - Neste ponto precisamos definir a url da aplicação que estará consumindo informações do Id.Cultura
- OAuth.Strategy.LoginCidadao.client_id = "xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx"
  - A propriedade "client_id" é fornecida quando o serviço é registrado em "http://id.cultura.gov.br";
- OAuth.Strategy.LoginCidadao.client_secret = "xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx"
  - A propriedade "client_secret" é fornecida quando o serviço é registrado em "http://id.cultura.gov.br";

##### Banco de dados

A aplicação necessita que exista a coluna "id_login_cidadao" na tabela "SGCAcesso". Caso ela ainda não exista, execute o comando SQL localizado no arquivo abaixo, de acordo com o Banco de dados utilizado.  

- Postgres > "./sql/PostgreSQL/dbchangelog/25-10-2016_salic.controledeacesso.sgcacesso.sql"
- SQLServer > "./sql/SQLServer/dbchangelog/25-10-2016_controledeacesso.dbo.sgcacesso.sql"