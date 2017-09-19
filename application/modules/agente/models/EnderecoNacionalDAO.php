<?php

class Agente_Model_EnderecoNacionalDAO extends MinC_Db_Table_Abstract
{
    protected $_name = 'EnderecoNacional';
    protected $_schema = 'agentes';

    /**
     * @deprecated Utilizar m&eacute;todo da DbTable
     */
    public static function buscarEnderecoNacional($idAgente)
    {
        $db = Zend_Db_Table::getDefaultAdapter();
        $e = array(
            'idEndereco',
            'idAgente',
            'TipoEndereco',
            'TipoLogradouro',
            'Logradouro',
            'Numero',
            'Bairro',
            'Complemento',
            'Cidade',
            'UF',
            'Cep',
            'Municipio',
            'UfDescricao',
            'Status',
            'Divulgar',
            'Usuario'
        );

        $sql = $db->select()
            ->from('EnderecoNacional', $e, 'agentes')
            ->where('idAgente = ?', $idAgente);

        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        $dados = $db->fetchAll($sql);

        return $dados;
    }

    /**
     * @deprecated Utilizar m&eacute;todo da DbTable
     */
    public static function gravarEnderecoNacional($dados)
    {
        $db = Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);
        $i = $db->insert('agentes.EnderecoNacional', $dados);
    }

    public function inserir($dados)
    {
        $db = Zend_Db_Table::getDefaultAdapter();

        $schema = $this->getSchema($this->_schema) . '.' . $this->_name;
        $db->insert($schema, $dados);
    }

    /**
     * @deprecated Utilizar m&eacute;todo da DbTable
     */
    public static function atualizaEnderecoNacional($idAgente, $dados)
    {
        $db = Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        $where['idAgente = ?'] = $idAgente;

        $i = $db->update('agentes.EnderecoNacional', $dados, $where);
    }

    /**
     * @deprecated Utilizar m&eacute;todo da DbTable
     *
     */
    public static function deletarEnderecoNacional($idEndereco)
    {
        try {
            $db = Zend_Db_Table::getDefaultAdapter();
            return $resultado = $db->delete('agentes.EnderecoNacional', array('idEndereco = ? ' => $idEndereco));

        } catch (Zend_Exception $e) {
            throw new Zend_Db_Exception("Erro ao excluir Telefone do Proponente: " . $e->getMessage());
        }
    }

    /**
     * @deprecated Utilizar m&eacute;todo da DbTable
     */
    public static function mudaCorrespondencia($idAgente)
    {
        try {
            $db = Zend_Db_Table::getDefaultAdapter();

            return $resultado = $db->update('agentes.EnderecoNacional', array('Status = ?' => 0), array('idAgente = ?' => $idAgente));
        } catch (Zend_Exception $e) {
            throw new Zend_Db_Exception("Erro ao alterar o Status dos endere�os: " . $e->getMessage());
        }
    }

    /**
     * @deprecated Utilizar m&eacute;todo da DbTable
     */
    public static function novaCorrespondencia($idAgente)
    {
        try {
            $db = Zend_Db_Table::getDefaultAdapter();
            $sql = "UPDATE agentes.EnderecoNacional set Status = 1
                    WHERE idAgente = " . $idAgente . "
                    AND idEndereco = (select MIN(idEndereco) as valor from agentes.EnderecoNacional  where idAgente = " . $idAgente . ")";

            $db = Zend_Db_Table::getDefaultAdapter();
            $db->setFetchMode(Zend_DB :: FETCH_OBJ);
        } catch (Zend_Exception_Db $e) {
            throw $e;
        }

        return $db->fetchAll($sql);
    }
}
