<?php

interface MinC_Assinatura_Autenticacao_IAutenticacaoAdapter
{
    /**
     * @return boolean
     */
    public function autenticar();

    /**
     * @return array
     */
    public function obterInformacoesAssinante();

    /**
     * @return array
     */
    public function obterTemplateAutenticacao();
}