<?php

/**
 * Controller Login
 * @author tisomar - Politec
 * @author Vinícius Feitosa da Silva <viniciusfesil@mail.com>
 * @since 2011
 * @version 1.0
 * @package application
 * @subpackage application.controller
 * @link http://www.cultura.gov.br
 */
class Autenticacao_IndexController extends MinC_Controller_Action_Abstract
{
    public $orgaoAtivo;

    /**
     * Reescreve o método init()
     * @access public
     * @param void
     * @return void
     */
    public function init()
    {
        Zend_Layout::startMvc(array('layout' => 'layout_login'));
        parent::init();
    }

    public function indexAction()
    {
    }

    /**
     * Efetua o login no sistema
     * @access public
     * @param void
     * @return void
     *
     * @todo Melhorar a view do PostgreSQL onde usa 3 funcoes (tabelas.vwUsuariosOrgaosGrupos)
     */
    public function loginAction()
    {
        $this->_helper->layout->disableLayout();
        $username = Mascara::delMaskCNPJ(Mascara::delMaskCPF($this->getParam('Login', null)));
        $password = $this->getParam('Senha', null);

        try {

            if (empty($username) || empty($password))
            {
                throw new Exception("Login ou Senha inválidos!");
            } else if (strlen($username) == 11 && !Validacao::validarCPF($username)) {
                throw new Exception("O CPF informado é invalido!");
            } else if (strlen($username) == 14 && !Validacao::validarCNPJ($username)) {
                throw new Exception("O CPF informado é invalido!");
            } else {
                $Usuario = new Autenticacao_Model_Usuario();
                $buscar = $Usuario->login($username, $password);
                if ($buscar) {
                    $auth = array_change_key_case((array) Zend_Auth::getInstance()->getIdentity());
                    $objUnidades = $Usuario->buscarUnidades($auth['usu_codigo'], 21)->current();
                    if($objUnidades) {
                       $objUnidades = $objUnidades->toArray();
                    }
                    // registra o primeiro grupo do usuário (pega unidade autorizada, orgão e grupo do usuário)
                    $Grupo = array_change_key_case($objUnidades);
                    $GrupoAtivo = new Zend_Session_Namespace('GrupoAtivo');
                    $GrupoAtivo->codGrupo = $Grupo['gru_codigo'];
                    $GrupoAtivo->codOrgao = $Grupo['uog_orgao'];
                    $this->orgaoAtivo = $GrupoAtivo->codOrgao;

                    return $this->_helper->redirector->goToRoute(array('controller' => 'principal'), null, true);
                } else {
                    //se nenhum registro foi encontrado na tabela Usuario, ele passa a tentar se logar como proponente.
                    //neste ponto o _forward encaminha o processamento para o metodo login do controller login, que recebe
                    //o post igualmente e tenta encontrar usuario cadastrado em SGCAcesso
                    $this->forward("login-proponente", "index", "autenticacao");
//                    throw new Exception("Usuário inexistente!");
                }
            }

        } catch (Exception $objException) {
            echo '<pre>';
            var_dump($objException->getMessage());
            exit;
            parent::message($objException->getMessage(), "index", "ERROR");
        }
    }

