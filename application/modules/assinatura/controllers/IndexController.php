<?php

class Assinatura_IndexController extends Assinatura_GenericController
{
    private $idTipoDoAtoAdministrativo;

    public function init()
    {
        parent::init();

        $this->auth = Zend_Auth::getInstance();
        $this->grupoAtivo = new Zend_Session_Namespace('GrupoAtivo');
        parent::perfil();
    }

    public function indexAction()
    {
        $this->redirect("/{$this->moduleName}/enquadramento/gerenciar-projetos");
    }

    public function gerenciarAssinaturasAction()
    {
        $this->view->idUsuarioLogado = $this->auth->getIdentity()->usu_codigo;
        $enquadramento = new Admissibilidade_Model_Enquadramento();

        $ordenacao = array("projetos.DtSituacao asc");
        $this->view->dados = $enquadramento->obterProjetosEncaminhadosParaAssinatura($this->grupoAtivo->codOrgao, $ordenacao);
        $this->view->codGrupo = $this->grupoAtivo->codGrupo;
    }

    public function visualizarProjetoAction()
    {
        $get = Zend_Registry::get('get');
        $idPronac = $get->IdPRONAC;
        $idTipoDoAtoAdministrativo = $get->idTipoDoAtoAdministrativo;
        try {
            try {
                if (!filter_input(INPUT_GET, 'IdPRONAC')) {
                    throw new Exception("Identificador do projeto &eacute; necess&aacute;rio para acessar essa funcionalidade.");
                }

                if (!filter_input(INPUT_GET, 'idTipoDoAtoAdministrativo')) {
                    throw new Exception("Identificador do tipo do ato administrativo &eacute; necess&aacute;rio para acessar essa funcionalidade.");
                }
            } catch (Exception $objException) {
                parent::message($objException->getMessage(), "/{$this->moduleName}/index/gerenciar-assinaturas");
            }

            $this->view->IdPRONAC = $idPronac;
            $this->view->idTipoDoAtoAdministrativo = $idTipoDoAtoAdministrativo;

            $objTbProjetos = new Projeto_Model_DbTable_Projetos();
            $this->view->projeto = $objTbProjetos->findBy(array(
                'IdPRONAC' => $idPronac
            ));

            $this->view->valoresProjeto = $objTbProjetos->obterValoresProjeto($idPronac);
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
                'IdPronac = ?' => $idPronac
            ));

            $objEnquadramento = new Admissibilidade_Model_Enquadramento();
            $arrayPesquisa = array(
                'AnoProjeto' => $this->view->projeto['AnoProjeto'],
                'Sequencial' => $this->view->projeto['Sequencial'],
                'IdPRONAC' => $idPronac
            );
            $this->view->dadosEnquadramento = $objEnquadramento->findBy($arrayPesquisa);

            $objAssinatura = new Assinatura_Model_DbTable_TbAssinatura();
            $this->view->assinaturas = $objAssinatura->obterAssinaturas($idPronac, $idTipoDoAtoAdministrativo);

            $objTbAtoAdministrativo = new Assinatura_Model_DbTable_TbAtoAdministrativo();
            $this->view->quantidade_minima_assinaturas = $objTbAtoAdministrativo->obterQuantidadeMinimaAssinaturas($idTipoDoAtoAdministrativo);

            $objModelDocumentoAssinatura = new Assinatura_Model_DbTable_TbDocumentoAssinatura();
            $this->view->documentoAssinatura = $objModelDocumentoAssinatura->findBy(
                array(
                    'IdPRONAC' => $idPronac,
                    'idTipoDoAtoAdministrativo' => $idTipoDoAtoAdministrativo
                )
            );
        } catch (Exception $objException) {
            parent::message($objException->getMessage(), "/{$this->moduleName}/index/visualizar-projeto?IdPRONAC={$idPronac}&idTipoDoAtoAdministrativo={$idTipoDoAtoAdministrativo}");
        }
    }

    /**
     * @todo Revisar
     */
    public function assinarProjetoAction()
    {
        $get = Zend_Registry::get('get');
        $idPronac = $get->IdPRONAC;
        $idTipoDoAtoAdministrativo = $get->idTipoDoAtoAdministrativo;

        try {
            if (!filter_input(INPUT_GET, 'IdPRONAC')) {
                throw new Exception("Identificador do projeto &eacute; necess&aacute;rio para acessar essa funcionalidade.");
            }

            if (!filter_input(INPUT_GET, 'idTipoDoAtoAdministrativo')) {
                throw new Exception("Identificador do tipo do ato administrativo &eacute; necess&aacute;rio para acessar essa funcionalidade.");
            }
        } catch (Exception $objException) {
            parent::message($objException->getMessage(), "/{$this->moduleName}/index/gerenciar-assinaturas");
        }

        try {
            $objTbAtoAdministrativo = new Assinatura_Model_DbTable_TbAtoAdministrativo();

            $this->view->perfilAssinante = $objTbAtoAdministrativo->obterPerfilAssinante(
                $this->grupoAtivo->codOrgao,
                $this->grupoAtivo->codGrupo,
                $idTipoDoAtoAdministrativo
            );

            if (!$this->view->perfilAssinante) {
                throw new Exception ("A fase atual de assinaturas do projeto atual n&atilde;o permite realizar essa opera&ccedil;&atilde;o.");
            }

            if (is_array($get->IdPRONAC)) {
                $idPronacUnidos = implode(',', $get->IdPRONAC);
                $this->redirect("/{$this->moduleName}/enquadramento-assinatura/assinar-projeto?IdPRONAC={$idPronacUnidos}");
            }

            $this->view->IdPRONAC = $get->IdPRONAC;
            $arrayIdPronacs = explode(',', $get->IdPRONAC);
            if (count($arrayIdPronacs) < 1) {
                throw new Exception ("Identificador do projeto &eacute; necess&aacute;rio para acessar essa funcionalidade.");
            }

            $post = $this->getRequest()->getPost();
            if ($post) {

                foreach ($arrayIdPronacs as $idPronac) {
                    $this->assinarProjeto(
                        $idPronac,
                        $post['password'],
                        $post['dsManifestacao']
                    );
                }

                if (count($arrayIdPronacs) > 1) {
                    parent::message(
                        "Projetos assinados com sucesso!",
                        "/{$this->moduleName}/index/gerenciar-assinaturas",
                        'CONFIRM'
                    );
                }
                parent::message(
                    "Projeto assinado com sucesso!",
                    "/{$this->moduleName}/index/visualizar-projeto?IdPRONAC={$idPronac}",
                    'CONFIRM'
                );
            }

            $objProjeto = new Projeto_Model_DbTable_Projetos();
            $this->view->projeto = array();
            foreach ($arrayIdPronacs as $idPronac) {
                $this->view->projeto[] = $objProjeto->findBy(array(
                    'IdPRONAC' => $idPronac
                ));
            }

            $objVerificacao = new Verificacao();
            $this->view->tipoDocumento = $objVerificacao->findBy(array(
                'idVerificacao = ?' => $idTipoDoAtoAdministrativo
            ));

        } catch (Exception $objException) {
            parent::message(
                $objException->getMessage(),
                "/{$this->moduleName}/index/gerenciar-assinaturas"
            );
        }
    }
}