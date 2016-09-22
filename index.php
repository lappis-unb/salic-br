<?php

/**
 * Arquivo principal da aplicação (bootstrap)
 * Define todos os caminhos onde os arquivos estão armazenados
 * Carrega as classes do Zend utilizadas durante toda a aplicação
 * @author Equipe RUP - Politec
 * @author wouerner <wouerner@gmail.com>
 * @author Ruy Junior Ferreira Silva <ruyjfs@gmail.com>
 * @since 29/03/2010
 * @version 0.1
 * @copyright ç 2010 - Ministçrio da Cultura - Todos os direitos reservados.
 * @link http://www.cultura.gov.br
 */

define('APPLICATION_PATH', realpath(__DIR__ . DIRECTORY_SEPARATOR . 'application'));

defined('APPLICATION_ENV') || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

/* configuração do caminho dos includes */
set_include_path('.'. PATH_SEPARATOR . './library/' . PATH_SEPARATOR . get_include_path());

/** Zend_Application */
require_once APPLICATION_PATH.'/../library/Zend/Application.php';

// Create application, bootstrap, and run
$app = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);

$app->bootstrap()->run();