    /**
     * Método que efetua o login
     * @access public
     * @param void
     * @return void
     */
    public function loginProponenteAction()
    {
        // recebe os dados do formulário via post
        $username = Mascara::delMaskCNPJ(Mascara::delMaskCPF($this->getParam('Login', null)));
        $password = $this->getParam('Senha', null);
        $password = str_replace("##menor##", "<", $password);
        $password = str_replace("##maior##", ">", $password);
        $password = str_replace("##aspa##", "'", $password);

        try {
            if (empty($username) || empty($password)) {
                # verifica se os campos foram preenchidos
                parent::message("Senha ou login inv&aacute;lidos", "/login/index", "ALERT");
            } else if (strlen($username) == 11 && !Validacao::validarCPF($username)) {
                # verifica se o CPF e valido
                parent::message("CPF inv&aacute;lido", "/login/index");
            } else if (strlen($username) == 14 && !Validacao::validarCNPJ($username)) // verifica se o CNPJ é válido
            {
                parent::message("CNPJ inv&aacute;lido", "/login/index");
            } else {
                // realiza a busca do usuario no banco, fazendo a autenticação do mesmo
                $Usuario = new Autenticacao_Model_Sgcacesso();
                $verificaStatus = $Usuario->buscar(array('cpf = ?' => $username))->toArray();
                if ($verificaStatus) {
                    $verificaStatus = array_change_key_case(reset($verificaStatus));

                    $IdUsuario = $verificaStatus['idusuario'];
                    $verificaSituacao = $verificaStatus['situacao'];

                    if (md5($password) != $this->validarSenhaInicial()) {
                        $SenhaFinal = EncriptaSenhaDAO::encriptaSenha($username, $password);
                        $buscar = $Usuario->loginSemCript($username, $SenhaFinal);
                    } else {
                        $buscar = $Usuario->loginSemCript($username, md5($password));
                    }
                    if (!$buscar) {
                        parent::message("Login ou Senha inv&aacute;lidos!", "/autenticacao", "ALERT");
                    }
                } else {
                    $SenhaFinal = addslashes($password);
                    $buscar = $Usuario->loginSemCript($username, $SenhaFinal);
                }

                if ($buscar) // acesso permitido
                {
                    $verificaSituacao = $verificaStatus['situacao'];
                    if ($verificaSituacao == 1) {
                        parent::message("Voc&ecirc; logou com uma senha tempor&aacute;ria. Por favor, troque a senha.", "/autenticacao/index/alterarsenha?idUsuario=" . $IdUsuario, "ALERT");
                    }
                    $agentes = new Agente_Model_Agentes();
                    $verificaAgentes = $agentes->buscar(array('cnpjcpf = ?' => $username))->current();
                    if (!empty ($verificaAgentes)) {
                        //                                        $this->_redirect("/agente/agentes/incluiragenteexterno");
                        //                                        parent::message("Voc&ecirc; ainda n&atilde;o est&aacute; cadastrado como proponente, por favor fa&ccedil;a isso agora.", "/manteragentes/agentes?acao=cc&idusuario={$verificaStatus[0]->IdUsuario}", "ALERT");
                        return $this->_helper->redirector->goToRoute(array('controller' => 'principalproponente'), null, true);
                    } else {
//                        return $this->_helper->redirector->goToRoute(array('controller' => 'principalproponente'), null, true);
                        parent::message("Voc&ecirc; ainda n&atilde;o est&aacute; cadastrado como proponente, por favor fa&ccedil;a isso agora.", "/agente/manteragentes/agentes?acao=cc&idusuario={$verificaStatus['idusuario']}", "ALERT");
                    }

                }
                else {
                    parent::message("Usu&aacute;rio n&atilde;o cadastrado", "/autenticacao", "ALERT");
                }
            }
        }
        catch (Exception $e) {
            echo '<pre>';
            var_dump($e->getMessage());
            exit;
            parent::message($e->getMessage(), "index", "ERROR");
        }
    }

    /**
     * Efetua o logout do sistema
     * @access public
     * @param void
     * @return void
     */
    public function logoutAction()
    {
        $auth = Zend_Auth::getInstance();
        $auth->clearIdentity();
        Zend_Session::destroy();
        unset($_SESSION);
        $this->redirect('index');
    }

