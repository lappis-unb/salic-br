<?php
/**
 * PrincipalController
 * @author Equipe RUP - Politec
 * @since 29/03/2010
 * @package application
 * @subpackage application.controllers
 * @link http://www.cultura.gov.br
 */

class PrincipalController extends MinC_Controller_Action_Abstract
{
    /**
     * Reescreve o método init()
     * @access public
     * @param void
     * @return void
     */
    public function init()
    {
        $this->view->title = "Salic - Sistema de Apoio ás Leis de Incentivo é Cultura"; // tétulo da pégina
        $auth              = Zend_Auth::getInstance(); // pega a autenticaééo
        $Usuario           = new Autenticacao_Model_Usuario(); // objeto usuério
        $GrupoAtivo        = new Zend_Session_Namespace('GrupoAtivo'); // cria a sesséo com o grupo ativo

        parent::perfil(); // autenticaééo zend

        parent::init(); // chama o init() do pai GenericControllerNew
    }

    /**
     * Pégina inicial do sistema
     * @access public
     * @param void
     * @return void
     */
    public function indexAction()
    {
        $this->view->saudacao = Data::saudacao() . "! " . Data::mostraData() . ".";

        $tbComunicados = new tbComunicados();

        $where['stEstado = ?'] = 1;
        $where['stOpcao = ?'] = 0;
        $ordem = array();

        $rs = $tbComunicados->listarComunicados($where, $ordem);

        $this->view->comunicados = $rs;
    }

    public function abasAction() {} // fecha método abasAction()

    public function textoAction() {} // fecha método textoAction()

    public function gridAction() {} // fecha método gridAction()

    public function caixadetextoAction() {} // fecha método caixadetextoAction()

    public function modalAction() {} // fecha método modalAction()

    public function botoesAction() {} // fecha método botoesAction()

    public function exemplosAction() {} // fecha método exemplosAction()

    /**
     * Método listarComunicados()
     * @access public
     * @param void
     * @return List
     */
    public function listarComunicadosAction()
    {
        //header("Content-Type: text/html; charset=ISO-8859-1");
        $this->_helper->layout->disableLayout();
        $post = Zend_Registry::get('post');
        $this->intTamPag = 1;

        $tbComunicados = new tbComunicados();

        $where = array();

        $periodo1 = $this->_request->getParam("periodo1");
        $periodo2 = $this->_request->getParam("periodo2");
        $stEstado = $this->_request->getParam("stEstado");
        $stOpcao  = $this->_request->getParam("stOpcao");

        if(!empty($periodo1) && !empty($periodo1))
        {
            $where['dtInicioVigencia >= ?']  = $periodo1;
            $where['dtTerminoVigencia <= ?'] = $periodo2;
        }

        if($stEstado != '')
        {
            $where['stEstado = ?'] = $stEstado;
        }

        if($stOpcao != '')
        {
            $where['stOpcao = ?'] = $stOpcao;
        }


        $pag = 1;
        if (isset($post->pag)) $pag = $post->pag;
        if (isset($post->tamPag)) $this->intTamPag = $post->tamPag;
        $inicio = ($pag>1) ? ($pag-1)*$this->intTamPag : 0;
        $fim    = $inicio + $this->intTamPag;

        $total = $tbComunicados->listarComunicados($where, array(), null, null, true);

        $totalPag = (int)(($total % $this->intTamPag == 0)?($total/$this->intTamPag):(($total/$this->intTamPag)+1));
        $tamanho = ($fim > $total) ? $total - $inicio : $this->intTamPag;
        if ($fim>$total) $fim = $total;

        $ordem = array("6 DESC");
        $rs = $tbComunicados->listarComunicados($where, $ordem, $tamanho, $inicio);

        $this->view->registros 		  = $rs;
        $this->view->pag 			  = $pag;
        $this->view->total 			  = $total;
        $this->view->inicio 		  = ($inicio+1);
        $this->view->fim 			  = $fim;
        $this->view->totalPag 		  = $totalPag;
        $this->view->parametrosBusca  = $_POST;
    }

	/**
	 * Método buscarProjeto()
	 * @access public
	 * @param void
	 * @return void
	 */
	public function buscarprojetoAction()
	{
		$Pronac = $this->_request->getParam("Pronac");

		if(!empty($Pronac)){
			$proj = new Projetos();
            $resp = $proj->buscarIdPronac($Pronac);

            if(!empty($resp)){
            	$this->_redirect('consultardadosprojeto/index?idPronac='.$resp->IdPRONAC);
            }else{
            	parent::message("Nenhum projeto encontrado com o n&uacute;mero de Pronac informado.", "principal/index", "ERROR");
            }

		}else{
			parent::message("Informe o Pronac.", "principal/index", "ERROR");
		}
	}
}
