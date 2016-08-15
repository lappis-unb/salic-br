<?php
/**
 * DAO Email
 * @author Equipe RUP - Politec
 * @since 01/12/2010
 * @version 1.0
 * @package application
 * @subpackage application.model.DAO
 * @link http://www.cultura.gov.br
 */

class EmailDAO extends Zend_Db_Table
{
    /**
     * Método para envio de e-mail
     * @access public
     * @static
     * @param string $email
     * @param string $texto
     * @return object
     */
    public static function enviarEmail($email, $assunto, $texto, $perfil = 'PerfilGrupoPRONAC')
    {
        $config = new Zend_Config_Ini(APPLICATION_PATH .'/configs/config.ini');
        $config = $config->default->mail->transport->toArray();

        $transport = new Zend_Mail_Transport_Smtp($config['host'], $config);
        $mail = new Zend_Mail();

        $mail->setBodyHtml($texto);
        $mail->setFrom('srv_salic@cultura.gov.br', 'Salic BR');
        $mail->addTo($email);
        $mail->addTo('wouerner.costa@cultura.gov.br');
        $mail->setSubject($assunto);
        return $mail->send($transport);
    }

     /**
	 * Método para buscar e-mails
     * Módulo Fiscalizar Projetos - Comunicar Proponente da Fiscalização
	 * @access public
	 * @static
	 * @param string $email
	 * @param string $texto
	 * @return object
	 */
	public static function buscarEmailsFiscalizacao($idPronac, $idFiscalizacao)
	{
		$sql = "SELECT i.Descricao AS email
                        FROM sac.dbo.Projetos p
                        INNER JOIN sac.dbo.PreProjeto pr           ON (p.idProjeto = pr.idPreProjeto)
                        INNER JOIN sac.dbo.tbFiscalizacao f        ON (f.IdPRONAC = p.IdPRONAC)
                        INNER JOIN Agentes.dbo.Internet i          ON (i.idAgente = pr.idAgente )
                        WHERE (p.IdPRONAC = $idPronac) AND (f.idFiscalizacao = $idFiscalizacao)
                        UNION ALL
                        SELECT t.Descricao AS email
                        FROM sac.dbo.Projetos p
                        INNER JOIN sac.dbo.tbFiscalizacao f        ON (f.IdPRONAC = p.IdPRONAC)
                        INNER JOIN Agentes.dbo.Internet t   ON (t.idAgente = f.idAgente)
                        WHERE (p.IdPRONAC = $idPronac) AND (f.idFiscalizacao = $idFiscalizacao)";

		return $sql;
	}
}
