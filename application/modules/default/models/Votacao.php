<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Votacao
 *
 * @author augusto
 */
class Votacao extends MinC_Db_Table_Abstract {

    protected $_banco = 'bdcorporativo';
    protected $_schema = 'bdcorporativo.scSAC';
    protected $_name = 'tbVotacao';

    public function resultadovotacao($idNrReuniao, $idPRONAC, $stvoto = null, $tipoReadequacao = null) {
        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->from(
                $this,
                array('count(stVoto) as qtdvotos')
        );
        $select->where('idNrReuniao = ?', $idNrReuniao);
        $select->where('idPRONAC = ?', $idPRONAC);
        if ($stvoto) {
            $select->where('stVoto = ?', $stvoto);
        }
        if ($tipoReadequacao) {
            $select->where('tpTipoReadequacao = ?', $tipoReadequacao);
        }
        return $this->fetchRow($select);
    }

    public function votantesjustificativavoto($idNrReuniao, $idPRONAC, $tipoReadequacao = null) {
        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->from(
                    array('tv'=>$this->_name),
                    array(
                            '(cast(tv.dsJustificativa AS TEXT)) as justificativa',
                            'tv.stVoto'
                        )
                    );
        $select->joinInner(
                          array('nm'=>'Nomes'),
                          'nm.idAgente = tv.idAgente',
                          array('nm.Descricao as nome'),
                          'agentes'
                          );
        $select->where('idNrReuniao = ?', $idNrReuniao);
        $select->where('idPRONAC = ?', $idPRONAC);
        $select->order('tv.dsJustificativa desc');
        if ($tipoReadequacao) {
            $select->where('tv.tpTipoReadequacao = ?', $tipoReadequacao);
        }
        return $this->fetchAll($select);
    }

}

