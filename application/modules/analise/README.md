Módulo de An&aacute;lise
=====================

M&oacute;dulo respons&aacute;vel por realizar an&aacute;lise de projetos

##PR&eacute;-REQUISITO

RN - 37 (AJUSTAR PROJETO)
- Art. 72 Captado 10% (dez) do valor total aprovado (Custo do Projeto), ser&aacute; oportunizada a adequa&ccedil;&atilde;o do projeto &agrave; realidade de execu&ccedil;&atilde;o, n&atilde;o podendo representar aumento do Custo do Projeto e observando as veda&ccedil;&otilde;es do Art. 42, visando o encaminhamento para an&aacute;lise t&eacute;cnica.

RN - 37.1
- §1º O prazo para adequa&ccedil;&atilde;o do projeto ser&aacute; de 10 (dez) dias corridos, improrrog&aacute;vel, a contar do dia seguinte do seu registro no Salic e envio desta informa&ccedil;&atilde;o pelo sistema.

RN - 37.2
- §2º O dispositivo do caput n&atilde;o se aplica para projetos de prote&ccedil;&atilde;o do patrimônio material ou imaterial e de acervos, aos planos anuais, aos projetos museológicos, aos projetos de manuten&ccedil;&atilde;o de corpos est&aacute;veis ou de equipamentos culturais, bem como projetos aprovados em editais públicos ou privados com termo de parceria, ou com contratos de patrocínios firmados, que garantam o alcance do índice ou projetos apresentados por institui&ccedil;&otilde;es criadas pelo patrocinador na forma do §2º do Art. 27 da Lei 8.313/91.

##CASO DE USO

APÓS CAPTADO 10%  valor total aprovado (Custo do Projeto),   
                     
1. O sistema por meio de rotina automatizada(**dbo.spLiberarAdequacaoDoProjeto**) enviar&aacute; e-mail ao proponente informando da oportunidade para adequa&ccedil;&atilde;o do projeto &agrave; realidade de execu&ccedil;&atilde;o e ao mesmo tempo alterar&aacute; a situa&ccedil;&atilde;o do projeto para E90.

2. A altera&ccedil;&atilde;o da situa&ccedil;&atilde;o do projeto para E90 &eacute; a condi&ccedil;&atilde;o para que o sistema liberar na lista de projetos do Escritório Virtual do Proponente a coluna com o bot&atilde;o que permitir&aacute; ao proponente abrir o projeto para a adequa&ccedil;&atilde;o do projeto &agrave; realidade de execu&ccedil;&atilde;o conforme a regra de negócio RN - 37.

3. O prazo para adequa&ccedil;&atilde;o do projeto ser&aacute; de 10 (dez) dias corridos, improrrog&aacute;vel, a contar do dia seguinte do seu registro no Salic e envio desta informa&ccedil;&atilde;o pelo sistema, conforme regra de negócio RN - 37.1.
A contagem de desse prazo &eacute; calculada entre a diferen&ccedil;a a DtSituacao da tabela projetos e a data atual.

4. Findo o prazo de 10 dias, o sistema por meio de rotina automatizada alterar&aacute; novamente a situa&ccedil;&atilde;o do projeto para uma das descritas abaixo:
    1. E12 se o projeto ainda n&atilde;o atingiu 20% ou
    2. B11 se o projeto j&aacute; atingiu os 20% e encaminhar&aacute; o projeto para a Unidade Vinculada para a avalia&ccedil;&atilde;o t&eacute;cnica.

5. A veda&ccedil;&atilde;o do Art 42 a que se refere a RN - 37 &eacute; para esclarecer  que os itens descritos abaixo n&atilde;o poder&atilde;o sob nenhuma hipótese serem modificados.
    1. PRODUTO PRINCIPAL;
    2. &aacute;REA CULTURAL
    3. SEGMENTO CULTURAL

##TRIGGERS E PROCEDURES NO BANCO
PROCEDURE dbo.spLiberarAdequacaoDoProjeto
