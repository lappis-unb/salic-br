<?php
/**
 * LocalDeRealizacaoController
 * @author Equipe RUP - Politec
 * @author wouerner <wouerner@gmail.com>
 * @since 15/12/2010
 * @version 1.0
 * @package application
 * @subpackage application.controllers
 * @copyright ? 2010 - Ministerio da Cultura - Todos os direitos reservados.
 * @link http://www.cultura.gov.br
 */

class Proposta_LocalderealizacaoController extends MinC_Controller_Action_Abstract {
    private $idPreProjeto = null;
    private $usuarioLogado = null;
    private $idUsuario = 0;

    /**
     * Reescreve o metodo init()
     * @access public
     * @param void
     * @return void
     */
    public function init() {

        $auth = Zend_Auth::getInstance();
        $arrAuth = array_change_key_case((array) $auth->getIdentity());
        $PermissoesGrupo = array();

        //Da permissao de acesso a todos os grupos do usuario logado afim de atender o UC75
        if(isset($auth->getIdentity()->usu_codigo)){
            //Recupera todos os grupos do Usuario
            $Usuario = new Autenticacao_Model_Usuario(); // objeto usu�rio
            $grupos = $Usuario->buscarUnidades($arrAuth['usu_codigo'], 21);
            foreach ($grupos as $grupo){
                $PermissoesGrupo[] = $grupo->gru_codigo;
            }
        }

        isset($arrAuth['usu_codigo']) ? parent::perfil(1, $PermissoesGrupo) : parent::perfil(4, $PermissoesGrupo);

        $this->usuarioLogado = isset($arrAuth['usu_codigo']) ? $arrAuth['usu_codigo'] : $arrAuth['idusuario'];
        parent::init();

        //recupera ID do pre projeto (proposta)
        if(!empty ($_REQUEST['idPreProjeto'])) {
            $this->idPreProjeto = $_REQUEST['idPreProjeto'];

            //VERIFICA SE A PROPOSTA ESTA COM O MINC
            $Movimentacao = new Proposta_Model_DbTable_Movimentacao();
            $rsStatusAtual = $Movimentacao->buscarStatusAtualProposta($_REQUEST['idPreProjeto']);
            $this->view->movimentacaoAtual = isset($rsStatusAtual->Movimentacao) ? $rsStatusAtual->Movimentacao : '';
        }else {
            if($_REQUEST['idPreProjeto'] != '0'){
                parent::message("Necessário informar o número da proposta.", "/manterpropostaincentivofiscal/index", "ERROR");
            }
        }

        $this->idUsuario = isset($arrAuth['usu_codigo']) ? $arrAuth['usu_codigo'] : $arrAuth['idusuario'];

        //*******************************************
        //VALIDA ITENS DO MENU (Documento pendentes)
        //*******************************************
        $get = Zend_Registry::get("get");
        $model = new Proposta_Model_DbTable_DocumentosExigidos();
        $this->view->documentosPendentes = $model->buscarDocumentoPendente($get->idPreProjeto);

        if(!empty($this->view->documentosPendentes)) {
            $verificarmenu = 1;
            $this->view->verificarmenu = $verificarmenu;
        }
        else {
            $verificarmenu = 0;
            $this->view->verificarmenu = $verificarmenu;
        }

        //(Enviar Proposta ao MinC , Excluir Proposta)
        $mov = new Proposta_Model_DbTable_Movimentacao();
        $movBuscar = $mov->buscar(array('idprojeto = ?' => $get->idPreProjeto), array('idmovimentacao desc'), 1, 0)->current();

        if(isset($movBuscar->Movimentacao) && $movBuscar->Movimentacao != 95) {
            $enviado = 'true';
            $this->view->enviado = $enviado;
        }else {
            $enviado = 'false';
            $this->view->enviado = $enviado;
        }
        //*****************
        //FIM DA VALIDACAO
        //*****************

        /* =============================================================================== */
        /* ==== VERIFICA PERMISSAO DE ACESSO DO PROPONENTE A PROPOSTA OU AO PROJETO ====== */
        /* =============================================================================== */
        $this->verificarPermissaoAcesso(true, false, false);
    }

