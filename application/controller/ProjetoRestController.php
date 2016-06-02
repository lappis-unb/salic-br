<?php

/**
 * Dados do proponente via REST
 * 
 * @version 1.0
 * @package application
 * @subpackage application.controller
 * @link http://www.cultura.gov.br
 * @copyright © 2016 - Ministério da Cultura - Todos os direitos reservados.
 */
class ProjetoRestController extends AbstractRestController {
    
    public function init(){
        $this->setPublicMethod('GET');
        $this->setPublicMethod('index');
        parent::init();
    }

    public function postAction(){}
    
    public function indexAction(){
        $idProponente = $this->_request->getParam('proponente');
        $pronac = $this->_request->getParam('pronac');
        $cgcCpf = $this->_request->getParam('cgcCpf');
        $nomeProponente = $this->_request->getParam('nomeProponente');
        $idUsuario = NULL;
        if($this->usuario){
            $idUsuario = $this->usuario->IdUsuario;
        }
        
        $listaProjeto = array();
        $modelProjeto = new Projetos();
        $objListaRs = $modelProjeto->listarProjetosDeUsuario(
            (int)$idUsuario,
            $idProponente,
            $pronac,
            $cgcCpf,
            $nomeProponente);
        if($objListaRs){
            $arrListaRs = $objListaRs->toArray();
            if($arrListaRs){
                foreach ($arrListaRs as $projeto) {
                    $projeto['NomeProjeto'] = utf8_encode($projeto['NomeProjeto']);
                    $listaProjeto[] = (object)$projeto;
                }
            }
        }

        # Resposta do serviço.
        $this->getResponse()->setHttpResponseCode(200)->setBody(json_encode($listaProjeto));
    }

    public function getAction(){
        $pronac = $this->_request->getParam('id');
        $modelProjeto = new Projetos();
        $resultado = $modelProjeto->buscarPorPronac($pronac);
//        $resultado = $modelProjeto->buscarPorPronac(614); # TESTE
        $projeto = (object) $resultado->toArray();
        if($projeto){
            # Busca lancamentos no Extrato Bancário
            $listaResult = $modelProjeto->buscarAnoExtratoDeProjeto($pronac);
            $listaAno = $listaResult->toArray();
            $numeroLancamentoExtrato = count($listaAno);
            
            # Formatando dados
            $projeto->NomeProjeto = utf8_encode($projeto->NomeProjeto);
            $projeto->Situacao = utf8_encode($projeto->Situacao);
            $projeto->Enquadramento = utf8_encode($projeto->Enquadramento);
            $projeto->stConta = $this->formatarSituacaoConta($projeto);
            $projeto->Conta = $this->formatarContaCorrente($projeto->Conta);
            $projeto->dtFimCaptacao = date('d/m/Y',strtotime($projeto->dtFimCaptacao));
            $projeto->DtFimExecucao = date('d/m/Y',strtotime($projeto->DtFimExecucao));
            $projeto->ValorAprovado = number_format($projeto->ValorAprovado, 2, ',', '.');
            $projeto->ValorProjeto = number_format($projeto->ValorProjeto, 2, ',', '.');
            $projeto->ValorCaptado = number_format($projeto->ValorCaptado, 2, ',', '.');
            $projeto->VlComprovado = number_format($projeto->VlComprovado, 2, ',', '.');
            $projeto->PercCaptado = number_format($projeto->PercCaptado, 2, ',', '.');
            $projeto->Area = utf8_encode($projeto->Area);
            $projeto->Segmento = utf8_encode($projeto->Segmento);
            $projeto->ResumoProjeto = utf8_encode($projeto->ResumoProjeto);
            $projeto->nuLancamento = $numeroLancamentoExtrato;
        }
//xd($projeto);
        # Resposta do serviço.
        $this->getResponse()->setHttpResponseCode(200)->setBody(json_encode($projeto));
    }

    /**
     * Regra de visualização para formatar a descrição da conta.
     * 
     * @param stdClass $projeto
     * @return string
     */
    protected function formatarSituacaoConta($projeto) {
        $descricao = '';
        switch($projeto->stConta) {
            case 'LIBE':
                $descricao = 'Liberado';
            break;
            case 'BLOQ':
                $descricao = 'Bloqueado';
            break;
            default:
                $descricao = 'Conta Inexistente';
        }
        if(!(int)$projeto->Conta){
            $descricao = '-';
        }
        
        return $descricao;
    }

    /**
     * Regra de visualização para formatar o número da conta corrente.
     * 
     * @param string $conta
     * @return string
     */
    protected function formatarContaCorrente($conta) {
        $resultado = (int)$conta;
        if($resultado){
            $numero = strlen($resultado);
            $resultado = substr($resultado, 0, ((int)$numero)-1). '-'. substr($resultado, ((int) $numero) -1, $numero);
        }
        
        return $resultado;
    }

    public function putAction(){}

    public function deleteAction(){}

}