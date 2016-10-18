<?php
class PlanoDistribuicao extends MinC_Db_Table_Abstract
{
    protected $_banco = "sac";
    protected $_schema = "sac";
    protected $_name = "planodistribuicaoproduto";
    //protected $_primary = "idPlanoDistribuicao";
    /**
     * Grava registro. Se seja passado um ID ele altera um registro existente
     * @param array $dados - array com dados referentes as colunas da tabela no formato "nome_coluna_1"=>"valor_1","nome_coluna_2"=>"valor_2"
     * @return ID do registro inserido/alterado ou FALSE em caso de erro
     */
    public function salvar($dados)
    {
        //INSTANCIANDO UM OBJETO DE ACESSO AOS DADOS DA TABELA
        $tmpTblPlanoDistribuicao = new PlanoDistribuicao();

        //DECIDINDO SE SERA FEITA UM INSERT OU UPDATE
        if(!empty($dados['idPlanoDistribuicao'])){
            $tmpRsPlanoDistribuicao = $tmpTblPlanoDistribuicao->find($dados['idPlanoDistribuicao'])->current();
        }else{
            $tmpRsPlanoDistribuicao = $tmpTblPlanoDistribuicao->createRow();
        }
        //ATRIBUINDO VALORES AOS CAMPOS QUE FORAM PASSADOS
        if(isset($dados['idProjeto'])){ $tmpRsPlanoDistribuicao->idProjeto = $dados['idProjeto']; }
        if(isset($dados['idProduto'])){ $tmpRsPlanoDistribuicao->idProduto = $dados['idProduto']; }
        if(isset($dados['Area'])){ $tmpRsPlanoDistribuicao->Area = $dados['Area']; }
        if(isset($dados['Segmento'])){ $tmpRsPlanoDistribuicao->Segmento = $dados['Segmento']; }
        if(isset($dados['idPosicaoDaLogo'])){ $tmpRsPlanoDistribuicao->idPosicaoDaLogo = $dados['idPosicaoDaLogo']; }
        if(isset($dados['QtdeProduzida'])){ $tmpRsPlanoDistribuicao->QtdeProduzida = $dados['QtdeProduzida']; }
        if(isset($dados['QtdePatrocinador'])){ $tmpRsPlanoDistribuicao->QtdePatrocinador = $dados['QtdePatrocinador']; }
        if(isset($dados['QtdeProponente'])){ $tmpRsPlanoDistribuicao->QtdeProponente = $dados['QtdeProponente']; }
        if(isset($dados['QtdeOutros'])){ $tmpRsPlanoDistribuicao->QtdeOutros = $dados['QtdeOutros']; }
        if(isset($dados['QtdeVendaNormal'])){ $tmpRsPlanoDistribuicao->QtdeVendaNormal = $dados['QtdeVendaNormal']; }
        if(isset($dados['QtdeVendaPromocional'])){ $tmpRsPlanoDistribuicao->QtdeVendaPromocional = $dados['QtdeVendaPromocional']; }
        if(isset($dados['PrecoUnitarioNormal'])){ $tmpRsPlanoDistribuicao->PrecoUnitarioNormal = $dados['PrecoUnitarioNormal']; }
        if(isset($dados['PrecoUnitarioPromocional'])){ $tmpRsPlanoDistribuicao->PrecoUnitarioPromocional = $dados['PrecoUnitarioPromocional']; }
        if(isset($dados['stPrincipal'])){ $tmpRsPlanoDistribuicao->stPrincipal = $dados['stPrincipal']; }
        if(isset($dados['Usuario'])){ $tmpRsPlanoDistribuicao->Usuario = $dados['Usuario']; }
        if(isset($dados['dsJustificativaPosicaoLogo'])){ $tmpRsPlanoDistribuicao->dsJustificativaPosicaoLogo = $dados['dsJustificativaPosicaoLogo'] ; }
        if(isset($dados['stPlanoDistribuicaoProduto'])){ $tmpRsPlanoDistribuicao->stPlanoDistribuicaoProduto = $dados['stPlanoDistribuicaoProduto'] ; }

        //echo "<pre>";
        //xd($tmpRsPlanoDistribuicao);
        //SALVANDO O OBJETO CRIADO

        $id = $tmpRsPlanoDistribuicao->save();

        if($id){
            return $id;
        }else{
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
    public function buscar($where=array(), $order=array(), $tamanho=-1, $inicio=-1)
    {
            // criando objeto do tipo select
            $slct = $this->select();

            $slct->setIntegrityCheck(false);

            $slct->from(array("a"=> $this->_name), $this->_getCols(), $this->_schema);
            $slct->joinInner(array("b"=>"produto"),
                            "a.idproduto = b.codigo",
                            array("Produto"=>"b.descricao"),
                            $this->_schema);
            $slct->joinInner(array("c"=>"verificacao"),
                            "a.idposicaodalogo = c.idverificacao",
                            array("PosicaoLogomarca"=>"c.descricao"),  $this->_schema);
            $slct->joinInner(array("ar"=>"area"),
                            "a.area = ar.codigo",
                            array("DescricaoArea"=>"ar.descricao"),  $this->_schema);
            $slct->joinInner(array("s"=>"segmento"),
                            "a.segmento = s.codigo",
                            array("DescricaoSegmento"=>"s.descricao"),  $this->_schema);

            $slct->where('a.stplanodistribuicaoproduto = ?', '1');

            // adicionando clausulas where
            foreach ($where as $coluna=>$valor)
            {
                    $slct->where($coluna, $valor);
            }

            // adicionando linha order ao select
            $slct->order($order);
                //xd($slct->__toString());
            // paginacao
            if ($tamanho > -1)
            {
                    $tmpInicio = 0;
                    if ($inicio > -1)
                    {
                            $tmpInicio = $inicio;
                    }
                    $slct->limit($tamanho, $tmpInicio);
            }

            //SETANDO A QUANTIDADE DE REGISTROS
            $this->_totalRegistros = $this->pegaTotal($where);
            //$this->_totalRegistros = 100;
            // retornando os registros conforme objeto select
            return $this->fetchAll($slct);
    }

    /**
     * Retorna registros do banco de dados
     * @param array $where - array com dados where no formato "nome_coluna_1"=>"valor_1","nome_coluna_2"=>"valor_2"
     * @param array $order - array com orders no formado "coluna_1 desc","coluna_2"...
     * @param int $tamanho - numero de registros que deve retornar
     * @param int $inicio - offset
     * @return Zend_Db_Table_Rowset_Abstract
     */
    public function buscarPlanoDistribuicao($where=array())
    {
        $slct = $this->select();
        $slct->setIntegrityCheck(false);
        $slct->from(
                array('a' => $this->_name),
                array(
                    'a.idplanodistribuicao',
                    'a.idprojeto',
                    'a.idproduto',
                    'a.area',
                    'a.segmento',
                    'a.idposicaodalogo',
                    'a.qtdeproduzida',
                    'a.qtdepatrocinador',
                    'a.qtdeproponente',
                    'a.qtdeoutros',
                    'a.qtdevendanormal',
                    'a.qtdevendapromocional',
                    'a.precounitarionormal',
                    'a.precounitariopromocional',
                    'a.stprincipal',
                    'a.usuario',
                    'CAST(a.dsjustificativaposicaologo AS TEXT) AS dsJustificativaPosicaoLogo',
                    'a.usuario'
                ),
                $this->_schema
        );

        foreach ($where as $coluna=>$valor)
        {
            $slct->where($coluna, $valor);
        }

        return $this->fetchRow($slct);
    }

    /**
     * Retorna registros do banco de dados
     * @param array $where - array com dados where no formato "nome_coluna_1"=>"valor_1","nome_coluna_2"=>"valor_2"
     * @param array $order - array com orders no formado "coluna_1 desc","coluna_2"...
     * @param int $tamanho - numero de registros que deve retornar
     * @param int $inicio - offset
     * @return Zend_Db_Table_Rowset_Abstract
     */
    public function pegaTotal($where=array(), $order=array(), $tamanho=-1, $inicio=-1)
    {
            // criando objeto do tipo select
            $slct = $this->select();

            $slct->setIntegrityCheck(false);

            $slct->from(array("a"=> $this->_name), '*', $this->_schema);
            $slct->joinInner(array("b"=>"Produto"),
                            "a.idProduto = b.Codigo",
                            array("Produto"=>"b.Descricao"),  $this->_schema);
            $slct->joinInner(array("c"=>"verificacao"),
                            "a.idPosicaoDaLogo = c.idVerificacao",
                            array("PosicaoLogomarca"=>"c.Descricao"),  $this->_schema);

			$slct->where('a.stPlanoDistribuicaoProduto = ?', '1');

            // adicionando clausulas where
            foreach ($where as $coluna=>$valor)
            {
                    $slct->where($coluna, $valor);
            }

            // adicionando linha order ao select
            $slct->order($order);

            // paginacao
            if ($tamanho > -1)
            {
                    $tmpInicio = 0;
                    if ($inicio > -1)
                    {
                            $tmpInicio = $inicio;
                    }
                    $slct->limit($tamanho, $tmpInicio);
            }
            try{
                        $rows = $this->fetchAll($slct);
                        return $rows->count();
            }catch(Exception $e){
                echo ($slct->assemble());die;
            }
    }

    public function apagar($id){
        $objApagar = $this->find($id)->current();

        return $objApagar->delete();
    }
}
