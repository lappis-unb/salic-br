<?php

class ManterorcamentoControllerTest extends MinC_Test_ControllerActionTestCase {

    public function testProdutoscadastrados()
    {
        $this->autenticar();

        //reset para garantir respostas.
        $this->resetRequest()
            ->resetResponse();

        // trocar para perfil Proponente
        $this->request->setMethod('GET');
        $this->dispatch('/autenticacao/perfil/alterarperfil?codGrupo=1111&codOrgao=2222');
        $this->assertRedirectTo('/principalproponente');

        $auth = Zend_Auth::getInstance();
        $usuarioCpf = $auth->getIdentity()->cpf;

        // Busca na SGCAcesso
        $sgcAcesso = new Autenticacao_Model_Sgcacesso();
        $acessos = $sgcAcesso->findBy(['cpf' => $usuarioCpf]);

        // Buscar projetos do Usuario Logado.
        $where['stestado = ?'] = 1;
        $where['idusuario = ?'] = $acessos['idusuario'];

        $tblPreProjeto = new Proposta_Model_DbTable_PreProjeto();
        $rsPreProjeto = $tblPreProjeto->buscar($where, array("idpreprojeto DESC"));

        //id do Pre Projeto, necessario usuario ter um pre projeto
        $idPreProjeto = $rsPreProjeto[0]->idPreProjeto;

        //reset para garantir respostas.
        $this->resetRequest()
            ->resetResponse();

        // Acessando local de realizacao
        $url = '/proposta/manterorcamento/produtoscadastrados?idPreProjeto='. $idPreProjeto;
        $this->request->setMethod('GET');
        $this->dispatch($url);
        $this->assertNotRedirect();

        $this->assertModule('proposta');
        $this->assertController('manterorcamento');
        $this->assertAction('produtoscadastrados');
        $this->assertQuery('html body div#titulo');
    }

    public function testCustosadministrativosAction()
    {
        $this->autenticar();

        $this->perfilParaProponente();

        $auth = Zend_Auth::getInstance();
        $usuarioCpf = $auth->getIdentity()->cpf;

        // Busca na SGCAcesso
        $sgcAcesso = new Autenticacao_Model_Sgcacesso();
        $acessos = $sgcAcesso->findBy(['cpf' => $usuarioCpf]);

        // Buscar projetos do Usuario Logado.
        $where['stestado = ?'] = 1;
        $where['idusuario = ?'] = $acessos['idusuario'];

        $tblPreProjeto = new Proposta_Model_DbTable_PreProjeto();
        $rsPreProjeto = $tblPreProjeto->buscar($where, array("idpreprojeto DESC"));

        //id do Pre Projeto, necessario usuario ter um pre projeto
        $idPreProjeto = $rsPreProjeto[0]->idPreProjeto;

        //reset para garantir respostas.
        $this->resetRequest()
            ->resetResponse();

        // Acessando local de realizacao
        $url = '/proposta/manterorcamento/custosadministrativos?idPreProjeto='. $idPreProjeto;
        $this->request->setMethod('GET');
        $this->dispatch($url);
        $this->assertNotRedirect();

        $this->assertModule('proposta');
        $this->assertController('manterorcamento');
        $this->assertAction('custosadministrativos');
        $this->assertQueryContentContains('html body div#titulo div', 'Custos Administrativos');
    }

    public function testPlanilhaorcamentariageralAction()
    {
        $this->autenticar();

        $this->perfilParaProponente();

        $auth = Zend_Auth::getInstance();
        $usuarioCpf = $auth->getIdentity()->cpf;

        // Busca na SGCAcesso
        $sgcAcesso = new Autenticacao_Model_Sgcacesso();
        $acessos = $sgcAcesso->findBy(['cpf' => $usuarioCpf]);

        // Buscar projetos do Usuario Logado.
        $where['stestado = ?'] = 1;
        $where['idusuario = ?'] = $acessos['idusuario'];

        $tblPreProjeto = new Proposta_Model_DbTable_PreProjeto();
        $rsPreProjeto = $tblPreProjeto->buscar($where, array("idpreprojeto DESC"));

        //id do Pre Projeto, necessario usuario ter um pre projeto
        $idPreProjeto = $rsPreProjeto[0]->idPreProjeto;

        //reset para garantir respostas.
        $this->resetRequest()
            ->resetResponse();

        // Acessando local de realizacao
        $url = '/proposta/manterorcamento/planilhaorcamentariageral?idPreProjeto='. $idPreProjeto;
        $this->request->setMethod('GET');
        $this->dispatch($url);
        $this->assertNotRedirect();

        $this->assertModule('proposta');
        $this->assertController('manterorcamento');
        $this->assertAction('planilhaorcamentariageral');
        $this->assertQueryContentContains('html body div#titulo div', 'Planilha Orçamentária ');
    }
}
