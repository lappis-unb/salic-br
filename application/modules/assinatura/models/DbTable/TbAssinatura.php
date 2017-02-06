<?php

class Assinatura_Model_DbTable_TbAssinatura extends MinC_Db_Table_Abstract
{
    protected $_schema = 'sac';
    protected $_name = 'tbAssinatura';
    protected $_primary = 'idAssinatura';

    public function obterAssinaturas($idPronac, $idTipoDoAto)
    {
        $query = $this->select();
        $query->setIntegrityCheck(false);

        $queryPlanilhaOrcamentaria = $this->select();
        $queryPlanilhaOrcamentaria->setIntegrityCheck(false);
        $queryPlanilhaOrcamentaria->from(
            array(
                'tbAssinatura' => $this->_name
            ),
            '*',
            $this->_schema
        );
        $queryPlanilhaOrcamentaria->joinInner('tbAtoAdministrativo',
            'tbAssinatura.idAtoAdministrativo = tbAtoAdministrativo.idAtoAdministrativo',
            'tbAtoAdministrativo.*',
            $this->_schema
        );
        $queryPlanilhaOrcamentaria->joinInner('usuarios',
            'tbAssinatura.idAssinante = usuarios.usu_codigo',
            array(
                'usuarios.usu_identificacao',
                'usuarios.usu_nome'
            ),
            $this->getSchema('tabelas')
        );
        $queryPlanilhaOrcamentaria->where("IdPRONAC = ?", $idPronac);
        $queryPlanilhaOrcamentaria->where("tbAtoAdministrativo.idTipoDoAto = ?", $idTipoDoAto);

        return $this->_db->fetchAll($query);
    }

//    public function obterSituacaoAtualAssinaturas($idPronac, $idOrgaoDoAssinante, $idTipoDoAto)
//    {
//        $objQuery = $this->select();
//        $objQuery->setIntegrityCheck(false);
//        $objQuery->from(
//            $this->_name,
//            array(
//                'idAtoAdministrativo',
//                'idTipoDoAto',
//                'idCargoDoAssinante',
//                'idOrdemDaAssinatura'
//            ),
//            $this->_schema
//        );
//        $objQuery->joinInner(
//            array('Verificacao' => 'Verificacao'),
//            'Verificacao.idVerificacao = tbAtoAdministrativo.idCargoDoAssinante',
//            array('dsCargoAssinante' => 'Verificacao.Descricao'),
//            $this->getSchema('Agentes')
//        );
//        $objQuery->where('idOrgaoDoAssinante = ?', $idOrgaoDoAssinante);
////        $objQuery->where('idPerfilDoAssinante = ?', $idPerfilDoAssinante);
//        $objQuery->where('idTipoDoAto = ?', $idTipoDoAto);
////xd($objQuery->assemble());
//        $result = $this->fetchAll($objQuery);
//        if ($result) {
//            return $result->toArray();
//        }
//    }

}