<?php
class CepController extends MinC_Controller_Action_Abstract
{
    public function cepAction()
    {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);

        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV);
        $strCharset = $config->resources->db->params->charset;
        $this->view->charset = $strCharset;
        header('Content-type: text/html; charset=' . $strCharset);
        $this->_helper->layout->disableLayout();

        $get = Zend_Registry::get('get');
        $cep = Mascara::delMaskCEP(Seguranca::tratarVarAjaxUFT8($get->cep));

        $cepObj = new Cep();
        $resultadoBuscaCep = $cepObj->buscarCEP($cep);

        $resultado = [];
        if ($resultadoBuscaCep) {
            $resultado['endereco'] = $resultadoBuscaCep['logradouro'];
            $resultado['complemento'] = $resultadoBuscaCep['tipo_logradouro'];
            $resultado['bairro'] = $resultadoBuscaCep['bairro'];
            $resultado['uf'] = $resultadoBuscaCep['uf'];
            $resultado['cod_cidade'] = $resultadoBuscaCep['idcidademunicipios'];
            $resultado['cidade'] = $resultadoBuscaCep['dscidademunicipios'];
            if (empty($resultadoBuscaCep['idcidademunicipios']) || empty($resultadoBuscaCep['dscidademunicipios'])) {
                $resultado['cod_cidade'] = $resultadoBuscaCep['idcidadeuf'];
                $resultado['cidade'] = $resultadoBuscaCep['dscidadeuf'];
            }
        }
        
        $this->_helper->json($resultado);
    }
}
