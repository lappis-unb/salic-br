<?php
/**
 * tbVinculoPropostaResponsavelProjeto
 *
 * @uses GenericModel
 * @author  wouerner <wouerner@gmail.com>
 */
class tbVinculoPropostaResponsavelProjeto extends GenericModel{

    protected $_banco = 'Agentes';
    protected $_name = 'tbVinculoProposta';
    protected $_schema = 'Agentes';

    /**
     * buscarResponsaveisProponentes
     *
     * @param bool $where
     * @access public
     * @return void
     */
    public function buscarResponsaveisProponentes($where=array())
    {
        $slct = $this->select();
        $slct->distinct();
        $slct->setIntegrityCheck(false);

        $slct->from(
                array('VP' => $this->_name),
                array('*')
        );

        $slct->joinInner(
                array('VI' => 'tbVinculo'),'VI.idVinculo = VP.idVinculo',
                array('*')
        );

        foreach ($where as $coluna => $valor)
        {
            $slct->where($coluna, $valor);
        }

        return $this->fetchAll($slct);
    }
}

