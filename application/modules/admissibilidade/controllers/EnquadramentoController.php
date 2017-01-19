<?php

class Admissibilidade_EnquadramentoController extends MinC_Controller_Action_Abstract
{
    public function init()
    {
        parent::perfil();
        parent::init();
        $this->auth = Zend_Auth::getInstance();
        $this->grupoAtivo = new Zend_Session_Namespace('GrupoAtivo');
    }

    public function indexAction()
    {
        $this->redirect("/admissibilidade/enquadramento/gerenciar-enquadramento");
    }

    public function gerenciarEnquadramentoAction()
    {
        $auth = Zend_Auth::getInstance();
        $objSession = $auth->getIdentity();

        $this->view->idUsuarioLogado = $this->auth->getIdentity()->usu_codigo;
        $enquadramento = new Enquadramento();

        $this->view->dados = array();
        $ordenacao = array("projetos.DtSituacao asc");

        if($this->grupoAtivo->codGrupo == Autenticacao_Model_Grupos::COORDENADOR_ADMISSIBILIDADE) {
            $this->view->dados = $enquadramento->obterProjetosParaEnquadramento(
                $objSession->usu_org_max_superior,
                $ordenacao
            );
        } elseif ($this->grupoAtivo->codGrupo == Autenticacao_Model_Grupos::TECNICO_ADMISSIBILIDADE) {
            $this->view->dados = $enquadramento->obterProjetosParaEnquadramentoVinculados(
                $this->view->idUsuarioLogado,
                $this->grupoAtivo->codOrgao,
                $ordenacao
            );
        }

        $this->view->codGrupo = $this->grupoAtivo->codGrupo;
    }

    public function enquadrarprojetoAction()
    {
        try {
            $get = $this->getRequest()->getParams();
            if (!isset($get['IdPRONAC']) || empty($get['IdPRONAC'])) {
                throw new Exception("Número de PRONAC não informado.");
            }
            $this->view->IdPRONAC = $get['IdPRONAC'];
            $objProjeto = new Projetos();
            $projeto = $objProjeto->findBy(array('IdPRONAC' => $this->view->IdPRONAC));

            if (!$projeto) {
                throw new Exception("PRONAC não encontrado.");
            }

            $arraySituacoesValidas = array("B01", "B03");
            if (!in_array($projeto['Situacao'], $arraySituacoesValidas)) {
                throw new Exception("Situa&ccedil;&atilde;o do projeto n&atilde;o &eacute; v&aacute;lida.");
            }

            $post = $this->getRequest()->getPost();
            if (!$post) {
                $this->carregardadosEnquadramentoProjeto($projeto);
            } else {
                $this->salvarEnquadramentoProjeto($projeto);
            }

        } catch (Exception $objException) {
            parent::message($objException->getMessage(), '/admissibilidade/enquadramento/gerenciar-enquadramento');
        }
    }

