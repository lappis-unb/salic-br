<?php

/**
 * Modelo Tipoemail
 * @author Equipe RUP - Politec
 * @since 29/03/2010
 * @version 1.0
 * @package application
 * @subpackage application.models
 * @copyright � 2010 - Minist�rio da Cultura - Todos os direitos reservados.
 * @link http://www.cultura.gov.br
 */
class Tipoemail extends Zend_Db_Table
{
    protected $_banco = "agentes";
    protected $_name = 'verificacao';
    protected $_schema = 'agentes';

    /**
     * Método para buscar todos os tipos de e-mails
     * @access public
     * @param void
     * @author Vinícius Feitosa da Silva <viniciusfesil@mail.com>
     * @return array
     */
    public static function buscar()
    {
        $sql = "SELECT idVerificacao AS id, Descricao AS descricao ";
        $sql .= "FROM " . GenericModel::getStaticTableName("agentes", 'verificacao');
        $sql .= " WHERE idTipo = 4 ";
        $sql .= "   AND (idVerificacao = 28 OR idVerificacao = 29) ";
        $sql .= "ORDER BY Descricao;";

        try {
//            $db= Zend_Db_Table::getDefaultAdapter();
            $db = Zend_Db_Table::getDefaultAdapter();
            $db->setFetchMode(Zend_DB::FETCH_OBJ);
        } catch (Zend_Exception_Db $objException) {
            throw new Exception("Erro ao buscar Tipos de E-mails: " . $objException->getMessage(), 0, $objException);
        }

        return $db->fetchAll($sql);
    }
}