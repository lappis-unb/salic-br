<?php

class EmailDAO extends Zend_Db_Table
{
    public static function enviarEmail($email, $assunto, $texto, $perfil = 'PerfilGrupoPRONAC')
    {

        $config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', APPLICATION_ENV);
        if ($config->mail->activated == true) {
            $transport = $config->mail->transport->toArray();

            $transport = new Zend_Mail_Transport_Smtp($transport['host'], $transport);
            $mail = new Zend_Mail();

            $mail->setBodyHtml($texto);
            $mail->setFrom($config->mail->default->from_email, $config->mail->default->from_name);
            $mail->addTo($email);
            $mail->setSubject($assunto);
            return $mail->send($transport);
        }
        return true;
    }

    public static function buscarEmailsFiscalizacao($idPronac, $idFiscalizacao)
    {
        $sql = "SELECT i.Descricao AS email
                        FROM sac.Projetos p
                        INNER JOIN sac.PreProjeto pr           ON (p.idProjeto = pr.idPreProjeto)
                        INNER JOIN sac.tbFiscalizacao f        ON (f.IdPRONAC = p''.IdPRONAC)
                        INNER JOIN agentes.Internet i          ON (i.idAgente = pr.idAgente )
                        WHERE (p.IdPRONAC = $idPronac) AND (f.idFiscalizacao = $idFiscalizacao)
                        UNION ALL
                        SELECT t.Descricao AS email
                        FROM sac.Projetos p
                        INNER JOIN sac.tbFiscalizacao f        ON (f.IdPRONAC = p.IdPRONAC)
                        INNER JOIN agentes.Internet t   ON (t.idAgente = f.idAgente)
                        WHERE (p.IdPRONAC = $idPronac) AND (f.idFiscalizacao = $idFiscalizacao)";

        return $sql;
    }
}
