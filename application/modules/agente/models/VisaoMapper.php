<?php

class Agente_Model_VisaoMapper extends MinC_Db_Mapper
{
    public function __construct()
    {
        parent::setDbTable('Agente_Model_DbTable_Visao');
    }

    public function saveCustom($arrData)
    {
        /*
         * Validacao - Se for componente da comissao ele nao salva a visao
         * Regra o componente da comissao nao pode alterar sua visao.
         */
        if ($arrData['grupologado'] != 118) {
            $arrDataVisao = array(
                'idAgente' => $arrData['idAgente'],
                'Visao' => $arrData['Visao'],
                'usuario' => $arrData['IdUsuario'],
                'stativo' => 'A'
            );
            $arrVisao = $this->findBy(array('idAgente' => $arrData['idAgente'], 'Visao' => $arrData['Visao']));
            if ($arrVisao) {
                return $arrVisao['idVisao'];
            } else {
                return $this->save(new Agente_Model_Visao($arrDataVisao));
            }
        }
    }
}