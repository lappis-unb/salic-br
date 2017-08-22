<?php
/**
 * Controller DBF
 * @author emanuel.sampaio - Politec
 * @since 29/07/2011
 * @version 1.0
 * @package application
 * @subpackage application.controller
 * @link http://www.cultura.gov.br
 * @copyright © 2011 - Minist&eacute;rio da Cultura - Todos os direitos reservados.
 */

class DbfController extends MinC_Controller_Action_Abstract
{
	/**
	 * @access private
	 * @var integer (idAgente do usu&aacute;rio logado)
	 */
	private $getIdUsuario;



	/**
	 * @access private
	 * @var object (tabelas utilizadas)
	 */
	private $Dbf;
	private $sInformacaoReceitaFederalV3;



	/**
	 * @access private
	 * @var string (diretório onde se enconta o arquivo .txt)
	 */
	private $arquivoTXT = 'DBF';



	/**
	 * Reescreve o m&eacute;todo init()
	 * @access public
	 * @param void
	 * @return void
	 */
	public function init()
	{
		$this->view->title = 'Salic - Sistema de Apoio &agrave;s Leis de Incentivo &agrave; Cultura'; // t&iacute;tulo da p&aacute;gina

		/* ========== INÍCIO PERFIL ========== */
		// define os grupos que tem acesso
		$PermissoesGrupo = array();
		//$PermissoesGrupo[] = 121; // T&eacute;cnico de Acompanhamento
		$PermissoesGrupo[] = 122; // Coordenador de Acompanhamento
		//$PermissoesGrupo[] = 123; // Coordenador - Geral de Acompanhamento
		//$PermissoesGrupo[] = 129; // T&eacute;cnico de Acompanhamento
		//$PermissoesGrupo[] = ; // Coordenador de Avalia&ccedil;&atilde;o
		//$PermissoesGrupo[] = 134; // Coordenador de Fiscaliza&ccedil;&atilde;o
		//$PermissoesGrupo[] = 124; // T&eacute;cnico de Presta&ccedil;&atilde;o de Contas
		//$PermissoesGrupo[] = 125; // Coordenador de Presta&ccedil;&atilde;o de Contas
		//$PermissoesGrupo[] = 126; // Coordenador - Geral de Presta&ccedil;&atilde;o de Contas
		parent::perfil(1, $PermissoesGrupo); // perfil novo salic

		// pega o idAgente do usu&aacute;rio logado
		$auth = Zend_Auth::getInstance(); // pega a autentica&ccedil;&atilde;o
		if (isset($auth->getIdentity()->usu_codigo)) // autenticacao novo salic
		{
			$this->getIdUsuario = UsuarioDAO::getIdUsuario($auth->getIdentity()->usu_codigo);
			$this->getIdUsuario = ($this->getIdUsuario) ? $this->getIdUsuario['idAgente'] : 0;
		}
		else
		{
			$this->getIdUsuario = 0;
		}
		/* ========== FIM PERFIL ========== */

		parent::init();
	} // fecha m&eacute;todo init()



	/**
	 * Redireciona para o fluxo inicial
	 * @access public
	 * @param void
	 * @return void
	 */
	public function indexAction()
	{
		$this->_forward('form'); // redireciona para o formul&aacute;rio
	} // fecha m&eacute;todo indexAction()



	/**
	 * Formul&aacute;rio para gerar o DBF
	 * @access public
	 * @param void
	 * @return void
	 */
	public function formAction()
	{
	} // fecha m&eacute;todo formAction()



