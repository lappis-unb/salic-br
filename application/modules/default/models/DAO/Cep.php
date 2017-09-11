<?php

class Cep extends MinC_Db_Table_Abstract
{

    protected $_schema = 'bddne';
    protected $_name = 'scDNE.VW_ENDERECO';
    protected $_primary = 'cep';

    public function __construct()
    {
        parent::__construct();
    }

    public function init()
    {
        parent::init();
    }

    /**
     * @name buscarCEP
     * @param $cep
     * @return null|Zend_Db_Table_Row_Abstract
     *
     * @todo verificar por que o zend nao reconhece uma view para o mapeamento.
     */
    public function buscarCEP($cep)
    {
        $db = Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_ASSOC);
        $objQuery = $this->select();
        
        $cols = array(
            'cep',
            'logradouro',
            'tipo_logradouro',
            'bairro',
            'cidade',
            'uf',
            'idcidademunicipios',
            'dscidademunicipios',
            'idcidadeuf',
            'dscidademunicipios as dscidadeuf'
        );

        $objQuery->from(
            $this->_name,
            $cols,
            $this->_schema
        );
        $objQuery->where('cep = ?', $cep);

        xd($objQuery->assemble(), $this->_name);

        return $db->fetchRow($sql);
    }
}
