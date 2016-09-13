<?php

/**
 * Class tbVinculoPropostaResponsavelProjeto
 * @uses GenericModel
 * @author  wouerner <wouerner@gmail.com>
 * @author Vinícius Feitosa da Silva <viniciusfesil@gmail.com>
 * @since 18/08/2016 14:29
 */
class tbVinculoPropostaResponsavelProjeto extends GenericModel
{
    protected $_banco = 'agentes';
    protected $_name = 'tbvinculoproposta';
    protected $_schema = 'agentes';
    protected $_primary = 'idvinculoproposta';

    /**
     * @param array $where
     * @return Zend_Db_Table_Rowset_Abstract
     */
    public function buscarResponsaveisProponentes($where = array())
    {
        $slct = $this->select();
        $slct->distinct();
        $slct->setIntegrityCheck(false);

        $slct->from(
            array('VP' => $this->_name),
            array('*')
        );

        $slct->joinInner(
            array('VI' => 'tbVinculo'), 'VI.idVinculo = VP.idVinculo',
            array('*')
        );

        foreach ($where as $coluna => $valor) {
            $slct->where($coluna, $valor);
        }

        return $this->fetchAll($slct);
    }
}

