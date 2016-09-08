<?php


class tbLoginTentativasAcesso extends GenericModel {

    protected $_banco = 'SAC';
    protected $_schema = 'sac.dbo';
    protected $_name = 'tbLoginTentativasAcesso';

    public function consultarAcessoCpf($cpf,$ip)
    {
        $table = Zend_Db_Table::getDefaultAdapter();

        $select = $table->select()
            ->from('tbLoginTentativasAcesso',
                array('*'),
                'SAC.dbo')
            ->where('nrCPF = ?', $cpf)
            ->where('nrIP = ?',$ip);

        $db = Zend_Registry::get('db');
        $db->setFetchMode(Zend_DB::FETCH_OBJ);


        return $db->fetchRow($select);
    }

    public function insereTentativa($cpf,$ip)
    {
        $dados = array('nrCPF'       => $cpf,
                       'nrIP'        => $ip,
                       'nrTentativa' => 1,
                       'dtTentativa' => new Zend_Db_Expr('GETDATE()'));


        return $this->insert($dados);
    }
    public function atualizaTentativa($cpf, $ip, $atualtentativa)
    {
        $novatentativa = $atualtentativa+1;

            $dados = array('nrTentativa' => new Zend_Db_Expr("$novatentativa"));
            $where = array('nrCPF = ?'=> $cpf, 'nrIP = ?' => $ip);

            return $this->update($dados, $where);
    }

    public function removeTentativa($cpf, $ip)
    {
        $where = array('nrCPF = ?' => $cpf, 'nrIP = ?' => $ip);

        return $this->delete($where);
    }

}