<?php
/**
 * Bootstrap
 *
 * @uses Zend
 * @uses _Application_Bootstrap_Bootstrap
 * @package Config
 * @version 0.1
 * @author  wouerner <wouerner@gmail.com>
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
        $strBancoAmbiente      = "bancos_treinamento";

        /* configuração do caminho dos includes */
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

        /* classes pessoais do ministçrio da cultura */
        require_once "MinC/Loader.php";



        //Registrando variçveis
        Zend_Registry::set('DIR_CONFIG', APPLICATION_PATH . '/configs/' . $strBancoAmbiente . '.ini'); // registra

        /* ambientes: (DES: desenvolvimento - TES: teste - PRO: producao) */
        $AMBIENTE = 'DES';

        /* configura para exibir as mensagens de erro */
        if ($AMBIENTE == 'DES') {
            ini_set('display_errors', true);
            error_reporting(E_ALL | E_STRICT);
            #if(getenv("APPLICATION_ENV") == 'development') {
            require_once 'vendor/autoload.php';
            #}
        }
        Zend_Registry::set('ambiente', $AMBIENTE);
    }

    /**
     * _initLocal
     *
     * @access public
     * @return void
     */
    public function _initLocal()
    {
        /* formato, idioma e localização */
        setlocale(LC_ALL, 'pt_BR');
        setlocale(LC_CTYPE, 'de_DE.iso-8859-1');
        date_default_timezone_set('America/Sao_Paulo');

        // Registra currency que será usado automáticamente pelo zend
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
        /* manipulação de sessão */
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
        /* variçveis para pegar dados vindos via get e post */
        $filter = new Zend_Filter();
        $filter->addFilter(new Zend_Filter_StringTrim()); // retira espaãos antes e depois
        $filter->addFilter(new Zend_Filter_StripTags()); // retira cçdigo html e etc
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
    public function _initRegistry()
    {
        $strConexao     = "conexao_02";

        /* configurações do banco de dados */
        $config = new Zend_Config_Ini(APPLICATION_PATH. '/configs/config.ini', $strConexao);
        $registry = Zend_Registry::getInstance();
        $registry->set('config', $config); // registra

        $db = Zend_Db::factory($config->db);
        Zend_Db_Table::setDefaultAdapter($db);
        Zend_Registry::set('db', $db); // registra
        $profiler = $db->getProfiler();
        $profiler->setEnabled(false);

        /* registra a conexão para mudar em ambiente scriptcase */
        Zend_Registry::set('conexao_banco', $strConexao);
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
}
