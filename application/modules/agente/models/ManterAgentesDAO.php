<?php
/**
 * ManterAgentesDAO
 * @author Equipe RUP - Politec
 * @author wouerner <wouerner@gmail.com>
 * @since 09/08/2010
 * @version 1.0
 * @package application
 * @subpackage application.model.DAO
 */

class Agente_Model_ManterAgentesDAO extends MinC_Db_Table_Abstract
{
    /**
     * Metodo para buscar agentes
     * @access public
     * @static
     * @param string $cnpjcpf
     * @param string $nome
     * @param integer $idAgente
     * @return object
     */
    public static function buscarAgentes($cnpjcpf = null, $nome = null, $idAgente = null)
    {
        $db= Zend_Db_Table::getDefaultAdapter();
        $schemaAgentes = parent::getSchema('agentes');
        $schemaSac = parent::getSchema('sac');

        $a = [
            'a.idagente'
            ,'a.cnpjcpf'
            ,'a.cnpjcpfsuperior'
            ,'a.tipopessoa'
        ];

        $e = [
            'e.tipologradouro'
            ,'e.cidade'
            ,'e.cep as cep'
            ,'e.uf'
            ,'e.status'
            ,'e.tipoendereco'
            ,'e.idendereco'
            ,'e.logradouro'
            ,'e.numero'
            ,'e.complemento'
            ,'e.bairro'
            ,'e.divulgar as divulgarendereco'
            ,'e.status as enderecocorrespondencia'
        ];

        $t = [
            't.sttitular'
            ,'t.cdarea'
            ,'t.cdsegmento'
        ];

        $sql = $db->select()->distinct()->from(['a' => 'agentes'], $a, $schemaAgentes)
            ->joinLeft(['n' => 'nomes'], 'n.idagente = a.idagente', ['n.descricao as nome'], $schemaAgentes)
            ->joinLeft(['e' => 'endereconacional'], 'e.idagente = a.idagente', $e, $schemaAgentes)
            ->joinLeft(['m' => 'municipios'], 'm.idmunicipioibge = e.cidade', '*', $schemaAgentes)
            ->joinLeft(['u' => 'uf'], 'u.iduf = e.uf', 'u.sigla as dsuf', $schemaAgentes)
            ->joinLeft(['ve' => 'verificacao'], 've.idverificacao = e.tipoendereco', 've.descricao as dstipoendereco', $schemaAgentes)
            ->joinLeft(['vl' => 'verificacao'], 'vl.idverificacao = e.tipologradouro', 'vl.descricao as dstipologradouro', $schemaAgentes)
            ->joinLeft(['t' => 'tbtitulacaoconselheiro'], 't.idagente = a.idagente', $t, $schemaAgentes)
            ->joinLeft(['v' => 'visao'], 'v.idagente = a.idagente', '*', $schemaAgentes)
            ->joinLeft(['sa' => 'area'], 'sa.codigo = t.cdarea', 'sa.descricao as dsarea', $schemaSac)
            ->joinLeft(['ss' => 'segmento'], 'ss.codigo = t.cdsegmento', 'ss.descricao as dssegmento', $schemaSac)
            ->where('a.tipopessoa = 0 or a.tipopessoa = 1')
            ;

        if (!empty($cnpjcpf)) {
            # busca pelo cpf/cnpj
            $sql->where('a.cnpjcpf = ?', $cnpjcpf);
        } if (!empty($nome)) {
            # filtra pelo nome
            $sql->where('n.descricao LIKE ?', '%'.$nome.'%');
        } if (!empty($idAgente)) {
            # busca de acordo com o id do agente
            $sql->where('a.idagente = ?',$idAgente);
        }

        $sql->order(['e.status Desc', 'n.descricao Asc']);
        $db->setFetchMode(Zend_DB::FETCH_OBJ);
//        echo '<pre>';
//        print_r($sql->assemble());
//        exit;
        return $db->fetchAll($sql);
    }

