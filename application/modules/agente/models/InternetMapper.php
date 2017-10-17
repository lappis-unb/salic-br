<?php

class Agente_Model_InternetMapper extends MinC_Db_Mapper
{
    public function __construct()
    {
        parent::setDbTable('Agente_Model_DbTable_Internet');
    }

    public function saveCustom($arrData)
    {
        $rowsDeleted = $this->deleteBy(array('idAgente' => $arrData['idAgente']));

        # cadastra todos os e-mails
        $arrId = array();
        for ($i = 0; $i < sizeof($arrData['emails']); $i++) {
            $arrayEmail = array(
                'idAgente' => $arrData['idAgente'],
                'TipoInternet' => $arrData['tipoemails'][$i],
                'Descricao' => $arrData['emails'][$i],
                'Status' => $arrData['enviaremails'][$i],
                'Divulgar' => $arrData['divulgaremails'][$i],
                'Usuario' => $arrData['usuario']
            );
            $arrId[] = $this->save(new Agente_Model_Internet($arrayEmail));
        }

        return $arrId;
    }

    public function save(Agente_Model_Internet $model)
    {
        return parent::save($model);
    }
}