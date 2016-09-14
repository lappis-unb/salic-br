<?php
class TbVinculo extends MinC_Db_Table_Abstract{

    protected $_banco = 'agentes';
    protected $_schema = 'agentes';
    protected $_name = 'tbvinculo';
    protected $_primary = 'idVinculo';


	public function buscarVinculoProponenteResponsavel($where=array())
    {
    	$slct = $this->select();
        $slct->distinct();
        $slct->setIntegrityCheck(false);

        $slct->from(
                array('vi' => $this->_name),
                array('*'),
                $this->_schema
        );

        $slct->joinInner(
                array('ag' => 'agentes'), "ag.idagente = vi.idagenteproponente",
                array('ag.cnpjcpf'),
                $this->_schema
        );

        $slct->joinInner(
                array('nm' => 'nomes'), "nm.idagente = ag.idagente",
                array('nm.descricao as nomeproponente'),
                $this->_schema
        );

        $slct
            ->joinLeft(
                array('sga' => 'sgcacesso'), "sga.idusuario = vi.idusuarioresponsavel",
                array('sga.idusuario as idusuarioresponsavel', 'sga.nome as nomeresponsavel', 'sga.cpf as cpfresponsavel'),
                $this->getSchema('controledeacesso')
            );

        $slct->joinLeft(
                array('vp' => 'tbvinculoproposta'), "vp.idvinculo = vi.idvinculo",
                array('vp.idvinculoproposta', 'vp.sivinculoproposta'),
                $this->_schema
        );

        foreach ($where as $coluna => $valor)
        {
            $slct->where($coluna, $valor);
        }
        return $this->fetchAll($slct);
    }

	/* M�todo que lista os vinculos do Proponente ao Responsavel
     *
     * */
    public function buscarProponenteResponsavel($idUsuarioLogado, $mecanismo = false)
    {
        $slct1 = $this->select()
                    ->setIntegrityCheck(false)
                    ->distinct()
                    ->from(
                            array('a' => 'Projetos'),
                            array(new Zend_Db_Expr('0 as Ordem'),'CgcCpf as CNPJCPF'), 'SAC.dbo'
                    )
                    ->joinInner(
                            array('b' => 'Agentes'), "a.CgcCpf = b.CNPJCPF",
                            array('idAgente', 'dbo.fnNome(b.idAgente) as NomeProponente'), 'AGENTES.dbo'
                    )
                    ->joinInner(
                            array('c' => 'SGCacesso'), "a.CgcCpf = c.Cpf",
                            array(), 'CONTROLEDEACESSO.dbo'
                    )
                    ->where('c.IdUsuario = ?', $idUsuarioLogado);

        if(!empty($mecanismo)){
            $slct1->where('a.Mecanismo = ?', $mecanismo);
        }


        $slct2 = $this->select()
                    ->setIntegrityCheck(false)
                    ->distinct()
                    ->from(
                            array('a' => 'Projetos'),
                            array(new Zend_Db_Expr('1 as Ordem')), 'SAC.dbo'
                    )
                    ->joinInner(
                            array('b' => 'Agentes'), "a.CgcCpf = b.CNPJCPF",
                            array('CNPJCPF', 'idAgente', 'dbo.fnNome(b.idAgente) as NomeProponente'), 'AGENTES.dbo'
                    )
                    ->joinInner(
                            array('c' => 'tbProcuradorProjeto'), "a.IdPRONAC = c.idPronac",
                            array(), 'AGENTES.dbo'
                    )
                    ->joinInner(
                            array('d' => 'tbProcuracao'), "c.idProcuracao = d.idProcuracao",
                            array(), 'AGENTES.dbo'
                    )
                    ->joinInner(
                            array('f' => 'Agentes'), "d.idAgente = f.idAgente",
                            array(), 'AGENTES.dbo'
                    )
                    ->joinInner(
                            array('e' => 'SGCacesso'), "f.CNPJCPF = e.Cpf",
                            array(), 'CONTROLEDEACESSO.dbo'
                    )
                    ->where('c.siEstado = ?', 2)
                    ->where('e.IdUsuario = ?', $idUsuarioLogado);

        if(!empty($mecanismo)){
            $slct2->where('a.Mecanismo = ?', $mecanismo);
        }

        $slct3 = $this->select()
                    ->setIntegrityCheck(false)
                    ->distinct()
                    ->from(
                            array('a' => 'Projetos'),
                            array(new Zend_Db_Expr('2 as Ordem')), 'SAC.dbo'
                    )
                    ->joinInner(
                            array('b' => 'Agentes'), "a.CgcCpf = b.CNPJCPF",
                            array('CNPJCPF', 'idAgente', 'dbo.fnNome(b.idAgente) as NomeProponente'), 'AGENTES.dbo'
                    )
                    ->joinInner(
                            array('c' => 'Vinculacao'), "b.idAgente = c.idVinculoPrincipal",
                            array(), 'AGENTES.dbo'
                    )
                    ->joinInner(
                            array('d' => 'Agentes'), "c.idAgente = d.idAgente",
                            array(), 'AGENTES.dbo'
                    )
                    ->joinInner(
                            array('e' => 'SGCacesso'), "d.CNPJCPF = e.Cpf",
                            array(), 'CONTROLEDEACESSO.dbo'
                    )
                    ->where('e.IdUsuario = ?', $idUsuarioLogado);

        if(!empty($mecanismo)){
            $slct3->where('a.Mecanismo = ?', $mecanismo);
        }

        $slctUnion = $this->select()
                            ->union(array('('.$slct1.')', '('.$slct2.')', '('.$slct3.')'))
                            ->order('Ordem ASC')
                            ->order('NomeProponente ASC');

        return $this->fetchAll($slctUnion);
    }

