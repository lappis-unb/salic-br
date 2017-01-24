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

        $queryPlanilhaOrcamentaria = $this->select();
        $queryPlanilhaOrcamentaria->setIntegrityCheck(false);
        $queryPlanilhaOrcamentaria->from('tbPlanilhaAprovacao', array(
            "vlAprovado" => New Zend_Db_Expr(
                "tbPlanilhaAprovacao.vlUnitario * tbPlanilhaAprovacao.qtItem * tbPlanilhaAprovacao.nrOcorrencia"
            )
        ), $this->_schema
        );
        $queryPlanilhaOrcamentaria->where("tbPlanilhaAprovacao.IdPRONAC = projetos.IdPRONAC");

        $query->from(
            array("Projetos" => "Projetos"),
            array(
                'pronac' => New Zend_Db_Expr('Projetos.AnoProjeto + Projetos.Sequencial'),
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
                'dias' => 'DATEDIFF(DAY, projetos.DtSituacao, GETDATE())',
                '(' . $queryPlanilhaOrcamentaria->assemble() . ') as vlAprovado'
            ),
            $this->_schema
        );
        $query->joinInner(
            array('Area' => 'Area'),
            "Area.Codigo = Projetos.Area",
            "Area.Descricao as area",
            $this->_schema
        );
        $query->joinInner(
          array('Segmento' => 'Segmento'),
          "Segmento.Codigo = Projetos.Segmento",
          array(
              "Segmento.Descricao as segmento",
              "Segmento.tp_enquadramento"
          ),
            $this->_schema
        );

//        $query->where("Projetos.Orgao = ?", $codOrgao);
        $query->where("Projetos.Situacao in (?)", array('B04'));
        $query->order($ordenacao);
//xd($this->_db->fetchAll($query));
        return $this->_db->fetchAll($query);
    }

}