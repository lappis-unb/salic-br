<?php

class Assinatura_EnquadramentoController extends Assinatura_GenericController
{
    private $idTipoDoAtoAdministrativo;

    public function init()
    {
        $auth = Zend_Auth::getInstance();

        $PermissoesGrupo = array();
        $PermissoesGrupo[] = 147;
        $PermissoesGrupo[] = 148;
        $PermissoesGrupo[] = 149;
        $PermissoesGrupo[] = 150;
        $PermissoesGrupo[] = 151;
        $PermissoesGrupo[] = 152;

        isset($auth->getIdentity()->usu_codigo) ? parent::perfil(1, $PermissoesGrupo) : parent::perfil(4, $PermissoesGrupo);

        parent::init();
        $this->auth = Zend_Auth::getInstance();
        $this->grupoAtivo = new Zend_Session_Namespace('GrupoAtivo');
        $this->idTipoDoAtoAdministrativo = Assinatura_Model_DbTable_TbAssinatura::TIPO_ATO_ENQUADRAMENTO;
    }

    public function indexAction()
    {
        $this->redirect("/{$this->moduleName}/enquadramento/gerenciar-projetos");
    }

    /**
     * @todo foram comentados os tratamentos de acordo com o pefil, temporariamente
     */
    public function gerenciarProjetosAction()
    {
        $this->view->idUsuarioLogado = $this->auth->getIdentity()->usu_codigo;
        $enquadramento = new Admissibilidade_Model_Enquadramento();

        $ordenacao = array("projetos.DtSituacao asc");
        $this->view->dados = $enquadramento->obterProjetosEncaminhadosParaAssinatura($this->grupoAtivo->codOrgao, $ordenacao);
        $this->view->codGrupo = $this->grupoAtivo->codGrupo;

        $objTbAtoAdministrativo = new Assinatura_Model_DbTable_TbAtoAdministrativo();
        $this->view->quantidade_minima_assinaturas = $objTbAtoAdministrativo->obterQuantidadeMinimaAssinaturas($this->idTipoDoAtoAdministrativo);
        $this->view->idTipoDoAtoAdministrativo = $this->idTipoDoAtoAdministrativo;
    }

    /**
     * @todo Criar opção de gerar PDF
     * @todo Criar opção de Imprimir
     * @todo tratar a exibi&ccedil;&atilde;o dos bot&otilde;es de acordo com a fase do projeto
     * @todo Dividir cada bloco da camada de apresentação em partials que podem ser adicionadas ou não
     */
    public function visualizarProjetoAction()
    {
        $get = Zend_Registry::get('get');
        try {
            if (!filter_input(INPUT_GET, 'IdPRONAC')) {
                throw new Exception ("Identificador do projeto é necessário para acessar essa funcionalidade.");
            }

            $this->view->IdPRONAC = $get->IdPRONAC;

            $objTbProjetos = new Projeto_Model_DbTable_Projetos();
            $this->view->projeto = $objTbProjetos->findBy(array(
                'IdPRONAC' => $get->IdPRONAC
            ));

            $this->view->valoresProjeto = $objTbProjetos->obterValoresProjeto($get->IdPRONAC);

            $objAgentes = new Agente_Model_DbTable_Agentes();
            $dadosAgente = $objAgentes->buscarFornecedor(array(
                'a.CNPJCPF = ?' => $this->view->projeto['CgcCpf']
            ));
            $arrayDadosAgente = $dadosAgente->current();
            $this->view->nomeAgente = $arrayDadosAgente['nome'];

            $mapperArea = new Agente_Model_AreaMapper();
            $this->view->areaCultural = $mapperArea->findBy(array(
                'Codigo' => $this->view->projeto['Area']
            ));

            $objSegmentocultural = new Segmentocultural();
            $this->view->segmentoCultural = $objSegmentocultural->findBy(array(
                'Codigo' => $this->view->projeto['Segmento']
            ));

            $objPlanoDistribuicaoProduto = new Projeto_Model_vwPlanoDeDistribuicaoProduto();
            $this->view->dadosProducaoProjeto = $objPlanoDistribuicaoProduto->obterProducaoProjeto(array(
                'IdPRONAC = ?' => $get->IdPRONAC
            ));

            $objEnquadramento = new Admissibilidade_Model_Enquadramento();
            $arrayPesquisa = array(
                'AnoProjeto' => $this->view->projeto['AnoProjeto'],
                'Sequencial' => $this->view->projeto['Sequencial'],
                'IdPRONAC' => $get->IdPRONAC
            );
            $this->view->dadosEnquadramento = $objEnquadramento->findBy($arrayPesquisa);

            $objAssinatura = new Assinatura_Model_DbTable_TbAssinatura();
            $this->view->assinaturas = $objAssinatura->obterAssinaturas($get->IdPRONAC, $this->idTipoDoAtoAdministrativo);

            $objTbAtoAdministrativo = new Assinatura_Model_DbTable_TbAtoAdministrativo();
            $this->view->quantidade_minima_assinaturas = $objTbAtoAdministrativo->obterQuantidadeMinimaAssinaturas($this->idTipoDoAtoAdministrativo);

            $objModelDocumentoAssinatura = new Assinatura_Model_DbTable_TbDocumentoAssinatura();
            $this->view->documentoAssinatura = $objModelDocumentoAssinatura->findBy(
                array(
                    'IdPRONAC' => $get->IdPRONAC,
                    'idTipoDoAtoAdministrativo' => $this->idTipoDoAtoAdministrativo
                )
            );
        } catch (Exception $objException) {
            parent::message($objException->getMessage(), "/assinatura/enquadramento/visualizar-projeto?IdPRONAC={$get->IdPRONAC}");
        }
    }