    private function salvarEnquadramentoProjeto($projeto)
    {
        try {
            $auth = Zend_Auth::getInstance();
            $post = $this->getRequest()->getPost();
            $observacao = trim($post['observacao']);
            if(empty($observacao)) {
                throw new Exception("O campo 'Justificativa' é de preenchimento obrigatório.");
            }

            $get = $this->getRequest()->getParams();
            $authIdentity = array_change_key_case((array)$auth->getIdentity());
            $objEnquadramento = new Enquadramento();
            $arrayDadosEnquadramento = $objEnquadramento->findBy(array('IdPRONAC = ?'=>$projeto['IdPRONAC']));
            $arrayArmazenamentoEnquadramento = array(
                'AnoProjeto' => $projeto['AnoProjeto'],
                'Sequencial' => $projeto['Sequencial'],
                'Enquadramento' => $post['enquadramento_projeto'],
                'DtEnquadramento' => $objEnquadramento->getExpressionDate(),
                'Observacao' => $post['observacao'],
                'Logon' => $authIdentity['usu_codigo'],
                'IdPRONAC' => $get['IdPRONAC']
            );

            $objEnquadramento = new Enquadramento();
            if(!$arrayDadosEnquadramento) {
                $objEnquadramento->inserir($arrayArmazenamentoEnquadramento);
            } else {
                $objEnquadramento->update($arrayArmazenamentoEnquadramento, array(
                    'IdEnquadramento = ?' => $arrayDadosEnquadramento['IdEnquadramento']
                ));
            }

            $situacaoFinalProjeto = 'B02';
            $orgaoDestino = null;
            if($projeto['Situacao'] == 'B03') {
                $situacaoFinalProjeto = 'D27';
                $orgaoDestino = 272;
                if($projeto['Area'] == 2) {
                    $orgaoDestino = 166;
                }
            }

            $objPlanoDistribuicaoProduto = new PlanoDistribuicao();
            $objPlanoDistribuicaoProduto->atualizarAreaESegmento($post['areaCultural'], $post['segmentoCultural'], $projeto['idProjeto']);

            $objProjeto = new Projetos();
            $arrayDadosProjeto = array(
                'Situacao' => $situacaoFinalProjeto,
                'DtSituacao' => $objProjeto->getExpressionDate(),
                'ProvidenciaTomada' => 'Projeto enquadrado após avaliação técnica.',
                'Area' => $post['areaCultural'],
                'Segmento' => $post['segmentoCultural'],
                'logon' => $authIdentity['usu_codigo']
            );

            if($orgaoDestino) {
                $arrayDadosProjeto['Orgao'] = $orgaoDestino;
            }

            $arrayWhere = array('IdPRONAC  = ?' => $projeto['IdPRONAC']);
            $objProjeto->update($arrayDadosProjeto, $arrayWhere);

            if($projeto['Situacao'] == 'B03') {
                $tbRecurso = new tbRecurso();
                $tbRecurso->finalizarRecurso($projeto['IdPRONAC']);
            }

            $objVerificacao = new Verificacao();
            $verificacao = $objVerificacao->findBy(array(
                'idVerificacao = ?' => 620
            ));

            $tbTextoEmailDAO = new tbTextoEmail();
            $textoEmail = $tbTextoEmailDAO->findBy(array(
                'idTextoEmail = ?' => 23
            ));

            $objInternet = new Agente_Model_DbTable_Internet();
            $arrayEmails = $objInternet->obterEmailProponentesPorPreProjeto($projeto['idProjeto']);
            foreach ($arrayEmails as $email) {
                EmailDAO::enviarEmail($email->Descricao, $verificacao['Descricao'], $textoEmail['dsTexto']);
            }

            parent::message('Enquadramento cadastrado com sucesso.', '/admissibilidade/enquadramento/gerenciar-enquadramento', 'CONFIRM');
        } catch (Exception $objException) {
            parent::message($objException->getMessage(), "/admissibilidade/enquadramento/enquadrarprojeto?IdPRONAC={$projeto['IdPRONAC']}");
        }
    }

    private function carregardadosEnquadramentoProjeto(array $projeto)
    {
        $mapperArea = new Agente_Model_AreaMapper();
        $this->view->comboareasculturais = $mapperArea->fetchPairs('Codigo', 'Descricao');
        $this->view->projeto = $projeto;

        if (count($this->view->comboareasculturais) < 1) {
            throw new Exception("N&atilde;o foram encontradas &Aacute;reas Culturais para o PRONAC informado.");
        }
        $objSegmentocultural = new Segmentocultural();
        $this->view->combosegmentosculturais = $objSegmentocultural->buscarSegmento($projeto['Area']);

        if (count($this->view->combosegmentosculturais) < 1) {
            throw new Exception("N&atilde;o foram encontradas Segmentos Culturais para o PRONAC informado.");
        }

        $objEnquadramento = new Enquadramento();
        $arrayPesquisa = array(
            'AnoProjeto' => $projeto['AnoProjeto'],
            'Sequencial' => $projeto['Sequencial'],
            'IdPRONAC' => $projeto['IdPRONAC']
        );
        $arrayEnquadramento = $objEnquadramento->findBy($arrayPesquisa);

        $this->view->observacao = $arrayEnquadramento['Observacao'];
        if($projeto['Situacao'] == 'B03') {
            $objRecurso = new tbRecurso();
            $this->view->avaliacaoRecurso = trim($objRecurso->buscarAvaliacaoRecurso($projeto['IdPRONAC']));
        }
        $this->view->codGrupo = $this->grupoAtivo->codGrupo;
    }

