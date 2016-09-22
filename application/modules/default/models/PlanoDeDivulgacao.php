<?php

class PlanoDeDivulgacao extends MinC_Db_Table_Abstract {

    //protected $_name = 'SAC.dbo.PlanoDeDivulgacao';
    protected $_banco = 'SAC';
    protected $_name = 'PlanoDeDivulgacao';

    public function buscarPlanoDivulgacao($idprojeto) {
        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->distinct();
        $select->from(
                array('pd' => $this->_name),
                array('pd.idPlanoDivulgacao')
        );
        $select->joinInner(
                array('vei' => 'Verificacao'),
                'pd.idVeiculo = vei.idVerificacao',
                array('vei.Descricao as Veiculo')
        );
        $select->joinInner(
                array('pr' => 'Projetos'),
                'pr.idProjeto = pd.idProjeto',
                array()
        );
        $select->joinInner(
                array('r' => 'tbRelatorio'),
                'r.idPronac = pr.idPronac',
                array()
        );
        $select->joinInner(
                array('pdp' => 'PlanoDistribuicaoProduto'),
                'pdp.idProjeto = pr.idProjeto ',
                array()
        );
        $select->joinInner(
                array('dp' => 'tbDistribuicaoProduto'),
                'dp.idPlanoDistribuicao = dp.idPlanoDistribuicao',
                array('dp.dsTamanhoDuracao')
        );
        $select->joinInner(
                array('lg'=>'tbLogoMarca'),
                'pd.idPlanoDivulgacao = lg.idPlanoDivulgacao',
                array('dsPosicao')
        );
        $select->joinInner(
                array('ver'=>'Verificacao'),
                'pd.idPeca = ver.idVerificacao',
                array('ver.Descricao as Peca')
        );
        $select->where('pd.idProjeto = ?', $idprojeto);
        $select->where('pdp.stPlanoDistribuicaoProduto = ?', 1);
//        xd($select->assemble());
        return $this->fetchAll($select);
    }

    /**
     * M�todo para alterar
     * @access public
     * @param array $dados
     * @param integer $where
     * @return integer (quantidade de registros alterados)
     */
    public function alterarDados($dados, $where) {
        $where = "idPlanoDivulgacao = " . $where;
        return $this->update($dados, $where);
    } // fecha m�todo alterarDados()

}