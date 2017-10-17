<?php

class Agente_Model_EnderecoNacionalMapper extends MinC_Db_Mapper
{
    public function __construct()
    {
        parent::setDbTable('Agente_Model_DbTable_EnderecoNacional');
    }

    public function saveCustom($arrData)
    {
        $rowsDeleted = $this->deleteBy(array('idAgente' => $arrData['idAgente']));
        if (!empty($arrData['correspondenciaenderecos'])) {
            $correspondenciaEnderecos = $arrData['correspondenciaEnderecos'];
        } else {
            $correspondenciaEnderecos = 0;
        }

        # cadastra todos os telefones
        $arrId = array();
        for ($i = 0; $i < sizeof($arrData['ceps']); $i++) {
            $arrayEnderecos = array(
                'idAgente' => $arrData['idAgente'],
                'Cep' => str_replace(".", "", str_replace("-", "", $arrData['ceps'][$i])),
                'TipoEndereco' => $arrData['tipoenderecos'][$i],
                'UF' => $arrData['ufs'][$i],
                'Cidade' => $arrData['cidades'][$i],
                'Logradouro' => $arrData['logradouros'][$i],
                'Divulgar' => $arrData['divulgarEnderecos'][$i],
                'TipoLogradouro' => $arrData['TipoLogradouros'][$i],
                'Numero' => $arrData['numeros'][$i],
                'Complemento' => $arrData['complementos'][$i],
                'Bairro' => $arrData['bairros'][$i],
                'Status' => $correspondenciaEnderecos,
                'Usuario' => $arrData['IdUsuario']);
            $arrId[] = $this->save(new Agente_Model_EnderecoNacional($arrayEnderecos));
        }

        return $arrId;
    }

    public function save(Agente_Model_EnderecoNacional $model)
    {
        return parent::save($model);
    }
}