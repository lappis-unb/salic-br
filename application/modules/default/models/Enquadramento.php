<?php

class Enquadramento extends MinC_Db_Table_Abstract
{
    protected $_name = "Enquadramento";
    protected $_schema = "sac";
    protected $_primary = "IdEnquadramento";

    public function alterarEnquadramento($dados)
    {
        $id = null;
        $tmpTbl = new Enquadramento();
        $tmpTbl = $tmpTbl->find($dados['IdEnquadramento'])->current();

        if ($tmpTbl) {
            //ATRIBUINDO VALORES AOS CAMPOS QUE FORAM PASSADOS
            $tmpTbl->Enquadramento = $dados['Enquadramento'];
            $tmpTbl->DtEnquadramento = $dados['DtEnquadramento'];
            $tmpTbl->Logon = $dados['Logon'];
            $id = $tmpTbl->save();
        }
        if ($id) {
            return $id;
        } else {
            return false;
        }
    }

    /**
     * M�todo para buscar o enquadramento do projeto
     * @access public
     * @param integer $idPronac
     * @param string $pronac
     * @param boolean $buscarTodos (informa se busca todos ou somente um)
     * @return array || object
     */
    public function buscarDados($idPronac = null, $pronac = null, $buscarTodos = true)
    {
        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->from($this->_name);

        // busca pelo idPronac
        if (!empty($idPronac)) {
            $select->where("IdPRONAC = ?", $idPronac);
        }

        // busca pelo pronac
        if (!empty($pronac)) {
            $select->where("AnoProjeto+Sequencial = ?", $pronac);
        }

        return $buscarTodos ? $this->fetchAll($select) : $this->fetchRow($select);
    } // fecha m�todo buscarDados()


    /**
     * M�todo para cadastrar
     * @access public
     * @param array $dados
     * @return integer (retorna o �ltimo id cadastrado)
     */
    public function cadastrarDados($dados)
    {
        return $this->insert($dados);
    } // fecha m�todo cadastrarDados()


    /**
     * M�todo para alterar
     * @access public
     * @param array $dados
     * @param integer $idEnquadramento
     * @param integer $idPronac
     * @param string $pronac
     * @return integer (quantidade de registros alterados)
     */
    public function alterarDados($dados, $idEnquadramento = null, $idPronac = null, $pronac = null)
    {
        // altera pelo id do enquadramento
        if (!empty($idEnquadramento)) {
            $where = "IdEnquadramento = " . $idEnquadramento;
        } // altera pelo id do pronac
        else if (!empty($idPronac)) {
            $where = "IdPRONAC = " . $idPronac;
        } // altera pelo pronac
        else if (!empty($pronac)) {
            $where = "AnoProjeto+Sequencial = " . $pronac;
        }

        return $this->update($dados, $where);
    } // fecha m�todo alterarDados()


    /**
     * M�todo para excluir
     * @access public
     * @param integer $idEnquadramento
     * @param integer $idPronac
     * @param string $pronac
     * @return integer (quantidade de registros exclu�dos)
     */
    public function excluirDados($idEnquadramento = null, $idPronac = null, $pronac = null)
    {
        // exclui pelo id do enquadramento
        if (!empty($idEnquadramento)) {
            $where = "IdEnquadramento = " . $idEnquadramento;
        } // exclui pelo id do pronac
        else if (!empty($idPronac)) {
            $where = "IdPRONAC = " . $idPronac;
        } // exclui pelo pronac
        else if (!empty($pronac)) {
            $where = "AnoProjeto+Sequencial = " . $pronac;
        }

        return $this->delete($where);
    }

