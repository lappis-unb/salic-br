<?php

/**
 * @package Controller
 * @author  Wouerner <wouerner@gmail.com>
 * @author  VinÃ­cius Feitosa da Silva <viniciusfesil@gmail.com>
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

            $get = Zend_Registry::get('get');
            if (!isset($get->pronac) || empty($get->pronac)) {
                throw new Exception("Número de PRONAC não informado.");
            }
            $idPronac = $get->pronac;

            $objProjeto = new Projetos();
            $whereProjeto['IdPRONAC '] = $idPronac;

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

            //$this->consolidacao[0]->ResumoParecer
        } catch (Exception $objException) {
            parent::message($objException->getMessage(), "/admissibilidade/enquadramento/listar");
        }
    }
}
