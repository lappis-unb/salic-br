<?php
/**
 * Login e autenticacao
 * @author Equipe RUP - Politec
 * @since 20/07/2010
 * @version 1.0
 * @package application
 * @subpackage application.controller
 * @link http://www.cultura.gov.br
 * @copyright © 2010 - Ministerio da Cultura - Todos os direitos reservados.
 */
 
class IndexController extends MinC_Controller_Action_Abstract
{
    /**
     * Metodo principal
     * @access public
     * @param void
     * @return void
     */
    public function indexAction()
    {
        $this->redirect("/autenticacao/index/index");
    }

    public function indisponivelAction()
    {

    }

    public function montarPlanilhaOrcamentariaAction()
    {
        
        $this->_helper->layout->disableLayout(); // desabilita o Zend_Layout
        $get = Zend_Registry::get('get');
        
        $GrupoAtivo = new Zend_Session_Namespace('GrupoAtivo'); // cria a sessão com o grupo ativo
        $this->view->idPerfil = $GrupoAtivo->codGrupo;
        
        $this->view->idPronac = $get->idPronac;
        $spPlanilhaOrcamentaria = new spPlanilhaOrcamentaria();
        $planilhaOrcamentaria = $spPlanilhaOrcamentaria->exec($get->idPronac, $get->tipoPlanilha); 
        $planilha = $this->montarPlanilhaOrcamentaria($planilhaOrcamentaria, $get->tipoPlanilha);
        // tipoPlanilha = 0 : Planilha Orcamentaria da Proposta
        // tipoPlanilha = 1 : Planilha Orcamentaria do Proponente
        // tipoPlanilha = 2 : Planilha Orcamentaria do Parecerista
        // tipoPlanilha = 3 : Planilha Orcamentaria Aprovada Ativa
        // tipoPlanilha = 4 : Cortes Orcamentarios Aprovados
        // tipoPlanilha = 5 : Remanejamento menor que 20%
        // tipoPlanilha = 6 : Readequacao
        
        $link = isset($get->link) ? true : false;
        
        $this->montaTela(
            'index/montar-planilha-orcamentaria.phtml', array(
            'tipoPlanilha' => $get->tipoPlanilha,
            'tpPlanilha' => (count($planilhaOrcamentaria)>0) ? isset($planilhaOrcamentaria[0]->tpPlanilha) ? $planilhaOrcamentaria[0]->tpPlanilha : '' : '',
            'planilha' => $planilha,
            'link' => $link
            )
        );
    }

} // fecha class