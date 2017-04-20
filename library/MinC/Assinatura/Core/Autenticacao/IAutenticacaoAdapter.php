<?php

/**
 * Class MinC_Assinatura_Model_Assinatura
 * @author Vinícius Feitosa da Silva <viniciusfesil@mail.com>
 * @since 19/04/2017
 */
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