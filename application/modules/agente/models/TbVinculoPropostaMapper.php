<?php

class Agente_Model_TbVinculoPropostaMapper extends MinC_Db_Mapper
{
    public function __construct()
    {
        parent::setDbTable('Agente_Model_DbTable_TbVinculoProposta');
    }

    /**
     * @param array $arrData - Parametros necessarios para 3 transacoes com o banco.
     * @return bool
     */
    public function saveCustom($arrData)
    {
        $booStatus = true;
        $tblPreProjeto = new Proposta_Model_DbTable_PreProjeto();

        try {
            $this->beginTransaction();
            $msg = "Respons&aacute;vel vinculado com sucesso!";

            if ($arrData['opcaovinculacao'] == 1) {
                $msg = "O respons&aacute;vel foi desvinculado!";
            }

            $tblPreProjeto->alteraresponsavel($arrData['idpreprojeto'], $arrData['idresponsavel']);
            $arrTbVinculoProposta['sivinculoproposta'] = 3;
            $whereTbVinculoProposta['idpreprojeto = ?'] = $arrData['idpreprojeto'];
            $this->getDbTable()->alterar($arrTbVinculoProposta, $whereTbVinculoProposta, false);
            $arrData['sivinculoproposta'] = 2;
            $this->save(new Agente_Model_TbVinculoProposta($arrData));
            $this->setMessage($msg);
            $this->commit();
        } catch (Exception $e) {
            $this->rollBack();
            $this->setMessage($e->getMessage());
            $booStatus = false;
        }
        return $booStatus;
    }

    public function save(Agente_Model_TbVinculoProposta $model) {
        return parent::save($model);
    }
}