Infra técnica e de desenvolvimento SALIC


# Código

    Histórias de uso - backlog

    A1) criação de branch dev-backlog a partir do MASTER; criação dos branches dev-bl-fernao e dev-bl-pedro a partir da branch dev-backlog
    A2) após realizar alterações em sua branch, fernao manda suas alterações para a dev-backlog
    A3) correções de uma funcionalidade são feitas diretamente na dev-backlog, que é tageada com v1.5.1 e jogada para a master
    A4) após realizar correções em sua branch, pedro faz um merge para pegar as atualizações da master (ou da dev-backlog, que estará sincada com a master nesse momento)
    A5) pedro envia suas alterações para a dev-backlog
    A6) alterações na backlog são publicadas na produção com a tag v1.5.2

    A                       1          2      3        4      5     6

    ----dev-bl-pedro--------o---o---o---o---o----o-----o---o
                           /                          /    \
    ----dev-bl-fernao-----o---o---o---o------------------o--\--------o
                         /             \            /   /    \      /
    A---dev-backlog-----o---------------o-----o----/---/------o----o
                       /                       \  /   /             \
                      /                         \/                   \
    ----MASTER-------o-v1.5----------------------o-v1.5.1-------------o-v1.5.2--------------------------------------------------------------o-v2.0.0
                      \                          \                     \                                                                   /
                       \                          \                     \                                                                 /
    B---dev-novaIN------o----------------o-a2.0.0--\-------o-a2.0.1------\-------o---------------------o-a2.0.2--------------o-a2.0.3----o
                         \              /\          \     / \             \     / \           \       / \     \             /
    ----dev-IN-rafa-------o---o---o----o--\----------\---/---o---o---o---o-\---/---\---o---o---o-----o   \     \           /
                           \               \          \ /     \             \ /     \                     \     \         /
    ----dev-INsync----------o---------------o----------o-------\-------------o-------\---------------------o     \       /        
                                                                \                     \                           \     /
    ----dev-INfernao---------------------------------------------o---o---o---o---o-----o---------------------------o---o


    B                       1            2  3          4    5  6   7         8   9     10     11      12   13     14   15                16


    Histórias de uso - nova IN:

    B1) criação de branch dev-novaIN a partir do MASTER; criação dos branches dev-IN-RAFA e dev-INsyn a partir da branch dev-novaIN
    B2) após realizar alterações em sua branch, Rafa manda suas alterações para a dev-novaIN, que são tageadas como versão alpha2.0.0
    B3) responsável pela branch de sincronização atualiza branch com a dev-novaIN
    B4) responsável pela branch de sincronização puxa atualizações da branch dev-backlog que foram lançadas para a master
    B5) alterações de sync são enviadas para branch dev-novaIN
    B6) rafa atualiza sua branch recebendo últimas alterações de sync a partir da dev-novaIN
    B7) fernão se junta ao time e faz um branch a partir da última versão da dev-novaIN
    B8) novo sync feito com alterações jogadas para a master
    B9) alterações de sync são enviadas para dev-novaIN
    B10) fernão realiza alterações na sua branch e faz merge com últimas alterações vindas do backlog/sync
    B11) rafa realiza alterações na sua branch e faz merge com últimas alterações vindas do backlog/sync
    B12) rafa publica suas alterações no dev-novaIN
    B13) sync atualiza código com base no dev-novaIN
    B14) fernao atualiza código com base no dev-novaIN
    B15) fernão publica suas alterações no dev-novaIN
    B16) alterações da dev-novaIN são publicadas na master

# BANCOS

    ----------
    | dev X  |                             ---------------->  * ambientes
    ----------

    ---------------
    | dev-novaIN  |                        ---------------->  * ambientes
    ---------------

    --------------
    | homolog  X |                         ---------------->    homolog.novosalic.cultura.gov.br
    --------------

    ---------------------
    | homolog-novaIN  X |                  ---------------->    homologn.novosalic-novain.cultura.gov.br
    ---------------------


    ---------------
    | treinamento |                        ---------------->    treinamento.novosalic.cultura.gov.br
    ---------------

    -------------
    | producao  |                          ---------------->    novosalic.cultura.gov.br
    -------------


# Deploy das alterações do banco / versionamento


## Fase 1: criar pasta sql e criar pastas para cada versão em que são adicionados os scripts da versão

    /sql/v1.5.2
    /sql/v1.5.2/tblAgentes.sql
    /sql/v1.5.2/spValidaAgentes.sql
    /sql/v1.5.2/triggerAgentes.sql
    /sql/v1.5.3/tabelaProposta.sql
    /sql/v2.0.0/tblProposta.sql


## Fase 2: versionar sql estrutural (tabelas / procedures / triggers / etc)
## Fase 3: disponibilizar carga inicial

    /db
    /db/dbname
    /db/dbname/table/tabela1.sql
    /db/dbname/table/tabela2.sql
    /db/dbname/table/tabela3.sql
    /db/dbname/trigger/trigger1.sql
    /db/dbname/trigger/trigger2.sql
    /db/dbname/trigger/trigger3.sql
    /db/procedure/sp1.sql
    /db/procedure/sp2.sql
    /db/procedure/sp3.sql
    /db/etc...
    
    ...
    /db/carga_inicial.sql


