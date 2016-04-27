# Roteiro de publicação de release


## 1) Certifique-se de que funcionalidade está ok em desenvolvimento no seu branch

Todo o trabalho é feito em uma branch específica, que agrupa um conjunto de tarefas.

## 2) Faça o merge da branch com a master

Certifique-se de que está trabalhando no branch correto e que os commits estão na versão correta:

    $ git branch
    * nome-do-branch
      correcoes
      hotfix-readequacoes
      master

    $ git log

Mude para o branch master e faça o merge

    $ git checkout master
    $ git pull origin master
    $ git merge nome-do-branch

É possível que ocorram conflitos. Um conflito é quando um arquivo é alterado em duas branches e o git não consegue fazer o merge automático. Nesse caso, você deve resolver o conflito na mão. Verifique a seção <http://192.168.14.67/wiki/Comandos_gerais_de_GIT/#merge-conflitos>




## 2) Listar correções e novidades, adicionando as linhas

Verificar qual será o número da próxima versão, seguindo as <http://192.168.14.67/wiki/Salic/RegrasVersionamento/>

Para o changelog, adotar o padrão:

* fix: correção de bug
* novo: nova funcionalidade

## 3) Edite o arquivo CHANGELOG

Adicione o texto do release ao CHANGELOG

    // edite
    $ vim CHANGELOG
    

    // faça o commit
    $ git add CHANGELOG
    $ git commit -m 'adicionando changelog da versão v1.x.x'
    $ git push

## 4) Editar texto de tela de release

  <http://192.168.14.67/wiki/ReleasesSalic/>

## 5) Criar tag no código

    // certifique-se de estar trabalhando no branch correto e faça o merge com o master, caso isso ainda não tenha sido feito
    $ git branch
      correcoes
    * master

    // liste as tags que já existem
    $ git tag
    v1.3.0
    v1.3.1
    v1.3.2
    v1.3.3
   
    // crie a tag
    $ git tag -a v1.3.1 -m 'informações sobre o release v1.3.1'
    
    // envie a tag para o repositório central (ex: github, gitlab). Caso contrário a tag será apenas local
    $ git push --follow-tags

Em caso de dúvida, consultar <http://192.168.14.67/wiki/Comandos_gerais_de_GIT/#tags>

## 6) Subir para homologação

    // verifique qual é a tag atual
    $ git describe --tags
    v1.3.0

    // atualize o repositório
    $ git pull
    
    // baixe a tag nova criando uma branch para ela
    # git checkout v1.3.1 -b v1.3.1

    $ git describe --tags
    v1.3.0

## 7) Verificar publicação

## 8) Preparar email de release

Caso haja necessidade, elaborar um email de release.


## 9) Subir para produção

    # cd /var/www/novosalic.cultura.gov.br 
    
    // verifique qual é a tag atual
    # git describe --tags
    v1.3.0
    
    // atualize as referências do repositório sem alterar os arquivos
    # git fetch

    // verifique se a nova tag apareceu
    # git tag
    v1.3.0
    v1.3.1

    // baixe a tag nova criando uma branch para ela
    # git checkout v1.3.1 -b v1.3.1

## 10) Enviar email