    public function devolverProjetoAction()
    {
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

                parent::message('Projeto devolvido com sucesso.', "/assinatura/enquadramento/gerenciar-projetos", 'CONFIRM');
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
            parent::message($objException->getMessage(), "/assinatura/enquadramento/devolver-projeto?IdPRONAC={$get->IdPRONAC}");
        }
    }

    /**
     * @todo Tratar quando receber mais de um número de PRONAC
     */
    public function assinarProjetoAction()
    {
        $get = Zend_Registry::get('get');
        try {
            if (!filter_input(INPUT_GET, 'IdPRONAC')) {
                throw new Exception ("Identificador do projeto é necessário para acessar essa funcionalidade.");
            }

            $this->view->IdPRONAC = $get->IdPRONAC;

            $objTbAtoAdministrativo = new Assinatura_Model_DbTable_TbAtoAdministrativo();
            $dadosAtoAdministrativoAtual = $objTbAtoAdministrativo->obterAtoAdministrativoAtual(
                $this->idTipoDoAtoAdministrativo,
                $this->grupoAtivo->codGrupo,
                $this->grupoAtivo->codOrgao
            );

            if (!$dadosAtoAdministrativoAtual) {
                throw new Exception ("A fase atual de assinaturas do projeto atual n&atilde;o permite realizar essa opera&ccedil;&atilde;o.");
            }

            $objProjeto = new Projetos();
            $this->view->projeto = $objProjeto->findBy(array(
                'IdPRONAC' => $get->IdPRONAC
            ));

            $post = $this->getRequest()->getPost();
            if ($post) {

                if (!filter_input(INPUT_POST, 'dsManifestacao')) {
                    throw new Exception ("Campo \"De acordo do Assinante\" &eacute; de preenchimento obrigat&oacute;rio.");
                }
                if (!filter_input(INPUT_POST, 'password')) {
                    throw new Exception ("Campo \"Senha\" &eacute; de preenchimento obrigat&oacute;rio.");
                }

                $auth = Zend_Auth::getInstance();

                $Usuario = new Autenticacao_Model_Usuario();
                $isUsuarioESenhaValidos = $Usuario->isUsuarioESenhaValidos($auth->getIdentity()->usu_identificacao, $post['password']);
                if (!$isUsuarioESenhaValidos) {
                    throw new Exception ("Senha inv&aacute;lida.");
                }

                $objEnquadramento = new Admissibilidade_Model_Enquadramento();
                $arrayPesquisa = array(
                    'AnoProjeto' => $this->view->projeto['AnoProjeto'],
                    'Sequencial' => $this->view->projeto['Sequencial'],
                    'IdPRONAC' => $get->IdPRONAC
                );
                $dadosEnquadramento = $objEnquadramento->findBy($arrayPesquisa);

                $objModelDocumentoAssinatura = new Assinatura_Model_DbTable_TbDocumentoAssinatura();
                $dadosDocumentoAssinatura = $objModelDocumentoAssinatura->findBy(
                    array(
                        'IdPRONAC' => $get->IdPRONAC,
                        'idTipoDoAtoAdministrativo' => $this->idTipoDoAtoAdministrativo
                    )
                );

                $objTbAssinatura = new Assinatura_Model_DbTable_TbAssinatura();

                $dadosInclusaoAssinatura = array(
                    'idPronac' => $get->IdPRONAC,
                    'idAtoAdministrativo' => $dadosAtoAdministrativoAtual['idAtoAdministrativo'],
                    'idAtoDeGestao' => $dadosEnquadramento['IdEnquadramento'],
                    'dtAssinatura' => $objEnquadramento->getExpressionDate(),
                    'idAssinante' => $auth->getIdentity()->usu_codigo,
                    'dsManifestacao' => $post['dsManifestacao'],
                    'idDocumentoAssinatura' => $dadosDocumentoAssinatura['idDocumentoAssinatura']
                );

                $objTbAssinatura->inserir($dadosInclusaoAssinatura);

                $orgaoDestino = $objTbAtoAdministrativo->obterProximoOrgaoDeDestino($this->idTipoDoAtoAdministrativo, $dadosAtoAdministrativoAtual['idOrdemDaAssinatura']);

                if ($orgaoDestino) {
                    $objTbProjetos = new Projeto_Model_DbTable_Projetos();
                    $objTbProjetos->alterarOrgao($orgaoDestino, $get->IdPRONAC);
                }

                parent::message('Projeto assinado com sucesso!', "/assinatura/enquadramento/visualizar-projeto?IdPRONAC={$get->IdPRONAC}", 'CONFIRM');
            }

            $objVerificacao = new Verificacao();
            $this->view->tipoDocumento = $objVerificacao->findBy(array(
                'idVerificacao = ?' => $this->idTipoDoAtoAdministrativo
            ));

            $objTbAtoAdministrativo = new Assinatura_Model_DbTable_TbAtoAdministrativo();
            $this->view->dadosAtoAdministrativo = $objTbAtoAdministrativo->obterPerfilAssinante(
                $this->grupoAtivo->codOrgao,
                $this->grupoAtivo->codGrupo,
                $this->idTipoDoAtoAdministrativo
            );
        } catch (Exception $objException) {
            parent::message($objException->getMessage(), "/assinatura/enquadramento/assinar-projeto?IdPRONAC={$get->IdPRONAC}");
        }
    }

    public function finalizarAssinaturaAction()
    {
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

            parent::message('Projeto finalizado com sucesso!', "/assinatura/enquadramento/gerenciar-projetos", 'CONFIRM');
        } catch (Exception $objException) {
            parent::message($objException->getMessage(), "/assinatura/enquadramento/assinar-projeto?IdPRONAC={$get->IdPRONAC}");
        }
    }

}