<?php

class Proposta_Model_DbTable_TbMovimentacao extends MinC_Db_Table_Abstract
{
    protected $_schema = 'sac';
    protected $_name = "tbMovimentacao";
    protected $_primary = "idMovimentacao";

    /**
     * Grava registro. Se seja passado um ID ele altera um registro existente
     * @param array $dados - array com dados referentes as colunas da tabela no formato "nome_coluna_1"=>"valor_1","nome_coluna_2"=>"valor_2"
     * @return ID do registro inserido/alterado ou FALSE em caso de erro
     */
    public function salvar($dados)
    {
        //DECIDINDO SE INCLUI OU ALTERA UM REGISTRO
        if(isset($dados['idMovimentacao']) && !empty ($dados['idMovimentacao'])){
            //UPDATE
            $rsAbrangencia = $this->find($dados['idMovimentacao'])->current();
        }else{
            //INSERT
            $dados['idMovimentacao'] = null;
            return $this->insert($dados);
            //$rsMovimentacao = $this->createRow();
        }

        //ATRIBUINDO VALORES AOS CAMPOS QUE FORAM PASSADOS
        $rsMovimentacao->idProjeto = $dados['idProjeto'];
        $rsMovimentacao->Movimentacao = $dados['Movimentacao'];
        $rsMovimentacao->DtMovimentacao = $dados['DtMovimentacao'];
        $rsMovimentacao->stEstado = $dados['stEstado'];
        $rsMovimentacao->Usuario = $dados['Usuario'];

        //SALVANDO O OBJETO
        $id = $rsMovimentacao->save();

        if($id){
            return $id;
        }else{
            return false;
        }
    }

    public function buscarStatusAtualProposta($idPreProjeto)
    {
        $slct = $this->select();
        $slct->setIntegrityCheck(false);
        $slct->from($this->_name, $this->_getCols(), $this->_schema);
        $slct->where('idProjeto = ? ', $idPreProjeto);
        $slct->where('stEstado = false');
        $slct->order(array("DtMovimentacao DESC"));
        $arrResult = $this->fetchRow($slct);
        return ($arrResult) ? $arrResult->toArray() : array();
    }

    public function buscarStatusPropostaNome($idPreProjeto)
    {
        if($idPreProjeto) {

            $slct = $this->select();
            $slct->setIntegrityCheck(false);
            $slct->from(
                array('mov' => $this->_name),
                $this->_getCols(),
                $this->_schema);

            $slct->joinInner(
                array('ver' => 'Verificacao'),
                'mov.Movimentacao = ver.idVerificacao',
                array('Descricao as MovimentacaoNome'),
                $this->_schema
            );
            $slct->where('mov.idProjeto = ?', $idPreProjeto);
            $slct->where('mov.stEstado::integer = ?', 0);
            $slct->order(array("mov.DtMovimentacao DESC"));

            $arrResult = $this->fetchRow($slct);
            if($arrResult) {
                return $arrResult->toArray();
            }
        }
        return array();
    }

    public function buscarTecCoordAdmissibilidade($idPronac, $idusuario=null) {

        $slct = $this->select();
        $slct->setIntegrityCheck(false);
        $slct->distinct();
        $slct->from(
                array('mov' => $this->_name), array()
        );

        $slct->joinInner(
                array('pre'=>'PreProjeto'),
                'pre.idPreProjeto = mov.idProjeto',
                array(),
                'SAC'
                );

        $slct->joinLeft(
                array('proj'=>'Projetos'),
                'proj.idProjeto = mov.idProjeto',
                array(),
                'SAC'
                );
        $slct->joinInner(
                array('usu'=>'Usuarios'),
                'usu.usu_codigo = mov.Usuario',
                array(),
                'TABELAS'
                );
        $slct->joinInner(
                array('age'=>'Agentes'),
                'age.CNPJCPF = usu.usu_identificacao',
                array('age.idAgente'),
                'agentes'
                );
        $slct->joinInner(
                array('nm'=>'Nomes'),
                'age.idAgente = nm.idAgente',
                array('Nome'=>'nm.Descricao'),
                'agentes'
                );

        $slct->joinInner(
                array('uog'=>'UsuariosXOrgaosXGrupos'),
                'uog.uog_usuario = usu.usu_codigo',
                array(),
                'TABELAS'
                );
        $slct->joinInner(
                array('gru'=>'Grupos'),
                'gru.gru_codigo = uog.uog_grupo',
                array('Perfil'=>'gru.gru_nome', 'cdPerfil'=>'gru.gru_codigo'),
                'TABELAS'
                );

        $slct->joinInner(
                array('org'=>'Orgaos'),
                'org.Codigo = uog.uog_orgao and org.Codigo = usu.usu_orgao',
                array('Orgao'=>'org.Sigla'),
                'SAC'
        );


        $slct->where('gru.gru_codigo in (92,131) ');
        $slct->where('proj.IdPRONAC = ? ', $idPronac);
        //$slct->where('usu.usu_codigo <> ? ', $idusuario);
        return $this->fetchAll($slct);
    }

    public function alterarConformidadeProposta($idPreProjeto, $idUsuario, $idVerificacao) {
        $arrayInclusao = array(
            'idProjeto' => $idPreProjeto,
            'Movimentacao' => $idVerificacao,
            'DtMovimentacao' => $this->getExpressionDate(),
            'stEstado' => 0,
            'Usuario' => $idUsuario,
        );
        $this->inserir($arrayInclusao);
    }
}
