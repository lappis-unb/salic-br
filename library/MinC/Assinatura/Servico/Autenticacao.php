<?php

class MinC_Assinatura_Servico_Autenticacao implements MinC_Assinatura_Servico_IServico
{
    private $configuracoesAplicacao = array();
    private $post;
    private $identidadeUsuarioLogado;

    public function __construct(
        array $configuracoesAplicacao,
        stdClass $post,
        stdClass $identidadeUsuarioLogado
    ) {
        $this->configuracoesAplicacao = $configuracoesAplicacao;
        $this->post = $post;
        $this->identidadeUsuarioLogado = $identidadeUsuarioLogado;
        $this->validarDefinicaoDePropriedades();
    }

    protected function validarDefinicaoDePropriedades() {
        if(!is_array($this->configuracoesAplicacao['Assinatura']['Autenticacao'])
            || count($this->configuracoesAplicacao['Assinatura']['Autenticacao']) < 1
            || !$this->configuracoesAplicacao['Assinatura']['Autenticacao']['Metodo'])
        {
            throw new Exception("É necessário informar a propriedade Assiantura.Autenticacao.Metodo no arquivo de configuracao do sistema.");
        }
    }

    /**
     * @return MinC_Assinatura_Autenticacao_IAutenticacaoAdapter
     * @return mixed
     */
    public function obterMetodoAutenticacao() {
        $metodoAutenticacao =  ucfirst($this->configuracoesAplicacao['Assinatura']['Autenticacao']['Metodo']);
        $metodoAutenticacao = "MinC_Assinatura_Autenticacao_{$metodoAutenticacao}";
        return new $metodoAutenticacao($this->post, $this->identidadeUsuarioLogado);
    }
}