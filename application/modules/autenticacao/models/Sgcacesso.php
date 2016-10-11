<?php

/**
 * Description of Sgcacesso
 *
 * @author augusto
 * @author wouerner <wouerner@gmail.com>
 * @author Ruy Junior Ferreira Silva <ruyjfs@gmail.com>
 * @author Vinicius Feitosa da Silva <viniciusfesil@mail.com>
 */
class Autenticacao_Model_Sgcacesso extends MinC_Db_Table_Abstract
{

    protected $_banco = 'controledeacesso';
    protected $_schema = 'controledeacesso';
    protected $_name = 'sgcacesso';
    protected $_primary = 'idusuario';

    /**
     * @var Zend_Db_Table
     */
    private static $instancia;

    /**
     * Responsavel por implementar o Singleton, retornando apenas uma instancia da classe
     * utilizando uma chamada estatica.
     * @return Autenticacao_Model_Sgcacesso
     * @author Vinicius Feitosa da Silva <viniciusfesil@mail.com>
     */
    public static function obterInstancia()
    {
        if (!self::$instancia) {
            self::$instancia = new Autenticacao_Model_Sgcacesso();
        }
        return self::$instancia;
    }

    public function init()
    {
        parent::init();
    }

    /**
     * Metodo para buscar os dados do usuï¿½rio de acordo com id (login scriptcase)
     * @access public
     * @dinamic
     * @param @cod (codigo do usuario)
     * @return bool
     */
    public static function loginScriptcase($cod)
    {
        // busca pelo usuario no banco de dados
        $buscar = self::obterInstancia()->buscar(array('IdUsuario = ?' => $cod));

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

        // configuracoes do banco de dados (seta uma nova conexao no arquivo config.ini)

        $config = new Zend_Config_Ini(
            APPLICATION_PATH. '/configs/application.ini',
            $conexao_scriptcase
        );

        $db = Zend_Db::factory($config->db);
        Zend_Db_Table::setDefaultAdapter($db);

        if ($buscar) { // realiza a autenticacao
            // configuracoes do banco
            $authAdapter = new Zend_Auth_Adapter_DbTable($db);
            $authAdapter->setTableName(self::obterInstancia()->getTableName())
                ->setIdentityColumn('cpf')
                ->setCredentialColumn('senha');

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
                $authData = $authAdapter->getResultRowObject(null, 'senha');

                // armazena os dados do usuï¿½rio
                $objAuth = $auth->getStorage()->write($authData);

                return true;
            }
        }
    }

    /**
     * @param $username
     * @param $password
     * @return bool
     */
    public function login($username, $password)
    {
        $objSgcAcesso = $this->select();
        $objSgcAcesso->where('cpf = ?', $username);

        $senhaCriptografada = EncriptaSenhaDAO::encriptaSenha($username, $password);

        $objSgcAcesso->where("senha in ('{$senhaCriptografada}') as senha)");
        $scriptSenha = $this->fetchRow($objSgcAcesso);
        $sql = $this->select();
        $sql->setIntegrityCheck(false);
        $sql->from($this, array
            (
                'cpf',
                'senha',
            )
        );
        $sql->where('cpf = ?', $username);
        $sql->where("senha  = ?", $objSgcAcesso);
        $buscar = $this->fetchRow($sql);

        if ($buscar) {
            $dbAdapter = Zend_Db_Table::getDefaultAdapter();
            $authAdapter = new Zend_Auth_Adapter_DbTable($dbAdapter);
            $authAdapter->setTableName($this->getTableName())// CONTROLEDEACESSO.dbo.sgcacesso
            ->setIdentityColumn('cpf')
                ->setCredentialColumn('senha');

            $authAdapter
                ->setIdentity($buscar['cpf'])
                ->setCredential($buscar['senha']);

            $auth = Zend_Auth::getInstance();
            $acesso = $auth->authenticate($authAdapter);

            if ($acesso->isValid()) {
                $authData = $authAdapter->getResultRowObject(null, 'senha');
                $auth->getStorage()->write($authData);
                return true;
            }
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
    public function buscar($where = array(), $order = array(), $tamanho = -1, $inicio = -1)
    {
        $select = $this->select()->from($this->_name, $this->_getCols(), $this->_schema);

        //adiciona quantos filtros foram enviados
        foreach ($where as $coluna => $valor) {
            if ($valor) {
                $select->where($coluna, $valor);
            }
        }
        $select->order($order);

        // paginacao
        if ($tamanho > -1) {
            $tmpInicio = 0;
            if ($inicio > -1) {
                $tmpInicio = $inicio;
            }
            $select->limit($tamanho, $tmpInicio);
        }

        return $this->fetchAll($select);
    }

    public function loginSemCript($username, $password) {
        // busca o usu?rio de acordo com o login e a senha


        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->from($this, array(
                'cpf',
                'senha',
            )
        );
        $select->where('cpf = ?', $username);
        if ($password != MinC_Controller_Action_Abstract::validarSenhaInicial()) {
            $select->where("senha  = ?", $password);
        }

        $buscar = $this->fetchRow($select);

        if ($buscar) { // realiza a autenticacao
            // configura??es do banco
            $dbAdapter = Zend_Db_Table::getDefaultAdapter();
            // pegamos o zend_auth

            $authAdapter = new Zend_Auth_Adapter_DbTable($dbAdapter);
            $authAdapter->setTableName($this->getTableName(null, null, false)) // CONTROLEDEACESSO.dbo.sgcacesso
            ->setIdentityColumn('cpf')
                ->setCredentialColumn('senha');

            // seta as credenciais informada pelo usu?rio
            $authAdapter
                ->setIdentity($buscar['cpf'])
                ->setCredential($buscar['senha']);

            // tenta autenticar o usu?rio
            $auth = Zend_Auth::getInstance();

            $acesso = $auth->authenticate($authAdapter);

            // verifica se o acesso foi permitido
            if ($acesso->isValid()) {
                // pega os dados do usu?rio com exce??o da senha
                $authData = $authAdapter->getResultRowObject(null, 'senha');

                // armazena os dados do usu?rio
                $auth->getStorage()->write($authData);

                return true;
            }
        }
    }

    /**
     * @param array $dados
     * @return mixed
     * @author Vinicius Feitosa da Silva <viniciusfesil@mail.com>
     */
    public function salvar(array $dados)
    {
        try {
            $tmpTblSgcAcesso = new Autenticacao_Model_Sgcacesso();

            if (isset($dados['idusuario'])) {
                $tmpTblSgcAcesso = $tmpTblSgcAcesso->buscar(array("idusuario = ?" => $dados['idusuario']))->current();
            } else {
                $tmpTblSgcAcesso = $tmpTblSgcAcesso->createRow();
            }
            //ATRIBUINDO VALORES AOS CAMPOS QUE FORAM PASSADOS
            if (isset($dados['cpf'])) $tmpTblSgcAcesso->cpf = $dados['cpf'];
            if (isset($dados['nome'])) $tmpTblSgcAcesso->nome = $dados['nome'];
            if (isset($dados['dtnascimento'])) $tmpTblSgcAcesso->dtnascimento = $dados['dtnascimento'];
            if (isset($dados['email'])) $tmpTblSgcAcesso->email = $dados['email'];
            if (isset($dados['senha'])) $tmpTblSgcAcesso->senha = $dados['senha'];
            if (isset($dados['dtcadastro'])) $tmpTblSgcAcesso->dtcadastro = $dados['dtcadastro'];
            if (isset($dados['situacao'])) $tmpTblSgcAcesso->situacao = $dados['situacao'];
            if (isset($dados['dtsituacao'])) $tmpTblSgcAcesso->dtsituacao = $dados['dtsituacao'];

            return $tmpTblSgcAcesso->save();
        } catch (Exception $objException) {
            throw new Exception($objException->getMessage(), 0, $objException);
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
    public function buscar_($where = array(), $order = array(), $tamanho = -1, $inicio = -1)
    {
        $slct = $this->select();
        $slct->from($this, array("*", "dtnascimento" => new Zend_Db_Expr("CONVERT(CHAR(20),dtnascimento, 120)")));

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

