<?php
/**
 * LocalDeRealizacaoControllerTest
 * @author wouerner <wouerner@gmail.com>
 * @link http://www.cultura.gov.br
 */

class LocalderealizacaoControllerTest extends MinC_Test_ControllerActionTestCase {

    public function testIndex()
    {
        $this->autenticar();
        $auth = Zend_Auth::getInstance();
        $usuarioCpf = $auth->getIdentity()->usu_identificacao;

        // Busca na SGCAcesso
        $sgcAcesso = new Autenticacao_Model_Sgcacesso();
        $acessos = $sgcAcesso->findBy(['cpf' => $usuarioCpf]);

        // Buscar projetos do Usuario Logado.
        $where['stestado = ?'] = 1;
        $where['idusuario = ?'] = $acessos['idusuario'];

        $tblPreProjeto = new Proposta_Model_PreProjeto();
        $rsPreProjeto = $tblPreProjeto->buscar($where, array("idpreprojeto ASC"));

        //id do Pre Projeto, necessario usuario ter um pre projeto
        $idPreProjeto = $rsPreProjeto[0]->idPreProjeto;

        // Acessando local de realização
        $this->dispatch('proposta/localderealizacao?idPreProjeto='. $idPreProjeto);

        $this->assertModule('proposta');
        $this->assertController('localderealizacao');
        $this->assertAction('index');
    }
}
