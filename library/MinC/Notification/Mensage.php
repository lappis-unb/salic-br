<?php

include_once APPLICATION_PATH.'/../library/Zend/Rest/Client.php';

/**
 * Classe para controlar As notificações enviadas para os dispositivos móveis.
 * 
 * @version 1.0
 * @package application
 * @subpackage application.notification
 * @link http://www.cultura.gov.br
 * @copyright © 2016 - Ministério da Cultura - Todos os direitos reservados.
 */
class Minc_Notification_Mensage{
    
    /**
     * Lista de Ids dos dispositivos dos usuários que receberão a notificação.
     * 
     * @var array 
     */
    protected $listResgistrationIds;
    
    /**
     * Titulo da mensagem.
     * 
     * @var string
     */
    protected $title;
    
    /**
     * Descrição da mensagem.
     * 
     * @var string
     */
    protected $text;

    /**
     * Parametros para exibir os dados da notificação.
     * 
     * @var array
     */
    protected $listParameters;

    /**
     * Url do serviço GCM para enviar notificações.
     * 
     * @var string
     */
    protected $gcmUrl;
    
    /**
     * Chave da aplicação para acessar o serviço GCM.
     * 
     * @var string
     */
    protected $gcmApiKey;
    
    /**
     * Lista de parametros segundo a documentação do serviço que será consumido para o envio de notificações.
     * 
     * @var array
     */
    protected $listParametersService;

    /**
     * Classe realizar requisição e usufruir serviços.
     * 
     * @var Zend_Rest_Client 
     */
    protected $client;

    public function getListResgistrationIds() {
        return $this->listResgistrationIds;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getText() {
        return $this->text;
    }

    public function getListParameters() {
        return $this->listParameters;
    }

    public function getGcmUrl() {
        return $this->gcmUrl;
    }

    public function getGcmApiKey() {
        return $this->gcmApiKey;
    }

    public function getListParametersService() {
        return $this->listParametersService;
    }

    public function getClient() {
        return $this->client;
    }

    public function setListResgistrationIds($listResgistrationIds) {
        $this->listResgistrationIds = $listResgistrationIds;
        return $this;
    }

    public function setTitle($title) {
        $this->title = $title;
        return $this;
    }

    public function setText($text) {
        $this->text = $text;
        return $this;
    }

    public function setListParameters($listParameters) {
        $this->listParameters = $listParameters;
        return $this;
    }

    public function setGcmUrl($gcmUrl) {
        $this->gcmUrl = $gcmUrl;
        return $this;
    }

    public function setGcmApiKey($gcmApiKey) {
        $this->gcmApiKey = $gcmApiKey;
        return $this;
    }

    public function setListParametersService($listParametersService) {
        $this->listParametersService = $listParametersService;
        return $this;
    }

    public function setClient(Zend_Rest_Client $client) {
        $this->client = $client;
        return $this;
    }

    /**
     * Envia notificações para dispositivos móveis.
     * 
     * @param array $listResgistrationIds Lista de Ids dos dispositivos dos usuários que receberão a notificação.
     * @param string $title Titulo da mensagem.
     * @param string $text Descrição da mensagem.
     * @param array $listParameters Parametros para exibir os dados da notificação.
     */
    public function __construct($listResgistrationIds = NULL, $title = NULL, $text = NULL, $listParameters = NULL) {
        $this->listResgistrationIds = $listResgistrationIds;
        $this->title = $title;
        $this->text = $text;
        $this->listParameters = $listParameters;
        $this->loadConfig();
    }

    /**
     * Carrega configurações padrões de envio de notificações.
     * 
     * @return \Minc_Notification_Mensage
     */
    protected function loadConfig(){
        $this->gcmUrl = Zend_Registry::get('config')->resources->view->service->gcmUrl;
        $this->gcmApiKey = Zend_Registry::get('config')->resources->view->service->gcmApiKey;
        $this->client = new Zend_Rest_Client($this->gcmUrl);
        $this->loadListParametersService();
        
        return $this;
    }
    
    /**
     * Carrega configurações dos parametros utilizados para configurar o serviço de envio de notificações.
     * 
     * @return \Minc_Notification_Mensage
     */
    protected function loadListParametersService() {
        $this->listParametersService =  array(
            'priority' => 'high',
            'delay_while_idle' => true,
            'registration_ids' => $this->listResgistrationIds,
            'priority' => 'normal',
            'notification' => array(
                'icon' => 'icon',
                'title' => utf8_encode($this->title),
                'body' => utf8_encode($this->text)
            ),
            'data' => $this->listParameters
        );
        
        return $this;
    }
    
    /**
     * Envia notificação para para dispositivos registrados.
     * 
     * @return array Metadados de envio das notificações.
     */
    public function send() {
        $this->loadListParametersService();
        $this->client
            ->getHttpClient()
                ->setHeaders(
                    array(
                        'Authorization: key='. $this->gcmApiKey,
                        'Content-Type: application/json'))
                ->setRawData(json_encode($this->listParametersService))
                ->setUri($this->gcmUrl);
        $response = json_decode($this->client->getHttpClient()->request('POST')->getBody());

        return $response;
    }
    
}