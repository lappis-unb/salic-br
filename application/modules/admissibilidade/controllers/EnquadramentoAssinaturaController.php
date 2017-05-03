<?php

class Admissibilidade_EnquadramentoAssinaturaController extends Assinatura_GenericController
{
    private $idTipoDoAtoAdministrativo;

    private function validarPerfis() {
        $auth = Zend_Auth::getInstance();

        $PermissoesGrupo = array();
        $PermissoesGrupo[] = 147;
        $PermissoesGrupo[] = 148;
        $PermissoesGrupo[] = 149;
        $PermissoesGrupo[] = 150;
        $PermissoesGrupo[] = 151;
        $PermissoesGrupo[] = 152;

        isset($auth->getIdentity()->usu_codigo) ? parent::perfil(1, $PermissoesGrupo) : parent::perfil(4, $PermissoesGrupo);
    }

    public function init()
    {
        parent::init();

        $this->auth = Zend_Auth::getInstance();
        $this->grupoAtivo = new Zend_Session_Namespace('GrupoAtivo');
        $this->idTipoDoAtoAdministrativo = Assinatura_Model_DbTable_TbAssinatura::TIPO_ATO_ENQUADRAMENTO;
    }

    public function indexAction()
    {
        $this->validarPerfis();
        $this->redirect("/{$this->moduleName}/enquadramento-assinatura/gerenciar-projetos");
    }

    public function gerenciarProjetosAction()
    {
        $this->validarPerfis();
        $this->view->idUsuarioLogado = $this->auth->getIdentity()->usu_codigo;
        $enquadramento = new Admissibilidade_Model_Enquadramento();

        $ordenacao = array("projetos.DtSituacao asc");
        $this->view->dados = $enquadramento->obterProjetosEncaminhadosParaAssinatura($this->grupoAtivo->codOrgao, $ordenacao);
        $this->view->codGrupo = $this->grupoAtivo->codGrupo;

        $objTbAtoAdministrativo = new Assinatura_Model_DbTable_TbAtoAdministrativo();
        $this->view->quantidade_minima_assinaturas = $objTbAtoAdministrativo->obterQuantidadeMinimaAssinaturas($this->idTipoDoAtoAdministrativo);
        $this->view->idTipoDoAtoAdministrativo = $this->idTipoDoAtoAdministrativo;
    }