    public function cadastrarusuarioAction()
    {
        if ($_POST) {
            $post = Zend_Registry::get('post');
            $cpf = Mascara::delMaskCNPJ(Mascara::delMaskCPF($post->cpf));

            if (trim($post->email) != trim($post->emailConf)) {
                parent::message("Digite o email certo!", "/autenticacao/index/cadastrarusuario", "ALERT");
            }

            $senha = Gerarsenha::gerasenha(15, true, true, true, false);
            $db= Zend_Db_Table::getDefaultAdapter();
            $senhaCriptografada = $senha;
            if (!($db instanceof Zend_Db_Adapter_Pdo_Mssql)) {
                $senhaCriptografada = EncriptaSenhaDAO::encriptaSenha($cpf, $senha);
            }
            $dataFinal = data::dataAmericana($post->dataNasc);

            $dados = array(
                "cpf" => $cpf,
                "nome" => $post->nome,
                "dtnascimento" => $dataFinal,
                "email" => $post->email,
                "senha" => $senhaCriptografada,
                "dtcadastro" => date("Y-m-d"),
                "situacao" => 1,
                "dtsituacao" => date("Y-m-d")
            );

            $sgcAcesso = new Autenticacao_Model_Sgcacesso();
            $sgcAcessoBuscaCpf = $sgcAcesso->buscar(array("Cpf = ?" => $cpf));

            $sgcAcessoBuscaCpfArray = $sgcAcessoBuscaCpf->toArray();

            if (!empty ($sgcAcessoBuscaCpfArray)) {
                parent::message("CPF j&aacute; cadastrado", "/autenticacao/index/cadastrarusuario", "ALERT");
            }

            $sgcAcessoBuscaEmail = $sgcAcesso->buscar(array("Email = ?" => $post->email));
            $sgcAcessoBuscaEmailArray = $sgcAcessoBuscaEmail->toArray();

            if (!empty ($sgcAcessoBuscaEmailArray)) {
                parent::message("E-mail j&aacute; cadastrado", "/autenticacao/index/cadastrarusuario", "ALERT");
            }

            if (empty ($sgcAcessoBuscaCpfArray) && empty ($sgcAcessoBuscaEmailArray)) {
                $sgcAcessoSave = $sgcAcesso->salvar($dados);

                /**
                 * ==============================================================
                 * INICIO DO VINCULO DO RESPONSAVEL COM ELE MESMO (PROPONENTE)
                 * ==============================================================
                 */
                /* ========== VERIFICA SE O RESPONSAVEL JA TEM CADASTRO COMO PROPONENTE ========== */
                $Agentes = new Agente_Model_Agentes();
                $Visao = new Visao();

                $buscarAgente = $Agentes->buscar(array('CNPJCPF = ?' => $cpf));
                $idAgenteProp = count($buscarAgente) > 0 ? $buscarAgente[0]->idagente : 0;
                $buscarVisao = $Visao->buscar(array('Visao = ?' => 144, 'stAtivo = ?' => 'A', 'idAgente = ?' => $idAgenteProp));
                /* ========== VINCULA O RESPONSAVEL A SEU PROPRIO PERFIL DE PROPONENTE ========== */
                if (count($buscarVisao) > 0) :
                    $tbVinculo = new TbVinculo();
                    $idResp = $sgcAcesso->buscar(array('Cpf = ?' => $sgcAcessoSave)); // pega o id do responsável cadastrado
                    $dadosVinculo = array(
                        'idAgenteProponente' => $idAgenteProp
                    , 'dtVinculo' => new Zend_Db_Expr('GETDATE()')
                    , 'siVinculo' => 2
                    , 'idUsuarioResponsavel' => $idResp[0]->idusuario);
                    $tbVinculo->inserir($dadosVinculo);
                endif;

                /**
                 * ==============================================================
                 * FIM DO VINCULO DO RESPONSAVEL COM ELE MESMO (PROPONENTE)
                 * ==============================================================
                 */

                /* ========== ENVIA O E-MAIL PARA O USUARIO ========== */
                $assunto = "Cadastro SALICWEB";
                $perfil = 'SALICWEB';
                $mens = "Ol&aacute; $post->nome ,<br><br>";
                $mens .= "Senha: $senha <br><br>";
                $mens .= "Esta &eacute; a sua senha de acesso ao Sistema de Apresenta&ccedil;&atilde;o de Projetos via Web do ";
                $mens .= "Minist&eacute;rio da Cultura.<br><br>Lembramos que a mesma dever&aacute; ser ";
                $mens .= "trocada no seu primeiro acesso ao sistema.<br><br>";
                $mens .= "Esta &eacute; uma mensagem autom&aacute;tica. Por favor n&atilde;o responda.<br><br>";
                $mens .= "Atenciosamente,<br>Minist&eacute;rio da Cultura";

                $enviaEmail = EmailDAO::enviarEmail($post->email, $assunto, $mens, $perfil);
                parent::message("Cadastro efetuado com sucesso. Verifique a senha no seu email", "/autenticacao", "CONFIRM");
            }
        }
    }