    public function obterProjetosParaEnquadramento($order = null, $limit = null)
    {
        $select = $this->select();
        $this->_db->setFetchMode(Zend_DB::FETCH_OBJ);

        $queryMensagensNaoRespondidas = $this->select();
        $queryMensagensNaoRespondidas->setIntegrityCheck(false);
        $queryMensagensNaoRespondidas->from(array('tbMensagemProjeto' => 'tbMensagemProjeto'), 'count(*) as quantidade', $this->getSchema("BDCORPORATIVO.scsac"));
        $queryMensagensNaoRespondidas->where("projetos.IdPRONAC = tbMensagemProjeto.IdPRONAC");
        $queryMensagensNaoRespondidas->where("tbMensagemProjeto.idMensagemOrigem IS NULL");

        $queryMensagensRespondidas = $this->select();
        $queryMensagensRespondidas->setIntegrityCheck(false);
        $queryMensagensRespondidas->from(array('tbMensagemProjeto'), 'count(*) as quantidade', $this->getSchema("BDCORPORATIVO.scsac"));
        $queryMensagensRespondidas->where("projetos.IdPRONAC = tbMensagemProjeto.IdPRONAC");
        $queryMensagensNaoRespondidas->where("tbMensagemProjeto.idMensagemOrigem IS NOT NULL");

        $select->setIntegrityCheck(false);
        $select->from(
            array("projetos"),
            array('pronac' => New Zend_Db_Expr('projetos.AnoProjeto + projetos.Sequencial'),
                'projetos.nomeProjeto',
                'projetos.IdPRONAC',
                'projetos.CgcCpf',
                'projetos.idpronac',
                'projetos.Area as cdarea',
                'projetos.ResumoProjeto',
                'projetos.UfProjeto',
                'projetos.DtInicioExecucao',
                'projetos.DtFimExecucao',
                'projetos.Situacao',
                'projetos.DtSituacao',
                '(' . $queryMensagensNaoRespondidas->assemble() . ') as mensagens_nao_respondidas',
                '(' . $queryMensagensRespondidas->assemble() . ') as mensagens_respondidas',
            ),
            $this->_schema
        );

        $select->joinInner(array('Area' => 'Area'), 'Area.Codigo = projetos.Area', array('Area.Descricao AS area'));
        $select->joinLeft(array('Segmento' => 'Segmento'), 'Segmento.Codigo = projetos.Segmento', array('Segmento.Descricao AS segmento'));
        $select->where("projetos.situacao in ( ? )", array('B01', 'B03'));

        !empty($order) ? $select->order($order) : null;
        !empty($limit) ? $select->limit($limit) : null;
        return $this->_db->fetchAll($select);
    }

    public function obterProjetosParaEnquadramentoVinculados($id_usuario, $order = null, $limit = null)
    {
        $select = $this->select();
        $this->_db->setFetchMode(Zend_DB::FETCH_OBJ);

        $queryMensagensNaoRespondidas = $this->select();
        $queryMensagensNaoRespondidas->setIntegrityCheck(false);
        $queryMensagensNaoRespondidas->from(array('tbMensagemProjeto' => 'tbMensagemProjeto'), 'count(*) as quantidade', $this->getSchema("BDCORPORATIVO.scsac"));
        $queryMensagensNaoRespondidas->where("projetos.IdPRONAC = tbMensagemProjeto.IdPRONAC");
        $queryMensagensNaoRespondidas->where("tbMensagemProjeto.idMensagemOrigem IS NULL");

        $queryMensagensRespondidas = $this->select();
        $queryMensagensRespondidas->setIntegrityCheck(false);
        $queryMensagensRespondidas->from(array('tbMensagemProjeto'), 'count(*) as quantidade', $this->getSchema("BDCORPORATIVO.scsac"));
        $queryMensagensRespondidas->where("projetos.IdPRONAC = tbMensagemProjeto.IdPRONAC");
        $queryMensagensNaoRespondidas->where("tbMensagemProjeto.idMensagemOrigem IS NOT NULL");

        $select->setIntegrityCheck(false);
        $select->from(
            array("projetos"),
            array('pronac' => New Zend_Db_Expr('projetos.AnoProjeto + projetos.Sequencial'),
                'projetos.nomeProjeto',
                'projetos.IdPRONAC',
                'projetos.CgcCpf',
                'projetos.idpronac',
                'projetos.Area as cdarea',
                'projetos.ResumoProjeto',
                'projetos.UfProjeto',
                'projetos.DtInicioExecucao',
                'projetos.DtFimExecucao',
                'projetos.DtSituacao',
                'projetos.Situacao',
                '(' . $queryMensagensNaoRespondidas->assemble() . ') as mensagens_nao_respondidas',
                '(' . $queryMensagensRespondidas->assemble() . ') as mensagens_respondidas',
            ),
            $this->_schema
        );

        $select->joinInner(array('tbAvaliacaoProposta' => 'tbAvaliacaoProposta'), 'tbAvaliacaoProposta.idProjeto = projetos.idProjeto', array(), $this->_schema);
        $select->joinInner(array('Area' => 'Area'), 'Area.Codigo = projetos.Area', array('Area.Descricao AS area'), $this->_schema);
        $select->joinLeft(array('Segmento' => 'Segmento'), 'Segmento.Codigo = projetos.Segmento', array('Segmento.Descricao AS segmento'), $this->_schema);

        $select->where("projetos.situacao in ( ? )", array('B01', 'B03'));
        $select->where("tbAvaliacaoProposta.stEstado = ?", array('0'));
        $select->where("tbAvaliacaoProposta.idTecnico = ?", array($id_usuario));

        !empty($order) ? $select->order($order) : null;
        !empty($limit) ? $select->limit($limit) : null;

        return $this->_db->fetchAll($select);
    }

}