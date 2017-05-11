<?php

class MinC_Assinatura_Autenticacao_Padrao implements MinC_Assinatura_Autenticacao_IAutenticacaoAdapter
{
    /**
     * @var Autenticacao_Model_Usuario $usuario
     */
    private $usuario;

    public function __construct($post, $identidadeUsuarioLogado)
    {
        $this->usuario = new Autenticacao_Model_Usuario();
        $this->usuario->setUsuIdentificacao($identidadeUsuarioLogado->usu_codigo);
        $this->usuario->setUsuSenha($post['password']);
    }

    /**
     * @return boolean
     */
    public function autenticar()
    {
        $isUsuarioESenhaValidos = $this->usuario->isUsuarioESenhaValidos();
        if (!$isUsuarioESenhaValidos) {
            throw new Exception ("Usu&aacute;rio ou Senha inv&aacute;lida.");
        }
    }

    /**
     * @return array
     */
    public function obterInformacoesAssinante()
    {
        $usuariosBuscar = $this->usuario->buscar(array('usu_identificacao = ?' => $this->usuario))->current();
        return $usuariosBuscar->toArray();
    }

    /**
     * @return string
     * @todo melhorar meneira de obtenção dos templates de tipos de documento passando como parametro
     * pelo application.ini
     */
    public function obterTemplateAutenticacao()
    {
        $view = new Zend_View();
        $view->setScriptPath(APPLICATION_PATH . '/../library/MinC/Assinatura/Autenticacao/templates');
        return $view->render('padrao.phtml');
    }
}