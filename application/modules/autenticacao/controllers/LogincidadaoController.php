<?php

/**
 * Classe responsável por fazer a autenticação Utilizando o Login Cidadão.
 * @author Vinícius Feitosa da Silva <viniciusfesil@mail.com>
 * @since 06/10/16 11:25
 */
class Autenticacao_LogincidadaoController extends MinC_Auth_Controller_AOAuth
{
    /**
     * @author Vinícius Feitosa da Silva <viniciusfesil@mail.com>
     * @return void
     */
    public function successAction()
    {
        $this->redirect("/principal");
    }

    /**
     * @author Vinícius Feitosa da Silva <viniciusfesil@mail.com>
     * @return void
     */
    public function errorAction()
    {
        parent::message("Não foi possível autenticar na aplicação.", "/autenticacao", "ALERT");
    }

    /**
     * @access public
     * @return void
     * @author Vinícius Feitosa da Silva <viniciusfesil@gmail.com>
     */
    public function cadastrarusuarioAction()
    {
       try {
            if (!$_POST) {
                throw new Exception("Tentativa de acesso inv&aacute;lido.");
            }
            $objPost = $this->getAllParams();
            $cpf = Mascara::delMaskCNPJ(Mascara::delMaskCPF($objPost["cpf"]));
            $senhaCriptografada = EncriptaSenhaDAO::encriptaSenha($cpf, $objPost["id"]);
            $arrayDTNascimento = explode("T", $objPost["birthdate"]);
            $dtNascimento = $arrayDTNascimento[0];

            $dados = array(
                "cpf" => $cpf,
                "nome" => $objPost["full_name"],
                "dtnascimento" => $dtNascimento,
                "email" => $objPost["email"],
                "senha" => $senhaCriptografada,
                "dtcadastro" => date("Y-m-d"),
                "situacao" => 3,
                "dtsituacao" => date("Y-m-d"),
                "id_login_cidadao" => $objPost["id"]
            );
           
            $this->validarCadastro($dados);

            $sgcAcesso = new Autenticacao_Model_Sgcacesso();
            $pkSgcAcessoSave = $sgcAcesso->salvar($dados);

            $objAgentes = new Agente_Model_DbTable_Agentes();
            $buscarAgente = $objAgentes->buscar(array('cnpjcpf = ?' => $cpf));
            $idAgenteProp = count($buscarAgente) > 0 ? $buscarAgente[0]->idagente : 0;
            $objVisao = new Visao();
            $buscarVisao = $objVisao->buscar(array('visao = ?' => 144, 'stativo = ?' => 'A', 'idagente = ?' => $idAgenteProp));

            if (count($buscarVisao) > 0)  {
                $tbVinculo = new Agente_Model_DbTable_TbVinculo();
                $idResp = $sgcAcesso->buscar(array('Cpf = ?' => $pkSgcAcessoSave));
                $dadosVinculo = array(
                    'idAgenteProponente' => $idAgenteProp
                    ,'dtVinculo' => new Zend_Db_Expr('GETDATE()')
                    ,'siVinculo' => 2
                    ,'idUsuarioResponsavel' => $idResp[0]->idusuario
                );
                $tbVinculo->inserir($dadosVinculo);
            }

            parent::message("Bem vindo!", "/principal", "CONFIRM");

        } catch (Exception $objException) {
            parent::message($objException->getMessage(), "/autenticacao", "ALERT");
        }
    }

    /**
     * @param array $dados
     * @author Vinícius Feitosa da Silva <viniciusfesil@mail.com>
     * @return void
     */
    private function validarCadastro($dados)
    {
        $objSgcAcesso = new Autenticacao_Model_Sgcacesso();
        if (!$objSgcAcesso->hasCPFCadastrado($dados['cpf'])) {
            throw new Exception('CNPJ ou CPF j&aacute; cadastrado.');
        }

        if(!$objSgcAcesso->hasEmailCadastrado($dados['email'])) {
            throw new Exception('E-mail j&aacute; cadastrado');
        }
    }
}