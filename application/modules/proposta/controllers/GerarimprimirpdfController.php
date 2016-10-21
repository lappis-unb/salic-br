<?php

/**
 * AnexarDocumentosController
 * @author Equipe RUP - Politec
 * @author wouerner <wouerner@gmail.com>
 * @since 28/04/2010
 * @link http://www.cultura.gov.br
 */
//require_once "GenericControllerNew.php";

class Proposta_GerarimprimirpdfController extends MinC_Controller_Action_Abstract
{
    public function init()
    {

        $this->view->title = "Salic - Sistema de Apoio �s Leis de Incentivo � Cultura"; // t�tulo da p�gina
        $auth = Zend_Auth::getInstance(); // pega a autenticacao
        $Usuario = new UsuarioDAO(); // objeto usuario
        $GrupoAtivo = new Zend_Session_Namespace('GrupoAtivo'); // cria a sess�o com o grupo ativo

        if ($auth->hasIdentity()) // caso o usuario esteja autenticado
        {
            // verifica as permissoes
            $PermissoesGrupo = array();
            $PermissoesGrupo[] = 93;  // Coordenador de Parecerista
            $PermissoesGrupo[] = 94;  // Parecerista
            $PermissoesGrupo[] = 103; // Coordenador de Analise
            $PermissoesGrupo[] = 118; // Componente da Comissao
            $PermissoesGrupo[] = 119; // Presidente da Mesa
            $PermissoesGrupo[] = 120; // Coordenador Administrativo CNIC
            //if (!in_array($GrupoAtivo->codGrupo, $PermissoesGrupo)) // verifica se o grupo ativo est� no array de permiss�es
            //{
            //    parent::message("Voc� n�o tem permiss�o para acessar essa �rea do sistema!", "principal/index", "ALERT");
            //}

            // pega as unidades autorizadas, org�os e grupos do usu�rio (pega todos os grupos)
//            $grupos = $Usuario->buscarUnidades($auth->getIdentity()->usu_codigo, 21);
//
//            // manda os dados para a visao
//            $this->view->usuario = $auth->getIdentity(); // manda os dados do usuario para a visao
//            $this->view->arrayGrupos = $grupos; // manda todos os grupos do usuario para a visao
//            $this->view->grupoAtivo = $GrupoAtivo->codGrupo; // manda o grupo ativo do usuario para a visao
//            $this->view->orgaoAtivo = $GrupoAtivo->codOrgao; // manda o orgao ativo do usuario para a visao

        } // fecha if
        else // caso o usuario nao esteja autenticado
        {
            return $this->_helper->redirector->goToRoute(array('controller' => 'index', 'action' => 'logout'), null, true);
        }
        parent::init(); // chama o init() do pai GenericControllerNew
    }
    public function indexAction(){
       $this->_helper->layout->disableLayout();

       function verifica($var){
           if($var or $var == 1){
               return "Sim";
           }else{
               return "N&atilde;o";
           }
       }
       function data($data){
           if(!empty ($data)){
                $dataF = new data($data);
                return $dataF->dataBrasileira($dataF->tratarDataZend($data, "americano"));
           }
       }
       function tratatexto($valor){

           $valor = str_replace('<br>', '|br|', $valor);
           $valor = str_replace('</p>', '|br|', $valor);
           $valor = strip_tags($valor);
           $valor = str_replace('|br|', '<br>', $valor);
           return $valor;

       }

       $id_projeto = $_REQUEST['idPreProjeto'];


       //$consultaDadosProjeto = GerarImprimirpdfDAO::ConsultaDadosProjeto($id_projeto);//busca dados do preprojeto na tabela PreProjeto
       $tblPreProjeto = new Proposta_Model_DbTable_PreProjeto();
       $consultaDadosProjeto = array_change_key_case( $tblPreProjeto->buscaCompleta(array("idPreProjeto = ?"=>$id_projeto))->current()->toArray() );

        $tbAb = new Proposta_Model_DbTable_Abrangencia();
        $abrangencias = $tbAb->buscar( array("idProjeto"=>$id_projeto) );

        $tbldivulgacao = new Proposta_Model_DbTable_PlanoDeDivulgacao();
        $divulgacoes= $tbldivulgacao->buscar(array("pd.idprojeto = ?" => $id_projeto)); //busca dados de divulgacao do preprojeto

        $tblPlanoDistribuicao = new PlanoDistribuicao();
        $rsPlanoDistribuicao = $tblPlanoDistribuicao->buscar(
            array("a.idprojeto = ?" => $id_projeto), array("idplanodistribuicao DESC"));

        $tbPlanilhaProposta = new Proposta_Model_DbTable_PlanilhaProposta;
        $planilhasProposta = $tbPlanilhaProposta->Orcamento($id_projeto);

        if($consultaDadosProjeto['mecanismo'] == "1" and ($consultaDadosProjeto['idedital'] == NULL or ($consultaDadosProjeto['idedital'] == "0"))){
            $mecanismo = 'Incentivo Fiscal';
        }else{
            $mecanismo = 'FNC';
        }
        //xd($consultaDadosProjeto);

        $texto = '<html>
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Leitor PDF</title>
        <style>
        body {
                font:12px "Trebuchet MS", Georgia, "Times New Roman", Times, serif;
                color:#666;
                line-height:14pt;
                margin:30px;
            }
            h2{
                margin:0px;
                margin-top:50px;
                border-bottom:1px #36963f solid;
                font-size:13pt;
            }

            p{
                padding:15px;
            }
        </style>
        </head>

        <body style="paddig:20px">
        <center><h1>Proposta Cultural</h1></center>
        <br>
        <h2>Identifica&ccedil;&atilde;o </h2>
        <table>
            <tr>
                <td width="300px">
                    N. da Proposta: <br><b>'.$id_projeto.'</b>
                </td>
                <td>
                    Nome da Proposta: <br><b>'.$consultaDadosProjeto['nomeprojeto'].'</b>
                </td>
             </tr>
           <tr>
                <td width="300px">
                    Proponente: <br><b>'.$consultaDadosProjeto['cnpjcpf'].' - '.$consultaDadosProjeto['nomeagente'].'</b>
                </td>
                <td>
                    Mecanismo: <br>
                    <b>'.$mecanismo.'</b>

                </td>
            </tr>
        </table>
        <br /><br />
        <h2>Resumo da Proposta Cultural </h2>

        <p class="MsoNormal">'.$consultaDadosProjeto['resumodoprojeto'].'</p>
        <br /><br />
        <h2>Abrang&ecirc;ncia geogr&aacute;fica da proposta cultural</h2>
    <br>
        <p>
            <table cellpadding="3" style="width:100%">
            <tr>
                <th>Pa&iacute;s</th>
                <th>UF</th>
                <th>Cidade</th>
                <th>Dt.In&iacute;cio de Execu&ccedil;&atilde;o</th>
                <th>Dt.Final de Execu&ccedil;&atilde;o</th>
            </tr>
                ';


                foreach ($abrangencias as $abrangencia )//busca locais onde o preprojeto sera realizado
                {
                    $texto .= '<tr bgcolor="#EEEEEE" valign="center"><td>'.$abrangencia['pais'].'</td>';
                    $texto .= '<td>'.$abrangencia['uf'].'</td>';
                    $texto .= '<td>'.$abrangencia['cidade'].'</td>';
                    $texto .= '<td>'.data($abrangencia['dtiniciodeexecucao']).'</td>';
                    $texto .= '<td>'.data($abrangencia['dtfinaldeexecucao']).'</td></tr>';
                }
    $texto .= '
          </table>
        </p>';

    if($mecanismo == 'Incentivo Fiscal'){
        ///DADOS APENAS PARA O INCENTIVO FISCAL
        $texto .= '<h2>Informa&ccedil;&otilde;es Complementares</h2>
        <table style="width:100%" cellpadding="13" border="0">
            <tr>
                <td>Mecanismo: '.$mecanismo.'</td>
                <td><nobr>Data Fixa: '.verifica($consultaDadosProjeto['stdatafixa']).'</nobr></td>
                <td>Plano Anual: '.verifica($consultaDadosProjeto['stplanoanual']).'</td>
                <td>Ag.Banc&aacute;ria: '.$consultaDadosProjeto['agenciabancaria'].'</td>
                <td>Proposta Audiovisual: '.verifica($consultaDadosProjeto['areaabrangencia']).'</td>
            </tr>
        </table>
        <br>
        <table style="width:100%; margin-top:50px">
            <tr>
                <td style="padding-right:10px">
                    <h2>Per&iacute;odo de Realiza&ccedil;&atilde;o</h2>
                    <p>
                    Data In&iacute;cio: '.data($consultaDadosProjeto['dtiniciodeexecucao']).'<br />
                    Data Final: '.data($consultaDadosProjeto['dtfinaldeexecucao']).'
                    </p>
                </td>
                <td style="padding-left:10px">
                    <h2>Bem Tombado</h2>
                    <p>';
                    if ($consultaDadosProjeto['esferatombamento'] > 0){
                      $texto .= 'N. do Ato: '.$consultaDadosProjeto['nratotombamento'].'
                                 Data do Ato: '.data($consultaDadosProjeto['dtatotombamento']).'
                                 Esfera do Ato: '.$consultaDadosProjeto['esferatombamento'];
                    }else{
                      $texto .= 'Bem n&atilde;o tombado';
                    };
                $texto .= '</p></td>
            </tr>
        </table>
        <h2>Objetivos do Projeto</h2>
        <p>'.tratatexto($consultaDadosProjeto['objetivos']).'</p>
        <h2>Justificativa do Projeto</h2>
        <p>'.tratatexto($consultaDadosProjeto['justificativa']).'</p>
        <h2>Acessibilidade</h2>
        <p>'.tratatexto($consultaDadosProjeto['acessibilidade']).'</p>
        <h2>Democratiza&ccedil;&atilde;o de Acesso</h2>
        <p>'.tratatexto($consultaDadosProjeto['democratizacaodeacesso']).'</p>
        <h2>Fases do Projeto</h2>
        <p>'.tratatexto($consultaDadosProjeto['etapadetrabalho']).'</p>
        <h2>Ficha T&eacute;cnica</h2>
        <p>'.tratatexto($consultaDadosProjeto['fichatecnica']).'</p>
        <h2>Sinopse da obra</h2>
        <p>'.tratatexto($consultaDadosProjeto['sinopse']).'</p>
        <h2>Impacto Ambiental</h2>
        <p>'.tratatexto($consultaDadosProjeto['impactoambiental']).'</p>
        <h2>Especifica&ccedil;&otilde;es t&eacute;cnicas do produto</h2>
        <p>'.tratatexto($consultaDadosProjeto['especificacaotecnica']).'</p>
        <h2>Plano b&aacute;sico de Divulga&ccedil;&atilde;o</h2>
        <p>
            <table cellpadding="3" style="width:100%">
                ';

                foreach ($divulgacoes as $divulgacao) {
                    $texto .= '<tr bgcolor="#EEEEEE"><td width="80%">'.$divulgacao['peca'].'</td>';
                    $texto .= '<td>'.$divulgacao['veiculo'].'</td></tr>';
                }
    $texto .= '
            </table>
        </p>
        <br><br>
      <h2>Plano de distribui&ccedil;&atilde;o de produtos culturais</h2>
      <p>
        <table cellpadding="3" style="width:100%">
        <tr>
            <td style="font-size:8pt" valin="bottom"><b>Nome do Evento/Produto</b></td>
            <td style="font-size:8pt"><b>Qtde.Divulga&ccedil;&atilde;o</b></td>
            <td style="font-size:8pt"><b>Qtde.Patrocinador</b></td>
            <td style="font-size:8pt"><b>Distribu&ccedil;&atilde;o Gratuita</b></td>
            <td style="font-size:8pt"><b>Total Venda</b></td>
            <td style="font-size:8pt"><b>Total Venda Promocional</b></td>
            <td style="font-size:8pt"><b>Qtde Total</b></td>
            <td style="font-size:8pt"><b>Preco Uni. Promocional</b></td>
            <td style="font-size:8pt"><b>Preco Uni. Normal</b></td>
            <td style="font-size:8pt"><b>Receita Prev. Normal</b></td>
            <td style="font-size:8pt"><b>Receita Prev. Promocional</b></td>
        </tr>
        ';

                    $Total                          = 0;
                    $QtdeOutrosTotal                = 0;
                    $QtdeVendaNormalTotal           = 0;
                    $QtdeVendaPromocionalTotal      = 0;
                    $QtdeProduzidaTotal             = 0;
                    $PrecoUnitarioPromocionalTotal  = 0;
                    $PrecoUnitarioNormalTotal       = 0;
                    $ReceitaNormalTotal             = 0;
                    $ReceitaProTotal                = 0;


                foreach ( $rsPlanoDistribuicao as $DadosDistribuicao )//busca dados de distribuicao do preprojeto
                {
                    $receitanormal   = 0;
                    $receitapro      = 0;
                    $receitaprevista = 0;


                    $DadosDistribuicao->precounitarionormal = str_replace('$','', $DadosDistribuicao->precounitarionormal);
                    $DadosDistribuicao->precounitariopromocional = str_replace('$','', $DadosDistribuicao->precounitariopromocional);

                    $receitanormal   = ($DadosDistribuicao->qtdevendanormal*$DadosDistribuicao->precounitarionormal);
                    $receitapro      = ($DadosDistribuicao->qtdevendapromocional*$DadosDistribuicao->precounitariopromocional);
                    $receitaprevista = $receitanormal + $receitapro;

                    $QtdeOutrosTotal                = $QtdeOutrosTotal + $DadosDistribuicao->qtdeoutros;
                    $QtdeVendaNormalTotal           = $QtdeVendaNormalTotal + $DadosDistribuicao->qtdevendanormal;
                    $QtdeVendaPromocionalTotal      = $QtdeVendaPromocionalTotal + $DadosDistribuicao->qtdevendapromocional;
                    $QtdeProduzidaTotal             = $QtdeProduzidaTotal + $DadosDistribuicao->qtdeproduzida;
                    $PrecoUnitarioPromocionalTotal  = $PrecoUnitarioPromocionalTotal + $DadosDistribuicao->precounitariopromocional;
                    $PrecoUnitarioNormalTotal       = $PrecoUnitarioNormalTotal + $DadosDistribuicao->precounitarionormal;
                    $ReceitaNormalTotal             = $ReceitaNormalTotal + $receitanormal;
                    $ReceitaProTotal                = $ReceitaProTotal + $receitapro;
                    $Total++;


                    $texto .= '<tr bgcolor="#EEEEEE">';
                    $texto .= '<td>'.$DadosDistribuicao->produto.'</td>';
                    $texto .= '<td>'.$DadosDistribuicao->qtdeproponente.'</td>';
                    $texto .= '<td>'.$DadosDistribuicao->qtdepatrocinador.'</td>';
                    $texto .= '<td>'.$DadosDistribuicao->qtdeoutros.'</td>';
                    $texto .= '<td>'.$DadosDistribuicao->qtdevendanormal.'</td>';
                    $texto .= '<td>'.$DadosDistribuicao->qtdevendapromocional.'</td>';
                    $texto .= '<td>'.$DadosDistribuicao->qtdeproduzida.'</td>';
                    $texto .= '<td>'.number_format($DadosDistribuicao->precounitariopromocional,"2",",",".") . '</td>';
                    $texto .= '<td>'.number_format($DadosDistribuicao->precounitarionormal,"2",",",".").'</td>';
                    $texto .= '<td>'.number_format($receitanormal,"2",",",".").'</td>';
                    $texto .= '<td>'.number_format($receitapro,"2",",",".").'</td>';
                    $texto .= '</tr>';
                }
    $texto .= '
        <tr>
            <th colspan="3" align="left">Total Geral ('.$Total.')</th>
            <th align="left">'.$QtdeOutrosTotal.'</th>
            <th align="left">'.$QtdeVendaNormalTotal.'</th>
            <th align="left">'.$QtdeVendaPromocionalTotal.'</th>
            <th align="left">'.$QtdeProduzidaTotal.'</th>
            <th align="left">'.number_format($PrecoUnitarioPromocionalTotal,"2",",",".").'</th>
            <th align="left">'.number_format($PrecoUnitarioNormalTotal,"2",",",".").'</th>
            <th align="left">'.number_format($ReceitaNormalTotal,"2",",",".").'</th>
            <th align="left">'.number_format($ReceitaProTotal,"2",",",".").'</th>
        </tr>
        </table>
      </p>';

        $texto .='<h2>Planilha Or&ccedil;ament&aacute;ria</h2>';
        $etapa = -1;
        $Produto = -1;
        $TotalProduto = 0;
        $TotalEtapa = 0;
        $TotalOrcamento = 0;

        foreach ($tbPlanilhaProposta->Orcamento($id_projeto) as $Orcamento)//busca dados de or�amento do preprojeto
        {

            if (($Produto != $Orcamento->idProduto or $etapa != $Orcamento->idEtapa) and $Produto != -1){

                $texto .= '<tr>
                                <th colspan="9" align="left" style="font-size:9pt;">Total Produto: '.number_format($TotalProduto,2,',','.').'</th>
                           </tr>';
                $TotalEtapa = $TotalEtapa + $TotalProduto;
                $TotalProduto = 0;

            }

            if ($etapa != $Orcamento->idEtapa and $etapa != -1){

                $texto .= '<tr>
                                <th colspan="9" align="left" style="font-size:11pt;">Total Etapa: '.number_format($TotalEtapa,2,',','.').'</th>
                           </tr>
                           '; //</table>
                $TotalOrcamento = $TotalOrcamento + $TotalEtapa;
                $TotalEtapa = 0;
            }


             if ($etapa != $Orcamento->idEtapa or $Produto != $Orcamento->idProduto)
            {

                $texto .= '
                <table border=0 cellpading="5" width="100%" style="font-size:6pt; margin-top:20px">
                <tr>
                    <th colspan="11" align="left" style="font-size:12pt;">Etapa: '.$Orcamento->Etapa.'</th>
                </tr>
                <tr>
                   <th colspan="11" align="left" style="font-size:12pt;">Produto: '.$Orcamento->ProdutoF.' </th>
                </tr>
                <tr>
                    <td bgcolor="#EEEEEE" align="center"><b>Item</b></td>
                    <td bgcolor="#EEEEEE" align="center"><b>Unid.</b></td>
                    <td bgcolor="#EEEEEE" align="center"><b>Quant.</b></td>
                    <td bgcolor="#EEEEEE" align="center"><b>Ocorr</b></td>
                    <td bgcolor="#EEEEEE" align="center"><b>Valor Unid.</b></td>
                    <td bgcolor="#EEEEEE" align="center"><b>Total</b></td>
                    <td bgcolor="#EEEEEE" align="center"><b>Dias</b></td>
                    <td bgcolor="#EEEEEE" align="center"><b>Fonte de Recurso</b></td>
                    <td bgcolor="#EEEEEE" align="center"><b>UF</b></td>
                    <td bgcolor="#EEEEEE" align="center"><b>Munic&iacute;pio</b></td>
                    <td bgcolor="#EEEEEE" align="center"><b>Justificativa</b></td>
                </tr>';
                };
                $TotalProduto = $TotalProduto + (($Orcamento->Quantidade)*($Orcamento->Ocorrencia)*($Orcamento->ValorUnitario));
                $etapa = $Orcamento->idEtapa;
                $Produto = $Orcamento->idProduto;
                 $texto .= '<tr>';
                    $texto .= '<td>'.$Orcamento->Item.'</td>';
                    $texto .= '<td>'.$Orcamento->UnidadeF.'</td>';
                    $texto .= '<td>'.(int)$Orcamento->Quantidade.'</td>';
                    $texto .= '<td>'.(int)$Orcamento->Ocorrencia.'</td>';
                    $texto .= '<td>'.number_format($Orcamento->ValorUnitario,2,',','.').'</td>';
                    $texto .= '<td>'.number_format(($Orcamento->Quantidade)*($Orcamento->Ocorrencia)*($Orcamento->ValorUnitario),2,',','.').'</td>';
                    $texto .= '<td>'.$Orcamento->QtdeDias.'</td>';
                    $texto .= '<td>'.$Orcamento->FonteRecursoF.'</td>';
                    $texto .= '<td>'.$Orcamento->UfDespesaF.'</td>';
                    $texto .= '<td>'.$Orcamento->MunicipioDespesaF.'</td>';
                    $texto .= '<td>'.$Orcamento->dsJustificativa.'</td>';
                $texto .= '</tr>
                           <tr>
                                <td bgcolor="#EEEEEE"  colspan="11" style="height:1px"></td>
                           </tr>
                ';
         }

         $TotalOrcamento = $TotalOrcamento + $TotalProduto;

         $texto .= '

                       <table border=0 cellpading="5" width="100%" style="font-size:6pt;">
                           <tr>
                                <th colspan="9" align="left" style="font-size:9pt;">Total Produto: '.number_format($TotalProduto,2,',','.').'</th>
                           </tr>
                           <tr>
                                <th colspan="9" align="left" style="font-size:11pt;">Total Etapa: '.number_format($TotalEtapa + $TotalProduto,2,',','.').'</th>
                           </tr>
                           <tr>
                                <th colspan="9" align="left" bgcolor="#EEEEEE" style="font-size:11pt; padding-top:10px;">Or&ccedil;amento Total: '.number_format($TotalOrcamento,2,',','.').'</th>
                           </tr>
                        </table>';


   //FIM DOS DADOS POR ENCENTIVO FISCAL
   }//Fim do IF para, este if exibe o conteudo apenas quando a proposta � por encentivo fiscal
        $texto .= '</div>
        </body>
        </html>';

    end:
        $pdf = new PDF($texto, 'pdf');
        $pdf->gerarRelatorio();
        $this->_helper->viewRenderer->setNoRender(TRUE);
    }
}
