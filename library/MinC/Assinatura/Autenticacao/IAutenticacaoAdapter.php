<?php

interface MinC_Assinatura_Core_Autenticacao_IAutenticacaoAdapter
{
    /**
     * @return boolean
     */
    public function autenticar();

    /**
     * @return array
     */
    public function obterInformacoesAssinante();
}