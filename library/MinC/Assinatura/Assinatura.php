<?php

class MinC_Assinatura_Assinatura
{
    public function criarDocumentoAssinatura($idPronac, $idTipoDoAtoAdministrativo)
    {
        $auth = Zend_Auth::getInstance();

        $conteudo = $this->gerarConteudo($idPronac, $idTipoDoAtoAdministrativo);

        $dadosDocumentoAssinatura = array(
            'IdPRONAC' => $idPronac,
            'idTipoDoAtoAdministrativo' => $idTipoDoAtoAdministrativo,
            'conteudo' => $conteudo,
            'idCriadorDocumento' => $auth->getIdentity()->usu_codigo
        );
        $objModelDocumentoAssinatura = new Assinatura_Model_DbTable_TbDocumentoAssinatura();
        $objModelDocumentoAssinatura->inserir($dadosDocumentoAssinatura);
    }

}