<?php

class Analise_Model_DbTable_TbAvaliarAdequacaoProjeto extends MinC_Db_Table_Abstract
{
    protected $_schema = 'sac';
    protected $_name = 'tbAvaliarAdequacaoProjeto';
    protected $_primary = 'idAvaliarAdequacaoProjeto';

    public function inserirAvaliacao($idPronac, $orgaoUsuario)
    {
        $dados = array(
            'idPronac' => $idPronac,
            'dtEncaminhamento' => new Zend_Db_Expr('GETDATE()'),
            'idTecnico' => new Zend_Db_Expr('sac.dbo.fnPegarTecnico(110, ' . $orgaoUsuario . ' ,1)'),
            'dtAvaliacao' => null,
            'dsAvaliacao' => null,
            'siEncaminhamento' => 1,
            'stAvaliacao' => 0,
            'stEstado' => 1
        );

        return $this->insert($dados);
    }

}