	/**
	 * Gera o DBF
	 * @access public
	 * @param void
	 * @return void
	 */
	public function gerarDbfAction()
	{
		// caso o formul&aacute;rio seja enviado via post
		if ($this->getRequest()->isPost())
		{
			// configura&ccedil;&atilde;o o php.ini para 100MB
			@set_time_limit(0);
			@ini_set('mssql.textsize',      10485760000);
			@ini_set('mssql.textlimit',     10485760000);
			@ini_set('mssql.timeout',       10485760000);
			@ini_set('upload_max_filesize', '100M');

			// recebe os dados via post
			$post    = Zend_Registry::get('post');
			$ano     = (int) $post->ano;
			$cnpj    = $post->cnpj;
			$receber = (isset($post->receber) && $post->receber == 'S') ? 'S' : 'N';
			$email   = strtolower($post->email);

			// objeto para valida&ccedil;&atilde;o de e-mail
			$ValidarEmail = new Zend_Validate_EmailAddress();

			try
			{
				if (empty($ano))
				{
					throw new Exception('Por favor, informe um ano v&aacute;lido!');
				}
				else if ($ano > date('Y'))
				{
					throw new Exception('O ano informado n&atilde;o pode ser maior que o ano atual!');
				}
				else if ($receber == 'S' && (empty($email) || !$ValidarEmail->isValid($email)))
				{
					throw new Exception('Por favor, informe um e-mail v&aacute;lido!');
				}
				else
				{
					// executa a sp
					$this->sInformacaoReceitaFederalV3 = new sInformacaoReceitaFederalV3();
					$this->sInformacaoReceitaFederalV3->gerarDBF($ano);

					// busca as infoma&ccedil;&otilde;es que ser&atilde;o armazenadas no arquivo
					$this->Dbf = new Dbf();
					$buscar = $this->Dbf->buscarInformacoes();

					if (count($buscar) > 0)
					{
						// caminho do arquivo txt
						$so               = stripos($_SERVER['SERVER_SOFTWARE'], 'win32') != FALSE ? 'WINDOWS' : 'LINUX';            // sistema operacional
						$bar              = $so == 'WINDOWS' ? '\\' : '/';                                                           // configura a barra de acordo com o SO
						$protocolo        = (strpos(strtolower($_SERVER['SERVER_PROTOCOL']), 'https') === false) ? 'http' : 'https'; // configura o protocolo
						$host             = $_SERVER['HTTP_HOST'];                                                                   // configura o host
						$arq              = 'DBF_' . date('YmdHis', strtotime('now')) . '.txt';                                      // nome do arquivo
						$url              = $protocolo . '://' . $host . Zend_Controller_Front::getInstance()->getBaseUrl();         // url externa do arquivo
						$url             .= '/public/txt/DBF/' . $arq;
						$folder           = getcwd() . $bar . 'public' . $bar . 'txt' . $bar . $this->arquivoTXT;
						$this->arquivoTXT = $folder . $bar . $arq;      // diretório interno do arquivo

						if (!file_exists($folder)) {
							mkdir($folder, 0755, true);
						}
						// tenta abrir/criar o arquivo txt
						if (!$arquivo = fopen($this->arquivoTXT, 'x+'))
						{
							throw new Exception('Erro ao tentar abrir o arquivo <strong>' . $url . '</strong>');
						}
						else
						{
							for ($i = 0; $i < count($buscar); $i++)
							{
								// monta linha contendo o registro
								$conteudo = trim($buscar[$i]->Informacao) . "\r\n";

								// escreve no arquivo
								if (!fwrite($arquivo, $conteudo))
								{
									throw new Exception('Erro ao tentar escrever no arquivo <strong>' . $url . '</strong>');
								}
							} // fecha for

							// fecha o arquivo
							fclose($arquivo);

							if ($receber == 'S')
							{
								// envia uma cópia do arquivo para o e-mail informado no formul&aacute;rio
								$msg = 'Segue cópia do arquivo para gera&ccedil;&atilde;o da DBF. ';
								$msg.= 'O arquivo original foi salvo em: ';
								$msg.= '<strong><a href="' . $url . '" title="Abrir arquivo" target="_blank">' . $url . '</a></strong>';

								EmailDAO::enviarEmail($email, 'arquivo DBF', $msg);

								// a linha abaixo serve apenas para visualizar como era feito o envio do e-mail no scriptcase:
								//sc_mail_send('correio.cultura.gov.br', '', '', 'cadastro@cultura.gov.br', $email, 'arquivo DBF', $msg, 'H', '', 'H', '25', '', $this->arquivoTXT);
							}

							parent::message('Arquivo <strong>' . $url . '</strong> gravado com sucesso!', 'dbf', 'CONFIRM');
						} // fecha else

					} // fecha if

				} // fecha else
			} // fecha try
			catch (Exception $e)
			{
				$this->view->message      = $e->getMessage();
				$this->view->message_type = 'ERROR';
			}

		} // fecha if post
		else
		{
			parent::message('Por favor, preencha o formul&aacute;rio para gerar o DBF corretamente!', 'dbf/form', 'ALERT');
		}

	} // fecha m&eacute;todo gerarDbfAction()

} // fecha class