    public function devolverProjetoAction()
    {
        $this->validarPerfis();
        $get = Zend_Registry::get('get');
        try {

            if (!filter_input(INPUT_GET, 'IdPRONAC')) {
                throw new Exception ("Identificador do projeto é necessário para acessar essa funcionalidade.");
            }

            $objTbProjetos = new Projeto_Model_DbTable_Projetos();

            $this->view->projeto = $objTbProjetos->findBy(array(
                'IdPRONAC' => $get->IdPRONAC
            ));

            $post = $this->getRequest()->getPost();
            if ($post) {

                if (!$post['motivoDevolucao']) {
                    throw new Exception("Campo 'Motivação da Devolução para nova avaliação' não informado.");
                }

                $objTbDepacho = new Proposta_Model_DbTable_TbDespacho();
                $objTbDepacho->devolverProjetoEncaminhadoParaAssinatura($get->IdPRONAC, $post['motivoDevolucao']);

                $objOrgaos = new Orgaos();
                $orgaoSuperior = $objOrgaos->obterOrgaoSuperior($this->view->projeto['Orgao']);

                $orgaoDestino = 171;
                if ($orgaoSuperior['Codigo'] == Orgaos::ORGAO_SUPERIOR_SEFIC) {
                    $orgaoDestino = 262;
                }

                $objTbProjetos->alterarOrgao($orgaoDestino, $get->IdPRONAC);
                $objProjetos = new Projetos();
                $objProjetos->alterarSituacao(
                    $get->IdPRONAC,
                    null,
                    'B01',
                    'Projeto encaminhado ao t&eacute;cnico para a readequa&ccedil;&atilde;o do Enquadramento'
                );

                $objTbAtoAdministrativo = new Assinatura_Model_DbTable_TbAtoAdministrativo();
                $arrayAtosAdministrativos = $objTbAtoAdministrativo->findAll(
                    array(
                        'idTipoDoAto = ?' => $this->idTipoDoAtoAdministrativo
                    )
                );
                $arrayAtosAdministrativosEnquadramento = array();
                foreach($arrayAtosAdministrativos as $atoAdministrativo) {
                    $arrayAtosAdministrativosEnquadramento[] = $atoAdministrativo['idAtoAdministrativo'];
                }

                $objModelDocumentoAssinatura = new Assinatura_Model_DbTable_TbDocumentoAssinatura();
                $data = array('cdSituacao = ?' => 2);
                $where = array(
                    'IdPRONAC = ?' => $get->IdPRONAC,
                    'idTipoDoAtoAdministrativo = ?' => $this->idTipoDoAtoAdministrativo,
                    'cdSituacao = ?' => 1
                );
                $objModelDocumentoAssinatura->update($data, $where);

                parent::message('Projeto devolvido com sucesso.', "/{$this->moduleName}/enquadramento-assinatura/gerenciar-projetos", 'CONFIRM');
            }

            $this->view->IdPRONAC = $get->IdPRONAC;

            $mapperArea = new Agente_Model_AreaMapper();
            $this->view->areaCultural = $mapperArea->findBy(array(
                'Codigo' => $this->view->projeto['Area']
            ));

            $objSegmentocultural = new Segmentocultural();
            $this->view->segmentoCultural = $objSegmentocultural->findBy(array(
                'Codigo' => $this->view->projeto['Segmento']
            ));

            $objEnquadramento = new Admissibilidade_Model_Enquadramento();
            $arrayPesquisa = array(
                'AnoProjeto' => $this->view->projeto['AnoProjeto'],
                'Sequencial' => $this->view->projeto['Sequencial'],
                'IdPRONAC' => $this->view->projeto['IdPRONAC']
            );
            $this->view->dadosEnquadramento = $objEnquadramento->findBy($arrayPesquisa);

            $this->view->titulo = "Devolver";
        } catch (Exception $objException) {
            parent::message($objException->getMessage(), "/{$this->moduleName}/enquadramento-assinatura/devolver-projeto?IdPRONAC={$get->IdPRONAC}");
        }
    }

    public function assinarProjetoAction()
    {
        $this->validarPerfis();
        $get = Zend_Registry::get('get');

        try {

            $objTbAtoAdministrativo = new Assinatura_Model_DbTable_TbAtoAdministrativo();
            $this->view->perfilAssinante = $objTbAtoAdministrativo->obterPerfilAssinante(
                $this->grupoAtivo->codOrgao,
                $this->grupoAtivo->codGrupo,
                $this->idTipoDoAtoAdministrativo
            );

            if (!$this->view->perfilAssinante) {
                throw new Exception ("A fase atual de assinaturas do projeto atual n&atilde;o permite realizar essa opera&ccedil;&atilde;o.");
            }

            if(is_array($get->IdPRONAC)) {
                $idPronacUnidos = implode(',', $get->IdPRONAC);
                $this->redirect("/{$this->moduleName}/enquadramento-assinatura/assinar-projeto?IdPRONAC={$idPronacUnidos}");
            }

            $this->view->IdPRONAC = $get->IdPRONAC;
            $arrayIdPronacs = explode(',', $get->IdPRONAC);
            if(count($arrayIdPronacs) < 1) {
                throw new Exception ("Identificador do projeto &eacute; necess&aacute;rio para acessar essa funcionalidade.");
            }

            $post = $this->getRequest()->getPost();

            if ($post) {

                foreach($arrayIdPronacs as $idPronac) {
                    $this->assinarProjeto(
                        $idPronac,
                        $post['password'],
                        $post['dsManifestacao']
                    );
                }

                if(count($arrayIdPronacs) > 1) {
                    parent::message(
                        "Projetos assinados com sucesso!",
                        "/{$this->moduleName}/enquadramento-assinatura/gerenciar-projetos",
                        'CONFIRM'
                    );
                }
                parent::message(
                    "Projeto assinado com sucesso!",
                    "/{$this->moduleName}/enquadramento-assinatura/visualizar-projeto?IdPRONAC={$idPronac}",
                    'CONFIRM'
                );
            }

            $objProjeto = new Projeto_Model_DbTable_Projetos();
            $this->view->projeto = array();
            foreach($arrayIdPronacs as $idPronac) {
                $this->view->projeto[] = $objProjeto->findBy(array(
                    'IdPRONAC' => $idPronac
                ));
            }

            $objVerificacao = new Verificacao();
            $this->view->tipoDocumento = $objVerificacao->findBy(array(
                'idVerificacao = ?' => $this->idTipoDoAtoAdministrativo
            ));

        } catch (Exception $objException) {
            if(is_array($get->IdPRONAC)) {
                parent::message(
                    $objException->getMessage(),
                    "/{$this->moduleName}/enquadramento-assinatura/gerenciar-projetos"
                );
            }
            parent::message(
                $objException->getMessage(),
                "/{$this->moduleName}/enquadramento-assinatura/assinar-projeto?IdPRONAC={$get->IdPRONAC}"
            );
        }
    }

