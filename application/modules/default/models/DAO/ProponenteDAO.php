<?php

Class ProponenteDAO extends Zend_Db_Table
{

    protected $_name = 'Projetos';
    protected $_schema = 'SAC';
    protected $_primary = 'IdPRONAC';

    public function execPaProponente($idPronac)
    {
        $idPronac = preg_replace("/[^0-9]/","", $idPronac); //REMOVE injections
        $sql = "exec sac.dbo.paAgente $idPronac";
        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);
        $resultado = $db->fetchAll($sql);


        return $resultado;
    }

    public function verificarHabilitado($CgcCPf)
    {
        $sql = "SELECT i.Habilitado FROM sac.dbo.Inabilitado i
                WHERE i.CgcCpf = '$CgcCPf' AND i.Habilitado='N'";

        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);
        $resultado = $db->fetchAll($sql);
        return $resultado;
    }

    public function buscarDadosProponente($idpronac)
    {
        $table = Zend_Db_Table::getDefaultAdapter();

        $select = $table->select()
            ->from(array('pr' => 'Projetos'),
                array(new Zend_Db_Expr('
                    case
                        when ag.tipoPessoa = 0 then \'Pessoa F�sica\'
                        when ag.tipoPessoa = 1 then \'Pessoa Jur�dica\'
                    end as tipoPessoa')),
                'SAC')
            ->joinInner(array('itn' => 'Interessado'),
                'pr.CgcCpf = itn.CgcCpf',
                array('Nome', 'Endereco', 'CgcCpf', 'Uf', 'Cidade', 'Esfera', 'Responsavel', 'Cep', 'Administracao', 'Utilidade'),
                'SAC')
            ->joinLeft(array('ag' => 'Agentes'),
                'ag.CNPJCPF = pr.CgcCpf',
                array(''),
                'agentes')
            ->joinLeft(array('nat' => 'Natureza'),
                'nat.idAgente = ag.idAgente',
                array('Direito'),
                'agentes')
            ->where('pr.IdPRONAC = ?', $idpronac);

        $db = Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);
        $resultado = $db->fetchAll($select);
        return $resultado;
    }

    public function buscarEmail($idpronac)
    {
        $db = Zend_Db_Table::getDefaultAdapter();

        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        $select = $db->select()
            ->from('Internet',
                array(new Zend_db_Expr('*'),new Zend_db_Expr("
                    CASE
                    WHEN Internet.TipoInternet = 28
                        THEN 'Email Particular'
                    WHEN Internet.TipoInternet = 29
                        THEN 'Email Institucional'
                    End as TipoInternet,
                    Internet.Descricao as Email    
                ")),
                'agentes')
            ->where('Projetos.IdPRONAC = ?', $idpronac)
            ->joinLeft('Agentes',
                'Agentes.IdAgente = Internet.IdAgente',
                array(''),
                'agentes')
            ->joinLeft('Projetos',
                'Agentes.CNPJCPF = Projetos.CgcCpf',
                array(''),
                'SAC');

        $resultado = $db->fetchAll($select);

        return $resultado;
    }

    public function buscarTelefone($idpronac)
    {
        $db = Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);

        $select = $db->select()
            ->from('Telefones',
                array(new Zend_db_Expr("
                    CASE
                        WHEN Telefones.TipoTelefone = 22 or Telefones.TipoTelefone = 24
                            THEN 'Residencial'
                        WHEN Telefones.TipoTelefone = 23 or Telefones.TipoTelefone = 25
                            THEN 'Comercial'
                        WHEN Telefones.TipoTelefone = 26
                            THEN 'Celular'
                        WHEN Telefones.TipoTelefone = 27
                            THEN 'Fax'
                        END as TipoTelefone,
                        Uf.Descricao as UF,
                        Telefones.DDD as DDDTelefone,
                        Telefones.Numero as NumeroTelefone,
                        CASE
                        WHEN Telefones.Divulgar = 1
                            THEN 'Sim'
                        WHEN Telefones.Divulgar = 0
                            THEN 'N�o'
                    end as Divulgar
                ")),
                'agentes')
            ->where('Projetos.IdPRONAC = ?', $idpronac)
            ->joinInner('Uf',
                'Uf.idUF = Telefones.UF',
                array(''),
                'agentes')
            ->joinInner('Agentes',
                'Agentes.IdAgente = Telefones.IdAgente',
                array(''),
                'agentes')
            ->joinInner('Projetos',
                'Agentes.CNPJCPF = Projetos.CgcCpf',
                array(''),
                'SAC');

        $resultado = $db->fetchAll($select);

        return $resultado;
    }

    public function dadospronacs($idpronac)
    {
        $sql = "select
            pr.AnoProjeto+pr.Sequencial as pronac,
            pr.NomeProjeto as nomeprojeto,
            ar.Descricao as area,
            seg.Descricao as seg,
            ap.AprovadoReal,
            sac.dbo.fnTotalCaptacao(pr.AnoProjeto, pr.Sequencial) as captado,
            sac.dbo.fnTotalAprovadoProjeto(pr.AnoProjeto, pr.Sequencial) as aprovado
            from sac.dbo.Projetos pr
            join sac.dbo.Aprovacao ap on ap.IdPRONAC = pr.IdPRONAC
            join sac.dbo.Area ar on ar.Codigo = pr.Area
            join sac.dbo.Segmento seg on seg.Codigo = pr.Segmento
            where pr.IdPRONAC = " . $idpronac;
        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);
        $resultado1 = $db->fetchAll($sql);

        return $resultado1;
    }

    public function buscarArquivados($idpronac)
    {
        $sql3 = "SELECT
							Pr.IdPRONAC,
                                                        Pr.AnoProjeto+Pr.Sequencial as pronac,
							Pr.NomeProjeto,
							Ar.descricao dsArea,
							Sg.descricao dsSegmento,
							Pr.SolicitadoReal,
							CASE WHEN Pr.Mecanismo IN ('2','6')
							THEN sac.dbo.fnValorAprovadoConvenio(Pr.AnoProjeto,Pr.Sequencial) 
							ELSE sac.dbo.fnValorAprovado(Pr.AnoProjeto,Pr.Sequencial)
							END AS ValorAprovado,
							sac.dbo.fnCustoProjeto (Pr.AnoProjeto,Pr.Sequencial) as ValorCaptado
							FROM sac.dbo.Projetos Pr 
							INNER JOIN sac.dbo.Situacao St ON St.Codigo = Pr.Situacao
							INNER JOIN sac.dbo.Area Ar ON  Ar.Codigo = Pr.Area
							INNER JOIN sac.dbo.Segmento Sg ON Sg.Codigo = Pr.Segmento
							INNER JOIN sac.dbo.Mecanismo Mc ON Mc.Codigo = Pr.Mecanismo
							INNER JOIN sac.dbo.Enquadramento En ON En.idPRONAC =  Pr.idPRONAC
							LEFT JOIN agentes.dbo.Agentes A ON A.CNPJCPF = Pr.CgcCpf
							LEFT JOIN sac.dbo.PreProjeto PP ON PP.idPreProjeto = Pr.idProjeto
							LEFT JOIN agentes.dbo.Nomes N ON N.idAgente = A.idAgente 
							LEFT JOIN sac.dbo.tbArquivamento Ta ON Ta.idPronac = Pr.idPRONAC and Ta.stEstado = '1'
							LEFT JOIN sac.dbo.Interessado I ON Pr.CgcCpf = I.CgcCpf
					  		WHERE Pr.idPRONAC = " . $idpronac . " and Ta.stEstado = '1'";

        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);
        $resultado1 = $db->fetchAll($sql3);

        return $resultado1;
    }

    public function buscarInativos($cpfCnpj)
    {
        $sql4 = "SELECT
                    Pr.IdPRONAC,
                    Pr.NomeProjeto,
                    Pr.AnoProjeto+Pr.Sequencial as pronac,
                    Ar.descricao dsArea,
                    Sg.descricao dsSegmento,
                    Pr.SolicitadoReal,
                    CASE WHEN Pr.Mecanismo IN ('2','6')
                    THEN sac.dbo.fnValorAprovadoConvenio(Pr.AnoProjeto,Pr.Sequencial)
                    ELSE sac.dbo.fnValorAprovado(Pr.AnoProjeto,Pr.Sequencial)
                    END AS ValorAprovado,
                    sac.dbo.fnCustoProjeto (Pr.AnoProjeto,Pr.Sequencial) as ValorCaptado
                    FROM sac.dbo.Projetos Pr
                    INNER JOIN sac.dbo.Situacao St ON St.Codigo = Pr.Situacao
                    INNER JOIN sac.dbo.Area Ar ON  Ar.Codigo = Pr.Area
                    INNER JOIN sac.dbo.Segmento Sg ON Sg.Codigo = Pr.Segmento
                    WHERE Pr.CgcCpf='$cpfCnpj' and St.StatusProjeto = '0' order by 2 asc ";

        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);
        $resultado1 = $db->fetchAll($sql4);

        return $resultado1;
    }

    public function buscarAtivos($cpfCnpj)
    {
        $sql5 = "SELECT
                    Pr.IdPRONAC,
                    Pr.AnoProjeto+Pr.Sequencial as pronac,
                    Pr.NomeProjeto,
                    Ar.descricao dsArea,
                    Sg.descricao dsSegmento,
                    Pr.SolicitadoReal,
                    CASE WHEN Pr.Mecanismo IN ('2','6')
                    THEN sac.dbo.fnValorAprovadoConvenio(Pr.AnoProjeto,Pr.Sequencial)
                    ELSE sac.dbo.fnValorAprovado(Pr.AnoProjeto,Pr.Sequencial)
                    END AS ValorAprovado,
                    sac.dbo.fnCustoProjeto (Pr.AnoProjeto,Pr.Sequencial) as ValorCaptado
                    FROM sac.dbo.Projetos Pr
                    INNER JOIN sac.dbo.Situacao St ON St.Codigo = Pr.Situacao
                    INNER JOIN sac.dbo.Area Ar ON  Ar.Codigo = Pr.Area
                    INNER JOIN sac.dbo.Segmento Sg ON Sg.Codigo = Pr.Segmento
                    WHERE Pr.CgcCpf='$cpfCnpj' and St.StatusProjeto = '1' order by 2 asc";

        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);
        $resultado1 = $db->fetchAll($sql5);

        return $resultado1;
    }

    public function buscarDirigentes($idPronac)
    {
        $sql = "SELECT A.CNPJCPF, N.Descricao
				FROM sac.dbo.Projetos P
					,sac.dbo.Interessado I
					,agentes.dbo.Agentes A
					,agentes.dbo.Nomes N
				
				WHERE P.CgcCpf = I.CgcCpf
					AND A.idAgente = N.idAgente
					AND I.CgcCpf = A.CNPJCPF
					AND P.IdPRONAC = $idPronac";

        $db= Zend_Db_Table::getDefaultAdapter();
        $db->setFetchMode(Zend_DB::FETCH_OBJ);
        $resultado = $db->fetchAll($sql);

        return $resultado;
    }

}