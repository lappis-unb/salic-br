<?php

/**
 * Modelo Telefone
 *
 * @package Application
 *
 * @author Equipe RUP - Politec
 * @author wouerner <wouerner@gmail.com>
 * @link   http://www.cultura.gov.br
 * @since  29/03/2010
 */

class Agente_Model_Telefone extends Zend_Db_Table
{
    /**
     * _name
     *
     * @var bool
     * @access protected
     */
    protected $_name = 'Telefones';

    /**
     * _schema
     *
     * @var string
     * @access protected
     */
    protected $_schema = 'AGENTES.dbo';

    /**
     * _primary
     *
     * @var bool
     * @access protected
     */
    protected $_primary = 'idTelefone';

    /**
     * Método para buscar todos os telefones de um conselheiro
     *
     * @param integer $idAgente ID do agente
     *
     * @access public
     * @return object
     *
     * @throws     Apenas inteiros.
     * @deprecated
     */
    public static function buscar($idAgente)
    {
        if (!is_int($idAgente)) {
            throw new InvalidArgumentException('Precisa ser inteiro');
        }

        $db = Zend_Registry::get('db');

        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        $sql = $db->select()->from(['t' => 'Telefones'], '*', 'Agentes.dbo')->where('t.idAgente = ?', $idAgente);

        return $db->fetchAll($sql);
    }

    /**
     * Método para cadastrar todos os telefones de um agente
     *
     * @param array $dados Array com dados de cadastro
     *
     * @access public
     * @return boolean
     *
     * @throws Zend_Db_Exception
     * @deprecated
     */
    public static function cadastrar($dados)
    {
        try
        {
            $db = Zend_Registry::get('db');

            $inserir = $db->insert('Agentes.dbo.Telefones', $dados);

            return true;
        }
        catch (Zend_Exception $e)
        {
            throw new Zend_Db_Exception("Erro ao cadastrar Telefones do Proponente: " . $e->getMessage());
        }
    }

    /**
     * Método para excluir telefone
     *
     * @param integer $id ID do telefone
     *
     * @access public
     *
     * @return bool
     *
     * @throws Zend_Db_Exception
     * @deprecated
     */
    public static function excluir($id)
    {
        try
        {
            $db = Zend_Registry :: get('db');

            $resultado = $db->delete('Agentes.dbo.Telefones', ['idTelefone = ? '=> $id]);

            return $resultado;
        }
        catch (Zend_Exception $e)
        {
            throw new Zend_Db_Exception("Erro ao excluir Telefone do Proponente: " . $e->getMessage());
        }
    }

    /**
     * Método para excluir todos os telefones de um agente
     *
     * @param integer $idAgente ID do Agente
     *
     * @access public
     *
     * @return bool
     *
     * @throws Zend_Db_Exception
     * @deprecated
     */
    public static function excluirTodos($idAgente)
    {
        try
        {
            $db = Zend_Registry :: get('db');

            $resultado = $db->delete('Agentes.dbo.Telefones', ['idAgente = ? '=> $idAgente]);

            return $resultado;
        }
        catch (Zend_Exception $e)
        {
            throw new Zend_Db_Exception("Erro ao excluir Telefone: " . $e->getMessage());
        }
    }
}
