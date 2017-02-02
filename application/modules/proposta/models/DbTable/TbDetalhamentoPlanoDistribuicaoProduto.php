<?php

/**
 *
 *
 */
class Proposta_Model_DbTable_TbDetalhamentoPlanoDistribuicaoProduto extends MinC_Db_Table_Abstract
{
    /**
     * _schema
     *
     * @var string
     * @access protected
     */
    protected $_schema = 'sac';

    /**
     * _name
     *
     * @var bool
     * @access protected
     */
    protected $_name = 'TbDetalhamentoPlanoDistribuicaoProduto';

    /**
     * _primary
     *
     * @var bool
     * @access protected
     */
    //protected $_primary = 'idAbrangencia';

    public function salvar($dados)
    {
        return $this->insert($dados);
    }

    public function listarPorMunicicipioUF($dados)
    {
        $sql = $this->select()
            ->where(' idUF= ?', $dados['idUF'])
            ->where(' idMunicipio= ?', $dados['idMunicipio'])
            ->where('idPlanoDistribuicao = ?', $dados['idPlanoDistribuicao']);
        return $this->fetchAll($sql);
    }
}
