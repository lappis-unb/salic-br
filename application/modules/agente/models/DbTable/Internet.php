<?php

class Agente_Model_DbTable_Internet extends MinC_Db_Table_Abstract
{
    protected $_schema = 'agentes';
    protected $_name = 'Internet';
    protected $_primary = 'idInternet';

    /**
     * @param string $email
     * @param string $assunto
     * @param string $texto
     * @param string $perfil
     * @param string $formato
     * @return void
     * @todo retirar SP, n�o foi encontrada uso do metodo no sistema, proposta de remo��o.
     */
    public function enviarEmail($email, $assunto, $texto, $perfil = "PerfilGrupoPRONAC", $formato = "HTML")
    {
        $sql = "EXEC msdb.dbo.sp_send_dbmail
                    @profile_name          = '" . $perfil . "'
                    ,@recipients           = '" . $email . "'
                    ,@body                 = '" . $texto . "'
                    ,@body_format          = '" . $formato . "'
                    ,@subject              = '" . $assunto . "'
                    ,@exclude_query_output = 1;";

        return $this->getAdapter()->query($sql);
    }

    /**
     * Metodo para buscar o(s) e-mail(s) do agente
     * @access public
     * @param string $cpfcnpj
     * @param integer $idAgente
     * @param integer $StatusEmail (1 = ATIVADO, 0 = DESATIVADO)
     * @param integer $StatusDivulgacao (1 = ATIVADO, 0 = DESATIVADO)
     * @param boolean $buscarTodos (informa se busca todos ou somente um)
     * @return array || object
     */
        public function buscarEmailAgente(
        $cpfcnpj = null,
        $idAgente = null,
        $StatusEmail = null,
        $StatusDivulgacao = null,
        $buscarTodos = true)
    {
        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->from(
            array("e" => $this->_name),
            array(
                "e.idInternet as idemail"
                , "e.TipoInternet as tipoemail"
                , "e.Descricao as email"
                , "e.Status"
                , "e.Divulgar"
            ),
            $this->_schema
        );

        $select->join(
            array("a" => "Agentes"),
            "a.idAgente = e.idAgente",
            array(),
            $this->_schema
        );

        // busca pelo cnpj ou cpf
        if (!empty($cpfcnpj)) {
            $select->where("a.CNPJCPF = ?", $cpfcnpj);
        }

        // busca pelo id do agente
        if (!empty($idAgente)) {
            $select->where("a.idAgente = ?", $idAgente);
        }

        // busca pelo email ativado/desativado
        if (!empty($StatusEmail)) {
            $select->where("e.Status = ?", $StatusEmail);
        }

        // busca pelo email de divulgacao
        if (!empty($StatusDivulgacao)) {
            $select->where("e.Divulgar = ?", $StatusDivulgacao);
        }

        return $buscarTodos ? $this->fetchAll($select) : $this->fetchRow($select);
    }

    /**
     * Metodo para buscar os e-mails do agente
     *
     * @access public
     * @static
     * @param integer $idAgente
     * @return object
     */
    public function buscarEmails($idAgente = null)
    {
        $tblAgentes = new Agente_Model_DbTable_Agentes();
        $db = Zend_Db_Table::getDefaultAdapter();

        $i = array(
            'i.idInternet',
            'i.idAgente',
            'i.TipoInternet',
            'i.Descricao',
            'i.Status',
            'i.Divulgar'
        );

        $query = $this->select();
        $query->from(array('i' => 'Internet'), $i, $this->_schema);
        $query->join(array('v' => 'Verificacao'), 'i.TipoInternet = v.idVerificacao', 'v.Descricao as tipo', $this->_schema);
        $query->join(array('t' => 'Tipo'), 't.idTipo = v.IdTipo', null, $this->_schema);

        if (!empty($idAgente)) {// busca de acordo com o id do agente
            $query->where('i.idAgente = ?', $idAgente);
        }

        $db->setFetchMode(Zend_DB::FETCH_OBJ);
        return $db->fetchAll($query);
    }

    /**
     * M�todo para cadastrar
     * @access public
     * @param array $dados
     * @return integer (retorna o ultimo id cadastrado)
     */
    public function cadastrarEmailAgente($dados)
    {
        return $this->insert($dados);
    }

    /**
     * Metodo para excluir
     * @access public
     * @param integer $idAgente (excluir todos os e-mails de um agente)
     * @param integer $idInternet (excluir um determinado e-mail)
     * @return integer (quantidade de registros exclu�dos)
     */
    public function excluirEmailAgente($idAgente = null, $idInternet = null)
    {
        // exclui todos os e-mails de um agente
        if (!empty($idAgente)) {
            $where['idAgente = ?'] = $idAgente;
        } // exclui um determinado e-mail
        else if (!empty($idInternet)) {
            $where['idInternet = ?'] = $idInternet;
        }

        return $this->delete($where);
    }

    public function obterEmailProponentesPorPreProjeto($idPreProjeto)
    {

        $select = $this->select();
        $this->_db->setFetchMode(Zend_DB::FETCH_OBJ);

        $select->setIntegrityCheck(false);
        $select->from(
            array("Internet"),
            array('Internet.Descricao'),
            $this->_schema
        );

        $select->joinInner(array("PreProjeto" => "PreProjeto"), 'PreProjeto.idAgente = Internet.idAgente', array(), $this->getSchema("sac"));
        $select->where("PreProjeto.idPreProjeto = ?", array($idPreProjeto));
        $select->where("Internet.Status = ?", array(1));

        return $this->_db->fetchAll($select);
    }
}