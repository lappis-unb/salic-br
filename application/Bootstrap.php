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
 * @author  Vinícius Feitosa da Silva <viniciusfesil@gmail.com>
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
     * _initView
     *
     * @access public
     * @return void
     */
    public function _initView()
    {
        /* configuraççes do layout padrão do sistema */
        Zend_Layout::startMvc(array(
                'layout'     => 'layout',
                'layoutPath' => APPLICATION_PATH.'/layout/',
                'contentKey' => 'content'));

        # paginacao
        Zend_View_Helper_PaginationControl::setDefaultViewPartial('paginacao/paginacaoMinc.phtml');
    }

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
        if(file_exists('vendor/autoload.php')) {
            require_once 'vendor/autoload.php';
        }
    }


    /**
     * @return Zend_Config
     * @author  Ruy Junior Ferreira Silva <ruyjfs@gmail.com>
     */
    public function _initConfig()
    {
        $config = new Zend_Config($this->getOptions(), true);
        Zend_Registry::set('config', $config);
        return $config;
    }

    /**
     * _initRoutes Texto de descri��o do m�todo.
     *
     * @access public
     * @return void
     */
    public function _initRoutes()
    {
        $controllerFront = Zend_Controller_Front::getInstance();
        $restRoute = new Zend_Rest_Route(
            $controllerFront,
            array(),
            array(
                'default' => array(
                    'proponente-autenticacao-rest',
                    'proponente-rest',
                    'projeto-rest',
                    'dispositivo-movel-rest',
                    'mensagem-rest',
                    'diligencia-rest',
                    'projeto-extrato-rest',
                    'projeto-extrato-ano-rest',
                    'projeto-extrato-mes-rest'
        )));
        $controllerFront->getRouter()->addRoute('rest', $restRoute);
    }
}
