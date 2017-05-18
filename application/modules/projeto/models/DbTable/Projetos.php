<?php

/**
 * @todo Mover todas os métodos e alterar todas as referências para da antiga classe para essa.
 */
class Projeto_Model_DbTable_Projetos extends MinC_Db_Table_Abstract
{
    protected $_schema = 'sac';
    protected $_name = 'Projetos';
    protected $_primary = 'IdPRONAC';

    public function alterarOrgao($orgao, $idPronac) {
        $this->update(
            array(
                'Orgao' => $orgao
            ),
            array('IdPRONAC = ?' => $idPronac)
        );
    }

    public function obterValoresProjeto($idPronac) {
        $objQuery = $this->select();
        $objQuery->setIntegrityCheck(false);
        $objQuery->from(
            array(
                'projetos' => $this->_name
            )
            ,array(
                "ValorProposta" => new Zend_Db_Expr("sac.dbo.fnValorSolicitado(projetos.AnoProjeto,projetos.Sequencial)"),
                "ValorSolicitado" => new Zend_Db_Expr("sac.dbo.fnValorSolicitado(projetos.AnoProjeto,projetos.Sequencial)") ,
                "OutrasFontes" => new Zend_Db_Expr("sac.dbo.fnOutrasFontes(projetos.idPronac)"),
                "ValorAprovado" => new Zend_Db_Expr(
                    "case when projetos.Mecanismo ='2' or projetos.Mecanismo ='6'
                        then sac.dbo.fnValorAprovadoConvenio(projetos.AnoProjeto,projetos.Sequencial)
                     else
                        sac.dbo.fnValorAprovado(projetos.AnoProjeto,projetos.Sequencial)
                     end"
                ),
                "ValorProjeto" => new Zend_Db_Expr(
                    "case when projetos.Mecanismo ='2' or projetos.Mecanismo ='6'
                     then sac.dbo.fnValorAprovadoConvenio(projetos.AnoProjeto,projetos.Sequencial)
                     else sac.dbo.fnValorAprovado(projetos.AnoProjeto,projetos.Sequencial) + sac.dbo.fnOutrasFontes(projetos.idPronac)
                      end "
                ),
                "ValorCaptado" => new Zend_Db_Expr("sac.dbo.fnCustoProjeto (projetos.AnoProjeto,projetos.Sequencial)"),
            )
        );
        $objQuery->where('projetos.IdPRONAC = ?', $idPronac);

        return $this->_db->fetchRow($objQuery);
    }

    public function verificarIN2017($idPronac)
    {
        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->from(
            array('projetos' => $this->_name),
            'idPRONAC',
            $this->_schema
        );
        $select->where("CONVERT(CHAR(10), (DtProtocolo),112) >= '20170320' AND idPronac = ?", $idPronac);

        return $this->_db->fetchRow($select);
    }

}
