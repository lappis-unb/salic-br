<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Vinculo
 *
 * @author tisomar
 */
class tbHistoricoAlteracaoProjeto extends MinC_Db_Table_Abstract {

    protected $_banco = "SAC";
    protected $_name = "tbHistoricoAlteracaoProjeto";

    public function listadocumentosanexados($where=array()){

       
        $slct = $this->select();
        $slct->setIntegrityCheck(false);
        $slct->from(
                array('P' => $this->_name),
                array(
                    'P.cdArea',
                    'P.cdSegmento',
                    'P.nmProjeto',
                    'P.cdSituacao',
                    'P.cdOrgao',
                    'P.dtInicioExecucao',
                    'P.dtFimExecucao',
                    'P.idLogon',
                    'P.idDocumento',
                    'P.idEnquadramento',
                    'P.dtHistoricoAlteracaoProjeto',
                    'P.dsHistoricoAlteracaoProjeto',
                    'P.cgccpf',
                    'P.dsProvidenciaTomada'
                    )
        );

        $slct->joinInner(
                array('D' => 'tbHistoricoAlteracaoDocumento'),
                'D.idHistoricoAlteracaoProjeto = P.idHistoricoAlteracaoProjeto'
        );

        $slct->joinInner(
                array('Doc' => 'tbDocumento'),
                'Doc.idDocumento = D.idDocumento',
                array("*"),
                'bdcorporativo.scCorp'
        );

        $slct->joinInner(
                array('Arq' => 'tbArquivo'),
                'Arq.idArquivo = Doc.idArquivo',
                array("*"),
                'bdcorporativo.scCorp'
        );

        $slct->joinLeft(
                array('ArqAg' => 'tbDocumentoAgente'),
                'ArqAg.idDocumento = Doc.idDocumento',
                array("ArqAg.idAgente as AgenteDoc"),
                'bdcorporativo.scCorp'
        );

        $slct->joinLeft(
                array('ArqPr' => 'tbDocumentoProjeto'),
                'ArqPr.idDocumento = Doc.idDocumento',
               array("ArqPr.idPronac as ProjetoDoc"),
                'bdcorporativo.scCorp'
        );

        $slct->joinLeft(
                array('E' => 'tbTipoDocumento'),
                'Doc.idTipoDocumento = E.idTipoDocumento',
                array('E.dsTipoDocumento as Descricao'),
                'bdcorporativo.scCorp'
        );

  
        foreach ($where as $coluna => $valor) {
            $slct->where($coluna, $valor);
        }

            
        return $this->fetchAll($slct);
       


    }

  //  SELECT * from SAC.dbo.tbHistoricoAlteracaoProjeto as P
  //inner join SAC.dbo.tbHistoricoAlteracaoDocumento as D on D.idHistoricoAlteracaoProjeto = P.idHistoricoAlteracaoProjeto
  //inner join bdcorporativo.scCorp.tbDocumento as Doc on Doc.idDocumento = D.idDocumento
  //inner join bdcorporativo.scCorp.tbArquivo as Arq on Arq.idArquivo = Doc.idArquivo
 // where idPRONAC = 124914 and cdSituacao is not null

}
?>
