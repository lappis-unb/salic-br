<?php
/**
 * DAO Upload
 * @author Equipe RUP - Politec
 * @since 28/04/2010
 * @version 1.0
 * @package application
 * @subpackage application.model.DAO
 * @copyright © 2010 - Ministério da Cultura - Todos os direitos reservados.
 * @link http://www.cultura.gov.br
 */

class UploadDAO extends Zend_Db_Table {
    /* dados das tabelas */
    protected $_schema          = "";
    protected $_tbArquivo       = "tbArquivo";
    protected $_tbArquivoImagem = "tbArquivoImagem";

    /**
     * Método para abrir o arquivo
     * @access public
     * @static
     * @param integer $id
     * @return object || bool
     */
    public static function abrir($id) {
        $table = Zend_Db_Table::getDefaultAdapter();
        $db = Zend_Registry::get('db');
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        $select = $table->select()
            ->from('tbArquivo',
                array('dsTipoPadronizado', 'nmArquivo'),
                'BDCORPORATIVO.scCorp')
            ->where('tbArquivo.idArquivo = ?',  $id)
            ->joinInner(
                'tbArquivoImagem',
                'tbArquivo.idArquivo = tbArquivoImagem.idArquivo',
                array('biArquivo'),
                'BDCORPORATIVO.scCorp');

        $resultado = $db->fetchAll('SET TEXTSIZE 2147483647');
        return $db->fetchAll($select);
    } // fecha método abrir()


    /**
     * Método para abrir o arquivo
     * @access public
     * @static
     * @param integer $id
     * @return object || bool
     */
    public static function abrirdocumentosanexados($id, $busca) {
        $db = Zend_Registry::get('db');
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        if ($busca == "tbDocumentosAgentes") { //acrescentado-jass
            // busca o arquivo
            $sql = "SELECT NoArquivo AS nmArquivo, imDocumento AS biArquivo, 1 AS biArquivo2
                    FROM SAC.dbo.tbDocumentosAgentes d
                    INNER JOIN SAC.dbo.DocumentosExigidos e on (d.CodigoDocumento = e.Codigo)
                    WHERE idDocumentosAgentes = $id";
        }
        else if ($busca == "tbDocumentosPreProjeto") { //acrescentado-jass
            // busca o arquivo
            $sql = "SELECT NoArquivo AS nmArquivo, imDocumento AS biArquivo, 1 AS biArquivo2
                    FROM SAC.dbo.tbDocumentosPreProjeto d
                    INNER JOIN SAC.dbo.DocumentosExigidos e on (d.CodigoDocumento = e.Codigo)
                    WHERE idDocumentosPreProjetos = $id";
        }
        else if ($busca == "tbDocumento") { //acrescentado-jass
            // busca o arquivo
            $sql = "SELECT NoArquivo AS nmArquivo, imDocumento AS biArquivo, biDocumento AS biArquivo2
                    FROM SAC.dbo.tbDocumento d
                    INNER JOIN SAC.dbo.tbTipoDocumento e on (d.idTipoDocumento = e.idTipoDocumento)
                    WHERE idDocumento =  $id";
        }

        $resultado = $db->fetchAll("SET TEXTSIZE 104857600");
        $resultado = $db->fetchAll($sql);
        return $resultado;
    } // fecha método abrir()

} // fecha class