    public function solicitarsenhaAction()
    {

        if ($_POST) {
            //$enviaEmail = EnviaemailController::enviaEmail("ewrwr", "tiago.rodrigues@cultura.gov.br", "tisomar@gmail.com");
            $post = Zend_Registry::get('post');
            $cpf = Mascara::delMaskCNPJ(Mascara::delMaskCPF($post->cpf)); // recebe cpf
            $dataNasc = data::dataAmericana($post->dataNasc); // recebe dataNasc
            $email = $post->email; // recebe email
            $sgcAcesso = new Autenticacao_Model_Sgcacesso();
            $sgcAcessoBuscaCpf = $sgcAcesso->buscar(array("Cpf = ?" => $cpf, "Email = ?" => $email, "DtNascimento = ?" => $dataNasc));

            $verificaUsuario = $sgcAcessoBuscaCpf->toArray();
            if (empty ($verificaUsuario)) {
                parent::message("Dados incorretos!", "/autenticacao", "ALERT");
            }

            $sgcAcessoBuscaCpfArray = $sgcAcessoBuscaCpf->toArray();
            $nome = $sgcAcessoBuscaCpfArray[0]['Nome'];
            $senha = Gerarsenha::gerasenha(15, true, true, true, true);
            $senhaFormatada = str_replace(">", "", str_replace("<", "", str_replace("'", "", $senha)));

            $dados = array(
                "IdUsuario" => $sgcAcessoBuscaCpfArray[0]['IdUsuario'],
                "Senha" => $senhaFormatada,
                "Situacao" => 1,
                "DtSituacao" => date("Y-m-d")
            );
            $sgcAcessoSave = $sgcAcesso->salvar($dados);

            $assunto = "Cadastro SALICWEB";
            $perfil = "SALICWEB";
            $mens = "Ol&aacute; " . $nome . ",<br><br>";
            $mens .= "Senha....: " . $senhaFormatada . "<br><br>";
            $mens .= "Esta &eacute; a sua senha tempor&aacute;ria de acesso ao Sistema de Apresenta&ccedil;&atilde;o de Projetos via Web do ";
            $mens .= "Minist&eacute;rio da Cultura.<br><br>Lembramos que a mesma dever&aacute; ser ";
            $mens .= "trocada no seu primeiro acesso ao sistema.<br><br>";
            $mens .= "Esta &eacute; uma mensagem autom&aacute;tica. Por favor n?o responda.<br><br>";
            $mens .= "Atenciosamente,<br>Minist&eacute;rio da Cultura";

            $email = $sgcAcessoBuscaCpfArray[0]['Email'];
            $enviaEmail = EmailDAO::enviarEmail($email, $assunto, $mens, $perfil);
            parent::message("Senha gerada com sucesso. Verifique seu email!", "/autenticacao", "CONFIRM");
        }
    }