    private function assinarProjeto($idPronac, $password, $dsManifestacao)
    {
        $this->validarPerfis();

        if (!filter_input(INPUT_POST, 'dsManifestacao')) {
            throw new Exception ("Campo \"De acordo do Assinante\" &eacute; de preenchimento obrigat&oacute;rio.");
        }

        if (!filter_input(INPUT_POST, 'password')) {
            throw new Exception ("Campo \"Senha\" &eacute; de preenchimento obrigat&oacute;rio.");
        }

        $auth = Zend_Auth::getInstance();

        $Usuario = new Autenticacao_Model_Usuario();
        $isUsuarioESenhaValidos = $Usuario->isUsuarioESenhaValidos($auth->getIdentity()->usu_identificacao, $password);
        if (!$isUsuarioESenhaValidos) {
            throw new Exception ("Senha inv&aacute;lida.");
        }

        $objProjeto = new Projeto_Model_DbTable_Projetos();
        $dadosProjeto = $objProjeto->findBy(array(
            'IdPRONAC' => $idPronac
        ));

        $objEnquadramento = new Admissibilidade_Model_Enquadramento();
        $arrayPesquisa = array(
            'AnoProjeto' => $dadosProjeto['AnoProjeto'],
            'Sequencial' => $dadosProjeto['Sequencial'],
            'IdPRONAC' => $idPronac
        );
        $dadosEnquadramento = $objEnquadramento->findBy($arrayPesquisa);

        $objModelDocumentoAssinatura = new Assinatura_Model_DbTable_TbDocumentoAssinatura();
        $dadosDocumentoAssinatura = $objModelDocumentoAssinatura->findBy(
            array(
                'IdPRONAC' => $idPronac,
                'idTipoDoAtoAdministrativo' => $this->idTipoDoAtoAdministrativo
            )
        );

        $objTbAtoAdministrativo = new Assinatura_Model_DbTable_TbAtoAdministrativo();
        $dadosAtoAdministrativoAtual = $objTbAtoAdministrativo->obterAtoAdministrativoAtual(
            $this->idTipoDoAtoAdministrativo,
            $this->grupoAtivo->codGrupo,
            $this->grupoAtivo->codOrgao
        );

        if (!$dadosAtoAdministrativoAtual) {
            throw new Exception ("A fase atual de assinaturas do projeto atual n&atilde;o permite realizar essa opera&ccedil;&atilde;o.");
        }

        $objTbAssinatura = new Assinatura_Model_DbTable_TbAssinatura();

        $dadosInclusaoAssinatura = array(
            'idPronac' => $idPronac,
            'idAtoAdministrativo' => $dadosAtoAdministrativoAtual['idAtoAdministrativo'],
            'idAtoDeGestao' => $dadosEnquadramento['IdEnquadramento'],
            'dtAssinatura' => $objEnquadramento->getExpressionDate(),
            'idAssinante' => $auth->getIdentity()->usu_codigo,
            'dsManifestacao' => $dsManifestacao,
            'idDocumentoAssinatura' => $dadosDocumentoAssinatura['idDocumentoAssinatura']
        );

        $objTbAssinatura->inserir($dadosInclusaoAssinatura);

        $orgaoDestino = $objTbAtoAdministrativo->obterProximoOrgaoDeDestino($this->idTipoDoAtoAdministrativo, $dadosAtoAdministrativoAtual['idOrdemDaAssinatura']);

        if ($orgaoDestino) {
            $objTbProjetos = new Projeto_Model_DbTable_Projetos();
            $objTbProjetos->alterarOrgao($orgaoDestino, $idPronac);
        }
    }

