<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Arquivo
 *
 * @author 01610881125
 */
class Arquivo extends MinC_Db_Table_Abstract {
    protected $_name = 'tbArquivo';
    protected $_schema = 'bdcorporativo.scCorp';
    protected $_banco = 'bdcorporativo';

    /**
     * Insere arquvos de Marca
     * @return TRUE ou FALSE
     */
    public function inserirMarca($dados) {

        $name = $dados['nmArquivo'];
        $fileType = $dados['sgExtensao'];
        $data = $dados['biArquivo'];
        $dsDocumento = $dados['dsDocumento'];
        $IdPRONAC = $dados['idPronac'];

        $objAcesso = new Acesso();
        $sql = "INSERT INTO sac.dbo.vwAnexarMarca " .
               "(nmArquivo,sgExtensao,dtEnvio,stAtivo,biArquivo,idTipoDocumento,dsDocumento,idPronac,stAtivoDocumentoProjeto) " .
               "VALUES ('$name', '$fileType', {$objAcesso->getDate()},'I',$data,1,'$dsDocumento', $IdPRONAC,'E')";

        $db = Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);
        return $db->fetchAll($sql);
    }

    /**
     * @return TRUE ou FALSE
     */
    public function inserirUploads($dados) {

        $name = $dados['nmArquivo'];
        $fileType = $dados['sgExtensao'];
        $data = $dados['biArquivo'];
        $dsDocumento = $dados['dsDocumento'];
        $IdPRONAC = $dados['idPronac'];
        $idTipoDocumento = $dados['idTipoDocumento'];

        $objAcesso = new Acesso();
        $sql = "INSERT INTO sac.dbo.vwAnexarComprovantes " .
               "(nmArquivo,sgExtensao,dtEnvio,stAtivo,biArquivo,idTipoDocumento,dsDocumento,idPronac,stAtivoDocumentoProjeto) " .
               "VALUES ('$name', '$fileType', {$objAcesso->getDate()},'I',$data,$idTipoDocumento,'$dsDocumento', $IdPRONAC,'E')";

//        

        $db = Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);
        return $db->fetchAll($sql);
    }

    public function buscarComprovantesExecucao($idPronac) {

        $sql = "SELECT idArquivo,nmArquivo,sgExtensao,dtEnvio,stAtivo,idTipoDocumento,dsDocumento,idPronac,stAtivoDocumentoProjeto
                FROM sac.dbo.vwAnexarComprovantes
                WHERE idTipoDocumento in (22,23,24)
                AND idPronac = $idPronac";

        $db = Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);
        return $db->fetchAll($sql);
    }

    public function buscarAnexosDiligencias($idDiligencia) {
        $sql = "SELECT a.idArquivo,a.nmArquivo,a.dtEnvio,b.idDiligencia
                FROM  bdcorporativo.scCorp.tbArquivo AS a
                INNER JOIN sac.dbo.tbDiligenciaxArquivo AS b on (a.idArquivo = b.idArquivo)
                WHERE b.idDiligencia = $idDiligencia";

        $db = Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);
        return $db->fetchAll($sql);
    }

}
