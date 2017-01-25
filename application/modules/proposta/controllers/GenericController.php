<?php

abstract class Proposta_GenericController extends MinC_Controller_Action_Abstract
{

    protected $_proposta;

    protected $_proponente;

    public function init()
    {
        parent::init();

        //recupera ID do pre projeto (proposta)
        $idPreProjeto = $this->getRequest()->getParam('idPreProjeto');

        if (!empty($idPreProjeto)) {

            $arrBusca['idPreProjeto = ?'] = $idPreProjeto;

            $tblPreProjeto = new Proposta_Model_DbTable_PreProjeto();
            $this->_proposta = $tblPreProjeto->buscar($arrBusca)->current();

            if ($this->_proposta) {
                $this->_proposta = array_change_key_case($this->_proposta->toArray());
            }
            $this->view->proposta = $this->_proposta;

            $arrBuscaProponete['a.idagente = ?'] = $this->_proposta['idagente'];
            $tblAgente = new Agente_Model_DbTable_Agentes();
            $this->_proponente = $tblAgente->buscarAgenteNome($arrBuscaProponete)->current();
            if ($this->_proponente) {
                $this->_proponente = array_change_key_case($this->_proponente->toArray());
            }
            $this->view->proponente = $this->_proponente;

            $this->view->url = $this->getRequest()->REQUEST_URI;
            $this->view->isEditarProposta = $this->isEditarProposta($idPreProjeto);
            $this->view->isEditarProjeto = $this->isEditarProjeto($idPreProjeto);
            $this->view->isEditavel = $this->isEditavel($idPreProjeto);

            $layout = array(
                'titleShort' => 'Proposta',
                'titleFull' => 'Proposta Cultural',
                'projeto' => $idPreProjeto,
                'listagem' => array('Lista de propostas' => array('controller' => 'manterpropostaincentivofiscal', 'action' => 'listar-propostas')),
            );

            // Alterar projeto
            if (!empty($this->view->isEditarProjeto)) {
                $tblProjetos = new Projetos();
                $projeto = $tblProjetos->findBy(array('idprojeto = ?' => $idPreProjeto));
                $this->view->projeto = $projeto;

                $layout = array(
                    'titleShort' => 'Projeto',
                    'titleFull' => 'Alterar projeto',
                    'projeto' => $idPreProjeto,
                    'listagem' => array('Lista de projetos' => array('module' => 'default', 'controller' => 'Listarprojetos', 'action' => 'listarprojetos')),
                );

            }
            $this->view->layout = $layout;
        }
    }

    public function isEditarProposta($idPreProjeto)
    {

        if (empty($idPreProjeto))
            return false;

        // Verifica se a proposta estah com o minc
        $tbMovimentacao = new Proposta_Model_DbTable_TbMovimentacao();
        $rsStatusAtual = $tbMovimentacao->findBy(array('idprojeto = ?' => $idPreProjeto, 'stestado = ?' => 0));

        if ($rsStatusAtual['Movimentacao'] == '95')
            return true;

        return false;
    }

    public function isEditarProjeto($idPreProjeto)
    {

        if (empty($idPreProjeto))
            return false;

        // Verifica se o projeto esta na situacao para editar
        $tblProjetos = new Projetos();
        $projeto = $tblProjetos->findBy(array('idprojeto = ?' => $idPreProjeto));

        if ($projeto['Situacao'] == 'B02') // @todo romulo vai criar a situacao correta
            return true;

        return false;
    }

    public function isEditavel($idPreProjeto)
    {
        if (!$this->isEditarProjeto($idPreProjeto) && !$this->isEditarProposta($idPreProjeto))
            return false;

        return true;
    }


}
