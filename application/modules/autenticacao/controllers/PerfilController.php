<?php
/**
 * Login e autenticação
 * @author Equipe RUP - Politec
 * @since 20/07/2010
 * @version 1.0
 * @package application
 * @subpackage application.controller
 * @link http://www.cultura.gov.br
 * @copyright © 2010 - Ministério da Cultura - Todos os direitos reservados.
 */

class Autenticacao_PerfilController extends MinC_Controller_Action_Abstract
{
    /**
     * Altera o pefil do usuário
     * @access public
     * @param void
     * @return void
     */
    public function alterarperfilAction()
    {
        $get      = Zend_Registry::get('get');
        $codGrupo = $get->codGrupo; // grupo do usuário logado
        $codOrgao = $get->codOrgao; // órgão do usuário logado

        $auth       = Zend_Auth::getInstance(); // pega a autenticação
        $GrupoAtivo = new Zend_Session_Namespace('GrupoAtivo'); // cria a sessão com o grupo ativo
        $GrupoAtivo->codGrupo = $codGrupo; // armazena o grupo ativo na sessão
        $GrupoAtivo->codOrgao = $codOrgao; // armazena o órgão ativo na sessão

        if($GrupoAtivo->codGrupo == "1111" && $GrupoAtivo->codOrgao == "2222"){
            $auth   = Zend_Auth::getInstance();
            $tblSGCacesso = new Sgcacesso();
            $rsSGCacesso = $tblSGCacesso->buscar(array("Cpf = ? "=>$auth->getIdentity()->usu_identificacao))->current()->toArray();
            $objAuth = $auth->getStorage()->write((object)$rsSGCacesso);

            $_SESSION["GrupoAtivo"]["codGrupo"] = $GrupoAtivo->codGrupo;
            $_SESSION["GrupoAtivo"]["codOrgao"] = $GrupoAtivo->codOrgao;
            parent::message("Seu perfil foi alterado no sistema. Voc&ecirc; ter&aacute; acesso a outras funcionalidades!", "principalproponente", "ALERT");
        }

        //Reescreve a sessao com o novo orgao superior
        $tblUsuario = new Usuario();
        $codOrgaoMaxSuperior = $tblUsuario->recuperarOrgaoMaxSuperior($codOrgao);
        $_SESSION['Zend_Auth']['storage']->usu_org_max_superior = $codOrgaoMaxSuperior;

        // redireciona para a página inicial do sistema
        parent::message("Seu perfil foi alterado no sistema. Voc&ecirc; ter&aacute; acesso a outras funcionalidades!", "principal", "ALERT");
    } // fecha alterarPerfilAction()

} // fecha class