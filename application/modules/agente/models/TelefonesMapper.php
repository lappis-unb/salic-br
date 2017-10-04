<?php

class Agente_Model_TelefonesMapper extends MinC_Db_Mapper
{
    public function __construct()
    {
        parent::setDbTable('Agente_Model_DbTable_Telefones');
    }

    public function saveCustom($arrData)
    {
        $rowsDeleted = $this->deleteBy(array('idAgente' => $arrData['idAgente']));

        # cadastra todos os telefones
        $arrId = array();

        for ($i = 0; $i < sizeof($arrData['fones']); $i++) {
            $arrData = array(
                'idAgente' => $arrData['idAgente'],
                'TipoTelefone' => $arrData['tipofones'][$i],
                'UF' => $arrData['uffones'][$i],
                'DDD' => $arrData['dddfones'][$i],
                'Numero' => $arrData['fones'][$i],
                'Divulgar' => $arrData['divulgarfones'][$i],
                'intidusuario' => $arrData['intidusuario'],
                'Usuario' => $arrData['Usuario']);
            $arrId[] = $this->save(new Agente_Model_Telefones($arrData));
        }
        return $arrId;
    }

    public function save(Agente_Model_Telefones $model)
    {
        return parent::save($model);
    }
}