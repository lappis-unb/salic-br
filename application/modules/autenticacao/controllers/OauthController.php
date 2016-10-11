<?php

/**
 * Classe responsável por fazer a autenticação no sistema através de outros sistemas.
 * @author Vinícius Feitosa da Silva <viniciusfesil@mail.com>
 * @since 06/10/16 11:25
 */
class Autenticacao_OauthController extends MinC_Controller_Action_Abstract
{
    public function init()
    {
        Zend_Layout::startMvc(array('layout' => 'layout_login'));
        parent::init();
    }

    public function indexAction()
    {
        /**
         * Opauth example
         *
         * This is an example on how to instantiate Opauth
         * For this example, Opauth config is loaded from a separate file: opauth.conf.php
            */

        define('OPAUTH_LIB_DIR', dirname(dirname(__FILE__)) . '/lib/Opauth/');

        $config = new Zend_Config_Ini(APPLICATION_PATH. '/configs/config.ini', "oauth");

xd($config);

        $Opauth = new Opauth($config);
    }

    public function oauth2Callback()
    {
        /*
        define('CONF_FILE', dirname(__FILE__) . '/' . 'opauth.conf.php');
        define('OPAUTH_LIB_DIR', dirname(dirname(__FILE__)) . '/lib/Opauth/');


        if (!file_exists(CONF_FILE)) {
            trigger_error('Config file missing at ' . CONF_FILE, E_USER_ERROR);
            exit();
        }
        require CONF_FILE;


        require OPAUTH_LIB_DIR . 'Opauth.php';
        $Opauth = new Opauth($config, false);


        $response = null;

        switch ($Opauth->env['callback_transport']) {
            case 'session':
                session_start();
                $response = $_SESSION['opauth'];
                unset($_SESSION['opauth']);
                break;
            case 'post':
                $response = unserialize(base64_decode($_POST['opauth']));
                break;
            case 'get':
                $response = unserialize(base64_decode($_GET['opauth']));
                break;
            default:
                echo '<strong style="color: red;">Error: </strong>Unsupported callback_transport.' . "<br>\n";
                break;
        }

        if (array_key_exists('error', $response)) {
            echo '<strong style="color: red;">Authentication error: </strong> Opauth returns error auth response.' . "<br>\n";
        }
        else {
            if (empty($response['auth']) || empty($response['timestamp']) || empty($response['signature']) || empty($response['auth']['provider']) || empty($response['auth']['uid'])) {
                echo '<strong style="color: red;">Invalid auth response: </strong>Missing key auth response components.' . "<br>\n";
            } elseif (!$Opauth->validate(sha1(print_r($response['auth'], true)), $response['timestamp'], $response['signature'], $reason)) {
                echo '<strong style="color: red;">Invalid auth response: </strong>' . $reason . ".<br>\n";
            } else {
                echo '<strong style="color: green;">OK: </strong>Auth response is validated.' . "<br>\n";

            }
        }


        echo "<pre>";
        print_r($response);
        echo "</pre>";
        */
    }
}