<?php
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap

{

    protected $_cache;

    /**
     * @name _initCoreCache
     *
     * @author Ruy Junior Ferreira Silva <ruyjfs@gmail.com>
     * @since  10/01/2016
     */
    protected function _initCoreCache()
    {
        $frontendOptions = array(
            'lifetime' => 7200, // cache lifetime of 2 hours
            'automatic_serialization' => true
        );
        $backendOptions = array(
//            'cache_dir' => APPLICATION_PATH . '/../data/cache/' // Directory where to put the cache files
        );

        // getting a Zend_Cache_Core object
        $this->_cache = Zend_Cache::factory(
            'Core',
            'File',
            $frontendOptions,
            $backendOptions
        );
    }

    /**
     * @name _initConfig
     * @return Zend_Config
     * @author  Ruy Junior Ferreira Silva <ruyjfs@gmail.com>
     */
    public function _initConfig()
    {
        $config = new Zend_Config($this->getOptions(), true);
//        Zend_Registry::set('config', $config);

        $cacheId = APPLICATION_ENV . __FUNCTION__;
        if (APPLICATION_ENV == 'development' || APPLICATION_ENV == 'testing') $this->_cache->remove($cacheId);
//        if ( ($configObject = $this->_cache->load($cacheId)) === false ) {
            $configPath = APPLICATION_PATH . '/configs';
            $configFileExt = '.ini';
            if ($handle = opendir($configPath)) {
                while (false !== ($file = readdir($handle))) {
                    if ($configName = strstr($file, $configFileExt, true)) {
                        if ($configName == 'application' || $configName == 'exemplo-application') continue;
                        $config->{$configName} = new Zend_Config_Ini($configPath . '/' . $file, null, array(
                          'allowModifications' => true,
//                          'nestSeparator'      => ':',
//                          'skipExtends'        => false
                        ));

                    }
                }
                closedir($handle);
            }
//            $configObject->{'application'}->{APPLICATION_ENV} = new Zend_Config($this->getApplication()->getOptions());
//            $this->_cache->save($config, $cacheId);
//        }

        Zend_Registry::set('config', $config);
        return $config;
    }

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
    public function _initLocal()
    {
        /* formato, idioma e localizacao */
        setlocale(LC_ALL, 'pt_BR');
//        setlocale(LC_CTYPE, 'de_DE.iso-8859-1');
        date_default_timezone_set('America/Sao_Paulo');

        // Registra currency que sera usado automaticamente pelo zend
        Zend_Registry::set('Zend_Currency', new Zend_Currency('pt_BR'));
    }

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
     *
     * @todo verificar uma forma de nao adicionar o path de helper na mao e sim utilizando apenas o application.ini.
     */
    public function _initView()
    {
        /* configura&ccedil;&ccedil;es do layout padr&atilde;o do sistema */
        Zend_Layout::startMvc(array(
                'layout'     => 'layout',
                'layoutPath' => APPLICATION_PATH.'/layout/',
                'contentKey' => 'content'));
        # paginacao
        Zend_View_Helper_PaginationControl::setDefaultViewPartial('paginacao/paginacaoMinc.phtml');

        // Initialize view
        $view         = new Zend_View();
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper(
            'ViewRenderer'
        );
        $viewRenderer->setView($view);
        $view->addHelperPath(
            APPLICATION_PATH . '/../library/MinC/View/Helper/',
            'MinC_View_Helper_'
        );
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
                    'dispositivo-movel-rest',
                    'mensagem-rest',
                    'diligencia-rest',
                    'segmento-cultural-rest',
                    'projeto-extrato-rest',
                    'projeto-extrato-ano-rest',
                    'projeto-extrato-mes-rest'
                )));
        $controller->getRouter()->addRoute('rest', $restRoute);
    }

    /**
     * @author Vin&iacute;cius Feitosa da Silva <viniciusfesil@mail.com>
     * @return void
     */
    public function _initCarregarDependenciasComposer()
    {
        if(file_exists('vendor/autoload.php')) {
            require_once 'vendor/autoload.php';
        }
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

    /**
     * Adiciona os helpers a controller
     */
    protected function _initController()
    {
        // Add helpers prefixed with Helper in Plugins/Helpers/
//        Zend_Controller_Action_HelperBroker::addPath('./Plugins/Helpers',

//            $breadCrumb = new MinC_Controller_Action_Helper_BreadCrumb();
//        Zend_Controller_Action_HelperBroker::addHelper($breadCrumb);
//            $breadCrumb = new Zend_View_Helper_BreadCrumb();
//        echo '<pre>';
//        var_dump($breadCrumb);
//        exit;
//            Zend_Controller_Action_HelperBroker::addHelper($breadCrumb);
    }


    public function _initLayouts()
    {
        /* configura&ccedil;&ccedil;es do layout padr&atilde;o do sistema */
//        Zend_Layout::startMvc(array(
//            'layout'     => 'layout',
//            'layoutPath' => APPLICATION_PATH.'/layout/',
//            'contentKey' => 'content'));
//        # paginacao
//        Zend_View_Helper_PaginationControl::setDefaultViewPartial('paginacao/paginacaoMinc.phtml');

        // Initialize view
//        $view         = new Zend_View();
//        $layout         = new Zend_Layout();
//        echo '<pre>';
//        var_dump($layout->name);
//        exit;
//        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper(
//            'ViewRenderer'
//        );
//        $viewRenderer->setView($view);
//        $view->addHelperPath(
//            APPLICATION_PATH . '/../library/MinC/View/Helper/',
//            'MinC_View_Helper_'
//        );
    }

}
