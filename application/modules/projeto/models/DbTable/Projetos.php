<?php

/**
 * @todo Mover todas os métodos e alterar todas as referências para da antiga classe para essa.
 */
class Projeto_Model_DbTable_Projetos extends MinC_Db_Table_Abstract
{
    protected $_schema = 'sac';
    protected $_name = 'Projetos';
    protected $_primary = 'IdPRONAC';

    public function alterarOrgao($orgao, $idPronac) {
        $this->update(
            array(
                'Orgao' => $orgao
            ),
            array('IdPRONAC = ?' => $idPronac)
        );
    }

    public function alterarSituacao($idPronac = null, $situacao, $ProvidenciaTomada = null)
    {
        $auth = Zend_Auth::getInstance();
        $Logon = $auth->getIdentity()->usu_codigo;

        // grava no hist?rico a situa??o atual do projeto caso a trigger HISTORICO_INSERT esteja desabilitada
        $HistoricoInsert = new HistoricoInsert();
        if ($HistoricoInsert->statusHISTORICO_INSERT() == 1) { // desabilitada
            // busca a situa??o atual do projeto
            $p = $this->buscarSituacaoAtual($idPronac, $pronac);

            // grava o hist?rico da situa??o
            if ($situacao != $p['Situacao']) :
                $dadosHistorico = array(
                    'AnoProjeto' => $p['AnoProjeto'],
                    'Sequencial' => $p['Sequencial'],
                    'DtSituacao' => $p['DtSituacao'],
                    'Situacao' => $p['Situacao'],
                    'ProvidenciaTomada' => $p['ProvidenciaTomada'],
                    'Logon' => $p['Logon']);
                $HistoricoSituacao = new HistoricoSituacao();
                $cadastrarHistorico = $HistoricoSituacao->cadastrarDados($dadosHistorico);
            endif;
        } // fecha if

        $dados = array(
            'Situacao' => $situacao
        , 'DtSituacao' => new Zend_Db_Expr('GETDATE()')
        , 'ProvidenciaTomada' => $ProvidenciaTomada
        , 'Logon' => $Logon);

        $where = '';
        // alterar pelo idPronac
        if (!empty($idPronac)) {
            $where = "IdPRONAC = " . $idPronac;
        }

        // alterar pelo pronac
        if (!empty($pronac)) {
            $where = "(AnoProjeto+Sequencial) = '" . $pronac . "'";
        }

        //x("Se voce esta vendo esta mensagem, favor entrar em contato com o Everton ou Danilo Lisboa urgentemente! <br>Informe tambem os dados abaixo, se houver! ");
        //xd($where);
        if (!empty($where)) {
            return $this->update($dados, $where);
        } else {
            return new Exception("Erro ao alterar situa&ccedil;&atilde;o do Projeto.");
        }
    }

}