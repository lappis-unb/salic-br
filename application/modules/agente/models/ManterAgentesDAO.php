<?php

class Agente_Model_ManterAgentesDAO extends MinC_Db_Table_Abstract
{
    public static function buscarAgentes($cnpjcpf = null, $nome = null, $idAgente = null)
    {
        throw new Exception("Método descontinuado. Favor utilizar o m&eacute;todo buscarAgentes da classe.");
    }

    public function buscarVinculados($cnpjcpfSuperior = null, $nome = null, $idAgente = null, $idVinculado = null, $idVinculoPrincipal = null)
    {
        throw new Exception("Método descontinuado. Favor utilizar o m&eacute;todo 'buscarVinculados' da Agente_Model_DbTable_Agentes.");
    }

    public static function buscarEnderecos($idAgente = null)
    {
        $db = Zend_Db_Table::getDefaultAdapter();

        $ve = array(
            'VE.Descricao as TipoEndereco',
            'VE.idVerificacao as CodTipoEndereco',
        );

        $m = array(
            'M.Descricao as Municipio',
            'M.idMunicipioIBGE as CodMun',
        );

        $u = array(
            'U.Sigla as UF',
            'U.idUF as CodUF'
        );

        $e = array(
            'idEndereco',
            'idAgente',
            'Logradouro',
            'TipoLogradouro',
            'Numero',
            'Bairro',
            'Complemento',
            'Cep',
            'Status',
            'Divulgar',
            'Usuario',
        );

        $sql = $db->select()
            ->from(array('E' => 'EnderecoNacional'), $e, 'agentes')
            ->joinLeft(array('VE' => 'Verificacao'), 'VE.idVerificacao = E.TipoEndereco', $ve, 'agentes')
            ->joinLeft(array('M' => 'Municipios'), 'M.idMunicipioIBGE = E.Cidade', $m, 'agentes')
            ->joinLeft(array('U' => 'UF'), 'U.idUF = E.UF', $u, 'agentes')
            ->joinLeft(array('VL' => 'Verificacao'), 'VL.idVerificacao = E.TipoLogradouro', array('VL.Descricao as dsTipoLogradouro'), 'agentes')
            ->where('E.idAgente = ?', $idAgente)
            ->order(array('Status DESC'));

        $db->setFetchMode(Zend_DB::FETCH_OBJ);
        return $db->fetchAll($sql);
    }

    public static function buscarEmails($idAgente = null)
    {
        $tblAgentes = new Agente_Model_DbTable_Agentes();
        $db = Zend_Db_Table::getDefaultAdapter();

        $i = array(
            'i.idinternet'
        , 'i.idAgente'
        , 'i.tipointernet'
        , 'i.Descricao'
        , 'i.status'
        , 'i.Divulgar'
        );

        $sql = $db->select()
            ->from(array('i' => 'internet'), $i, $tblAgentes->getSchema('agentes'))
            ->join(array('v' => 'Verificacao'), 'i.tipointernet = v.idVerificacao', 'v.Descricao as tipo', $tblAgentes->getSchema('agentes'))
            ->join(array('t' => 'tipo'), 't.idTipo = v.idTipo', null, $tblAgentes->getSchema('agentes'));

        if (!empty($idAgente)) {// busca de acordo com o id do agente

            $sql->where('i.idAgente = ?', $idAgente);
        }

        $db->setFetchMode(Zend_DB::FETCH_OBJ);
        return $db->fetchAll($sql);
    }

    public static function buscarFones($idAgente = null)
    {
        $tblAgentes = new Agente_Model_DbTable_Agentes();
        $db = Zend_Db_Table::getDefaultAdapter();

        $tl = array(
            'tl.idTelefone',
            'tl.TipoTelefone',
            'tl.Numero',
            'tl.Divulgar',
        );

        $ddd = array(
            'ddd.Codigo as ddd',
            'ddd.Codigo as codigo',
        );

        $sql = $db->select()
            ->from(array('tl' => 'Telefones'), $tl, $tblAgentes->getSchema('agentes'))
            ->join(array('uf' => 'UF'), 'uf.iduf = tl.uf', array('uf.sigla as ufsigla'), $tblAgentes->getSchema('agentes'))
            ->join(array('ag' => 'Agentes'), 'ag.idAgente = tl.idAgente', array('ag.idAgente'), $tblAgentes->getSchema('agentes'))
            ->joinLeft(array('ddd' => 'DDD'), 'tl.DDD = ddd.Codigo', $ddd, $tblAgentes->getSchema('agentes'))
            ->joinLeft(array('v' => 'Verificacao'), 'v.idVerificacao = tl.TipoTelefone', array('v.Descricao as dstelefone'), $tblAgentes->getSchema('agentes'));
        if (!empty($idAgente)) { // busca de acordo com o id do agente
            $sql->where('tl.idAgente = ?', $idAgente);
        }
        $db->setFetchMode(Zend_DB::FETCH_OBJ);
        return $db->fetchAll($sql);
    }

    public static function cadastrarAgente($dados)
    {
        $db = Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        $insert = $db->insert(GenericModel::getStaticTableName('agentes', 'Agentes'), $dados); // cadastra

        if ($insert) {
            return true;
        } else {
            return false;
        }
    }

    public static function cadastraAgente($dados)
    {
        //INSTANCIANDO UM OBJETO DE ACESSO AOS DADOS DA TABELA
        $Agentes = new Agente_Model_DbTable_Agentes();

        $rsAgente = $Agentes->createRow();

        //ATRIBUINDO VALORES AOS CAMPOS QUE FORAM PASSADOS
        if (isset($dados['stTipoRespPergunta'])) {
            $rsAgente->stTipoRespPergunta = $dados['stTipoRespPergunta'];
        }

        if (isset($dados['dsPergunta'])) {
            $rsAgente->dsPergunta = $dados['dsPergunta'];
        }

        if (isset($dados['dtCadastramento'])) {
            $rsAgente->dtCadastramento = $dados['dtCadastramento'];
        }

        if (isset($dados['idPessoaCadastro'])) {
            $rsAgente->idPessoaCadastro = $dados['idPessoaCadastro'];
        }

        //SALVANDO O OBJETO CRIADO
        $id = $rsAgente->save();

        if ($id) {
            return $id;
        } else {
            return false;
        }
    }

    /**
     * @todo Existe uma trigger no db que impede o acesso direto a atualizacao. Pendente de verificacao
     */
    public static function alterarAgente($idAgente, $dados)
    {
        $db = Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);


        $where = "idAgente = {$idAgente}"; // condicao para alteracao

        $update = $db->update('agentes.dbo.Agentes', $dados, $where); // altera

        if ($update) {
            return true;
        } else {
            return false;
        }
    }

    public static function cadastrarVinculados($dados)
    {
        $db = Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        $insert = $db->insert('agentes.dbo.Vinculacao', $dados); // cadastra

        return ($insert) ? true : false;
    }
}