    /**
     * M�todo para buscar agentes vinculados
     *
     * @access public
     * @static
     * @param string $cnpjcpfSuperior
     * @param string $nome
     * @param integer $idAgente
     * @param integer $idVinculado
     * @param integer $idVinculoPrincipal
     * @return object
     */
    public static function buscarVinculados($cnpjcpfSuperior = null, $nome = null, $idAgente = null, $idVinculado = null, $idVinculoPrincipal = null)
    {
        $db= Zend_Db_Table::getDefaultAdapter();

        $a = [
            'a.idAgente'
            ,'a.CNPJCPF'
            ,'a.CNPJCPFSuperior'
        ];

        $sql = $db->select()
            ->from(['a' => 'Agentes'], $a, 'AGENTES.dbo')
            ->joinLeft(['n' => 'Nomes'], 'N.idAgente = A.idAgente', ['n.Descricao AS Nome'], 'AGENTES.dbo')
            ->joinLeft(['vis' => 'Visao'], 'a.idAgente = vis.idAgente', null, 'AGENTES.dbo')
            ->joinLeft(['ver' => 'Verificacao'], 'ver.idVerificacao = vis.Visao', null, 'AGENTES.dbo')
            ->joinLeft(['vin' => 'Vinculacao'], 'a.idAgente = vin.idAgente', null, 'AGENTES.dbo')
            ->joinLeft(['tp' => 'Tipo'], 'tp.idTipo = ver.IdTipo', null, 'AGENTES.dbo')
            ->where('a.TipoPessoa = 0 OR a.TipoPessoa = 1')
            ->where('n.TipoNome = 18 OR n.TipoNome = 19')
            ->where('vis.Visao = 198')
            ;

        if (!empty($cnpjcpfSuperior)) // busca pelo cnpj/cpf com o vinculo principal
        {
            $sql->where('a.CNPJCPFSuperior = ?', $cnpjcpfSuperior);
        }
        if (!empty($nome)) // filtra pelo nome
        {
            $sql->where('n.Descricao LIKE ?', "$nome%");
        }
        if (!empty($idAgente)) // busca pelo idAgente
        {
            $sql->where('vin.idAgente =  ?', $idAgente);
        }
        if (!empty($idVinculado)) // busca pelo idVinculado
        {
            $sql->where('vin.idVinculado =  ?', $idVinculado);
        }
        if (!empty($idVinculoPrincipal)) {// busca pelo idVinculoPrincipal
            $sql->where('vin.idVinculoPrincipal =  ?', $idVinculoPrincipal);
        }

        $sql->order(['n.Descricao']);

        $db->setFetchMode(Zend_DB::FETCH_OBJ);
        return $db->fetchAll($sql);
    }

    /**
     * M�todo para buscar os endere�os do agente
     *
     * @access public
     * @static
     * @param integer $idAgente
     * @return object
     */
    public static function buscarEnderecos($idAgente = null)
    {
        $db = Zend_Db_Table::getDefaultAdapter();

        $ve = [
            'VE.Descricao as TipoEndereco',
            'VE.idVerificacao as CodTipoEndereco',
        ];

        $m = [
            'M.Descricao as Municipio',
            'M.idMunicipioIBGE as CodMun',
        ];

        $u = [
            'U.Sigla as UF',
            'U.idUF as CodUF'
        ];

        $e =[
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
        ];

        $sql = $db->select()
            ->from(['E' => 'EnderecoNacional'], $e, 'AGENTES.dbo')
            ->joinLeft(['VE' => 'Verificacao'], 'VE.idVerificacao = E.TipoEndereco', $ve, 'AGENTES.dbo')
            ->joinLeft(['M' => 'Municipios'], 'M.idMunicipioIBGE = E.Cidade', $m, 'AGENTES.dbo')
            ->joinLeft(['U' => 'UF'], 'U.idUF = E.UF', $u, 'AGENTES.dbo')
            ->joinLeft(['VL' => 'Verificacao'], 'VL.idVerificacao = E.TipoLogradouro', ['VL.Descricao as dsTipoLogradouro'], 'AGENTES.dbo')
            ->where('E.idAgente = ?', $idAgente)
            ->order(['Status DESC'])
            ;

        $db->setFetchMode(Zend_DB::FETCH_OBJ);
        return $db->fetchAll($sql);
    }

    /**
     * M�todo para buscar os e-mails do agente
     *
     * @access public
     * @static
     * @param integer $idAgente
     * @return object
     */
    public static function buscarEmails($idAgente = null)
    {
        $db = Zend_Db_Table::getDefaultAdapter();

        $i = [
            'I.idInternet'
            ,'I.idAgente'
            ,'I.TipoInternet'
            ,'I.Descricao'
            ,'I.Status'
            ,'I.Divulgar'
        ];

        $sql = $db->select()
            ->from(['I' => 'Internet'], $i, 'AGENTES.dbo')
            ->join(['V' => 'Verificacao'], 'I.TipoInternet = V.idVerificacao', 'V.Descricao as tipo', 'AGENTES.dbo')
            ->join(['T' => 'Tipo'], 'T.idTipo = V.IdTipo', null, 'AGENTES.dbo')
        ;

        if (!empty($idAgente)) {// busca de acordo com o id do agente

            $sql->where('I.idAgente = ?', $idAgente);
        }

        $db->setFetchMode(Zend_DB::FETCH_OBJ);
        return $db->fetchAll($sql);
    }

