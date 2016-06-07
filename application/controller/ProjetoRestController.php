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
        $this->setPublicMethod('get');
        $this->setPublicMethod('index');
        parent::init();
    }

    public function postAction(){}
    
    public function indexAction(){
        $next = $this->_request->getParam('next');
        $offset = $this->_request->getParam('offset');
        $total = $this->_request->getParam('total');
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
        $objParam = (object) array(
            'next' => $next,
            'offset' => $offset,
            'idUsuario' => (int)$idUsuario,
            'idProponente' => $idProponente,
            'pronac' => $pronac,
            'cgcCpf' => $cgcCpf,
            'nomeProponente' => $nomeProponente);
        # Verifica se existe necessidade de buscar o número total de registros da consulta
        if(!$total){
            $total = $modelProjeto->buscarTotalListarProjetosDeUsuario($objParam);
        }
        # Busca os dados da lista
        $objListaRs = $modelProjeto->listarProjetosDeUsuario($objParam);
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
        $this->getResponse()->setHttpResponseCode(200)->setBody(json_encode(array(
            'list' => $listaProjeto,
            'total' => (int)$total)
        ));
    }

    public function getAction(){
        $pronac = $this->_request->getParam('id');
        $modelProjeto = new Projetos();
        $resultado = $modelProjeto->buscarPorPronac($pronac);
        $projeto = (object) $resultado->toArray();
        if($projeto){
            # Busca lancamentos no Extrato Bancário
            $listaResult = $modelProjeto->buscarAnoExtratoDeProjeto($pronac);
            $listaAno = $listaResult->toArray();
            $numeroLancamentoExtrato = count($listaAno);
            
            # Formatando dados
            $projeto->NomeProjeto = utf8_encode($projeto->NomeProjeto);
            $projeto->CNPJCPF = mascara::addMaskCpfCnpj($projeto->CNPJCPF);
            $projeto->Proponente = utf8_encode($projeto->Proponente);
            $projeto->Area = utf8_encode($projeto->Area);
            $projeto->Segmento = utf8_encode($projeto->Segmento);
            $projeto->Situacao = utf8_encode($projeto->Situacao);
            $projeto->Enquadramento = utf8_encode($projeto->Enquadramento);
            $projeto->Agencia = $this->formatarAgencia($projeto->Agencia);
            $projeto->Conta = $this->formatarContaCorrente($projeto->Conta);
            $projeto->stConta = $this->formatarSituacaoConta($projeto);
            $projeto->dtFimCaptacao = $projeto->dtFimCaptacao? date('d/m/Y',strtotime($projeto->dtFimCaptacao)): NULL;
            $projeto->DtFimExecucao = $projeto->DtFimExecucao? date('d/m/Y',strtotime($projeto->DtFimExecucao)): NULL;
            $projeto->ValorAprovado = number_format($projeto->ValorAprovado, 2, ',', '.');
            $projeto->ValorProjeto = number_format($projeto->ValorProjeto, 2, ',', '.');
            $projeto->ValorCaptado = number_format($projeto->ValorCaptado, 2, ',', '.');
            $projeto->VlComprovado = number_format($projeto->VlComprovado, 2, ',', '.');
            $projeto->PercCaptado = number_format($projeto->PercCaptado, 2, ',', '.');
            $projeto->ResumoProjeto = utf8_encode($projeto->ResumoProjeto);
            $projeto->nuLancamento = $numeroLancamentoExtrato;
        }

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
        $resultado = NULL;
        if($conta){
            # Retira os zeros à esquerda.
            $resultado = (int)$conta;
            # Numero de caracteres.
            $qtdCarecteres = strlen($resultado);
            # Numero principal da CC
            $numeroPrincipal = substr($resultado, 0, ((int)$qtdCarecteres)-1);
            $numero = number_format($numeroPrincipal, 0, '.', '.');
            # Digito da CC
            $digito = substr($resultado, ((int) $qtdCarecteres) -1, $qtdCarecteres);
            # Inserindo traço
            $resultado = $numero. '-'. $digito;
        }
        
        return $resultado;
    }
    
    /**
     * Regra de visualização para formatar o número da Agência.
     * 
     * @param string $agencia
     * @return string
     */
    protected function formatarAgencia($agencia) {
        $resultado = NULL;
        if($agencia){
            $qtdNumero = strlen($agencia);
            $resultado = substr($agencia, 0, ((int)$qtdNumero)-1). '-'. substr($agencia, ((int) $qtdNumero) -1, $qtdNumero);
        }
        
        return $resultado;
    }

    public function putAction(){}

    public function deleteAction(){}

}