    public function finalizarAssinaturaAction()
    {
        $this->validarPerfis();
        $get = Zend_Registry::get('get');
        try {
            if (!filter_input(INPUT_GET, 'IdPRONAC')) {
                throw new Exception ("Identificador do projeto é necessário para acessar essa funcionalidade.");
            }

            $objProjetos = new Projetos();
            $objProjetos->alterarSituacao(
                $get->IdPRONAC,
                null,
                'D27',
                'Projeto para inclus&atilde;o em Portaria'
            );

            $objTbProjetos = new Projeto_Model_DbTable_Projetos();
            $dadosProjeto = $objTbProjetos->findBy(array(
                'IdPRONAC' => $get->IdPRONAC
            ));

            $orgaoDestino = 166;
            $objOrgaos = new Orgaos();
            $dadosOrgaoSuperior = $objOrgaos->obterOrgaoSuperior($dadosProjeto['Orgao']);
            if ($dadosOrgaoSuperior['Codigo'] == Orgaos::ORGAO_SUPERIOR_SEFIC) {
                $orgaoDestino = 272;
            }
            $objTbProjetos->alterarOrgao($orgaoDestino, $get->IdPRONAC);

            $auth = Zend_Auth::getInstance();

            $valoresProjeto = $objTbProjetos->obterValoresProjeto($get->IdPRONAC);

            $dadosInclusaoAprovacao = array(
                'IdPRONAC' => $get->IdPRONAC,
                'AnoProjeto' => $dadosProjeto['AnoProjeto'],
                'Sequencial' => $dadosProjeto['Sequencial'],
                'TipoAprovacao' => 1,
                'dtAprovacao' => $objTbProjetos->getExpressionDate(),
                'ResumoAprovacao' => 'Projeto Aprovado para capta&ccedil;&atilde;o de recursos',
                'AprovadoReal' => $valoresProjeto['ValorAprovado'],
                'Logon' => $auth->getIdentity()->usu_codigo,
            );
            $objAprovacao = new Aprovacao();
            $objAprovacao->inserir($dadosInclusaoAprovacao);

            parent::message('Projeto finalizado com sucesso!', "/{$this->moduleName}/enquadramento-assinatura/gerenciar-projetos", 'CONFIRM');
        } catch (Exception $objException) {
            parent::message($objException->getMessage(), "/{$this->moduleName}/enquadramento-assinatura/assinar-projeto?IdPRONAC={$get->IdPRONAC}");
        }
    }

    public function gerarPdfAction()
    {
        ini_set("memory_limit", "5000M");
        set_time_limit(30);

        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender();
        $cssContents = file_get_contents(APPLICATION_PATH . '/../public/library/materialize/css/materialize.css');
        $cssContents .= file_get_contents(APPLICATION_PATH . '/../public/library/materialize/css/materialize-custom.css');
        $html = $_POST['html'];

        $pdf = new mPDF('pt', 'A4', 12, '', 8, 8, 5, 14, 9, 9, 'P');
        $pdf->allow_charset_conversion = true;
        $pdf->WriteHTML($cssContents, 1);
        $pdf->charset_in = 'ISO-8859-1';

        if(!mb_check_encoding($html, 'ISO-8859-1')) {
            $pdf->charset_in = 'UTF-8';
        }

        $pdf->WriteHTML($html, 2);
        $pdf->Output();
        die;
    }
}