    public function encaminharAssinaturaAction() {
        try {
            $get = $this->getRequest()->getParams();
            $post = $this->getRequest()->getPost();

            if (isset($get['IdPRONAC']) && !empty($get['IdPRONAC']) && $get['encaminhar'] == 'true') {
                $this->encaminharProjetoParaPortaria($get['IdPRONAC']);
                parent::message('Projeto encaminhado com sucesso.', '/admissibilidade/enquadramento/encaminhar-assinatura', 'CONFIRM');
            } elseif(isset($post['IdPRONAC']) && is_array($post['IdPRONAC']) && count($post['IdPRONAC']) > 0) {
                foreach($post['IdPRONAC'] as $idPronac) {
                    $this->encaminharProjetoParaPortaria($idPronac);
                }
                parent::message('Projetos encaminhados com sucesso.', '/admissibilidade/enquadramento/encaminhar-assinatura', 'CONFIRM');
            }
            $this->carregarListaEncaminhamentoPortaria();
        } catch (Exception $objException) {
            parent::message($objException->getMessage(), '/admissibilidade/enquadramento/encaminhar-assinatura');
        }
    }

    private function encaminharProjetoParaPortaria($IdPRONAC) {

        $objProjeto = new Projetos();
        $projeto = $objProjeto->findBy(array('IdPRONAC' => $IdPRONAC));

        if(!$projeto) {
            throw new Exception("Projeto n&atilde;o encontrado.");
        }

        if($projeto['Situacao'] != 'B02' && $projeto['Situacao'] != 'B03') {
            throw new Exception("Situa&ccedil;&atilde;o do projeto inv&aacute;lida!");
        }

        $orgaoDestino = 272;
        if($projeto['Area'] == 2) {
            $orgaoDestino = 166;
        }

        $auth = Zend_Auth::getInstance();
        $authIdentity = array_change_key_case((array)$auth->getIdentity());
        $objProjeto = new Projetos();
        $arrayDadosProjeto = array(
            'Situacao' => 'D27',
            'DtSituacao' => $objProjeto->getExpressionDate(),
            'ProvidenciaTomada' => 'Projeto encamihado para Portaria.',
            'logon' => $authIdentity['usu_codigo'],
            'Orgao' => $orgaoDestino
        );

        $arrayWhere = array('IdPRONAC  = ?' => $projeto['IdPRONAC']);
        $objProjeto->update($arrayDadosProjeto, $arrayWhere);
    }

    private function carregarListaEncaminhamentoPortaria() {
        $this->view->idUsuarioLogado = $this->auth->getIdentity()->usu_codigo;
        $enquadramento = new Enquadramento();

        $this->view->dados = array();
        $ordenacao = array("dias desc");
        $this->view->dados = $enquadramento->obterProjetosEnquadradosParaEncaminhamento($ordenacao);
        $this->view->codGrupo = $this->grupoAtivo->codGrupo;
        $this->view->codOrgao = $this->grupoAtivo->codOrgao;
    }

   //@TODO TERMINAR DESISTENCIA RECURSAL E FINALIZAR DESISTENCIA RECURSAL
    public function desistenciaRecursalAction()
    {
        $GrupoAtivo = new Zend_Session_Namespace('GrupoAtivo');

        $Orgaos = new Orgaos();
        $idSecretaria = $Orgaos->buscar(array('codigo = ?'=>$GrupoAtivo->codOrgao))->current();

        $area = 1;
        if($idSecretaria->idSecretaria == 160){
            $area = 2;
        }

        $recurso = new tbRecurso();
        $recursos = $recurso->desistenciaRecursal($area);

        $this->view->recursos = $recursos;
    }

    public function finalizarDesistenciaRecursalAction()
    {
        $idPronac = $this->getRequest()->getParam('idPronac');
        $idRecurso = $this->getRequest()->getParam('idRecurso');

        $objProjetos = new Projetos();

        $dadosProjetos = array(
            'DtSituacao'        => $objProjetos->getExpressionDate(),
            'Situacao'          => 'D27',
            'logon'             => $this->auth->getIdentity()->IdUsuario,
            'ProvidenciaTomada' => 'Desistência do prazo recursal'
        );

        $objProjetos->update($dadosProjetos, array('IdPRONAC = ?' => $idPronac));

        $tbRecurso = new tbRecurso();
        $recurso = $tbRecurso->find(array('idRecurso = ?' => $idRecurso))->current();

        $recurso->dtAvaliacao = new Zend_Db_Expr('GETDATE()');
        $recurso->idAgenteAvaliador = $this->auth->IdUsuario;
        $recurso->stAtendimento = 'D';
        $recurso->siRecurso = 15;
        $recurso->stEstado = 1;

        $recurso->save();

        parent::message('Projeto enviado para portaria.', "/admissibilidade/enquadramento/desistencia-recursal");
    }
}