	/* M�todo que lista os respons�veis
     *
     * */
    public function buscarResponsaveis($where=array() , $idAgenteProponente)
    {
    	$slct = $this->select();
        $slct->distinct();
        $slct->setIntegrityCheck(false);

        $slct->from(
                array('SGA' => 'SGCacesso'),
                array('SGA.Nome AS NomeResponsavel', 'SGA.Cpf AS CpfResponsavel', 'SGA.IdUsuario AS idResponsavel'), 'CONTROLEDEACESSO.dbo'
        );

        $slct->joinLeft(
                array('VI' => $this->_name),'VI.idUsuarioResponsavel = SGA.IdUsuario AND idAgenteProponente = '.$idAgenteProponente,
                array('*')
        );

        $slct->joinLeft(
                array('ag' => 'Agentes'), "SGA.Cpf = ag.CNPJCPF",
                array('ag.CNPJCPF', 'ag.idAgente')
        );
        $slct->joinLeft(
                array('v' => 'Visao'), "v.idAgente = ag.idAgente AND v.Visao = 146",
                array('v.visao as UsuarioVinculo'), 'AGENTES.dbo'
        );

        foreach ($where as $coluna => $valor)
        {
            $slct->where($coluna, $valor);
        }
        //xd($slct->assemble());
        return $this->fetchAll($slct);
    }



	/**
	 * M�todo para buscar os Proponentes vinculados a um determinado Respons�vel, bem como, os Projetos desses Proponentes
	 * @access public
	 * @param integer $idResponsavel
	 * @param integer $idPronac
	 * @return object
	 */
	public function buscarProponentesProjetosResponsavel($idResponsavel, $idPronac)
	{
		$select = $this->select();
		$select->setIntegrityCheck(false);
		$select->from(array('v' => $this->_name));

		$select->joinInner(
			array('a' => 'Agentes'),
			'v.idAgenteProponente = a.idAgente',
			array(),
			'AGENTES.dbo'
		);

		$select->joinInner(
			array('p' => 'Projetos'),
			'a.CNPJCPF = p.CgcCpf',
			array(),
			'SAC.dbo'
		);

		$select->where('v.idUsuarioResponsavel = ?', $idResponsavel);
		$select->where('p.IdPRONAC             = ?', $idPronac);

		$select->order('v.idUsuarioResponsavel ASC');
		$select->order('v.idAgenteProponente ASC');
		$select->order('p.NomeProjeto ASC');

		return $this->fetchAll($select);
	} // fecha m�todo buscarProponentesProjetosResponsavel()



        /**
	 * M�todo para buscar os Proponentes vinculados a um determinado Respons�vel
	 * @access public
	 * @param integer $idResponsavel
	 * @param integer $idPronac
	 * @return object
	 */
	public function buscarProponentes($idResponsavel)
	{
            $slctResponsavel = $this->select()
                                ->setIntegrityCheck(false)
                                ->distinct()
                                ->from(
                                        array('a' => 'Agentes'),
                                        array('CNPJCPF', 'idAgente', 'dbo.fnNome(a.idAgente) AS NomeProponente'), 'AGENTES.dbo'
                                )
                                ->joinInner(
                                        array('c' => 'SGCacesso'), "a.CNPJCPF = c.Cpf",
                                        array(), 'CONTROLEDEACESSO.dbo'
                                )

                                ->where('c.IdUsuario = ?', $idResponsavel);


            $slctProponentes = $this->select()
                                ->setIntegrityCheck(false)
                                ->distinct()
                                ->from(
                                        array('a' => 'Agentes'),
                                        array('CNPJCPF', 'idAgente', 'dbo.fnNome(idAgente) as NomeProponente'), 'AGENTES.dbo'
                                )
                                ->joinInner(
                                        array('v' => 'tbVinculo'), "a.idAgente = v.idAgenteProponente",
                                        array(), 'AGENTES.dbo'
                                )
                                ->joinInner(
                                        array('k' => 'SGCacesso'), "k.IdUsuario = v.idUsuarioResponsavel",
                                        array(), 'CONTROLEDEACESSO.dbo'
                                )
                                ->where('k.IdUsuario = ?', $idResponsavel)
                                ->where('v.siVinculo = ?', 2);


            $slctUnion = $this->select()
                            ->union(array('('.$slctResponsavel.')', '('.$slctProponentes.')'))
                            ->order('NomeProponente ASC');

            return $this->fetchAll($slctUnion);

	} // fecha m�todo buscarProponentes()


}