    /**
     * Metodo que monta grid de locais de realizacao
     *
     * @name indexAction
     * @return void
     *
     * @author Ruy Junior Ferreira Silva <ruyjfs@gmail.com>
     * @author wouerner <wouerner@gmail.com>
     * @since  17/08/2016
     */
    public function indexAction() {
        //RECUPERA OS LOCAIS DE REALIZACAO CADASTRADOS
        $arrBusca = array();
        $arrBusca['idprojeto']=$this->idPreProjeto;
        $arrBusca['stabrangencia'] = 1;
        $tblAbrangencia = new Proposta_Model_DbTable_Abrangencia();
        $rsAbrangencia = $tblAbrangencia->buscar($arrBusca);

        $arrDados = array("localizacoes"=>$rsAbrangencia,
                "acaoAlterar"=>$this->_urlPadrao."/proposta/localderealizacao/form-local-de-realizacao",
                "acaoExcluir"=>$this->_urlPadrao."/proposta/localderealizacao/excluir" );

        //METODO QUE MONTA TELA DO USUARIO ENVIANDO TODOS OS PARAMENTROS NECESSARIO DENTRO DO ARRAY DADOS
        $this->montaTela("localderealizacao/index.phtml", $arrDados);

    }

    /**
     * Metodo que monta o formulario de cadastro e alteracao de locais de realizacao
     * @param void
     * @return void
     */
    public function formLocalDeRealizacaoAction() {
        //recupera parametros
        $get = Zend_Registry::get('get');
        $idAbrangencia = $get->cod;

        //RECUPERA OS PAISES
        $table = new Agente_Model_DbTable_Pais();
        $arrPais = $table->fetchPairs('idpais', 'descricao');

        //RECUPRA OS ESTADOS
        $mapperUf = new Agente_Model_UFMapper();
        $arrUf = $mapperUf->fetchPairs('iduf', 'sigla');

        //RECUPERA LOCALIZACOES CADASTRADAS
        $tblAbrangencia = new Proposta_Model_DbTable_Abrangencia();
        $arrBusca = array();
        $arrBusca['idProjeto']=$this->idPreProjeto;
        $arrBusca['stAbrangencia']=1;
        if(!empty($idAbrangencia)) {
            $arrBusca['idAbrangencia']=$idAbrangencia;
        }
        $arrAbrangencia = $tblAbrangencia->buscar($arrBusca);
        $arrDados = array("paises"=>$arrPais,
                "estados"=>$arrUf,
                "localizacoes"=>$arrAbrangencia,
                "idAbrangencia"=>$idAbrangencia,
                "acao"=>$this->_urlPadrao."/localderealizacao/salvar" );

        //METODO QUE MONTA TELA DO USUARIO ENVIANDO TODOS OS PARAMENTROS NECESSARIO DENTRO DO ARRAY DADOS
        $this->montaTela("localderealizacao/formlocalderealizacao.phtml", $arrDados);
    }