    public function alterarsenhaAction()
    {
        $auth = Zend_Auth::getInstance();
        $Usuario = new Autenticacao_Model_Usuario();
        $idUsuario = $Usuario->getIdUsuario(null, $auth->getIdentity()->cpf);

        if ($idUsuario) {
            $this->getIdUsuario = ($idUsuario) ? $idUsuario['idagente'] : $auth->getIdentity()->idusuario;
            $this->getIdUsuario = empty($this->getIdUsuario) ? 0 : $this->getIdUsuario;
            parent::perfil(4);
        }

        Zend_Layout::startMvc(array('layout' => 'layout_proponente'));

        $this->view->cpf = "";
        $this->view->nome = "";
        $dataFormatada = "";
        $this->view->dtNascimento = "";
        $this->view->email = "";
        if (count(Zend_Auth::getInstance()->getIdentity()) > 0) {
            $auth = Zend_Auth::getInstance();

            $idUsuario = $auth->getIdentity()->idusuario;

            $this->view->idUsuario = $auth->getIdentity()->idusuario;
            $cpf = $auth->getIdentity()->cpf;
            $this->view->cpf = $auth->getIdentity()->cpf;
            $this->view->nome = $auth->getIdentity()->nome;
            $dataFormatada = data::formatarDataMssql($auth->getIdentity()->dtnascimento);
            $this->view->dtNascimento = $dataFormatada;
            $this->view->email = $auth->getIdentity()->email;

        }

        if ($_POST) {

            $post = Zend_Registry::get('post');
            $senhaAtual = $post->senhaAtual;
            $senhaNova = $post->senhaNova;
            $repeteSenha = $post->repeteSenha;

            $senhaAtual = str_replace("##menor##", "<", $senhaAtual);
            $senhaAtual = str_replace("##maior##", ">", $senhaAtual);
            $senhaAtual = str_replace("##aspa##", "'", $senhaAtual);

            $sgcAcesso = new Autenticacao_Model_Sgcacesso();

            if (empty ($_POST['idUsuario'])) {
                $idUsuario = $_POST['idUsuarioGet'];
                $buscarSenha = $sgcAcesso->buscar(array('idusuario = ?' => $idUsuario))->toArray();
            } else {
                $idUsuario = $_POST['idUsuario'];
                $buscarSenha = $sgcAcesso->buscar(array('idusuario = ?' => $idUsuario))->toArray();
            }
            $senhaAtualBanco = $buscarSenha[0]['senha'];

            if (empty ($cpf)) {
                $cpf = $buscarSenha[0]['cpf'];
            }

            // busca a senha do banco TABELAS
            $Usuarios = new Autenticacao_Model_Usuario();
            $buscarCPF = $Usuarios->buscar(array('usu_identificacao = ?' => trim($cpf)));
            $cpfTabelas = count($buscarCPF) > 0 ? true : false;
            $senhaTabelas = $Usuarios->verificarSenha(trim($cpf), $senhaAtual);

            if ($buscarSenha[0]['situacao'] != 1) {

                $comparaSenha = EncriptaSenhaDAO::encriptaSenha($cpf, $senhaAtual);
                $SenhaFinal = $comparaSenha[0]->senha;

                if (trim($senhaAtualBanco) != trim($SenhaFinal) && ($cpfTabelas && !$senhaTabelas)) {
                    parent::message("Por favor, digite a senha atual correta!", "/autenticacao/index/alterarsenha?idUsuario=$idUsuario", "ALERT");
                }
            } else {

                if (trim($senhaAtualBanco) != trim($senhaAtual) && ($cpfTabelas && !$senhaTabelas)) {
                    parent::message("Por favor, digite a senha atual correta!", "/autenticacao/index/alterarsenha?idUsuario=$idUsuario", "ALERT");
                }
            }
            if (trim($senhaNova) == trim($repeteSenha) && !empty($senhaNova) && !empty($repeteSenha)) {

                if (empty ($idUsuario)) {
                    $post = Zend_Registry::get('post');
                    $idUsuario = $post->idUsuario;
                }
                $sgcAcessoBuscaCpf = $sgcAcesso->buscar(array("idusuario = ?" => $idUsuario));

                $cpf = $sgcAcessoBuscaCpf[0]['cpf'];
                $nome = $sgcAcessoBuscaCpf[0]['nome'];
                $email = $sgcAcessoBuscaCpf[0]['email'];
                $senhaCriptografada = EncriptaSenhaDAO::encriptaSenha($cpf, $senhaNova);
                //$SenhaFinal = $encriptaSenha[0]->senha;

                $dados = array(
                    "idusuario" => $idUsuario,
                    "senha" => $senhaCriptografada,
                    "situacao" => 3,
                    "dtsituacao" => date("Y-m-d")
                );


                $sgcAcessoSave = $sgcAcesso->salvar($dados);

                $assunto = "Cadastro SALICWEB";
                $perfil = "SALICWEB";
                $mens = "Ol&aacute; " . $nome . ",<br><br>";
                $mens .= "Senha....: " . $senhaNova . "<br><br>";
                $mens .= "Esta &eacute; a sua nova senha de acesso ao Sistema de Apresenta&ccedil;&atilde;o de Projetos via Web do ";
                $mens .= "Minist&eacute;rio da Cultura.<br><br>Lembramos que a mesma dever&aacute; ser ";
                $mens .= "trocada no seu primeiro acesso ao sistema.<br><br>";
                $mens .= "Esta &eacute; uma mensagem autom&aacute;tica. Por favor não responda.<br><br>";
                $mens .= "Atenciosamente,<br>Minist&eacute;rio da Cultura";

                $enviaEmail = EmailDAO::enviarEmail($email, $assunto, $mens, $perfil);
                parent::message("Senha alterada com sucesso!", "/autenticacao", "CONFIRM");
            }
        }
    }

