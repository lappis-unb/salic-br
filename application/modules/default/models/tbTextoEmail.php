<?php

class tbTextoEmail extends MinC_Db_Table_Abstract
{
    protected $_name = 'tbTextoEmail';
    protected $_schema = "sac";
    protected $_primary = "idTextoemail";

    public function obterTextoPorIdentificador($idTextoEmail)
    {
        $objQuery = $this->select();
        $objQuery->from(
            $this->_name,
            'dsTexto',
            $this->_schema
        );
        $objQuery->where('idTextoemail = ?', $idTextoEmail);

        return $this->fetchRow($objQuery);
    }
}
