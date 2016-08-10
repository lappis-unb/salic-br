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
        //$sql = "EXEC msdb.dbo.sp_send_dbmail
                    //@profile_name          = 'PerfilGrupoPRONAC'
                    //,@recipients           = '" . htmlspecialchars($email) . "'
                    //,@body                 = '" . $texto . "'
                    //,@body_format          = 'HTML'
                    //,@subject              = '" . $assunto . "'
                    //,@exclude_query_output = 1;";

        //$db = Zend_Registry::get('db');
        //$db->setFetchMode(Zend_DB::FETCH_OBJ);
        //return $db->query($sql);

        $mail = new Zend_Mail();
        $mail->setBodyText($texto);
        $mail->setFrom('somebody@example.com', 'Some Sender');
        $mail->addTo($email, 'Some Recipient');
        $mail->setSubject($assunto);
        return $mail->send();
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
