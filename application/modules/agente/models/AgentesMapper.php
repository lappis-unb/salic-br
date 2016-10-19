<?php

/**
 * Class Agente_Model_AgentesMapper
 *
 * @name Agente_Model_AgentesMapper
 * @package Modules/Agente
 * @subpackage Models
 * @version $Id$
 *
 * @author Ruy Junior Ferreira Silva <ruyjfs@gmail.com>
 * @since 01/09/2016
 *
 * @copyright © 2012 - Ministerio da Cultura - Todos os direitos reservados.
 * @link http://salic.cultura.gov.br
 */
class Agente_Model_AgentesMapper extends MinC_Db_Mapper
{
    public function __construct()
    {
        parent::setDbTable('Agente_Model_DbTable_Agentes');
    }

    public function fetchAll()
    {
        $resultSet = $this->getDbTable()->fetchAll();
        $entries   = array();
        foreach ($resultSet as $row) {
            $entry = new Agente_Model_Agentes($row->toArray());
            $entries[] = $entry->toArray();
        }
        return $entries;
    }

    public function isUniqueCpfCnpj($value)
    {
        return ($this->findBy(array('cnpjcpf = ?', $value))) ? true : false;
    }

    public function save(Agente_Model_Agentes $model)
    {
        if (self::isUniqueCpfCnpj($model->getCnpjcpf())) {
            throw new Exception('CNPJ ou CPF j&aacute; cadastrado.');
        } else {
            return parent::save($model);
        }
    }

//    public function fetchAll()
//    {
//        $schemaAgentes = parent::getSchema('agentes');
//        $schemaSac = parent::getSchema('sac');
//
//        $a = [
//            'a.idagente'
//            ,'a.cnpjcpf'
//            ,'a.cnpjcpfsuperior'
//            ,'a.tipopessoa'
//        ];
//
//        $e = [
//            'e.tipologradouro'
//            ,'e.cidade'
//            ,'e.cep as cep'
//            ,'e.uf'
//            ,'e.status'
//            ,'e.tipoendereco'
//            ,'e.idendereco'
//            ,'e.logradouro'
//            ,'e.numero'
//            ,'e.complemento'
//            ,'e.bairro'
//            ,'e.divulgar as divulgarendereco'
//            ,'e.status as enderecocorrespondencia'
//        ];
//
//        $t = [
//            't.sttitular'
//            ,'t.cdarea'
//            ,'t.cdsegmento'
//        ];
//
////        $sql = $db->select()->distinct()->from(['a' => 'agentes'], $a, $schemaAgentes)
//
//
//
//        $select = $this->getDbTable()->select()
//            ->setIntegrityCheck(false)
//            ->distinct()
//            ->from(['a' => 'agentes'], '*', $schemaAgentes)
//            ->joinLeft(['n' => 'nomes'], 'n.idagente = a.idagente', ['*'], $schemaAgentes)
//            ->joinLeft(['e' => 'endereconacional'], 'e.idagente = a.idagente', '*', $schemaAgentes)
//            ->joinLeft(['m' => 'municipios'], 'm.idmunicipioibge = e.cidade', '*', $schemaAgentes)
//            ->joinLeft(['u' => 'uf'], 'u.iduf = e.uf', '*', $schemaAgentes)
//            ->joinLeft(['ve' => 'verificacao'], 've.idverificacao = e.tipoendereco', '*', $schemaAgentes)
//            ->joinLeft(['vl' => 'verificacao'], 'vl.idverificacao = e.tipologradouro', '*', $schemaAgentes)
//            ->joinLeft(['t' => 'tbtitulacaoconselheiro'], 't.idagente = a.idagente', '*', $schemaAgentes)
//            ->joinLeft(['v' => 'visao'], 'v.idagente = a.idagente', '*', $schemaAgentes)
//            ->joinLeft(['sa' => 'area'], 'sa.codigo = t.cdarea', '*', $schemaSac)
//            ->joinLeft(['ss' => 'segmento'], 'ss.codigo = t.cdsegmento', '*', $schemaSac)
//            ->where('a.tipopessoa = 0 or a.tipopessoa = 1')
//        ;
//
//        if (!empty($cnpjcpf)) {
//            # busca pelo cpf/cnpj
//            $select->where('a.cnpjcpf = ?', $cnpjcpf);
//        }
//        if (!empty($nome)) {
//            # filtra pelo nome
//            $select->where('n.descricao LIKE ?', '%'.$nome.'%');
//        } if (!empty($idAgente)) {
//        # busca de acordo com o id do agente
//        $select->where('a.idagente = ?',$idAgente);
//    }
//
//        $select->order(['e.status Desc', 'n.descricao Asc']);
//        $result = $this->fetchAll($select);
//
//        echo '<pre>';
//        var_dump($result->toArray());
//        exit;
//
//        return ($result)? $result->toArray() : array();
//        echo '<pre>';
//        var_dump($select);
//        exit;
//    }
}