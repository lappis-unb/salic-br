<?php

class Agente_Model_NomesMapper extends MinC_Db_Mapper
{
    public function __construct()
    {
        parent::setDbTable('Agente_Model_DbTable_Nomes');
    }

    public function saveCustom( $arrData )
    {
        $arrNome = $this->findBy(array('idAgente' => $arrData['idAgente']));
        $arrData = array(
            'idAgente' => $arrData['idAgente'],
            'TipoNome' => (!$arrData['TipoNome'] && strlen($arrData['CPF']) == 11 ? 18 : 19), # 18 = pessoa fisica e 19 = pessoa juridica
//            'TipoNome' => (strlen($arrData['cpf']) == 11 ? 18 : 19), # 18 = pessoa fisica e 19 = pessoa juridica
            'Descricao' => ($arrData['nome'] ? $arrData['nome'] : $arrData['Descricao']),
            'Status' => 0,
            'Usuario' => ($arrData['IdUsuario'] ? $arrData['IdUsuario'] : $arrData['Usuario']),
        );
        if ($arrNome) {
            $arrData['idNome'] = $arrNome['idNome'];
        }

        return parent::save(new Agente_Model_Nomes($arrData));
    }
}