    /**
     * Metodo responsavel por gravar os locais de realizacao em banco (INSERT e UPDATE)
     * @param void
     * @return objeto
     */
    public function salvarAction() {
        $post = Zend_Registry::get("post");
        $idAbrangencia = $post->cod;
        //instancia classe modelo
        $tblAbrangencia = new Proposta_Model_DbTable_Abrangencia();

        if(isset($_REQUEST['edital'])) {
            $edital = "&edital=s";
        }else {
            $edital = "";
        }

        $qtdeLocais = $post->qtdeLocais;
        $locais = array();
        $locaisinvalidos = array();
        for ($i=1; $i<=$qtdeLocais; $i++) {

            $pais 	= $post->__get("pais_".$i);
            $uf 	= $post->__get("uf_".$i);
            $municipio 	= $post->__get("cidade_".$i);
            $local_c    = $pais.$uf.$municipio;

            if (!in_array($local_c, $locaisinvalidos) || empty($local_c)) {

                $locais[$i]["idPais"]          = $post->__get("pais_".$i);

                if ($locais[$i]["idPais"] == 31) {
                    $locais[$i]["idUF"]            = $post->__get("uf_".$i);
                    $locais[$i]["idMunicipioIBGE"] = $post->__get("cidade_".$i);
                }
                else {
                    $locais[$i]["idUF"]            = "0";
                    $locais[$i]["idMunicipioIBGE"] = "0";
                }
            }else {
                parent::message("Registro j� cadastrado, transa��o cancelada!", "/proposta/localderealizacao/index?idPreProjeto=".$this->idPreProjeto.$edital, "ALERT");
            }
            $locaisinvalidos[$i] = $local_c;

        }

//        try {
            $global = 0;
            //incluindo novos registros
            if(empty($idAbrangencia)) {
                //APAGA TODOS OS REGISTROS PARA CADASTRALOS NOVAMENTE
                $tblAbrangencia->deleteBy(array('idprojeto' => $this->idPreProjeto, 'stabrangencia' => 1));
            } else {

                foreach($locais as $d) {

                    $p =  $d['idPais'];
                    if ($p == 31) {
                        $u =  $d['idUF'];
                        $m =  $d['idMunicipioIBGE'];
                    }
                    else {
                        $u =  0;
                        $m =  0;
                    }

                }

                $resultado = $tblAbrangencia->verificarIgual($p, $u, $m, $this->idPreProjeto);

                if(count($resultado)>0){
                    parent::message("Registro j&aacute; cadastrado, transa&ccedil;&atilde;o cancelada!", "/proposta/localderealizacao/index?idPreProjeto=".$this->idPreProjeto.$edital, "ALERT");
                    return;
                }

            }

            //INSERE LOCAIS DE REALIZACAO (tabela SAC.dbo.Abrangencia)
            for ($i=1; $i<=count($locais); $i++) {
                $dados = array( "idProjeto"=>$this->idPreProjeto,
                		"stAbrangencia" => 1,
                        "Usuario"  =>$this->usuarioLogado,
                        "idPais"   =>$locais[$i]["idPais"],
                        "idUF"     =>($locais[$i]["idPais"]==31)?$locais[$i]["idUF"]:0,
                        "idMunicipioIBGE"=>($locais[$i]["idPais"]==31)?$locais[$i]["idMunicipioIBGE"]:0);

                $dados['stAbrangencia']=1;
                $dados['idAbrangencia']=$idAbrangencia;
                if (!empty($dados["idProjeto"]) && !empty($dados["idPais"])) {

                    $retorno = $tblAbrangencia->salvar($dados);
                }
            }
            if($idAbrangencia) {
                parent::message("Altera&ccedil;&atilde;o realizada com sucesso!", "/proposta/localderealizacao/index?idPreProjeto=".$this->idPreProjeto.$edital, "CONFIRM");
            }
            else {
                parent::message("Cadastro realizado com sucesso!", "/proposta/localderealizacao/index?idPreProjeto=".$this->idPreProjeto.$edital, "CONFIRM");

            }

//        }catch(Zend_Exception $ex) {
//            parent::message("N&atilde;o foi poss&iacute;vel realizar a opera&ccedil;&atilde;o! <br>", "/proposta/localderealizacao/index?idPreProjeto=".$this->idPreProjeto.$edital, "ERROR");
//        }

    }

    /**
     * Metodo responsavel por apagar um local de realiza&ccedil;&atilde;o gravado
     * @param void
     * @return objeto
     */
    public function excluirAction() {
        $get = Zend_Registry::get("get");
        $idAbrangencia = $get->cod;

        if(isset($_REQUEST['edital'])) {
            $edital = "&edital=s";
        }else {
            $edital = "";
        }

        //EXCLUI REGISTRO DA TABELA ABRANGENCIA
        $mapper = new Proposta_Model_DbTable_Abrangencia();
        $excluir = $mapper->delete(['idabrangencia = ?' => $_GET['cod']]);

        if($excluir) {

            parent::message("Exclus&atilde;o realizada com sucesso!", "/proposta/localderealizacao/index?idPreProjeto=".$this->idPreProjeto.$edital, "CONFIRM");

        }else {
            parent::message("N&atilde;o foi poss&iacute;vel realizar a opera&ccedil;&atilde;o!", "/proposta/localderealizacao/index?idPreProjeto=".$this->idPreProjeto.$edital, "ERROR");
        }
    }

