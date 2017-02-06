<?php

class Assinatura_Model_DbTable_TbAtoAdministrativo extends MinC_Db_Table_Abstract
{
    protected $_schema    = 'sac';
    protected $_name      = 'tbAtoAdministrativo';
    protected $_primary   = 'idAtoAdministrativo';

    /**
     * @todo A validação pelo perfil do assinante foi temporariamente comentada
     * porque existem inconsistências no banco de dados.
     */
    public function obterCargoAssinante($idOrgaoDoAssinante, $idPerfilDoAssinante, $idTipoDoAto) {

        $objQuery = $this->select();
        $objQuery->setIntegrityCheck(false);
        $objQuery->from(
            $this->_name,
            array(
                'idAtoAdministrativo',
                'idTipoDoAto',
                'idCargoDoAssinante',
                'idOrdemDaAssinatura'
            ),
            $this->_schema
        );
        $objQuery->joinInner(
            array('Verificacao' => 'Verificacao'),
            'Verificacao.idVerificacao = tbAtoAdministrativo.idCargoDoAssinante',
            array('dsCargoAssinante' => 'Verificacao.Descricao'),
            $this->getSchema('Agentes')
        );
        $objQuery->where('idOrgaoDoAssinante = ?', $idOrgaoDoAssinante);
//        $objQuery->where('idPerfilDoAssinante = ?', $idPerfilDoAssinante);
        $objQuery->where('idTipoDoAto = ?', $idTipoDoAto);
//xd($objQuery->assemble());
        $result = $this->fetchRow($objQuery);
        if($result) {
            return $result->toArray();
        }
    }

    public function obterQuantidadeMinimaAssinaturas($idTipoDoAto) {
        $objQuery = $this->select();
        $objQuery->setIntegrityCheck(false);
        $objQuery->from(
            $this->_name,
            array(
                'quantidade_assinaturas' => new Zend_Db_Expr(
                    "count(*)"
                )
            ),
            $this->_schema
        );
        $objQuery->where('idTipoDoAto = ?', $idTipoDoAto);
        $result = $this->fetchRow($objQuery);
        if($result) {
            return $result->toArray();
        }
    }

}