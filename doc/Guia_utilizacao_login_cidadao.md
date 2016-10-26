# Guia para utilização do Login Cidadão no Salic

Foi criada uma estrutura no SALIC para que seja possível implementar o protocolo OAuth e realizar uma integração com o sistema Login Cidadão "id.cultura.gov.br";

Para que essa integração funcione corretamente, certifique-se que:

- A tabela "ControleDeAcesso.dbo.SGCAcesso"(SQL Server) ou "salic.controledeacesso.sgcacesso"(Postgres) possui a coluna "id_login_cidadao";
- A extensão "curl" está instalada e habilitada no servidor que está hosperando sua aplicação;

Esse recurso não só garante simplicidade e menos etapas para acesso ao sistema, mas permite mais segurança para quem o utiliza.

Tudo isso é possível porque agora basta permitir que a aplicação acesse seus dados através do Login Cidadão e a aplicação já se encarregará de realizar todo o processo para tratar e validar o usuário que está logando no sistema.