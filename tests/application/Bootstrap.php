<?php
/**
 * Arquivo principal da aplicação (bootstrap)
 * Define todos os caminhos onde os arquivos estão armazenados
 * Carrega as classes do Zend utilizadas durante toda a aplicação
 * @author Equipe RUP - Politec
 * @author wouerner <wouerner@gmail.com>
 * @since 29/03/2010
 * @version 0.1
 * @copyright ç 2010 - Ministçrio da Cultura - Todos os direitos reservados.
 * @link http://www.cultura.gov.br
 */

define('APPLICATION_PATH', realpath(__DIR__ . '/../..' . DIRECTORY_SEPARATOR . 'application'));

/* configuração do caminho dos includes */
set_include_path('.'. PATH_SEPARATOR . './library/'
                                     . PATH_SEPARATOR . get_include_path());
/** Zend_Application */
require_once APPLICATION_PATH.'/../library/Zend/Application.php';

require_once APPLICATION_PATH . '/../vendor/autoload.php';


// Create application, bootstrap, and run

require_once __DIR__ . '/../library/BaseTestCase.php';
