<?php
/**
 * Bootstrap
 *
 * @uses Zend
 * @uses _Application_Bootstrap_Bootstrap
 * @package Config
 * @version 0.1
 * @author  wouerner <wouerner@gmail.com>
 * @author  Ruy Junior Ferreira Silva <ruyjfs@gmail.com>
 */
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

    /**
     * _initPath
     *
     * @access public
     * @return void
     */
    public function _initPath()
    {
//        $strBancoAmbiente      = "bancos_treinamento";

        /* configuracao do caminho dos includes */
        set_include_path('.'
                            . PATH_SEPARATOR . APPLICATION_PATH . '/modules/default/controllers'
                            . PATH_SEPARATOR . APPLICATION_PATH . '/modules/default/models'
                            . PATH_SEPARATOR . APPLICATION_PATH . '/modules/default/models/DAO'
                            . PATH_SEPARATOR . APPLICATION_PATH . '/modules/default/models/Exception'
                            . PATH_SEPARATOR . APPLICATION_PATH . '/modules/default/models/Servico'
                            . PATH_SEPARATOR . APPLICATION_PATH . '/modules/default/models/Table'
                            . PATH_SEPARATOR . APPLICATION_PATH . '/modules/default/models/TO'
                            . PATH_SEPARATOR . get_include_path()
        );

        /* componente obrigatorio para carregar arquivos, classes e recursos */
        Zend_Loader_Autoloader::getInstance()->suppressNotFoundWarnings(true)->setFallbackAutoloader(true);

        /* classes pessoais do ministcrio da cultura */
        require_once "MinC/Loader.php";
    }

    /**
     * _initLocal
     *
     * @access public
     * @return void
     */
//    public function _initLocal()
//    {
        /* formato, idioma e localizacao */
//        setlocale(LC_ALL, 'pt_BR');
//        setlocale(LC_CTYPE, 'de_DE.iso-8859-1');
//        date_default_timezone_set('America/Sao_Paulo');

        // Registra currency que sera usado automaticamente pelo zend
//        Zend_Registry::set('Zend_Currency', new Zend_Currency('pt_BR'));
//    }

    /**
     * _initSession
     *
     * @access public
     * @return void
     */
    public function _initSession()
    {
        /* manipulacao de sessao */
        Zend_Session::start();
        Zend_Registry::set('session', new Zend_Session_Namespace()); // registra

    }

    /**
     * _initFilter
     *
     * @access public
     * @return void
     */
    public function _initFilter()
    {
        /* varicveis para pegar dados vindos via get e post */
        $filter = new Zend_Filter();
        $filter->addFilter(new Zend_Filter_StringTrim()); // retira espaaos antes e depois
        $filter->addFilter(new Zend_Filter_StripTags()); // retira ccdigo html e etc
        $options = array('escapeFilter' => $filter);

        /* registra */
        Zend_Registry::set('post', new Zend_Filter_Input(NULL, NULL, $_POST, $options));
        Zend_Registry::set('get',  new Zend_Filter_Input(NULL, NULL, $_GET,  $options));
    }
    

    /**
     * _initRegistry
     *
     * @access public
     * @return void
     */
//    public function _initRegistry()
//    {
//        $strConexao     = "conexao_02";
//
//        /* configuracoes do banco de dados */
//        $config = new Zend_Config_Ini(APPLICATION_PATH. '/configs/config.ini', $strConexao);
//        $registry = Zend_Registry::getInstance();
//        $registry->set('config', $config); // registra
//
//        $db = Zend_Db::factory($config->db);
//        Zend_Db_Table::setDefaultAdapter($db);
//        Zend_Registry::set('db', $db); // registra
//        $profiler = $db->getProfiler();
//        $profiler->setEnabled(false);
//
//        /* registra a conexao para mudar em ambiente scriptcase */
//        Zend_Registry::set('conexao_banco', $strConexao);
//    }

    public function _initRouteRest()
    {
        $controller = Zend_Controller_Front::getInstance();
        $restRoute = new Zend_Rest_Route(
            $controller,
            array(),
            array(
                'default' => array(
                    'proponente-autenticacao-rest',
                    'proponente-rest',
                    'projeto-rest',
                    'projeto-extrato-rest',
                    'projeto-extrato-ano-rest',
                    'projeto-extrato-mes-rest'
                )));
        $controller->getRouter()->addRoute('rest', $restRoute);
    }

    /**
     * @author Vinícius Feitosa da Silva <viniciusfesil@mail.com>
     * @return void
     */
    public function _initCarregarDependenciasComposer()
    {
        if(APPLICATION_ENV == "development") {
            ini_set('display_errors', true);
            error_reporting(E_ALL ^E_NOTICE ^E_WARNING);
        }
        require_once 'vendor/autoload.php';

    }

    /**
     *
     * @name _initDoctype
     *
     * @author Ruy Junior Ferreira Silva <ruyjfs@gmail.com>
     * @since  ${DATE}
     */
//    protected function _initDoctype()
//    {
//        $this->bootstrap('view');
//        $view = $this->getResource('view');
//        $view->doctype('XHTML1_STRICT');
////        $view->headMeta()->setCharset('UTF-8');
//        $view->setEncoding('UTF-8');
//    }
//    protected function _initHelperPath()
//    {
//
//        $view = $this->bootstrap('view')->getResource('view');
//
//        $view->setHelperPath(APPLICATION_PATH . '/views/helpers', 'View_Helper');
//
//    }
//    protected function _initView () {
//        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV);
//        $strCharset = $config->resources->db->params->charset;
//        $view = new Zend_View();
//        // snip...
//        $view->setEncoding($strCharset);
//        // snip...
//        $view->headMeta()->appendHttpEquiv("content-type", "text/html; charset=" . $strCharset);
//        return $view;
//    }

//    protected function _initFrontControllerOutput()
//    {
//        $this->bootstrap('FrontController');
//        $frontController = $this->getResource('FrontController');
////
//        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV);
//        $strCharset = $config->resources->db->params->charset;
////
//        $response = new Zend_Controller_Response_Http;
//        $response->setHeader('Content-Type', 'text/html; charset=' . $strCharset, true);
//        $frontController->setResponse($response);
//        $frontController->setParam('useDefaultControllerAlways', false);
//        return $frontController;
//    }
}
