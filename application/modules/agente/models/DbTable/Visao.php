<?php

/**
 * Class Agente_Model_DbTable_Visao
 *
 * @name Agente_Model_DbTable_Visao
 * @package Modules/Agente
 * @subpackage Models/DbTable
 * @version $Id$
 *
 * @author Ruy Junior Ferreira Silva <ruyjfs@gmail.com>
 * @since 01/09/2016
 *
 * @copyright © 2012 - Ministerio da Cultura - Todos os direitos reservados.
 * @link http://salic.cultura.gov.br
 *
 * @todo refatorar metodos.
 */
class Agente_Model_DbTable_Visao extends MinC_Db_Table_Abstract
{
    /**
     * Nome da tabela do banco
     */
    protected $_name = 'AGENTES.dbo.Visao';


    /**
     * Metodo para buscar as visoes do agente
     * @access public
     * @static
     * @param integer $idAgente
     * @param integer $visao
     * @param boolean $todasVisoes
     * @return object
     */
    public function buscarVisao($idAgente = null, $visao = null, $todasVisoes = false)
    {
        // busca todas as visoes existentes no banco
        if ($todasVisoes) {
            $sql = "select distinct idverificacao, descricao from  " . GenericModel::getStaticTableName('agentes', 'verificacao') . "  where idtipo = 16 and sistema = 21 ";
        } // busca todas as visoes do usuario
        else {
            $sql = "select
                                distinct vis.idvisao ,
                                ver.descricao,
                                ver.idverificacao,
                                vis.idagente ,
                                vis.visao ,
                                vis.usuario ,
                                vis.stativo ,
                                ar.descricao as area
                                from " . parent::getStaticTableName('agentes', 'visao') . " vis
                                inner join " . parent::getStaticTableName('agentes', 'verificacao') . " ver on ver.idverificacao = vis.visao
                                left join " . parent::getStaticTableName('agentes', 'tbtitulacaoconselheiro') . " ttc on ttc.idagente =  vis.idagente
                                left join " . parent::getStaticTableName('sac', 'area') . " ar on ttc.cdArea = ar.Codigo ";

            $sql .= " where ver.idverificacao = vis.visao
				and ver.idtipo = 16 and sistema = 21";

            if (!empty($idAgente)) {
                $sql .= " and vis.idagente = " . $idAgente;
            }

            if (!empty($visao)) {
                $sql .= " and vis.visao = " . $visao;
            }
        }
        $sql .= " order by 2";
        $db = Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        $dados = $db->fetchAll($sql);
        return $dados;
    }


    /**
     * Metodo para cadastrar a visao de um agente
     * @access public
     * @static
     * @param array $dados
     * @return boolean
     */
    public function cadastrarVisao($dados)
    {
        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        $insert = $db->insert('AGENTES.dbo.Visao', $dados); // cadastra

        return $insert ? true : false;
    }

    /**
     * M�todo para alterar a vis�o de um agente
     * @access public
     * @static
     * @param integer $idAgente
     * @param array $dados
     * @return boolean
     */
    public function alterarVisao($idAgente, $dados)
    {
        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        $where = "idAgente = " . $idAgente; // condi��o para altera��o

        $update = $db->update('AGENTES.dbo.Visao', $dados, $where); // altera

        if ($update) {
            return true;
        } else {
            return false;
        }
    }


    /**
     * Metodo para excluir a visao de um agente
     * @access public
     * @static
     * @param integer $idAgente
     * @return boolean
     */
    public function excluirVisao($idAgente)
    {
        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        $where = "idAgente = " . $idAgente; // condi��o para exclus�o

        $delete = $db->delete('AGENTES.dbo.Visao', $where); // exclui

        if ($delete) {
            return true;
        } else {
            return false;
        }
    }
}