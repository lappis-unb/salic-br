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
    public function _initPath(){

        $DIR_BANCO      = "bancos_treinamento";
        $DIR_BANCOP     = "conexao_02";
        $DIR_LIB        = "./library/";                       // bibliotecas
        $DIR_CONFIG     = "./application/configs/$DIR_BANCO.ini"; // configuraççes
        $DIR_CONFIGP    = "./application/configs/config.ini"; // configurações
        $DIR_MODELS     = "./application/model";              // models
        $DIR_SERVICE    = "./application/model/Servico";     // services
        $DIR_TO         = "./application/model/TO/";          // tos
        $DIR_DAO        = "./application/model/DAO/";         // daos
        $DIR_DOMAIN     = "./application/model/domain/";         // domain
        $DIR_TABLE      = "./application/model/Table/";         // table
        $DIR_CONTROLLER = "./application/modules/default/controllers/";        // controles

        /* configuração do caminho dos includes */
        set_include_path('.'
                             . PATH_SEPARATOR . $DIR_MODELS
                             . PATH_SEPARATOR . $DIR_SERVICE
                             . PATH_SEPARATOR . $DIR_TO
                             . PATH_SEPARATOR . $DIR_DAO
                             . PATH_SEPARATOR . $DIR_DOMAIN
                             . PATH_SEPARATOR . $DIR_TABLE
                             . PATH_SEPARATOR . $DIR_CONTROLLER
                             . PATH_SEPARATOR . './library/Zend'
                             . PATH_SEPARATOR . './application/views'
                             . PATH_SEPARATOR . get_include_path());

        /* componente obrigatorio para carregar arquivos, classes e recursos */
        Zend_Loader_Autoloader::getInstance()->setFallbackAutoloader(true);

        /* classes pessoais do ministçrio da cultura */
        require_once "MinC/Loader.php";

        //Registrando variçveis
        Zend_Registry::set('DIR_CONFIG', $DIR_CONFIG); // registra

        /* ambientes: (DES: desenvolvimento - TES: teste - PRO: producao) */
        $AMBIENTE = 'DES';

        /* configura para exibir as mensagens de erro */
        if ($AMBIENTE == 'DES') {
            error_reporting(E_ALL | E_STRICT);
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

    /**
     * _initRegistry
     *
     * @access public
     * @return void
     */
    public function _initRegistry()
    {
        $DIR_CONFIGP    = "./application/configs/config.ini";
        $DIR_BANCOP     = "conexao_02";

        /* configurações do banco de dados */
        $config = new Zend_Config_Ini($DIR_CONFIGP, $DIR_BANCOP);
        $registry = Zend_Registry::getInstance();
        $registry->set('config', $config); // registra

        $db = Zend_Db::factory($config->db);
        Zend_Db_Table::setDefaultAdapter($db);
        Zend_Registry::set('db', $db); // registra
        $profiler = $db->getProfiler();
        $profiler->setEnabled(false);

        /* registra a conexão para mudar em ambiente scriptcase */
        Zend_Registry::set('conexao_banco', $DIR_BANCOP);
    }
}