    /**
     * M�todo para buscar os telefones do agente
     *
     * @access public
     * @static
     * @param integer $idAgente
     * @return object
     */
    public static function buscarFones($idAgente = null)
    {
        $db = Zend_Db_Table::getDefaultAdapter();

        $tl = [
            'Tl.idTelefone',
            'Tl.TipoTelefone',
            'Tl.Numero',
            'Tl.Divulgar',
            new Zend_Db_Expr("
                    CASE
                    WHEN Tl.TipoTelefone = 22 or Tl.TipoTelefone = 24
                    THEN 'Residencial'
                    WHEN Tl.TipoTelefone = 23 or Tl.TipoTelefone = 25
                    THEN 'Comercial'
                    WHEN Tl.TipoTelefone = 26
                    THEN 'Celular'
                    WHEN Tl.TipoTelefone = 27
                    THEN 'Fax'
                    END as dsTelefone
            ")
        ];

        $ddd = [
            'ddd.Codigo as DDD',
            'ddd.Codigo as Codigo',
        ];

        $sql = $db->select()
            ->from(['Tl' => 'Telefones'], $tl, 'AGENTES.dbo')
            ->join(['Uf' => 'Uf'], 'Uf.idUF = Tl.UF', ['Uf.Sigla as ufSigla'], 'AGENTES.dbo')
            ->join(['Ag' => 'Agentes'], 'Ag.IdAgente = Tl.IdAgente', ['Ag.IdAgente'], 'AGENTES.dbo')
            ->joinLeft(['ddd' => 'DDD'], 'Tl.DDD = ddd.Codigo', $ddd, 'AGENTES.dbo')
            ;

        if (!empty($idAgente)) { // busca de acordo com o id do agente
            $sql->where('Tl.idAgente = ?',$idAgente);
        }

        $db->setFetchMode(Zend_DB::FETCH_OBJ);
        return $db->fetchAll($sql);
    }

    /**
     * Metodo para cadastrar dados do agente
     * @access public
     * @static
     * @param array $dados
     * @return boolean
     */
    public static function cadastrarAgente($dados)
    {
        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        $insert = $db->insert(GenericModel::getStaticTableName('agentes', 'agentes'), $dados); // cadastra

        if ($insert)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * M�todo para cadastrar dados do agente
     * @access public
     * @static
     * @param array $dados
     * @return int (idAgente)
     */
    public static function cadastraAgente($dados)
    {
        //INSTANCIANDO UM OBJETO DE ACESSO AOS DADOS DA TABELA
        $Agentes = new Agente_Model_DbTable_Agentes();

        $rsAgente = $Agentes->createRow();

        //ATRIBUINDO VALORES AOS CAMPOS QUE FORAM PASSADOS
        if(isset($dados['stTipoRespPergunta'])){ $rsAgente->stTipoRespPergunta = $dados['stTipoRespPergunta']; }

        if(isset($dados['dsPergunta'])){ $rsAgente->dsPergunta = $dados['dsPergunta']; }

        if(isset($dados['dtCadastramento'])){ $rsAgente->dtCadastramento = $dados['dtCadastramento']; }

        if(isset($dados['idPessoaCadastro'])){ $rsAgente->idPessoaCadastro = $dados['idPessoaCadastro']; }

        //SALVANDO O OBJETO CRIADO
        $id = $rsAgente->save();

        if($id){
            return $id;
        }else{
            return false;
        }
    }

    /**
     * Metodo para alterar dados do agente
     * @access public
     * @static
     * @param integer $idAgente
     * @param array $dados
     * @return boolean
     *
     * @todo Existe uma trigger no db que impede o acesso direto a atualizacao. Pendente de verificacao
     */
    public static function alterarAgente($idAgente, $dados)
    {
        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);



        $where = "idAgente = " . $idAgente; // condicao para alteracao

        $update = $db->update('AGENTES.dbo.Agentes', $dados, $where); // altera

        if ($update)
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * M�todo para cadastrar o v�nculo entre os agentes
     * @access public
     * @static
     * @param array $dados
     * @return boolean
     */
    public static function cadastrarVinculados($dados)
    {
        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        $insert = $db->insert('AGENTES.dbo.Vinculacao', $dados); // cadastra

        return ($insert) ? true : false;
    }
}
