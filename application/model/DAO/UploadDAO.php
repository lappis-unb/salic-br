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
        $table = Zend_Db_Table::getDefaultAdapter();
        $db = Zend_Registry::get('db');
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        if ($busca == "tbDocumentosAgentes") { //acrescentado-jass
           // busca o arquivo

            $select = $table->select()
                ->from('tbDocumentosAgentes',
                    array('NoArquivo AS nmArquivo','imDocumento AS biArquivo',new Zend_db_Expr('1 AS biArquivo2')),
                    'SAC.dbo')
                ->where('idDocumentosAgentes = ?', $id)
                ->joinInner('DocumentosExigidos',
                    'tbDocumentosAgentes.CodigoDocumento = DocumentosExigidos.Codigo',
                    array(''),
                    'SAC.dbo');
            
        }
        else if ($busca == "tbDocumentosPreProjeto") { //acrescentado-jass
            // busca o arquivo

            $select = $table->select()
                ->from('tbDocumentosPreprojeto',
                    array('NoArquivo AS nmArquivo','imDocumento AS biArquivo',new Zend_db_Expr('1 AS biArquivo2')),
                    'SAC.dbo')
                ->where('idDocumentosPreprojetos = ?', $id)
                ->joinInner('DocumentosExigidos',
                    'tbDocumentosPreprojeto.CodigoDocumento = DocumentosExigidos.Codigo',
                    array(''),
                    'SAC.dbo');

        }
        else if ($busca == "tbDocumento") { //acrescentado-jass
            // busca o arquivo

            $select = $table->select()
                ->from('tbDocumento',
                    array('NoArquivo AS nmArquivo', 'imDocumento AS biArquivo', 'biDocumento AS biArquivo2'),
                        'SAC.dbo')
                ->where('tbDocumento.idDocumento = ?',  $id)
                ->joinInner(
                    'tbTipoDocumento',
                    'tbDocumento.idTipoDocumento = tbTipoDocumento.idTipoDocumento',
                     array(''),
                    'SAC.dbo');

        }

        $resultado = $db->fetchAll("SET TEXTSIZE 104857600");
        $resultado = $db->fetchAll($select);
        return $resultado;
    } // fecha método abrir()

} // fecha class