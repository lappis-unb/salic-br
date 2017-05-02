Assinatura de Projetos Culturais
===============

### Sobre

Tem como responsabilidade fornecer recursos de assinatura de projetos culturais.

## Configurações

Como futuramente além do usuário e senha será possível assinar projetos utilizando outras 
formas de autenticação, foram criados meios para possibilitar injetar a forma de 
autenticação desejada para assinar o documento.

No arquivo ``.../application/configs/application.ini`` é possível escolher as configurações 
desejadas para a aplicação definindo a propriedade abaixo:
* Assinatura.isServicoHabilitado = true
* Assinatura.Autenticacao.metodo = "padrao"

## Dependências

O módulo apresenta dependências de acordo com a forma de autenticação escolhida.
Caso a forma de autenticação escolhida seja a ``padrão`` as dependências são:

     * Módulo: Autenticacao 
     * Classe: Autenticacao_Model_Usuario

---

     * Módulo: Admissibilidade 
     * Classe: Admissibilidade_Model_Enquadramento

---

     * Módulo: Assinatura 
     * Classe: Assinatura_Model_DbTable_TbAssinatura

---

     * Módulo: Assinatura 
     * Classe: Assinatura_Model_DbTable_TbAtoAdministrativo
          
---

     * Módulo: Assinatura 
     * Classe: Assinatura_Model_DbTable_TbDocumentoAssinatura
          
---

     * Módulo: Projeto
     * Classe: Projeto_Model_DbTable_Projetos
          
          