    public function alterarsenhausuarioAction()
    {
        parent::perfil(0);
        // autenticação proponente (Novo Salic)

        /* ========== INÍCIO ID DO USUÁRIO LOGADO ========== */
        $auth = Zend_Auth::getInstance(); // pega a autentica��o
        $Usuario = new Autenticacao_Model_Usuario();

        // verifica se o usuário logado é agente
        $idUsuario = $Usuario->getIdUsuario(null, $auth->getIdentity()->usu_identificacao);
        if (isset($auth->getIdentity()->usu_identificacao)) {
            // caso não tenha idAgente, atribui o idUsuario
            $this->getIdUsuario = ($idUsuario) ? $idUsuario['idAgente'] : $auth->getIdentity()->usu_codigo;
            //$this->getIdUsuario = empty($this->getIdUsuario) ? 0 : $this->getIdUsuario;
            /* ========== FIM ID DO USUÁRIO LOGADO ========== */

        }

        Zend_Layout::startMvc(array('layout' => 'layout'));

        $this->view->cpf = "";
        $this->view->nome = "";

        if (count(Zend_Auth::getInstance()->getIdentity()) > 0) {
            $auth = Zend_Auth::getInstance();// instancia da autentica��o

            $idUsuario = $auth->getIdentity()->usu_codigo;
            $cpf = $auth->getIdentity()->usu_identificacao;

            $this->view->idUsuario = $auth->getIdentity()->usu_codigo;
            $this->view->cpf = $auth->getIdentity()->usu_identificacao;
            $this->view->nome = $auth->getIdentity()->usu_nome;

        }

        if ($_POST) {

            $post = Zend_Registry::get('post');

            $senhaAtual = $post->senhaAtual; // recebe senha atua
            $senhaNova = $post->senhaNova; // recebe senha nova
            $repeteSenha = $post->repeteSenha; // recebe repete senha

            $senhaAtual = str_replace("##menor##", "<", $senhaAtual);
            $senhaAtual = str_replace("##maior##", ">", $senhaAtual);
            $senhaAtual = str_replace("##aspa##", "'", $senhaAtual);

            if (empty ($_POST['idUsuario'])) {
                $idUsuario = $_POST['idUsuarioGet'];
                $buscarSenha = $Usuario->buscar(array('usu_codigo = ?' => $idUsuario))->toArray();
            } else {
                $idUsuario = $_POST['idUsuario'];
                $buscarSenha = $Usuario->buscar(array('usu_codigo = ?' => $idUsuario))->toArray();
            }
            $senhaAtualBanco = $buscarSenha[0]['usu_senha'];

            $comparaSenha = EncriptaSenhaDAO::encriptaSenha($cpf, $senhaAtual);
            $SenhaFinal = $comparaSenha[0]->senha;

            $comparaSenhaNova = EncriptaSenhaDAO::encriptaSenha($cpf, $senhaNova);
            $senhaNovaCript = $comparaSenhaNova[0]->senha;

            if (trim($senhaAtualBanco) != trim($SenhaFinal)) {
                parent::message("Por favor, digite a senha atual correta!", "/autenticacao/index/alterarsenhausuario?idUsuario=$idUsuario", "ALERT");
            }

            if ($repeteSenha != $senhaNova) {
                parent::message("Senhas diferentes!", "/autenticacao/index/alterarsenhausuario?idUsuario=$idUsuario", "ALERT");
            }

            if ($senhaAtualBanco == $senhaNovaCript) {
                parent::message("Por favor, digite a senha diferente da atual!", "/autenticacao/index/alterarsenhausuario?idUsuario=$idUsuario", "ALERT");
            }

            if (strlen(trim($senhaNova)) < 5) {
                parent::message("Por favor, sua nova senha dever� conter no m�nimo 5 d�gitos!", "/autenticacao/index/alterarsenhausuario?idUsuario=$idUsuario", "ALERT");
            }

            $alterar = $Usuario->alterarSenhaSalic($cpf, $senhaNova);
            if ($alterar) {
                parent::message("Senha alterada com sucesso!", "/principal/index/", "CONFIRM");
            } else {
                parent::message("Erro ao alterar senha!", "/autenticacao/index/alterarsenhausuario?idUsuario=$idUsuario", "ALERT");
            }
        }
    }

