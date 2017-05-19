<?php

class MinC_Assinatura_Servico_DocumentoAssinatura implements MinC_Assinatura_Servico_IServico
{
    public function registrarDocumentoAssinatura(Assinatura_Model_TbDocumentoAssinatura $objModelDocumentoAssinatura)
    {
//xd($objModelDocumentoAssinatura);
        $objDocumentoAssinaturaMapper = new Assinatura_Model_TbDocumentoAssinaturaMapper();
        $objDocumentoAssinaturaMapper->save($objModelDocumentoAssinatura);
    }
}