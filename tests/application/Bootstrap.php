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
// Include path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    realpath(APPLICATION_PATH . '/../tests'),
    get_include_path(),
)));

define('APPLICATION_ENV', 'testing');

/** Zend_Application */
require_once 'Zend/Application.php';

require_once APPLICATION_PATH . '/../library/vendor/autoload.php';