    /**
     * Metodo que retorna lista de locais de realizacao
     * @param void
     * @return objeto
     */
    public function consultarcomponenteAction() {
        //recebe o id via GET
        $get = Zend_Registry::get('get');
        $idProjeto = $get->idPreProjeto;
        $this->_helper->layout->disableLayout(); // desabilita o layout
        if(!empty($idProjeto) || $idProjeto=='0') {
            //RECUPERA OS LOCAIS DE REALIZACAO CADASTRADOS
            $arrBusca = array();
            $arrBusca['idProjeto']=$idProjeto;
            $arrBusca['stAbrangencia']=1;

            $tblAbrangencia = new Abrangencia();
            $rsAbrangencia = $tblAbrangencia->buscar($arrBusca);
            $this->view->localizacoes = $rsAbrangencia;
        }
        else {
            return false;
        }
    }

    /**
     * formInserirAction
     *
     * @access public
     * @return void
     */
    public function formInserirAction() {
        $get = Zend_Registry::get('get');
        $idProjeto = $get->idPreProjeto;
        $this->view->idPreProjeto = $idProjeto;

        # RECUPERA OS PAISES
        $tablePais = new Agente_Model_DbTable_Pais();
        $rsPais = $tablePais->fetchPairs('idpais', 'descricao');
        $this->view->paises = $rsPais;

        # RECUPERA OS ESTADOS
        $mapperUf = new Agente_Model_UFMapper();
        $rsEstados = $mapperUf->fetchPairs('iduf', 'descricao');
        $this->view->estados = $rsEstados;
    }

    /**
     * cidadesAction
     *
     * @access public
     * @return void
     */
    public function cidadesAction() {
        $this->_helper->layout->disableLayout();
        $this->_helper->viewRenderer->setNoRender(TRUE);
        $post = Zend_Registry::get('post');
        $idEstado = $post->idEstado;

        # RECUPERA AS CIDADES
        $table = new Agente_Model_DbTable_Municipios();
        $arrCidades = $table->fetchPairs('idmunicipioibge', 'descricao', array('idufibge' => $idEstado));
        $html = '';
        foreach ($arrCidades as $key => $cidades) {
            $html .= "<option value=\"{$key}\">{$cidades}</option>";
        }
        echo $html;
    }

    /**
     * salvarLocalRealizacaoAction
     *
     * @access public
     * @return void
     */
    public function salvarLocalRealizacaoAction() {
        $post = Zend_Registry::get("post");
        $idAbrangencia = $post->cod;
        $tblAbrangencia = new Proposta_Model_DbTable_Abrangencia();

        //RECUPERA LOCALIZACOES CADASTRADAS
        $arrBusca = array();
        $arrBusca['idProjeto']=$this->idPreProjeto;
        $arrBusca['stAbrangencia']=1;
        $arrBusca['p.idPais']=$post->pais;
        if($post->pais == 31){
            $arrBusca['u.idUF']=$post->estados;
            $arrBusca['m.idMunicipioIBGE']=$post->cidades;

        }

        if(!empty($idAbrangencia)) {
            $arrBusca['idAbrangencia']=$idAbrangencia;
        }
        $rsAbrangencia = $tblAbrangencia->buscar($arrBusca);

        if(count($rsAbrangencia)>0 && empty($idAbrangencia)){
            parent::message("Local de Realização já cadastrado!", "/proposta/localderealizacao/index?idPreProjeto=". $this->idPreProjeto . $edital, "ALERT");
        }

        if(isset($_REQUEST['edital'])) {
            $edital = "&edital=s";
        }else {
            $edital = "";
        }

        $pais = $post->pais;
        $estados = $post->estados;
        $cidades = $post->cidades;

        //INSERE LOCAIS DE REALIZACAO (tabela SAC.dbo.Abrangencia)
        $dados = array(
                    "idprojeto"=>$this->idPreProjeto,
                    "stabrangencia" => 1,
                    "usuario"  =>$this->usuarioLogado,
                    "idpais"   =>$pais,
                    "iduf"     =>($pais==31) ? $estados : 0,
                    "idmunicipioibge"=>($pais==31) ? $cidades : 0
                );

        if (!empty($dados["idprojeto"]) && !empty($dados["idpais"])) {
            $retorno = $tblAbrangencia->insert($dados);
        }

        parent::message("Cadastro realizado com sucesso!", "/proposta/localderealizacao/index?idPreProjeto=".$this->idPreProjeto.$edital, "CONFIRM");
    }
}