    public function logarcomoAction()
    {

        $this->_helper->layout->disableLayout(); // desabilita Zend_Layout
        Zend_Layout::startMvc(array('layout' => 'layout_proponente'));

        $buscaUsuario = new Usuariosorgaosgrupos();
        $buscaUsuarioRs = $buscaUsuario->buscarUsuariosOrgaosGrupos(
            array('gru_status > ?' => 0, 'sis_codigo = ?' => 21), array('usu_nome asc'));

        $this->view->buscaUsuario = $buscaUsuarioRs->toArray();


        if ($_POST) {


            // recebe os dados do formul&aacute;rio via post
            $post = Zend_Registry::get('post');
            $username = Mascara::delMaskCNPJ(Mascara::delMaskCPF($post->cpf)); // recebe o login sem máscaras
            $password = $post->senha; // recebe a senha
            $idLogarComo = $post->logarComo;

            $sql = "SELECT tabelas.dbo.fnEncriptaSenha('" . $username . "', '" . $password . "') as senha";
            $db= Zend_Db_Table::getDefaultAdapter();
            $db->setFetchMode(Zend_DB::FETCH_OBJ);
            $senha = $db->fetchRow($sql);

            $SenhaFinal = $senha->senha;

            $usuario = new Autenticacao_Model_Usuario();
            $usuarioRs = $usuario->buscar(
                array('usu_identificacao = ?' => $username, 'usu_senha = ?' => $SenhaFinal));

            if (!empty ($usuarioRs)) {
                $usuarioRs = $usuario->buscar(
                    array('usu_identificacao = ?' => $idLogarComo))->current();
                $senha = $usuarioRs->usu_senha;

                $Usuario = new Autenticacao_Model_Usuario();
                $buscar = $Usuario->loginSemCript($idLogarComo, $senha);

                if ($buscar) // acesso permitido
                {
                    $auth = Zend_Auth::getInstance(); // instancia da autentica�?o

                    // registra o primeiro grupo do usu&aacute;rio (pega unidade autorizada, organiza e grupo do usuaáio)
                    $Grupo = $Usuario->buscarUnidades($auth->getIdentity()->usu_codigo, 21); // busca todos os grupos do usuário

                    $GrupoAtivo = new Zend_Session_Namespace('GrupoAtivo'); // cria a sess?o com o grupo ativo
                    $GrupoAtivo->codGrupo = $Grupo[0]->gru_codigo; // armazena o grupo na sess?o
                    $GrupoAtivo->codOrgao = $Grupo[0]->uog_orgao; // armazena o org?o na sess?o

                    // redireciona para o Controller protegido
                    return $this->_helper->redirector->goToRoute(array('controller' => 'principal'), null, true);
                } // fecha if
            }
        }
    }

