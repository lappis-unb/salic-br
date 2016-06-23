<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of paUsuariosDoPerfil
 */
class sVerificaValidadeCertidaoNegativa extends GenericModel {
        
    protected $_banco = 'SAC';
    protected $_name  = 'sVerificaValidadeCertidaoNegativa';

    public function buscarDados($cpfcnpj){
        $sql = "exec ".$this->_banco.".dbo.".$this->_name." '$cpfcnpj' ";
        $db = Zend_Registry :: get('db');
        $db->setFetchMode(Zend_DB :: FETCH_OBJ);
        return $db->fetchAll($sql);
    }
    
    public function buscarDadosSemSP($cpfcnpj)
    {
        $table = Zend_Db_Table::getDefaultAdapter();
        $db = Zend_Registry::get('db');
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        $select = $table->select()
            ->from('CertidoesNegativas',
                array(new Zend_Db_Expr('
                   CASE
                            WHEN CodigoCertidao = \'49\'
                            THEN \'Quitação de Tributos Federais\'
                            WHEN CodigoCertidao = \'51\'
                            THEN \'FGTS\'
                            WHEN CodigoCertidao = \'52\'
                            THEN \'INSS\'
                            WHEN CodigoCertidao = \'244\'
                            THEN \'CADIN\'
                        END AS dsCertidao,
                        CodigoCertidao, DtEmissao, DtValidade, AnoProjeto+Sequencial AS Pronac,
                        CASE
                            WHEN CodigoCertidao = 244
                            THEN
                                CASE
                                    WHEN cdSituacaoCertidao = 0
                                    THEN \'Pendente\'
                                    ELSE \'Não Pendente\'
                                END
                        END AS Situacao,
                        CASE
                            WHEN CodigoCertidao = 244
                            THEN DateDiff(dy,getdate(),DtEmissao)
                            ELSE DateDiff(dy,getdate(),DtValidade)
                   END AS qtDias
                ')),
                'SAC.dbo')
            ->where('CgcCpf = ?',$cpfcnpj);

        return $db->fetchAll($select);
    }
}
?>
