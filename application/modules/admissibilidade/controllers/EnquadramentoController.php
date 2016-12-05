<?php

/**
 * @package Controller
 * @author  Vinícius Feitosa da Silva <viniciusfesil@gmail.com>
 * @author  Wouerner <wouerner@gmail.com>
 * @since 02/12/2016 16:06
 */
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
        $this->redirect("/admissibilidade/enquadramento/listar");
    }

    /**
     * ListarAction - Lista com Projetos em situção de Enuquadramento.
     *
     * @access public
     * @return void
     * @todo Falta o Romulo criar a situação adequada, usando uma generica para
     * testes.
     */
    public function listarAction()
    {
        $idusuario = $this->auth->getIdentity()->usu_codigo;
        $projeto = new  Projetos();
        $projetos = $projeto->listarPorSituacao('E63');

        $codOrgao = $this->grupoAtivo->codOrgao;
        $this->view->codOrgao = $codOrgao;
        $this->view->idUsuarioLogado = $idusuario;

        $this->view->dados = $projetos;
    }

    public function enquadrarprojetoAction()
    {
        try {
            $post = $this->getRequest()->getPost();

            if (!$post) {
                $get = $this->getRequest()->getParams();
                if (!isset($get['pronac']) || empty($get['pronac'])) {
                    throw new Exception("Número de PRONAC não informado.");
                }
                $this->view->idPronac = $get['pronac'];
                $objProjeto = new Projetos();
                $whereProjeto['IdPRONAC'] = $this->view->idPronac;
                $projeto = $objProjeto->findBy($whereProjeto);
                if (!$projeto) {
                    throw new Exception("PRONAC não encontrado.");
                }

                $mapperArea = new Agente_Model_AreaMapper();
                $this->view->comboareasculturais = $mapperArea->fetchPairs('Codigo', 'Descricao');
                $this->view->projeto = $projeto;

                if(count($this->view->comboareasculturais) < 1) {
                    throw new Exception("Não foram encontradas Áreas Culturais para o PRONAC informado.");
                }

                $this->view->combosegmentosculturais = Segmentocultural::buscarSegmento($projeto['Area']);

                if(count($this->view->combosegmentosculturais) < 1) {
                    throw new Exception("Não foram encontradas Segmentos Culturais para o PRONAC informado.");
                }

                $parecerDAO 			= new Parecer();
                $whereParecer['idPRONAC = ?'] = $this->view->idPronac;
                $buscaParecer 			= $parecerDAO->buscar($whereParecer);
                //$this->consolidacao->ResumoParecer
                // $buscaParecer == descrição
            } else {

                $auth = Zend_Auth::getInstance(); // pega a autenticacao
                $authIdentity = array_change_key_case((array) $auth->getIdentity());

xd($authIdentity);
                $objEnquadramento = new Enquadramento();
                $objEnquadramento->
                    /*
                     * Enquadramento
                     *  > AnoProjeto = $projeto['AnoProjeto']
                     *  > Sequencial = $projeto['Sequencial']
                     *  > Enquadramento = $post['enquadramento_projeto']
                     *  > DtEnquadramento = 'getDate()'
                     *  > Observacao = $post['observacao']
                     *  > Logon = $authIdentity['usu_codigo']
                     */
                xd(123);
            }
        } catch (Exception $objException) {
            parent::message($objException->getMessage(), "/admissibilidade/enquadramento/listar");
        }
    }
}
