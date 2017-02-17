<?php

class Analise_AnaliseController extends Analise_GenericController
{
    private $idUsuario = null;
    private $idPreProjeto = null;
    private $idProjeto = null;

    private $codGrupo = null;
    private $codOrgao = null;

    public function init()
    {
        parent::init();

        # define as permissoes
        $PermissoesGrupo = array();
        $PermissoesGrupo[] = Autenticacao_Model_Grupos::COORDENADOR_ANALISE;
        $PermissoesGrupo[] = Autenticacao_Model_Grupos::TECNICO_ANALISE;


        if (!empty ($_REQUEST['idPreProjeto'])) {
            $this->idPreProjeto = $_REQUEST['idPreProjeto'];
        }
        $auth = Zend_Auth::getInstance(); // instancia da autenticacao

        //parent::perfil(1, $PermissoesGrupo);
        isset($auth->getIdentity()->usu_codigo) ? parent::perfil(1, $PermissoesGrupo) : parent::perfil(4, $PermissoesGrupo);

        isset($auth->getIdentity()->usu_codigo) ? $this->idUsuario = $auth->getIdentity()->usu_codigo : $this->idUsuario = $auth->getIdentity()->IdUsuario;
        //$this->idUsuario = $auth->getIdentity()->usu_codigo;
        $GrupoAtivo = new Zend_Session_Namespace('GrupoAtivo');
        if (isset($auth->getIdentity()->usu_codigo)) {

            $this->codGrupo = $GrupoAtivo->codGrupo;
            $this->codOrgao = $GrupoAtivo->codOrgao;
            $this->codOrgaoSuperior = (!empty($auth->getIdentity()->usu_org_max_superior)) ? $auth->getIdentity()->usu_org_max_superior : null;
        }


    }

    public function exibirAction() {

    }

    public function listarprojetosAction() {

    }

    public function listarProjetosAjaxAction()
    {
        $start = $this->getRequest()->getParam('start');
        $length = $this->getRequest()->getParam('length');
        $draw = (int)$this->getRequest()->getParam('draw');
        $search = $this->getRequest()->getParam('search');
        $order = $this->getRequest()->getParam('order');
        $columns = $this->getRequest()->getParam('columns');

        $order = ($order[0]['dir'] != 1) ? array($columns[$order[0]['column']]['name'] . ' '. $order[0]['dir']) : array("DtSituacao DESC");

        $vwPainelAvaliar = new Analise_Model_DbTable_vwProjetosAdequadosRealidadeExecucao();

        if (Autenticacao_Model_Grupos::TECNICO_ANALISE==$this->codGrupo){
            $where['idUsuario = ?'] = $this->idUsuario;
        }

        $orgao = new Orgaos();
        $orgao = $orgao->codigoOrgaoSuperior($this->codOrgao);
        $orgaoSuperior = $orgao[0]['Codigo'];
        $where['Orgao = ?'] = $orgaoSuperior;

        $projetos = $vwPainelAvaliar->projetos($where, $order, $start, $length, $search);
        $recordsTotal = 0;
        $recordsFiltered = 0;
        if (!empty($projetos)) {
            $zDate = new Zend_Date();
            foreach($projetos as $key => $projetos){
//                $zDate->set($projetos->DtMovimentacao);

                $projetos->NomeProjeto = utf8_encode($projetos->NomeProjeto);
                $projetos->Tecnico = utf8_encode($projetos->Tecnico);
                $projetos->Segmento = utf8_encode($projetos->Segmento);
                $projetos->Proponente = utf8_encode($projetos->Proponente);
                $projetos->Enquadramento = utf8_encode($projetos->Enquadramento);
//                $projetos->DtMovimentacao= $zDate->toString('dd/MM/y h:m');
                $aux[$key] = $projetos;
            }

            $recordsTotal = $vwPainelAvaliar->projetosTotal($where);
            $recordsFiltered = $vwPainelAvaliar->projetosTotal($where, null, null, null, $search);
        }

        $this->_helper->json(array(
            "data" => !empty($aux) ? $aux : 0,
            'recordsTotal' => $recordsTotal ? $recordsTotal->total : 0,
            'draw' => $draw,
            'recordsFiltered' => $recordsFiltered ? $recordsFiltered->total : 0 ));
    }
}