    public function alterardadosAction()
    {
        // autenticação proponente (Novo Salic)
        parent::perfil(4);

        /* ========== INÍCIO ID DO USUÁRIO LOGADO ========== */
        $auth = Zend_Auth::getInstance(); // pega a autentica��o
        $Usuario = new Autenticacao_Model_Usuario();

        // verifica se o usuário logado é agente
        $idUsuario = $Usuario->getIdUsuario(null, $auth->getIdentity()->Cpf);

        // caso não tenha idAgente, atribui o idUsuario
        $this->getIdUsuario = ($idUsuario) ? $idUsuario['idAgente'] : $auth->getIdentity()->IdUsuario;
        $this->getIdUsuario = empty($this->getIdUsuario) ? 0 : $this->getIdUsuario;
        /* ========== FIM ID DO USUÁRIO LOGADO ========== */

        $sgcAcesso = new Autenticacao_Model_Sgcacesso();
        $auth = Zend_Auth::getInstance();// instancia da autentica��o
        $cpf = Mascara::delMaskCPF($auth->getIdentity()->Cpf);
        $buscarDados = $sgcAcesso->buscar(array('Cpf = ?' => $cpf))->current();

        if (count(Zend_Auth::getInstance()->getIdentity()) > 0) {
            if (strlen($buscarDados['Cpf']) > 11) {
                $this->view->cpf = Mascara::addMaskCNPJ($buscarDados['Cpf']);
            } else {
                $this->view->cpf = Mascara::addMaskCPF($buscarDados['Cpf']);
            }

            $this->view->nome = $buscarDados['Nome'];
            $dataFormatada = Data::tratarDataZend($buscarDados['DtNascimento'], 'Brasileira');
            $this->view->dtNascimento = $dataFormatada;
            $this->view->email = $buscarDados['Email'];
        }

        $this->_helper->layout->disableLayout(); // desabilita Zend_Layout
        Zend_Layout::startMvc(array('layout' => 'layout_proponente'));

        if ($_POST) {
            $post = Zend_Registry::get('post');
            $cpf = Mascara::delMaskCNPJ(Mascara::delMaskCPF($post->cpf)); // recebe cpf
            $nome = $post->nome; // recebe o nome
            $dataNasc = $post->dataNasc; // recebe dataNasc
            $email = $post->email; // recebe email
            $emailConf = $post->emailConf; // recebe confirmacao senha
            $module = $this->getRequest()->getModuleName();
            $controller = $this->getRequest()->getControllerName();

            if (trim($email) != trim($emailConf)) {
                parent::message("Digite o email certo!", "/{$module}/{$controller}/alterardados", "ALERT");
            }

            $dataFinal = data::dataAmericana($dataNasc);
            $dados = array(
                "IdUsuario" => $auth->getIdentity()->IdUsuario,
                "Cpf" => $cpf,
                "Nome" => $nome,
                "DtNascimento" => $dataFinal . ' 00:00:00',
                "Email" => $email,
                "DtCadastro" => date("Y-m-d"),
                "DtSituacao" => date("Y-m-d")
            );

            $sgcAcessoSave = $sgcAcesso->salvar($dados);
            parent::message("Dados alterados com sucesso", "/{$module}/{$controller}/alterardados", "CONFIRM");
        }
    }
}

