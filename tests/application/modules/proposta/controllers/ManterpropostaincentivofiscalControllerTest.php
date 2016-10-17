<?php

/**
 * ManterpropostaincentivofiscalControllerTest
 *
 * @author  wouerner <wouerner@gmail.com>
 */
class ManterpropostaincentivofiscalControllerTest extends MinC_Test_ControllerActionTestCase
{
    /**
     * TestListarpropostaAction Verifica acesso a tela.
     *
     * @access public
     * @return void
     */
    public function testListarpropostaAction()
    {
        $this->autenticar();

        //reset para garantir respostas.
        $this->resetRequest()
            ->resetResponse();

        // trocar para perfil Proponente
        $this->request->setMethod('GET');
        $this->dispatch('/autenticacao/perfil/alterarperfil?codGrupo=1111&codOrgao=2222');
        $this->assertRedirectTo('/principalproponente');

        //reset para garantir respostas.
        $this->resetRequest()
            ->resetResponse();

        $this->dispatch('/proposta/manterpropostaincentivofiscal/listarproposta');
        $this->assertModule('proposta');
        $this->assertController('manterpropostaincentivofiscal');
        $this->assertAction('listarproposta');

        //verifica se tela carregou corretamente
        $this->assertQuery('div#titulo div');
    }

    public function testConsultarresponsaveisAction()
    {
        $this->autenticar();

        //reset para garantir respostas.
        $this->resetRequest()
            ->resetResponse();

        // trocar para perfil Proponente
        $this->request->setMethod('GET');
        $this->dispatch('/autenticacao/perfil/alterarperfil?codGrupo=1111&codOrgao=2222');
        $this->assertRedirectTo('/principalproponente');

        //reset para garantir respostas.
        $this->resetRequest()
            ->resetResponse();
        $url = '/proposta/manterpropostaincentivofiscal/consultarresponsaveis';
        $this->dispatch($url);

        $this->assertModule('proposta');
        $this->assertController('manterpropostaincentivofiscal');
        $this->assertAction('consultarresponsaveis');

        //verifica se tela carregou corretamente
        $this->assertQueryContentContains('div#titulo div', 'Gerenciar responsáveis');
    }

    /**
     * TestNovoresponsavel
     *
     * @access public
     * @return void
     */
    public function testNovoresponsavel()
    {
        $this->autenticar();
        $this->perfilParaProponente();

        //reset para garantir respostas.
        $this->resetRequest()
            ->resetResponse();
        $url = '/proposta/manterpropostaincentivofiscal/novoresponsavel';
        $this->dispatch($url);

        $this->assertModule('proposta');
        $this->assertController('manterpropostaincentivofiscal');
        $this->assertAction('novoresponsavel');

        //verifica se tela carregou corretamente
        $this->assertQueryContentContains('html body div#titulo div', 'Novo Responsável ');
    }

    public function testEditarAction()
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

        //id do Pre Projeto, necessario usuario ter um pre projeto para testar
        $idPreProjeto = $rsPreProjeto[0]->idPreProjeto;

        //reset para garantir respostas.
        $this->resetRequest()
            ->resetResponse();

        // Acessando local de realizacao
        $url = '/proposta/manterpropostaincentivofiscal/editar?idPreProjeto='. $idPreProjeto;
        $this->request->setMethod('GET');
        $this->dispatch($url);
        $this->assertNotRedirect();

        $this->assertModule('proposta');
        $this->assertController('manterpropostaincentivofiscal');
        $this->assertAction('editar');
        $this->assertQuery('html body div#titulo div', ' PROPOSTA ');
    }
}
