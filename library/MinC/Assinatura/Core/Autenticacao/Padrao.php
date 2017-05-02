<?php

class MinC_Assinatura_Core_Autenticacao_Padrao implements MinC_Assinatura_Core_Autenticacao_IAutenticacaoAdapter
{

    /**
     * @var string $usuario
     */
    private $login;

    /**
     * @var string $senha
     */
    private $senha;

    /**
     * @var array $senha
     */
    private $usuario;

    public function __construct($login, $senha)
    {
        $this->login = $login;
        $this->senha = $senha;
        $this->usuario = new Autenticacao_Model_Usuario();
    }

    /**
     * @return boolean
     */
    public function autenticar()
    {
        $isUsuarioESenhaValidos = $this->usuario->isUsuarioESenhaValidos($this->login, $this->senha);
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
}