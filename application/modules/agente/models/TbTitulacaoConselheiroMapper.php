<?php

class Agente_Model_TbTitulacaoConselheiroMapper extends MinC_Db_Mapper
{
    public function __construct()
    {
        parent::setDbTable('Agente_Model_DbTable_TbTitulacaoConselheiro');
    }

    public function saveCustom($arrData)
    {
        $titular = $arrData['titular'];
        $areaCultural = $arrData['areacultural'];
        $segmentoCultural = $arrData['segmentocultural'];
        $intVisao = $arrData['Visao'];
        $idAgente = $arrData['idAgente'];

        # so salva area e segmento para a visao de componente da comissao e se os campos titular e areaCultural forem informados
        if ((int) $intVisao == 210 && ((int)$titular == 0 || (int)$titular == 1) && !empty($areaCultural)) {
            # busca a titulacao do agente (titular/suplente de area cultural)
            $arrTitulacaoConselheiro = $this->findBy(array('idAgente' => $idAgente));
            $arrData = array (
                'cdarea' => $areaCultural,
                'cdsegmento' => $segmentoCultural,
                'sttitular' => $titular,
                'idAgente' => $idAgente
            );
            if (!$arrTitulacaoConselheiro) {
                $arrData['stConselheiro'] = 'A';
            }
            $intId = $this->save(new Agente_Model_TbTitulacaoConselheiro($arrData));
            return $intId;
        }

        return false;
    }

    public function save(Agente_Model_TbTitulacaoConselheiro $model)
    {
        return parent::save($model);
    }
}