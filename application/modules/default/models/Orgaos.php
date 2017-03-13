<?php
class Orgaos extends MinC_Db_Table_Abstract{

    protected $_banco = 'SAC';
    protected $_name  = 'Orgaos';
    protected $_primary = 'Codigo';

    const ORGAO_SUPERIOR_SAV = 160;
    const ORGAO_SAV = 166;
    const ORGAO_SEFIC = 262;
    const ORGAO_SUPERIOR_SEFIC = 251;
    const ORGAO_SUPERIOR_IPHAN = 91;

    public function pesquisarTodosOrgaos() {
        $select = $this->select();
        $select->setIntegrityCheck(false);
        $select->distinct();
        $select->from(
                array('o'=>$this->_name),
                array(
                'o.Codigo',
                'Tabelas.dbo.fnEstruturaOrgao(o.codigo, 0) as Sigla',
                'org.org_nomeautorizado'
                )
        );
        $select->joinInner(
                array('org'=>'vwUsuariosOrgaosGrupos'),
                'org.uog_orgao = o.Codigo ',
                array('org.org_nomeautorizado'),
                'Tabelas.dbo'
        );
        $select->where('o.Status = ?', 0);
        $select->where('o.idSecretaria IS NOT NULL');
//        $select->order('o.Codigo ASC');
        $select->order('2');

        return $this->fetchAll($select);
    }

    public function pesquisarNomeOrgao($codOrgao){
    	$select = $this->select();
        $select->setIntegrityCheck(false);
        $select->from(
                        array('o'=>$this->_name),
                        array(
                                'o.Codigo',
                                'o.Sigla as NomeOrgao'
                             )
                     );
        $select->where("o.Codigo = ?", $codOrgao);

       return $this->fetchAll($select);
    }

    public function codigoOrgaoSuperior($codOrgao){
    	$select = $this->select();
        $select->setIntegrityCheck(false);
        $select->from(array('o'=>$this->_name),
                      array('o.Codigo',
                            'o.Sigla',
                            'o.idSecretaria as Superior')
		);

		$select->where("o.Codigo = ?", $codOrgao);

       return $this->fetchAll($select);
    }


    /**
     * Retorna registros do banco de dados
     * @param array $where - array com dados where no formato "nome_coluna_1"=>"valor_1","nome_coluna_2"=>"valor_2"
     * @param array $order - array com orders no formado "coluna_1 desc","coluna_2"...
     * @param int $tamanho - numero de registros que deve retornar
     * @param int $inicio - offset
     * @return Zend_Db_Table_Rowset_Abstract
     */
//    public function buscarOrgaoPorSegmento($area, $segmento)
//    {
//        $slct = $this->select();
//        $slct->setIntegrityCheck(false);
//        $slct->from(
//                    array("o"=>$this->_name),
//                    array("Codigo", "Sigla")
//                    );
//        $slct->joinInner(
//                        array("se"=>"Segmento"),
//                        "o.Codigo = se.idOrgao",
//                        array(),
//                        "SAC.dbo"
//                        );
//        $slct->joinInner(
//                        array("a"=>"Area"),
//                        "a.Codigo = substring(se.Codigo, 1, 1)",
//                        array(),
//                        "SAC.dbo"
//                        );
//
//        //adiciona quantos filtros foram enviados
//        $slct->where("se.stEstado = ?", 1);
//        $slct->where("se.Codigo = ?", $area);
//        $slct->orWhere("se.Codigo = ?", $segmento);
//        //xd($slct->assemble());
//        return $this->fetchAll($slct);
//    }

    /**
     * Retorna registros do banco de dados
     * @param array $where - array com dados where no formato "nome_coluna_1"=>"valor_1","nome_coluna_2"=>"valor_2"
     * @param array $order - array com orders no formado "coluna_1 desc","coluna_2"...
     * @param int $tamanho - numero de registros que deve retornar
     * @param int $inicio - offset
     * @return Zend_Db_Table_Rowset_Abstract
     */
    public function buscarOrgaoPorSegmento($segmento)
    {
        $slct = $this->select();
        $slct->setIntegrityCheck(false);
        $slct->from(
                    array("o"=>$this->_name),
                    array("Codigo", "Sigla")
                    );
        $slct->joinInner(
                        array("se"=>"Segmento"),
                        "o.Codigo = se.idOrgao",
                        array(),
                        "SAC.dbo"
                        );

        //adiciona quantos filtros foram enviados
        //$slct->where("se.stEstado = ?", 1);
        $slct->where("se.Codigo = ?", $segmento);
        //xd($slct->assemble());
        return $this->fetchAll($slct);
    }

    public function obterOrgaoSuperior($codOrgao) {
        $objQuery = $this->select();
        $objQuery->setIntegrityCheck(false);

        $objQuery->from(
            array('OrgaoSuperior' => $this->_name),
            'OrgaoSuperior.*',
            $this->_schema
        );

        $objQuery->joinInner(
            array('OrgaoFilho' => $this->_name),
            'OrgaoFilho.idSecretaria = OrgaoSuperior.Codigo',
            array(),
            $this->_schema
        );

        $objQuery->where("OrgaoFilho.Codigo = ?", $codOrgao);
        $resultado = $this->fetchRow($objQuery);
        if($resultado) {
            return $resultado->toArray();
        }
    }


    /*
     * Busca superintendências do IPHAN
     */
    public function buscarSuperintendencias() {

        $query = $this->select()
               ->from($this,
                      array('Codigo', 'Sigla'));

        $query->where('Vinculo = 1');
        $query->where('idSecretaria = ' . Orgaos::ORGAO_SUPERIOR_IPHAN);
        $query->order('Sigla');
        
        return $this->fetchAll($query);
    }
}
?>
