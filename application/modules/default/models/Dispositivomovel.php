<?php

/**
 * Abstra&ccedil;&atilde;o da tabela sac.DBo.tbAplicativoSalic
 *
 * @author rafael.gloria@cultura.gov.br
 */
class Dispositivomovel extends MinC_Db_Table_Abstract
{

    protected $_name = 'tbDispositivoMovel';
    protected $_schema = 'SAC';
    protected $_primary = 'idDispositivoMovel';
    
    public function init()
    {
        parent::init();
    }
    
    /**
     * Salva o dispositivo que est&aacute; conectado, salva o CPF do usu&aacute;rio e atualiza a �ltima data de acesso.
     *
     * @param string $registrationId
     * @param string $cpf Optional
     * @return array $dispositivo Dados do dispositivo
     */
    public function salvar($registrationId, $cpf = NULL){
        $dispositivo = array();

        if(!empty($registrationId)){
            $dispositivoRow = $this->fetchRow("idRegistration = '{$registrationId}'");
            if(!$dispositivoRow){
                $dispositivoRow = $this->createRow(array(
                    'idRegistration' => $registrationId
                ));
            }
            if($cpf){
                $dispositivoRow->nrCPF = $cpf;
            }
            $objAcesso = new Acesso();
            $dispositivoRow->dtAcesso = $objAcesso->getExpressionDate();
            $dispositivoRow->save();
            $dispositivo = $dispositivoRow->toArray();
        }

        return $dispositivo;
    }

    /**
     * Lista todos os dispositivos por idPronac.
     *
     * @param integer $idPronac
     * @return array
     */
    public function listarPorIdPronac($idPronac){
        if($idPronac){
            $consulta = $this->select();
            $consulta->setIntegrityCheck(false);
            $consulta
                ->from(array('projetos' => 'vwAgentesSeusProjetos'), array(), 'SAC')
                ->join(array('usuario' => 'SGCacesso'), 'projetos.IdUsuario = usuario.IdUsuario', array(
                    'cpf' => 'Cpf'), 'ControleDeAcesso')
                ->join(array('dispositivo' => 'tbDispositivoMovel'), 'usuario.Cpf = dispositivo.nrCPF', array(
                    'idDispositivoMovel',
                    'idRegistration'), 'SAC')
                ->where('projetos.IdPRONAC = ?', $idPronac)
                ->group(array(
                    'cpf',
                    'idDispositivoMovel',
                    'idRegistration'))
            ;
            $listaResultado = $this->fetchAll($consulta);
        } else {
            $listaResultado = array();
        }
        return $listaResultado;
    }

    /**
     * Lista dispositivos que receber&atilde;o a notifica&ccedil;&atilde;o.
     *
     * @param array $listaResultadoDispositivo
     * @return array
     */
    public function listarIdRegistration($listaResultadoDispositivo) {
        $listaDispositivos = array();
        foreach ($listaResultadoDispositivo as $dispositivo) {
            $listaDispositivos[] = $dispositivo->idRegistration;
        }

        return $listaDispositivos;
    }

    /**
     * Lista de id dos dispositivos que receber&atilde;o a notifica&ccedil;&atilde;o.
     *
     * @param array $listaResultadoDispositivo
     * @return array
     */
    public function listarIdDispositivoMovel($listaResultadoDispositivo) {
        $listaDispositivos = array();
        foreach ($listaResultadoDispositivo as $dispositivo) {
            $listaDispositivos[] = $dispositivo->idDispositivoMovel;
        }

        return $listaDispositivos;
    }

}
