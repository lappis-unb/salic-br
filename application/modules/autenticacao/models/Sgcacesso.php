<?php


/**
 * Description of Sgcacesso
 *
 * @author augusto
 * @author wouerner <wouerner@gmail.com>
 * @author Ruy Junior Ferreira Silva <ruyjfs@gmail.com>
 */
class Autenticacao_Model_Sgcacesso extends GenericModel {

    protected $_banco = 'controledeacesso';
    protected $_schema = 'controledeacesso';
    protected $_name = 'sgcacesso';

    /**
     * Método para buscar os dados do usuï¿½rio de acordo com id (login scriptcase)
     * @access public
     * @dinamic
     * @param @cod (codigo do usuario)
     * @return bool
     */
    public static function loginScriptcase($cod) {
        // busca pelo usuario no banco de dados
        $buscar = $this->buscar(array('IdUsuario = ?' => $cod));

        $conexao = Zend_Registry::get('conexao_banco');

        if ($conexao == "conexao_01") {
            $conexao_scriptcase = "conexao_scriptcase_01";
        } else if ($conexao == "conexao_02") {
            $conexao_scriptcase = "conexao_scriptcase_02";
        } else if ($conexao == "conexao_03") {
            $conexao_scriptcase = "conexao_scriptcase_03";
        } else if ($conexao == "conexao_04") {
            $conexao_scriptcase = "conexao_scriptcase_04";
        } else if ($conexao == "conexao_05") {
            $conexao_scriptcase = "conexao_scriptcase";
        }

        // configurações do banco de dados (seta uma nova conexï¿½o no arquivo config.ini)
        $config = new Zend_Config_Ini('./application/configs/config.ini', $conexao_scriptcase);
        $db = Zend_Db::factory($config->db);
        Zend_Db_Table::setDefaultAdapter($db);

        if ($buscar) { // realiza a autenticação
            // configurações do banco
            $authAdapter = new Zend_Auth_Adapter_DbTable($db);
            $authAdapter->setTableName('dbo.SGCacesso') // ControleDeAcesso.dbo.SGCacesso
                    ->setIdentityColumn('Cpf')
                    ->setCredentialColumn('Senha');

            // seta as credenciais informada pelo usuï¿½rio
            $authAdapter
                    ->setIdentity($buscar[0]->Cpf)
                    ->setCredential($buscar[0]->Senha);

            // tenta autenticar o usuï¿½rio
            $auth = Zend_Auth::getInstance();
            $acesso = $auth->authenticate($authAdapter);

            // verifica se o acesso foi permitido
            if ($acesso->isValid()) {
                // pega os dados do usuï¿½rio com exceï¿½ï¿½o da senha
                $authData = $authAdapter->getResultRowObject(null, 'Senha');

                // armazena os dados do usuï¿½rio
                $objAuth = $auth->getStorage()->write($authData);

                return true;
            }
        }
    }

// fecha método loginScriptcase()

    /**
     * @param $username
     * @param $password
     * @return bool
     *
     * @todo melhorar codigo para funcionar em ambos os bancos.
     */
    public function login($username, $password) {

        // busca o usu?rio de acordo com o login e a senha

        $senha = $this->select();
        $senha->where('Cpf = ?', $username);
        $senha->where("Senha in (select tabelas.dbo.fnEncriptaSenha('" . $username . "', '" . $password . "') as senha)");
        $criptSenha = $this->fetchRow($senha);
        $sql = $this->select();
        $sql->setIntegrityCheck(false);
        $sql->from($this, array
            (
            'Cpf',
            'Senha',
                )
        );
        $sql->where('Cpf = ?', $username);
        $sql->where("Senha  = ?", $senha);
        $buscar = $this->fetchRow($sql);

        if ($buscar) { // realiza a autentica??o
            // configura??es do banco
            $dbAdapter = Zend_Db_Table::getDefaultAdapter();
            // pegamos o zend_auth

            $authAdapter = new Zend_Auth_Adapter_DbTable($dbAdapter);
            $authAdapter->setTableName($this->_name) // CONTROLEDEACESSO.dbo.sgcacesso
                    ->setIdentityColumn('Cpf')
                    ->setCredentialColumn('Senha');

            // seta as credenciais informada pelo usu?rio
            $authAdapter
                    ->setIdentity($buscar['Cpf'])
                    ->setCredential($buscar['Senha']);

            // tenta autenticar o usu?rio
            $auth = Zend_Auth::getInstance();
            $acesso = $auth->authenticate($authAdapter);

            // verifica se o acesso foi permitido
            if ($acesso->isValid()) {
                // pega os dados do usu?rio com exce??o da senha
                $authData = $authAdapter->getResultRowObject(null, 'Senha');

                // armazena os dados do usu?rio
                $objAuth = $auth->getStorage()->write($authData);

                return true;
            } // fecha if
            else { // caso n?o tenha sido validado
                return false;
            }
        } // fecha if
        else {
            return false;
        }
    }

// fecha m?todo login()

