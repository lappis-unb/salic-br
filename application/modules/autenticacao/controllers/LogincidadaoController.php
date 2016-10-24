<?php

/**
 * Classe responsável por fazer a autenticação Utilizando o Login Cidadão.
 * @author Vinícius Feitosa da Silva <viniciusfesil@mail.com>
 * @since 06/10/16 11:25
 */
class Autenticacao_LogincidadaoController extends MinC_Controller_Action_Abstract
{
    private $oauthConfig;

    /**
     * @author Vinícius Feitosa da Silva <viniciusfesil@mail.com>
     * @return void
     */
    public function init()
    {
        $this->oauthConfig = $this->obterConfiguracoesOPAuth();
        Zend_Layout::startMvc(array('layout' => 'layout_login'));
        parent::init();
    }

    /**
     * @author Vinícius Feitosa da Silva <viniciusfesil@mail.com>
     * @return void
     */
    public function indexAction()
    {
        $opauth = new Opauth($this->oauthConfig, false);
        $opauth->run();
    }

    /**
     * @return array
     * @author Vinícius Feitosa da Silva <viniciusfesil@mail.com>
     * @return mixed
     */
    private function obterConfiguracoesOPAuth()
    {
        $oauthConfig = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', "oauth");
        $oauthConfigArray = $oauthConfig->toArray();
        return $oauthConfigArray['config'];
    }

    /**
     * @author Vinícius Feitosa da Silva <viniciusfesil@mail.com>
     * @return void
     */
    public function oauth2Callback()
    {
        $objOputh = new Opauth($this->oauthConfig, false);
        $response = null;

        switch ($objOputh->env['callback_transport']) {
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
        } else {
            if (empty($response['auth']) || empty($response['timestamp']) || empty($response['signature']) || empty($response['auth']['provider']) || empty($response['auth']['uid'])) {
                echo '<strong style="color: red;">Invalid auth response: </strong>Missing key auth response components.' . "<br>\n";
            } elseif (!$objOputh->validate(sha1(print_r($response['auth'], true)), $response['timestamp'], $response['signature'], $reason)) {
                echo '<strong style="color: red;">Invalid auth response: </strong>' . $reason . ".<br>\n";
            } else {
                echo '<strong style="color: green;">OK: </strong>Auth response is validated.' . "<br>\n";
            }
        }

        xd($response);
    }
}