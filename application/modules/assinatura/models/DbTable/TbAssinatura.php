<?php

class Assinatura_Model_DbTable_TbAssinatura extends MinC_Db_Table_Abstract
{
    protected $_schema    = 'sac';
    protected $_name      = 'tbAssinatura';
    protected $_primary   = 'idAssinatura';

    public function obterProjetosEnquadrados($codOrgao, $ordenacao = array())
    {
        $query = $this->select();
        $query->setIntegrityCheck(false);
        $query->from(
            array("Projetos" => "Projetos"),
            array(
                'Projetos.nomeProjeto',
                'Projetos.IdPRONAC',
                'Projetos.CgcCpf',
                'Projetos.Area as cdarea',
                'Projetos.ResumoProjeto',
                'Projetos.UfProjeto',
                'Projetos.DtInicioExecucao',
                'Projetos.DtFimExecucao',
                'Projetos.Situacao',
                'Projetos.DtSituacao',
            ),
            $this->_schema
        );
        $query->joinLeft(
            array($this->_name => $this->_name),
            "{$this->_name}.idPronac = Projetos.IdPRONAC",
            "",
            $this->_schema
        );
        $query->joinInner(
            array('Area' => 'Area'),
            "Area.Codigo = Projetos.Area",
            "Area.Descricao",
            $this->_schema
        );
        $query->joinInner(
          array('Segmento' => 'Segmento'),
          "Segmento.Codigo = Projetos.Segmento",
          array(
              "Segmento.Descricao",
                "Segmento.tp_enquadramento"
          ),
            $this->_schema
        );
        $query->joinLeft(
            array('tbPlanilhaAprovacao' => 'tbPlanilhaAprovacao'),
            "tbPlanilhaAprovacao.IdPRONAC = projetos.IdPRONAC",
            array(
                "vlAprovado" => New Zend_Db_Expr(
                    "tbPlanilhaAprovacao.vlUnitario * tbPlanilhaAprovacao.qtItem * tbPlanilhaAprovacao.nrOcorrencia"
                )
            ),
            $this->_schema
        );
        $query->where("Projetos.Orgao = ?", $codOrgao);
        $query->order($ordenacao);
xd("OBS : Colocar no ZendDB Expression a assinatura");
        return $this->_db->fetchAll($query);
    }

}