    public function loginSemCript($username, $password) {
        // busca o usu?rio de acordo com o login e a senha


        $sql = $this->select();
        $sql->setIntegrityCheck(false);
        $sql->from($this, array(
                'cpf',
                'senha',
            )
        );
        $sql->where('cpf = ?', $username);
        if ($password != MinC_Controller_Action_Abstract::validarSenhaInicial()) {
            $sql->where("senha  = ?", $password);
        }

        $buscar = $this->fetchRow($sql);

        if ($buscar) { // realiza a autenticacao
            // configura??es do banco
            $dbAdapter = Zend_Db_Table::getDefaultAdapter();
            // pegamos o zend_auth

            $authAdapter = new Zend_Auth_Adapter_DbTable($dbAdapter);
            $authAdapter->setTableName($this->_name) // CONTROLEDEACESSO.dbo.sgcacesso
                    ->setIdentityColumn('cpf')
                    ->setCredentialColumn('senha');

            // seta as credenciais informada pelo usu?rio
            $authAdapter
                    ->setIdentity($buscar['cpf'])
                    ->setCredential($buscar['senha']);

            // tenta autenticar o usuario
            $auth = Zend_Auth::getInstance();
            $acesso = $auth->authenticate($authAdapter);

            // verifica se o acesso foi permitido
            if ($acesso->isValid()) {
                // pega os dados do usuario com excecao da senha
                $authData = $authAdapter->getResultRowObject(null, 'senha');

                // armazena os dados do usuario
                $objAuth = $auth->getStorage()->write($authData);

                return true;
            } // fecha if
            else { // caso nao tenha sido validado
                return false;
            }
        } // fecha if
        else {
            return false;
        }
    }

// fecha m?todo login()

    public function salvar($dados) {
        //INSTANCIANDO UM OBJETO DE ACESSO AOS DADOS DA TABELA
        $tmpTblSgcAcesso = new Autenticacao_Model_Sgcacesso();

        //DECIDINDO SE SERA FEITA UM INSERT OU UPDATE
        if (isset($dados['IdUsuario'])) {
            $tmpTblSgcAcesso = $tmpTblSgcAcesso->buscar(array("IdUsuario = ?" => $dados['IdUsuario']))->current();
        } else {
            $tmpTblSgcAcesso = $tmpTblSgcAcesso->createRow();
        }
        //ATRIBUINDO VALORES AOS CAMPOS QUE FORAM PASSADOS
        if (isset($dados['Cpf'])) {
            $tmpTblSgcAcesso->Cpf = $dados['Cpf'];
        }
        if (isset($dados['Nome'])) {
            $tmpTblSgcAcesso->Nome = $dados['Nome'];
        }
        if (isset($dados['DtNascimento'])) {
            $tmpTblSgcAcesso->DtNascimento = $dados['DtNascimento'];
        }
        if (isset($dados['Email'])) {
            $tmpTblSgcAcesso->Email = $dados['Email'];
        }
        if (isset($dados['Senha'])) {
            $tmpTblSgcAcesso->Senha = $dados['Senha'];
        }
        if (isset($dados['DtCadastro'])) {
            $tmpTblSgcAcesso->DtCadastro = $dados['DtCadastro'];
        }
        if (isset($dados['Situacao'])) {
            $tmpTblSgcAcesso->Situacao = $dados['Situacao'];
        }
        if (isset($dados['DtSituacao'])) {
            $tmpTblSgcAcesso->DtSituacao = $dados['DtSituacao'];
        }

        //echo "<pre>";
        //print_r($tmpRsVinculo);
        //SALVANDO O OBJETO CRIADO
        $id = $tmpTblSgcAcesso->save();

        if ($id) {
            return $id;
        } else {
            return false;
        }
    }

    /**
     * Retorna registros do banco de dados
     * @param array $where - array com dados where no formato "nome_coluna_1"=>"valor_1","nome_coluna_2"=>"valor_2"
     * @param array $order - array com orders no formado "coluna_1 desc","coluna_2"...
     * @param int $tamanho - numero de registros que deve retornar
     * @param int $inicio - offset
     * @return Zend_Db_Table_Rowset_Abstract
     */
    public function buscar_($where = array(), $order = array(), $tamanho = -1, $inicio = -1) {
        $slct = $this->select();
        $slct->from($this, array("*", "DtNascimento" => new Zend_Db_Expr("CONVERT(CHAR(20),DtNascimento, 120)")));

        //adiciona quantos filtros foram enviados
        foreach ($where as $coluna => $valor) {
            $slct->where($coluna, $valor);
        }

        //adicionando linha order ao select
        $slct->order($order);

        // paginacao
        if ($tamanho > -1) {
            $tmpInicio = 0;
            if ($inicio > -1) {
                $tmpInicio = $inicio;
            }
            $slct->limit($tamanho, $tmpInicio);
        }
        //xd($slct->__toString());
        return $this->fetchAll($slct);
    }

}

