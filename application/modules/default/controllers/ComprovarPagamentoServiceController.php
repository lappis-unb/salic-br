<?php

/**
 * Description of ComprovarPagamentoServiceController
 *
 * @author mikhail
 */
class ComprovarPagamentoServiceController extends ServicoController
{

    /**
     * Action que define a classe de servico que ser&aacute; publicada como servi&ccedil;o
     * pelo protocolo soap
     */
    public function comprovarPagamentoAction()
    {
        $this->setServiceClass('ComprovantePagamentoService');
    }

}
